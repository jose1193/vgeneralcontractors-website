<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\EmailData;
use App\Models\User;
use Livewire\WithPagination;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Cache;
use App\Traits\EmailValidation;
use App\Traits\EmailDataFormatter;
use App\Traits\EmailCache;
use Illuminate\Validation\Rule;

class EmailDatas extends Component
{
    use WithPagination;
    use EmailValidation;
    use EmailDataFormatter;
    use EmailCache;

    protected $paginationTheme = 'tailwind';

    public $uuid, $description, $email, $phone, $type, $user_id;
    public $isOpen = false;
    public $modalTitle = 'Create Email';
    public $modalAction = 'store';
    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $showDeleted = false;
    public $page = 1;
    public $isSubmitting = false;

    protected $listeners = [
        'delete' => 'deleteEmail',
        'restore' => 'restoreEmail',
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
        return $this->getEmailValidationRules();
    }

    public function mount()
    {
        $this->resetPage();
    }

    public function render()
    {
        $searchTerm = '%' . $this->search . '%';
        $cacheKey = $this->generateEmailCacheKey();

        $emailDatas = Cache::remember($cacheKey, 300, function () use ($searchTerm) {
            return $this->getEmailsQuery($searchTerm)->paginate($this->perPage);
        });

        $users = User::select('id', 'name')->orderBy('name')->get();

        return view('livewire.email-datas', [
            'emailDatas' => $emailDatas,
            'users' => $users
        ]);
    }

    protected function getEmailsQuery($searchTerm)
    {
        $query = EmailData::query();

        if ($this->showDeleted) {
            $query->withTrashed();
        }

        $query->where(function ($query) use ($searchTerm) {
            $query->where('description', 'like', $searchTerm)
                ->orWhere('email', 'like', $searchTerm)
                ->orWhere('type', 'like', $searchTerm)
                ->orWhere('phone', 'like', $searchTerm);
        })->orderBy($this->sortField, $this->sortDirection);

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
        $this->modalTitle = 'Create New Email';
        $this->modalAction = 'store';
        $this->user_id = auth()->id();
        $this->openModal();
    }

    public function store()
    {
        \Log::info('Store method called', ['email' => $this->email]);

        try {
            $this->validate($this->getCreateValidationRules());
            \Log::info('Validation passed');

            $data = [
                'uuid' => Uuid::uuid4()->toString(),
                'description' => $this->description,
                'email' => $this->email,
                'phone' => $this->phone,
                'type' => $this->type,
                'user_id' => $this->user_id
            ];

            $formattedData = $this->formatEmailData($data);
            \Log::info('Formatted data', $formattedData);

            $emailData = EmailData::create($formattedData);
            \Log::info('Email created', ['id' => $emailData->id]);

            $this->significantDataChange = true;
            $this->clearEmailCache();

            session()->flash('message', 'Email Created Successfully.');
            $this->closeModal();
            $this->resetInputFields();
            $this->dispatch('refreshComponent');
            $this->dispatch('email-created-success');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed', ['errors' => $e->errors()]);
            $this->dispatch('validation-failed');
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Error creating email', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error creating email: ' . $e->getMessage());
            $this->dispatch('email-created-error');
        }
    }

    public function edit($uuid)
    {
        try {
            $emailData = EmailData::where('uuid', $uuid)->firstOrFail();
            $this->uuid = $emailData->uuid;
            $this->description = $emailData->description;
            $this->email = $emailData->email;
            $this->phone = $this->formatPhoneForDisplay($emailData->phone);
            $this->type = $emailData->type;
            $this->user_id = $emailData->user_id;

            $this->modalTitle = 'Edit Email: ' . $emailData->email;
            $this->modalAction = 'update';
            $this->openModal();

            $this->dispatch('email-edit', [
                'description' => $this->description,
                'email' => $this->email,
                'phone' => $this->phone,
                'type' => $this->type,
                'user_id' => $this->user_id
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading email data', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error loading email data: ' . $e->getMessage());
            $this->dispatch('email-edit-error');
        }
    }

    public function update()
    {
        try {
            $this->validate($this->getUpdateValidationRules());

            $emailData = EmailData::where('uuid', $this->uuid)->firstOrFail();

            $data = [
                'description' => $this->description,
                'email' => $this->email,
                'phone' => $this->phone,
                'type' => $this->type,
                'user_id' => $this->user_id
            ];

            $formattedData = $this->formatEmailData($data);
            $emailData->update($formattedData);

            $this->significantDataChange = true;
            $this->clearEmailCache();

            session()->flash('message', 'Email Updated Successfully.');
            $this->closeModal();
            $this->resetInputFields();
            $this->dispatch('refreshComponent');
            $this->dispatch('email-updated-success');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('validation-failed');
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Error updating email', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error updating email: ' . $e->getMessage());
            $this->dispatch('email-updated-error');
        }
    }

    public function deleteEmail($uuid)
    {
        try {
            \Log::info('Attempting to delete email', ['uuid' => $uuid]);
            $emailData = EmailData::where('uuid', $uuid)->firstOrFail();
            \Log::info('Found email to delete', [
                'uuid' => $uuid,
                'email' => $emailData->email
            ]);
            $emailData->delete();
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

    public function restoreEmail($uuid)
    {
        try {
            \Log::info('Attempting to restore email', ['uuid' => $uuid]);
            $emailData = EmailData::withTrashed()->where('uuid', $uuid)->firstOrFail();

            if (!$emailData->trashed()) {
                \Log::warning('Email is not deleted', ['uuid' => $uuid]);
                session()->flash('error', 'Email is not deleted.');
                return;
            }

            \Log::info('Found email to restore', [
                'uuid' => $uuid,
                'email' => $emailData->email
            ]);
            $emailData->restore();
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
            'user_id' => $this->user_id,
            'action' => $this->modalAction
        ]);
    }

    public function closeModal()
    {
        $this->isOpen = false;
        if ($this->modalAction !== 'update') {
            $this->resetInputFields();
        }
        $this->resetValidation();
        $this->dispatch('email-edit');
    }

    private function resetInputFields()
    {
        $this->reset([
            'uuid', 'description', 'email', 'phone', 'type', 'user_id', 'isSubmitting'
        ]);
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedEmail()
    {
        $emailRules = ['required', 'email', 'max:255'];
        if ($this->uuid) {
            $emailRules[] = Rule::unique('email_data', 'email')->ignore($this->uuid, 'uuid');
        } else {
            $emailRules[] = Rule::unique('email_data', 'email');
        }
        $this->validateOnly('email', ['email' => $emailRules]);
    }

    public function updatedPhone()
    {
        if (empty($this->phone)) return;

        $phoneRules = ['required', 'string', 'max:20'];
        if ($this->uuid) {
            $phoneRules[] = Rule::unique('email_data', 'phone')->ignore($this->uuid, 'uuid');
        } else {
            $phoneRules[] = Rule::unique('email_data', 'phone');
        }
        $this->validateOnly('phone', ['phone' => $phoneRules]);
    }

    public function toggleShowDeleted()
    {
        $this->showDeleted = !$this->showDeleted;
        $this->resetPage();
        $this->clearEmailCache();
    }
}