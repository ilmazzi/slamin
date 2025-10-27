<?php

namespace App\Livewire\Groups;

use Livewire\Component;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;

class GroupShow extends Component
{
    public Group $group;
    public $stats = [];
    public $userRole = null;
    public $isAdmin = false;
    public $isModerator = false;
    public $isMember = false;
    public $joinMessage = '';

    public function mount(Group $group)
    {
        $user = Auth::user();

        // Verifica permessi
        if ($group->visibility === 'private' && !$user->isMemberOf($group) && !$user->hasRole('admin')) {
            abort(403, 'Non hai i permessi per visualizzare questo gruppo.');
        }

        $this->group = $group;
        $this->loadGroupData();
    }

    public function loadGroupData()
    {
        $user = Auth::user();

        $this->group->load(['creator', 'members.user', 'events', 'linkedEvents']);

        // Stats
        $this->stats = [
            'total_members' => $this->group->getMembersCount(),
            'total_events' => $this->group->events()->count(),
            'pending_invitations' => $this->group->getPendingInvitations()->count(),
            'pending_requests' => $this->group->getPendingJoinRequests()->count(),
        ];

        // User role
        $this->userRole = $user->getRoleInGroup($this->group);
        $this->isAdmin = $user->isAdminOf($this->group);
        $this->isModerator = $user->isModeratorOf($this->group);
        $this->isMember = $user->isMemberOf($this->group);
    }

    public function joinGroup()
    {
        $user = Auth::user();

        // Verifica se già membro
        if ($user->isMemberOf($this->group)) {
            $this->dispatch('notify', ['message' => __('groups.already_member'), 'type' => 'error']);
            return;
        }

        // Verifica se ha già richiesta pendente
        if ($this->group->hasPendingInvitation($user)) {
            $this->dispatch('notify', ['message' => __('groups.pending_invitation_exists'), 'type' => 'error']);
            return;
        }

        $existingRequest = $this->group->joinRequests()->where('user_id', $user->id)->first();

        if ($existingRequest && $existingRequest->status === 'pending') {
            $this->dispatch('notify', ['message' => __('groups.pending_request_exists'), 'type' => 'error']);
            return;
        }

        // Crea richiesta
        if ($existingRequest) {
            $existingRequest->update([
                'message' => $this->joinMessage,
                'status' => 'pending',
                'processed_by' => null,
                'processed_at' => null,
            ]);
        } else {
            $existingRequest = $this->group->joinRequests()->create([
                'user_id' => $user->id,
                'message' => $this->joinMessage,
                'status' => 'pending',
            ]);
        }

        // Notifica
        \App\Models\Notification::createGroupJoinRequest($existingRequest);

        $this->dispatch('notify', ['message' => __('groups.join_request_sent'), 'type' => 'success']);
        $this->joinMessage = '';
        $this->loadGroupData();
    }

    public function leaveGroup()
    {
        $user = Auth::user();

        if (!$user->isMemberOf($this->group)) {
            $this->dispatch('notify', ['message' => __('groups.not_member'), 'type' => 'error']);
            return;
        }

        // Non permettere al creatore di lasciare se è l'unico admin
        if ($user->isAdminOf($this->group) && $this->group->getAdmins()->count() === 1) {
            $this->dispatch('notify', ['message' => __('groups.cannot_leave_only_admin'), 'type' => 'error']);
            return;
        }

        $this->group->members()->where('user_id', $user->id)->delete();

        $this->dispatch('notify', ['message' => __('groups.left_successfully'), 'type' => 'success']);
        
        return $this->redirect(route('groups.index'));
    }

    public function render()
    {
        return view('livewire.groups.group-show')
            ->extends('layout.master')
            ->section('main-content');
    }
}
