<?php

namespace App\Livewire;

use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Services\LoggingService;

class EventEdit extends EventCreation
{
    // Event being edited
    public $eventId;
    public $event;
    public $existing_image_url = '';

    // Override mount to load existing event data
    public function mount($eventId = null)
    {
        $this->eventId = $eventId;
        
        if ($eventId) {
            $this->event = Event::with(['invitations.invitedUser', 'groups', 'availabilityOptions'])->findOrFail($eventId);

            // Check permissions
            if (!Auth::user()->can('events.manage.own') || 
                (!Auth::user()->hasRole(['admin', 'moderator']) && $this->event->organizer_id !== Auth::id())) {
                abort(403, 'Non hai i permessi per modificare questo evento');
            }

            // Load event data into component properties
            $this->loadEventData();
        }

        // Load recent venues (from parent class logic)
        $this->recentVenues = \App\Models\RecentVenue::getPopularVenues(8)->toArray();
    }

    private function loadEventData()
    {
        // Step 1: Basic Information
        $this->title = $this->event->title;
        $this->subtitle = $this->event->subtitle ?? '';
        $this->has_subtitle = !empty($this->event->subtitle);
        $this->description = $this->event->description ?? '';
        $this->requirements = $this->event->requirements ?? '';
        $this->category = $this->event->category;
        $this->is_public = $this->event->is_public;

        // Step 2: Date & Location
        $this->start_datetime = $this->event->start_datetime ? $this->event->start_datetime->format('Y-m-d H:i') : '';
        $this->end_datetime = $this->event->end_datetime ? $this->event->end_datetime->format('Y-m-d H:i') : '';
        $this->registration_deadline = $this->event->registration_deadline ? $this->event->registration_deadline->format('Y-m-d H:i') : '';
        $this->invitation_deadline = $this->event->invitation_deadline ? $this->event->invitation_deadline->format('Y-m-d H:i') : '';
        
        $this->is_availability_based = $this->event->is_availability_based;
        $this->availability_deadline = $this->event->availability_deadline ? $this->event->availability_deadline->format('Y-m-d H:i') : '';
        $this->availability_instructions = $this->event->availability_instructions ?? '';
        
        // Load availability options
        if ($this->event->availabilityOptions && $this->event->availabilityOptions->count() > 0) {
            $this->availability_options = $this->event->availabilityOptions->map(function($option) {
                return [
                    'datetime' => $option->datetime->format('Y-m-d H:i'),
                    'description' => $option->description ?? '',
                ];
            })->toArray();
        }

        $this->is_online = $this->event->is_online;
        $this->online_url = $this->event->online_url ?? '';
        $this->timezone = $this->event->timezone ?? 'Europe/Rome';
        $this->venue_name = $this->event->venue_name ?? '';
        $this->venue_address = $this->event->venue_address ?? '';
        $this->city = $this->event->city ?? '';
        $this->postcode = $this->event->postcode ?? '';
        $this->country = $this->event->country ?? 'IT';
        $this->latitude = $this->event->latitude ?? '';
        $this->longitude = $this->event->longitude ?? '';

        $this->is_recurring = $this->event->is_recurring;
        $this->recurrence_type = $this->event->recurrence_type ?? '';
        $this->recurrence_interval = $this->event->recurrence_interval ?? 1;
        $this->recurrence_count = $this->event->recurrence_count ?? '';
        $this->recurrence_weekdays = $this->event->recurrence_weekdays ?? [];
        $this->recurrence_monthday = $this->event->recurrence_monthday ?? '';

        // Step 3: Details
        $this->existing_image_url = $this->event->image_url ?? '';
        $this->promotional_video = $this->event->promotional_video ?? '';
        $this->is_paid_event = $this->event->entry_fee > 0;
        $this->ticket_price = $this->event->entry_fee ?? 0;
        $this->ticket_currency = 'EUR';
        
        // Load groups
        $this->selected_groups = $this->event->groups->pluck('id')->toArray();
        $this->is_linked_to_group = count($this->selected_groups) > 0;
        
        $this->is_festival_event = !empty($this->event->festival_id);
        $this->festival_id = $this->event->festival_id ?? '';
        
        // Load gig positions
        $this->gig_positions = $this->event->gig_positions ?? [];

        // Step 4: Settings & Invitations
        $this->status = $this->event->status;
        $this->max_participants = $this->event->max_participants ?? '';
        $this->allow_requests = $this->event->allow_requests;
        
        // Load existing invitations
        if ($this->event->invitations && $this->event->invitations->count() > 0) {
            $this->invitations = $this->event->invitations->map(function($invitation) {
                return [
                    'id' => $invitation->id,
                    'user_id' => $invitation->invited_user_id,
                    'name' => $invitation->invitedUser->name ?? 'Unknown',
                    'role' => $invitation->role,
                ];
            })->toArray();
        }

        $this->tags = $this->event->tags ?? [];
    }

