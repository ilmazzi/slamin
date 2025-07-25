<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configurazione Poesie
    |--------------------------------------------------------------------------
    |
    | Configurazioni per il sistema di gestione poesie
    |
    */

    // Categorie disponibili per le poesie
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

    // Tipi di poesia
    'poem_types' => [
        'free_verse' => 'Verso libero',
        'sonnet' => 'Sonetto',
        'haiku' => 'Haiku',
        'limerick' => 'Limerick',
        'other' => 'Altro'
    ],

    // Stati di moderazione
    'moderation_statuses' => [
        'pending' => 'In attesa',
        'approved' => 'Approvata',
        'rejected' => 'Rifiutata'
    ],

    // Lingue supportate
    'languages' => [
        'it' => 'Italiano',
        'en' => 'English',
        'es' => 'Español',
        'fr' => 'Français',
        'de' => 'Deutsch',
        'pt' => 'Português',
        'ru' => 'Русский',
        'zh' => '中文',
        'ja' => '日本語',
        'ko' => '한국어',
        'ar' => 'العربية',
        'hi' => 'हिन्दी'
    ],

    // Limiti e configurazioni
    'limits' => [
        'max_title_length' => 255,
        'min_content_length' => 10,
        'max_content_length' => 10000,
        'max_description_length' => 500,
        'max_tags' => 10,
        'max_tag_length' => 50,
        'max_thumbnail_size' => 2048, // KB
        'max_translation_price' => 1000,
        'max_comment_length' => 1000,
        'min_comment_length' => 3,
        'poems_per_page' => 12,
        'comments_per_page' => 10,
        'related_poems_limit' => 4
    ],

    // Configurazioni per l'upload
    'upload' => [
        'thumbnail_path' => 'poems/thumbnails',
        'allowed_image_types' => ['jpeg', 'png', 'jpg', 'gif', 'webp'],
        'max_image_size' => 2048, // KB
        'image_quality' => 85,
        'thumbnail_sizes' => [
            'small' => [150, 150],
            'medium' => [300, 300],
            'large' => [600, 600]
        ]
    ],

    // Configurazioni per la moderazione
    'moderation' => [
        'auto_approve_verified_users' => true,
        'auto_approve_admin_users' => true,
        'require_moderation_for_new_users' => true,
        'moderation_queue_limit' => 100
    ],

    // Configurazioni per le traduzioni
    'translation' => [
        'enabled' => true,
        'auto_translate' => false,
        'min_price' => 5,
        'max_price' => 1000,
        'default_price' => 25,
        'commission_percentage' => 10, // Percentuale trattenuta dalla piattaforma
        'supported_target_languages' => [
            'en', 'es', 'fr', 'de', 'pt', 'ru', 'zh', 'ja', 'ko', 'ar', 'hi'
        ]
    ],

    // Configurazioni per l'analytics
    'analytics' => [
        'track_views' => true,
        'track_time_spent' => true,
        'track_shares' => true,
        'track_bookmarks' => true,
        'retention_days' => 90
    ],

    // Configurazioni per la ricerca
    'search' => [
        'enable_fulltext_search' => true,
        'searchable_fields' => ['title', 'content', 'description', 'tags'],
        'highlight_results' => true,
        'max_search_results' => 100
    ],

    // Configurazioni per la cache
    'cache' => [
        'enabled' => true,
        'ttl' => 3600, // 1 ora
        'popular_poems_cache_ttl' => 1800, // 30 minuti
        'user_poems_cache_ttl' => 600 // 10 minuti
    ],

    // Configurazioni per le notifiche
    'notifications' => [
        'poem_approved' => true,
        'poem_rejected' => true,
        'new_comment' => true,
        'new_like' => true,
        'translation_request' => true,
        'translation_completed' => true
    ],

    // Configurazioni per la monetizzazione
    'monetization' => [
        'enabled' => false,
        'premium_poems' => false,
        'donations' => false,
        'subscription_required' => false,
        'free_poems_limit' => 10
    ],

    // Configurazioni per la sicurezza
    'security' => [
        'rate_limit_poem_creation' => 5, // poesie per ora
        'rate_limit_comments' => 10, // commenti per ora
        'rate_limit_likes' => 50, // like per ora
        'content_filtering' => true,
        'spam_protection' => true
    ]
]; 