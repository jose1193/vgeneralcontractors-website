<?php

namespace App\Helpers;

use Illuminate\Support\Facades\App;

class TranslationHelper
{
    /**
     * Get translation with fallback
     */
    public static function trans($key, $parameters = [], $locale = null)
    {
        $locale = $locale ?? App::getLocale();
        
        // Try to get translation from JSON files first
        $translation = __("app.{$key}", $parameters, $locale);
        
        // If translation not found, try without app prefix
        if ($translation === "app.{$key}") {
            $translation = __($key, $parameters, $locale);
        }
        
        // If still not found, return the key
        if ($translation === $key) {
            return ucfirst(str_replace('_', ' ', $key));
        }
        
        return $translation;
    }
    
    /**
     * Get current locale info
     */
    public static function getCurrentLocaleInfo()
    {
        $locale = App::getLocale();
        return config("app.available_locales.{$locale}", [
            'name' => 'Unknown',
            'flag' => '🌐',
            'date_format' => 'Y-m-d',
            'time_format' => 'H:i'
        ]);
    }
    
    /**
     * Format date according to current locale
     */
    public static function formatDate($date, $format = null)
    {
        if (!$date) return '';
        
        $localeInfo = self::getCurrentLocaleInfo();
        $format = $format ?? $localeInfo['date_format'];
        
        return $date->format($format);
    }
    
    /**
     * Format time according to current locale
     */
    public static function formatTime($time, $format = null)
    {
        if (!$time) return '';
        
        $localeInfo = self::getCurrentLocaleInfo();
        $format = $format ?? $localeInfo['time_format'];
        
        return $time->format($format);
    }
    
    /**
     * Get available locales
     */
    public static function getAvailableLocales()
    {
        return config('app.available_locales', []);
    }
    
    /**
     * Check if locale is RTL
     */
    public static function isRtl($locale = null)
    {
        $locale = $locale ?? App::getLocale();
        $rtlLocales = ['ar', 'he', 'fa', 'ur'];
        
        return in_array($locale, $rtlLocales);
    }
} 