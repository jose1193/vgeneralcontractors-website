<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCredentialsMail;

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
        $this->modalTitle = 'Create User';
        $this->modalAction = 'store';
        $this->openModal();
    }

    public function store()
    {
        try {
            $this->validate([
                'name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => ['required', 'string', 'email', 'max:255', 
                    Rule::unique('users', 'email')->ignore($this->uuid, 'uuid')],
            ]);

            // Generate username from name and last_name
            $baseUsername = strtolower($this->name) . strtolower(substr($this->last_name, 0, 1));
            $username = $this->generateUniqueUsername($baseUsername);
            
            // Generate random password
            $randomPassword = Str::random(12);

            $data = [
                'uuid' => Str::uuid(),
                'name' => ucwords(strtolower($this->name)),
                'last_name' => ucwords(strtolower($this->last_name)),
                'username' => $username,
                'date_of_birth' => $this->date_of_birth,
                'email' => $this->email,
                'password' => Hash::make($randomPassword),
                'phone' => preg_replace('/[^0-9]/', '', $this->phone),
                'address' => strtoupper($this->address),
                'zip_code' => $this->zip_code,
                'city' => $this->city,
                'country' => $this->country,
                'gender' => $this->gender,
                'terms_and_conditions' => true,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
            ];

            $user = User::create($data);

            // Enviar correo usando queue
            Mail::to($user->email)->queue(new UserCredentialsMail($user, $randomPassword));

            session()->flash('message', 'User Created Successfully. Credentials will be sent by email.');
            $this->closeModal();
            $this->resetInputFields();
        } catch (\Exception $e) {
            $this->dispatch('validation-failed');
            \Log::error('Error creating user', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Error creating user: ' . $e->getMessage());
        }
    }

    /**
     * Generate a unique username
     * 
     * @param string $baseUsername
     * @return string
     */
    private function generateUniqueUsername($baseUsername)
    {
        $username = $baseUsername;
        $isUnique = false;
        $attempts = 0;
        
        while (!$isUnique && $attempts < 10) {
            // Add 3 random numbers
            $randomNumbers = rand(100, 999);
            $username = $baseUsername . $randomNumbers;
            
            // Check if username exists
            $exists = User::where('username', $username)->exists();
            
            if (!$exists) {
                $isUnique = true;
            }
            
            $attempts++;
        }
        
        // If we couldn't generate a unique username after 10 attempts,
        // add more random characters
        if (!$isUnique) {
            $username = $baseUsername . Str::random(5);
        }
        
        return $username;
    }

    public function update()
    {
        try {
            $this->validate([
                'name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($this->uuid, 'uuid')],
                'password' => 'nullable|min:8|confirmed',
                // ... otras validaciones
            ]);

            \Log::info('Attempting to update user', [
                'uuid' => $this->uuid
            ]);

            $user = User::where('uuid', $this->uuid)->firstOrFail();
            
            $data = [
                'name' => ucwords(strtolower($this->name)),
                'last_name' => ucwords(strtolower($this->last_name)),
                'username' => $user->username, // Keep existing username on update
                'date_of_birth' => $this->date_of_birth,
                'email' => $this->email,
                'phone' => preg_replace('/[^0-9]/', '', $this->phone),
                'address' => strtoupper($this->address),
                'zip_code' => $this->zip_code,
                'city' => $this->city,
                'country' => $this->country,
                'gender' => $this->gender,
                'terms_and_conditions' => true,
                'latitude' => $this->latitude,
                'longitude' => $this->longitude,
            ];

            if ($this->password) {
                $data['password'] = Hash::make($this->password);
            }

            $user->update($data);

            \Log::info('User updated successfully', [
                'uuid' => $this->uuid
            ]);

            session()->flash('message', 'User Updated Successfully.');
            $this->closeModal();
            $this->resetInputFields();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('validation-failed');
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('validation-failed');
            \Log::error('Error updating user', [
                'uuid' => $this->uuid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Error updating user: ' . $e->getMessage());
            session()->flash('error', 'Error saving user data: ' . $e->getMessage());
        }
    }

    public function edit($uuid)
    {
        try {
            \Log::info('Attempting to edit user', ['uuid' => $uuid]);
            
            $user = User::where('uuid', $uuid)->firstOrFail();
            
            $this->uuid = $user->uuid;
            $this->name = $user->name;
            $this->last_name = $user->last_name;
            $this->username = $user->username;
            $this->date_of_birth = $user->date_of_birth;
            $this->email = $user->email;
            
            // Formatear el teléfono
            $rawPhone = preg_replace('/[^0-9]/', '', $user->phone);
            if (strlen($rawPhone) >= 10) {
                $rawPhone = substr($rawPhone, -10);
                $this->phone = sprintf("(%s) %s - %s",
                    substr($rawPhone, 0, 3),
                    substr($rawPhone, 3, 3),
                    substr($rawPhone, 6)
                );
            } else {
                $this->phone = $user->phone;
            }
            
            $this->address = $user->address;
            $this->zip_code = $user->zip_code;
            $this->city = $user->city;
            $this->country = $user->country;
            $this->gender = $user->gender;
            $this->terms_and_conditions = $user->terms_and_conditions;
            $this->latitude = $user->latitude;
            $this->longitude = $user->longitude;
            
            $this->modalTitle = 'Edit User';
            $this->modalAction = 'update';
            
            $this->openModal();
            
            // Make sure to dispatch this event AFTER all properties are set
            $this->dispatch('user-edit');
            
            \Log::info('User data loaded for editing', [
                'uuid' => $uuid
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading user for edit', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Error loading user data: ' . $e->getMessage());
        }
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
