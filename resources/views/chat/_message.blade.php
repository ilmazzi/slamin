@if($message['is_own'])
<div class="position-relative">
    <div class="chat-box-right">
        <div class="position-relative">
            <p class="chat-text">{{ $message['content'] }}</p>

            <!-- Reazioni ai messaggi propri -->
            <div class="message-reactions">
                <div class="reactions-display" data-reactions-display="{{ $message['id'] }}">
                    @if(isset($message['reactions']) && !empty($message['reactions']))
                        @foreach($message['reactions'] as $reaction)
                            @php
                                $currentUserId = auth()->id();
                                $hasUserReacted = $reaction['users'] && collect($reaction['users'])->contains('id', $currentUserId);
                                $reactionClass = $hasUserReacted ? 'reaction-item user-reaction' : 'reaction-item';
                            @endphp
                            <div class="{{ $reactionClass }}" title="{{ implode(', ', array_column($reaction['users'], 'name')) }}" onclick="toggleReaction('{{ $message['id'] }}', '{{ $reaction['emoji'] }}')">
                                <span class="reaction-emoji">{{ $reaction['emoji'] }}</span>
                                <span class="reaction-count">{{ $reaction['count'] }}</span>
                            </div>
                        @endforeach
                    @endif
                </div>
                <button class="btn btn-sm reaction-btn" onclick="toggleReactionPicker({{ $message['id'] }})" title="{{ __('chat.add_reaction') }}">
                    <i class="ti ti-dots-vertical"></i>
                </button>

                <!-- Emoji Picker -->
                <div class="emoji-picker d-none" id="emoji-picker-{{ $message['id'] }}">
                    <div class="emoji-picker-header">
                        <span>{{ __('chat.react') }}</span>
                        <button type="button" class="btn-close btn-close-sm" onclick="hideReactionPicker({{ $message['id'] }})"></button>
                    </div>
                    <div class="emoji-grid">
                        <button class="emoji-btn" onclick="addReaction({{ $message['id'] }}, '👍')">👍</button>
                        <button class="emoji-btn" onclick="addReaction({{ $message['id'] }}, '❤️')">❤️</button>
                        <button class="emoji-btn" onclick="addReaction({{ $message['id'] }}, '😊')">😊</button>
                        <button class="emoji-btn" onclick="addReaction({{ $message['id'] }}, '😂')">😂</button>
                        <button class="emoji-btn" onclick="addReaction({{ $message['id'] }}, '😮')">😮</button>
                        <button class="emoji-btn" onclick="addReaction({{ $message['id'] }}, '😢')">😢</button>
                        <button class="emoji-btn" onclick="addReaction({{ $message['id'] }}, '👏')">👏</button>
                        <button class="emoji-btn" onclick="addReaction({{ $message['id'] }}, '🎉')">🎉</button>
                    </div>
                </div>
            </div>
        </div>
        <p class="text-muted"><i class="ti ti-checks text-primary"></i> {{ $message['time'] }}</p>
    </div>
    <div class="chatdp h-45 w-45 b-r-50 position-absolute end-0 top-0 bg-danger">
        @php $user = \App\Models\User::find($message['sender_id']); @endphp
        <span class="h-45 w-45 d-flex-center b-r-10 position-relative m-auto"
              data-user-id="{{ $user->id }}">
            <img alt="avatar" class="img-fluid b-r-10"
                 src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($user) }}">
            <span class="position-absolute top-0 end-0 p-1 border border-light rounded-circle bg-secondary"
                  data-presence-dot></span>
        </span>
    </div>
