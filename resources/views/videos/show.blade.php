@extends('layout.master')

@section('title', $video->title)

@section('main-content')
@if(!$video)
    <div class="page-content">
        <div class="container-fluid">
            <div class="alert alert-danger">
                <i class="ph-duotone ph-warning f-s-16 me-2"></i>
                Video non trovato.
            </div>
        </div>
    </div>
@else
<div class="page-content">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">{{ $video->title }}</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a href="{{ route('dashboard') }}" class="f-s-14 f-w-500">
                            <span>
                                <i class="ph-duotone ph-house f-s-16"></i> {{ __('dashboard.dashboard') }}
                            </span>
                        </a>
                    </li>
                    <li class="">
                        <a href="{{ route('profile.videos') }}" class="f-s-14 f-w-500">
                            <span>
                                <i class="ph-duotone ph-video-camera f-s-16"></i> {{ __('videos.videos') }}
                            </span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="#" class="f-s-14 f-w-500">{{ $video->title }}</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Video Player -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card hover-effect">
                    <div class="card-body p-0">
                        @if($video->isUploadedToPeerTube() && $video->peertube_embed_url)
                            <!-- Player HTML5 Nativo con URL Diretto PeerTube -->
                            <div class="video-container position-relative">
                                <video
                                    id="videoPlayer"
                                    class="w-100"
                                    style="height: 500px; max-height: 500px; object-fit: cover;"
                                    preload="metadata"
                                    data-duration="{{ $video->duration ?? 60 }}"
                                    data-video-id="{{ $video->id }}"
                                    controls>
                                    Il tuo browser non supporta la riproduzione video.
                                </video>

                                                                                                                                                                                                                                                                <!-- Pulsante per creare snap al hover -->
                                <div class="position-absolute top-50 end-0 translate-middle-y me-4" id="floatingSnapButton" style="opacity: 0; transition: opacity 0.3s ease; z-index: 20;">
                                    <button type="button" class="btn btn-gradient-success hover-effect rounded-circle shadow-lg"
                                            style="width: 70px; height: 70px;"
                                            onclick="createSnapAtCurrentTime()"
                                            title="Crea snap al tempo corrente">
                                        <i class="ph-duotone ph-hands-clapping f-s-28 text-white"></i>
                                    </button>
                                </div>

                                <!-- Snap Markers sulla Progress Bar del Player -->
                                <div class="snap-markers-overlay position-absolute" style="bottom: 0; left: 0; right: 0; height: 40px; pointer-events: none;">




                                    @php
                                        // Raggruppa gli snap per timestamp
                                        $snapsByTimestamp = $snaps->groupBy('timestamp');
                                    @endphp

                                    @foreach($snapsByTimestamp as $timestamp => $snapsAtTime)
                                        @php
                                            $snapCount = $snapsAtTime->count();
                                            $firstSnap = $snapsAtTime->first();

                                            // Usa la durata reale del video o fallback
                                            $videoDuration = $video->duration ?? 60;
                                            if ($videoDuration <= 0) {
                                                $videoDuration = 60; // Fallback
                                            }

                                            $percentage = ($timestamp / $videoDuration) * 100;
                                            $leftPosition = $percentage . '%';
                                        @endphp

                                        <div class="snap-marker position-absolute"
                                             style="left: {{ $leftPosition }}; transform: translateX(-50%); pointer-events: auto; cursor: pointer;"
                                             data-timestamp="{{ $timestamp }}"
                                             onclick="seekToTime({{ $timestamp }})"
                                             title="{{ $firstSnap->display_title }} ({{ $snapCount }} snap)">

                                                                                        <!-- Marker principale -->
                                            <div class="snap-indicator bg-info rounded-circle d-flex align-items-center justify-content-center"
                                                 style="width: 28px; height: 28px; border: 3px solid white; box-shadow: 0 3px 6px rgba(0,0,0,0.4);">
                                                <i class="ph-duotone ph-hands-clapping f-s-16 text-white"></i>
                                            </div>

                                            <!-- Badge per numero di snap -->
                                            @if($snapCount > 1)
                                                <div class="position-absolute top-0 end-0 bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center"
                                                     style="width: 24px; height: 24px; font-size: 12px; font-weight: bold; transform: translate(30%, -30%);">
                                                    {{ $snapCount }}
                                                </div>
                                            @endif

                                            <!-- Tooltip -->
                                            <div class="snap-tooltip position-absolute bottom-100 start-50 translate-middle-x mb-1 bg-dark text-white rounded p-2"
                                                 style="font-size: 11px; white-space: nowrap; opacity: 0; transition: opacity 0.2s ease; pointer-events: none;">
                                                <strong>{{ $firstSnap->display_title }}</strong>
                                                @if($snapCount > 1)
                                                    <br><small>+{{ $snapCount - 1 }} altri</small>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Loading indicator -->
                            <div class="text-center mt-3" id="videoLoading">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Caricamento video...</span>
                                </div>
                                <p class="mt-2 text-muted">Caricamento video...</p>
                            </div>

                            <!-- Error message -->
                            <div class="alert alert-danger mt-3" id="videoError" style="display: none;">
                                <i class="ph-duotone ph-warning f-s-16 me-2"></i>
                                <span id="errorMessage">Errore nel caricamento del video</span>
                            </div>
                        @elseif($video->file_path && Storage::exists($video->file_path))
                            <!-- Player locale (fallback) -->
                            <video controls class="w-100" style="max-height: 500px;">
                                <source src="{{ Storage::url($video->file_path) }}" type="video/mp4">
                                Il tuo browser non supporta la riproduzione video.
                            </video>
                        @else
                            <!-- Video non disponibile -->
                            <div class="d-flex align-items-center justify-content-center" style="height: 500px; background-color: #f8f9fa;">
                                <div class="text-center">
                                    <i class="ph-duotone ph-video-camera-slash f-s-48 text-muted mb-3"></i>
                                    <h5 class="text-muted">Video non disponibile</h5>
                                    <p class="text-muted">Il video potrebbe essere in fase di elaborazione o non essere più disponibile.</p>
                                    @if($video->peertube_url)
                                        <a href="{{ $video->peertube_url }}" target="_blank" class="btn btn-primary">
                                            <i class="ph-duotone ph-external-link me-1"></i>Apri su PeerTube
                                        </a>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Video Info -->
                <div class="card hover-effect mt-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="card-title mb-1">{{ $video->title }}</h5>
                                <p class="text-muted mb-0">
                                    <i class="ph-duotone ph-eye f-s-14 me-1"></i>
                                    <span id="viewCount">{{ $video->view_count }}</span> {{ __('videos.views') }}
                                </p>
                            </div>
                            <div class="d-flex gap-2">
                                @if($video->is_public)
                                    <span class="badge bg-success f-s-12">
                                        <i class="ph-duotone ph-globe f-s-12 me-1"></i>Pubblico
                                    </span>
                                @else
                                    <span class="badge bg-warning f-s-12">
                                        <i class="ph-duotone ph-lock f-s-12 me-1"></i>Privato
                                    </span>
                                @endif

                                @if($video->moderation_status === 'approved')
                                    <span class="badge bg-success f-s-12">
                                        <i class="ph-duotone ph-check-circle f-s-12 me-1"></i>Approvato
                                    </span>
                                @elseif($video->moderation_status === 'pending')
                                    <span class="badge bg-warning f-s-12">
                                        <i class="ph-duotone ph-clock f-s-12 me-1"></i>In attesa
                                    </span>
                                @else
                                    <span class="badge bg-danger f-s-12">
                                        <i class="ph-duotone ph-x-circle f-s-12 me-1"></i>Rifiutato
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if($video->description)
                            <p class="card-text">{{ $video->description }}</p>
                        @endif

                        <!-- Video Actions -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex gap-2">
                                <!-- Like Button -->
                                <button type="button" class="btn btn-outline-primary hover-effect" onclick="toggleLike('like')" id="likeBtn">
                                    <i class="ph-duotone ph-thumbs-up f-s-14 me-1"></i>
                                    <span id="likeCount">{{ $video->like_count }}</span>
                                </button>

                                <!-- Dislike Button -->
                                <button type="button" class="btn btn-outline-secondary hover-effect" onclick="toggleLike('dislike')" id="dislikeBtn">
                                    <i class="ph-duotone ph-thumbs-down f-s-14 me-1"></i>
                                    <span id="dislikeCount">{{ $video->dislike_count }}</span>
                                </button>

                                            <!-- Snap Button -->
            <button type="button" class="btn btn-outline-dark hover-effect" onclick="showSnapModal()">
                <i class="ph-duotone ph-hands-clapping f-s-14 me-1"></i>
                Snap
            </button>
                            </div>

                            <small class="text-muted">
                                <i class="ph-duotone ph-calendar f-s-12 me-1"></i>
                                {{ __('videos.uploaded_on') }} {{ $video->created_at->format('d/m/Y') }}
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="card hover-effect mt-3">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="ph-duotone ph-chat-circle f-s-16 me-2"></i>
                            Commenti (<span id="commentCount">{{ $video->comment_count }}</span>)
                        </h6>
                    </div>
                    <div class="card-body">
                        <!-- Add Comment Form -->
                        <div class="mb-4">
                            @auth
                            <form id="commentForm">
                                <div class="mb-3">
                                    <textarea class="form-control" id="commentContent" rows="3" placeholder="Scrivi un commento..." maxlength="1000"></textarea>
                                    <div class="form-text">
                                        <span id="charCount">0</span>/1000 caratteri
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary hover-effect">
                                    <i class="ph-duotone ph-paper-plane f-s-14 me-1"></i>
                                    Pubblica Commento
                                </button>
                            </form>
                            @else
                            <div class="alert alert-info">
                                <i class="ph-duotone ph-info f-s-16 me-2"></i>
                                <a href="{{ route('login') }}">Accedi</a> per lasciare un commento.
                            </div>
                            @endauth
                        </div>

                        <!-- Comments List -->
                        <div id="commentsList">
                            @foreach($comments as $comment)
                                <div class="Comment-box mb-3" id="comment-{{ $comment->id }}">
                                    <div class="d-flex align-items-center">
                                        <div class="h-45 w-45 d-flex-center b-r-50 overflow-hidden bg-primary">
                                            @if($comment->user->profile_photo)
                                                <img src="{{ $comment->user->profile_photo_url }}" alt="" class="img-fluid">
                                            @else
                                                <span class="text-white fw-bold">{{ substr($comment->user->name, 0, 2) }}</span>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1 ps-2 pe-2">
                                            <div class="f-w-600">{{ $comment->user->name }}</div>
                                            <div class="text-muted f-s-12">{{ $comment->created_at->diffForHumans() }}</div>
                                        </div>
                                        @if(auth()->id() === $comment->user_id || auth()->user()->isModerator())
                                            <div class="dropdown">
                                                <button class="btn btn-link p-0" type="button" data-bs-toggle="dropdown">
                                                    <i class="ti ti-dots-vertical"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item text-danger" href="#" onclick="deleteComment({{ $comment->id }})">
                                                        <i class="ti ti-trash f-s-14 me-1"></i> Elimina
                                                    </a></li>
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="mt-2">
                                        <p class="mb-0">{{ $comment->content }}</p>
                                        @if($comment->timestamp)
                                            <small class="text-muted">
                                                <i class="ph-duotone ph-clock f-s-12 me-1"></i>
                                                {{ $comment->formatted_timestamp }}
                                            </small>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Video Stats -->
                <div class="card hover-effect">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="ph-duotone ph-chart-line f-s-16 me-2"></i>
                            Statistiche
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="border-end">
                                    <h5 class="mb-1" id="viewCountStats">{{ $video->view_count }}</h5>
                                    <small class="text-muted">{{ __('videos.views') }}</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border-end">
                                    <h5 class="mb-1" id="likeCountStats">{{ $video->like_count }}</h5>
                                    <small class="text-muted">Mi piace</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <h5 class="mb-1" id="commentCountStats">{{ $video->comment_count }}</h5>
                                <small class="text-muted">Commenti</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Video Details -->
                <div class="card hover-effect mt-3">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="ph-duotone ph-info f-s-16 me-2"></i>
                            Dettagli
                        </h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            @if($video->duration)
                                <li class="mb-2">
                                    <i class="ph-duotone ph-clock f-s-14 me-2 text-muted"></i>
                                    Durata: {{ $video->formatted_duration }}
                                </li>
                            @endif
                            @if($video->file_size)
                                <li class="mb-2">
                                    <i class="ph-duotone ph-hard-drive f-s-14 me-2 text-muted"></i>
                                    Dimensione: {{ $video->formatted_file_size }}
                                </li>
                            @endif
                            @if($video->resolution)
                                <li class="mb-2">
                                    <i class="ph-duotone ph-monitor f-s-14 me-2 text-muted"></i>
                                    Risoluzione: {{ $video->resolution }}
                                </li>
                            @endif
                            <li class="mb-2">
                                <i class="ph-duotone ph-user f-s-14 me-2 text-muted"></i>
                                Autore: {{ $video->user->name }}
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Snaps Section -->
                @if($snaps->count() > 0)
                <div class="card hover-effect mt-3">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="ph-duotone ph-hands-clapping f-s-16 me-2"></i>
                            Snap Popolari
                        </h6>
                    </div>
                    <div class="card-body">
                        @foreach($snaps->take(5) as $snap)
                            <div class="d-flex align-items-center mb-2">
                                <div class="flex-shrink-0 position-relative">
                                    @if($video->thumbnail_path)
                                        <img src="{{ Storage::url($video->thumbnail_path) }}" alt="Snap" class="rounded" style="width: 40px; height: 30px; object-fit: cover;">
                                    @elseif($video->peertube_thumbnail_url)
                                        <img src="{{ $video->peertube_thumbnail_url }}" alt="Snap" class="rounded" style="width: 40px; height: 30px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 30px;">
                                            <i class="ph-duotone ph-video-camera f-s-16 text-muted"></i>
                                        </div>
                                    @endif
                                    <div class="position-absolute top-0 end-0 bg-info text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 16px; height: 16px; font-size: 8px; transform: translate(25%, -25%);">
                                        <i class="ph-duotone ph-hands-clapping f-s-8"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-2">
                                    <h6 class="mb-0 f-s-14">{{ $snap->display_title }}</h6>
                                    <small class="text-muted">{{ $snap->formatted_timestamp }}</small>
                                </div>
                                <div class="flex-shrink-0">
                                    <button class="btn btn-sm btn-outline-primary" onclick="seekToTime({{ $snap->timestamp }})">
                                        <i class="ph-duotone ph-play f-s-12"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Snap Modal -->
