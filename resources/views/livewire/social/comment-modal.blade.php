<div>
    @if($showModal)
        <div class="modal fade show d-block" tabindex="-1" style="background-color: rgba(0,0,0,0.5); z-index: 1055;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="ph-duotone ph-chat-circle me-2"></i>
                            Commenti
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Form per nuovo commento -->
                        @if(auth()->check())
                            <div class="mb-4">
                                <div class="d-flex align-items-start gap-2">
                                    <div class="flex-shrink-0">
                                        @if(auth()->user()->avatar_url)
                                            <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl(auth()->user()) }}" 
                                                 alt="{{ auth()->user()->name }}" 
                                                 class="rounded-circle" 
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="bg-light-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="ph-duotone ph-user f-s-16 text-primary"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <textarea 
                                            wire:model="newComment" 
                                            class="form-control" 
                                            rows="3" 
                                            placeholder="Scrivi un commento..."
                                            style="resize: none;"
                                        ></textarea>
                                        <div class="d-flex justify-content-end mt-2">
                                            <button 
                                                type="button" 
                                                class="btn btn-primary btn-sm" 
                                                wire:click="addComment"
                                                wire:loading.attr="disabled"
                                                wire:target="addComment"
                                                {{ empty(trim($newComment)) ? 'disabled' : '' }}
                                            >
                                                <span wire:loading.remove wire:target="addComment">
                                                    <i class="ph-duotone ph-paper-plane-tilt me-1"></i>
                                                    Commenta
                                                </span>
                                                <span wire:loading wire:target="addComment">
                                                    <div class="spinner-border spinner-border-sm me-1" role="status">
                                                        <span class="visually-hidden">Loading...</span>
                                                    </div>
                                                    Invio...
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Lista commenti -->
                        <div class="comments-list">
                            @if(count($comments) > 0)
                                @foreach($comments as $comment)
                                    <div class="comment-item mb-3 pb-3 border-bottom">
                                        <div class="d-flex align-items-start gap-2">
                                            <div class="flex-shrink-0">
                                                @if($comment['user'] && $comment['user']['avatar_url'])
                                                    <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($comment['user']) }}" 
                                                         alt="{{ $comment['user']['name'] }}" 
                                                         class="rounded-circle" 
                                                         style="width: 32px; height: 32px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                                        <i class="ph-duotone ph-user f-s-12 text-primary"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center gap-2 mb-1">
                                                    <h6 class="mb-0 f-s-13 f-w-600">
                                                        {{ $comment['user']['name'] ?? 'Utente sconosciuto' }}
                                                    </h6>
                                                    <span class="f-s-11 text-muted">
                                                        {{ \Carbon\Carbon::parse($comment['created_at'])->diffForHumans() }}
                                                    </span>
                                                </div>
                                                <p class="mb-2 f-s-14 text-dark">
                                                    {{ $comment['content'] }}
                                                </p>
                                                
                                                <!-- Risposte -->
                                                @if(isset($comment['replies']) && count($comment['replies']) > 0)
                                                    <div class="replies ms-4">
                                                        @foreach($comment['replies'] as $reply)
                                                            <div class="reply-item mb-2 pb-2 border-start border-2 border-light ps-3">
                                                                <div class="d-flex align-items-start gap-2">
                                                                    <div class="flex-shrink-0">
                                                                        @if($reply['user'] && $reply['user']['avatar_url'])
                                                                            <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($reply['user']) }}" 
                                                                                 alt="{{ $reply['user']['name'] }}" 
                                                                                 class="rounded-circle" 
                                                                                 style="width: 24px; height: 24px; object-fit: cover;">
                                                                        @else
                                                                            <div class="bg-light-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                                                                                <i class="ph-duotone ph-user f-s-10 text-secondary"></i>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    <div class="flex-grow-1">
                                                                        <div class="d-flex align-items-center gap-2 mb-1">
                                                                            <h6 class="mb-0 f-s-12 f-w-600">
                                                                                {{ $reply['user']['name'] ?? 'Utente sconosciuto' }}
                                                                            </h6>
                                                                            <span class="f-s-10 text-muted">
                                                                                {{ \Carbon\Carbon::parse($reply['created_at'])->diffForHumans() }}
                                                                            </span>
                                                                        </div>
                                                                        <p class="mb-0 f-s-13 text-dark">
                                                                            {{ $reply['content'] }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-4">
                                    <div class="bg-light-secondary h-50 w-50 d-flex-center rounded-circle m-auto mb-2">
                                        <i class="ph-duotone ph-chat-circle f-s-24 text-secondary"></i>
                                    </div>
                                    <p class="text-muted f-s-14 mb-0">Nessun commento ancora</p>
                                    <p class="text-muted f-s-12 mb-0">Sii il primo a commentare!</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeModal">
                            <i class="ph-duotone ph-x me-1"></i>
                            Chiudi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
