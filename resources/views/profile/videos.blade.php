@extends('layout.master')

@section('title', 'I Miei Video - Slamin')

@section('css')
<style>
#breadcrumb-nav {
    position: relative !important;
    z-index: 1 !important;
    background: transparent !important;
    width: auto !important;
    height: auto !important;
}
</style>
@endsection

@section('main-content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">{{ __('profile.my_videos') }}</h4>
            <ul class="app-line-breadcrumbs mb-3">
                <li class="">
                    <a href="{{ route('dashboard') }}" class="f-s-14 f-w-500">
                        <span>
                            <i class="ph-duotone ph-house f-s-16"></i> {{ __('dashboard.dashboard') }}
                        </span>
                    </a>
                </li>
                <li class="">
                    <a href="{{ route('profile.show') }}" class="f-s-14 f-w-500">{{ __('profile.breadcrumb_profile') }}</a>
                </li>
                <li class="active">
                    <a href="#" class="f-s-14 f-w-500">{{ __('profile.videos') }}</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ph-duotone ph-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ph-duotone ph-warning me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>
    @endif

    <!-- Header Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0 f-w-600">Gestione Video</h4>
                <a href="{{ route('videos.upload') }}" class="btn btn-primary hover-effect">
                    <i class="ph ph-plus me-2"></i>Carica Nuovo Video
                </a>
            </div>
        </div>
    </div>

    <!-- Videos Grid -->
    <div class="row">
        @if($videos->count() > 0)
        @foreach($videos as $video)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card hover-effect">
                <div class="position-relative">
                    @if($video->thumbnail_path)
                        <img src="{{ Storage::url($video->thumbnail_path) }}" alt="{{ $video->title }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="ph-duotone ph-video-camera f-s-48 text-muted"></i>
                        </div>
                    @endif
                    <div class="position-absolute top-0 end-0 m-2">
                        <span class="badge bg-dark f-s-11">{{ $video->view_count ?? $video->views ?? 0 }} visualizzazioni</span>
                    </div>
                    <div class="position-absolute top-0 start-0 m-2">
                        @if($video->is_public)
                        <span class="badge bg-success f-s-11">Pubblico</span>
                        @else
                        <span class="badge bg-warning f-s-11">Privato</span>
                        @endif
                        @if($video->moderation_status)
                        <span class="badge bg-{{ $video->moderation_status === 'approved' ? 'success' : ($video->moderation_status === 'pending' ? 'warning' : 'danger') }} f-s-11 ms-1">
                            {{ ucfirst($video->moderation_status) }}
                        </span>
                        @endif
                    </div>
                </div>
                <div class="card-body pa-20">
                    <h5 class="card-title f-w-600 f-s-16 mb-2">{{ $video->title }}</h5>
                    @if($video->description)
                    <p class="text-muted f-s-14 mb-3">{{ Str::limit($video->description, 100) }}</p>
                    @endif

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <small class="text-muted f-s-12">{{ $video->created_at->format('d/m/Y') }}</small>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-outline-primary hover-effect" onclick="editVideo({{ $video->id }})" title="Modifica">
                                <i class="ph ph-pencil f-s-14"></i>
                            </button>
                            <button class="btn btn-outline-success hover-effect" onclick="window.location.href='{{ route('videos.play', $video) }}'" title="Visualizza">
                                <i class="ph ph-play f-s-14"></i>
                            </button>
                            <button class="btn btn-outline-danger hover-effect" onclick="deleteVideo({{ $video->id }})" title="Elimina">
                                <i class="ph ph-trash f-s-14"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-grid">
                        @if($video->file_path)
                            <a href="{{ route('videos.play', $video) }}" class="btn btn-success hover-effect">
                                <i class="ph ph-play me-2"></i>Riproduci Video
                            </a>
                        @elseif($video->video_url)
                            <a href="{{ $video->video_url }}" target="_blank" class="btn btn-primary hover-effect">
                                <i class="ph ph-external-link me-2"></i>Guarda Video
                            </a>
                        @else
                            <button class="btn btn-secondary hover-effect" disabled>
                                <i class="ph ph-warning me-2"></i>Video non disponibile
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        @else
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center pa-40">
                    <i class="ph-duotone ph-video-camera f-s-64 text-muted mb-3"></i>
                    <h5 class="mb-3">Nessun video caricato</h5>
                    <p class="text-muted mb-4">Non hai ancora caricato nessun video. Inizia subito caricando il tuo primo video!</p>
                    <a href="{{ route('videos.upload') }}" class="btn btn-primary hover-effect">
                        <i class="ph ph-plus me-2"></i>Carica il tuo primo video
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($videos->hasPages())
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $videos->links() }}
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Upload Video Modal -->
<div class="modal fade" id="uploadVideoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header card-light-primary">
                <h5 class="modal-title f-w-600">
                    <i class="ph ph-video-camera me-2"></i>
                    Carica Nuovo Video
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="uploadVideoForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label f-w-600">Titolo del Video *</label>
                                <input type="text" class="form-control" name="title" required>
                                <small class="text-muted f-s-12">Un titolo accattivante per il tuo video</small>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label class="form-label f-w-600">Visibilità</label>
                                <select class="form-select" name="is_public">
                                    <option value="1">Pubblico</option>
                                    <option value="0">Privato</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label f-w-600">Descrizione</label>
                        <textarea class="form-control" name="description" rows="3"
                                  placeholder="Descrivi il contenuto del video, il contesto, le emozioni..."></textarea>
                        <small class="text-muted f-s-12">Racconta la storia dietro il tuo video</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label f-w-600">URL del Video *</label>
                        <input type="url" class="form-control" name="video_url" required
                               placeholder="https://youtube.com/watch?v=... o https://vimeo.com/...">
                        <small class="text-muted f-s-12">Supporta YouTube e Vimeo</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label f-w-600">Thumbnail (Opzionale)</label>
                        <input type="file" class="form-control" name="thumbnail" accept="image/*">
                        <small class="text-muted f-s-12">Se non carichi un'immagine, verrà usata quella di YouTube</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary hover-effect" data-bs-dismiss="modal">Annulla</button>
                    <button type="submit" class="btn btn-primary hover-effect">
                        <i class="ph ph-upload me-2"></i>Carica Video
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Video Modal -->
<div class="modal fade" id="viewVideoModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header card-light-info">
                <h5 class="modal-title f-w-600" id="videoModalTitle">
                    <i class="ph ph-play me-2"></i>
                    Visualizza Video
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="ratio ratio-16x9">
                    <iframe id="videoIframe" src="" frameborder="0" allowfullscreen></iframe>
                </div>
                <div class="mt-3">
                    <h6 id="videoTitle" class="f-w-600"></h6>
                    <p id="videoDescription" class="text-muted f-s-14"></p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted f-s-12" id="videoDate"></small>
                        <span class="badge bg-info f-s-12" id="videoViews"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
