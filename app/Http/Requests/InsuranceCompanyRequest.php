<?php

namespace App\Http\Requests;

class InsuranceCompanyRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $isUpdate = $this->route('uuid') !== null;
        $uuid = $isUpdate ? $this->route('uuid') : null;

        $rules = array_merge($this->getCommonRules(), [
            'insurance_company_name' => [
                'required',
                'string',
                'min:2',
                'max:255',
                $isUpdate 
                    ? "unique:insurance_companies,insurance_company_name,{$uuid},uuid"
                    : 'unique:insurance_companies,insurance_company_name',
                'not_regex:/test|example|fake|asdf/i'
            ],
            'address' => $this->getAddressRules(10, 500),
            'phone' => $this->getPhoneRules(),
            'email' => array_merge($this->getEmailRules(), [
                $isUpdate 
                    ? "unique:insurance_companies,email,{$uuid},uuid"
                    : 'unique:insurance_companies,email'
            ]),
            'website' => [
                'nullable',
                'url',
                'max:255',
                'regex:/^https?:\/\//',
                'not_regex:/test|example|fake|localhost/i'
            ],
            'user_id' => [
                'required',
                'exists:users,id'
            ]
        ]);

        return $rules;
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return array_merge($this->getCommonMessages(), [
            'insurance_company_name.required' => 'Company name is required.',
            'insurance_company_name.unique' => 'This insurance company name is already taken.',
            'insurance_company_name.min' => 'Company name must be at least 2 characters.',
            'insurance_company_name.max' => 'Company name may not be greater than 255 characters.',
            'insurance_company_name.not_regex' => 'Please provide a real company name.',
            
            'address.required' => 'Address is required.',
            'address.min' => 'Address must be at least 10 characters.',
            'address.max' => 'Address may not be greater than 500 characters.',
            'address.not_regex' => 'Please provide a real address.',
            
            'website.url' => 'Website must be a valid URL.',
            'website.regex' => 'Website must start with http:// or https://',
            'website.not_regex' => 'Please provide a real website URL.',
            
            'user_id.required' => 'Assigned user is required.',
            'user_id.exists' => 'Selected user does not exist.',
        ]);
    }

    /**
     * Get custom attribute names for validation messages.
     */
    public function attributes(): array
    {
        return [
            'insurance_company_name' => 'company name',
            'address' => 'address',
            'phone' => 'phone number',
            'email' => 'email address',
            'website' => 'website',
            'user_id' => 'assigned user'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Clean and format phone number
        if ($this->has('phone')) {
            $phone = preg_replace('/\D/', '', $this->phone);
            if (strlen($phone) === 10) {
                $this->merge([
                    'phone' => $phone
                ]);
            }
        }

        // Clean and format email
        if ($this->has('email')) {
            $this->merge([
                'email' => strtolower(trim($this->email))
            ]);
        }

        // Clean website URL
        if ($this->has('website') && !empty($this->website)) {
            $website = trim($this->website);
            if (!preg_match('/^https?:\/\//', $website)) {
                $website = 'https://' . $website;
            }
            $this->merge([
                'website' => $website
            ]);
        }

        // Trim insurance company name
        if ($this->has('insurance_company_name')) {
            $this->merge([
                'insurance_company_name' => trim($this->insurance_company_name)
            ]);
        }
    }
} 