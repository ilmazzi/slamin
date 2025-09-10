<?php

namespace App\Observers;

use App\Models\Gig;
use App\Services\ActivityService;

class GigObserver
{
    /**
     * Handle the Gig "created" event.
     */
    public function created(Gig $gig): void
    {
        // Log activity
        if ($gig->user) {
            ActivityService::logCreate($gig->user, $gig, request());
        }
    }

    /**
     * Handle the Gig "updated" event.
     */
    public function updated(Gig $gig): void
    {
        // Log activity for important changes
        if ($gig->user && $gig->wasChanged(['title', 'description', 'status', 'is_closed'])) {
            ActivityService::logUpdate($gig->user, $gig, request());
        }
    }

    /**
     * Handle the Gig "deleted" event.
     */
    public function deleted(Gig $gig): void
    {
        // Log activity before deletion
        if ($gig->user) {
            ActivityService::logDelete(
                $gig->user,
                'App\\Models\\Gig',
                $gig->id,
                $gig->title ?? 'Gig',
                request()
            );
        }
    }

    /**
     * Handle the Gig "restored" event.
     */
    public function restored(Gig $gig): void
    {
        //
    }

    /**
     * Handle the Gig "force deleted" event.
     */
    public function forceDeleted(Gig $gig): void
    {
        //
    }
}
