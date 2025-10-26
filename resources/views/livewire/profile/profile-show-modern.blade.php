<div>
<div class="container-fluid px-3 px-lg-4">
    <!-- Hero Banner with Avatar -->
    <div class="card mb-4 overflow-hidden border-0 shadow-sm">
        <div class="position-relative bg-primary" style="height: 280px;">
            @if($user->banner_image)
            <img src="{{ $user->getBannerImageUrlAttribute() }}" 
                 alt="Banner"
                 class="w-100 h-100 position-absolute top-0 start-0"
                 style="object-fit: cover; opacity: 0.9;">
            @endif
            
            <!-- Avatar -->
            <div class="position-absolute bottom-0 start-50 translate-middle-x" style="margin-bottom: -50px;">
                <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($user) }}"
                     alt="{{ $user->name }}"
                     class="rounded-circle border border-4 border-white shadow"
                     style="width: 120px; height: 120px; object-fit: cover;">
                @if($user->verified_at)
                <span class="position-absolute bottom-0 end-0 bg-white rounded-circle p-1 shadow-sm">
                    <i class="ph-fill ph-check-circle text-success" style="font-size: 1.5rem;"></i>
                </span>
                @endif
            </div>

            <!-- Stats Pills -->
            <div class="position-absolute top-0 end-0 m-3 d-flex gap-2">
                <span class="badge bg-white text-dark px-3 py-2 shadow-sm">
                    <i class="ph ph-medal text-warning me-1"></i>
                    <strong>{{ $badgesCount }}</strong>
                </span>
                <span class="badge bg-white text-dark px-3 py-2 shadow-sm">
                    <i class="ph ph-star text-success me-1"></i>
                    <strong>{{ $totalPoints }}</strong>
                </span>
                <span class="badge bg-white text-dark px-3 py-2 shadow-sm">
                    <i class="ph ph-ranking text-primary me-1"></i>
                    <strong>Lv{{ $level }}</strong>
                </span>
            </div>
        </div>
        
        <!-- User Info -->
        <div class="card-body text-center pt-5 pb-3">
            <h3 class="fw-bold mb-1">{{ $user->getDisplayName() }}</h3>
            @if($user->bio)
            <p class="text-muted f-s-14">{{ Str::limit($user->bio, 150) }}</p>
            @endif
        </div>
    </div>

    <!-- Navigation Tabs -->
    <ul class="nav nav-pills nav-fill mb-4 gap-2" role="tablist">
        <li class="nav-item">
            <button class="nav-link {{ $activeTab === 'about' ? 'active' : '' }}" 
                    wire:click="setActiveTab('about')">
                <i class="ph ph-user-circle me-1"></i>
                <span class="d-none d-md-inline">Profilo</span>
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link {{ $activeTab === 'badges' ? 'active' : '' }}" 
                    wire:click="setActiveTab('badges')">
                <i class="ph ph-trophy me-1"></i>
                <span class="d-none d-md-inline">Badge</span>
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link {{ $activeTab === 'poems' ? 'active' : '' }}" 
                    wire:click="setActiveTab('poems')">
                <i class="ph ph-book-open me-1"></i>
                <span class="d-none d-md-inline">Poesie</span>
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link {{ $activeTab === 'articles' ? 'active' : '' }}" 
                    wire:click="setActiveTab('articles')">
                <i class="ph ph-newspaper me-1"></i>
                <span class="d-none d-md-inline">Articoli</span>
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link {{ $activeTab === 'media' ? 'active' : '' }}" 
                    wire:click="setActiveTab('media')">
                <i class="ph ph-play-circle me-1"></i>
                <span class="d-none d-md-inline">Media</span>
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link {{ $activeTab === 'events' ? 'active' : '' }}" 
                    wire:click="setActiveTab('events')">
                <i class="ph ph-calendar me-1"></i>
                <span class="d-none d-md-inline">Eventi</span>
            </button>
        </li>
        <li class="nav-item">
            <button class="nav-link {{ $activeTab === 'activities' ? 'active' : '' }}" 
                    wire:click="setActiveTab('activities')">
                <i class="ph ph-activity me-1"></i>
                <span class="d-none d-md-inline">Attività</span>
            </button>
        </li>
        @if($isOwnProfile)
        <li class="nav-item">
            <button class="nav-link {{ $activeTab === 'settings' ? 'active' : '' }}" 
                    wire:click="setActiveTab('settings')">
                <i class="ph ph-gear me-1"></i>
                <span class="d-none d-md-inline">Impostazioni</span>
            </button>
        </li>
        @endif
    </ul>

    <!-- Content Area -->
    @if($activeTab === 'about')
        <!-- About Section -->
        <div class="card mb-4">
            <div class="card-header bg-light border-bottom border-3 border-primary">
                <h5 class="mb-0 fw-bold">
                    <i class="ph ph-info me-2 text-primary"></i>
                    Chi Sono
                </h5>
            </div>
            <div class="card-body">
                @if($user->bio)
                    <p>{{ $user->bio }}</p>
                @else
                    <p class="text-muted fst-italic">Nessuna biografia disponibile</p>
                @endif
                
                @if($user->city || $user->country)
                <div class="d-flex align-items-center gap-2 text-muted">
                    <i class="ph ph-map-pin text-primary"></i>
                    <span>{{ $user->city }}{{ $user->city && $user->country ? ', ' : '' }}{{ $user->country }}</span>
                </div>
                @endif
            </div>
        </div>

    @elseif($activeTab === 'badges')
        <!-- Badges Section -->
        <div class="card">
            <div class="card-header bg-light border-bottom border-3 border-primary d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    <i class="ph ph-medal-military me-2 text-primary"></i>
                    Badge in Evidenza
                </h5>
                <a href="{{ route('profile.my-badges') }}" class="btn btn-sm btn-primary">
                    <i class="ph ph-trophy me-1"></i>
                    Trophy Case
                </a>
            </div>
            <div class="card-body">
                @livewire('profile.badge-display-stack-cards', ['user' => $user])
            </div>
        </div>

    @elseif($activeTab === 'poems')
        <!-- Poems Grid -->
        <div class="row g-3">
            @forelse($poems as $poem)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 hover-effect">
                    @if($poem->thumbnail_url)
                    <img src="{{ $poem->thumbnail_url }}" class="card-img-top" alt="{{ $poem->title }}" style="height: 200px; object-fit: cover;">
                    @endif
                    <div class="card-body">
                        <h6 class="card-title fw-bold">{{ Str::limit($poem->title, 50) }}</h6>
                        <p class="card-text text-muted f-s-14">{{ Str::limit($poem->content, 100) }}</p>
                        <div class="d-flex gap-3 text-muted f-s-13">
                            <span><i class="ph ph-eye me-1"></i>{{ $poem->view_count }}</span>
                            <span><i class="ph ph-heart me-1"></i>{{ $poem->like_count }}</span>
                            <span><i class="ph ph-chat me-1"></i>{{ $poem->comment_count }}</span>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0">
                        <a href="{{ route('poems.show', $poem->slug) }}" class="btn btn-sm btn-primary w-100">
                            <i class="ph ph-eye me-1"></i>
                            Leggi
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="ph ph-book-open text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2">Nessuna poesia ancora</p>
                </div>
            </div>
            @endforelse
        </div>
        <div class="mt-3">
            {{ $poems->links() }}
        </div>

    @elseif($activeTab === 'articles')
        <!-- Articles Grid -->
        <div class="row g-3">
            @forelse($articles as $article)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 hover-effect">
                    @if($article->featured_image)
                    <img src="{{ Storage::url($article->featured_image) }}" class="card-img-top" alt="{{ $article->title }}" style="height: 200px; object-fit: cover;">
                    @endif
                    <div class="card-body">
                        <h6 class="card-title fw-bold">{{ Str::limit($article->title, 50) }}</h6>
                        <p class="card-text text-muted f-s-14">{{ Str::limit($article->excerpt ?? $article->content, 100) }}</p>
                        <div class="d-flex gap-3 text-muted f-s-13">
                            <span><i class="ph ph-eye me-1"></i>{{ $article->views_count }}</span>
                            <span><i class="ph ph-heart me-1"></i>{{ $article->likes_count }}</span>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0">
                        <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-sm btn-primary w-100">
                            <i class="ph ph-article me-1"></i>
                            Leggi
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="ph ph-newspaper text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2">Nessun articolo ancora</p>
                </div>
            </div>
            @endforelse
        </div>
        <div class="mt-3">
            {{ $articles->links() }}
        </div>

    @elseif($activeTab === 'media')
        <!-- Media Grid -->
        <div class="row g-3">
            @forelse($photos->take(12) as $photo)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card hover-effect">
                    <img src="{{ $photo->image_url }}" class="card-img-top" alt="{{ $photo->title }}" style="height: 200px; object-fit: cover;">
                    <div class="card-body p-2">
                        <small class="text-muted">
                            <i class="ph ph-heart me-1"></i>{{ $photo->like_count }}
                        </small>
                    </div>
                </div>
            </div>
            @empty
            @endforelse
            
            @forelse($videos->take(12) as $video)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card hover-effect">
                    <div class="position-relative" style="height: 200px;">
                        <video src="{{ $video->video_url }}" class="w-100 h-100" style="object-fit: cover;"></video>
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <i class="ph ph-play-circle text-white" style="font-size: 3rem; opacity: 0.8;"></i>
                        </div>
                    </div>
                    <div class="card-body p-2">
                        <small class="text-muted">
                            <i class="ph ph-heart me-1"></i>{{ $video->like_count }}
                        </small>
                    </div>
                </div>
            </div>
            @empty
            @endforelse

            @if($photos->count() === 0 && $videos->count() === 0)
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="ph ph-images text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2">Nessun media ancora</p>
                </div>
            </div>
            @endif
        </div>

    @elseif($activeTab === 'events')
        <!-- Events Grid -->
        <div class="row g-4">
            @forelse($events as $event)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 hover-effect border-0 shadow-sm">
                    <div class="position-relative">
                        <img src="{{ \App\Helpers\EventImageHelper::getEventImageUrl($event) }}" 
                             class="card-img-top" 
                             alt="{{ $event->title }}"
                             style="height: 200px; object-fit: cover;">
                        <span class="position-absolute top-0 start-0 m-3 badge bg-primary shadow">
                            <div class="fw-bold" style="font-size: 1.2rem;">{{ $event->start_datetime->format('d') }}</div>
                            <div class="text-uppercase" style="font-size: 0.7rem;">{{ $event->start_datetime->format('M') }}</div>
                        </span>
                    </div>
                    <div class="card-body">
                        <h6 class="card-title fw-bold mb-2">{{ $event->title }}</h6>
                        @if($event->description)
                        <p class="card-text text-muted f-s-13 mb-3">{{ Str::limit($event->description, 100) }}</p>
                        @endif
                        
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="p-2 bg-light rounded-3">
                                    <i class="ph ph-calendar-dots text-primary me-1"></i>
                                    <small>{{ $event->start_datetime->format('d/m/Y') }}</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 bg-light rounded-3">
                                    <i class="ph ph-clock text-primary me-1"></i>
                                    <small>{{ $event->start_datetime->format('H:i') }}</small>
                                </div>
                            </div>
                            @if($event->city)
                            <div class="col-12">
                                <div class="p-2 bg-light rounded-3">
                                    <i class="ph ph-map-pin text-primary me-1"></i>
                                    <small>{{ $event->city }}</small>
                                </div>
                            </div>
                            @endif
                            @if($event->venue)
                            <div class="col-12">
                                <div class="p-2 bg-light rounded-3">
                                    <i class="ph ph-buildings text-primary me-1"></i>
                                    <small>{{ $event->venue->name }}</small>
                                </div>
                            </div>
                            @endif
                            @if(isset($event->price))
                            <div class="col-6">
                                <div class="p-2 bg-light rounded-3">
                                    <i class="ph ph-ticket text-primary me-1"></i>
                                    <small>{{ $event->price > 0 ? '€ '.$event->price : 'Gratis' }}</small>
                                </div>
                            </div>
                            @endif
                            @if($event->category)
                            <div class="col-6">
                                <div class="p-2 bg-light rounded-3">
                                    <i class="ph ph-tag text-primary me-1"></i>
                                    <small>{{ $event->category }}</small>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0">
                        <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-primary w-100">
                            Dettagli Evento
                            <i class="ph ph-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="ph ph-calendar-x text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2">Nessun evento organizzato</p>
                </div>
            </div>
            @endforelse
        </div>
        <div class="mt-4">
            {{ $events->links() }}
        </div>

    @elseif($activeTab === 'activities')
        <!-- Activities Timeline -->
        <div class="row g-3">
            @forelse($activities as $activity)
            <div class="col-12">
                <div class="card hover-effect">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="flex-shrink-0">
                            <div class="rounded-circle d-flex align-items-center justify-content-center 
                                 @if($activity->type === 'poem_created') bg-primary
                                 @elseif($activity->type === 'article_created') bg-info
                                 @elseif($activity->type === 'event_organized') bg-warning
                                 @elseif($activity->type === 'badge_earned') bg-warning
                                 @elseif($activity->type === 'video_uploaded') bg-danger
                                 @elseif($activity->type === 'photo_uploaded') bg-info
                                 @elseif($activity->type === 'comment_added') bg-success
                                 @elseif($activity->type === 'like_given') bg-danger
                                 @else bg-secondary
                                 @endif"
                                 style="width: 50px; height: 50px;">
                                <i class="ph ph-{{ $this->getActivityIcon($activity->type) }} text-white f-s-20"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold">{{ $activity->description }}</h6>
                            <small class="text-muted">
                                <i class="ph ph-clock me-1"></i>
                                {{ $activity->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="ph ph-activity text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2">Nessuna attività recente</p>
                </div>
            </div>
            @endforelse
        </div>
        <div class="mt-4">
            {{ $activities->links() }}
        </div>

    @elseif($activeTab === 'settings' && $isOwnProfile)
        <!-- Settings Section -->
        <div class="row g-3">
            <div class="col-12 col-md-6 col-lg-4">
                <a href="{{ route('profile.edit') }}" class="text-decoration-none">
                    <div class="card hover-effect h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-light-primary p-3 rounded-3">
                                <i class="ph ph-user-circle text-primary f-s-24"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">Modifica Profilo</h6>
                                <p class="text-muted f-s-12 mb-0">Aggiorna le tue informazioni</p>
                            </div>
                            <i class="ph ph-caret-right text-muted"></i>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <a href="{{ route('profile.my-badges') }}" class="text-decoration-none">
                    <div class="card hover-effect h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-light-warning p-3 rounded-3">
                                <i class="ph ph-medal-military text-warning f-s-24"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">Gestione Badge</h6>
                                <p class="text-muted f-s-12 mb-0">Badge e trophy</p>
                            </div>
                            <i class="ph ph-caret-right text-muted"></i>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <a href="{{ route('profile.languages.index') }}" class="text-decoration-none">
                    <div class="card hover-effect h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-light-info p-3 rounded-3">
                                <i class="ph ph-globe text-info f-s-24"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">Lingue</h6>
                                <p class="text-muted f-s-12 mb-0">Gestisci le lingue</p>
                            </div>
                            <i class="ph ph-caret-right text-muted"></i>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <a href="{{ route('profile.media') }}" class="text-decoration-none">
                    <div class="card hover-effect h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-light-success p-3 rounded-3">
                                <i class="ph ph-video-camera text-success f-s-24"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">Media</h6>
                                <p class="text-muted f-s-12 mb-0">Gestisci foto e video</p>
                            </div>
                            <i class="ph ph-caret-right text-muted"></i>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <a href="{{ route('profile.activity') }}" class="text-decoration-none">
                    <div class="card hover-effect h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-light-secondary p-3 rounded-3">
                                <i class="ph ph-lightning text-secondary f-s-24"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">Tutte le Attività</h6>
                                <p class="text-muted f-s-12 mb-0">Visualizza tutto</p>
                            </div>
                            <i class="ph ph-caret-right text-muted"></i>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <a href="{{ route('articles.create') }}" class="text-decoration-none">
                    <div class="card hover-effect h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-light-info p-3 rounded-3">
                                <i class="ph ph-article text-info f-s-24"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">Crea Articolo</h6>
                                <p class="text-muted f-s-12 mb-0">Scrivi un articolo</p>
                            </div>
                            <i class="ph ph-caret-right text-muted"></i>
                        </div>
                    </div>
                </a>
            </div>

            @if($user->hasRole('poet'))
            <div class="col-12 col-md-6 col-lg-4">
                <a href="{{ route('poems.create') }}" class="text-decoration-none">
                    <div class="card hover-effect h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-light-primary p-3 rounded-3">
                                <i class="ph ph-pen-nib text-primary f-s-24"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">Crea Poesia</h6>
                                <p class="text-muted f-s-12 mb-0">Scrivi una poesia</p>
                            </div>
                            <i class="ph ph-caret-right text-muted"></i>
                        </div>
                    </div>
                </a>
            </div>
            @endif

            @if($user->hasRole('organizer'))
            <div class="col-12 col-md-6 col-lg-4">
                <a href="{{ route('events.create') }}" class="text-decoration-none">
                    <div class="card hover-effect h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-light-warning p-3 rounded-3">
                                <i class="ph ph-calendar-plus text-warning f-s-24"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">Crea Evento</h6>
                                <p class="text-muted f-s-12 mb-0">Organizza evento</p>
                            </div>
                            <i class="ph ph-caret-right text-muted"></i>
                        </div>
                    </div>
                </a>
            </div>
            @endif

            @if($user->hasRole('venue'))
            <div class="col-12 col-md-6 col-lg-4">
                <a href="{{ route('venues.create') }}" class="text-decoration-none">
                    <div class="card hover-effect h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-light-secondary p-3 rounded-3">
                                <i class="ph ph-buildings text-secondary f-s-24"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">Crea Venue</h6>
                                <p class="text-muted f-s-12 mb-0">Aggiungi locale</p>
                            </div>
                            <i class="ph ph-caret-right text-muted"></i>
                        </div>
                    </div>
                </a>
            </div>
            @endif

            <div class="col-12 col-md-6 col-lg-4">
                <a href="{{ route('videos.upload') }}" class="text-decoration-none">
                    <div class="card hover-effect h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-light-danger p-3 rounded-3">
                                <i class="ph ph-upload text-danger f-s-24"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">Carica Video</h6>
                                <p class="text-muted f-s-12 mb-0">Upload video</p>
                            </div>
                            <i class="ph ph-caret-right text-muted"></i>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <a href="{{ route('photos.create') }}" class="text-decoration-none">
                    <div class="card hover-effect h-100">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="bg-light-info p-3 rounded-3">
                                <i class="ph ph-image-square text-info f-s-24"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">Carica Foto</h6>
                                <p class="text-muted f-s-12 mb-0">Upload foto</p>
                            </div>
                            <i class="ph ph-caret-right text-muted"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    @endif
</div>

<style>
/* Minimal CSS - Only what's strictly necessary for layout */
.hero-banner .position-absolute.bottom-0 {
    margin-bottom: -50px !important;
}
.nav-pills .nav-link.active {
    background-color: rgba(var(--primary), 1) !important;
}
</style>
</div>
</div>
