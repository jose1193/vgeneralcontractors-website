<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class FacebookLeadFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true; // Allow anyone to submit this form
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            // 'first_name' => 'required|min:2|alpha',
            'first_name' => ['required', 'min:2', 'regex:/^[A-Za-z\'-]+$/'], // Allow letters, apostrophe, hyphen - no spaces
            // 'last_name' => 'required|min:2|alpha',
            'last_name' => ['required', 'min:2', 'regex:/^[A-Za-z\'-]+$/'], // Allow letters, apostrophe, hyphen - no spaces
            // Note: The phone regex might need adjustment if the input mask is handled purely client-side now.
            // If the JS sends raw digits, the regex needs to change.
            // If the JS sends the masked format, this regex is correct.
            'phone' => 'required|regex:/^\(\d{3}\)\s\d{3}-\d{4}$/',
            'email' => 'required|email',
            'address_map_input' => 'required|min:5', // New field for the visible address input
            'address' => 'required|min:5',
            'address_2' => 'nullable',
            'city' => 'required',
            'state' => 'required',
           
            'zipcode' => 'required|digits:5', // Enforce exactly 5 digits
            'country' => 'required', // Consider validating against a list or keeping it simple
            'insurance_property' => 'required|in:yes,no',
            'message' => 'nullable|min:5',
            'sms_consent' => 'nullable|boolean', // Use nullable and boolean
            'latitude' => 'nullable|numeric|between:-90,90', // Add latitude validation
            'longitude' => 'nullable|numeric|between:-180,180', // Add longitude validation
            'lead_source' => 'nullable|string', // Add lead_source validation as nullable string
            'status_lead' => 'nullable|string|in:New,Called,Pending,Declined', // Add status_lead field validation
            'inspection_date' => 'nullable|date|required_with:inspection_time',
            'inspection_time' => 'nullable|date_format:H:i|required_with:inspection_date',
            // Add reCAPTCHA rule (using package's default v3 rule)
            // The package handles checking the score against a default threshold (0.5)
            // You can customize the threshold in the config/nocaptcha.php file if published
            'g-recaptcha-response' => 'required',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'first_name.regex' => 'First Name must contain only letters, hyphens, or apostrophes (no spaces).',
            'last_name.regex' => 'Last Name must contain only letters, hyphens, or apostrophes (no spaces).',
            'phone.regex' => 'The phone number must be in the format (XXX) XXX-XXXX.',
            'zipcode.digits' => 'The zip code must be exactly 5 digits.', // Changed from regex
            'address_map_input.required' => 'Please enter your full address.',
            'address_map_input.min' => 'The address must be at least 5 characters.',
            'g-recaptcha-response.required' => 'CAPTCHA verification is required.',
            'inspection_date.required_with' => 'The inspection date is required when inspection time is present.',
            'inspection_time.required_with' => 'The inspection time is required when inspection date is present.',
            // Add other custom messages if needed
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        // Since this is specifically for an AJAX request, always return JSON
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Validation errors',
                'errors' => $validator->errors()
            ], 422) // Use 422 Unprocessable Entity status code
        );
    }
}