<div class="modal fade" id="snapModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Crea Snap</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="snapForm">
                    <div class="mb-3">
                        <label for="snapTitle" class="form-label">Titolo (opzionale)</label>
                        <input type="text" class="form-control" id="snapTitle" placeholder="Titolo dello snap">
                    </div>
                    <div class="mb-3">
                        <label for="snapDescription" class="form-label">Descrizione (opzionale)</label>
                        <textarea class="form-control" id="snapDescription" rows="3" placeholder="Descrizione dello snap"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Timestamp: <span id="currentTime">00:00</span></label>
                        <input type="hidden" id="snapTimestamp" value="0">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" onclick="createSnap()">Crea Snap</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<style>
/* Snap Markers sulla Progress Bar del Player */
.snap-markers-overlay {
    z-index: 10;
}

.snap-marker {
    transition: all 0.2s ease;
}

.snap-marker:hover .snap-indicator {
    transform: scale(1.3);
    box-shadow: 0 4px 8px rgba(0,0,0,0.4) !important;
}

.snap-marker:hover .snap-tooltip {
    opacity: 1 !important;
}

.snap-indicator {
    transition: all 0.2s ease;
}

/* Assicura che i marker siano sopra i controlli del player */
video::-webkit-media-controls {
    z-index: 5;
}

