<div x-data="{ 
    currentTime: $wire.entangle('currentTime'),
    duration: {{ $duration }},
    tooltipVisible: false,
    tooltipTitle: ''
}" 
     x-init="initTimeline()"
     class="snap-timeline position-relative">
    
    <!-- Progress Bar -->
    <div class="progress" style="height: 8px; background-color: #e9ecef;">
        <div class="progress-bar bg-primary" 
             :style="`width: ${(currentTime / duration) * 100}%`"></div>
    </div>
    
    <!-- Snap Markers -->
    @foreach($snaps as $snap)
        <div class="snap-marker position-absolute" 
             style="left: {{ ($snap->timestamp / ($duration ?: 1)) * 100 }}%"
             x-on:click="$wire.seekToTime({{ $snap->timestamp }})"
             x-on:mouseenter="tooltipVisible = true; tooltipTitle = '{{ $snap->title }}'"
             x-on:mouseleave="tooltipVisible = false">
            <div class="snap-indicator bg-success rounded-circle d-flex align-items-center justify-content-center"
                 style="width: 20px; height: 20px; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                <i class="ph ph-camera text-white" style="font-size: 10px;"></i>
            </div>
        </div>
    @endforeach
    
    <!-- Tooltip -->
    <div x-show="tooltipVisible" 
         x-transition
         class="snap-tooltip position-absolute bg-dark text-white rounded p-2"
         style="bottom: 100%; left: 50%; transform: translateX(-50%); font-size: 12px; white-space: nowrap;">
        <strong x-text="tooltipTitle"></strong>
    </div>
</div>

<script>
function initTimeline() {
    // Listener per aggiornamenti tempo
    Livewire.on('video-time-update', (data) => {
        this.currentTime = data.time;
    });
}
</script>
