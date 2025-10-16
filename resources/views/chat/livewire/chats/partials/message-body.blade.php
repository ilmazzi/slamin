<div class="chat-item-message">

    <div class="chat-item-message-content">
        {{-- Only show if AUTH is owner of message --}}
        @if ($belongsToAuth)
            <span class="fw-bold" style="font-size: 0.75rem; color: var(--chat-text);">
                @lang('chat::chats.labels.you'):
            </span>
        @elseif(!$belongsToAuth && $group !== null)
            <span class="fw-bold" style="font-size: 0.75rem; color: var(--chat-text);">
                {{ $lastMessage->sendable?->display_name }}:
            </span>
        @endif

        <p class="chat-item-message-text @if(!$isReadByAuth && !$lastMessage?->ownedBy($this->auth)) fw-semibold @endif"
           style="font-size: 0.875rem; color: var(--chat-text-secondary);">
            {{ $lastMessage->body != '' ? $lastMessage->body : ($lastMessage->isAttachment() ? '📎 '.__('chat::chats.labels.attachment') : '') }}
        </p>
    </div>

    <span class="chat-item-message-time">
        @if ($lastMessage->created_at->diffInMinutes(now()) < 1)
          @lang('chat::chats.labels.now')
        @else
            {{ $lastMessage->created_at->shortAbsoluteDiffForHumans() }}
        @endif
    </span>

</div>
