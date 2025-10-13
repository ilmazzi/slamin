<?php

namespace App\Livewire\Events\Scoring;

use Livewire\Component;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\User;

class ParticipantManagement extends Component
{
    public $event;
    public $participants;
    public $showAddModal = false;
    public $participantType = 'user'; // 'user' or 'guest'
    
    // User participant
    public $userSearch = '';
    public $searchResults = [];
    public $selectedUser = null;
    
    // Guest participant
    public $guest_name;
    public $guest_email;
    public $guest_phone;
    public $guest_bio;
    
    // Common fields
    public $performance_order;
    public $notes;

    public function mount(Event $event)
    {
        $this->event = $event;
        $this->loadParticipants();
    }

    public function loadParticipants()
    {
        $this->participants = $this->event->participants()
            ->with(['user', 'ranking'])
            ->orderBy('performance_order')
            ->get();
    }

    public function openAddModal()
    {
        $this->resetForm();
        $this->showAddModal = true;
    }

    public function updatedUserSearch()
    {
        if (strlen($this->userSearch) >= 2) {
            $this->searchResults = User::where(function($query) {
                $query->where('name', 'like', '%' . $this->userSearch . '%')
                      ->orWhere('nickname', 'like', '%' . $this->userSearch . '%')
                      ->orWhere('email', 'like', '%' . $this->userSearch . '%');
            })
            ->limit(10)
            ->get()
            ->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'nickname' => $user->nickname,
                    'email' => $user->email,
                    'display_name' => $user->getDisplayName(),
                    'avatar' => $user->profile_photo_url ?? asset('assets/images/avatar/default-avatar.webp'),
                ];
            });
        } else {
            $this->searchResults = [];
        }
    }

    public function selectUser($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $this->selectedUser = [
                'id' => $user->id,
                'display_name' => $user->getDisplayName(),
                'avatar' => $user->profile_photo_url ?? asset('assets/images/avatar/default-avatar.webp'),
            ];
            $this->userSearch = $user->getDisplayName();
            $this->searchResults = [];
        }
    }

    public function clearSelectedUser()
    {
        $this->selectedUser = null;
        $this->userSearch = '';
        $this->searchResults = [];
    }

    public function addParticipant()
    {
        if ($this->participantType === 'user') {
            $this->validate([
                'selectedUser' => 'required',
            ]);

            // Check if user is already a participant
            $exists = $this->event->participants()->where('user_id', $this->selectedUser['id'])->exists();
            if ($exists) {
                $this->dispatch('swal:warning', ['title' => 'Attenzione', 'text' => 'Questo utente è già un partecipante!']);
                return;
            }

            EventParticipant::create([
                'event_id' => $this->event->id,
                'user_id' => $this->selectedUser['id'],
                'registration_type' => 'organizer_added',
                'status' => 'confirmed',
                'performance_order' => $this->performance_order ?: ($this->participants->max('performance_order') ?? 0) + 1,
                'notes' => $this->notes,
                'added_by' => auth()->id(),
            ]);

            $this->dispatch('swal:success', ['title' => 'Aggiunto!', 'text' => 'Partecipante aggiunto con successo!']);
        } else {
            // Guest participant
            $this->validate([
                'guest_name' => 'required|string|max:255',
                'guest_email' => 'nullable|email',
                'guest_phone' => 'nullable|string',
                'guest_bio' => 'nullable|string',
            ]);

            EventParticipant::create([
                'event_id' => $this->event->id,
                'guest_name' => $this->guest_name,
                'guest_email' => $this->guest_email,
                'guest_phone' => $this->guest_phone,
                'guest_bio' => $this->guest_bio,
                'registration_type' => 'guest',
                'status' => 'confirmed',
                'performance_order' => $this->performance_order ?: ($this->participants->max('performance_order') ?? 0) + 1,
                'notes' => $this->notes,
                'added_by' => auth()->id(),
            ]);

            $this->dispatch('swal:success', ['title' => 'Aggiunto!', 'text' => 'Partecipante ospite aggiunto con successo!']);
        }

        $this->loadParticipants();
        $this->showAddModal = false;
        $this->resetForm();
    }

    public function updateStatus($participantId, $newStatus)
    {
        $participant = EventParticipant::findOrFail($participantId);
        $participant->status = $newStatus;
        $participant->save();

        $this->loadParticipants();
        $this->dispatch('swal:success', ['title' => 'Aggiornato!', 'text' => 'Stato partecipante aggiornato!']);
    }

    public function removeParticipant($participantId)
    {
        $participant = EventParticipant::findOrFail($participantId);
        $participant->delete();

        $this->loadParticipants();
        $this->dispatch('swal:success', ['title' => 'Rimosso!', 'text' => 'Partecipante rimosso con successo!']);
    }

    public function importRegisteredUsers()
    {
        $imported = 0;
        $skipped = 0;

        // Get users with accepted invitations
        $acceptedInvitations = $this->event->invitations()
            ->where('status', 'accepted')
            ->with('invitedUser')
            ->get();

        foreach ($acceptedInvitations as $invitation) {
            if ($invitation->invitedUser) {
                // Check if already added as participant
                $exists = EventParticipant::where('event_id', $this->event->id)
                    ->where('user_id', $invitation->invitedUser->id)
                    ->exists();

                if (!$exists) {
                    EventParticipant::create([
                        'event_id' => $this->event->id,
                        'user_id' => $invitation->invitedUser->id,
                        'registration_type' => 'invitation',
                        'status' => 'confirmed',
                    ]);
                    $imported++;
                } else {
                    $skipped++;
                }
            }
        }

        // Get users with accepted requests
        $acceptedRequests = $this->event->requests()
            ->where('status', 'accepted')
            ->with('user')
            ->get();

        foreach ($acceptedRequests as $request) {
            if ($request->user) {
                // Check if already added as participant
                $exists = EventParticipant::where('event_id', $this->event->id)
                    ->where('user_id', $request->user->id)
                    ->exists();

                if (!$exists) {
                    EventParticipant::create([
                        'event_id' => $this->event->id,
                        'user_id' => $request->user->id,
                        'registration_type' => 'request',
                        'status' => 'confirmed',
                    ]);
                    $imported++;
                } else {
                    $skipped++;
                }
            }
        }

        $this->loadParticipants();

        if ($imported > 0) {
            $message = "Importati {$imported} partecipanti!";
            if ($skipped > 0) {
                $message .= " ({$skipped} già presenti)";
            }
            $this->dispatch('swal:success', ['title' => 'Importazione Completata!', 'text' => $message]);
        } else {
            $this->dispatch('swal:warning', ['title' => 'Nessun nuovo partecipante', 'text' => 'Tutti gli utenti iscritti sono già stati aggiunti o non ci sono utenti iscritti.']);
        }
    }

    private function resetForm()
    {
        $this->reset(['userSearch', 'searchResults', 'selectedUser', 'guest_name', 'guest_email', 'guest_phone', 'guest_bio', 'performance_order', 'notes']);
        $this->participantType = 'user';
    }

    public function render()
    {
        return view('livewire.events.scoring.participant-management');
    }
}
