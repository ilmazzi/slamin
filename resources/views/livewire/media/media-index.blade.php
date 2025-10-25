<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">
                <i class="ph ph-video-camera me-2"></i>
                {{ __('media.media_section') }}
            </h4>
        </div>
    </div>

    <!-- Prima Riga: {{ __('media.most_popular_video') }} + 6 Video con Switch -->
    <div class="row mb-4">
        <!-- {{ __('media.most_popular_video') }} (Grande) -->
        <div class="col-lg-8">
            <div class="card hover-effect">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph-duotone ph-trophy me-2"></i>
                        {{ __('media.most_popular_video') }}
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($mostPopularVideo)
                        <div class="position-relative">
                            @if($mostPopularVideo->thumbnail_url && $mostPopularVideo->thumbnail_url !== asset('assets/images/placeholder/placholder-1.jpg'))
                                <div class="position-relative" style="cursor: pointer;" onclick="Livewire.dispatch('openVideoModal', { videoId: {{ $mostPopularVideo->id }} })">
                                    <img src="{{ $mostPopularVideo->thumbnail_url }}" alt="{{ $mostPopularVideo->title }}" class="card-img-top" style="height: 400px; object-fit: cover;">
                                    <div class="position-absolute top-50 start-50 translate-middle">
                                        <div class="play-button bg-white rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                                            <i class="ph-duotone ph-play f-s-32 text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="position-absolute bottom-0 start-0 end-0 p-3" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                                        <small class="text-white f-s-12">
                                            <i class="ph-duotone ph-clock me-1"></i>
                                            @if($mostPopularVideo->duration && $mostPopularVideo->duration > 0)
                                                {{ $mostPopularVideo->formatted_duration ?? '--:--' }}
                                            @else
                                                <span title="{{ __('media.duration_not_available') }}">--:--</span>
                                            @endif
                                        </small>
                                    </div>
                                    <div class="position-absolute top-0 end-0 m-3">
                                        <span class="badge bg-dark f-s-12">{{ $mostPopularVideo->view_count ?? $mostPopularVideo->views ?? 0 }} {{ __('media.views') }}</span>
                                    </div>
                                </div>
                            @else
                                <div class="position-relative" style="cursor: pointer;" onclick="Livewire.dispatch('openVideoModal', { videoId: {{ $mostPopularVideo->id }} })">
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                                        <div class="text-center">
                                            <i class="ph-duotone ph-video-camera f-s-64 text-muted mb-3"></i>
                                            <div class="play-button bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 80px; height: 80px; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                                                <i class="ph-duotone ph-play f-s-32 text-white"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="position-absolute top-0 end-0 m-3">
                                        <span class="badge bg-dark f-s-12">{{ $mostPopularVideo->view_count ?? $mostPopularVideo->views ?? 0 }} {{ __('media.views') }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="card-body">
                            <h5 class="card-title f-w-600 f-s-16 mb-2">
                                <a href="#" class="text-decoration-none text-dark hover-text-primary" style="cursor: pointer;">
                                    {{ $mostPopularVideo->title }}
                                </a>
                            </h5>
                            @if($mostPopularVideo->description)
                                <p class="text-muted f-s-13 mb-3">{{ Str::limit($mostPopularVideo->description, 120) }}</p>
                            @endif

                            <!-- Video Actions -->
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                                <div class="d-flex gap-2">
                                    @if($mostPopularVideo)
                                        <livewire:social.social-like-button :content="$mostPopularVideo" type="video" size="sm" />
                                        <livewire:social.social-view-counter :content="$mostPopularVideo" type="video" size="sm" />
                                        <livewire:social.social-comment-button :content="$mostPopularVideo" type="video" size="sm" />
                                    @endif
                                </div>
                                <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center gap-2 w-100 w-md-auto">
                                    <a href="{{ route('videos.show', $mostPopularVideo) }}" 
                                       class="btn btn-primary btn-sm d-flex align-items-center gap-1 w-100 w-md-auto justify-content-center"
                                       target="_blank">
                                        <i class="ph-duotone ph-arrow-square-out f-s-12"></i>
                                        <span>{{ __('media.open_video') }}</span>
                                    </a>
                                    <small class="text-muted d-none d-md-block">
                                        <i class="ph-duotone ph-calendar f-s-12 me-1"></i>
                                        {{ $mostPopularVideo->created_at->format('d/m/Y') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="bg-light-primary h-80 w-80 d-flex-center rounded-circle m-auto mb-3">
                                <i class="ph-duotone ph-video-camera-slash f-s-48 text-primary"></i>
                            </div>
                            <p class="text-muted f-s-16 mb-0">{{ __('media.no_videos_available') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- 6 Video con Switch Nuovi/Popolari (Piccolo) -->
        <div class="col-lg-4">
            <div class="card hover-effect">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="ph-duotone ph-video-camera me-2"></i>
                            Video
                        </h5>
                        <!-- Switch Nuovi/Popolari -->
                        <div class="d-flex align-items-center">
                            <span class="me-2 {{ $videoType === 'popular' ? 'text-primary' : 'text-muted' }} f-s-14 f-w-500" style="cursor: pointer;" wire:click="toggleVideoType('popular')">{{ __('media.popular') }}</span>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" {{ $videoType === 'recent' ? 'checked' : '' }} wire:change="toggleVideoType('{{ $videoType === 'popular' ? 'recent' : 'popular' }}')">
                            </div>
                            <span class="ms-2 {{ $videoType === 'recent' ? 'text-primary' : 'text-muted' }} f-s-14 f-w-500" style="cursor: pointer;" wire:click="toggleVideoType('recent')">{{ __('media.recent') }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($videoType === 'popular')
                        @if($popularVideos->count() > 0)
                            @foreach($popularVideos as $video)
                                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                    <div class="flex-shrink-0 position-relative" style="cursor: pointer;" onclick="Livewire.dispatch('openVideoModal', { videoId: {{ $video->id }} })">
                                        @if($video->thumbnail_url && $video->thumbnail_url !== asset('assets/images/placeholder/placholder-1.jpg'))
                                            <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}" class="rounded" style="width: 80px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center rounded" style="width: 80px; height: 60px;">
                                                <i class="ph-duotone ph-video-camera f-s-24 text-muted"></i>
                                            </div>
                                        @endif
                                        <div class="position-absolute top-50 start-50 translate-middle">
                                            <i class="ph-duotone ph-play f-s-16 text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 f-w-600 f-s-13">
                                            <a href="#" class="text-decoration-none text-dark hover-text-primary" style="cursor: pointer;">
                                                {{ Str::limit($video->title, 40) }}
                                            </a>
                                        </h6>
                                        <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-2">
                                            <div class="d-flex align-items-center gap-2">
                                                @if($video)
                                                    <livewire:social.social-like-button :content="$video" type="video" size="xs" />
                                                    <livewire:social.social-view-counter :content="$video" type="video" size="xs" />
                                                    <livewire:social.social-comment-button :content="$video" type="video" size="xs" />
                                                @endif
                                            </div>
                                            <a href="{{ route('videos.show', $video) }}" 
                                               class="btn btn-primary btn-xs d-flex align-items-center gap-1"
                                               target="_blank"
                                               style="font-size: 10px; padding: 4px 8px; white-space: nowrap;">
                                                <i class="ph-duotone ph-arrow-square-out f-s-10"></i>
                                                <span>{{ __('media.open') }}</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <div class="bg-light-warning h-50 w-50 d-flex-center rounded-circle m-auto mb-2">
                                    <i class="ph-duotone ph-video-camera-slash f-s-24 text-warning"></i>
                                </div>
                                <p class="text-muted f-s-14 mb-0">{{ __('media.no_popular_videos') }}</p>
                            </div>
                        @endif
                    @else
                        @if($recentVideos->count() > 0)
                            @foreach($recentVideos as $video)
                                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                    <div class="flex-shrink-0 position-relative" style="cursor: pointer;" onclick="Livewire.dispatch('openVideoModal', { videoId: {{ $video->id }} })">
                                        @if($video->thumbnail_url && $video->thumbnail_url !== asset('assets/images/placeholder/placholder-1.jpg'))
                                            <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}" class="rounded" style="width: 80px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center rounded" style="width: 80px; height: 60px;">
                                                <i class="ph-duotone ph-video-camera f-s-24 text-muted"></i>
                                            </div>
                                        @endif
                                        <div class="position-absolute top-50 start-50 translate-middle">
                                            <i class="ph-duotone ph-play f-s-16 text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 f-w-600 f-s-13">
                                            <a href="#" class="text-decoration-none text-dark hover-text-primary" style="cursor: pointer;">
                                                {{ Str::limit($video->title, 40) }}
                                            </a>
                                        </h6>
                                        <div class="d-flex flex-column flex-lg-row align-items-start align-items-lg-center justify-content-between gap-2">
                                            <div class="d-flex align-items-center gap-2">
                                                @if($video)
                                                    <livewire:social.social-like-button :content="$video" type="video" size="xs" />
                                                    <livewire:social.social-view-counter :content="$video" type="video" size="xs" />
                                                    <livewire:social.social-comment-button :content="$video" type="video" size="xs" />
                                                @endif
                                            </div>
                                            <a href="{{ route('videos.show', $video) }}" 
                                               class="btn btn-primary btn-xs d-flex align-items-center gap-1"
                                               target="_blank"
                                               style="font-size: 10px; padding: 4px 8px; white-space: nowrap;">
                                                <i class="ph-duotone ph-arrow-square-out f-s-10"></i>
                                                <span>{{ __('media.open') }}</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <div class="bg-light-info h-50 w-50 d-flex-center rounded-circle m-auto mb-2">
                                    <i class="ph-duotone ph-video-camera-slash f-s-24 text-info"></i>
                                </div>
                                <p class="text-muted f-s-14 mb-0">{{ __('media.no_recent_videos') }}</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Seconda Riga: Foto con Switch Nuovi/Popolari + {{ __('media.most_popular_photo') }} -->
    <div class="row mb-4">
        <!-- 6 Foto con Switch Nuovi/Popolari (Piccolo) -->
        <div class="col-lg-4">
            <div class="card hover-effect">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="ph-duotone ph-image me-2"></i>
                            Foto
                        </h5>
                        <!-- Switch Nuovi/Popolari -->
                        <div class="d-flex align-items-center">
                            <span class="me-2 {{ $photoType === 'popular' ? 'text-primary' : 'text-muted' }} f-s-14 f-w-500" style="cursor: pointer;" wire:click="togglePhotoType('popular')">{{ __('media.popular') }}</span>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" {{ $photoType === 'recent' ? 'checked' : '' }} wire:change="togglePhotoType('{{ $photoType === 'popular' ? 'recent' : 'popular' }}')">
                            </div>
                            <span class="ms-2 {{ $photoType === 'recent' ? 'text-primary' : 'text-muted' }} f-s-14 f-w-500" style="cursor: pointer;" wire:click="togglePhotoType('recent')">{{ __('media.recent') }}</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if($photoType === 'popular')
                        @if($popularPhotos->count() > 0)
                            @foreach($popularPhotos as $photo)
                                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                    <div class="flex-shrink-0 position-relative" style="cursor: pointer;" onclick="Livewire.dispatch('openPhotoModal', { photoId: {{ $photo->id }} })">
                                        @if($photo->image_url)
                                            <img src="{{ $photo->image_url }}" alt="{{ $photo->title }}" class="rounded" style="width: 80px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center rounded" style="width: 80px; height: 60px;">
                                                <i class="ph-duotone ph-image f-s-24 text-muted"></i>
                                            </div>
                                        @endif
                                        <div class="position-absolute top-50 start-50 translate-middle">
                                            <i class="ph-duotone ph-magnifying-glass f-s-16 text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 f-w-600 f-s-13">
                                            <a href="#" class="text-decoration-none text-dark hover-text-primary" style="cursor: pointer;">
                                                {{ $photo->title ? Str::limit($photo->title, 40) : __('media.untitled_photo') }}
                                            </a>
                                        </h6>
                                        <div class="d-flex align-items-center gap-1 mb-2">
                                            <div class="d-flex align-items-center gap-1">
                                                @if($photo->user)
                                                    <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($photo->user) }}" alt="{{ $photo->user->name }}" class="rounded-circle" style="width: 20px; height: 20px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 20px; height: 20px;">
                                                        <i class="ph-duotone ph-user f-s-10 text-primary"></i>
                                                    </div>
                                                @endif
                                                <span class="f-s-11 text-muted">{{ $photo->user ? $photo->user->name : __('media.unknown_user') }}</span>
                                            </div>
                                            <span class="f-s-11 text-muted">•</span>
                                            <span class="f-s-11 text-muted">{{ $photo->created_at->diffForHumans() }}</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($photo)
                                                <livewire:social.social-like-button :content="$photo" type="photo" size="xs" />
                                                <livewire:social.social-view-counter :content="$photo" type="photo" size="xs" />
                                                <livewire:social.social-comment-button :content="$photo" type="photo" size="xs" />
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <div class="bg-light-warning h-50 w-50 d-flex-center rounded-circle m-auto mb-2">
                                    <i class="ph-duotone ph-image-slash f-s-24 text-warning"></i>
                                </div>
                                <p class="text-muted f-s-14 mb-0">{{ __('media.no_popular_photos') }}</p>
                            </div>
                        @endif
                    @else
                        @if($recentPhotos->count() > 0)
                            @foreach($recentPhotos as $photo)
                                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                    <div class="flex-shrink-0 position-relative" style="cursor: pointer;" onclick="Livewire.dispatch('openPhotoModal', { photoId: {{ $photo->id }} })">
                                        @if($photo->image_url)
                                            <img src="{{ $photo->image_url }}" alt="{{ $photo->title }}" class="rounded" style="width: 80px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center rounded" style="width: 80px; height: 60px;">
                                                <i class="ph-duotone ph-image f-s-24 text-muted"></i>
                                            </div>
                                        @endif
                                        <div class="position-absolute top-50 start-50 translate-middle">
                                            <i class="ph-duotone ph-magnifying-glass f-s-16 text-white"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1 f-w-600 f-s-13">
                                            <a href="#" class="text-decoration-none text-dark hover-text-primary" style="cursor: pointer;">
                                                {{ $photo->title ? Str::limit($photo->title, 40) : __('media.untitled_photo') }}
                                            </a>
                                        </h6>
                                        <div class="d-flex align-items-center gap-1 mb-2">
                                            <div class="d-flex align-items-center gap-1">
                                                @if($photo->user)
                                                    <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($photo->user) }}" alt="{{ $photo->user->name }}" class="rounded-circle" style="width: 20px; height: 20px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 20px; height: 20px;">
                                                        <i class="ph-duotone ph-user f-s-10 text-primary"></i>
                                                    </div>
                                                @endif
                                                <span class="f-s-11 text-muted">{{ $photo->user ? $photo->user->name : __('media.unknown_user') }}</span>
                                            </div>
                                            <span class="f-s-11 text-muted">•</span>
                                            <span class="f-s-11 text-muted">{{ $photo->created_at->diffForHumans() }}</span>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            @if($photo)
                                                <livewire:social.social-like-button :content="$photo" type="photo" size="xs" />
                                                <livewire:social.social-view-counter :content="$photo" type="photo" size="xs" />
                                                <livewire:social.social-comment-button :content="$photo" type="photo" size="xs" />
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <div class="bg-light-info h-50 w-50 d-flex-center rounded-circle m-auto mb-2">
                                    <i class="ph-duotone ph-image-slash f-s-24 text-info"></i>
                                </div>
                                <p class="text-muted f-s-14 mb-0">{{ __('media.no_recent_photos') }}</p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        <!-- {{ __('media.most_popular_photo') }} (Grande) -->
        <div class="col-lg-8">
            <div class="card hover-effect">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph-duotone ph-trophy me-2"></i>
                        {{ __('media.most_popular_photo') }}
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($mostPopularPhoto)
                        <div class="position-relative">
                            @if($mostPopularPhoto->image_url)
                                <div class="position-relative" style="cursor: pointer;" onclick="Livewire.dispatch('openPhotoModal', { photoId: {{ $mostPopularPhoto->id }} })">
                                    <img src="{{ $mostPopularPhoto->image_url }}" alt="{{ $mostPopularPhoto->title }}" class="card-img-top" style="height: 400px; object-fit: cover;">
                                    <div class="position-absolute top-50 start-50 translate-middle">
                                        <div class="zoom-button bg-white rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                                            <i class="ph-duotone ph-magnifying-glass-plus f-s-32 text-primary"></i>
                                        </div>
                                    </div>
                                    <div class="position-absolute top-0 end-0 m-3">
                                        <span class="badge bg-dark f-s-12">{{ $mostPopularPhoto->view_count ?? $mostPopularPhoto->views ?? 0 }} {{ __('media.views') }}</span>
                                    </div>
                                </div>
                            @else
                                <div class="position-relative" style="cursor: pointer;" onclick="Livewire.dispatch('openPhotoModal', { photoId: {{ $mostPopularPhoto->id }} })">
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                                        <div class="text-center">
                                            <i class="ph-duotone ph-image f-s-64 text-muted mb-3"></i>
                                            <div class="zoom-button bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 80px; height: 80px; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                                                <i class="ph-duotone ph-magnifying-glass-plus f-s-32 text-white"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="position-absolute top-0 end-0 m-3">
                                        <span class="badge bg-dark f-s-12">{{ $mostPopularPhoto->view_count ?? $mostPopularPhoto->views ?? 0 }} {{ __('media.views') }}</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="card-body">
                            <h5 class="card-title f-w-600 f-s-16 mb-2">
                                <a href="#" class="text-decoration-none text-dark hover-text-primary" style="cursor: pointer;">
                                    {{ $mostPopularPhoto->title }}
                                </a>
                            </h5>
                            @if($mostPopularPhoto->description)
                                <p class="text-muted f-s-13 mb-3">{{ Str::limit($mostPopularPhoto->description, 120) }}</p>
                            @endif

                            <!-- Photo Actions -->
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                                <div class="d-flex gap-2">
                                    @if($mostPopularPhoto)
                                        <livewire:social.social-like-button :content="$mostPopularPhoto" type="photo" size="sm" />
                                        <livewire:social.social-view-counter :content="$mostPopularPhoto" type="photo" size="sm" />
                                        <livewire:social.social-comment-button :content="$mostPopularPhoto" type="photo" size="sm" />
                                    @endif
                                </div>
                                <small class="text-muted d-none d-md-block">
                                    <i class="ph-duotone ph-calendar f-s-12 me-1"></i>
                                    {{ $mostPopularPhoto->created_at->format('d/m/Y') }}
                                </small>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="bg-light-primary h-80 w-80 d-flex-center rounded-circle m-auto mb-3">
                                <i class="ph-duotone ph-image-slash f-s-48 text-primary"></i>
                            </div>
                            <p class="text-muted f-s-16 mb-0">{{ __('media.no_photos_available') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Terza Riga: Box di Ricerca Media -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card hover-effect">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph-duotone ph-magnifying-glass me-2"></i>
                        {{ __('media.search_media') }}
                    </h5>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="searchMedia" class="row g-3">
                        <div class="col-md-6">
                            <label for="searchQuery" class="form-label">
                                <i class="ph-duotone ph-search me-1"></i>
                                {{ __('media.keywords') }}
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="searchQuery" 
                                   wire:model.live.debounce.300ms="searchQuery"
                                   placeholder="{{ __('media.search_placeholder') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="mediaType" class="form-label">
                                <i class="ph-duotone ph-files me-1"></i>
                                {{ __('media.media_type') }}
                            </label>
                            <select class="form-select" id="mediaType" wire:model.live="mediaType">
                                <option value="">{{ __('media.all_media') }}</option>
                                <option value="video">{{ __('media.only_videos') }}</option>
                                <option value="photo">{{ __('media.only_photos') }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="userId" class="form-label">
                                <i class="ph-duotone ph-user me-1"></i>
                                Utente
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="userId" 
                                   wire:model.live.debounce.300ms="userId"
                                   placeholder="{{ __('media.username_placeholder') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="dateFrom" class="form-label">
                                <i class="ph-duotone ph-calendar me-1"></i>
                                {{ __('media.date_from') }}
                            </label>
                            <input type="date" 
                                   class="form-control" 
                                   id="dateFrom" 
                                   wire:model.live="dateFrom">
                        </div>
                        <div class="col-md-4">
                            <label for="dateTo" class="form-label">
                                <i class="ph-duotone ph-calendar me-1"></i>
                                {{ __('media.date_to') }}
                            </label>
                            <input type="date" 
                                   class="form-control" 
                                   id="dateTo" 
                                   wire:model.live="dateTo">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="ph-duotone ph-magnifying-glass me-1"></i>
                                Cerca
                            </button>
                            <button type="button" class="btn btn-outline-secondary" wire:click="clearSearch">
                                <i class="ph-duotone ph-x me-1"></i>
                                Pulisci
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- {{ __('media.search_results') }} -->
    @if($hasActiveSearch)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph-duotone ph-magnifying-glass me-2"></i>
                            {{ __('media.search_results') }}
                            <span class="badge bg-primary ms-2">{{ $searchResults['total'] }} {{ __('media.results') }}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($searchResults['total'] > 0)
                            <div class="row">
                                @foreach($searchResults['videos'] as $video)
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card hover-effect">
                                            <div class="position-relative" style="cursor: pointer;" onclick="Livewire.dispatch('openVideoModal', { videoId: {{ $video->id }} })">
                                                @if($video->thumbnail_url)
                                                    <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                                        <i class="ph-duotone ph-video f-s-48 text-muted"></i>
                                                    </div>
                                                @endif
                                                <div class="position-absolute top-0 end-0 m-2">
                                                    <span class="badge bg-primary">{{ __('media.video') }}</span>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <h6 class="card-title" style="cursor: pointer;" onclick="Livewire.dispatch('openVideoModal', { videoId: {{ $video->id }} })">{{ Str::limit($video->title ?? __('media.untitled_video'), 50) }}</h6>
                                                <p class="card-text text-muted f-s-12 mb-3">{{ Str::limit($video->description ?? '', 100) }}</p>
                                                
                                                <!-- User Info -->
                                                <div class="d-flex align-items-center gap-2 mb-3">
                                                    @if($video->user)
                                                        <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($video->user) }}" 
                                                             alt="{{ $video->user->name }}" 
                                                             class="rounded-circle" 
                                                             style="width: 24px; height: 24px; object-fit: cover;">
                                                        <span class="f-s-12 text-muted">{{ $video->user->name }}</span>
                                                        <span class="f-s-11 text-muted ms-auto">{{ $video->created_at->diffForHumans() }}</span>
                                                    @endif
                                                </div>
                                                
                                                <!-- Social Stats -->
                                                <div class="d-flex align-items-center gap-2 mb-3">
                                                    <div class="d-flex align-items-center justify-content-center text-muted" style="width: 60px; height: 32px;">
                                                        <i class="ph-duotone ph-eye f-s-18"></i>
                                                        <span class="f-s-13 ms-1">{{ number_format($video->views_count ?? 0) }}</span>
                                                    </div>
                                                    <button 
                                                        class="btn btn-outline-0 d-flex align-items-center justify-content-center gap-1 {{ auth()->check() && $video->isLikedBy(auth()->user()) ? 'text-primary' : 'text-muted' }}"
                                                        style="width: 60px; height: 32px; transition: all 0.2s ease;"
                                                        onclick="Livewire.dispatch('openVideoModal', { videoId: {{ $video->id }} })"
                                                        title="{{ __('media.like') }}">
                                                        <img src="{{ asset('assets/icon/new/like.svg') }}" 
                                                             alt="Like" 
                                                             style="width: 26px; height: 26px; {{ auth()->check() && $video->isLikedBy(auth()->user()) ? 'filter: brightness(0) saturate(100%) invert(27%) sepia(51%) saturate(2878%) hue-rotate(346deg) brightness(104%) contrast(97%);' : 'filter: brightness(0) saturate(100%) invert(60%) sepia(0%) saturate(0%) hue-rotate(0deg) brightness(89%) contrast(86%);' }}">
                                                        <span class="f-s-13">{{ number_format($video->likes_count ?? 0) }}</span>
                                                    </button>
                                                    <button 
                                                        class="btn btn-outline-0 d-flex align-items-center justify-content-center gap-1 text-muted"
                                                        style="width: 60px; height: 32px; transition: all 0.2s ease;"
                                                        onclick="Livewire.dispatch('openVideoModal', { videoId: {{ $video->id }} })"
                                                        title="{{ __('media.comments') }}">
                                                        <i class="ph-duotone ph-chat-circle f-s-18"></i>
                                                        <span class="f-s-13">{{ number_format($video->comments_count ?? 0) }}</span>
                                                    </button>
                                                </div>
                                                
                                                <!-- Action Button -->
                                                <div class="d-flex justify-content-center">
                                                    <a href="{{ route('videos.show', $video) }}" 
                                                       class="btn btn-primary btn-sm d-flex align-items-center gap-1"
                                                       target="_blank">
                                                        <i class="ph-duotone ph-arrow-square-out f-s-12"></i>
                                                        <span>{{ __('media.open_video') }}</span>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                
                                @foreach($searchResults['photos'] as $photo)
                                    <div class="col-md-6 col-lg-4 mb-3">
                                        <div class="card hover-effect">
                                            <div class="position-relative" style="cursor: pointer;" onclick="Livewire.dispatch('openPhotoModal', { photoId: {{ $photo->id }} })">
                                                @if($photo->image_url)
                                                    <img src="{{ $photo->image_url }}" alt="{{ $photo->title }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                                        <i class="ph-duotone ph-image f-s-48 text-muted"></i>
                                                    </div>
                                                @endif
                                                <div class="position-absolute top-0 end-0 m-2">
                                                    <span class="badge bg-success">{{ __('media.photo') }}</span>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <h6 class="card-title" style="cursor: pointer;" onclick="Livewire.dispatch('openPhotoModal', { photoId: {{ $photo->id }} })">{{ Str::limit($photo->title ?? __('media.untitled_photo'), 50) }}</h6>
                                                <p class="card-text text-muted f-s-12 mb-3">{{ Str::limit($photo->description ?? '', 100) }}</p>
                                                
                                                <!-- User Info -->
                                                <div class="d-flex align-items-center gap-2 mb-3">
                                                    @if($photo->user)
                                                        <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($photo->user) }}" 
                                                             alt="{{ $photo->user->name }}" 
                                                             class="rounded-circle" 
                                                             style="width: 24px; height: 24px; object-fit: cover;">
                                                        <span class="f-s-12 text-muted">{{ $photo->user->name }}</span>
                                                        <span class="f-s-11 text-muted ms-auto">{{ $photo->created_at->diffForHumans() }}</span>
                                                    @endif
                                                </div>
                                                
                                                <!-- Social Stats -->
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="d-flex align-items-center justify-content-center text-muted" style="width: 60px; height: 32px;">
                                                        <i class="ph-duotone ph-eye f-s-18"></i>
                                                        <span class="f-s-13 ms-1">{{ number_format($photo->views_count ?? 0) }}</span>
                                                    </div>
                                                    <button 
                                                        class="btn btn-outline-0 d-flex align-items-center justify-content-center gap-1 {{ auth()->check() && $photo->isLikedBy(auth()->user()) ? 'text-primary' : 'text-muted' }}"
                                                        style="width: 60px; height: 32px; transition: all 0.2s ease;"
                                                        onclick="Livewire.dispatch('openPhotoModal', { photoId: {{ $photo->id }} })"
                                                        title="{{ __('media.like') }}">
                                                        <img src="{{ asset('assets/icon/new/like.svg') }}" 
                                                             alt="Like" 
                                                             style="width: 26px; height: 26px; {{ auth()->check() && $photo->isLikedBy(auth()->user()) ? 'filter: brightness(0) saturate(100%) invert(27%) sepia(51%) saturate(2878%) hue-rotate(346deg) brightness(104%) contrast(97%);' : 'filter: brightness(0) saturate(100%) invert(60%) sepia(0%) saturate(0%) hue-rotate(0deg) brightness(89%) contrast(86%);' }}">
                                                        <span class="f-s-13">{{ number_format($photo->likes_count ?? 0) }}</span>
                                                    </button>
                                                    <button 
                                                        class="btn btn-outline-0 d-flex align-items-center justify-content-center gap-1 text-muted"
                                                        style="width: 60px; height: 32px; transition: all 0.2s ease;"
                                                        onclick="Livewire.dispatch('openPhotoModal', { photoId: {{ $photo->id }} })"
                                                        title="{{ __('media.comments') }}">
                                                        <i class="ph-duotone ph-chat-circle f-s-18"></i>
                                                        <span class="f-s-13">{{ number_format($photo->comments_count ?? 0) }}</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <div class="bg-light-warning h-50 w-50 d-flex-center rounded-circle m-auto mb-2">
                                    <i class="ph-duotone ph-magnifying-glass f-s-24 text-warning"></i>
                                </div>
                                <p class="text-muted f-s-14 mb-0">{{ __('media.no_results_found') }}</p>
                                <p class="text-muted f-s-12 mb-0">{{ __('media.try_different_filters') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modals -->
    <livewire:media.photo-modal />
    <livewire:media.video-modal />
</div>
