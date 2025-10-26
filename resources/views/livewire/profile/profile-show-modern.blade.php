<div>
<div class="container-fluid px-2 px-md-3 px-lg-4">
    
    <!-- Hero Banner - Mobile First -->
    <div class="card mb-3 mb-md-4 overflow-hidden border-0 shadow-sm">
        <div class="position-relative bg-primary" style="height: 200px;">
            @if($user->banner_image)
            <img src="{{ $user->getBannerImageUrlAttribute() }}" 
                 alt="Banner"
                 class="w-100 h-100 position-absolute top-0 start-0"
                 style="object-fit: cover; opacity: 0.9;">
            @endif
            
            <!-- Avatar - Responsive Size -->
            <div class="position-absolute bottom-0 start-50 translate-middle-x" style="margin-bottom: -40px;">
                <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($user) }}"
                     alt="{{ $user->name }}"
                     class="rounded-circle border border-3 border-white shadow"
                     style="width: 90px; height: 90px; object-fit: cover;">
                @if($user->verified_at)
                <span class="position-absolute bottom-0 end-0 bg-white rounded-circle p-1 shadow-sm">
                    <i class="ph-fill ph-check-circle text-success f-s-18"></i>
                </span>
                @endif
            </div>

            <!-- Stats - Mobile Optimized -->
            <div class="position-absolute top-0 end-0 m-2 d-flex flex-column flex-sm-row gap-2">
                <span class="badge bg-white text-dark px-2 py-1 shadow-sm f-s-11">
                    <i class="ph ph-medal text-warning me-1"></i>
                    <strong>{{ $badgesCount }}</strong>
                </span>
                <span class="badge bg-white text-dark px-2 py-1 shadow-sm f-s-11">
                    <i class="ph ph-star text-success me-1"></i>
                    <strong>{{ $totalPoints }}</strong>
                </span>
                <span class="badge bg-white text-dark px-2 py-1 shadow-sm f-s-11">
                    <i class="ph ph-ranking text-primary me-1"></i>
                    <strong>Lv{{ $level }}</strong>
                </span>
            </div>
        </div>
        
        <!-- User Info -->
        <div class="card-body text-center pt-5 pb-2 pb-md-3">
            <h3 class="fw-bold mb-1 f-s-18 f-s-md-24">{{ $user->getDisplayName() }}</h3>
            @if($user->bio)
            <p class="text-muted f-s-12 f-s-md-14 mb-0">{{ Str::limit($user->bio, 120) }}</p>
            @endif
        </div>
    </div>

    <!-- Navigation Tabs - Icons only on mobile, with text on desktop -->
    <div class="d-flex gap-2 mb-3 mb-md-4 overflow-auto pb-2" style="scrollbar-width: none; -webkit-overflow-scrolling: touch;">
        <button class="btn {{ $activeTab === 'about' ? 'btn-primary' : 'btn-outline-secondary' }} flex-shrink-0 px-2 px-md-3" 
                wire:click="setActiveTab('about')" style="min-width: auto;">
            <i class="ph ph-user-circle f-s-18"></i>
            <span class="d-none d-md-inline ms-2">Profilo</span>
        </button>
        <button class="btn {{ $activeTab === 'badges' ? 'btn-primary' : 'btn-outline-secondary' }} flex-shrink-0 px-2 px-md-3" 
                wire:click="setActiveTab('badges')" style="min-width: auto;">
            <i class="ph ph-trophy f-s-18"></i>
            <span class="d-none d-md-inline ms-2">Badge</span>
        </button>
        <button class="btn {{ $activeTab === 'poems' ? 'btn-primary' : 'btn-outline-secondary' }} flex-shrink-0 px-2 px-md-3" 
                wire:click="setActiveTab('poems')" style="min-width: auto;">
            <i class="ph ph-book-open f-s-18"></i>
            <span class="d-none d-md-inline ms-2">Poesie</span>
        </button>
        <button class="btn {{ $activeTab === 'articles' ? 'btn-primary' : 'btn-outline-secondary' }} flex-shrink-0 px-2 px-md-3" 
                wire:click="setActiveTab('articles')" style="min-width: auto;">
            <i class="ph ph-newspaper f-s-18"></i>
            <span class="d-none d-md-inline ms-2">Articoli</span>
        </button>
        <button class="btn {{ $activeTab === 'media' ? 'btn-primary' : 'btn-outline-secondary' }} flex-shrink-0 px-2 px-md-3" 
                wire:click="setActiveTab('media')" style="min-width: auto;">
            <i class="ph ph-play-circle f-s-18"></i>
            <span class="d-none d-md-inline ms-2">Media</span>
        </button>
        <button class="btn {{ $activeTab === 'events' ? 'btn-primary' : 'btn-outline-secondary' }} flex-shrink-0 px-2 px-md-3" 
                wire:click="setActiveTab('events')" style="min-width: auto;">
            <i class="ph ph-calendar f-s-18"></i>
            <span class="d-none d-md-inline ms-2">Eventi</span>
        </button>
        <button class="btn {{ $activeTab === 'activities' ? 'btn-primary' : 'btn-outline-secondary' }} flex-shrink-0 px-2 px-md-3" 
                wire:click="setActiveTab('activities')" style="min-width: auto;">
            <i class="ph ph-activity f-s-18"></i>
            <span class="d-none d-md-inline ms-2">Attività</span>
        </button>
        @if($isOwnProfile)
        <button class="btn {{ $activeTab === 'settings' ? 'btn-primary' : 'btn-outline-secondary' }} flex-shrink-0 px-2 px-md-3" 
                wire:click="setActiveTab('settings')" style="min-width: auto;">
            <i class="ph ph-gear f-s-18"></i>
            <span class="d-none d-md-inline ms-2">Impostazioni</span>
        </button>
        @endif
    </div>

    <!-- Content Area - Tab Based -->
    @if($activeTab === 'about')
        <!-- About Section -->
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white border-bottom border-2 border-primary">
                <h5 class="mb-0 fw-bold f-s-16">
                    <i class="ph ph-info me-2 text-primary"></i>
                    Chi Sono
                </h5>
            </div>
            <div class="card-body">
                @if($user->bio)
                    <p class="mb-2 f-s-14">{{ $user->bio }}</p>
                @else
                    <p class="text-muted fst-italic mb-0 f-s-14">Nessuna biografia disponibile</p>
                @endif
                
                @if($user->city || $user->country)
                <div class="d-flex align-items-center gap-2 text-muted mt-3 f-s-13">
                    <i class="ph ph-map-pin text-primary"></i>
                    <span>{{ $user->city }}{{ $user->city && $user->country ? ', ' : '' }}{{ $user->country }}</span>
                </div>
                @endif
            </div>
        </div>

    @elseif($activeTab === 'badges')
        <!-- Badges Section -->
        <div class="card shadow-sm mb-3">
            <div class="card-header bg-white border-bottom border-2 border-primary d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0 fw-bold f-s-16">
                    <i class="ph ph-medal-military me-2 text-primary"></i>
                    Badge in Evidenza
                </h5>
                <a href="{{ route('profile.my-badges') }}" class="btn btn-sm btn-primary">
                    <i class="ph ph-trophy me-1"></i>
                    <span class="d-none d-sm-inline">Trophy Case</span>
                </a>
            </div>
            <div class="card-body p-2 p-md-3">
                <div class="overflow-hidden">
                    @livewire('profile.badge-display-stack-cards', ['user' => $user])
                </div>
            </div>
        </div>

    @elseif($activeTab === 'poems')
        <!-- Poems Grid - Mobile First -->
        <div class="row g-2 g-md-3">
            @forelse($poems as $poem)
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="card h-100 hover-effect shadow-sm">
                    @if($poem->thumbnail_url)
                    <img src="{{ $poem->thumbnail_url }}" class="card-img-top" alt="{{ $poem->title }}" style="height: 150px; object-fit: cover;">
                    @endif
                    <div class="card-body p-3">
                        <h6 class="card-title fw-bold f-s-14 mb-2">{{ Str::limit($poem->title, 50) }}</h6>
                        <p class="card-text text-muted f-s-12 mb-2">{{ Str::limit($poem->content, 80) }}</p>
                        <div class="d-flex gap-3 text-muted f-s-11">
                            <span><i class="ph ph-eye me-1"></i>{{ $poem->view_count }}</span>
                            <span><i class="ph ph-heart me-1"></i>{{ $poem->like_count }}</span>
                            <span><i class="ph ph-chat me-1"></i>{{ $poem->comment_count }}</span>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0 pt-0 pb-3 px-3">
                        <a href="{{ route('poems.show', $poem->slug) }}" class="btn btn-sm btn-primary w-100">
                            <i class="ph ph-eye me-1"></i>
                            Leggi
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-4">
                <i class="ph ph-book-open text-muted" style="font-size: 2.5rem;"></i>
                <p class="text-muted mt-2 f-s-13">Nessuna poesia ancora</p>
            </div>
            @endforelse
        </div>
        <div class="mt-3">{{ $poems->links() }}</div>

    @elseif($activeTab === 'articles')
        <!-- Articles Grid -->
        <div class="row g-2 g-md-3">
            @forelse($articles as $article)
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="card h-100 hover-effect shadow-sm">
                    @if($article->featured_image)
                    <img src="{{ Storage::url($article->featured_image) }}" class="card-img-top" alt="{{ $article->title }}" style="height: 150px; object-fit: cover;">
                    @endif
                    <div class="card-body p-3">
                        <h6 class="card-title fw-bold f-s-14 mb-2">{{ Str::limit($article->title, 50) }}</h6>
                        <p class="card-text text-muted f-s-12 mb-2">{{ Str::limit($article->excerpt ?? $article->content, 80) }}</p>
                        <div class="d-flex gap-3 text-muted f-s-11">
                            <span><i class="ph ph-eye me-1"></i>{{ $article->views_count }}</span>
                            <span><i class="ph ph-heart me-1"></i>{{ $article->likes_count }}</span>
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0 pt-0 pb-3 px-3">
                        <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-sm btn-primary w-100">
                            <i class="ph ph-article me-1"></i>
                            Leggi
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-4">
                <i class="ph ph-newspaper text-muted" style="font-size: 2.5rem;"></i>
                <p class="text-muted mt-2 f-s-13">Nessun articolo ancora</p>
            </div>
            @endforelse
        </div>
        <div class="mt-3">{{ $articles->links() }}</div>

    @elseif($activeTab === 'media')
        <!-- Media Grid - Mobile First -->
        <div class="row g-2">
            @forelse($photos->take(12) as $photo)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card hover-effect shadow-sm">
                    <img src="{{ $photo->image_url }}" class="card-img" alt="{{ $photo->title }}" style="height: 140px; object-fit: cover;">
                    <div class="card-body p-2">
                        <small class="text-muted f-s-11">
                            <i class="ph ph-heart me-1"></i>{{ $photo->like_count }}
                        </small>
                    </div>
                </div>
            </div>
            @empty
            @endforelse
            
            @forelse($videos->take(12) as $video)
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card hover-effect shadow-sm position-relative">
                    <div class="position-relative" style="height: 140px; overflow: hidden;">
                        <video src="{{ $video->video_url }}" class="w-100 h-100" style="object-fit: cover;"></video>
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <i class="ph ph-play-circle text-white" style="font-size: 2rem; opacity: 0.9;"></i>
                        </div>
                    </div>
                    <div class="card-body p-2">
                        <small class="text-muted f-s-11">
                            <i class="ph ph-heart me-1"></i>{{ $video->like_count }}
                        </small>
                    </div>
                </div>
            </div>
            @empty
            @endforelse

            @if($photos->count() === 0 && $videos->count() === 0)
            <div class="col-12 text-center py-4">
                <i class="ph ph-images text-muted" style="font-size: 2.5rem;"></i>
                <p class="text-muted mt-2 f-s-13">Nessun media ancora</p>
            </div>
            @endif
        </div>

    @elseif($activeTab === 'events')
        <!-- Events Grid - Mobile First -->
        <div class="row g-2 g-md-3 g-lg-4">
            @forelse($events as $event)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <div class="position-relative">
                        <img src="{{ \App\Helpers\EventImageHelper::getEventImageUrl($event) }}" 
                             class="card-img-top" 
                             alt="{{ $event->title }}"
                             style="height: 160px; object-fit: cover;">
                        <span class="position-absolute top-0 start-0 m-2 badge bg-primary shadow-sm px-2 py-1">
                            <div class="fw-bold lh-1 f-s-16">{{ $event->start_datetime->format('d') }}</div>
                            <div class="text-uppercase lh-1 f-s-9">{{ $event->start_datetime->format('M') }}</div>
                        </span>
                    </div>
                    <div class="card-body p-3">
                        <h6 class="card-title fw-bold mb-2 f-s-14">{{ $event->title }}</h6>
                        @if($event->description)
                        <p class="card-text text-muted f-s-12 mb-3" style="line-height: 1.4;">{{ Str::limit($event->description, 90) }}</p>
                        @endif
                        
                        <div class="d-flex flex-column gap-2 mb-3">
                            <div class="d-flex align-items-center gap-2 f-s-12 text-muted">
                                <i class="ph ph-calendar-dots text-primary f-s-14"></i>
                                <span>{{ $event->start_datetime->format('d/m/Y H:i') }}</span>
                            </div>
                            @if($event->city)
                            <div class="d-flex align-items-center gap-2 f-s-12 text-muted">
                                <i class="ph ph-map-pin text-primary f-s-14"></i>
                                <span>{{ $event->city }}</span>
                            </div>
                            @endif
                            @if($event->venue)
                            <div class="d-flex align-items-center gap-2 f-s-12 text-muted">
                                <i class="ph ph-buildings text-primary f-s-14"></i>
                                <span>{{ Str::limit($event->venue->name, 25) }}</span>
                            </div>
                            @endif
                            @if(isset($event->price))
                            <div class="d-flex align-items-center gap-2 f-s-12 text-muted">
                                <i class="ph ph-ticket text-primary f-s-14"></i>
                                <span>{{ $event->price > 0 ? '€ '.$event->price : 'Gratuito' }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-footer bg-white border-top-0 pt-0 pb-3 px-3">
                        <a href="{{ route('events.show', $event) }}" class="btn btn-sm btn-primary w-100">
                            Dettagli
                            <i class="ph ph-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-4">
                <i class="ph ph-calendar-x text-muted" style="font-size: 2.5rem;"></i>
                <p class="text-muted mt-2 f-s-13">Nessun evento organizzato</p>
            </div>
            @endforelse
        </div>
        <div class="mt-3">{{ $events->links() }}</div>

    @elseif($activeTab === 'activities')
        <!-- Activities List - Mobile First -->
        <div class="row g-2">
            @forelse($activities as $activity)
            <div class="col-12">
                <div class="card hover-effect shadow-sm">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
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
                                 style="width: 40px; height: 40px;">
                                <i class="ph ph-{{ $this->getActivityIcon($activity->type) }} text-white f-s-16"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1">
                            <h6 class="mb-1 fw-bold f-s-13">{{ $activity->description }}</h6>
                            <small class="text-muted f-s-11">
                                <i class="ph ph-clock me-1"></i>
                                {{ $activity->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-4">
                <i class="ph ph-activity text-muted" style="font-size: 2.5rem;"></i>
                <p class="text-muted mt-2 f-s-13">Nessuna attività recente</p>
            </div>
            @endforelse
        </div>
        <div class="mt-3">{{ $activities->links() }}</div>

    @elseif($activeTab === 'settings' && $isOwnProfile)
        <!-- Settings Grid - Mobile First -->
        <div class="row g-2 g-md-3">
            <div class="col-12 col-sm-6 col-lg-4">
                <a href="{{ route('profile.edit') }}" class="text-decoration-none">
                    <div class="card hover-effect shadow-sm h-100">
                        <div class="card-body p-3 d-flex align-items-center gap-2">
                            <div class="bg-light-primary p-2 rounded-3 flex-shrink-0">
                                <i class="ph ph-user-circle text-primary f-s-20"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-0 f-s-13">Modifica Profilo</h6>
                                <p class="text-muted f-s-10 mb-0">Info personali</p>
                            </div>
                            <i class="ph ph-caret-right text-muted f-s-16"></i>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-sm-6 col-lg-4">
                <a href="{{ route('profile.my-badges') }}" class="text-decoration-none">
                    <div class="card hover-effect shadow-sm h-100">
                        <div class="card-body p-3 d-flex align-items-center gap-2">
                            <div class="bg-light-warning p-2 rounded-3 flex-shrink-0">
                                <i class="ph ph-medal-military text-warning f-s-20"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-0 f-s-13">Badge</h6>
                                <p class="text-muted f-s-10 mb-0">Gestisci trophy</p>
                            </div>
                            <i class="ph ph-caret-right text-muted f-s-16"></i>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-sm-6 col-lg-4">
                <a href="{{ route('profile.languages.index') }}" class="text-decoration-none">
                    <div class="card hover-effect shadow-sm h-100">
                        <div class="card-body p-3 d-flex align-items-center gap-2">
                            <div class="bg-light-info p-2 rounded-3 flex-shrink-0">
                                <i class="ph ph-globe text-info f-s-20"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-0 f-s-13">Lingue</h6>
                                <p class="text-muted f-s-10 mb-0">Gestisci lingue</p>
                            </div>
                            <i class="ph ph-caret-right text-muted f-s-16"></i>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-sm-6 col-lg-4">
                <a href="{{ route('profile.media') }}" class="text-decoration-none">
                    <div class="card hover-effect shadow-sm h-100">
                        <div class="card-body p-3 d-flex align-items-center gap-2">
                            <div class="bg-light-success p-2 rounded-3 flex-shrink-0">
                                <i class="ph ph-video-camera text-success f-s-20"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-0 f-s-13">Media</h6>
                                <p class="text-muted f-s-10 mb-0">Foto e video</p>
                            </div>
                            <i class="ph ph-caret-right text-muted f-s-16"></i>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-sm-6 col-lg-4">
                <a href="{{ route('profile.activity') }}" class="text-decoration-none">
                    <div class="card hover-effect shadow-sm h-100">
                        <div class="card-body p-3 d-flex align-items-center gap-2">
                            <div class="bg-light-secondary p-2 rounded-3 flex-shrink-0">
                                <i class="ph ph-lightning text-secondary f-s-20"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-0 f-s-13">Attività</h6>
                                <p class="text-muted f-s-10 mb-0">Vedi tutte</p>
                            </div>
                            <i class="ph ph-caret-right text-muted f-s-16"></i>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-sm-6 col-lg-4">
                <a href="{{ route('articles.create') }}" class="text-decoration-none">
                    <div class="card hover-effect shadow-sm h-100">
                        <div class="card-body p-3 d-flex align-items-center gap-2">
                            <div class="bg-light-info p-2 rounded-3 flex-shrink-0">
                                <i class="ph ph-article text-info f-s-20"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-0 f-s-13">Articolo</h6>
                                <p class="text-muted f-s-10 mb-0">Scrivi nuovo</p>
                            </div>
                            <i class="ph ph-caret-right text-muted f-s-16"></i>
                        </div>
                    </div>
                </a>
            </div>

            @if($user->hasRole('poet'))
            <div class="col-12 col-sm-6 col-lg-4">
                <a href="{{ route('poems.create') }}" class="text-decoration-none">
                    <div class="card hover-effect shadow-sm h-100">
                        <div class="card-body p-3 d-flex align-items-center gap-2">
                            <div class="bg-light-primary p-2 rounded-3 flex-shrink-0">
                                <i class="ph ph-pen-nib text-primary f-s-20"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-0 f-s-13">Poesia</h6>
                                <p class="text-muted f-s-10 mb-0">Scrivi nuova</p>
                            </div>
                            <i class="ph ph-caret-right text-muted f-s-16"></i>
                        </div>
                    </div>
                </a>
            </div>
            @endif

            @if($user->hasRole('organizer'))
            <div class="col-12 col-sm-6 col-lg-4">
                <a href="{{ route('events.create') }}" class="text-decoration-none">
                    <div class="card hover-effect shadow-sm h-100">
                        <div class="card-body p-3 d-flex align-items-center gap-2">
                            <div class="bg-light-warning p-2 rounded-3 flex-shrink-0">
                                <i class="ph ph-calendar-plus text-warning f-s-20"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-0 f-s-13">Evento</h6>
                                <p class="text-muted f-s-10 mb-0">Organizza</p>
                            </div>
                            <i class="ph ph-caret-right text-muted f-s-16"></i>
                        </div>
                    </div>
                </a>
            </div>
            @endif

            @if($user->hasRole('venue'))
            <div class="col-12 col-sm-6 col-lg-4">
                <a href="{{ route('venues.create') }}" class="text-decoration-none">
                    <div class="card hover-effect shadow-sm h-100">
                        <div class="card-body p-3 d-flex align-items-center gap-2">
                            <div class="bg-light-secondary p-2 rounded-3 flex-shrink-0">
                                <i class="ph ph-buildings text-secondary f-s-20"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-0 f-s-13">Venue</h6>
                                <p class="text-muted f-s-10 mb-0">Aggiungi locale</p>
                            </div>
                            <i class="ph ph-caret-right text-muted f-s-16"></i>
                        </div>
                    </div>
                </a>
            </div>
            @endif

            <div class="col-12 col-sm-6 col-lg-4">
                <a href="{{ route('videos.upload') }}" class="text-decoration-none">
                    <div class="card hover-effect shadow-sm h-100">
                        <div class="card-body p-3 d-flex align-items-center gap-2">
                            <div class="bg-light-danger p-2 rounded-3 flex-shrink-0">
                                <i class="ph ph-upload text-danger f-s-20"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-0 f-s-13">Video</h6>
                                <p class="text-muted f-s-10 mb-0">Carica nuovo</p>
                            </div>
                            <i class="ph ph-caret-right text-muted f-s-16"></i>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-12 col-sm-6 col-lg-4">
                <a href="{{ route('photos.create') }}" class="text-decoration-none">
                    <div class="card hover-effect shadow-sm h-100">
                        <div class="card-body p-3 d-flex align-items-center gap-2">
                            <div class="bg-light-info p-2 rounded-3 flex-shrink-0">
                                <i class="ph ph-image-square text-info f-s-20"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-0 f-s-13">Foto</h6>
                                <p class="text-muted f-s-10 mb-0">Carica nuova</p>
                            </div>
                            <i class="ph ph-caret-right text-muted f-s-16"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    @endif
</div>
</div>
