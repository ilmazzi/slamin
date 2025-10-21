<div x-data="{ 
    currentTime: $wire.entangle('currentTime'),
    duration: {{ $video->duration ?? 0 }},
    
    updateTime() {
        if (this.$refs.videoPlayer.tagName === 'VIDEO') {
            this.currentTime = this.$refs.videoPlayer.currentTime;
            this.$dispatch('video-time-update', { time: this.currentTime });
        }
        // Per PeerTube iframe, non possiamo ottenere il tempo corrente
    },
    
    createSnapAtCurrentTime() {
        if (this.$refs.videoPlayer.tagName === 'VIDEO') {
            this.$wire.openSnapModal(this.currentTime);
        } else {
            // Per PeerTube, chiediamo all'utente di inserire il timestamp manualmente
            const timestamp = prompt('Inserisci il timestamp in secondi (es: 120 per 2 minuti):');
            if (timestamp && !isNaN(timestamp)) {
                this.$wire.openSnapModal(parseInt(timestamp));
            }
        }
    }
}" 
     x-init="Livewire.on('player-seek', (data) => {
         if (this.$refs.videoPlayer.tagName === 'VIDEO') {
             this.$refs.videoPlayer.currentTime = data.timestamp;
         } else {
             // Per PeerTube iframe, apri il video con timestamp
             const videoUrl = '{{ $video->peertube_url }}?start=' + data.timestamp;
             window.open(videoUrl, '_blank');
         }
     })"
     class="snap-player">
    
    <!-- Video Player -->
    <div class="position-relative" style="background-color: #000;">
        @if($video->isUploadedToPeerTube() && $video->isReadyOnPeerTube())
            <!-- PeerTube iframe per video con snap -->
            <div class="position-relative" style="padding-bottom: 56.25%; height: 0; overflow: hidden;">
                <iframe x-ref="videoPlayer"
                        src="{{ $video->peertube_embed_url }}" 
                        frameborder="0" 
                        allowfullscreen
                        class="position-absolute top-0 left-0 w-100 h-100"
                        style="border: none;">
                </iframe>
            </div>
        @else
            <!-- Video HTML5 per video locali -->
            <video x-ref="videoPlayer" 
                   controls 
                   class="w-100"
                   style="max-height: 60vh;"
                   x-on:timeupdate="updateTime()">
                <source src="{{ $video->video_url }}" type="video/mp4">
                Il tuo browser non supporta il tag video.
            </video>
        @endif
        
        <!-- Pulsante Crea Snap -->
        @if(!$video->isUploadedToPeerTube() || !$video->isReadyOnPeerTube())
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
    <div class="mt-3">
        @livewire('snap.snap-timeline', ['video' => $video, 'snaps' => $snaps])
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
                        <form wire:submit="createSnap">
                            <div class="mb-3">
                                <label class="form-label">Titolo *</label>
                                <input type="text" class="form-control" wire:model="snapTitle" placeholder="Titolo del momento">
                                @error('snapTitle') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Descrizione</label>
                                <textarea class="form-control" wire:model="snapDescription" rows="3" placeholder="Descrizione del momento"></textarea>
                                @error('snapDescription') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Timestamp</label>
                                <div class="form-control-plaintext">
                                    <i class="ph ph-clock me-1"></i>{{ gmdate('i:s', $snapTimestamp) }}
                                </div>
                                <input type="hidden" wire:model="snapTimestamp">
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

