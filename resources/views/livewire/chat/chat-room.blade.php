<div class="chat-container h-100 d-flex flex-column" 
     x-data="chatRoom()" 
     x-init="init()"
     wire:key="chat-room-{{ $roomId }}">
     
    <!-- Chat Header -->
    <div class="chat-header p-3 border-bottom bg-light">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 me-3">{{ $room->name ?? __('chat.private_chat') }}</h5>
                <span class="badge bg-success" x-show="onlineUsers.length > 0" x-text="onlineUsers.length + ' online'"></span>
            </div>
            <div class="d-flex align-items-center gap-2">
                <!-- Online Users -->
                <div class="online-users d-flex gap-1">
                    @foreach($users as $user)
                        <div class="position-relative" 
                             wire:key="user-{{ $user->id }}"
                             x-show="onlineUsers.includes({{ $user->id }})">
                            <img src="{{ $user->avatar_url }}" 
                                 alt="{{ $user->name }}" 
                                 class="rounded-circle" 
                                 style="width: 32px; height: 32px;"
                                 data-bs-toggle="tooltip" 
                                 title="{{ $user->name }}">
                            <span class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-white" 
                                  style="width: 8px; height: 8px;"></span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Messages Container -->
    <div class="messages-container flex-grow-1 p-3 overflow-auto" 
         style="max-height: calc(100vh - 200px);"
         x-ref="messagesContainer"
         x-on:scroll="handleScroll()">
         
        @foreach($messages as $message)
            <div class="message-item mb-3" 
                 wire:key="message-{{ $message->id }}"
                 x-data="{ message: @js($message) }"
                 x-init="animateMessage()">
                
                <!-- Own Messages (Right) -->
                @if($message->user_id === auth()->id())
                    <div class="position-relative">
                        <div class="chat-box-right d-flex flex-column align-items-end" style="margin-right: 60px;">
                            <div class="chat-text bg-primary text-white p-3 rounded-3 mb-2 shadow-sm" style="max-width: 85%;">
                                {!! nl2br(e($message->content)) !!}
                            </div>
                            <p class="text-muted f-s-12">
                                <i class="ti ti-checks text-primary"></i> 
                                {{ $message->created_at->format('H:i') }}
                            </p>
                        </div>
                        <div class="chatdp h-45 w-45 b-r-50 position-absolute end-0 top-0 bg-danger">
                            <span class="h-45 w-45 d-flex-center b-r-10 position-relative m-auto">
                                <img alt="avatar" class="img-fluid b-r-10" src="{{ $message->sender->avatar_url }}">
                                <span class="position-absolute top-0 end-0 p-1 border border-light rounded-circle bg-secondary" 
                                      x-show="onlineUsers.includes({{ $message->user_id }})"></span>
                            </span>
                        </div>
                    </div>
                @else
                    <!-- Other Messages (Left) -->
                    <div class="position-relative">
                        <div class="chatdp h-45 w-45 b-r-50 position-absolute start-0 bg-light">
                            <span class="h-45 w-45 d-flex-center b-r-10 position-relative m-auto">
                                <img alt="avatar" class="img-fluid b-r-10" src="{{ $message->sender->avatar_url }}">
                                <span class="position-absolute top-0 end-0 p-1 border border-light rounded-circle bg-secondary" 
                                      x-show="onlineUsers.includes({{ $message->user_id }})"></span>
                            </span>
                        </div>
                        <div class="chat-box d-flex flex-column align-items-start" style="margin-left: 60px;">
                            <div class="chat-text bg-light text-dark p-3 rounded-3 mb-2 shadow-sm border" style="max-width: 85%;">
                                {!! nl2br(e($message->content)) !!}
                            </div>
                            <p class="text-muted f-s-12">
                                <i class="ti ti-checks text-primary"></i> 
                                {{ $message->created_at->format('H:i') }}
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        @endforeach

        <!-- Typing Indicator -->
        <div x-show="typingUsers.length > 0" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             class="typing-indicator mb-3">
            <div class="chat-box d-flex flex-column align-items-start" style="margin-left: 60px;">
                <div class="chat-text bg-light text-muted p-2 rounded-3 border" style="max-width: 200px;">
                    <div class="d-flex align-items-center">
                        <div class="typing-dots me-2">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        <span class="f-s-12" x-text="typingText"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Message Input -->
    <div class="message-input p-3 border-top bg-light">
        <form wire:submit.prevent="sendMessage" 
              x-data="messageInput()" 
              @keydown="handleKeyDown($event)"
              @keyup="handleKeyUp($event)">
            
            <div class="input-group">
                <!-- Emoji Picker Button -->
                <button type="button" 
                        class="btn btn-outline-secondary"
                        @click="toggleEmojiPicker()"
                        data-bs-toggle="tooltip" 
                        title="{{ __('chat.add_emoji') }}">
                    <i class="ph ph-smiley"></i>
                </button>

                <!-- Message Input -->
                <input type="text" 
                       class="form-control" 
                       placeholder="{{ __('chat.type_message') }}"
                       wire:model.live="newMessage"
                       x-ref="messageInput"
                       @input="handleInput()"
                       maxlength="1000">

                <!-- Send Button -->
                <button type="submit" 
                        class="btn btn-primary"
                        :disabled="!message.trim() || sending">
                    <span x-show="!sending">
                        <i class="ti ti-send"></i>
                    </span>
                    <span x-show="sending" class="spinner-border spinner-border-sm"></span>
                </button>
            </div>

            <!-- Character Counter -->
            <div class="text-end mt-1">
                <small class="text-muted" x-text="message.length + '/1000'"></small>
            </div>
        </form>

        <!-- Emoji Picker -->
        <livewire:chat.emoji-picker />
    </div>
