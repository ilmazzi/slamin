<?php

namespace App\Helpers;

class FlagHelper
{
    /**
     * Mappa dei codici lingua alle bandiere
     */
    private static $flagMap = [
        'it' => '🇮🇹',
        'en' => '🇺🇸',
        'fr' => '🇫🇷',
        'de' => '🇩🇪',
        'es' => '🇪🇸',
        'pt' => '🇵🇹',
        'ru' => '🇷🇺',
        'zh' => '🇨🇳',
        'ja' => '🇯🇵',
        'ko' => '🇰🇷',
        'ar' => '🇸🇦',
        'hi' => '🇮🇳',
        'nl' => '🇳🇱',
        'sv' => '🇸🇪',
        'no' => '🇳🇴',
        'da' => '🇩🇰',
        'fi' => '🇫🇮',
        'pl' => '🇵🇱',
        'cs' => '🇨🇿',
        'hu' => '🇭🇺',
        'ro' => '🇷🇴',
        'bg' => '🇧🇬',
        'hr' => '🇭🇷',
        'sk' => '🇸🇰',
        'sl' => '🇸🇮',
        'et' => '🇪🇪',
        'lv' => '🇱🇻',
        'lt' => '🇱🇹',
        'el' => '🇬🇷',
        'tr' => '🇹🇷',
        'he' => '🇮🇱',
        'th' => '🇹🇭',
        'vi' => '🇻🇳',
        'id' => '🇮🇩',
        'ms' => '🇲🇾',
        'tl' => '🇵🇭',
        'uk' => '🇺🇦',
        'be' => '🇧🇾',
        'ka' => '🇬🇪',
        'hy' => '🇦🇲',
        'az' => '🇦🇿',
        'kk' => '🇰🇿',
        'ky' => '🇰🇬',
        'uz' => '🇺🇿',
        'tg' => '🇹🇯',
        'mn' => '🇲🇳',
        'my' => '🇲🇲',
        'km' => '🇰🇭',
        'lo' => '🇱🇦',
        'si' => '🇱🇰',
        'ne' => '🇳🇵',
        'bn' => '🇧🇩',
        'ur' => '🇵🇰',
        'fa' => '🇮🇷',
        'ku' => '🇮🇶',
        'ps' => '🇦🇫',
        'sw' => '🇹🇿',
        'am' => '🇪🇹',
        'yo' => '🇳🇬',
        'ig' => '🇳🇬',
        'ha' => '🇳🇬',
        'zu' => '🇿🇦',
        'af' => '🇿🇦',
        'xh' => '🇿🇦',
        'st' => '🇿🇦',
        'tn' => '🇿🇦',
        'ss' => '🇿🇦',
        've' => '🇿🇦',
        'ts' => '🇿🇦',
        'nr' => '🇿🇦',
        'nso' => '🇿🇦',
    ];

    /**
     * Ottieni la bandiera per un codice lingua
     */
    public static function getFlag(string $languageCode): string
    {
        return self::$flagMap[strtolower($languageCode)] ?? '🌍';
    }

    /**
     * Ottieni la bandiera con il nome della lingua
     */
    public static function getFlagWithName(string $languageCode, string $languageName): string
    {
        $flag = self::getFlag($languageCode);
        return $flag . ' ' . $languageName;
    }

    /**
     * Ottieni l'HTML per la bandiera con il nome della lingua (usando flag-icon)
     */
    public static function getFlagIconWithName(string $languageCode, string $languageName): string
    {
        $flagCode = \App\Providers\LanguageServiceProvider::getFlagCode($languageCode);
        return '<i class="flag-icon flag-icon-' . $flagCode . ' me-1"></i>' . $languageName;
    }

    public static function getFlagIcon(string $languageCode, string $size = '16px'): string
    {
        $flagCode = \App\Providers\LanguageServiceProvider::getFlagCode($languageCode);
        return '<i class="flag-icon flag-icon-' . $flagCode . '" style="font-size: ' . $size . ';"></i>';
    }
}
