<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use App\Models\Portfolio;
use App\Models\ServiceCategory;
use App\Models\ProjectType;
use App\Helpers\ImageHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Traits\ChecksPermissions;

class Portfolios extends Component
{
    use WithFileUploads, WithPagination, ChecksPermissions;

    protected $paginationTheme = 'tailwind';

    public $uuid;
    public $title;
    public $description;
    public $images = [];
    public $service_category_id;
    public $project_type_id;
    public $status = 'active';
    public $portfolioId;
    
    public $isOpen = false;
    public $modalTitle = 'Create Portfolio';
    public $modalAction = 'store';
    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $isSubmitting = false;
    
    public $tempImages = [];
    public $selectedCategory = '';
    public $selectedProjectType = '';
    public $maxTotalSize = 20480; // 20MB in KB
    public $maxFiles = 10; // Maximum number of files allowed

    protected $listeners = [
        'refreshComponent' => '$refresh'
    ];

    protected function rules()
    {
        return [
            'title' => 'required|min:3',
            'description' => 'required|min:10',
            'images.*' => 'image|max:20480',
            'service_category_id' => 'required|exists:service_categories,id',
            'project_type_id' => 'required|exists:project_types,id',
            'status' => 'required|in:active,inactive',
            'tempImages.*' => 'image|max:20480'
        ];
    }

    public function render()
    {
        if (!$this->checkPermission('READ_PORTFOLIO', true)) {
            return;
        }
        
        $query = Portfolio::query()
            ->when($this->search, function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('description', 'like', '%' . $this->search . '%');
            })
            ->when($this->selectedCategory, function ($query) {
                $query->where('service_category_id', $this->selectedCategory);
            })
            ->when($this->selectedProjectType, function ($query) {
                $query->where('project_type_id', $this->selectedProjectType);
            })
            ->orderBy($this->sortField, $this->sortDirection);

        $portfolios = $query->paginate($this->perPage);

        $categories = Cache::remember('service_categories_list', 3600, function () {
            return ServiceCategory::where('status', 'active')->get();
        });

        $projectTypes = Cache::remember('project_types_list', 3600, function () {
            return ProjectType::where('status', 'active')->get();
        });

