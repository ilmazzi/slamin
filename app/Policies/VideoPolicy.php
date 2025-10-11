<?php

namespace App\Policies;

use App\Models\Video;
use App\Models\User;

class VideoPolicy
{
    /**
     * Determine if the user can view any videos.
     */
    public function viewAny(?User $user): bool
    {
        // Anyone can view videos (including guests)
        return true;
    }

    /**
     * Determine if the user can view the video.
     */
    public function view(?User $user, Video $video): bool
    {
        // Anyone can view published videos (including guests)
        // Private videos only visible to owner, admin, or moderator
        if ($video->visibility === 'private') {
            return $user && ($user->id === $video->user_id || $user->hasRole(['admin', 'moderator']));
        }
        return true;
    }

    /**
     * Determine if the user can create videos.
     */
    public function create(User $user): bool
    {
        // Any authenticated user can create videos
        return true;
    }

    /**
     * Determine if the user can update the video.
     */
    public function update(User $user, Video $video): bool
    {
        // User can update their own videos OR admin/moderator can update any video
        return $user->id === $video->user_id 
            || $user->hasRole(['admin', 'moderator']);
    }

    /**
     * Determine if the user can delete the video.
     */
    public function delete(User $user, Video $video): bool
    {
        // User can delete their own videos OR admin/moderator can delete any video
        return $user->id === $video->user_id 
            || $user->hasRole(['admin', 'moderator']);
    }

    /**
     * Determine if the user can restore the video.
     */
    public function restore(User $user, Video $video): bool
    {
        // Only admin/moderator can restore
        return $user->hasRole(['admin', 'moderator']);
    }

    /**
     * Determine if the user can permanently delete the video.
     */
    public function forceDelete(User $user, Video $video): bool
    {
        // Only admin can force delete
        return $user->hasRole('admin');
    }
}
