<?php

namespace App\Http\Controllers;

use App\Models\EventInvitation;
use App\Models\Event;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class EventInvitationController extends Controller
{
    /**
     * Display invitations for the authenticated user
     */
    public function index(Request $request): View
    {
        $user = Auth::user();

        $query = EventInvitation::with(['event', 'inviter'])
                               ->where('invited_user_id', $user->id);

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

        $invitations = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('invitations.index', compact('invitations'));
    }

    /**
     * Show the specified invitation
     */
    public function show(EventInvitation $invitation): View
    {
        // Check if user can view this invitation
        if ($invitation->invited_user_id !== Auth::id()) {
            abort(403, 'Non autorizzato a visualizzare questo invito.');
        }

        $invitation->load(['event.organizer', 'inviter']);

        return view('invitations.show', compact('invitation'));
    }

    /**
     * Create a new invitation
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'invited_user_ids' => 'required|array|min:1',
            'invited_user_ids.*' => 'exists:users,id',
            'message' => 'nullable|string|max:1000',
            'role' => 'required|string|in:performer,judge,technician,host',
            'compensation' => 'nullable|numeric|min:0',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $event = Event::findOrFail($validated['event_id']);

        // Check if user can invite to this event
        Gate::authorize('update', $event);

        $invitations = [];
        $errors = [];
        $alreadyInvited = [];

        foreach ($validated['invited_user_ids'] as $userId) {
            // Check if user already has invitation for this event
            $existingInvitation = EventInvitation::where('event_id', $event->id)
                                                ->where('invited_user_id', $userId)
                                                ->first();

            if ($existingInvitation) {
                $user = User::find($userId);
                $alreadyInvited[] = $user->name;
                continue;
            }

            // Create invitation
            $invitation = EventInvitation::create([
                'event_id' => $event->id,
                'invited_user_id' => $userId,
                'inviter_id' => Auth::id(),
                'message' => $validated['message'],
                'role' => $validated['role'],
                'compensation' => $validated['compensation'] ?? null,
                'expires_at' => $validated['expires_at'] ?? null,
            ]);

            // Create notification
            Notification::createEventInvitation($invitation);

            $invitations[] = $invitation;
        }

        $response = [
            'success' => true,
            'message' => count($invitations) . ' inviti inviati con successo!',
            'invitations_sent' => count($invitations),
        ];

        if (!empty($alreadyInvited)) {
            $response['warnings'] = 'Alcuni utenti erano già stati invitati: ' . implode(', ', $alreadyInvited);
        }

        return response()->json($response);
    }

    /**
     * Accept an invitation
     */
    public function accept(Request $request, EventInvitation $invitation): RedirectResponse
    {
        // Check if user can accept this invitation
        if ($invitation->invited_user_id !== Auth::id()) {
            abort(403, 'Non autorizzato a gestire questo invito.');
        }

        $validated = $request->validate([
            'response_message' => 'nullable|string|max:500',
        ]);

        if ($invitation->accept($validated['response_message'] ?? null)) {
            return redirect()
                ->route('invitations.show', $invitation)
                ->with('success', 'Invito accettato con successo!');
        }

        return back()->with('error', 'Impossibile accettare questo invito.');
    }

    /**
     * Decline an invitation
     */
    public function decline(Request $request, EventInvitation $invitation): RedirectResponse
    {
        // Check if user can decline this invitation
        if ($invitation->invited_user_id !== Auth::id()) {
            abort(403, 'Non autorizzato a gestire questo invito.');
        }

        $validated = $request->validate([
            'response_message' => 'nullable|string|max:500',
        ]);

        if ($invitation->decline($validated['response_message'] ?? null)) {
            return redirect()
                ->route('invitations.show', $invitation)
                ->with('success', 'Invito rifiutato.');
        }

        return back()->with('error', 'Impossibile rifiutare questo invito.');
    }

    /**
     * Cancel an invitation (organizer only)
     */
    public function cancel(EventInvitation $invitation): RedirectResponse
    {
        // Check if user can cancel this invitation
        Gate::authorize('update', $invitation->event);

        if ($invitation->status !== EventInvitation::STATUS_PENDING) {
            return back()->with('error', 'Impossibile cancellare un invito già gestito.');
        }

        // Create notification for invited user
        Notification::create([
            'user_id' => $invitation->invited_user_id,
            'type' => 'invitation_cancelled',
            'title' => 'Invito Cancellato',
            'message' => 'L\'invito per "' . $invitation->event->title . '" è stato cancellato dall\'organizzatore',
            'data' => [
                'event_id' => $invitation->event_id,
                'invitation_id' => $invitation->id,
            ],
            'priority' => Notification::PRIORITY_NORMAL,
        ]);

        $invitation->delete();

        return back()->with('success', 'Invito cancellato con successo.');
    }

    /**
     * Get invitation statistics for event
     */
    public function statistics(Event $event): JsonResponse
    {
        Gate::authorize('update', $event);

        $stats = [
            'total' => $event->invitations()->count(),
            'pending' => $event->invitations()->where('status', EventInvitation::STATUS_PENDING)->count(),
            'accepted' => $event->invitations()->where('status', EventInvitation::STATUS_ACCEPTED)->count(),
            'declined' => $event->invitations()->where('status', EventInvitation::STATUS_DECLINED)->count(),
            'expired' => $event->invitations()->where('status', EventInvitation::STATUS_EXPIRED)->count(),
        ];

        $stats['response_rate'] = $stats['total'] > 0
            ? round((($stats['accepted'] + $stats['declined']) / $stats['total']) * 100, 1)
            : 0;

        $stats['acceptance_rate'] = ($stats['accepted'] + $stats['declined']) > 0
            ? round(($stats['accepted'] / ($stats['accepted'] + $stats['declined'])) * 100, 1)
            : 0;

        return response()->json($stats);
    }

    /**
     * Mark expired invitations
     */
    public function markExpired(): JsonResponse
    {
        $expiredCount = EventInvitation::where('status', EventInvitation::STATUS_PENDING)
                                     ->where('expires_at', '<=', Carbon::now())
                                     ->update(['status' => EventInvitation::STATUS_EXPIRED]);

        return response()->json([
            'success' => true,
            'message' => "$expiredCount inviti marcati come scaduti.",
            'expired_count' => $expiredCount,
        ]);
    }

    /**
     * Resend invitation
     */
    public function resend(EventInvitation $invitation): JsonResponse
    {
        Gate::authorize('update', $invitation->event);

        if ($invitation->status !== EventInvitation::STATUS_PENDING) {
            return response()->json([
                'success' => false,
                'message' => 'Impossibile reinviare un invito già gestito.',
            ], 400);
        }

        // Update expiration if needed
        if ($invitation->expires_at && $invitation->expires_at <= Carbon::now()) {
            $invitation->update([
                'expires_at' => Carbon::now()->addDays(7),
            ]);
        }

        // Create new notification
        Notification::createEventInvitation($invitation);

        return response()->json([
            'success' => true,
            'message' => 'Invito reinviato con successo!',
        ]);
    }

    /**
     * Bulk actions on invitations
     */
    public function bulkAction(Request $request, Event $event): JsonResponse
    {
        Gate::authorize('update', $event);

        $validated = $request->validate([
            'action' => 'required|in:cancel,resend,extend',
            'invitation_ids' => 'required|array|min:1',
            'invitation_ids.*' => 'exists:event_invitations,id',
            'extend_days' => 'required_if:action,extend|integer|min:1|max:30',
        ]);

        $invitations = EventInvitation::whereIn('id', $validated['invitation_ids'])
                                    ->where('event_id', $event->id)
                                    ->get();

        $successCount = 0;

        foreach ($invitations as $invitation) {
            try {
                switch ($validated['action']) {
                    case 'cancel':
                        if ($invitation->status === EventInvitation::STATUS_PENDING) {
                            $invitation->delete();
                            $successCount++;
                        }
                        break;

                    case 'resend':
                        if ($invitation->status === EventInvitation::STATUS_PENDING) {
                            Notification::createEventInvitation($invitation);
                            $successCount++;
                        }
                        break;

                    case 'extend':
                        if ($invitation->status === EventInvitation::STATUS_PENDING) {
                            $invitation->update([
                                'expires_at' => Carbon::now()->addDays($validated['extend_days']),
                            ]);
                            $successCount++;
                        }
                        break;
                }
            } catch (\Exception $e) {
                // Log error but continue with other invitations
                continue;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Azione eseguita su $successCount inviti.",
            'processed' => $successCount,
        ]);
    }
}
