<?php

namespace App\Observers;

use App\Models\UnifiedLike;
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
    public function created(UnifiedLike $like): void
    {
        if ($like->user) {
            // Check and award badges for likes
            $this->badgeService->checkAndAwardBadge($like->user, 'likes', $like);
        }
    }
}

