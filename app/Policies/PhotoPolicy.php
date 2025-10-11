<?php

namespace App\Policies;

use App\Models\Photo;
use App\Models\User;

class PhotoPolicy
{
    /**
     * Determine if the user can view any photos.
     */
    public function viewAny(?User $user): bool
    {
        // Anyone can view photos (including guests)
        return true;
    }

    /**
     * Determine if the user can view the photo.
     */
    public function view(?User $user, Photo $photo): bool
    {
        // Anyone can view approved photos (including guests)
        return $photo->status === 'approved';
    }

    /**
     * Determine if the user can create photos.
     */
    public function create(User $user): bool
    {
        // Any authenticated user can create photos
        return true;
    }

    /**
     * Determine if the user can update the photo.
     */
    public function update(User $user, Photo $photo): bool
    {
        // User can update their own photos OR admin/moderator can update any photo
        return $user->id === $photo->user_id 
            || $user->hasRole(['admin', 'moderator']);
    }

    /**
     * Determine if the user can delete the photo.
     */
    public function delete(User $user, Photo $photo): bool
    {
        // User can delete their own photos OR admin/moderator can delete any photo
        return $user->id === $photo->user_id 
            || $user->hasRole(['admin', 'moderator']);
    }

    /**
     * Determine if the user can restore the photo.
     */
    public function restore(User $user, Photo $photo): bool
    {
        // Only admin/moderator can restore
        return $user->hasRole(['admin', 'moderator']);
    }

    /**
     * Determine if the user can permanently delete the photo.
     */
    public function forceDelete(User $user, Photo $photo): bool
    {
        // Only admin can force delete
        return $user->hasRole('admin');
    }
}
