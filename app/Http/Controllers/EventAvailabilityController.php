<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventAvailabilityOption;
use App\Models\EventAvailabilityResponse;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class EventAvailabilityController extends Controller
{
    /**
     * Show availability options for an event (organizer view)
     */
    public function show(Event $event): View
    {
        // Check if user can view this event's availability
        if (!$event->canBeViewedBy(Auth::user()) && !$event->canBeModifiedBy(Auth::user())) {
            abort(403, 'Non hai i permessi per visualizzare le disponibilità di questo evento.');
        }

        $event->load(['activeAvailabilityOptions.responses.user', 'organizer']);

        $availabilitySummary = $event->getAvailabilitySummary();

        return view('events.availability.show', compact('event', 'availabilitySummary'));
    }

    /**
     * Show availability form for participants
     */
    public function respond(Event $event): View
    {
        // Check if user can respond to this event's availability
        if (!$event->isAvailabilityBased()) {
            abort(404, 'Questo evento non richiede la selezione di disponibilità.');
        }

        // Check if user is invited or can participate
        $user = Auth::user();
        $canParticipate = $event->canBeViewedBy($user) ||
                         $event->invitations()->where('invited_user_id', $user->id)->where('status', 'accepted')->exists() ||
                         $event->requests()->where('user_id', $user->id)->where('status', 'accepted')->exists();

        if (!$canParticipate) {
            abort(403, 'Non hai i permessi per rispondere alle disponibilità di questo evento.');
        }

        $event->load(['activeAvailabilityOptions']);
        $userResponses = $event->getUserAvailabilityResponses($user)->get();

        // Create a map of option_id => response for easy access
        $responsesMap = $userResponses->keyBy('availability_option_id');

        return view('events.availability.respond', compact('event', 'responsesMap'));
    }

    /**
     * Store availability options (organizer)
     */
    public function storeOptions(Request $request, Event $event): JsonResponse
    {
        // Check if user can modify this event
        if (!$event->canBeModifiedBy(Auth::user())) {
            abort(403, 'Non hai i permessi per modificare le disponibilità di questo evento.');
        }

        $validated = $request->validate([
            'options' => 'required|array|min:1',
            'options.*.datetime' => 'required|date_format:Y-m-d H:i',
            'options.*.description' => 'nullable|string|max:255',
        ]);

        // Check limit based on user subscription
        $currentCount = $event->getAvailabilityOptionsCount();
        $newCount = count($validated['options']);
        $maxOptions = $this->getMaxAvailabilityOptions(Auth::user());

        if ($currentCount + $newCount > $maxOptions) {
            return response()->json([
                'success' => false,
                'message' => "Puoi aggiungere al massimo {$maxOptions} opzioni di disponibilità. Hai già {$currentCount} opzioni."
            ], 422);
        }

        try {
            $sortOrder = $event->availabilityOptions()->max('sort_order') ?? 0;

            foreach ($validated['options'] as $optionData) {
                $sortOrder++;

                EventAvailabilityOption::create([
                    'event_id' => $event->id,
                    'datetime' => $optionData['datetime'],
                    'description' => $optionData['description'] ?? null,
                    'sort_order' => $sortOrder,
                ]);
            }

            // Send notifications to participants
            $this->notifyParticipantsAboutAvailability($event);

            return response()->json([
                'success' => true,
                'message' => 'Opzioni di disponibilità aggiunte con successo.',
                'options_count' => $event->fresh()->getAvailabilityOptionsCount()
            ]);

        } catch (\Exception $e) {
            Log::error('Error storing availability options', [
                'event_id' => $event->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Errore durante il salvataggio delle opzioni.'
            ], 500);
        }
    }

    /**
     * Store user's availability response
     */
    public function storeResponse(Request $request, Event $event): JsonResponse
    {
        $user = Auth::user();
        Log::info('StoreResponse called', [
            'event_id' => $event->id,
            'user_id' => $user->id,
            'request_data' => $request->all()
        ]);

        // Check if user can respond
        if (!$event->isAvailabilityBased()) {
            return response()->json([
                'success' => false,
                'message' => 'Questo evento non richiede la selezione di disponibilità.'
            ], 422);
        }

        // Check if deadline has passed
        if ($event->isAvailabilityDeadlinePassed()) {
            return response()->json([
                'success' => false,
                'message' => 'La scadenza per rispondere alle disponibilità è scaduta.'
            ], 422);
        }

        $validated = $request->validate([
            'responses' => 'required|array',
            'responses.*.option_id' => 'required|exists:event_availability_options,id',
            'responses.*.status' => 'required|in:preferred,available,unavailable',
            'responses.*.notes' => 'nullable|string|max:500',
        ]);

        Log::info('Validation passed', ['validated_data' => $validated]);

        try {
            foreach ($validated['responses'] as $responseData) {
                // Verify the option belongs to this event
                $option = EventAvailabilityOption::where('id', $responseData['option_id'])
                    ->where('event_id', $event->id)
                    ->first();

                if (!$option) {
                    continue;
                }

                // Update or create response
                $response = EventAvailabilityResponse::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'availability_option_id' => $responseData['option_id'],
                    ],
                    [
                        'event_id' => $event->id,
                        'status' => $responseData['status'],
                        'notes' => $responseData['notes'] ?? null,
                    ]
                );

                Log::info('Response saved', [
                    'response_id' => $response->id,
                    'option_id' => $responseData['option_id'],
                    'status' => $responseData['status']
                ]);
            }

            // Notify organizer about response
            $this->notifyOrganizerAboutResponse($event, $user);

            return response()->json([
                'success' => true,
                'message' => 'Disponibilità salvata con successo.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error storing availability response', [
                'event_id' => $event->id,
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Errore durante il salvataggio della disponibilità.'
            ], 500);
        }
    }

    /**
     * Delete an availability option (organizer)
     */
    public function deleteOption(Event $event, EventAvailabilityOption $option): JsonResponse
    {
        // Check if user can modify this event
        if (!$event->canBeModifiedBy(Auth::user())) {
            abort(403, 'Non hai i permessi per modificare le disponibilità di questo evento.');
        }

        // Verify option belongs to this event
        if ($option->event_id !== $event->id) {
            abort(404, 'Opzione non trovata.');
        }

        try {
            $option->delete();

            return response()->json([
                'success' => true,
                'message' => 'Opzione di disponibilità eliminata.'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting availability option', [
                'option_id' => $option->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Errore durante l\'eliminazione dell\'opzione.'
            ], 500);
        }
    }

    /**
     * Get max availability options for user based on subscription
     */
    private function getMaxAvailabilityOptions($user): int
    {
        // Get limits from system settings
        $defaultLimit = \App\Models\SystemSetting::get('availability_options_limit', 10);
        $premiumLimit = \App\Models\SystemSetting::get('availability_options_premium_limit', 50);

        // Check if user has premium subscription
        if ($user->hasRole('premium') || $user->hasRole('organization')) {
            return $premiumLimit;
        }

        return $defaultLimit;
    }

    /**
     * Notify participants about new availability options
     */
    private function notifyParticipantsAboutAvailability(Event $event): void
    {
        $participants = collect();

        // Get invited users
        $invitedUsers = $event->invitations()
            ->where('status', 'accepted')
            ->with('invitedUser')
            ->get()
            ->pluck('invitedUser');

        // Get accepted request users
        $requestUsers = $event->requests()
            ->where('status', 'accepted')
            ->with('user')
            ->get()
            ->pluck('user');

        $participants = $participants->merge($invitedUsers)->merge($requestUsers);

        foreach ($participants as $participant) {
            Notification::create([
                'user_id' => $participant->id,
                'type' => 'availability_request',
                'title' => 'Nuove disponibilità richieste',
                'message' => "L'organizzatore di '{$event->title}' ha richiesto la tua disponibilità per nuove date/orari.",
                'data' => [
                    'event_id' => $event->id,
                    'event_title' => $event->title,
                ],
                'action_url' => route('events.availability.respond', $event),
                'action_text' => 'Rispondi alle disponibilità',
                'priority' => 'normal',
            ]);
        }
    }

    /**
     * Get responses for a specific availability option
     */
    public function getOptionResponses(Event $event, EventAvailabilityOption $option): JsonResponse
    {
        // Check if user can view this event's availability
        if (!$event->canBeViewedBy(Auth::user()) && !$event->canBeModifiedBy(Auth::user())) {
            abort(403, 'Non hai i permessi per visualizzare le risposte di questo evento.');
        }

        // Verify option belongs to this event
        if ($option->event_id !== $event->id) {
            abort(404, 'Opzione non trovata.');
        }

        $responses = $option->responses()
            ->with('user')
            ->get()
            ->map(function ($response) {
                return [
                    'id' => $response->id,
                    'user_name' => $response->user->getDisplayName(),
                    'user_email' => $response->user->email,
                    'status' => $response->status,
                    'status_label' => $response->status_label,
                    'status_color' => $response->status_color,
                    'notes' => $response->notes,
                    'created_at' => $response->created_at->format('d/m/Y H:i'),
                ];
            });

        return response()->json([
            'success' => true,
            'option' => [
                'id' => $option->id,
                'datetime' => $option->formatted_datetime,
                'description' => $option->description,
            ],
            'responses' => $responses,
            'summary' => [
                'total' => $responses->count(),
                'preferred' => $responses->where('status', 'preferred')->count(),
                'available' => $responses->where('status', 'available')->count(),
                'unavailable' => $responses->where('status', 'unavailable')->count(),
            ]
        ]);
    }

    /**
     * Notify organizer about user response
     */
    private function notifyOrganizerAboutResponse(Event $event, $user): void
    {
        Notification::create([
            'user_id' => $event->organizer_id,
            'type' => 'availability_response',
            'title' => 'Nuova risposta alle disponibilità',
            'message' => "{$user->name} ha aggiornato la propria disponibilità per '{$event->title}'.",
            'data' => [
                'event_id' => $event->id,
                'event_title' => $event->title,
                'respondent_id' => $user->id,
                'respondent_name' => $user->name,
            ],
            'action_url' => route('events.availability.show', $event),
            'action_text' => 'Visualizza disponibilità',
            'priority' => 'normal',
        ]);
    }
}
