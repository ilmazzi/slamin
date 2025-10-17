@extends('layout.master')

@section('title', __('common.media_section') . ' - Slamin')

@section('css')
</style>
@endsection

@section('main-content')
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
                                    <x-social-like-button :content="$mostPopularVideo" type="video" />
                                    <x-social-view-counter :content="$mostPopularVideo" type="video" />
                                    <x-social-snap-button :content="$mostPopularVideo" type="video" />
                                    <x-social-comment-button :content="$mostPopularVideo" type="video" />
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
                                        <h6 class="mb-1 f-w-600 f-s-13">
                                            <a href="{{ route('videos.show', $video) }}" class="text-decoration-none text-dark hover-text-primary" style="cursor: pointer;">
                                                {{ Str::limit($video->title, 40) }}
                                            </a>
                                        </h6>
                                        <div class="d-flex align-items-center gap-2">
                                            <x-social-view-counter :content="$video" type="video" size="sm" />
                                            <x-social-like-button :content="$video" type="video" size="sm" />
                                            <x-social-snap-button :content="$video" type="video" size="sm" />
                                            <x-social-comment-button :content="$video" type="video" size="sm" />
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
                                        <h6 class="mb-1 f-w-600 f-s-13">
                                            <a href="{{ route('videos.show', $video) }}" class="text-decoration-none text-dark hover-text-primary" style="cursor: pointer;">
                                                {{ Str::limit($video->title, 40) }}
                                            </a>
                                        </h6>
                                        <div class="d-flex align-items-center gap-2">
                                            <x-social-view-counter :content="$video" type="video" size="sm" />
                                            <x-social-like-button :content="$video" type="video" size="sm" />
                                            <x-social-snap-button :content="$video" type="video" size="sm" />
                                            <x-social-comment-button :content="$video" type="video" size="sm" />
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
                                            <a href="{{ route('photos.show', $photo) }}" class="text-decoration-none text-dark hover-text-primary" style="cursor: pointer;">
                                                {{ Str::limit($photo->title, 40) }}
                                            </a>
                                        </h6>
                                        <div class="d-flex align-items-center gap-2">
                                            <x-social-view-counter :content="$photo" type="photo" size="sm" />
                                            <x-social-like-button :content="$photo" type="photo" size="sm" />
                                            <x-social-comment-button :content="$photo" type="photo" size="sm" />
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
                                            <a href="{{ route('photos.show', $photo) }}" class="text-decoration-none text-dark hover-text-primary" style="cursor: pointer;">
                                                {{ Str::limit($photo->title, 40) }}
                                            </a>
                                        </h6>
                                        <div class="d-flex align-items-center gap-2">
                                            <x-social-view-counter :content="$photo" type="photo" size="sm" />
                                            <x-social-like-button :content="$photo" type="photo" size="sm" />
                                            <x-social-comment-button :content="$photo" type="photo" size="sm" />
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
                                    <x-social-like-button :content="$mostPopularPhoto" type="photo" />
                                    <x-social-view-counter :content="$mostPopularPhoto" type="photo" />
                                    <x-social-comment-button :content="$mostPopularPhoto" type="photo" />
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

                            <!-- Snap Markers sulla Progress Bar del Player -->
                            <div class="snap-markers-overlay position-absolute" id="modalSnapMarkers" style="bottom: 0; left: 0; right: 0; height: 40px; pointer-events: none;">
                                <!-- Snap markers verranno aggiunti dinamicamente -->
                            </div>
                        </div>
                    </div>

                    <!-- Pulsante per creare snap con scritta sotto -->
                    @auth
                    <div class="position-absolute" id="modalFloatingSnapButton" style="opacity: 1; transition: opacity 0.3s ease; z-index: 10000; top: 20px; right: 20px; display: flex; flex-direction: column; align-items: center; gap: 8px;">
                        <button type="button" class="btn btn-gradient-success hover-effect rounded-circle shadow-lg"
                                style="width: 60px; height: 60px;"
                                onclick="toggleSnapForm()">
                            <img src="{{ asset('assets/images/snap.png') }}" alt="{{ __('common.snap') }}" style="width: 28px; height: 28px; filter: brightness(0) invert(1);">
                        </button>
                        <div class="snap-label" style="color: white; font-size: 11px; text-align: center; white-space: nowrap; text-shadow: 0 1px 2px rgba(0,0,0,0.8); font-weight: 500;">
                            Crea snap
                        </div>
                    </div>
                    @endauth

                    <!-- Form inline per creare snap -->
                    @auth
                    <div class="position-absolute" id="modalSnapForm" style="display: none; z-index: 10001; top: 20px; right: 20px; background: rgba(0,0,0,0.9); border-radius: 12px; padding: 20px; min-width: 300px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1);">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="text-white mb-0">Crea Snap</h6>
                            <button type="button" class="btn-close btn-close-white" onclick="toggleSnapForm()"></button>
                        </div>
                        <form id="inlineSnapForm">
                            <div class="mb-3">
                                <label for="inlineSnapTitle" class="form-label text-white" style="font-size: 12px;">Titolo (opzionale)</label>
                                <input type="text" class="form-control form-control-sm" id="inlineSnapTitle"  style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white;">
                            </div>
                            <div class="mb-3">
                                <label for="inlineSnapDescription" class="form-label text-white" style="font-size: 12px;">Descrizione (opzionale)</label>
                                <textarea class="form-control form-control-sm" id="inlineSnapDescription" rows="2"  style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; resize: none;"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-white" style="font-size: 12px;">Timestamp: <span id="inlineCurrentTime" class="text-warning">00:00</span></label>
                                <input type="hidden" id="inlineSnapTimestamp" value="0">
                                <input type="hidden" id="inlineSnapVideoId" value="">
                            </div>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-secondary" onclick="toggleSnapForm()">Annulla</button>
                                <button type="button" class="btn btn-sm btn-primary" onclick="createInlineSnap()">Crea Snap</button>
                            </div>
                        </form>
                    </div>
                    @endauth
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

