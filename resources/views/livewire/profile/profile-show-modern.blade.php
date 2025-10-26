@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/profile-modern.css') }}">
@endpush

<div class="modern-profile-wrapper">
    <!-- Hero Banner with Avatar Overlay -->
    <div class="profile-hero mb-4">
        <div class="hero-banner">
            <img src="{{ $user->getBannerImageUrlAttribute() }}" 
                 alt="Banner"
                 class="banner-image">
            <div class="banner-overlay"></div>
            
            <!-- Avatar Float -->
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

            <!-- User Info Overlay -->
            <div class="user-info-overlay">
                <h2 class="user-name text-white">
                    {{ $user->getDisplayName() }}
                </h2>
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

    <!-- Navigation Tabs -->
    <div class="profile-nav-tabs mb-4">
        <button class="nav-tab {{ $activeTab === 'about' ? 'active' : '' }}" 
                wire:click="setActiveTab('about')">
            <i class="ph ph-user-circle"></i>
            <span>Profilo</span>
        </button>
        <button class="nav-tab {{ $activeTab === 'badges' ? 'active' : '' }}" 
                wire:click="setActiveTab('badges')">
            <i class="ph ph-trophy"></i>
            <span>Badge</span>
        </button>
        <button class="nav-tab {{ $activeTab === 'poems' ? 'active' : '' }}" 
                wire:click="setActiveTab('poems')">
            <i class="ph ph-book-open"></i>
            <span>Poesie</span>
        </button>
        <button class="nav-tab {{ $activeTab === 'articles' ? 'active' : '' }}" 
                wire:click="setActiveTab('articles')">
            <i class="ph ph-newspaper"></i>
            <span>Articoli</span>
        </button>
        <button class="nav-tab {{ $activeTab === 'media' ? 'active' : '' }}" 
                wire:click="setActiveTab('media')">
            <i class="ph ph-play-circle"></i>
            <span>Media</span>
        </button>
        <button class="nav-tab {{ $activeTab === 'events' ? 'active' : '' }}" 
                wire:click="setActiveTab('events')">
            <i class="ph ph-calendar"></i>
            <span>Eventi</span>
        </button>
        <button class="nav-tab {{ $activeTab === 'activities' ? 'active' : '' }}" 
                wire:click="setActiveTab('activities')">
            <i class="ph ph-activity"></i>
            <span>Attività</span>
        </button>
        @if($isOwnProfile)
        <button class="nav-tab {{ $activeTab === 'settings' ? 'active' : '' }}" 
                wire:click="setActiveTab('settings')">
            <i class="ph ph-gear"></i>
            <span>Impostazioni</span>
        </button>
        @endif
    </div>

    <!-- Content Area -->
    <div class="profile-content">
        @if($activeTab === 'about')
            <!-- About Section -->
            <div class="content-card fade-in">
                <div class="card-header-modern">
                    <i class="ph ph-info"></i>
                    <h5>Chi Sono</h5>
                </div>
                <div class="card-body-modern">
                    @if($user->bio)
                        <p class="bio-text">{{ $user->bio }}</p>
                    @else
                        <p class="text-muted fst-italic">Nessuna biografia disponibile</p>
                    @endif
                    
                    @if($user->city || $user->country)
                    <div class="info-item">
                        <i class="ph ph-map-pin text-primary"></i>
                        <span>{{ $user->city }}{{ $user->city && $user->country ? ', ' : '' }}{{ $user->country }}</span>
                    </div>
                    @endif
                </div>
            </div>

        @elseif($activeTab === 'badges')
            <!-- Badges Section -->
            <div class="content-card fade-in">
                <div class="card-header-modern">
                    <div>
                        <i class="ph ph-medal-military"></i>
                        <h5>Badge in Evidenza</h5>
                    </div>
                    <a href="{{ route('profile.my-badges') }}" class="btn-modern-sm">
                        <i class="ph ph-trophy"></i>
                        Trophy Case
                    </a>
                </div>
                <div class="card-body-modern">
                    @livewire('profile.badge-display-stack-cards', ['user' => $user])
                </div>
            </div>

        @elseif($activeTab === 'poems')
            <!-- Poems Section -->
            <div class="poems-grid">
                @forelse($poems as $poem)
                <div class="content-card-mini fade-in-up" style="animation-delay: {{ $loop->index * 0.05 }}s;">
                    @if($poem->thumbnail_url)
                    <div class="card-image">
                        <img src="{{ $poem->thumbnail_url }}" alt="{{ $poem->title }}">
                        <div class="image-overlay">
                            <a href="{{ route('poems.show', $poem->slug) }}" class="overlay-btn">
                                <i class="ph ph-eye"></i>
                            </a>
                        </div>
                    </div>
                    @endif
                    <div class="card-content-mini">
                        <h6 class="mini-title">{{ Str::limit($poem->title, 40) }}</h6>
                        <p class="mini-desc">{{ Str::limit($poem->description, 60) }}</p>
                        <div class="mini-stats">
                            <span><i class="ph ph-eye"></i> {{ $poem->view_count }}</span>
                            <span><i class="ph ph-heart"></i> {{ $poem->like_count }}</span>
                            <span><i class="ph ph-chat"></i> {{ $poem->comment_count }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <i class="ph ph-book-open"></i>
                    <p>Nessuna poesia ancora</p>
                </div>
                @endforelse
            </div>

        @elseif($activeTab === 'articles')
            <!-- Articles Section -->
            <div class="articles-grid">
                @forelse($articles as $article)
                <div class="content-card-mini fade-in-up" style="animation-delay: {{ $loop->index * 0.05 }}s;">
                    @if($article->featured_image)
                    <div class="card-image">
                        <img src="{{ Storage::url($article->featured_image) }}" alt="{{ $article->title }}">
                        <div class="image-overlay">
                            <a href="{{ route('articles.show', $article->slug) }}" class="overlay-btn">
                                <i class="ph ph-article"></i>
                            </a>
                        </div>
                    </div>
                    @endif
                    <div class="card-content-mini">
                        <h6 class="mini-title">{{ Str::limit($article->title, 40) }}</h6>
                        <p class="mini-desc">{{ Str::limit($article->excerpt, 60) }}</p>
                        <div class="mini-stats">
                            <span><i class="ph ph-eye"></i> {{ $article->views_count }}</span>
                            <span><i class="ph ph-heart"></i> {{ $article->likes_count }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <i class="ph ph-newspaper"></i>
                    <p>Nessun articolo ancora</p>
                </div>
                @endforelse
            </div>

        @elseif($activeTab === 'media')
            <!-- Media Grid (Photos + Videos) -->
            <div class="media-grid">
                @forelse($photos->take(6) as $photo)
                <div class="media-item fade-in-up" style="animation-delay: {{ $loop->index * 0.05 }}s;">
                    <img src="{{ $photo->image_url }}" alt="{{ $photo->title }}">
                    <div class="media-overlay">
                        <i class="ph ph-image f-s-24"></i>
                        <span class="media-stats">
                            <i class="ph ph-heart"></i> {{ $photo->like_count }}
                        </span>
                    </div>
                </div>
                @empty
                @endforelse
                
                @forelse($videos->take(6) as $video)
                <div class="media-item fade-in-up" style="animation-delay: {{ ($loop->index + 6) * 0.05 }}s;">
                    <video src="{{ $video->video_url }}" style="width: 100%; height: 100%; object-fit: cover;"></video>
                    <div class="media-overlay">
                        <i class="ph ph-play-circle f-s-24"></i>
                        <span class="media-stats">
                            <i class="ph ph-heart"></i> {{ $video->like_count }}
                        </span>
                    </div>
                </div>
                @empty
                @endforelse

                @if($photos->count() === 0 && $videos->count() === 0)
                <div class="empty-state">
                    <i class="ph ph-images"></i>
                    <p>Nessun media ancora</p>
                </div>
                @endif
            </div>

        @elseif($activeTab === 'events')
            <!-- Events Grid -->
            <div class="events-grid">
                @forelse($events as $event)
                <div class="event-card-modern fade-in-up" style="animation-delay: {{ $loop->index * 0.05 }}s;">
                    <div class="event-date-badge">
                        <div class="date-day">{{ $event->start_datetime->format('d') }}</div>
                        <div class="date-month">{{ $event->start_datetime->format('M') }}</div>
                    </div>
                    
                    <div class="event-image">
                        <img src="{{ \App\Helpers\EventImageHelper::getEventImageUrl($event) }}" 
                             alt="{{ $event->title }}"
                             style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    
                    <div class="event-content">
                        <h6 class="event-title">{{ $event->title }}</h6>
                        @if($event->description)
                        <p class="event-description">{{ Str::limit($event->description, 120) }}</p>
                        @endif
                        
                        <div class="event-info-grid">
                            <div class="event-info-item">
                                <i class="ph ph-calendar-dots"></i>
                                <span>{{ $event->start_datetime->format('d/m/Y') }}</span>
                            </div>
                            <div class="event-info-item">
                                <i class="ph ph-clock"></i>
                                <span>{{ $event->start_datetime->format('H:i') }}</span>
                            </div>
                            @if($event->city)
                            <div class="event-info-item">
                                <i class="ph ph-map-pin"></i>
                                <span>{{ $event->city }}</span>
                            </div>
                            @endif
                            @if($event->venue)
                            <div class="event-info-item">
                                <i class="ph ph-buildings"></i>
                                <span>{{ $event->venue->name }}</span>
                            </div>
                            @endif
                            @if($event->price)
                            <div class="event-info-item">
                                <i class="ph ph-ticket"></i>
                                <span>{{ $event->price > 0 ? '€ '.$event->price : 'Gratuito' }}</span>
                            </div>
                            @endif
                            @if($event->category)
                            <div class="event-info-item">
                                <i class="ph ph-tag"></i>
                                <span>{{ $event->category }}</span>
                            </div>
                            @endif
                        </div>
                        
                        <a href="{{ route('events.show', $event) }}" class="btn-view-event">
                            Dettagli Evento <i class="ph ph-arrow-right"></i>
                        </a>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <i class="ph ph-calendar-x"></i>
                    <p>Nessun evento organizzato</p>
                </div>
                @endforelse
            </div>
            <div class="mt-4">
                {{ $events->links() }}
            </div>

        @elseif($activeTab === 'activities')
            <!-- Activities Timeline -->
            <div class="activities-timeline">
                @forelse($activities as $activity)
                <div class="activity-item fade-in-left" style="animation-delay: {{ $loop->index * 0.05 }}s;">
                    <div class="activity-icon {{ $activity->type }}">
                        <i class="ph ph-{{ $this->getActivityIcon($activity->type) }}"></i>
                    </div>
                    <div class="activity-content">
                        <h6 class="activity-title">{{ $activity->description }}</h6>
                        <p class="activity-time">
                            <i class="ph ph-clock"></i>
                            {{ $activity->created_at->diffForHumans() }}
                        </p>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <i class="ph ph-activity"></i>
                    <p>Nessuna attività recente</p>
                </div>
                @endforelse
            </div>
            <div class="mt-4">
                {{ $activities->links() }}
            </div>

        @elseif($activeTab === 'settings' && $isOwnProfile)
            <!-- Settings Section -->
            <div class="content-card fade-in">
                <div class="card-header-modern">
                    <div>
                        <i class="ph ph-gear"></i>
                        <h5>Impostazioni</h5>
                    </div>
                </div>
                <div class="card-body-modern">
                    <div class="settings-grid">
                        <a href="{{ route('profile.edit') }}" class="setting-card">
                            <div class="setting-icon">
                                <i class="ph ph-user-circle"></i>
                            </div>
                            <div class="setting-content">
                                <h6>Modifica Profilo</h6>
                                <p>Aggiorna le tue informazioni personali</p>
                            </div>
                            <i class="ph ph-caret-right"></i>
                        </a>

                        <a href="{{ route('profile.my-badges') }}" class="setting-card">
                            <div class="setting-icon">
                                <i class="ph ph-medal-military"></i>
                            </div>
                            <div class="setting-content">
                                <h6>Gestione Badge</h6>
                                <p>Gestisci i tuoi badge e trophy</p>
                            </div>
                            <i class="ph ph-caret-right"></i>
                        </a>

                        <a href="{{ route('profile.languages.index') }}" class="setting-card">
                            <div class="setting-icon">
                                <i class="ph ph-globe"></i>
                            </div>
                            <div class="setting-content">
                                <h6>Lingue</h6>
                                <p>Gestisci le lingue che parli</p>
                            </div>
                            <i class="ph ph-caret-right"></i>
                        </a>

                        <a href="{{ route('profile.media') }}" class="setting-card">
                            <div class="setting-icon">
                                <i class="ph ph-video-camera"></i>
                            </div>
                            <div class="setting-content">
                                <h6>Media</h6>
                                <p>Gestisci foto e video</p>
                            </div>
                            <i class="ph ph-caret-right"></i>
                        </a>

                        <a href="{{ route('profile.activity') }}" class="setting-card">
                            <div class="setting-icon">
                                <i class="ph ph-lightning"></i>
                            </div>
                            <div class="setting-content">
                                <h6>Tutte le Attività</h6>
                                <p>Visualizza tutte le tue attività</p>
                            </div>
                            <i class="ph ph-caret-right"></i>
                        </a>

                        <a href="{{ route('articles.create') }}" class="setting-card">
                            <div class="setting-icon">
                                <i class="ph ph-article"></i>
                            </div>
                            <div class="setting-content">
                                <h6>Crea Articolo</h6>
                                <p>Scrivi un nuovo articolo</p>
                            </div>
                            <i class="ph ph-caret-right"></i>
                        </a>

                        @if($user->hasRole('poet'))
                        <a href="{{ route('poems.create') }}" class="setting-card">
                            <div class="setting-icon">
                                <i class="ph ph-pen-nib"></i>
                            </div>
                            <div class="setting-content">
                                <h6>Crea Poesia</h6>
                                <p>Scrivi una nuova poesia</p>
                            </div>
                            <i class="ph ph-caret-right"></i>
                        </a>
                        @endif

                        @if($user->hasRole('organizer'))
                        <a href="{{ route('events.create') }}" class="setting-card">
                            <div class="setting-icon">
                                <i class="ph ph-calendar-plus"></i>
                            </div>
                            <div class="setting-content">
                                <h6>Crea Evento</h6>
                                <p>Organizza un nuovo evento</p>
                            </div>
                            <i class="ph ph-caret-right"></i>
                        </a>
                        @endif

                        @if($user->hasRole('venue'))
                        <a href="{{ route('venues.create') }}" class="setting-card">
                            <div class="setting-icon">
                                <i class="ph ph-buildings"></i>
                            </div>
                            <div class="setting-content">
                                <h6>Crea Venue</h6>
                                <p>Aggiungi un nuovo locale</p>
                            </div>
                            <i class="ph ph-caret-right"></i>
                        </a>
                        @endif

                        <a href="{{ route('videos.upload') }}" class="setting-card">
                            <div class="setting-icon">
                                <i class="ph ph-upload"></i>
                            </div>
                            <div class="setting-content">
                                <h6>Carica Video</h6>
                                <p>Carica un nuovo video</p>
                            </div>
                            <i class="ph ph-caret-right"></i>
                        </a>

                        <a href="{{ route('photos.create') }}" class="setting-card">
                            <div class="setting-icon">
                                <i class="ph ph-image-square"></i>
                            </div>
                            <div class="setting-content">
                                <h6>Carica Foto</h6>
                                <p>Carica una nuova foto</p>
                            </div>
                            <i class="ph ph-caret-right"></i>
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