video::-webkit-media-controls-panel {
    z-index: 5;
}
</style>
<script>
// Variabili globali per il player HTML5
let videoPlayer = null;
let currentVideoTime = 0;
let videoDuration = {{ $video->duration ?? 60 }};
let isVideoPlaying = false;
let isFullscreen = false;

// Funzione globale per incrementare le visualizzazioni
function incrementVideoViews() {
    console.log('Incrementando visualizzazioni per video {{ $video->id }}');

    fetch('{{ route("videos.increment-views", $video) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            document.getElementById('viewCount').textContent = data.view_count;
            document.getElementById('viewCountStats').textContent = data.view_count;
            console.log('Views updated to:', data.view_count);
        }
    })
    .catch(error => {
        console.error('Errore nell\'incremento delle visualizzazioni:', error);
    });
}

// Inizializzazione del player HTML5
document.addEventListener('DOMContentLoaded', function() {
    const snapModal = document.getElementById('snapModal');

    // Event listener per quando il modal snap viene chiuso
    if (snapModal) {
        snapModal.addEventListener('hidden.bs.modal', function() {
            // Rimuovi l'attributo del timestamp fisso quando il modal viene chiuso
            this.removeAttribute('data-fixed-timestamp');
        });
    }

    // Inizializza il player HTML5
    initializeVideoPlayer();
});

