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
    public $isSubmitting = false;

    protected $listeners = [
        'refreshComponent' => '$refresh'
    ];

    protected function rules()
    {
        return [
            'description' => 'nullable|string',
            'email' => ['required', 'email', 'max:255', 
                Rule::unique('email_data', 'email')->ignore($this->uuid, 'uuid')],
            'phone' => 'nullable|string|max:20',
            'type' => 'required|string|max:50',
            'user_id' => 'required|exists:users,id',
        ];
    }

    public function render()
    {
        $searchTerm = '%' . $this->search . '%';
        
        $cacheKey = 'email_datas_' . $this->search . '_' . $this->sortField . '_' . $this->sortDirection . '_' . $this->perPage . '_' . $this->page;
        
        $emailDatas = Cache::remember($cacheKey, 300, function () use ($searchTerm) {
            return EmailData::where('description', 'like', $searchTerm)
                ->orWhere('email', 'like', $searchTerm)
                ->orWhere('type', 'like', $searchTerm)
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage);
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
            $this->validate();

            \Log::info('Attempting to save email data', [
                'uuid' => $this->uuid,
                'email' => $this->email,
                'action' => $this->uuid ? 'update' : 'create'
            ]);

            // Format phone if provided
            $phone = null;
            if (!empty($this->phone)) {
                $phone = preg_replace('/[^0-9]/', '', $this->phone);
                $phone = '+1' . $phone;
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
            $this->validate();

            \Log::info('Attempting to update email data', [
                'uuid' => $this->uuid,
                'email' => $this->email
            ]);

            $emailData = EmailData::where('uuid', $this->uuid)->firstOrFail();
            
            // Format phone if provided
            $phone = null;
            if (!empty($this->phone)) {
                $phone = preg_replace('/[^0-9]/', '', $this->phone);
                $phone = '+1' . $phone;
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
            return ['success' => true];
        } catch (\Exception $e) {
            \Log::error('Error deleting email', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Error deleting email: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
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
        // Clear all email-related caches using tags
        Cache::tags(['email_datas'])->flush();
        
        // Clear any other related caches
        $searchPatterns = [
            'email_datas_*',
        ];
        
        foreach ($searchPatterns as $pattern) {
            $keys = Cache::get($pattern);
            if (is_array($keys)) {
                foreach ($keys as $key) {
                    Cache::forget($key);
                }
            }
        }
    }
} 