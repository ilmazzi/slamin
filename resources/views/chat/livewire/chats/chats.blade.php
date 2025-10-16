@use('App\Helpers\Chat\ChatHelper')

<div x-data="{ selectedConversationId: '{{ request()->conversation ?? $selectedConversationId }}' }"
    x-on:open-chat.window="selectedConversationId= $event.detail.conversation; $wire.selectedConversationId= $event.detail.conversation;"
    x-init=" setTimeout(() => {
         conversationElement = document.getElementById('conversation-' + selectedConversationId);
    
         // Scroll to the conversation element
         if (conversationElement) {
             conversationElement.scrollIntoView({ behavior: 'smooth' });
         }
     }, 200);"
    class="d-flex flex-column chat-transition h-100 overflow-hidden w-100" style="background: var(--chat-bg-primary);">

    @php
        /* Show header if any of these conditions are true  */
        $showHeader = $showNewChatModalButton || $allowChatsSearch || $showHomeRouteButton || !empty($title);
    @endphp

    {{-- include header --}}
    @includeWhen($showHeader, 'chat::livewire.chats.partials.header')

    <main x-data
        @scroll.self.debounce="
           {{-- Detect when scrolled to the bottom --}}
            // Calculate scroll values
            scrollTop = $el.scrollTop;
            scrollHeight = $el.scrollHeight;
            clientHeight = $el.clientHeight;

            // Check if the user is at the bottom of the scrollable element
            if ((scrollTop + clientHeight) >= (scrollHeight - 1) && $wire.canLoadMore) {
                // Trigger load more if we're at the bottom
                await $nextTick();
                $wire.loadMore();
            }
            "
        class="overflow-auto py-2 flex-grow-1 h-100 position-relative" style="contain:content">

        @if (count($conversations) > 0)
            {{-- include list item --}}
            @include('chat::livewire.chats.partials.list')

            {{-- include load more if true --}}
            @includeWhen($canLoadMore, 'chat::livewire.chats.partials.load-more-button')
        @else
            <div class="w-100 d-flex align-items-center h-100 justify-content-center">
                <h6 class="fw-bold" style="color: var(--chat-text);">{{ __('chat::chats.labels.no_conversations_yet')  }}</h6>
            </div>
        @endif
    </main>

    {{-- Modal component for new chat/group --}}
    <livewire:chat.modal />

</div>
