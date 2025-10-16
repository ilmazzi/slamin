<div class="d-flex position-relative" style="height: calc(100vh - 100px);">
    <!-- Sidebar -->
    <div class="chat-sidebar border-end p-0 {{ $selectedConversationId ? 'sidebar-hidden' : '' }}" style="background-color: #ffffff;">
        <!-- Header -->
        <div class="p-3 border-bottom bg-white">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0 fw-bold text-dark">Messaggi</h5>
                    <button class="btn btn-link p-0 text-primary" data-bs-toggle="modal" data-bs-target="#newChatModal" style="font-size: 1.5rem;">
                        <i class="ph ph-chat-circle-dots"></i>
                    </button>
                </div>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="ph ph-magnifying-glass text-muted"></i>
                    </span>
                    <input type="text" class="form-control border-start-0 ps-0" placeholder="Cerca conversazioni..." wire:model.live="search">
                </div>
            </div>

            <!-- Conversations List -->
            <div class="overflow-auto" style="height: calc(100vh - 200px); background-color: #ffffff;">
                @forelse($this->conversations as $conversation)
                    <div class="p-3 border-bottom cursor-pointer position-relative {{ $selectedConversationId === $conversation->id ? 'conversation-selected' : 'conversation-item' }}"
                         wire:click="selectConversation({{ $conversation->id }})">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                @if($conversation->isGroup())
                                    @if($conversation->avatar)
                                        <img src="{{ asset('storage/' . $conversation->avatar) }}" alt="{{ $conversation->name }}" class="rounded-circle" style="width: 45px; height: 45px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle d-flex align-items-center bg-light-primary justify-content-center bg-opacity-10" style="width: 45px; height: 45px;">
                                            <i class="ph ph-users" style="font-size: 1.5rem;"></i>
                                        </div>
                                    @endif
                                @else
                                    @php
                                        $otherUser = $conversation->participants->where('id', '!=', auth()->id())->first();
                                    @endphp
                                    @if($otherUser)
                                        <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($otherUser) }}" alt="{{ $otherUser->name }}" class="rounded-circle" style="width: 45px; height: 45px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10" style="width: 45px; height: 45px;">
                                            <i class="ph ph-user text-primary" style="font-size: 1.5rem;"></i>
                                        </div>
                                    @endif
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-semibold text-dark">
                                    {{ $conversation->isGroup() ? $conversation->name : ($otherUser->name ?? 'Conversazione') }}
                                </h6>
                                @if($conversation->getLastMessage())
                                    <p class="mb-0 small text-muted text-truncate" style="max-width: 200px;">
                                        {{ $conversation->getLastMessage()->body }}
                                    </p>
                                @endif
                            </div>
                            @if($conversation->getUnreadCountForUser(auth()->id()) > 0)
                                <span class="badge bg-primary rounded-pill">
                                    {{ $conversation->getUnreadCountForUser(auth()->id()) }}
                                </span>
                            @endif
                        </div>
                    </div>
            @empty
                <div class="p-3 text-center text-muted">
                    <i class="ph ph-chat-circle mb-2" style="font-size: 2rem;"></i>
                    <p>Nessuna conversazione</p>
                </div>
            @endforelse
        </div>
    </div>

        <!-- Main Chat Area -->
        <div class="chat-main flex-grow-1 d-flex flex-column bg-white">
            @if($this->selectedConversation)
            <!-- Chat Header -->
            <div class="p-3 border-bottom bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <!-- Mobile back button -->
                    <button class="btn btn-link text-dark p-0 me-3 d-md-none" wire:click="$set('selectedConversationId', null)" style="font-size: 1.5rem;">
                        <i class="ph ph-arrow-left"></i>
                    </button>
                    
                    <div class="d-flex align-items-center flex-grow-1">
                        @if($this->selectedConversation->isGroup())
                            @if($this->selectedConversation->avatar)
                                <img src="{{ asset('storage/' . $this->selectedConversation->avatar) }}" alt="{{ $this->selectedConversation->name }}" class="rounded-circle me-3" style="width: 45px; height: 45px; object-fit: cover;">
                            @else
                                <div class="rounded-circle d-flex align-items-center justify-content-center  bg-opacity-10 me-3" style="width: 45px; height: 45px;">
                                    <i class="ph ph-users text-success" style="font-size: 1.5rem;"></i>
                                </div>
                            @endif
                            <div>
                                <h6 class="mb-0 fw-semibold text-dark">{{ $this->selectedConversation->name }}</h6>
                                <small class="text-muted">{{ $this->selectedConversation->participants->count() }} membri</small>
                            </div>
                        @else
                            @php
                                $otherUser = $this->selectedConversation->participants->where('id', '!=', auth()->id())->first();
                            @endphp
                            @if($otherUser)
                                <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($otherUser) }}" alt="{{ $otherUser->name }}" class="rounded-circle me-3" style="width: 45px; height: 45px; object-fit: cover;">
                            @else
                                <div class="rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 me-3" style="width: 45px; height: 45px;">
                                    <i class="ph ph-user text-primary" style="font-size: 1.5rem;"></i>
                                </div>
                            @endif
                            <div>
                                <h6 class="mb-0 fw-semibold text-dark">
                                    {{ $otherUser->name ?? 'Utente' }}
                                </h6>
                                <small class="text-muted">Online</small>
                            </div>
                        @endif
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-link text-dark p-0" data-bs-toggle="dropdown" style="font-size: 1.3rem;">
                            <i class="ph ph-dots-three-vertical"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="ph ph-info me-2"></i>Info</a></li>
                            <li><a class="dropdown-item" href="#"><i class="ph ph-archive me-2"></i>Archivia</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="#"><i class="ph ph-trash me-2"></i>Elimina</a></li>
                        </ul>
                    </div>
                </div>
            </div>

                <!-- Messages Area -->
                <div class="flex-grow-1 overflow-auto p-3" style="height: calc(100vh - 300px); background-color: #F5F5F5;">
                    @forelse($this->messages as $message)
                    <div class="mb-3 d-flex {{ $message->user_id === auth()->id() ? 'justify-content-end' : 'justify-content-start' }}">
                        <div class="max-w-75">
                            @if($message->replyTo)
                                <div class="p-2 rounded mb-2 border-start border-3 border-primary bg-primary bg-opacity-10">
                                    <small class="text-muted">{{ $message->replyTo->user->name }}</small>
                                    <p class="mb-0 small text-dark">{{ $message->replyTo->body }}</p>
                                </div>
                            @endif
                            
                            <div class="d-flex align-items-end {{ $message->user_id === auth()->id() ? 'flex-row-reverse' : '' }}">
                                @if($message->user_id !== auth()->id())
                                    <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($message->user) }}" alt="{{ $message->user->name }}" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                                @endif
                                
                                <div class="p-3 rounded-3 position-relative {{ $message->user_id === auth()->id() ? 'bg-primary text-white' : 'bg-white text-dark' }}" style="box-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                                    <p class="mb-3">{{ $message->body }}</p>
                                    <small class="opacity-75" style="font-size: 0.75rem;">
                                        {{ $message->created_at->format('H:i') }}
                                    </small>
                                </div>
                            </div>
                            
                            <!-- Reactions -->
                            @if(!empty($message->getReactions()))
                                <div class="mt-1 d-flex gap-1">
                                    @foreach($message->getReactions() as $emoji => $userIds)
                                        <button class="btn btn-sm btn-outline-secondary" 
                                                wire:click="addReaction({{ $message->id }}, '{{ $emoji }}')">
                                            {{ $emoji }} {{ count($userIds) }}
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted mt-5">
                        <i class="ph ph-chat-circle mb-3" style="font-size: 3rem;"></i>
                        <p>Inizia una conversazione</p>
                    </div>
                @endforelse
            </div>

                <!-- Message Input -->
                <div class="p-3 border-top bg-white">
                    @if($replyTo)
                        <div class="p-2 rounded mb-3 d-flex justify-content-between align-items-center border">
                            <div>
                                <small class="text-muted"><i class="ph ph-arrow-bend-up-left me-1"></i>Rispondendo a</small>
                                <p class="mb-0 small text-dark">{{ Message::find($replyTo)?->body }}</p>
                            </div>
                            <button class="btn btn-sm btn-link text-muted p-0" wire:click="cancelReply">
                                <i class="ph ph-x"></i>
                            </button>
                        </div>
                    @endif
                    
                    <form wire:submit="sendMessage">
                        <div class="d-flex align-items-center gap-2">
                            <input type="file" class="d-none" id="fileInput" wire:model="files" multiple>
                            <label for="fileInput" class="btn btn-link text-muted p-2" style="font-size: 1.3rem;">
                                <i class="ph ph-paperclip"></i>
                            </label>
                            
                            <button class="btn btn-link text-muted p-2" type="button" wire:click="$set('showEmojiPicker', !$showEmojiPicker)" style="font-size: 1.3rem;">
                                <i class="ph ph-smiley"></i>
                            </button>
                            
                            <input type="text" class="form-control shadow-none" placeholder="Scrivi un messaggio..." wire:model="newMessage" style="border-radius: 20px;">
                            
                            <button class="btn btn-link text-primary p-2" type="submit" style="font-size: 1.3rem;">
                                <i class="ph ph-paper-plane-right"></i>
                            </button>
                        </div>
                    </form>
                </div>
        @else
            <!-- No Conversation Selected -->
            <div class="d-flex align-items-center justify-content-center h-100">
                <div class="text-center text-muted">
                    <i class="ph ph-chat-circle mb-3" style="font-size: 4rem;"></i>
                    <h4>Seleziona una conversazione</h4>
                    <p>Inizia a chattare con i tuoi amici</p>
                </div>
            </div>
        @endif
    </div>
</div>
