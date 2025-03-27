<?php

namespace App\Livewire;

use App\Models\Portfolio;
use App\Models\ProjectType;
use App\Models\ServiceCategory;
use App\Models\PortfolioImage;
use App\Services\PortfolioImageService; // Importar el servicio de imágenes
use App\Traits\CacheTrait;             // Importar Trait de Caché
use App\Traits\ChecksPermissions;      // Importar Trait de Permisos
use Illuminate\Database\Eloquent\ModelNotFoundException; // Added for specific exception handling
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile; // Necesario para el manejo de archivos acumulados

class Portfolios extends Component
{
    // --- Traits Requeridos ---
    use WithPagination;
    use WithFileUploads;
    use CacheTrait;         // Para manejo de caché
    use ChecksPermissions;  // Para manejo de permisos

    // --- Propiedad requerida por CacheTrait (Fix) ---
    public bool $significantDataChange = false; // Añadido para satisfacer CacheTrait

    // --- Propiedades del Formulario ---
    public string $title = '';           // Mapeado a ProjectType.title
    public string $description = '';     // Mapeado a ProjectType.description
    public ?int $service_category_id = null; // ID de ServiceCategory seleccionada

    // --- Manejo de Archivos ---
    public $image_files = [];
    public $pendingNewImages = [];

    // --- Estado del Componente ---
    public bool $showModal = false;
    public bool $isEditing = false;
    public ?Portfolio $editingPortfolio = null;
    public Collection $existing_images;
    public array $images_to_delete = [];

    // --- Configuración/Control Adicional ---
    public string $search = '';
    public int $perPage = 10;
    public string $sortField = 'created_at';
    public string $sortDirection = 'desc';
    public bool $showDeleted = false;

    // --- Datos para Selects ---
    public $serviceCategories;

    // --- Constantes para Límites de Imágenes ---
    public const MAX_FILES = 10;
    public const MAX_SIZE_KB = 5120;
    public const MAX_TOTAL_SIZE_KB = 20480;

    // --- Servicio Inyectado ---
    protected PortfolioImageService $portfolioImageService;

    // --- Listeners para eventos ---
    // Includes modal listeners and uses delete/restore methods
    protected $listeners = [
        'delete' => 'delete',           // Maps 'delete' event to delete method (now expects UUID)
        'restore' => 'restore',         // Maps 'restore' event to restore method (now expects UUID)
        'closeModal' => 'closeModal',     // Standard listener to close the modal
        'refreshComponent' => '$refresh', // Standard listener to refresh the component
        // You might have other listeners e.g., from confirmation dialogs
        'confirmedDeletePortfolio' => 'delete',
        'confirmedRestorePortfolio' => 'restore',
    ];

