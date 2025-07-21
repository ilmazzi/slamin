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
                        <video id="videoPlayer" controls class="w-100" style="max-height: 500px;">
                            <source src="{{ Storage::url($video->file_path) }}" type="video/mp4">
                            Il tuo browser non supporta la riproduzione video.
                        </video>

                                                <!-- Timeline personalizzata con snap -->
                        <div class="p-3 border-top">
                            <div class="d-flex align-items-center justify-content-end mb-2">
                                <small class="text-muted" id="currentTimeDisplay">00:00 / {{ $video->formatted_duration }}</small>
                            </div>
                            <div class="position-relative" style="height: 40px;">
                                <div class="progress" style="height: 10px; background-color: #e9ecef; cursor: pointer;" onclick="seekToPosition(event)">
                                    <div class="progress-bar bg-primary" id="videoProgress" style="width: 0%"></div>
                                </div>

                                                                                                @php
                                    // Raggruppa gli snap per timestamp
                                    $snapsByTimestamp = $snaps->groupBy('timestamp');
                                @endphp

                                @foreach($snapsByTimestamp as $timestamp => $snapsAtTime)
                                                                            @php
                                            $snapCount = $snapsAtTime->count();
                                            $firstSnap = $snapsAtTime->first();

                                            // Calcola la posizione sulla timeline
                                            // Se il video non ha durata, usa una durata di fallback basata sul timestamp più alto
                                            $maxTimestamp = $snaps->max('timestamp');
                                            $effectiveDuration = $video->duration > 0 ? $video->duration : max($maxTimestamp + 10, 60); // Almeno 60 secondi o timestamp max + 10
                                            $position = ($timestamp / $effectiveDuration) * 100;
                                        @endphp
                                                                                <div class="snap-marker"
                                             style="position: absolute; left: {{ $position }}%; top: 50%; transform: translate(-50%, -50%);"
                                             data-timestamp="{{ $timestamp }}"
                                             onclick="seekToTime({{ $timestamp }})"
                                             title="{{ $snapCount }} snap a {{ $firstSnap->formatted_timestamp }}">
                                            <div class="snap-indicator" style="width: {{ $snapCount > 1 ? '20px' : '16px' }}; height: {{ $snapCount > 1 ? '20px' : '16px' }}; background: {{ $snapCount > 1 ? '#dc3545' : '#17a2b8' }}; border: 2px solid white; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.3); cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; justify-content: center; color: white; font-size: 10px; font-weight: bold;">
                                                @if($snapCount > 1)
                                                    {{ $snapCount }}
                                                @else
                                                    <i class="ph-duotone ph-hands-clapping f-s-10"></i>
                                                @endif
                                            </div>
                                            <div class="snap-tooltip" style="position: absolute; bottom: 30px; left: 50%; transform: translateX(-50%); background: rgba(0,0,0,0.8); color: white; padding: 6px 10px; border-radius: 4px; font-size: 12px; white-space: nowrap; opacity: 0; transition: opacity 0.2s ease; pointer-events: none;">
                                                {{ $snapCount }} snap a {{ $firstSnap->formatted_timestamp }}
                                            </div>
                                        </div>
                                @endforeach
                            </div>
                        </div>
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
.snap-marker:hover .snap-indicator {
    transform: scale(1.3);
    box-shadow: 0 4px 8px rgba(0,0,0,0.4) !important;
}

.snap-marker:hover .snap-indicator[style*="background: #17a2b8"] {
    background: #138496 !important;
}

.snap-marker:hover .snap-indicator[style*="background: #dc3545"] {
    background: #c82333 !important;
}

.snap-marker:hover .snap-tooltip {
    opacity: 1 !important;
}

