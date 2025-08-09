<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Applications
    |--------------------------------------------------------------------------
    | Elenco delle app servite da Reverb. La chiave nel path (/app/{key})
    | deve esistere qui, altrimenti "Application does not exist".
    */
    'apps' => [
        [
            'id' => env('REVERB_APP_ID', 'app'),
            'name' => env('APP_NAME', 'Laravel'),
            'key' => env('REVERB_APP_KEY'),
            'secret' => env('REVERB_APP_SECRET'),
            'path' => env('REVERB_APP_PATH', ''),
            'capacity' => env('REVERB_APP_CAPACITY', null),
            'enable_client_messages' => (bool) env('REVERB_ENABLE_CLIENT_MESSAGES', false),
            'enable_statistics' => (bool) env('REVERB_ENABLE_STATISTICS', false),
            'routes' => [
                'websocket' => '/app',
                'api'       => '/apps',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Server (BIND INTERNO)
    |--------------------------------------------------------------------------
    | ATTENZIONE: qui va l'IP locale/porta su cui il processo si mette in ascolto.
    | NON mettere il dominio pubblico qui.
    */
    'servers' => [
        'reverb' => [
            // <<< QUI forziamo bind locale >>>
            'host' => env('REVERB_HOST', '127.0.0.1'),
            'port' => (int) env('REVERB_PORT', 8080),

            // Opzionali/compat: non impattano il bind TCP
            'path' => env('REVERB_SERVER_PATH', ''),
            // "hostname" è opzionale; lo lasciamo uguale al client host per compat,
            // ma NON viene usato per il bind TCP.
            'hostname' => env('REVERB_CLIENT_HOST', 'localhost'),

            // Opzioni socket aggiuntive (lasciamo vuote)
            'options' => [
                // es: 'tcp_nodelay' => true,
            ],

            // Limite dimensione richiesta (byte)
            'max_request_size' => (int) env('REVERB_MAX_REQUEST_SIZE', 10000),

            // Replica/scaling (facoltativo, default off)
            'scaling' => [
                'enabled' => (bool) env('REVERB_REPLICATION_ENABLED', false),
                'channel' => env('REVERB_REPLICATION_CHANNEL', 'reverb'),
                'server' => [
                    'url'      => env('REVERB_REPLICATION_URL', null),
                    'host'     => env('REDIS_HOST', '127.0.0.1'),
                    'port'     => (string) env('REDIS_PORT', '6379'),
                    'username' => env('REDIS_USERNAME', null),
                    'password' => env('REDIS_PASSWORD', ''),
                    'database' => (string) env('REDIS_DB', '0'),
                    'timeout'  => (int) env('REDIS_TIMEOUT', 60),
                ],
            ],

            // Intervalli di ingest (se usi Pulse/Telescope)
            'pulse_ingest_interval'     => (int) env('REVERB_PULSE_INGEST_INTERVAL', 15),
            'telescope_ingest_interval' => (int) env('REVERB_TELESCOPE_INGEST_INTERVAL', 15),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Connessioni broadcasting verso Reverb
    |--------------------------------------------------------------------------
    | Queste sono usate da Laravel per pubblicare gli eventi. Qui usi l'host
    | pubblico dietro Nginx (wss://...).
    */
    'connections' => [
        'reverb' => [
            'driver'  => 'reverb',
            'key'     => env('REVERB_APP_KEY'),
            'secret'  => env('REVERB_APP_SECRET'),
            'app_id'  => env('REVERB_APP_ID'),
            'options' => [
                'host'   => env('REVERB_CLIENT_HOST', 'localhost'),
                'port'   => (int) env('REVERB_CLIENT_PORT', 443),
                'scheme' => env('REVERB_CLIENT_SCHEME', 'https'),
                'useTLS' => env('REVERB_CLIENT_SCHEME', 'https') === 'https',
                'timeout' => (int) env('REVERB_TIMEOUT', 30),
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | (Altre sezioni Reverb se presenti nella tua versione)
    |--------------------------------------------------------------------------
    */
];
