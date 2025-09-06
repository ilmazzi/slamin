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
     * Ottieni l'emoji della bandiera per la lingua
     */
    public static function getLanguageFlag($code)
    {
        $flags = [
            'it' => '🇮🇹',
            'en' => '🇬🇧',
            'es' => '🇪🇸',
            'fr' => '🇫🇷',
            'de' => '🇩🇪',
            'pt' => '🇵🇹',
            'pt-br' => '🇧🇷',
            'nl' => '🇳🇱',
            'pl' => '🇵🇱',
            'ru' => '🇷🇺',
            'ja' => '🇯🇵',
            'zh' => '🇨🇳',
            'ar' => '🇸🇦',
            'hi' => '🇮🇳',
            'ko' => '🇰🇷',
        ];

        return $flags[$code] ?? '🌍';
    }
}
