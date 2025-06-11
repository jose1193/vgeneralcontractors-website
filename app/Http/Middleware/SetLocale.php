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
        // Check if language is set in URL parameter
        if ($request->has('lang')) {
            $language = $request->get('lang');
            $availableLanguages = array_keys(LanguageHelper::getAvailableLanguages());
            
            if (in_array($language, $availableLanguages)) {
                LanguageHelper::setLanguage($language);
            }
        } else {
            // Set language from session or use default
            $language = LanguageHelper::getLanguageFromSession();
            App::setLocale($language);
        }

        return $next($request);
    }
} 