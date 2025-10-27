@extends('layout.master')

@section('title', __('common.media_section') . ' - Slamin')

@section('css')
@endsection

@section('main-content')
    <!-- Media Page Manager -->
    <livewire:media.media-page-manager />
    
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">
                <i class="ph ph-video-camera me-2"></i>
                {{ __('common.media_section') }}
            </h4>

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
                        <div class="position-relative" style="cursor: pointer;" onclick="openVideoModal({{ $mostPopularVideo->id }})">
                            <img src="{{ $mostPopularVideo->thumbnail_url }}" 
                                 alt="{{ $mostPopularVideo->title }}" 
                                 class="card-img-top" 
                                 style="height: 400px; object-fit: cover;">
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
                        <div class="card-body">
                            <h5 class="card-title f-w-600 f-s-16 mb-2">
                                <a href="{{ route('videos.show', $mostPopularVideo) }}" class="text-decoration-none text-dark hover-text-primary" style="cursor: pointer;">
                                    {{ $mostPopularVideo->title }}
                                </a>
                            </h5>
                            @if($mostPopularVideo->description)
                                <p class="text-muted f-s-13 mb-3">{{ Str::limit($mostPopularVideo->description, 120) }}</p>
                            @endif

                            <!-- Video Actions -->
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex gap-2">
                                    
                                    
                                    
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
                                        <img src="{{ $video->thumbnail_url }}" 
                                             alt="{{ $video->title }}" 
                                             class="rounded" 
                                             style="width: 80px; height: 60px; object-fit: cover;">
                                        <div class="position-absolute top-50 start-50 translate-middle">
                                            <i class="ph-duotone ph-play f-s-16 text-white" style="filter: drop-shadow(0 2px 4px rgba(0,0,0,0.8));"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 f-w-600 f-s-13">
                                            <a href="{{ route('videos.show', $video) }}" class="text-decoration-none text-dark hover-text-primary" style="cursor: pointer;">
                                                {{ Str::limit($video->title, 40) }}
                                            </a>
                                        </h6>
                                        <div class="d-flex align-items-center gap-2">
                                            
                                            
                                            
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
                                        <img src="{{ $video->thumbnail_url }}" 
                                             alt="{{ $video->title }}" 
                                             class="rounded" 
                                             style="width: 80px; height: 60px; object-fit: cover;">
                                        <div class="position-absolute top-50 start-50 translate-middle">
                                            <i class="ph-duotone ph-play f-s-16 text-white" style="filter: drop-shadow(0 2px 4px rgba(0,0,0,0.8));"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 f-w-600 f-s-13">
                                            <a href="{{ route('videos.show', $video) }}" class="text-decoration-none text-dark hover-text-primary" style="cursor: pointer;">
                                                {{ Str::limit($video->title, 40) }}
                                            </a>
                                        </h6>
                                        <div class="d-flex align-items-center gap-2">
                                            
                                            
                                            
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

    <!-- Seconda Riga: Foto con Switch Nuovi/Popolari + Foto Più Popolare -->
    <div class="row mb-4">
        <!-- 6 Foto con Switch Nuovi/Popolari (Piccolo) -->
        <div class="col-lg-4">
            <div class="card hover-effect">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="ph-duotone ph-image me-2"></i>
                            Foto
                        </h5>
                        <!-- Switch Nuovi/Popolari -->
                        <div class="d-flex align-items-center">
                            <span class="me-2 text-primary f-s-14 f-w-500" id="popularPhotoLabel" style="cursor: pointer;">Popolari</span>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="photoToggle" onchange="togglePhotoContent()">
                            </div>
                            <span class="ms-2 text-muted f-s-14 f-w-500" id="newPhotoLabel" style="cursor: pointer;">Nuovi</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Contenuto Popolari (Default) -->
                    <div id="popularPhotos">
                        @if($popularPhotos->count() > 0)
                            @foreach($popularPhotos->take(6) as $photo)
                                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                    <div class="flex-shrink-0 position-relative" style="cursor: pointer;" onclick="openPhotoModal({{ $photo->id }})">
                                        @if($photo->image_url)
                                            <img src="{{ $photo->image_url }}" alt="{{ $photo->title }}" class="rounded" style="width: 80px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center rounded" style="width: 80px; height: 60px;">
                                                <i class="ph-duotone ph-image f-s-24 text-muted"></i>
                                            </div>
                                        @endif
                                        <div class="position-absolute top-50 start-50 translate-middle">
                                            <i class="ph-duotone ph-magnifying-glass f-s-16 text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 f-w-600 f-s-13">
                                            <a href="#" onclick="Livewire.dispatch('openPhotoModal', { photoId: {{ $photo->id }} })" class="text-decoration-none text-dark hover-text-primary" style="cursor: pointer;">
                                                {{ Str::limit($photo->title, 40) }}
                                            </a>
                                        </h6>
                                        <div class="d-flex align-items-center gap-2">
                                            
                                            
                                            
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <div class="bg-light-warning h-50 w-50 d-flex-center rounded-circle m-auto mb-2">
                                    <i class="ph-duotone ph-image-slash f-s-24 text-warning"></i>
                                </div>
                                <p class="text-muted f-s-14 mb-0">Nessuna foto popolare</p>
                            </div>
                        @endif
                    </div>

                    <!-- Contenuto Nuovi (Nascosto) -->
                    <div id="newPhotos" style="display: none;">
                        @if($newPhotos->count() > 0)
                            @foreach($newPhotos->take(6) as $photo)
                                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                    <div class="flex-shrink-0 position-relative" style="cursor: pointer;" onclick="openPhotoModal({{ $photo->id }})">
                                        @if($photo->image_url)
                                            <img src="{{ $photo->image_url }}" alt="{{ $photo->title }}" class="rounded" style="width: 80px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center rounded" style="width: 80px; height: 60px;">
                                                <i class="ph-duotone ph-image f-s-24 text-muted"></i>
                                            </div>
                                        @endif
                                        <div class="position-absolute top-50 start-50 translate-middle">
                                            <i class="ph-duotone ph-magnifying-glass f-s-16 text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 f-w-600 f-s-13">
                                            <a href="#" onclick="Livewire.dispatch('openPhotoModal', { photoId: {{ $photo->id }} })" class="text-decoration-none text-dark hover-text-primary" style="cursor: pointer;">
                                                {{ Str::limit($photo->title, 40) }}
                                            </a>
                                        </h6>
                                        <div class="d-flex align-items-center gap-2">
                                            
                                            
                                            
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <div class="bg-light-info h-50 w-50 d-flex-center rounded-circle m-auto mb-2">
                                    <i class="ph-duotone ph-image-slash f-s-24 text-info"></i>
                                </div>
                                <p class="text-muted f-s-14 mb-0">Nessuna foto nuova</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Foto Più Popolare (Grande) -->
        <div class="col-lg-8">
            <div class="card hover-effect">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph-duotone ph-trophy me-2"></i>
                        Foto Più Popolare
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($mostPopularPhoto)
                        <div class="position-relative">
                            @if($mostPopularPhoto->image_url)
                                <div class="position-relative" style="cursor: pointer;" onclick="openPhotoModal({{ $mostPopularPhoto->id }})">
                                    <img src="{{ $mostPopularPhoto->image_url }}" alt="{{ $mostPopularPhoto->title }}" class="card-img-top" style="height: 400px; object-fit: cover;">
                                    <div class="position-absolute top-50 start-50 translate-middle">
                                        <div class="zoom-button bg-white rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                                            <i class="ph-duotone ph-magnifying-glass-plus f-s-32 text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="position-absolute top-0 end-0 m-3">
                                        <span class="badge bg-dark f-s-12">{{ $mostPopularPhoto->view_count ?? $mostPopularPhoto->views }} {{ __('profile.views') }}</span>
                                    </div>
                                </div>
                            @else
                                <div class="position-relative" style="cursor: pointer;" onclick="openPhotoModal({{ $mostPopularPhoto->id }})">
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                                        <div class="text-center">
                                            <i class="ph-duotone ph-image f-s-64 text-muted mb-3"></i>
                                            <div class="zoom-button bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 80px; height: 80px; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                                                <i class="ph-duotone ph-magnifying-glass-plus f-s-32 text-white"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="position-absolute top-0 end-0 m-3">
                                        <span class="badge bg-dark f-s-12">{{ $mostPopularPhoto->view_count ?? $mostPopularPhoto->views }} {{ __('profile.views') }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="card-body">
                            <h5 class="card-title f-w-600 f-s-16 mb-2">
                                <a href="{{ route('photos.show', $mostPopularPhoto) }}" class="text-decoration-none text-dark hover-text-primary" style="cursor: pointer;">
                                    {{ $mostPopularPhoto->title }}
                                </a>
                            </h5>
                            @if($mostPopularPhoto->description)
                                <p class="text-muted f-s-13 mb-3">{{ Str::limit($mostPopularPhoto->description, 120) }}</p>
                            @endif

                            <!-- Photo Actions -->
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex gap-2">
                                    
                                    
                                    
                                </div>
                                <small class="text-muted">
                                    <i class="ph-duotone ph-calendar f-s-12 me-1"></i>
                                    {{ $mostPopularPhoto->created_at->format('d/m/Y') }}
                                </small>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="bg-light-primary h-80 w-80 d-flex-center rounded-circle m-auto mb-3">
                                <i class="ph-duotone ph-image-slash f-s-48 text-primary"></i>
                            </div>
                            <p class="text-muted f-s-16 mb-0">Nessuna foto disponibile</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

<!-- Terza Riga: Box di Ricerca Media -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card hover-effect">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="ph-duotone ph-magnifying-glass me-2"></i>
                    Cerca Media
                </h5>
            </div>
            <div class="card-body">
                <form id="mediaSearchForm" class="row g-3">
                    <div class="col-md-6">
                        <label for="searchQuery" class="form-label">
                            <i class="ph-duotone ph-search me-1"></i>
                            Parole chiave
                        </label>
                        <input type="text" class="form-control" id="searchQuery" name="query" placeholder="Cerca video e foto..." value="{{ request('query') }}">
                    </div>
                    <div class="col-md-3">
                        <label for="mediaType" class="form-label">
                            <i class="ph-duotone ph-files me-1"></i>
                            Tipo di media
                        </label>
                        <select class="form-select" id="mediaType" name="type">
                            <option value="">Tutti i media</option>
                            <option value="video" {{ request('type') == 'video' ? 'selected' : '' }}>Solo video</option>
                            <option value="photo" {{ request('type') == 'photo' ? 'selected' : '' }}>Solo foto</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="sortBy" class="form-label">
                            <i class="ph-duotone ph-sort-ascending me-1"></i>
                            Ordina per
                        </label>
                        <select class="form-select" id="sortBy" name="sort">
                            <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Più recenti</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Più popolari</option>
                            <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>Più visualizzazioni</option>
                            <option value="likes" {{ request('sort') == 'likes' ? 'selected' : '' }}>Più apprezzati</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="ph-duotone ph-magnifying-glass me-1"></i>
                                Cerca
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="clearSearch()">
                                <i class="ph-duotone ph-arrow-clockwise me-1"></i>
                                Pulisci
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Quarta Riga: Risultati della Ricerca -->
<div class="row" id="searchResults" style="display: none;">
    <div class="col-12">
        <div class="card hover-effect">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="ph-duotone ph-list me-2"></i>
                    Risultati della ricerca
                </h5>
                <span class="badge bg-primary" id="resultsCount">0 risultati</span>
            </div>
            <div class="card-body">
                <div class="row" id="resultsContainer">
                    <!-- I risultati verranno caricati dinamicamente qui -->
                </div>
                <div class="text-center mt-3" id="loadingResults" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Caricamento risultati...</span>
                    </div>
                    <p class="mt-2 text-muted">Caricamento risultati...</p>
                </div>
                <div class="text-center mt-3" id="noResults" style="display: none;">
                    <div class="bg-light-primary h-80 w-80 d-flex-center rounded-circle m-auto mb-3">
                        <i class="ph-duotone ph-magnifying-glass f-s-48 text-primary"></i>
                    </div>
                    <p class="text-muted f-s-16 mb-0">Nessun risultato trovato</p>
                    <p class="text-muted f-s-14">Prova a modificare i criteri di ricerca</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Commenti per Risultati di Ricerca -->
<div class="modal fade" id="commentsModal" tabindex="-1" aria-labelledby="commentsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="commentsModalLabel">
                    <i class="ph-duotone ph-chat-circle me-2"></i>
                    Commenti
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Loading indicator -->
                <div class="text-center" id="commentsLoading" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Caricamento commenti...</span>
                    </div>
                    <p class="mt-2 text-muted">Caricamento commenti...</p>
                </div>

                <!-- Error message -->
                <div class="alert alert-danger" id="commentsError" style="display: none;">
                    <i class="ph-duotone ph-warning me-2"></i>
                    <span id="commentsErrorMessage">Errore nel caricamento dei commenti</span>
                </div>

                <!-- Comments Container -->
                <div id="commentsContainer" style="display: none;">
                    <!-- Lista commenti -->
                    <div id="commentsList" class="mb-3">
                        <!-- I commenti verranno caricati qui -->
                    </div>

                    <!-- Form per nuovo commento -->
                    <div class="border-top pt-3">
                        <h6 class="mb-3">
                            <i class="ph-duotone ph-plus-circle me-2"></i>
                            Aggiungi un commento
                        </h6>
                        <form id="newCommentForm">
                            <input type="hidden" id="commentMediaType" value="">
                            <input type="hidden" id="commentMediaId" value="">
                            <div class="mb-3">
                                <textarea class="form-control" id="commentContent" rows="3" placeholder="Scrivi il tuo commento..." maxlength="500"></textarea>
                                <div class="form-text">
                                    <span id="commentCharCount">0</span>/500 caratteri
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <button type="submit" class="btn btn-primary" id="submitCommentBtn">
                                    <i class="ph-duotone ph-paper-plane-right me-1"></i>
                                    Invia commento
                                </button>
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    Chiudi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Photo Viewer Modal a Tutta Pagina -->
<div class="custom-modal" id="photoViewerModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999; background: rgba(0,0,0,0.95); backdrop-filter: blur(15px);">
    <div class="modal-content" style="position: relative; width: 100%; height: 100%; display: flex; flex-direction: column;">
        <div class="modal-header" style="background: rgba(0,0,0,0.8); border-bottom: 1px solid rgba(255,255,255,0.1); padding: 1rem; display: flex; justify-content: space-between; align-items: center;">
            <h5 class="modal-title text-white" id="photoViewerModalLabel">Photo Viewer</h5>
            <button type="button" class="btn-close btn-close-white" onclick="closePhotoModal()" aria-label="Close"></button>
        </div>
        <div class="modal-body" style="flex: 1; padding: 0; position: relative;">
            <!-- Loading indicator -->
            <div class="text-center position-absolute top-50 start-50 translate-middle" id="modalPhotoLoading">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Caricamento foto...</span>
                </div>
                <p class="mt-2 text-white">Caricamento foto...</p>
            </div>

            <!-- Error message -->
            <div class="alert alert-danger position-absolute top-50 start-50 translate-middle" id="modalPhotoError" style="display: none; z-index: 1000;">
                <i class="ph-duotone ph-warning f-s-16 me-2"></i>
                <span id="modalPhotoErrorMessage">Errore nel caricamento della foto</span>
            </div>

            <!-- Photo Container -->
            <div class="photo-container position-relative d-flex align-items-center justify-content-center" id="modalPhotoContainer" style="display: none; padding: 20px;">
                <div class="w-100" style="max-width: 1200px;">
                    <div class="photo-container position-relative">
                        <img id="modalPhotoImage" class="w-100" style="max-height: 70vh; object-fit: contain; border-radius: 12px; box-shadow: 0 15px 40px rgba(0,0,0,0.6);" alt="{{ __('common.photo') }}">
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>

<!-- Video Player Modal a Tutta Pagina -->
<div class="custom-modal" id="videoPlayerModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999; background: rgba(0,0,0,0.85); backdrop-filter: blur(15px);">
    <div class="modal-content" style="position: relative; width: 100%; height: 100%; display: flex; flex-direction: column;">
        <div class="modal-header" style="background: rgba(0,0,0,0.8); border-bottom: 1px solid rgba(255,255,255,0.1); padding: 1rem; display: flex; justify-content: space-between; align-items: center;">
            <h5 class="modal-title text-white" id="videoPlayerModalLabel">Video Player</h5>
            <button type="button" class="btn-close btn-close-white" onclick="closeVideoModal()" aria-label="Close"></button>
        </div>
        <div class="modal-body" style="flex: 1; padding: 0; position: relative;">
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
                <div class="video-container position-relative d-flex align-items-center justify-content-center" id="modalVideoContainer" style="display: none; padding: 20px;">
                    <div class="w-100" style="max-width: 1200px;">
                        <div class="video-container position-relative">
                            <!-- Video Player HTML5 Nativo -->
                            <video
                                id="modalVideoPlayer"
                                class="w-100"
                                style="height: 500px; max-height: 500px; object-fit: cover; border-radius: 12px; box-shadow: 0 15px 40px rgba(0,0,0,0.6); background: #000;"
                                preload="metadata"
                                controls>
                                Il tuo browser non supporta la riproduzione video.
                            </video>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>



@endsection

@push('styles')
<style>
/* Stili per il modal video */
.custom-modal {
    background: rgba(0,0,0,0.95) !important;
    backdrop-filter: blur(20px) !important;
}


/* Responsive per schermi piccoli */
@media (max-width: 768px) {
    #modalVideoContainer {
        padding: 10px !important;
    }
}

