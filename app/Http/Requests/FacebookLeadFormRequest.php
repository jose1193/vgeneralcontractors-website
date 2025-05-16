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
            'first_name' => ['required', 'min:2', 'regex:/^[A-Za-z\'-]+$/', 'not_regex:/test|asdf|qwerty|fake/i'], // Block common test names
            // 'last_name' => 'required|min:2|alpha',
            'last_name' => ['required', 'min:2', 'regex:/^[A-Za-z\'-]+$/', 'not_regex:/test|asdf|qwerty|fake/i'], // Block common test names
            // Note: The phone regex might need adjustment if the input mask is handled purely client-side now.
            // If the JS sends raw digits, the regex needs to change.
            // If the JS sends the masked format, this regex is correct.
            'phone' => ['required', 'regex:/^\(\d{3}\)\s\d{3}-\d{4}$/', 'not_regex:/\(123\)|^(555)|0000$/'], // Block obvious test phones
            'email' => ['required', 'email', 'not_regex:/test@|example@|fake@|sample@|temp@/i'], // Block test emails
            'address_map_input' => ['required', 'min:5', 'not_regex:/test|example|fake|asdf/i'], // Block test addresses
            'address' => ['required', 'min:5', 'not_regex:/test|example|fake|asdf/i'], // Block test addresses
            'address_2' => 'nullable',
            'city' => ['required', 'not_regex:/test|example|fake/i'], // Block test cities
            'state' => 'required',
           
            'zipcode' => 'required|digits:5', // Enforce exactly 5 digits
            'country' => 'required', // Consider validating against a list or keeping it simple
            'insurance_property' => 'required|in:yes,no',
            'message' => ['nullable', 'min:5', 'not_regex:/test|do not|don\'t reply|ignore|asdf|dummy/i'], // Block obvious test messages
            'sms_consent' => 'nullable|boolean', // Use nullable and boolean
            'latitude' => 'nullable|numeric|between:-90,90', // Add latitude validation
            'longitude' => 'nullable|numeric|between:-180,180', // Add longitude validation
            'lead_source' => 'nullable|string', // Add lead_source validation as nullable string
            'status_lead' => 'nullable|string|in:New,Called,Pending,Declined', // Add status_lead field validation
            'inspection_date' => 'nullable|date|required_with:inspection_time',
            'inspection_time' => 'nullable|date_format:H:i|required_with:inspection_date',
            // Add reCAPTCHA rule (using package's default v3 rule)
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
            'first_name.not_regex' => 'Please provide your actual first name.',
            'last_name.regex' => 'Last Name must contain only letters, hyphens, or apostrophes (no spaces).',
            'last_name.not_regex' => 'Please provide your actual last name.',
            'phone.regex' => 'The phone number must be in the format (XXX) XXX-XXXX.',
            'phone.not_regex' => 'Please provide your actual phone number.',
            'email.not_regex' => 'Please provide your actual email address.',
            'zipcode.digits' => 'The zip code must be exactly 5 digits.', // Changed from regex
            'address_map_input.required' => 'Please enter your full address.',
            'address_map_input.min' => 'The address must be at least 5 characters.',
            'address_map_input.not_regex' => 'Please provide your actual address.',
            'address.not_regex' => 'Please provide your actual address.',
            'city.not_regex' => 'Please provide your actual city.',
            'message.not_regex' => 'Please provide an actual message.',
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
