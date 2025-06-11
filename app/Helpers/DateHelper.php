<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Helpers\LanguageHelper;

class DateHelper
{
    /**
     * Format date according to current locale
     */
    public static function formatDate($date, string $format = 'dddd, LL'): string
    {
        if (!$date) {
            return '';
        }

        $carbon = Carbon::parse($date);
        $locale = LanguageHelper::getCurrentLanguage();
        
        return $carbon->locale($locale)->isoFormat($format);
    }

    /**
     * Format date and time according to current locale
     */
    public static function formatDateTime($date, string $format = 'dddd, LL [at] LT'): string
    {
        if (!$date) {
            return '';
        }

        $carbon = Carbon::parse($date);
        $locale = LanguageHelper::getCurrentLanguage();
        
        return $carbon->locale($locale)->isoFormat($format);
    }

    /**
     * Get relative time (e.g., "2 hours ago")
     */
    public static function diffForHumans($date): string
    {
        if (!$date) {
            return '';
        }

        $carbon = Carbon::parse($date);
        $locale = LanguageHelper::getCurrentLanguage();
        
        return $carbon->locale($locale)->diffForHumans();
    }

    /**
     * Get current date formatted for locale
     */
    public static function today(string $format = 'dddd, LL'): string
    {
        $locale = LanguageHelper::getCurrentLanguage();
        
        return Carbon::now()
            ->locale($locale)
            ->isoFormat($format);
    }

    /**
     * Get current time formatted for locale
     */
    public static function now(string $format = 'LT'): string
    {
        $locale = LanguageHelper::getCurrentLanguage();
        
        return Carbon::now()
            ->locale($locale)
            ->isoFormat($format);
    }

    /**
     * Get business hours formatted for locale
     */
    public static function formatBusinessHours(string $startTime, string $endTime): string
    {
        $locale = LanguageHelper::getCurrentLanguage();
        
        $start = Carbon::createFromFormat('H:i', $startTime)->locale($locale);
        $end = Carbon::createFromFormat('H:i', $endTime)->locale($locale);
        
        return $start->isoFormat('LT') . ' - ' . $end->isoFormat('LT');
    }

    /**
     * Get available locale formats
     */
    public static function getLocaleFormats(): array
    {
        return [
            'en' => [
                'date' => 'MMM DD, YYYY',
                'datetime' => 'MMM DD, YYYY [at] h:mm A',
                'time' => 'h:mm A',
                'full_date' => 'dddd, MMMM Do, YYYY'
            ],
            'es' => [
                'date' => 'DD [de] MMM [de] YYYY',
                'datetime' => 'DD [de] MMM [de] YYYY [a las] HH:mm',
                'time' => 'HH:mm',
                'full_date' => 'dddd, D [de] MMMM [de] YYYY'
            ]
        ];
    }

    /**
     * Format date range
     */
    public static function formatDateRange(Carbon $startDate, Carbon $endDate): string
    {
        $locale = LanguageHelper::getCurrentLanguage();
        
        $start = $startDate->locale($locale);
        $end = $endDate->locale($locale);
        
        // Same day
        if ($start->isSameDay($end)) {
            return $start->isoFormat('dddd, LL');
        }
        
        // Same month
        if ($start->isSameMonth($end)) {
            return $start->isoFormat('MMM DD') . ' - ' . $end->isoFormat('DD, YYYY');
        }
        
        // Different months
        return $start->isoFormat('MMM DD') . ' - ' . $end->isoFormat('MMM DD, YYYY');
    }
} 