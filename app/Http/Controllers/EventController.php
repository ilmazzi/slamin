<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventInvitation;
use App\Models\EventRequest;
use App\Models\User;
use App\Models\Notification;
use App\Models\RecentVenue;
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
use App\Services\LoggingService;

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
                     ->orderBy('start_datetime');

        // Apply upcoming filter only if not filtering for past events or invitations
        if (!$request->filled('filter') || ($request->filter !== 'past' && $request->filter !== 'invitations')) {
            $query->upcoming();
        }

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

        // New dashboard filters
        if ($request->filled('filter') && $user) {
            $userId = $user->id;

            switch ($request->filter) {
                case 'past':
                    // Past events (organized + participated)
                    $query->where('start_datetime', '<', now())
                          ->where(function ($q) use ($userId) {
                              $q->where('organizer_id', $userId)
                                ->orWhereHas('invitations', function ($inviteQuery) use ($userId) {
                                    $inviteQuery->where('invited_user_id', $userId)
                                                ->where('status', 'accepted');
                                })
                                ->orWhereHas('requests', function ($requestQuery) use ($userId) {
                                    $requestQuery->where('user_id', $userId)
                                                 ->where('status', 'accepted');
                                });
                          });
                    break;

                case 'future':
                    // Future events (organized + participated)
                    $query->where('start_datetime', '>=', now())
                          ->where(function ($q) use ($userId) {
                              $q->where('organizer_id', $userId)
                                ->orWhereHas('invitations', function ($inviteQuery) use ($userId) {
                                    $inviteQuery->where('invited_user_id', $userId)
                                                ->where('status', 'accepted');
                                })
                                ->orWhereHas('requests', function ($requestQuery) use ($userId) {
                                    $requestQuery->where('user_id', $userId)
                                                 ->where('status', 'accepted');
                                });
                          });
                    break;

                case 'organized':
                    // Only events organized by user
                    $query->where('organizer_id', $userId);
                    break;

                case 'invitations':
                    // Events with pending invitations (received + sent)
                    $query->where(function ($q) use ($userId) {
                        // Events where user has received pending invitations
                        $q->whereHas('invitations', function ($inviteQuery) use ($userId) {
                            $inviteQuery->where('invited_user_id', $userId)
                                        ->where('status', 'pending');
                        })
                        // OR events where user has sent pending invitations
                        ->orWhereHas('invitations', function ($inviteQuery) use ($userId) {
                            $inviteQuery->where('inviter_id', $userId)
                                        ->where('status', 'pending');
                        });
                    });

                    // Debug: Log the query for invitations filter
                    Log::info('Invitations filter query', [
                        'user_id' => $userId,
                        'pending_received' => \App\Models\EventInvitation::where('invited_user_id', $userId)->where('status', 'pending')->count(),
                        'pending_sent' => \App\Models\EventInvitation::where('inviter_id', $userId)->where('status', 'pending')->count(),
                    ]);
                    break;
            }
        }

        $events = $query->paginate($request->get('per_page', 10));

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

        // Ottieni i luoghi recenti dell'utente (solo se autenticato)
        $recentVenues = collect(); // Default vuoto
        if (Auth::check()) {
            $recentVenues = RecentVenue::getRecentVenues(4);
        }

        return view('events.create', compact('venueOwners', 'recentVenues'));
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
                'description' => 'nullable|string',
                'requirements' => 'nullable|string',
                'start_datetime' => 'required|date_format:Y-m-d H:i|after:now',
                'end_datetime' => 'required|date_format:Y-m-d H:i|after:start_datetime',
                'registration_deadline' => 'nullable|date_format:Y-m-d H:i|before:start_datetime',
                'venue_name' => 'nullable|string|max:255',
                'venue_address' => 'nullable|string',
                'city' => 'nullable|string|max:255',
                'postcode' => 'nullable|string|max:10',
                'country' => 'nullable|string|size:2',
                'latitude' => 'nullable|numeric|between:-90,90',
                'longitude' => 'nullable|numeric|between:-180,180',
                'is_public' => 'required|in:0,1',
                'category' => 'required|string|in:' . implode(',', array_keys(Event::getCategories())),
                'max_participants' => 'nullable|integer|min:1',
                'entry_fee' => 'nullable|numeric|min:0',
                'venue_owner_id' => 'nullable|exists:users,id',
                'allow_requests' => 'nullable',
                'tags' => 'nullable|string',
                'event_image' => 'nullable',
                'invitations' => 'nullable|string', // JSON string of invitations
                'invited_users' => 'nullable|string', // JSON string of invited users for private events
                // Nuovi campi per inviti e ingaggi
                'invitation_deadline' => 'nullable|date_format:Y-m-d H:i|after:now',
                'gig_positions' => 'nullable|string', // JSON string of gig positions
                // Recurrence fields
                'is_recurring' => 'nullable|boolean',
                'recurrence_type' => 'nullable|in:daily,weekly,monthly,yearly',
                'recurrence_interval' => 'nullable|integer|min:1',
                'recurrence_count' => 'nullable|integer|min:1|max:100',
                'recurrence_weekdays' => 'nullable|array',
                'recurrence_weekdays.*' => 'integer|in:1,2,3,4,5,6,7',
                'recurrence_monthday' => 'nullable|integer|min:1|max:31',
                // Online event fields
                'is_online' => 'nullable|boolean',
                'timezone' => 'required_if:is_online,1|string|max:50',
                'online_url' => 'nullable|url|max:500',
            ], [
                'start_datetime.after' => 'La data di inizio deve essere nel futuro.',
                'end_datetime.after' => 'La data di fine deve essere dopo la data di inizio.',
                'registration_deadline.before' => 'La scadenza iscrizioni deve essere prima della data di inizio.',
                'category.required' => 'La categoria è obbligatoria.',
                'category.in' => 'La categoria selezionata non è valida.',
                'recurrence_type.in' => 'Il tipo di ricorrenza selezionato non è valido.',
                'recurrence_count.max' => 'Il numero di occorrenze non può superare 100.',
                'invitation_deadline.after' => 'La scadenza inviti deve essere nel futuro.',
                'timezone.required_if' => 'Il fuso orario è obbligatorio per eventi online.',
                'online_url.url' => 'L\'URL dell\'evento online deve essere un link valido.',
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

        // Process tags (if present)
        if (isset($validated['tags']) && $validated['tags']) {
            $validated['tags'] = array_map('trim', explode(',', $validated['tags']));
        } else {
            $validated['tags'] = []; // Set empty array if no tags
        }

        // Convert is_public to boolean explicitly
        $validated['is_public'] = $validated['is_public'] === '1' || $validated['is_public'] === 1;

        // Convert allow_requests to boolean
        $validated['allow_requests'] = isset($validated['allow_requests']) && ($validated['allow_requests'] === 'on' || $validated['allow_requests'] === '1' || $validated['allow_requests'] === 1 || $validated['allow_requests'] === true);

        // Convert is_recurring to boolean
        $validated['is_recurring'] = isset($validated['is_recurring']) && ($validated['is_recurring'] === '1' || $validated['is_recurring'] === 1 || $validated['is_recurring'] === true);

        // Convert is_online to boolean
        $validated['is_online'] = isset($validated['is_online']) && ($validated['is_online'] === '1' || $validated['is_online'] === 1 || $validated['is_online'] === true);

        // Convert numeric recurrence fields
        if (isset($validated['recurrence_interval'])) {
            $validated['recurrence_interval'] = (int) $validated['recurrence_interval'];
        }
        if (isset($validated['recurrence_count'])) {
            $validated['recurrence_count'] = (int) $validated['recurrence_count'];
        }
        if (isset($validated['recurrence_monthday'])) {
            $validated['recurrence_monthday'] = (int) $validated['recurrence_monthday'];
        }

        // Handle online event fields
        if (!$validated['is_online']) {
            // Clear online fields if not online
            $validated['timezone'] = null;
            $validated['online_url'] = null;
        } else {
            // Clear physical location fields if online
            $validated['venue_name'] = null;
            $validated['venue_address'] = null;
            $validated['city'] = null;
            $validated['postcode'] = null;
            $validated['country'] = null;
            $validated['latitude'] = null;
            $validated['longitude'] = null;
            $validated['venue_owner_id'] = null;
        }

        // Handle image upload
        if ($request->hasFile('event_image')) {
            $image = $request->file('event_image');
            if (is_array($image)) {
                // Se è un array, prendi il primo elemento
                $image = $image[0] ?? null;
            }
            if ($image && $image->isValid()) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $imagePath = $image->storeAs('events', $imageName, 'public');
                $validated['image_url'] = '/storage/' . $imagePath;
            }
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

        // Process invited users for private events
        $invitedUsers = [];
        if (!$validated['is_public'] && !empty($validated['invited_users'])) {
            try {
                $invitedUsers = json_decode($validated['invited_users'], true);
                if (!is_array($invitedUsers)) {
                    $invitedUsers = [];
                }
            } catch (\Exception $e) {
                Log::warning('Failed to parse invited users JSON: ' . $e->getMessage());
                $invitedUsers = [];
            }
        }

        // Remove invitations and invited_users from validated data as they're not part of Event model
        unset($validated['invitations']);
        unset($validated['invited_users']);

        DB::transaction(function () use ($validated, $invitations, $invitedUsers, &$event) {
            // Process recurrence settings
            if (isset($validated['is_recurring']) && $validated['is_recurring'] && !empty($validated['recurrence_type'])) {
                // Set default values for recurrence
                $validated['recurrence_interval'] = $validated['recurrence_interval'] ?? 1;
                $validated['recurrence_count'] = $validated['recurrence_count'] ?? 5; // Default to 5 occurrences

                // For weekly type, ensure recurrence_weekdays is set
                if ($validated['recurrence_type'] === 'weekly' && empty($validated['recurrence_weekdays'])) {
                    $startDate = Carbon::parse($validated['start_datetime']);
                    $validated['recurrence_weekdays'] = [$startDate->dayOfWeek];
                }

                // For monthly type, ensure recurrence_monthday is set
                if ($validated['recurrence_type'] === 'monthly' && empty($validated['recurrence_monthday'])) {
                    $startDate = Carbon::parse($validated['start_datetime']);
                    $validated['recurrence_monthday'] = $startDate->day;
                }
            } else {
                // Clear recurrence fields if not recurring
                $validated['is_recurring'] = false;
                $validated['recurrence_type'] = null;
                $validated['recurrence_interval'] = null;
                $validated['recurrence_count'] = null;
                $validated['recurrence_weekdays'] = null;
                $validated['recurrence_monthday'] = null;
            }

            // Create the event
            $event = Event::create($validated);

            // Create recurring events if needed
            if ($event->is_recurring) {
                $createdEvents = $event->createRecurringEvents();
                Log::info('Created recurring events', [
                    'parent_event_id' => $event->id,
                    'created_count' => count($createdEvents)
                ]);
            }



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

            // Create invitations for private event users
            foreach ($invitedUsers as $invitedUser) {
                if (isset($invitedUser['id'])) {
                    // Verify user exists
                    $user = User::find($invitedUser['id']);
                    if ($user) {
                        // Create invitation for audience role
                        $eventInvitation = EventInvitation::create([
                            'event_id' => $event->id,
                            'invited_user_id' => $invitedUser['id'],
                            'inviter_id' => Auth::id(),
                            'role' => 'audience', // Default role for private event invitations
                            'message' => "Sei invitato al nostro evento privato!",
                            'status' => EventInvitation::STATUS_PENDING,
                            'expires_at' => Carbon::parse($event->start_datetime)->subDays(1), // Expires 1 day before event
                        ]);

                        // Create notification
                        Notification::createEventInvitation($eventInvitation);

                        // Send email invitation
                        try {
                            Mail::to($user->email)->send(new EventInvitationMail($eventInvitation));
                            Log::info('Private event invitation email sent', [
                                'event_id' => $event->id,
                                'invited_user_id' => $invitedUser['id'],
                                'email' => $user->email
                            ]);
                        } catch (\Exception $e) {
                            Log::error('Failed to send private event invitation email', [
                                'event_id' => $event->id,
                                'invited_user_id' => $invitedUser['id'],
                                'email' => $user->email,
                                'error' => $e->getMessage()
                            ]);
                        }
                    }
                }
            }
        });

        $invitationCount = count($invitations);
        $invitedUsersCount = count($invitedUsers);

        // Log dell'attività
        LoggingService::logEvent('create', [
            'event_id' => $event->id,
            'event_title' => $event->title,
            'event_category' => $event->category,
            'is_public' => $event->is_public,
            'is_recurring' => $event->is_recurring,
            'recurrence_type' => $event->recurrence_type,
            'recurrence_count' => $event->recurrence_count,
            'invitations_count' => $invitationCount,
            'private_invitations_count' => $invitedUsersCount,
            'has_image' => !empty($event->image_url),
            'venue_name' => $event->venue_name,
            'city' => $event->city,
            'country' => $event->country,
            'start_datetime' => $event->start_datetime,
            'end_datetime' => $event->end_datetime,
        ], 'Event', $event->id);

        $successMessage = __('events.event_created_success');

        if ($invitationCount > 0) {
            $successMessage .= ' ' . __('events.invitations_sent_success', ['count' => $invitationCount]);
        }

        if ($invitedUsersCount > 0) {
            $successMessage .= ' ' . __('events.private_invitations_sent_success', ['count' => $invitedUsersCount]);
        }

        // Salva il luogo come recente solo per eventi fisici
        if (!$event->is_online && $event->venue_name) {
            RecentVenue::saveRecentVenue([
                'venue_name' => $event->venue_name,
                'venue_address' => $event->venue_address,
                'city' => $event->city,
                'postcode' => $event->postcode,
                'country' => $event->country,
                'latitude' => $event->latitude,
                'longitude' => $event->longitude,
            ]);
        }

        return redirect()
            ->route('events.show', $event)
            ->with('success', $successMessage);
    }

    /**
     * Get recent venues for the current user
     */
    public function getRecentVenues(): JsonResponse
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not authenticated'
            ], 401);
        }
        
        $recentVenues = RecentVenue::where('user_id', $user->id)
            ->orderBy('last_used_at', 'desc')
            ->limit(4)
            ->get();
        
        return response()->json([
            'success' => true,
            'venues' => $recentVenues
        ]);
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
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'start_datetime' => 'required|date|after:now',
            'end_datetime' => 'required|date|after:start_datetime',
            'registration_deadline' => 'nullable|date|before:start_datetime',
            'venue_name' => 'nullable|string|max:255',
            'venue_address' => 'nullable|string',
            'city' => 'nullable|string|max:255',
            'postcode' => 'nullable|string|max:10',
            'country' => 'nullable|string|size:2',
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
            // Online event fields
            'is_online' => 'nullable|boolean',
            'timezone' => 'required_if:is_online,1|string|max:50',
            'online_url' => 'nullable|url|max:500',
        ], [
            'timezone.required_if' => 'Il fuso orario è obbligatorio per eventi online.',
            'online_url.url' => 'L\'URL dell\'evento online deve essere un link valido.',
        ]);

        // Process tags (if present)
        if (isset($validated['tags']) && $validated['tags']) {
            $validated['tags'] = array_map('trim', explode(',', $validated['tags']));
        } else {
            $validated['tags'] = []; // Set empty array if no tags
        }

        // Convert is_online to boolean
        $validated['is_online'] = isset($validated['is_online']) && ($validated['is_online'] === '1' || $validated['is_online'] === 1 || $validated['is_online'] === true);

        // Custom validation for physical events
        if (!$validated['is_online']) {
            $errors = [];
            
            if (empty($validated['venue_name'])) {
                $errors['venue_name'] = ['Il nome del venue è obbligatorio per eventi fisici.'];
            }
            
            if (empty($validated['venue_address'])) {
                $errors['venue_address'] = ['L\'indirizzo del venue è obbligatorio per eventi fisici.'];
            }
            
            if (empty($validated['city'])) {
                $errors['city'] = ['La città è obbligatoria per eventi fisici.'];
            }
            
            if (empty($validated['country'])) {
                $errors['country'] = ['Il paese è obbligatorio per eventi fisici.'];
            }
            
            if (!empty($errors)) {
                Log::error('Event validation failed - physical event requirements', [
                    'errors' => $errors,
                    'request_data' => $request->all()
                ]);
                throw new \Illuminate\Validation\ValidationException(
                    new \Illuminate\Validation\Validator(app('translator'), [], [], []),
                    response()->json($errors, 422)
                );
            }
        }

        // Handle online event fields
        if (!$validated['is_online']) {
            // Clear online fields if not online
            $validated['timezone'] = null;
            $validated['online_url'] = null;
        } else {
            // Clear physical location fields if online
            $validated['venue_name'] = null;
            $validated['venue_address'] = null;
            $validated['city'] = null;
            $validated['postcode'] = null;
            $validated['country'] = null;
            $validated['latitude'] = null;
            $validated['longitude'] = null;
            $validated['venue_owner_id'] = null;
        }

        // Log dell'attività prima dell'aggiornamento
        $oldData = $event->toArray();

        $event->update($validated);

        // Log dell'attività dopo l'aggiornamento
        LoggingService::logEvent('update', [
            'event_id' => $event->id,
            'event_title' => $event->title,
            'old_title' => $oldData['title'],
            'old_status' => $oldData['status'],
            'new_status' => $event->status,
            'old_start_datetime' => $oldData['start_datetime'],
            'new_start_datetime' => $event->start_datetime,
            'old_venue_name' => $oldData['venue_name'],
            'new_venue_name' => $event->venue_name,
            'old_city' => $oldData['city'],
            'new_city' => $event->city,
            'has_image_changed' => isset($validated['image_url']),
            'tags_changed' => isset($validated['tags']),
            'updated_fields' => array_keys($validated),
        ], 'Event', $event->id);

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

        // Log dell'attività prima dell'eliminazione
        LoggingService::logEvent('delete', [
            'event_id' => $event->id,
            'event_title' => $event->title,
            'event_category' => $event->category,
            'event_status' => $event->status,
            'organizer_id' => $event->organizer_id,
            'venue_name' => $event->venue_name,
            'city' => $event->city,
            'start_datetime' => $event->start_datetime,
            'end_datetime' => $event->end_datetime,
            'participants_count' => $event->invitations()->where('status', 'accepted')->count(),
            'pending_invitations_count' => $event->invitations()->where('status', 'pending')->count(),
            'requests_count' => $event->requests()->count(),
        ], 'Event', $event->id);

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
                'filter' => 'nullable|string|in:my,my_private,nearby',
                'type' => 'nullable|string|in:public,private',
            ]);

            Log::info('Events near request params:', $request->all());

            $user = Auth::user();

            // Base query
            $query = Event::whereNotNull('latitude')
                          ->whereNotNull('longitude')
                          ->published();

            Log::info('After published() - Count: ' . $query->count());

            // Apply upcoming filter only if not filtering for past events or invitations
            if (!$request->filled('filter') || ($request->filter !== 'past' && $request->filter !== 'invitations')) {
                $query->upcoming();
                Log::info('After upcoming() - Count: ' . $query->count());
            }

            // Filter by type (public/private)
            if ($request->filled('type')) {
                $query->where('is_public', $request->type === 'public');
                Log::info('After type filter - Count: ' . $query->count());
            }

            // Gestione filtri per utente autenticato
            if ($request->filled('filter') && $user) {
                $userId = $user->id;

                switch ($request->filter) {
                    case 'my':
                        // Eventi organizzati dall'utente o dove partecipa
                        $query->where(function ($q) use ($userId) {
                            $q->where('organizer_id', $userId)
                              ->orWhereHas('invitations', function ($inviteQuery) use ($userId) {
                                  $inviteQuery->where('invited_user_id', $userId)
                                              ->where('status', 'accepted');
                              })
                              ->orWhereHas('requests', function ($requestQuery) use ($userId) {
                                  $requestQuery->where('user_id', $userId)
                                               ->where('status', 'accepted');
                              });
                        });
                        Log::info('After my filter - Count: ' . $query->count());
                        break;

                    case 'my_private':
                        // Solo eventi privati organizzati dall'utente o dove partecipa
                        $query->where('is_public', false)
                              ->where(function ($q) use ($userId) {
                                  $q->where('organizer_id', $userId)
                                    ->orWhereHas('invitations', function ($inviteQuery) use ($userId) {
                                        $inviteQuery->where('invited_user_id', $userId)
                                                    ->where('status', 'accepted');
                                    })
                                    ->orWhereHas('requests', function ($requestQuery) use ($userId) {
                                        $requestQuery->where('user_id', $userId)
                                                     ->where('status', 'accepted');
                                    });
                              });
                        Log::info('After my_private filter - Count: ' . $query->count());
                        break;
                }
            } else {
                // Se non ci sono filtri specifici, mostra solo eventi pubblici o privati accessibili
                if ($user) {
                    $userId = $user->id;
                    $query->where(function ($q) use ($userId) {
                        // Eventi pubblici
                        $q->where('is_public', true)
                          // OR eventi privati organizzati dall'utente
                          ->orWhere('organizer_id', $userId)
                          // OR eventi privati dove l'utente ha un invito accettato
                          ->orWhere(function ($subQ) use ($userId) {
                              $subQ->where('is_public', false)
                                   ->whereHas('invitations', function ($inviteQuery) use ($userId) {
                                       $inviteQuery->where('invited_user_id', $userId)
                                                   ->where('status', 'accepted');
                                   });
                          })
                          // OR eventi privati dove l'utente ha una richiesta accettata
                          ->orWhere(function ($subQ) use ($userId) {
                              $subQ->where('is_public', false)
                                   ->whereHas('requests', function ($requestQuery) use ($userId) {
                                       $requestQuery->where('user_id', $userId)
                                                    ->where('status', 'accepted');
                                   });
                          });
                    });
                }
            }

            // Applica filtri temporali
            if ($request->filled('date_from')) {
                $query->whereDate('start_datetime', '>=', $request->date_from);
                Log::info('After date_from filter - Count: ' . $query->count());
            }

            if ($request->filled('date_to')) {
                $query->whereDate('start_datetime', '<=', $request->date_to);
                Log::info('After date_to filter - Count: ' . $query->count());
            }

            // Filtro eventi gratuiti
            if ($request->filled('free_only') && $request->free_only == '1') {
                $query->where(function($q) {
                    $q->where('entry_fee', 0)
                      ->orWhereNull('entry_fee');
                });
                Log::info('After free_only filter - Count: ' . $query->count());
            }

            // Applica filtro di distanza SOLO se esplicitamente richiesto (filtro 'nearby')
            // o se viene passato un raggio specifico
            if ($request->filled('filter') && $request->filter === 'nearby') {
                $radius = $request->radius ?? 10; // Default 10km per filtro "Vicino a me"
                $query->nearLocation(
                    $request->latitude,
                    $request->longitude,
                    $radius
                );
                Log::info('After nearLocation filter (nearby) - Count: ' . $query->count());
            } elseif ($request->filled('radius') && $request->radius > 0) {
                // Se viene specificato un raggio personalizzato
                $query->nearLocation(
                    $request->latitude,
                    $request->longitude,
                    $request->radius
                );
                Log::info('After nearLocation filter (custom radius) - Count: ' . $query->count());
            }

            $events = $query->with(['organizer'])->get();

            Log::info('Final events count: ' . $events->count());
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
                    'category' => $event->category,
                    'category_name' => $event->category ? __('events.category_' . $event->category) : null,
                    'category_color_class' => $event->category_color_class,
                    'is_online' => $event->is_online,
                    'online_url' => $event->online_url,
                    'timezone' => $event->timezone,
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
