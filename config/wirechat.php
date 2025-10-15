<?php

return [
    'attachments' => [
        'media_mimes' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        'file_mimes' => ['pdf', 'doc', 'docx', 'txt', 'zip'],
        'max_uploads' => 5,
        'media_max_upload_size' => 12288, // KB
        'file_max_upload_size' => 5120, // KB
    ],
    'allow_file_attachments' => true,
    'allow_media_attachments' => true,
    'notifications' => [
        'main_sw_script' => 'sw.js',
    ],
];
