<?php

namespace App\Observers;

use App\Models\Follow;
use App\Services\ActivityService;

class FollowObserver
{
    /**
     * Handle the Follow "created" event.
     */
    public function created(Follow $follow): void
    {
        // Log activity for the follower
        if ($follow->follower) {
            ActivityService::logFollow($follow->follower, $follow->following, request());
        }
    }

    /**
     * Handle the Follow "updated" event.
     */
    public function updated(Follow $follow): void
    {
        //
    }

    /**
     * Handle the Follow "deleted" event.
     */
    public function deleted(Follow $follow): void
    {
        // Log activity for the unfollower
        if ($follow->follower) {
            ActivityService::logUnfollow($follow->follower, $follow->following, request());
        }
    }

    /**
     * Handle the Follow "restored" event.
     */
    public function restored(Follow $follow): void
    {
        //
    }

    /**
     * Handle the Follow "force deleted" event.
     */
    public function forceDeleted(Follow $follow): void
    {
        //
    }
}
