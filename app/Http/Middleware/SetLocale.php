<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        if (session()->has('locale')) {
            app()->setLocale(session()->get('locale'));
        } else {
            app()->setLocale(config('app.fallback_locale')); // Usa el idioma predeterminado
        }
        return $next($request);
    }
}