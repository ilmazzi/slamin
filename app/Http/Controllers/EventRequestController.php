<?php

namespace App\Http\Controllers;

use App\Models\EventRequest;
use App\Models\Event;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class EventRequestController extends Controller
{
    /**
     * Display requests for the authenticated user
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        $query = EventRequest::with(['event.organizer'])
                            ->where('user_id', $user->id);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by event type
        if ($request->filled('type')) {
            $query->whereHas('event', function ($q) use ($request) {
                $q->where('is_public', $request->type === 'public');
            });
        }

        $requests = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('requests.index', compact('requests'));
    }

    /**
     * Show the specified request
     */
    public function show(EventRequest $eventRequest): View
    {
        // Check if user can view this request
        if ($eventRequest->user_id !== Auth::id() &&
            $eventRequest->event->organizer_id !== Auth::id()) {
            abort(403, 'Non autorizzato a visualizzare questa richiesta.');
        }

        $eventRequest->load(['event.organizer', 'user', 'reviewer']);

        return view('requests.show', compact('eventRequest'));
    }

    /**
     * Accept a request (organizer only)
     */
    public function accept(Request $request, EventRequest $eventRequest): RedirectResponse
    {
        Gate::authorize('update', $eventRequest->event);

        $validated = $request->validate([
            'organizer_response' => 'nullable|string|max:500',
        ]);

        if ($eventRequest->accept(Auth::user(), $validated['organizer_response'] ?? null)) {
            return redirect()
                ->route('events.manage', $eventRequest->event)
                ->with('success', 'Richiesta accettata con successo!');
        }

        return back()->with('error', 'Impossibile accettare questa richiesta.');
    }

    /**
     * Accept a request via AJAX (organizer only)
     */
    public function acceptAjax(Request $request, EventRequest $eventRequest): JsonResponse
    {
        Gate::authorize('update', $eventRequest->event);

        try {
            if ($eventRequest->accept(Auth::user(), null)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Richiesta accettata con successo!'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Impossibile accettare questa richiesta.'
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore durante l\'accettazione della richiesta.'
            ], 500);
        }
    }

    /**
     * Decline a request (organizer only)
     */
    public function decline(Request $request, EventRequest $eventRequest): RedirectResponse
    {
        Gate::authorize('update', $eventRequest->event);

        $validated = $request->validate([
            'organizer_response' => 'required|string|max:500',
        ]);

        if ($eventRequest->decline(Auth::user(), $validated['organizer_response'])) {
            return redirect()
                ->route('events.manage', $eventRequest->event)
                ->with('success', 'Richiesta rifiutata.');
        }

        return back()->with('error', 'Impossibile rifiutare questa richiesta.');
    }

    /**
     * Decline a request via AJAX (organizer only)
     */
    public function declineAjax(Request $request, EventRequest $eventRequest): JsonResponse
    {
        Gate::authorize('update', $eventRequest->event);

        try {
            if ($eventRequest->decline(Auth::user(), 'Richiesta non accettata')) {
                return response()->json([
                    'success' => true,
                    'message' => 'Richiesta rifiutata con successo!'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Impossibile rifiutare questa richiesta.'
            ], 400);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Errore durante il rifiuto della richiesta.'
            ], 500);
        }
    }

    /**
     * Cancel a request (user only)
     */
    public function cancel(EventRequest $eventRequest): RedirectResponse
    {
        // Check if user can cancel this request
        if ($eventRequest->user_id !== Auth::id()) {
            abort(403, 'Non autorizzato a cancellare questa richiesta.');
        }

        if ($eventRequest->cancel()) {
            return redirect()
                ->route('requests.index')
                ->with('success', 'Richiesta cancellata con successo.');
        }

        return back()->with('error', 'Impossibile cancellare questa richiesta.');
    }

    /**
     * Get request statistics for event
     */
    public function statistics(Event $event): JsonResponse
    {
        Gate::authorize('update', $event);

        $stats = [
            'total' => $event->requests()->count(),
            'pending' => $event->requests()->where('status', EventRequest::STATUS_PENDING)->count(),
            'accepted' => $event->requests()->where('status', EventRequest::STATUS_ACCEPTED)->count(),
            'declined' => $event->requests()->where('status', EventRequest::STATUS_DECLINED)->count(),
            'cancelled' => $event->requests()->where('status', EventRequest::STATUS_CANCELLED)->count(),
        ];

        $stats['approval_rate'] = ($stats['accepted'] + $stats['declined']) > 0
            ? round(($stats['accepted'] / ($stats['accepted'] + $stats['declined'])) * 100, 1)
            : 0;

        // Get most requested roles
        $roleStats = $event->requests()
                          ->selectRaw('requested_role, COUNT(*) as count')
                          ->groupBy('requested_role')
                          ->orderBy('count', 'desc')
                          ->get()
                          ->mapWithKeys(function ($item) {
                              return [$item->requested_role => $item->count];
                          });

        $stats['roles'] = $roleStats;

        return response()->json($stats);
    }

    /**
     * Bulk actions on requests
     */
    public function bulkAction(Request $request, Event $event): JsonResponse
    {
        Gate::authorize('update', $event);

        $validated = $request->validate([
            'action' => 'required|in:accept,decline',
            'request_ids' => 'required|array|min:1',
            'request_ids.*' => 'exists:event_requests,id',
            'response_message' => 'nullable|string|max:500',
        ]);

        $requests = EventRequest::whereIn('id', $validated['request_ids'])
                               ->where('event_id', $event->id)
                               ->where('status', EventRequest::STATUS_PENDING)
                               ->get();

        $successCount = 0;
        $user = Auth::user();

        foreach ($requests as $eventRequest) {
            try {
                if ($validated['action'] === 'accept') {
                    if ($eventRequest->accept($user, $validated['response_message'])) {
                        $successCount++;
                    }
                } else {
                    if ($eventRequest->decline($user, $validated['response_message'] ?? 'Richiesta non accettata')) {
                        $successCount++;
                    }
                }
            } catch (\Exception $e) {
                // Log error but continue with other requests
                continue;
            }
        }

        $action = $validated['action'] === 'accept' ? 'accettate' : 'rifiutate';

        return response()->json([
            'success' => true,
            'message' => "$successCount richieste $action con successo.",
            'processed' => $successCount,
        ]);
    }

    /**
     * Get pending requests for organizer dashboard
     */
    public function pending(): JsonResponse
    {
        $user = Auth::user();

        // Get events organized by user with pending requests
        $events = Event::where('organizer_id', $user->id)
                      ->whereHas('requests', function ($query) {
                          $query->where('status', EventRequest::STATUS_PENDING);
                      })
                      ->with(['requests' => function ($query) {
                          $query->where('status', EventRequest::STATUS_PENDING)
                                ->with(['user']);
                      }])
                      ->get();

        $pendingRequests = [];

        foreach ($events as $event) {
            foreach ($event->requests as $request) {
                $pendingRequests[] = [
                    'id' => $request->id,
                    'event_title' => $event->title,
                    'event_id' => $event->id,
                    'user_name' => $request->user->name,
                    'user_id' => $request->user->id,
                    'requested_role' => $request->requested_role,
                    'message' => $request->message,
                    'experience' => $request->experience,
                    'created_at' => $request->created_at->diffForHumans(),
                    'url' => route('requests.show', $request),
                ];
            }
        }

        // Sort by creation date
        usort($pendingRequests, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        return response()->json([
            'requests' => array_slice($pendingRequests, 0, 10), // Last 10
            'total_count' => count($pendingRequests),
        ]);
    }

    /**
     * Quick response to a request
     */
    public function quickResponse(Request $request, EventRequest $eventRequest): JsonResponse
    {
        Gate::authorize('update', $eventRequest->event);

        $validated = $request->validate([
            'action' => 'required|in:accept,decline',
            'message' => 'nullable|string|max:500',
        ]);

        $success = false;
        $user = Auth::user();

        if ($validated['action'] === 'accept') {
            $success = $eventRequest->accept($user, $validated['message']);
            $message = $success ? 'Richiesta accettata!' : 'Errore nell\'accettazione.';
        } else {
            $success = $eventRequest->decline($user, $validated['message'] ?? 'Richiesta non accettata');
            $message = $success ? 'Richiesta rifiutata.' : 'Errore nel rifiuto.';
        }

        return response()->json([
            'success' => $success,
            'message' => $message,
        ]);
    }

    /**
     * Get request form data for modal
     */
    public function formData(Event $event): JsonResponse
    {
        $user = Auth::user();

        // Check if user can apply
        if (!EventRequest::canUserApplyToEvent($user, $event)) {
            return response()->json([
                'can_apply' => false,
                'message' => 'Non puoi richiedere di partecipare a questo evento.',
            ]);
        }

        // Get available roles based on user's permissions
        $availableRoles = [];

        if ($user->hasRole('poet')) {
            $availableRoles['performer'] = 'Performer';
        }

        if ($user->hasRole('judge')) {
            $availableRoles['judge'] = 'Judge';
        }

        if ($user->hasRole('technician')) {
            $availableRoles['technician'] = 'Technician';
        }

        // Anyone can apply as host if event allows
        $availableRoles['host'] = 'Host';

        return response()->json([
            'can_apply' => true,
            'event' => [
                'id' => $event->id,
                'title' => $event->title,
                'description' => $event->description,
                'requirements' => $event->requirements,
                'start_datetime' => $event->start_datetime->format('d/m/Y H:i'),
                'venue_name' => $event->venue_name,
                'city' => $event->city,
            ],
            'available_roles' => $availableRoles,
        ]);
    }
}
