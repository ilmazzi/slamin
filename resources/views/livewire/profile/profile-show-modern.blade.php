<div>
<div class="modern-profile-wrapper">
    <!-- Hero Banner with Avatar -->
    <div class="profile-hero mb-4">
        <div class="hero-banner">
            @if($user->banner_image)
            <img src="{{ $user->getBannerImageUrlAttribute() }}" 
                 alt="Banner"
                 class="banner-image">
            @endif
            <div class="banner-overlay"></div>
            
            <!-- Avatar -->
            <div class="avatar-container">
                <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($user) }}"
                     alt="{{ $user->name }}"
                     class="avatar-image">
                @if($user->verified_at)
                <div class="verified-badge">
                    <i class="ph-fill ph-check-circle"></i>
                </div>
                @endif
            </div>

            <!-- User Info -->
            <div class="user-info-overlay">
                <h2 class="user-name">{{ $user->getDisplayName() }}</h2>
                @if($user->bio)
                <p class="user-bio">{{ Str::limit($user->bio, 100) }}</p>
                @endif
            </div>

            <!-- Stats Pills -->
            <div class="stats-pills">
                <div class="stat-pill">
                    <i class="ph ph-medal"></i>
                    <span class="stat-value">{{ $badgesCount }}</span>
                    <span class="stat-label">Badge</span>
                </div>
                <div class="stat-pill">
                    <i class="ph ph-star"></i>
                    <span class="stat-value">{{ $totalPoints }}</span>
                    <span class="stat-label">Punti</span>
                </div>
                <div class="stat-pill">
                    <i class="ph ph-ranking"></i>
                    <span class="stat-value">{{ $level }}</span>
                    <span class="stat-label">Livello</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs - Hidden on Mobile -->
    <div class="profile-nav-tabs mb-4 d-none d-md-flex">
        <button class="nav-tab {{ $activeTab === 'about' ? 'active' : '' }}" wire:click="setActiveTab('about')">
            <i class="ph ph-user-circle"></i>
            <span>Profilo</span>
        </button>
        <button class="nav-tab {{ $activeTab === 'badges' ? 'active' : '' }}" wire:click="setActiveTab('badges')">
            <i class="ph ph-trophy"></i>
            <span>Badge</span>
        </button>
        <button class="nav-tab {{ $activeTab === 'poems' ? 'active' : '' }}" wire:click="setActiveTab('poems')">
            <i class="ph ph-book-open"></i>
            <span>Poesie</span>
        </button>
        <button class="nav-tab {{ $activeTab === 'articles' ? 'active' : '' }}" wire:click="setActiveTab('articles')">
            <i class="ph ph-newspaper"></i>
            <span>Articoli</span>
        </button>
        <button class="nav-tab {{ $activeTab === 'media' ? 'active' : '' }}" wire:click="setActiveTab('media')">
            <i class="ph ph-play-circle"></i>
            <span>Media</span>
        </button>
        <button class="nav-tab {{ $activeTab === 'events' ? 'active' : '' }}" wire:click="setActiveTab('events')">
            <i class="ph ph-calendar"></i>
            <span>Eventi</span>
        </button>
        <button class="nav-tab {{ $activeTab === 'activities' ? 'active' : '' }}" wire:click="setActiveTab('activities')">
            <i class="ph ph-activity"></i>
            <span>Attività</span>
        </button>
        @if($isOwnProfile)
        <button class="nav-tab {{ $activeTab === 'settings' ? 'active' : '' }}" wire:click="setActiveTab('settings')">
            <i class="ph ph-gear"></i>
            <span>Impostazioni</span>
        </button>
        @endif
    </div>

    <!-- Content Area -->
    <div class="profile-content">
        <!-- About Section -->
        <div class="about-section mb-4 d-block {{ $activeTab !== 'about' ? 'd-md-none' : '' }}">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom border-3 border-primary">
                    <h5 class="mb-0 fw-bold">
                        <i class="ph ph-info me-2 text-primary"></i>
                        Chi Sono
                    </h5>
                </div>
                <div class="card-body">
                    @if($user->bio)
                        <p class="mb-3">{{ $user->bio }}</p>
                    @else
                        <p class="text-muted fst-italic mb-0">Nessuna biografia disponibile</p>
                    @endif
                    
                    @if($user->city || $user->country)
                    <div class="d-flex align-items-center gap-2 text-muted mt-3">
                        <i class="ph ph-map-pin text-primary"></i>
                        <span>{{ $user->city }}{{ $user->city && $user->country ? ', ' : '' }}{{ $user->country }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Badges Section -->
        <div class="badges-section mb-4 d-block {{ $activeTab !== 'badges' ? 'd-md-none' : '' }}">
            <!-- Badges Section -->
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom border-3 border-primary d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="mb-0 fw-bold">
                        <i class="ph ph-medal-military me-2 text-primary"></i>
                        Badge in Evidenza
                    </h5>
                    <a href="{{ route('profile.my-badges') }}" class="btn btn-sm btn-primary">
                        <i class="ph ph-trophy me-1"></i>
                        Trophy Case
                    </a>
                </div>
                <div class="card-body overflow-hidden">
                    @livewire('profile.badge-display-stack-cards', ['user' => $user])
                </div>
            </div>
        </div>

        <!-- Poems Section -->
        <div class="poems-section mb-4 d-block {{ $activeTab !== 'poems' ? 'd-md-none' : '' }}">
            <!-- Poems Grid -->
            <div class="row g-3">
                @forelse($poems as $poem)
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="card h-100 hover-effect shadow-sm">
                        @if($poem->thumbnail_url)
                        <img src="{{ $poem->thumbnail_url }}" class="card-img-top" alt="{{ $poem->title }}" style="height: 180px; object-fit: cover;">
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
                <div class="col-12 text-center py-5">
                    <i class="ph ph-book-open text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2">Nessuna poesia ancora</p>
                </div>
                @endforelse
            </div>
            <div class="mt-3">{{ $poems->links() }}</div>
        </div>

        <!-- Articles Section -->
        <div class="articles-section mb-4 d-block {{ $activeTab !== 'articles' ? 'd-md-none' : '' }}">
            <!-- Articles Grid -->
            <div class="row g-3">
                @forelse($articles as $article)
                <div class="col-12 col-sm-6 col-lg-4">
                    <div class="card h-100 hover-effect shadow-sm">
                        @if($article->featured_image)
                        <img src="{{ Storage::url($article->featured_image) }}" class="card-img-top" alt="{{ $article->title }}" style="height: 180px; object-fit: cover;">
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
                <div class="col-12 text-center py-5">
                    <i class="ph ph-newspaper text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2">Nessun articolo ancora</p>
                </div>
                @endforelse
            </div>
            <div class="mt-3">{{ $articles->links() }}</div>
        </div>

        <!-- Media Section -->
        <div class="media-section mb-4 d-block {{ $activeTab !== 'media' ? 'd-md-none' : '' }}">
            <!-- Media Grid -->
            <div class="row g-3">
                @forelse($photos->take(12) as $photo)
                <div class="col-6 col-sm-4 col-lg-3">
                    <div class="card hover-effect shadow-sm">
                        <img src="{{ $photo->image_url }}" class="card-img" alt="{{ $photo->title }}" style="height: 180px; object-fit: cover;">
                        <div class="card-img-overlay d-flex align-items-end p-2">
                            <small class="badge bg-dark bg-opacity-75">
                                <i class="ph ph-heart me-1"></i>{{ $photo->like_count }}
                            </small>
                        </div>
                    </div>
                </div>
                @empty
                @endforelse
                
                @forelse($videos->take(12) as $video)
                <div class="col-6 col-sm-4 col-lg-3">
                    <div class="card hover-effect shadow-sm position-relative">
                        <video src="{{ $video->video_url }}" class="card-img" style="height: 180px; object-fit: cover;"></video>
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <i class="ph ph-play-circle text-white" style="font-size: 3rem; opacity: 0.8;"></i>
                        </div>
                        <div class="card-img-overlay d-flex align-items-end p-2">
                            <small class="badge bg-dark bg-opacity-75">
                                <i class="ph ph-heart me-1"></i>{{ $video->like_count }}
                            </small>
                        </div>
                    </div>
                </div>
                @empty
                @endforelse

                @if($photos->count() === 0 && $videos->count() === 0)
                <div class="col-12 text-center py-5">
                    <i class="ph ph-images text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2">Nessun media ancora</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Events Section -->
        <div class="events-section mb-4 d-block {{ $activeTab !== 'events' ? 'd-md-none' : '' }}">
            <!-- Events Grid -->
            <div class="row g-3 g-lg-4">
                @forelse($events as $event)
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm event-card">
                        <div class="position-relative">
                            <img src="{{ \App\Helpers\EventImageHelper::getEventImageUrl($event) }}" 
                                 class="card-img-top event-image" 
                                 alt="{{ $event->title }}">
                            <span class="position-absolute top-0 start-0 m-3 badge bg-primary shadow px-3 py-2">
                                <div class="fw-bold lh-1" style="font-size: 1.3rem;">{{ $event->start_datetime->format('d') }}</div>
                                <div class="text-uppercase mt-1" style="font-size: 0.65rem;">{{ $event->start_datetime->format('M') }}</div>
                            </span>
                        </div>
                        <div class="card-body">
                            <h6 class="card-title fw-bold mb-2">{{ $event->title }}</h6>
                            @if($event->description)
                            <p class="card-text text-muted f-s-13 mb-3">{{ Str::limit($event->description, 100) }}</p>
                            @endif
                            
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <div class="p-2 bg-body-secondary rounded-2 d-flex align-items-center gap-1">
                                        <i class="ph ph-calendar-dots text-primary f-s-16"></i>
                                        <small class="f-s-11">{{ $event->start_datetime->format('d/m/Y') }}</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2 bg-body-secondary rounded-2 d-flex align-items-center gap-1">
                                        <i class="ph ph-clock text-primary f-s-16"></i>
                                        <small class="f-s-11">{{ $event->start_datetime->format('H:i') }}</small>
                                    </div>
                                </div>
                                @if($event->city)
                                <div class="col-12">
                                    <div class="p-2 bg-body-secondary rounded-2 d-flex align-items-center gap-1">
                                        <i class="ph ph-map-pin text-primary f-s-16"></i>
                                        <small class="f-s-11">{{ $event->city }}</small>
                                    </div>
                                </div>
                                @endif
                                @if($event->venue)
                                <div class="col-12">
                                    <div class="p-2 bg-body-secondary rounded-2 d-flex align-items-center gap-1">
                                        <i class="ph ph-buildings text-primary f-s-16"></i>
                                        <small class="f-s-11">{{ Str::limit($event->venue->name, 30) }}</small>
                                    </div>
                                </div>
                                @endif
                                @if(isset($event->price))
                                <div class="col-6">
                                    <div class="p-2 bg-body-secondary rounded-2 d-flex align-items-center gap-1">
                                        <i class="ph ph-ticket text-primary f-s-16"></i>
                                        <small class="f-s-11">{{ $event->price > 0 ? '€'.$event->price : 'Gratis' }}</small>
                                    </div>
                                </div>
                                @endif
                                @if($event->category)
                                <div class="col-6">
                                    <div class="p-2 bg-body-secondary rounded-2 d-flex align-items-center gap-1">
                                        <i class="ph ph-tag text-primary f-s-16"></i>
                                        <small class="f-s-11">{{ $event->category }}</small>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="card-footer bg-white border-top-0 pt-0">
                            <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-primary w-100">
                                Dettagli Evento
                                <i class="ph ph-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <i class="ph ph-calendar-x text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2">Nessun evento organizzato</p>
                </div>
                @endforelse
            </div>
            <div class="mt-4">{{ $events->links() }}</div>
        </div>

        <!-- Activities Section -->
        <div class="activities-section mb-4 d-block {{ $activeTab !== 'activities' ? 'd-md-none' : '' }}">
            <!-- Activities List -->
            <div class="row g-3">
                @forelse($activities as $activity)
                <div class="col-12">
                    <div class="card hover-effect shadow-sm">
                        <div class="card-body d-flex align-items-center gap-3">
                            <div class="flex-shrink-0">
                                <div class="rounded-circle d-flex align-items-center justify-content-center
                                     @if($activity->type === 'poem_created') bg-primary
                                     @elseif($activity->type === 'article_created') bg-info
                                     @elseif($activity->type === 'event_organized') bg-warning
                                     @elseif($activity->type === 'event_participation') bg-success
                                     @elseif($activity->type === 'badge_earned') bg-warning
                                     @elseif($activity->type === 'video_uploaded') bg-danger
                                     @elseif($activity->type === 'photo_uploaded') bg-info
                                     @elseif($activity->type === 'comment_added') bg-success
                                     @elseif($activity->type === 'like_given') bg-danger
                                     @else bg-secondary
                                     @endif"
                                     style="width: 45px; height: 45px;">
                                    <i class="ph ph-{{ $this->getActivityIcon($activity->type) }} text-white f-s-18"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-bold f-s-14">{{ $activity->description }}</h6>
                                <small class="text-muted f-s-12">
                                    <i class="ph ph-clock me-1"></i>
                                    {{ $activity->created_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <i class="ph ph-activity text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2">Nessuna attività recente</p>
                </div>
                @endforelse
            </div>
            <div class="mt-4">{{ $activities->links() }}</div>
        </div>

        @if($isOwnProfile)
        <!-- Settings Section -->
        <div class="settings-section mb-4 d-block {{ $activeTab !== 'settings' ? 'd-md-none' : '' }}">
            <!-- Settings Grid -->
            <div class="row g-3">
                <div class="col-12 col-sm-6 col-lg-4">
                    <a href="{{ route('profile.edit') }}" class="text-decoration-none">
                        <div class="card hover-effect shadow-sm h-100">
                            <div class="card-body d-flex align-items-center gap-3">
                                <div class="bg-light-primary p-3 rounded-3">
                                    <i class="ph ph-user-circle text-primary f-s-24"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1 f-s-14">Modifica Profilo</h6>
                                    <p class="text-muted f-s-11 mb-0">Info personali</p>
                                </div>
                                <i class="ph ph-caret-right text-muted"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-lg-4">
                    <a href="{{ route('profile.my-badges') }}" class="text-decoration-none">
                        <div class="card hover-effect shadow-sm h-100">
                            <div class="card-body d-flex align-items-center gap-3">
                                <div class="bg-light-warning p-3 rounded-3">
                                    <i class="ph ph-medal-military text-warning f-s-24"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1 f-s-14">Badge</h6>
                                    <p class="text-muted f-s-11 mb-0">Gestisci trophy</p>
                                </div>
                                <i class="ph ph-caret-right text-muted"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-lg-4">
                    <a href="{{ route('profile.languages.index') }}" class="text-decoration-none">
                        <div class="card hover-effect shadow-sm h-100">
                            <div class="card-body d-flex align-items-center gap-3">
                                <div class="bg-light-info p-3 rounded-3">
                                    <i class="ph ph-globe text-info f-s-24"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1 f-s-14">Lingue</h6>
                                    <p class="text-muted f-s-11 mb-0">Gestisci lingue</p>
                                </div>
                                <i class="ph ph-caret-right text-muted"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-lg-4">
                    <a href="{{ route('profile.media') }}" class="text-decoration-none">
                        <div class="card hover-effect shadow-sm h-100">
                            <div class="card-body d-flex align-items-center gap-3">
                                <div class="bg-light-success p-3 rounded-3">
                                    <i class="ph ph-video-camera text-success f-s-24"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1 f-s-14">Media</h6>
                                    <p class="text-muted f-s-11 mb-0">Foto e video</p>
                                </div>
                                <i class="ph ph-caret-right text-muted"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-lg-4">
                    <a href="{{ route('profile.activity') }}" class="text-decoration-none">
                        <div class="card hover-effect shadow-sm h-100">
                            <div class="card-body d-flex align-items-center gap-3">
                                <div class="bg-light-secondary p-3 rounded-3">
                                    <i class="ph ph-lightning text-secondary f-s-24"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1 f-s-14">Attività</h6>
                                    <p class="text-muted f-s-11 mb-0">Vedi tutte</p>
                                </div>
                                <i class="ph ph-caret-right text-muted"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-lg-4">
                    <a href="{{ route('articles.create') }}" class="text-decoration-none">
                        <div class="card hover-effect shadow-sm h-100">
                            <div class="card-body d-flex align-items-center gap-3">
                                <div class="bg-light-info p-3 rounded-3">
                                    <i class="ph ph-article text-info f-s-24"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1 f-s-14">Articolo</h6>
                                    <p class="text-muted f-s-11 mb-0">Scrivi nuovo</p>
                                </div>
                                <i class="ph ph-caret-right text-muted"></i>
                            </div>
                        </div>
                    </a>
                </div>

                @if($user->hasRole('poet'))
                <div class="col-12 col-sm-6 col-lg-4">
                    <a href="{{ route('poems.create') }}" class="text-decoration-none">
                        <div class="card hover-effect shadow-sm h-100">
                            <div class="card-body d-flex align-items-center gap-3">
                                <div class="bg-light-primary p-3 rounded-3">
                                    <i class="ph ph-pen-nib text-primary f-s-24"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1 f-s-14">Poesia</h6>
                                    <p class="text-muted f-s-11 mb-0">Scrivi nuova</p>
                                </div>
                                <i class="ph ph-caret-right text-muted"></i>
                            </div>
                        </div>
                    </a>
                </div>
                @endif

                @if($user->hasRole('organizer'))
                <div class="col-12 col-sm-6 col-lg-4">
                    <a href="{{ route('events.create') }}" class="text-decoration-none">
                        <div class="card hover-effect shadow-sm h-100">
                            <div class="card-body d-flex align-items-center gap-3">
                                <div class="bg-light-warning p-3 rounded-3">
                                    <i class="ph ph-calendar-plus text-warning f-s-24"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1 f-s-14">Evento</h6>
                                    <p class="text-muted f-s-11 mb-0">Organizza</p>
                                </div>
                                <i class="ph ph-caret-right text-muted"></i>
                            </div>
                        </div>
                    </a>
                </div>
                @endif

                @if($user->hasRole('venue'))
                <div class="col-12 col-sm-6 col-lg-4">
                    <a href="{{ route('venues.create') }}" class="text-decoration-none">
                        <div class="card hover-effect shadow-sm h-100">
                            <div class="card-body d-flex align-items-center gap-3">
                                <div class="bg-light-secondary p-3 rounded-3">
                                    <i class="ph ph-buildings text-secondary f-s-24"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1 f-s-14">Venue</h6>
                                    <p class="text-muted f-s-11 mb-0">Aggiungi locale</p>
                                </div>
                                <i class="ph ph-caret-right text-muted"></i>
                            </div>
                        </div>
                    </a>
                </div>
                @endif

                <div class="col-12 col-sm-6 col-lg-4">
                    <a href="{{ route('videos.upload') }}" class="text-decoration-none">
                        <div class="card hover-effect shadow-sm h-100">
                            <div class="card-body d-flex align-items-center gap-3">
                                <div class="bg-light-danger p-3 rounded-3">
                                    <i class="ph ph-upload text-danger f-s-24"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1 f-s-14">Video</h6>
                                    <p class="text-muted f-s-11 mb-0">Carica nuovo</p>
                                </div>
                                <i class="ph ph-caret-right text-muted"></i>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-sm-6 col-lg-4">
                    <a href="{{ route('photos.create') }}" class="text-decoration-none">
                        <div class="card hover-effect shadow-sm h-100">
                            <div class="card-body d-flex align-items-center gap-3">
                                <div class="bg-light-info p-3 rounded-3">
                                    <i class="ph ph-image-square text-info f-s-24"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1 f-s-14">Foto</h6>
                                    <p class="text-muted f-s-11 mb-0">Carica nuova</p>
                                </div>
                                <i class="ph ph-caret-right text-muted"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<style>
/* ========================================
   MOBILE FIRST - MINIMAL CSS
   ======================================== */

/* Hero Banner - Mobile Base */
.modern-profile-wrapper {
    padding: 15px;
}

.hero-banner {
    position: relative;
    height: 250px;
    background: rgba(var(--primary), 1);
    border-radius: 15px;
    overflow: hidden;
}

.banner-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    opacity: 0.9;
}

.banner-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 120px;
    background: rgba(0, 0, 0, 0.4);
}

/* Avatar */
.avatar-container {
    position: absolute;
    bottom: 60px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 10;
}

.avatar-image {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
}

.verified-badge {
    position: absolute;
    bottom: 0;
    right: 0;
    background: white;
    border-radius: 50%;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.verified-badge i {
    font-size: 1.3rem;
    color: rgba(var(--success), 1);
}

/* User Info */
.user-info-overlay {
    position: absolute;
    bottom: 10px;
    left: 0;
    right: 0;
    text-align: center;
    z-index: 6;
    padding: 0 15px;
}

.user-name {
    font-size: 1.3rem;
    font-weight: 700;
    color: white;
    margin: 0;
    text-shadow: 0 2px 8px rgba(0, 0, 0, 0.6);
}

.user-bio {
    font-size: 0.85rem;
    color: white;
    opacity: 0.95;
    margin: 5px 0 0;
    text-shadow: 0 1px 4px rgba(0, 0, 0, 0.6);
}

/* Stats Pills - Mobile Stacked */
.stats-pills {
    position: absolute;
    top: 10px;
    right: 10px;
    display: flex;
    flex-direction: column;
    gap: 6px;
    z-index: 7;
}

.stat-pill {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 20px;
    padding: 6px 10px;
    display: flex;
    align-items: center;
    gap: 5px;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.15);
    backdrop-filter: blur(8px);
}

.stat-pill:nth-child(1) i { color: rgb(249, 193, 35); font-size: 1.1rem; }
.stat-pill:nth-child(2) i { color: rgb(10, 185, 100); font-size: 1.1rem; }
.stat-pill:nth-child(3) i { color: rgb(15, 98, 106); font-size: 1.1rem; }

.stat-value {
    font-size: 0.9rem;
    font-weight: 700;
    color: #2d3748;
}

.stat-label {
    display: none;
}

/* Navigation - Mobile Scroll */
.profile-nav-tabs {
    display: flex;
    overflow-x: auto;
    gap: 8px;
    padding: 5px 0;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
}

.profile-nav-tabs::-webkit-scrollbar {
    display: none;
}

.nav-tab {
    background: white;
    border: 2px solid #edf2f7;
    border-radius: 12px;
    padding: 10px 16px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 6px;
    transition: all 0.3s ease;
    font-weight: 600;
    color: #4a5568;
    white-space: nowrap;
    flex-shrink: 0;
    font-size: 0.9rem;
}

.nav-tab:hover {
    background: #f7fafc;
    border-color: rgba(var(--primary), 1);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(var(--primary), 0.2);
}

.nav-tab.active {
    background: rgba(var(--primary), 1);
    border-color: rgba(var(--primary), 1);
    color: white;
    box-shadow: 0 6px 16px rgba(var(--primary), 0.4);
}

.nav-tab i {
    font-size: 1.1rem;
}

/* Colored icons when NOT active */
.nav-tab:not(.active):nth-child(1) i { color: rgb(15, 98, 106); }
.nav-tab:not(.active):nth-child(2) i { color: rgb(249, 193, 35); }
.nav-tab:not(.active):nth-child(3) i { color: rgb(10, 185, 100); }
.nav-tab:not(.active):nth-child(4) i { color: rgb(65, 150, 250); }
.nav-tab:not(.active):nth-child(5) i { color: rgb(225, 78, 90); }
.nav-tab:not(.active):nth-child(6) i { color: rgb(249, 193, 35); }
.nav-tab:not(.active):nth-child(7) i { color: rgb(15, 98, 106); }
.nav-tab:not(.active):nth-child(8) i { color: rgb(98, 98, 98); }

/* Event card hover */
.event-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12) !important;
}

