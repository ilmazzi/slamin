<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\File;

class LanguageServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Condividi le lingue disponibili con tutte le viste
        View::composer('*', function ($view) {
            $view->with('availableLanguages', $this->getAvailableLanguages());
        });
    }

    /**
     * Ottieni le lingue disponibili
     */
    private function getAvailableLanguages()
    {
        $languages = [];
        $langPath = lang_path();

        if (File::exists($langPath)) {
            $directories = File::directories($langPath);

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

    /**
     * Ottieni il codice della bandiera per una lingua
     */
    public static function getFlagCode($code)
    {
        $flagCodes = [
            'en' => 'gbr',
            'de' => 'deu',
            'es' => 'esp',
            'fr' => 'fra',
            'pt' => 'prt',
            'pt-br' => 'bra',
            'nl' => 'nld',
            'pl' => 'pol',
            'ru' => 'rus',
            'ja' => 'jpn',
            'zh' => 'chn',
            'ar' => 'sau',
            'hi' => 'ind',
            'ko' => 'kor',
            'it' => 'ita',
        ];

        return $flagCodes[$code] ?? 'ita';
    }
}