// Funzioni per like/dislike
function toggleLike(type) {
    fetch('{{ route("videos.toggle-like", $video) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ type: type })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('likeCount').textContent = data.like_count;
            document.getElementById('dislikeCount').textContent = data.dislike_count;
            updateLikeButtons(data.user_like);
        }
    })
    .catch(error => console.error('Errore:', error));
}

function updateLikeButtons(userLike) {
    const likeBtn = document.getElementById('likeBtn');
    const dislikeBtn = document.getElementById('dislikeBtn');

    // Reset
    likeBtn.classList.remove('btn-primary', 'btn-outline-primary');
    dislikeBtn.classList.remove('btn-secondary', 'btn-outline-secondary');
    likeBtn.classList.add('btn-outline-primary');
    dislikeBtn.classList.add('btn-outline-secondary');

    if (userLike === 'like') {
        likeBtn.classList.remove('btn-outline-primary');
        likeBtn.classList.add('btn-primary');
    } else if (userLike === 'dislike') {
        dislikeBtn.classList.remove('btn-outline-secondary');
        dislikeBtn.classList.add('btn-secondary');
    }
}

// Funzioni per commenti
document.getElementById('commentForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const content = document.getElementById('commentContent').value.trim();
    if (!content) return;

    fetch('{{ route("videos.add-comment", $video) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ content: content })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('commentContent').value = '';
            document.getElementById('charCount').textContent = '0';
            loadComments();
        }
    })
    .catch(error => console.error('Errore:', error));
});

