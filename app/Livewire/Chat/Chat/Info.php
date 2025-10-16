<?php

namespace App\Livewire\Chat\Chat;

use Livewire\Attributes\Locked;
use App\Helpers\Chat\ChatHelper;
use App\Helpers\AvatarHelper;
use App\Livewire\Chat\Chats\Chats;
use App\Livewire\Chat\Concerns\ModalComponent;
use App\Livewire\Chat\Concerns\Widget;
use App\Models\Chat\Conversation;

class Info extends ModalComponent
{
    use Widget;

    #[Locked]
    public Conversation $conversation;

    public static function closeModalOnEscapeIsForceful(): bool
    {
        return false;
    }

    /**
     * -----------------------------
     * Delete Chat
     * */
    public function deleteChat()
    {
        abort_unless(auth()->check(), 401);

        abort_unless(auth()->user()->belongsToConversation($this->conversation), 403);
        abort_unless($this->conversation->isSelf() || $this->conversation->isPrivate(), 403, 'This operation is not available for Groups.');

        // delete conversation
        $this->conversation->deleteFor(auth()->user());

        // redirect to chats page pr
        // Dispatach event instead if isWidget
        // handle widget termination
        $this->handleComponentTermination(
            redirectRoute: route(ChatHelper::indexRouteName()),
            events: [
                'close-chat',
                Chats::class => ['chat-deleted',  [$this->conversation->id]],
            ]
        );

    }

    public function placeholder()
    {
        return <<<'HTML'
        <div>
            <!-- Loading spinner... -->
            <x-chat::loading-spin class="m-auto" />
        </div>
        HTML;
    }

    public function mount()
    {

        abort_if(empty($this->conversation), 404);

        abort_unless(auth()->check(), 401);
        abort_unless(auth()->user()->belongsToConversation($this->conversation), 403);

        abort_if($this->conversation->isGroup(), 403, __('chat::chat.info.messages.invalid_conversation_type_error'));

        // load participants
        $this->conversation->load('participants.participantable');

    }

    public function render()
    {

        $receiver = $this->conversation->peerParticipant(auth()->user())?->participantable;

        // Pass data to the view
        return view('chat::livewire.chat.info', [
            'receiver' => $receiver,
            'cover_url' => $receiver ? AvatarHelper::getUserAvatarUrl($receiver) : null,
        ]);
    }
}
