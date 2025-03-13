<?php

namespace App\Traits;

trait EmailDataFormatter
{
    /**
     * Format email data for storage
     *
     * @param array $data
     * @return array
     */
    public function formatEmailData(array $data)
    {
        $formattedData = $data;
        
        // Format phone if provided
        if (!empty($formattedData['phone'])) {
            $formattedData['phone'] = $this->formatPhoneForStorage($formattedData['phone']);
        }
        
        return $formattedData;
    }

    /**
     * Format phone number for display
     *
     * @param string|null $phone
     * @return string
     */
    public function formatPhoneForDisplay($phone)
    {
        if (empty($phone)) {
            return '';
        }
        
        // Remove any non-numeric characters
        $rawPhone = preg_replace('/[^0-9]/', '', $phone);
        
        // Remove country code if present (assuming US +1)
        if (strlen($rawPhone) > 10 && substr($rawPhone, 0, 1) == '1') {
            $rawPhone = substr($rawPhone, 1);
        }
        
        // Format as (XXX) XXX-XXXX
        if (strlen($rawPhone) >= 10) {
            return sprintf("(%s) %s-%s",
                substr($rawPhone, 0, 3),
                substr($rawPhone, 3, 3),
                substr($rawPhone, 6, 4)
            );
        }
        
        return $phone;
    }

    /**
     * Format phone number for storage
     *
     * @param string $phone
     * @return string|null
     */
    public function formatPhoneForStorage($phone)
    {
        if (empty($phone)) {
            return null;
        }
        
        return '+1' . preg_replace('/[^0-9]/', '', $phone);
    }
} 