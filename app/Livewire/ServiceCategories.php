<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ServiceCategory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use App\Traits\CacheTrait;
use App\Traits\ChecksPermissions;

class ServiceCategories extends Component
{
    use WithPagination;
    use CacheTrait;
    use ChecksPermissions;

    public $uuid;
    public $category;
    
    public $isOpen = false;
    public $modalTitle = 'Create Service Category';
    public $modalAction = 'store';
    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $page = 1;
    public $showDeleted = false;

    protected $listeners = [
        'delete',
        'restore', 
        'closeModal', 
        'refreshComponent' => '$refresh',
        'categoryDeleteError',
        'categoryRestoreError'
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 10],
        'showDeleted' => ['except' => false]
    ];

    protected $significantDataChange = false;

    protected $rules = [
        'category' => 'required|string|min:3|max:100',
    ];

    public function mount()
    {
        $this->resetPage();
    }

    public function render()
    {
        if (!$this->checkPermission('READ_SERVICE_CATEGORY', true)) {
            return;
        }
        
        $searchTerm = '%' . $this->search . '%';
        
        // Use CacheTrait's generic method
        $cacheKey = $this->generateCacheKey('service_categories');
        
        $categories = Cache::remember($cacheKey, 300, function () use ($searchTerm) {
            return $this->getCategoriesQuery($searchTerm)->paginate($this->perPage);
        });

        return view('livewire.service-categories', [
            'categories' => $categories
        ]);
    }
    
    /**
     * Build the categories query with appropriate filters
     * 
     * @param string $searchTerm Search term with wildcards
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getCategoriesQuery($searchTerm)
    {
        $query = ServiceCategory::query();
        
        // Include trashed categories if showDeleted is true
        if ($this->showDeleted) {
            $query->withTrashed();
        }
        
        $query->where(function ($query) use ($searchTerm) {
            $query->where('category', 'like', $searchTerm);
        })
        ->orderBy($this->sortField, $this->sortDirection);
        
        return $query;
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
        if (!$this->checkPermissionWithMessage('CREATE_SERVICE_CATEGORY', 'No tienes permiso para crear categorías de servicio')) {
            return;
        }
        
        // Make sure all fields are clean
        $this->resetInputFields();
        
        // Set the title and action of the modal
        $this->modalTitle = 'Create New Service Category';
        $this->modalAction = 'store';
        
        // Open the modal
        $this->isOpen = true;
        
        // Emit event to update Alpine.js
        $this->dispatch('category-edit', [
            'category' => '',
            'action' => 'store'
        ]);
    }

    public function store()
    {
        try {
            if (!$this->checkPermissionWithMessage('CREATE_SERVICE_CATEGORY', 'No tienes permiso para crear categorías de servicio')) {
                return;
            }

            $this->validate();

            \Log::info('Storing service category with data:', [
                'category' => $this->category
            ]);

            $category = ServiceCategory::create([
                'uuid' => Str::uuid(),
                'category' => $this->category,
                'user_id' => auth()->id()
            ]);
            
            $this->significantDataChange = true;
            
            // Use the generic method with 'service_categories' parameter
            $this->clearCache('service_categories');

            session()->flash('message', 'Service Category Created Successfully.');
            $this->closeModal();
            $this->resetInputFields();
            $this->dispatch('refreshComponent');
            $this->dispatch('category-created-success');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('validation-failed');
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('validation-failed');
            \Log::error('Error creating service category', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error creating service category: ' . $e->getMessage());
            $this->dispatch('category-created-error');
        }
    }

    public function edit($uuid)
    {
        try {
            if (!$this->checkPermissionWithMessage('UPDATE_SERVICE_CATEGORY', 'No tienes permiso para editar categorías de servicio')) {
                return;
            }
            
            \Log::info('Attempting to edit service category', ['uuid' => $uuid]);
            
            $category = ServiceCategory::where('uuid', $uuid)->firstOrFail();
            $this->uuid = $category->uuid;
            $this->category = $category->category;
            
            $this->modalTitle = 'Edit Service Category';
            $this->modalAction = 'update';
            $this->openModal();
            
            // Dispatch event with category data
            $this->dispatch('category-edit', [
                'category' => $this->category,
                'action' => 'update'
            ]);
            
            \Log::info('Service category data loaded successfully', [
                'uuid' => $this->uuid,
                'category' => $this->category
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading service category data', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error loading service category data: ' . $e->getMessage());
            $this->dispatch('category-edit-error');
        }
    }

    public function update()
    {
        try {
            if (!$this->checkPermissionWithMessage('UPDATE_SERVICE_CATEGORY', 'No tienes permiso para actualizar categorías de servicio')) {
                return;
            }

            $this->validate([
                'category' => [
                    'required', 
                    'string', 
                    'min:3', 
                    'max:100',
                    Rule::unique('service_categories', 'category')
                        ->ignore($this->uuid, 'uuid')
                        ->whereNull('deleted_at')
                ]
            ]);

            $category = ServiceCategory::where('uuid', $this->uuid)->firstOrFail();
            
            $category->update([
                'category' => $this->category,
                'user_id' => auth()->id()
            ]);
            
            // Set flag for cache clearing
            $this->significantDataChange = true;

            // Clear cache using the generic method
            $this->clearCache('service_categories');

            session()->flash('message', 'Service Category Updated Successfully.');
            $this->closeModal();
            $this->resetInputFields();
            $this->dispatch('refreshComponent');
            $this->dispatch('category-updated-success');

        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('validation-failed');
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('validation-failed');
            \Log::error('Error updating service category', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error updating service category: ' . $e->getMessage());
            $this->dispatch('category-updated-error');
        }
    }

    public function delete($uuid)
    {
        try {
            if (!$this->checkPermissionWithMessage('DELETE_SERVICE_CATEGORY', 'No tienes permiso para eliminar categorías de servicio')) {
                return false;
            }
            
            \Log::info('Attempting to delete service category', ['uuid' => $uuid]);
            
            // Find the category first to log details
            $category = ServiceCategory::where('uuid', $uuid)->first();
            
            if (!$category) {
                \Log::warning('Service category not found for deletion', ['uuid' => $uuid]);
                session()->flash('error', 'Service category not found.');
                $this->dispatch('categoryDeleteError', ['message' => 'Service category not found.']);
                return false;
            }
            
            \Log::info('Found service category to delete', [
                'uuid' => $uuid,
                'category' => $category->category,
                'id' => $category->id
            ]);
            
            // Perform the deletion
            $deleted = $category->delete();
            
            \Log::info('Service category deletion result', [
                'uuid' => $uuid,
                'deleted' => $deleted ? 'success' : 'failed'
            ]);
            
            // Clear cache using the generic method
            $this->clearCache('service_categories');
            
            session()->flash('message', 'Service category deleted successfully.');
            $this->dispatch('categoryDeleted');
            return true;
        } catch (\Exception $e) {
            \Log::error('Error deleting service category', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Error deleting service category: ' . $e->getMessage());
            $this->dispatch('categoryDeleteError', ['message' => $e->getMessage()]);
            return false;
        }
    }

    public function restore($uuid)
    {
        try {
            if (!$this->checkPermissionWithMessage('RESTORE_SERVICE_CATEGORY', 'No tienes permiso para restaurar categorías de servicio')) {
                return false;
            }
            
            \Log::info('Attempting to restore service category', ['uuid' => $uuid]);
            
            // Find the category first to log details
            $category = ServiceCategory::withTrashed()->where('uuid', $uuid)->first();
            
            if (!$category) {
                \Log::warning('Service category not found for restoration', ['uuid' => $uuid]);
                session()->flash('error', 'Service category not found.');
                $this->dispatch('categoryRestoreError', ['message' => 'Service category not found.']);
                return false;
            }
            
            \Log::info('Found service category to restore', [
                'uuid' => $uuid,
                'category' => $category->category,
                'id' => $category->id
            ]);
            
            // Perform the restoration
            $restored = $category->restore();
            
            \Log::info('Service category restoration result', [
                'uuid' => $uuid,
                'restored' => $restored ? 'success' : 'failed'
            ]);
            
            // Clear cache using the generic method
            $this->clearCache('service_categories');
            
            session()->flash('message', 'Service category restored successfully.');
            $this->dispatch('categoryRestored');
            return true;
        } catch (\Exception $e) {
            \Log::error('Error restoring service category', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Error restoring service category: ' . $e->getMessage());
            $this->dispatch('categoryRestoreError', ['message' => $e->getMessage()]);
            return false;
        }
    }

    public function openModal()
    {
        $this->isOpen = true;
        $this->dispatch('category-edit', [
            'category' => $this->category,
            'action' => $this->modalAction
        ]);
    }

    public function closeModal()
    {
        $this->isOpen = false;
        // Reset fields always when closing the modal
        $this->resetInputFields();
        $this->resetValidation();
        $this->dispatch('category-edit');
    }

    private function resetInputFields()
    {
        $this->reset(['uuid', 'category']);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updating($name, $value)
    {
        if ($name === 'search') {
            $this->resetPage();
        }
    }

    public function updatedCategory()
    {
        $this->validateOnly('category');
    }

    public function toggleShowDeleted()
    {
        $this->showDeleted = !$this->showDeleted;
        $this->resetPage();
        $this->clearCache('service_categories');
    }
}

