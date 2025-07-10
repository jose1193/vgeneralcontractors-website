<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseFormRequest;
use App\Enums\RequestMethod;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class InsuranceCompanyRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        Log::debug('InsuranceCompanyRequest@rules method entered.');
        
        $baseRules = [
            'insurance_company_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('insurance_companies', 'insurance_company_name')
                    ->ignore($this->route('insurance_company'), 'uuid')
                    ->whereNull('deleted_at')
            ],
            'address' => 'nullable|string|max:500',
            'phone' => [
                'nullable',
                'string',
                'regex:/^\+?[1-9]\d{1,14}$/',
                'max:20'
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('insurance_companies', 'email')
                    ->ignore($this->route('insurance_company'), 'uuid')
                    ->whereNull('deleted_at')
            ],
            'website' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^(https?:\/\/)?(www\.)?[a-zA-Z0-9-]+(\.[a-zA-Z]{2,})+(\/.*)?\/$/'
            ],

        ];
        
        $method = RequestMethod::from($this->method());
        
        $rules = match($method) {
            RequestMethod::POST => $baseRules,
            RequestMethod::PUT, RequestMethod::PATCH => [
                'insurance_company_name' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('insurance_companies', 'insurance_company_name')
                        ->ignore($this->route('insurance_company'), 'uuid')
                        ->whereNull('deleted_at')
                ],
                'address' => 'nullable|string|max:500',
                'phone' => [
                    'nullable',
                    'string',
                    'regex:/^\+?[1-9]\d{1,14}$/',
                    'max:20'
                ],
                'email' => [
                    'nullable',
                    'email',
                    'max:255',
                    Rule::unique('insurance_companies', 'email')
                        ->ignore($this->route('insurance_company'), 'uuid')
                        ->whereNull('deleted_at')
                ],
                'website' => [
                    'nullable',
                    'string',
                    'max:255',
                    'regex:/^(https?:\/\/)?(www\.)?[a-zA-Z0-9-]+(\.[a-zA-Z]{2,})+(\/.*)?\/$/'
                ],

            ],
            default => $baseRules
        };
        
        Log::debug('Validation rules generated.', $rules);
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
            
            'address.max' => 'Address may not be greater than 500 characters.',
            
            'phone.max' => 'Phone number may not be greater than 20 characters.',
            'phone.regex' => 'Phone number format is invalid.',
            
            'email.email' => 'Email must be a valid email address.',
            'email.unique' => 'This email is already taken.',
            'email.max' => 'Email may not be greater than 255 characters.',
            
            'website.url' => 'Website must be a valid URL.',
            'website.regex' => 'Website must start with http:// or https://',
            'website.not_regex' => 'Please provide a real website URL.',
            
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
        Log::debug('InsuranceCompanyRequest@prepareForValidation method entered.');
        
        $data = $this->all();
        
        // Set user_id from authenticated user
        $data['user_id'] = auth()->id();
        Log::debug('User ID set from auth.', ['user_id' => $data['user_id']]);
        
        // Clean and format phone number
        if (isset($data['phone']) && !empty($data['phone'])) {
            $data['phone'] = $this->cleanPhoneNumber($data['phone']);
            Log::debug('Phone number cleaned.', ['original' => $this->input('phone'), 'cleaned' => $data['phone']]);
        }
        
        // Clean and format email
        if (isset($data['email']) && !empty($data['email'])) {
            $data['email'] = strtolower(trim($data['email']));
            Log::debug('Email cleaned.', ['original' => $this->input('email'), 'cleaned' => $data['email']]);
        }
        
        // Clean and format website
        if (isset($data['website']) && !empty($data['website'])) {
            $data['website'] = $this->formatWebsite($data['website']);
            Log::debug('Website formatted.', ['original' => $this->input('website'), 'formatted' => $data['website']]);
        }
        
        // Clean insurance company name
        if (isset($data['insurance_company_name']) && !empty($data['insurance_company_name'])) {
            $data['insurance_company_name'] = trim($data['insurance_company_name']);
            Log::debug('Insurance company name cleaned.', ['cleaned' => $data['insurance_company_name']]);
        }
        
        $this->merge($data);
        Log::debug('Data preparation completed.');
    }
}