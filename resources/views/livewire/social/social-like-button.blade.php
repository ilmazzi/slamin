<div class="social-like-button">
    @if(auth()->check())
        <button 
            type="button"
            class="{{ $this->sizeClasses['button'] }} btn-outline-0 d-flex align-items-center justify-content-center gap-1"
            wire:click="toggleLike"
            wire:loading.attr="disabled"
            wire:target="toggleLike"
            title="{{ $isLiked ? 'Rimuovi like' : 'Metti like' }}"
            style="width: 60px; height: 32px; transition: all 0.2s ease;"
        >
            <div wire:loading.remove wire:target="toggleLike">
                <img src="{{ asset('assets/icon/new/like.svg') }}" 
                     alt="Like" 
                     style="width: {{ $this->sizeClasses['width'] }}; height: {{ $this->sizeClasses['height'] }}; {{ $isLiked ? 'filter: brightness(0) saturate(100%) invert(27%) sepia(51%) saturate(2878%) hue-rotate(346deg) brightness(104%) contrast(97%);' : 'filter: brightness(0) saturate(100%) invert(60%) sepia(0%) saturate(0%) hue-rotate(0deg) brightness(89%) contrast(86%);' }}">
            </div>
            <div wire:loading wire:target="toggleLike">
                <div class="spinner-border spinner-border-sm" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <span class="{{ $this->sizeClasses['text'] }} {{ $isLiked ? 'text-primary' : 'text-muted' }}">
                {{ number_format($likeCount) }}
            </span>
        </button>
    @else
        <div class="d-flex align-items-center justify-content-center gap-1 text-muted" style="width: 60px; height: 32px;">
            <img src="{{ asset('assets/icon/new/like.svg') }}" 
                 alt="Like" 
                 style="width: {{ $this->sizeClasses['width'] }}; height: {{ $this->sizeClasses['height'] }}; filter: brightness(0) saturate(100%) invert(60%) sepia(0%) saturate(0%) hue-rotate(0deg) brightness(89%) contrast(86%); opacity: 0.6;">
            <span class="{{ $this->sizeClasses['text'] }}">{{ number_format($likeCount) }}</span>
        </div>
    @endif
</div>