/* Stili per il pulsante snap nel modal */
#modalFloatingSnapButton {
    z-index: 99999 !important;
}

#modalFloatingSnapButton .btn {
    z-index: 99999 !important;
}

#modalFloatingSnapButton .snap-label {
    color: white !important;
    font-size: 11px !important;
    text-align: center !important;
    white-space: nowrap !important;
    text-shadow: 0 1px 2px rgba(0,0,0,0.8) !important;
    font-weight: 500 !important;
}

/* Stili per i placeholder nel form snap */
#inlineSnapTitle::placeholder,
#inlineSnapDescription::placeholder {
    color: rgba(255,255,255,0.7) !important;
    opacity: 1 !important;
}

#inlineSnapTitle:focus::placeholder,
#inlineSnapDescription:focus::placeholder {
    color: rgba(255,255,255,0.5) !important;
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


    // Mostra il modal personalizzato
    const modal = document.getElementById('videoPlayerModal');
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden'; // Previene lo scroll

    // Carica il video
    loadVideoInModal(videoId);
}

// Funzione per chiudere il modal video
function closeVideoModal() {
    const modal = document.getElementById('videoPlayerModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto'; // Ripristina lo scroll

    // Ferma il video se in riproduzione
    const videoPlayer = document.getElementById('modalVideoPlayer');
    if (videoPlayer && !videoPlayer.paused) {
        videoPlayer.pause();
    }

    // Reset variabili
    modalCurrentVideoTime = 0;
    modalVideoDuration = 0;
    modalSnaps = [];
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

        // Imposta l'ID del video per le funzioni snap
        videoPlayer.setAttribute('data-video-id', video.id);

        // Carica gli snap
        loadSnapsForModal(videoId);

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

// Funzione per caricare gli snap nel modal
function loadSnapsForModal(videoId) {


    fetch(`/api/videos/${videoId}/snaps`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                modalSnaps = data.snaps || [];

                updateModalSnapMarkers();
            } else {

                modalSnaps = [];
                updateModalSnapMarkers();
            }
        })
        .catch(error => {
            console.error('❌ Errore caricamento snap:', error);
            modalSnaps = [];
            updateModalSnapMarkers();
        });
}

// Funzione per inizializzare il player del modal
function initializeModalVideoPlayer(video) {
    const videoPlayer = document.getElementById('modalVideoPlayer');
    modalVideoDuration = video.duration || 60;
    modalVideoPlayer = videoPlayer;

    // Event listeners per il player HTML5
    videoPlayer.addEventListener('loadedmetadata', function() {

        modalVideoDuration = videoPlayer.duration || modalVideoDuration;
        updateModalSnapMarkers();
    });

    videoPlayer.addEventListener('timeupdate', function() {
        modalCurrentVideoTime = videoPlayer.currentTime;
    });

    videoPlayer.addEventListener('durationchange', function() {

        modalVideoDuration = videoPlayer.duration;
        updateModalSnapMarkers();
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

    // Pulsante snap sempre visibile
    const snapButton = document.getElementById('modalFloatingSnapButton');
    if (snapButton) {
        snapButton.style.opacity = '1';
    }
}

// Funzione per aggiornare i marker degli snap nel modal
function updateModalSnapMarkers() {
    const markersContainer = document.getElementById('modalSnapMarkers');
    if (!markersContainer) return;

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
        marker.title = `${firstSnap.title || 'Snap'} (${snapCount} snap)`;

        marker.innerHTML = `
            <div class="snap-indicator bg-success rounded-circle d-flex align-items-center justify-content-center"
                 style="width: 30px; height: 30px; border: 2px solid white; box-shadow: 0 3px 6px rgba(0,0,0,0.4);">
                <img src="{{ asset('assets/images/snap.png') }}" alt="{{ __('common.snap') }}" style="width: 16px; height: 16px; filter: brightness(0) invert(1);">
            </div>
            ${snapCount > 1 ? `
                <div class="position-absolute top-0 end-0 bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center"
                     style="width: 24px; height: 24px; font-size: 12px; font-weight: bold; transform: translate(30%, -30%);">
                    ${snapCount}
                </div>
            ` : ''}
            <div class="snap-tooltip position-absolute bottom-100 start-50 translate-middle-x mb-1 bg-dark text-white rounded p-2"
                 style="font-size: 11px; white-space: nowrap; opacity: 0; transition: opacity 0.2s ease; pointer-events: none;">
                <strong>${firstSnap.title || 'Snap'}</strong>
                ${snapCount > 1 ? `<br><small>+${snapCount - 1} altri</small>` : ''}
            </div>
        `;

        markersContainer.appendChild(marker);
    });



    // Aggiungi event listeners per i tooltip come nella pagina video
    const snapMarkers = markersContainer.querySelectorAll('.snap-marker');
    snapMarkers.forEach(marker => {
        const tooltip = marker.querySelector('.snap-tooltip');
        if (tooltip) {
            marker.addEventListener('mouseenter', function() {
                tooltip.style.opacity = '1';
            });
            marker.addEventListener('mouseleave', function() {
                tooltip.style.opacity = '0';
            });
        }
    });
}

