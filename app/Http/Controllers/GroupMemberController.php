<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupMember;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupMemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostra la lista dei membri di un gruppo
     */
    public function index(Group $group)
    {
        $user = Auth::user();

        // Verifica se l'utente può visualizzare i membri
        if (!$user->isMemberOf($group) && !$user->hasRole('admin')) {
            abort(403, 'Non hai i permessi per visualizzare i membri di questo gruppo.');
        }

        $members = $group->members()
                        ->with('user')
                        ->orderBy('role', 'desc')
                        ->orderBy('joined_at', 'asc')
                        ->paginate(20);

        $isAdmin = $user->isAdminOf($group);
        $isModerator = $user->isModeratorOf($group);

        return view('groups.members.index', compact('group', 'members', 'isAdmin', 'isModerator'));
    }

    /**
     * Promuovi un membro ad admin
     */
    public function promote(Group $group, User $member)
    {
        $user = Auth::user();

        if (!$user->isAdminOf($group) && !$user->hasRole('admin')) {
            abort(403, 'Non hai i permessi per promuovere membri.');
        }

        $groupMember = $group->members()->where('user_id', $member->id)->first();

        if (!$groupMember) {
            return back()->with('error', 'Utente non trovato nel gruppo.');
        }

        if ($groupMember->role === 'admin') {
            return back()->with('error', 'L\'utente è già admin del gruppo.');
        }

        $groupMember->update(['role' => 'admin']);

        return back()->with('success', "{$member->name} è stato promosso ad admin del gruppo.");
    }

    /**
     * Degrada un admin a moderatore
     */
    public function demote(Group $group, User $member)
    {
        $user = Auth::user();

        if (!$user->isAdminOf($group) && !$user->hasRole('admin')) {
            abort(403, 'Non hai i permessi per degradare admin.');
        }

        $groupMember = $group->members()->where('user_id', $member->id)->first();

        if (!$groupMember) {
            return back()->with('error', 'Utente non trovato nel gruppo.');
        }

        if ($groupMember->role !== 'admin') {
            return back()->with('error', 'L\'utente non è admin del gruppo.');
        }

        // Non permettere di degradare l'ultimo admin
        if ($group->getAdmins()->count() === 1) {
            return back()->with('error', 'Non puoi degradare l\'ultimo admin del gruppo.');
        }

        $groupMember->update(['role' => 'moderator']);

        return back()->with('success', "{$member->name} è stato degradato a moderatore.");
    }

    /**
     * Promuovi un membro a moderatore
     */
    public function promoteToModerator(Group $group, User $member)
    {
        $user = Auth::user();

        if (!$user->isAdminOf($group) && !$user->hasRole('admin')) {
            abort(403, 'Non hai i permessi per promuovere membri.');
        }

        $groupMember = $group->members()->where('user_id', $member->id)->first();

        if (!$groupMember) {
            return back()->with('error', 'Utente non trovato nel gruppo.');
        }

        if (in_array($groupMember->role, ['admin', 'moderator'])) {
            return back()->with('error', 'L\'utente è già admin o moderatore del gruppo.');
        }

        $groupMember->update(['role' => 'moderator']);

        return back()->with('success', "{$member->name} è stato promosso a moderatore del gruppo.");
    }

    /**
     * Degrada un moderatore a membro
     */
    public function demoteToMember(Group $group, User $member)
    {
        $user = Auth::user();

        if (!$user->isAdminOf($group) && !$user->hasRole('admin')) {
            abort(403, 'Non hai i permessi per degradare moderatori.');
        }

        $groupMember = $group->members()->where('user_id', $member->id)->first();

        if (!$groupMember) {
            return back()->with('error', 'Utente non trovato nel gruppo.');
        }

        if ($groupMember->role !== 'moderator') {
            return back()->with('error', 'L\'utente non è moderatore del gruppo.');
        }

        $groupMember->update(['role' => 'member']);

        return back()->with('success', "{$member->name} è stato degradato a membro.");
    }

    /**
     * Rimuovi un membro dal gruppo
     */
    public function remove(Group $group, User $member)
    {
        $user = Auth::user();

        if (!$user->isModeratorOf($group) && !$user->hasRole('admin')) {
            abort(403, 'Non hai i permessi per rimuovere membri.');
        }

        $groupMember = $group->members()->where('user_id', $member->id)->first();

        if (!$groupMember) {
            return back()->with('error', 'Utente non trovato nel gruppo.');
        }

        // Non permettere di rimuovere se stessi
        if ($member->id === $user->id) {
            return back()->with('error', 'Non puoi rimuovere te stesso dal gruppo.');
        }

        // Non permettere di rimuovere admin se non sei admin
        if ($groupMember->role === 'admin' && !$user->isAdminOf($group)) {
            return back()->with('error', 'Solo gli admin possono rimuovere altri admin.');
        }

        // Non permettere di rimuovere l'ultimo admin
        if ($groupMember->role === 'admin' && $group->getAdmins()->count() === 1) {
            return back()->with('error', 'Non puoi rimuovere l\'ultimo admin del gruppo.');
        }

        $groupMember->delete();

        return back()->with('success', "{$member->name} è stato rimosso dal gruppo.");
    }

    /**
     * Cerca utenti per invitare al gruppo
     */
    public function searchUsers(Group $group, Request $request)
    {
        $user = Auth::user();

        if (!$user->isModeratorOf($group) && !$user->hasRole('admin')) {
            abort(403, 'Non hai i permessi per invitare utenti.');
        }

        $search = $request->get('search', '');

        $users = User::where(function($query) use ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        })
        ->whereNotIn('id', $group->members()->pluck('user_id'))
        ->whereNotIn('id', $group->invitations()->where('status', 'pending')->pluck('user_id'))
        ->limit(10)
        ->get();

        return response()->json($users);
    }

    /**
     * Invita un utente al gruppo
     */
    public function invite(Group $group, Request $request)
    {
        $user = Auth::user();

        if (!$user->isModeratorOf($group) && !$user->hasRole('admin')) {
            abort(403, 'Non hai i permessi per invitare utenti.');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'nullable|string|max:500',
        ]);

        $invitedUser = User::findOrFail($request->user_id);

        // Verifica se l'utente è già membro
        if ($group->hasMember($invitedUser)) {
            return back()->with('error', 'L\'utente è già membro del gruppo.');
        }

        // Verifica se l'utente ha già un invito pendente
        if ($group->hasPendingInvitation($invitedUser)) {
            return back()->with('error', 'L\'utente ha già un invito pendente per questo gruppo.');
        }

        // Verifica se l'utente ha già una richiesta pendente
        if ($group->hasPendingJoinRequest($invitedUser)) {
            return back()->with('error', 'L\'utente ha già una richiesta pendente per questo gruppo.');
        }

        // Crea l'invito
        $group->invitations()->create([
            'user_id' => $invitedUser->id,
            'invited_by' => $user->id,
            'message' => $request->message,
            'status' => 'pending',
            'expires_at' => now()->addDays(7), // Invito valido per 7 giorni
        ]);

        return back()->with('success', "Invito inviato a {$invitedUser->name}.");
    }
}
