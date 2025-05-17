<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BlogCategory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;
use App\Traits\CacheTrait;
use App\Traits\ChecksPermissions;

class BlogCategories extends Component
{
    use WithPagination;
    use CacheTrait;
    use ChecksPermissions;

    public $uuid;
    public $blog_category_name;
    public $blog_category_description;
    
    public $isOpen = false;
    public $modalTitle = 'Create Blog Category';
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
        'blog_category_name' => 'required|string|min:3|max:100',
        'blog_category_description' => 'nullable|string|max:500',
    ];

    public function mount()
    {
        $this->resetPage();
    }

    public function render()
    {
        if (!$this->checkPermission('READ_BLOG_CATEGORY', true)) {
            return;
        }
        
        $searchTerm = '%' . $this->search . '%';
        
        // Use CacheTrait's generic method
        $cacheKey = $this->generateCacheKey('blog_categories');
        
        $categories = Cache::remember($cacheKey, 300, function () use ($searchTerm) {
            return $this->getCategoriesQuery($searchTerm)->paginate($this->perPage);
        });

        return view('livewire.blog-categories', [
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
        $query = BlogCategory::query();
        
        // Include trashed categories if showDeleted is true
        if ($this->showDeleted) {
            $query->withTrashed();
        }
        
        $query->where(function ($query) use ($searchTerm) {
            $query->where('blog_category_name', 'like', $searchTerm)
                  ->orWhere('blog_category_description', 'like', $searchTerm);
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
        if (!$this->checkPermissionWithMessage('CREATE_BLOG_CATEGORY', 'No tienes permiso para crear categorías de blog')) {
            return;
        }
        
        // Asegúrate de que estos campos se inicializan correctamente
        $this->resetInputFields();
        
        // Configura el modal
        $this->modalTitle = 'Create New Blog Category';
        $this->modalAction = 'store';
        
        // Importante: establece isOpen a true
        $this->isOpen = true;
        
        // Despacha el evento - mantén el mismo formato de evento que usas en ServiceCategories
        $this->dispatch('category-edit', [
            'blog_category_name' => '',
            'blog_category_description' => '',
            'action' => 'store'
        ]);

        // Para debugging
        \Log::info('Modal should open now', ['isOpen' => $this->isOpen]);
    }

    public function store()
    {
        try {
            if (!$this->checkPermissionWithMessage('CREATE_BLOG_CATEGORY', 'No tienes permiso para crear categorías de blog')) {
                return;
            }

            // Update validation rules to include uniqueness check
            $validationRules = $this->rules;
            $validationRules['blog_category_name'] = [
                'required', 
                'string', 
                'min:3', 
                'max:100',
                'unique:blog_categories,blog_category_name,NULL,id,deleted_at,NULL'
            ];

            $this->validate($validationRules);

            \Log::info('Storing blog category with data:', [
                'blog_category_name' => $this->blog_category_name,
                'blog_category_description' => $this->blog_category_description
            ]);

            $category = BlogCategory::create([
                'uuid' => Str::uuid(),
                'blog_category_name' => $this->blog_category_name,
                'blog_category_description' => $this->blog_category_description,
                'user_id' => auth()->id()
            ]);
            
            $this->significantDataChange = true;
            
            // Use the generic method with 'blog_categories' parameter
            $this->clearCache('blog_categories');

            session()->flash('message', 'Blog Category Created Successfully.');
            $this->closeModal();
            $this->resetInputFields();
            $this->dispatch('refreshComponent');
            $this->dispatch('category-created-success');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('validation-failed');
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('validation-failed');
            \Log::error('Error creating blog category', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error creating blog category: ' . $e->getMessage());
            $this->dispatch('category-created-error');
        }
    }

    public function edit($uuid)
    {
        try {
            if (!$this->checkPermissionWithMessage('UPDATE_BLOG_CATEGORY', 'No tienes permiso para editar categorías de blog')) {
                return;
            }
            
            \Log::info('Attempting to edit blog category', ['uuid' => $uuid]);
            
            $category = BlogCategory::where('uuid', $uuid)->firstOrFail();
            $this->uuid = $category->uuid;
            $this->blog_category_name = $category->blog_category_name;
            $this->blog_category_description = $category->blog_category_description;
            
            $this->modalTitle = 'Edit Blog Category';
            $this->modalAction = 'update';
            
            // Use the enhanced openModal method
            $this->openModal();
            
            \Log::info('Blog category data loaded successfully', [
                'uuid' => $this->uuid,
                'blog_category_name' => $this->blog_category_name
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading blog category data', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error loading blog category data: ' . $e->getMessage());
            $this->dispatch('category-edit-error');
        }
    }

    public function update()
    {
        try {
            if (!$this->checkPermissionWithMessage('UPDATE_BLOG_CATEGORY', 'No tienes permiso para actualizar categorías de blog')) {
                return;
            }

            // Use validation with unique rule that ignores current record
            $this->validate([
                'blog_category_name' => [
                    'required', 
                    'string', 
                    'min:3', 
                    'max:100',
                    Rule::unique('blog_categories', 'blog_category_name')
                        ->ignore($this->uuid, 'uuid')
                        ->whereNull('deleted_at')
                ],
                'blog_category_description' => 'nullable|string|max:500',
            ]);

            $category = BlogCategory::where('uuid', $this->uuid)->firstOrFail();
            
            $category->update([
                'blog_category_name' => $this->blog_category_name,
                'blog_category_description' => $this->blog_category_description,
                'user_id' => auth()->id()
            ]);
            
            // Set flag for cache clearing
            $this->significantDataChange = true;

            // Clear cache using the generic method
            $this->clearCache('blog_categories');

            session()->flash('message', 'Blog Category Updated Successfully.');
            $this->closeModal();
            $this->resetInputFields();
            $this->dispatch('refreshComponent');
            $this->dispatch('category-updated-success');

        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('validation-failed');
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('validation-failed');
            \Log::error('Error updating blog category', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error updating blog category: ' . $e->getMessage());
            $this->dispatch('category-updated-error');
        }
    }

    public function delete($uuid)
    {
        try {
            if (!$this->checkPermissionWithMessage('DELETE_BLOG_CATEGORY', 'No tienes permiso para eliminar categorías de blog')) {
                return false;
            }
            
            \Log::info('Attempting to delete blog category', ['uuid' => $uuid]);
            
            // Find the category first to log details
            $category = BlogCategory::where('uuid', $uuid)->first();
            
            if (!$category) {
                \Log::warning('Blog category not found for deletion', ['uuid' => $uuid]);
                session()->flash('error', 'Blog category not found.');
                $this->dispatch('categoryDeleteError', ['message' => 'Blog category not found.']);
                return false;
            }
            
            \Log::info('Found blog category to delete', [
                'uuid' => $uuid,
                'blog_category_name' => $category->blog_category_name,
                'id' => $category->id
            ]);
            
            // Perform the deletion
            $deleted = $category->delete();
            
            \Log::info('Blog category deletion result', [
                'uuid' => $uuid,
                'deleted' => $deleted ? 'success' : 'failed'
            ]);
            
            // Clear cache using the generic method
            $this->clearCache('blog_categories');
            
            session()->flash('message', 'Blog category deleted successfully.');
            $this->dispatch('categoryDeleted');
            return true;
        } catch (\Exception $e) {
            \Log::error('Error deleting blog category', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Error deleting blog category: ' . $e->getMessage());
            $this->dispatch('categoryDeleteError', ['message' => $e->getMessage()]);
            return false;
        }
    }

    public function restore($uuid)
    {
        try {
            if (!$this->checkPermissionWithMessage('RESTORE_BLOG_CATEGORY', 'No tienes permiso para restaurar categorías de blog')) {
                return false;
            }
            
            \Log::info('Attempting to restore blog category', ['uuid' => $uuid]);
            
            // Find the category first to log details
            $category = BlogCategory::withTrashed()->where('uuid', $uuid)->first();
            
            if (!$category) {
                \Log::warning('Blog category not found for restoration', ['uuid' => $uuid]);
                session()->flash('error', 'Blog category not found.');
                $this->dispatch('categoryRestoreError', ['message' => 'Blog category not found.']);
                return false;
            }
            
            \Log::info('Found blog category to restore', [
                'uuid' => $uuid,
                'blog_category_name' => $category->blog_category_name,
                'id' => $category->id
            ]);
            
            // Perform the restoration
            $restored = $category->restore();
            
            \Log::info('Blog category restoration result', [
                'uuid' => $uuid,
                'restored' => $restored ? 'success' : 'failed'
            ]);
            
            // Clear cache using the generic method
            $this->clearCache('blog_categories');
            
            session()->flash('message', 'Blog category restored successfully.');
            $this->dispatch('categoryRestored');
            return true;
        } catch (\Exception $e) {
            \Log::error('Error restoring blog category', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Error restoring blog category: ' . $e->getMessage());
            $this->dispatch('categoryRestoreError', ['message' => $e->getMessage()]);
            return false;
        }
    }

    public function openModal()
    {
        $this->isOpen = true;
        
        // Give Alpine.js a moment to update its state
        $this->dispatch('modal-opened');
        
        // Then send category data with a slight delay to ensure modal is open
        $this->dispatch('category-edit', [
            'blog_category_name' => $this->blog_category_name,
            'blog_category_description' => $this->blog_category_description,
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
        $this->reset(['uuid', 'blog_category_name', 'blog_category_description']);
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

    public function updatedBlogCategoryName()
    {
        // First validate basic requirements
        $this->validateOnly('blog_category_name', [
            'blog_category_name' => 'required|string|min:3|max:100'
        ]);
        
        // Then check uniqueness
        if (!empty($this->blog_category_name) && $this->checkCategoryNameExists($this->blog_category_name)) {
            $this->addError('blog_category_name', 'This category name already exists.');
        }
    }

    public function updatedBlogCategoryDescription()
    {
        $this->validateOnly('blog_category_description');
    }

    public function toggleShowDeleted()
    {
        $this->showDeleted = !$this->showDeleted;
        $this->resetPage();
        $this->clearCache('blog_categories');
    }

    /**
     * Check if a category name already exists in the database
     * 
     * @param string $categoryName
     * @return bool
     */
    public function checkCategoryNameExists($categoryName)
    {
        if (empty($categoryName)) {
            return false;
        }

        // If we're in update mode and the category name hasn't changed, it's valid
        if ($this->modalAction === 'update' && $this->uuid) {
            $category = BlogCategory::where('uuid', $this->uuid)->first();
            if ($category && $category->blog_category_name === $categoryName) {
                return false;
            }
        }

        // Check if category name exists for any other category
        return BlogCategory::where('blog_category_name', $categoryName)
            ->when($this->uuid, function ($query) {
                return $query->where('uuid', '!=', $this->uuid);
            })
            ->whereNull('deleted_at')
            ->exists();
    }
}
