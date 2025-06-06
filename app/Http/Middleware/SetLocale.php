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
        // Priority order for locale detection:
        // 1. URL parameter (?lang=es)
        // 2. Session stored locale
        // 3. User preference (if authenticated)
        // 4. Browser Accept-Language header
        // 5. Default app locale

        $locale = $this->detectLocale($request);
        
        // Validate locale is supported
        $availableLocales = array_keys(config('app.available_locales', ['es', 'en']));
        if (!in_array($locale, $availableLocales)) {
            $locale = config('app.locale', 'es');
        }

        // Set the application locale
        App::setLocale($locale);
        
        // Store in session for future requests
        Session::put('locale', $locale);

        return $next($request);
    }

    /**
     * Detect the preferred locale for the user
     */
    private function detectLocale(Request $request): string
    {
        // 1. Check URL parameter
        if ($request->has('lang')) {
            $langParam = $request->get('lang');
            if (in_array($langParam, ['es', 'en'])) {
                return $langParam;
            }
        }

        // 2. Check session
        if (Session::has('locale')) {
            return Session::get('locale');
        }

        // 3. Check authenticated user preference (if you add user locale field later)
        if (auth()->check() && auth()->user()->locale ?? false) {
            return auth()->user()->locale;
        }

        // 4. Check browser Accept-Language header
        $browserLang = $request->getPreferredLanguage(['es', 'en']);
        if ($browserLang) {
            return $browserLang === 'es' ? 'es' : 'en';
        }

        // 5. Default to Spanish (Houston market)
        return config('app.locale', 'es');
    }
} 