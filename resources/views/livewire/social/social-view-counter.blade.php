<div>
    <div class="post-icon social-view-counter"
         style="display: flex; flex-direction: column; align-items: center; border-radius: 8px; transition: all 0.2s; {{ $this->getSizeStyles() }}">
        <i class="ti ti-eye {{ $this->getIconClass() }} text-primary"></i>
        @if($showCount)
            <span class="text-secondary view-count {{ $this->getTextClass() }}">{{ number_format($viewCount) }}</span>
        @endif
    </div>
</div>
