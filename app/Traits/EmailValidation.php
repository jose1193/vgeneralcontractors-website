<?php

namespace App\Traits;

use Illuminate\Validation\Rule;

trait EmailValidation
{
    /**
     * Get common validation rules for emails
     *
     * @return array
     */
    public function getEmailValidationRules()
    {
        return [
            'description' => 'nullable|string',
            'email' => ['required', 'email', 'max:255', 
                Rule::unique('email_data', 'email')->ignore($this->uuid, 'uuid')],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^\(\d{3}\) \d{3}-\d{4}$/',
                Rule::unique('email_data', 'phone')->ignore($this->uuid, 'uuid')],
            'type' => 'required|string|max:50',
            'user_id' => 'required|exists:users,id',
        ];
    }

    /**
     * Get validation rules for creating emails
     *
     * @return array
     */
    public function getCreateValidationRules()
    {
        return $this->getEmailValidationRules();
    }

    /**
     * Get validation rules for updating emails
     *
     * @return array
     */
    public function getUpdateValidationRules()
    {
        return $this->getEmailValidationRules();
    }

    /**
     * Get validation messages
     *
     * @return array
     */
    public function getValidationMessages()
    {
        return [
            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'This email is already taken',
            'phone.unique' => 'This phone number is already taken',
            'phone.regex' => 'Please enter a valid phone number format: (XXX) XXX-XXXX',
            'type.required' => 'Type is required',
            'user_id.required' => 'User is required',
            'user_id.exists' => 'Selected user is invalid',
        ];
    }

    /**
     * Check if an email already exists in the database
     * 
     * @param string $email
     * @return bool
     */
    public function checkEmailExists($email)
    {
        if (empty($email)) {
            return false;
        }

        // If we're in update mode and the email hasn't changed, it's valid
        if ($this->modalAction === 'update' && $this->uuid) {
            $emailData = \App\Models\EmailData::where('uuid', $this->uuid)->first();
            if ($emailData && $emailData->email === $email) {
                return false;
            }
        }

        // Check if email exists for any other email data
        return \App\Models\EmailData::where('email', $email)
            ->when($this->uuid, function ($query) {
                return $query->where('uuid', '!=', $this->uuid);
            })
            ->exists();
    }

    /**
     * Check if a phone already exists in the database
     * 
     * @param string $phone
     * @return bool
     */
    public function checkPhoneExists($phone)
    {
        if (empty($phone)) {
            return false;
        }

        // Format phone for comparison
        $formattedPhone = '+1' . preg_replace('/[^0-9]/', '', $phone);

        // If we're in update mode and the phone hasn't changed, it's valid
        if ($this->modalAction === 'update' && $this->uuid) {
            $emailData = \App\Models\EmailData::where('uuid', $this->uuid)->first();
            if ($emailData && $emailData->phone === $formattedPhone) {
                return false;
            }
        }

        // Check if phone exists for any other email data
        return \App\Models\EmailData::where('phone', $formattedPhone)
            ->when($this->uuid, function ($query) {
                return $query->where('uuid', '!=', $this->uuid);
            })
            ->exists();
    }
} 