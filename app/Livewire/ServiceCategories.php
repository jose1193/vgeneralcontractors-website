<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\ServiceCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

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

    protected $listeners = [
        'refreshComponent' => '$refresh'
    ];

    protected function rules()
    {
        return [
            'name' => 'required|min:3',
            'type' => 'required|in:Roof Repair,New Roof,Storm Damage,Mold Remediation,Mitigation,Tarp,ReTarp,Rebuild,Roof Paint',
            'description' => 'nullable|min:10',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function render()
    {
        $searchTerm = '%' . $this->search . '%';
        $categories = ServiceCategory::where('name', 'like', $searchTerm)
            ->orWhere('type', 'like', $searchTerm)
            ->orWhere('description', 'like', $searchTerm)
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

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
            $this->validate();

            \Log::info('Attempting to save service category', [
                'name' => $this->name,
                'action' => 'create'
            ]);

            ServiceCategory::create([
                'uuid' => Str::uuid(),
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'type' => $this->type,
                'description' => $this->description,
                'status' => $this->status,
                'user_id' => auth()->id(),
            ]);

            \Log::info('Service category saved successfully', [
                'name' => $this->name
            ]);

            session()->flash('message', 'Category created successfully.');
            $this->closeModal();
            $this->resetInputFields();
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
            
            $this->editId = $id;
            $this->name = $category->name;
            $this->type = $category->type;
            $this->description = $category->description ?? '';
            $this->status = $category->status;
            
            $this->modalTitle = 'Edit Category';
            $this->modalAction = 'update';
            
            // Asegurarse de que el modal se abra
            $this->isOpen = true;
            
            // Inicializar los valores del formulario Alpine
            $this->dispatch('category-edit', [
                'name' => $this->name,
                'type' => $this->type,
                'description' => $this->description,
                'status' => $this->status
            ]);
            
            \Log::info('Category data loaded for editing', [
                'id' => $id, 
                'name' => $category->name
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
            $this->validate();

            \Log::info('Attempting to update service category', [
                'id' => $this->editId,
                'name' => $this->name
            ]);

            $category = ServiceCategory::findOrFail($this->editId);
            
            $category->update([
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'type' => $this->type,
                'description' => $this->description,
                'status' => $this->status,
            ]);

            \Log::info('Service category updated successfully', [
                'id' => $this->editId,
                'name' => $this->name
            ]);

            session()->flash('message', 'Category updated successfully.');
            $this->closeModal();
            $this->resetInputFields();
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
} 