// Funzione per saltare al tempo specifico nel modal
function seekToTimeInModal(timestamp) {
    if (modalVideoPlayer) {
        modalVideoPlayer.currentTime = timestamp;
        modalVideoPlayer.play();
    }
}

// Funzione per mostrare/nascondere il form inline degli snap
function toggleSnapForm() {
    // Verifica se l'utente è autenticato
    const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};

    if (!isAuthenticated) {
        window.location.href = '{{ route("login") }}';
        return;
    }

    const snapForm = document.getElementById('modalSnapForm');
    const snapButton = document.getElementById('modalFloatingSnapButton');

    if (snapForm.style.display === 'none') {
        // Mostra il form
        snapForm.style.display = 'block';
        snapButton.style.display = 'none';

        // Aggiorna il tempo corrente
        updateInlineSnapTime();


    } else {
        // Nascondi il form
        snapForm.style.display = 'none';
        snapButton.style.display = 'flex';

        // Pulisci i campi
        document.getElementById('inlineSnapTitle').value = '';
        document.getElementById('inlineSnapDescription').value = '';


    }
}

// Funzione per aggiornare il tempo nel form inline
function updateInlineSnapTime() {
    const currentTimeElement = document.getElementById('inlineCurrentTime');
    const timestampElement = document.getElementById('inlineSnapTimestamp');
    const videoIdElement = document.getElementById('inlineSnapVideoId');

    if (currentTimeElement && timestampElement && videoIdElement && modalVideoPlayer) {
        const currentTime = Math.floor(modalVideoPlayer.currentTime);
        currentTimeElement.textContent = formatTimestamp(currentTime);
        timestampElement.value = currentTime;
        videoIdElement.value = modalVideoPlayer.getAttribute('data-video-id');
    }
}

