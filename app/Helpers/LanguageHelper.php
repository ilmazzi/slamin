<?php

namespace App\Helpers;

class LanguageHelper
{
    /**
     * Ottieni le lingue disponibili
     */
    public static function getAvailableLanguages()
    {
        $languages = [];
        $langPath = lang_path();

        if (file_exists($langPath)) {
            $directories = glob($langPath . '/*', GLOB_ONLYDIR);

            foreach ($directories as $directory) {
                $languageCode = basename($directory);
                $languages[$languageCode] = self::getLanguageName($languageCode);
            }
        }

        return $languages;
    }

    /**
     * Ottieni il nome della lingua
     */
    public static function getLanguageName($code)
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
     * Ottieni il codice paese per la lingua
     */
    public static function getLanguageFlag($code)
    {
        $flagCodes = [
            'it' => 'IT',
            'en' => 'GB',
            'es' => 'ES',
            'fr' => 'FR',
            'de' => 'DE',
            'pt' => 'PT',
            'pt-br' => 'BR',
            'nl' => 'NL',
            'pl' => 'PL',
            'ru' => 'RU',
            'ja' => 'JP',
            'zh' => 'CN',
            'ar' => 'SA',
            'hi' => 'IN',
            'ko' => 'KR',
        ];

        return $flagCodes[$code] ?? 'IT';
    }

    /**
     * Ottieni il codice CSS per la bandiera della lingua
     */
    public static function getLanguageFlagCode($code)
    {
        $flagCodes = [
            'it' => 'ita',
            'en' => 'gbr',
            'es' => 'esp',
            'fr' => 'fra',
            'de' => 'deu',
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
        ];

        return $flagCodes[$code] ?? 'ita';
    }

    /**
     * Ottieni il simbolo della bandiera per la lingua (alternativa compatibile)
     */
    public static function getLanguageSymbol($code)
    {
        $symbols = [
            'it' => '●',
            'en' => '●',
            'es' => '●',
            'fr' => '●',
            'de' => '●',
            'pt' => '●',
            'pt-br' => '●',
            'nl' => '●',
            'pl' => '●',
            'ru' => '●',
            'ja' => '●',
            'zh' => '●',
            'ar' => '●',
            'hi' => '●',
            'ko' => '●',
        ];

        return $symbols[$code] ?? '●';
    }
}
