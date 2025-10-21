<div>
@if($showModal && $photo)
<div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.8); z-index: 1055;">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title d-flex align-items-center gap-2">
                    <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($photo->user) }}" 
                         class="rounded-circle" 
                         alt="{{ $photo->user->name }}"
                         style="width: 32px; height: 32px; object-fit: cover;">
                    <div>
                        <div class="f-s-14 f-w-600">{{ $photo->user->name }}</div>
                        <small class="text-muted f-s-12">{{ $photo->created_at->diffForHumans() }}</small>
                    </div>
                </h5>
                <button type="button" class="btn-close" wire:click="closeModal"></button>
            </div>
            
            <div class="modal-body p-3">
                <!-- Photo -->
                <div class="mb-3 text-center" style="background-color: #f8f9fa;">
                    <img src="{{ $photo->image_url }}" 
                         class="img-fluid" 
                         alt="{{ $photo->title }}"
                         style="max-height: 60vh; object-fit: contain;">
                </div>
                
                <!-- Title & Description -->
                @if($photo->title || $photo->description)
                    <div class="mb-3">
                        @if($photo->title)
                            <h6 class="f-s-16 f-w-600 mb-2">{{ $photo->title }}</h6>
                        @endif
                        @if($photo->description)
                            <p class="f-s-14 text-muted mb-0">{{ $photo->description }}</p>
                        @endif
                    </div>
                @endif
                
                <!-- Social Buttons -->
                <div class="d-flex align-items-center gap-2 mb-3 pb-3 border-bottom">
                    @livewire('social.social-view-counter', ['content' => $photo, 'type' => 'photo', 'size' => 'md'], key('photo-modal-view-'.$photo->id))
                    @livewire('social.social-like-button', ['content' => $photo, 'type' => 'photo', 'size' => 'md'], key('photo-modal-like-'.$photo->id))
                    @livewire('social.social-comment-button', ['content' => $photo, 'type' => 'photo', 'size' => 'md'], key('photo-modal-comment-'.$photo->id))
                </div>
                
                <!-- Comments Section -->
                @livewire('social.comment-section', ['contentId' => $photo->id, 'contentType' => 'photo'], key('comment-section-photo-'.$photo->id))
            </div>
        </div>
    </div>
</div>
@endif
</div>
