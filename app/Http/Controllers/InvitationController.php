<?php

namespace App\Http\Controllers;

use App\Models\EventInvitation;
use App\Models\Notification;
use App\Mail\EventInvitationMail;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class InvitationController extends Controller
{
    /**
     * Accept an event invitation
     */
    public function accept(EventInvitation $invitation): RedirectResponse
    {
        // Check if user is authorized to accept this invitation
        if ($invitation->invited_user_id !== Auth::id()) {
            abort(403, 'Non autorizzato ad accettare questo invito.');
        }

        // Check if invitation is still pending
        if ($invitation->status !== EventInvitation::STATUS_PENDING) {
            return redirect()->route('events.show', $invitation->event)
                ->with('error', 'Questo invito non è più valido.');
        }

        // Check if invitation has expired
        if ($invitation->expires_at && $invitation->expires_at->isPast()) {
            return redirect()->route('events.show', $invitation->event)
                ->with('error', 'Questo invito è scaduto.');
        }

        // Accept the invitation
        $invitation->update([
            'status' => EventInvitation::STATUS_ACCEPTED,
            'responded_at' => now()
        ]);

        // Create notification for the organizer
        Notification::createInvitationResponse($invitation, 'accepted');

        // Send email to organizer (optional)
        try {
            // You can create a new mail class for this if needed
            Log::info('Invitation accepted', [
                'invitation_id' => $invitation->id,
                'event_id' => $invitation->event_id,
                'user_id' => $invitation->invited_user_id
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send acceptance notification', [
                'invitation_id' => $invitation->id,
                'error' => $e->getMessage()
            ]);
        }

        return redirect()->route('invitations.index')
            ->with('success', 'Hai accettato l\'invito per partecipare all\'evento!');
    }

    /**
     * Decline an event invitation
     */
    public function decline(EventInvitation $invitation): RedirectResponse
    {
        // Check if user is authorized to decline this invitation
        if ($invitation->invited_user_id !== Auth::id()) {
            abort(403, 'Non autorizzato a rifiutare questo invito.');
        }

        // Check if invitation is still pending
        if ($invitation->status !== EventInvitation::STATUS_PENDING) {
            return redirect()->route('events.show', $invitation->event)
                ->with('error', 'Questo invito non è più valido.');
        }

        // Check if invitation has expired
        if ($invitation->expires_at && $invitation->expires_at->isPast()) {
            return redirect()->route('events.show', $invitation->event)
                ->with('error', 'Questo invito è scaduto.');
        }

        // Decline the invitation
        $invitation->update([
            'status' => EventInvitation::STATUS_DECLINED,
            'responded_at' => now()
        ]);

        // Create notification for the organizer
        Notification::createInvitationResponse($invitation, 'declined');

        // Send email to organizer (optional)
        try {
            Log::info('Invitation declined', [
                'invitation_id' => $invitation->id,
                'event_id' => $invitation->event_id,
                'user_id' => $invitation->invited_user_id
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send decline notification', [
                'invitation_id' => $invitation->id,
                'error' => $e->getMessage()
            ]);
        }

        return redirect()->route('invitations.index')
            ->with('info', 'Hai rifiutato l\'invito per partecipare all\'evento.');
    }

    /**
     * Show all invitations for the current user
     */
    public function index(): \Illuminate\View\View
    {
        $invitations = EventInvitation::with(['event', 'inviter'])
            ->where('invited_user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('invitations.fixed', compact('invitations'));
    }
}
