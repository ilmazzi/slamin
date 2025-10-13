<div class="chat-container d-flex flex-column" 
     x-data="chatRoom()" 
     x-init="init()"
     wire:key="chat-room-{{ $roomId }}">
     
    <!-- Chat Header -->
    <div class="chat-header border-bottom bg-white">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center flex-grow-1">
                @if($this->otherUser)
                    <img src="{{ $this->otherUser->avatar_url }}" 
                         alt="{{ $this->otherUser->getDisplayName() }}" 
                         class="chat-avatar rounded-circle me-2">
                    <div class="flex-grow-1 overflow-hidden">
                        <h6 class="mb-0 text-truncate">{{ $this->chatTitle }}</h6>
                        <small class="text-muted" x-show="onlineUsers.includes({{ $this->otherUser->id }})">
                            <i class="ph ph-circle-fill text-success" style="font-size: 8px;"></i>
                            {{ __('chat_general.online_status') }}
                        </small>
                        <small class="text-muted" x-show="!onlineUsers.includes({{ $this->otherUser->id }})">
                            {{ __('chat_general.offline') }}
                        </small>
                    </div>
                @else
                    <div class="flex-grow-1">
                        <h6 class="mb-0">{{ $this->chatTitle }}</h6>
                        <small class="text-muted" x-show="onlineUsers.length > 0">
                            <span x-text="onlineUsers.length"></span> online
                        </small>
                    </div>
                @endif
            </div>
            <a href="{{ route('chat.livewire.index') }}" class="btn btn-sm btn-light" wire:navigate>
                <i class="ph ph-x"></i>
            </a>
        </div>
    </div>

    <!-- Messages Container -->
    <div class="messages-container flex-grow-1 overflow-auto" 
         x-ref="messagesContainer"
         x-on:scroll="handleScroll()">
         
        @forelse($messages as $message)
            <div class="message-wrapper" 
                 wire:key="message-{{ $message->id }}">
                
                <!-- Own Messages (Right) -->
                @if($message->user_id === auth()->id())
                    <div class="message-item message-own">
                        <div class="message-content">
                            <div class="message-bubble message-bubble-own">
                                {!! nl2br(e($message->content)) !!}
                            </div>
                            <div class="message-time">
                                <i class="ph ph-checks"></i>
                                {{ $message->created_at->format('H:i') }}
                            </div>
                        </div>
                        <img src="{{ $message->sender->avatar_url }}" 
                             alt="{{ $message->sender->getDisplayName() }}" 
                             class="message-avatar">
                    </div>
                @else
                    <!-- Other Messages (Left) -->
                    <div class="message-item message-other">
                        <img src="{{ $message->sender->avatar_url }}" 
                             alt="{{ $message->sender->getDisplayName() }}" 
                             class="message-avatar">
                        <div class="message-content">
                            <div class="message-bubble message-bubble-other">
                                {!! nl2br(e($message->content)) !!}
                            </div>
                            <div class="message-time">
                                {{ $message->created_at->format('H:i') }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @empty
            <div class="no-messages">
                <i class="ph ph-chat-circle-dots"></i>
                <p>{{ __('chat_general.no_messages') }}</p>
                <small>{{ __('chat_general.type_message_placeholder') }}</small>
            </div>
        @endforelse

        <!-- Typing Indicator -->
        <div x-show="typingUsers.length > 0" 
             x-transition
             class="message-wrapper">
            <div class="message-item message-other">
                <div class="typing-indicator">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Message Input -->
    <div class="message-input-container border-top bg-white">
        <form wire:submit.prevent="sendMessage" 
              x-data="messageInput()" 
              @keydown="handleKeyDown($event)"
              @keyup="handleKeyUp($event)"
              class="message-input-form">
            
            <div class="d-flex align-items-end gap-2">
                <!-- Emoji Picker Button -->
                <button type="button" 
                        class="btn btn-light btn-sm"
                        @click="toggleEmojiPicker()"
                        title="{{ __('chat_general.add_emoji') }}">
                    <i class="ph ph-smiley"></i>
                </button>

                <!-- Message Input -->
                <div class="flex-grow-1">
                    <textarea 
                        class="form-control message-textarea" 
                        placeholder="{{ __('chat_general.type_message_placeholder') }}"
                        wire:model.live="newMessage"
                        x-ref="messageInput"
                        @input="handleInput()"
                        maxlength="1000"
                        rows="1"></textarea>
                </div>

                <!-- Send Button -->
                <button type="submit" 
                        class="btn btn-primary btn-send"
                        :disabled="!$wire.newMessage || !$wire.newMessage.trim() || sending">
                    <span x-show="!sending">
                        <i class="ph ph-paper-plane-tilt"></i>
                    </span>
                    <span x-show="sending" class="spinner-border spinner-border-sm"></span>
                </button>
            </div>
        </form>

        <!-- Emoji Picker -->
        <livewire:chat.emoji-picker />
    </div>
</div>

@push('styles')
<style>
/* Mobile-First Chat Layout */
.chat-container {
    height: 100vh;
    max-height: 100vh;
    background: #f8f9fa;
}

/* Header */
.chat-header {
    padding: 12px 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.chat-avatar {
    width: 36px;
    height: 36px;
    object-fit: cover;
}

.chat-header h6 {
    font-size: 0.95rem;
    font-weight: 600;
    color: #212529;
}

.chat-header small {
    font-size: 0.75rem;
    display: block;
}

/* Messages Container */
.messages-container {
    padding: 16px;
    background: #f8f9fa;
    scroll-behavior: smooth;
    overflow-y: auto;
}

.message-wrapper {
    margin-bottom: 12px;
}

.message-item {
    display: flex;
    gap: 8px;
    max-width: 100%;
}

.message-item.message-own {
    flex-direction: row-reverse;
}

.message-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}

.message-content {
    display: flex;
    flex-direction: column;
    max-width: calc(100% - 40px);
}

.message-item.message-own .message-content {
    align-items: flex-end;
}

.message-bubble {
    padding: 10px 14px;
    border-radius: 16px;
    font-size: 0.9rem;
    line-height: 1.4;
    word-wrap: break-word;
    max-width: 100%;
}

.message-bubble-own {
    background: #0d6efd;
    color: white;
    border-bottom-right-radius: 4px;
}

.message-bubble-other {
    background: white;
    color: #212529;
    border: 1px solid #e9ecef;
    border-bottom-left-radius: 4px;
}

.message-time {
    font-size: 0.7rem;
    color: #6c757d;
    margin-top: 4px;
    padding: 0 4px;
}

.message-time i {
    font-size: 0.7rem;
}

/* No Messages */
.no-messages {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 48px 24px;
    text-align: center;
    color: #6c757d;
}

.no-messages i {
    font-size: 4rem;
    margin-bottom: 16px;
    opacity: 0.3;
}

.no-messages p {
    font-size: 1rem;
    font-weight: 500;
    margin-bottom: 4px;
}

.no-messages small {
    font-size: 0.85rem;
}

/* Typing Indicator */
.typing-indicator {
    display: flex;
    gap: 4px;
    padding: 12px 16px;
    background: white;
    border: 1px solid #e9ecef;
    border-radius: 16px;
    border-bottom-left-radius: 4px;
}

.typing-indicator span {
    width: 8px;
    height: 8px;
    background: #6c757d;
    border-radius: 50%;
    animation: typing 1.4s infinite ease-in-out;
}

.typing-indicator span:nth-child(1) { animation-delay: -0.32s; }
.typing-indicator span:nth-child(2) { animation-delay: -0.16s; }

@keyframes typing {
    0%, 80%, 100% {
        transform: scale(0.8);
        opacity: 0.4;
    }
    40% {
        transform: scale(1.2);
        opacity: 1;
    }
}

/* Message Input */
.message-input-container {
    padding: 12px 16px;
    background: white;
    box-shadow: 0 -1px 3px rgba(0, 0, 0, 0.1);
}

.message-input-form {
    width: 100%;
}

.message-textarea {
    border-radius: 20px;
    border: 1px solid #e9ecef;
    padding: 10px 16px;
    font-size: 0.9rem;
    resize: none;
    max-height: 120px;
    min-height: 40px;
}

.message-textarea:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
}