.snap-indicator {
    transition: all 0.2s ease;
}
</style>
<script>
// Aggiorna la barra di progresso del video
function updateVideoProgress() {
    const videoPlayer = document.getElementById('videoPlayer');
    const progressBar = document.getElementById('videoProgress');
    const currentTimeDisplay = document.getElementById('currentTimeDisplay');
    const snapModal = document.getElementById('snapModal');

    if (videoPlayer && progressBar) {
        const progress = (videoPlayer.currentTime / videoPlayer.duration) * 100;
        progressBar.style.width = progress + '%';
    }

    if (videoPlayer && currentTimeDisplay) {
        const currentTime = Math.floor(videoPlayer.currentTime);
        const duration = Math.floor(videoPlayer.duration);
        const currentFormatted = formatTimestamp(currentTime);
        const durationFormatted = formatTimestamp(duration);
        currentTimeDisplay.textContent = `${currentFormatted} / ${durationFormatted}`;
    }

    // Non aggiornare il timestamp nel modal se è aperto
    if (snapModal && snapModal.classList.contains('show')) {
        return; // Il modal è aperto, non aggiornare il timestamp
    }
}

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

// Event listeners per il video player
document.addEventListener('DOMContentLoaded', function() {
    const videoPlayer = document.getElementById('videoPlayer');
    const snapModal = document.getElementById('snapModal');

    if (videoPlayer) {
        // Aggiorna la barra di progresso durante la riproduzione
        videoPlayer.addEventListener('timeupdate', updateVideoProgress);

        // Incrementa le visualizzazioni quando il video inizia
        videoPlayer.addEventListener('play', incrementVideoViews);

        // Aggiorna posizioni snap quando la durata del video è disponibile
        videoPlayer.addEventListener('loadedmetadata', function() {
            updateSnapPositions();
        });

        // Fallback: aggiorna posizioni anche quando il video inizia a caricare
        videoPlayer.addEventListener('canplay', function() {
            if (videoPlayer.duration > 0) {
                updateSnapPositions();
            }
        });
    }

    // Event listener per quando il modal snap viene chiuso
    if (snapModal) {
        snapModal.addEventListener('hidden.bs.modal', function() {
            // Rimuovi l'attributo del timestamp fisso quando il modal viene chiuso
            this.removeAttribute('data-fixed-timestamp');
        });
    }

});

// Toggle Like/Dislike
function toggleLike(type) {
    fetch('{{ route("videos.toggle-like", $video) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ type: type })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('likeCount').textContent = data.like_count;
            document.getElementById('dislikeCount').textContent = data.dislike_count;
            document.getElementById('likeCountStats').textContent = data.like_count;

            // Aggiorna lo stile dei bottoni
            updateLikeButtons(data.user_like_type);
        }
    })
    .catch(error => {
        console.error('Errore nel toggle like:', error);
    });
}

// Aggiorna lo stile dei bottoni like
function updateLikeButtons(userLikeType) {
    const likeBtn = document.getElementById('likeBtn');
    const dislikeBtn = document.getElementById('dislikeBtn');

    // Reset entrambi i bottoni
    likeBtn.className = 'btn btn-outline-primary hover-effect';
    dislikeBtn.className = 'btn btn-outline-secondary hover-effect';

    // Evidenzia il bottone attivo
    if (userLikeType === 'like') {
        likeBtn.className = 'btn btn-primary hover-effect';
    } else if (userLikeType === 'dislike') {
        dislikeBtn.className = 'btn btn-secondary hover-effect';
    }
}

// Aggiungi commento
const commentForm = document.getElementById('commentForm');
if (commentForm) {
    commentForm.addEventListener('submit', function(e) {
    e.preventDefault();
    console.log('Form commento inviato');

    const content = document.getElementById('commentContent').value.trim();
    if (!content) {
        console.log('Contenuto vuoto, ritorno');
        return;
    }

    const videoPlayer = document.getElementById('videoPlayer');
    const timestamp = Math.floor(videoPlayer.currentTime);

    console.log('Dati da inviare:', { content, timestamp });
    console.log('URL:', '{{ route("videos.add-comment", $video) }}');
    console.log('CSRF Token:', '{{ csrf_token() }}');

    fetch('{{ route("videos.add-comment", $video) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            content: content,
            timestamp: timestamp
        })
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            // Aggiungi il commento alla lista
            addCommentToDOM(data.comment);

            // Reset form
            document.getElementById('commentContent').value = '';
            document.getElementById('charCount').textContent = '0';

            // Aggiorna contatore
            document.getElementById('commentCount').textContent = data.comment_count;
            document.getElementById('commentCountStats').textContent = data.comment_count;
        } else {
            console.error('Errore dal server:', data);
        }
    })
    .catch(error => {
        console.error('Errore nell\'aggiunta del commento:', error);
    });
    });
} else {
    console.error('Form commento non trovato!');
}