    // --- Mapeo del Estado a Query String de URL ---
    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 10],
        'showDeleted' => ['except' => false],
    ];

    // --- Inyectar Servicio ---
    public function boot(PortfolioImageService $service): void
    {
        $this->portfolioImageService = $service;
    }

    // --- Reglas de Validación (para el guardado final) ---
    protected function rules(): array
    {
        // Determine if at least one image is required
        $isImageRequired = false;
        if (!$this->isEditing) {
            $isImageRequired = true; // Creating: always need at least one new image
        } elseif ($this->isEditing && $this->existing_images instanceof Collection) {
            $hasVisibleExisting = $this->existing_images->whereNotIn('id', $this->images_to_delete)->isNotEmpty();
            $hasPendingNew = !empty($this->pendingNewImages);
            // Editing: require image if no visible existing AND no pending new images
            $isImageRequired = !$hasVisibleExisting && !$hasPendingNew;
        }

        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'service_category_id' => 'required|exists:service_categories,id',

            // Validate the accumulated array `$pendingNewImages` for the final save
            'pendingNewImages' => [
                 $isImageRequired ? 'required' : 'nullable', // Required if no other images remain/are added
                 'array',
                 // MAX total count validation happens in `validateTotals`
            ],
            // Validation for each file in the accumulated `$pendingNewImages` array
            'pendingNewImages.*' => [
                 'required',
                 'image',
                 'mimes:jpeg,png,jpg,gif,webp',
                 'max:' . self::MAX_SIZE_KB // Limit per individual file
            ],

             // Validation 'sometimes' for the input $image_files for immediate feedback on selection
             'image_files.*' => [
                 'sometimes',
                 'image',
                 'mimes:jpeg,png,jpg,gif,webp',
                 'max:' . self::MAX_SIZE_KB
             ],
        ];
    }

    // --- Mensajes de Validación Personalizados ---
    protected function messages(): array
    {
        return [
            'title.required' => 'The project title is required.',
            'description.required' => 'The project description is required.',
            'service_category_id.required' => 'Please select a service category.',
            'pendingNewImages.required' => 'At least one image is required for the portfolio.',
            'pendingNewImages.array' => 'Image processing failed. Please review pending images.',
            'pendingNewImages.*.image' => 'One or more pending files are not valid images.',
            'pendingNewImages.*.mimes' => 'Only JPEG, PNG, JPG, GIF, WEBP images are allowed.',
            'pendingNewImages.*.max' => 'Each image must not be larger than ' . (self::MAX_SIZE_KB / 1024) . 'MB.',
            'image_files.*.image' => 'The selected file is not a valid image.',
            'image_files.*.mimes' => 'Only JPEG, PNG, JPG, GIF, WEBP are allowed.',
            'image_files.*.max' => 'The selected image must not be larger than ' . (self::MAX_SIZE_KB / 1024) . 'MB.',
        ];
    }

    // --- Método Mount (Inicialización) ---
    public function mount(): void
    {
        $this->serviceCategories = ServiceCategory::orderBy('category')->get();
        $this->existing_images = collect();
        $this->resetPage();
    }

    // --- Hooks (updated*, updating) ---
    public function updatedImageFiles(): void
    {
        $this->resetErrorBag(['image_files', 'image_files.*']);
        $newlySelectedFiles = $this->image_files;

        try {
            $this->validateOnly('image_files.*');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->image_files = [];
            Log::warning('Validation failed for newly selected files.', ['errors' => $e->errors()]);
            return;
        }

        $currentTempNames = collect($this->pendingNewImages)->map(fn($file) => $file instanceof TemporaryUploadedFile ? $file->getFilename() : null)->filter()->toArray();
        $added = false;
        foreach ($newlySelectedFiles as $file) {
            if ($file instanceof TemporaryUploadedFile && $file->isValid() && !in_array($file->getFilename(), $currentTempNames)) {
                 $this->pendingNewImages[] = $file;
                 $currentTempNames[] = $file->getFilename();
                 $added = true;
            }
        }
        $this->image_files = [];

        if ($added) {
            $this->validateTotals();
        }
    }

    public function updating($name, $value): void
    {
        if (in_array($name, ['search', 'perPage', 'showDeleted'])) {
            $this->resetPage();
        }
    }

    // --- Render Method ---
    public function render()
    {
        if (!$this->checkPermission('READ_PORTFOLIO', true)) {
            return view('livewire.forbidden');
        }

        $cacheKey = $this->generateCacheKey('portfolios');

        // CORRECTED: Added 'use ($cacheKey)' to the closure
        $portfolios = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($cacheKey) {
            Log::info("Cache miss or expired for key: {$cacheKey}. Fetching portfolios from DB.");
            return $this->getPortfolioQuery()->paginate($this->perPage);
        });

        return view('livewire.portfolios', [
            'portfolios' => $portfolios,
            'serviceCategoriesList' => $this->serviceCategories,
        ]);
    }

    // --- Query Builder ---
    protected function getPortfolioQuery()
    {
         $query = Portfolio::query()
            ->with([
                'projectType.serviceCategory',
                'images' => fn ($q) => $q->orderBy('order', 'asc')->limit(1)
            ]);

        if ($this->showDeleted) {
            $query->withTrashed();
        }

        if (!empty(trim($this->search))) {
            $searchTerm = '%' . trim($this->search) . '%';
            $query->whereHas('projectType', function($q) use ($searchTerm) {
                 $q->where('title', 'like', $searchTerm)
                   ->orWhere('description', 'like', $searchTerm);
            });
        }

        $sortableFields = ['created_at'];
        $sortField = in_array($this->sortField, $sortableFields) ? $this->sortField : 'created_at';
        $sortDirection = $this->sortDirection === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortField, $sortDirection);

        return $query;
    }

    // --- Modal Control and CRUD Methods ---

    public function create(): void
    {
        if (!$this->checkPermissionWithMessage('CREATE_PORTFOLIO', 'You do not have permission to create portfolio items.')) {
            return;
        }
        $this->resetFields();
        $this->isEditing = false;
        $this->showModal = true;
        // Optional: Dispatch event for Alpine/JS if needed
        $this->dispatch('portfolio-modal-opened', mode: 'create');
    }

    // Edit can still use Route Model Binding if the route is set up with {portfolio:uuid}
    // Or you can change it to accept a UUID like delete/restore if triggered differently
    public function edit(string $uuid): void
{
    // Add permission check first
    if (!$this->checkPermissionWithMessage('UPDATE_PORTFOLIO', 'You do not have permission to edit portfolio items.')) {
       return;
    }

    try {
        // Find the portfolio by UUID
        $portfolio = Portfolio::where('uuid', $uuid)->firstOrFail(); // Or use first() and check if null

        $this->resetErrorBag();

        // Load relations
        $portfolio->loadMissing(['projectType.serviceCategory', 'images' => fn($q) => $q->orderBy('order', 'asc')]);

        if (!$portfolio->projectType) {
             session()->flash('error', 'Cannot edit: Associated project data is missing.');
             Log::error("Attempted to edit Portfolio UUID {$portfolio->uuid} but its ProjectType relation is missing.");
             // Don't call closeModal() here, let the user see the error maybe? Or just return.
             return;
        }

        // Set component state
        $this->isEditing = true;
        $this->editingPortfolio = $portfolio; // Store the found model
        $this->title = $portfolio->projectType->title;
        $this->description = $portfolio->projectType->description;
        $this->service_category_id = $portfolio->projectType->service_category_id;
        $this->existing_images = $portfolio->images;
        $this->image_files = [];
        $this->pendingNewImages = [];
        $this->images_to_delete = [];

        $this->showModal = true; // This should now be reached

        $this->dispatch('portfolio-modal-opened', mode: 'edit', portfolioUuid: $portfolio->uuid);

    } catch (ModelNotFoundException $e) {
        session()->flash('error', 'Portfolio not found.');
        Log::error("Portfolio not found for editing with UUID: {$uuid}");
    } catch (\Exception $e) {
        session()->flash('error', 'An error occurred while trying to load portfolio data.');
        Log::error("Error loading portfolio for edit (UUID: {$uuid}): " . $e->getMessage());
    }
}

    public function save(): void
    {
        $permission = $this->isEditing ? 'UPDATE_PORTFOLIO' : 'CREATE_PORTFOLIO';
        $action = $this->isEditing ? 'update' : 'create';
        if (!$this->checkPermissionWithMessage($permission, "You do not have permission to {$action} portfolio items.")) {
            return;
        }

        // 1. Validate main fields and pending images array structure/individual files
        $validatedData = $this->validate();

        // 2. Final validation of total limits (count, size) and requirement
        $this->validateTotals();
        if ($this->getErrorBag()->isNotEmpty()) {
             Log::warning("Portfolio save stopped due to total image validation errors.", $this->getErrorBag()->toArray());
             session()->flash('error', 'Please correct the image errors before saving.'); // More specific flash
             // Important: Dispatch event to potentially stop loading indicators in the frontend
             $this->dispatch('portfolio-save-failed');
             return;
        }

        DB::beginTransaction();
        try {
            $portfolio = null;
            $filesToUpload = $this->pendingNewImages; // Use the accumulated list

            if ($this->isEditing) {
                // --- UPDATE LOGIC ---
                if (!$this->editingPortfolio || !$this->editingPortfolio->projectType) {
                    throw new \Exception("Editing target portfolio (UUID: {$this->editingPortfolio?->uuid}) or its project type not found during save.");
                }
                $portfolio = $this->editingPortfolio;

                // Update ProjectType
                $portfolio->projectType->update([
                    'title' => $validatedData['title'],
                    'description' => $validatedData['description'],
                    'service_category_id' => $validatedData['service_category_id'],
                    'user_id' => Auth::id(), // Keep track of who last edited
                ]);
                $portfolio->touch(); // Update portfolio's updated_at

                // Delete Marked Images
                if (!empty($this->images_to_delete)) {
                    $imagesToDelete = PortfolioImage::whereIn('id', $this->images_to_delete)
                                        ->where('portfolio_id', $portfolio->id)->get();
                    if ($imagesToDelete->isNotEmpty()) {
                        foreach ($imagesToDelete as $img) {
                            $this->portfolioImageService->deleteImage($img->path);
                            $img->delete();
                        }
                        $portfolio->load('images'); // Reload relation
                        $this->existing_images = $portfolio->images->sortBy('order')->values();
                        Log::info("Deleted marked images for portfolio UUID {$portfolio->uuid}", ['deleted_ids' => $imagesToDelete->pluck('id')->all()]);
                    }
                    $this->images_to_delete = []; // Clear after processing
                }

                // Save New Images
                $nextOrderIndex = $this->existing_images->max('order') !== null ? $this->existing_images->max('order') + 1 : 0;
                if (!empty($filesToUpload)) {
                    foreach ($filesToUpload as $file) {
                        if ($file instanceof TemporaryUploadedFile && $file->isValid()) {
                            $storedPath = $this->portfolioImageService->storeImage($file);
                            if ($storedPath) {
                                $portfolio->images()->create([
                                    'path' => $storedPath,
                                    'order' => $nextOrderIndex++
                                ]);
                            } else {
                                 throw new \Exception("Failed to store new image during update for portfolio UUID {$portfolio->uuid}.");
                            }
                        }
                    }
                    $portfolio->load('images'); // Reload to include new ones
                    $this->existing_images = $portfolio->images->sortBy('order')->values();
                    Log::info("Added new images for portfolio UUID {$portfolio->uuid}", ['count' => count($filesToUpload)]);
                }

                // Reorder remaining images
                $this->reorderImages($portfolio);

                session()->flash('message', 'Portfolio updated successfully.');

            } else {
                // --- CREATE LOGIC ---
                // Create ProjectType
                $projectType = ProjectType::create([
                    'title' => $validatedData['title'],
                    'description' => $validatedData['description'],
                    'service_category_id' => $validatedData['service_category_id'],
                    'status' => 'active',
                    'user_id' => Auth::id(),
                ]);

                // Create Portfolio (assuming Portfolio model uses UUID trait or similar)
                $portfolio = Portfolio::create([
                    'project_type_id' => $projectType->id,
                    // 'uuid' => Str::uuid(), // Only if not automatically generated by model/trait
                    ]);
                Log::info("Created new Portfolio with UUID: {$portfolio->uuid}");

                // Save Images (must have some per validation rules)
                if (!empty($filesToUpload)) {
                    foreach ($filesToUpload as $index => $file) {
                        if ($file instanceof TemporaryUploadedFile && $file->isValid()) {
                            $storedPath = $this->portfolioImageService->storeImage($file);
                            if ($storedPath) {
                                $portfolio->images()->create([
                                    'path' => $storedPath,
                                    'order' => $index
                                ]);
                            } else {
                                throw new \Exception("Failed to store image during creation for portfolio UUID {$portfolio->uuid}.");
                            }
                        }
                    }
                     Log::info("Added images for new portfolio UUID {$portfolio->uuid}", ['count' => count($filesToUpload)]);
                } else {
                    // Should not happen due to validation, but good safeguard
                    throw new \Exception("No images provided during portfolio creation despite validation rules.");
                }
                session()->flash('message', 'Portfolio created successfully.');
            }

            DB::commit();
            Log::info("Portfolio save transaction committed for UUID: {$portfolio->uuid}");

            $this->clearCache('portfolios'); // Clear cache
            $this->closeModal(); // Close modal and reset fields
            // Dispatch success event for frontend feedback (e.g., toast notification)
            $this->dispatch('portfolio-saved-success', action: $action);
            // $this->dispatch('refreshComponent'); // closeModal often triggers render, might not be needed

        } catch (\Illuminate\Validation\ValidationException $e) {
             DB::rollBack();
             Log::error("ValidationException during portfolio save transaction: ", $e->errors());
             // Errors are already in the bag, modal stays open.
             session()->flash('error', 'Please correct the errors in the form.');
             $this->dispatch('portfolio-save-failed'); // Event for frontend

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error saving portfolio (UUID: {$this->editingPortfolio?->uuid}): " . $e->getMessage(), [
                'exception_trace' => Str::limit($e->getTraceAsString(), 1500),
                'user_id' => Auth::id(),
            ]);
            session()->flash('error', 'An unexpected error occurred while saving. Please try again.');
            // Keep modal open for user to retry or see data
            $this->dispatch('portfolio-save-failed'); // Event for frontend
        }
    }

    /**
     * Elimina (Soft Delete) un portfolio usando su UUID.
     */
    public function delete(string $uuid): void // Changed parameter to UUID
    {
        if (!$this->checkPermissionWithMessage('DELETE_PORTFOLIO', 'You do not have permission to delete portfolio items.')) {
            return;
        }

        DB::beginTransaction();
        try {
            // Find by UUID, including trashed in case of repeated attempts
            $portfolio = Portfolio::withTrashed()->with(['images', 'projectType'])
                                    ->where('uuid', $uuid)->firstOrFail(); // Query by UUID

            // Delete physical image files
            if ($portfolio->images->isNotEmpty()) {
                Log::info("Deleting image files for portfolio UUID {$uuid}. Count: {$portfolio->images->count()}");
                foreach ($portfolio->images as $image) {
                    if ($image->path) {
                        $this->portfolioImageService->deleteImage($image->path);
                    }
                }
                // Delete PortfolioImage records (cascade might handle this, but explicit is safer)
                $portfolio->images()->delete();
            }

            // Soft delete Portfolio
            $portfolio->delete();
            Log::info("Soft deleted portfolio UUID {$uuid}.");

            // Optional: Handle orphaned ProjectType
            $projectType = $portfolio->projectType;
            if ($projectType && !$projectType->trashed()) {
                 if ($projectType->portfolios()->whereNull('deleted_at')->doesntExist()) {
                     Log::info("ProjectType ID {$projectType->id} (Title: {$projectType->title}) is orphaned after deleting Portfolio UUID {$uuid}. Considering action.");
                     // $projectType->delete(); // Or mark inactive, based on your logic
                 }
            }

            DB::commit();
            $this->clearCache('portfolios');
            session()->flash('message', 'Portfolio item moved to trash successfully.');
            // Dispatch event for frontend feedback
            $this->dispatch('portfolio-deleted-success', uuid: $uuid);
            $this->resetPage(); // Refresh list

        } catch (ModelNotFoundException $e) { // More specific exception
            DB::rollBack();
            Log::warning("Attempted to delete non-existent portfolio UUID {$uuid}.");
            session()->flash('error', 'Portfolio item not found.');
            $this->dispatch('portfolio-delete-failed', uuid: $uuid, message: 'Not found');
        } catch (\Exception $e) {
             DB::rollBack();
             Log::error("Error deleting portfolio UUID {$uuid}: " . $e->getMessage(), [
                'trace' => Str::limit($e->getTraceAsString(), 1000)
             ]);
             session()->flash('error', 'Failed to move portfolio item to trash.');
             $this->dispatch('portfolio-delete-failed', uuid: $uuid, message: $e->getMessage());
        }
    }

    /**
     * Restaura un portfolio borrado (Soft Delete) usando su UUID.
     */
    public function restore(string $uuid): void // Changed parameter to UUID
    {
        if (!$this->checkPermissionWithMessage('RESTORE_PORTFOLIO', 'You do not have permission to restore portfolio items.')) {
             return;
        }
        DB::beginTransaction();
        try {
            // Find by UUID ONLY among trashed items
            $portfolio = Portfolio::onlyTrashed()->with('projectType')
                                    ->where('uuid', $uuid)->firstOrFail(); // Query by UUID

            // Restore the portfolio
            $portfolio->restore();
            Log::info("Restored portfolio UUID {$uuid}.");

            // Optional: Restore associated ProjectType if it was also trashed
            $projectType = $portfolio->projectType()->onlyTrashed()->first();
            if ($projectType) {
                 $projectType->restore();
                 // Consider reactivating if status was changed
                 // if ($projectType->status === 'inactive') $projectType->update(['status' => 'active']);
                 Log::info("Restored associated ProjectType ID {$projectType->id} for portfolio UUID {$uuid}.");
            }
            // NOTE: Physical image files are NOT restored from storage by this action.

            DB::commit();
            $this->clearCache('portfolios');
            session()->flash('message', 'Portfolio restored successfully.');
            // Optional: Hide deleted items view after restore
            if ($this->showDeleted) {
                $this->showDeleted = false;
            }
             // Dispatch event for frontend feedback
            $this->dispatch('portfolio-restored-success', uuid: $uuid);
            $this->resetPage(); // Refresh list

        } catch (ModelNotFoundException $e) { // More specific exception
            DB::rollBack();
            Log::warning("Portfolio UUID {$uuid} not found in trash for restoration.");
            session()->flash('error', 'Portfolio not found in trash or already restored.');
             $this->dispatch('portfolio-restore-failed', uuid: $uuid, message: 'Not found in trash');
        } catch (\Exception $e) {
             DB::rollBack();
             Log::error("Error restoring portfolio UUID {$uuid}: " . $e->getMessage(), [
                 'trace' => Str::limit($e->getTraceAsString(), 1000)
             ]);
             session()->flash('error', 'Failed to restore portfolio item.');
             $this->dispatch('portfolio-restore-failed', uuid: $uuid, message: $e->getMessage());
        }
     }

     // --- Image Management Methods (Pending & Existing) ---

    public function removePendingNewImage(int $index): void
    {
        if (isset($this->pendingNewImages[$index])) {
            // Optional: Delete temp file (usually handled by Livewire)
            // if ($this->pendingNewImages[$index] instanceof TemporaryUploadedFile) { $this->pendingNewImages[$index]->delete(); }
            unset($this->pendingNewImages[$index]);
            $this->pendingNewImages = array_values($this->pendingNewImages); // Reindex
            Log::debug("Removed pending new image at index {$index}.");
            $this->validateTotals(); // Re-check limits
        } else {
             Log::warning("Attempted to remove pending image at non-existent index: {$index}");
        }
    }

    public function markImageForDeletion(int $imageId): void
    {
        if (!in_array($imageId, $this->images_to_delete)) {
            $this->images_to_delete[] = $imageId;
            Log::debug("Marked existing image ID {$imageId} for deletion.");
            $this->validateTotals(); // Re-check limits/requirement
        }
    }

    public function unmarkImageForDeletion(int $imageId): void
    {
        $key = array_search($imageId, $this->images_to_delete);
        if ($key !== false) {
            unset($this->images_to_delete[$key]);
            $this->images_to_delete = array_values($this->images_to_delete); // Reindex
            Log::debug("Unmarked existing image ID {$imageId} from deletion list.");
            $this->validateTotals(); // Re-check limits/requirement
        }
    }

    // --- Modal, Reset, and Utility Methods ---

    public function closeModal(): void
    {
        if ($this->showModal) { // Prevent unnecessary resets if already closed
            $this->showModal = false;
            $this->resetFields();
            $this->resetErrorBag();
            $this->resetValidation();
            Log::debug("Portfolio modal closed and fields reset.");
             // Dispatch event for Alpine/JS if needed
             $this->dispatch('portfolio-modal-closed');
        }
    }

    public function resetFields(): void
    {
        $this->isEditing = false;
        $this->editingPortfolio = null;
        $this->title = '';
        $this->description = '';
        $this->service_category_id = null;
        $this->image_files = [];
        $this->pendingNewImages = [];
        $this->existing_images = collect();
        $this->images_to_delete = [];
        // Don't reset search, pagination, etc.
        Log::debug("Portfolio form fields and image arrays reset.");
    }

    public function toggleShowDeleted(): void
    {
        $this->showDeleted = !$this->showDeleted;
        $this->resetPage();
        $this->clearCache('portfolios');
        Log::debug("Toggled showDeleted. New state: " . ($this->showDeleted ? 'true' : 'false'));
    }

     public function sort($field): void
    {
         $allowedSorts = ['created_at']; // Add 'title' if you join/select it in getPortfolioQuery
         if (!in_array($field, $allowedSorts)) {
             Log::warning("Attempted to sort by invalid field: {$field}");
             return;
         }

         if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
         $this->clearCache('portfolios');
         $this->resetPage();
         Log::debug("Sorting changed. Field: {$this->sortField}, Direction: {$this->sortDirection}");
    }

    // --- Image Validation & Reordering ---

    protected function validateTotals(): void
    {
        // Reset only errors added by this method
        $this->resetErrorBag(['pendingNewImages']); // Use a consistent key for these global errors

        // Calculate counts
        $currentVisibleExistingCount = ($this->isEditing && $this->existing_images instanceof Collection)
            ? $this->existing_images->whereNotIn('id', $this->images_to_delete)->count()
            : 0;
        $newPendingCount = count($this->pendingNewImages);
        $totalImages = $currentVisibleExistingCount + $newPendingCount;

        // Validate total count
        if ($totalImages > self::MAX_FILES) {
            $errorMessage = 'Total images (' . $totalImages . ') cannot exceed ' . self::MAX_FILES . '. Please remove some.';
            $this->addError('pendingNewImages', $errorMessage); // Add error to the bag
            Log::warning("Image limit violation: Total count.", ['total' => $totalImages, 'limit' => self::MAX_FILES]);
            return; // Stop if count fails
        }

        // Validate total size of NEW images
        $totalNewSizeInBytes = 0;
        foreach ($this->pendingNewImages as $file) {
             if ($file instanceof TemporaryUploadedFile && $file->isValid()) {
                 try {
                     $totalNewSizeInBytes += $file->getSize();
                 } catch (\Exception $e) {
                     Log::error("Could not get size for temp file: " . $e->getMessage());
                     $this->addError('pendingNewImages', 'Could not process an uploaded file size. Please try re-adding it.');
                     return;
                 }
             }
        }
        $maxTotalBytes = self::MAX_TOTAL_SIZE_KB * 1024;
        if ($totalNewSizeInBytes > $maxTotalBytes) {
            $errorMessage = 'Total size of new images (' . round($totalNewSizeInBytes / 1024 / 1024, 2) . ' MB) exceeds limit (' . (self::MAX_TOTAL_SIZE_KB / 1024) . ' MB).';
            $this->addError('pendingNewImages', $errorMessage);
            Log::warning("Image limit violation: Total size.", ['total_bytes' => $totalNewSizeInBytes, 'limit_bytes' => $maxTotalBytes]);
            // Don't return yet, check requirement too
        }

        // Validate if at least one image is present overall (re-check based on current state)
        $isImageStillRequired = false;
         if ($this->isEditing) {
             // Editing: required if no visible existing AND no pending new
             $isImageStillRequired = ($currentVisibleExistingCount === 0 && $newPendingCount === 0);
         } else {
             // Creating: required if no pending new
             $isImageStillRequired = ($newPendingCount === 0);
         }

         // Add the 'required' error specifically if needed and not already added by `rules()`
         if ($isImageStillRequired && !$this->getErrorBag()->has('pendingNewImages.required') && !$this->getErrorBag()->has('pendingNewImages')) {
            // Check !$this->getErrorBag()->has('pendingNewImages') to avoid duplicate messages if count/size also failed
            $this->addError('pendingNewImages', 'At least one image is required for the portfolio.');
            Log::warning("Image requirement violation: No images present after potential removals.");
         }
    }

    protected function reorderImages(Portfolio $portfolio): void
    {
        Log::debug("Reordering images for portfolio UUID: {$portfolio->uuid}");
        $images = $portfolio->images()->orderBy('order', 'asc')->get();
        DB::transaction(function () use ($images) { // Wrap in transaction for safety
            foreach ($images as $index => $image) {
                if ($image->order !== $index) {
                    $image->order = $index;
                    $image->saveQuietly(); // Use saveQuietly if you don't want events/timestamps updated
                    // $image->save(); // Use save() if you need events/timestamps
                }
            }
        });
        // Reload relation to reflect changes in the component state
        $portfolio->load('images');
        $this->existing_images = $portfolio->images->sortBy('order')->values();
        Log::debug("Finished reordering images for portfolio UUID: {$portfolio->uuid}");
    }
}