/* Responsive per schermi molto piccoli */
@media (max-width: 480px) {
    #modalVideoContainer {
        padding: 5px !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Variabili globali per il modal
let modalVideoPlayer = null;
let modalCurrentVideoTime = 0;
let modalVideoDuration = 0;

// Listener per la chiusura del modal con ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        // Chiudi il modal video se aperto
        if (document.querySelector('.modal-backdrop')) {
            closeVideoModal();
        }
        // Chiudi il modal foto se aperto
        if (document.querySelector('.modal-backdrop')) {
            closePhotoModalJS();
        }
    }
});

// Listener globale per preservare i componenti Livewire
document.addEventListener('DOMContentLoaded', function() {
    // Preserva i componenti Livewire quando la pagina è caricata
    preserveLivewireComponents();
    
    // Listener per eventi di navigazione
    window.addEventListener('beforeunload', function() {
        preserveLivewireComponents();
    });
    
    // Listener per eventi di focus/blur
    window.addEventListener('focus', function() {
        preserveLivewireComponents();
    });
    
});


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
    // Disabilita temporaneamente i componenti Livewire
    disableLivewireComponents();
    
    // Preserva i componenti Livewire prima di aprire il modal
    preserveLivewireComponents();
    
    // Usa Livewire per aprire il modal
    Livewire.dispatch('openVideoModal', { videoId: videoId });
    
    // Emetti evento per preservare lo stato dei componenti social
    Livewire.dispatch('modalOpened');
    
    // Riabilita i componenti Livewire dopo un breve delay
    setTimeout(() => {
        enableLivewireComponents();
    }, 100);
}