// Funzione per formattare il timestamp
function formatTimestamp(seconds) {
    const minutes = Math.floor(seconds / 60);
    const remainingSeconds = seconds % 60;
    return `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
}

// Funzione per creare lo snap dal form inline
function createInlineSnap() {
    // Verifica se l'utente è autenticato
    const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};

    if (!isAuthenticated) {
        window.location.href = '{{ route("login") }}';
        return;
    }

    const title = document.getElementById('inlineSnapTitle').value.trim();
    const timestamp = parseInt(document.getElementById('inlineSnapTimestamp').value);
    const videoId = document.getElementById('inlineSnapVideoId').value;



    if (timestamp < 0 || !videoId) {

        return;
    }

    fetch(`/api/videos/${videoId}/snaps`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ title: title, timestamp: timestamp })
    })
    .then(response => {
        if (response.status === 401) {
            // Utente non autenticato
            return response.json().then(data => {
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    showErrorMessage('Devi essere autenticato per creare uno snap');
                }
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {


            // Chiudi il form
            toggleSnapForm();

            // Ricarica gli snap nel modal video
            loadVideoSnaps(videoId);

            // Mostra un messaggio di successo
            showSuccessMessage('Snap creato con successo!');
        } else {

            showErrorMessage(data.message || 'Errore nella creazione dello snap. Riprova.');
        }
    })
    .catch(error => {
        console.error('❌ Errore nella creazione dello snap:', error);
        showErrorMessage('Errore nella creazione dello snap. Riprova.');
    });
}

// Funzione per mostrare messaggio di successo
function showSuccessMessage(message) {
    const successDiv = document.createElement('div');
    successDiv.className = 'position-fixed';
    successDiv.style.cssText = 'top: 20px; right: 20px; z-index: 10002; background: rgba(40, 167, 69, 0.9); color: white; padding: 12px 20px; border-radius: 8px; font-size: 14px; backdrop-filter: blur(10px);';
    successDiv.textContent = message;
    document.body.appendChild(successDiv);

    setTimeout(() => {
        successDiv.remove();
    }, 3000);
}

// Funzione per caricare gli snap di un video
function loadVideoSnaps(videoId) {


    fetch(`/api/videos/${videoId}/snaps`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                modalSnaps = data.snaps || [];

                updateModalSnapMarkers();
            } else {

                modalSnaps = [];
                updateModalSnapMarkers();
            }
        })
        .catch(error => {
            console.error('❌ Errore ricaricamento snap:', error);
            modalSnaps = [];
            updateModalSnapMarkers();
        });
}

// Funzione per mostrare messaggio di errore
function showErrorMessage(message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'position-fixed';
    errorDiv.style.cssText = 'top: 20px; right: 20px; z-index: 10002; background: rgba(220, 53, 69, 0.9); color: white; padding: 12px 20px; border-radius: 8px; font-size: 14px; backdrop-filter: blur(10px);';
    errorDiv.textContent = message;
    document.body.appendChild(errorDiv);

    setTimeout(() => {
        errorDiv.remove();
    }, 3000);
}

// ===== FUNZIONI PER LE FOTO =====

// Funzione per toggle del contenuto foto
function togglePhotoContent() {
    const toggle = document.getElementById('photoToggle');
    const popularPhotos = document.getElementById('popularPhotos');
    const newPhotos = document.getElementById('newPhotos');
    const popularPhotoLabel = document.getElementById('popularPhotoLabel');
    const newPhotoLabel = document.getElementById('newPhotoLabel');

    if (toggle.checked) {
        // Mostra nuovi
        popularPhotos.style.display = 'none';
        newPhotos.style.display = 'block';
        popularPhotoLabel.classList.remove('text-primary');
        popularPhotoLabel.classList.add('text-muted');
        newPhotoLabel.classList.remove('text-muted');
        newPhotoLabel.classList.add('text-primary');
    } else {
        // Mostra popolari
        popularPhotos.style.display = 'block';
        newPhotos.style.display = 'none';
        popularPhotoLabel.classList.remove('text-muted');
        popularPhotoLabel.classList.add('text-primary');
        newPhotoLabel.classList.remove('text-primary');
        newPhotoLabel.classList.add('text-muted');
    }
}

// Funzione per aprire il modal foto
function openPhotoModal(photoId) {


    // Mostra il modal personalizzato
    const modal = document.getElementById('photoViewerModal');
    modal.style.display = 'block';
    document.body.style.overflow = 'hidden'; // Previene lo scroll

    // Carica la foto
    loadPhotoInModal(photoId);
}

// Funzione per chiudere il modal foto
function closePhotoModal() {
    const modal = document.getElementById('photoViewerModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto'; // Ripristina lo scroll


}

// Funzione per caricare la foto nel modal
async function loadPhotoInModal(photoId) {
    const loadingDiv = document.getElementById('modalPhotoLoading');
    const errorDiv = document.getElementById('modalPhotoError');
    const containerDiv = document.getElementById('modalPhotoContainer');
    const photoImage = document.getElementById('modalPhotoImage');

    // Mostra loading
    loadingDiv.style.display = 'block';
    errorDiv.style.display = 'none';
    containerDiv.style.display = 'none';

    try {


        // Ottieni i dati della foto
        const photoResponse = await fetch(`/api/photos/${photoId}`);
        const photoData = await photoResponse.json();

        if (!photoData.success) {
            throw new Error(photoData.message || 'Errore nel caricamento della foto');
        }

        const photo = photoData.photo;

        // Imposta il titolo del modal
        document.getElementById('photoViewerModalLabel').textContent = photo.title;

        // Carica l'immagine
        if (photo.image_url) {
            photoImage.src = photo.image_url;
            photoImage.alt = photo.title;

            // Imposta l'ID della foto
            photoImage.setAttribute('data-photo-id', photo.id);

            // Nascondi loading e mostra foto
            loadingDiv.style.display = 'none';
            containerDiv.style.display = 'block';


        } else {
            throw new Error('Nessuna immagine disponibile');
        }

    } catch (error) {
        console.error('❌ Errore caricamento foto nel modal:', error);
        loadingDiv.style.display = 'none';
        errorDiv.style.display = 'block';
        document.getElementById('modalPhotoErrorMessage').textContent = error.message;
    }
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

    // Event listeners per i label delle foto
    const popularPhotoLabel = document.getElementById('popularPhotoLabel');
    const newPhotoLabel = document.getElementById('newPhotoLabel');
    const photoToggle = document.getElementById('photoToggle');

    if (popularPhotoLabel && newPhotoLabel && photoToggle) {
        popularPhotoLabel.addEventListener('click', function() {
            photoToggle.checked = false;
            togglePhotoContent();
        });

        newPhotoLabel.addEventListener('click', function() {
            photoToggle.checked = true;
            togglePhotoContent();
        });
    }

    // Gestione chiusura modal video con ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const videoModal = document.getElementById('videoPlayerModal');
            const photoModal = document.getElementById('photoViewerModal');

            if (videoModal && videoModal.style.display === 'block') {
                closeVideoModal();
            } else if (photoModal && photoModal.style.display === 'block') {
                closePhotoModal();
            }
        }
    });

    // Gestione click fuori dal modal per chiudere
    document.getElementById('videoPlayerModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeVideoModal();
        }
    });

    document.getElementById('photoViewerModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closePhotoModal();
        }
    });

    // Gestione form di ricerca media
    const mediaSearchForm = document.getElementById('mediaSearchForm');
    if (mediaSearchForm) {
        mediaSearchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            performMediaSearch();
        });
    }

    // Inizializza i contatori di visualizzazioni per i risultati di ricerca
    initializeSearchViewCounters();
});

// Inizializza i contatori di visualizzazioni per i risultati di ricerca
function initializeSearchViewCounters() {
    const viewCounters = document.querySelectorAll('.social-view-counter');

    viewCounters.forEach(counter => {
        const contentType = counter.dataset.contentType;
        const contentId = counter.dataset.contentId;
        const viewCountSpan = counter.querySelector('.view-count');

        // Incrementa le visualizzazioni
        fetch('/api/social/views/increment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                viewable_type: contentType,
                viewable_id: contentId
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Aggiorna il contatore
                viewCountSpan.textContent = data.view_count.toLocaleString();

                // Aggiorna anche il badge
                const badge = document.querySelector(`.view-badge-${contentId}`);
                if (badge) {
                    badge.textContent = `${data.view_count} visualizzazioni`;
                }
            }
        })
        .catch(error => {
            console.error('Errore incremento visualizzazioni:', error);
        });
    });
}

// Funzione per eseguire la ricerca media
async function performMediaSearch() {
    const query = document.getElementById('searchQuery').value.trim();
    const type = document.getElementById('mediaType').value;
    const sort = document.getElementById('sortBy').value;

    // Mostra loading
    document.getElementById('searchResults').style.display = 'block';
    document.getElementById('loadingResults').style.display = 'block';
    document.getElementById('noResults').style.display = 'none';
    document.getElementById('resultsContainer').innerHTML = '';

    try {
        const params = new URLSearchParams();
        if (query) params.append('query', query);
        if (type) params.append('type', type);
        if (sort) params.append('sort', sort);

        const response = await fetch(`/api/media/search?${params.toString()}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const data = await response.json();

        if (data.success) {
            displaySearchResults(data.results, data.total);
        } else {
            showNoResults();
        }
    } catch (error) {
        console.error('Errore nella ricerca:', error);
        showNoResults();
    } finally {
        document.getElementById('loadingResults').style.display = 'none';
    }
}

