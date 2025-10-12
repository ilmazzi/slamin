<?php

namespace App\Observers;

use App\Models\Poem;
use App\Services\ActivityService;
use App\Services\BadgeService;

class PoemObserver
{
    protected $badgeService;

    public function __construct(BadgeService $badgeService)
    {
        $this->badgeService = $badgeService;
    }

    /**
     * Handle the Poem "created" event.
     */
    public function created(Poem $poem): void
    {
        // Log activity
        if ($poem->user) {
            ActivityService::logCreate($poem->user, $poem, request());
            
            // Check and award badges for poems
            $this->badgeService->checkAndAwardBadge($poem->user, 'poems', $poem);
        }
    }

    /**
     * Handle the Poem "updated" event.
     */
    public function updated(Poem $poem): void
    {
        // Log activity for important changes
        if ($poem->user && $poem->wasChanged(['title', 'content', 'is_public', 'is_draft'])) {
            ActivityService::logUpdate($poem->user, $poem, request());
        }
    }

    /**
     * Handle the Poem "deleted" event.
     */
    public function deleted(Poem $poem): void
    {
        // Log activity before deletion
        if ($poem->user) {
            ActivityService::logDelete(
                $poem->user,
                'App\\Models\\Poem',
                $poem->id,
                $poem->title ?? 'Poesia',
                request()
            );
        }
    }

    /**
     * Handle the Poem "restored" event.
     */
    public function restored(Poem $poem): void
    {
        //
    }

    /**
     * Handle the Poem "force deleted" event.
     */
    public function forceDeleted(Poem $poem): void
    {
        //
    }
}
