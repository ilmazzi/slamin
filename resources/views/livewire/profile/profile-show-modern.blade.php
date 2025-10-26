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
                <h2 class="user-name">
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
                @forelse($organizedEvents as $event)
                <div class="event-card-modern fade-in-up" style="animation-delay: {{ $loop->index * 0.05 }}s;">
                    <div class="event-date-badge">
                        <div class="date-day">{{ $event->start_datetime->format('d') }}</div>
                        <div class="date-month">{{ $event->start_datetime->format('M') }}</div>
                    </div>
                    
                    @if($event->image_url)
                    <div class="event-image">
                        <img src="{{ $event->image_url }}" alt="{{ $event->title }}">
                    </div>
                    @endif
                    
                    <div class="event-content">
                        <h6 class="event-title">{{ Str::limit($event->title, 50) }}</h6>
                        <div class="event-meta">
                            <span><i class="ph ph-map-pin"></i> {{ $event->city }}</span>
                            <span><i class="ph ph-clock"></i> {{ $event->start_datetime->format('H:i') }}</span>
                        </div>
                        <a href="{{ route('events.show', $event) }}" class="btn-view-event">
                            Visualizza <i class="ph ph-arrow-right"></i>
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

        @elseif($activeTab === 'activities')
            <!-- Activities Timeline -->
            <div class="activities-timeline">
                @forelse($activities->take(10) as $activity)
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

    <style>
        /* Modern Profile Wrapper */
        .modern-profile-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Hero Banner */
        .profile-hero {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            animation: heroAppear 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        @keyframes heroAppear {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero-banner {
            position: relative;
            height: 350px;
            background: linear-gradient(135deg, rgb(15, 98, 106) 0%, rgb(10, 185, 100) 100%);
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
            height: 150px;
            background: linear-gradient(0deg, rgba(0,0,0,0.7) 0%, transparent 100%);
        }

        /* Avatar */
        .avatar-container {
            position: absolute;
            bottom: 80px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 10;
            animation: avatarFloat 3s ease-in-out infinite;
        }

        @keyframes avatarFloat {
            0%, 100% { transform: translateX(-50%) translateY(0); }
            50% { transform: translateX(-50%) translateY(-10px); }
        }

        .avatar-image {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            object-fit: cover;
            position: relative;
            z-index: 2;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .verified-badge {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background: white;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 3;
            color: #48bb78;
            font-size: 1.5rem;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
        }

        /* User Info */
        .user-info-overlay {
            position: absolute;
            bottom: 20px;
            left: 0;
            right: 0;
            text-align: center;
            color: white;
            z-index: 5;
        }

        .user-name {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 5px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }

        .user-bio {
            font-size: 0.95rem;
            opacity: 0.9;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
            max-width: 600px;
            margin: 0 auto;
        }

        /* Stats Pills */
        .stats-pills {
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            gap: 10px;
            z-index: 5;
        }

        .stat-pill {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 25px;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            animation: pillPop 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) backwards;
        }

        .stat-pill:nth-child(1) { animation-delay: 0.1s; }
        .stat-pill:nth-child(2) { animation-delay: 0.2s; }
        .stat-pill:nth-child(3) { animation-delay: 0.3s; }

        @keyframes pillPop {
            from {
                opacity: 0;
                transform: scale(0) rotate(-180deg);
            }
            to {
                opacity: 1;
                transform: scale(1) rotate(0);
            }
        }

        .stat-pill:nth-child(1) i {
            font-size: 1.3rem;
            color: rgb(249, 193, 35); /* Warning/Gold for badges */
        }

        .stat-pill:nth-child(2) i {
            font-size: 1.3rem;
            color: rgb(10, 185, 100); /* Success/Green for points */
        }

        .stat-pill:nth-child(3) i {
            font-size: 1.3rem;
            color: rgb(15, 98, 106); /* Primary/Teal for level */
        }

        .stat-value {
            font-size: 1.1rem;
            font-weight: 700;
            color: #2d3748;
        }

        .stat-label {
            font-size: 0.8rem;
            color: #718096;
        }

        /* Navigation Tabs */
        .profile-nav-tabs {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
            animation: navSlide 0.6s ease-out 0.3s backwards;
        }

        @keyframes navSlide {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .nav-tab {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 15px;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            font-weight: 600;
            color: #4a5568;
        }

        .nav-tab:hover {
            background: #f7fafc;
            border-color: rgb(15, 98, 106);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(15, 98, 106, 0.2);
        }

        .nav-tab.active {
            background: linear-gradient(135deg, rgb(15, 98, 106) 0%, rgb(10, 185, 100) 100%);
            border-color: rgb(15, 98, 106);
            color: white;
            box-shadow: 0 8px 20px rgba(15, 98, 106, 0.4);
        }

        .nav-tab i {
            font-size: 1.2rem;
        }

        /* Colored tab icons (when not active) */
        .nav-tab:not(.active):nth-child(1) i { color: rgb(15, 98, 106); }
        .nav-tab:not(.active):nth-child(2) i { color: rgb(249, 193, 35); }
        .nav-tab:not(.active):nth-child(3) i { color: rgb(10, 185, 100); }
        .nav-tab:not(.active):nth-child(4) i { color: rgb(65, 150, 250); }
        .nav-tab:not(.active):nth-child(5) i { color: rgb(225, 78, 90); }
        .nav-tab:not(.active):nth-child(6) i { color: rgb(249, 193, 35); }
        .nav-tab:not(.active):nth-child(7) i { color: rgb(15, 98, 106); }

        /* Content Cards */
        .content-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-bottom: 20px;
        }

        .card-header-modern {
            padding: 20px 25px;
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-bottom: 3px solid rgb(15, 98, 106);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header-modern i {
            font-size: 1.5rem;
            color: rgb(15, 98, 106);
            margin-right: 10px;
        }

        .card-header-modern h5 {
            display: inline;
            font-weight: 700;
            color: #2d3748;
            margin: 0;
        }

        .card-body-modern {
            padding: 25px;
        }

        .btn-modern-sm {
            background: linear-gradient(135deg, rgb(15, 98, 106) 0%, rgb(10, 185, 100) 100%);
            color: white;
            border: none;
            border-radius: 20px;
            padding: 8px 20px;
            font-weight: 600;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(15, 98, 106, 0.3);
        }

        .btn-modern-sm:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(15, 98, 106, 0.5);
            color: white;
        }

        /* Mini Content Cards (Poems, Articles) */
        .poems-grid,
        .articles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .content-card-mini {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        }

        .content-card-mini:hover {
            transform: translateY(-8px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        }

        .card-image {
            position: relative;
            height: 200px;
            overflow: hidden;
        }

        .card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .content-card-mini:hover .card-image img {
            transform: scale(1.1);
        }

        .image-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(79, 172, 254, 0.9) 0%, rgba(0, 242, 254, 0.9) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .content-card-mini:hover .image-overlay {
            opacity: 1;
        }

        .overlay-btn {
            background: white;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #667eea;
            font-size: 1.8rem;
            transition: transform 0.3s ease;
        }

        .overlay-btn:hover {
            transform: scale(1.2) rotate(10deg);
        }

        .card-content-mini {
            padding: 20px;
        }

        .mini-title {
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 10px;
        }

        .mini-desc {
            font-size: 0.9rem;
            color: #718096;
            margin-bottom: 15px;
        }

        .mini-stats {
            display: flex;
            gap: 15px;
            font-size: 0.85rem;
            color: #a0aec0;
        }

        .mini-stats span {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* Media Grid */
        .media-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 15px;
        }

        .media-item {
            position: relative;
            aspect-ratio: 1;
            border-radius: 15px;
            overflow: hidden;
            cursor: pointer;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease;
        }

        .media-item:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        }

        .media-item img,
        .media-item video {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .media-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.9) 0%, rgba(118, 75, 162, 0.9) 100%);
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .media-item:hover .media-overlay {
            opacity: 1;
        }

        .media-stats {
            position: absolute;
            bottom: 10px;
            right: 10px;
            background: rgba(0, 0, 0, 0.6);
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* Events Grid */
        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
        }

        .event-card-modern {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
            position: relative;
        }

        .event-card-modern:hover {
            transform: translateY(-10px) rotate(1deg);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
        }

        .event-date-badge {
            position: absolute;
            top: 15px;
            left: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px;
            padding: 10px 15px;
            text-align: center;
            z-index: 5;
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .date-day {
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1;
        }

        .date-month {
            font-size: 0.75rem;
            text-transform: uppercase;
            margin-top: 2px;
        }

        .event-image {
            height: 200px;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        }

        .event-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .event-content {
            padding: 20px;
        }

        .event-title {
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 12px;
        }

        .event-meta {
            display: flex;
            gap: 15px;
            color: #718096;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }

        .event-meta span {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .btn-view-event {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 10px 20px;
            border-radius: 20px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-view-event:hover {
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
            color: white;
        }

        /* Activities Timeline */
        .activities-timeline {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        .activity-item {
            display: flex;
            gap: 20px;
            padding: 20px;
            border-radius: 15px;
            margin-bottom: 15px;
            background: #f8f9fa;
            transition: all 0.3s ease;
        }

        .activity-item:hover {
            background: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transform: translateX(10px);
        }

        .activity-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        }

        .activity-icon.poem_created { background: linear-gradient(135deg, rgb(15, 98, 106) 0%, rgb(10, 185, 100) 100%); color: white; }
        .activity-icon.article_created { background: linear-gradient(135deg, rgb(65, 150, 250) 0%, rgb(15, 98, 106) 100%); color: white; }
        .activity-icon.event_organized { background: linear-gradient(135deg, rgb(249, 193, 35) 0%, rgb(225, 78, 90) 100%); color: white; }
        .activity-icon.event_participation { background: linear-gradient(135deg, rgb(10, 185, 100) 0%, rgb(65, 150, 250) 100%); color: white; }
        .activity-icon.badge_earned { background: linear-gradient(135deg, rgb(249, 193, 35) 0%, rgb(225, 78, 90) 100%); color: white; }
        .activity-icon.video_uploaded { background: linear-gradient(135deg, rgb(225, 78, 90) 0%, rgb(15, 98, 106) 100%); color: white; }
        .activity-icon.photo_uploaded { background: linear-gradient(135deg, rgb(65, 150, 250) 0%, rgb(10, 185, 100) 100%); color: white; }
        .activity-icon.comment_added { background: linear-gradient(135deg, rgb(10, 185, 100) 0%, rgb(15, 98, 106) 100%); color: white; }
        .activity-icon.like_given { background: linear-gradient(135deg, rgb(225, 78, 90) 0%, rgb(249, 193, 35) 100%); color: white; }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 5px;
        }

        .activity-time {
            font-size: 0.85rem;
            color: #a0aec0;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #a0aec0;
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: 15px;
            display: block;
        }

        /* Animations */
        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        .fade-in-up {
            animation: fadeInUp 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55) backwards;
        }

        .fade-in-left {
            animation: fadeInLeft 0.5s ease-out backwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .stats-pills {
                position: static;
                justify-content: center;
                margin-top: 20px;
                flex-wrap: wrap;
            }

            .stat-pill {
                flex-direction: column;
                padding: 12px 16px;
                gap: 4px;
            }

            .user-name {
                font-size: 1.5rem;
            }

            .nav-tab span {
                display: none;
            }

            .nav-tab {
                padding: 12px;
            }
        }

        /* Settings Grid */
        .settings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .setting-card {
            background: white;
            border: 2px solid #edf2f7;
            border-radius: 15px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .setting-card:hover {
            border-color: rgba(var(--primary), 1);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(var(--primary), 0.15);
        }

        .setting-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            background: rgba(var(--primary), 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .setting-icon i {
            font-size: 1.5rem;
            color: rgba(var(--primary), 1);
        }

        .setting-content {
            flex: 1;
        }

        .setting-content h6 {
            margin: 0 0 5px 0;
            font-weight: 700;
            color: #2d3748;
        }

        .setting-content p {
            margin: 0;
            font-size: 0.85rem;
            color: #a0aec0;
        }

        .setting-card > i.ph-caret-right {
            color: #cbd5e0;
            font-size: 1.2rem;
            transition: all 0.3s ease;
        }

        .setting-card:hover > i.ph-caret-right {
            color: rgba(var(--primary), 1);
            transform: translateX(5px);
        }
    </style>
</div>

