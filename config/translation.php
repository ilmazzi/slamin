<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Translation API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for external translation API services
    |
    */

    'api' => [
        'default_provider' => env('TRANSLATION_PROVIDER', 'google'),
        'api_key' => env('TRANSLATION_API_KEY'),
        'timeout' => env('TRANSLATION_TIMEOUT', 30),
        'retry_attempts' => env('TRANSLATION_RETRY_ATTEMPTS', 3),
    ],

    'providers' => [
        'google' => [
            'name' => 'Google Translate',
            'url' => 'https://translation.googleapis.com/language/translate/v2',
            'requires_key' => true,
            'rate_limit' => 1000000, // requests per day
            'cost_per_1m_chars' => 20, // USD
        ],
        'deepl' => [
            'name' => 'DeepL',
            'url' => 'https://api-free.deepl.com/v2/translate',
            'requires_key' => true,
            'rate_limit' => 500000, // requests per month (free tier)
            'cost_per_1m_chars' => 25, // USD
        ],
        'microsoft' => [
            'name' => 'Microsoft Translator',
            'url' => 'https://api.cognitive.microsofttranslator.com/translate',
            'requires_key' => true,
            'rate_limit' => 2000000, // requests per month (free tier)
            'cost_per_1m_chars' => 10, // USD
        ],
        'libre' => [
            'name' => 'LibreTranslate',
            'url' => 'https://libretranslate.com/translate',
            'requires_key' => false,
            'rate_limit' => 1000, // requests per hour (free tier)
            'cost_per_1m_chars' => 0, // Free
        ],
    ],

    'supported_languages' => [
        'en' => 'English',
        'es' => 'Spanish',
        'fr' => 'French',
        'de' => 'German',
        'pt' => 'Portuguese',
        'it' => 'Italian',
        'ru' => 'Russian',
        'ja' => 'Japanese',
        'ko' => 'Korean',
        'zh' => 'Chinese',
        'ar' => 'Arabic',
        'hi' => 'Hindi',
        'nl' => 'Dutch',
        'sv' => 'Swedish',
        'da' => 'Danish',
        'no' => 'Norwegian',
        'fi' => 'Finnish',
        'pl' => 'Polish',
        'tr' => 'Turkish',
        'cs' => 'Czech',
        'hu' => 'Hungarian',
        'ro' => 'Romanian',
        'bg' => 'Bulgarian',
        'hr' => 'Croatian',
        'sk' => 'Slovak',
        'sl' => 'Slovenian',
        'et' => 'Estonian',
        'lv' => 'Latvian',
        'lt' => 'Lithuanian',
        'uk' => 'Ukrainian',
        'be' => 'Belarusian',
        'mk' => 'Macedonian',
        'sq' => 'Albanian',
        'sr' => 'Serbian',
        'bs' => 'Bosnian',
        'mt' => 'Maltese',
        'is' => 'Icelandic',
        'ga' => 'Irish',
        'cy' => 'Welsh',
        'eu' => 'Basque',
        'ca' => 'Catalan',
        'gl' => 'Galician'
    ],

    'batch_size' => env('TRANSLATION_BATCH_SIZE', 100),
    'delay_between_requests' => env('TRANSLATION_DELAY', 1), // seconds
];
