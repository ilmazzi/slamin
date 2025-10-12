<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        // Everyone can view public events
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Event $event): bool
    {
        // Public events can be viewed by everyone
        if ($event->is_public) {
            return true;
        }

        // Private events only by authenticated users who are involved
        if (!$user) {
            return false;
        }

        // Admin and moderators can view all events
        if ($user->hasRole(['admin', 'moderator'])) {
            return true;
        }

        // Organizers can view their own events
        if ($event->organizer_id === $user->id) {
            return true;
        }

        // Users with accepted invitations can view
        if ($event->invitations()->where('invited_user_id', $user->id)->where('status', 'accepted')->exists()) {
            return true;
        }

        // Users with accepted requests can view
        if ($event->requests()->where('user_id', $user->id)->where('status', 'accepted')->exists()) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Admin, moderators, and organizers can create events
        return $user->hasRole(['admin', 'moderator', 'organizer']) || 
               $user->can('events.manage.own');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Event $event): bool
    {
        // Admin and moderators have full access
        if ($user->hasRole(['admin', 'moderator'])) {
            return true;
        }

        // Organizers can update their own events if they have the permission
        return $user->can('events.manage.own') && $event->organizer_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $event): bool
    {
        // Admin and moderators have full access
        if ($user->hasRole(['admin', 'moderator'])) {
            return true;
        }

        // Organizers can delete their own events if they have the permission
        return $user->can('events.manage.own') && $event->organizer_id === $user->id;
    }

    /**
     * Determine whether the user can manage the model (invitations, requests, etc).
     */
    public function manage(User $user, Event $event): bool
    {
        // Admin and moderators have full access
        if ($user->hasRole(['admin', 'moderator'])) {
            return true;
        }

        // Organizers can manage their own events if they have the permission
        return $user->can('events.manage.own') && $event->organizer_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Event $event): bool
    {
        // Only admin and moderators can restore
        return $user->hasRole(['admin', 'moderator']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Event $event): bool
    {
        // Only admin can force delete
        return $user->hasRole('admin');
    }
}
