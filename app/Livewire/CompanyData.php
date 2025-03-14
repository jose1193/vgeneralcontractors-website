<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CompanyData as CompanyDataModel;
use Livewire\WithPagination;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Cache;
use App\Traits\CompanyValidation;
use App\Traits\CompanyDataFormatter;
use App\Traits\CompanyCache;
use App\Traits\KeyboardShortcuts;
use Illuminate\Validation\Rule;

class CompanyData extends Component
{
    use WithPagination;
    use CompanyValidation;
    use CompanyDataFormatter;
    use CompanyCache;
    use KeyboardShortcuts;

    public $company_name, $name, $signature_path, $email, $phone, $address, $website, $latitude, $longitude;
    public $uuid, $companyId;
    public $search = '';
    public $perPage = 10;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $page = 1;
    public $isOpen = false;
    public $modalTitle = 'Create Company Data';
    public $modalAction = 'store';
    public $isSubmitting = false;
    public $showDeleted = false; // New property to toggle deleted records

    protected $listeners = [
        'delete' => 'deleteCompany',
        'restore', // Add restore listener
        'refreshComponent' => '$refresh'
    ];

    protected $queryString = [
        'search' => ['except' => ''],
        'page' => ['except' => 1],
        'sortField' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
        'perPage' => ['except' => 10],
        'showDeleted' => ['except' => false] // Add to query string
    ];

    protected $significantDataChange = false;

    protected function rules()
    {
        return $this->getCompanyValidationRules();
    }

    public function mount()
    {
        $this->resetPage();
        $this->mountKeyboardShortcuts();
    }

    public function render()
    {
        $searchTerm = '%' . $this->search . '%';
        $cacheKey = $this->generateCompanyCacheKey();
        
        $companies = Cache::remember($cacheKey, 300, function () use ($searchTerm) {
            return $this->getCompaniesQuery($searchTerm)->paginate($this->perPage);
        });

        return view('livewire.company-data', [
            'companies' => $companies
        ]);
    }