function showUploadModal() {
    $('#uploadVideoModal').modal('show');
}

function editVideo(videoId) {
    // Implementazione modifica video
    Swal.fire('Info', 'Funzionalità modifica video in sviluppo', 'info');
}

function viewVideo(videoId) {
    // Carica i dati del video via AJAX
    fetch(`/api/videos/${videoId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('videoModalTitle').textContent = data.title;
            document.getElementById('videoTitle').textContent = data.title;
            document.getElementById('videoDescription').textContent = data.description || 'Nessuna descrizione';
            document.getElementById('videoDate').textContent = new Date(data.created_at).toLocaleDateString('it-IT');
            document.getElementById('videoViews').textContent = `${data.views} visualizzazioni`;
            document.getElementById('videoIframe').src = data.embed_url;
            $('#viewVideoModal').modal('show');
        })
        .catch(error => {
            Swal.fire('Errore', 'Impossibile caricare il video', 'error');
        });
}

function deleteVideo(videoId) {
    Swal.fire({
        title: 'Sei sicuro?',
        text: "Questa azione non può essere annullata!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sì, elimina!',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`{{ route('profile.videos.delete', '') }}/${videoId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Eliminato!', data.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Errore!', data.message, 'error');
                }
            });
        }
    });
}

// Upload Video Form Handler
$('#uploadVideoForm').on('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    // Show loading
    Swal.fire({
        title: 'Caricamento...',
        text: 'Sto caricando il tuo video',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    fetch('{{ route("profile.videos.upload") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire('Successo!', data.message, 'success').then(() => {
                location.reload();
            });
        } else {
            Swal.fire('Errore!', data.message, 'error');
        }
    })
    .catch(error => {
        Swal.fire('Errore', 'Errore durante il caricamento', 'error');
    });
});

// Hide loader as fallback
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const loader = document.querySelector('.loader-wrapper');
        if (loader) {
            loader.style.display = 'none';
        }
    }, 1000);
});
</script>
@endsection