</div>

<style>
.chat-container {
    height: 100vh;
    max-height: 100vh;
}

.messages-container {
    scroll-behavior: smooth;
}

.message-item {
    animation: fadeInUp 0.3s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.typing-dots {
    display: inline-flex;
    gap: 2px;
}

.typing-dots span {
    width: 4px;
    height: 4px;
    background-color: #6c757d;
    border-radius: 50%;
    animation: typing 1.4s infinite ease-in-out;
}

.typing-dots span:nth-child(1) { animation-delay: -0.32s; }
.typing-dots span:nth-child(2) { animation-delay: -0.16s; }

@keyframes typing {
    0%, 80%, 100% {
        transform: scale(0.8);
        opacity: 0.5;
    }
    40% {
        transform: scale(1);
        opacity: 1;
    }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .chat-box-right {
        margin-right: 50px !important;
    }
    
    .chat-box {
        margin-left: 50px !important;
    }
    
    .chatdp {
        width: 40px !important;
        height: 40px !important;
    }
}
</style>

<script>
function chatRoom() {
    return {
        onlineUsers: [],
        typingUsers: [],
        isAtBottom: true,
        typingTimeout: null,

        init() {
            this.listenToEcho();
            this.scrollToBottom();
            
            // Initialize tooltips
            if (typeof bootstrap !== 'undefined') {
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }
        },

        listenToEcho() {
            if (window.Echo) {
                // Listen for new messages
                window.Echo.private(`chat.room.${@this.roomId}`)
                    .listen('.chat.message', (e) => {
                        @this.dispatch('messageReceived', e);
                    });

                // Listen for typing events
                window.Echo.private(`chat.room.${@this.roomId}`)
                    .listen('.user.typing', (e) => {
                        this.handleUserTyping(e.user_id);
                    });

                window.Echo.private(`chat.room.${@this.roomId}`)
                    .listen('.user.stopped.typing', (e) => {
                        this.handleUserStoppedTyping(e.user_id);
                    });

                // Listen for presence updates
                window.Echo.join(`chat.room.${@this.roomId}`)
                    .here((users) => {
                        this.onlineUsers = users.map(user => user.id);
                    })
                    .joining((user) => {
                        this.onlineUsers.push(user.id);
                    })
                    .leaving((user) => {
                        this.onlineUsers = this.onlineUsers.filter(id => id !== user.id);
                    });
            }
        },

        handleScroll() {
            const container = this.$refs.messagesContainer;
            const scrollTop = container.scrollTop;
            const scrollHeight = container.scrollHeight;
            const clientHeight = container.clientHeight;
            
            this.isAtBottom = (scrollHeight - scrollTop - clientHeight) < 50;
        },

        scrollToBottom() {
            this.$nextTick(() => {
                const container = this.$refs.messagesContainer;
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            });
        },

        animateMessage() {
            // Animation is handled by CSS
        },

        handleUserTyping(userId) {
            if (!this.typingUsers.includes(userId)) {
                this.typingUsers.push(userId);
            }
        },

        handleUserStoppedTyping(userId) {
            this.typingUsers = this.typingUsers.filter(id => id !== userId);
        },

        get typingText() {
            if (this.typingUsers.length === 0) return '';
            if (this.typingUsers.length === 1) {
                const user = @this.users.find(u => u.id === this.typingUsers[0]);
                return user ? `${user.name} sta scrivendo...` : 'Qualcuno sta scrivendo...';
            }
            return `${this.typingUsers.length} persone stanno scrivendo...`;
        }
    }
}

function messageInput() {
    return {
        message: '',
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
            if (this.message.trim()) {
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
            this.message = this.$refs.messageInput.value;
        },

        sendMessage() {
            if (!this.message.trim() || this.sending) return;
            
            this.sending = true;
            @this.sendMessage().then(() => {
                this.message = '';
                this.$refs.messageInput.value = '';
                this.sending = false;
                @this.stopTyping();
            }).catch(() => {
                this.sending = false;
            });
        },

        toggleEmojiPicker() {
            // This will be handled by the emoji picker component
            this.$dispatch('toggle-emoji-picker');
        }
    }
}
</script>
