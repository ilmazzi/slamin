<div class="social-view-counter">
    <div class="d-flex align-items-center justify-content-center gap-1 text-muted" style="width: 60px; height: 32px;">
        <i class="ph-duotone ph-eye {{ $this->sizeClasses['icon'] }}"></i>
        <span class="{{ $this->sizeClasses['text'] }}">{{ number_format($viewCount) }}</span>
    </div>
</div>