</div>
@else
<div class="position-relative">
    <div class="chatdp h-45 w-45 b-r-50 position-absolute start-0 bg-light">
        @php $user = \App\Models\User::find($message['sender_id']); @endphp
        <span class="h-45 w-45 d-flex-center b-r-10 position-relative m-auto"
              data-user-id="{{ $user->id }}">
            <img alt="avatar" class="img-fluid b-r-10"
                 src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($user) }}">
            <span class="position-absolute top-0 end-0 p-1 border border-light rounded-circle bg-secondary"
                  data-presence-dot></span>
        </span>
    </div>
    <div class="chat-box">
        <div class="position-relative">
            <p class="chat-text">{{ $message['content'] }}</p>

            <!-- Reazioni ai messaggi degli altri -->
            <div class="message-reactions">
                <div class="reactions-display" data-reactions-display="{{ $message['id'] }}">
                    @if(isset($message['reactions']) && !empty($message['reactions']))
                        @foreach($message['reactions'] as $reaction)
                            @php
                                $currentUserId = auth()->id();
                                $hasUserReacted = $reaction['users'] && collect($reaction['users'])->contains('id', $currentUserId);
                                $reactionClass = $hasUserReacted ? 'reaction-item user-reaction' : 'reaction-item';
                            @endphp
                            <div class="{{ $reactionClass }}" title="{{ implode(', ', array_column($reaction['users'], 'name')) }}" onclick="toggleReaction('{{ $message['id'] }}', '{{ $reaction['emoji'] }}')">
                                <span class="reaction-emoji">{{ $reaction['emoji'] }}</span>
                                <span class="reaction-count">{{ $reaction['count'] }}</span>
                            </div>
                        @endforeach
                    @endif
                </div>
                <button class="btn btn-sm reaction-btn" onclick="toggleReactionPicker({{ $message['id'] }})" title="{{ __('chat.add_reaction') }}">
                    <i class="ti ti-dots-vertical"></i>
                </button>

                <!-- Emoji Picker -->
                <div class="emoji-picker d-none" id="emoji-picker-{{ $message['id'] }}">
                    <div class="emoji-picker-header">
                        <span>{{ __('chat.react') }}</span>
                        <button type="button" class="btn-close btn-close-sm" onclick="hideReactionPicker({{ $message['id'] }})"></button>
                    </div>
                    <div class="emoji-grid">
                        <button class="emoji-btn" onclick="addReaction({{ $message['id'] }}, '👍')">👍</button>
                        <button class="emoji-btn" onclick="addReaction({{ $message['id'] }}, '❤️')">❤️</button>
                        <button class="emoji-btn" onclick="addReaction({{ $message['id'] }}, '😊')">😊</button>
                        <button class="emoji-btn" onclick="addReaction({{ $message['id'] }}, '😂')">😂</button>
                        <button class="emoji-btn" onclick="addReaction({{ $message['id'] }}, '😮')">😮</button>
                        <button class="emoji-btn" onclick="addReaction({{ $message['id'] }}, '😢')">😢</button>
                        <button class="emoji-btn" onclick="addReaction({{ $message['id'] }}, '👏')">👏</button>
                        <button class="emoji-btn" onclick="addReaction({{ $message['id'] }}, '🎉')">🎉</button>
                    </div>
                </div>
            </div>
        </div>
        <p class="text-muted"><i class="ti ti-checks text-primary"></i> {{ $message['time'] }}</p>
    </div>
</div>
@endif

<style>
/* Stili per le reazioni ai messaggi - Posizionamento WhatsApp */
.message-reactions {
    position: absolute;
    bottom: -8px;
    left: 0;
    background: transparent;
    border: none;
    border-radius: 16px;
    padding: 4px 8px;
    box-shadow: none;
    z-index: 10;
    white-space: nowrap;
    display: flex;
    align-items: center;
    gap: 4px;
}

/* Posizionamento per messaggi propri (destra) */
.chat-box-right .message-reactions {
    right: 0;
    left: auto;
}

/* Posizionamento per messaggi degli altri (sinistra) */
.chat-box .message-reactions {
    left: 0;
    right: auto;
}

.reaction-item {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: transparent;
    border: none;
    border-radius: 12px;
    padding: 2px 6px;
    font-size: 11px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.reaction-item:hover {
    background: transparent;
    transform: scale(1.05);
}

.reaction-item.user-reaction {
    background: rgba(13, 110, 253, 0.1);
    border-color: rgba(13, 110, 253, 0.3);
}

.reaction-emoji {
    font-size: 14px;
}

.reaction-count {
    font-weight: 500;
    color: #6c757d;
}

.reaction-btn {
    padding: 3px 6px;
    font-size: 11px;
    border-radius: 50%;
    background: transparent;
    border: none;
    transition: all 0.2s ease;
}

.reaction-btn:hover {
    background: transparent;
    transform: scale(1.05);
}

/* Emoji Picker */
.emoji-picker {
    position: absolute;
    bottom: 100%;
    left: 0;
    background: white;
    border: 1px solid rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    z-index: 1000;
    min-width: 200px;
    margin-bottom: 8px;
}

.emoji-picker-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 12px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    font-size: 12px;
    font-weight: 500;
}

.emoji-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 4px;
    padding: 8px;
}

.emoji-btn {
    padding: 8px;
    font-size: 18px;
    border: none;
    background: transparent;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.emoji-btn:hover {
    background: rgba(0, 0, 0, 0.05);
}

.emoji-btn:active {
    transform: scale(0.95);
}
</style>