// Funzione per visualizzare i risultati
function displaySearchResults(results, total) {
    const container = document.getElementById('resultsContainer');
    const countElement = document.getElementById('resultsCount');

    countElement.textContent = `${total} risultato${total !== 1 ? 'i' : ''}`;

    if (results.length === 0) {
        showNoResults();
        return;
    }

    let html = '';
    results.forEach(item => {
        if (item.type === 'video') {
            html += createVideoCard(item);
        } else if (item.type === 'photo') {
            html += createPhotoCard(item);
        }
    });

    container.innerHTML = html;

    // Inizializza i contatori di visualizzazioni per i nuovi risultati
    setTimeout(() => {
        initializeSearchViewCounters();
    }, 100);
}

// Funzione per creare card video
        function createVideoCard(video) {
            const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
            return `
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card hover-effect h-100">
                <div class="position-relative" style="cursor: pointer;" onclick="openVideoModal(${video.id})">
                    ${video.thumbnail_url ?
                        `<img src="${video.thumbnail_url}" alt="${video.title}" class="card-img-top" style="height: 200px; object-fit: cover;">` :
                        `<div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="ph-duotone ph-video-camera f-s-48 text-muted"></i>
                        </div>`
                    }
                    <div class="position-absolute top-50 start-50 translate-middle">
                        <div class="play-button bg-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                            <i class="ph-duotone ph-play f-s-24 text-primary"></i>
                        </div>
                    </div>
                    <div class="position-absolute top-0 end-0 m-2">
                        <span class="badge bg-dark f-s-11 view-badge-${video.id}">${video.view_count || video.views || 0} visualizzazioni</span>
                    </div>
                </div>
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title f-w-600 f-s-14 mb-2">${video.title}</h6>
                    ${video.description ? `<p class="text-muted f-s-12 mb-2">${video.description.substring(0, 80)}${video.description.length > 80 ? '...' : ''}</p>` : ''}

                    <!-- Social Actions -->
                    <div class="mt-auto">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex gap-2">
                                ${createSocialLikeButton('video', video.id, video.likes_count || 0)}
                                ${createSocialViewCounter('video', video.id, video.view_count || video.views || 0)}
                                ${createSocialCommentButton('video', video.id, video.comments_count || 0)}
                            </div>
                            <span class="badge bg-info f-s-11">Video</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="ph-duotone ph-calendar f-s-11 me-1"></i>
                                ${new Date(video.created_at).toLocaleDateString('it-IT')}
                            </small>
                            <small class="text-muted">
                                <i class="ph-duotone ph-user f-s-11 me-1"></i>
                                ${video.user ?
                                    `<a href="/user/${video.user.id}" class="text-decoration-none hover-effect">${video.user.name}</a>` :
                                    'Utente'
                                }
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Funzione per creare card foto
        function createPhotoCard(photo) {
            const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
            return `
        <div class="col-lg-4 col-md-6 mb-3">
            <div class="card hover-effect h-100">
                <div class="position-relative" style="cursor: pointer;" onclick="openPhotoModal(${photo.id})">
                    ${photo.image_url ?
                        `<img src="${photo.image_url}" alt="${photo.title}" class="card-img-top" style="height: 200px; object-fit: cover;">` :
                        `<div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="ph-duotone ph-image f-s-48 text-muted"></i>
                        </div>`
                    }
                    <div class="position-absolute top-50 start-50 translate-middle">
                        <div class="zoom-button bg-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                            <i class="ph-duotone ph-magnifying-glass-plus f-s-24 text-primary"></i>
                        </div>
                    </div>
                    <div class="position-absolute top-0 end-0 m-2">
                        <span class="badge bg-dark f-s-11 view-badge-${photo.id}">${photo.view_count || photo.views || 0} visualizzazioni</span>
                    </div>
                </div>
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title f-w-600 f-s-14 mb-2">${photo.title}</h6>
                    ${photo.description ? `<p class="text-muted f-s-12 mb-2">${photo.description.substring(0, 80)}${photo.description.length > 80 ? '...' : ''}</p>` : ''}

                    <!-- Social Actions -->
                    <div class="mt-auto">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex gap-2">
                                ${createSocialLikeButton('photo', photo.id, photo.likes_count || 0)}
                                ${createSocialViewCounter('photo', photo.id, photo.view_count || photo.views || 0)}
                                ${createSocialCommentButton('photo', photo.id, photo.comments_count || 0)}
                            </div>
                            <span class="badge bg-success f-s-11">Foto</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="ph-duotone ph-calendar f-s-11 me-1"></i>
                                ${new Date(photo.created_at).toLocaleDateString('it-IT')}
                            </small>
                            <small class="text-muted">
                                <i class="ph-duotone ph-user f-s-11 me-1"></i>
                                ${photo.user ?
                                    `<a href="/user/${photo.user.id}" class="text-decoration-none hover-effect">${photo.user.name}</a>` :
                                    'Utente'
                                }
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Funzione per mostrare nessun risultato
function showNoResults() {
    document.getElementById('resultsCount').textContent = '0 risultati';
    document.getElementById('noResults').style.display = 'block';
    document.getElementById('resultsContainer').innerHTML = '';
}

// Funzione per pulire la ricerca
function clearSearch() {
    document.getElementById('searchQuery').value = '';
    document.getElementById('mediaType').value = '';
    document.getElementById('sortBy').value = 'recent';
    document.getElementById('searchResults').style.display = 'none';
}

// ===== FUNZIONI SOCIAL PER RISULTATI RICERCA =====

// Funzione toggleSocialLike (già definita nei componenti, ma la includiamo qui per sicurezza)
function toggleSocialLike(button) {
    const contentType = button.dataset.contentType;
    const contentId = button.dataset.contentId;
    const likeCountSpan = button.querySelector('.like-count');
    const heartIcon = button.querySelector('img');

    // Disabilita il pulsante durante la richiesta
    button.style.pointerEvents = 'none';

            fetch('/api/social/likes/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            likeable_type: contentType,
            likeable_id: contentId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Aggiorna l'aspetto del pulsante
            if (data.liked) {
                heartIcon.style.filter = 'brightness(0) saturate(100%) invert(27%) sepia(51%) saturate(2878%) hue-rotate(346deg) brightness(104%) contrast(97%);';
            } else {
                heartIcon.style.filter = 'brightness(0) saturate(100%) invert(60%) sepia(0%) saturate(0%) hue-rotate(0deg) brightness(89%) contrast(86%);';
            }

            // Aggiorna il contatore
            likeCountSpan.textContent = data.like_count.toLocaleString();
        } else {
            console.error('Errore like:', data.message);
        }
    })
    .catch(error => {
        console.error('Errore connessione like:', error);
    })
    .finally(() => {
        button.style.pointerEvents = 'auto';
    });
}

