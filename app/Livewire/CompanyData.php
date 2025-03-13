<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CompanyData as CompanyDataModel;
use Livewire\WithPagination;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Cache;
use App\Traits\CompanyValidation;
use App\Traits\CompanyCache;
use App\Traits\CompanyDataFormatter;

class CompanyData extends Component
{
    use WithPagination;
    use CompanyValidation;
    use CompanyCache;
    use CompanyDataFormatter;

    protected $paginationTheme = 'tailwind';

    public $company_name, $name, $signature_path, $email, $phone, $address, $website, $latitude, $longitude;
    public $isOpen = false;
    public $modalTitle = 'Create Company Data';
    public $companyId;
    public $search = '';
    public $isSubmitting = false;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 10;

    protected $listeners = [
        'refreshComponent' => '$refresh'
    ];

    public function render()
    {
        $searchTerm = '%' . $this->search . '%';
        
        // Use the cache key generator from the trait
        $cacheKey = $this->generateCompanyCacheKey();
        
        $companies = Cache::remember($cacheKey, 300, function () use ($searchTerm) {
            return CompanyDataModel::where('company_name', 'like', $searchTerm)
                ->orWhere('name', 'like', $searchTerm)
                ->orWhere('email', 'like', $searchTerm)
                ->orderBy($this->sortField, $this->sortDirection)
                ->paginate($this->perPage);
        });
        
        return view('livewire.company-data', [
            'companies' => $companies
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
        $this->openModal();
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
        $this->company_name = '';
        $this->name = '';
        $this->signature_path = '';
        $this->email = '';
        $this->phone = '';
        $this->address = '';
        $this->website = '';
        $this->latitude = '';
        $this->longitude = '';
        $this->companyId = '';
        $this->isSubmitting = false;
    }

    public function store()
    {
        try {
            // Use validation trait
            $this->validate($this->getCompanyValidationRules());

            \Log::info('Attempting to save company data', [
                'company_id' => $this->companyId,
                'company_name' => $this->company_name,
                'action' => $this->companyId ? 'update' : 'create'
            ]);

            // Prepare data
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

            // Add UUID if creating a new company
            if (!$this->companyId) {
                $data['uuid'] = Uuid::uuid4()->toString();
            }

            // Format data using trait
            $formattedData = $this->formatCompanyData($data);
            
            CompanyDataModel::updateOrCreate(
                ['id' => $this->companyId],
                $formattedData
            );

            // Clear cache using trait
            $this->clearCompanyCache();

            \Log::info('Company data saved successfully', [
                'company_id' => $this->companyId,
                'company_name' => $this->company_name
            ]);

            session()->flash('message', 
                $this->companyId ? 'Company Data Updated Successfully.' : 'Company Data Created Successfully.');

            $this->closeModal();
            $this->resetInputFields();
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('validation-failed');
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('validation-failed');
            \Log::error('Error saving company data', [
                'company_id' => $this->companyId,
                'company_name' => $this->company_name,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Error saving company data: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            \Log::info('Attempting to edit company', ['id' => $id]);
            
            $company = CompanyDataModel::findOrFail($id);
            $this->companyId = $id;
            $this->company_name = $company->company_name;
            $this->name = $company->name;
            $this->signature_path = $company->signature_path;
            $this->email = $company->email;
            
            // Format phone using trait method
            $this->phone = $this->formatPhoneForDisplay($company->phone);
            
            $this->address = $company->address;
            $this->website = $company->website;
            $this->latitude = $company->latitude;
            $this->longitude = $company->longitude;
            $this->modalTitle = 'Edit Company Data';
            
            $this->openModal();
            $this->dispatch('company-edit')->self();
            
            \Log::info('Company data loaded for editing', [
                'id' => $id, 
                'company_name' => $company->company_name
            ]);
        } catch (\Exception $e) {
            \Log::error('Error loading company for edit', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Error loading company data: ' . $e->getMessage());
        }
    }

    public function deleteCompany($id)
    {
        try {
            \Log::info('Attempting to delete company', ['id' => $id]);
            
            $company = CompanyDataModel::findOrFail($id);
            $company->delete();
            
            // Clear cache using trait
            $this->clearCompanyCache();
            
            \Log::info('Company deleted successfully', ['id' => $id]);
            
            session()->flash('message', 'Company deleted successfully.');
            $this->dispatch('companyDeleted');
            return ['success' => true];
        } catch (\Exception $e) {
            \Log::error('Error deleting company', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Error deleting company: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function restore($id)
    {
        try {
            \Log::info('Attempting to restore company', ['id' => $id]);
            
            // Find the company with trashed records
            $company = CompanyDataModel::withTrashed()->findOrFail($id);
            
            if (!$company) {
                \Log::warning('Company not found for restoration', ['id' => $id]);
                session()->flash('error', 'Company not found.');
                return ['success' => false, 'error' => 'Company not found.'];
            }
            
            \Log::info('Found company to restore', [
                'id' => $id,
                'company_name' => $company->company_name
            ]);
            
            // Perform the restoration
            $restored = $company->restore();
            
            \Log::info('Company restoration result', [
                'id' => $id,
                'restored' => $restored ? 'success' : 'failed'
            ]);
            
            // Clear cache using trait
            $this->clearCompanyCache();
            
            session()->flash('message', 'Company restored successfully.');
            $this->dispatch('companyRestored');
            return ['success' => true];
        } catch (\Exception $e) {
            \Log::error('Error restoring company', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            session()->flash('error', 'Error restoring company: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}