    protected function getCompaniesQuery($searchTerm)
    {
        $query = CompanyDataModel::query();
        
        if ($this->showDeleted) {
            $query->withTrashed(); // Include soft-deleted records
        }

        $query->where(function ($query) use ($searchTerm) {
            $query->where('company_name', 'like', $searchTerm)
                ->orWhere('name', 'like', $searchTerm)
                ->orWhere('email', 'like', $searchTerm);
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
        $this->modalTitle = 'Create New Company';
        $this->modalAction = 'store';
        $this->openModal();
    }

    public function store()
    {
        $this->isSubmitting = true;
        
        try {
            $this->validate($this->getCreateValidationRules());

            \Log::info('Storing company data', [
                'company_name' => $this->company_name,
                'email' => $this->email
            ]);

            $data = [
                'uuid' => Uuid::uuid4()->toString(),
                'company_name' => $this->company_name,
                'name' => $this->name,
                'signature_path' => $this->signature_path,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'website' => $this->website,
                'latitude' => $this->latitude ?: null,
                'longitude' => $this->longitude ?: null,
                'user_id' => auth()->id()
            ];

            $formattedData = $this->formatCompanyData($data);
            $company = CompanyDataModel::create($formattedData);
            
            $this->significantDataChange = true;
            $this->clearCompanyCache();

            session()->flash('message', 'Company Data Created Successfully.');
            $this->closeModal();
            $this->resetInputFields();
            $this->dispatch('refreshComponent');
            $this->dispatch('company-created-success');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->isSubmitting = false;
            $this->dispatch('validation-failed');
            throw $e;
        } catch (\Exception $e) {
            $this->isSubmitting = false;
            \Log::error('Error creating company', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error creating company: ' . $e->getMessage());
            $this->dispatch('company-created-error');
        }
        
        $this->isSubmitting = false;
    }

    public function edit($id)
    {
        try {
            $company = CompanyDataModel::findOrFail($id);
            $this->companyId = $id;
            $this->uuid = $company->uuid;
            $this->company_name = $company->company_name;
            $this->name = $company->name;
            $this->signature_path = $company->signature_path;
            $this->email = $company->email;
            $this->phone = $this->formatPhoneForDisplay($company->phone);
            $this->address = $company->address;
            $this->website = $company->website;
            $this->latitude = $company->latitude;
            $this->longitude = $company->longitude;

            $this->modalTitle = 'Edit Company: ' . $company->company_name;
            $this->modalAction = 'update';
            $this->openModal();

            $this->dispatch('company-edit', [
                'company_name' => $this->company_name,
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'website' => $this->website
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading company data', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error loading company data: ' . $e->getMessage());
            $this->dispatch('company-edit-error');
        }
    }

    public function update()
    {
        $this->isSubmitting = true;
        
        try {
            $this->validate($this->getUpdateValidationRules());

            $company = CompanyDataModel::findOrFail($this->companyId);

            $data = [
                'company_name' => $this->company_name,
                'name' => $this->name,
                'signature_path' => $this->signature_path,
                'email' => $this->email,
                'phone' => $this->phone,
                'address' => $this->address,
                'website' => $this->website,
                'latitude' => $this->latitude ?: null,
                'longitude' => $this->longitude ?: null,
                'user_id' => auth()->id()
            ];

            $formattedData = $this->formatCompanyData($data);
            $company->update($formattedData);

            $this->significantDataChange = true;
            $this->clearCompanyCache();

            session()->flash('message', 'Company Data Updated Successfully.');
            $this->closeModal();
            $this->resetInputFields();
            $this->dispatch('refreshComponent');
            $this->dispatch('company-updated-success');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->isSubmitting = false;
            $this->dispatch('validation-failed');
            throw $e;
        } catch (\Exception $e) {
            $this->isSubmitting = false;
            \Log::error('Error updating company', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error updating company: ' . $e->getMessage());
            $this->dispatch('company-updated-error');
        }
        
        $this->isSubmitting = false;
    }

    public function deleteCompany($id)
    {
        try {
            \Log::info('Attempting to delete company', ['id' => $id]);
            
            $company = CompanyDataModel::findOrFail($id);
            
            \Log::info('Found company to delete', [
                'id' => $id,
                'company_name' => $company->company_name,
                'email' => $company->email
            ]);
            
            $company->delete();
            
            $this->clearCompanyCache();
            session()->flash('message', 'Company deleted successfully.');
            $this->dispatch('companyDeleted');
        } catch (\Exception $e) {
            \Log::error('Error deleting company', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error deleting company: ' . $e->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            \Log::info('Attempting to restore company', ['id' => $id]);
            
            $company = CompanyDataModel::withTrashed()->findOrFail($id);
            
            if (!$company->trashed()) {
                \Log::warning('Company is not deleted', ['id' => $id]);
                session()->flash('error', 'Company is not deleted.');
                return;
            }
            
            \Log::info('Found company to restore', [
                'id' => $id,
                'company_name' => $company->company_name,
                'email' => $company->email
            ]);
            
            $company->restore();
            
            $this->clearCompanyCache();
            session()->flash('message', 'Company restored successfully.');
            $this->dispatch('companyRestored');
        } catch (\Exception $e) {
            \Log::error('Error restoring company', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'Error restoring company: ' . $e->getMessage());
        }
    }

    public function openModal()
    {
        $this->isOpen = true;
        $this->dispatch('company-edit', [
            'company_name' => $this->company_name,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'website' => $this->website,
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
        $this->dispatch('company-edit');
    }

    private function resetInputFields()
    {
        $this->reset([
            'companyId', 'uuid', 'company_name', 'name', 'signature_path',
            'email', 'phone', 'address', 'website', 'latitude', 'longitude',
            'isSubmitting'
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
        $emailRule = ['required', 'string', 'email', 'max:255'];
        if ($this->companyId) {
            $emailRule[] = Rule::unique('company_data', 'email')->ignore($this->companyId);
        } else {
            $emailRule[] = Rule::unique('company_data', 'email');
        }
        $this->validateOnly('email', ['email' => $emailRule]);
    }

    public function updatedPhone()
    {
        if (empty($this->phone)) return;
        
        $phoneRule = ['required', 'string', 'max:20'];
        if ($this->companyId) {
            $phoneRule[] = Rule::unique('company_data', 'phone')->ignore($this->companyId);
        } else {
            $phoneRule[] = Rule::unique('company_data', 'phone');
        }
        $this->validateOnly('phone', ['phone' => $phoneRule]);
    }

    public function toggleShowDeleted()
    {
        $this->showDeleted = !$this->showDeleted;
        $this->resetPage();
        $this->clearCompanyCache();
    }
}