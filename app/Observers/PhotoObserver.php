<?php

namespace App\Observers;

use App\Models\Photo;
use App\Services\ActivityService;

class PhotoObserver
{
    /**
     * Handle the Photo "created" event.
     */
    public function created(Photo $photo): void
    {
        // Log activity
        if ($photo->user) {
            ActivityService::logCreate($photo->user, $photo, request());
        }
    }

    /**
     * Handle the Photo "updated" event.
     */
    public function updated(Photo $photo): void
    {
        // Log activity for important changes
        if ($photo->user && $photo->wasChanged(['title', 'description', 'is_public'])) {
            ActivityService::logUpdate($photo->user, $photo, request());
        }
    }

    /**
     * Handle the Photo "deleted" event.
     */
    public function deleted(Photo $photo): void
    {
        // Log activity before deletion
        if ($photo->user) {
            ActivityService::logDelete(
                $photo->user,
                'App\\Models\\Photo',
                $photo->id,
                $photo->title ?? 'Foto',
                request()
            );
        }
    }

    /**
     * Handle the Photo "restored" event.
     */
    public function restored(Photo $photo): void
    {
        //
    }

    /**
     * Handle the Photo "force deleted" event.
     */
    public function forceDeleted(Photo $photo): void
    {
        //
    }
}
