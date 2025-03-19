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
use App\Traits\UserCache;

use App\Traits\UserDataFormatter;

class Users extends Component
{
    use WithPagination;
    use UserValidation;
    use UserCache;
   
    use UserDataFormatter;

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
        'refreshComponent' => '$refresh',
        'userDeleteError',
        'userRestoreError'
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
        
    }

    public function render()
    {
        $searchTerm = '%' . $this->search . '%';
        
        // Use a cache key generator from UserCache trait
        $cacheKey = $this->generateUserCacheKey();
        
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
        // Asegurarse de que todos los campos estén limpios
        $this->resetInputFields();
        
        // Establecer el título y la acción del modal
        $this->modalTitle = 'Create New User';
        $this->modalAction = 'store';
        
        // Abrir el modal
        $this->isOpen = true;
        
        // Emitir evento para actualizar Alpine.js
        $this->dispatch('user-edit', [
            'name' => '',
            'last_name' => '',
            'email' => '',
            'username' => '',
            'phone' => '',
            'address' => '',
            'zip_code' => '',
            'city' => '',
            'country' => '',
            'gender' => '',
            'date_of_birth' => '',
            'action' => 'store'
        ]);
    }

    public function store()
    {
        try {
            // Use validation trait
            $this->validate($this->getCreateValidationRules());

            \Log::info('Storing user with data:', [
                'name' => $this->name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'phone' => $this->phone
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
            ];

            // Format data and create user
            $formattedData = $this->formatUserData($data);
            $formattedData['password'] = Hash::make($randomPassword); // Hash the password
            
            $user = User::create($formattedData);
            
            $this->significantDataChange = true;
            
            // Clear cache using the trait
            $this->clearUserCache();

            // Send email using queue job
            dispatch(new SendUserCredentialsEmail($user, $randomPassword, false));

            session()->flash('message', 'User Created Successfully. Credentials will be sent by email.');
            $this->closeModal();
            $this->resetInputFields();
            $this->dispatch('refreshComponent');
            $this->dispatch('user-created-success');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('validation-failed');
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('validation-failed');
            \Log::error('Error creating user', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error creating user: ' . $e->getMessage());
            $this->dispatch('user-created-error');
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
            
            $this->modalTitle = 'Edit User: ' . $user->name . ' ' . $user->last_name;
            $this->modalAction = 'update';
            $this->openModal();
            
            // Dispatch event with user data
            $this->dispatch('user-edit', [
                'name' => $this->name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'username' => $this->username,
                'phone' => $this->phone,
                'address' => $this->address,
                'zip_code' => $this->zip_code,
                'city' => $this->city,
                'country' => $this->country,
                'gender' => $this->gender,
                'date_of_birth' => $this->date_of_birth,
                'action' => 'update'
            ]);
            
            \Log::info('User data loaded successfully', [
                'uuid' => $this->uuid,
                'name' => $this->name,
                'email' => $this->email,
                'username' => $this->username
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
            // Use validation trait
            $validationRules = $this->getUpdateValidationRules();
            
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
            
            // Set flag for cache clearing
            $this->significantDataChange = true;

            // Clear cache
            $this->clearUserCache();

            session()->flash('message', 'User Updated Successfully.');
            $this->closeModal();
            $this->resetInputFields();
            $this->dispatch('refreshComponent');
            $this->dispatch('user-updated-success');

        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('validation-failed');
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('validation-failed');
            \Log::error('Error updating user', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error updating user: ' . $e->getMessage());
            $this->dispatch('user-updated-error');
        }
    }

    public function delete($uuid)
    {
        try {
            \Log::info('Attempting to delete user', ['uuid' => $uuid]);
            
            // Find the user first to log details
            $user = User::where('uuid', $uuid)->first();
            
            if (!$user) {
                \Log::warning('User not found for deletion', ['uuid' => $uuid]);
                session()->flash('error', 'User not found.');
                $this->dispatch('userDeleteError', ['message' => 'User not found.']);
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
            
            // Clear cache
            $this->clearUserCache();
            
            session()->flash('message', 'User deleted successfully.');
            $this->dispatch('userDeleted');
            return true;
        } catch (\Exception $e) {
            \Log::error('Error deleting user', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Error deleting user: ' . $e->getMessage());
            $this->dispatch('userDeleteError', ['message' => $e->getMessage()]);
            return false;
        }
    }

    public function restore($uuid)
    {
        try {
            \Log::info('Attempting to restore user', ['uuid' => $uuid]);
            
            // Find the user first to log details
            $user = User::withTrashed()->where('uuid', $uuid)->first();
            
            if (!$user) {
                \Log::warning('User not found for restoration', ['uuid' => $uuid]);
                session()->flash('error', 'User not found.');
                $this->dispatch('userRestoreError', ['message' => 'User not found.']);
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
            
            // Clear cache
            $this->clearUserCache();
            
            session()->flash('message', 'User restored successfully.');
            $this->dispatch('userRestored');
            return true;
        } catch (\Exception $e) {
            \Log::error('Error restoring user', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Error restoring user: ' . $e->getMessage());
            $this->dispatch('userRestoreError', ['message' => $e->getMessage()]);
            return false;
        }
    }

    public function openModal()
    {
        $this->isOpen = true;
        $this->dispatch('user-edit', [
            'name' => $this->name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'username' => $this->username,
            'phone' => $this->phone,
            'address' => $this->address,
            'zip_code' => $this->zip_code,
            'city' => $this->city,
            'country' => $this->country,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth,
            'action' => $this->modalAction
        ]);
    }

    public function closeModal()
    {
        $this->isOpen = false;
        // Reset fields always when closing the modal
        $this->resetInputFields();
        $this->resetValidation();
        $this->dispatch('user-edit');
    }

    private function resetInputFields()
    {
        $this->reset([
            'uuid', 'name', 'last_name', 'username', 'date_of_birth', 
            'email', 'password', 'password_confirmation', 'phone', 
            'address', 'zip_code', 'city', 'country', 'gender', 
            'profile_photo_path', 'terms_and_conditions', 'latitude', 'longitude',
            'send_password_reset', 'state'
        ]);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    private function clearCache()
    {
        // Use trait method instead
        $this->clearUserCache();
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

    // Add this method for real-time email validation
    public function updatedEmail()
    {
        $emailRule = ['required', 'string', 'email', 'max:255'];
        
        if ($this->uuid) {
            $emailRule[] = Rule::unique('users', 'email')->ignore($this->uuid, 'uuid');
        } else {
            $emailRule[] = Rule::unique('users', 'email');
        }
        
        $this->validateOnly('email', ['email' => $emailRule]);
    }

    // Add this method for real-time phone validation
    public function updatedPhone()
    {
        if (empty($this->phone)) {
            return;
        }
        
        $phoneRule = ['nullable', 'string', 'max:20'];
        
        if ($this->uuid) {
            $phoneRule[] = Rule::unique('users', 'phone')->ignore($this->uuid, 'uuid');
        } else {
            $phoneRule[] = Rule::unique('users', 'phone');
        }
        
        $this->validateOnly('phone', ['phone' => $phoneRule]);
    }

    // Add this method for real-time username validation
    public function updatedUsername()
    {
        $usernameRule = [
            'required', 
            'string', 
            'min:7', 
            'max:255',
            'regex:/^.*[0-9].*[0-9].*$/' // Requires at least 2 numbers
        ];
        
        if ($this->uuid) {
            $usernameRule[] = Rule::unique('users', 'username')->ignore($this->uuid, 'uuid');
        } else {
            $usernameRule[] = Rule::unique('users', 'username');
        }
        
        $this->validateOnly('username', ['username' => $usernameRule]);
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
        $this->clearUserCache();
    }
}