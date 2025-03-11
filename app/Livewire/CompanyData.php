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
    }

    public function store()
    {
        $this->validate([
            'company_name' => 'required',
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'website' => 'required|url'
        ]);

        CompanyDataModel::updateOrCreate(['id' => $this->companyId], [
            'uuid' => $this->companyId ? null : Uuid::uuid4()->toString(),
            'company_name' => $this->company_name,
            'name' => $this->name,
            'signature_path' => $this->signature_path,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'website' => $this->website,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'user_id' => auth()->id()
        ]);

        session()->flash('message', 
            $this->companyId ? 'Company Data Updated Successfully.' : 'Company Data Created Successfully.');

        $this->closeModal();
        $this->resetInputFields();
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

    public function delete($id)
    {
        CompanyDataModel::find($id)->delete();
        session()->flash('message', 'Company Data Deleted Successfully.');
    }
}
