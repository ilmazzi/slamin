<?php

namespace App\Observers;

use App\Models\EventRequest;
use App\Models\EventParticipant;
use App\Services\ActivityService;

class EventRequestObserver
{
    /**
     * Handle the EventRequest "created" event.
     */
    public function created(EventRequest $eventRequest): void
    {
        // Log activity for the user who made the request
        if ($eventRequest->user) {
            ActivityService::log(
                $eventRequest->user,
                'request',
                'App\\Models\\Event',
                $eventRequest->event_id,
                'requested',
                null,
                [
                    'title' => $eventRequest->event->title ?? 'Evento',
                    'url' => route('events.show', $eventRequest->event),
                ],
                request()
            );
        }
    }

    /**
     * Handle the EventRequest "updated" event.
     */
    public function updated(EventRequest $eventRequest): void
    {
        // Log activity for status changes
        if ($eventRequest->wasChanged('status')) {
            $user = $eventRequest->user;
            $action = match($eventRequest->status) {
                'accepted' => 'accepted',
                'declined' => 'declined',
                'cancelled' => 'cancelled',
                default => 'updated'
            };

            if ($user) {
                ActivityService::log(
                    $user,
                    $action === 'accepted' ? 'accept' : ($action === 'declined' ? 'decline' : 'update'),
                    'App\\Models\\Event',
                    $eventRequest->event_id,
                    $action,
                    null,
                    [
                        'title' => $eventRequest->event->title ?? 'Evento',
                        'url' => route('events.show', $eventRequest->event),
                    ],
                    request()
                );
            }

            // Auto-add to event participants if Poetry Slam event and request accepted
            if ($eventRequest->status === 'accepted' && 
                $eventRequest->event && 
                $eventRequest->event->category === 'poetry_slam') {
                
                // Check if participant doesn't already exist
                $exists = EventParticipant::where('event_id', $eventRequest->event_id)
                    ->where('user_id', $eventRequest->user_id)
                    ->exists();

                if (!$exists) {
                    EventParticipant::create([
                        'event_id' => $eventRequest->event_id,
                        'user_id' => $eventRequest->user_id,
                        'registration_type' => 'request',
                        'status' => 'confirmed',
                    ]);
                }
            }
        }
    }

    /**
     * Handle the EventRequest "deleted" event.
     */
    public function deleted(EventRequest $eventRequest): void
    {
        //
    }

    /**
     * Handle the EventRequest "restored" event.
     */
    public function restored(EventRequest $eventRequest): void
    {
        //
    }

    /**
     * Handle the EventRequest "force deleted" event.
     */
    public function forceDeleted(EventRequest $eventRequest): void
    {
        //
    }
}