function loadComments() {
    // I commenti sono già caricati nel DOM, non serve ricaricarli
    // Questa funzione è mantenuta per compatibilità
}

function deleteComment(commentId) {
    if (!confirm('Sei sicuro di voler eliminare questo commento?')) return;

    fetch(`{{ route('videos.delete-comment', '') }}/${commentId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadComments();
        }
    })
    .catch(error => console.error('Errore:', error));
}

// Funzioni per snap
function showSnapModal() {
    console.log('🎯 Apertura modal snap - tempo corrente:', currentVideoTime);

    // Ferma il video se è in riproduzione
    if (videoPlayer && !videoPlayer.paused) {
        videoPlayer.pause();
        console.log('⏸️ Video fermato per creazione snap');
    }

    // Aggiorna il tempo nel modal prima di mostrarlo
    updateSnapModalTime();

    const modal = new bootstrap.Modal(document.getElementById('snapModal'));
    modal.show();
}

function createSnap() {
    const title = document.getElementById('snapTitle').value.trim();
    const timestamp = parseInt(document.getElementById('snapTimestamp').value);

    console.log('🎯 Creazione snap - title:', title, 'timestamp:', timestamp);

    if (!title || timestamp < 0) {
        console.log('❌ Validazione fallita - title:', title, 'timestamp:', timestamp);
        return;
    }

    fetch('{{ route("videos.add-snap", $video) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ title: title, timestamp: timestamp })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('✅ Snap creato con successo:', data.snap);

            document.getElementById('snapTitle').value = '';
            document.getElementById('snapTimestamp').value = '0';
            document.getElementById('currentTime').textContent = '00:00';

            const modal = bootstrap.Modal.getInstance(document.getElementById('snapModal'));
            modal.hide();

            // Ricarica la pagina per aggiornare la timeline
            location.reload();
        } else {
            console.log('❌ Errore nella creazione dello snap:', data);
        }
    })
    .catch(error => {
        console.error('❌ Errore nella creazione dello snap:', error);
    });
}

function deleteSnap(snapId) {
    if (!confirm('Sei sicuro di voler eliminare questo snap?')) return;

    fetch(`{{ route('videos.delete-snap', '') }}/${snapId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Errore:', error));
}

// Contatore caratteri per commenti
document.getElementById('commentContent').addEventListener('input', function() {
    const charCount = this.value.length;
    document.getElementById('charCount').textContent = charCount;
});

// Inizializzazione
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - Inizializzazione...');

    // Inizializza lo stile dei bottoni like
    @if($userLike)
        updateLikeButtons('{{ $userLike->type }}');
    @endif

    // Incrementa le visualizzazioni
    incrementVideoViews();

    // Sistema per rilevare interazioni con la timeline
    console.log('Inizializzazione event listeners...');

    // Rileva click sui snap markers
document.addEventListener('click', function(event) {
    if (event.target.closest('.snap-marker')) {
        console.log('🔥 Click su snap marker rilevato!');

        // Ottieni il timestamp dal marker
        const marker = event.target.closest('.snap-marker');
        const timestamp = parseInt(marker.getAttribute('data-timestamp'));

        console.log('🎯 Timestamp del snap:', timestamp);

        // Salta al timestamp del snap
        seekToTime(timestamp);
    }
});



    // Aggiorna il tempo nel modal snap ogni secondo quando è aperto
setInterval(function() {
    const snapModal = document.getElementById('snapModal');
    if (snapModal && snapModal.classList.contains('show')) {
        updateSnapModalTime();
    }
}, 1000);

// Debug: mostra lo stato del video ogni 30 secondi se in riproduzione
setInterval(function() {
    if (isVideoPlaying) {
        console.log('📊 Stato video - isVideoPlaying:', isVideoPlaying, 'currentVideoTime:', currentVideoTime, 'videoDuration:', videoDuration);
    }
}, 30000);
});



