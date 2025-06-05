<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use App\Helpers\LanguageHelper;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Get the default locale
        $defaultLocale = config('app.locale', 'en');
        
        // Check for language parameter in request
        if ($request->has('lang')) {
            $language = $request->get('lang');
            $availableLanguages = array_keys(LanguageHelper::getAvailableLanguages());
            
            if (in_array($language, $availableLanguages)) {
                LanguageHelper::setLanguage($language);
            }
        } else {
            // Get locale from session or use default
            $locale = Session::get('locale', $defaultLocale);
            $availableLanguages = array_keys(LanguageHelper::getAvailableLanguages());
            
            // Validate the locale from session
            if (in_array($locale, $availableLanguages)) {
                App::setLocale($locale);
            } else {
                // Fallback to default and store it
                App::setLocale($defaultLocale);
                Session::put('locale', $defaultLocale);
            }
        }

        return $next($request);
    }
} 