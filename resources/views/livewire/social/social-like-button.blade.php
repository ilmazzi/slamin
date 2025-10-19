<div>
    @if(auth()->check())
        <div class="social-component social-like-btn {{ $isLiked ? 'liked' : '' }}"
             wire:click="toggleLike"
             title="{{ $isLiked ? 'Rimuovi like' : 'Metti like' }}"
             style="cursor: pointer; border-radius: 8px; transition: all 0.2s; {{ $this->getSizeStyles() }}"
             onmouseover="this.style.backgroundColor='rgba(0,0,0,0.05)'"
             onmouseout="this.style.backgroundColor='transparent'">
            <svg class="icon icon-like {{ $isLiked ? 'text-danger liked' : 'text-primary' }}" 
                 style="{{ $this->getIconStyles() }} {{ $isLiked ? 'transform: scale(1.3); filter: drop-shadow(0 0 8px rgba(220, 53, 69, 0.5));' : '' }}">
                <use xlink:href="#icon-like"></use>
            </svg>
            @if($showCount)
                <span class="text-secondary like-count {{ $this->getTextClass() }}">{{ number_format($likeCount) }}</span>
            @endif
        </div>
    @else
        <div class="social-component social-like-counter"
             style="border-radius: 8px; {{ $this->getSizeStyles() }}">
            <svg class="icon icon-like text-primary" style="{{ $this->getIconStyles() }} opacity: 0.6;">
                <use xlink:href="#icon-like"></use>
            </svg>
            @if($showCount)
                <span class="text-secondary like-count {{ $this->getTextClass() }}">{{ number_format($likeCount) }}</span>
            @endif
        </div>
    @endif
</div>
