<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Categorie poesie
    |--------------------------------------------------------------------------
    |
    | Definizione delle categorie disponibili per le poesie
    |
    */
    'categories' => [
        'love' => 'Amore',
        'nature' => 'Natura',
        'social' => 'Sociale',
        'politics' => 'Politica',
        'personal' => 'Personale',
        'philosophy' => 'Filosofia',
        'religion' => 'Religione',
        'war' => 'Guerra',
        'peace' => 'Pace',
        'death' => 'Morte',
        'life' => 'Vita',
        'friendship' => 'Amicizia',
        'family' => 'Famiglia',
        'work' => 'Lavoro',
        'travel' => 'Viaggio',
        'other' => 'Altro'
    ],

    /*
    |--------------------------------------------------------------------------
    | Tipi di poesia
    |--------------------------------------------------------------------------
    |
    | Definizione dei tipi di poesia disponibili
    |
    */
    'poem_types' => [
        'free_verse' => 'Verso libero',
        'sonnet' => 'Sonetto',
        'haiku' => 'Haiku',
        'limerick' => 'Limerick',
        'ballad' => 'Ballata',
        'ode' => 'Ode',
        'elegy' => 'Elegia',
        'epic' => 'Poema epico',
        'other' => 'Altro'
    ],


    /*
    |--------------------------------------------------------------------------
    | Limiti e restrizioni
    |--------------------------------------------------------------------------
    |
    | Limiti per la creazione e gestione delle poesie
    |
    */
    'limits' => [
        'max_poems_per_user' => env('POEMS_MAX_PER_USER', 50),
        'max_drafts_per_user' => env('POEMS_MAX_DRAFTS_PER_USER', 20),
        'max_title_length' => 255,
        'min_content_length' => 10,
        'max_content_length' => 10000,
        'max_description_length' => 500,
        'max_tags_per_poem' => 10,
        'max_tag_length' => 50,
    ],

    /*
    |--------------------------------------------------------------------------
    | Impostazioni di visualizzazione
    |--------------------------------------------------------------------------
    |
    | Configurazioni per la visualizzazione delle poesie
    |
    */
    'display' => [
        'poems_per_page' => 12,
        'related_poems_limit' => 4,
        'excerpt_length' => 150,
        'show_word_count' => true,
        'show_reading_time' => true,
        'words_per_minute' => 200,
    ],

    /*
    |--------------------------------------------------------------------------
    | Impostazioni di traduzione
    |--------------------------------------------------------------------------
    |
    | Configurazioni per il sistema di traduzione
    |
    */
    'translation' => [
        'enabled' => env('POEMS_TRANSLATION_ENABLED', true),
        'auto_translate' => env('POEMS_AUTO_TRANSLATE', false),
        'max_translation_price' => 1000,
        'min_translation_price' => 0,
        'translation_commission' => 0.10, // 10% di commissione
    ],

    /*
    |--------------------------------------------------------------------------
    | Impostazioni di condivisione
    |--------------------------------------------------------------------------
    |
    | Configurazioni per la condivisione delle poesie
    |
    */
    'sharing' => [
        'social_media' => [
            'facebook' => true,
            'twitter' => true,
            'linkedin' => true,
            'whatsapp' => true,
        ],
        'embed_enabled' => true,
        'download_enabled' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Impostazioni di notifiche
    |--------------------------------------------------------------------------
    |
    | Configurazioni per le notifiche relative alle poesie
    |
    */
    'notifications' => [
        'new_poem_published' => true,
        'poem_liked' => true,
        'poem_commented' => true,
        'poem_bookmarked' => true,
        'translation_requested' => true,
        'poem_approved' => true,
        'poem_rejected' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Impostazioni di cache
    |--------------------------------------------------------------------------
    |
    | Configurazioni per la cache delle poesie
    |
    */
    'cache' => [
        'enabled' => env('POEMS_CACHE_ENABLED', true),
        'ttl' => env('POEMS_CACHE_TTL', 3600), // 1 ora
        'popular_poems_ttl' => env('POEMS_POPULAR_CACHE_TTL', 1800), // 30 minuti
    ],

    /*
    |--------------------------------------------------------------------------
    | Impostazioni di ricerca
    |--------------------------------------------------------------------------
    |
    | Configurazioni per la ricerca delle poesie
    |
    */
    'search' => [
        'min_query_length' => 3,
        'max_results' => 100,
        'highlight_enabled' => true,
        'fuzzy_search' => true,
        'searchable_fields' => [
            'title',
            'content',
            'description',
            'tags',
            'user.name'
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Lingue supportate
    |--------------------------------------------------------------------------
    |
    | Definizione delle lingue supportate per le poesie
    |
    */
    'languages' => [
        'it' => 'Italiano',
        'en' => 'English',
        'fr' => 'Français',
        'es' => 'Español',
        'de' => 'Deutsch',
        'pt' => 'Português',
        'ru' => 'Русский',
        'ar' => 'العربية',
        'zh' => '中文',
        'ja' => '日本語',
        'ko' => '한국어',
        'hi' => 'हिन्दी',
        'tr' => 'Türkçe',
        'nl' => 'Nederlands',
        'pl' => 'Polski',
        'sv' => 'Svenska',
        'da' => 'Dansk',
        'no' => 'Norsk',
        'fi' => 'Suomi',
        'cs' => 'Čeština',
        'sk' => 'Slovenčina',
        'hu' => 'Magyar',
        'ro' => 'Română',
        'bg' => 'Български',
        'hr' => 'Hrvatski',
        'sl' => 'Slovenščina',
        'et' => 'Eesti',
        'lv' => 'Latviešu',
        'lt' => 'Lietuvių',
        'mt' => 'Malti',
        'ga' => 'Gaeilge',
        'cy' => 'Cymraeg',
        'eu' => 'Euskara',
        'ca' => 'Català',
        'gl' => 'Galego',
        'is' => 'Íslenska',
        'fo' => 'Føroyskt',
        'sq' => 'Shqip',
        'mk' => 'Македонски',
        'sr' => 'Српски',
        'bs' => 'Bosanski',
        'me' => 'Crnogorski',
        'uk' => 'Українська',
        'be' => 'Беларуская',
        'kk' => 'Қазақша',
        'ky' => 'Кыргызча',
        'uz' => 'Oʻzbekcha',
        'tg' => 'Тоҷикӣ',
        'mn' => 'Монгол',
        'ka' => 'ქართული',
        'hy' => 'Հայերեն',
        'az' => 'Azərbaycanca',
        'fa' => 'فارسی',
        'ps' => 'پښتو',
        'ur' => 'اردو',
        'bn' => 'বাংলা',
        'si' => 'සිංහල',
        'my' => 'မြန်မာဘာသာ',
        'th' => 'ไทย',
        'lo' => 'ລາວ',
        'km' => 'ខ្មែរ',
        'vi' => 'Tiếng Việt',
        'id' => 'Bahasa Indonesia',
        'ms' => 'Bahasa Melayu',
        'tl' => 'Tagalog',
        'ceb' => 'Cebuano',
        'jv' => 'Basa Jawa',
        'su' => 'Basa Sunda',
        'he' => 'עברית',
        'yi' => 'יידיש',
        'am' => 'አማርኛ',
        'sw' => 'Kiswahili',
        'zu' => 'isiZulu',
        'af' => 'Afrikaans',
        'xh' => 'isiXhosa',
        'st' => 'Sesotho',
        'tn' => 'Setswana',
        'ss' => 'siSwati',
        've' => 'Tshivenda',
        'ts' => 'Xitsonga',
        'nr' => 'isiNdebele',
        'sn' => 'chiShona',
        'rw' => 'Ikinyarwanda',
        'lg' => 'Luganda',
        'ak' => 'Akan',
        'yo' => 'Yorùbá',
        'ig' => 'Igbo',
        'ha' => 'Hausa',
        'ff' => 'Fulfulde',
        'wo' => 'Wolof',
        'so' => 'Soomaali',
        'om' => 'Afaan Oromoo',
        'ti' => 'ትግርኛ',
        'aa' => 'Afar',
        'dz' => 'རྫོང་ཁ',
        'bo' => 'བོད་ཡིག',
    ],
];
