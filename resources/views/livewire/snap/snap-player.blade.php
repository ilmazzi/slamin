<div x-data="{ 
    currentTime: $wire.entangle('currentTime'),
    duration: {{ $video->duration ?? 0 }},
    
    updateTime() {
        this.currentTime = this.$refs.videoPlayer.currentTime;
        this.$dispatch('video-time-update', { time: this.currentTime });
    },
    
    createSnapAtCurrentTime() {
        this.$wire.openSnapModal(Math.floor(this.currentTime));
    }
}" 
     x-init=""
     class="snap-player">
    
    
    <!-- Video Player -->
    <div class="position-relative" style="background-color: #000;">
        @if($videoDirectUrl)
            <!-- Video HTML5 con URL diretto (PeerTube o locale) -->
            <video x-ref="videoPlayer" 
                   controls 
                   class="w-100"
                   style="max-height: 60vh;"
                   x-on:timeupdate="updateTime()">
                <source src="{{ $videoDirectUrl }}" type="video/mp4">
                Il tuo browser non supporta il tag video.
            </video>
        @else
            <!-- Video non disponibile -->
            <div class="d-flex align-items-center justify-content-center bg-light" 
                 style="height: 400px;">
                <div class="text-center">
                    <i class="ph-duotone ph-video f-s-48 text-muted mb-3"></i>
                    <p class="text-muted">Video non disponibile</p>
                </div>
            </div>
        @endif
        
        <!-- Pulsante Crea Snap -->
        @if($videoDirectUrl)
        <div class="position-absolute top-0 end-0 m-3">
            <button x-on:click="createSnapAtCurrentTime()" 
                    class="btn btn-success btn-sm rounded-circle"
                    style="width: 50px; height: 50px;"
                    title="Crea Snap">
                <img src="{{ asset('assets/images/snap.svg') }}" alt="Snap" style="width: 24px; height: 24px; filter: brightness(0) invert(1);">
            </button>
        </div>
        @endif
    </div>
    
    <!-- Timeline con Snap -->
    <div class="mt-4 mb-3">
        @livewire('snap.snap-timeline', ['video' => $video, 'snaps' => $this->snaps])
    </div>
    
    <!-- Snap Modal -->
    @if($showSnapModal)
        <div class="modal fade show d-block" style="background: rgba(0,0,0,0.8); z-index: 1055;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <img src="{{ asset('assets/images/snap.svg') }}" alt="Snap" style="width: 20px; height: 20px; margin-right: 8px;">Crea Snap
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeSnapModal"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="createSnap">
                            <div class="mb-3">
                                <label class="form-label">Titolo *</label>
                                <input type="text" class="form-control" wire:model.defer="snapTitle" placeholder="Titolo del momento" required>
                                @error('snapTitle') <span class="text-danger d-block mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Descrizione</label>
                                <textarea class="form-control" wire:model.defer="snapDescription" rows="3" placeholder="Descrizione del momento"></textarea>
                                @error('snapDescription') <span class="text-danger d-block mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Timestamp</label>
                                <div class="form-control-plaintext">
                                    <i class="ph ph-clock me-1"></i>{{ gmdate('i:s', $snapTimestamp) }}
                                </div>
                                <input type="hidden" wire:model.defer="snapTimestamp">
                                @error('snapTimestamp') <span class="text-danger d-block mt-1">{{ $message }}</span> @enderror
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeSnapModal">Annulla</button>
                        <button type="button" class="btn btn-primary" wire:click="createSnap" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="createSnap">
                                <img src="{{ asset('assets/images/snap.svg') }}" alt="Snap" style="width: 16px; height: 16px; margin-right: 4px;">Crea Snap
                            </span>
                            <span wire:loading wire:target="createSnap">
                                <div class="spinner-border spinner-border-sm me-1" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                Creazione...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
// Funzioni globali per i tooltip degli snap
window.showSnapTooltip = function(element, title, description) {
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
};

window.hideSnapTooltip = function() {
    const tooltip = document.getElementById('snap-tooltip');
    if (tooltip) {
        tooltip.style.display = 'none';
    }
};
</script>