    // Override save() to update instead of create
    public function save()
    {
        try {
            // Final validation
            $this->validate();

            // Handle image upload
            $imagePath = $this->existing_image_url;
            if ($this->event_image) {
                // Delete old image if exists
                if ($this->existing_image_url) {
                    $oldPath = str_replace('/storage/', '', $this->existing_image_url);
                    Storage::disk('public')->delete($oldPath);
                }
                $imagePath = Storage::url($this->event_image->store('events', 'public'));
            }

            // Prepare gig positions
            $gigPositions = !empty($this->gig_positions) ? array_values($this->gig_positions) : null;

            // Prepare tags
            $tagsArray = !empty($this->tags) ? $this->tags : null;

            // Update event
            $this->event->update([
                // Basic Information
                'title' => $this->title,
                'subtitle' => $this->subtitle,
                'description' => $this->description,
                'requirements' => $this->requirements,
                'category' => $this->category,
                'is_public' => $this->is_public,

                // Date & Time
                'start_datetime' => $this->start_datetime ?: null,
                'end_datetime' => $this->end_datetime ?: null,
                'registration_deadline' => $this->has_registration_deadline && $this->registration_deadline_date && $this->registration_deadline_time
                    ? $this->registration_deadline_date . ' ' . $this->registration_deadline_time
                    : null,
                'invitation_deadline' => $this->invitation_deadline ?: null,

                // Availability
                'is_availability_based' => $this->is_availability_based,
                'availability_deadline' => $this->availability_deadline ?: null,
                'availability_instructions' => $this->availability_instructions,

                // Location
                'is_online' => $this->is_online,
                'online_url' => $this->online_url,
                'timezone' => $this->timezone,
                'venue_name' => $this->venue_name,
                'venue_address' => $this->venue_address,
                'city' => $this->city,
                'postcode' => $this->postcode,
                'country' => $this->country,
                'latitude' => $this->latitude ?: null,
                'longitude' => $this->longitude ?: null,

                // Recurrence
                'is_recurring' => $this->is_recurring,
                'recurrence_type' => $this->is_recurring && $this->recurrence_type ? $this->recurrence_type : null,
                'recurrence_interval' => $this->is_recurring ? $this->recurrence_interval : null,
                'recurrence_count' => $this->is_recurring && $this->recurrence_count ? $this->recurrence_count : null,
                'recurrence_weekdays' => $this->is_recurring && !empty($this->recurrence_weekdays) ? $this->recurrence_weekdays : null,
                'recurrence_monthday' => $this->is_recurring && $this->recurrence_monthday ? $this->recurrence_monthday : null,

                // Details
                'image_url' => $imagePath,
                'promotional_video' => $this->promotional_video,
                'entry_fee' => $this->is_paid_event ? $this->ticket_price : 0,
                'gig_positions' => $gigPositions,
                'tags' => $tagsArray,

                // Settings
                'max_participants' => $this->max_participants ?: null,
                'allow_requests' => $this->allow_requests,
                'status' => $this->status,

                // Festival
                'festival_id' => $this->festival_id ?: null,
                'festival_events' => !empty($this->selected_festival_events) ? $this->selected_festival_events : null,
            ]);

            // Sync groups
            if ($this->is_linked_to_group && !empty($this->selected_groups)) {
                $this->event->groups()->sync($this->selected_groups);
            } else {
                $this->event->groups()->detach();
            }

            // Handle invitations (delete old, create new)
            $this->event->invitations()->delete();
            if (!empty($this->invitations)) {
                foreach ($this->invitations as $invitation) {
                    // Skip invitations that have an 'id' (existing ones we're keeping)
                    if (!isset($invitation['id'])) {
                        $this->event->invitations()->create([
                            'invited_user_id' => $invitation['user_id'],
                            'inviter_id' => Auth::id(),
                            'role' => $invitation['role'],
                            'status' => 'pending',
                        ]);
                    }
                }
            }

            // Handle availability options
            $this->event->availabilityOptions()->delete();
            if ($this->is_availability_based && !empty($this->availability_options)) {
                foreach ($this->availability_options as $option) {
                    if (is_array($option) && !empty($option['datetime'])) {
                        $this->event->availabilityOptions()->create([
                            'datetime' => $option['datetime'],
                            'description' => $option['description'] ?? null,
                        ]);
                    }
                }
            }

            // Log event update
            LoggingService::logEvent('update', [
                'event_id' => $this->event->id,
                'title' => $this->event->title,
                'category' => $this->event->category,
            ], Event::class, $this->event->id);

            session()->flash('success', 'Evento aggiornato con successo!');
            return redirect()->route('events.show', $this->event->id);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Validation errors - let Livewire handle them
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Event update failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'event_id' => $this->eventId,
            ]);

            LoggingService::logError('event_update_failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'event_id' => $this->eventId,
            ]);

            session()->flash('error', 'Errore durante l\'aggiornamento dell\'evento: ' . $e->getMessage());
        }
    }

    // Override render to use edit view
    public function render()
    {
        return view('livewire.event-edit', [
            'categories' => Event::getCategories(),
        ]);
    }
}
