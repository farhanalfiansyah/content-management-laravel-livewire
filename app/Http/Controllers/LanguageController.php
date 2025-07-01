<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    /**
     * Switch the application language
     */
    public function switch(Request $request, string $locale): RedirectResponse
    {
        $supportedLocales = ['en', 'id'];

        if (!in_array($locale, $supportedLocales)) {
            return redirect()->back();
        }

        App::setLocale($locale);
        Session::put('locale', $locale);

        return redirect()->back();
    }

    /**
     * Get supported locales
     */
    public function getSupportedLocales()
    {
        return response()->json([
            'en' => ['name' => 'English', 'native' => 'English', 'flag' => '🇺🇸'],
            'id' => ['name' => 'Indonesian', 'native' => 'Bahasa Indonesia', 'flag' => '🇮🇩'],
            'es' => ['name' => 'Spanish', 'native' => 'Español', 'flag' => '🇪🇸'],
            'fr' => ['name' => 'French', 'native' => 'Français', 'flag' => '🇫🇷'],
            'de' => ['name' => 'German', 'native' => 'Deutsch', 'flag' => '🇩🇪'],
            'ar' => ['name' => 'Arabic', 'native' => 'العربية', 'flag' => '🇸🇦']
        ]);
    }

    /**
     * Get current locale info
     */
    public function getCurrentLocale()
    {
        $currentLocale = App::getLocale();
        $supportedLocales = $this->getSupportedLocales()->getData(true);
        
        return response()->json([
            'current' => $currentLocale,
            'info' => $supportedLocales[$currentLocale] ?? $supportedLocales['en']
        ]);
    }
} 