<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ServiceCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;

class ServiceCategories extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $editId;
    public $name = '';
    public $type = '';
    public $description = '';
    public $status = 'active';
    
    public $isOpen = false;
    public $modalTitle = 'Create Category';
    public $modalAction = 'store';
    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $isSubmitting = false;

    // Añade esta propiedad para validación en tiempo real
    protected $validationAttributes = [
        'name' => 'name',
        'type' => 'type',
        'description' => 'description',
        'status' => 'status'
    ];

    protected $listeners = [
        'refreshComponent' => '$refresh'
    ];

    protected function rules()
    {
        $nameRule = 'required|min:3|unique:service_categories,name';
        
        // For edit mode, ignore the current record
        if ($this->editId) {
            $nameRule .= ',' . $this->editId;
        }
        
        return [
            'name' => $nameRule,
            'type' => 'required|in:Roof Repair,New Roof,Storm Damage,Mold Remediation,Mitigation,Tarp,ReTarp,Rebuild,Roof Paint',
            'description' => 'nullable|min:10',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function render()
    {
        $searchTerm = '%' . $this->search . '%';
        
        // Get the current page from the URL
        $currentPage = request()->query('page', 1);
        
        $cacheKey = 'service_categories_' . $this->search . '_' . $this->sortField . '_' . $this->sortDirection . '_' . $this->perPage . '_' . $currentPage;
        
        // Clear cache when changing perPage to ensure fresh results
        if (session()->has('perPage_changed')) {
            Cache::forget($cacheKey);
            session()->forget('perPage_changed');
        }
        
        $categories = Cache::remember($cacheKey, 300, function () use ($searchTerm) {
            return ServiceCategory::where('name', 'like', $searchTerm)
                ->orWhere('type', 'like', $searchTerm)
                ->orWhere('description', 'like', $searchTerm)
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage);
        });

        return view('livewire.service-categories', [
            'categories' => $categories
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
        $this->resetInputFields();
        $this->modalTitle = 'Create Category';
        $this->modalAction = 'store';
        $this->openModal();
    }

    public function store()
    {
        try {
            $this->validate([
                'name' => 'required|min:3|unique:service_categories,name',
                'type' => 'required|in:Roof Repair,New Roof,Storm Damage,Mold Remediation,Mitigation,Tarp,ReTarp,Rebuild,Roof Paint',
                'description' => 'nullable|min:10',
                'status' => 'required|in:active,inactive',
            ]);

            \Log::info('Attempting to save service category', [
                'name' => $this->name,
                'action' => 'create'
            ]);

            // Generate base slug
            $baseSlug = Str::slug($this->name);
            
            // Check if slug exists and make it unique if needed
            $slug = $this->generateUniqueSlug($baseSlug);

            $category = ServiceCategory::create([
                'uuid' => Str::uuid(),
                'name' => $this->name,
                'slug' => $slug,
                'type' => $this->type,
                'description' => $this->description,
                'status' => $this->status,
                'user_id' => auth()->id(),
            ]);

            // Clear cache
            $this->clearCache();

            \Log::info('Service category saved successfully', [
                'name' => $this->name
            ]);

            session()->flash('message', 'Category created successfully.');
            $this->closeModal();
            $this->resetInputFields();
            
            // Replace the full page reload with a component refresh
            $this->dispatch('refreshComponent');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('validation-failed');
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('validation-failed');
            \Log::error('Error saving service category', [
                'name' => $this->name,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Error saving category: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            \Log::info('Attempting to edit category', ['id' => $id]);
            
            $category = ServiceCategory::findOrFail($id);
            
            // Set the Livewire properties
            $this->editId = $id;
            $this->name = $category->name;
            $this->type = $category->type;
            $this->description = $category->description ?? '';
            $this->status = $category->status;
            
            $this->modalTitle = 'Edit Category';
            $this->modalAction = 'update';
            
            // Make sure the modal opens
            $this->openModal();
            
            // Dispatch event to update Alpine.js state
            $this->dispatch('category-edit', [
                'id' => $id,
                'name' => $this->name,
                'type' => $this->type,
                'description' => $this->description,
                'status' => $this->status
            ]);
            
            \Log::info('Category data loaded for editing', [
                'id' => $id, 
                'name' => $category->name,
                'data' => [
                    'name' => $this->name,
                    'type' => $this->type,
                    'description' => $this->description,
                    'status' => $this->status
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading category for edit', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Error loading category data: ' . $e->getMessage());
        }
    }

    public function update()
    {
        try {
            $this->validate([
                'name' => 'required|min:3|unique:service_categories,name,'.$this->editId,
                'type' => 'required|in:Roof Repair,New Roof,Storm Damage,Mold Remediation,Mitigation,Tarp,ReTarp,Rebuild,Roof Paint',
                'description' => 'nullable|min:10',
                'status' => 'required|in:active,inactive',
            ]);

            \Log::info('Attempting to update service category', [
                'id' => $this->editId,
                'name' => $this->name
            ]);

            $category = ServiceCategory::findOrFail($this->editId);
            
            // Generate base slug
            $baseSlug = Str::slug($this->name);
            
            // If name changed, check if new slug exists and make it unique if needed
            if ($category->name !== $this->name) {
                $slug = $this->generateUniqueSlug($baseSlug, $category->id);
            } else {
                $slug = $category->slug;
            }
            
            $category->update([
                'name' => $this->name,
                'slug' => $slug,
                'type' => $this->type,
                'description' => $this->description,
                'status' => $this->status,
            ]);

            // Clear cache
            $this->clearCache();

            \Log::info('Service category updated successfully', [
                'id' => $this->editId,
                'name' => $this->name
            ]);

            session()->flash('message', 'Category updated successfully.');
            $this->closeModal();
            $this->resetInputFields();
            
            // At the end, use dispatch instead of refresh-categories
            $this->dispatch('refreshComponent');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('validation-failed');
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('validation-failed');
            \Log::error('Error updating service category', [
                'id' => $this->editId,
                'name' => $this->name,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Error updating category: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            \Log::info('Attempting to delete category', ['id' => $id]);
            
            $category = ServiceCategory::findOrFail($id);
            $category->delete();
            
            // Clear cache
            $this->clearCache();
            
            \Log::info('Category deleted successfully', ['id' => $id]);
            
            session()->flash('message', 'Category deleted successfully.');
            $this->dispatch('categoryDeleted');
            return ['success' => true];
        } catch (\Exception $e) {
            \Log::error('Error deleting category', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Error deleting category: ' . $e->getMessage());
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
            'editId', 'name', 'type', 'description', 'status', 'isSubmitting'
        ]);
        $this->resetErrorBag();
        $this->resetValidation();
    }
    
    private function clearCache()
    {
        try {
            // Try to use tags if supported
            if (method_exists(Cache::getStore(), 'tags')) {
                Cache::tags(['service_categories'])->flush();
            }
            
            // Clear specific cache keys (works with any driver)
            Cache::forget('service_categories_list');
            
            // Clear any other related caches
            $searchPatterns = [
                'service_categories_*',
                'portfolios_*', // Clear portfolio caches as they depend on categories
            ];
            
            // For file/database cache, we need to manually clear keys that match patterns
            // This is a simplified approach - in production you might want to use a more robust solution
            $currentPage = request()->query('page', 1);
            for ($i = 1; $i <= max(1, $currentPage + 5); $i++) {
                Cache::forget('service_categories_' . $this->search . '_' . $this->sortField . '_' . $this->sortDirection . '_' . $this->perPage . '_' . $i);
            }
            
            // Clear portfolio cache keys
            Cache::forget('portfolios_list');
            Cache::forget('service_categories_list');
            Cache::forget('project_types_list');
            
        } catch (\Exception $e) {
            \Log::error('Error clearing cache', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Generate a unique slug
     * 
     * @param string $baseSlug
     * @param int|null $ignoreId
     * @return string
     */
    private function generateUniqueSlug($baseSlug, $ignoreId = null)
    {
        $slug = $baseSlug;
        $counter = 1;
        
        // Check if slug exists
        while (true) {
            $query = ServiceCategory::where('slug', $slug);
            
            // Ignore current category when updating
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
            
            $exists = $query->exists();
            
            if (!$exists) {
                break;
            }
            
            // Add counter to slug
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }

    // Add this method to handle perPage changes
    public function updatedPerPage()
    {
        // Mark that perPage was changed to clear cache on next render
        session()->put('perPage_changed', true);
        $this->resetPage(); // Reset to first page when changing items per page
    }

    // Añade este método para validación en tiempo real del campo nombre
    public function updatedName()
    {
        $nameRule = 'required|min:3|unique:service_categories,name';
        
        if ($this->editId) {
            $nameRule .= ',' . $this->editId;
        }
        
        $this->validateOnly('name', ['name' => $nameRule]);
    }
} 