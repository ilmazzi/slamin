<div class="snap-timeline position-relative">
    
    <!-- Progress Bar -->
    <div class="progress" style="height: 8px; background-color: #e9ecef;">
        <div class="progress-bar bg-primary" style="width: 0%"></div>
    </div>
    
    <!-- Snap Markers -->
    @foreach($snaps as $snap)
        <div class="snap-marker position-absolute" 
             style="left: {{ ($snap->timestamp / ($duration ?: 1)) * 100 }}%"
             onclick="const video = document.querySelector('.snap-player video'); if(video) { video.currentTime = {{ $snap->timestamp }}; }"
             onmouseenter="showSnapTooltip(this, '{{ addslashes($snap->title) }}', '{{ addslashes($snap->description) }}')"
             onmouseleave="hideSnapTooltip()">
            <div class="snap-indicator bg-success rounded-circle d-flex align-items-center justify-content-center"
                 style="width: 20px; height: 20px; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3); cursor: pointer;">
                <img src="{{ asset('assets/images/snap.svg') }}" alt="Snap" style="width: 12px; height: 12px; filter: brightness(0) invert(1);">
            </div>
        </div>
    @endforeach
    
    <!-- Tooltip Container -->
    <div id="snap-tooltip" class="position-absolute bg-dark text-white rounded p-2" 
         style="display: none; z-index: 1000; font-size: 12px; max-width: 200px; pointer-events: none;">
        <div class="fw-bold" id="snap-tooltip-title"></div>
        <div class="text-muted" id="snap-tooltip-description"></div>
    </div>
</div>

<script>
function showSnapTooltip(element, title, description) {
    const tooltip = document.getElementById('snap-tooltip');
    const titleEl = document.getElementById('snap-tooltip-title');
    const descEl = document.getElementById('snap-tooltip-description');
    
    if (tooltip && titleEl && descEl) {
        titleEl.textContent = title;
        descEl.textContent = description || 'Nessuna descrizione';
        
        // Posiziona il tooltip sopra il marker
        const rect = element.getBoundingClientRect();
        const timelineRect = element.closest('.snap-timeline').getBoundingClientRect();
        
        tooltip.style.display = 'block';
        tooltip.style.left = (rect.left - timelineRect.left) + 'px';
        tooltip.style.bottom = '100%';
        tooltip.style.marginBottom = '5px';
        tooltip.style.transform = 'translateX(-50%)';
    }
}

function hideSnapTooltip() {
    const tooltip = document.getElementById('snap-tooltip');
    if (tooltip) {
        tooltip.style.display = 'none';
    }
}
</script>

