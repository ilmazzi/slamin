<div>
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body p-0">
                    <div class="position-relative overflow-hidden" style="height: 200px;">
                        <img src="{{ $user->getBannerImageUrlAttribute() }}" 
                             alt="{{ $user->getDisplayName() }}" 
                             class="w-100 h-100" 
                             style="object-fit: cover;">
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Mobile Navigation - Horizontal Tabs (visible only on mobile) -->
        <div class="col-12 d-md-none mb-3">
            <div class="d-flex gap-2 overflow-auto pb-2" style="scrollbar-width: none;">
                <button class="btn {{ $activeTab === 'about' ? 'btn-primary' : 'btn-outline-secondary' }} flex-shrink-0 btn-sm" 
                        wire:click="setActiveTab('about')">
                    <i class="ph ph-user-circle me-1"></i>
                    <span class="d-none d-sm-inline">Profilo</span>
                </button>
                <button class="btn {{ $activeTab === 'poems' ? 'btn-primary' : 'btn-outline-secondary' }} flex-shrink-0 btn-sm" 
                        wire:click="setActiveTab('poems')">
                    <i class="ph ph-book-open me-1"></i>
                    <span class="d-none d-sm-inline">Poesie</span>
                </button>
                <button class="btn {{ $activeTab === 'events' ? 'btn-primary' : 'btn-outline-secondary' }} flex-shrink-0 btn-sm" 
                        wire:click="setActiveTab('events')">
                    <i class="ph ph-calendar me-1"></i>
                    <span class="d-none d-sm-inline">Eventi</span>
                </button>
                <button class="btn {{ $activeTab === 'media' ? 'btn-primary' : 'btn-outline-secondary' }} flex-shrink-0 btn-sm" 
                        wire:click="setActiveTab('media')">
                    <i class="ph ph-play-circle me-1"></i>
                    <span class="d-none d-sm-inline">Media</span>
                </button>
                <button class="btn {{ $activeTab === 'articles' ? 'btn-primary' : 'btn-outline-secondary' }} flex-shrink-0 btn-sm" 
                        wire:click="setActiveTab('articles')">
                    <i class="ph ph-newspaper me-1"></i>
                    <span class="d-none d-sm-inline">Articoli</span>
                </button>
                <button class="btn {{ $activeTab === 'activities' ? 'btn-primary' : 'btn-outline-secondary' }} flex-shrink-0 btn-sm" 
                        wire:click="setActiveTab('activities')">
                    <i class="ph ph-activity me-1"></i>
                    <span class="d-none d-sm-inline">Attività</span>
                </button>
                @if($isOwnProfile)
                <button class="btn {{ $activeTab === 'settings' ? 'btn-primary' : 'btn-outline-secondary' }} flex-shrink-0 btn-sm" 
                        wire:click="setActiveTab('settings')">
                    <i class="ph ph-gear me-1"></i>
                    <span class="d-none d-sm-inline">Impostazioni</span>
                </button>
                @endif
            </div>
        </div>
        
        <!-- Left Sidebar - Hidden on Mobile -->
        <div class="col-lg-3 col-md-4 mb-4 d-none d-md-block">
            <div class="card">
                <div class="card-body">
                    <!-- Navigation -->
                    <div class="mb-4">
                        <div class="tab-wrapper">
                            <ul class="profile-app-tabs">
                                <li class="tab-link fw-medium f-s-16 f-w-600 {{ $activeTab === 'about' ? 'active' : '' }}" 
                                    wire:click="setActiveTab('about')">
                                    <x-icon name="profile" size="20" class="me-2" />
                                    {{ __('profile.profile') }}
                                </li>
                                <li class="tab-link fw-medium f-s-16 f-w-600 {{ $activeTab === 'poems' ? 'active' : '' }}" 
                                    wire:click="setActiveTab('poems')">
                                    <x-icon name="poetry" size="20" class="me-2" />
                                    {{__('profile.poems')}}
                                </li>
                                <li class="tab-link fw-medium f-s-16 f-w-600 {{ $activeTab === 'events' ? 'active' : '' }}" 
                                    wire:click="setActiveTab('events')">
                                    <x-icon name="event" size="20" class="me-2" />
                                    {{__('profile.events')}}
                                    <span class="badge rounded-pill bg-success badge-notification">
                                        1
                                        <span class="visually-hidden">unread messages</span>
                                    </span>
                                </li>
                                <li class="tab-link fw-medium f-s-16 f-w-600 {{ $activeTab === 'media' ? 'active' : '' }}" 
                                    wire:click="setActiveTab('media')">
                                    <x-icon name="media" size="20" class="me-2" />
                                    {{__('profile.my_media')}}
                                    <span class="badge rounded-pill bg-primary badge-notification">
                                        2
                                        <span class="visually-hidden">unread messages</span>
                                    </span>
                                </li>
                                <li class="tab-link fw-medium f-s-16 f-w-600 {{ $activeTab === 'articles' ? 'active' : '' }}" 
                                    wire:click="setActiveTab('articles')">
                                    <x-icon name="article" size="20" class="me-2" />
                                    {{__('profile.my_articles')}}
                                </li>
                                <li class="tab-link fw-medium f-s-16 f-w-600 {{ $activeTab === 'activities' ? 'active' : '' }}" 
                                    wire:click="setActiveTab('activities')">
                                    <x-icon name="activity" size="20" class="me-2" />
                                    {{__('profile.my_activities')}}
                                    <span class="badge rounded-pill bg-warning badge-notification">
                                        10+
                                        <span class="visually-hidden">unread messages</span>
                                    </span>
                                </li>
                                <li class="tab-link fw-medium f-s-16 f-w-600 {{ $activeTab === 'settings' ? 'active' : '' }}" 
                                    wire:click="setActiveTab('settings')">
                                    <x-icon name="settings" size="20" class="me-2" />
                                    {{__('profile.settings')}}
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div>
                        <h6 class="f-w-600 mb-3">{{__('profile.quick_actions')}}</h6>
                        <div class="row g-2">
                            <div class="col-6">
                                <a href="{{ route('profile.edit') }}" class="text-decoration-none">
                                    <div class="card text-center hover-effect">
                                        <div class="card-body p-3">
                                            <i class="ph-duotone ph-pencil f-s-28 text-primary mb-2"></i>
                                            <p class="mb-0 f-s-12 text-dark">{{__('profile.edit_profile')}}</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('profile.media') }}" class="text-decoration-none">
                                    <div class="card text-center hover-effect">
                                        <div class="card-body p-3">
                                            <i class="ph-duotone ph-video-camera f-s-28 text-success mb-2"></i>
                                            <p class="mb-0 f-s-12 text-dark">{{__('profile.my_media')}}</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('articles.create') }}" class="text-decoration-none">
                                    <div class="card text-center hover-effect">
                                        <div class="card-body p-3">
                                            <i class="ph-duotone ph-article f-s-28 text-warning mb-2"></i>
                                            <p class="mb-0 f-s-12 text-dark">{{__('profile.create_article')}}</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('profile.activity') }}" class="text-decoration-none">
                                    <div class="card text-center hover-effect">
                                        <div class="card-body p-3">
                                            <i class="ph-duotone ph-lightning f-s-28 text-secondary mb-2"></i>
                                            <p class="mb-0 f-s-12 text-dark">{{__('profile.view_all_activities')}}</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-6">
                                <a href="{{ route('profile.languages.index') }}" class="text-decoration-none">
                                    <div class="card text-center hover-effect">
                                        <div class="card-body p-3">
                                            <i class="ph-duotone ph-globe f-s-28 text-primary mb-2"></i>
                                            <p class="mb-0 f-s-12 text-dark">{{__('profile.manage_languages')}}</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content - Full width on mobile -->
        <div class="col-12 col-md-8 col-lg-6 mb-4">
            @if($activeTab === 'about')
                <!-- Modern Profile Header Card -->
                <div class="card overflow-hidden mb-4 border-0 shadow-sm">
                    <div class="card-body p-0">
                        <!-- Banner -->
                        <div class="profile-banner position-relative" style="height: 180px; background: rgba(var(--primary), 1);">
                            <!-- Avatar - Responsive Size -->
                            <div class="position-absolute" style="bottom: -40px; left: 50%; transform: translateX(-50%); z-index: 10;">
                                <div class="avatar-circle">
                                    <img alt="{{ $user->name }}" 
                                         src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($user) }}"
                                         class="rounded-circle border border-3 border-white shadow"
                                         style="width: 90px; height: 90px; object-fit: cover;">
                                    @if($user->verified_at)
                                    <span class="position-absolute bottom-0 end-0 bg-white rounded-circle p-1">
                                        <i class="ph ph-check-circle-fill text-success f-s-18"></i>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Stats Bar - Responsive -->
                            <div class="position-absolute w-100" style="bottom: 10px; z-index: 5;">
                                <div class="d-flex justify-content-center gap-2 gap-md-4">
                                    <div class="text-white text-center">
                                        <div class="f-w-700 f-s-16 f-s-md-20">{{ $badgesCount }}</div>
                                        <small class="opacity-75 f-s-10 f-s-md-12">{{ __('profile.badge') }}</small>
                                    </div>
                                    <div class="text-white text-center">
                                        <div class="f-w-700 f-s-16 f-s-md-20">{{ $totalPoints }}</div>
                                        <small class="opacity-75 f-s-10 f-s-md-12">{{ __('profile.points') }}</small>
                                    </div>
                                    <div class="text-white text-center">
                                        <div class="f-w-700 f-s-16 f-s-md-20">{{ $level }}</div>
                                        <small class="opacity-75 f-s-10 f-s-md-12">{{ __('profile.level') }}</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- User Info Below Banner -->
                        <div class="text-center pt-5 pb-3 px-3 px-md-4" style="margin-top: 40px;">
                            <h3 class="mb-1 f-w-700 f-s-18 f-s-md-24">
                                {{ $user->getDisplayName() }}
                            </h3>
                            @if($user->bio)
                            <p class="text-muted f-s-13 f-s-md-14 mb-0">{{ Str::limit($user->bio, 120) }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- About Card -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h6 class="f-w-600 mb-3">{{__('profile.about_me')}}</h6>
                        @if($user->bio)
                            <p class="f-s-14 text-muted">{{ $user->bio }}</p>
                        @else
                            <p class="f-s-14 text-muted fst-italic">{{__('profile.no_bio_available')}}</p>
                        @endif
                    </div>
                </div>


                <!-- Badge Showcase - Stack Cards -->
                <div class="card mb-4 border-0 shadow-sm">
                    <div class="card-body p-3 p-md-4">
                        <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                            <h5 class="mb-0 f-w-700 f-s-16">
                                <i class="ph ph-medal-military me-2 text-primary"></i>
                                Badge in Evidenza
                            </h5>
                            <a href="{{ route('profile.my-badges') }}" class="btn btn-sm btn-primary">
                                <i class="ph ph-trophy me-1"></i>
                                <span class="d-none d-sm-inline">Vedi</span> Trophy
                            </a>
                        </div>
                        <p class="text-muted f-s-12 f-s-md-13 mb-3">I tuoi 3 badge preferiti - gestiscili per scegliere quali mostrare</p>
                        
                        <div class="overflow-hidden">
                            @livewire('profile.badge-display-stack-cards', ['user' => $user])
                        </div>
                    </div>
                </div>

            @else
                <!-- Other Tabs Content -->
                <div class="card">
                    <div class="card-body">
                        @if($activeTab === 'poems')
                            <h5 class="mb-4 f-w-700">
                                <i class="ph ph-book-open me-2 text-primary"></i>
                                {{ __('profile.poems') }}
                            </h5>
                            <div class="row">
                                @forelse($poems as $poem)
                                    <div class="col-12 mb-3">
                                        <div class="card hover-effect">
                                            <div class="card-body">
                                                <h5 class="card-title f-w-600">{{ $poem->title }}</h5>
                                                <p class="card-text f-s-14">{{ Str::limit($poem->content, 150) }}</p>
                                                <small class="text-muted f-s-12">{{ $poem->created_at->format('d/m/Y') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="text-center py-5">
                                            <i class="ph ph-book text-muted" style="font-size: 48px;"></i>
                                            <p class="text-muted mt-2 f-s-14">{{__('profile.no_poems_available')}}</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                            {{ $poems->links() }}
                        @endif

                        @if($activeTab === 'articles')
                            <h5 class="mb-4 f-w-700">
                                <i class="ph ph-newspaper me-2 text-primary"></i>
                                {{ __('profile.my_articles') }}
                            </h5>
                            <div class="row">
                                @forelse($articles as $article)
                                    <div class="col-12 mb-3">
                                        <div class="card hover-effect">
                                            <div class="card-body">
                                                <h5 class="card-title f-w-600">{{ $article->title }}</h5>
                                                <p class="card-text f-s-14">{{ Str::limit($article->content, 150) }}</p>
                                                <small class="text-muted f-s-12">{{ $article->created_at->format('d/m/Y') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="text-center py-5">
                                            <i class="ph ph-article text-muted" style="font-size: 48px;"></i>
                                            <p class="text-muted mt-2 f-s-14">{{__('profile.no_articles_available')}}</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                            {{ $articles->links() }}
                        @endif

                        @if($activeTab === 'media')
                            <h5 class="mb-4 f-w-700">
                                <i class="ph ph-images me-2 text-primary"></i>
                                {{ __('profile.my_media') }}
                            </h5>
                            
                            <h6 class="f-w-600 mb-3">{{ __('profile.photos') }}</h6>
                            <div class="row">
                                @forelse($photos as $photo)
                                    <div class="col-md-4 mb-3">
                                        <div class="card hover-effect">
                                            <img src="{{ $photo->image_url }}" alt="{{ $photo->title }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                                            <div class="card-body">
                                                <h6 class="card-title f-w-600">{{ $photo->title }}</h6>
                                                <p class="card-text text-muted f-s-12">{{ $photo->description }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="text-center py-5">
                                            <i class="ph ph-image text-muted" style="font-size: 48px;"></i>
                                            <p class="text-muted mt-2 f-s-14">{{__('profile.no_photos_available')}}</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                            {{ $photos->links() }}
                            
                            <h6 class="f-w-600 mb-3 mt-4">{{ __('profile.videos') }}</h6>
                            <div class="row">
                                @forelse($videos as $video)
                                    <div class="col-md-6 mb-3">
                                        <div class="card hover-effect">
                                            <video class="card-img-top" style="height: 200px; object-fit: cover;" controls>
                                                <source src="{{ $video->video_url }}" type="video/mp4">
                                            </video>
                                            <div class="card-body">
                                                <h6 class="card-title f-w-600">{{ $video->title }}</h6>
                                                <p class="card-text text-muted f-s-12">{{ $video->description }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="text-center py-5">
                                            <i class="ph ph-video text-muted" style="font-size: 48px;"></i>
                                            <p class="text-muted mt-2 f-s-14">{{__('profile.no_videos_available')}}</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        @endif

                        @if($activeTab === 'events')
                            <h5 class="mb-4 f-w-700">
                                <i class="ph ph-calendar-check me-2 text-primary"></i>
                                {{ __('profile.events') }}
                            </h5>
                            <div class="row">
                                @forelse($events as $event)
                                    <div class="col-12 mb-3">
                                        <div class="card hover-effect">
                                            <div class="card-body">
                                                <h5 class="card-title f-w-600">{{ $event->title }}</h5>
                                                <p class="card-text f-s-14">{{ Str::limit($event->description, 150) }}</p>
                                                <small class="text-muted f-s-12">{{ $event->event_date->format('d/m/Y H:i') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="text-center py-5">
                                            <i class="ph ph-calendar-x text-muted" style="font-size: 48px;"></i>
                                            <p class="text-muted mt-2 f-s-14">{{__('profile.no_events_available')}}</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                            {{ $events->links() }}
                        @endif

                        @if($activeTab === 'activities')
                            <h5 class="mb-4 f-w-700">
                                <i class="ph ph-lightning me-2 text-primary"></i>
                                {{ __('profile.my_activities') }}
                            </h5>
                            <div class="activities-list">
                                @forelse($activities as $activity)
                                    <div class="activity-item mb-3 p-3 rounded" style="background: #f8f9fa;">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="activity-icon">
                                                <i class="ph ph-{{ $this->getActivityIcon($activity->type) }} f-s-24 text-primary"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 f-w-600">{{ $activity->description }}</h6>
                                                <small class="text-muted">
                                                    <i class="ph ph-clock me-1"></i>
                                                    {{ $activity->created_at->diffForHumans() }}
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="text-center py-5">
                                            <i class="ph ph-list-dashes text-muted" style="font-size: 48px;"></i>
                                            <p class="text-muted mt-2 f-s-14">{{__('profile.no_activities_available')}}</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                            {{ $activities->links() }}
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Sidebar - Hidden on mobile -->
        <div class="col-lg-3 col-md-12 mb-4 d-none d-md-block">
            <!-- Statistics Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <h6 class="f-w-600 mb-3">{{__('profile.statistics')}}</h6>
                    <div class="row g-2">
                        <div class="col-6">
                            <div class="text-center p-3 rounded" style="background-color: rgba(var(--success), 0.1);">
                                <i class="ph ph-book mb-2" style="font-size: 24px; color: rgba(var(--success), 1);"></i>
                                <div class="f-w-600 f-s-16" style="color: rgba(var(--success), 1);">{{ $stats['poems'] }}</div>
                                <small class="text-muted f-s-12">{{__('profile.poems')}}</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 rounded" style="background-color: rgba(var(--success), 0.1);">
                                <i class="ph ph-calendar mb-2" style="font-size: 24px; color: rgba(var(--success), 1);"></i>
                                <div class="f-w-600 f-s-16" style="color: rgba(var(--success), 1);">1</div>
                                <small class="text-muted f-s-12">{{__('profile.events_general')}}</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 rounded" style="background-color: rgba(var(--warning), 0.1);">
                                <i class="ph ph-article mb-2" style="font-size: 24px; color: rgba(var(--warning), 1);"></i>
                                <div class="f-w-600 f-s-16" style="color: rgba(var(--warning), 1);">{{ $stats['articles'] }}</div>
                                <small class="text-muted f-s-12">{{__('profile.articles')}}</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 rounded" style="background-color: rgba(var(--info), 0.1);">
                                <i class="ph ph-map-pin mb-2" style="font-size: 24px; color: rgba(var(--info), 1);"></i>
                                <div class="f-w-600 f-s-16" style="color: rgba(var(--info), 1);">1</div>
                                <small class="text-muted f-s-12">{{__('profile.venue')}}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($isOwnProfile)
            <!-- Badge Management Card -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="f-w-600 mb-0">{{ __('profile.my_badges') }}</h6>
                        <a href="{{ route('profile.my-badges') }}" class="btn btn-sm btn-primary">
                            <i class="ph ph-trophy me-1"></i>
                            {{ __('profile.manage') }}
                        </a>
                    </div>
                    @livewire('profile.badge-display-sidebar', ['user' => $user])
                </div>
            </div>
            @endif

            <!-- Participated Events Card -->
            <div class="card">
                <div class="card-body">
                        <h6 class="f-w-600 mb-3">{{__('profile.participated_events')}}</h6>
                    <div class="text-center py-4">
                        <i class="ph ph-calendar text-muted" style="font-size: 48px; opacity: 0.3;"></i>
                        <p class="text-muted mt-2 f-s-14">{{__('profile.no_participated_events')}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