.btn-send {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-send i {
    font-size: 1.1rem;
}

/* Tablet and Desktop */
@media (min-width: 768px) {
    .chat-avatar {
        width: 40px;
        height: 40px;
    }

    .message-avatar {
        width: 36px;
        height: 36px;
    }

    .message-bubble {
        font-size: 0.95rem;
        max-width: 70%;
    }

    .messages-container {
        padding: 20px 24px;
    }

    .message-input-container {
        padding: 16px 24px;
    }
}

@media (min-width: 1024px) {
    .message-bubble {
        max-width: 60%;
    }
}
</style>
@endpush

@push('scripts')
<script>
function chatRoom() {
    return {
        onlineUsers: [],
        typingUsers: [],

        init() {
            this.listenToEcho();
            this.scrollToBottom();
            
            // Auto-scroll on new messages
            Livewire.on('messageReceived', () => {
                this.$nextTick(() => this.scrollToBottom());
            });
        },

        listenToEcho() {
            if (typeof window.Echo !== 'undefined' && window.Echo) {
                try {
                    // Listen for new messages
                    window.Echo.private(`chat.room.${@this.roomId}`)
                        .listen('.chat.message', (e) => {
                            @this.call('refreshMessages');
                            this.scrollToBottom();
                        });

                    // Listen for typing events
                    window.Echo.private(`chat.room.${@this.roomId}`)
                        .listen('.user.typing', (e) => {
                            if (!this.typingUsers.includes(e.user_id)) {
                                this.typingUsers.push(e.user_id);
                            }
                        })
                        .listen('.user.stopped.typing', (e) => {
                            this.typingUsers = this.typingUsers.filter(id => id !== e.user_id);
                        });

                    // Listen for presence updates
                    window.Echo.join(`chat.room.${@this.roomId}`)
                        .here((users) => {
                            this.onlineUsers = users.map(user => user.id);
                        })
                        .joining((user) => {
                            if (!this.onlineUsers.includes(user.id)) {
                                this.onlineUsers.push(user.id);
                            }
                        })
                        .leaving((user) => {
                            this.onlineUsers = this.onlineUsers.filter(id => id !== user.id);
                        });
                } catch (error) {
                    console.warn('Echo initialization failed:', error);
                    console.info('Chat will work without real-time features.');
                }
            } else {
                console.info('Echo not available. Using polling fallback.');
            }
        },

        handleScroll() {
            // Placeholder for future scroll handling
        },

        scrollToBottom() {
            this.$nextTick(() => {
                const container = this.$refs.messagesContainer;
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            });
        }
    }
}

function messageInput() {
    return {
        sending: false,
        typingTimeout: null,

        handleKeyDown(event) {
            if (event.key === 'Enter' && !event.shiftKey) {
                event.preventDefault();
                this.sendMessage();
            }
        },

        handleKeyUp(event) {
            // Handle typing indicators
            if (this.$wire.newMessage && this.$wire.newMessage.trim()) {
                @this.startTyping();
                clearTimeout(this.typingTimeout);
                this.typingTimeout = setTimeout(() => {
                    @this.stopTyping();
                }, 1000);
            } else {
                @this.stopTyping();
            }
        },

        handleInput() {
            // Auto-resize textarea
            const textarea = this.$refs.messageInput;
            textarea.style.height = 'auto';
            textarea.style.height = Math.min(textarea.scrollHeight, 120) + 'px';
        },

        sendMessage() {
            if (!this.$wire.newMessage || !this.$wire.newMessage.trim() || this.sending) return;
            
            this.sending = true;
            @this.sendMessage().then(() => {
                this.$refs.messageInput.value = '';
                this.$refs.messageInput.style.height = 'auto';
                this.sending = false;
                @this.stopTyping();
            }).catch(() => {
                this.sending = false;
            });
        },

        toggleEmojiPicker() {
            this.$dispatch('toggle-emoji-picker');
        }
    }
}
</script>
@endpush
