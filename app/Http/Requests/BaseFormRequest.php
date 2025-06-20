<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

abstract class BaseFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Override in child classes if needed
    }

    /**
     * Handle a failed validation attempt.
     */
    protected function failedValidation(Validator $validator)
    {
        Log::warning('Validation failed', [
            'request_class' => static::class,
            'errors' => $validator->errors()->toArray(),
            'input' => $this->except(['password', 'password_confirmation'])
        ]);

        if ($this->wantsJson() || $this->ajax()) {
            throw new HttpResponseException(
                response()->json([
                    'success' => false,
                    'message' => 'Validation errors occurred.',
                    'errors' => $validator->errors()
                ], 422)
            );
        }

        parent::failedValidation($validator);
    }

    /**
     * Get common validation rules that apply to most entities
     */
    protected function getCommonRules(): array
    {
        return [
            'uuid' => 'sometimes|uuid',
            'created_at' => 'sometimes|date',
            'updated_at' => 'sometimes|date',
        ];
    }

    /**
     * Get common anti-spam validation rules
     */
    protected function getAntiSpamRules(): array
    {
        return [
            'not_regex:/test|example|fake|asdf|qwerty|dummy/i'
        ];
    }

    /**
     * Get common phone validation rules
     */
    protected function getPhoneRules(): array
    {
        return [
            'required',
            'regex:/^\(\d{3}\)\s\d{3}-\d{4}$/',
            'not_regex:/\(123\)|^(555)|0000$/'
        ];
    }

    /**
     * Get common email validation rules
     */
    protected function getEmailRules(): array
    {
        return [
            'required',
            'email',
            'max:100',
            'not_regex:/test@|example@|fake@|sample@|temp@/i'
        ];
    }

    /**
     * Get common name validation rules
     */
    protected function getNameRules(int $minLength = 2, int $maxLength = 100): array
    {
        return [
            'required',
            'string',
            "min:{$minLength}",
            "max:{$maxLength}",
            'regex:/^[A-Za-z\s\'-]+$/',
            'not_regex:/test|example|fake|asdf/i'
        ];
    }

    /**
     * Get common address validation rules
     */
    protected function getAddressRules(int $minLength = 10, int $maxLength = 255): array
    {
        return [
            'required',
            'string',
            "min:{$minLength}",
            "max:{$maxLength}",
            'not_regex:/test|example|fake|123 main|456 elm/i'
        ];
    }

    /**
     * Get common validation messages
     */
    protected function getCommonMessages(): array
    {
        return [
            'not_regex' => 'Please provide valid, real information.',
            'regex' => 'The format is invalid.',
            'required' => 'This field is required.',
            'email' => 'Please provide a valid email address.',
            'uuid' => 'Invalid identifier format.',
        ];
    }
} 