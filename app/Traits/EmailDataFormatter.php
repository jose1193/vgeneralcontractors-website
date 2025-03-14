<?php

namespace App\Traits;

trait EmailDataFormatter
{
    public function formatEmailData(array $data)
    {
        $formattedData = $data;

        // Format phone number if provided
        if (!empty($formattedData['phone'])) {
            $numbers = preg_replace('/[^0-9]/', '', $formattedData['phone']);
            if (strlen($numbers) === 10) {
                // Format as +1 followed by 10 digits
                $formattedData['phone'] = '+1' . $numbers;
            } elseif (strlen($numbers) === 11 && substr($numbers, 0, 1) === '1') {
                // Already has country code, just add the + sign
                $formattedData['phone'] = '+' . $numbers;
            }
        }

        return $formattedData;
    }

    public function formatPhoneForDisplay($phone)
    {
        if (empty($phone)) return '';
        $numbers = preg_replace('/[^0-9]/', '', $phone);

        // If number starts with country code, remove it for display
        if (strlen($numbers) === 11 && str_starts_with($numbers, '1')) {
            $numbers = substr($numbers, 1);
        }

        if (strlen($numbers) === 10) {
            return '(' . substr($numbers, 0, 3) . ') ' . substr($numbers, 3, 3) . '-' . substr($numbers, 6, 4);
        }
        return $phone;
    }
}