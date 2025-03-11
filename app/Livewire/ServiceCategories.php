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

    public $name = '';
    public $type = '';
    public $description = '';
    public $status = 'active';
    public $search = '';
    public $showModal = false;
    public $isEditing = false;
    public $editId;
    
    protected $paginationTheme = 'tailwind';

    protected $rules = [
        'name' => 'required|min:3',
        'type' => 'required|in:Roof Repair,New Roof,Storm Damage,Mold Remediation,Mitigation,Tarp,ReTarp,Rebuild,Roof Paint',
        'description' => 'required|min:10',
        'status' => 'required|in:active,inactive',
    ];

    protected $listeners = [
        'refreshComponent' => '$refresh',
        'delete' => 'deleteConfirmed'
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updated($field)
    {
        $this->validateOnly($field);
    }

    public function openModal()
    {
        $this->resetValidation();
        $this->reset(['name', 'type', 'description', 'isEditing', 'editId']);
        $this->status = 'active';
        $this->showModal = true;
        $this->dispatch('open-modal');
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->dispatch('close-modal');
        $this->resetValidation();
        $this->reset(['name', 'type', 'description', 'isEditing', 'editId']);
        $this->status = 'active';
    }

    public function create()
    {
        $this->validate();

        try {
            ServiceCategory::create([
                'uuid' => Str::uuid(),
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'type' => $this->type,
                'description' => $this->description,
                'status' => $this->status,
                'user_id' => auth()->id(),
            ]);

            session()->flash('message', 'Category created successfully.');
            $this->closeModal();
            $this->dispatch('refreshComponent');
            $this->dispatchBrowserEvent('notification');
        } catch (\Exception $e) {
            session()->flash('error', 'Error creating category: ' . $e->getMessage());
            $this->dispatchBrowserEvent('notification');
        }
    }

    public function edit($id)
    {
        $category = ServiceCategory::findOrFail($id);
        $this->editId = $id;
        $this->name = $category->name;
        $this->type = $category->type;
        $this->description = $category->description;
        $this->status = $category->status;
        $this->isEditing = true;
        $this->showModal = true;
        $this->dispatchBrowserEvent('open-modal');
    }

    public function update()
    {
        $this->validate();

        try {
            $category = ServiceCategory::findOrFail($this->editId);
            $category->update([
                'name' => $this->name,
                'slug' => Str::slug($this->name),
                'type' => $this->type,
                'description' => $this->description,
                'status' => $this->status,
            ]);

            session()->flash('message', 'Category updated successfully.');
            $this->closeModal();
            $this->dispatch('refreshComponent');
            $this->dispatchBrowserEvent('notification');
        } catch (\Exception $e) {
            session()->flash('error', 'Error updating category: ' . $e->getMessage());
            $this->dispatchBrowserEvent('notification');
        }
    }

    public function delete($id)
    {
        $this->dispatchBrowserEvent('confirm-delete', [
            'id' => $id,
            'message' => 'Are you sure you want to delete this category?'
        ]);
    }

    public function deleteConfirmed($data)
    {
        try {
            ServiceCategory::findOrFail($data['id'])->delete();
            session()->flash('message', 'Category deleted successfully.');
            $this->dispatch('refreshComponent');
        } catch (\Exception $e) {
            session()->flash('error', 'Error deleting category. Please try again.');
        }
    }

    public function render()
    {
        $categories = ServiceCategory::query()
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('type', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.service-categories', [
            'categories' => $categories
        ]);
    }
} 