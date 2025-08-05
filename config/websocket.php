<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WebSocket Server Configuration
    |--------------------------------------------------------------------------
    |
    | Configurazione per il server WebSocket personalizzato
    |
    */

    'host' => env('WEBSOCKET_HOST', '127.0.0.1'),
    'port' => env('WEBSOCKET_PORT', 8080),
    
    /*
    |--------------------------------------------------------------------------
    | WebRTC Configuration
    |--------------------------------------------------------------------------
    |
    | Configurazione per WebRTC (STUN/TURN servers)
    |
    */
    
    'webrtc' => [
        'ice_servers' => [
            [
                'urls' => 'stun:stun.l.google.com:19302'
            ],
            [
                'urls' => 'stun:stun1.l.google.com:19302'
            ]
        ]
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    |
    | Configurazione per l'autenticazione WebSocket
    |
    */
    
    'auth' => [
        'token_header' => 'X-CSRF-TOKEN',
        'session_lifetime' => 120, // minuti
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Message Types
    |--------------------------------------------------------------------------
    |
    | Tipi di messaggi supportati dal WebSocket
    |
    */
    
    'message_types' => [
        'auth',
        'message',
        'typing',
        'call_request',
        'call_response',
        'webrtc_signal',
        'join_chat',
        'leave_chat',
        'user_status'
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    |
    | Configurazione per il logging del WebSocket
    |
    */
    
    'logging' => [
        'enabled' => env('WEBSOCKET_LOGGING', true),
        'channel' => env('WEBSOCKET_LOG_CHANNEL', 'websocket'),
    ],
]; 