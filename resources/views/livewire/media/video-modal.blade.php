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
                @livewire('social.comment-section', ['contentId' => $video->id, 'contentType' => 'video'], key('comment-section-video-'.$video->id))
            </div>
        </div>
    </div>
</div>
@endif
</div>
