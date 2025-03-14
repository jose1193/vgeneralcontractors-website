<?php

namespace App\Traits;

use Illuminate\Validation\Rule;
use App\Models\EmailData;

trait EmailValidation
{
    protected function getEmailValidationRules()
    {
        return [
            'description' => ['nullable', 'string', 'max:255'], // Changed to array, made description nullable
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'], // Changed to array, made phone nullable
            'type' => ['required', 'string'], // Updated to use 'in' for specific types
            'user_id' => ['required', 'exists:users,id'],
        ];
    }

    protected function getCreateValidationRules()
    {
        $rules = $this->getEmailValidationRules();
        $rules['email'][] = Rule::unique('email_data', 'email');
        $rules['phone'][] = Rule::unique('email_data', 'phone');
        return $rules;
    }

    protected function getUpdateValidationRules()
    {
        $rules = $this->getEmailValidationRules();
        $rules['email'][] = Rule::unique('email_data', 'email')->ignore($this->uuid, 'uuid');
        $rules['phone'][] = Rule::unique('email_data', 'phone')->ignore($this->uuid, 'uuid');
        return $rules;
    }

    public function checkEmailExists($email)
    {
        return EmailData::where('email', $email)
            ->where('uuid', '!=', $this->uuid)
            ->exists();
    }

    public function checkPhoneExists($phone)
    {
        return EmailData::where('phone', $phone)
            ->where('uuid', '!=', $this->uuid)
            ->exists();
    }

    protected function getValidationMessages()
    {
        return [
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'email.max' => 'Email must not exceed 255 characters',
            'email.unique' => 'This email is already taken',
            'phone.unique' => 'This phone is already taken',
            'phone.max' => 'Phone must not exceed 20 characters',
            'description.max' => 'Description must not exceed 255 characters',
            'type.required' => 'Type is required',
            'type.in' => 'Type must be one of: Personal, Work, Business, Other',
            'user_id.required' => 'User is required',
            'user_id.exists' => 'Selected user does not exist',
        ];
    }
}