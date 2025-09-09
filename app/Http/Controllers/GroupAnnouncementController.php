<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupAnnouncement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class GroupAnnouncementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostra gli annunci di un gruppo
     */
    public function index(Group $group)
    {
        $user = Auth::user();
        
        // Verifica se l'utente può vedere gli annunci
        if (!$this->canViewAnnouncements($group, $user)) {
            abort(403, 'Non hai i permessi per visualizzare gli annunci di questo gruppo.');
        }

        $announcements = $group->announcements()
            ->active()
            ->with(['author'])
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('groups.announcements.index', compact('group', 'announcements'));
    }

    /**
     * Mostra un annuncio specifico
     */
    public function show(Group $group, GroupAnnouncement $announcement)
    {
        $user = Auth::user();
        
        // Verifica se l'annuncio appartiene al gruppo
        if ($announcement->group_id !== $group->id) {
            abort(404);
        }

        // Verifica se l'utente può vedere l'annuncio
        if (!$this->canViewAnnouncement($announcement, $user)) {
            abort(403, 'Non hai i permessi per visualizzare questo annuncio.');
        }

        return view('groups.announcements.show', compact('group', 'announcement'));
    }

    /**
     * Mostra il form per creare un nuovo annuncio
     */
    public function create(Group $group)
    {
        $user = Auth::user();
        
        // Verifica se l'utente può creare annunci
        if (!$this->canCreateAnnouncement($group, $user)) {
            abort(403, 'Non hai i permessi per creare annunci in questo gruppo.');
        }

        return view('groups.announcements.create', compact('group'));
    }

    /**
     * Salva un nuovo annuncio
     */
    public function store(Request $request, Group $group)
    {
        $user = Auth::user();
        
        // Verifica se l'utente può creare annunci
        if (!$this->canCreateAnnouncement($group, $user)) {
            abort(403, 'Non hai i permessi per creare annunci in questo gruppo.');
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'visibility' => 'required|in:public,members_only,admins_only',
            'is_pinned' => 'boolean',
            'has_poll' => 'boolean',
            'poll_options' => 'array|max:10',
            'poll_options.*' => 'string|max:255',
            'expires_at' => 'nullable|date|after:now',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only([
            'title', 'content', 'visibility', 'is_pinned', 'has_poll', 'expires_at'
        ]);

        $data['group_id'] = $group->id;
        $data['author_id'] = $user->id;

        // Gestisci il pinning (solo admin/moderatori possono pinnare)
        if (isset($data['is_pinned']) && $data['is_pinned'] && !$group->hasModerator($user)) {
            $data['is_pinned'] = false;
        }

        // Gestisci il sondaggio
        if ($request->has_poll && $request->poll_options) {
            $data['poll_options'] = array_filter($request->poll_options);
            $data['poll_votes'] = [];
        }

        $announcement = GroupAnnouncement::create($data);

        return redirect()->route('groups.announcements.show', [$group, $announcement])
            ->with('success', 'Annuncio creato con successo!');
    }

    /**
     * Mostra il form per modificare un annuncio
     */
    public function edit(Group $group, GroupAnnouncement $announcement)
    {
        $user = Auth::user();
        
        // Verifica se l'annuncio appartiene al gruppo
        if ($announcement->group_id !== $group->id) {
            abort(404);
        }

        // Verifica se l'utente può modificare l'annuncio
        if (!$this->canEditAnnouncement($announcement, $user)) {
            abort(403, 'Non hai i permessi per modificare questo annuncio.');
        }

        return view('groups.announcements.edit', compact('group', 'announcement'));
    }

    /**
     * Aggiorna un annuncio
     */
    public function update(Request $request, Group $group, GroupAnnouncement $announcement)
    {
        $user = Auth::user();
        
        // Verifica se l'annuncio appartiene al gruppo
        if ($announcement->group_id !== $group->id) {
            abort(404);
        }

        // Verifica se l'utente può modificare l'annuncio
        if (!$this->canEditAnnouncement($announcement, $user)) {
            abort(403, 'Non hai i permessi per modificare questo annuncio.');
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'visibility' => 'required|in:public,members_only,admins_only',
            'is_pinned' => 'boolean',
            'expires_at' => 'nullable|date|after:now',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = $request->only(['title', 'content', 'visibility', 'expires_at']);

        // Gestisci il pinning (solo admin/moderatori possono pinnare)
        if ($request->has('is_pinned')) {
            if ($request->is_pinned && $group->hasModerator($user)) {
                $data['is_pinned'] = true;
            } else {
                $data['is_pinned'] = false;
            }
        }

        $announcement->update($data);

        return redirect()->route('groups.announcements.show', [$group, $announcement])
            ->with('success', 'Annuncio aggiornato con successo!');
    }

    /**
     * Elimina un annuncio
     */
    public function destroy(Group $group, GroupAnnouncement $announcement)
    {
        $user = Auth::user();
        
        // Verifica se l'annuncio appartiene al gruppo
        if ($announcement->group_id !== $group->id) {
            abort(404);
        }

        // Verifica se l'utente può eliminare l'annuncio
        if (!$this->canDeleteAnnouncement($announcement, $user)) {
            abort(403, 'Non hai i permessi per eliminare questo annuncio.');
        }

        $announcement->delete();

        return redirect()->route('groups.announcements.index', $group)
            ->with('success', 'Annuncio eliminato con successo!');
    }

    /**
     * Vota in un sondaggio
     */
    public function vote(Request $request, Group $group, GroupAnnouncement $announcement)
    {
        $user = Auth::user();
        
        // Verifica se l'annuncio appartiene al gruppo
        if ($announcement->group_id !== $group->id) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'option_index' => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Opzione non valida'], 400);
        }

        $optionIndex = $request->option_index;

        // Verifica se l'opzione esiste
        if (!isset($announcement->poll_options[$optionIndex])) {
            return response()->json(['error' => 'Opzione non valida'], 400);
        }

        // Registra il voto
        if ($announcement->recordVote($user, $optionIndex)) {
            return response()->json([
                'success' => true,
                'results' => $announcement->getPollResults()
            ]);
        }

        return response()->json(['error' => 'Impossibile registrare il voto'], 400);
    }

    /**
     * Verifica se un utente può visualizzare gli annunci di un gruppo
     */
    private function canViewAnnouncements(Group $group, $user): bool
    {
        // Gli admin possono vedere tutto
        if ($user->hasRole('admin')) {
            return true;
        }

        // I membri possono vedere gli annunci del gruppo
        return $group->hasMember($user);
    }

    /**
     * Verifica se un utente può visualizzare un annuncio specifico
     */
    private function canViewAnnouncement(GroupAnnouncement $announcement, $user): bool
    {
        switch ($announcement->visibility) {
            case 'public':
                return true;
            case 'members_only':
                return $announcement->group->hasMember($user);
            case 'admins_only':
                return $announcement->group->hasModerator($user);
            default:
                return false;
        }
    }

    /**
     * Verifica se un utente può creare annunci
     */
    private function canCreateAnnouncement(Group $group, $user): bool
    {
        // Solo i membri possono creare annunci
        return $group->hasMember($user);
    }

    /**
     * Verifica se un utente può modificare un annuncio
     */
    private function canEditAnnouncement(GroupAnnouncement $announcement, $user): bool
    {
        // L'autore può modificare il proprio annuncio
        if ($announcement->author_id === $user->id) {
            return true;
        }

        // Gli admin/moderatori possono modificare tutti gli annunci
        return $announcement->group->hasModerator($user);
    }

    /**
     * Verifica se un utente può eliminare un annuncio
     */
    private function canDeleteAnnouncement(GroupAnnouncement $announcement, $user): bool
    {
        // L'autore può eliminare il proprio annuncio
        if ($announcement->author_id === $user->id) {
            return true;
        }

        // Gli admin/moderatori possono eliminare tutti gli annunci
        return $announcement->group->hasModerator($user);
    }
}
