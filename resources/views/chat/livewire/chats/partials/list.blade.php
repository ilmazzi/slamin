
@use('App\Helpers\Chat\ChatHelper')
@use('App\Helpers\AvatarHelper')

<ul wire:loading.delay.long.remove wire:target="search" class="chat-list">
    @foreach ($conversations as $key=> $conversation)
    @php
    $group = $conversation->isGroup() ? $conversation->group : null;
    $receiver = $conversation->isGroup() ? null : ($conversation->isPrivate() ? $conversation->peer_participant?->participantable : $this->auth);
    $lastMessage = $conversation->lastMessage;
    $isReadByAuth = $conversation?->readBy($conversation->auth_participant??$this->auth) || $selectedConversationId == $conversation->id;
    $belongsToAuth = $lastMessage?->belongsToAuth();
    @endphp

    <li x-data="{
        conversationID: @js($conversation->id),
        showUnreadStatus: @js(!$isReadByAuth),
        handleChatOpened(event) {
            if (event.detail.conversation== this.conversationID) {
                this.showUnreadStatus= false;
            }
            $wire.selectedConversationId= event.detail.conversation;
        },
        handleChatClosed(event) {
                $wire.selectedConversationId = null;
                selectedConversationId = null;
        },
        handleOpenChat(event) {
            if (this.showUnreadStatus==  event.detail.conversation== this.conversationID) {
                this.showUnreadStatus= false;
            }
    }
    }"  

    id="conversation-{{ $conversation->id }}" 
        wire:key="conversation-em-{{ $conversation->id }}-{{ $conversation->updated_at->timestamp }}"
        x-on:chat-opened.window="handleChatOpened($event)"
        x-on:chat-closed.window="handleChatClosed($event)">
        
        <a @if ($widget) tabindex="0" 
        role="button" 
        dusk="openChatWidgetButton"
        @click="$dispatch('open-chat',{conversation:@js($conversation->id)})"
        @keydown.enter="$dispatch('open-chat',{conversation:@js($conversation->id)})"
        @else
        wire:navigate href="{{ route(ChatHelper::viewRouteName(), $conversation->id) }}" @endif
            class="chat-item"
            :class="$wire.selectedConversationId == conversationID && 'active'"
            :style="$wire.selectedConversationId == conversationID ? 'border-right-color: var(--chat-primary);' : ''">

            <div class="chat-item-avatar">
                <x-chat::avatar disappearing="{{ $conversation->hasDisappearingTurnedOn() }}"
                    group="{{ $conversation->isGroup() }}"
                    :src="$group ? $group?->cover_url : ($receiver ? AvatarHelper::getUserAvatarUrl($receiver) : null)" class="chat-avatar w-12 h-12" />
            </div>

            <aside class="chat-item-content">
                <div class="chat-item-info">

                    {{-- name --}}
                    <div class="chat-item-name">
                        <h6>
                            {{ $group ? $group?->name : $receiver?->display_name }}
                        </h6>

                        @if ($conversation->isSelfConversation())
                            <span class="fw-medium" style="color: var(--chat-text);">({{__('chat::chats.labels.you')  }})</span>
                        @endif

                    </div>

                    {{-- Message body --}}
                    @if ($lastMessage != null)
                        @include('chat::livewire.chats.partials.message-body')
                    @endif

                </div>

                {{-- Read status --}}
                {{-- Only show if AUTH is NOT owner of message --}}
                @if ($lastMessage != null && !$lastMessage?->ownedBy($this->auth) && !$isReadByAuth)
                    <div x-show="showUnreadStatus" dusk="unreadMessagesDot" class="chat-item-time">
                        {{-- Dots icon --}}
                        <span dusk="unreadDotItem" class="sr-only">unread dot</span>
                        <svg class="chat-unread-dot" xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                            fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 9.5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z" />
                        </svg>

                    </div>
                @endif


            </aside>
        </a>

    </li>
    @endforeach

</ul>
