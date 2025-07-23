@extends('layout.master')

@section('title', 'Slam in - Home')

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
                                        class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}"
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
                                    <div class="carousel-caption d-none d-md-block">
                                        <h5 class="f-w-600 f-s-24 mb-3">{{ $carousel->title }}</h5>
                                        @if($carousel->description)
                                            <p class="mb-4 f-s-16">{{ $carousel->description }}</p>
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
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">{{ __('home.carousel.previous') }}</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">{{ __('home.carousel.next') }}</span>
                            </button>
                            @endif
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

        <!-- Recent Events Section -->
        @if($recentEvents->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-light-warning">
                    <div class="card-header bg-gradient-warning text-white">
                        <h5 class="card-title mb-0">
                            <i class="ph-duotone ph-calendar f-s-16 me-2"></i>
                            {{ __('home.recent_events.title') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($recentEvents as $event)
                            <div class="col-lg-6 col-md-12 mb-3">
                                <div class="card card-light-warning hover-effect border-warning">
                                    <div class="card-body pa-20">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div>
                                                <h6 class="card-title f-w-600 f-s-16 mb-1 text-warning">{{ $event->title }}</h6>
                                                <p class="text-muted f-s-14 mb-0">
                                                    <i class="ph-duotone ph-map-pin f-s-12 me-1"></i>
                                                    {{ $event->venue_name }}
                                                </p>
                                            </div>
                                            <span class="badge bg-gradient-warning f-s-11">
                                                <i class="ph-duotone ph-calendar f-s-12 me-1"></i>
                                                {{ $event->start_datetime->format('d/m/Y') }}
                                            </span>
                                        </div>
                                        @if($event->description)
                                            <p class="text-muted f-s-14 mb-3">{{ Str::limit($event->description, 120) }}</p>
                                        @endif
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted f-s-12">
                                                <i class="ph-duotone ph-clock f-s-12 me-1"></i>
                                                {{ $event->start_datetime->format('H:i') }}
                                            </small>
                                            <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-gradient-warning hover-effect">
                                                <i class="ph-duotone ph-info f-s-14 me-1"></i>{{ __('home.recent_events.details') }}
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

        <!-- Top Poets Section -->
        @if($topPoets->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-light-info">
                    <div class="card-header bg-gradient-info text-white">
                        <h5 class="card-title mb-0">
                            <i class="ph-duotone ph-crown f-s-16 me-2"></i>
                            {{ __('home.top_poets.title') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($topPoets as $poet)
                            <div class="col-lg-4 col-md-6 mb-3">
                                <div class="card card-light-info hover-effect border-info">
                                    <div class="card-body pa-20 text-center">
                                        <div class="mb-3">
                                            @if($poet->profile_photo)
                                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center overflow-hidden" style="width: 80px; height: 80px;">
                                                    <img src="{{ $poet->profile_photo_url }}" alt="{{ $poet->name }}" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                                                </div>
                                            @else
                                                <div class="bg-gradient-info rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                                    <span class="text-white fw-bold f-s-24">{{ substr($poet->name, 0, 2) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <h6 class="card-title f-w-600 f-s-16 mb-1 text-info">{{ $poet->name }}</h6>
                                        <p class="text-muted f-s-14 mb-3">
                                            <i class="ph-duotone ph-video-camera f-s-12 me-1"></i>
                                            {{ $poet->videos_count }} {{ __('home.top_poets.videos_count') }}
                                        </p>
                                        <a href="{{ route('user.show', $poet) }}" class="btn btn-sm btn-gradient-info hover-effect">
                                            <i class="ph-duotone ph-user f-s-14 me-1"></i>{{ __('home.top_poets.profile') }}
                                        </a>
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

        <!-- Call to Action -->
        <div class="row">
            <div class="col-12">
                <div class="card card-light-primary">
                    <div class="card-body pa-40 text-center">
                        <div class="mb-4">
                            <div class="bg-gradient-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 100px; height: 100px;">
                                <i class="ph-duotone ph-users f-s-48 text-white"></i>
                            </div>
                            <h4 class="mb-3 text-primary">{{ __('home.call_to_action.title') }}</h4>
                            <p class="text-muted mb-4">{{ __('home.call_to_action.description') }}</p>
                        </div>
                        <div class="d-flex justify-content-center gap-3">
                            @guest
                                <a href="{{ route('register') }}" class="btn btn-gradient-primary hover-effect btn-lg">
                                    <i class="ph-duotone ph-user-plus f-s-16 me-2"></i>{{ __('home.call_to_action.register') }}
                                </a>
                                <a href="{{ route('login') }}" class="btn btn-outline-primary hover-effect btn-lg">
                                    <i class="ph-duotone ph-sign-in f-s-16 me-2"></i>{{ __('home.call_to_action.login') }}
                                </a>
                            @else
                                <a href="{{ route('videos.upload') }}" class="btn btn-gradient-success hover-effect btn-lg">
                                    <i class="ph-duotone ph-upload f-s-16 me-2"></i>{{ __('home.call_to_action.upload_video') }}
                                </a>
                                <a href="{{ route('events.index') }}" class="btn btn-outline-success hover-effect btn-lg">
                                    <i class="ph-duotone ph-calendar f-s-16 me-2"></i>{{ __('home.call_to_action.view_events') }}
                                </a>
                            @endguest
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
});
</script>
@endsection
