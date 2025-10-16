<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Participant;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChatSimple extends Component
{
    use WithFileUploads;

    public $selectedConversationId = null;
    public $newMessage = '';
    public $replyTo = null;
    public $files = [];
    public $showEmojiPicker = false;
    public $search = '';

    protected $listeners = [
        'messageSent' => 'loadMessages',
        'conversationSelected' => 'selectConversation',
    ];

    public function getConversationsProperty()
    {
        return Auth::user()
            ->conversations()
            ->with(['messages' => function($query) {
                $query->latest()->limit(1);
            }])
            ->when($this->search, function($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->get()
            ->sortByDesc(function($conversation) {
                return $conversation->getLastMessage()?->created_at ?? $conversation->created_at;
            });
    }

    public function getSelectedConversationProperty()
    {
        return $this->selectedConversationId 
            ? Conversation::find($this->selectedConversationId)
            : null;
    }

    public function getMessagesProperty()
    {
        if (!$this->selectedConversation) {
            return collect([]);
        }

        return $this->selectedConversation
            ->messages()
            ->with(['user', 'replyTo.user'])
            ->latest()
            ->take(50)
            ->get()
            ->reverse()
            ->values();
    }

    public function selectConversation($conversationId)
    {
        $this->selectedConversationId = $conversationId;
        $this->markAsRead();
    }

    public function sendMessage()
    {
        if (empty($this->newMessage) && empty($this->files)) {
            return;
        }

        if (!$this->selectedConversationId) {
            return;
        }

        $message = Message::create([
            'conversation_id' => $this->selectedConversationId,
            'user_id' => Auth::id(),
            'body' => $this->newMessage,
            'type' => 'text',
            'reply_to' => $this->replyTo,
        ]);

        // Handle file uploads
        if (!empty($this->files)) {
            foreach ($this->files as $file) {
                $path = $file->store('chat-attachments', 'public');
                Message::create([
                    'conversation_id' => $this->selectedConversationId,
                    'user_id' => Auth::id(),
                    'body' => $file->getClientOriginalName(),
                    'type' => $file->getMimeType(),
                    'metadata' => ['url' => Storage::url($path)],
                ]);
            }
            $this->files = [];
        }

        $this->newMessage = '';
        $this->replyTo = null;

        // Broadcast the message
        $this->dispatch('messageSent', $this->selectedConversationId);
    }

    public function replyToMessage($messageId)
    {
        $this->replyTo = $messageId;
    }

    public function cancelReply()
    {
        $this->replyTo = null;
    }

    public function addReaction($messageId, $emoji)
    {
        $message = Message::find($messageId);
        if ($message) {
            $message->addReaction($emoji, Auth::id());
        }
    }

    public function removeReaction($messageId, $emoji)
    {
        $message = Message::find($messageId);
        if ($message) {
            $message->removeReaction($emoji, Auth::id());
        }
    }

    public function markAsRead()
    {
        if ($this->selectedConversationId) {
            Participant::where('conversation_id', $this->selectedConversationId)
                ->where('user_id', Auth::id())
                ->update(['last_read_at' => now()]);
        }
    }

    public function render()
    {
        return view('livewire.chat-simple');
    }
}
