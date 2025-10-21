<div class="social-comment-button">
    @if(auth()->check())
        <button 
            type="button"
            class="{{ $this->sizeClasses['button'] }} btn-outline-0 d-flex align-items-center justify-content-center gap-1"
            wire:click="openCommentModal"
            title="Commenti"
            style="width: 60px; height: 32px; transition: all 0.2s ease;"
        >
            <i class="ph-duotone ph-chat-circle {{ $this->sizeClasses['icon'] }} text-muted"></i>
            <span class="{{ $this->sizeClasses['text'] }} text-muted">
                {{ number_format($commentCount) }}
            </span>
        </button>
    @else
        <div class="d-flex align-items-center justify-content-center gap-1 text-muted" style="width: 60px; height: 32px;">
            <i class="ph-duotone ph-chat-circle {{ $this->sizeClasses['icon'] }} opacity-50"></i>
            <span class="{{ $this->sizeClasses['text'] }}">{{ number_format($commentCount) }}</span>
        </div>
    @endif

    <!-- Comment Modal will be handled globally -->
</div>
