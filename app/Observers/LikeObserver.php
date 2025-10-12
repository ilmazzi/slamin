<?php

namespace App\Observers;

use App\Models\Like;
use App\Services\BadgeService;

class LikeObserver
{
    protected $badgeService;

    public function __construct(BadgeService $badgeService)
    {
        $this->badgeService = $badgeService;
    }

    /**
     * Handle the Like "created" event.
     */
    public function created(Like $like): void
    {
        if ($like->user) {
            // Check and award badges for likes
            $this->badgeService->checkAndAwardBadge($like->user, 'likes', $like);
        }
    }
}