// Crea pulsante commenti dinamico usando la stessa logica del componente
function createSocialCommentButton(contentType, contentId, commentCount) {
    return `
        <div class="social-comment-btn"
             data-content-type="${contentType}"
             data-content-id="${contentId}"
             onclick="showVideoComments(${contentId}, event)"
             title="{{ __('common.comments') }}"
             style="cursor: pointer; display: flex; flex-direction: column; align-items: center; gap: 2px; padding: 8px; border-radius: 8px; transition: all 0.2s; min-width: 60px;"
             onmouseover="this.style.backgroundColor='rgba(0,0,0,0.05)'"
             onmouseout="this.style.backgroundColor='transparent'">
            <i class="ph-duotone ph-chat-circle f-s-24 text-primary"></i>
            <span class="text-secondary comment-count f-s-12">${commentCount.toLocaleString()}</span>
        </div>
    `;
}

// Crea view counter dinamico usando la stessa logica del componente
function createSocialViewCounter(contentType, contentId, viewCount) {
    return `
        <div class="post-icon social-view-counter"
             data-content-type="${contentType}"
             data-content-id="${contentId}"
             style="display: flex; flex-direction: column; align-items: center; gap: 2px; padding: 8px; border-radius: 8px; min-width: 60px;">
            <i class="ti ti-eye f-s-24 text-primary"></i>
            <span class="text-secondary view-count f-s-12">${viewCount.toLocaleString()}</span>
        </div>
    `;
}

