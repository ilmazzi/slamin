<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Polymorphic Morph Map
    |--------------------------------------------------------------------------
    |
    | This array defines the mapping for polymorphic relationships.
    | This helps to avoid storing full class names in the database.
    |
    */
    'morph_map' => [
        'user' => \App\Models\User::class,
        'badge' => \App\Models\Badge::class,
        'user_badge' => \App\Models\UserBadge::class,
        'event' => \App\Models\Event::class,
        'gig' => \App\Models\Gig::class,
        'poem' => \App\Models\Poem::class,
        'video' => \App\Models\Video::class,
        'photo' => \App\Models\Photo::class,
        'article' => \App\Models\Article::class,
        'notification' => \App\Models\Notification::class,
        'activity' => \App\Models\Activity::class,
        'forum_post' => \App\Models\ForumPost::class,
        'forum_comment' => \App\Models\ForumComment::class,
    ],
];

