<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\CompanyData as CompanyDataModel;
use Livewire\WithPagination;
use Ramsey\Uuid\Uuid;

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

    protected $listeners = [];

    public function render()
    {
        $searchTerm = '%' . $this->search . '%';
        return view('livewire.company-data', [
            'companies' => CompanyDataModel::where('company_name', 'like', $searchTerm)
                ->orWhere('name', 'like', $searchTerm)
                ->orWhere('email', 'like', $searchTerm)
                ->orderBy('created_at', 'desc')
                ->paginate(10)
        ]);
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
        $this->isSubmitting = true;
        
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

        try {
            // Formatear el número de teléfono
            $phone = preg_replace('/[^0-9]/', '', $this->phone);
            $phone = '+1' . $phone;
            
            // Format website URL if needed
            $website = $this->website;
            if (!empty($website)) {
                // If website doesn't start with http:// or https://, add https://
                if (!preg_match('/^https?:\/\//i', $website)) {
                    // If it starts with www., add https:// before it
                    if (preg_match('/^www\./i', $website)) {
                        $website = 'https://' . $website;
                    } else {
                        // Otherwise, add https://www.
                        $website = 'https://' . $website;
                    }
                }
            }

            CompanyDataModel::updateOrCreate(['id' => $this->companyId], [
                'uuid' => $this->companyId ? null : Uuid::uuid4()->toString(),
                'company_name' => strtoupper($this->company_name),
                'name' => strtoupper($this->name),
                'signature_path' => $this->signature_path,
                'email' => $this->email,
                'phone' => $phone,
                'address' => strtoupper($this->address),
                'website' => $website,
                'latitude' => $this->latitude ?: null,
                'longitude' => $this->longitude ?: null,
                'user_id' => auth()->id()
            ]);

            session()->flash('message', 
                $this->companyId ? 'Company Data Updated Successfully.' : 'Company Data Created Successfully.');

            $this->closeModal();
            $this->resetInputFields();
        } catch (\Exception $e) {
            session()->flash('error', 'Error saving company data: ' . $e->getMessage());
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function edit($id)
    {
        $company = CompanyDataModel::findOrFail($id);
        $this->companyId = $id;
        $this->company_name = $company->company_name;
        $this->name = $company->name;
        $this->signature_path = $company->signature_path;
        $this->email = $company->email;
        $this->phone = $company->phone;
        $this->address = $company->address;
        $this->website = $company->website;
        $this->latitude = $company->latitude;
        $this->longitude = $company->longitude;
        $this->modalTitle = 'Edit Company Data';
        $this->openModal();
    }

    public function deleteCompany($id)
{
    try {
        $company = CompanyDataModel::findOrFail($id);
        $company->delete();
        session()->flash('message', 'Company deleted successfully.');
        $this->dispatch('companyDeleted');
        return ['success' => true];
    } catch (\Exception $e) {
        session()->flash('error', 'Error deleting company: ' . $e->getMessage());
        return ['success' => false, 'error' => $e->getMessage()];
    }
}
}
