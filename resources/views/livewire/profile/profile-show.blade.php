<div>
    <!-- Floating Avatar - Above Everything -->
    <div class="position-relative" style="z-index: 1000;">
        <div class="d-flex justify-content-center">
            <div class="position-relative floating-avatar" style="margin-bottom: -60px;">
                <img alt="{{ $user->name }}" 
                     src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($user) }}"
                     class="rounded-circle border border-4 border-white shadow-lg"
                     style="width: 120px; height: 120px; object-fit: cover; background: white;">
                @if($user->verified_at)
                <span class="position-absolute bottom-0 end-0 bg-white rounded-circle p-2 shadow">
                    <i class="ph ph-check-circle-fill text-success f-s-20"></i>
                </span>
                @endif
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-body p-0">
                    <div class="position-relative overflow-hidden" style="height: 280px;">
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
                <button class="btn {{ $activeTab === 'about' ? 'btn-primary' : 'btn-light-primary' }} flex-shrink-0 btn-sm" 
                        wire:click="setActiveTab('about')">
                    <i class="ph ph-user-circle {{ $activeTab === 'about' ? 'text-white' : 'text-primary' }} me-1"></i>
                    <span class="d-none d-sm-inline {{ $activeTab === 'about' ? 'text-white' : '' }}">{{ __('profile.tab_profile') }}</span>
                </button>
                <button class="btn {{ $activeTab === 'poems' ? 'btn-primary' : 'btn-light-primary' }} flex-shrink-0 btn-sm" 
                        wire:click="setActiveTab('poems')">
                    <i class="ph ph-book-open {{ $activeTab === 'poems' ? 'text-white' : 'text-primary' }} me-1"></i>
                    <span class="d-none d-sm-inline {{ $activeTab === 'poems' ? 'text-white' : '' }}">{{ __('profile.tab_poems') }}</span>
                </button>
                <button class="btn {{ $activeTab === 'events' ? 'btn-primary' : 'btn-light-primary' }} flex-shrink-0 btn-sm" 
                        wire:click="setActiveTab('events')">
                    <i class="ph ph-calendar {{ $activeTab === 'events' ? 'text-white' : 'text-primary' }} me-1"></i>
                    <span class="d-none d-sm-inline {{ $activeTab === 'events' ? 'text-white' : '' }}">{{ __('profile.tab_events') }}</span>
                </button>
                <button class="btn {{ $activeTab === 'media' ? 'btn-primary' : 'btn-light-primary' }} flex-shrink-0 btn-sm" 
                        wire:click="setActiveTab('media')">
                    <i class="ph ph-play-circle {{ $activeTab === 'media' ? 'text-white' : 'text-primary' }} me-1"></i>
                    <span class="d-none d-sm-inline {{ $activeTab === 'media' ? 'text-white' : '' }}">{{ __('profile.tab_media') }}</span>
                </button>
                <button class="btn {{ $activeTab === 'articles' ? 'btn-primary' : 'btn-light-primary' }} flex-shrink-0 btn-sm" 
                        wire:click="setActiveTab('articles')">
                    <i class="ph ph-newspaper {{ $activeTab === 'articles' ? 'text-white' : 'text-primary' }} me-1"></i>
                    <span class="d-none d-sm-inline {{ $activeTab === 'articles' ? 'text-white' : '' }}">{{ __('profile.tab_articles') }}</span>
                </button>
                <button class="btn {{ $activeTab === 'activities' ? 'btn-secondary' : 'btn-light-secondary' }} flex-shrink-0 btn-sm" 
                        wire:click="setActiveTab('activities')">
                    <i class="ph ph-activity {{ $activeTab === 'activities' ? 'text-white' : 'text-secondary' }} me-1"></i>
                    <span class="d-none d-sm-inline {{ $activeTab === 'activities' ? 'text-white' : '' }}">{{ __('profile.tab_activities') }}</span>
                </button>
                @if($isOwnProfile)
                <button class="btn {{ $activeTab === 'settings' ? 'btn-dark' : 'btn-light-dark' }} flex-shrink-0 btn-sm" 
                        wire:click="setActiveTab('settings')">
                    <i class="ph ph-gear {{ $activeTab === 'settings' ? 'text-white' : 'text-dark' }} me-1"></i>
                    <span class="d-none d-sm-inline {{ $activeTab === 'settings' ? 'text-white' : '' }}">{{ __('profile.tab_settings') }}</span>
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
                            <ul class="profile-app-tabs list-unstyled mb-0">
                                <li class="profile-tab-item mb-1 {{ $activeTab === 'about' ? 'active' : '' }}" wire:click="setActiveTab('about')">
                                    <i class="ph ph-user-circle me-2"></i>
                                    <span>{{ __('profile.profile') }}</span>
                                </li>
                                <li class="profile-tab-item mb-1 {{ $activeTab === 'poems' ? 'active' : '' }}" wire:click="setActiveTab('poems')">
                                    <i class="ph ph-scroll me-2"></i>
                                    <span>{{__('profile.poems')}}</span>
                                </li>
                                <li class="profile-tab-item mb-1 {{ $activeTab === 'events' ? 'active' : '' }}" wire:click="setActiveTab('events')">
                                    <i class="ph ph-calendar me-2"></i>
                                    <span>{{__('profile.events')}}</span>
                                </li>
                                <li class="profile-tab-item mb-1 {{ $activeTab === 'media' ? 'active' : '' }}" wire:click="setActiveTab('media')">
                                    <i class="ph ph-video-camera me-2"></i>
                                    <span>{{__('profile.my_media')}}</span>
                                </li>
                                <li class="profile-tab-item mb-1 {{ $activeTab === 'articles' ? 'active' : '' }}" wire:click="setActiveTab('articles')">
                                    <i class="ph ph-article me-2"></i>
                                    <span>{{__('profile.my_articles')}}</span>
                                </li>
                                <li class="profile-tab-item mb-1 {{ $activeTab === 'activities' ? 'active' : '' }}" wire:click="setActiveTab('activities')">
                                    <i class="ph ph-activity me-2"></i>
                                    <span>{{__('profile.my_activities')}}</span>
                                </li>
                                @if($isOwnProfile)
                                <li class="profile-tab-item mb-1 {{ $activeTab === 'settings' ? 'active' : '' }}" wire:click="setActiveTab('settings')">
                                    <i class="ph ph-gear me-2"></i>
                                    <span>{{__('profile.settings')}}</span>
                                </li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    @if($isOwnProfile)
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
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Content - Full width on mobile -->
        <div class="col-12 col-md-8 col-lg-6 mb-4">
            @if($activeTab === 'about')
                <!-- Profile Info Card -->
                <div class="card overflow-hidden mb-4 border-0 shadow-sm">
                    <div class="card-body text-center pt-5 pb-3 px-3">
                        <h3 class="mb-1 f-w-700 f-s-20 f-s-md-28">
                            {{ $user->name }}
                        </h3>
                        @if($user->nickname)
                        <p class="text-muted f-s-14 mb-2">{{ $user->nickname }}</p>
                        @endif
                        @if($user->bio)
                        <p class="text-muted f-s-13 f-s-md-14 mb-0 mt-3">{{ Str::limit($user->bio, 120) }}</p>
                        @endif
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
                                {{ __('profile.badge_in_evidenza') }}
                            </h5>
                            @if($isOwnProfile)
                            <a href="{{ route('profile.my-badges') }}" class="btn btn-sm btn-primary">
                                <i class="ph ph-trophy me-1"></i>
                                <span class="d-none d-sm-inline">{{ __('profile.manage') }}</span> {{ __('profile.trophy') }}
                            </a>
                            @endif
                        </div>
                        <p class="text-muted f-s-12 f-s-md-13 mb-3">
                            @if($isOwnProfile)
                                {{ __('profile.your_featured_badges') }}
                            @else
                                {{ __('profile.user_featured_badges', ['name' => $user->name]) }}
                            @endif
                        </p>
                        
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
                            <div class="row g-3">
                                @forelse($poems as $poem)
                                    <div class="col-12">
                                        <a href="{{ route('poems.show', $poem) }}" class="text-decoration-none">
                                            <div class="card hover-effect h-100 border-0 shadow-sm">
                                                <div class="card-body p-0">
                                                    <div class="row g-0">
                                                        <div class="col-3 col-md-2">
                                                            <img src="{{ \App\Helpers\PoemImageHelper::getPoemImageUrl($poem) }}" 
                                                                 alt="{{ $poem->title ?: __('poems.untitled') }}" 
                                                                 class="w-100 h-100 rounded-start"
                                                                 style="object-fit: cover; min-height: 100px; max-height: 120px;">
                                                        </div>
                                                        <div class="col-9 col-md-10">
                                                            <div class="p-3">
                                                                <h6 class="mb-2 f-w-700 text-dark">{{ $poem->title ?: __('poems.untitled') }}</h6>
                                                                <p class="text-muted f-s-13 mb-2" style="line-height: 1.6;">{{ Str::limit(strip_tags($poem->content), 100) }}</p>
                                                                <div class="d-flex align-items-center gap-3 text-muted f-s-12">
                                                                    <span><i class="ph ph-calendar me-1"></i>{{ $poem->created_at->format('d/m/Y') }}</span>
                                                                    @if($poem->like_count > 0)
                                                                    <span><i class="ph ph-heart me-1"></i>{{ $poem->like_count }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
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
                            @if($poems->hasPages())
                            <div class="mt-4 d-flex justify-content-center">
                                {{ $poems->links('pagination.livewire-bootstrap-4') }}
                            </div>
                            @endif
                        @endif

                        @if($activeTab === 'articles')
                            <h5 class="mb-4 f-w-700">
                                <i class="ph ph-newspaper me-2 text-primary"></i>
                                {{ __('profile.my_articles') }}
                            </h5>
                            <div class="row g-3">
                                @forelse($articles as $article)
                                    <div class="col-12">
                                        <a href="{{ route('articles.show', $article) }}" class="text-decoration-none">
                                            <div class="card hover-effect h-100 border-0 shadow-sm">
                                                <div class="card-body p-0">
                                                    <div class="row g-0">
                                                        <div class="col-4 col-md-3">
                                                            <img src="{{ \App\Helpers\ArticleImageHelper::getArticleImageUrl($article) }}" 
                                                                 alt="{{ is_array($article->title) ? ($article->title[app()->getLocale()] ?? reset($article->title)) : $article->title }}" 
                                                                 class="w-100 h-100 rounded-start"
                                                                 style="object-fit: cover; min-height: 120px; max-height: 150px;">
                                                        </div>
                                                        <div class="col-8 col-md-9">
                                                            <div class="p-3">
                                                                <h6 class="mb-2 f-w-700 text-dark">
                                                                    {{ is_array($article->title) ? ($article->title[app()->getLocale()] ?? reset($article->title)) : $article->title }}
                                                                </h6>
                                                                <p class="text-muted f-s-13 mb-2">
                                                                    {{ Str::limit(strip_tags(is_array($article->content) ? ($article->content[app()->getLocale()] ?? reset($article->content)) : $article->content), 120) }}
                                                                </p>
                                                                <div class="d-flex align-items-center gap-3 text-muted f-s-12">
                                                                    <span><i class="ph ph-calendar me-1"></i>{{ $article->published_at?->format('d/m/Y') ?? $article->created_at->format('d/m/Y') }}</span>
                                                                    @if($article->views_count > 0)
                                                                    <span><i class="ph ph-eye me-1"></i>{{ $article->views_count }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
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
                            @if($articles->hasPages())
                            <div class="mt-4 d-flex justify-content-center">
                                {{ $articles->links('pagination.livewire-bootstrap-4') }}
                            </div>
                            @endif
                        @endif

                        @if($activeTab === 'media')
                            <h5 class="mb-4 f-w-700">
                                <i class="ph ph-images me-2 text-primary"></i>
                                {{ __('profile.my_media') }}
                            </h5>
                            
                            <h6 class="f-w-600 mb-3">{{ __('profile.photos') }}</h6>
                            <div class="row g-3">
                                @forelse($photos as $photo)
                                    <div class="col-md-4 col-sm-6">
                                        <div class="card hover-effect border-0 shadow-sm h-100" style="cursor: pointer;" 
                                             data-bs-toggle="modal" 
                                             data-bs-target="#photoModal{{ $photo->id }}">
                                            <div class="position-relative overflow-hidden">
                                                <img src="{{ $photo->image_url }}" 
                                                     alt="{{ $photo->title }}" 
                                                     class="card-img-top" 
                                                     style="height: 200px; object-fit: cover;">
                                                <div class="position-absolute top-0 end-0 m-2">
                                                    <span class="badge bg-dark bg-opacity-75">
                                                        <i class="ph ph-heart me-1"></i>{{ $photo->like_count ?? 0 }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="card-body p-3">
                                                <h6 class="card-title f-w-600 mb-1 text-dark">{{ Str::limit($photo->title, 40) }}</h6>
                                                <small class="text-muted f-s-12">
                                                    <i class="ph ph-calendar me-1"></i>{{ $photo->created_at->format('d/m/Y') }}
                                                </small>
                                            </div>
                                        </div>
                                        
                                        <!-- Photo Modal -->
                                        <div class="modal fade" id="photoModal{{ $photo->id }}" tabindex="-1">
                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                <div class="modal-content">
                                                    <div class="modal-header border-0">
                                                        <h5 class="modal-title">{{ $photo->title }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body p-0">
                                                        <img src="{{ $photo->image_url }}" alt="{{ $photo->title }}" class="w-100">
                                                    </div>
                                                    @if($photo->description)
                                                    <div class="modal-footer border-0">
                                                        <p class="text-muted mb-0">{{ $photo->description }}</p>
                                                    </div>
                                                    @endif
                                                </div>
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
                            @if($photos->hasPages())
                            <div class="mt-4 d-flex justify-content-center">
                                {{ $photos->links('pagination.livewire-bootstrap-4') }}
                            </div>
                            @endif
                            
                            <h6 class="f-w-600 mb-3 mt-4">{{ __('profile.videos') }}</h6>
                            <div class="row g-3">
                                @forelse($videos as $video)
                                    <div class="col-md-6">
                                        <a href="{{ route('videos.show', $video) }}" class="text-decoration-none">
                                            <div class="card hover-effect border-0 shadow-sm h-100">
                                                <div class="position-relative overflow-hidden">
                                                    @if($video->thumbnail_url)
                                                        <img src="{{ $video->thumbnail_url }}" 
                                                             alt="{{ $video->title }}" 
                                                             class="card-img-top" 
                                                             style="height: 200px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                                            <i class="ph ph-video-camera text-muted" style="font-size: 64px;"></i>
                                                        </div>
                                                    @endif
                                                    <div class="position-absolute top-50 start-50 translate-middle">
                                                        <div class="bg-dark bg-opacity-75 rounded-circle p-3">
                                                            <i class="ph ph-play-fill text-white f-s-32"></i>
                                                        </div>
                                                    </div>
                                                    <div class="position-absolute top-0 end-0 m-2">
                                                        <span class="badge bg-dark bg-opacity-75">
                                                            <i class="ph ph-eye me-1"></i>{{ $video->view_count ?? 0 }}
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="card-body p-3">
                                                    <h6 class="card-title f-w-600 mb-1 text-dark">{{ Str::limit($video->title, 50) }}</h6>
                                                    <small class="text-muted f-s-12">
                                                        <i class="ph ph-calendar me-1"></i>{{ $video->created_at->format('d/m/Y') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </a>
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
                            @if($videos->hasPages())
                            <div class="mt-4 d-flex justify-content-center">
                                {{ $videos->links('pagination.livewire-bootstrap-4') }}
                            </div>
                            @endif
                        @endif

                        @if($activeTab === 'events')
                            <h5 class="mb-4 f-w-700">
                                <i class="ph ph-calendar-check me-2 text-primary"></i>
                                {{ __('profile.events') }}
                            </h5>
                            <div class="row g-3">
                                @forelse($events as $event)
                                    @php
                                        $eventDate = $event->start_datetime ?? $event->event_date;
                                        $isPast = $eventDate ? $eventDate->isPast() : false;
                                    @endphp
                                    <div class="col-12">
                                        <a href="{{ route('events.show', $event) }}" class="text-decoration-none">
                                            <div class="card hover-effect h-100 border-0 shadow-sm {{ $isPast ? 'opacity-75' : '' }}" 
                                                 style="{{ $isPast ? 'filter: grayscale(40%);' : '' }}">
                                                <div class="card-body p-0">
                                                    <div class="row g-0">
                                                        <div class="col-4 col-md-3 position-relative">
                                                            <img src="{{ \App\Helpers\EventImageHelper::getEventImageUrl($event) }}" 
                                                                 alt="{{ $event->title }}" 
                                                                 class="w-100 h-100 rounded-start"
                                                                 style="object-fit: cover; min-height: 120px; max-height: 150px;">
                                                            @if($isPast)
                                                            <span class="position-absolute top-0 start-0 m-2 badge bg-secondary">
                                                                <i class="ph ph-clock-countdown me-1"></i>{{ __('profile.event_past') }}
                                                            </span>
                                                            @endif
                                                        </div>
                                                        <div class="col-8 col-md-9">
                                                            <div class="p-3">
                                                                <h6 class="mb-2 f-w-700 {{ $isPast ? 'text-muted' : 'text-dark' }}">{{ $event->title }}</h6>
                                                                <p class="text-muted f-s-13 mb-2">{{ Str::limit(strip_tags($event->description), 100) }}</p>
                                                                <div class="d-flex align-items-center gap-3 text-muted f-s-12 flex-wrap">
                                                                    <span>
                                                                        <i class="ph ph-calendar me-1"></i>
                                                                        @if($event->start_datetime)
                                                                            {{ $event->start_datetime->format('d/m/Y H:i') }}
                                                                        @elseif($event->event_date)
                                                                            {{ $event->event_date->format('d/m/Y H:i') }}
                                                                        @else
                                                                            {{ __('profile.date_not_available') }}
                                                                        @endif
                                                                    </span>
                                                                    @if($event->city)
                                                                    <span><i class="ph ph-map-pin me-1"></i>{{ $event->city }}</span>
                                                                    @endif
                                                                    @if($event->venue_name)
                                                                    <span><i class="ph ph-buildings me-1"></i>{{ $event->venue_name }}</span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>
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
                            @if($events->hasPages())
                            <div class="mt-4 d-flex justify-content-center">
                                {{ $events->links('pagination.livewire-bootstrap-4') }}
                            </div>
                            @endif
                        @endif

                        @if($activeTab === 'activities')
                            <h5 class="mb-4 f-w-700">
                                <i class="ph ph-lightning me-2 text-primary"></i>
                                {{ __('profile.my_activities') }}
                            </h5>
                            <div class="activities-list">
                                @forelse($activities as $activity)
                                    @php
                                        // Generate link to subject based on type
                                        $link = '#';
                                        if ($activity->subject) {
                                            $link = match($activity->subject_type) {
                                                'App\Models\Poem' => route('poems.show', $activity->subject_id),
                                                'App\Models\Event' => route('events.show', $activity->subject_id),
                                                'App\Models\Article' => route('articles.show', $activity->subject_id),
                                                'App\Models\Video' => route('videos.show', $activity->subject_id),
                                                'App\Models\Photo' => route('photos.show', $activity->subject_id),
                                                default => '#'
                                            };
                                        }
                                    @endphp
                                    <a href="{{ $link }}" class="text-decoration-none d-block">
                                        <div class="activity-item mb-2 p-3 rounded bg-white shadow-sm hover-effect">
                                            <div class="d-flex align-items-start gap-3">
                                                <div class="activity-icon flex-shrink-0">
                                                    <i class="{{ $activity->icon }} f-s-24 text-primary"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <p class="mb-1 f-s-14 f-w-600 text-dark">{{ $activity->formatted_description }}</p>
                                                    <small class="text-muted f-s-12">
                                                        <i class="ph ph-clock me-1"></i>
                                                        {{ $activity->created_at->diffForHumans() }}
                                                    </small>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <i class="ph ph-caret-right text-muted"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @empty
                                    <div class="col-12">
                                        <div class="text-center py-5">
                                            <i class="ph ph-list-dashes text-muted" style="font-size: 48px;"></i>
                                            <p class="text-muted mt-2 f-s-14">{{__('profile.no_activities_available')}}</p>
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                            @if($activities->hasPages())
                            <div class="mt-4 d-flex justify-content-center">
                                {{ $activities->links('pagination.livewire-bootstrap-4') }}
                            </div>
                            @endif
                        @endif

                        @if($activeTab === 'settings')
                            <h5 class="mb-4 f-w-700">
                                <i class="ph ph-gear me-2 text-primary"></i>
                                {{ __('profile.settings') }}
                            </h5>
                            <div class="row g-3">
                                <div class="col-12 col-sm-6 col-lg-4">
                                    <a href="{{ route('profile.edit') }}" class="text-decoration-none">
                                        <div class="card hover-effect h-100">
                                            <div class="card-body p-3 d-flex align-items-center gap-3">
                                                <div class="bg-light-primary p-3 rounded-3">
                                                    <i class="ph-duotone ph-user-circle text-primary f-s-28"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-bold mb-1">{{__('profile.edit_profile')}}</h6>
                                                    <p class="text-muted f-s-12 mb-0">{{__('profile.info_personali')}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-12 col-sm-6 col-lg-4">
                                    <a href="{{ route('profile.my-badges') }}" class="text-decoration-none">
                                        <div class="card hover-effect h-100">
                                            <div class="card-body p-3 d-flex align-items-center gap-3">
                                                <div class="bg-light-warning p-3 rounded-3">
                                                    <i class="ph-duotone ph-medal-military text-warning f-s-28"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-bold mb-1">{{__('profile.manage_badges')}}</h6>
                                                    <p class="text-muted f-s-12 mb-0">{{__('profile.badge_e_trophy')}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-12 col-sm-6 col-lg-4">
                                    <a href="{{ route('profile.languages.index') }}" class="text-decoration-none">
                                        <div class="card hover-effect h-100">
                                            <div class="card-body p-3 d-flex align-items-center gap-3">
                                                <div class="bg-light-info p-3 rounded-3">
                                                    <i class="ph-duotone ph-globe text-info f-s-28"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-bold mb-1">{{__('profile.manage_languages')}}</h6>
                                                    <p class="text-muted f-s-12 mb-0">{{__('profile.gestisci_lingue')}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-12 col-sm-6 col-lg-4">
                                    <a href="{{ route('profile.media') }}" class="text-decoration-none">
                                        <div class="card hover-effect h-100">
                                            <div class="card-body p-3 d-flex align-items-center gap-3">
                                                <div class="bg-light-success p-3 rounded-3">
                                                    <i class="ph-duotone ph-video-camera text-success f-s-28"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-bold mb-1">{{__('profile.my_media')}}</h6>
                                                    <p class="text-muted f-s-12 mb-0">{{__('profile.foto_e_video')}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-12 col-sm-6 col-lg-4">
                                    <a href="{{ route('profile.activity') }}" class="text-decoration-none">
                                        <div class="card hover-effect h-100">
                                            <div class="card-body p-3 d-flex align-items-center gap-3">
                                                <div class="bg-light-secondary p-3 rounded-3">
                                                    <i class="ph-duotone ph-lightning text-secondary f-s-28"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-bold mb-1">{{__('profile.view_all_activities')}}</h6>
                                                    <p class="text-muted f-s-12 mb-0">{{__('profile.vedi_tutte')}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-12 col-sm-6 col-lg-4">
                                    <a href="{{ route('articles.create') }}" class="text-decoration-none">
                                        <div class="card hover-effect h-100">
                                            <div class="card-body p-3 d-flex align-items-center gap-3">
                                                <div class="bg-light-info p-3 rounded-3">
                                                    <i class="ph-duotone ph-article text-info f-s-28"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-bold mb-1">{{__('profile.create_article')}}</h6>
                                                    <p class="text-muted f-s-12 mb-0">{{__('profile.scrivi_articolo')}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                @if($user->hasRole('poet'))
                                <div class="col-12 col-sm-6 col-lg-4">
                                    <a href="{{ route('poems.create') }}" class="text-decoration-none">
                                        <div class="card hover-effect h-100">
                                            <div class="card-body p-3 d-flex align-items-center gap-3">
                                                <div class="bg-light-primary p-3 rounded-3">
                                                    <i class="ph-duotone ph-pen-nib text-primary f-s-28"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-bold mb-1">{{__('profile.create_poem')}}</h6>
                                                    <p class="text-muted f-s-12 mb-0">{{__('profile.write_poem')}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endif

                                @if($user->hasRole('organizer'))
                                <div class="col-12 col-sm-6 col-lg-4">
                                    <a href="{{ route('events.create') }}" class="text-decoration-none">
                                        <div class="card hover-effect h-100">
                                            <div class="card-body p-3 d-flex align-items-center gap-3">
                                                <div class="bg-light-warning p-3 rounded-3">
                                                    <i class="ph-duotone ph-calendar-plus text-warning f-s-28"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-bold mb-1">{{__('profile.create_event')}}</h6>
                                                    <p class="text-muted f-s-12 mb-0">{{__('profile.organize_event')}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endif

                                @if($user->hasRole('venue'))
                                <div class="col-12 col-sm-6 col-lg-4">
                                    <a href="{{ route('venues.create') }}" class="text-decoration-none">
                                        <div class="card hover-effect h-100">
                                            <div class="card-body p-3 d-flex align-items-center gap-3">
                                                <div class="bg-light-secondary p-3 rounded-3">
                                                    <i class="ph-duotone ph-buildings text-secondary f-s-28"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-bold mb-1">{{__('profile.create_venue')}}</h6>
                                                    <p class="text-muted f-s-12 mb-0">{{__('profile.add_venue')}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endif

                                <div class="col-12 col-sm-6 col-lg-4">
                                    <a href="{{ route('videos.upload') }}" class="text-decoration-none">
                                        <div class="card hover-effect h-100">
                                            <div class="card-body p-3 d-flex align-items-center gap-3">
                                                <div class="bg-light-danger p-3 rounded-3">
                                                    <i class="ph-duotone ph-upload text-danger f-s-28"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-bold mb-1">{{__('profile.upload_video')}}</h6>
                                                    <p class="text-muted f-s-12 mb-0">{{__('profile.upload_video_desc')}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>

                                <div class="col-12 col-sm-6 col-lg-4">
                                    <a href="{{ route('photos.create') }}" class="text-decoration-none">
                                        <div class="card hover-effect h-100">
                                            <div class="card-body p-3 d-flex align-items-center gap-3">
                                                <div class="bg-light-info p-3 rounded-3">
                                                    <i class="ph-duotone ph-image-square text-info f-s-28"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-bold mb-1">{{__('profile.upload_photo')}}</h6>
                                                    <p class="text-muted f-s-12 mb-0">{{__('profile.upload_photo_desc')}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
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

