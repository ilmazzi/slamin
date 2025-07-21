<?php

return [

    /*
    |--------------------------------------------------------------------------
    | PeerTube Configuration
    |--------------------------------------------------------------------------
    |
    | Configurazione per l'integrazione con l'istanza PeerTube
    | video.slamin.it
    |
    */

    // URL base dell'istanza PeerTube
    'base_url' => env('PEERTUBE_BASE_URL', 'https://video.slamin.it'),

    // Credenziali API (OAuth2)
    'username' => env('PEERTUBE_USERNAME', 'slamin'),
    'password' => env('PEERTUBE_PASSWORD'),

    // Configurazione canale
    'channel_id' => env('PEERTUBE_CHANNEL_ID'),
    'channel_name' => env('PEERTUBE_CHANNEL_NAME', 'SlamIn Poetry'),

    // Limiti upload
    'upload_limit_mb' => env('PEERTUBE_UPLOAD_LIMIT_MB', 500),
    'max_duration_minutes' => env('PEERTUBE_MAX_DURATION_MINUTES', 60),

    // Formati supportati
    'allowed_formats' => [
        'mp4', 'avi', 'mov', 'mkv', 'webm', 'flv'
    ],

    // Configurazione privacy
    'default_privacy' => env('PEERTUBE_DEFAULT_PRIVACY', 1), // 1=public, 2=unlisted, 3=private

    // Categoria default (15 = Entertainment)
    'default_category' => env('PEERTUBE_DEFAULT_CATEGORY', 15),

    // Lingua default
    'default_language' => env('PEERTUBE_DEFAULT_LANGUAGE', 'it'),

    // Tags default per Poetry Slam
    'default_tags' => [
        'poetry',
        'slam',
        'poesia',
        'performance',
        'slamin'
    ],

    // Configurazione transcoding
    'transcoding_enabled' => env('PEERTUBE_TRANSCODING_ENABLED', true),
    'transcoding_resolutions' => [
        '240p', '360p', '480p', '720p', '1080p'
    ],

    // Timeout per le richieste API
    'timeout' => env('PEERTUBE_TIMEOUT', 300), // 5 minuti

    // Retry configuration
    'max_retries' => env('PEERTUBE_MAX_RETRIES', 3),
    'retry_delay' => env('PEERTUBE_RETRY_DELAY', 5), // secondi

    // Webhook URL per notifiche
    'webhook_url' => env('PEERTUBE_WEBHOOK_URL'),

    // Configurazione cache
    'cache_ttl' => env('PEERTUBE_CACHE_TTL', 3600), // 1 ora

];
