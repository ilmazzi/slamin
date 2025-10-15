<?php

return [
    'attachments' => [
        'media_mimes' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        'file_mimes' => ['pdf', 'doc', 'docx', 'txt', 'zip'],
        'max_uploads' => 5,
        'media_max_upload_size' => 12288, // KB
        'file_max_upload_size' => 5120, // KB
        'storage_disk' => 'public',
    ],
    'allow_file_attachments' => true,
    'allow_media_attachments' => true,
    'notifications' => [
        'main_sw_script' => 'sw.js',
    ],
    'user_model' => \App\Models\User::class,
    'user_searchable_fields' => ['name', 'email'],
    'morph_map' => [
        'user' => \App\Models\User::class,
        'conversation' => \App\Models\Chat\Conversation::class,
        'message' => \App\Models\Chat\Message::class,
        'participant' => \App\Models\Chat\Participant::class,
        'attachment' => \App\Models\Chat\Attachment::class,
        'chat_group' => \App\Models\Chat\Group::class,
        'action' => \App\Models\Chat\Action::class,
    ],
    'layout' => 'chat::layouts.app',
    'home_route' => '/',
];
