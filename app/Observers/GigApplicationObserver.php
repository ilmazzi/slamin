<?php

namespace App\Observers;

use App\Models\GigApplication;

class GigApplicationObserver
{
    /**
     * Handle the GigApplication "created" event.
     */
    public function created(GigApplication $gigApplication): void
    {
        $gig = $gigApplication->gig;
        $gig->increment('application_count');
    }

    /**
     * Handle the GigApplication "updated" event.
     */
    public function updated(GigApplication $gigApplication): void
    {
        $gig = $gigApplication->gig;

        // Se lo status è cambiato da pending a accepted
        if ($gigApplication->wasChanged('status') &&
            $gigApplication->status === 'accepted' &&
            $gigApplication->getOriginal('status') === 'pending') {

            $gig->increment('accepted_applications_count');
        }

        // Se lo status è cambiato da accepted a rejected/withdrawn
        if ($gigApplication->wasChanged('status') &&
            $gigApplication->getOriginal('status') === 'accepted' &&
            in_array($gigApplication->status, ['rejected', 'withdrawn'])) {

            $gig->decrement('accepted_applications_count');
        }
    }

    /**
     * Handle the GigApplication "deleted" event.
     */
    public function deleted(GigApplication $gigApplication): void
    {
        $gig = $gigApplication->gig;
        $gig->decrement('application_count');

        if ($gigApplication->status === 'accepted') {
            $gig->decrement('accepted_applications_count');
        }
    }

    /**
     * Handle the GigApplication "restored" event.
     */
    public function restored(GigApplication $gigApplication): void
    {
        $gig = $gigApplication->gig;
        $gig->increment('application_count');

        if ($gigApplication->status === 'accepted') {
            $gig->increment('accepted_applications_count');
        }
    }

    /**
     * Handle the GigApplication "force deleted" event.
     */
    public function forceDeleted(GigApplication $gigApplication): void
    {
        $gig = $gigApplication->gig;
        $gig->decrement('application_count');

        if ($gigApplication->status === 'accepted') {
            $gig->decrement('accepted_applications_count');
        }
    }
}
