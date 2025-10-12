<?php

namespace App\Policies;

use App\Models\ForumComment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ForumCommentPolicy
{
    /**
     * Admin and moderators bypass checks
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
    public function view(?User $user, ForumComment $comment): bool
    {
        // Deleted comments can only be viewed by author/moderators
        if ($comment->is_deleted) {
            if (!$user) {
                return false;
            }

            if ($user->id === $comment->user_id) {
                return true;
            }

            if ($user->hasRole('moderator') && $comment->post->subreddit->isModerator($user)) {
                return true;
            }

            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Any authenticated user can comment
    }

    /**
     * Determine whether the user can reply to this comment
     */
    public function reply(User $user, ForumComment $comment): bool
    {
        // Can't reply to deleted comments
        if ($comment->is_deleted) {
            return false;
        }

        // Can't reply if max depth reached
        if (!$comment->canReply()) {
            return false;
        }

        // Can't reply if post is locked
        if ($comment->post->is_locked) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ForumComment $comment): bool
    {
        // Only author can update
        if ($user->id === $comment->user_id && !$comment->is_deleted) {
            return true;
        }

        // Moderators can update
        if ($user->hasRole('moderator') && $comment->post->subreddit->isModerator($user)) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ForumComment $comment): bool
    {
        // Already deleted
        if ($comment->is_deleted) {
            return false;
        }

        // Author can delete
        if ($user->id === $comment->user_id) {
            return true;
        }

        // Moderators can delete
        if ($user->hasRole('moderator') && $comment->post->subreddit->isModerator($user)) {
            return true;
        }

        return false;
    }
}
