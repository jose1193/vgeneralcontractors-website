<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class Users extends Component
{
    use WithPagination;

    public $uuid;
    public $name;
    public $last_name;
    public $username;
    public $date_of_birth;
    public $email;
    public $password;
    public $password_confirmation;
    public $phone;
    public $address;
    public $zip_code;
    public $city;
    public $country;
    public $gender;
    public $profile_photo_path;
    public $terms_and_conditions = false;
    public $latitude;
    public $longitude;

    public $isOpen = false;
    public $modalTitle = 'Crear Usuario';
    public $modalAction = 'store';
    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $listeners = ['delete', 'closeModal'];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => ['required', 'string', 'max:255', 
                Rule::unique('users', 'username')->ignore($this->uuid, 'uuid')],
            'date_of_birth' => 'nullable|date',
            'email' => ['required', 'string', 'email', 'max:255', 
                Rule::unique('users', 'email')->ignore($this->uuid, 'uuid')],
            'password' => $this->modalAction === 'store' 
                ? 'required|string|min:8|confirmed' 
                : 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'gender' => 'nullable|string|in:male,female,other',
            'terms_and_conditions' => 'boolean',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ];
    }

    public function render()
    {
        $users = User::where(function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%')
                ->orWhere('last_name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%')
                ->orWhere('username', 'like', '%' . $this->search . '%');
        })
        ->orderBy($this->sortField, $this->sortDirection)
        ->paginate($this->perPage);

        return view('livewire.users', [
            'users' => $users
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
        $this->modalTitle = 'Crear Usuario';
        $this->modalAction = 'store';
        $this->openModal();
    }

    public function store()
    {
        $this->validate();

        User::create([
            'uuid' => Str::uuid(),
            'name' => $this->name,
            'last_name' => $this->last_name,
            'username' => $this->username,
            'date_of_birth' => $this->date_of_birth,
            'email' => $this->email,
            'password' => Hash::make($this->password),
            'phone' => $this->phone,
            'address' => $this->address,
            'zip_code' => $this->zip_code,
            'city' => $this->city,
            'country' => $this->country,
            'gender' => $this->gender,
            'terms_and_conditions' => $this->terms_and_conditions,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ]);

        session()->flash('message', 'Usuario creado exitosamente.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($uuid)
    {
        $user = User::where('uuid', $uuid)->firstOrFail();
        
        $this->uuid = $user->uuid;
        $this->name = $user->name;
        $this->last_name = $user->last_name;
        $this->username = $user->username;
        $this->date_of_birth = $user->date_of_birth;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->address = $user->address;
        $this->zip_code = $user->zip_code;
        $this->city = $user->city;
        $this->country = $user->country;
        $this->gender = $user->gender;
        $this->terms_and_conditions = $user->terms_and_conditions;
        $this->latitude = $user->latitude;
        $this->longitude = $user->longitude;
        
        $this->modalTitle = 'Editar Usuario';
        $this->modalAction = 'update';
        $this->openModal();
    }

    public function update()
    {
        $this->validate();

        $user = User::where('uuid', $this->uuid)->firstOrFail();
        
        $data = [
            'name' => $this->name,
            'last_name' => $this->last_name,
            'username' => $this->username,
            'date_of_birth' => $this->date_of_birth,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'zip_code' => $this->zip_code,
            'city' => $this->city,
            'country' => $this->country,
            'gender' => $this->gender,
            'terms_and_conditions' => $this->terms_and_conditions,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
        ];
        
        if ($this->password) {
            $data['password'] = Hash::make($this->password);
        }
        
        $user->update($data);

        session()->flash('message', 'Usuario actualizado exitosamente.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function confirmDelete($uuid)
    {
        $this->dispatch('confirmDelete', $uuid);
    }

    public function delete($uuid)
    {
        User::where('uuid', $uuid)->delete();
        session()->flash('message', 'Usuario eliminado exitosamente.');
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
            'uuid', 'name', 'last_name', 'username', 'date_of_birth', 
            'email', 'password', 'password_confirmation', 'phone', 
            'address', 'zip_code', 'city', 'country', 'gender', 
            'profile_photo_path', 'terms_and_conditions', 'latitude', 'longitude'
        ]);
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
