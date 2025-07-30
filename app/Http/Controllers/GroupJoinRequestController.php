<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupJoinRequest;
use App\Models\GroupMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupJoinRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostra le richieste di partecipazione inviate dall'utente
     */
    public function index()
    {
        $user = Auth::user();
        
        $requests = $user->groupJoinRequests()
                        ->with('group')
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);

        return view('groups.join_requests.index', compact('requests'));
    }

    /**
     * Mostra le richieste pendenti di un gruppo (per admin/moderatori)
     */
    public function pending(Group $group)
    {
        $user = Auth::user();

        if (!$user->isModeratorOf($group) && !$user->hasRole('admin')) {
            abort(403, 'Non hai i permessi per visualizzare le richieste del gruppo.');
        }

        $requests = $group->getPendingJoinRequests()
                         ->paginate(10);

        return view('groups.join_requests.pending', compact('group', 'requests'));
    }

    /**
     * Crea una nuova richiesta di partecipazione
     */
    public function store(Request $request, Group $group)
    {
        $user = Auth::user();

        // Verifica che l'utente non sia già membro del gruppo
        if ($user->isMemberOf($group)) {
            return back()->with('error', 'Sei già membro di questo gruppo.');
        }

        // Verifica che non ci sia già una richiesta pendente
        $existingRequest = GroupJoinRequest::where('group_id', $group->id)
                                          ->where('user_id', $user->id)
                                          ->where('status', 'pending')
                                          ->first();

        if ($existingRequest) {
            return back()->with('error', 'Hai già una richiesta pendente per questo gruppo.');
        }

        // Crea la richiesta
        GroupJoinRequest::create([
            'group_id' => $group->id,
            'user_id' => $user->id,
            'status' => 'pending',
            'message' => $request->input('message', ''),
        ]);

        return back()->with('success', 'Richiesta di partecipazione inviata con successo.');
    }

    /**
     * Mostra i dettagli di una richiesta
     */
    public function show(GroupJoinRequest $request)
    {
        $user = Auth::user();

        // Verifica che l'utente sia chi ha fatto la richiesta o admin/moderatore del gruppo
        if ($request->user_id !== $user->id && !$user->isModeratorOf($request->group) && !$user->hasRole('admin')) {
            abort(403, 'Non hai i permessi per visualizzare questa richiesta.');
        }

        $request->load(['group', 'user', 'processedBy']);

        return view('groups.join_requests.show', compact('request'));
    }

    /**
     * Accetta una richiesta di partecipazione
     */
    public function accept(GroupJoinRequest $request)
    {
        $user = Auth::user();

        // Verifica che l'utente sia admin/moderatore del gruppo
        if (!$user->isModeratorOf($request->group) && !$user->hasRole('admin')) {
            abort(403, 'Non hai i permessi per accettare richieste di partecipazione.');
        }

        // Verifica che la richiesta sia pendente
        if (!$request->isPending()) {
            return back()->with('error', 'Questa richiesta non è più valida.');
        }

        // Verifica che l'utente non sia già membro del gruppo
        if ($request->user->isMemberOf($request->group)) {
            $request->update(['status' => 'accepted']);
            return back()->with('error', 'L\'utente è già membro del gruppo.');
        }

        // Accetta la richiesta
        $request->accept($user);

        // Aggiungi l'utente come membro del gruppo
        GroupMember::create([
            'group_id' => $request->group_id,
            'user_id' => $request->user_id,
            'role' => 'member',
            'joined_at' => now(),
        ]);

        return back()->with('success', "Richiesta di {$request->user->name} accettata con successo.");
    }

    /**
     * Rifiuta una richiesta di partecipazione
     */
    public function decline(GroupJoinRequest $request)
    {
        $user = Auth::user();

        // Verifica che l'utente sia admin/moderatore del gruppo
        if (!$user->isModeratorOf($request->group) && !$user->hasRole('admin')) {
            abort(403, 'Non hai i permessi per rifiutare richieste di partecipazione.');
        }

        // Verifica che la richiesta sia pendente
        if (!$request->isPending()) {
            return back()->with('error', 'Questa richiesta non è più valida.');
        }

        // Rifiuta la richiesta
        $request->decline($user);

        return back()->with('success', "Richiesta di {$request->user->name} rifiutata.");
    }

    /**
     * Cancella una richiesta di partecipazione (per chi l'ha fatta)
     */
    public function cancel(GroupJoinRequest $request)
    {
        $user = Auth::user();

        // Verifica che l'utente sia chi ha fatto la richiesta
        if ($request->user_id !== $user->id) {
            abort(403, 'Non puoi cancellare richieste di altri utenti.');
        }

        // Verifica che la richiesta sia pendente
        if (!$request->isPending()) {
            return back()->with('error', 'Questa richiesta non è più valida.');
        }

        $request->delete();

        return back()->with('success', 'Richiesta di partecipazione cancellata.');
    }

    /**
     * Mostra le statistiche delle richieste per un gruppo
     */
    public function stats(Group $group)
    {
        $user = Auth::user();

        if (!$user->isModeratorOf($group) && !$user->hasRole('admin')) {
            abort(403, 'Non hai i permessi per visualizzare le statistiche del gruppo.');
        }

        $stats = [
            'total_requests' => $group->joinRequests()->count(),
            'pending_requests' => $group->joinRequests()->where('status', 'pending')->count(),
            'accepted_requests' => $group->joinRequests()->where('status', 'accepted')->count(),
            'declined_requests' => $group->joinRequests()->where('status', 'declined')->count(),
            'recent_requests' => $group->joinRequests()->with('user')->latest()->limit(5)->get(),
        ];

        return view('groups.join_requests.stats', compact('group', 'stats'));
    }
}