        return view('livewire.portfolios', [
            'portfolios' => $portfolios,
            'categories' => $categories,
            'projectTypes' => $projectTypes
        ]);
    }

    public function sort($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function create()
    {
        if (!$this->checkPermissionWithMessage('CREATE_PORTFOLIO', 'No tienes permiso para crear elementos de portfolio')) {
            return;
        }
        
        $this->resetInputFields();
        $this->modalTitle = 'Create Portfolio';
        $this->modalAction = 'store';
        $this->openModal();
    }

    public function store()
    {
        try {
            if (!$this->checkPermissionWithMessage('CREATE_PORTFOLIO', 'No tienes permiso para crear elementos de portfolio')) {
                return;
            }

            $this->validate();

            \Log::info('Attempting to save portfolio', [
                'title' => $this->title,
                'action' => 'create'
            ]);

            $portfolio = new Portfolio();
            $portfolio->uuid = Str::uuid();
            $portfolio->title = $this->title;
            $portfolio->description = $this->description;
            $portfolio->service_category_id = $this->service_category_id;
            $portfolio->project_type_id = $this->project_type_id;
            $portfolio->status = $this->status;
            $portfolio->user_id = Auth::id();

            if (!empty($this->images)) {
                // Store the first image as the main image
                $portfolio->image = ImageHelper::storeAndResize(
                    $this->images[0],
                    'portfolios'
                );

                // Store additional images in the images JSON column
                if (count($this->images) > 1) {
                    $additionalImages = [];
                    foreach (array_slice($this->images, 1) as $image) {
                        $additionalImages[] = ImageHelper::storeAndResize(
                            $image,
                            'portfolios'
                        );
                    }
                    $portfolio->additional_images = $additionalImages;
                }
            }

            $portfolio->save();

            \Log::info('Portfolio saved successfully', [
                'title' => $this->title
            ]);

            session()->flash('message', 'Portfolio created successfully.');
            $this->closeModal();
            $this->resetInputFields();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('validation-failed');
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('validation-failed');
            \Log::error('Error saving portfolio', [
                'title' => $this->title,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Error saving portfolio: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            if (!$this->checkPermissionWithMessage('UPDATE_PORTFOLIO', 'No tienes permiso para editar elementos de portfolio')) {
                return;
            }
            
            \Log::info('Attempting to edit portfolio', ['id' => $id]);
            
            $portfolio = Portfolio::findOrFail($id);
            
            $this->portfolioId = $id;
            $this->title = $portfolio->title;
            $this->description = $portfolio->description;
            $this->service_category_id = $portfolio->service_category_id;
            $this->project_type_id = $portfolio->project_type_id;
            $this->status = $portfolio->status;
            
            $this->modalTitle = 'Edit Portfolio';
            $this->modalAction = 'update';
            
            // Make sure the modal opens
            $this->isOpen = true;
            
            // Initialize Alpine form values
            $this->dispatch('portfolio-edit', [
                'title' => $this->title,
                'description' => $this->description,
                'service_category_id' => $this->service_category_id,
                'project_type_id' => $this->project_type_id,
                'status' => $this->status
            ]);
            
            \Log::info('Portfolio data loaded for editing', [
                'id' => $id, 
                'title' => $portfolio->title
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading portfolio for edit', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Error loading portfolio data: ' . $e->getMessage());
        }
    }

    public function update()
    {
        try {
            if (!$this->checkPermissionWithMessage('UPDATE_PORTFOLIO', 'No tienes permiso para actualizar elementos de portfolio')) {
                return;
            }

            $this->validate();

            \Log::info('Attempting to update portfolio', [
                'id' => $this->portfolioId,
                'title' => $this->title
            ]);

            $portfolio = Portfolio::findOrFail($this->portfolioId);
            
            $portfolio->title = $this->title;
            $portfolio->description = $this->description;
            $portfolio->service_category_id = $this->service_category_id;
            $portfolio->project_type_id = $this->project_type_id;
            $portfolio->status = $this->status;

            if (!empty($this->tempImages)) {
                // Delete old images if they exist
                if ($portfolio->image) {
                    ImageHelper::deleteImage($portfolio->image);
                }
                if (!empty($portfolio->additional_images)) {
                    foreach ($portfolio->additional_images as $oldImage) {
                        ImageHelper::deleteImage($oldImage);
                    }
                }

                // Store new images
                $portfolio->image = ImageHelper::storeAndResize(
                    $this->tempImages[0],
                    'portfolios'
                );

                // Store additional images
                if (count($this->tempImages) > 1) {
                    $additionalImages = [];
                    foreach (array_slice($this->tempImages, 1) as $image) {
                        $additionalImages[] = ImageHelper::storeAndResize(
                            $image,
                            'portfolios'
                        );
                    }
                    $portfolio->additional_images = $additionalImages;
                } else {
                    $portfolio->additional_images = [];
                }
            }

            $portfolio->save();

            \Log::info('Portfolio updated successfully', [
                'id' => $this->portfolioId,
                'title' => $this->title
            ]);

            session()->flash('message', 'Portfolio updated successfully.');
            $this->closeModal();
            $this->resetInputFields();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('validation-failed');
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('validation-failed');
            \Log::error('Error updating portfolio', [
                'id' => $this->portfolioId,
                'title' => $this->title,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Error updating portfolio: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            if (!$this->checkPermissionWithMessage('DELETE_PORTFOLIO', 'No tienes permiso para eliminar elementos de portfolio')) {
                return false;
            }
            
            \Log::info('Attempting to delete portfolio', ['id' => $id]);
            
            $portfolio = Portfolio::findOrFail($id);
            
            // Delete main image
            if ($portfolio->image) {
                ImageHelper::deleteImage($portfolio->image);
            }

            // Delete additional images
            if (!empty($portfolio->additional_images)) {
                foreach ($portfolio->additional_images as $image) {
                    ImageHelper::deleteImage($image);
                }
            }

            $portfolio->delete();
            
            \Log::info('Portfolio deleted successfully', ['id' => $id]);
            
            session()->flash('message', 'Portfolio deleted successfully.');
            $this->dispatch('portfolioDeleted');
            return ['success' => true];
        } catch (\Exception $e) {
            \Log::error('Error deleting portfolio', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Error deleting portfolio: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    private function resetInputFields()
    {
        $this->reset([
            'portfolioId', 'title', 'description', 'service_category_id', 'project_type_id', 
            'status', 'images', 'tempImages', 'isSubmitting'
        ]);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function removeImage($index)
    {
        unset($this->images[$index]);
        $this->images = array_values($this->images);
    }

    public function removeTemporaryImage($index)
    {
        unset($this->tempImages[$index]);
        $this->tempImages = array_values($this->tempImages);
    }

    public function handleImageReorder()
    {
        if (!empty($this->tempImages)) {
            $this->tempImages = array_values($this->tempImages);
        } else if (!empty($this->images)) {
            $this->images = array_values($this->images);
        }
    }

    private function clearCache()
    {
        Cache::tags(['portfolios'])->flush();
    }

    public function restore($uuid)
    {
        try {
            if (!$this->checkPermissionWithMessage('RESTORE_PORTFOLIO', 'No tienes permiso para restaurar elementos de portfolio')) {
                return false;
            }
            
            // ... código existente ...
            
        } catch (\Exception $e) {
            // ... manejo de errores ...
        }
    }
} 