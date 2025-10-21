<div x-data="{ 
    currentTime: $wire.entangle('currentTime'),
    duration: {{ $duration }},
    
    initTimeline() {
        // Listener per aggiornamenti tempo
        Livewire.on('video-time-update', (data) => {
            this.currentTime = data.time;
        });
    }
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
        <div x-data="{ showTooltip: false }"
             class="snap-marker position-absolute" 
             style="left: {{ ($snap->timestamp / ($duration ?: 1)) * 100 }}%"
             x-on:click="Livewire.dispatch('seek-video', { timestamp: {{ $snap->timestamp }} })"
             x-on:mouseenter="showTooltip = true"
             x-on:mouseleave="showTooltip = false">
            <div class="snap-indicator bg-success rounded-circle d-flex align-items-center justify-content-center"
                 style="width: 20px; height: 20px; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3); cursor: pointer;">
                <img src="{{ asset('assets/images/snap.svg') }}" alt="Snap" style="width: 12px; height: 12px; filter: brightness(0) invert(1);">
            </div>
            
            <!-- Tooltip per questo snap -->
            <div x-show="showTooltip" 
                 x-transition
                 class="snap-tooltip position-absolute bg-dark text-white rounded p-2"
                 style="bottom: 100%; left: 50%; transform: translateX(-50%); font-size: 12px; white-space: nowrap; margin-bottom: 5px; z-index: 1000;">
                <strong>{{ $snap->title }}</strong>
            </div>
        </div>
    @endforeach
</div>

