<div x-data="{ 
    currentTime: $wire.entangle('currentTime'),
    duration: $wire.entangle('duration'),
    
    initPlayer() {
        // Listener per seek
        Livewire.on('player-seek', (data) => {
            this.$refs.videoPlayer.currentTime = data.timestamp;
        });
    },
    
    loadPeerTubeVideo() {
        @if($video->isUploadedToPeerTube() && $video->isReadyOnPeerTube())
            // Carica l'URL diretto di PeerTube via API
            fetch('{{ route("videos.peertube-url", $video) }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.files && data.files.length > 0) {
                        // Usa il primo file disponibile (migliore qualità)
                        const videoFile = data.files[0];
                        this.$refs.videoSource.src = videoFile.url;
                        this.$refs.videoPlayer.load();
                    } else {
                        console.error('Errore caricamento video PeerTube:', data.error);
                    }
                })
                .catch(error => {
                    console.error('Errore API PeerTube:', error);
                });
        @endif
    },
    
    updateTime() {
        this.currentTime = this.$refs.videoPlayer.currentTime;
        // Emetti evento per timeline
        this.$dispatch('video-time-update', { time: this.currentTime });
    },
    
    setDuration() {
        this.duration = this.$refs.videoPlayer.duration;
    },
    
    createSnapAtCurrentTime() {
        this.$wire.openSnapModal(this.currentTime);
    }
}" 
     x-init="initPlayer()"
     class="snap-player">
    
    <!-- Video Player -->
    <div class="position-relative" style="background-color: #000;">
        <!-- Video HTML5 con URL PeerTube diretto -->
        <video x-ref="videoPlayer" 
               controls 
               class="w-100"
               style="max-height: 60vh;"
               x-on:timeupdate="updateTime()"
               x-on:loadedmetadata="setDuration()"
               x-on:loadstart="loadPeerTubeVideo()">
            @if($video->isUploadedToPeerTube() && $video->isReadyOnPeerTube())
                <!-- Per PeerTube, carichiamo l'URL diretto via JavaScript -->
                <source src="" type="video/mp4" x-ref="videoSource">
            @else
                <!-- Per video locali -->
                <source src="{{ $video->video_url }}" type="video/mp4">
            @endif
            Il tuo browser non supporta il tag video.
        </video>
        
        <!-- Pulsante Crea Snap -->
        <div class="position-absolute top-0 end-0 m-3">
            <button x-on:click="createSnapAtCurrentTime()" 
                    class="btn btn-success btn-sm rounded-circle"
                    style="width: 50px; height: 50px;"
                    title="Crea Snap">
                <img src="{{ asset('assets/images/snap.svg') }}" alt="Snap" style="width: 24px; height: 24px; filter: brightness(0) invert(1);">
            </button>
        </div>
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

