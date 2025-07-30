<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class SetLocale
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Get available languages
        $availableLanguages = $this->getAvailableLanguages();

        // Handle language switch if requested via URL parameter
        if ($request->has('lang')) {
            $locale = $request->get('lang');
            if (array_key_exists($locale, $availableLanguages)) {
                session(['locale' => $locale]);

                // Redirect to clean URL without lang parameter
                return redirect()->to($request->url())->with('language_switched', true);
            }
        }

        // Check for language in session or use default
        $locale = session('locale', config('app.locale'));

        // Validate locale
        if (array_key_exists($locale, $availableLanguages)) {
            App::setLocale($locale);
        }

        return $next($request);
    }

    /**
     * Ottieni le lingue disponibili
     */
    private function getAvailableLanguages()
    {
        $languages = [];
        $langPath = lang_path();

        if (file_exists($langPath)) {
            $directories = glob($langPath . '/*', GLOB_ONLYDIR);

            foreach ($directories as $directory) {
                $languageCode = basename($directory);
                $languages[$languageCode] = $this->getLanguageName($languageCode);
            }
        }

        return $languages;
    }

    /**
     * Ottieni il nome della lingua
     */
    private function getLanguageName($code)
    {
        $names = [
            'it' => 'Italiano',
            'en' => 'English',
            'es' => 'Español',
            'fr' => 'Français',
            'de' => 'Deutsch',
            'pt' => 'Português',
            'pt-br' => 'Português (Brasil)',
            'nl' => 'Nederlands',
            'pl' => 'Polski',
            'ru' => 'Русский',
            'ja' => '日本語',
            'zh' => '中文',
            'ar' => 'العربية',
            'hi' => 'हिन्दी',
            'ko' => '한국어',
        ];

        return $names[$code] ?? ucfirst($code);
    }
}
