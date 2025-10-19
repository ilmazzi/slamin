<?php

namespace App\Observers;

use App\Models\UnifiedComment;
use App\Services\BadgeService;

class CommentObserver
{
    protected $badgeService;

    public function __construct(BadgeService $badgeService)
    {
        $this->badgeService = $badgeService;
    }

    /**
     * Handle the Comment "created" event.
     */
    public function created(UnifiedComment $comment): void
    {
        if ($comment->user) {
            // Check and award badges for comments
            $this->badgeService->checkAndAwardBadge($comment->user, 'comments', $comment);
        }
    }
}