.event-image {
    height: 180px;
    object-fit: cover;
}

/* ========================================
   TABLET (768px+)
   ======================================== */
@media (min-width: 768px) {
    .modern-profile-wrapper {
        padding: 20px;
    }

    .hero-banner {
        height: 300px;
    }

    .avatar-container {
        bottom: 70px;
    }

    .avatar-image {
        width: 120px;
        height: 120px;
    }

    .user-name {
        font-size: 1.6rem;
    }

    .user-bio {
        font-size: 0.95rem;
    }

    .stats-pills {
        flex-direction: row;
        top: 15px;
    }

    .stat-pill {
        padding: 8px 14px;
    }

    .stat-label {
        display: inline;
        font-size: 0.75rem;
        color: #718096;
    }

    .profile-nav-tabs {
        justify-content: center;
        overflow-x: visible;
    }

    .nav-tab {
        padding: 12px 20px;
        font-size: 0.95rem;
    }

    .nav-tab i {
        font-size: 1.2rem;
    }

    .event-image {
        height: 200px;
    }
}

/* ========================================
   DESKTOP (1024px+)
   ======================================== */
@media (min-width: 1024px) {
    .modern-profile-wrapper {
        max-width: 1200px;
        margin: 0 auto;
        padding: 30px;
    }

    .hero-banner {
        height: 350px;
    }

    .avatar-container {
        bottom: 80px;
    }

    .avatar-image {
        width: 140px;
        height: 140px;
    }

    .user-name {
        font-size: 1.8rem;
    }

    .stats-pills {
        top: 20px;
        right: 20px;
    }

    .stat-pill {
        padding: 10px 18px;
    }
}
</style>
</div>
