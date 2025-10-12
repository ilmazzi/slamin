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
                     ->orderByRaw('CASE WHEN start_datetime IS NULL THEN 1 ELSE 0 END, start_datetime');

        // Apply upcoming filter only if not filtering for past events or invitations
        if (!$request->filled('filter') || ($request->filter !== 'past' && $request->filter !== 'invitations')) {
            $query->where(function ($q) {
                $q->where(function ($subQ) {
                    $subQ->where('start_datetime', '>', Carbon::now())
                         ->orWhere('is_availability_based', true);
                })
                // Include completed events
                ->orWhere('status', 'completed');
            });
        }

        // Filter by search term
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('venue_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('city', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('organizer', function ($organizerQuery) use ($searchTerm) {
                      $organizerQuery->where('name', 'like', '%' . $searchTerm . '%')
                                    ->orWhere('nickname', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        // Filter by city
        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        // Filter by type (public/private)
        if ($request->filled('type')) {
            $query->where('is_public', $request->type === 'public');
        }

        // Filter by organizer
        if ($request->filled('organizer')) {
            $query->where('organizer_id', $request->organizer);
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

        // Quick filters
        if ($request->filled('quick_filter')) {
            switch ($request->quick_filter) {
                case 'today':
                    $query->whereDate('start_datetime', today());
                    break;
                case 'tomorrow':
                    $query->whereDate('start_datetime', today()->addDay());
                    break;
                case 'weekend':
                    $query->where(function ($q) {
                        $q->whereRaw('DAYOFWEEK(start_datetime) IN (1, 7)') // Domenica = 1, Sabato = 7
                          ->whereDate('start_datetime', '>=', today())
                          ->whereDate('start_datetime', '<=', today()->addDays(7));
                    });
                    break;
                case 'free':
                    $query->where(function($q) {
                        $q->where('entry_fee', 0)
                          ->orWhereNull('entry_fee');
                    });
                    break;
                case 'nearby':
                    // This will be handled by the map filters, not here
                    break;
            }
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

        // Handle past events filter (show all public past events)
        if ($request->filled('filter') && $request->filter === 'past') {
            $query->where('start_datetime', '<', now());

            if ($user) {
                $userId = $user->id;
                $query->where(function ($q) use ($userId) {
                    // Public events
                    $q->where('is_public', true)
                      // OR private events organized by user
                      ->orWhere('organizer_id', $userId)
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
                // If no user, show only public events
                $query->where('is_public', true);
            }
        }
        // New dashboard filters
        elseif ($request->filled('filter') && $user) {
            $userId = $user->id;

            switch ($request->filter) {

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

        // Calcola le statistiche sui dati filtrati
        $filteredQuery = clone $query;
        $filteredEvents = $filteredQuery->get();

        $statistics = [
            'total_events' => $filteredEvents->count(),
            'public_events' => $filteredEvents->where('is_public', true)->count(),
            'upcoming_events' => $filteredEvents->where('start_datetime', '>', now())->count(),
            'venues_count' => $filteredEvents->pluck('venue_name')->filter()->unique()->count(),
        ];

        return view('events.index', compact('events', 'statistics'));
    }

    /**
     * Show the form for creating a new event
     * DEPRECATED: Now using Livewire component (resources/views/events/create-livewire.blade.php)
     * Route: /events/create
     */
    // Removed - using Livewire EventCreation component instead

    /**
     * Store a newly created event
     */
        public function store(Request $request): RedirectResponse
    {
        // Controlla se l'utente può creare eventi usando Spatie
        if (!Auth::check() || (!Auth::user()->can('events.create.public') && !Auth::user()->can('events.create.private'))) {
            abort(403, 'Non hai i permessi per creare eventi');
        }

        // Log the request data for debugging
        Log::info('Event creation request', [
            'user_id' => Auth::id(),
            'request_data' => $request->all()
        ]);



        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'subtitle' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'requirements' => 'nullable|string',
                'start_datetime' => 'nullable|date_format:Y-m-d H:i|after:now',
                'end_datetime' => 'nullable|date_format:Y-m-d H:i|after:start_datetime',
                'registration_deadline' => 'nullable|date_format:Y-m-d H:i',
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
                'is_linked_to_group' => 'nullable|boolean',
                'group_ids' => 'nullable|array',
                'group_ids.*' => 'exists:groups,id',
                'allow_requests' => 'nullable',
                'tags' => 'nullable|string',
                'event_image' => 'nullable',
                'invitations' => 'nullable|string', // JSON string of invitations
                'invited_users' => 'nullable|string', // JSON string of invited users for private events
                'private_invited_users' => 'nullable|string', // JSON string of private invited users
                'artist_invited_users' => 'nullable|string', // JSON string of artist invited users
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
                // Availability fields
                'is_availability_based' => 'nullable|boolean',
                'availability_instructions' => 'nullable|string|max:1000',
                'availability_deadline' => 'nullable|date_format:Y-m-d H:i',
                'availability_options' => 'nullable|array',
                'availability_options.*.datetime' => 'required|date_format:Y-m-d H:i',
                'availability_options.*.description' => 'nullable|string|max:255',
                // Festival fields
                'festival_id' => 'nullable|exists:events,id',
                'selected_festival_events' => 'nullable|string', // JSON string of selected events
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

        // Validazione personalizzata per eventi con disponibilità multiple
        if ($validated['is_availability_based'] ?? false) {
            // Per eventi con disponibilità multiple, le date non sono obbligatorie
            // ma se fornite devono essere valide
            if (empty($validated['start_datetime']) && empty($validated['end_datetime'])) {
                // OK - le date verranno definite tramite le opzioni di disponibilità
            } elseif (!empty($validated['start_datetime']) && !empty($validated['end_datetime'])) {
                // Se fornite, devono essere valide
                if (strtotime($validated['end_datetime']) <= strtotime($validated['start_datetime'])) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'end_datetime' => ['La data di fine deve essere successiva a quella di inizio.']
                    ]);
                }
            } else {
                // Se una sola data è fornita, entrambe devono essere fornite
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'start_datetime' => ['Se fornisci una data, devi fornire sia data di inizio che di fine.'],
                    'end_datetime' => ['Se fornisci una data, devi fornire sia data di inizio che di fine.']
                ]);
            }

            // Validazione registration_deadline se fornita
            if (!empty($validated['registration_deadline']) && !empty($validated['start_datetime'])) {
                if (strtotime($validated['registration_deadline']) >= strtotime($validated['start_datetime'])) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'registration_deadline' => ['La scadenza iscrizioni deve essere prima della data di inizio.']
                    ]);
                }
            }

            // Validazione availability_deadline se fornita
            if (!empty($validated['availability_deadline']) && !empty($validated['start_datetime'])) {
                if (strtotime($validated['availability_deadline']) >= strtotime($validated['start_datetime'])) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'availability_deadline' => ['La scadenza disponibilità deve essere prima della data di inizio.']
                    ]);
                }
            }
        } else {
            // Per eventi normali, le date sono obbligatorie
            if (empty($validated['start_datetime'])) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'start_datetime' => ['La data e ora di inizio sono obbligatorie per eventi normali.']
                ]);
            }
            if (empty($validated['end_datetime'])) {
                throw \Illuminate\Validation\ValidationException::withMessages([
                    'end_datetime' => ['La data e ora di fine sono obbligatorie per eventi normali.']
                ]);
            }

            // Validazione registration_deadline per eventi normali
            if (!empty($validated['registration_deadline'])) {
                if (strtotime($validated['registration_deadline']) >= strtotime($validated['start_datetime'])) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'registration_deadline' => ['La scadenza iscrizioni deve essere prima della data di inizio.']
                    ]);
                }
            }
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

        // Convert is_linked_to_group to boolean
        $validated['is_linked_to_group'] = isset($validated['is_linked_to_group']) && ($validated['is_linked_to_group'] === '1' || $validated['is_linked_to_group'] === 1 || $validated['is_linked_to_group'] === true);

        // Handle group association
        if (!$validated['is_linked_to_group']) {
            $validated['group_id'] = null;
        }

        // Handle registration deadline
        $hasRegistrationDeadline = $request->input('has_registration_deadline') === '1';
        if ($hasRegistrationDeadline) {
            $deadlineDate = $request->input('registration_deadline_date');
            $deadlineTime = $request->input('registration_deadline_time');

            if ($deadlineDate && $deadlineTime) {
                $validated['registration_deadline'] = $deadlineDate . ' ' . $deadlineTime;
            } else {
                $validated['registration_deadline'] = null;
            }
        } else {
            $validated['registration_deadline'] = null;
        }

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
        $privateInvitedUsers = [];
        if (!$validated['is_public'] && !empty($validated['private_invited_users'])) {
            try {
                $privateInvitedUsers = json_decode($validated['private_invited_users'], true);
                if (!is_array($privateInvitedUsers)) {
                    $privateInvitedUsers = [];
                }
            } catch (\Exception $e) {
                Log::warning('Failed to parse private invited users JSON: ' . $e->getMessage());
                $privateInvitedUsers = [];
            }
        }

        // Process invited users for artist invites
        $artistInvitedUsers = [];
        if (!empty($validated['artist_invited_users'])) {
            try {
                $artistInvitedUsers = json_decode($validated['artist_invited_users'], true);
                if (!is_array($artistInvitedUsers)) {
                    $artistInvitedUsers = [];
                }
            } catch (\Exception $e) {
                Log::warning('Failed to parse artist invited users JSON: ' . $e->getMessage());
                $artistInvitedUsers = [];
            }
        }

        // Process gig_positions if provided
        if (!empty($validated['gig_positions'])) {
            try {
                $gigPositions = json_decode($validated['gig_positions'], true);
                if (is_array($gigPositions)) {
                    $validated['gig_positions'] = $gigPositions;
                } else {
                    $validated['gig_positions'] = [];
                }
            } catch (\Exception $e) {
                Log::warning('Failed to parse gig_positions JSON: ' . $e->getMessage());
                $validated['gig_positions'] = [];
            }
        } else {
            $validated['gig_positions'] = [];
        }

        // Remove invitations and invited_users from validated data as they're not part of Event model
        unset($validated['invitations']);
        unset($validated['private_invited_users']);
        unset($validated['artist_invited_users']);

        // Process festival data
        $festivalEvents = [];
        if (!empty($validated['selected_festival_events'])) {
            try {
                $festivalEvents = json_decode($validated['selected_festival_events'], true);
                if (!is_array($festivalEvents)) {
                    $festivalEvents = [];
                }
            } catch (\Exception $e) {
                Log::warning('Failed to parse selected festival events JSON: ' . $e->getMessage());
                $festivalEvents = [];
            }
        }

        // Remove selected_festival_events from validated data as it's not part of Event model
        unset($validated['selected_festival_events']);

        // Save group_ids before removing from validated data
        $groupIds = $validated['group_ids'] ?? [];

        // Remove group_ids from validated data as it's handled separately
        unset($validated['group_ids']);

        DB::transaction(function () use ($validated, $invitations, $privateInvitedUsers, $artistInvitedUsers, $festivalEvents, $groupIds, &$event) {
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

            // Save availability options if this is an availability-based event
            if ($event->is_availability_based && !empty($validated['availability_options'])) {
                foreach ($validated['availability_options'] as $index => $option) {
                    \App\Models\EventAvailabilityOption::create([
                        'event_id' => $event->id,
                        'datetime' => $option['datetime'],
                        'description' => $option['description'] ?? null,
                        'sort_order' => $index + 1,
                        'is_active' => true,
                    ]);
                }

                Log::info('Availability options saved', [
                    'event_id' => $event->id,
                    'options_count' => count($validated['availability_options'])
                ]);
            }

            // Process groups if linked to groups
            if (!empty($groupIds) && is_array($groupIds)) {
                $groupData = [];
                foreach ($groupIds as $groupId) {
                    $groupData[$groupId] = ['group_permissions' => 'creator_only'];
                }
                $event->groups()->attach($groupData);

                Log::info('Event groups attached', [
                    'event_id' => $event->id,
                    'group_ids' => $groupIds
                ]);
            }

            // Process festival events if this is a festival
            if ($event->category === Event::CATEGORY_FESTIVAL && !empty($festivalEvents)) {
                $eventIds = array_column($festivalEvents, 'id');
                $event->festival_events = $eventIds;
                $event->save();

                // Update festival_id for all events that are part of this festival
                Event::whereIn('id', $eventIds)->update(['festival_id' => $event->id]);

                Log::info('Festival events saved', [
                    'festival_id' => $event->id,
                    'event_count' => count($eventIds),
                    'event_ids' => $eventIds
                ]);
            }

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
            foreach ($privateInvitedUsers as $invitedUser) {
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

            // Create invitations for artist users
            foreach ($artistInvitedUsers as $invitedUser) {
                if (isset($invitedUser['id'])) {
                    // Verify user exists
                    $user = User::find($invitedUser['id']);
                    if ($user) {
                        // Create invitation for artist role
                        $eventInvitation = EventInvitation::create([
                            'event_id' => $event->id,
                            'invited_user_id' => $invitedUser['id'],
                            'inviter_id' => Auth::id(),
                            'role' => $invitedUser['role'] ?? 'performer', // Use specified role or default to performer
                            'message' => "Sei invitato come artista al nostro evento!",
                            'status' => EventInvitation::STATUS_PENDING,
                            'expires_at' => Carbon::parse($event->start_datetime)->subDays(1), // Expires 1 day before event
                        ]);

                        // Create notification
                        Notification::createEventInvitation($eventInvitation);

                        // Send email invitation
                        try {
                            Mail::to($user->email)->send(new EventInvitationMail($eventInvitation));
                            Log::info('Artist invitation email sent', [
                                'event_id' => $event->id,
                                'invited_user_id' => $invitedUser['id'],
                                'email' => $user->email,
                                'role' => $invitedUser['role'] ?? 'performer'
                            ]);
                        } catch (\Exception $e) {
                            Log::error('Failed to send artist invitation email', [
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
        $privateInvitedUsersCount = count($privateInvitedUsers);
        $artistInvitedUsersCount = count($artistInvitedUsers);

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
            'private_invitations_count' => $privateInvitedUsersCount,
            'artist_invitations_count' => $artistInvitedUsersCount,
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

        if ($privateInvitedUsersCount > 0) {
            $successMessage .= ' ' . __('events.private_invitations_sent_success', ['count' => $privateInvitedUsersCount]);
        }

        if ($artistInvitedUsersCount > 0) {
            $successMessage .= ' ' . __('events.artist_invitations_sent_success', ['count' => $artistInvitedUsersCount]);
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
     * Get popular venues from all users
     */
    public function getRecentVenues(): JsonResponse
    {
        $recentVenues = RecentVenue::getPopularVenues(8);

        return response()->json([
            'success' => true,
            'venues' => $recentVenues
        ]);
    }

    /**
     * Search venues by name for autocomplete
     */
    public function searchVenues(Request $request): JsonResponse
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([
                'success' => true,
                'venues' => []
            ]);
        }

        $venues = RecentVenue::selectRaw('
                venue_name,
                venue_address,
                city,
                postcode,
                country,
                latitude,
                longitude,
                SUM(usage_count) as total_usage,
                MAX(last_used_at) as last_used_at,
                COUNT(DISTINCT user_id) as unique_users
            ')
            ->where('venue_name', 'LIKE', '%' . $query . '%')
            ->whereNotNull('venue_name')
            ->where('venue_name', '!=', '')
            ->groupBy('venue_name', 'venue_address', 'city', 'postcode', 'country', 'latitude', 'longitude')
            ->having('total_usage', '>=', 1)
            ->orderBy('total_usage', 'desc')
            ->orderBy('unique_users', 'desc')
            ->limit(5)
            ->get();

        return response()->json([
            'success' => true,
            'venues' => $venues
        ]);
    }

    /**
     * Search users for event invitations (API endpoint)
     */
    public function searchUsers(Request $request): JsonResponse
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json(['users' => []]);
        }

        $users = User::where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%");
            })
            ->whereHas('roles', function ($q) {
                // Only users with relevant roles for poetry slam events
                $q->whereIn('name', ['poet', 'judge', 'technician', 'organizer']);
            });

        // Exclude current user only if authenticated
        if (Auth::check()) {
            $users = $users->where('id', '!=', Auth::id());
        }

        $users = $users->limit(10)->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'roles' => $user->roles->pluck('name')->toArray(),
                    'avatar_url' => \App\Helpers\AvatarHelper::getUserAvatarUrl($user),
                ];
            });

        return response()->json(['users' => $users]);
    }

    /**
     * Search events for festival selection
     */
    public function searchEvents(Request $request): JsonResponse
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json(['events' => []]);
        }

        $events = Event::where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('description', 'like', "%{$query}%")
                  ->orWhere('venue_name', 'like', "%{$query}%")
                  ->orWhere('city', 'like', "%{$query}%");
            })
            ->where('is_public', true) // Solo eventi pubblici
            ->where('category', '!=', 'festival') // Escludi altri festival
            ->where('start_datetime', '>', now()) // Solo eventi futuri
            ->orderBy('start_datetime')
            ->limit(10)
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'date' => $event->start_datetime ? $event->start_datetime->format('d/m/Y') : 'N/A',
                    'venue' => $event->venue_name ?: $event->city,
                    'city' => $event->city,
                    'category' => $event->category,
                    'organizer' => $event->organizer ? $event->organizer->name : 'Organizzatore non specificato'
                ];
            });

        return response()->json(['events' => $events]);
    }

    /**
     * Get festivals for dropdown selection
     */
    public function getFestivals(Request $request): JsonResponse
    {
        $festivals = Event::where('category', 'festival')
            ->where('is_public', true)
            ->where('start_datetime', '>', now())
            ->orderBy('start_datetime')
            ->limit(20)
            ->get()
            ->map(function ($festival) {
                return [
                    'id' => $festival->id,
                    'title' => $festival->title,
                    'date' => $festival->start_datetime ? $festival->start_datetime->format('d/m/Y') : 'N/A',
                    'city' => $festival->city
                ];
            });

        return response()->json(['festivals' => $festivals]);
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

        $event->load(['organizer', 'venueOwner', 'invitations.invitedUser', 'requests.user', 'festival.organizer', 'groups']);

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
     * NOTA: Questo metodo non è più utilizzato - la modifica eventi ora usa Livewire (EventEdit component)
     * La route events.edit ora punta direttamente al componente Livewire
     */
    // public function edit(Event $event): View
    // {
    //     // Controlla se l'utente può modificare questo evento usando Spatie
    //     if (!Auth::check() || !Auth::user()->can('events.manage.own') ||
    //         (!Auth::user()->hasRole(['admin', 'moderator']) && $event->organizer_id !== Auth::id())) {
    //         abort(403, 'Non hai i permessi per modificare questo evento');
    //     }

    //     $venueOwners = User::whereHas('roles', function ($query) {
    //         $query->where('name', 'venue_owner');
    //     })->get();

    //     // Ottieni i gruppi pubblici per la selezione
    //     try {
    //         $groups = \App\Models\Group::public()->get();
    //     } catch (\Exception $e) {
    //         // Fallback: ottieni tutti i gruppi se la query public() fallisce
    //         $groups = \App\Models\Group::all();
    //     }

    //     return view('events.edit', compact('event', 'venueOwners', 'groups'));
    // }

    /**
     * Update the specified event
     */
    public function update(Request $request, Event $event): RedirectResponse
    {
        // Controlla se l'utente può modificare questo evento usando Spatie
        if (!Auth::check() || !Auth::user()->can('events.manage.own') ||
            (!Auth::user()->hasRole(['admin', 'moderator']) && $event->organizer_id !== Auth::id())) {
            abort(403, 'Non hai i permessi per modificare questo evento');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
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
            'is_linked_to_group' => 'nullable|boolean',
            'group_id' => 'nullable|exists:groups,id',
            'allow_requests' => 'boolean',
            'tags' => 'nullable|string',
            'image_url' => 'nullable|url',
            'status' => ['required', Rule::in([Event::STATUS_DRAFT, Event::STATUS_PUBLISHED, Event::STATUS_CANCELLED])],
            // Online event fields
            'is_online' => 'nullable|boolean',
            'timezone' => 'required_if:is_online,1|string|max:50',
            'online_url' => 'nullable|url|max:500',
            // Festival fields
            'selected_festival_events' => 'nullable|string', // JSON string of selected events
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

        // Convert is_linked_to_group to boolean
        $validated['is_linked_to_group'] = isset($validated['is_linked_to_group']) && ($validated['is_linked_to_group'] === '1' || $validated['is_linked_to_group'] === 1 || $validated['is_linked_to_group'] === true);

        // Handle group association
        if (!$validated['is_linked_to_group']) {
            $validated['group_id'] = null;
        }

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

                // Process festival data if this is a festival
        if ($event->category === Event::CATEGORY_FESTIVAL && !empty($request->selected_festival_events)) {
            try {
                $festivalEvents = json_decode($request->selected_festival_events, true);
                if (is_array($festivalEvents)) {
                    $eventIds = array_column($festivalEvents, 'id');
                    $validated['festival_events'] = $eventIds;

                                        // Get previous festival events to clean up old relationships
                    $previousEventIds = $event->getFestivalEventIds();

                    // Update festival_id for all events that are part of this festival
                    Event::whereIn('id', $eventIds)->update(['festival_id' => $event->id]);

                    // Clean up festival_id for events that are no longer part of this festival
                    $removedEventIds = array_diff($previousEventIds, $eventIds);
                    if (!empty($removedEventIds)) {
                        Event::whereIn('id', $removedEventIds)->update(['festival_id' => null]);
                    }

                    Log::info('Festival events updated', [
                        'festival_id' => $event->id,
                        'event_count' => count($eventIds),
                        'event_ids' => $eventIds,
                        'removed_count' => count($removedEventIds),
                        'removed_ids' => $removedEventIds
                    ]);
                }
            } catch (\Exception $e) {
                Log::warning('Failed to parse selected festival events JSON: ' . $e->getMessage());
            }
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
        // Controlla se l'utente può eliminare questo evento usando Spatie
        if (!Auth::check() || !Auth::user()->can('events.manage.own') ||
            (!Auth::user()->hasRole(['admin', 'moderator']) && $event->organizer_id !== Auth::id())) {
            abort(403, 'Non hai i permessi per eliminare questo evento');
        }

        // Check if event is part of a festival
        $isPartOfFestival = $event->festival_id !== null;
        $festivalTitle = $isPartOfFestival ? $event->festival->title : null;

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
            'is_part_of_festival' => $isPartOfFestival,
            'festival_title' => $festivalTitle,
            'deleted_by_user_id' => Auth::id(),
            'deleted_by_user_email' => Auth::user()->email,
        ], 'Event', $event->id);

        // Notify all participants about event deletion
        $this->notifyEventDeletion($event);

        // Delete event and all related data using transaction
        DB::transaction(function () use ($event) {
            // Delete all invitations (cascade will handle this, but we log it)
            $invitationsCount = $event->invitations()->count();
            $event->invitations()->delete();

            // Delete all requests (cascade will handle this, but we log it)
            $requestsCount = $event->requests()->count();
            $event->requests()->delete();

            // Delete all wishlist entries
            $wishlistCount = $event->wishlistedBy()->count();
            $event->wishlistedBy()->detach();

            // Delete child events if this is a parent event
            $childEventsCount = $event->childEvents()->count();
            $event->childEvents()->delete();

            // Delete recurrence series if this is part of one
            $recurrenceSeriesCount = $event->recurrenceSeries()->count();
            $event->recurrenceSeries()->delete();

            // Log the deletion of related data
            Log::info('Event deletion - related data removed', [
                'event_id' => $event->id,
                'event_title' => $event->title,
                'invitations_deleted' => $invitationsCount,
                'requests_deleted' => $requestsCount,
                'wishlist_entries_deleted' => $wishlistCount,
                'child_events_deleted' => $childEventsCount,
                'recurrence_series_deleted' => $recurrenceSeriesCount,
            ]);

            // Finally delete the event itself
            $event->delete();
        });

        $successMessage = 'Evento eliminato con successo!';
        if ($isPartOfFestival) {
            $successMessage .= " L'evento è stato rimosso dal festival '{$festivalTitle}'.";
        }

        return redirect()
            ->route('events.index')
            ->with('success', $successMessage);
    }

    /**
     * Show event management interface for organizers
     */
    public function manage(Event $event): View
    {
        // Controlla se l'utente può gestire questo evento usando Spatie
        if (!Auth::check() || !Auth::user()->can('events.manage.own') ||
            (!Auth::user()->hasRole(['admin', 'moderator']) && $event->organizer_id !== Auth::id())) {
            abort(403, 'Non hai i permessi per gestire questo evento');
        }

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
                $query->where(function ($q) {
                    $q->where(function ($subQ) {
                        $subQ->where('start_datetime', '>', Carbon::now())
                             ->orWhere('is_availability_based', true);
                    })
                    // Include completed events
                    ->orWhere('status', 'completed');
                });
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

            // Applica filtri di distanza SOLO se esplicitamente richiesto (filtro 'nearby')
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
                    'start_datetime' => $event->start_datetime ? $event->start_datetime->format('d/m/Y H:i') : 'N/A',
                    'venue_name' => $event->venue_name,
                    'city' => $event->city,
                    'latitude' => (float) $event->latitude,
                    'longitude' => (float) $event->longitude,
                    'organizer' => $event->organizer ? $event->organizer->getDisplayName() : 'N/A',
                    'url' => route('events.show', $event),
                    'category' => $event->category,
                    'category_name' => $event->getCategoryDisplayName(),
                    'category_color_class' => $event->category_color_class,
                    'is_online' => $event->is_online,
                    'online_url' => $event->online_url,
                    'timezone' => $event->timezone,
                    'image_url' => $event->image_url,
                    'max_participants' => $event->max_participants,
                    'entry_fee' => $event->entry_fee,
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
                'start' => $event->start_datetime ? $event->start_datetime->toISOString() : now()->toISOString(),
                'end' => $event->end_datetime ? $event->end_datetime->toISOString() : now()->addHour()->toISOString(),
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
     * Get events for API (without location filter)
     */
    public function events(Request $request): JsonResponse
    {
        Log::info('Events API request params: ', $request->all());

        $query = Event::with(['organizer'])
                     ->published();

        // Apply upcoming filter only if not filtering for past events or invitations
        if (!$request->filled('filter') || ($request->filter !== 'past' && $request->filter !== 'invitations')) {
            $query->where(function ($q) {
                $q->where(function ($subQ) {
                    $subQ->where('start_datetime', '>', Carbon::now())
                         ->orWhere('is_availability_based', true);
                })
                // Include completed events
                ->orWhere('status', 'completed');
            });
        }

        // Filter by search term
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('venue_name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('city', 'like', '%' . $searchTerm . '%')
                  ->orWhereHas('organizer', function ($organizerQuery) use ($searchTerm) {
                      $organizerQuery->where('name', 'like', '%' . $searchTerm . '%')
                                    ->orWhere('nickname', 'like', '%' . $searchTerm . '%');
                  });
            });
        }

        // Filter by city
        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        // Filter by type (public/private)
        if ($request->filled('type')) {
            $query->where('is_public', $request->type === 'public');
        }

        // Filter by organizer
        if ($request->filled('organizer')) {
            $query->where('organizer_id', $request->organizer);
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

        // Quick filters
        if ($request->filled('quick_filter')) {
            switch ($request->quick_filter) {
                case 'today':
                    $query->whereDate('start_datetime', today());
                    break;
                case 'tomorrow':
                    $query->whereDate('start_datetime', today()->addDay());
                    break;
                case 'weekend':
                    $query->where(function ($q) {
                        $q->whereRaw('DAYOFWEEK(start_datetime) IN (1, 7)') // Domenica = 1, Sabato = 7
                          ->whereDate('start_datetime', '>=', today())
                          ->whereDate('start_datetime', '<=', today()->addDays(7));
                    });
                    break;
                case 'free':
                    $query->where(function($q) {
                        $q->where('entry_fee', 0)
                          ->orWhereNull('entry_fee');
                    });
                    break;
            }
        }

        // Handle user-specific filters
        $user = Auth::user();
        if ($user) {
            $userId = $user->id;

            // Filter for "My Events"
            if ($request->filled('filter') && $request->filter === 'my') {
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
            }
            // Filter for "My Private Events"
            elseif ($request->filled('filter') && $request->filter === 'my_private') {
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
            } else {
                // If not filtering for "my events", only show public events or private events where user has access
                $query->where(function ($q) use ($userId) {
                    $q->where('is_public', true)
                      ->orWhere('organizer_id', $userId)
                      ->orWhere(function ($subQ) use ($userId) {
                          $subQ->where('is_public', false)
                               ->whereHas('invitations', function ($inviteQuery) use ($userId) {
                                   $inviteQuery->where('invited_user_id', $userId)
                                               ->where('status', 'accepted');
                               });
                      })
                      ->orWhere(function ($subQ) use ($userId) {
                          $subQ->where('is_public', false)
                               ->whereHas('requests', function ($requestQuery) use ($userId) {
                                   $requestQuery->where('user_id', $userId)
                                                ->where('status', 'accepted');
                               });
                      });
                });
            }
        } else {
            // If user is not authenticated, only show public events
            $query->where('is_public', true);
        }

        $events = $query->get();

        Log::info('Final events count: ' . $events->count());

        $eventsData = $events->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'start_datetime' => $event->start_datetime ? $event->start_datetime->format('d/m/Y H:i') : 'N/A',
                'venue_name' => $event->venue_name,
                'city' => $event->city,
                'organizer' => $event->organizer ? $event->organizer->name : 'N/A',
                'is_online' => $event->is_online,
                'timezone' => $event->timezone,
                'online_url' => $event->online_url,
                'latitude' => $event->latitude,
                'longitude' => $event->longitude,
                'category' => $event->category,
                'category_name' => $event->getCategoryDisplayName(),
                'category_color_class' => $event->category_color_class,
                'url' => route('events.show', $event),
                'image_url' => $event->image_url,
                'max_participants' => $event->max_participants,
                'entry_fee' => $event->entry_fee,
            ];
        });

        return response()->json($eventsData);
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

    /**
     * Notify participants about event deletion
     */
    private function notifyEventDeletion(Event $event): void
    {
        try {
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

            // Get all users who have this event in their wishlist (with error handling)
            $wishlistUserIds = collect();
            try {
                $wishlistUserIds = $event->wishlistedBy()->pluck('users.id');
            } catch (\Exception $e) {
                Log::warning('Failed to get wishlist users for event deletion', [
                    'event_id' => $event->id,
                    'error' => $e->getMessage()
                ]);
            }

            // Merge all affected users
            $allAffectedUsers = $participantIds->merge($wishlistUserIds)->unique();

            // Create notifications for all affected users
            foreach ($allAffectedUsers as $userId) {
                try {
                    $user = User::find($userId);
                    if (!$user) continue;

                    // Determine notification type based on user's relationship with the event
                    $hasInvitation = $event->invitations()->where('invited_user_id', $userId)->exists();
                    $hasRequest = $event->requests()->where('user_id', $userId)->exists();
                    $hasWishlist = false;

                    try {
                        $hasWishlist = $event->wishlistedBy()->where('users.id', $userId)->exists();
                    } catch (\Exception $e) {
                        Log::warning('Failed to check wishlist for user', [
                            'user_id' => $userId,
                            'event_id' => $event->id,
                            'error' => $e->getMessage()
                        ]);
                    }

                    $notificationType = 'event_deleted';
                    $title = 'Evento Eliminato';
                    $message = 'L\'evento "' . $event->title . '" è stato eliminato';

                    if ($hasInvitation) {
                        $invitation = $event->invitations()->where('invited_user_id', $userId)->first();
                        if ($invitation && $invitation->status === 'accepted') {
                            $notificationType = 'event_deleted_participant';
                            $title = 'Evento Eliminato - Sei Stato Disiscritto';
                            $message = 'L\'evento "' . $event->title . '" a cui eri iscritto è stato eliminato';
                        } else {
                            $notificationType = 'event_deleted_invitation';
                            $title = 'Evento Eliminato - Invito Annullato';
                            $message = 'L\'evento "' . $event->title . '" per cui avevi ricevuto un invito è stato eliminato';
                        }
                    } elseif ($hasRequest) {
                        $request = $event->requests()->where('user_id', $userId)->first();
                        if ($request && $request->status === 'accepted') {
                            $notificationType = 'event_deleted_participant';
                            $title = 'Evento Eliminato - Sei Stato Disiscritto';
                            $message = 'L\'evento "' . $event->title . '" a cui eri iscritto è stato eliminato';
                        } else {
                            $notificationType = 'event_deleted_request';
                            $title = 'Evento Eliminato - Richiesta Annullata';
                            $message = 'L\'evento "' . $event->title . '" per cui avevi fatto richiesta è stato eliminato';
                        }
                    } elseif ($hasWishlist) {
                        $notificationType = 'event_deleted_wishlist';
                        $title = 'Evento Eliminato - Rimosso dai Preferiti';
                        $message = 'L\'evento "' . $event->title . '" che avevi nei tuoi preferiti è stato eliminato';
                    }

                    Notification::create([
                        'user_id' => $userId,
                        'type' => $notificationType,
                        'title' => $title,
                        'message' => $message,
                        'data' => [
                            'event_id' => $event->id,
                            'event_title' => $event->title,
                            'deleted_by_user_id' => Auth::id(),
                            'deleted_by_user_email' => Auth::user()->email,
                        ],
                        'priority' => Notification::PRIORITY_HIGH,
                    ]);

                    // Log the notification
                    Log::info('Event deletion notification sent', [
                        'user_id' => $userId,
                        'user_email' => $user->email,
                        'event_id' => $event->id,
                        'event_title' => $event->title,
                        'notification_type' => $notificationType,
                        'user_relationship' => [
                            'has_invitation' => $hasInvitation,
                            'has_request' => $hasRequest,
                            'has_wishlist' => $hasWishlist,
                        ],
                    ]);
                } catch (\Exception $e) {
                    Log::error('Failed to send notification to user', [
                        'user_id' => $userId,
                        'event_id' => $event->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }

            // Log summary
            Log::info('Event deletion notifications summary', [
                'event_id' => $event->id,
                'event_title' => $event->title,
                'total_notifications_sent' => $allAffectedUsers->count(),
                'participants_notified' => $participantIds->count(),
                'wishlist_users_notified' => $wishlistUserIds->count(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send event deletion notifications', [
                'event_id' => $event->id,
                'event_title' => $event->title,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Mark event as completed
     */
    public function complete(Event $event)
    {
        // Check authorization
        if (!Auth::check() || ($event->organizer_id !== Auth::id() && !Auth::user()->hasRole(['admin', 'moderator']))) {
            abort(403, 'Non hai i permessi per completare questo evento');
        }

        $event->status = Event::STATUS_COMPLETED;
        $event->save();

        return redirect()->route('events.show', $event)
            ->with('success', 'Evento segnato come completato!');
    }
}
