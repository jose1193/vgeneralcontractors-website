<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

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
        // Available languages
        $availableLanguages = ['en', 'es'];
        
        // Check for language parameter in request first (for manual switches)
        if ($request->has('lang')) {
            $language = $request->get('lang');
            if (in_array($language, $availableLanguages)) {
                Session::put('locale', $language);
                App::setLocale($language);
            }
        } else {
            // Get language from session or default to config
            $locale = Session::get('locale', config('app.locale', 'en'));
            
            // Ensure it's a valid language
            if (!in_array($locale, $availableLanguages)) {
                $locale = 'en';
                Session::put('locale', $locale); // Update session with valid locale
            }
            
            App::setLocale($locale);
        }

        return $next($request);
    }
} 