// Crea like button dinamico usando la stessa logica del componente
function createSocialLikeButton(contentType, contentId, likeCount, isLiked = false) {
    const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};

    if (isAuthenticated) {
        return `
            <div class="social-like-btn"
                 data-content-type="${contentType}"
                 data-content-id="${contentId}"
                 onclick="toggleSocialLike(this)"
                 title="${isLiked ? 'Rimuovi like' : 'Metti like'}"
                 style="cursor: pointer; display: flex; flex-direction: column; align-items: center; gap: 2px; padding: 8px; border-radius: 8px; transition: all 0.2s; min-width: 60px;"
                 onmouseover="this.style.backgroundColor='rgba(0,0,0,0.05)'"
                 onmouseout="this.style.backgroundColor='transparent'">
                <img src="{{ asset('assets/images/like.png') }}" alt="{{ __('common.like') }}" style="width: 24px; height: 24px; ${isLiked ? 'filter: brightness(0) saturate(100%) invert(27%) sepia(51%) saturate(2878%) hue-rotate(346deg) brightness(104%) contrast(97%);' : 'filter: brightness(0) saturate(100%) invert(60%) sepia(0%) saturate(0%) hue-rotate(0deg) brightness(89%) contrast(86%);'}">
                <span class="text-secondary like-count f-s-12">${likeCount.toLocaleString()}</span>
            </div>
        `;
    } else {
        return `
            <div class="social-like-counter"
                 style="display: flex; flex-direction: column; align-items: center; gap: 2px; padding: 8px; border-radius: 8px; min-width: 60px;">
                <img src="{{ asset('assets/images/like.png') }}" alt="{{ __('common.like') }}" style="width: 24px; height: 24px; filter: brightness(0) saturate(100%) invert(60%) sepia(0%) saturate(0%) hue-rotate(0deg) brightness(89%) contrast(86%); opacity: 0.6;">
                <span class="text-secondary like-count f-s-12">${likeCount.toLocaleString()}</span>
            </div>
        `;
    }
}

// Mostra commenti per video nei risultati
function showVideoComments(videoId, event) {
    event.stopPropagation(); // Previene l'apertura del modal
    openCommentsModal('video', videoId);
}

// Mostra commenti per foto nei risultati
function showPhotoComments(photoId, event) {
    event.stopPropagation(); // Previene l'apertura del modal
    openCommentsModal('photo', photoId);
}

// Apre il modal dei commenti
async function openCommentsModal(mediaType, mediaId) {
    // Imposta i valori nel form
    document.getElementById('commentMediaType').value = mediaType;
    document.getElementById('commentMediaId').value = mediaId;

    // Aggiorna il titolo del modal
    const modalTitle = document.getElementById('commentsModalLabel');
    modalTitle.innerHTML = `<i class="ph-duotone ph-chat-circle me-2"></i>Commenti ${mediaType === 'video' ? 'Video' : 'Foto'}`;

    // Mostra loading
    document.getElementById('commentsLoading').style.display = 'block';
    document.getElementById('commentsError').style.display = 'none';
    document.getElementById('commentsContainer').style.display = 'none';

    // Apri il modal
    const modal = new bootstrap.Modal(document.getElementById('commentsModal'));
    modal.show();

    // Carica i commenti
    await loadComments(mediaType, mediaId);
}