// Contatore caratteri per il commento
const commentContent = document.getElementById('commentContent');
const charCount = document.getElementById('charCount');

if (commentContent && charCount) {
    commentContent.addEventListener('input', function() {
        charCount.textContent = this.value.length;
    });
}

// Aggiungi commento al DOM
function addCommentToDOM(comment) {
    const commentsList = document.getElementById('commentsList');
    const commentHtml = `
        <div class="Comment-box mb-3" id="comment-${comment.id}">
            <div class="d-flex align-items-center">
                <div class="h-45 w-45 d-flex-center b-r-50 overflow-hidden bg-primary">
                    ${comment.user.profile_photo_url ?
                        `<img src="${comment.user.profile_photo_url}" alt="" class="img-fluid">` :
                        `<span class="text-white fw-bold">${comment.user.name.substring(0, 2)}</span>`
                    }
                </div>
                <div class="flex-grow-1 ps-2 pe-2">
                    <div class="f-w-600">${comment.user.name}</div>
                    <div class="text-muted f-s-12">Adesso</div>
                </div>
            </div>
            <div class="mt-2">
                <p class="mb-0">${comment.content}</p>
                ${comment.timestamp ? `<small class="text-muted"><i class="ph-duotone ph-clock f-s-12 me-1"></i>${formatTimestamp(comment.timestamp)}</small>` : ''}
            </div>
        </div>
    `;

    commentsList.insertAdjacentHTML('afterbegin', commentHtml);
}

