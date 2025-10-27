<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Remove the specified group
     */
    public function destroy(Group $group)
    {
        $user = Auth::user();

        if (!$user->isAdminOf($group) && !$user->hasRole('admin')) {
            abort(403, 'Non hai i permessi per eliminare questo gruppo.');
        }

        // Elimina l'immagine se esiste
        if ($group->image) {
            Storage::disk('public')->delete($group->image);
        }

        $group->delete();

        return redirect()->route('groups.index')
                        ->with('success', 'Gruppo eliminato con successo!');
    }

    /**
     * Dashboard del gruppo per admin/moderatori
     */
    public function dashboard(Group $group)
    {
        $user = Auth::user();

        if (!$user->isModeratorOf($group) && !$user->hasRole('admin')) {
            abort(403, 'Non hai i permessi per accedere alla dashboard del gruppo.');
        }

        $group->load(['members.user', 'invitations.user', 'joinRequests.user', 'events']);

        $stats = [
            'total_members' => $group->getMembersCount(),
            'total_events' => $group->events()->count(),
            'pending_invitations' => $group->getPendingInvitations()->count(),
            'pending_requests' => $group->getPendingJoinRequests()->count(),
            'recent_events' => $group->events()->latest()->limit(5)->get(),
        ];

        return view('groups.dashboard', compact('group', 'stats'));
    }

    /**
     * Richiedi di entrare in un gruppo
     */
        public function join(Group $group, Request $request)
    {
        $user = Auth::user();

        // Verifica se l'utente è già membro
        if ($user->isMemberOf($group)) {
            return back()->with('error', 'Sei già membro di questo gruppo.');
        }

        // Verifica se l'utente ha già un invito pendente
        if ($group->hasPendingInvitation($user)) {
            return back()->with('error', 'Hai già un invito pendente per questo gruppo.');
        }

        // Verifica i permessi per entrare nel gruppo
        if ($group->visibility === 'private' && !$user->can('groups.join.private')) {
            abort(403, 'Non hai i permessi per richiedere di entrare in gruppi privati.');
        }

        if ($group->visibility === 'public' && !$user->can('groups.join.public')) {
            abort(403, 'Non hai i permessi per richiedere di entrare in gruppi pubblici.');
        }

        // Cerca una richiesta esistente per questo utente e gruppo
        $existingRequest = $group->joinRequests()->where('user_id', $user->id)->first();

        if ($existingRequest) {
            // Se esiste già una richiesta pendente, mostra errore
            if ($existingRequest->status === 'pending') {
                return back()->with('error', 'Hai già una richiesta pendente per questo gruppo.');
            }

            // Se la richiesta precedente è stata accettata o rifiutata, aggiornala
            $existingRequest->update([
                'message' => $request->input('message', ''),
                'status' => 'pending',
                'processed_by' => null,
                'processed_at' => null,
            ]);

            $joinRequest = $existingRequest;
        } else {
            // Crea una nuova richiesta di partecipazione
            $joinRequest = $group->joinRequests()->create([
                'user_id' => $user->id,
                'message' => $request->input('message', ''),
                'status' => 'pending',
            ]);
        }

        // Crea la notifica per admin e moderatori
        \App\Models\Notification::createGroupJoinRequest($joinRequest);

        return back()->with('success', 'Richiesta di partecipazione inviata con successo!');
    }

    /**
     * Lascia un gruppo
     */
    public function leave(Group $group)
    {
        $user = Auth::user();

        if (!$user->isMemberOf($group)) {
            return back()->with('error', 'Non sei membro di questo gruppo.');
        }

        // Non permettere al creatore di lasciare il gruppo se è l'unico admin
        if ($user->isAdminOf($group) && $group->getAdmins()->count() === 1) {
            return back()->with('error', 'Non puoi lasciare il gruppo se sei l\'unico admin. Promuovi prima un altro membro ad admin.');
        }

        $group->members()->where('user_id', $user->id)->delete();

        return redirect()->route('groups.index')
                        ->with('success', 'Hai lasciato il gruppo con successo.');
    }
}
