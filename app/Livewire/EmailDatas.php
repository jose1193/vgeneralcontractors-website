<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\EmailData;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Cache;
use App\Traits\PhoneDataFormatter;
use App\Traits\CacheTrait;
use App\Traits\EmailValidation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class EmailDatas extends Component
{
    use WithPagination;
    use PhoneDataFormatter;
    use CacheTrait;
    use EmailValidation;

    // Definir el modelo para validación
    protected $validationModel = EmailData::class;
    
    // Opcionalmente, si la tabla tiene un nombre diferente
    protected $validationTable = 'email_data';

    public $uuid;
    public $description;
    public $email;
    public $phone;
    public $type;
    public $user_id;
    public $showDeleted = false;

    public $isOpen = false;
    public $modalTitle = 'Create Email Data';
    public $modalAction = 'store';
    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';

    protected $listeners = [
        'delete',
        'restore',
        'closeModal',
        'refreshComponent' => '$refresh',
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 10],
        'showDeleted' => ['except' => false],
    ];

    protected $significantDataChange = false;

    public function mount()
    {
        $this->resetPage();
    }

    public function render()
    {
        $searchTerm = '%' . $this->search . '%';
        $cacheKey = $this->generateCacheKey('emaildatas');

        $emailDatas = Cache::remember($cacheKey, 300, function () use ($searchTerm) {
            return $this->getEmailDatasQuery($searchTerm)->paginate($this->perPage);
        });

        return view('livewire.email-datas', [
            'emailDatas' => $emailDatas,
        ]);
    }

    protected function getEmailDatasQuery($searchTerm)
    {
        $query = EmailData::query();

        if ($this->showDeleted) {
            $query->withTrashed();
        }

        $query->where(function ($query) use ($searchTerm) {
            $query->where('description', 'like', $searchTerm)
                ->orWhere('email', 'like', $searchTerm)
                ->orWhere('phone', 'like', $searchTerm)
                ->orWhere('type', 'like', $searchTerm);
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
        $this->modalTitle = 'Create New Email Data';
        $this->modalAction = 'store';
        $this->openModal();
    }

    public function store()
    {
        try {
            $this->validate($this->getCreateValidationRules());

            Log::info('Attempting to create new EmailData', [
                'description' => $this->description,
                'email' => $this->email,
                'phone' => $this->phone,
                'type' => $this->type,
                'user_id' => $this->user_id,
            ]);

            EmailData::create([
                'uuid' => (string) Str::uuid(), // Generar UUID único
                'description' => $this->description,
                'email' => $this->email,
                'phone' => $this->formatPhone($this->phone),
                'type' => $this->type,
                'user_id' => $this->user_id,
            ]);

            $this->significantDataChange = true;
            $this->clearCache('emaildatas');

            Log::info('EmailData created successfully', [
                'description' => $this->description,
                'email' => $this->email,
            ]);

            session()->flash('message', 'Email Data Created Successfully.');
            $this->closeModal();
            $this->resetInputFields();
            $this->dispatch('refreshComponent');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('validation-failed');
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error creating EmailData', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Error creating Email Data: ' . $e->getMessage());
        }
    }

    public function checkEmailExists($email)
    {
        if (empty($email)) {
            return false;
        }

        // If updating and email hasn't changed, it's valid
        if ($this->modalAction === 'update' && $this->uuid) {
            $emailData = EmailData::where('uuid', $this->uuid)->first();
            if ($emailData && $emailData->email === $email) {
                return false;
            }
        }

        // Check if email exists, excluding the current record if updating
        return EmailData::where('email', $email)
            ->when($this->uuid, function ($query) {
                return $query->where('uuid', '!=', $this->uuid);
            })
            ->exists();
    }

    public function checkPhoneExists($phone)
    {
        if (empty($phone)) {
            return false;
        }

        // Si estamos actualizando y el teléfono no ha cambiado, es válido
        if ($this->modalAction === 'update' && $this->uuid) {
            $emailData = EmailData::where('uuid', $this->uuid)->first();
            if ($emailData && $emailData->phone === $this->formatPhone($phone)) {
                return false;
            }
        }

        // Verificar si el teléfono existe, excluyendo el registro actual si estamos actualizando
        return EmailData::where('phone', $this->formatPhone($phone))
            ->when($this->uuid, function ($query) {
                return $query->where('uuid', '!=', $this->uuid);
            })
            ->exists();
    }

    public function edit($uuid)
    {
        try {
            Log::info('Attempting to edit EmailData', ['uuid' => $uuid]);

            $emailData = EmailData::where('uuid', $uuid)->firstOrFail();
            $this->uuid = $emailData->uuid;
            $this->description = $emailData->description;
            $this->email = $emailData->email;
            $this->phone = $this->formatPhoneForDisplay($emailData->phone);
            $this->type = $emailData->type;
            $this->user_id = $emailData->user_id;

            $this->modalTitle = 'Edit Email Data';
            $this->modalAction = 'update';
            $this->openModal();

            Log::info('EmailData loaded for editing', [
                'uuid' => $this->uuid,
                'description' => $this->description,
                'email' => $this->email,
            ]);

            $this->dispatch('email-data-edit', [
                'description' => $this->description,
                'email' => $this->email,
                'phone' => $this->phone,
                'type' => $this->type
            ]);
        } catch (\Exception $e) {
            Log::error('Error loading EmailData for editing', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Error loading Email Data: ' . $e->getMessage());
        }
    }

    public function update()
    {
        try {
            $this->validate($this->getUpdateValidationRules());

            Log::info('Attempting to update EmailData', [
                'uuid' => $this->uuid,
                'description' => $this->description,
                'email' => $this->email,
                'phone' => $this->phone,
                'type' => $this->type,
                'user_id' => $this->user_id,
            ]);

            $emailData = EmailData::where('uuid', $this->uuid)->firstOrFail();
            $emailData->update([
                'description' => $this->description,
                'email' => $this->email,
                'phone' => $this->formatPhone($this->phone),
                'type' => $this->type,
                'user_id' => $this->user_id,
            ]);

            $this->significantDataChange = true;
            $this->clearCache('emaildatas');

            Log::info('EmailData updated successfully', [
                'uuid' => $this->uuid,
                'description' => $this->description,
                'email' => $this->email,
            ]);

            session()->flash('message', 'Email Data Updated Successfully.');
            $this->closeModal();
            $this->resetInputFields();
            $this->dispatch('refreshComponent');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('validation-failed');
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error updating EmailData', [
                'uuid' => $this->uuid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Error updating Email Data: ' . $e->getMessage());
        }
    }

    public function delete($uuid)
    {
        try {
            Log::info('Attempting to delete EmailData', ['uuid' => $uuid]);

            $emailData = EmailData::where('uuid', $uuid)->first();
            if (!$emailData) {
                Log::warning('EmailData not found for deletion', ['uuid' => $uuid]);
                session()->flash('error', 'Email Data not found.');
                return;
            }

            $emailData->delete();
            $this->clearCache('emaildatas');

            Log::info('EmailData deleted successfully', ['uuid' => $uuid]);

            session()->flash('message', 'Email Data Deleted Successfully.');
            $this->dispatch('refreshComponent');
        } catch (\Exception $e) {
            Log::error('Error deleting EmailData', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Error deleting Email Data: ' . $e->getMessage());
        }
    }

    public function restore($uuid)
    {
        try {
            Log::info('Attempting to restore EmailData', ['uuid' => $uuid]);

            $emailData = EmailData::withTrashed()->where('uuid', $uuid)->first();
            if (!$emailData) {
                Log::warning('EmailData not found for restoration', ['uuid' => $uuid]);
                session()->flash('error', 'Email Data not found.');
                return;
            }

            $emailData->restore();
            $this->clearCache('emaildatas');

            Log::info('EmailData restored successfully', ['uuid' => $uuid]);

            session()->flash('message', 'Email Data Restored Successfully.');
            $this->dispatch('refreshComponent');
        } catch (\Exception $e) {
            Log::error('Error restoring EmailData', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            session()->flash('error', 'Error restoring Email Data: ' . $e->getMessage());
        }
    }

    public function openModal()
    {
        $this->isOpen = true;
        
        $this->dispatch('email-data-edit', [
            'description' => $this->description,
            'email' => $this->email,
            'phone' => $this->phone,
            'type' => $this->type
        ]);
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->reset(['uuid', 'description', 'email', 'phone', 'type', 'user_id']);
    }

    public function toggleShowDeleted()
    {
        $this->showDeleted = !$this->showDeleted;
        $this->resetPage();
        $this->clearCache('emaildatas');
    }
}