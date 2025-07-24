@extends('layout.master')

@section('title', 'Media - Slamin')

@section('css')
<!-- Slick CSS -->
<link rel="stylesheet" href="{{ asset('assets/vendor/slick/slick.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/slick/slick-theme.css') }}">
<!-- GLightbox CSS -->
<link rel="stylesheet" href="{{ asset('assets/vendor/glightbox/glightbox.min.css') }}">
@endsection

@section('main-content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">
                <i class="ph ph-video-camera me-2"></i>
                Media
            </h4>
            <ul class="app-line-breadcrumbs mb-3">
                <li class="">
                    <a href="{{ route('dashboard') }}" class="f-s-14 f-w-500">
                        <span>
                            <i class="ph-duotone ph-house f-s-16"></i> Dashboard
                        </span>
                    </a>
                </li>
                <li class="active">
                    <a href="#" class="f-s-14 f-w-500">Media</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-6 mb-3">
            <div class="card card-light-primary hover-effect">
                <div class="card-body text-center">
                    <div class="f-s-48 f-w-700 text-primary">{{ $stats['total_videos'] }}</div>
                    <div class="f-s-14 text-muted">Video</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card card-light-success hover-effect">
                <div class="card-body text-center">
                    <div class="f-s-48 f-w-700 text-success">{{ $stats['total_photos'] }}</div>
                    <div class="f-s-14 text-muted">Foto</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card card-light-warning hover-effect">
                <div class="card-body text-center">
                    <div class="f-s-48 f-w-700 text-warning">{{ $stats['total_likes'] }}</div>
                    <div class="f-s-14 text-muted">Like</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card card-light-info hover-effect">
                <div class="card-body text-center">
                    <div class="f-s-48 f-w-700 text-info">{{ $stats['total_comments'] }}</div>
                    <div class="f-s-14 text-muted">Commenti</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Media Slider -->
    @if(isset($allMedia) && $allMedia->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph ph-star me-2"></i>
                        Media in Evidenza
                    </h5>
                </div>
                <div class="card-body">
                    <div class="story-container app-arrow">
                        @foreach($allMedia->take(8) as $media)
                        <div>
                            @if($media['type'] === 'video')
                                <a href="{{ $media['item']->peertube_embed_url ?? asset('storage/' . $media['item']->file_path) }}" 
                                   class="glightbox story" data-glightbox="type: video; zoomable: true;">
                                    <div class="position-relative">
                                        <video src="{{ $media['item']->peertube_embed_url ?? asset('storage/' . $media['item']->file_path) }}" 
                                               poster="{{ $media['item']->thumbnail_path ? $media['item']->thumbnail_url : '' }}"
                                               class="rounded img-fluid">
                                        </video>
                                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
                                            <i class="ph ph-play-circle text-white f-s-48"></i>
                                        </div>
                                    </div>
                                    <div class="h-50 w-50 d-flex-center b-r-50 overflow-hidden story-icon bg-primary">
                                        <img src="{{ $media['user']->profile_photo_url }}" alt="{{ $media['user']->getDisplayName() }}" class="img-fluid">
                                    </div>
                                </a>
                            @else
                                <a href="{{ asset($media['item']->image_path) }}" 
                                   class="glightbox story" data-glightbox="type: image; zoomable: true;">
                                    <img src="{{ asset($media['item']->image_path) }}" 
                                         alt="{{ $media['item']->alt_text }}" class="rounded img-fluid">
                                    <div class="h-50 w-50 d-flex-center b-r-50 overflow-hidden story-icon bg-primary">
                                        <img src="{{ $media['user']->profile_photo_url }}" alt="{{ $media['user']->getDisplayName() }}" class="img-fluid">
                                    </div>
                                </a>
                            @endif
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Filters and Sort -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="btn-group" role="group">
                                <button type="button" class="btn {{ $type === 'all' ? 'btn-primary' : 'btn-outline-primary' }}" 
                                        onclick="filterMedia('all')">
                                    <i class="ph ph-grid me-1"></i> Tutto
                                </button>
                                <button type="button" class="btn {{ $type === 'videos' ? 'btn-primary' : 'btn-outline-primary' }}" 
                                        onclick="filterMedia('videos')">
                                    <i class="ph ph-video-camera me-1"></i> Video
                                </button>
                                <button type="button" class="btn {{ $type === 'photos' ? 'btn-primary' : 'btn-outline-primary' }}" 
                                        onclick="filterMedia('photos')">
                                    <i class="ph ph-image me-1"></i> Foto
                                </button>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <select class="form-select d-inline-block w-auto" onchange="sortMedia(this.value)">
                                <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Più recenti</option>
                                <option value="popular" {{ $sort === 'popular' ? 'selected' : '' }}>Più popolari</option>
                                <option value="oldest" {{ $sort === 'oldest' ? 'selected' : '' }}>Più vecchi</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Media Content -->
    <div class="row">
        <!-- Video Gallery -->
        @if($type === 'videos' || $type === 'all')
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Gallery Video ({{ $allMedia->where('type', 'video')->count() }} video totali)</h5>
                    </div>
                    <div class="card-body">
                        <div class="col-12">
                            <div class="photos-container">
                                @php
                                    $allVideos = $allMedia->where('type', 'video')->shuffle();
                                    $firstVideo = $allVideos->first();
                                    $secondVideo = $allVideos->get(1);
                                @endphp
                                
                                @if($firstVideo)
                                    <div class="left-main-img img-box">
                                        <a href="{{ $firstVideo['item']->peertube_embed_url ?? asset('storage/' . $firstVideo['item']->file_path) }}" 
                                           class="glightbox" data-glightbox="type: video">
                                            <img src="{{ $firstVideo['item']->thumbnail_path ? $firstVideo['item']->thumbnail_url : asset('assets/images/placeholder.jpg') }}" 
                                                 alt="{{ $firstVideo['item']->title }}">
                                            <div class="transparent-box">
                                                <div class="caption">
                                                    <div class="d-flex align-items-center">
                                                        <a href="{{ route('user.show', $firstVideo['user']->id) }}" class="text-decoration-none me-2">
                                                            <img src="{{ $firstVideo['user']->profile_photo_url }}" 
                                                                 alt="{{ $firstVideo['user']->getDisplayName() }}" 
                                                                 class="rounded-circle" style="width: 30px; height: 30px;">
                                                        </a>
                                                        <i class="ph ph-play-circle"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endif
                                
                                @if($secondVideo)
                                    <div class="right-main-img img-box">
                                        <a href="{{ $secondVideo['item']->peertube_embed_url ?? asset('storage/' . $secondVideo['item']->file_path) }}" 
                                           class="glightbox" data-glightbox="type: video">
                                            <img src="{{ $secondVideo['item']->thumbnail_path ? $secondVideo['item']->thumbnail_url : asset('assets/images/placeholder.jpg') }}" 
                                                 alt="{{ $secondVideo['item']->title }}">
                                            <div class="transparent-box">
                                                <div class="caption">
                                                    <div class="d-flex align-items-center">
                                                        <a href="{{ route('user.show', $secondVideo['user']->id) }}" class="text-decoration-none me-2">
                                                            <img src="{{ $secondVideo['user']->profile_photo_url }}" 
                                                                 alt="{{ $secondVideo['user']->getDisplayName() }}" 
                                                                 class="rounded-circle" style="width: 30px; height: 30px;">
                                                        </a>
                                                        <i class="ph ph-play-circle"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Photo Gallery -->
        @if($type === 'photos' || $type === 'all')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Gallery Foto ({{ $allMedia->where('type', 'photo')->count() }} foto totali)</h5>
                    </div>
                    <div class="card-body">
                        <div class="col-12">
                            <div class="photos-container">
                                @php
                                    $allPhotos = $allMedia->where('type', 'photo')->shuffle();
                                    $firstPhoto = $allPhotos->first();
                                    $remainingPhotos = $allPhotos->slice(1, 3);
                                    $extraPhotos = $allPhotos->slice(4);
                                @endphp
                                
                                @if($firstPhoto)
                                    <div class="left-main-img img-box">
                                        <a href="{{ asset($firstPhoto['item']->image_path) }}" 
                                           class="glightbox" data-glightbox="type: image; zoomable: true;">
                                            <img src="{{ asset($firstPhoto['item']->image_path) }}" 
                                                 alt="{{ $firstPhoto['item']->alt_text }}">
                                            <div class="transparent-box2">
                                                <div class="captions">
                                                    <div class="d-flex align-items-center">
                                                        <a href="{{ route('user.show', $firstPhoto['user']->id) }}" class="text-decoration-none me-2">
                                                            <img src="{{ $firstPhoto['user']->profile_photo_url }}" 
                                                                 alt="{{ $firstPhoto['user']->getDisplayName() }}" 
                                                                 class="rounded-circle" style="width: 30px; height: 30px;">
                                                        </a>
                                                        <span>{{ $firstPhoto['item']->title }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endif
                                
                                @if($remainingPhotos->count() > 0)
                                    <div>
                                        <div class="sub">
                                            @foreach($remainingPhotos as $photo)
                                                <div class="img-box">
                                                    <a href="{{ asset($photo['item']->image_path) }}" 
                                                       class="glightbox" data-glightbox="type: image">
                                                        <img src="{{ asset($photo['item']->image_path) }}" 
                                                             alt="{{ $photo['item']->alt_text }}">
                                                        <div class="transparent-box2">
                                                            <div class="captions">
                                                                <div class="d-flex align-items-center">
                                                                    <a href="{{ route('user.show', $photo['user']->id) }}" class="text-decoration-none me-2">
                                                                        <img src="{{ $photo['user']->profile_photo_url }}" 
                                                                             alt="{{ $photo['user']->getDisplayName() }}" 
                                                                             class="rounded-circle" style="width: 25px; height: 25px;">
                                                                    </a>
                                                                    <span>{{ $photo['item']->title }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            @endforeach
                                            
                                            @if($extraPhotos->count() > 0)
                                                <div id="multi-link" class="img-box">
                                                    <a href="{{ asset($extraPhotos->first()['item']->image_path) }}" 
                                                       class="glightbox" data-glightbox="type: image">
                                                        <img src="{{ asset($extraPhotos->first()['item']->image_path) }}" 
                                                             alt="{{ $extraPhotos->first()['item']->alt_text }}">
                                                        <div class="transparent-box">
                                                            <div class="caption">
                                                                +{{ $extraPhotos->count() }}
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                                
                                @if($extraPhotos->count() > 1)
                                    <div id="more-img" class="extra-images-container hide-element">
                                        @foreach($extraPhotos->slice(1) as $photo)
                                            <a href="{{ asset($photo['item']->image_path) }}" 
                                               class="glightbox" data-glightbox="type: image">
                                                <img src="{{ asset($photo['item']->image_path) }}" 
                                                     alt="{{ $photo['item']->alt_text }}">
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Tutti i Media - Lista Completa -->
        @if($type === 'all')
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5>Tutti i Media ({{ $allMedia->count() }} totali)</h5>
                        <p class="text-muted mb-0">Clicca su un media per visualizzarlo in fullscreen</p>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($allMedia->shuffle() as $media)
                                <div class="col-md-4 col-lg-3 mb-3" data-type="{{ $media['type'] }}" data-id="{{ $media['item']->id }}">
                                    <div class="card hover-effect">
                                        <div class="card-header p-2">
                                            <div class="d-flex align-items-center">
                                                <a href="{{ route('user.show', $media['user']->id) }}" class="text-decoration-none">
                                                    <img src="{{ $media['user']->profile_photo_url }}" 
                                                         alt="{{ $media['user']->getDisplayName() }}" 
                                                         class="rounded-circle me-2" style="width: 32px; height: 32px;">
                                                </a>
                                                <div>
                                                    <a href="{{ route('user.show', $media['user']->id) }}" class="text-decoration-none">
                                                        <h6 class="mb-0 text-dark small">{{ $media['user']->getDisplayName() }}</h6>
                                                    </a>
                                                    <small class="text-muted">{{ $media['created_at']->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body p-2">
                                            @if($media['type'] === 'photo')
                                                <a href="{{ asset($media['item']->image_path) }}" 
                                                   class="glightbox" data-glightbox="type: image">
                                                    <img src="{{ asset($media['item']->image_path) }}" 
                                                         alt="{{ $media['item']->alt_text }}" 
                                                         class="img-fluid rounded">
                                                </a>
                                            @else
                                                <a href="{{ $media['item']->peertube_embed_url ?? asset('storage/' . $media['item']->file_path) }}" 
                                                   class="glightbox" data-glightbox="type: video">
                                                    <div class="position-relative">
                                                        <img src="{{ $media['item']->thumbnail_path ? $media['item']->thumbnail_url : asset('assets/images/placeholder.jpg') }}" 
                                                             alt="{{ $media['item']->title }}" 
                                                             class="img-fluid rounded">
                                                        <div class="position-absolute top-50 start-50 translate-middle">
                                                            <i class="ph ph-play-circle text-white" style="font-size: 2rem;"></i>
                                                        </div>
                                                    </div>
                                                </a>
                                            @endif
                                            
                                            <div class="mt-2">
                                                <h6 class="mb-1 small">{{ $media['item']->title }}</h6>
                                                @if($media['item']->description)
                                                    <p class="text-muted small mb-2">{{ Str::limit($media['item']->description, 50) }}</p>
                                                @endif
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="text-muted small">
                                                        <i class="ph ph-heart me-1"></i>{{ $media['likes_count'] }}
                                                        <i class="ph ph-chat-circle ms-2 me-1"></i>{{ $media['comments_count'] }}
                                                    </div>
                                                    <div>
                                                        <button class="btn btn-sm btn-outline-primary" onclick="toggleLike('{{ $media['type'] }}', {{ $media['item']->id }})">
                                                            <i class="ph ph-heart"></i>
                                                        </button>
                                                        @if($media['type'] === 'video')
                                                            <button class="btn btn-sm btn-outline-secondary" onclick="showComments('{{ $media['type'] }}', {{ $media['item']->id }})">
                                                                <i class="ph ph-chat-circle"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Comments Modal -->
<div class="modal fade" id="commentsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Commenti</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="commentsContent"></div>
                <div class="mt-3">
                    <textarea class="form-control comment-input" placeholder="Scrivi un commento..." rows="3"></textarea>
                    <button class="btn btn-primary mt-2" onclick="submitComment()">Invia</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Media Modal -->
<div class="modal fade" id="mediaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Media</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="mediaModalContent"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<!-- Slick JS -->
<script src="{{ asset('assets/vendor/slick/slick.min.js') }}"></script>
<!-- GLightbox JS -->
<script src="{{ asset('assets/vendor/glightbox/glightbox.min.js') }}"></script>

<script>
// Initialize Slick Slider
$(document).ready(function() {
    $('.story-container').slick({
        dots: false,
        infinite: true,
        speed: 300,
        slidesToShow: 6,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        responsive: [
            {
                breakpoint: 1200,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            }
        ]
    });
});

// Initialize GLightbox
const lightbox = GLightbox({
    selector: '.glightbox'
});

let currentMediaType = '';
let currentMediaId = 0;

// Filter media function
function filterMedia(type) {
    window.location.href = '{{ route("media.index") }}?type=' + type + '&sort={{ $sort }}';
}

// Sort media function
function sortMedia(sort) {
    window.location.href = '{{ route("media.index") }}?type={{ $type }}&sort=' + sort;
}

// Toggle like function
function toggleLike(type, id) {
    @auth
    fetch('{{ route("media.like") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            type: type,
            id: id
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update like count and button state
            const button = event.target.closest('button');
            const icon = button.querySelector('i');
            const statsElement = button.closest('.card').querySelector('.text-muted small:first-child');
            
            if (data.liked) {
                icon.style.color = '#e74c3c';
                statsElement.innerHTML = `<i class="ph ph-heart me-1"></i>${data.likes_count}`;
            } else {
                icon.style.color = '';
                statsElement.innerHTML = `<i class="ph ph-heart me-1"></i>${data.likes_count}`;
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
    @else
    window.location.href = '{{ route("login") }}';
    @endauth
}

function showComments(type, id) {
    currentMediaType = type;
    currentMediaId = id;
    
    // Carica commenti via AJAX
    fetch(`/media/${type}/${id}/comments`)
        .then(response => response.json())
        .then(data => {
            let commentsHtml = '';
            data.comments.forEach(comment => {
                commentsHtml += `
                    <div class="d-flex gap-2 mb-2 p-2 bg-light rounded">
                        <img src="${comment.user.profile_photo_url}" alt="${comment.user.name}" class="rounded-circle" style="width: 30px; height: 30px;">
                        <div>
                            <small class="f-w-600">${comment.user.name}</small>
                            <div class="small">${comment.content}</div>
                        </div>
                    </div>
                `;
            });
            
            document.getElementById('commentsContent').innerHTML = commentsHtml;
            $('#commentsModal').modal('show');
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('commentsContent').innerHTML = '<p>Errore nel caricamento dei commenti</p>';
            $('#commentsModal').modal('show');
        });
}

function submitComment() {
    const content = document.querySelector('.comment-input').value;
    if (!content.trim()) return;
    
    @auth
    fetch('{{ route("media.comment") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            type: currentMediaType,
            id: currentMediaId,
            content: content
        })
    })
    .then(response => response.json())
    .then(data => {
        // Aggiungi nuovo commento alla lista
        const commentsContent = document.getElementById('commentsContent');
        const newComment = `
            <div class="d-flex gap-2 mb-2 p-2 bg-light rounded">
                <img src="${data.comment.user.profile_photo_url}" alt="${data.comment.user.name}" class="rounded-circle" style="width: 30px; height: 30px;">
                <div>
                    <small class="f-w-600">${data.comment.user.name}</small>
                    <div class="small">${data.comment.content}</div>
                </div>
            </div>
        `;
        commentsContent.insertAdjacentHTML('afterbegin', newComment);
        
        // Pulisci input
        document.querySelector('.comment-input').value = '';
        
        // Aggiorna contatore commenti
        const mediaItem = document.querySelector(`[data-type="${currentMediaType}"][data-id="${currentMediaId}"]`);
        const commentCount = mediaItem.querySelector('.text-muted small:last-child');
        commentCount.innerHTML = `<i class="ph ph-chat-circle me-1"></i>${data.comments_count}`;
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Errore durante l\'invio del commento');
    });
    @else
    window.location.href = '{{ route("login") }}';
    @endauth
}

function openMediaModal(type, id) {
    // Reindirizza alla pagina del video o mostra modal con foto
    if (type === 'video') {
        window.location.href = `{{ route('videos.show', '') }}/${id}`;
    } else {
        // Per le foto, mostra in modal
        const mediaItem = document.querySelector(`[data-type="${type}"][data-id="${id}"]`);
        const img = mediaItem.querySelector('img');
        document.getElementById('mediaModalContent').innerHTML = `
            <img src="${img.src}" class="img-fluid" alt="Media">
        `;
        $('#mediaModal').modal('show');
    }
}
</script>
@endsection 