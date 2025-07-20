<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventInvitation;
use App\Models\EventRequest;
use App\Models\User;
use App\Models\Notification;
use App\Mail\EventInvitationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class EventController extends Controller
{
    /**
     * Display a listing of events
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        $query = Event::with([
            'organizer',
            'venueOwner',
            'invitations.invitedUser',
            'requests.user'
        ])
                     ->published()
                     ->upcoming()
                     ->orderBy('start_datetime');

        // Filter by location if provided
        if ($request->has(['lat', 'lng'])) {
            $query->nearLocation(
                $request->lat,
                $request->lng,
                $request->radius ?? 50
            );
        }

        // Filter by city
        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        // Filter by type (public/private)
        if ($request->filled('type')) {
            $query->where('is_public', $request->type === 'public');
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('start_datetime', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('start_datetime', '<=', $request->date_to);
        }

        // Filter free events only
        if ($request->filled('free_only') && $request->free_only == '1') {
            $query->where(function($q) {
                $q->where('entry_fee', 0)
                  ->orWhereNull('entry_fee');
            });
        }

        // Filter by tags
        if ($request->filled('tags')) {
            $tags = explode(',', $request->tags);
            $query->where(function ($q) use ($tags) {
                foreach ($tags as $tag) {
                    $q->orWhereJsonContains('tags', trim($tag));
                }
            });
        }

        // Filter for "My Events" - events organized by user or where user participates
        if ($request->filled('filter') && $request->filter === 'my' && $user) {
            $userId = $user->id;
            $query->where(function ($q) use ($userId) {
                // Events organized by user
                $q->where('organizer_id', $userId)
                  // OR events where user has accepted invitation
                  ->orWhereHas('invitations', function ($inviteQuery) use ($userId) {
                      $inviteQuery->where('invited_user_id', $userId)
                                  ->where('status', 'accepted');
                  })
                  // OR events where user has accepted request
                  ->orWhereHas('requests', function ($requestQuery) use ($userId) {
                      $requestQuery->where('user_id', $userId)
                                   ->where('status', 'accepted');
                  });
            });
        }
        // Filter for "My Private Events" - only private events organized by user or where user participates
        elseif ($request->filled('filter') && $request->filter === 'my_private' && $user) {
            $userId = $user->id;
            $query->where('is_public', false)
                  ->where(function ($q) use ($userId) {
                      // Private events organized by user
                      $q->where('organizer_id', $userId)
                        // OR private events where user has accepted invitation
                        ->orWhereHas('invitations', function ($inviteQuery) use ($userId) {
                            $inviteQuery->where('invited_user_id', $userId)
                                        ->where('status', 'accepted');
                        })
                        // OR private events where user has accepted request
                        ->orWhereHas('requests', function ($requestQuery) use ($userId) {
                            $requestQuery->where('user_id', $userId)
                                         ->where('status', 'accepted');
                        });
                  });
        } else {
            // If not filtering for "my events", only show public events or private events where user has access
            if ($user) {
                $userId = $user->id;
                $query->where(function ($q) use ($userId) {
                    // Public events
                    $q->where('is_public', true)
                      // OR private events organized by user
                      ->orWhere('organizer_id', $userId)
                      // OR private events where user has accepted invitation
                      ->orWhere(function ($subQ) use ($userId) {
                          $subQ->where('is_public', false)
                               ->whereHas('invitations', function ($inviteQuery) use ($userId) {
                                   $inviteQuery->where('invited_user_id', $userId)
                                               ->where('status', 'accepted');
                               });
                      })
                      // OR private events where user has accepted request
                      ->orWhere(function ($subQ) use ($userId) {
                          $subQ->where('is_public', false)
                               ->whereHas('requests', function ($requestQuery) use ($userId) {
                                   $requestQuery->where('user_id', $userId)
                                                ->where('status', 'accepted');
                               });
                      });
                });
            } else {
                // If user is not authenticated, only show public events
                $query->where('is_public', true);
            }
        }

        $events = $query->paginate(12);

        return view('events.index', compact('events'));
    }

    /**
     * Show the form for creating a new event
     */
    public function create(): View
    {
        $venueOwners = User::whereHas('roles', function ($query) {
            $query->where('name', 'venue_owner');
        })->get();

        return view('events.create', compact('venueOwners'));
    }

    /**
     * Store a newly created event
     */
        public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Event::class);

        // Log the request data for debugging
        Log::info('Event creation request', [
            'user_id' => Auth::id(),
            'request_data' => $request->all()
        ]);



        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'requirements' => 'nullable|string',
                'start_datetime' => 'required|date_format:Y-m-d H:i|after:now',
                'end_datetime' => 'required|date_format:Y-m-d H:i|after:start_datetime',
                'registration_deadline' => 'nullable|date_format:Y-m-d H:i|before:start_datetime',
                'venue_name' => 'required|string|max:255',
                'venue_address' => 'required|string',
                'city' => 'required|string|max:255',
                'country' => 'required|string|size:2',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'is_public' => 'required|in:0,1',
                'max_participants' => 'nullable|integer|min:1',
                'entry_fee' => 'nullable|numeric|min:0',
                'venue_owner_id' => 'nullable|exists:users,id',
                'allow_requests' => 'nullable',
                            'tags' => 'nullable|string',
            'event_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'invitations' => 'nullable|string', // JSON string of invitations
            ], [
                'start_datetime.after' => 'La data di inizio deve essere nel futuro.',
                'end_datetime.after' => 'La data di fine deve essere dopo la data di inizio.',
                'registration_deadline.before' => 'La scadenza iscrizioni deve essere prima della data di inizio.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Event validation failed', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            throw $e;
        }

        // Log validated data
        Log::info('Event validation passed', [
            'validated_data' => $validated
        ]);

        // Process tags
        if ($validated['tags']) {
            $validated['tags'] = array_map('trim', explode(',', $validated['tags']));
        }

                // Convert is_public to boolean explicitly
        $validated['is_public'] = $validated['is_public'] === '1' || $validated['is_public'] === 1;

        // Convert allow_requests to boolean
        $validated['allow_requests'] = isset($validated['allow_requests']) && ($validated['allow_requests'] === 'on' || $validated['allow_requests'] === true);

        // Handle image upload
        if ($request->hasFile('event_image')) {
            $image = $request->file('event_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = $image->storeAs('events', $imageName, 'public');
            $validated['image_url'] = '/storage/' . $imagePath;
        }

        // Set organizer
        $validated['organizer_id'] = Auth::id();
        $validated['status'] = Event::STATUS_PUBLISHED;





        // Process invitations if provided
        $invitations = [];
        if (!empty($validated['invitations'])) {
            try {
                $invitations = json_decode($validated['invitations'], true);
                if (!is_array($invitations)) {
                    $invitations = [];
                }
            } catch (\Exception $e) {
                Log::warning('Failed to parse invitations JSON: ' . $e->getMessage());
                $invitations = [];
            }
        }

        // Remove invitations from validated data as it's not part of Event model
        unset($validated['invitations']);

        DB::transaction(function () use ($validated, $invitations, &$event) {
                        // Create the event
            $event = Event::create($validated);



            // Create invitations if any
            foreach ($invitations as $invitation) {
                if (isset($invitation['user_id']) && isset($invitation['role'])) {
                    // Verify user exists
                    $user = User::find($invitation['user_id']);
                    if ($user) {
                        // Create invitation
                        $eventInvitation = EventInvitation::create([
                            'event_id' => $event->id,
                            'invited_user_id' => $invitation['user_id'],
                            'inviter_id' => Auth::id(),
                            'role' => $invitation['role'],
                            'message' => $invitation['message'] ?? "Sei invitato a partecipare al nostro evento Poetry Slam!",
                            'status' => EventInvitation::STATUS_PENDING,
                            'expires_at' => Carbon::parse($event->start_datetime)->subDays(1), // Expires 1 day before event
                        ]);

                        // Create notification
                        Notification::createEventInvitation($eventInvitation);

                        // Send email invitation
                        try {
                            Mail::to($user->email)->send(new EventInvitationMail($eventInvitation));
                            Log::info('Event invitation email sent', [
                                'event_id' => $event->id,
                                'invited_user_id' => $invitation['user_id'],
                                'email' => $user->email
                            ]);
                        } catch (\Exception $e) {
                            Log::error('Failed to send event invitation email', [
                                'event_id' => $event->id,
                                'invited_user_id' => $invitation['user_id'],
                                'email' => $user->email,
                                'error' => $e->getMessage()
                            ]);
                        }
                    }
                }
            }
        });

        $invitationCount = count($invitations);
        $successMessage = __('events.event_created_success');
        if ($invitationCount > 0) {
            $successMessage .= ' ' . __('events.invitations_sent_success', ['count' => $invitationCount]);
        }

        return redirect()
            ->route('events.show', $event)
            ->with('success', $successMessage);
    }

    /**
     * Search users for event invitations (API endpoint)
     */
    public function searchUsers(Request $request): JsonResponse
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $users = User::where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->whereHas('roles', function ($q) {
                // Only users with relevant roles for poetry slam events
                $q->whereIn('name', ['poet', 'judge', 'technician', 'organizer']);
            })
            ->where('status', 'active')
            ->where('id', '!=', Auth::id()) // Exclude current user
            ->limit(10)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'roles' => $user->roles->pluck('name')->toArray(),
                    'avatar' => $user->avatar ?? null,
                ];
            });

        return response()->json($users);
    }

    /**
     * Display the specified event
     */
    public function show(Event $event): View|RedirectResponse
    {
        $user = Auth::user();

        // Check access for private events
        if (!$event->is_public) {
            // If user is not authenticated, redirect to login
            if (!$user) {
                return redirect()->route('login')
                    ->with('error', 'Devi effettuare il login per visualizzare questo evento privato.');
            }

            // Check if user is the organizer
            $isOrganizer = $event->organizer_id === $user->id;

            // Check if user has accepted invitation
            $hasAcceptedInvitation = $event->invitations()
                ->where('invited_user_id', $user->id)
                ->where('status', 'accepted')
                ->exists();

            // Check if user has accepted request
            $hasAcceptedRequest = $event->requests()
                ->where('user_id', $user->id)
                ->where('status', 'accepted')
                ->exists();

            // If user is not organizer and has no accepted participation, deny access
            if (!$isOrganizer && !$hasAcceptedInvitation && !$hasAcceptedRequest) {
                abort(403, 'Non hai i permessi per visualizzare questo evento privato.');
            }
        }

        $event->load(['organizer', 'venueOwner', 'invitations.invitedUser', 'requests.user']);

        $canApply = false;
        $hasInvitation = false;
        $hasRequest = false;
        $userInvitation = null;
        $userRequest = null;

        if ($user) {
            // Check if user can apply to this event
            $canApply = EventRequest::canUserApplyToEvent($user, $event);

            // Check if user has invitation
            $userInvitation = $event->invitations()
                                  ->where('invited_user_id', $user->id)
                                  ->first();
            $hasInvitation = $userInvitation !== null;

            // Check if user has request
            $userRequest = $event->requests()
                               ->where('user_id', $user->id)
                               ->first();
            $hasRequest = $userRequest !== null;
        }

        return view('events.show', compact(
            'event',
            'canApply',
            'hasInvitation',
            'hasRequest',
            'userInvitation',
            'userRequest'
        ));
    }

    /**
     * Show the form for editing the event
     */
    public function edit(Event $event): View
    {
        Gate::authorize('update', $event);

        $venueOwners = User::whereHas('roles', function ($query) {
            $query->where('name', 'venue_owner');
        })->get();

        return view('events.edit', compact('event', 'venueOwners'));
    }

    /**
     * Update the specified event
     */
    public function update(Request $request, Event $event): RedirectResponse
    {
        Gate::authorize('update', $event);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'start_datetime' => 'required|date|after:now',
            'end_datetime' => 'required|date|after:start_datetime',
            'registration_deadline' => 'nullable|date|before:start_datetime',
            'venue_name' => 'required|string|max:255',
            'venue_address' => 'required|string',
            'city' => 'required|string|max:255',
            'country' => 'required|string|size:2',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'is_public' => 'boolean',
            'max_participants' => 'nullable|integer|min:1',
            'entry_fee' => 'nullable|numeric|min:0',
            'venue_owner_id' => 'nullable|exists:users,id',
            'allow_requests' => 'boolean',
            'tags' => 'nullable|string',
            'image_url' => 'nullable|url',
            'status' => ['required', Rule::in([Event::STATUS_DRAFT, Event::STATUS_PUBLISHED, Event::STATUS_CANCELLED])],
        ]);

        // Process tags
        if ($validated['tags']) {
            $validated['tags'] = array_map('trim', explode(',', $validated['tags']));
        }

        $event->update($validated);

        // Notify participants about event update
        $this->notifyEventUpdate($event);

        return redirect()
            ->route('events.show', $event)
            ->with('success', 'Evento aggiornato con successo!');
    }

    /**
     * Remove the specified event
     */
    public function destroy(Event $event): RedirectResponse
    {
        Gate::authorize('delete', $event);

        // Notify all participants about cancellation
        $this->notifyEventCancellation($event);

        $event->delete();

        return redirect()
            ->route('events.index')
            ->with('success', 'Evento eliminato con successo!');
    }

    /**
     * Show event management interface for organizers
     */
    public function manage(Event $event): View
    {
        Gate::authorize('update', $event);

        $event->load([
            'pendingInvitations.invitedUser',
            'pendingRequests.user',
            'invitations', // Load ALL invitations for statistics
            'requests',    // Load ALL requests for statistics
            'acceptedInvitations' => function ($query) {
                $query->where('status', 'accepted');
            },
            'declinedInvitations' => function ($query) {
                $query->where('status', 'declined');
            },
            'acceptedRequests' => function ($query) {
                $query->where('status', 'accepted');
            },
            'declinedRequests' => function ($query) {
                $query->where('status', 'declined');
            }
        ]);

        // Get potential artists to invite
        $availableArtists = User::whereHas('roles', function ($query) {
            $query->whereIn('name', ['poet', 'judge', 'technician']);
        })
        ->whereNotIn('id', function ($query) use ($event) {
            $query->select('invited_user_id')
                  ->from('event_invitations')
                  ->where('event_id', $event->id);
        })
        ->whereNotIn('id', function ($query) use ($event) {
            $query->select('user_id')
                  ->from('event_requests')
                  ->where('event_id', $event->id);
        })
        ->get();

        return view('events.manage', compact('event', 'availableArtists'));
    }

    /**
     * Search events near a location
     */
        public function near(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'radius' => 'nullable|integer|min:1|max:200',
                'date_from' => 'nullable|date',
                'date_to' => 'nullable|date',
                'free_only' => 'nullable|boolean',
            ]);

            Log::info('Events near request params:', $request->all());

                                    // Recupera eventi pubblici vicini alla posizione richiesta
            $query = Event::where('is_public', true)
                          ->whereNotNull('latitude')
                          ->whereNotNull('longitude');

            // Temporaneamente disabilito nearLocation per debug
            // ->nearLocation(
            //     $request->latitude,
            //     $request->longitude,
            //     $request->radius ?? 50
            // );

            // Applica filtri temporali se presenti
            if ($request->filled('date_from')) {
                $query->whereDate('start_datetime', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('start_datetime', '<=', $request->date_to);
            }

            // Filtro eventi gratuiti
            if ($request->filled('free_only') && $request->free_only == '1') {
                $query->where(function($q) {
                    $q->where('entry_fee', 0)
                      ->orWhereNull('entry_fee');
                });
            }

            $events = $query->with(['organizer'])->get();

            Log::info('Found events count: ' . $events->count());
            if ($events->count() === 0) {
                Log::info('No events found with current filters');
            }

            $mappedEvents = $events->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start_datetime' => $event->start_datetime->format('d/m/Y H:i'),
                    'venue_name' => $event->venue_name,
                    'city' => $event->city,
                    'latitude' => (float) $event->latitude,
                    'longitude' => (float) $event->longitude,
                    'organizer' => $event->organizer ? $event->organizer->getDisplayName() : 'N/A',
                    'url' => route('events.show', $event),
                ];
            });

            return response()->json($mappedEvents);

        } catch (\Exception $e) {
            Log::error('Error in events.near endpoint: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return response()->json(['error' => 'Errore nel caricamento degli eventi', 'debug' => $e->getMessage()], 500);
        }
    }

    /**
     * Apply to participate in a public event
     */
    public function apply(Request $request, Event $event): RedirectResponse
    {
        $user = Auth::user();

        if (!EventRequest::canUserApplyToEvent($user, $event)) {
            return back()->with('error', 'Non puoi richiedere di partecipare a questo evento.');
        }

        $validated = $request->validate([
            'message' => 'required|string|min:10',
            'requested_role' => 'required|string|in:performer,judge,technician,host',
            'portfolio_links' => 'nullable|array',
            'portfolio_links.*' => 'url',
            'experience' => 'nullable|string',
        ]);

        $validated['event_id'] = $event->id;
        $validated['user_id'] = $user->id;

        EventRequest::createWithNotification($validated);

        return back()->with('success', 'Richiesta di partecipazione inviata con successo!');
    }

    /**
     * Get events for calendar
     */
    public function calendar(Request $request): JsonResponse
    {
        $user = Auth::user();

        $query = Event::published()->upcoming();

        // Show user's events (organized, invited to, or requested)
        if ($user) {
            $query->where(function ($q) use ($user) {
                $q->where('organizer_id', $user->id)
                  ->orWhereHas('invitations', function ($query) use ($user) {
                      $query->where('invited_user_id', $user->id)
                            ->where('status', 'accepted');
                  })
                  ->orWhereHas('requests', function ($query) use ($user) {
                      $query->where('user_id', $user->id)
                            ->where('status', 'accepted');
                  });
            });
        }

        $events = $query->get()->map(function ($event) use ($user) {
            $isOrganizer = $user && $event->organizer_id === $user->id;
            $isPrivate = !$event->is_public;

            // Determine event type and styling
            $className = 'event-participant';
            $backgroundColor = '#007bff';

            if ($isOrganizer) {
                $className = 'event-organizer';
                $backgroundColor = '#28a745';
            } elseif ($isPrivate) {
                $className = 'event-private';
                $backgroundColor = '#ffc107';
            }

            return [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start_datetime->toISOString(),
                'end' => $event->end_datetime->toISOString(),
                'url' => route('events.show', $event),
                'className' => $className,
                'backgroundColor' => $backgroundColor,
                'extendedProps' => [
                    'venue' => $event->venue_name,
                    'city' => $event->city,
                    'isPrivate' => $isPrivate,
                    'isOrganizer' => $isOrganizer,
                    'description' => Str::limit($event->description, 100)
                ]
            ];
        });

        return response()->json($events);
    }

    /**
     * Notify participants about event update
     */
    private function notifyEventUpdate(Event $event): void
    {
        $participantIds = collect();

        // Get accepted invitations
        $participantIds = $participantIds->merge(
            $event->invitations()
                  ->where('status', 'accepted')
                  ->pluck('invited_user_id')
        );

        // Get accepted requests
        $participantIds = $participantIds->merge(
            $event->requests()
                  ->where('status', 'accepted')
                  ->pluck('user_id')
        );

        // Create notifications
        foreach ($participantIds->unique() as $userId) {
            Notification::create([
                'user_id' => $userId,
                'type' => Notification::TYPE_EVENT_UPDATE,
                'title' => 'Evento Aggiornato',
                'message' => 'L\'evento "' . $event->title . '" è stato aggiornato',
                'data' => ['event_id' => $event->id],
                'action_url' => route('events.show', $event),
                'action_text' => 'Vedi Evento',
                'priority' => Notification::PRIORITY_NORMAL,
            ]);
        }
    }

    /**
     * Notify participants about event cancellation
     */
    private function notifyEventCancellation(Event $event): void
    {
        $participantIds = collect();

        // Get all invitations (pending and accepted)
        $participantIds = $participantIds->merge(
            $event->invitations()
                  ->whereIn('status', ['pending', 'accepted'])
                  ->pluck('invited_user_id')
        );

        // Get all requests (pending and accepted)
        $participantIds = $participantIds->merge(
            $event->requests()
                  ->whereIn('status', ['pending', 'accepted'])
                  ->pluck('user_id')
        );

        // Create notifications
        foreach ($participantIds->unique() as $userId) {
            Notification::create([
                'user_id' => $userId,
                'type' => Notification::TYPE_EVENT_CANCELLED,
                'title' => 'Evento Cancellato',
                'message' => 'L\'evento "' . $event->title . '" è stato cancellato',
                'data' => ['event_id' => $event->id],
                'priority' => Notification::PRIORITY_HIGH,
            ]);
        }
    }
}
