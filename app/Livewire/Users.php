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
    public $send_password_reset = false;
    public $state;

    public $isOpen = false;
    public $modalTitle = 'Create User';
    public $modalAction = 'store';
    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $page = 1;

    protected $listeners = ['delete', 'closeModal', 'refreshComponent' => '$refresh'];

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 10]
    ];

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => ['required', 'string', 'min:7', 'max:255', 'regex:/^.*[0-9].*[0-9].*$/',
                Rule::unique('users', 'username')->ignore($this->uuid, 'uuid')],
            'date_of_birth' => 'nullable|date',
            'email' => ['required', 'string', 'email', 'max:255', 
                Rule::unique('users', 'email')->ignore($this->uuid, 'uuid')],
            'password' => $this->modalAction === 'store' 
                ? 'required|string|min:8|confirmed' 
                : 'nullable|string|min:8|confirmed',
            'phone' => ['nullable', 'string', 'max:20',
                Rule::unique('users', 'phone')->ignore($this->uuid, 'uuid')],
            'address' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'gender' => 'nullable|string|in:male,female,other',
            'terms_and_conditions' => 'boolean',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'state' => 'nullable|string|max:100',
        ];
    }

    public function mount()
    {
        $this->resetPage();
    }

    public function render()
    {
        $searchTerm = '%' . $this->search . '%';
        
        $query = User::where(function ($query) use ($searchTerm) {
            $query->where('name', 'like', $searchTerm)
                ->orWhere('last_name', 'like', $searchTerm)
                ->orWhere('email', 'like', $searchTerm)
                ->orWhere('username', 'like', $searchTerm)
                ->orWhere('address', 'like', $searchTerm);
        })
        ->orderBy($this->sortField, $this->sortDirection);
        
        // Get the current page from the request
        $currentPage = request()->query('page', 1);
        
        // Cache the results for this specific page
        $cacheKey = 'users_' . $this->search . '_' . $this->sortField . '_' . $this->sortDirection . '_' . $this->perPage . '_' . $currentPage;
        
        $users = Cache::remember($cacheKey, 300, function () use ($query) {
            return $query->paginate($this->perPage);
        });

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
        $this->modalTitle = 'Create New User';
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
                'phone' => ['nullable', 'string', 'max:20',
                    Rule::unique('users', 'phone')->ignore($this->uuid, 'uuid')],
            ]);

            \Log::info('Storing user with data:', [
                'name' => $this->name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'phone' => $this->phone
            ]);

            // Capitalize name, last_name and address
            $name = ucwords(strtolower($this->name));
            $lastName = ucwords(strtolower($this->last_name));
            $address = ucwords(strtolower($this->address));

            // Generate username from name and last_name
            $baseUsername = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $name)) . 
                           strtolower(substr(preg_replace('/[^a-zA-Z0-9]/', '', $lastName), 0, 1));
            $username = $this->generateUniqueUsername($baseUsername);
            
            // Generate random password
            $randomPassword = Str::random(12);

            // Format phone number
            $phone = '';
            if ($this->phone) {
                $phone = '+1' . preg_replace('/[^0-9]/', '', $this->phone);
            }

            $data = [
                'uuid' => Str::uuid(),
                'name' => $name,
                'last_name' => $lastName,
                'username' => $username,
                'date_of_birth' => $this->date_of_birth,
                'email' => $this->email,
                'password' => Hash::make($randomPassword),
                'phone' => $phone,
                'address' => $address,
                'zip_code' => $this->zip_code,
                'city' => ucwords(strtolower($this->city)),
                'country' => ucwords(strtolower($this->country)),
                'gender' => $this->gender,
                'terms_and_conditions' => true,
                'latitude' => null,
                'longitude' => null,
                'state' => ucwords(strtolower($this->state)),
            ];

            $user = User::create($data);

            // Clear cache
            $this->clearCache();

            // Send email using queue job
            dispatch(new SendUserCredentialsEmail($user, $randomPassword, false));

            session()->flash('message', 'User Created Successfully. Credentials will be sent by email.');
            $this->closeModal();
            $this->resetInputFields();
            $this->dispatch('refreshComponent');
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
            if ($user->phone) {
                $rawPhone = preg_replace('/[^0-9]/', '', $user->phone);
                if (strlen($rawPhone) >= 10) {
                    $rawPhone = substr($rawPhone, -10);
                    $this->phone = sprintf("(%s) %s-%s",
                        substr($rawPhone, 0, 3),
                        substr($rawPhone, 3, 3),
                        substr($rawPhone, 6)
                    );
                } else {
                    $this->phone = $user->phone;
                }
            }
            
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
                'date_of_birth' => $this->date_of_birth
            ]);
            
            \Log::info('User data loaded successfully', [
                'uuid' => $this->uuid,
                'name' => $this->name,
                'email' => $this->email
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading user data', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error loading user data: ' . $e->getMessage());
        }
    }

    public function update()
    {
        try {
            $this->validate([
                'name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => ['required', 'string', 'email', 'max:255', 
                    Rule::unique('users', 'email')->ignore($this->uuid, 'uuid')],
                'username' => ['required', 'string', 'min:7', 'max:255', 'regex:/^.*[0-9].*[0-9].*$/',
                    Rule::unique('users', 'username')->ignore($this->uuid, 'uuid')],
                'date_of_birth' => 'nullable|date',
                'phone' => ['nullable', 'string', 'max:20',
                    Rule::unique('users', 'phone')->ignore($this->uuid, 'uuid')],
                'address' => 'nullable|string|max:255',
                'zip_code' => 'nullable|string|max:20',
                'city' => 'nullable|string|max:100',
                'country' => 'nullable|string|max:100',
                'gender' => 'nullable|string|in:male,female,other',
            ]);

            $user = User::where('uuid', $this->uuid)->firstOrFail();

            $data = [
                'name' => ucwords(strtolower($this->name)),
                'last_name' => ucwords(strtolower($this->last_name)),
                'username' => $this->username,
                'email' => $this->email,
                'date_of_birth' => $this->date_of_birth,
                'phone' => $this->formatPhone($this->phone),
                'address' => strtoupper($this->address),
                'zip_code' => $this->zip_code,
                'city' => ucwords(strtolower($this->city)),
                'country' => ucwords(strtolower($this->country)),
                'gender' => $this->gender,
                'state' => ucwords(strtolower($this->state)),
            ];

            // Only update password if provided or reset requested
            if ($this->password || $this->send_password_reset) {
                $newPassword = $this->password ?: Str::random(12);
                $data['password'] = Hash::make($newPassword);

                // Send password reset email
                if ($this->send_password_reset) {
                    dispatch(new SendUserCredentialsEmail($user, $newPassword, true));
                }
            }

            $user->update($data);

            // Clear cache
            $this->clearCache();

            session()->flash('message', 'User Updated Successfully.');
            $this->closeModal();
            $this->resetInputFields();
            $this->dispatch('refreshComponent');

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
        }
    }

    private function formatPhone($phone)
    {
        if (empty($phone)) {
            return null;
        }
        return '+1' . preg_replace('/[^0-9]/', '', $phone);
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
                return;
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
            $this->clearCache();
            
            session()->flash('message', 'User deleted successfully.');
            $this->dispatch('userDeleted');
        } catch (\Exception $e) {
            \Log::error('Error deleting user', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Error deleting user: ' . $e->getMessage());
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
        // Only reset fields when not in edit mode to preserve data
        if ($this->modalAction !== 'update') {
            $this->resetInputFields();
        }
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
        // Get the current page
        $currentPage = request()->query('page', 1);
        
        // Get search term for cache keys
        $searchTerm = '%' . $this->search . '%';
        
        // Clear cache for the current page
        $cacheKey = 'users_' . $this->search . '_' . $this->sortField . '_' . $this->sortDirection . '_' . $this->perPage . '_' . $currentPage;
        Cache::forget($cacheKey);
        
        // Clear count cache
        Cache::forget('users_count_' . $this->search);
        
        // Clear cache for adjacent pages to ensure proper pagination updates
        Cache::forget('users_' . $this->search . '_' . $this->sortField . '_' . $this->sortDirection . '_' . $this->perPage . '_' . ($currentPage - 1));
        Cache::forget('users_' . $this->search . '_' . $this->sortField . '_' . $this->sortDirection . '_' . $this->perPage . '_' . ($currentPage + 1));
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
}