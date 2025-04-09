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
use App\Traits\ChecksPermissions;
use App\Services\TransactionService;
use Throwable;

class EmailDatas extends Component
{
    use WithPagination;
    use PhoneDataFormatter;
    use CacheTrait;
    use EmailValidation;
    use ChecksPermissions;

    protected TransactionService $transactionService;

    protected $validationModel = EmailData::class;
    
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
        'delete' => 'confirmDelete',
        'restore' => 'confirmRestore',
        'confirmedDeleteEmailData' => 'delete',
        'confirmedRestoreEmailData' => 'restore',
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

    public function boot(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function mount()
    {
        $this->resetPage();
    }

    public function render()
    {
        if (!$this->checkPermission('READ_EMAIL_DATA', true)) {
            return view('livewire.forbidden');
        }
        
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
        $this->clearCache('emaildatas');
        $this->resetPage();
    }

    public function create()
    {
        if (!$this->checkPermissionWithMessage('CREATE_EMAIL_DATA', 'No tienes permiso para crear datos de email')) {
            return;
        }
        
        $this->resetInputFields();
        $this->resetErrorBag();
        $this->modalTitle = 'Create New Email Data';
        $this->modalAction = 'store';
        $this->openModal();
    }

    public function store()
    {
        if (!$this->checkPermissionWithMessage('CREATE_EMAIL_DATA', 'No tienes permiso para crear datos de email')) {
            $this->dispatch('validation-failed');
            return;
        }
        
        $validatedData = $this->validate($this->getCreateValidationRules());

        try {
            $this->transactionService->run(
                function () use ($validatedData) {
                    Log::info('Attempting to create new EmailData within transaction', $validatedData);
                    $emailData = EmailData::create([
                        'uuid' => (string) Str::uuid(),
                        'description' => $this->description,
                        'email' => $this->email,
                        'phone' => $this->formatPhone($this->phone),
                        'type' => $this->type,
                        'user_id' => $this->user_id,
                    ]);
                    Log::info('EmailData created successfully within transaction', ['uuid' => $emailData->uuid]);
                    return $emailData;
                },
                function ($createdEmailData) {
                    $this->clearCache('emaildatas');
                    session()->flash('message', 'Email Data Created Successfully.');
                    $this->closeModal();
                    $this->resetInputFields();
                    $this->dispatch('refreshComponent');
                },
                function (Throwable $e) {
                    Log::error('Error creating EmailData during transaction.', ['error' => $e->getMessage()]);
                    if ($e instanceof \Illuminate\Validation\ValidationException) {
                         $this->dispatch('validation-failed');
                    }
                }
            );
        } catch (Throwable $e) {
            if (!($e instanceof \Illuminate\Validation\ValidationException)) {
                 session()->flash('error', 'Error creating Email Data: ' . $e->getMessage());
            }
        }
    }

    public function checkEmailExists($email)
    {
        if (empty($email)) {
            return false;
        }

        if ($this->modalAction === 'update' && $this->uuid) {
            $emailData = EmailData::where('uuid', $this->uuid)->first();
            if ($emailData && $emailData->email === $email) {
                return false;
            }
        }

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

        if ($this->modalAction === 'update' && $this->uuid) {
            $emailData = EmailData::where('uuid', $this->uuid)->first();
            if ($emailData && $emailData->phone === $this->formatPhone($phone)) {
                return false;
            }
        }

        return EmailData::where('phone', $this->formatPhone($phone))
            ->when($this->uuid, function ($query) {
                return $query->where('uuid', '!=', $this->uuid);
            })
            ->exists();
    }

    public function edit($uuid)
    {
        if (!$this->checkPermissionWithMessage('UPDATE_EMAIL_DATA', 'No tienes permiso para editar datos de email')) {
            return;
        }
        
        try {
            Log::info('Attempting to edit EmailData', ['uuid' => $uuid]);
            $emailData = EmailData::where('uuid', $uuid)->firstOrFail();

            $this->resetErrorBag();
            $this->uuid = $emailData->uuid;
            $this->description = $emailData->description;
            $this->email = $emailData->email;
            $this->phone = $this->formatPhoneForDisplay($emailData->phone);
            $this->type = $emailData->type;
            $this->user_id = $emailData->user_id;

            $this->modalTitle = 'Edit Email Data';
            $this->modalAction = 'update';
            $this->openModal();

            Log::info('EmailData loaded for editing', ['uuid' => $this->uuid]);

            $this->dispatch('email-data-edit', [
                'description' => $this->description,
                'email' => $this->email,
                'phone' => $this->phone,
                'type' => $this->type
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('EmailData not found for editing.', ['uuid' => $uuid]);
            session()->flash('error', 'Email Data not found.');
        } catch (Throwable $e) {
            Log::error('Error loading EmailData for editing', ['uuid' => $uuid, 'error' => $e->getMessage()]);
            session()->flash('error', 'Error loading Email Data: ' . $e->getMessage());
        }
    }

    public function update()
    {
        if (!$this->checkPermissionWithMessage('UPDATE_EMAIL_DATA', 'No tienes permiso para actualizar datos de email')) {
            $this->dispatch('validation-failed');
            return;
        }
        
        $validatedData = $this->validate($this->getUpdateValidationRules());

        try {
             $this->transactionService->run(
                function () use ($validatedData) {
                    Log::info('Attempting to update EmailData within transaction', ['uuid' => $this->uuid]);
                    $emailData = EmailData::where('uuid', $this->uuid)->firstOrFail();
                    $emailData->update([
                        'description' => $this->description,
                        'email' => $this->email,
                        'phone' => $this->formatPhone($this->phone),
                        'type' => $this->type,
                        'user_id' => $this->user_id,
                    ]);
                     Log::info('EmailData updated successfully within transaction', ['uuid' => $this->uuid]);
                    return $emailData;
                },
                function ($updatedEmailData) {
                    $this->clearCache('emaildatas');
                    session()->flash('message', 'Email Data Updated Successfully.');
                    $this->closeModal();
                    $this->resetInputFields();
                     $this->dispatch('refreshComponent');
                },
                function (Throwable $e) {
                    Log::error('Error updating EmailData during transaction.', ['uuid' => $this->uuid, 'error' => $e->getMessage()]);
                     if ($e instanceof \Illuminate\Validation\ValidationException) {
                         $this->dispatch('validation-failed');
                     }
                 }
             );
        } catch (Throwable $e) {
            if (!($e instanceof \Illuminate\Validation\ValidationException)) {
                 session()->flash('error', 'Error updating Email Data: ' . $e->getMessage());
             }
        }
    }

    public function confirmDelete($uuid)
    {
        if (!$this->checkPermissionWithMessage('DELETE_EMAIL_DATA', 'No tienes permiso para eliminar datos de email')) {
            return;
        }
        $this->dispatch('show-confirmation-modal', [
            'title' => 'Confirm Deletion',
            'message' => 'Are you sure you want to move this email data to trash?',
            'confirmEvent' => 'confirmedDeleteEmailData',
            'eventData' => $uuid
        ]);
    }

    public function confirmRestore($uuid)
    {
        if (!$this->checkPermissionWithMessage('RESTORE_EMAIL_DATA', 'No tienes permiso para restaurar datos de email')) {
            return;
        }
        $this->dispatch('show-confirmation-modal', [
            'title' => 'Confirm Restoration',
            'message' => 'Are you sure you want to restore this email data?',
            'confirmEvent' => 'confirmedRestoreEmailData',
            'eventData' => $uuid
        ]);
    }

    public function delete($uuid)
    {
        if (!$this->checkPermissionWithMessage('DELETE_EMAIL_DATA', 'No tienes permiso para eliminar datos de email')) {
            return;
        }

        try {
            $this->transactionService->run(
                function () use ($uuid) {
                    Log::info('Attempting to delete EmailData within transaction', ['uuid' => $uuid]);
                    $emailData = EmailData::where('uuid', $uuid)->firstOrFail();
                    $emailData->delete();
                    Log::info('EmailData deleted successfully within transaction', ['uuid' => $uuid]);
                    return $uuid;
                },
                function ($deletedUuid) {
                    $this->clearCache('emaildatas');
                    session()->flash('message', 'Email Data Deleted Successfully.');
                    $this->dispatch('refreshComponent');
                    $this->resetPage();
                },
                function (Throwable $e) use ($uuid) {
                    Log::error('Error deleting EmailData during transaction.', ['uuid' => $uuid, 'error' => $e->getMessage()]);
                }
            );
        } catch (Throwable $e) {
            session()->flash('error', 'Error deleting Email Data: ' . $e->getMessage());
        }
    }

    public function restore($uuid)
    {
        if (!$this->checkPermissionWithMessage('RESTORE_EMAIL_DATA', 'No tienes permiso para restaurar datos de email')) {
            return;
        }

        try {
            $this->transactionService->run(
                function () use ($uuid) {
                    Log::info('Attempting to restore EmailData within transaction', ['uuid' => $uuid]);
                    $emailData = EmailData::withTrashed()->where('uuid', $uuid)->firstOrFail();
                    $emailData->restore();
                    Log::info('EmailData restored successfully within transaction', ['uuid' => $uuid]);
                    return $uuid;
                },
                function ($restoredUuid) {
                    $this->clearCache('emaildatas');
                    session()->flash('message', 'Email Data Restored Successfully.');
                    $this->dispatch('refreshComponent');
                    $this->resetPage();
                },
                function (Throwable $e) use ($uuid) {
                    Log::error('Error restoring EmailData during transaction.', ['uuid' => $uuid, 'error' => $e->getMessage()]);
                }
            );
        } catch (Throwable $e) {
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
        $this->resetValidation();
    }

    private function resetInputFields()
    {
        $this->reset(['uuid', 'description', 'email', 'phone', 'type', 'user_id']);
        $this->resetErrorBag();
    }

    public function toggleShowDeleted()
    {
        $this->showDeleted = !$this->showDeleted;
        $this->resetPage();
        $this->clearCache('emaildatas');
    }
}