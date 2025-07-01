<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the supported locales from config
        $supportedLocales = array_keys(config('app.supported_locales', ['en' => []]));
        
        // Check if locale is passed in the URL
        $locale = $request->segment(1);
        
        // If URL has locale prefix and it's supported
        if (in_array($locale, $supportedLocales)) {
            App::setLocale($locale);
            Session::put('locale', $locale);
        } else {
            // Try to get locale from session
            $sessionLocale = Session::get('locale');
            
            if ($sessionLocale && in_array($sessionLocale, $supportedLocales)) {
                App::setLocale($sessionLocale);
            } else {
                // Try to detect from browser preference
                $browserLocale = $this->detectBrowserLocale($request, $supportedLocales);
                App::setLocale($browserLocale);
                Session::put('locale', $browserLocale);
            }
        }

        return $next($request);
    }

    /**
     * Detect browser preferred locale
     */
    private function detectBrowserLocale(Request $request, array $supportedLocales): string
    {
        $acceptLanguage = $request->header('Accept-Language');
        
        if (!$acceptLanguage) {
            return config('app.locale', 'en');
        }

        // Parse Accept-Language header
        $languages = [];
        preg_match_all('/([a-z]{1,8}(?:-[a-z]{1,8})?)\s*(?:;\s*q\s*=\s*(1(?:\.0{1,3})?|0(?:\.[0-9]{1,3})?))?/i', $acceptLanguage, $matches);

        if (count($matches[1])) {
            $languages = array_combine($matches[1], $matches[2]);
            
            // Set quality to 1 if not specified
            foreach ($languages as $lang => $val) {
                if ($val === '') {
                    $languages[$lang] = 1;
                }
            }
            
            // Sort by quality
            arsort($languages, SORT_NUMERIC);
        }

        // Find the best match
        foreach ($languages as $lang => $quality) {
            $lang = strtolower($lang);
            
            // Check exact match
            if (in_array($lang, $supportedLocales)) {
                return $lang;
            }
            
            // Check language prefix (e.g., 'en-US' -> 'en')
            $langPrefix = substr($lang, 0, 2);
            if (in_array($langPrefix, $supportedLocales)) {
                return $langPrefix;
            }
        }

        return config('app.locale', 'en');
    }
} 