<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Presence (Redis + last_seen)
    |--------------------------------------------------------------------------
    */
    'ttl_seconds' => env('ONLINE_TTL_SECONDS', 120),

    // ogni quanto aggiornare last_seen_at su DB
    'db_update_interval_seconds' => env('ONLINE_DB_UPDATE_INTERVAL_SECONDS', 300),

    // prefisso per le chiavi Redis
    'redis_prefix' => env('ONLINE_REDIS_PREFIX', 'online:user:'),

    /*
    |--------------------------------------------------------------------------
    | Finestre temporali per gli stati non-online
    |--------------------------------------------------------------------------
    | Se la chiave Redis NON esiste:
    | - "recent": last_seen_at entro recent_window_seconds
    | - "idle"  : last_seen_at entro idle_window_seconds
    | - "offline": altrimenti
    */
    'recent_window_seconds' => env('ONLINE_RECENT_WINDOW_SECONDS', 300),   // 5 min
    'idle_window_seconds'   => env('ONLINE_IDLE_WINDOW_SECONDS',   1800),  // 30 min

    /*
    |--------------------------------------------------------------------------
    | UI defaults (etichette, classi, icone)
    |--------------------------------------------------------------------------
    */
   // config/online.php
'ui' => [
    'labels' => [
        'online'  => 'Online',
        'recent'  => 'Attivo di recente',
        'idle'    => 'Assente',
        'offline' => 'Offline',
    ],
    // ⚠️ Usa classi BACKGROUND, non text-*
    'classes' => [
        'online'  => 'bg-success',
        'recent'  => 'bg-info',
        'idle'    => 'bg-warning',
        'offline' => 'bg-secondary',
    ],
    'icons' => [
        'online'  => 'fas fa-circle',
        'recent'  => 'fas fa-clock',
        'idle'    => 'fas fa-moon',
        'offline' => 'far fa-circle',
    ],
],

];
