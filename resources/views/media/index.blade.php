@extends('layout.master')

@section('title', __('common.media_section') . ' - Slamin')

@section('main-content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">
                <i class="ph ph-video-camera me-2"></i>
                {{ __('common.media_section') }}
            </h4>
            <ul class="app-line-breadcrumbs mb-3">
                <li class="">
                    <a href="{{ route('dashboard') }}" class="f-s-14 f-w-500">
                        <span>
                            <i class="ph-duotone ph-house f-s-16"></i> {{ __('dashboard.dashboard') }}
                        </span>
                    </a>
                </li>
                <li class="active">
                    <a href="#" class="f-s-14 f-w-500">{{ __('common.media_section') }}</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Prima Riga: Video Più Popolare + 6 Video con Switch -->
    <div class="row mb-4">
        <!-- Video Più Popolare (Grande) -->
        <div class="col-lg-8">
            <div class="card hover-effect">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph-duotone ph-trophy me-2"></i>
                        Video Più Popolare
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($mostPopularVideo)
                        <div class="position-relative">
                            @if($mostPopularVideo->thumbnail_url && $mostPopularVideo->thumbnail_url !== asset('assets/images/placeholder/placholder-1.jpg'))
                                <div class="position-relative" style="cursor: pointer;" onclick="openVideoModal({{ $mostPopularVideo->id }})">
                                    <img src="{{ $mostPopularVideo->thumbnail_url }}" alt="{{ $mostPopularVideo->title }}" class="card-img-top" style="height: 400px; object-fit: cover;">
                                    <div class="position-absolute top-50 start-50 translate-middle">
                                        <div class="play-button bg-white rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                                            <i class="ph-duotone ph-play f-s-32 text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="position-absolute bottom-0 start-0 end-0 p-3" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                                        <small class="text-white f-s-12">
                                            <i class="ph-duotone ph-clock me-1"></i>
                                            @if($mostPopularVideo->duration && $mostPopularVideo->duration > 0)
                                                {{ $mostPopularVideo->formatted_duration }}
                                            @else
                                                <span title="{{ __('videos.duration_unavailable') }}">--:--</span>
                                            @endif
                                        </small>
                                    </div>
                                    <div class="position-absolute top-0 end-0 m-3">
                                        <span class="badge bg-dark f-s-12">{{ $mostPopularVideo->view_count ?? $mostPopularVideo->views }} {{ __('profile.views') }}</span>
                                    </div>
                                </div>
                            @elseif($mostPopularVideo->peertube_uuid)
                                                            <div class="card-img-top video-preview bg-gradient-primary d-flex align-items-center justify-content-center position-relative"
                                 style="height: 400px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); cursor: pointer;"
                                 onclick="openVideoModal({{ $mostPopularVideo->id }})">
                                    <div class="position-absolute top-50 start-50 translate-middle">
                                        <div class="play-button bg-white rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                                            <i class="ph-duotone ph-play f-s-32 text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="position-absolute bottom-0 start-0 end-0 p-3" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                                        <small class="text-white f-s-12">
                                            <i class="ph-duotone ph-clock me-1"></i>
                                            @if($mostPopularVideo->duration && $mostPopularVideo->duration > 0)
                                                {{ $mostPopularVideo->formatted_duration }}
                                            @else
                                                <span title="{{ __('videos.duration_unavailable') }}">--:--</span>
                                            @endif
                                        </small>
                                    </div>
                                    <div class="position-absolute top-0 end-0 m-3">
                                        <span class="badge bg-dark f-s-12">{{ $mostPopularVideo->view_count ?? $mostPopularVideo->views }} {{ __('profile.views') }}</span>
                                    </div>
                                </div>
                            @else
                                <div class="position-relative" style="cursor: pointer;" onclick="openVideoModal({{ $mostPopularVideo->id }})">
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                                        <div class="text-center">
                                            <i class="ph-duotone ph-video-camera f-s-64 text-muted mb-3"></i>
                                            <div class="play-button bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 80px; height: 80px; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                                                <i class="ph-duotone ph-play f-s-32 text-white"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="position-absolute top-0 end-0 m-3">
                                        <span class="badge bg-dark f-s-12">{{ $mostPopularVideo->view_count ?? $mostPopularVideo->views }} {{ __('profile.views') }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="card-body">
                            <h5 class="card-title f-w-600 f-s-16 mb-2">{{ $mostPopularVideo->title }}</h5>
                            @if($mostPopularVideo->description)
                                <p class="text-muted f-s-13 mb-3">{{ Str::limit($mostPopularVideo->description, 120) }}</p>
                            @endif

                            <!-- Video Actions -->
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex gap-2">
                                    <x-social-like-button :content="$mostPopularVideo" type="video" />
                                    <x-social-view-counter :content="$mostPopularVideo" type="video" />
                                    <x-social-snap-button :content="$mostPopularVideo" type="video" />
                                </div>
                                <small class="text-muted">
                                    <i class="ph-duotone ph-calendar f-s-12 me-1"></i>
                                    {{ $mostPopularVideo->created_at->format('d/m/Y') }}
                                </small>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="bg-light-primary h-80 w-80 d-flex-center rounded-circle m-auto mb-3">
                                <i class="ph-duotone ph-video-camera-slash f-s-48 text-primary"></i>
                            </div>
                            <p class="text-muted f-s-16 mb-0">Nessun video disponibile</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- 6 Video con Switch Nuovi/Popolari (Piccolo) -->
        <div class="col-lg-4">
            <div class="card hover-effect">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="ph-duotone ph-video-camera me-2"></i>
                            Video
                        </h5>
                        <!-- Switch Nuovi/Popolari -->
                        <div class="d-flex align-items-center">
                            <span class="me-2 text-primary f-s-14 f-w-500" id="popularLabel" style="cursor: pointer;">Popolari</span>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="videoToggle" onchange="toggleVideoContent()">
                            </div>
                            <span class="ms-2 text-muted f-s-14 f-w-500" id="newLabel" style="cursor: pointer;">Nuovi</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Contenuto Popolari (Default) -->
                    <div id="popularVideos">
                        @if($popularVideos->count() > 0)
                            @foreach($popularVideos->take(6) as $video)
                                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                    <div class="flex-shrink-0 position-relative" style="cursor: pointer;" onclick="window.location.href='{{ route('videos.show', $video) }}'">
                                        @if($video->thumbnail_url && $video->thumbnail_url !== asset('assets/images/placeholder/placholder-1.jpg'))
                                            <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}" class="rounded" style="width: 80px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center rounded" style="width: 80px; height: 60px;">
                                                <i class="ph-duotone ph-video-camera f-s-24 text-muted"></i>
                                            </div>
                                        @endif
                                        <div class="position-absolute top-50 start-50 translate-middle">
                                            <i class="ph-duotone ph-play f-s-16 text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 f-w-600 f-s-13">{{ Str::limit($video->title, 40) }}</h6>
                                        <div class="d-flex align-items-center gap-2">
                                            <small class="text-muted f-s-11">
                                                <i class="ph-duotone ph-eye me-1"></i>{{ $video->view_count ?? $video->views }}
                                            </small>
                                            <small class="text-muted f-s-11">
                                                <i class="ph-duotone ph-heart me-1"></i>{{ $video->like_count }}
                                            </small>
                                            <small class="text-muted f-s-11">
                                                <img src="{{ asset('assets/images/snap.png') }}" alt="Snap" style="width: 12px; height: 12px; filter: brightness(0) saturate(100%) invert(60%) sepia(0%) saturate(0%) hue-rotate(0deg) brightness(89%) contrast(86%);" class="me-1">{{ $video->snap_count ?? 0 }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <div class="bg-light-warning h-50 w-50 d-flex-center rounded-circle m-auto mb-2">
                                    <i class="ph-duotone ph-video-camera-slash f-s-24 text-warning"></i>
                                </div>
                                <p class="text-muted f-s-14 mb-0">Nessun video popolare</p>
                            </div>
                        @endif
                    </div>

                    <!-- Contenuto Nuovi (Nascosto) -->
                    <div id="newVideos" style="display: none;">
                        @if($newVideos->count() > 0)
                            @foreach($newVideos->take(6) as $video)
                                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                    <div class="flex-shrink-0 position-relative" style="cursor: pointer;" onclick="window.location.href='{{ route('videos.show', $video) }}'">
                                        @if($video->thumbnail_url && $video->thumbnail_url !== asset('assets/images/placeholder/placholder-1.jpg'))
                                            <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}" class="rounded" style="width: 80px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center rounded" style="width: 80px; height: 60px;">
                                                <i class="ph-duotone ph-video-camera f-s-24 text-muted"></i>
                                            </div>
                                        @endif
                                        <div class="position-absolute top-50 start-50 translate-middle">
                                            <i class="ph-duotone ph-play f-s-16 text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 f-w-600 f-s-13">{{ Str::limit($video->title, 40) }}</h6>
                                        <div class="d-flex align-items-center gap-2">
                                            <small class="text-muted f-s-11">
                                                <i class="ph-duotone ph-eye me-1"></i>{{ $video->view_count ?? $video->views }}
                                            </small>
                                            <small class="text-muted f-s-11">
                                                <i class="ph-duotone ph-heart me-1"></i>{{ $video->like_count }}
                                            </small>
                                            <small class="text-muted f-s-11">
                                                <img src="{{ asset('assets/images/snap.png') }}" alt="Snap" style="width: 12px; height: 12px; filter: brightness(0) saturate(100%) invert(60%) sepia(0%) saturate(0%) hue-rotate(0deg) brightness(89%) contrast(86%);" class="me-1">{{ $video->snap_count ?? 0 }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <div class="bg-light-info h-50 w-50 d-flex-center rounded-circle m-auto mb-2">
                                    <i class="ph-duotone ph-video-camera-slash f-s-24 text-info"></i>
                                </div>
                                <p class="text-muted f-s-14 mb-0">Nessun video nuovo</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Video Player Modal a Tutta Pagina -->
<div class="modal fade" id="videoPlayerModal" tabindex="-1" aria-labelledby="videoPlayerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content bg-dark">
            <div class="modal-header bg-dark border-0">
                <h5 class="modal-title text-white" id="videoPlayerModalLabel">Video Player</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 position-relative">
                <!-- Loading indicator -->
                <div class="text-center position-absolute top-50 start-50 translate-middle" id="modalVideoLoading">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Caricamento video...</span>
                    </div>
                    <p class="mt-2 text-white">Caricamento video...</p>
                </div>

                <!-- Error message -->
                <div class="alert alert-danger position-absolute top-50 start-50 translate-middle" id="modalVideoError" style="display: none; z-index: 1000;">
                    <i class="ph-duotone ph-warning f-s-16 me-2"></i>
                    <span id="modalErrorMessage">Errore nel caricamento del video</span>
                </div>

                <!-- Video Container -->
                <div class="video-container position-relative d-flex align-items-center justify-content-center" id="modalVideoContainer" style="display: none; height: 100vh;">
                    <div class="video-wrapper" style="max-width: 80%; max-height: 80%; width: 100%;">
                        <!-- Video Player per video locali -->
                        <video
                            id="modalVideoPlayer"
                            class="w-100 h-100"
                            style="object-fit: contain; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); display: none;"
                            preload="metadata"
                            controls>
                            Il tuo browser non supporta la riproduzione video.
                        </video>

                        <!-- Iframe per video PeerTube -->
                        <iframe
                            id="modalPeerTubePlayer"
                            class="w-100 h-100"
                            style="object-fit: contain; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); display: none; border: none;"
                            allowfullscreen>
                        </iframe>
                    </div>

                    <!-- Pulsante per creare snap al hover -->
                    <div class="position-absolute" id="modalFloatingSnapButton" style="opacity: 0; transition: opacity 0.3s ease; z-index: 20; top: 20px; right: 20px;">
                        <button type="button" class="btn btn-gradient-success hover-effect rounded-circle shadow-lg"
                                style="width: 60px; height: 60px;"
                                onclick="createSnapAtCurrentTime()"
                                title="Crea snap al tempo corrente">
                            <img src="{{ asset('assets/images/snap.png') }}" alt="Snap" style="width: 28px; height: 28px; filter: brightness(0) invert(1);">
                        </button>
                    </div>

                    <!-- Snap Markers sulla Progress Bar del Player -->
                    <div class="snap-markers-overlay position-absolute" id="modalSnapMarkers" style="bottom: 0; left: 0; right: 0; height: 40px; pointer-events: none;">
                        <!-- Snap markers verranno aggiunti dinamicamente -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Variabili globali per il modal
let modalVideoPlayer = null;
let modalCurrentVideoTime = 0;
let modalVideoDuration = 0;
let modalSnaps = [];

function toggleVideoContent() {
    const toggle = document.getElementById('videoToggle');
    const popularVideos = document.getElementById('popularVideos');
    const newVideos = document.getElementById('newVideos');
    const popularLabel = document.getElementById('popularLabel');
    const newLabel = document.getElementById('newLabel');

    if (toggle.checked) {
        // Mostra nuovi
        popularVideos.style.display = 'none';
        newVideos.style.display = 'block';
        popularLabel.classList.remove('text-primary');
        popularLabel.classList.add('text-muted');
        newLabel.classList.remove('text-muted');
        newLabel.classList.add('text-primary');
    } else {
        // Mostra popolari
        popularVideos.style.display = 'block';
        newVideos.style.display = 'none';
        popularLabel.classList.remove('text-muted');
        popularLabel.classList.add('text-primary');
        newLabel.classList.remove('text-primary');
        newLabel.classList.add('text-muted');
    }
}

// Funzione per aprire il modal video
function openVideoModal(videoId) {
    console.log('🎬 Apertura modal video per ID:', videoId);

    // Mostra il modal
    const modal = new bootstrap.Modal(document.getElementById('videoPlayerModal'));
    modal.show();

    // Carica il video
    loadVideoInModal(videoId);
}

// Funzione per caricare il video nel modal
function loadVideoInModal(videoId) {
    const loadingDiv = document.getElementById('modalVideoLoading');
    const errorDiv = document.getElementById('modalVideoError');
    const containerDiv = document.getElementById('modalVideoContainer');
    const videoPlayer = document.getElementById('modalVideoPlayer');

    // Mostra loading
    loadingDiv.style.display = 'block';
    errorDiv.style.display = 'none';
    containerDiv.style.display = 'none';

    // Fetch dei dati del video
    fetch(`/api/videos/${videoId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const video = data.video;

                // Imposta il titolo del modal
                document.getElementById('videoPlayerModalLabel').textContent = video.title;

                // Determina il tipo di video e imposta il player appropriato
                const videoPlayer = document.getElementById('modalVideoPlayer');
                const peerTubePlayer = document.getElementById('modalPeerTubePlayer');

                if (video.peertube_embed_url || video.is_uploaded_to_peertube) {
                    // Video PeerTube - usa iframe
                    videoPlayer.style.display = 'none';
                    peerTubePlayer.style.display = 'block';

                    const embedUrl = video.peertube_embed_url || video.peertube_url;
                    peerTubePlayer.src = embedUrl;
                    peerTubePlayer.setAttribute('data-video-id', video.id);
                } else {
                    // Video locale - usa tag video
                    videoPlayer.style.display = 'block';
                    peerTubePlayer.style.display = 'none';

                    if (video.video_url) {
                        videoPlayer.src = video.video_url;
                    } else {
                        throw new Error('Nessuna sorgente video disponibile');
                    }

                    // Imposta l'ID del video per le funzioni snap
                    videoPlayer.setAttribute('data-video-id', video.id);
                }

                // Carica gli snap
                loadSnapsForModal(videoId);

                // Nascondi loading e mostra video
                loadingDiv.style.display = 'none';
                containerDiv.style.display = 'block';

                // Inizializza il player
                initializeModalVideoPlayer(video);

            } else {
                throw new Error(data.message || 'Errore nel caricamento del video');
            }
        })
        .catch(error => {
            console.error('❌ Errore caricamento video:', error);
            loadingDiv.style.display = 'none';
            errorDiv.style.display = 'block';
            document.getElementById('modalErrorMessage').textContent = error.message;
        });
}

// Funzione per caricare gli snap nel modal
function loadSnapsForModal(videoId) {
    fetch(`/api/videos/${videoId}/snaps`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                modalSnaps = data.snaps;
                updateModalSnapMarkers();
            }
        })
        .catch(error => {
            console.error('❌ Errore caricamento snap:', error);
            modalSnaps = [];
        });
}

// Funzione per inizializzare il player del modal
function initializeModalVideoPlayer(video) {
    const videoPlayer = document.getElementById('modalVideoPlayer');
    const peerTubePlayer = document.getElementById('modalPeerTubePlayer');
    modalVideoDuration = video.duration || 60;

    if (video.peertube_embed_url || video.is_uploaded_to_peertube) {
        // Video PeerTube - usa iframe
        modalVideoPlayer = peerTubePlayer;
        console.log('🎬 Video PeerTube caricato nel modal');
    } else {
        // Video locale - usa tag video
        modalVideoPlayer = videoPlayer;

        // Event listeners solo per video locali
        videoPlayer.addEventListener('loadedmetadata', function() {
            console.log('🎬 Video caricato nel modal - Durata:', videoPlayer.duration);
            modalVideoDuration = videoPlayer.duration || modalVideoDuration;
            updateModalSnapMarkers();
        });

        videoPlayer.addEventListener('timeupdate', function() {
            modalCurrentVideoTime = videoPlayer.currentTime;
        });
    }

    // Hover per il pulsante snap
    const videoContainer = document.getElementById('modalVideoContainer');
    const snapButton = document.getElementById('modalFloatingSnapButton');

    videoContainer.addEventListener('mouseenter', function() {
        snapButton.style.opacity = '1';
    });

    videoContainer.addEventListener('mouseleave', function() {
        snapButton.style.opacity = '0';
    });
}

// Funzione per aggiornare i marker degli snap nel modal
function updateModalSnapMarkers() {
    const markersContainer = document.getElementById('modalSnapMarkers');
    markersContainer.innerHTML = '';

    if (!modalSnaps || modalSnaps.length === 0) return;

    // Raggruppa gli snap per timestamp
    const snapsByTimestamp = {};
    modalSnaps.forEach(snap => {
        if (!snapsByTimestamp[snap.timestamp]) {
            snapsByTimestamp[snap.timestamp] = [];
        }
        snapsByTimestamp[snap.timestamp].push(snap);
    });

    // Crea i marker
    Object.keys(snapsByTimestamp).forEach(timestamp => {
        const snapsAtTime = snapsByTimestamp[timestamp];
        const snapCount = snapsAtTime.length;
        const firstSnap = snapsAtTime[0];

        const percentage = (timestamp / modalVideoDuration) * 100;
        const leftPosition = percentage + '%';

        const marker = document.createElement('div');
        marker.className = 'snap-marker position-absolute';
        marker.style.cssText = `left: ${leftPosition}; transform: translateX(-50%); pointer-events: auto; cursor: pointer;`;
        marker.setAttribute('data-timestamp', timestamp);
        marker.onclick = () => seekToTimeInModal(timestamp);
        marker.title = `${firstSnap.title} (${snapCount} snap)`;

        marker.innerHTML = `
            <div class="snap-indicator bg-success rounded-circle d-flex align-items-center justify-content-center"
                 style="width: 30px; height: 30px; border: 2px solid white; box-shadow: 0 3px 6px rgba(0,0,0,0.4);">
                <img src="{{ asset('assets/images/snap.png') }}" alt="Snap" style="width: 16px; height: 16px; filter: brightness(0) invert(1);">
            </div>
            ${snapCount > 1 ? `
                <div class="position-absolute top-0 end-0 bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center"
                     style="width: 24px; height: 24px; font-size: 12px; font-weight: bold; transform: translate(30%, -30%);">
                    ${snapCount}
                </div>
            ` : ''}
        `;

        markersContainer.appendChild(marker);
    });
}

// Funzione per saltare al tempo specifico nel modal
function seekToTimeInModal(timestamp) {
    if (modalVideoPlayer) {
        modalVideoPlayer.currentTime = timestamp;
        modalVideoPlayer.play();
    }
}

// Funzione per creare snap al tempo corrente nel modal
function createSnapAtCurrentTime() {
    if (!modalVideoPlayer) return;

    const currentTime = Math.floor(modalVideoPlayer.currentTime);
    const videoId = modalVideoPlayer.getAttribute('data-video-id');

    console.log('🎯 Creazione snap nel modal - Tempo:', currentTime, 'Video ID:', videoId);

    // Qui puoi implementare la logica per creare lo snap
    // Per ora mostriamo un alert
    alert(`Snap creato al tempo ${currentTime} secondi!`);
}

// Event listeners per i label
document.addEventListener('DOMContentLoaded', function() {
    const popularLabel = document.getElementById('popularLabel');
    const newLabel = document.getElementById('newLabel');
    const toggle = document.getElementById('videoToggle');

    popularLabel.addEventListener('click', function() {
        toggle.checked = false;
        toggleVideoContent();
    });

    newLabel.addEventListener('click', function() {
        toggle.checked = true;
        toggleVideoContent();
    });
});
</script>
@endpush
