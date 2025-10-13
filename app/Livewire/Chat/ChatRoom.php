<?php

namespace App\Livewire\Chat;

use App\Models\ChatRoom;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ChatRoomComponent extends Component
{
    use WithPagination;

    public $roomId;
    public $room;
    public $newMessage = '';
    public $messages;
    public $users = [];
    public $isTyping = false;
    public $typingUsers = [];
    
    protected $listeners = [
        'messageReceived' => 'handleMessageReceived',
        'userTyping' => 'handleUserTyping',
        'userStoppedTyping' => 'handleUserStoppedTyping',
        'refreshMessages' => 'refreshMessages'
    ];

    public function mount($roomId)
    {
        $this->roomId = $roomId;
        $this->loadRoom();
        $this->loadMessages();
        $this->loadUsers();
    }

    public function loadRoom()
    {
        $this->room = ChatRoom::with(['participants.user'])
            ->findOrFail($this->roomId);
    }

    public function loadMessages()
    {
        $this->messages = ChatMessage::with(['sender'])
            ->where('chat_room_id', $this->roomId)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function loadUsers()
    {
        $this->users = $this->room->participants()
            ->with('user')
            ->get()
            ->pluck('user')
            ->filter()
            ->unique('id')
            ->values();
    }

    public function sendMessage()
    {
        $this->validate([
            'newMessage' => 'required|string|max:1000'
        ]);

        $message = ChatMessage::create([
            'chat_room_id' => $this->roomId,
            'user_id' => Auth::id(),
            'content' => $this->newMessage,
            'message_type' => 'text'
        ]);

        // Reset input
        $this->newMessage = '';

        // Broadcast the message
        broadcast(new \App\Events\ChatMessageSent($message))->toOthers();

        // Refresh messages
        $this->loadMessages();

        // Scroll to bottom (handled by Alpine.js)
        $this->dispatch('scrollToBottom');
    }

    public function handleMessageReceived($messageData)
    {
        $this->loadMessages();
        $this->dispatch('scrollToBottom');
    }

    public function handleUserTyping($userId)
    {
        $user = User::find($userId);
        if ($user && !in_array($user->id, $this->typingUsers)) {
            $this->typingUsers[] = $user->id;
        }
    }

    public function handleUserStoppedTyping($userId)
    {
        $this->typingUsers = array_filter($this->typingUsers, fn($id) => $id != $userId);
    }

    public function refreshMessages()
    {
        $this->loadMessages();
    }

    public function startTyping()
    {
        if (!$this->isTyping) {
            $this->isTyping = true;
            broadcast(new \App\Events\UserStartedTyping($this->roomId, Auth::id()))->toOthers();
        }
    }

    public function stopTyping()
    {
        if ($this->isTyping) {
            $this->isTyping = false;
            broadcast(new \App\Events\UserStoppedTyping($this->roomId, Auth::id()))->toOthers();
        }
    }

    public function render()
    {
        return view('livewire.chat.chat-room')
            ->layout('components.layouts.app');
    }
}
