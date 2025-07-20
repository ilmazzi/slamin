<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EventPolicy
{
    /**
     * Determine whether the user can view any events.
     */
    public function viewAny(User $user): bool
    {
        return true; // Everyone can view public events
    }

    /**
     * Determine if the user can view the event.
     */
    public function view(User $user, Event $event): bool
    {
        // Super admins can view any event
        if ($user->hasPermissionTo('manage events')) {
            return true;
        }

        // Organizers can view their own events
        if ($event->organizer_id === $user->id && $user->hasPermissionTo('view events')) {
            return true;
        }

        // For now, all authenticated users can view public events
        return true;
    }

    /**
     * Determine if the user can create events.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create events');
    }

    /**
     * Determine if the user can update the event.
     */
    public function update(User $user, Event $event): bool
    {
        // Super admins can update any event
        if ($user->hasPermissionTo('manage events')) {
            return true;
        }

        // Organizers can update their own events
        if ($event->organizer_id === $user->id && $user->hasPermissionTo('edit events')) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the user can delete the event.
     */
    public function delete(User $user, Event $event): bool
    {
        return $event->organizer_id === $user->id && $user->hasPermissionTo('delete events');
    }

    /**
     * Determine if the user can invite others to the event.
     */
    public function invite(User $user, Event $event): bool
    {
        // Only event organizers can invite
        if (!$user->hasPermissionTo('send invitations')) {
            return false;
        }

        // For now, only organizers can invite
        return $event->organizer_id === $user->id;
    }

    /**
     * Determine whether the user can apply to the event.
     */
    public function apply(User $user, Event $event): bool
    {
        // Event must be public and accept requests
        if (!$event->is_public || !$event->allow_requests) {
            return false;
        }

        // User must have participate permission
        if (!$user->hasPermission('events.participate')) {
            return false;
        }

        // User cannot apply to their own event
        if ($event->organizer_id === $user->id) {
            return false;
        }

        // Check if user already has invitation or request
        if ($event->invitations()->where('invited_user_id', $user->id)->exists()) {
            return false;
        }

        if ($event->requests()->where('user_id', $user->id)->exists()) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can manage invitations and requests.
     */
    public function manage(User $user, Event $event): bool
    {
        return $this->update($user, $event);
    }

    /**
     * Determine whether the user can view private event details.
     */
    public function viewPrivateDetails(User $user, Event $event): bool
    {
        // Organizer can always view
        if ($event->organizer_id === $user->id) {
            return true;
        }

        // Accepted participants can view
        $hasAcceptedInvitation = $event->invitations()
                                      ->where('invited_user_id', $user->id)
                                      ->where('status', 'accepted')
                                      ->exists();

        $hasAcceptedRequest = $event->requests()
                                   ->where('user_id', $user->id)
                                   ->where('status', 'accepted')
                                   ->exists();

        return $hasAcceptedInvitation || $hasAcceptedRequest;
    }

    /**
     * Determine whether the user can cancel the event.
     */
    public function cancel(User $user, Event $event): bool
    {
        // Same as delete permission
        return $this->delete($user, $event);
    }

    /**
     * Determine whether the user can publish the event.
     */
    public function publish(User $user, Event $event): bool
    {
        // Only organizer can publish their draft events
        return $event->organizer_id === $user->id &&
               $event->status === Event::STATUS_DRAFT &&
               $user->hasPermissionTo('edit events');
    }

    /**
     * Determine whether the user can view event analytics.
     */
    public function viewAnalytics(User $user, Event $event): bool
    {
        // Organizer and admins can view analytics
        return $event->organizer_id === $user->id ||
               $user->hasPermissionTo('manage events');
    }

    /**
     * Determine whether the user can export event data.
     */
    public function export(User $user, Event $event): bool
    {
        return $this->viewAnalytics($user, $event);
    }
}
