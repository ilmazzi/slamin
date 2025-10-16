<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Table Names
    |--------------------------------------------------------------------------
    |
    | Customize the table names used by the chat system.
    |
    */
    'table_prefix' => 'chat_',

    /*
    |--------------------------------------------------------------------------
    | Storage Configuration
    |--------------------------------------------------------------------------
    |
    | Configure where chat attachments are stored.
    |
    */
    'storage' => [
        'disk' => env('CHAT_STORAGE_DISK', 'public'),
        'folder' => env('CHAT_STORAGE_FOLDER', 'chat'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Routes Configuration
    |--------------------------------------------------------------------------
    |
    */
    'routes' => [
        'prefix' => 'chat',
        'middleware' => ['web', 'auth'],
        'index_name' => 'chat.index',
        'view_name' => 'chat.show',
    ],

    /*
    |--------------------------------------------------------------------------
    | Features
    |--------------------------------------------------------------------------
    |
    */
    'features' => [
        'max_group_members' => 256,
        'use_uuid' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Search Configuration
    |--------------------------------------------------------------------------
    |
    | Define which fields are searchable when looking for users to chat with.
    |
    */
    'searchable_fields' => ['name', 'email'],

    /*
    |--------------------------------------------------------------------------
    | Broadcasting
    |--------------------------------------------------------------------------
    |
    */
    'broadcasting' => [
        'enabled' => true,
        'connection' => env('BROADCAST_CONNECTION', 'reverb'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Layout Configuration
    |--------------------------------------------------------------------------
    |
    | Define the layout to use for chat pages.
    |
    */
    'layout' => 'layout.master',
    'home_route' => 'home',
];

