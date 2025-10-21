<div>
@if($showModal && $video)
<div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.8); z-index: 1055;">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title d-flex align-items-center gap-2">
                    <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($video->user) }}" 
                         class="rounded-circle" 
                         alt="{{ $video->user->name }}"
                         style="width: 32px; height: 32px; object-fit: cover;">
                    <div>
                        <div class="f-s-14 f-w-600">{{ $video->user->name }}</div>
                        <small class="text-muted f-s-12">{{ $video->created_at->diffForHumans() }}</small>
                    </div>
                </h5>
                <button type="button" class="btn-close" wire:click="closeModal"></button>
            </div>
            
            <div class="modal-body p-3">
                <!-- Video con Snap -->
                @livewire('snap.snap-player', ['video' => $video], key('snap-player-'.$video->id))
                
                <!-- Title & Description -->
                @if($video->title || $video->description)
                    <div class="mb-4">
                        @if($video->title)
                            <h6 class="f-s-16 f-w-600 mb-2">{{ $video->title }}</h6>
                        @endif
                        @if($video->description)
                            <p class="f-s-14 text-muted mb-0">{{ $video->description }}</p>
                        @endif
                    </div>
                @endif
                
                <!-- Social Buttons -->
                <div class="d-flex align-items-center justify-content-between mb-3 pb-3 border-bottom">
                    <div class="d-flex align-items-center gap-2">
                        @livewire('social.social-view-counter', ['content' => $video, 'type' => 'video', 'size' => 'md'], key('video-modal-view-'.$video->id))
                        @livewire('social.social-like-button', ['content' => $video, 'type' => 'video', 'size' => 'md'], key('video-modal-like-'.$video->id))
                        @livewire('social.social-comment-button', ['content' => $video, 'type' => 'video', 'size' => 'md'], key('video-modal-comment-'.$video->id))
                    </div>
                    <a href="{{ route('videos.show', $video) }}" 
                       class="btn btn-outline-primary btn-sm d-flex align-items-center gap-1"
                       target="_blank">
                        <i class="ph-duotone ph-arrow-square-out f-s-14"></i>
                        <span>Apri video</span>
                    </a>
                </div>
                
                <!-- Comments Section -->
                <div id="video-comments-section">
                    <h6 class="f-s-14 f-w-600 mb-3">
                        <i class="ph-duotone ph-chat-circle me-1"></i>
                        Commenti ({{ $comments->count() }})
                    </h6>
                    
                    <!-- Add Comment Form -->
                    @if(auth()->check())
                        <div class="mb-4">
                            <div class="d-flex align-items-start gap-2">
                                <div class="flex-shrink-0">
                                    <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl(auth()->user()) }}" 
                                         alt="{{ auth()->user()->name }}" 
                                         class="rounded-circle" 
                                         style="width: 40px; height: 40px; object-fit: cover;">
                                </div>
                                <div class="flex-grow-1">
                                    <textarea 
                                        wire:model.live="newComment" 
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
                                            @disabled(empty(trim($newComment)))
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
                    @else
                        <div class="alert alert-info">
                            <i class="ph-duotone ph-info me-2"></i>
                            Devi essere autenticato per commentare
                        </div>
                    @endif
                    
                    <!-- Comments List -->
                    @if($comments->count() > 0)
                        <div class="comments-list">
                            @foreach($comments as $comment)
                                <div class="comment-item mb-3 pb-3 border-bottom">
                                    <div class="d-flex align-items-start gap-2">
                                        <div class="flex-shrink-0">
                                            <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($comment->user) }}" 
                                                 alt="{{ $comment->user->name }}" 
                                                 class="rounded-circle" 
                                                 style="width: 32px; height: 32px; object-fit: cover;">
                                        </div>
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <h6 class="mb-0 f-s-13 f-w-600">
                                                    {{ $comment->user->name }}
                                                </h6>
                                                <span class="f-s-11 text-muted">
                                                    {{ $comment->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                            <p class="mb-0 f-s-14">{{ $comment->content }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-3">
                            <i class="ph-duotone ph-chat-circle f-s-24 mb-2"></i>
                            <p class="f-s-13 mb-0">Nessun commento ancora. Sii il primo a commentare!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif
</div>
