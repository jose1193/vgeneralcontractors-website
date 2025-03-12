<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CompanyData as CompanyDataModel;
use Livewire\WithPagination;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Cache;

class CompanyData extends Component
{
    use WithPagination;

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
        
        $cacheKey = 'companies_' . $this->search . '_' . $this->sortField . '_' . $this->sortDirection . '_' . $this->perPage;
        
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
            $this->validate([
                'company_name' => 'required',
                'name' => 'required',
                'email' => 'required|email',
                'phone' => 'required',
                'address' => 'required',
                'website' => 'required|url',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric'
            ]);

            \Log::info('Attempting to save company data', [
                'company_id' => $this->companyId,
                'company_name' => $this->company_name,
                'action' => $this->companyId ? 'update' : 'create'
            ]);

            // Formatear el número de teléfono
            $phone = preg_replace('/[^0-9]/', '', $this->phone);
            $phone = '+1' . $phone;
            
            // Format website URL if needed
            $website = $this->website;
            if (!empty($website)) {
                if (!preg_match('/^https?:\/\//i', $website)) {
                    if (preg_match('/^www\./i', $website)) {
                        $website = 'https://' . $website;
                    } else {
                        $website = 'https://' . $website;
                    }
                }
            }

            $data = [
                'company_name' => ucwords(strtolower($this->company_name)),
                'name' => ucwords(strtolower($this->name)),
                'signature_path' => $this->signature_path,
                'email' => $this->email,
                'phone' => $phone,
                'address' => strtoupper($this->address),
                'website' => $website,
                'latitude' => $this->latitude ?: null,
                'longitude' => $this->longitude ?: null,
                'user_id' => auth()->id()
            ];

            // Solo agregar UUID si es una nueva creación
            if (!$this->companyId) {
                $data['uuid'] = Uuid::uuid4()->toString();
            }

            CompanyDataModel::updateOrCreate(
                ['id' => $this->companyId],
                $data
            );

            // Clear cache
            $this->clearCache();

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
            
            // Formatear el teléfono para mostrar en el formato (XXX) XXX - XXXX
            $rawPhone = preg_replace('/[^0-9]/', '', $company->phone);
            if (strlen($rawPhone) >= 10) {
                $rawPhone = substr($rawPhone, -10);
                $this->phone = sprintf("(%s) %s - %s",
                    substr($rawPhone, 0, 3),
                    substr($rawPhone, 3, 3),
                    substr($rawPhone, 6)
                );
            } else {
                $this->phone = $company->phone;
            }
            
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
            
            // Clear cache
            $this->clearCache();
            
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
    
    private function clearCache()
    {
        // Clear specific cache keys
        $searchTerm = '%' . $this->search . '%';
        $cacheKey = 'companies_' . $this->search . '_' . $this->sortField . '_' . $this->sortDirection . '_' . $this->perPage;
        Cache::forget($cacheKey);
    }
}