// Carica i commenti usando il sistema unificato
async function loadComments(mediaType, mediaId) {
    try {
        const response = await fetch(`/api/social/comments?commentable_type=${mediaType}&commentable_id=${mediaId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const data = await response.json();

        if (response.ok && data.success) {
            displayComments(data.comments);
        } else {
            throw new Error(data.message || 'Errore nel caricamento dei commenti');
        }
    } catch (error) {
        console.error('Errore caricamento commenti:', error);
        showCommentsError(error.message);
    } finally {
        document.getElementById('commentsLoading').style.display = 'none';
    }
}

// Visualizza i commenti
function displayComments(comments) {
    const commentsList = document.getElementById('commentsList');

    if (comments.length === 0) {
        commentsList.innerHTML = `
            <div class="text-center py-4">
                <i class="ph-duotone ph-chat-circle f-s-48 text-muted mb-3"></i>
                <p class="text-muted mb-0">Nessun commento ancora</p>
                <p class="text-muted f-s-14">Sii il primo a commentare!</p>
            </div>
        `;
    } else {
        let html = '';
        comments.forEach(comment => {
            const userAvatar = comment.user && comment.user.avatar_url ?
                `<img src="${comment.user.avatar_url}" alt="${comment.user.name}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">` :
                `<div class="h-40 w-40 d-flex-center rounded-circle bg-light-primary">
                    <i class="ph-duotone ph-user f-s-16 text-primary"></i>
                </div>`;

            html += `
                <div class="d-flex mb-3">
                    <div class="flex-shrink-0 me-3">
                        ${userAvatar}
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <h6 class="mb-0 f-s-14 f-w-600">
                                ${comment.user ?
                                    `<a href="/user/${comment.user.id}" class="text-decoration-none hover-effect">${comment.user.name}</a>` :
                                    'Utente'
                                }
                            </h6>
                            <small class="text-muted f-s-12">${new Date(comment.created_at).toLocaleDateString('it-IT')}</small>
                        </div>
                        <p class="mb-0 f-s-13">${comment.content}</p>
                    </div>
                </div>
            `;
        });
        commentsList.innerHTML = html;
    }

    document.getElementById('commentsContainer').style.display = 'block';
}

// Mostra errore nei commenti
function showCommentsError(message) {
    document.getElementById('commentsErrorMessage').textContent = message;
    document.getElementById('commentsError').style.display = 'block';
}

// Gestione form nuovo commento
document.addEventListener('DOMContentLoaded', function() {
    const commentForm = document.getElementById('newCommentForm');
    const commentContent = document.getElementById('commentContent');
    const charCount = document.getElementById('commentCharCount');

    if (commentForm) {
        commentForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            await submitComment();
        });
    }

    if (commentContent) {
        commentContent.addEventListener('input', function() {
            const count = this.value.length;
            charCount.textContent = count;

            if (count > 450) {
                charCount.classList.add('text-warning');
            } else {
                charCount.classList.remove('text-warning');
            }
        });
    }
});

// Invia nuovo commento
async function submitComment() {
    // Verifica se l'utente è autenticato
    const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};

    if (!isAuthenticated) {
        window.location.href = '{{ route("login") }}';
        return;
    }

    const mediaType = document.getElementById('commentMediaType').value;
    const mediaId = document.getElementById('commentMediaId').value;
    const content = document.getElementById('commentContent').value.trim();
    const submitBtn = document.getElementById('submitCommentBtn');

    if (!content) {
        alert('Inserisci un commento');
        return;
    }

    // Disabilita il pulsante durante l'invio
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="ph-duotone ph-spinner f-s-12 me-1"></i>Invio...';

    try {
        const response = await fetch('/api/social/comments', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                commentable_type: mediaType,
                commentable_id: mediaId,
                content: content
            })
        });

        const data = await response.json();

        if (response.ok) {
            // Aggiungi il nuovo commento alla lista
            const commentsList = document.getElementById('commentsList');

            // Crea il nuovo commento con avatar
            const userName = data.comment.user.name;
            const userInitials = userName.substring(0, 2).toUpperCase();
            const userAvatar = data.comment.user.avatar_url ?
                `<img src="${data.comment.user.avatar_url}" alt="${userName}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">` :
                `<div class="h-40 w-40 d-flex-center rounded-circle bg-light-primary">
                    <i class="ph-duotone ph-user f-s-16 text-primary"></i>
                </div>`;

            const newCommentHtml = `
                <div class="d-flex mb-3">
                    <div class="flex-shrink-0 me-3">
                        ${userAvatar}
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <h6 class="mb-0 f-s-14 f-w-600">${userName}</h6>
                            <small class="text-muted f-s-12">Ora</small>
                        </div>
                        <p class="mb-0 f-s-13">${data.comment.content}</p>
                    </div>
                </div>
            `;

            // Se non ci sono commenti, rimuovi il messaggio "nessun commento"
            if (commentsList.querySelector('.text-center')) {
                commentsList.innerHTML = '';
            }

            commentsList.insertAdjacentHTML('afterbegin', newCommentHtml);

            // Pulisci il form
            document.getElementById('commentContent').value = '';
            document.getElementById('commentCharCount').textContent = '0';

            // Aggiorna il contatore nel pulsante commenti con il valore dal server
            const commentButton = document.querySelector(`.social-comment-btn[data-content-id="${mediaId}"] .comment-count`);
            if (commentButton && data.comment_count !== undefined) {
                commentButton.textContent = data.comment_count;
            }

            // Mostra messaggio di successo
            showSuccessMessage('Commento inviato con successo!');
        } else {
            if (response.status === 401) {
                window.location.href = '{{ route("login") }}';
            } else if (response.status === 419) {
                // CSRF token mismatch
                alert('Errore di sicurezza. Ricarica la pagina e riprova.');
                location.reload();
            } else {
                throw new Error(data.error || data.message || 'Errore nell\'invio del commento');
            }
        }
    } catch (error) {
        console.error('Errore invio commento:', error);
        alert('Errore nell\'invio del commento: ' + error.message);
    } finally {
        // Riabilita il pulsante
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="ph-duotone ph-paper-plane-right me-1"></i>Invia commento';
    }
}

// Declare functions globally to be accessible from onclick handlers
window.openVideoModal = openVideoModal;
window.openPhotoModal = openPhotoModal;
window.closeVideoModal = closeVideoModal;
window.closePhotoModal = closePhotoModal;
</script>
@endpush