// Elimina commento
function deleteComment(commentId) {
    if (confirm('Sei sicuro di voler eliminare questo commento?')) {
        fetch(`/videos/comments/${commentId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`comment-${commentId}`).remove();
                document.getElementById('commentCount').textContent = data.comment_count;
                document.getElementById('commentCountStats').textContent = data.comment_count;
            }
        })
        .catch(error => {
            console.error('Errore nell\'eliminazione del commento:', error);
        });
    }
}

// Mostra modal snap
function showSnapModal() {
    const videoPlayer = document.getElementById('videoPlayer');
    const currentTime = videoPlayer.currentTime; // Usa il timestamp esatto, non arrotondato
    const minutes = Math.floor(currentTime / 60);
    const seconds = Math.floor(currentTime % 60);

    // Salva il timestamp fisso quando si apre il modal
    const fixedTimestamp = Math.floor(currentTime);

    document.getElementById('currentTime').textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
    document.getElementById('snapTimestamp').value = fixedTimestamp;

    const modal = new bootstrap.Modal(document.getElementById('snapModal'));
    modal.show();

    // Ferma l'aggiornamento del timestamp nel modal
    const modalElement = document.getElementById('snapModal');
    modalElement.setAttribute('data-fixed-timestamp', fixedTimestamp);
}

// Crea snap
function createSnap() {
    const title = document.getElementById('snapTitle').value.trim();
    const description = document.getElementById('snapDescription').value.trim();
    const timestamp = parseInt(document.getElementById('snapTimestamp').value);



    fetch('{{ route("videos.add-snap", $video) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            title: title,
            description: description,
            timestamp: timestamp
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Chiudi modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('snapModal'));
            modal.hide();

            // Reset form
            document.getElementById('snapTitle').value = '';
            document.getElementById('snapDescription').value = '';

            // Ricarica la pagina per mostrare il nuovo snap
            location.reload();
        }
    })
    .catch(error => {
        console.error('Errore nella creazione dello snap:', error);
    });
}

// Vai al timestamp specifico
function seekToTime(timestamp) {
    const videoPlayer = document.getElementById('videoPlayer');
    videoPlayer.currentTime = timestamp;
    videoPlayer.play();
}

// Vai alla posizione cliccata sulla timeline
function seekToPosition(event) {
    const videoPlayer = document.getElementById('videoPlayer');
    const progressBar = event.currentTarget;
    const rect = progressBar.getBoundingClientRect();
    const clickX = event.clientX - rect.left;
    const progressBarWidth = rect.width;
    const percentage = (clickX / progressBarWidth) * 100;
    const newTime = (percentage / 100) * videoPlayer.duration;

    videoPlayer.currentTime = newTime;
}

// Formatta timestamp
function formatTimestamp(timestamp) {
    const minutes = Math.floor(timestamp / 60);
    const seconds = timestamp % 60;
    return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
}

// Aggiorna le posizioni degli snap quando la durata del video è disponibile
function updateSnapPositions() {
    const videoPlayer = document.getElementById('videoPlayer');
    const snapMarkers = document.querySelectorAll('.snap-marker');

    if (!videoPlayer || videoPlayer.duration <= 0) return;

    console.log('Aggiornando posizioni snap. Durata video:', videoPlayer.duration, 'secondi');

    snapMarkers.forEach(marker => {
        const timestamp = parseInt(marker.getAttribute('data-timestamp'));
        if (timestamp) {
            const position = (timestamp / videoPlayer.duration) * 100;
            marker.style.left = position + '%';
            console.log('Snap', timestamp, 's posizionato al', position.toFixed(2) + '%');
        }
    });
}

// Aggiungi snap alla timeline
function addSnapToTimeline(snap) {
    const videoPlayer = document.getElementById('videoPlayer');
    const snapMarkersOverlay = document.querySelector('.snap-markers-overlay');

    if (!videoPlayer || !snapMarkersOverlay) return;

    const videoDuration = videoPlayer.duration;
    if (videoDuration <= 0) return;

    const position = (snap.timestamp / videoDuration) * 100;

    const snapMarker = document.createElement('div');
    snapMarker.className = 'snap-marker';
    snapMarker.style.cssText = `position: absolute; left: ${position}%; bottom: 5px; transform: translateX(-50%); pointer-events: auto;`;
    snapMarker.onclick = () => seekToTime(snap.timestamp);
    snapMarker.title = `${snap.title || 'Snap'} - ${formatTimestamp(snap.timestamp)}`;

    snapMarker.innerHTML = `
        <div class="snap-indicator" style="width: 12px; height: 12px; background: #17a2b8; border: 2px solid white; border-radius: 50%; box-shadow: 0 2px 4px rgba(0,0,0,0.3); cursor: pointer; transition: all 0.2s ease;"></div>
        <div class="snap-tooltip" style="position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); background: rgba(0,0,0,0.8); color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px; white-space: nowrap; opacity: 0; transition: opacity 0.2s ease; pointer-events: none;">
            ${snap.title || 'Snap'}
        </div>
    `;

    snapMarkersOverlay.appendChild(snapMarker);
}

// Contatore caratteri per commenti
document.getElementById('commentContent').addEventListener('input', function() {
    const charCount = this.value.length;
    document.getElementById('charCount').textContent = charCount;
});

// Inizializzazione
document.addEventListener('DOMContentLoaded', function() {
    const videoPlayer = document.getElementById('videoPlayer');
    let viewIncremented = false;

    // Incrementa le visualizzazioni quando il video inizia a riprodursi
    videoPlayer.addEventListener('play', function() {
        if (!viewIncremented) {
            incrementVideoViews();
            viewIncremented = true;
        }
    });

    // Aggiorna il timestamp nel modal snap (solo se il modal non è aperto)
    videoPlayer.addEventListener('timeupdate', function() {
        const snapModal = document.getElementById('snapModal');

        // Non aggiornare se il modal è aperto
        if (snapModal && snapModal.classList.contains('show')) {
            return;
        }

        const currentTime = Math.floor(this.currentTime);
        const minutes = Math.floor(currentTime / 60);
        const seconds = currentTime % 60;

        if (document.getElementById('currentTime')) {
            document.getElementById('currentTime').textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            document.getElementById('snapTimestamp').value = currentTime;
        }
    });

    // Inizializza lo stile dei bottoni like
    @if($userLike)
        updateLikeButtons('{{ $userLike->type }}');
    @endif
});
</script>
@endif
@endsection
