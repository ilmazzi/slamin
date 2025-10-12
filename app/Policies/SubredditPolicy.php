<?php

namespace App\Policies;

use App\Models\Subreddit;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SubredditPolicy
{
    /**
     * Admin bypass all checks
     */
    public function before(User $user, string $ability): ?bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return null;
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Subreddit $subreddit): bool
    {
        // Public subreddits can be viewed by anyone
        if (!$subreddit->is_private) {
            return true;
        }

        // Private subreddits require authentication and subscription
        if (!$user) {
            return false;
        }

        return $subreddit->isSubscribedBy($user) || $subreddit->isModerator($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only admins can create subreddits for now
        // You can change this to allow any user if needed
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Subreddit $subreddit): bool
    {
        // Creator can update
        if ($user->id === $subreddit->created_by) {
            return true;
        }

        // Moderators can update
        return $subreddit->isModerator($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Subreddit $subreddit): bool
    {
        // Only creator or admin can delete
        return $user->id === $subreddit->created_by;
    }

    /**
     * Determine whether the user can moderate the subreddit
     */
    public function moderate(User $user, Subreddit $subreddit): bool
    {
        return $subreddit->isModerator($user);
    }
}
