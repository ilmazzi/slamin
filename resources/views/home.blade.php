@extends('layout.master')

@section('title', 'Poetry Slam - Home')

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
                                    @if($carousel->video_path)
                                        <video class="d-block w-100" autoplay muted loop style="height: 400px; object-fit: cover;">
                                            <source src="{{ $carousel->videoUrl }}" type="video/mp4">
                                        </video>
                                    @else
                                        <img src="{{ $carousel->imageUrl }}" class="d-block w-100" alt="{{ $carousel->title }}" style="height: 400px; object-fit: cover;">
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
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
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
                                        <p class="mb-0 text-muted">Video Totali</p>
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
                                        <p class="mb-0 text-muted">Visualizzazioni</p>
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
                                        <p class="mb-0 text-muted">Eventi</p>
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
                                        <p class="mb-0 text-muted">Utenti</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Popular Videos Section -->
        @if($popularVideos->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-light-success">
                    <div class="card-header bg-gradient-success text-white">
                        <h5 class="card-title mb-0">
                            <i class="ph-duotone ph-trend-up f-s-16 me-2"></i>
                            Video Più Popolari
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($popularVideos as $video)
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card hover-effect border-success">
                                    <div class="position-relative">
                                        @if($video->thumbnail_path)
                                            <img src="{{ Storage::url($video->thumbnail_path) }}" alt="{{ $video->title }}" class="card-img-top">
                                        @else
                                            <div class="card-img-top bg-gradient-light d-flex align-items-center justify-content-center">
                                                <i class="ph-duotone ph-video-camera f-s-48 text-success"></i>
                                            </div>
                                        @endif
                                        <div class="position-absolute top-0 end-0 m-2">
                                            <span class="badge bg-gradient-success f-s-11">
                                                <i class="ph-duotone ph-eye f-s-12 me-1"></i>
                                                {{ $video->view_count }} visualizzazioni
                                            </span>
                                        </div>
                                    </div>
                                    <div class="card-body pa-20">
                                        <h6 class="card-title f-w-600 f-s-16 mb-2 text-success">{{ $video->title }}</h6>
                                        @if($video->description)
                                            <p class="text-muted f-s-14 mb-3">{{ Str::limit($video->description, 80) }}</p>
                                        @endif
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted f-s-12">
                                                <i class="ph-duotone ph-user f-s-12 me-1"></i>
                                                {{ $video->user->name }}
                                            </small>
                                            <a href="{{ route('videos.play', $video) }}" class="btn btn-sm btn-gradient-success hover-effect">
                                                <i class="ph-duotone ph-play f-s-14 me-1"></i>Guarda
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

        <!-- Recent Events Section -->
        @if($recentEvents->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-light-warning">
                    <div class="card-header bg-gradient-warning text-white">
                        <h5 class="card-title mb-0">
                            <i class="ph-duotone ph-calendar f-s-16 me-2"></i>
                            Prossimi Eventi
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

        <!-- Top Poets Section -->
        @if($topPoets->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-light-info">
                    <div class="card-header bg-gradient-info text-white">
                        <h5 class="card-title mb-0">
                            <i class="ph-duotone ph-crown f-s-16 me-2"></i>
                            Poeti Più Attivi
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($topPoets as $poet)
                            <div class="col-lg-4 col-md-6 mb-3">
                                <div class="card card-light-info hover-effect border-info">
                                    <div class="card-body pa-20 text-center">
                                        <div class="mb-3">
                                            <div class="bg-gradient-info rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                                <i class="ph-duotone ph-user-circle f-s-48 text-white"></i>
                                            </div>
                                        </div>
                                        <h6 class="card-title f-w-600 f-s-16 mb-1 text-info">{{ $poet->name }}</h6>
                                        <p class="text-muted f-s-14 mb-3">
                                            <i class="ph-duotone ph-video-camera f-s-12 me-1"></i>
                                            {{ $poet->videos_count }} video pubblicati
                                        </p>
                                        <a href="{{ route('user.show', $poet) }}" class="btn btn-sm btn-gradient-info hover-effect">
                                            <i class="ph-duotone ph-user f-s-14 me-1"></i>Profilo
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
                            <h4 class="mb-3 text-primary">Unisciti alla Community Poetry Slam</h4>
                            <p class="text-muted mb-4">Carica i tuoi video, partecipa agli eventi e condividi la tua passione per la poesia</p>
                        </div>
                        <div class="d-flex justify-content-center gap-3">
                            @guest
                                <a href="{{ route('register') }}" class="btn btn-gradient-primary hover-effect btn-lg">
                                    <i class="ph-duotone ph-user-plus f-s-16 me-2"></i>Registrati
                                </a>
                                <a href="{{ route('login') }}" class="btn btn-outline-primary hover-effect btn-lg">
                                    <i class="ph-duotone ph-sign-in f-s-16 me-2"></i>Accedi
                                </a>
                            @else
                                <a href="{{ route('videos.upload') }}" class="btn btn-gradient-success hover-effect btn-lg">
                                    <i class="ph-duotone ph-upload f-s-16 me-2"></i>Carica Video
                                </a>
                                <a href="{{ route('events.index') }}" class="btn btn-outline-success hover-effect btn-lg">
                                    <i class="ph-duotone ph-calendar f-s-16 me-2"></i>Vedi Eventi
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