// Funzioni per la timeline
function seekToTime(timestamp) {
    console.log('seekToTime chiamata con timestamp:', timestamp);

    if (videoPlayer) {
        // Imposta il tempo del video
        videoPlayer.currentTime = timestamp;
        currentVideoTime = timestamp;

        console.log('🎯 Seek a:', formatTimestamp(timestamp));
    }
}



function formatTimestamp(timestamp) {
    const minutes = Math.floor(timestamp / 60);
    const seconds = timestamp % 60;
    return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
}

// Sistema per Player HTML5 con URL Diretto PeerTube
async function initializeVideoPlayer() {
    console.log('🎬 Inizializzazione player HTML5 con URL diretto PeerTube');

    videoPlayer = document.getElementById('videoPlayer');
    if (!videoPlayer) {
        console.log('❌ Player video non trovato');
        return;
    }

    // Ottieni la durata dal video o dal database
    videoDuration = parseInt(videoPlayer.dataset.duration) || {{ $video->duration ?? 60 }};
    const videoId = videoPlayer.dataset.videoId;

    // Mostra loading indicator
    const loading = document.getElementById('videoLoading');
    const error = document.getElementById('videoError');
    if (loading) loading.style.display = 'block';
    if (error) error.style.display = 'none';

    try {
        console.log('🔗 Richiesta URL diretto per video ID:', videoId);

        // Ottieni l'URL diretto del video da PeerTube
        const response = await fetch(`/videos/${videoId}/peertube-url`);
        const data = await response.json();

        if (data.success && data.files && data.files.length > 0) {
            // Usa il primo file disponibile (migliore qualità)
            const videoFile = data.files[0];
            console.log('✅ URL video ottenuto:', videoFile.url);

            // Crea l'elemento source
            const source = document.createElement('source');
            source.src = videoFile.url;
            source.type = 'video/mp4';

            // Rimuovi eventuali source esistenti e aggiungi quello nuovo
            videoPlayer.innerHTML = '';
            videoPlayer.appendChild(source);

            // Aggiorna la durata se disponibile
            if (data.video_info && data.video_info.duration) {
                videoDuration = data.video_info.duration;
                videoPlayer.dataset.duration = videoDuration;

                // Aggiorna la posizione degli snap con la durata reale
                updateSnapPositions(videoDuration);
            }

                // Event listeners per il player
    setupVideoEventListeners();

            // Nascondi loading indicator
            if (loading) loading.style.display = 'none';

            console.log('🎬 Player HTML5 inizializzato con successo - Durata:', videoDuration);
        } else {
            throw new Error(data.error || 'Nessun file video disponibile');
        }
    } catch (error) {
        console.error('❌ Errore nel caricamento del video:', error);

        // Nascondi loading e mostra errore
        if (loading) loading.style.display = 'none';
        if (error) {
            error.style.display = 'block';
            document.getElementById('errorMessage').textContent =
                'Errore nel caricamento del video: ' + error.message;
        }
    }
}

