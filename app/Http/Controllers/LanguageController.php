<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use App\Helpers\LanguageHelper;
use Illuminate\Support\Facades\Log;

class LanguageController extends Controller
{
    /**
     * Switch language
     */
    public function switch(Request $request, string $language): RedirectResponse
    {
        // Log the attempt
        Log::info('Language switch attempt', [
            'requested_language' => $language,
            'current_locale' => App::getLocale(),
            'session_locale' => Session::get('locale')
        ]);

        // Validate language
        $availableLanguages = array_keys(LanguageHelper::getAvailableLanguages());
        
        if (!in_array($language, $availableLanguages)) {
            Log::warning('Invalid language requested', ['language' => $language]);
            return redirect()->back()->withErrors(['language' => 'Invalid language selected']);
        }

        // Set language in session
        LanguageHelper::setLanguage($language);
        
        // Verify the change
        Log::info('Language set', [
            'new_locale' => App::getLocale(),
            'session_locale' => Session::get('locale')
        ]);

        // Get redirect URL from request or fallback to previous page
        $redirectUrl = $request->get('redirect', url()->previous());
        
        // Flash success message
        Session::flash('language_changed', LanguageHelper::trans('language_changed_successfully'));

        return redirect($redirectUrl);
    }

    /**
     * Get current language data as JSON
     */
    public function current(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'current' => LanguageHelper::getCurrentLanguageData(),
            'available' => LanguageHelper::getAvailableLanguages()
        ]);
    }

    /**
     * AJAX language switch
     */
    public function ajaxSwitch(Request $request): \Illuminate\Http\JsonResponse
    {
        $language = $request->input('language');
        
        Log::info('AJAX Language switch attempt', [
            'requested_language' => $language,
            'current_locale' => App::getLocale()
        ]);
        
        // Validate language
        $availableLanguages = array_keys(LanguageHelper::getAvailableLanguages());
        
        if (!in_array($language, $availableLanguages)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid language selected'
            ], 400);
        }

        // Set language
        LanguageHelper::setLanguage($language);

        return response()->json([
            'success' => true,
            'message' => LanguageHelper::trans('language_changed_successfully'),
            'current' => LanguageHelper::getCurrentLanguageData(),
            'reload_required' => true
        ]);
    }
} 