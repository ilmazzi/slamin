@extends('layout.master')



@section('title', 'Slam in - Home')

@section('css')
<!-- Slick CSS -->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/slick/slick.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/slick/slick-theme.css') }}">
@endsection

@section('script')
<!-- Slick JS -->
<script src="{{ asset('assets/vendor/slick/slick.min.js') }}"></script>
<script src="{{ asset('assets/js/slick.js') }}"></script>

<script>
$(document).ready(function() {
    // Inizializza lo slider degli eventi
    $('#events-slider').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: true,
        autoplaySpeed: 3000,
        arrows: true,
        dots: false,
        responsive: [
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2
                }
            },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 1
                }
            }
        ]
    });
});
</script>
@endsection

@section('main-content')
<div class="page-content">
    <div class="container-fluid">

        <!-- Hero Carousel -->
        @if($carousels->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
                            @if($carousels->count() > 1)
                            <div class="carousel-indicators">
                                @foreach($carousels as $index => $carousel)
                                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $index }}"
                                        class="bg-primary {{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                        aria-label="Slide {{ $index + 1 }}"></button>
                                @endforeach
                            </div>
                            @endif
                            <div class="carousel-inner">
                                @foreach($carousels as $index => $carousel)
                                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                    @if($carousel->video_path && $carousel->videoUrl)
                                        <video class="d-block w-100" autoplay muted loop style="height: 400px; object-fit: cover;">
                                            <source src="{{ $carousel->videoUrl }}" type="video/mp4">
                                        </video>
                                    @elseif($carousel->image_path && $carousel->imageUrl)
                                        <img src="{{ $carousel->imageUrl }}" class="d-block w-100" alt="{{ $carousel->title }}" style="height: 400px; object-fit: cover;">
                                    @else
                                        <!-- Fallback per media mancante -->
                                        <div class="d-block w-100 bg-gradient-primary d-flex align-items-center justify-content-center" style="height: 400px;">
                                            <div class="text-center text-white">
                                                <i class="ph-duotone ph-image f-s-48 mb-3"></i>
                                                <h5 class="f-w-600">{{ $carousel->title }}</h5>
                                                @if($carousel->description)
                                                    <p class="mb-0">{{ $carousel->description }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                    <div class="carousel-caption d-none d-md-block bg-light-success bg-opacity-75 rounded-3 p-4 mx-auto">
                                        <h5 class="f-w-600 f-s-24 mb-3 text-dark">{{ $carousel->title }}</h5>
                                        @if($carousel->description)
                                            <p class="mb-4 f-s-16 text-primary">{{ $carousel->description }}</p>
                                        @endif
                                        @if($carousel->link_url && $carousel->link_text)
                                            <a href="{{ $carousel->link_url }}" class="btn btn-primary btn-lg hover-effect">
                                                <i class="ph-duotone ph-arrow-right f-s-16 me-2"></i>
                                                {{ $carousel->link_text }}
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @if($carousels->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                                <i class="ph ph-arrow-circle-left f-s-24 text-primary"></i>
                                <span class="visually-hidden">{{ __('home.carousel.previous') }}</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                                <i class="ph ph-arrow-circle-right f-s-24 text-primary"></i>
                                <span class="visually-hidden">{{ __('home.carousel.next') }}</span>
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Upcoming Events Slider Section -->
        @if($recentEvents->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph-duotone ph-calendar f-s-16 me-2"></i>
                            Prossimi Eventi
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="autoplay-slider app-arrow" id="events-slider">
                            @foreach($recentEvents->take(10) as $event)
                            <div class="autoplay-item">
                                <div class="card overflow-hidden hover-effect">
                                    @if($event->image_path)
                                        <img src="{{ asset('storage/' . $event->image_path) }}" class="card-img-top" alt="{{ $event->title }}" style="height: 200px; object-fit: cover;">
                                    @else
                                        @php
                                            $fallbackImages = [
                                                'assets/images/background/default-event-1.webp',
                                                'assets/images/background/default-event-2.webp',
                                                'assets/images/background/default-event-3.webp',
                                                'assets/images/background/default-event-4.webp'
                                            ];
                                            $randomImage = $fallbackImages[array_rand($fallbackImages)];
                                        @endphp
                                        <img src="{{ asset($randomImage) }}" class="card-img-top" alt="{{ $event->title }}" style="height: 200px; object-fit: cover;">
                                    @endif
                                    <div class="card-body">
                                        <h5 class="card-title f-w-600">{{ $event->title }}</h5>
                                        <p class="card-text text-muted f-s-14">
                                            <i class="ph-duotone ph-map-pin f-s-12 me-1"></i>
                                            {{ $event->venue_name }}
                                        </p>
                                        @if($event->description)
                                            <p class="card-text">{{ Str::limit($event->description, 80) }}</p>
                                        @endif
                                        <div class="d-flex justify-content-between align-items-center">
                                            <p class="card-text">
                                                <small class="text-body-secondary">
                                                    <i class="ph-duotone ph-calendar f-s-12 me-1"></i>
                                                    {{ $event->start_datetime->format('d/m/Y H:i') }}
                                                </small>
                                            </p>
                                            <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-warning">
                                                <i class="ph-duotone ph-info f-s-14 me-1"></i>Dettagli
                                            </a>
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

        <!-- Most Popular Video Section -->
        @if($mostPopularVideo)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card hover-effect border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="position-relative">
                            <div class="p-3 p-md-4">
                                <!-- Mobile First Layout -->
                                <div class="row">
                                    <!-- Video Thumbnail Column -->
                                    <div class="col-12 col-lg-6 mb-3 mb-lg-0">
                                        <div class="position-relative">
                                            <div class="position-relative overflow-hidden rounded-3" style="aspect-ratio: 16/9;">
                                                @if($mostPopularVideo->thumbnail_path || $mostPopularVideo->peertube_thumbnail_url)
                                                    <img src="{{ $mostPopularVideo->thumbnail_url }}" alt="{{ $mostPopularVideo->title }}" class="w-100 h-100" style="object-fit: cover;">
                                                @else
                                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-gradient-light">
                                                        <div class="text-center">
                                                            <i class="ph-duotone ph-video-camera f-s-48 text-muted mb-2"></i>
                                                            <p class="text-muted f-s-14 mb-0">Anteprima non disponibile</p>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="position-absolute top-0 start-0 end-0 bottom-0 bg-dark opacity-20"></div>
                                                <div class="position-absolute top-50 start-50 translate-middle">
                                                    <div class="bg-white bg-opacity-90 rounded-circle p-3 p-md-4 d-flex-center" style="width: 70px; height: 70px;">
                                                        <i class="ph-duotone ph-play f-s-24 f-s-md-36 text-primary"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Popular Badge -->
                                            <div class="position-absolute top-0 end-0 m-2 m-md-3">
                                                <span class="badge bg-warning text-dark f-s-11 fw-bold px-2 px-md-3 py-1 py-md-2 rounded-pill shadow-sm">
                                                    <i class="ph-duotone ph-trophy f-s-12 me-1"></i>
                                                    Più Popolare
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Content Column -->
                                    <div class="col-12 col-lg-6">
                                        <div class="h-100 d-flex flex-column justify-content-between">
                                            <!-- Title and Description -->
                                            <div class="mb-3">
                                                <h4 class="text-dark f-w-700 mb-2 f-s-18 f-s-md-20">{{ $mostPopularVideo->title }}</h4>
                                                @if($mostPopularVideo->description)
                                                    <p class="text-muted mb-3 f-s-14">{{ Str::limit($mostPopularVideo->description, 120) }}</p>
                                                @endif

                                                <!-- Author Info -->
                                                <a href="{{ route('user.show', $mostPopularVideo->user) }}" class="text-decoration-none hover-effect">
                                                    <div class="d-flex align-items-center mb-3 p-2 rounded-3 transition-all">
                                                        @if($mostPopularVideo->user->profile_photo)
                                                            <div class="h-40 w-40 d-flex-center b-r-50 overflow-hidden me-3">
                                                                <img src="{{ $mostPopularVideo->user->profile_photo_url }}" alt="{{ $mostPopularVideo->user->name }}" class="w-100 h-100" style="object-fit: cover;">
                                                            </div>
                                                        @else
                                                            <div class="h-40 w-40 d-flex-center b-r-50 overflow-hidden bg-gradient-primary me-3">
                                                                <span class="text-white fw-bold f-s-16">{{ substr($mostPopularVideo->user->name, 0, 2) }}</span>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <h6 class="mb-0 f-w-600 f-s-14 text-dark">{{ $mostPopularVideo->user->name }}</h6>
                                                            <small class="text-muted f-s-11">Autore del video</small>
                                                        </div>
                                                        <div class="ms-auto">
                                                            <i class="ph-duotone ph-arrow-right f-s-16 text-muted"></i>
                                                        </div>
                                                    </div>
                                                </a>

                                                <!-- Watch Button -->
                                                <a href="{{ route('videos.show', $mostPopularVideo) }}" class="btn btn-primary btn-sm hover-effect f-w-600 px-3 py-2 rounded-pill shadow-sm">
                                                    <i class="ph-duotone ph-play f-s-14 me-1"></i>
                                                    Guarda Video
                                                </a>
                                            </div>

                                            <!-- Statistics -->
                                            <div class="row g-2">
                                                <div class="col-6 col-md-3">
                                                    <div class="text-center p-2 rounded-3 txt-bg-success">
                                                        <div class="d-flex-center mb-1">
                                                            <i class="ph-duotone ph-eye f-s-16 f-s-md-18 text-info"></i>
                                                        </div>
                                                        <h6 class="mb-1 text-dark f-w-700 f-s-12 f-s-md-14">{{ number_format($mostPopularVideo->view_count) }}</h6>
                                                        <small class="text-muted f-s-10">Visualizzazioni</small>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-3">
                                                    <div class="text-center p-2 rounded-3 txt-bg-success">
                                                        <div class="d-flex-center mb-1">
                                                            <i class="ph-duotone ph-thumbs-up f-s-16 f-s-md-18 text-success"></i>
                                                        </div>
                                                        <h6 class="mb-1 text-dark f-w-700 f-s-12 f-s-md-14">{{ number_format($mostPopularVideo->like_count) }}</h6>
                                                        <small class="text-muted f-s-10">Mi Piace</small>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-3">
                                                    <div class="text-center p-2 rounded-3 txt-bg-success">
                                                        <div class="d-flex-center mb-1">
                                                            <i class="ph-duotone ph-chat-circle f-s-16 f-s-md-18 text-warning"></i>
                                                        </div>
                                                        <h6 class="mb-1 text-dark f-w-700 f-s-12 f-s-md-14">{{ number_format($mostPopularVideo->comment_count) }}</h6>
                                                        <small class="text-muted f-s-10">Commenti</small>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-3">
                                                    <div class="text-center p-2 rounded-3 txt-bg-success">
                                                        <div class="d-flex-center mb-1">
                                                            <i class="ph-duotone ph-hands-clapping f-s-16 f-s-md-18 text-danger"></i>
                                                        </div>
                                                        <h6 class="mb-1 text-dark f-w-700 f-s-12 f-s-md-14">{{ number_format($mostPopularVideo->snaps()->count()) }}</h6>
                                                        <small class="text-muted f-s-10">Snap</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Stats Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-light-primary">
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3 col-6 mb-3">
                                <div class="eshop-cards">
                                    <div class="eshop-cards-body">
                                        <div class="eshop-cards-icon bg-gradient-primary">
                                            <i class="ph-duotone ph-video-camera f-s-24 text-white"></i>
                                        </div>
                                        <h4 class="mb-1 text-primary">{{ number_format($stats['total_videos']) }}</h4>
                                        <p class="mb-0 text-muted">{{ __('home.stats.total_videos') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="eshop-cards">
                                    <div class="eshop-cards-body">
                                        <div class="eshop-cards-icon bg-gradient-success">
                                            <i class="ph-duotone ph-eye f-s-24 text-white"></i>
                                        </div>
                                        <h4 class="mb-1 text-success">{{ number_format($stats['total_views']) }}</h4>
                                        <p class="mb-0 text-muted">{{ __('home.stats.total_views') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="eshop-cards">
                                    <div class="eshop-cards-body">
                                        <div class="eshop-cards-icon bg-gradient-warning">
                                            <i class="ph-duotone ph-calendar f-s-24 text-white"></i>
                                        </div>
                                        <h4 class="mb-1 text-warning">{{ number_format($stats['total_events']) }}</h4>
                                        <p class="mb-0 text-muted">{{ __('home.stats.total_events') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="eshop-cards">
                                    <div class="eshop-cards-body">
                                        <div class="eshop-cards-icon bg-gradient-info">
                                            <i class="ph-duotone ph-users f-s-24 text-white"></i>
                                        </div>
                                        <h4 class="mb-1 text-info">{{ number_format($stats['total_users']) }}</h4>
                                        <p class="mb-0 text-muted">{{ __('home.stats.total_users') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Entry Section - Nuovi Utenti Registrati -->
        @if($newUsers->count() > 0)

        <div class="row mb-4">
            <div class="col-12">
                <h5 class="text-primary mb-3">
                    <i class="ph-duotone ph-user-plus f-s-16 me-2"></i>
                    Nuovi Utenti
                </h5>
            </div>
            @foreach($newUsers->take(3) as $user)
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="profile-container" onclick="window.location.href='{{ route('user.show', $user) }}'" style="cursor: pointer;">
                            <div class="image-details">
                                <div class="profile-image">
                                    <img src="{{ $user->banner_image_url ?? asset('assets/images/avatar/default-banner.webp?v=1') }}" alt="{{ $user->name }}" class="w-100 h-100" style="object-fit: cover;">
                                </div>
                                <div class="profile-pic">
                                    <div class="avatar-upload">
                                        <div class="avatar-preview">
                                            <div id="imgPreview">
                                               
                                                    <img src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}" class="w-100 h-100" style="object-fit: cover;">
                                               
                                                    <div class="bg-gradient-success rounded-circle d-flex align-items-center justify-content-center w-100 h-100">
                                                        <span class="text-white fw-bold f-s-20">{{ strtoupper(substr(trim($user->name), 0, 2)) ?: 'U' }}</span>
                                                    </div>
                                              
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="person-details">
                                <h5 class="f-w-600">{{ $user->name }}
                                    @if($user->verified)
                                        <img src="https://phplaravel-1384472-5380003.cloudwaysapps.com/../assets/images/profile-app/01.png" class="w-20 h-20" alt="instagram-check-mark">
                                    @endif
                                </h5>
                                <p>{{ $user->city ?? 'Località non specificata' }}</p>
                                <div class="details">
                                    <div>
                                        <h4 class="text-primary">{{ $user->videos_count }}</h4>
                                        <p class="text-secondary">Video</p>
                                    </div>
                                    <div>
                                        <h4 class="text-primary">{{ $user->followers_count ?? 0 }}</h4>
                                        <p class="text-secondary">Follower</p>
                                    </div>
                                    <div>
                                        <h4 class="text-primary">{{ $user->following_count ?? 0 }}</h4>
                                        <p class="text-secondary">Following</p>
                                    </div>
                                </div>
                                <div class="my-2">
                                    <button type="button" class="btn btn-primary b-r-22" onclick="event.stopPropagation(); followUser({{ $user->id }})">
                                        <i class="ti ti-user"></i>
                                        Follow
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                 
                    </div>
                </div>
            </div>
            @endforeach
           
        </div>
        @endif

        <!-- Poetry and Articles Section -->
        <div class="row">
            <!-- Poetry Section (Left) -->
            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card card-light-info">
                    <div class="card-header bg-gradient-info text-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ph-duotone ph-book-open f-s-16 me-2"></i>
                            Poesia
                        </h5>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="poetryToggle" onchange="togglePoetryContent(this.checked ? 'popular' : 'new')">
                            <label class="form-check-label text-white f-s-12" for="poetryToggle">
                                <span id="poetryToggleLabel">Nuovi</span>
                            </label>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- New Poetry Content -->
                        <div id="newPoetryContent">
                            <div class="row">
                                @foreach($recentPoems ?? [] as $poem)
                                <div class="col-12 mb-3">
                                    <div class="card card-light-info hover-effect border-info">
                                        <div class="card-body pa-15">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="position-relative">
                                                        <div class="rounded overflow-hidden" style="width: 60px; height: 60px;">
                                                            @if($poem->thumbnail_path)
                                                                <img src="{{ $poem->thumbnail_url }}" alt="{{ $poem->title }}" class="w-100 h-100" style="object-fit: cover;">
                                                            @else
                                                                <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-gradient-light">
                                                                    <i class="ph-duotone ph-book-open f-s-20 text-muted"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="position-absolute top-0 start-0 end-0 bottom-0 bg-dark opacity-20"></div>
                                                        <div class="position-absolute top-50 start-50 translate-middle">
                                                            <i class="ph-duotone ph-book-open f-s-12 text-white"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="card-title f-w-600 f-s-14 mb-1 text-info">{{ Str::limit($poem->title, 40) }}</h6>
                                                    <p class="text-muted f-s-12 mb-1">{{ $poem->user->name }}</p>
                                                    <div class="d-flex align-items-center">
                                                        <small class="text-muted f-s-11 me-3">
                                                            <i class="ph-duotone ph-eye f-s-10 me-1"></i>{{ number_format($poem->view_count) }}
                                                        </small>
                                                        <small class="text-muted f-s-11">
                                                            <i class="ph-duotone ph-clock f-s-10 me-1"></i>{{ $poem->created_at->diffForHumans() }}
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <a href="{{ route('poems.show', $poem) }}" class="btn btn-sm btn-gradient-info hover-effect">
                                                        <i class="ph-duotone ph-book-open f-s-12"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Popular Poetry Content (Hidden by default) -->
                        <div id="popularPoetryContent" style="display: none;">
                            <div class="row">
                                @foreach($popularPoems ?? [] as $poem)
                                <div class="col-12 mb-3">
                                    <div class="card card-light-info hover-effect border-info">
                                        <div class="card-body pa-15">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="position-relative">
                                                        <div class="rounded overflow-hidden" style="width: 60px; height: 60px;">
                                                            @if($poem->thumbnail_path)
                                                                <img src="{{ $poem->thumbnail_url }}" alt="{{ $poem->title }}" class="w-100 h-100" style="object-fit: cover;">
                                                            @else
                                                                <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-gradient-light">
                                                                    <i class="ph-duotone ph-book-open f-s-20 text-muted"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="position-absolute top-0 start-0 end-0 bottom-0 bg-dark opacity-20"></div>
                                                        <div class="position-absolute top-50 start-50 translate-middle">
                                                            <i class="ph-duotone ph-book-open f-s-12 text-white"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="card-title f-w-600 f-s-14 mb-1 text-info">{{ Str::limit($poem->title, 40) }}</h6>
                                                    <p class="text-muted f-s-12 mb-1">{{ $poem->user->name }}</p>
                                                    <div class="d-flex align-items-center">
                                                        <small class="text-muted f-s-11 me-3">
                                                            <i class="ph-duotone ph-eye f-s-10 me-1"></i>{{ number_format($poem->view_count) }}
                                                        </small>
                                                        <small class="text-muted f-s-11">
                                                            <i class="ph-duotone ph-thumbs-up f-s-10 me-1"></i>{{ number_format($poem->like_count) }}
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <a href="{{ route('poems.show', $poem) }}" class="btn btn-sm btn-gradient-info hover-effect">
                                                        <i class="ph-duotone ph-book-open f-s-12"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Footer with link to all poems -->
                        <div class="text-center mt-3">
                            <a href="{{ route('poems.index') }}" class="btn btn-outline-info btn-sm">
                                <i class="ph-duotone ph-arrow-right f-s-12 me-1"></i>
                                Vedi tutte le poesie
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Articles Section (Right) -->
            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card card-light-warning">
                    <div class="card-header bg-gradient-warning text-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ph-duotone ph-newspaper f-s-16 me-2"></i>
                            Articoli
                        </h5>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="articlesToggle" onchange="toggleArticlesContent(this.checked ? 'popular' : 'new')">
                            <label class="form-check-label text-white f-s-12" for="articlesToggle">
                                <span id="articlesToggleLabel">Nuovi</span>
                            </label>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- New Articles Content -->
                        <div id="newArticlesContent">
                            <div class="row">
                                @foreach($recentArticles ?? [] as $article)
                                <div class="col-12 mb-3">
                                    <div class="card card-light-warning hover-effect border-warning">
                                        <div class="card-body pa-15">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="rounded overflow-hidden" style="width: 60px; height: 60px;">
                                                        @if($article->image_path)
                                                            <img src="{{ asset('storage/' . $article->image_path) }}" alt="{{ $article->title }}" class="w-100 h-100" style="object-fit: cover;">
                                                        @else
                                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-gradient-light">
                                                                <i class="ph-duotone ph-newspaper f-s-20 text-muted"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="card-title f-w-600 f-s-14 mb-1 text-warning">{{ Str::limit($article->title, 40) }}</h6>
                                                    <p class="text-muted f-s-12 mb-1">{{ $article->author->name ?? 'Redazione' }}</p>
                                                    <div class="d-flex align-items-center">
                                                        <small class="text-muted f-s-11 me-3">
                                                            <i class="ph-duotone ph-eye f-s-10 me-1"></i>{{ number_format($article->view_count ?? 0) }}
                                                        </small>
                                                        <small class="text-muted f-s-11">
                                                            <i class="ph-duotone ph-clock f-s-10 me-1"></i>{{ $article->created_at->diffForHumans() }}
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <a href="{{ route('articles.show', $article) }}" class="btn btn-sm btn-gradient-warning hover-effect">
                                                        <i class="ph-duotone ph-arrow-right f-s-12"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- Popular Articles Content (Hidden by default) -->
                        <div id="popularArticlesContent" style="display: none;">
                            <div class="row">
                                @foreach($popularArticles ?? [] as $article)
                                <div class="col-12 mb-3">
                                    <div class="card card-light-warning hover-effect border-warning">
                                        <div class="card-body pa-15">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="rounded overflow-hidden" style="width: 60px; height: 60px;">
                                                        @if($article->image_path)
                                                            <img src="{{ asset('storage/' . $article->image_path) }}" alt="{{ $article->title }}" class="w-100 h-100" style="object-fit: cover;">
                                                        @else
                                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-gradient-light">
                                                                <i class="ph-duotone ph-newspaper f-s-20 text-muted"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="card-title f-w-600 f-s-14 mb-1 text-warning">{{ Str::limit($article->title, 40) }}</h6>
                                                    <p class="text-muted f-s-12 mb-1">{{ $article->author->name ?? 'Redazione' }}</p>
                                                    <div class="d-flex align-items-center">
                                                        <small class="text-muted f-s-11 me-3">
                                                            <i class="ph-duotone ph-eye f-s-10 me-1"></i>{{ number_format($article->view_count ?? 0) }}
                                                        </small>
                                                        <small class="text-muted f-s-11">
                                                            <i class="ph-duotone ph-thumbs-up f-s-10 me-1"></i>{{ number_format($article->like_count ?? 0) }}
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <a href="{{ route('articles.show', $article) }}" class="btn btn-sm btn-gradient-warning hover-effect">
                                                        <i class="ph-duotone ph-arrow-right f-s-12"></i>
                                                    </a>
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
        </div>

    </div>
</div>
@endsection

@section('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inizializza il carosello Bootstrap
    const carousel = document.getElementById('heroCarousel');
    if (carousel) {
        console.log('Carousel trovato, inizializzazione...');

        // Prova prima con l'approccio standard
        try {
            const bsCarousel = new bootstrap.Carousel(carousel, {
                interval: 5000, // 5 secondi
                ride: 'carousel', // Avvia automaticamente
                wrap: true, // Loop infinito
                keyboard: true, // Controlli da tastiera
                pause: 'hover' // Pausa al hover
            });
            console.log('Carousel inizializzato con successo!');
        } catch (error) {
            console.log('Errore inizializzazione Bootstrap:', error);

            // Fallback: carosello manuale
            console.log('Tentativo con fallback manuale...');
            initManualCarousel();
        }

        // Debug: mostra informazioni sul carosello
        const slides = carousel.querySelectorAll('.carousel-item');
        console.log('Numero di slide trovate:', slides.length);

        slides.forEach((slide, index) => {
            console.log(`Slide ${index + 1}:`, slide.classList.contains('active') ? 'ATTIVA' : 'inattiva');
        });
    } else {
        console.log('Carousel non trovato nella pagina');
    }

    // Funzione fallback per carosello manuale
    function initManualCarousel() {
        const carousel = document.getElementById('heroCarousel');
        const slides = carousel.querySelectorAll('.carousel-item');
        const indicators = carousel.querySelectorAll('.carousel-indicators button');
        const prevBtn = carousel.querySelector('.carousel-control-prev');
        const nextBtn = carousel.querySelector('.carousel-control-next');

        let currentSlide = 0;
        let interval;

        function showSlide(index) {
            // Nascondi tutte le slide
            slides.forEach(slide => slide.classList.remove('active'));
            indicators.forEach(indicator => indicator.classList.remove('active'));

            // Mostra la slide corrente
            slides[index].classList.add('active');
            if (indicators[index]) {
                indicators[index].classList.add('active');
            }

            currentSlide = index;
        }

        function nextSlide() {
            const next = (currentSlide + 1) % slides.length;
            showSlide(next);
        }

        function prevSlide() {
            const prev = (currentSlide - 1 + slides.length) % slides.length;
            showSlide(prev);
        }

        // Event listeners
        if (nextBtn) {
            nextBtn.addEventListener('click', nextSlide);
        }
        if (prevBtn) {
            prevBtn.addEventListener('click', prevSlide);
        }

        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => showSlide(index));
        });

        // Auto-scroll
        interval = setInterval(nextSlide, 5000);

        // Pausa al hover
        carousel.addEventListener('mouseenter', () => clearInterval(interval));
        carousel.addEventListener('mouseleave', () => {
            interval = setInterval(nextSlide, 5000);
        });

        console.log('Carousel manuale inizializzato!');
    }

    // Toggle functions for Poetry and Articles sections
    window.togglePoetryContent = function(type) {
        const newContent = document.getElementById('newPoetryContent');
        const popularContent = document.getElementById('popularPoetryContent');
        const toggle = document.getElementById('poetryToggle');
        const label = document.getElementById('poetryToggleLabel');
        
        if (type === 'new') {
            newContent.style.display = 'block';
            popularContent.style.display = 'none';
            toggle.checked = false;
            label.textContent = 'Nuovi';
        } else {
            newContent.style.display = 'none';
            popularContent.style.display = 'block';
            toggle.checked = true;
            label.textContent = 'Popolari';
        }
    };

    window.toggleArticlesContent = function(type) {
        const newContent = document.getElementById('newArticlesContent');
        const popularContent = document.getElementById('popularArticlesContent');
        const toggle = document.getElementById('articlesToggle');
        const label = document.getElementById('articlesToggleLabel');
        
        if (type === 'new') {
            newContent.style.display = 'block';
            popularContent.style.display = 'none';
            toggle.checked = false;
            label.textContent = 'Nuovi';
        } else {
            newContent.style.display = 'none';
            popularContent.style.display = 'block';
            toggle.checked = true;
            label.textContent = 'Popolari';
        }
    };

    // Funzione per seguire un utente
    window.followUser = function(userId) {
        // Per ora mostra un alert, in futuro implementare la logica di follow
        alert('Funzionalità Follow in sviluppo per l\'utente ID: ' + userId);
        
        // TODO: Implementare chiamata AJAX per follow/unfollow
        // fetch('/api/follow/' + userId, {
        //     method: 'POST',
        //     headers: {
        //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        //         'Content-Type': 'application/json',
        //     }
        // })
        // .then(response => response.json())
        // .then(data => {
        //     if (data.success) {
        //         // Aggiorna il bottone
        //         const button = event.target;
        //         button.innerHTML = data.following ? '<i class="ti ti-user-check"></i> Following' : '<i class="ti ti-user"></i> Follow';
        //         button.classList.toggle('btn-success', data.following);
        //         button.classList.toggle('btn-primary', !data.following);
        //     }
        // });
    };

    // Stili personalizzati per gli switch
    document.addEventListener('DOMContentLoaded', function() {
        // Aggiungi stili CSS personalizzati
        const style = document.createElement('style');
        style.textContent = `
            .form-check-input:checked {
                background-color: #fff;
                border-color: #fff;
            }
            .form-check-input:focus {
                border-color: #fff;
                box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.25);
            }
            .form-check-label {
                cursor: pointer;
                user-select: none;
            }
        `;
        document.head.appendChild(style);
    });
});
</script>
@endsection
