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
        
        // Get the route parameter for updates, null for creation
        $routeParam = $this->route('insurance_company');
        
        $baseRules = [
            'insurance_company_name' => [
                'required',
                'string',
                'max:255',
                $routeParam 
                    ? Rule::unique('insurance_companies', 'insurance_company_name')
                        ->ignore($routeParam, 'uuid')
                        ->whereNull('deleted_at')
                    : Rule::unique('insurance_companies', 'insurance_company_name')
                        ->whereNull('deleted_at')
            ],
            'address' => 'nullable|string|max:500',
            'phone' => [
                'nullable',
                'string',
                'regex:/^(\(\d{3}\)\s\d{3}-\d{4}|\+?[1-9]\d{1,14})$/',
                'max:20'
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
                $routeParam 
                    ? Rule::unique('insurance_companies', 'email')
                        ->ignore($routeParam, 'uuid')
                        ->whereNull('deleted_at')
                    : Rule::unique('insurance_companies', 'email')
                        ->whereNull('deleted_at')
            ],
            'website' => [
                'nullable',
                'string',
                'max:255',
                'regex:/^(https?:\/\/)?(www\.)?[a-zA-Z0-9-]+(\.[a-zA-Z]{2,})+(\/.*)?\/?$/'
            ],
            'user_id' => [
                'required',
                'integer',
                'exists:users,id'
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
                    $routeParam 
                        ? Rule::unique('insurance_companies', 'insurance_company_name')
                            ->ignore($routeParam, 'uuid')
                            ->whereNull('deleted_at')
                        : Rule::unique('insurance_companies', 'insurance_company_name')
                            ->whereNull('deleted_at')
                ],
                'address' => 'nullable|string|max:500',
                'phone' => [
                    'nullable',
                    'string',
                    'regex:/^(\(\d{3}\)\s\d{3}-\d{4}|\+?[1-9]\d{1,14})$/',
                    'max:20'
                ],
                'email' => [
                    'nullable',
                    'email',
                    'max:255',
                    $routeParam 
                        ? Rule::unique('insurance_companies', 'email')
                            ->ignore($routeParam, 'uuid')
                            ->whereNull('deleted_at')
                        : Rule::unique('insurance_companies', 'email')
                            ->whereNull('deleted_at')
                ],
                'website' => [
                     'nullable',
                     'string',
                     'max:255',
                     'regex:/^(https?:\/\/)?(www\.)?[a-zA-Z0-9-]+(\.[a-zA-Z]{2,})+(\/.*)?\/?$/'
                 ],
                'user_id' => [
                    'sometimes',
                    'required',
                    'integer',
                    'exists:users,id'
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
    public function prepareForValidation(): void
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

    /**
     * Clean and format phone number
     */
    private function cleanPhoneNumber(string $phone): string
    {
        // If phone is already in (xxx) xxx-xxxx format, keep it as is
        if (preg_match('/^\(\d{3}\)\s\d{3}-\d{4}$/', $phone)) {
            return $phone;
        }
        
        // Remove all non-digits
        $cleaned = preg_replace('/\D/', '', $phone);
        
        // If it's 10 digits, format to (xxx) xxx-xxxx
        if (strlen($cleaned) === 10) {
            return '(' . substr($cleaned, 0, 3) . ') ' . substr($cleaned, 3, 3) . '-' . substr($cleaned, 6, 4);
        }
        
        // If it's 11 digits and starts with 1, remove the 1 and format
        if (strlen($cleaned) === 11 && str_starts_with($cleaned, '1')) {
            $cleaned = substr($cleaned, 1);
            return '(' . substr($cleaned, 0, 3) . ') ' . substr($cleaned, 3, 3) . '-' . substr($cleaned, 6, 4);
        }
        
        return $cleaned;
    }

    /**
     * Format website URL
     */
    private function formatWebsite(?string $website): ?string
    {
        if (empty($website)) {
            return null;
        }
        
        $website = trim($website);
        
        // Add https:// if no protocol specified
        if (!preg_match('/^https?:\/\//', $website)) {
            $website = 'https://' . $website;
        }
        
        return $website;
    }

    /**
     * Convert validated request data to DTO
     * 
     * @return \App\Http\DTOs\InsuranceCompanyDTO
     */
    public function toDTO(): \App\Http\DTOs\InsuranceCompanyDTO
    {
        $validated = $this->validated();
        
        // Add additional fields that might not be in validation
        $data = array_merge($validated, [
            'user_id' => $validated['user_id'] ?? auth()->id(),
            'is_active' => $validated['is_active'] ?? true,
        ]);

        // If updating, preserve the UUID
        if ($this->route('insurance_company')) {
            $data['uuid'] = $this->route('insurance_company');
        }

        return new \App\Http\DTOs\InsuranceCompanyDTO($data);
    }
}