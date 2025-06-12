<?php

namespace App\Livewire;

use App\Models\Portfolio;
use App\Models\ProjectType;
use App\Models\ServiceCategory;
use App\Models\PortfolioImage;
use App\Services\PortfolioImageService; // Importar el servicio de imágenes
use App\Traits\CacheTrait;             // Importar Trait de Caché
use App\Traits\ChecksPermissions;      // Importar Trait de Permisos
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
    // Array TEMPORAL vinculado al input (wire:model), contiene SOLO la última selección.
    // Livewire gestiona este array automáticamente con el input.
    public $image_files = [];
    // Array ACUMULATIVO de nuevos archivos (TemporaryUploadedFile) listos para guardar.
    // Este lo gestionamos nosotros en updatedImageFiles y removePendingNewImage.
    public $pendingNewImages = [];

    // --- Estado del Componente ---
    public bool $showModal = false;
    public bool $isEditing = false;
    public ?Portfolio $editingPortfolio = null; // El modelo Portfolio que se está editando
    public Collection $existing_images; // Colección de modelos PortfolioImage existentes al editar
    public array $images_to_delete = []; // Array de IDs de PortfolioImage existentes marcadas para borrar

    // --- Configuración/Control Adicional ---
    public string $search = ''; // Término de búsqueda
    public int $perPage = 10; // Elementos por página
    public string $sortField = 'created_at'; // Campo por defecto para ordenar
    public string $sortDirection = 'desc'; // Dirección por defecto para ordenar
    public bool $showDeleted = false; // Flag para mostrar/ocultar elementos borrados (Soft Deletes)

    // --- Datos para Selects ---
    public $serviceCategories; // Colección de ServiceCategory para el dropdown

    // --- Constantes para Límites de Imágenes ---
    public const MAX_FILES = 10; // Máximo número total de imágenes por portfolio (existentes + nuevas)
    public const MAX_SIZE_KB = 5120; // 5MB - Tamaño máximo por imagen individual
    public const MAX_TOTAL_SIZE_KB = 20480; // 20MB - Tamaño máximo total para nuevos imágenes subidas a la vez

    // --- Servicio Inyectado ---
    protected PortfolioImageService $portfolioImageService; // Servicio para manejar lógica de imágenes S3

    // --- Listeners para eventos ---
    protected $listeners = [
        'delete' => 'delete',
        'restore' => 'restore',
        'closeModal' => 'closeModal',
        'refreshComponent' => '$refresh',
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
        // Determinar si se requiere al menos una imagen en total (existente no marcada para borrar O nueva)
        $isImageRequired = false;
        if (!$this->isEditing) {
            // Creando: siempre se requiere al menos una imagen nueva
            $isImageRequired = true;
        } elseif ($this->isEditing && $this->existing_images instanceof Collection) {
            // Editando: se requiere una nueva si no quedan existentes (tras marcar para borrar)
            $isImageRequired = $this->existing_images->whereNotIn('id', $this->images_to_delete)->isEmpty() && empty($this->pendingNewImages);
        }

        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'service_category_id' => 'required|exists:service_categories,id',

            // Validar el array acumulado `$pendingNewImages` para el guardado final
            'pendingNewImages' => [
                 $isImageRequired ? 'required' : 'nullable', // Requerido si no quedan otras imágenes
                 'array',
                 // La validación del número MÁXIMO total se hará en `validateTotals`
            ],
            // Validación para cada archivo en el array acumulado `$pendingNewImages`
            'pendingNewImages.*' => [
                 'required', // Cada elemento del array debe ser un archivo válido
                 'image',
                 'mimes:jpeg,png,jpg,gif,webp', // Formatos permitidos
                 'max:' . self::MAX_SIZE_KB // Límite por archivo individual
            ],

             // Validación 'sometimes' para el input $image_files para feedback inmediato al seleccionar
             // Esto se valida en `updatedImageFiles`
             'image_files.*' => [
                 'sometimes', // Solo valida si image_files tiene algo
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
            'image_files.*.max' => 'The selected image must not be larger than ' . (self::MAX_SIZE_KB / 1024) . 'MB.', // <<< COMMA ADDED HERE
            // Mensaje genérico para errores añadidos con addError() en validateTotals()
            // (Se mostrará bajo el input de archivos o donde se asigne 'pendingNewImages')
            // 'pendingNewImages' => 'Please check image limits (total count or total size).' // Se usa addError con mensaje específico
        ];
    }


    // --- Método Mount (Inicialización) ---
    public function mount(): void
    {
        // Cargar categorías de servicio una vez
        $this->serviceCategories = ServiceCategory::orderBy('category')->get();
        // Inicializar colección de imágenes existentes (vacía al principio)
        $this->existing_images = collect();
        $this->resetPage(); // Asegura empezar en la página 1
    }

    /**
     * Hook: Se ejecuta cuando se actualiza `$image_files` (el input file `wire:model`).
     * Valida la nueva selección, la añade al array acumulativo `pendingNewImages`,
     * limpia el input model `$image_files` y revalida los totales.
     */
    public function updatedImageFiles(): void
    {
        // 1. Resetear errores previos del input temporal
        $this->resetErrorBag(['image_files', 'image_files.*']);

        // 2. Obtener los archivos recién seleccionados del input
        $newlySelectedFiles = $this->image_files;

        // 3. Validar SOLO los archivos recién seleccionados (tipo, tamaño individual)
        try {
            // Usamos validateOnly para no disparar todas las reglas del componente
            $this->validateOnly('image_files.*');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Si la validación de los nuevos falla, limpiamos el input model y detenemos.
            // Los errores se mostrarán automáticamente gracias al @error en la vista.
            $this->image_files = [];
            Log::warning('Validation failed for newly selected files.', ['errors' => $e->errors()]);
            return;
        }

        // 4. Añadir los archivos recién validados a la lista acumulada `$pendingNewImages`
        //    Evitar duplicados basados en el nombre temporal del archivo.
        $currentTempNames = collect($this->pendingNewImages)->map(function($file) {
            return $file instanceof TemporaryUploadedFile ? $file->getFilename() : null;
        })->filter()->toArray();

        $added = false;
        foreach ($newlySelectedFiles as $file) {
            // Asegurarnos de que es un archivo válido y no está ya en la lista pendiente
            if ($file instanceof TemporaryUploadedFile && $file->isValid() && !in_array($file->getFilename(), $currentTempNames)) {
                 $this->pendingNewImages[] = $file;
                 $currentTempNames[] = $file->getFilename(); // Actualizar para la siguiente iteración
                 $added = true;
            }
        }

        // 5. Limpiar el array vinculado al input (`$image_files`) después de procesarlo.
        //    Esto es crucial para permitir seleccionar más archivos y para que el estado
        //    acumulado en `$pendingNewImages` sea la única fuente de verdad para los nuevos archivos.
        $this->image_files = [];

        // 6. Si se añadieron archivos, validar la lista ACUMULADA completa (`$pendingNewImages`)
        //    respecto a los límites totales (cantidad y tamaño).
        if ($added) {
            $this->validateTotals();
        }
    }

    /**
     * Hook: Se ejecuta ANTES de actualizar una propiedad pública.
     * Usado aquí para resetear la paginación cuando cambian los filtros/búsqueda.
     */
    public function updating($name, $value): void
    {
        // Si cambia la búsqueda, ítems por página o el filtro de borrados, volver a la página 1
        if (in_array($name, ['search', 'perPage', 'showDeleted'])) {
            $this->resetPage();
        }
    }

    /**
     * Renderiza la vista del componente.
     */
        /**
     * Renderiza la vista del componente.
     */
    public function render()
    {
        // Comprobar permiso de lectura antes de mostrar nada
        if (!$this->checkPermission('READ_PORTFOLIO', true)) {
            return view('livewire.forbidden'); // Muestra vista de acceso denegado
        }

        // Generar clave de caché única basada en el estado actual (filtros, paginación, etc.)
        $cacheKey = $this->generateCacheKey('portfolios');

        // Intentar obtener datos de caché o ejecutar la query si no está cacheado
        // ***** CORRECTION: Added 'use ($cacheKey)' to the closure *****
        $portfolios = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($cacheKey) {
            Log::info("Cache miss or expired for key: {$cacheKey}. Fetching portfolios from DB.");
            return $this->getPortfolioQuery()->paginate($this->perPage);
        });

        return view('livewire.portfolios', [
            'portfolios' => $portfolios,
            'serviceCategoriesList' => $this->serviceCategories, // Pasar categorías al select del modal
        ]);
    }

    /**
     * Construye la query base para obtener los portfolios, aplicando filtros y ordenación.
     */
    protected function getPortfolioQuery()
    {
         // Empezar query con relaciones necesarias para la tabla y el modal
         $query = Portfolio::query()
            ->with([
                // Cargar ProjectType y su ServiceCategory para mostrar título y categoría
                'projectType.serviceCategory',
                // Cargar solo la primera imagen ordenada para la vista de tabla
                'images' => function ($q) {
                    $q->orderBy('order', 'asc')->limit(1);
                }
            ]);

        // Incluir (o no) los elementos borrados (Soft Deletes)
        if ($this->showDeleted) {
            $query->withTrashed();
        }

        // Aplicar filtro de búsqueda si existe
        if (!empty(trim($this->search))) {
            $searchTerm = '%' . trim($this->search) . '%';
            // Buscar en el título o descripción del ProjectType asociado
            $query->whereHas('projectType', function($q) use ($searchTerm) {
                 $q->where('title', 'like', $searchTerm)
                   ->orWhere('description', 'like', $searchTerm);
            });
            // Podrías añadir búsqueda en ServiceCategory también si es necesario:
            // ->orWhereHas('projectType.serviceCategory', function($q) use ($searchTerm) {
            //      $q->where('category', 'like', $searchTerm);
            // });
        }

        // Aplicar ordenación
        $sortableFields = ['created_at']; // Añadir más campos si se implementa ordenación por ellos (e.j. title via join)
        $sortField = in_array($this->sortField, $sortableFields) ? $this->sortField : 'created_at';
        $sortDirection = $this->sortDirection === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortField, $sortDirection);

        return $query;
    }

    // --- Métodos CRUD ---

    /**
     * Abre el modal en modo creación, reseteando los campos.
     */
    public function create(): void
    {
        // Verificar permiso antes de abrir
        if (!$this->checkPermissionWithMessage('CREATE_PORTFOLIO', 'You do not have permission to create portfolio items.')) {
            return;
        }
        $this->resetFields(); // Limpia datos de formulario y estado
        $this->isEditing = false; // Asegurar que está en modo creación
        $this->showModal = true; // Mostrar el modal
    }

    /**
     * Carga datos de un portfolio existente en el modal para edición.
     */
    public function edit(Portfolio $portfolio): void
    {
         // Verificar permiso antes de cargar datos
         if (!$this->checkPermissionWithMessage('UPDATE_PORTFOLIO', 'You do not have permission to edit portfolio items.')) {
            return;
         }
        $this->resetErrorBag(); // Limpiar errores de validación previos

        // Cargar relaciones necesarias si no están ya cargadas
        // 'images' se carga con todas las imágenes ordenadas para la gestión en el modal
        $portfolio->loadMissing(['projectType.serviceCategory', 'images' => fn($q) => $q->orderBy('order', 'asc')]);

        // Comprobar que los datos esenciales existen
        if (!$portfolio->projectType) {
             session()->flash('error', 'Cannot edit: Associated project data is missing. The portfolio might be corrupted.');
             Log::error("Attempted to edit Portfolio ID {$portfolio->id} but its ProjectType relation is missing or null.");
             $this->closeModal(); // Cerrar el modal si no se puede editar
             return;
        }

        // Establecer estado de edición y rellenar campos del formulario
        $this->isEditing = true;
        $this->editingPortfolio = $portfolio; // Guardar referencia al modelo que se edita
        $this->title = $portfolio->projectType->title;
        $this->description = $portfolio->projectType->description;
        $this->service_category_id = $portfolio->projectType->service_category_id;
        $this->existing_images = $portfolio->images; // Cargar la colección de imágenes existentes
        $this->image_files = []; // Limpiar input temporal de archivos
        $this->pendingNewImages = []; // Limpiar array acumulativo de nuevos archivos
        $this->images_to_delete = []; // Limpiar array de imágenes marcadas para borrar

        $this->showModal = true; // Mostrar el modal
    }

    /**
     * Guarda un portfolio (crea uno nuevo o actualiza uno existente).
     * Maneja la lógica de ProjectType, Portfolio, y PortfolioImages (borrado y subida).
     */
    public function save(): void
    {
        // Determinar permiso necesario basado en si es creación o edición
        $permission = $this->isEditing ? 'UPDATE_PORTFOLIO' : 'CREATE_PORTFOLIO';
        $action = $this->isEditing ? 'update' : 'create';
        if (!$this->checkPermissionWithMessage($permission, "You do not have permission to {$action} portfolio items.")) {
            return;
        }

        // 1. Validar datos del formulario y la lista acumulada `$pendingNewImages` usando las `rules()`
        //    Esto incluye la validación de requerimiento de imagen si aplica.
        $validatedData = $this->validate();

        // 2. Re-validar límites totales (cantidad y tamaño) como última comprobación
        //    Esto usa `$pendingNewImages` y `$existing_images` / `$images_to_delete`.
        $this->validateTotals();
        if ($this->getErrorBag()->isNotEmpty()) {
             // Si `validateTotals` añadió errores, detener el proceso.
             Log::warning("Validation failed on final total image checks.", $this->getErrorBag()->toArray());
             // No cerrar el modal para que el usuario vea los errores.
             return;
        }

        // 3. Iniciar transacción de base de datos
        DB::beginTransaction();
        try {
            $portfolio = null;
            // Obtener la lista final de archivos nuevos a subir desde la propiedad acumulada
            $filesToUpload = $this->pendingNewImages;

            if ($this->isEditing) {
                // --- LÓGICA DE ACTUALIZACIÓN ---
                if (!$this->editingPortfolio || !$this->editingPortfolio->projectType) {
                    // Doble chequeo por si algo cambió desde que se abrió el modal
                    throw new \Exception("Editing target portfolio (ID: {$this->editingPortfolio?->id}) or its project type not found during save.");
                }
                $portfolio = $this->editingPortfolio;

                // a) Actualizar ProjectType asociado
                $portfolio->projectType->update([
                    'title' => $validatedData['title'],
                    'description' => $validatedData['description'],
                    'service_category_id' => $validatedData['service_category_id'],
                    // Podrías querer actualizar 'user_id' si el editor es diferente, o 'status'
                    // 'user_id' => Auth::id(),
                ]);
                $portfolio->touch(); // Actualiza 'updated_at' del Portfolio aunque solo cambie ProjectType

                // b) Borrar Imágenes Existentes Marcadas
                if (!empty($this->images_to_delete)) {
                    Log::info("Processing image deletions for portfolio {$portfolio->id}", ['ids_to_delete' => $this->images_to_delete]);
                    // Asegurarse de borrar solo imágenes que pertenecen a ESTE portfolio
                    $imagesToDelete = PortfolioImage::whereIn('id', $this->images_to_delete)
                                        ->where('portfolio_id', $portfolio->id)->get();

                    if ($imagesToDelete->isNotEmpty()) {
                        foreach ($imagesToDelete as $img) {
                            Log::info("Deleting image file from storage: {$img->path}");
                            $this->portfolioImageService->deleteImage($img->path); // Borrar archivo físico (S3, local, etc.)
                            $img->delete(); // Borrar registro de la BD
                        }
                         // Recargar la relación de imágenes para reflejar los cambios
                        $portfolio->load('images');
                        // Actualizar estado del componente y asegurarse de que está ordenado
                        $this->existing_images = $portfolio->images->sortBy('order')->values();
                    } else {
                         Log::warning("Attempted to delete image IDs not found or not belonging to portfolio {$portfolio->id}", ['ids_attempted' => $this->images_to_delete]);
                    }
                    $this->images_to_delete = []; // Limpiar estado después de procesar
                }

                // c) Guardar Nuevas Imágenes (desde $filesToUpload) y asignar orden
                // Calcular el siguiente número de orden basado en las imágenes restantes
                $nextOrderIndex = $this->existing_images->max('order') !== null ? $this->existing_images->max('order') + 1 : 0;
                if (!empty($filesToUpload)) {
                    Log::info("Adding new images for portfolio {$portfolio->id}", ['count' => count($filesToUpload), 'starting_order' => $nextOrderIndex]);
                    foreach ($filesToUpload as $file) {
                        if ($file instanceof TemporaryUploadedFile && $file->isValid()) {
                            // Usar el servicio para guardar la imagen
                            $storedPath = $this->portfolioImageService->storeImage($file);
                            if ($storedPath) {
                                // Crear el registro PortfolioImage asociado al portfolio
                                $portfolio->images()->create([
                                    'path' => $storedPath,
                                    'order' => $nextOrderIndex++
                                ]);
                                Log::debug("Stored new image at path: " . $storedPath . " with order " . ($nextOrderIndex - 1));
                            } else {
                                 // Si el servicio falla al guardar, lanzar excepción para rollback
                                 throw new \Exception("Failed to store one or more new images during update for portfolio {$portfolio->id}. Check PortfolioImageService.");
                            }
                        } else {
                            Log::warning("Skipping an invalid item in filesToUpload during update.", ['item_info' => is_object($file) ? get_class($file) : gettype($file)]);
                        }
                    }
                    // Recargar la relación para incluir las nuevas imágenes en existing_images para el final
                     $portfolio->load('images');
                     $this->existing_images = $portfolio->images->sortBy('order')->values();
                }

                // d) Opcional pero recomendado: Reordenar todas las imágenes restantes para asegurar secuencia sin huecos
                $this->reorderImages($portfolio);

                session()->flash('message', 'Portfolio updated successfully.');

            } else {
                // --- LÓGICA DE CREACIÓN ---
                // a) Crear ProjectType
                $projectType = ProjectType::create([
                    'title' => $validatedData['title'],
                    'description' => $validatedData['description'],
                    'service_category_id' => $validatedData['service_category_id'],
                    'status' => 'active', // O el estado por defecto que desees
                    'user_id' => Auth::id(), // Asociar al usuario autenticado
                ]);
                Log::info("Created new ProjectType with ID: {$projectType->id}");

                // b) Crear Portfolio asociado al nuevo ProjectType
                $portfolio = Portfolio::create(['project_type_id' => $projectType->id]);
                Log::info("Created new Portfolio with ID: {$portfolio->id}");

                // c) Guardar Imágenes (desde $filesToUpload) - Obligatorio en creación (por validación)
                if (!empty($filesToUpload)) {
                    Log::info("Adding images for new portfolio {$portfolio->id}", ['count' => count($filesToUpload)]);
                    foreach ($filesToUpload as $index => $file) {
                        if ($file instanceof TemporaryUploadedFile && $file->isValid()) {
                            $storedPath = $this->portfolioImageService->storeImage($file);
                            if ($storedPath) {
                                // Crear registro PortfolioImage con orden secuencial empezando en 0
                                $portfolio->images()->create([
                                    'path' => $storedPath,
                                    'order' => $index
                                ]);
                                Log::debug("Stored new image at path: {$storedPath} with order {$index}");
                            } else {
                                throw new \Exception("Failed to store one or more images during creation for portfolio {$portfolio->id}. Check PortfolioImageService.");
                            }
                        } else {
                             Log::warning("Skipping an invalid item in filesToUpload during creation.", ['item_info' => is_object($file) ? get_class($file) : gettype($file)]);
                        }
                    }
                } else {
                    // Esta excepción no debería ocurrir si la validación 'pendingNewImages.required' funciona bien en creación
                    throw new \Exception("Image files array ('pendingNewImages') was unexpectedly empty during portfolio creation. Validation might have failed silently.");
                }
                session()->flash('message', 'Portfolio created successfully.');
            }

            // 4. Confirmar transacción si todo fue bien
            DB::commit();
            Log::info("Portfolio save transaction committed successfully for portfolio ID: {$portfolio->id}");

            // 5. Limpiar caché relevante
            $this->clearCache('portfolios');

            // 6. Cerrar modal y resetear estado del formulario
            $this->closeModal();

        } catch (\Illuminate\Validation\ValidationException $e) {
             // Rollback en caso de fallo de validación (aunque debería ser capturado antes)
             DB::rollBack();
             Log::error("ValidationException during portfolio save transaction: ", $e->errors());
             // Los errores de validación ya deberían estar en el ErrorBag, no cerrar modal.
             session()->flash('error', 'Please correct the errors in the form.');

        } catch (\Exception $e) {
            // Rollback en caso de cualquier otra excepción (guardado de archivo, BD, etc.)
            DB::rollBack();
            Log::error("Error saving portfolio: " . $e->getMessage(), [
                'exception_trace' => Str::limit($e->getTraceAsString(), 1500), // Limitar traza
                'portfolio_being_edited_id' => $this->editingPortfolio?->id,
                'is_editing_flag' => $this->isEditing,
                'user_id' => Auth::id(),
            ]);
            // Mostrar mensaje genérico al usuario y mantener el modal abierto si es posible
            session()->flash('error', 'An unexpected error occurred while saving the portfolio. Please try again or contact support if the problem persists.');
             // No cerrar el modal para que el usuario no pierda los datos introducidos
        }
    }

     /**
      * Elimina (Soft Delete) un portfolio y sus recursos asociados (imágenes).
      */
    public function delete($portfolioId): void
    {
        // Verificar permiso
        if (!$this->checkPermissionWithMessage('DELETE_PORTFOLIO', 'You do not have permission to delete portfolio items.')) {
            return;
        }

        DB::beginTransaction();
        try {
            // Encontrar el portfolio, incluyendo los borrados por si se intenta borrar de nuevo
            $portfolio = Portfolio::withTrashed()->with(['images', 'projectType'])->findOrFail($portfolioId);

            // 1. Borrar archivos físicos de las imágenes asociadas
            if ($portfolio->images->isNotEmpty()) {
                Log::info("Deleting image files for portfolio ID {$portfolioId}. Image count: {$portfolio->images->count()}");
                foreach ($portfolio->images as $image) {
                    if ($image->path) { // Comprobar que path no es null o vacío
                        $this->portfolioImageService->deleteImage($image->path);
                    } else {
                        Log::warning("Image record (ID: {$image->id}) for portfolio {$portfolioId} has empty path, skipping file deletion.");
                    }
                }
                // 2. Borrar registros PortfolioImage de la BD
                // (Si tienes onDelete('cascade') en la migración, esto es redundante pero seguro)
                $deletedImageRows = $portfolio->images()->delete();
                Log::info("Deleted {$deletedImageRows} PortfolioImage database records for portfolio ID {$portfolioId}.");
            } else {
                 Log::info("No images found or associated with portfolio ID {$portfolioId} to delete.");
            }


            // 3. Borrar Portfolio (Soft Delete)
            $portfolio->delete(); // Esto dispara el soft delete
            Log::info("Soft deleted portfolio ID {$portfolioId}.");

            // 4. Lógica opcional para ProjectType huérfano
            //    Comprobar si el ProjectType asociado ya no tiene ningún Portfolio
            $projectType = $portfolio->projectType;
            // Asegurarse de que projectType existe y no está ya borrado (soft deleted)
            if ($projectType && !$projectType->trashed()) {
                 // Contar portfolios activos (no borrados) asociados a este projectType
                 $activePortfoliosCount = $projectType->portfolios()->whereNull('deleted_at')->count();
                 if ($activePortfoliosCount === 0) {
                     Log::info("ProjectType ID {$projectType->id} is now potentially orphaned after deleting Portfolio ID {$portfolio->id}. Consider action.");
                     // --- DECISIÓN DE NEGOCIO AQUÍ ---
                     // Opción A: Marcar como inactivo
                     // $projectType->update(['status' => 'inactive']);
                     // Log::info("Marked orphaned ProjectType ID {$projectType->id} as inactive.");
                     // Opción B: Borrar (Soft Delete) el ProjectType también
                     // $projectType->delete();
                     // Log::info("Soft deleted orphaned ProjectType ID {$projectType->id}.");
                     // Opción C: No hacer nada (puede ser reutilizado o limpiado por otro proceso)
                     // --- Fin Decisión ---
                 }
            }


            DB::commit();
            $this->clearCache('portfolios'); // Limpiar caché para que la lista se actualice
            session()->flash('message', 'Portfolio item moved to trash successfully.');

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            Log::warning("Attempted to delete non-existent portfolio ID {$portfolioId}.");
            session()->flash('error', 'Portfolio item not found.');
        } catch (\Exception $e) {
             DB::rollBack();
             Log::error("Error deleting portfolio ID {$portfolioId}: " . $e->getMessage(), [
                'trace' => Str::limit($e->getTraceAsString(), 1000)
             ]);
             session()->flash('error', 'Failed to move portfolio item to trash.');
        }
        // Forzar refresh si estás mostrando borrados para que desaparezca de la lista principal
        // O simplemente dejar que el siguiente render lo haga al cambiar el estado cacheado.
        // $this->dispatch('refreshComponent');
        $this->resetPage(); // Reset page puede ser mejor que $refresh
    }

    /**
     * Restaura un portfolio borrado (Soft Delete).
     */
    public function restore($portfolioId): void
    {
        // Verificar permiso
        if (!$this->checkPermissionWithMessage('RESTORE_PORTFOLIO', 'You do not have permission to restore portfolio items.')) {
             return;
        }
        DB::beginTransaction();
        try {
            // Encontrar SOLO entre los borrados
            $portfolio = Portfolio::onlyTrashed()->with('projectType')->findOrFail($portfolioId);

            // Restaurar el portfolio
            $portfolio->restore();
            Log::info("Restored portfolio ID {$portfolioId}.");

            // Opcional: Restaurar ProjectType si también fue borrado (soft deleted) y ahora es necesario
            $projectType = $portfolio->projectType()->onlyTrashed()->first();
            if ($projectType) {
                 $projectType->restore();
                 // Quizás actualizar su estado si fue marcado como inactivo
                 // $projectType->update(['status' => 'active']);
                 Log::info("Restored associated ProjectType ID {$projectType->id} along with portfolio ID {$portfolioId}.");
            }
            // Nota: Las imágenes NO se restauran automáticamente de S3. La restauración aquí solo afecta a la BD.
            // Si los archivos fueron borrados físicamente en el 'delete', necesitarías un backup para restaurarlos.

            DB::commit();
            $this->clearCache('portfolios'); // Limpiar caché
            session()->flash('message', 'Portfolio restored successfully.');
            // Opcional: Ocultar los borrados después de restaurar uno
            $this->showDeleted = false;
            $this->resetPage();

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();
            Log::warning("Portfolio ID {$portfolioId} not found in trash for restoration.");
            session()->flash('error', 'Portfolio not found in trash or already restored.');
        } catch (\Exception $e) {
             DB::rollBack();
             Log::error("Error restoring portfolio ID {$portfolioId}: " . $e->getMessage(), [
                 'trace' => Str::limit($e->getTraceAsString(), 1000)
             ]);
             session()->flash('error', 'Failed to restore portfolio item.');
        }
         // Forzar refresh para que aparezca en la lista principal si es necesario
         // $this->dispatch('refreshComponent');
     }

     // --- Métodos para manejar Imágenes Pendientes y Existentes en el Formulario ---

    /**
     * Quita una imagen PENDIENTE (recién añadida, no guardada) del array acumulado `$pendingNewImages`.
     */
    public function removePendingNewImage(int $index): void
    {
        // Verificar que el índice existe en el array de imágenes pendientes
        if (isset($this->pendingNewImages[$index])) {

            // Opcional: Eliminar el archivo temporal si es necesario (Livewire suele limpiarlos)
            // if ($this->pendingNewImages[$index] instanceof TemporaryUploadedFile) {
            //     try {
            //         $this->pendingNewImages[$index]->delete();
            //     } catch (\Exception $e) {
            //         Log::error("Could not delete temporary file on removal: " . $e->getMessage());
            //     }
            // }

            // Quitar el elemento del array
            unset($this->pendingNewImages[$index]);
            // Reindexar el array para evitar huecos (importante para los bucles en la vista)
            $this->pendingNewImages = array_values($this->pendingNewImages);

            Log::debug("Removed pending new image at index {$index}.");

            // Revalidar totales después de quitar, ya que afecta la cuenta total y tamaño.
            $this->validateTotals();

            // Podrías necesitar un $this->dispatch('$refresh') si la vista no se actualiza
            // automáticamente en algún caso, pero normalmente no es necesario al modificar una propiedad pública.

        } else {
             Log::warning("Attempted to remove pending image at non-existent index: {$index}");
        }
    }

    /**
     * Marca una imagen EXISTENTE (ya guardada) para ser borrada cuando se guarde el formulario.
     */
    public function markImageForDeletion(int $imageId): void
    {
        // Añadir el ID al array si no está ya presente
        if (!in_array($imageId, $this->images_to_delete)) {
            $this->images_to_delete[] = $imageId;
            Log::debug("Marked existing image ID {$imageId} for deletion.");

            // Revalidar totales, ya que reducirá el número de imágenes existentes visibles
            // y podría hacer que se requiera una nueva imagen si se marcan todas.
            $this->validateTotals();
        } else {
             Log::debug("Image ID {$imageId} was already marked for deletion.");
        }
    }

     /**
      * DESmarca una imagen EXISTENTE que había sido marcada para borrar.
      */
    public function unmarkImageForDeletion(int $imageId): void
    {
        // Buscar la clave del ID en el array
        $key = array_search($imageId, $this->images_to_delete);
        // Si se encuentra, quitarla
        if ($key !== false) {
            unset($this->images_to_delete[$key]);
            // Reindexar el array (opcional pero buena práctica)
            $this->images_to_delete = array_values($this->images_to_delete);
            Log::debug("Unmarked existing image ID {$imageId} from deletion list.");

             // Revalidar totales, ya que ahora hay una imagen existente más
             $this->validateTotals();
        } else {
             Log::debug("Attempted to unmark image ID {$imageId}, but it was not in the deletion list.");
        }
    }

    // --- Métodos de Control del Modal y Reset ---

    /**
     * Cierra el modal y resetea completamente el estado del formulario.
     */
    public function closeModal(): void
    {
        $this->showModal = false; // Ocultar modal
        $this->resetFields(); // Limpiar datos del formulario y estado asociado
        $this->resetErrorBag(); // Limpiar errores de validación mostrados
        $this->resetValidation(); // Limpiar estado de validación interno de Livewire
        Log::debug("Modal closed and fields reset.");
    }

    /**
     * Resetea las propiedades del formulario, el estado de edición y los arrays de imágenes.
     * No resetea filtros, paginación, etc.
     */
    public function resetFields(): void
    {
        $this->isEditing = false;
        $this->editingPortfolio = null;
        $this->title = '';
        $this->description = '';
        $this->service_category_id = null;
        $this->image_files = [];      // Limpiar input temporal `wire:model`
        $this->pendingNewImages = []; // Limpiar lista acumulada de archivos nuevos << IMPORTANTE
        $this->existing_images = collect(); // Limpiar colección de imágenes existentes
        $this->images_to_delete = []; // Limpiar lista de IDs a borrar
        // No resetear search, perPage, sortField, sortDirection, showDeleted aquí
        Log::debug("Form fields and image arrays reset.");
    }

    // --- Métodos Adicionales (Soft Deletes, Sorting, Validación Total Imágenes) ---

    /**
     * Cambia el estado para mostrar/ocultar elementos borrados (Soft Deleted) en la tabla.
     */
    public function toggleShowDeleted(): void
    {
        $this->showDeleted = !$this->showDeleted;
        $this->resetPage(); // Volver a página 1 al cambiar el filtro
        $this->clearCache('portfolios'); // Invalidar caché porque los datos cambiarán
        Log::debug("Toggled showDeleted. New state: " . ($this->showDeleted ? 'true' : 'false'));
    }

     /**
      * Cambia el campo o la dirección de ordenación de la tabla.
      */
     public function sort($field): void
    {
         // Definir campos permitidos para ordenar para evitar manipulación
         $allowedSorts = ['created_at']; // Ampliar si añades más opciones (e.g., 'title')
         if (!in_array($field, $allowedSorts)) {
             Log::warning("Attempted to sort by invalid or disallowed field: {$field}");
             return;
         }

         // Si se clica en el mismo campo, invertir dirección; si no, ordenar por el nuevo campo ascendentemente
         if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc'; // Por defecto ascendente al cambiar de campo
        }
         $this->clearCache('portfolios'); // Invalidar caché por cambio de orden
         $this->resetPage(); // Volver a página 1 al cambiar orden
         Log::debug("Sorting changed. Field: {$this->sortField}, Direction: {$this->sortDirection}");
    }

    /**
     * Valida los límites globales de las imágenes:
     * 1. Número total máximo de archivos (existentes no borrados + nuevos pendientes).
     * 2. Tamaño total máximo de los archivos NUEVOS pendientes.
     * 3. Requerimiento de al menos una imagen nueva si todas las existentes se marcan para borrar.
     * Añade errores directamente al error bag usando la clave 'pendingNewImages' si se superan los límites.
     */
    protected function validateTotals(): void
    {
         // Resetear SOLO los errores específicos de totales que añadimos aquí manualmente.
         // Usamos 'pendingNewImages' como clave genérica para estos errores globales.
         $this->resetErrorBag(['pendingNewImages']);

         // 1. Calcular recuentos actuales
         $currentVisibleExistingCount = 0;
         if ($this->isEditing && $this->existing_images instanceof Collection) {
            $currentVisibleExistingCount = $this->existing_images->whereNotIn('id', $this->images_to_delete)->count();
         }
         $newPendingCount = count($this->pendingNewImages);
         $totalImages = $currentVisibleExistingCount + $newPendingCount;

         // 2. Validar número total de imágenes
         if ($totalImages > self::MAX_FILES) {
             $errorMessage = 'The total number of images (current: ' . $currentVisibleExistingCount
                           . ', new: ' . $newPendingCount . ') cannot exceed ' . self::MAX_FILES
                           . '. Please remove some images.';
             $this->addError('pendingNewImages', $errorMessage);
             Log::warning("Image limit violation: Total count exceeded.", ['total' => $totalImages, 'limit' => self::MAX_FILES]);
             // No continuar con la validación de tamaño si ya falló la cantidad
             return;
         }

         // 3. Validar tamaño total acumulado de los NUEVOS archivos pendientes (`$pendingNewImages`)
         $totalNewSizeInBytes = 0;
         foreach ($this->pendingNewImages as $index => $file) {
             // Asegurarse de que es un archivo válido antes de intentar obtener tamaño
             if ($file instanceof TemporaryUploadedFile && $file->isValid()) {
                 try {
                    // getSize() devuelve bytes
                    $totalNewSizeInBytes += $file->getSize();
                 } catch (\Exception $e) {
                     // Esto puede pasar si el archivo temporal desaparece por alguna razón
                     Log::error("Could not get size for temporary file: " . $file->getFilename() . " at index {$index} - " . $e->getMessage());
                     $this->addError('pendingNewImages', 'Could not process one of the uploaded files. Please try removing and re-adding it.');
                     return; // Detener si un archivo es inválido
                 }
             }
         }

         $maxTotalBytes = self::MAX_TOTAL_SIZE_KB * 1024; // Convertir KB a Bytes
         if ($totalNewSizeInBytes > $maxTotalBytes) {
             $errorMessage = 'The total size of newly added images ('
                           . round($totalNewSizeInBytes / 1024 / 1024, 2) . ' MB) exceeds the upload limit of '
                           . (self::MAX_TOTAL_SIZE_KB / 1024) . ' MB.';
             $this->addError('pendingNewImages', $errorMessage);
             Log::warning("Image limit violation: Total size exceeded.", ['total_bytes' => $totalNewSizeInBytes, 'limit_bytes' => $maxTotalBytes]);
             // No es necesario retornar aquí, puede haber también error de requerimiento
         }

         // 4. Validar si ahora se requieren imágenes nuevas (solo en modo edición)
         //    Esto ocurre si el usuario marcó TODAS las imágenes existentes para borrar y no ha añadido ninguna nueva.
         //    También se aplica si NO había imágenes existentes para empezar y no se añadió ninguna nueva.
         $needsNewImage = false;
         if ($this->isEditing && $this->existing_images instanceof Collection) {
            $needsNewImage = $this->existing_images->whereNotIn('id', $this->images_to_delete)->isEmpty() // No quedan existentes visibles
                            && empty($this->pendingNewImages); // Y no hay nuevas pendientes
         } elseif (!$this->isEditing) {
             $needsNewImage = empty($this->pendingNewImages); // En creación, se necesita al menos una pendiente
         }

         // Si se necesita una imagen y no hay, añadir error (excepto si el error de required ya está por $isImageRequired en rules())
        if ($needsNewImage && !$this->getErrorBag()->has('pendingNewImages.required')) {
            // Solo añadir este error específico si el error 'required' general no se disparó ya
            if ($this->isEditing && $this->existing_images->isNotEmpty()) {
                 // Caso específico: editando, había imágenes, se marcaron todas para borrar
                 $this->addError('pendingNewImages', 'You have marked all existing images for deletion. Please add at least one new image, or unmark an existing one.');
                 Log::warning("Image requirement violation: All existing marked for deletion, no new ones added.");
            } elseif (!$this->isEditing) {
                 // Caso: Creando y no se añadió ninguna imagen
                 // Este mensaje ya debería estar cubierto por 'pendingNewImages.required' de las rules(),
                 // pero lo añadimos por si acaso esa lógica falla o para dar un mensaje más específico.
                 $this->addError('pendingNewImages', 'At least one image is required when creating a portfolio.');
                 Log::warning("Image requirement violation: No images added during creation.");
            }
            // Podría haber un caso de edición donde no había imágenes existentes al principio,
            // en ese caso, se aplica la regla 'required' de `rules()`
        }

        // Si no hubo errores de validateTotals, el ErrorBag para 'pendingNewImages' estará vacío.
        // Si hubo errores, se mostrarán en la vista donde uses @error('pendingNewImages').
    }

    /**
     * Reordena las imágenes de un portfolio para asegurar una secuencia continua (0, 1, 2...).
     * Llama esto después de borrar y añadir imágenes en la actualización.
     */
    protected function reorderImages(Portfolio $portfolio): void
    {
        Log::debug("Reordering images for portfolio ID: {$portfolio->id}");
        // Obtener todas las imágenes restantes ordenadas por su orden actual
        $images = $portfolio->images()->orderBy('order', 'asc')->get();

        // Actualizar el orden en la base de datos secuencialmente
        foreach ($images as $index => $image) {
            if ($image->order !== $index) {
                $image->order = $index;
                $image->save(); // Guardar el cambio de orden
                Log::debug("Updated image ID {$image->id} to order {$index}");
            }
        }
        // Recargar la relación en el modelo editado para reflejar el reordenamiento
        $portfolio->load('images');
        $this->existing_images = $portfolio->images->sortBy('order')->values(); // Actualizar estado del componente
    }

}