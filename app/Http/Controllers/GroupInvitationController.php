<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupInvitation;
use App\Models\GroupMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupInvitationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostra gli inviti ricevuti dall'utente
     */
    public function index()
    {
        $user = Auth::user();

        $invitations = $user->groupInvitations()
                           ->with(['group', 'invitedBy'])
                           ->orderBy('created_at', 'desc')
                           ->paginate(10);

        return view('groups.invitations.index', compact('invitations'));
    }

    /**
     * Mostra gli inviti inviati dall'utente
     */
    public function sent()
    {
        $user = Auth::user();

        $invitations = $user->sentGroupInvitations()
                           ->with(['group', 'user'])
                           ->orderBy('created_at', 'desc')
                           ->paginate(10);

        return view('groups.invitations.sent', compact('invitations'));
    }

    /**
     * Mostra il form per creare un nuovo invito
     */
    public function create(Group $group)
    {
        $user = Auth::user();

        if (!$user->isModeratorOf($group) && !$user->hasRole('admin')) {
            abort(403, 'Non hai i permessi per invitare utenti in questo gruppo.');
        }

        return view('groups.invitations.create', compact('group'));
    }

    /**
     * Crea un nuovo invito
     */
    public function store(Request $request, Group $group)
    {
        $user = Auth::user();

        if (!$user->isModeratorOf($group) && !$user->hasRole('admin')) {
            abort(403, 'Non hai i permessi per invitare utenti in questo gruppo.');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'nullable|string|max:500',
        ]);

        $invitedUser = \App\Models\User::findOrFail($request->user_id);

        // Verifica che l'utente non sia già membro del gruppo
        if ($invitedUser->isMemberOf($group)) {
            return back()->with('error', 'L\'utente è già membro di questo gruppo.');
        }

        // Verifica che non ci sia già un invito pendente
        $existingInvitation = GroupInvitation::where('group_id', $group->id)
                                            ->where('user_id', $invitedUser->id)
                                            ->where('status', 'pending')
                                            ->first();

        if ($existingInvitation) {
            return back()->with('error', 'L\'utente ha già un invito pendente per questo gruppo.');
        }

        // Crea l'invito
        $invitation = GroupInvitation::create([
            'group_id' => $group->id,
            'user_id' => $invitedUser->id,
            'invited_by' => $user->id,
            'status' => 'pending',
            'message' => $request->message,
            'expires_at' => now()->addDays(7), // Invito valido per 7 giorni
        ]);

        // Crea la notifica
        \App\Models\Notification::createGroupInvitation($invitation);

        return back()->with('success', 'Invito inviato con successo.');
    }

    /**
     * Mostra gli inviti pendenti di un gruppo (per admin/moderatori)
     */
    public function pending(Group $group)
    {
        $user = Auth::user();

        if (!$user->isModeratorOf($group) && !$user->hasRole('admin')) {
            abort(403, 'Non hai i permessi per visualizzare gli inviti del gruppo.');
        }

        $invitations = $group->getPendingInvitations()
                           ->with(['user', 'invitedBy'])
                           ->paginate(10);

        return view('groups.invitations.pending', compact('group', 'invitations'));
    }

    /**
     * Accetta un invito
     */
    public function accept(GroupInvitation $invitation)
    {
        $user = Auth::user();

        // Verifica che l'invito sia per l'utente corrente
        if ($invitation->user_id !== $user->id) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Non puoi accettare inviti di altri utenti.'], 403);
            }
            abort(403, 'Non puoi accettare inviti di altri utenti.');
        }

        // Verifica che l'invito sia pendente
        if (!$invitation->isPending()) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Questo invito non è più valido.'], 400);
            }
            return back()->with('error', 'Questo invito non è più valido.');
        }

        // Verifica che l'invito non sia scaduto
        if ($invitation->isExpired()) {
            $invitation->markAsExpired();
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Questo invito è scaduto.'], 400);
            }
            return back()->with('error', 'Questo invito è scaduto.');
        }

        // Verifica che l'utente non sia già membro del gruppo
        if ($user->isMemberOf($invitation->group)) {
            $invitation->update(['status' => 'accepted']);
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Sei già membro di questo gruppo.'], 400);
            }
            return back()->with('error', 'Sei già membro di questo gruppo.');
        }

        // Accetta l'invito
        $invitation->accept();

        // Aggiungi l'utente come membro del gruppo
        GroupMember::create([
            'group_id' => $invitation->group_id,
            'user_id' => $user->id,
            'role' => 'member',
            'invited_by' => $invitation->invited_by,
            'joined_at' => now(),
        ]);

        // Crea la notifica di risposta
        \App\Models\Notification::createGroupInvitationResponse($invitation, 'accepted');

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Hai accettato l\'invito al gruppo!',
                'redirect_url' => route('groups.show', $invitation->group)
            ]);
        }

        return redirect()->route('groups.show', $invitation->group)
                        ->with('success', 'Hai accettato l\'invito al gruppo!');
    }

    /**
     * Rifiuta un invito
     */
    public function decline(GroupInvitation $invitation)
    {
        $user = Auth::user();

        // Verifica che l'invito sia per l'utente corrente
        if ($invitation->user_id !== $user->id) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Non puoi rifiutare inviti di altri utenti.'], 403);
            }
            abort(403, 'Non puoi rifiutare inviti di altri utenti.');
        }

        // Verifica che l'invito sia pendente
        if (!$invitation->isPending()) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Questo invito non è più valido.'], 400);
            }
            return back()->with('error', 'Questo invito non è più valido.');
        }

        // Rifiuta l'invito
        $invitation->decline();

        // Crea la notifica di risposta
        \App\Models\Notification::createGroupInvitationResponse($invitation, 'declined');

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Hai rifiutato l\'invito al gruppo.'
            ]);
        }

        return back()->with('success', 'Hai rifiutato l\'invito al gruppo.');
    }

    /**
     * Cancella un invito (per chi l'ha inviato)
     */
    public function cancel(GroupInvitation $invitation)
    {
        $user = Auth::user();

        // Verifica che l'utente sia chi ha inviato l'invito o admin del gruppo
        if ($invitation->invited_by !== $user->id && !$user->isAdminOf($invitation->group) && !$user->hasRole('admin')) {
            abort(403, 'Non puoi cancellare inviti di altri utenti.');
        }

        // Verifica che l'invito sia pendente
        if (!$invitation->isPending()) {
            return back()->with('error', 'Questo invito non è più valido.');
        }

        $invitation->delete();

        return back()->with('success', 'Invito cancellato con successo.');
    }

    /**
     * Rinvii un invito scaduto
     */
    public function resend(GroupInvitation $invitation)
    {
        $user = Auth::user();

        // Verifica che l'utente sia chi ha inviato l'invito o admin del gruppo
        if ($invitation->invited_by !== $user->id && !$user->isAdminOf($invitation->group) && !$user->hasRole('admin')) {
            abort(403, 'Non puoi rinviare inviti di altri utenti.');
        }

        // Verifica che l'invito sia scaduto
        if (!$invitation->isExpired()) {
            return back()->with('error', 'Questo invito non è scaduto.');
        }

        // Verifica che l'utente non sia già membro del gruppo
        if ($invitation->user->isMemberOf($invitation->group)) {
            $invitation->delete();
            return back()->with('error', 'L\'utente è già membro del gruppo.');
        }

        // Rinvii l'invito
        $invitation->update([
            'status' => 'pending',
            'expires_at' => now()->addDays(7),
        ]);

        return back()->with('success', 'Invito rinviato con successo.');
    }

    /**
     * Mostra i dettagli di un invito
     */
    public function show(GroupInvitation $invitation)
    {
        $user = Auth::user();

        // Verifica che l'utente sia il destinatario dell'invito o chi l'ha inviato
        if ($invitation->user_id !== $user->id && $invitation->invited_by !== $user->id && !$user->hasRole('admin')) {
            abort(403, 'Non hai i permessi per visualizzare questo invito.');
        }

        $invitation->load(['group', 'user', 'invitedBy']);

        return view('groups.invitations.show', compact('invitation'));
    }
}
