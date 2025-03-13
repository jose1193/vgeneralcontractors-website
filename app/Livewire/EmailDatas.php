<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\EmailData;
use Livewire\WithPagination;
use Ramsey\Uuid\Uuid;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cache;
use App\Traits\EmailValidation;
use App\Traits\EmailCache;
use App\Traits\KeyboardShortcuts;
use App\Traits\EmailDataFormatter;

class EmailDatas extends Component
{
    use WithPagination;
    use EmailValidation;
    use EmailCache;
    use KeyboardShortcuts;
    use EmailDataFormatter;

    protected $paginationTheme = 'tailwind';

    public $uuid;
    public $description;
    public $email;
    public $phone;
    public $type;
    public $user_id;
    public $isEditing = false;

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
        return $this->getEmailValidationRules();
    }

    public function mount()
    {
        $this->resetPage();
        $this->mountKeyboardShortcuts();
    }

    public function render()
    {
        $searchTerm = '%' . $this->search . '%';
        
        // Use a cache key generator from EmailCache trait
        $cacheKey = $this->generateEmailCacheKey();
        
        $emailDatas = Cache::remember($cacheKey, 300, function () use ($searchTerm) {
            return $this->getEmailDatasQuery($searchTerm)->paginate($this->perPage);
        });

        return view('livewire.email-datas', [
            'emailDatas' => $emailDatas,
            'users' => \App\Models\User::orderBy('name')->get()
        ]);
    }

    /**
     * Build the email datas query with appropriate filters
     * 
     * @param string $searchTerm Search term with wildcards
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function getEmailDatasQuery($searchTerm)
    {
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
        $this->resetInputFields();
        $this->modalTitle = 'Create Email';
        $this->modalAction = 'store';
        $this->isEditing = false;
        $this->user_id = auth()->id(); // Default to current user
        $this->openModal();
    }

    public function store()
    {
        try {
            // Use validation trait
            $this->validate($this->getCreateValidationRules());

            \Log::info('Attempting to store email data', [
                'email' => $this->email,
                'action' => 'create'
            ]);

            // Format data using trait method
            $data = [
                'uuid' => Uuid::uuid4()->toString(),
                'description' => $this->description,
                'email' => $this->email,
                'phone' => $this->phone,
                'type' => $this->type,
                'user_id' => $this->user_id,
            ];

            // Format data and create email
            $formattedData = $this->formatEmailData($data);
            
            EmailData::create($formattedData);

            // Clear cache using the trait
            $this->clearEmailCache();

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
            
            // Format phone for display
            $this->phone = $this->formatPhoneForDisplay($emailData->phone);
            
            $this->type = $emailData->type;
            $this->user_id = $emailData->user_id;
            
            $this->modalTitle = 'Edit Email';
            $this->modalAction = 'update';
            $this->isEditing = true;
            
            $this->openModal();
            
            // Dispatch event with email data
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
            // Use validation trait
            $this->validate($this->getUpdateValidationRules());

            \Log::info('Attempting to update email data', [
                'uuid' => $this->uuid,
                'email' => $this->email
            ]);

            $emailData = EmailData::where('uuid', $this->uuid)->firstOrFail();
            
            $data = [
                'description' => $this->description,
                'email' => $this->email,
                'phone' => $this->phone,
                'type' => $this->type,
                'user_id' => $this->user_id,
            ];
            
            // Format data using trait
            $formattedData = $this->formatEmailData($data);
            
            $emailData->update($formattedData);

            // Clear cache using the trait
            $this->clearEmailCache();

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
            
            // Find the email first to log details
            $emailData = EmailData::where('uuid', $uuid)->first();
            
            if (!$emailData) {
                \Log::warning('Email not found for deletion', ['uuid' => $uuid]);
                session()->flash('error', 'Email not found.');
                return;
            }
            
            \Log::info('Found email to delete', [
                'uuid' => $uuid,
                'email' => $emailData->email,
                'id' => $emailData->id
            ]);
            
            // Perform the deletion
            $deleted = $emailData->delete();
            
            \Log::info('Email deletion result', [
                'uuid' => $uuid,
                'deleted' => $deleted ? 'success' : 'failed'
            ]);
            
            // Clear cache using the trait
            $this->clearEmailCache();
            
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
            
            // Clear cache using the trait
            $this->clearEmailCache();
            
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

    public function openModal()
    {
        $this->isOpen = true;
        $this->dispatch('email-edit', [
            'description' => $this->description,
            'email' => $this->email,
            'phone' => $this->phone,
            'type' => $this->type,
            'user_id' => $this->user_id
        ]);
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetValidation();
    }

    private function resetInputFields()
    {
        $this->reset([
            'uuid', 'description', 'email', 'phone', 'type', 'user_id', 'isEditing'
        ]);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function toggleShowDeleted()
    {
        $this->showDeleted = !$this->showDeleted;
        $this->resetPage();
        $this->clearEmailCache();
    }

    public function formatPhoneForDisplay($phone)
    {
        if (empty($phone)) return '';
        $numbers = preg_replace('/[^0-9]/', '', $phone);
        
        // Handle numbers with country code
        if (strlen($numbers) === 11 && str_starts_with($numbers, '1')) {
            $numbers = substr($numbers, 1);
        }
        
        if (strlen($numbers) === 10) {
            return '(' . substr($numbers, 0, 3) . ') ' . substr($numbers, 3, 3) . '-' . substr($numbers, 6, 4);
        }
        return $phone;
    }
} 