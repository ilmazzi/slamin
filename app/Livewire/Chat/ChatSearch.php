<?php

namespace App\Livewire\Chat;

use App\Models\User;
use App\Models\ChatRoom as ChatRoomModel;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class ChatSearch extends Component
{
    public $searchQuery = '';
    public $searchResults = [];
    public $isSearching = false;
    public $selectedUser = null;

    protected $listeners = [
        'resetSearch' => 'resetSearch'
    ];

    public function updatedSearchQuery()
    {
        if (strlen($this->searchQuery) < 2) {
            $this->searchResults = [];
            $this->isSearching = false;
            return;
        }

        $this->isSearching = true;

        // Search users excluding current user
        $users = User::where('id', '!=', Auth::id())
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->searchQuery . '%')
                      ->orWhere('nickname', 'like', '%' . $this->searchQuery . '%');
            })
            ->limit(10)
            ->get();

        $this->searchResults = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'nickname' => $user->nickname,
                'avatar_url' => $user->avatar_url,
                'avatar_html' => $user->avatar_html
            ];
        })->toArray();
    }

    public function startChat($userId)
    {
        try {
            // Check if a private chat already exists between these users
            $existingRoom = ChatRoomModel::whereHas('participants', function ($query) use ($userId) {
                $query->where('user_id', Auth::id());
            })->whereHas('participants', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->where('type', 'private')->first();

            if ($existingRoom) {
                // Redirect to existing chat using Livewire redirect
                return $this->redirect(route('chat.room', $existingRoom->id), navigate: true);
            }

            // Create new private chat room
            $room = ChatRoomModel::create([
                'name' => 'Chat Privata',
                'type' => 'private',
                'created_by' => Auth::id()
            ]);

            // Add participants
            $room->participants()->createMany([
                ['user_id' => Auth::id()],
                ['user_id' => $userId]
            ]);

            // Redirect to new chat using Livewire redirect
            return $this->redirect(route('chat.room', $room->id), navigate: true);

        } catch (\Exception $e) {
            session()->flash('error', 'Errore nella creazione della chat: ' . $e->getMessage());
            \Log::error('ChatSearch::startChat error', [
                'user_id' => $userId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    public function resetSearch()
    {
        $this->searchQuery = '';
        $this->searchResults = [];
        $this->isSearching = false;
        $this->selectedUser = null;
    }

    public function render()
    {
        return view('livewire.chat.chat-search');
    }
}
