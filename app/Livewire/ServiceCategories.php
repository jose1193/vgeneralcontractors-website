<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ServiceCategory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use App\Traits\UserCache;

class ServiceCategories extends Component
{
    use WithPagination;
    use UserCache;

    public $category_id;
    public $uuid;
    public $category;
    public $user_id;
    public $showDeleted = false;

    public $isOpen = false;
    public $modalTitle = 'Create Category';
    public $modalAction = 'store';
    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $page = 1;

    protected $listeners = ['delete', 'restore', 'closeModal', 'refreshComponent' => '$refresh'];

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 10],
        'showDeleted' => ['except' => false]
    ];

    protected $significantDataChange = false;

    protected function rules()
    {
        return [
            'category' => 'required|string|max:255',
        ];
    }

    public function mount()
    {
        $this->resetPage();
    }

    public function render()
    {
        $searchTerm = '%' . $this->search . '%';
        
        // Use a cache key generator from UserCache trait
        $cacheKey = $this->generateCategoryCacheKey();
        
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
        $this->resetInputFields();
        $this->modalTitle = 'Create New Category';
        $this->modalAction = 'store';
        $this->isOpen = true;
        
        $this->dispatch('category-edit', [
            'category' => '',
            'action' => 'store'
        ]);
    }

    public function store()
    {
        try {
            $this->validate();

            \Log::info('Storing category with data:', [
                'category' => $this->category
            ]);

            // Create the category
            ServiceCategory::create([
                'uuid' => Str::uuid(),
                'category' => $this->category,
                'user_id' => auth()->id()
            ]);
            
            $this->significantDataChange = true;
            
            // Clear cache using the trait
            $this->clearCategoryCache();

            session()->flash('message', 'Category Created Successfully.');
            $this->closeModal();
            $this->resetInputFields();
            $this->dispatch('refreshComponent');
            $this->dispatch('category-created');
            $this->dispatch('category-created-success');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('validation-failed');
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('validation-failed');
            \Log::error('Error creating category', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error creating category: ' . $e->getMessage());
            $this->dispatch('category-created-error');
        }
    }

    public function edit($uuid)
    {
        try {
            \Log::info('Attempting to edit category', ['uuid' => $uuid]);
            
            $category = ServiceCategory::where('uuid', $uuid)->firstOrFail();
            $this->category_id = $category->id;
            $this->uuid = $category->uuid;
            $this->category = $category->category;
            
            $this->modalTitle = 'Edit Category';
            $this->modalAction = 'update';
            $this->openModal();
            
            // Dispatch event with category data
            $this->dispatch('category-edit', [
                'category' => $this->category,
                'action' => 'update'
            ]);
            
            \Log::info('Category data loaded successfully', [
                'uuid' => $this->uuid,
                'category' => $this->category
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading category data', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error loading category data: ' . $e->getMessage());
            $this->dispatch('category-edit-error');
        }
    }

    public function update()
    {
        try {
            $this->validate();

            $category = ServiceCategory::where('uuid', $this->uuid)->firstOrFail();
            
            $category->update([
                'category' => $this->category
            ]);
            
            // Set flag for cache clearing
            $this->significantDataChange = true;

            // Clear cache
            $this->clearCategoryCache();

            session()->flash('message', 'Category Updated Successfully.');
            $this->closeModal();
            $this->resetInputFields();
            $this->dispatch('refreshComponent');
            $this->dispatch('category-updated');
            $this->dispatch('category-updated-success');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('validation-failed');
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('validation-failed');
            \Log::error('Error updating category', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error updating category: ' . $e->getMessage());
            $this->dispatch('category-updated-error');
        }
    }

    public function delete($uuid)
    {
        try {
            \Log::info('Attempting to delete category', ['uuid' => $uuid]);
            
            // Find the category first to log details
            $category = ServiceCategory::where('uuid', $uuid)->first();
            
            if (!$category) {
                \Log::warning('Category not found for deletion', ['uuid' => $uuid]);
                session()->flash('error', 'Category not found.');
                return;
            }
            
            \Log::info('Found category to delete', [
                'uuid' => $uuid,
                'category' => $category->category,
                'id' => $category->id
            ]);
            
            // Perform the deletion
            $deleted = $category->delete();
            
            \Log::info('Category deletion result', [
                'uuid' => $uuid,
                'deleted' => $deleted ? 'success' : 'failed'
            ]);
            
            // Clear cache
            $this->clearCategoryCache();
            
            session()->flash('message', 'Category deleted successfully.');
            $this->dispatch('categoryDeleted');
        } catch (\Exception $e) {
            \Log::error('Error deleting category', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Error deleting category: ' . $e->getMessage());
        }
    }

    public function restore($uuid)
    {
        try {
            \Log::info('Attempting to restore category', ['uuid' => $uuid]);
            
            // Find the category first to log details
            $category = ServiceCategory::withTrashed()->where('uuid', $uuid)->first();
            
            if (!$category) {
                \Log::warning('Category not found for restoration', ['uuid' => $uuid]);
                session()->flash('error', 'Category not found.');
                return;
            }
            
            \Log::info('Found category to restore', [
                'uuid' => $uuid,
                'category' => $category->category,
                'id' => $category->id
            ]);
            
            // Perform the restoration
            $restored = $category->restore();
            
            \Log::info('Category restoration result', [
                'uuid' => $uuid,
                'restored' => $restored ? 'success' : 'failed'
            ]);
            
            // Clear cache
            $this->clearCategoryCache();
            
            session()->flash('message', 'Category restored successfully.');
            $this->dispatch('categoryRestored');
        } catch (\Exception $e) {
            \Log::error('Error restoring category', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Error restoring category: ' . $e->getMessage());
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
        if ($this->modalAction !== 'update') {
            $this->resetInputFields();
        }
        $this->resetValidation();
        $this->dispatch('category-edit');
    }

    private function resetInputFields()
    {
        $this->reset([
            'category_id', 'uuid', 'category'
        ]);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    /**
     * Generate a cache key for categories
     * 
     * @return string
     */
    protected function generateCategoryCacheKey()
    {
        return 'service_categories_' . $this->search . '_' . 
               $this->sortField . '_' . $this->sortDirection . '_' . 
               $this->perPage . '_' . $this->page . '_' . 
               ($this->showDeleted ? 'with_deleted' : 'without_deleted');
    }

    /**
     * Clear all category related cache
     */
    protected function clearCategoryCache()
    {
        Cache::forget($this->generateCategoryCacheKey());
        // Clear any other related cache keys
        Cache::forget('all_service_categories');
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

    public function toggleShowDeleted()
    {
        $this->showDeleted = !$this->showDeleted;
        $this->resetPage();
        $this->clearCategoryCache();
    }

    public function updatedCategory()
    {
        $this->validateOnly('category', ['category' => 'required|string|max:255']);
    }
}
