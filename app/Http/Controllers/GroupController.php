<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of groups
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Group::query();

        // Filtri
        if ($request->has('filter')) {
            switch ($request->filter) {
                case 'my_groups':
                    $query->whereHas('members', function($q) use ($user) {
                        $q->where('user_id', $user->id);
                    });
                    break;
                case 'my_admin_groups':
                    $query->whereHas('members', function($q) use ($user) {
                        $q->where('user_id', $user->id)->where('role', 'admin');
                    });
                    break;
                case 'public':
                    $query->where('visibility', 'public');
                    break;
                case 'private':
                    if ($user->hasRole('admin')) {
                        $query->where('visibility', 'private');
                    }
                    break;
            }
        }

        // Ricerca
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Ordinamento
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $groups = $query->with(['creator', 'members.user'])
                       ->paginate(12);

        return view('groups.index', compact('groups'));
    }

    /**
     * Show the form for creating a new group
     */
    public function create()
    {
        if (!Auth::user()->can('groups.create')) {
            abort(403, 'Non hai i permessi per creare gruppi.');
        }

        return view('groups.create');
    }

    /**
     * Store a newly created group
     */
    public function store(Request $request)
    {
        if (!Auth::user()->can('groups.create')) {
            abort(403, 'Non hai i permessi per creare gruppi.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:groups',
            'description' => 'nullable|string|max:1000',
            'visibility' => 'required|in:public,private',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $group = new Group();
        $group->name = $request->name;
        $group->description = $request->description;
        $group->visibility = $request->visibility;
        $group->created_by = Auth::id();

        // Gestione immagine
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('groups', 'public');
            $group->image = $imagePath;
        }

        $group->save();

        // Aggiungi il creatore come admin del gruppo
        GroupMember::create([
            'group_id' => $group->id,
            'user_id' => Auth::id(),
            'role' => 'admin',
            'joined_at' => now(),
        ]);

        // Gestisci gli inviti se presenti
        if ($request->has('invited_users') && !empty($request->invited_users)) {
            $invitedUsers = json_decode($request->invited_users, true);

            if (is_array($invitedUsers)) {
                foreach ($invitedUsers as $invitedUser) {
                    if (isset($invitedUser['id']) && $invitedUser['id'] != Auth::id()) {
                        // Crea l'invito
                        \App\Models\GroupInvitation::create([
                            'group_id' => $group->id,
                            'user_id' => $invitedUser['id'],
                            'invited_by' => Auth::id(),
                            'message' => "Sei stato invitato a unirti al gruppo \"{$group->name}\"",
                            'expires_at' => now()->addDays(7),
                        ]);

                        // Crea la notifica
                        \App\Models\Notification::createGroupInvitation(
                            \App\Models\GroupInvitation::where('group_id', $group->id)
                                                      ->where('user_id', $invitedUser['id'])
                                                      ->latest()
                                                      ->first()
                        );
                    }
                }
            }
        }

        return redirect()->route('groups.show', $group)
                        ->with('success', 'Gruppo creato con successo!' .
                              (isset($invitedUsers) && count($invitedUsers) > 0 ?
                               ' Inviti inviati a ' . count($invitedUsers) . ' utenti.' : ''));
    }

    /**
     * Display the specified group
     */


    public function show(Group $group)
    {
        $user = Auth::user();

        // Verifica se l'utente può visualizzare il gruppo
        if ($group->visibility === 'private' && !$user->isMemberOf($group) && !$user->hasRole('admin')) {
            abort(403, 'Non hai i permessi per visualizzare questo gruppo.');
        }

        $group->load(['creator', 'members.user', 'events']);

        // Statistiche del gruppo
        $stats = [
            'total_members' => $group->getMembersCount(),
            'total_events' => $group->events()->count(),
            'pending_invitations' => $group->getPendingInvitations()->count(),
            'pending_requests' => $group->getPendingJoinRequests()->count(),
        ];

        // Verifica ruolo dell'utente nel gruppo
        $userRole = $user->getRoleInGroup($group);
        $isAdmin = $user->isAdminOf($group);
        $isModerator = $user->isModeratorOf($group);
        $isMember = $user->isMemberOf($group);

        return view('groups.show', compact('group', 'stats', 'userRole', 'isAdmin', 'isModerator', 'isMember'));
    }

    /**
     * Show the form for editing the specified group
     */
    public function edit(Group $group)
    {
        $user = Auth::user();

        if (!$user->isAdminOf($group) && !$user->hasRole('admin')) {
            abort(403, 'Non hai i permessi per modificare questo gruppo.');
        }

        return view('groups.edit', compact('group'));
    }

    /**
     * Update the specified group
     */
    public function update(Request $request, Group $group)
    {
        $user = Auth::user();

        if (!$user->isAdminOf($group) && !$user->hasRole('admin')) {
            abort(403, 'Non hai i permessi per modificare questo gruppo.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('groups')->ignore($group->id)],
            'description' => 'nullable|string|max:1000',
            'visibility' => 'required|in:public,private',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $group->name = $request->name;
        $group->description = $request->description;
        $group->visibility = $request->visibility;

        // Gestione immagine
        if ($request->hasFile('image')) {
            // Elimina l'immagine precedente se esiste
            if ($group->image) {
                Storage::disk('public')->delete($group->image);
            }

            $imagePath = $request->file('image')->store('groups', 'public');
            $group->image = $imagePath;
        }

        $group->save();

        return redirect()->route('groups.show', $group)
                        ->with('success', 'Gruppo aggiornato con successo!');
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
