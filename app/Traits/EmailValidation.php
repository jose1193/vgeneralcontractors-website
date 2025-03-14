<?php

namespace App\Traits;

use Illuminate\Validation\Rule;
use App\Models\EmailData;

trait EmailValidation
{
    protected function getEmailValidationRules()
    {
        return [
            'description' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'type' => 'required|string|max:50',
            'user_id' => 'required|exists:users,id',
        ];
    }

    protected function getCreateValidationRules()
    {
        $rules = $this->getEmailValidationRules();
        $rules['email'] = array_merge($rules['email'], [Rule::unique('email_data', 'email')]);
        $rules['phone'] = array_merge($rules['phone'], [Rule::unique('email_data', 'phone')]);
        return $rules;
    }

    protected function getUpdateValidationRules()
    {
        $rules = $this->getEmailValidationRules();
        $rules['email'] = array_merge($rules['email'], [Rule::unique('email_data', 'email')->ignore($this->uuid, 'uuid')]);
        $rules['phone'] = array_merge($rules['phone'], [Rule::unique('email_data', 'phone')->ignore($this->uuid, 'uuid')]);
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
}