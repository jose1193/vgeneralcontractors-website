<?php

namespace App\Helpers;

use App\Models\EmailData;
use Illuminate\Support\Facades\Log;

class EmailHelper
{
    /**
     * Verify that the admin email exists and is valid.
     * 
     * @return array
     */
    public static function verifyAdminEmail()
    {
        // Get all email data for logging purposes
        $allEmails = EmailData::all(['id', 'type', 'email']);
        
        // Try exact match first
        $adminEmail = EmailData::where('type', 'Admin')->first();
        
        // If not found, try case-insensitive search
        if (!$adminEmail) {
            $adminEmail = EmailData::whereRaw('LOWER(type) = ?', [strtolower('Admin')])->first();
        }
        
        // Check if we found a valid email
        $isValid = $adminEmail && $adminEmail->email && filter_var($adminEmail->email, FILTER_VALIDATE_EMAIL);
        
        // Log information about admin email
        Log::info('Admin email verification', [
            'found' => (bool)$adminEmail,
            'email' => $adminEmail ? $adminEmail->email : 'Not found',
            'isValid' => $isValid,
            'allEmails' => $allEmails->map(fn($item) => ['id' => $item->id, 'type' => $item->type, 'email' => $item->email])->toArray()
        ]);
        
        return [
            'exists' => (bool)$adminEmail,
            'email' => $adminEmail ? $adminEmail->email : null,
            'isValid' => $isValid,
            'model' => $adminEmail,
        ];
    }
}
