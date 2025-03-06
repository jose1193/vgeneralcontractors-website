<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\EmailData;
use Livewire\WithPagination;
use Ramsey\Uuid\Uuid;
use Illuminate\Validation\Rule;

class EmailDatas extends Component
{
    use WithPagination;

    public $uuid;
    public $description;
    public $email;
    public $phone;
    public $type;
    public $user_id;

    public $isOpen = false;
    public $modalTitle = 'Crear Email';
    public $modalAction = 'store';
    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $listeners = ['delete', 'closeModal'];

    protected function rules()
    {
        return [
            'description' => 'nullable|string',
            'email' => ['required', 'email', 'max:255', 
                Rule::unique('email_data', 'email')->ignore($this->uuid, 'uuid')],
            'phone' => 'nullable|string|max:20',
            'type' => 'nullable|string|max:50',
            'user_id' => 'required|exists:users,id',
        ];
    }

    public function render()
    {
        $emailDatas = EmailData::where(function ($query) {
            $query->where('description', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%')
                ->orWhere('type', 'like', '%' . $this->search . '%');
        })
        ->orderBy($this->sortField, $this->sortDirection)
        ->paginate($this->perPage);

        return view('livewire.email-datas', [
            'emailDatas' => $emailDatas
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
        $this->modalTitle = 'Crear Email';
        $this->modalAction = 'store';
        $this->user_id = auth()->id(); // Asignar el usuario actual por defecto
        $this->openModal();
    }

    public function store()
    {
        $this->validate();

        EmailData::create([
            'uuid' => Uuid::uuid4()->toString(),
            'description' => $this->description,
            'email' => $this->email,
            'phone' => $this->phone,
            'type' => $this->type,
            'user_id' => $this->user_id,
        ]);

        session()->flash('message', 'Email creado exitosamente.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($uuid)
    {
        $emailData = EmailData::where('uuid', $uuid)->firstOrFail();
        
        $this->uuid = $emailData->uuid;
        $this->description = $emailData->description;
        $this->email = $emailData->email;
        $this->phone = $emailData->phone;
        $this->type = $emailData->type;
        $this->user_id = $emailData->user_id;
        
        $this->modalTitle = 'Editar Email';
        $this->modalAction = 'update';
        $this->openModal();
    }

    public function update()
    {
        $this->validate();

        $emailData = EmailData::where('uuid', $this->uuid)->firstOrFail();
        
        $emailData->update([
            'description' => $this->description,
            'email' => $this->email,
            'phone' => $this->phone,
            'type' => $this->type,
            'user_id' => $this->user_id,
        ]);

        session()->flash('message', 'Email actualizado exitosamente.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function confirmDelete($uuid)
    {
        $this->dispatch('confirmDelete', $uuid);
    }

    public function delete($uuid)
    {
        EmailData::where('uuid', $uuid)->delete();
        session()->flash('message', 'Email eliminado exitosamente.');
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
            'uuid', 'description', 'email', 'phone', 'type', 'user_id'
        ]);
        $this->resetErrorBag();
        $this->resetValidation();
    }
} 