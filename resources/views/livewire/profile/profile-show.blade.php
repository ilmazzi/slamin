<div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    {{ $user->getBannerImageUrlAttribute() }}
                </div>
            </div>
        </div>
        <!-- Left Sidebar -->
        <div class="col-lg-3 col-md-4 mb-4">
            <div class="card">
                <div class="card-body">
                    <!-- Navigation -->
                    <div class="mb-4">
                        <div class="tab-wrapper">
                            <ul class="profile-app-tabs">
                                <li class="tab-link fw-medium f-s-16 f-w-600 {{ $activeTab === 'about' ? 'active' : '' }}" 
                                    wire:click="setActiveTab('about')">
                                    <x-icon name="profile" size="20" class="me-2" />
                                    Profilo
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
                        <div class="d-grid gap-2">
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm">
                                <i class="ph ph-pencil me-2"></i>{{__('profile.edit_profile')}}
                            </a>
                            <a href="{{ route('profile.videos') }}" class="btn btn-success btn-sm">
                                <i class="ph ph-video-camera me-2"></i>{{__('profile.manage_videos')}}
                            </a>
                            <a href="{{ route('profile.photos') }}" class="btn btn-info btn-sm">
                                <i class="ph ph-image me-2"></i>{{__('profile.my_photos')}}
                            </a>
                            <a href="{{ route('articles.create') }}" class="btn btn-warning btn-sm">
                                <i class="ph ph-article me-2"></i>{{__('profile.create_article')}}
                            </a>
                            <a href="{{ route('profile.activity') }}" class="btn btn-secondary btn-sm">
                                <i class="ph ph-lightning me-2"></i>{{__('profile.view_all_activities')}}
                            </a>
                            <a href="{{ route('profile.languages.index') }}" class="btn btn-outline-primary btn-sm">
                                <i class="ph ph-globe me-2"></i>{{__('languages.manage_languages')}}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-6 col-md-8 mb-4">
            @if($activeTab === 'about')
                <!-- Elegant Profile Card with Badges -->
                <div class="card overflow-hidden mb-4">
                    <div class="card-body p-0">
                        <div class="profile-call-box bg-gradient-mode position-relative" style="height: 450px;">
                            <!-- Display Name at Top -->
                            <div class="position-absolute w-100 text-center text-primary fw-bold f-s-24" style="top: 20px; z-index: 10;">
                                {{ $user->getDisplayName() }}
                                @if($user->verified_at)
                                    <i class="ph ph-check-circle-fill text-success ms-1" title="Verificato"></i>
                                @endif
                            </div>

                            <!-- Solar System Container -->
                            <div class="meeting-call-box position-relative d-flex flex-column align-items-center justify-content-center" style="height: 400px; margin-top: 50px;">
                                <!-- Orbits -->
                                <div class="orbit-path orbit-1"></div>
                                <div class="orbit-path orbit-2"></div>
                                <div class="orbit-path orbit-3"></div>
                                
                                <!-- Central Avatar -->
                                <div class="position-absolute" style="top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 5;">
                                    <div class="d-flex-center b-r-50 overflow-hidden bg-white shadow" style="width: 100px; height: 100px;">
                                        <img alt="{{ $user->name }}" class="img-fluid" 
                                             src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($user) }}"
                                             style="width: 100%; height: 100%; object-fit: cover;">
                                    </div>
                                </div>
                                
                                <!-- Rotating Badges -->
                                @if(isset($topBadges[0]) && $topBadges[0]->badge)
                                <div class="badge-container badge-orbit-1">
                                    <img src="{{ $topBadges[0]->badge->icon_url ?? asset('assets/images/badge/default.png') }}" 
                                         alt="{{ $topBadges[0]->badge->name }}" 
                                         style="width: 60px; height: 60px; border-radius: 50%;">
                                    <div class="badge-name">{{ $topBadges[0]->badge->name }}</div>
                                </div>
                                @endif
                                
                                @if(isset($topBadges[1]) && $topBadges[1]->badge)
                                <div class="badge-container badge-orbit-2">
                                    <img src="{{ $topBadges[1]->badge->icon_url ?? asset('assets/images/badge/default.png') }}" 
                                         alt="{{ $topBadges[1]->badge->name }}" 
                                         style="width: 60px; height: 60px; border-radius: 50%;">
                                    <div class="badge-name">{{ $topBadges[1]->badge->name }}</div>
                                </div>
                                @endif
                                
                                @if(isset($topBadges[2]) && $topBadges[2]->badge)
                                <div class="badge-container badge-orbit-3">
                                    <img src="{{ $topBadges[2]->badge->icon_url ?? asset('assets/images/badge/default.png') }}" 
                                         alt="{{ $topBadges[2]->badge->name }}" 
                                         style="width: 60px; height: 60px; border-radius: 50%;">
                                    <div class="badge-name">{{ $topBadges[2]->badge->name }}</div>
                                </div>
                                @endif
                                
                                <!-- Name and Nickname Below Avatar -->
                                <div class="position-absolute text-center" style="top: 65%; left: 50%; transform: translate(-50%, 0); z-index: 10;">
                                    <div class="text-white-50 f-s-14">@{{ $user->nickname }}</div>
                                    @if($user->bio)
                                    <div class="text-white-75 f-s-12 mt-1" style="max-width: 250px;">{{ Str::limit($user->bio, 60) }}</div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Badge Info at Bottom -->
                            <div class="position-absolute w-100 text-center" style="bottom: 15px; z-index: 10;">
                                <div class="d-flex justify-content-center gap-4 flex-wrap">
                                    <div class="text-white">
                                        <i class="ph ph-medal f-s-20"></i>
                                        <span class="ms-1 fw-medium">{{ $badgesCount }} Badge</span>
                                    </div>
                                    <div class="text-white">
                                        <i class="ph ph-star f-s-20"></i>
                                        <span class="ms-1 fw-medium">{{ $totalPoints }} Punti</span>
                                    </div>
                                    <div class="text-white">
                                        <i class="ph ph-ranking f-s-20"></i>
                                        <span class="ms-1 fw-medium">Livello {{ $level }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Stats Card -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h6 class="f-w-600 mb-3">
                            <i class="ph ph-chart-bar me-2"></i>{{__('profile.statistics')}}
                        </h6>
                        <div class="d-flex justify-content-center gap-4">
                            <div class="text-center">
                                <div class="f-w-600 f-s-16">{{ $stats['photos'] + $stats['videos'] + $stats['articles'] + $stats['poems'] }}</div>
                                <small class="text-muted f-s-12">{{__('profile.posts')}}</small>
                            </div>
                            <div class="text-center">
                                <div class="f-w-600 f-s-16">{{ $stats['followers'] }}</div>
                                <small class="text-muted f-s-12">{{__('profile.followers')}}</small>
                            </div>
                            <div class="text-center">
                                <div class="f-w-600 f-s-16">{{ $stats['following'] }}</div>
                                <small class="text-muted f-s-12">{{__('profile.following')}}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Level Card -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="f-w-600 mb-1">
                                        <i class="ph ph-waves me-2"></i>{{__('profile.level')}}
                                </h6>
                                <div class="f-s-14 text-muted">{{__('profile.apprentice')}}</div>
                                <div class="f-s-16 f-w-600">{{__('profile.level')}} {{ $level }}</div>
                                <div class="f-s-14 text-muted">
                                    <i class="ph ph-diamond me-1"></i>{{ $totalPoints }} {{__('profile.points')}}
                                    <i class="ph ph-medal me-1 ms-2"></i>{{ $badgesCount }} {{__('profile.badge')}}
                                </div>
                            </div>
                            <button class="btn btn-outline-primary btn-sm">{{__('profile.manage')}}</button>
                        </div>
                    </div>
                </div>

                <!-- Badges Card with Meeting Call Style -->
                @if($topBadges->count() > 0)
                    <div class="card mb-4">
                        <div class="card-body">
                            <h6 class="f-w-600 mb-3">
                                <i class="ph ph-trophy me-2"></i>{{__('profile.badge')}}
                            </h6>
                            <div class="row">
                                @foreach($topBadges->take(6) as $badge)
                                    <div class="col-md-4 col-sm-6 mb-3">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-light-primary d-flex-center p-2 w-40 h-40 b-r-8 me-3">
                                                <img alt="{{ $badge->name }}" class="img-fluid" 
                                                     src="{{ $badge->icon_url ?? asset('assets/images/badges/default-badge.png') }}"
                                                     style="width: 24px; height: 24px; object-fit: cover;">
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="text-dark-800 mb-0 f-w-500 f-s-14 txt-ellipsis-1">{{ $badge->name }}</p>
                                                <small class="text-muted f-s-12">{{ $badge->description ?? 'Badge speciale' }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- About Card -->
                <div class="card">
                    <div class="card-body">
                        <h6 class="f-w-600 mb-3">{{__('profile.about_me')}}</h6>
                        @if($user->bio)
                            <p class="f-s-14 text-muted">{{ $user->bio }}</p>
                        @else
                            <p class="f-s-14 text-muted fst-italic">{{__('profile.no_bio_available')}}</p>
                        @endif
                    </div>
                </div>
            @else
                <!-- Other Tabs Content -->
                <div class="card">
                    <div class="card-body">
                        @if($activeTab === 'photos')
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
                        @endif

                        @if($activeTab === 'videos')
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
                            {{ $videos->links() }}
                        @endif

                        @if($activeTab === 'articles')
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

                        @if($activeTab === 'poems')
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
                    </div>
                </div>
            @endif
        </div>

        <!-- Right Sidebar -->
        <div class="col-lg-3 col-md-12 mb-4">
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

