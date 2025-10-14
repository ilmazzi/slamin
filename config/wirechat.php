<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Use UUIDs for Conversations
    |--------------------------------------------------------------------------
    |
    | Determines the primary key type for the conversations table and related
    | relationships. When enabled, UUIDs (version 7 if supported, otherwise
    | version 4) will be used during initial migrations.
    |
    | ⚠️ This setting is intended for **new applications only** and does not
    | affect how new conversations are created at runtime. It controls whether
    | migrations generate UUID-based keys or unsigned big integers.
    |
    */
    'uses_uuid_for_conversations' => false,

    /*
    |--------------------------------------------------------------------------
    | Table Prefix
    |--------------------------------------------------------------------------
    |
    | This value will be prefixed to all Wirechat-related database tables.
    | Useful if you're sharing a database with other apps or packages.
    | ⚠️ This setting is intended for **new applications only**
    |
    */
    'table_prefix' => 'wirechat_',
    'models' => [
        'user'         => \App\Models\User::class,
        'conversation' => \Wirechat\Wirechat\Models\Conversation::class,
        'message'      => \Wirechat\Wirechat\Models\Message::class,
        'participant'  => \Wirechat\Wirechat\Models\Participant::class,

        // tuoi modelli di dominio usati nelle relazioni polimorfiche
        'video'        => \App\Models\Video::class,
        'poem'         => \App\Models\Poem::class,
        'article'      => \App\Models\Article::class,
        'photo'        => \App\Models\Photo::class,
        'gig'          => \App\Models\Gig::class,
        'event'        => \App\Models\Event::class,
        'group'        => \App\Models\Group::class,
        'comment'      => \App\Models\Comment::class, // <— AGGIUNTO
        'badge'        => \App\Models\Badge::class,   // <— AGGIUNTO
    ],

    /*
    |--------------------------------------------------------------------------
    | Morph map (alias salvato nel DB -> classe Eloquent)
    |--------------------------------------------------------------------------
    | Le CHIAVI sono gli alias che DEVONO comparire nelle colonne *_type.
    */
    'morph_map' => [
        // attori tipici / wirechat
        'user'         => \App\Models\User::class,
        'conversation' => \Wirechat\Wirechat\Models\Conversation::class,
        'message'      => \Wirechat\Wirechat\Models\Message::class,
        'participant'  => \Wirechat\Wirechat\Models\Participant::class,

        // dominio applicativo
        'video'        => \App\Models\Video::class,
        'poem'         => \App\Models\Poem::class,
        'article'      => \App\Models\Article::class,
        'photo'        => \App\Models\Photo::class,
        'gig'          => \App\Models\Gig::class,
        'event'        => \App\Models\Event::class,
        'group'        => \App\Models\Group::class,
        'comment'      => \App\Models\Comment::class,
        'badge'        => \App\Models\Badge::class,
    ],

    /*
     |--------------------------------------------------------------------------
     | Storage
     |--------------------------------------------------------------------------
     |
     | Global configuration for Wirechat file storage. Defines the disk,
     | directory, and visibility used for saving attachments.
     |
     */
    'storage' => [
        'disk' => 'public',
        'visibility' => 'public',
        'directories' => [
            'attachments' => 'attachments',
        ],
    ],
];
