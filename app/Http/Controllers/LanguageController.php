<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
use App\Helpers\LanguageHelper;

class LanguageController extends Controller
{
    /**
     * Switch language
     */
    public function switch(Request $request, string $language): RedirectResponse
    {
        // Available languages
        $availableLanguages = ['en', 'es'];
        
        if (!in_array($language, $availableLanguages)) {
            return redirect()->back()->withErrors(['language' => 'Invalid language selected']);
        }

        // Set language in session
        Session::put('locale', $language);
        App::setLocale($language);

        // Get redirect URL from request or fallback to previous page
        $redirectUrl = $request->get('redirect', url()->previous());
        
        // Flash success message
        Session::flash('language_changed', true);

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