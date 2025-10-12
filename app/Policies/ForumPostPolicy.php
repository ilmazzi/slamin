<?php

namespace App\Policies;

use App\Models\ForumPost;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ForumPostPolicy
{
    /**
     * Admin and moderators bypass all checks
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
        return true; // Anyone can view posts
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, ForumPost $post): bool
    {
        // Can view if approved, or if user is author/moderator
        if ($post->isApproved()) {
            return true;
        }

        if ($user && $user->id === $post->user_id) {
            return true;
        }

        if ($user && $user->hasRole('moderator') && $post->subreddit->isModerator($user)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Any authenticated user can create posts
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ForumPost $post): bool
    {
        // Author can update
        if ($user->id === $post->user_id) {
            return true;
        }

        // Moderators of the subreddit can update
        if ($user->hasRole('moderator') && $post->subreddit->isModerator($user)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ForumPost $post): bool
    {
        // Author can delete
        if ($user->id === $post->user_id) {
            return true;
        }

        // Moderators of the subreddit can delete
        if ($user->hasRole('moderator') && $post->subreddit->isModerator($user)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can moderate the post (sticky, lock, etc.)
     */
    public function moderate(User $user, ForumPost $post): bool
    {
        // Moderators of the subreddit can moderate
        if ($user->hasRole('moderator') && $post->subreddit->isModerator($user)) {
            return true;
        }

        return false;
    }
}
