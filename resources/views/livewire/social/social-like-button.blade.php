<div>
    @if(auth()->check())
        <div class="social-like-btn"
             wire:click="toggleLike"
             title="{{ $isLiked ? 'Rimuovi like' : 'Metti like' }}"
             style="cursor: pointer; display: flex; flex-direction: column; align-items: center; border-radius: 8px; transition: all 0.2s; {{ $this->getSizeStyles() }}"
             onmouseover="this.style.backgroundColor='rgba(0,0,0,0.05)'"
             onmouseout="this.style.backgroundColor='transparent'">
            <img src="{{ asset('assets/images/like.png') }}" 
                 alt="Like" 
                 style="{{ $this->getIconStyles() }} {{ $isLiked ? 'filter: brightness(0) saturate(100%) invert(27%) sepia(51%) saturate(2878%) hue-rotate(346deg) brightness(104%) contrast(97%);' : 'filter: brightness(0) saturate(100%) invert(60%) sepia(0%) saturate(0%) hue-rotate(0deg) brightness(89%) contrast(86%);' }}">
            @if($showCount)
                <span class="text-secondary like-count {{ $this->getTextClass() }}">{{ number_format($likeCount) }}</span>
            @endif
        </div>
    @else
        <div class="social-like-counter"
             style="display: flex; flex-direction: column; align-items: center; border-radius: 8px; {{ $this->getSizeStyles() }}">
            <img src="{{ asset('assets/images/like.png') }}" 
                 alt="Like" 
                 style="{{ $this->getIconStyles() }} filter: brightness(0) saturate(100%) invert(60%) sepia(0%) saturate(0%) hue-rotate(0deg) brightness(89%) contrast(86%); opacity: 0.6;">
            @if($showCount)
                <span class="text-secondary like-count {{ $this->getTextClass() }}">{{ number_format($likeCount) }}</span>
            @endif
        </div>
    @endif
</div>
