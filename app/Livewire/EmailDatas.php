<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\EmailData;
use Livewire\WithPagination;
use Ramsey\Uuid\Uuid;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;

class EmailDatas extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $uuid;
    public $description;
    public $email;
    public $phone;
    public $type;
    public $user_id;

    public $isOpen = false;
    public $modalTitle = 'Create Email';
    public $modalAction = 'store';
    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $showDeleted = false;
    public $page = 1;

    protected $listeners = [
        'delete', 'restore', 'closeModal', 'refreshComponent' => '$refresh'
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 10],
        'showDeleted' => ['except' => false]
    ];

    protected function rules()
    {
        return [
            'description' => 'nullable|string',
            'email' => ['required', 'email', 'max:255', 
                Rule::unique('email_data', 'email')->ignore($this->uuid, 'uuid')],
            'phone' => ['nullable', 'string', 'max:20',
                Rule::unique('email_data', 'phone')->ignore($this->uuid, 'uuid')],
            'type' => 'required|string|max:50',
            'user_id' => 'required|exists:users,id',
        ];
    }

    protected function messages()
    {
        return [
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email is already taken',
            'phone.unique' => 'This Phone is already taken',
            'type.required' => 'Type is required',
        ];
    }

    public function mount()
    {
        $this->resetPage();
    }

    public function render()
    {
        $searchTerm = '%' . $this->search . '%';
        
        $query = EmailData::query();
        
        // Include trashed emails if showDeleted is true
        if ($this->showDeleted) {
            $query->withTrashed();
        }
        
        $query->where(function ($query) use ($searchTerm) {
            $query->where('description', 'like', $searchTerm)
                ->orWhere('email', 'like', $searchTerm)
                ->orWhere('type', 'like', $searchTerm)
                ->orWhere('phone', 'like', $searchTerm);
        })
        ->orderBy($this->sortField, $this->sortDirection);
        
        // Get the current page from the request
        $currentPage = request()->query('page', 1);
        
        // Cache the results for this specific page
        $cacheKey = 'email_datas_' . $this->search . '_' . $this->sortField . '_' . $this->sortDirection . '_' . $this->perPage . '_' . $currentPage . '_' . ($this->showDeleted ? 'with_deleted' : 'active');
        
        $emailDatas = Cache::remember($cacheKey, 300, function () use ($query) {
            return $query->paginate($this->perPage);
        });

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
        $this->modalTitle = 'Create Email';
        $this->modalAction = 'store';
        $this->user_id = auth()->id(); // Asignar el usuario actual por defecto
        $this->openModal();
    }

    public function store()
    {
        try {
            $this->validate([
                'email' => ['required', 'email', 'max:255', 
                    Rule::unique('email_data', 'email')->ignore($this->uuid, 'uuid')],
                'phone' => ['nullable', 'string', 'max:20',
                    Rule::unique('email_data', 'phone')->ignore($this->uuid, 'uuid')],
            ]);

            \Log::info('Attempting to save email data', [
                'email' => $this->email,
                'action' => 'create'
            ]);

            // Format phone if provided
            $phone = null;
            if (!empty($this->phone)) {
                $phone = $this->formatPhone($this->phone);
            }

            EmailData::create([
                'uuid' => Uuid::uuid4()->toString(),
                'description' => $this->description,
                'email' => $this->email,
                'phone' => $phone,
                'type' => $this->type,
                'user_id' => $this->user_id,
            ]);

            // Clear cache
            $this->clearCache();

            \Log::info('Email data saved successfully', [
                'email' => $this->email
            ]);

            session()->flash('message', 'Email created successfully.');
            $this->closeModal();
            $this->resetInputFields();
            $this->dispatch('refreshComponent');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('validation-failed');
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('validation-failed');
            \Log::error('Error saving email data', [
                'email' => $this->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Error saving email data: ' . $e->getMessage());
        }
    }

    public function edit($uuid)
    {
        try {
            \Log::info('Attempting to edit email', ['uuid' => $uuid]);
            
            $emailData = EmailData::where('uuid', $uuid)->firstOrFail();
            
            $this->uuid = $emailData->uuid;
            $this->description = $emailData->description;
            $this->email = $emailData->email;
            
            // Format phone for display if exists
            if ($emailData->phone) {
                $rawPhone = preg_replace('/[^0-9]/', '', $emailData->phone);
                if (strlen($rawPhone) >= 10) {
                    $rawPhone = substr($rawPhone, -10);
                    $this->phone = sprintf("(%s) %s - %s",
                        substr($rawPhone, 0, 3),
                        substr($rawPhone, 3, 3),
                        substr($rawPhone, 6)
                    );
                } else {
                    $this->phone = $emailData->phone;
                }
            } else {
                $this->phone = '';
            }
            
            $this->type = $emailData->type;
            $this->user_id = $emailData->user_id;
            
            $this->modalTitle = 'Edit Email';
            $this->modalAction = 'update';
            
            $this->openModal();
            
            // Importante: Inicializar los valores del formulario Alpine
            $this->dispatch('email-edit', [
                'description' => $this->description,
                'email' => $this->email,
                'phone' => $this->phone,
                'type' => $this->type,
                'user_id' => $this->user_id
            ]);
            
            \Log::info('Email data loaded for editing', [
                'uuid' => $uuid, 
                'email' => $emailData->email
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading email for edit', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Error loading email data: ' . $e->getMessage());
        }
    }

    public function update()
    {
        try {
            $this->validate([
                'email' => ['required', 'email', 'max:255', 
                    Rule::unique('email_data', 'email')->ignore($this->uuid, 'uuid')],
                'phone' => ['nullable', 'string', 'max:20',
                    Rule::unique('email_data', 'phone')->ignore($this->uuid, 'uuid')],
            ]);

            \Log::info('Attempting to update email data', [
                'uuid' => $this->uuid,
                'email' => $this->email
            ]);

            $emailData = EmailData::where('uuid', $this->uuid)->firstOrFail();
            
            // Format phone if provided
            $phone = null;
            if (!empty($this->phone)) {
                $phone = $this->formatPhone($this->phone);
            }
            
            $emailData->update([
                'description' => $this->description,
                'email' => $this->email,
                'phone' => $phone,
                'type' => $this->type,
                'user_id' => $this->user_id,
            ]);

            // Clear cache
            $this->clearCache();

            \Log::info('Email data updated successfully', [
                'uuid' => $this->uuid,
                'email' => $this->email
            ]);

            session()->flash('message', 'Email updated successfully.');
            $this->closeModal();
            $this->resetInputFields();
            $this->dispatch('refreshComponent');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('validation-failed');
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('validation-failed');
            \Log::error('Error updating email data', [
                'uuid' => $this->uuid,
                'email' => $this->email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Error updating email data: ' . $e->getMessage());
        }
    }

    public function delete($uuid)
    {
        try {
            \Log::info('Attempting to delete email', ['uuid' => $uuid]);
            
            $emailData = EmailData::where('uuid', $uuid)->firstOrFail();
            $emailData->delete();
            
            // Clear cache
            $this->clearCache();
            
            \Log::info('Email deleted successfully', ['uuid' => $uuid]);
            
            session()->flash('message', 'Email deleted successfully.');
            $this->dispatch('emailDeleted');
        } catch (\Exception $e) {
            \Log::error('Error deleting email', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Error deleting email: ' . $e->getMessage());
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
            'uuid', 'description', 'email', 'phone', 'type', 'user_id', 'isSubmitting'
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
        $cacheKey = 'email_datas_' . $this->search . '_' . $this->sortField . '_' . $this->sortDirection . '_' . $this->perPage . '_' . $currentPage . '_' . ($this->showDeleted ? 'with_deleted' : 'active');
        
        Cache::forget($cacheKey);
        
        // Clear count cache
        Cache::forget('email_datas_count_' . $this->search);
        
        // Clear cache for adjacent pages to ensure proper pagination updates
        Cache::forget('email_datas_' . $this->search . '_' . $this->sortField . '_' . $this->sortDirection . '_' . $this->perPage . '_' . ($currentPage - 1) . '_' . ($this->showDeleted ? 'with_deleted' : 'active'));
        Cache::forget('email_datas_' . $this->search . '_' . $this->sortField . '_' . $this->sortDirection . '_' . $this->perPage . '_' . ($currentPage + 1) . '_' . ($this->showDeleted ? 'with_deleted' : 'active'));
    }

    private function formatPhone($phone)
    {
        if (empty($phone)) {
            return null;
        }
        return '+1' . preg_replace('/[^0-9]/', '', $phone);
    }

    public function restore($uuid)
    {
        try {
            \Log::info('Attempting to restore email', ['uuid' => $uuid]);
            
            // Find the email first to log details
            $emailData = EmailData::withTrashed()->where('uuid', $uuid)->first();
            
            if (!$emailData) {
                \Log::warning('Email not found for restoration', ['uuid' => $uuid]);
                session()->flash('error', 'Email not found.');
                return;
            }
            
            \Log::info('Found email to restore', [
                'uuid' => $uuid,
                'email' => $emailData->email,
                'id' => $emailData->id
            ]);
            
            // Perform the restoration
            $restored = $emailData->restore();
            
            \Log::info('Email restoration result', [
                'uuid' => $uuid,
                'restored' => $restored ? 'success' : 'failed'
            ]);
            
            // Clear cache
            $this->clearCache();
            
            session()->flash('message', 'Email restored successfully.');
            $this->dispatch('emailRestored');
        } catch (\Exception $e) {
            \Log::error('Error restoring email', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Error restoring email: ' . $e->getMessage());
        }
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
            $emailData = EmailData::where('uuid', $this->uuid)->first();
            if ($emailData && $emailData->email === $email) {
                return false;
            }
        }

        // Check if email exists for any other email data
        return EmailData::where('email', $email)
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
            $emailData = EmailData::where('uuid', $this->uuid)->first();
            if ($emailData && $emailData->phone === $formattedPhone) {
                return false;
            }
        }

        // Check if phone exists for any other email data
        return EmailData::where('phone', $formattedPhone)
            ->when($this->uuid, function ($query) {
                return $query->where('uuid', '!=', $this->uuid);
            })
            ->exists();
    }

    // Add this method for real-time email validation
    public function updatedEmail()
    {
        $emailRule = ['required', 'email', 'max:255'];
        
        if ($this->uuid) {
            $emailRule[] = Rule::unique('email_data', 'email')->ignore($this->uuid, 'uuid');
        } else {
            $emailRule[] = Rule::unique('email_data', 'email');
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
            $phoneRule[] = Rule::unique('email_data', 'phone')->ignore($this->uuid, 'uuid');
        } else {
            $phoneRule[] = Rule::unique('email_data', 'phone');
        }
        
        $this->validateOnly('phone', ['phone' => $phoneRule]);
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

    public function toggleShowDeleted()
    {
        $this->showDeleted = !$this->showDeleted;
        $this->resetPage();
        $this->clearCache();
    }
} 