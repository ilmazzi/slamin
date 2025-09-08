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
     * Ottieni le lingue disponibili per il sito (solo quelle presenti nelle cartelle)
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
     * Ottieni tutte le lingue del mondo per il profilo utente
     */
    public static function getAllWorldLanguages()
    {
        $languages = [];

        $allLanguageCodes = [
            // Lingue Europee
            'it', 'en', 'es', 'fr', 'de', 'pt', 'pt-br', 'nl', 'pl', 'ru',
            'sv', 'no', 'da', 'fi', 'cs', 'hu', 'ro', 'bg', 'hr', 'sk',
            'sl', 'et', 'lv', 'lt', 'el', 'tr', 'he', 'uk', 'be', 'ka',
            'hy', 'az', 'kk', 'ky', 'uz', 'tg', 'mn',

            // Lingue Asiatiche
            'ja', 'zh', 'ko', 'ar', 'hi', 'th', 'vi', 'id', 'ms', 'tl',
            'my', 'km', 'lo', 'si', 'ne', 'bn', 'ur', 'fa', 'ku', 'ps',

            // Lingue Africane
            'sw', 'am', 'yo', 'ig', 'ha', 'zu', 'af', 'xh'
        ];

        foreach ($allLanguageCodes as $code) {
            $languages[$code] = self::getLanguageNameStatic($code);
        }

        return $languages;
    }

    /**
     * Ottieni il nome della lingua (versione statica)
     */
    private static function getLanguageNameStatic($code)
    {
        $names = [
            // Lingue Europee
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
            'sv' => 'Svenska',
            'no' => 'Norsk',
            'da' => 'Dansk',
            'fi' => 'Suomi',
            'cs' => 'Čeština',
            'hu' => 'Magyar',
            'ro' => 'Română',
            'bg' => 'Български',
            'hr' => 'Hrvatski',
            'sk' => 'Slovenčina',
            'sl' => 'Slovenščina',
            'et' => 'Eesti',
            'lv' => 'Latviešu',
            'lt' => 'Lietuvių',
            'el' => 'Ελληνικά',
            'tr' => 'Türkçe',
            'he' => 'עברית',
            'uk' => 'Українська',
            'be' => 'Беларуская',
            'ka' => 'ქართული',
            'hy' => 'Հայերեն',
            'az' => 'Azərbaycan',
            'kk' => 'Қазақша',
            'ky' => 'Кыргызча',
            'uz' => 'O\'zbek',
            'tg' => 'Тоҷикӣ',
            'mn' => 'Монгол',

            // Lingue Asiatiche
            'ja' => '日本語',
            'zh' => '中文',
            'ko' => '한국어',
            'ar' => 'العربية',
            'hi' => 'हिन्दी',
            'th' => 'ไทย',
            'vi' => 'Tiếng Việt',
            'id' => 'Bahasa Indonesia',
            'ms' => 'Bahasa Melayu',
            'tl' => 'Filipino',
            'my' => 'မြန်မာ',
            'km' => 'ខ្មែរ',
            'lo' => 'ລາວ',
            'si' => 'සිංහල',
            'ne' => 'नेपाली',
            'bn' => 'বাংলা',
            'ur' => 'اردو',
            'fa' => 'فارسی',
            'ku' => 'Kurdî',
            'ps' => 'پښتو',

            // Lingue Africane
            'sw' => 'Kiswahili',
            'am' => 'አማርኛ',
            'yo' => 'Yorùbá',
            'ig' => 'Igbo',
            'ha' => 'Hausa',
            'zu' => 'IsiZulu',
            'af' => 'Afrikaans',
            'xh' => 'IsiXhosa',
        ];

        return $names[$code] ?? ucfirst($code);
    }

    /**
     * Ottieni il nome della lingua
     */
    private function getLanguageName($code)
    {
        $names = [
            // Lingue Europee
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
            'sv' => 'Svenska',
            'no' => 'Norsk',
            'da' => 'Dansk',
            'fi' => 'Suomi',
            'cs' => 'Čeština',
            'hu' => 'Magyar',
            'ro' => 'Română',
            'bg' => 'Български',
            'hr' => 'Hrvatski',
            'sk' => 'Slovenčina',
            'sl' => 'Slovenščina',
            'et' => 'Eesti',
            'lv' => 'Latviešu',
            'lt' => 'Lietuvių',
            'el' => 'Ελληνικά',
            'tr' => 'Türkçe',
            'he' => 'עברית',
            'uk' => 'Українська',
            'be' => 'Беларуская',
            'ka' => 'ქართული',
            'hy' => 'Հայերեն',
            'az' => 'Azərbaycan',
            'kk' => 'Қазақша',
            'ky' => 'Кыргызча',
            'uz' => 'O\'zbek',
            'tg' => 'Тоҷикӣ',
            'mn' => 'Монгол',

            // Lingue Asiatiche
            'ja' => '日本語',
            'zh' => '中文',
            'ko' => '한국어',
            'ar' => 'العربية',
            'hi' => 'हिन्दी',
            'th' => 'ไทย',
            'vi' => 'Tiếng Việt',
            'id' => 'Bahasa Indonesia',
            'ms' => 'Bahasa Melayu',
            'tl' => 'Filipino',
            'my' => 'မြန်မာ',
            'km' => 'ខ្មែរ',
            'lo' => 'ລາວ',
            'si' => 'සිංහල',
            'ne' => 'नेपाली',
            'bn' => 'বাংলা',
            'ur' => 'اردو',
            'fa' => 'فارسی',
            'ku' => 'Kurdî',
            'ps' => 'پښتو',

            // Lingue Africane
            'sw' => 'Kiswahili',
            'am' => 'አማርኛ',
            'yo' => 'Yorùbá',
            'ig' => 'Igbo',
            'ha' => 'Hausa',
            'zu' => 'IsiZulu',
            'af' => 'Afrikaans',
            'xh' => 'IsiXhosa',
        ];

        return $names[$code] ?? ucfirst($code);
    }

    /**
     * Ottieni il codice della bandiera per una lingua
     */
    public static function getFlagCode($code)
    {
        $flagCodes = [
            // Lingue Europee
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
            'sv' => 'swe',
            'no' => 'nor',
            'da' => 'dnk',
            'fi' => 'fin',
            'cs' => 'cze',
            'hu' => 'hun',
            'ro' => 'rou',
            'bg' => 'bgr',
            'hr' => 'hrv',
            'sk' => 'svk',
            'sl' => 'svn',
            'et' => 'est',
            'lv' => 'lva',
            'lt' => 'ltu',
            'el' => 'grc',
            'tr' => 'tur',
            'he' => 'isr',
            'uk' => 'ukr',
            'be' => 'blr',
            'ka' => 'geo',
            'hy' => 'arm',
            'az' => 'aze',
            'kk' => 'kaz',
            'ky' => 'kgz',
            'uz' => 'uzb',
            'tg' => 'tjk',
            'mn' => 'mng',

            // Lingue Asiatiche
            'ja' => 'jpn',
            'zh' => 'chn',
            'ko' => 'kor',
            'ar' => 'sau',
            'hi' => 'ind',
            'th' => 'tha',
            'vi' => 'vnm',
            'id' => 'idn',
            'ms' => 'mys',
            'tl' => 'phl',
            'my' => 'mmr',
            'km' => 'khm',
            'lo' => 'lao',
            'si' => 'lka',
            'ne' => 'npl',
            'bn' => 'bgd',
            'ur' => 'pak',
            'fa' => 'irn',
            'ku' => 'irq',
            'ps' => 'afg',

            // Lingue Africane
            'sw' => 'tza',
            'am' => 'eth',
            'yo' => 'nga',
            'ig' => 'nga',
            'ha' => 'nga',
            'zu' => 'zaf',
            'af' => 'zaf',
            'xh' => 'zaf',
        ];

        return $flagCodes[$code] ?? 'ita';
    }
}
