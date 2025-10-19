<div class="page-content">
    <div class="container-fluid">
        <!-- Hero Carousel -->
        @if ($carousels && $carousels->count() > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body p-0">
                            <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
                                @if ($carousels && $carousels->count() > 1)
                                    <div class="carousel-indicators">
                                        @foreach ($carousels as $index => $carousel)
                                            <button type="button" data-bs-target="#heroCarousel"
                                                data-bs-slide-to="{{ $index }}"
                                                class="bg-primary {{ $index === 0 ? 'active' : '' }}"
                                                aria-current="{{ $index === 0 ? 'true' : 'false' }}"
                                                aria-label="Slide {{ $index + 1 }}"></button>
                                        @endforeach
                                    </div>
                                @endif
                                <div class="carousel-inner">
                                    @foreach ($carousels as $index => $carousel)
                                        <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                            @if ($carousel->video_path && $carousel->videoUrl)
                                                <video class="d-block w-100" autoplay muted loop style="height: 400px; object-fit: cover;">
                                                    <source src="{{ $carousel->videoUrl }}" type="video/mp4">
                                                </video>
                                                <div class="carousel-caption d-md-block bg-white rounded-3 p-4 shadow" style="position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); max-width: 80%; text-align: center;">
                                                    <h5 class="f-w-600 f-s-24 mb-3 text-dark">{{ $carousel->content_title ?? $carousel->title }}</h5>
                                                    @if ($carousel->content_description ?? $carousel->description)
                                                        <p class="mb-4 f-s-16 text-primary">{{ $carousel->content_description ?? $carousel->description }}</p>
                                                    @endif
                                                    @if ($carousel->content_url ?? $carousel->link_url)
                                                        <a href="{{ $carousel->content_url ?? $carousel->link_url }}" class="btn btn-primary btn-lg">
                                                            <i class="ph-duotone ph-arrow-right f-s-16 me-2"></i>
                                                            {{ $carousel->link_text ?? 'Visualizza' }}
                                                        </a>
                                                    @endif
                                                </div>
                                            @elseif($carousel->image_path && $carousel->imageUrl)
                                                <img src="{{ $carousel->imageUrl }}" class="d-block w-100" alt="{{ $carousel->title }}" style="height: 400px; object-fit: cover;">
                                                <div class="carousel-caption d-md-block bg-white rounded-3 p-4 shadow" style="position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); max-width: 80%; text-align: center;">
                                                    <h5 class="f-w-600 f-s-24 mb-3 text-dark">{{ $carousel->content_title ?? $carousel->title }}</h5>
                                                    @if ($carousel->content_description ?? $carousel->description)
                                                        <p class="mb-4 f-s-16 text-primary">{{ $carousel->content_description ?? $carousel->description }}</p>
                                                    @endif
                                                    @if ($carousel->content_url ?? $carousel->link_url)
                                                        <a href="{{ $carousel->content_url ?? $carousel->link_url }}" class="btn btn-primary btn-lg">
                                                            <i class="ph-duotone ph-arrow-right f-s-16 me-2"></i>
                                                            {{ $carousel->link_text ?? 'Visualizza' }}
                                                        </a>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                @if ($carousels && $carousels->count() > 1)
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

        <!-- Events Section -->
        @if ($recentEvents && $recentEvents->count() > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <a href="{{ route('events.index') }}" class="text-decoration-none text-white d-flex align-items-center">
                                    <i class="ph-duotone ph-calendar f-s-16 me-2"></i>
                                    {{ __('home.events_section') }}
                                    <i class="ph-duotone ph-arrow-right f-s-14 ms-2"></i>
                                </a>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach ($recentEvents->take(4) as $event)
                                    <div class="col-lg-3 col-md-6 mb-3">
                                        <div class="card h-100">
                                            @if ($event->image_url)
                                                <img src="{{ $event->image_url }}" class="card-img-top" alt="{{ $event->title }}" style="height: 200px; object-fit: cover;">
                                            @else
                                                <div class="card-img-top bg-light-primary d-flex align-items-center justify-content-center" style="height: 200px;">
                                                    <i class="ph-duotone ph-calendar f-s-48 text-primary"></i>
                                                </div>
                                            @endif
                                            <div class="card-body d-flex flex-column">
                                                <h6 class="card-title f-w-600">{{ $event->title }}</h6>
                                                <p class="card-text text-muted f-s-14">
                                                    <i class="ph-duotone ph-calendar f-s-12 me-1"></i>
                                                    {{ $event->start_datetime->format('d/m/Y H:i') }}
                                                </p>
                                                @if ($event->venue)
                                                    <p class="card-text text-muted f-s-14">
                                                        <i class="ph-duotone ph-map-pin f-s-12 me-1"></i>
                                                        {{ $event->venue }}
                                                    </p>
                                                @endif
                                                <div class="mt-auto">
                                                    <a href="{{ route('events.show', $event->id) }}" class="btn btn-primary btn-sm w-100">
                                                        {{ __('common.view') }}
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

        <!-- Videos Section -->
        @if ($recentVideos && $recentVideos->count() > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header bg-gradient-success text-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <a href="{{ route('videos.index') }}" class="text-decoration-none text-white d-flex align-items-center">
                                    <i class="ph-duotone ph-video-camera f-s-16 me-2"></i>
                                    {{ __('home.videos_section') }}
                                    <i class="ph-duotone ph-arrow-right f-s-14 ms-2"></i>
                                </a>
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                @foreach ($recentVideos->take(6) as $video)
                                    <div class="col-lg-2 col-md-4 col-6 mb-3">
                                        <div class="card h-100">
                                            @if ($video->thumbnail_path)
                                                <div class="position-relative" style="cursor: pointer;" onclick="openVideoModal({{ $video->id }})">
                                                    <img src="{{ $video->thumbnail_url }}" class="card-img-top" alt="{{ $video->title }}" style="height: 120px; object-fit: cover;">
                                                    <div class="position-absolute top-50 start-50 translate-middle">
                                                        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; box-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                                                            <i class="ph-duotone ph-play f-s-16 text-primary"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="card-img-top bg-light-success d-flex align-items-center justify-content-center" style="height: 120px;">
                                                    <i class="ph-duotone ph-video-camera f-s-32 text-success"></i>
                                                </div>
                                            @endif
                                            <div class="card-body d-flex flex-column p-2">
                                                <h6 class="card-title f-w-600 f-s-12 text-truncate">{{ $video->title }}</h6>
                                                <p class="card-text text-muted f-s-10">
                                                    <i class="ph-duotone ph-user f-s-10 me-1"></i>
                                                    {{ $video->user->getDisplayName() }}
                                                </p>
                                                <div class="d-flex justify-content-between align-items-center mt-auto">
                                                    <small class="text-muted f-s-10">
                                                        <i class="ph-duotone ph-eye f-s-10 me-1"></i>
                                                        {{ $video->views_count ?? 0 }}
                                                    </small>
                                                    <div class="d-flex gap-1">
                                                        <small class="text-muted f-s-10">
                                                            <i class="ph-duotone ph-heart f-s-10 me-1"></i>
                                                            {{ $video->likes_count ?? 0 }}
                                                        </small>
                                                        <small class="text-muted f-s-10">
                                                            <i class="ph-duotone ph-chat-circle f-s-10 me-1"></i>
                                                            {{ $video->comments_count ?? 0 }}
                                                        </small>
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

        <!-- Statistics Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3 col-6 mb-3">
                                <div class="card">
                                    <div class="card-body text-center py-3">
                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                            <div class="rounded-circle bg-light-primary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                <i class="ph-duotone ph-video-camera f-s-20 text-primary"></i>
                                            </div>
                                        </div>
                                        <h4 class="mb-1 text-primary f-s-18">{{ number_format($stats['total_videos']) }}</h4>
                                        <p class="text-muted mb-0 f-s-12">{{ __('home.stats.total_videos') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="card">
                                    <div class="card-body text-center py-3">
                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                            <div class="rounded-circle bg-light-success d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                <i class="ph-duotone ph-calendar f-s-20 text-success"></i>
                                            </div>
                                        </div>
                                        <h4 class="mb-1 text-success f-s-18">{{ number_format($stats['total_events']) }}</h4>
                                        <p class="text-muted mb-0 f-s-12">{{ __('home.stats.total_events') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="card">
                                    <div class="card-body text-center py-3">
                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                            <div class="rounded-circle bg-light-info d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                <i class="ph-duotone ph-users f-s-20 text-info"></i>
                                            </div>
                                        </div>
                                        <h4 class="mb-1 text-info f-s-18">{{ number_format($stats['total_users']) }}</h4>
                                        <p class="text-muted mb-0 f-s-12">{{ __('home.stats.total_users') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="card">
                                    <div class="card-body text-center py-3">
                                        <div class="d-flex align-items-center justify-content-center mb-2">
                                            <div class="rounded-circle bg-light-warning d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                <i class="ph-duotone ph-eye f-s-20 text-warning"></i>
                                            </div>
                                        </div>
                                        <h4 class="mb-1 text-warning f-s-18">{{ number_format($stats['total_views']) }}</h4>
                                        <p class="text-muted mb-0 f-s-12">{{ __('home.stats.total_views') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Poetry and Articles Section -->
        <div class="row">
            <!-- Poetry Section (Left) -->
            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card">
                    <div class="card-header bg-gradient-info text-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <a href="{{ route('poems.index') }}" class="text-decoration-none text-white d-flex align-items-center">
                                <i class="ph-duotone ph-book-open f-s-16 me-2"></i>
                                {{ __('home.poetry_section') }}
                                <i class="ph-duotone ph-arrow-right f-s-14 ms-2"></i>
                            </a>
                        </h5>
                        <div class="d-flex align-items-center justify-content-center">
                            <span class="text-white f-s-12 me-2">{{ __('common.new') }}</span>
                            <div class="form-check form-switch mx-2">
                                <input class="form-check-input" type="checkbox" 
                                       wire:click="togglePoetryContent('{{ $poetryContentType === 'new' ? 'popular' : 'new' }}')"
                                       {{ $poetryContentType === 'popular' ? 'checked' : '' }}>
                            </div>
                            <span class="text-white f-s-12 ms-2">{{ __('common.popular') }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($poetryContentType === 'new')
                            @foreach ($recentPoems ?? [] as $poem)
                                <div class="card mb-3 border-info">
                                    <div class="card-body pa-15">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="position-relative">
                                                    <div class="rounded overflow-hidden" style="width: 60px; height: 60px;">
                                                        @if($poem->thumbnail_url)
                                                            <img src="{{ $poem->thumbnail_url }}" alt="{{ $poem->title }}" class="w-100 h-100" style="object-fit: cover;">
                                                        @else
                                                            <div class="w-100 h-100 bg-light-info d-flex align-items-center justify-content-center">
                                                                <i class="ph-duotone ph-book-open f-s-24 text-info"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="card-title f-w-600 mb-1">
                                                    <a href="{{ route('poems.show', $poem->slug) }}" class="text-decoration-none text-dark">
                                                        {{ $poem->title }}
                                                    </a>
                                                </h6>
                                                <p class="text-muted f-s-12 mb-2">
                                                    <i class="ph-duotone ph-user f-s-10 me-1"></i>
                                                    {{ $poem->user->getDisplayName() }}
                                                </p>
                                                <div class="d-flex align-items-center gap-2">
                                                    <small class="text-muted f-s-11">
                                                        <i class="ph-duotone ph-eye f-s-10 me-1"></i>
                                                        {{ $poem->views_count ?? 0 }}
                                                    </small>
                                                    <small class="text-muted f-s-11">
                                                        <i class="ph-duotone ph-clock f-s-10 me-1"></i>{{ $poem->created_at->diffForHumans() }}
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <a href="{{ route('poems.show', $poem->slug) }}" class="btn btn-info btn-sm">
                                                    {{ __('common.view') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            @foreach ($popularPoems ?? [] as $poem)
                                <div class="card mb-3 border-info">
                                    <div class="card-body pa-15">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="position-relative">
                                                    <div class="rounded overflow-hidden" style="width: 60px; height: 60px;">
                                                        @if($poem->thumbnail_url)
                                                            <img src="{{ $poem->thumbnail_url }}" alt="{{ $poem->title }}" class="w-100 h-100" style="object-fit: cover;">
                                                        @else
                                                            <div class="w-100 h-100 bg-light-info d-flex align-items-center justify-content-center">
                                                                <i class="ph-duotone ph-book-open f-s-24 text-info"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="card-title f-w-600 mb-1">
                                                    <a href="{{ route('poems.show', $poem->slug) }}" class="text-decoration-none text-dark">
                                                        {{ $poem->title }}
                                                    </a>
                                                </h6>
                                                <p class="text-muted f-s-12 mb-2">
                                                    <i class="ph-duotone ph-user f-s-10 me-1"></i>
                                                    {{ $poem->user->getDisplayName() }}
                                                </p>
                                                <div class="d-flex align-items-center gap-2">
                                                    <small class="text-muted f-s-11">
                                                        <i class="ph-duotone ph-eye f-s-10 me-1"></i>
                                                        {{ $poem->views_count ?? 0 }}
                                                    </small>
                                                    <small class="text-muted f-s-11">
                                                        <i class="ph-duotone ph-clock f-s-10 me-1"></i>{{ $poem->created_at->diffForHumans() }}
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <a href="{{ route('poems.show', $poem->slug) }}" class="btn btn-info btn-sm">
                                                    {{ __('common.view') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <!-- Articles Section (Right) -->
            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card">
                    <div class="card-header bg-gradient-warning text-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <a href="{{ route('articles.index') }}" class="text-decoration-none text-white d-flex align-items-center">
                                <i class="ph-duotone ph-newspaper f-s-16 me-2"></i>
                                {{ __('home.articles_section') }}
                                <i class="ph-duotone ph-arrow-right f-s-14 ms-2"></i>
                            </a>
                        </h5>
                        <div class="d-flex align-items-center justify-content-center">
                            <span class="text-white f-s-12 me-2">{{ __('common.new') }}</span>
                            <div class="form-check form-switch mx-2">
                                <input class="form-check-input" type="checkbox" 
                                       wire:click="toggleArticlesContent('{{ $articlesContentType === 'new' ? 'popular' : 'new' }}')"
                                       {{ $articlesContentType === 'popular' ? 'checked' : '' }}>
                            </div>
                            <span class="text-white f-s-12 ms-2">{{ __('common.popular') }}</span>
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($articlesContentType === 'new')
                            @foreach ($recentArticles ?? [] as $article)
                                <div class="card mb-3 border-warning">
                                    <div class="card-body pa-15">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="position-relative">
                                                    <div class="rounded overflow-hidden" style="width: 60px; height: 60px;">
                                                        @if($article->featured_image_url)
                                                            <img src="{{ $article->featured_image_url }}" alt="{{ $article->title }}" class="w-100 h-100" style="object-fit: cover;">
                                                        @else
                                                            <div class="w-100 h-100 bg-light-warning d-flex align-items-center justify-content-center">
                                                                <i class="ph-duotone ph-newspaper f-s-24 text-warning"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="card-title f-w-600 mb-1">
                                                    <a href="{{ route('articles.show', $article->slug) }}" class="text-decoration-none text-dark">
                                                        {{ $article->title }}
                                                    </a>
                                                </h6>
                                                <p class="text-muted f-s-12 mb-2">
                                                    <i class="ph-duotone ph-user f-s-10 me-1"></i>
                                                    {{ $article->user->getDisplayName() }}
                                                </p>
                                                @if($article->category)
                                                    <span class="badge bg-light-info f-s-10 mb-2">
                                                        @if(is_array($article->category) || is_object($article->category))
                                                            {{ $article->category->name ?? 'Categoria' }}
                                                        @else
                                                            {{ $article->category }}
                                                        @endif
                                                    </span>
                                                @endif
                                                <div class="d-flex align-items-center gap-2">
                                                    <small class="text-muted f-s-11">
                                                        <i class="ph-duotone ph-eye f-s-10 me-1"></i>
                                                        {{ $article->views_count ?? 0 }}
                                                    </small>
                                                    <small class="text-muted f-s-11">
                                                        <i class="ph-duotone ph-clock f-s-10 me-1"></i>{{ $article->created_at->diffForHumans() }}
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-warning btn-sm">
                                                    {{ __('common.view') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            @foreach ($popularArticles ?? [] as $article)
                                <div class="card mb-3 border-warning">
                                    <div class="card-body pa-15">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <div class="position-relative">
                                                    <div class="rounded overflow-hidden" style="width: 60px; height: 60px;">
                                                        @if($article->featured_image_url)
                                                            <img src="{{ $article->featured_image_url }}" alt="{{ $article->title }}" class="w-100 h-100" style="object-fit: cover;">
                                                        @else
                                                            <div class="w-100 h-100 bg-light-warning d-flex align-items-center justify-content-center">
                                                                <i class="ph-duotone ph-newspaper f-s-24 text-warning"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="card-title f-w-600 mb-1">
                                                    <a href="{{ route('articles.show', $article->slug) }}" class="text-decoration-none text-dark">
                                                        {{ $article->title }}
                                                    </a>
                                                </h6>
                                                <p class="text-muted f-s-12 mb-2">
                                                    <i class="ph-duotone ph-user f-s-10 me-1"></i>
                                                    {{ $article->user->getDisplayName() }}
                                                </p>
                                                @if($article->category)
                                                    <span class="badge bg-light-info f-s-10 mb-2">
                                                        @if(is_array($article->category) || is_object($article->category))
                                                            {{ $article->category->name ?? 'Categoria' }}
                                                        @else
                                                            {{ $article->category }}
                                                        @endif
                                                    </span>
                                                @endif
                                                <div class="d-flex align-items-center gap-2">
                                                    <small class="text-muted f-s-11">
                                                        <i class="ph-duotone ph-eye f-s-10 me-1"></i>
                                                        {{ $article->views_count ?? 0 }}
                                                    </small>
                                                    <small class="text-muted f-s-11">
                                                        <i class="ph-duotone ph-clock f-s-10 me-1"></i>{{ $article->created_at->diffForHumans() }}
                                                    </small>
                                                </div>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-warning btn-sm">
                                                    {{ __('common.view') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>