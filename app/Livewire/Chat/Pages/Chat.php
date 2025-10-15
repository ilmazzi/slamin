<?php

namespace App\Livewire\Chat\Pages;

use Livewire\Attributes\Title;
use Livewire\Component;
use App\Models\Chat\Conversation;

class Chat extends Component
{
    public $conversation;

    public function mount()
    {
        // /make sure user is authenticated
        abort_unless(auth()->check(), 401);

        // We remove deleted conversation incase the user decides to visit the delted conversation
        $this->conversation = Conversation::where('id', $this->conversation)->firstOrFail();

        // Check if the user belongs to the conversation
        abort_unless(auth()->user()->belongsToConversation($this->conversation), 403);

    }

    #[Title('Chats')]
    public function render()
    {
        return view('chat::livewire.pages.chat')
            ->layout(config('chat.layout', 'chat::layouts.app'));
    }
}
