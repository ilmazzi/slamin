<div>
    <div class="social-comment-btn"
         wire:click="toggleModal"
         title="Commenti"
         style="cursor: pointer; display: flex; flex-direction: column; align-items: center; border-radius: 8px; transition: all 0.2s; {{ $this->getSizeStyles() }}"
         onmouseover="this.style.backgroundColor='rgba(0,0,0,0.05)'"
         onmouseout="this.style.backgroundColor='transparent'">
        <i class="ph-duotone ph-chat-circle {{ $this->getIconClass() }} text-primary"></i>
        @if($showCount)
            <span class="text-secondary comment-count {{ $this->getTextClass() }}">{{ number_format($commentCount) }}</span>
        @endif
    </div>

    <!-- Modal Commenti -->
    @if($showModal)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="ph-duotone ph-chat-circle me-2"></i>
                            Commenti
                        </h5>
                        <button type="button" class="btn-close" wire:click="toggleModal"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Lista Commenti -->
                        <div class="comments-list mb-4">
                            @forelse($model->approvedComments()->with('user')->latest()->take(10)->get() as $comment)
                                <div class="d-flex mb-3">
                                    <div class="flex-shrink-0 me-3">
                                        @if($comment->user->profile_photo_url)
                                            <img src="{{ $comment->user->profile_photo_url }}" 
                                                 class="rounded-circle" 
                                                 alt="{{ $comment->user->name }}" 
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="h-40 w-40 d-flex-center rounded-circle bg-light-primary">
                                                <i class="ph-duotone ph-user f-s-16 text-primary"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                            <h6 class="mb-0 f-s-14 f-w-600">
                                                <a href="{{ route('profile.show', $comment->user->id) }}" 
                                                   class="text-decoration-none text-dark">
                                                    {{ $comment->user->name }}
                                                </a>
                                            </h6>
                                            <small class="text-muted f-s-12">{{ $comment->created_at->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-0 f-s-13">{{ $comment->content }}</p>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-4">
                                    <i class="ph-duotone ph-chat-circle f-s-48 text-muted mb-3"></i>
                                    <p class="text-muted mb-0">Nessun commento ancora</p>
                                    <p class="text-muted f-s-14">Sii il primo a commentare!</p>
                                </div>
                            @endforelse
                        </div>

                        <!-- Form Nuovo Commento -->
                        @auth
                            <div class="border-top pt-3">
                                <h6 class="mb-3">
                                    <i class="ph-duotone ph-plus-circle me-2"></i>
                                    Aggiungi un commento
                                </h6>
                                <form wire:submit.prevent="addComment($event.target.commentContent.value)">
                                    <div class="mb-3">
                                        <textarea class="form-control" 
                                                  name="commentContent" 
                                                  rows="3" 
                                                  placeholder="Scrivi il tuo commento..." 
                                                  maxlength="500"
                                                  required></textarea>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ph-duotone ph-paper-plane-right me-1"></i>
                                            Invia commento
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" wire:click="toggleModal">
                                            Chiudi
                                        </button>
                                    </div>
                                </form>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="ph-duotone ph-info me-2"></i>
                                <a href="{{ route('login') }}" class="text-decoration-none">Accedi</a> per lasciare un commento.
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