// Funzione per aprire il modal foto
function openPhotoModal(photoId) {
    console.log('📸 openPhotoModal chiamato con ID:', photoId);
    
    // Per le foto, usiamo un modal JavaScript puro per evitare problemi con Livewire
    openPhotoModalJS(photoId);
}

// Funzione JavaScript pura per aprire il modal foto
async function openPhotoModalJS(photoId) {
    try {
        // Carica i dati della foto
        const response = await fetch(`/api/photos/${photoId}`);
        const data = await response.json();
        
        if (!data.success) {
            throw new Error(data.message || 'Errore nel caricamento della foto');
        }
        
        const photo = data.photo;
        
        // Crea il modal HTML
        const modalHTML = `
            <div class="modal-backdrop fade show" 
                 onclick="closePhotoModalJS()"
                 style="z-index: 1040; background: rgba(0,0,0,0.8); backdrop-filter: blur(10px);"></div>
            
            <div class="modal fade show d-block" 
                 style="z-index: 1050;"
                 tabindex="-1" 
                 role="dialog">
                <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                    <div class="modal-content" style="background: transparent; border: none; box-shadow: none;">
                        <div class="modal-header border-0" style="background: transparent;">
                            <button type="button" 
                                    class="btn-close btn-close-white" 
                                    onclick="closePhotoModalJS()"
                                    style="filter: brightness(0) invert(1);">
                            </button>
                        </div>
                        
                        <div class="modal-body p-0" style="background: transparent;">
                            <div class="text-center">
                                <img src="${photo.image_url}" 
                                     alt="${photo.title}"
                                     class="img-fluid rounded"
                                     style="max-height: 80vh; object-fit: contain;">
                                
                                <div class="mt-3">
                                    <h5 class="text-white mb-2">${photo.title}</h5>
                                    <p class="text-white-50 mb-0">${photo.description || ''}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        // Aggiungi il modal al body
        document.body.insertAdjacentHTML('beforeend', modalHTML);
        document.body.style.overflow = 'hidden';
        
        console.log('✅ Modal foto aperto con successo');
        
    } catch (error) {
        console.error('❌ Errore nell\'apertura del modal foto:', error);
        alert('Errore nel caricamento della foto: ' + error.message);
    }
}

// Funzione per chiudere il modal foto
function closePhotoModalJS() {
    const modal = document.querySelector('.modal-backdrop');
    const modalDialog = document.querySelector('.modal');
    
    if (modal) modal.remove();
    if (modalDialog) modalDialog.remove();
    
    document.body.style.overflow = 'auto';
    
    console.log('✅ Modal foto chiuso');
}

// Funzione per preservare i componenti Livewire
function preserveLivewireComponents() {
    // Emetti evento globale per preservare tutti i componenti
    Livewire.dispatch('preserveLivewireComponents');
    
    // Forza il refresh dei componenti social
    document.querySelectorAll('[wire\\:id]').forEach(component => {
        const wireId = component.getAttribute('wire:id');
        if (wireId) {
            try {
                // Preserva lo snapshot del componente
                const livewireComponent = Livewire.find(wireId);
                if (livewireComponent) {
                    // Forza il refresh del componente
                    livewireComponent.refresh();
                }
            } catch (error) {
                console.warn('Errore nel refresh del componente Livewire:', error);
            }
        }
    });
}

// Funzione per disabilitare temporaneamente i componenti Livewire
function disableLivewireComponents() {
    document.querySelectorAll('[wire\\:id]').forEach(component => {
        component.style.pointerEvents = 'none';
        component.style.opacity = '0.7';
    });
}

// Funzione per riabilitare i componenti Livewire
function enableLivewireComponents() {
    document.querySelectorAll('[wire\\:id]').forEach(component => {
        component.style.pointerEvents = 'auto';
        component.style.opacity = '1';
    });
}

// Funzione per chiudere il modal video
function closeVideoModal() {
    // Disabilita temporaneamente i componenti Livewire
    disableLivewireComponents();
    
    // Usa Livewire per chiudere il modal
    Livewire.dispatch('closeModal');
    
    // Emetti evento per preservare lo stato dei componenti social
    Livewire.dispatch('modalClosed');
    
    // Preserva i componenti Livewire dopo la chiusura
    preserveLivewireComponents();
    
    // Riabilita i componenti Livewire dopo un breve delay
    setTimeout(() => {
        enableLivewireComponents();
    }, 100);
}

// Funzione per caricare il video nel modal
async function loadVideoInModal(videoId) {
    const loadingDiv = document.getElementById('modalVideoLoading');
    const errorDiv = document.getElementById('modalVideoError');
    const containerDiv = document.getElementById('modalVideoContainer');
    const videoPlayer = document.getElementById('modalVideoPlayer');
    const peerTubePlayer = document.getElementById('modalPeerTubePlayer');

    // Mostra loading
    loadingDiv.style.display = 'block';
    errorDiv.style.display = 'none';
    containerDiv.style.display = 'none';

    try {
        // Prima ottieni i dati del video
        const videoResponse = await fetch(`/api/videos/${videoId}`);
        const videoData = await videoResponse.json();

        if (!videoData.success) {
            throw new Error(videoData.message || 'Errore nel caricamento del video');
        }

        const video = videoData.video;

        // Imposta il titolo del modal
        document.getElementById('videoPlayerModalLabel').textContent = video.title;

        // Usa sempre il player HTML5 nativo
        videoPlayer.style.display = 'block';



        // Ottieni l'URL diretto del video da PeerTube
        const urlResponse = await fetch(`/videos/${videoId}/peertube-url`);
        const urlData = await urlResponse.json();

        // Gestisci il caso in cui il video è ancora in elaborazione
        if (urlData.status === 'processing') {
            throw new Error('Il video è ancora in elaborazione su PeerTube. Riprova tra qualche minuto.');
        }

        if (urlData.success && urlData.files && urlData.files.length > 0) {
            // Usa il primo file disponibile (migliore qualità)
            const videoFile = urlData.files[0];


            // Crea l'elemento source
            const source = document.createElement('source');
            source.src = videoFile.url;
            source.type = 'video/mp4';

            // Rimuovi eventuali source esistenti e aggiungi quello nuovo
            videoPlayer.innerHTML = '';
            videoPlayer.appendChild(source);

            // Forza il caricamento del video
            videoPlayer.load();
        } else {
            throw new Error('Nessuna sorgente video disponibile');
        }



        // Nascondi loading e mostra video
        loadingDiv.style.display = 'none';
        containerDiv.style.display = 'block';

        // Inizializza il player
        initializeModalVideoPlayer(video);

    } catch (error) {
        console.error('❌ Errore caricamento video nel modal:', error);
        loadingDiv.style.display = 'none';
        errorDiv.style.display = 'block';
        document.getElementById('modalErrorMessage').textContent = error.message;
    }
}


// Funzione per inizializzare il player del modal
function initializeModalVideoPlayer(video) {
    const videoPlayer = document.getElementById('modalVideoPlayer');
    modalVideoDuration = video.duration || 60;
    modalVideoPlayer = videoPlayer;

    // Event listeners per il player HTML5
    videoPlayer.addEventListener('loadedmetadata', function() {

        modalVideoDuration = videoPlayer.duration || modalVideoDuration;
    });

    videoPlayer.addEventListener('timeupdate', function() {
        modalCurrentVideoTime = videoPlayer.currentTime;
    });

    videoPlayer.addEventListener('durationchange', function() {

        modalVideoDuration = videoPlayer.duration;
    });

    videoPlayer.addEventListener('canplay', function() {

    });

    videoPlayer.addEventListener('error', function() {
        console.error('❌ Errore nel video del modal:', videoPlayer.error);
        const errorDiv = document.getElementById('modalVideoError');
        if (errorDiv) {
            errorDiv.style.display = 'block';
            document.getElementById('modalErrorMessage').textContent = 'Errore nella riproduzione del video. Riprova più tardi.';                               
        }
    });

}

// Funzione per formattare il tempo (mantenuta per altri usi)
function formatTime(seconds) {
    const minutes = Math.floor(seconds / 60);
    const remainingSeconds = Math.floor(seconds % 60);
    return `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
}

// Funzione per mostrare messaggio di errore
function showErrorMessage(message) {
    // Implementazione semplice per mostrare errori
    alert(message);
}

// Funzione per mostrare messaggio di successo
function showSuccessMessage(message) {
    // Implementazione semplice per mostrare successi
    alert(message);
}

// Declare functions globally to be accessible from onclick handlers
window.openVideoModal = openVideoModal;
window.openPhotoModal = openPhotoModal;
window.closeVideoModal = closeVideoModal;
window.closePhotoModal = closePhotoModal;
window.closePhotoModalJS = closePhotoModalJS;
</script>
@endpush

