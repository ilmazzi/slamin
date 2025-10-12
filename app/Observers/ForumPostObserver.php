<?php

namespace App\Observers;

use App\Models\ForumPost;
use App\Services\BadgeService;

class ForumPostObserver
{
    protected $badgeService;

    public function __construct(BadgeService $badgeService)
    {
        $this->badgeService = $badgeService;
    }

    /**
     * Handle the ForumPost "created" event.
     */
    public function created(ForumPost $forumPost): void
    {
        if ($forumPost->user) {
            // Check and award badges for forum posts
            $this->badgeService->checkAndAwardBadge($forumPost->user, 'posts', $forumPost);
        }
    }
}

