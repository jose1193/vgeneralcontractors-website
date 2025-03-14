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
                $formattedData['phone'] = '(' . substr($numbers, 0, 3) . ') ' . substr($numbers, 3, 3) . '-' . substr($numbers, 6, 4);
            }
        }

        return $formattedData;
    }

    public function formatPhoneForDisplay($phone)
    {
        if (empty($phone)) return '';
        $numbers = preg_replace('/[^0-9]/', '', $phone);

        // Handle numbers with country code
        if (strlen($numbers) === 11 && str_starts_with($numbers, '1')) {
            $numbers = substr($numbers, 1);
        }

        if (strlen($numbers) === 10) {
            return '(' . substr($numbers, 0, 3) . ') ' . substr($numbers, 3, 3) . '-' . substr($numbers, 6, 4);
        }
        return $phone;
    }
}