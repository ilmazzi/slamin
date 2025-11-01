<?php

namespace App\Observers;

use App\Models\EventInvitation;
use App\Models\EventParticipant;
use App\Services\ActivityService;
use App\Notifications\EventInvitationNotification;

class EventInvitationObserver
{
    /**
     * Handle the EventInvitation "created" event.
     */
    public function created(EventInvitation $eventInvitation): void
    {
        // Load relationships to ensure they're available
        $eventInvitation->load(['invitedUser', 'inviter', 'event']);

        // Log activity for the inviter
        if ($eventInvitation->inviter) {
            ActivityService::log(
                $eventInvitation->inviter,
                'invite',
                'App\\Models\\Event',
                $eventInvitation->event_id,
                'invited',
                null,
                [
                    'title' => $eventInvitation->event->title ?? 'Evento',
                    'url' => route('events.show', $eventInvitation->event),
                    'invited_user' => $eventInvitation->invitedUser->getDisplayName() ?? 'Utente',
                ],
                request()
            );
        }

        // Send notification to invited user using new notification system
        if ($eventInvitation->invitedUser && $eventInvitation->event) {
            \Log::info('Sending invitation notification', [
                'invitation_id' => $eventInvitation->id,
                'event_id' => $eventInvitation->event_id,
                'invited_user_id' => $eventInvitation->invited_user_id,
                'event_title' => $eventInvitation->event->title,
            ]);
            
            // Use new real-time notification system
            \App\Models\Notification::createEventInvitation($eventInvitation);
        }
    }

    /**
     * Handle the EventInvitation "updated" event.
     */
    public function updated(EventInvitation $eventInvitation): void
    {
        // Log activity for status changes
        if ($eventInvitation->wasChanged('status')) {
            $user = $eventInvitation->invitedUser;
            $action = match($eventInvitation->status) {
                'accepted' => 'accepted',
                'declined' => 'declined',
                default => 'updated'
            };

            if ($user) {
                ActivityService::log(
                    $user,
                    $action === 'accepted' ? 'accept' : 'decline',
                    'App\\Models\\Event',
                    $eventInvitation->event_id,
                    $action,
                    null,
                    [
                        'title' => $eventInvitation->event->title ?? 'Evento',
                        'url' => route('events.show', $eventInvitation->event),
                    ],
                    request()
                );
            }

            // Auto-add to event participants if Poetry Slam event and invitation accepted
            if ($eventInvitation->status === 'accepted' && 
                $eventInvitation->event && 
                $eventInvitation->event->category === 'poetry_slam') {
                
                // Check if participant doesn't already exist
                $exists = EventParticipant::where('event_id', $eventInvitation->event_id)
                    ->where('user_id', $eventInvitation->invited_user_id)
                    ->exists();

                if (!$exists) {
                    EventParticipant::create([
                        'event_id' => $eventInvitation->event_id,
                        'user_id' => $eventInvitation->invited_user_id,
                        'registration_type' => 'invitation',
                        'status' => 'confirmed',
                    ]);
                }
            }
        }
    }

    /**
     * Handle the EventInvitation "deleted" event.
     */
    public function deleted(EventInvitation $eventInvitation): void
    {
        //
    }

    /**
     * Handle the EventInvitation "restored" event.
     */
    public function restored(EventInvitation $eventInvitation): void
    {
        //
    }

    /**
     * Handle the EventInvitation "force deleted" event.
     */
    public function forceDeleted(EventInvitation $eventInvitation): void
    {
        //
    }
}