function setupVideoEventListeners() {
    // Event listener per quando il video è caricato
    videoPlayer.addEventListener('loadedmetadata', function() {
        console.log('🎬 Video caricato - Durata:', videoPlayer.duration);
        videoDuration = videoPlayer.duration || videoDuration;
    });

    // Event listener per play
    videoPlayer.addEventListener('play', function() {
        console.log('▶️ Video in riproduzione');
        isVideoPlaying = true;
        incrementVideoViews();
    });

    // Event listener per pause
    videoPlayer.addEventListener('pause', function() {
        console.log('⏸️ Video in pausa');
        isVideoPlaying = false;
    });

    // Event listener per aggiornamento tempo
    videoPlayer.addEventListener('timeupdate', function() {
        currentVideoTime = videoPlayer.currentTime;
    });

    // Event listener per fine video
    videoPlayer.addEventListener('ended', function() {
        console.log('🏁 Video terminato');
        isVideoPlaying = false;
        updatePlayPauseButton();
    });

    // Event listener per errori
    videoPlayer.addEventListener('error', function() {
        console.error('❌ Errore nel video:', videoPlayer.error);

        // Mostra messaggio di errore
        const error = document.getElementById('videoError');
        if (error) {
            error.style.display = 'block';
            document.getElementById('errorMessage').textContent =
                'Errore nella riproduzione del video. Riprova più tardi.';
        }
    });

    // Event listener per quando il video non può essere riprodotto
    videoPlayer.addEventListener('stalled', function() {
        console.log('⚠️ Video in stallo - potrebbe non essere accessibile');
    });

    // Event listener per quando il video non ha dati
    videoPlayer.addEventListener('waiting', function() {
        console.log('⏳ Video in attesa di dati');
    });

            // Event listener per l'icona snap
    const videoContainer = document.querySelector('.video-container');
    const floatingButton = document.getElementById('floatingSnapButton');

    if (videoContainer && floatingButton) {
        videoContainer.addEventListener('mouseenter', function() {
            floatingButton.style.opacity = '1';
        });

        videoContainer.addEventListener('mouseleave', function() {
            floatingButton.style.opacity = '0';
        });
    }
}









// Funzione per creare snap al tempo corrente
function createSnapAtCurrentTime() {
    console.log('🎯 Creazione snap al tempo corrente:', currentVideoTime);

    // Ferma il video
    if (videoPlayer && !videoPlayer.paused) {
        videoPlayer.pause();
        console.log('⏸️ Video fermato per creazione snap');
    }

    // Mostra il modal con il tempo corrente
    showSnapModal();
}

// Funzione per aggiornare le posizioni degli snap
function updateSnapPositions(realDuration) {
    console.log('🔄 Aggiornamento posizioni snap con durata reale:', realDuration);

    const snapMarkers = document.querySelectorAll('.snap-marker');
    snapMarkers.forEach(marker => {
        const timestamp = parseInt(marker.getAttribute('data-timestamp'));
        const percentage = (timestamp / realDuration) * 100;
        marker.style.left = percentage + '%';
    });
}

// Funzione per aggiornare il tempo nel modal snap
function updateSnapModalTime() {
    const currentTimeElement = document.getElementById('currentTime');
    const timestampElement = document.getElementById('snapTimestamp');

    if (currentTimeElement && timestampElement) {
        currentTimeElement.textContent = formatTimestamp(currentVideoTime);
        timestampElement.value = Math.floor(currentVideoTime);
    }
}
</script>
@endif
@endsection
