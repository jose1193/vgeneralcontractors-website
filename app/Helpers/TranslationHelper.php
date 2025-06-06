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
        
        // Try different translation methods in order of preference:
        
        // 1. Try messages.{key} (PHP files)
        $translation = __("messages.{$key}", $parameters, $locale);
        if ($translation !== "messages.{$key}") {
            return $translation;
        }
        
        // 2. Try direct key from JSON files
        $translation = __($key, $parameters, $locale);
        if ($translation !== $key) {
            return $translation;
        }
        
        // 3. Try converting snake_case to spaces for better readability
        $fallback = ucwords(str_replace(['_', '-'], ' ', $key));
        
        return $fallback;
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
        
        // Convert Carbon instance if needed
        if (is_string($date)) {
            $date = \Carbon\Carbon::parse($date);
        }
        
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
        
        // Convert Carbon instance if needed
        if (is_string($time)) {
            $time = \Carbon\Carbon::parse($time);
        }
        
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
    
    /**
     * Helper method to get message translation specifically
     */
    public static function message($key, $parameters = [], $locale = null)
    {
        $locale = $locale ?? App::getLocale();
        return __("messages.{$key}", $parameters, $locale);
    }
} 