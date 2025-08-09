<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Reverb Apps (hardcoded per eliminare mismatch)
    |--------------------------------------------------------------------------
    */
    'apps' => [
        [
            'id' => '170567',
            'name' => 'Laravel',
            'key' => '1ozks0rqd5pwzgcdqzdi',
            'secret' => 'u5ms09akdgns36vbrzoo',
            'path' => '',
            'capacity' => null,
            'enable_client_messages' => false,
            'enable_statistics' => false,
            'routes' => [
                'websocket' => '/app',
                'api' => '/apps',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Server (queste opzioni NON servono al bind: avviamo da CLI con --host/--port)
    |--------------------------------------------------------------------------
    | Le lasciamo con valori sensati ma il bind reale lo decide il comando artisan.
    */
    'server' => [
        'host' => 'ws.slamin.it',
        'port' => 443,
        'scheme' => 'https',
        'secure' => true,
    ],

    'ssl' => [
        'local_cert' => null,
        'local_pk' => null,
        'passphrase' => null,
        'verify_peer' => false,
    ],

    'max_request_size' => 10000,
    'max_connections' => 10000,
];
