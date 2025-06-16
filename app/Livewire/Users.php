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
use Illuminate\Support\Facades\Cache;
use App\Jobs\SendUserCredentialsEmail;
use App\Traits\UserValidation;
use App\Traits\CacheTrait;
use App\Traits\UserDataFormatter;
use App\Traits\ChecksPermissions;

class Users extends Component
{
    use WithPagination;
    use UserValidation;
    use CacheTrait;
    use UserDataFormatter;
    use ChecksPermissions;

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
    public $send_password_reset = false;
    public $state;
    public $showDeleted = false;
    public $role;
    public $roles = [];

    public $isOpen = false;
    public $modalTitle = 'Create User';
    public $modalAction = 'store';
    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $page = 1;

    protected $listeners = [
        'delete',
        'restore', 
        'closeModal', 
        'refreshComponent' => '$refresh'
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

    protected function rules()
    {
        return $this->getUserValidationRules();
    }

    public function mount()
    {
        $this->resetPage();
        $this->loadRoles();
    }

    /**
     * Load roles for the dropdown
     */
    public function loadRoles()
    {
        // Get all roles from Spatie's Role model
        $this->roles = \Spatie\Permission\Models\Role::pluck('name', 'name')->toArray();
    }

    public function render()
    {
        if (!$this->checkPermission('READ_USER', true)) {
            return; // No continúa si no tiene permiso
        }
        
        $searchTerm = '%' . $this->search . '%';
        
        // Use CacheTrait's generic method instead of UserCache specific method
        $cacheKey = $this->generateCacheKey('users');
        
        $users = Cache::remember($cacheKey, 300, function () use ($searchTerm) {
            return $this->getUsersQuery($searchTerm)->paginate($this->perPage);
        });

        return view('livewire.users', [
            'users' => $users
        ]);
    }
    
    /**
     * Build the users query with appropriate filters
     * 
     * @param string $searchTerm Search term with wildcards
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getUsersQuery($searchTerm)
    {
        $query = User::query();
        
        // Include trashed users if showDeleted is true
        if ($this->showDeleted) {
            $query->withTrashed();
        }
        
        $query->where(function ($query) use ($searchTerm) {
            $query->where('name', 'like', $searchTerm)
                ->orWhere('last_name', 'like', $searchTerm)
                ->orWhere('email', 'like', $searchTerm)
                ->orWhere('username', 'like', $searchTerm)
                ->orWhere('address', 'like', $searchTerm);
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
        if (!$this->checkPermissionWithMessage('CREATE_USER', 'No tienes permiso para crear usuarios')) {
            return;
        }
        
        // Asegurarse de que todos los campos estén limpios
        $this->resetInputFields();
        
        // Establecer el título y la acción del modal
        $this->modalTitle = 'Create New User';
        $this->modalAction = 'store';
        
        // Abrir el modal
        $this->isOpen = true;
    }

    public function store()
    {
        try {
            if (!$this->checkPermissionWithMessage('CREATE_USER', 'No tienes permiso para crear usuarios')) {
                $this->dispatch('validation-failed');
                return;
            }
            
            // Use validation trait
            $validationRules = $this->getCreateValidationRules();
            $validationRules['role'] = 'required|string|exists:roles,name';
            
            $this->validate($validationRules);

            \Log::info('Storing user with data:', [
                'name' => $this->name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'phone' => $this->phone,
                'role' => $this->role
            ]);

            // Generate username from name and last_name
            $baseUsername = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $this->name)) . 
                           strtolower(substr(preg_replace('/[^a-zA-Z0-9]/', '', $this->last_name), 0, 1));
            $username = $this->generateUniqueUsername($baseUsername);
            
            // Generate random password
            $randomPassword = Str::random(12);

            // Prepare user data
            $data = [
                'uuid' => Str::uuid(),
                'name' => $this->name,
                'last_name' => $this->last_name,
                'username' => $username,
                'date_of_birth' => $this->date_of_birth,
                'email' => $this->email,
                'password' => $randomPassword, // Will be hashed in formatUserData
                'phone' => $this->phone,
                'address' => $this->address,
                'zip_code' => $this->zip_code,
                'city' => $this->city,
                'country' => $this->country,
                'gender' => $this->gender,
                'terms_and_conditions' => true,
                'latitude' => null,
                'longitude' => null,
                'state' => $this->state,
                'role' => $this->role,
            ];

            // Format data and create user
            $formattedData = $this->formatUserData($data);
            $formattedData['password'] = Hash::make($randomPassword); // Hash the password
            
            $user = User::create($formattedData);
            
            // Assign the selected role to the user
            $user->assignRole($this->role);
            
            $this->significantDataChange = true;
            
            // Use the generic method with 'users' parameter
            $this->clearCache('users');

            // Send email using queue job
            dispatch(new SendUserCredentialsEmail($user, $randomPassword, false));

            session()->flash('message', 'User Created Successfully. Credentials will be sent by email.');
            $this->closeModal();
            $this->resetInputFields();
            $this->dispatch('refreshComponent');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
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
            // Add 3 random numbers to ensure we have at least 2 numbers
            $randomNumbers = rand(100, 999);
            $username = $baseUsername . $randomNumbers;
            
            // Ensure username is at least 7 characters
            if (strlen($username) < 7) {
                // Add more characters if needed
                $extraChars = 7 - strlen($username);
                $username .= Str::random($extraChars);
            }
            
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
            $username = $baseUsername . rand(100, 999) . Str::random(3);
            
            // Ensure username is at least 7 characters
            if (strlen($username) < 7) {
                $username = $baseUsername . rand(100, 999) . Str::random(3);
                $extraChars = 7 - strlen($username);
                $username .= Str::random($extraChars);
            }
        }
        
        return $username;
    }

    public function edit($uuid)
    {
        try {
            if (!$this->checkPermissionWithMessage('UPDATE_USER', 'No tienes permiso para editar usuarios')) {
                return;
            }
            
            \Log::info('Attempting to edit user', ['uuid' => $uuid]);
            
            $user = User::where('uuid', $uuid)->firstOrFail();
            $this->uuid = $user->uuid;
            $this->name = $user->name;
            $this->last_name = $user->last_name;
            $this->username = $user->username;
            $this->email = $user->email;
            $this->date_of_birth = $user->date_of_birth;
            
            // Format phone number for display
            $this->phone = $this->formatPhoneForDisplay($user->phone);
            
            $this->address = $user->address;
            $this->zip_code = $user->zip_code;
            $this->city = $user->city;
            $this->country = $user->country;
            $this->gender = $user->gender;
            $this->latitude = $user->latitude;
            $this->longitude = $user->longitude;
            $this->state = $user->state;
            
            // Get the user's role
            $this->role = $user->roles->first()->name ?? '';
            
            $this->modalTitle = 'Edit User: ' . $user->name . ' ' . $user->last_name;
            $this->modalAction = 'update';
            $this->isOpen = true;
            
            \Log::info('User data loaded successfully', [
                'uuid' => $this->uuid,
                'name' => $this->name,
                'email' => $this->email,
                'username' => $this->username,
                'role' => $this->role
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading user data', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error loading user data: ' . $e->getMessage());
            $this->dispatch('user-edit-error');
        }
    }

    public function update()
    {
        try {
            if (!$this->checkPermissionWithMessage('UPDATE_USER', 'No tienes permiso para actualizar usuarios')) {
                $this->dispatch('validation-failed');
                return;
            }
            
            // Use validation trait
            $validationRules = $this->getUpdateValidationRules();
            $validationRules['role'] = 'required|string|exists:roles,name';
            
            // Verificar si el teléfono ha cambiado
            $user = User::where('uuid', $this->uuid)->firstOrFail();
            $formattedPhone = '+1' . preg_replace('/[^0-9]/', '', $this->phone);
            
            // Si el teléfono ha cambiado, verificar si ya existe
            if ($formattedPhone !== $user->phone) {
                $phoneExists = User::where('phone', $formattedPhone)
                    ->where('uuid', '!=', $this->uuid)
                    ->exists();
                
                if ($phoneExists) {
                    $this->addError('phone', 'The phone number has already been taken.');
                    $this->dispatch('validation-failed');
                    return;
                }
            }
            
            $this->validate($validationRules);

            $data = [
                'name' => $this->name,
                'last_name' => $this->last_name,
                'username' => $this->username,
                'email' => $this->email,
                'date_of_birth' => $this->date_of_birth,
                'phone' => $this->phone,
                'address' => $this->address,
                'zip_code' => $this->zip_code,
                'city' => $this->city,
                'country' => $this->country,
                'gender' => $this->gender,
                'state' => $this->state,
            ];

            // Format data
            $formattedData = $this->formatUserData($data);

            // Only update password if provided or reset requested
            if ($this->password || $this->send_password_reset) {
                $newPassword = $this->password ?: Str::random(12);
                $formattedData['password'] = Hash::make($newPassword);

                // Send password reset email
                if ($this->send_password_reset) {
                    dispatch(new SendUserCredentialsEmail($user, $newPassword, true));
                }
            }

            $user->update($formattedData);
            
            // Update user role
            $user->syncRoles([$this->role]);
            
            // Set flag for cache clearing
            $this->significantDataChange = true;

            // Clear cache using the generic method
            $this->clearCache('users');

            session()->flash('message', 'User Updated Successfully.');
            $this->closeModal();
            $this->resetInputFields();
            $this->dispatch('refreshComponent');

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Error updating user', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error updating user: ' . $e->getMessage());
        }
    }

    public function delete($uuid)
    {
        try {
            if (!$this->checkPermissionWithMessage('DELETE_USER', 'No tienes permiso para eliminar usuarios')) {
                return false;
            }
            
            \Log::info('Attempting to delete user', ['uuid' => $uuid]);
            
            // Find the user first to log details
            $user = User::where('uuid', $uuid)->first();
            
            if (!$user) {
                \Log::warning('User not found for deletion', ['uuid' => $uuid]);
                session()->flash('error', 'User not found.');
                return false;
            }
            
            \Log::info('Found user to delete', [
                'uuid' => $uuid,
                'name' => $user->name,
                'email' => $user->email,
                'id' => $user->id
            ]);
            
            // Perform the deletion - using the model instance directly to ensure proper deletion
            $deleted = $user->delete();
            
            \Log::info('User deletion result', [
                'uuid' => $uuid,
                'deleted' => $deleted ? 'success' : 'failed'
            ]);
            
            // Clear cache using the generic method
            $this->clearCache('users');
            
            session()->flash('message', 'User deleted successfully.');
            return true;
        } catch (\Exception $e) {
            \Log::error('Error deleting user', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Error deleting user: ' . $e->getMessage());
            return false;
        }
    }

    public function restore($uuid)
    {
        try {
            if (!$this->checkPermissionWithMessage('RESTORE_USER', 'No tienes permiso para restaurar usuarios')) {
                return false;
            }
            
            \Log::info('Attempting to restore user', ['uuid' => $uuid]);
            
            // Find the user first to log details
            $user = User::withTrashed()->where('uuid', $uuid)->first();
            
            if (!$user) {
                \Log::warning('User not found for restoration', ['uuid' => $uuid]);
                session()->flash('error', 'User not found.');
                return false;
            }
            
            \Log::info('Found user to restore', [
                'uuid' => $uuid,
                'name' => $user->name,
                'email' => $user->email,
                'id' => $user->id
            ]);
            
            // Perform the restoration
            $restored = $user->restore();
            
            \Log::info('User restoration result', [
                'uuid' => $uuid,
                'restored' => $restored ? 'success' : 'failed'
            ]);
            
            // Clear cache using the generic method
            $this->clearCache('users');
            
            session()->flash('message', 'User restored successfully.');
            return true;
        } catch (\Exception $e) {
            \Log::error('Error restoring user', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Error restoring user: ' . $e->getMessage());
            return false;
        }
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        
        // Reset fields always when closing the modal
        $this->resetInputFields();
        $this->resetValidation();
    }

    private function resetInputFields()
    {
        $this->reset([
            'uuid', 'name', 'last_name', 'username', 'date_of_birth', 
            'email', 'password', 'password_confirmation', 'phone', 
            'address', 'zip_code', 'city', 'country', 'gender', 
            'profile_photo_path', 'terms_and_conditions', 'latitude', 'longitude',
            'send_password_reset', 'state', 'role'
        ]);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    /**
     * Check if an email already exists in the database
     * 
     * @param string $email
     * @return bool
     */
    public function checkEmailExists($email)
    {
        if (empty($email)) {
            return false;
        }

        // If we're in update mode and the email hasn't changed, it's valid
        if ($this->modalAction === 'update' && $this->uuid) {
            $user = User::where('uuid', $this->uuid)->first();
            if ($user && $user->email === $email) {
                return false;
            }
        }

        // Check if email exists for any other user
        return User::where('email', $email)
            ->when($this->uuid, function ($query) {
                return $query->where('uuid', '!=', $this->uuid);
            })
            ->exists();
    }

    /**
     * Check if a phone already exists in the database
     * 
     * @param string $phone
     * @return bool
     */
    public function checkPhoneExists($phone)
    {
        if (empty($phone)) {
            return false;
        }

        // Format phone for comparison
        $formattedPhone = '+1' . preg_replace('/[^0-9]/', '', $phone);

        // If we're in update mode and the phone hasn't changed, it's valid
        if ($this->modalAction === 'update' && $this->uuid) {
            $user = User::where('uuid', $this->uuid)->first();
            if ($user && $user->phone === $formattedPhone) {
                return false;
            }
        }

        // Check if phone exists for any other user
        return User::where('phone', $formattedPhone)
            ->when($this->uuid, function ($query) {
                return $query->where('uuid', '!=', $this->uuid);
            })
            ->exists();
    }

    // Simplified real-time validation
    public function updatedEmail()
    {
        if (!empty($this->email)) {
            $this->validateOnly('email');
        }
    }

    public function updatedPhone()
    {
        if (!empty($this->phone)) {
            $this->validateOnly('phone');
        }
    }

    public function updatedUsername()
    {
        if (!empty($this->username) && $this->modalAction === 'update') {
            $this->validateOnly('username');
        }
    }

    // Add this method for real-time search updates
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

    /**
     * Check if a username already exists in the database
     * 
     * @param string $username
     * @return bool
     */
    public function checkUsernameExists($username)
    {
        if (empty($username)) {
            return false;
        }

        // If we're in update mode and the username hasn't changed, it's valid
        if ($this->modalAction === 'update' && $this->uuid) {
            $user = User::where('uuid', $this->uuid)->first();
            if ($user && $user->username === $username) {
                return false;
            }
        }

        // Check if username exists for any other user
        return User::where('username', $username)
            ->when($this->uuid, function ($query) {
                return $query->where('uuid', '!=', $this->uuid);
            })
            ->exists();
    }

    public function toggleShowDeleted()
    {
        $this->showDeleted = !$this->showDeleted;
        $this->resetPage();
        $this->clearCache('users');
    }
}