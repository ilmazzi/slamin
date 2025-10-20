<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">
                <i class="ph ph-video-camera me-2"></i>
                {{ __('common.media_section') }}
            </h4>
        </div>
    </div>

    <!-- Prima Riga: Video Più Popolare + 6 Video con Switch -->
    <div class="row mb-4">
        <!-- Video Più Popolare (Grande) -->
        <div class="col-lg-8">
            <div class="card hover-effect">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph-duotone ph-trophy me-2"></i>
                        Video Più Popolare
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($mostPopularVideo)
                        <div class="position-relative">
                            <div class="position-relative" style="cursor: pointer;" wire:click="openVideoModal({{ $mostPopularVideo->id }})">
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
                                            {{ $mostPopularVideo->formatted_duration }}
                                        @else
                                            <span title="{{ __('videos.duration_unavailable') }}">--:--</span>
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="p-3">
                            <h5 class="card-title f-s-18 f-w-600 mb-2">{{ $mostPopularVideo->title }}</h5>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="{{ $mostPopularVideo->user->profile_photo_url }}" 
                                         class="rounded-circle me-2" 
                                         alt="{{ $mostPopularVideo->user->name }}" 
                                         style="width: 32px; height: 32px; object-fit: cover;">
                                    <span class="text-muted f-s-14">{{ $mostPopularVideo->user->name }}</span>
                                </div>
                                <small class="text-muted f-s-12">{{ $mostPopularVideo->created_at->diffForHumans() }}</small>
                            </div>
                            <!-- Social Actions -->
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex gap-3">
                                    <livewire:social.social-view-counter :model="$mostPopularVideo" :size="'sm'" />
                                    <livewire:social.social-like-button :model="$mostPopularVideo" :size="'sm'" />
                                    <livewire:social.social-comment-button :model="$mostPopularVideo" :size="'sm'" />
                                </div>
                                <button class="btn btn-primary btn-sm" wire:click="openVideoModal({{ $mostPopularVideo->id }})">
                                    <i class="ph-duotone ph-play me-1"></i>
                                    Guarda
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="p-5 text-center">
                            <i class="ph-duotone ph-video f-s-48 text-muted mb-3"></i>
                            <p class="text-muted">Nessun video popolare disponibile</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- 6 Video con Switch -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ph-duotone ph-video me-2"></i>
                        Video
                    </h5>
                    <div class="btn-group" role="group">
                        <button type="button" 
                                class="btn btn-sm {{ $videoType === 'popular' ? 'btn-primary' : 'btn-outline-primary' }}"
                                wire:click="toggleVideoType('popular')">
                            Popolari
                        </button>
                        <button type="button" 
                                class="btn btn-sm {{ $videoType === 'recent' ? 'btn-primary' : 'btn-outline-primary' }}"
                                wire:click="toggleVideoType('recent')">
                            Recenti
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="row g-2 p-3">
                        @forelse($videos as $video)
                            <div class="col-12">
                                <div class="card border-0 shadow-sm hover-effect">
                                    <div class="position-relative">
                                        <div style="cursor: pointer;" wire:click="openVideoModal({{ $video->id }})">
                                            <img src="{{ $video->thumbnail_url }}" 
                                                 class="card-img-top" 
                                                 alt="{{ $video->title }}" 
                                                 style="height: 120px; object-fit: cover;">
                                            <div class="position-absolute top-50 start-50 translate-middle">
                                                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center" 
                                                     style="width: 40px; height: 40px; box-shadow: 0 2px 8px rgba(0,0,0,0.3);">
                                                    <i class="ph-duotone ph-play f-s-16 text-primary"></i>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="position-absolute top-0 end-0 m-2">
                                            <span class="badge bg-dark bg-opacity-75 f-s-10">
                                                @if($video->duration && $video->duration > 0)
                                                    {{ $video->formatted_duration }}
                                                @else
                                                    --:--
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    <div class="card-body p-2">
                                        <h6 class="card-title f-s-12 f-w-600 mb-1">{{ Str::limit($video->title, 40) }}</h6>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted f-s-10">{{ $video->user->name }}</small>
                                            <div class="d-flex gap-1">
                                                <livewire:social.social-view-counter :model="$video" :size="'xs'" />
                                                <livewire:social.social-like-button :model="$video" :size="'xs'" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-4">
                                <i class="ph-duotone ph-video f-s-32 text-muted mb-2"></i>
                                <p class="text-muted f-s-14 mb-0">Nessun video disponibile</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Seconda Riga: Foto Recenti -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph-duotone ph-images me-2"></i>
                        Foto Recenti
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @forelse($recentPhotos as $photo)
                            <div class="col-lg-3 col-md-4 col-sm-6">
                                <div class="card border-0 shadow-sm hover-effect">
                                    <div class="position-relative">
                                        <div style="cursor: pointer;" wire:click="openPhotoModal({{ $photo->id }})">
                                            <img src="{{ $photo->image_url }}" 
                                                 class="card-img-top" 
                                                 alt="{{ $photo->title }}" 
                                                 style="height: 200px; object-fit: cover;">
                                        </div>
                                    </div>
                                    <div class="card-body p-2">
                                        <h6 class="card-title f-s-12 f-w-600 mb-1">{{ Str::limit($photo->title, 30) }}</h6>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted f-s-10">{{ $photo->user->name }}</small>
                                            <div class="d-flex gap-1">
                                                <livewire:social.social-view-counter :model="$photo" :size="'xs'" />
                                                <livewire:social.social-like-button :model="$photo" :size="'xs'" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-4">
                                <i class="ph-duotone ph-images f-s-48 text-muted mb-3"></i>
                                <p class="text-muted">Nessuna foto disponibile</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Terza Riga: Video Più Visti + Snap Recenti -->
    <div class="row mb-4">
        <!-- Video Più Visti -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph-duotone ph-eye me-2"></i>
                        Video Più Visti
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @forelse($mostViewedVideos as $video)
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm hover-effect">
                                    <div class="position-relative">
                                        <div style="cursor: pointer;" wire:click="openVideoModal({{ $video->id }})">
                                            <img src="{{ $video->thumbnail_url }}" 
                                                 class="card-img-top" 
                                                 alt="{{ $video->title }}" 
                                                 style="height: 150px; object-fit: cover;">
                                            <div class="position-absolute top-50 start-50 translate-middle">
                                                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center" 
                                                     style="width: 50px; height: 50px; box-shadow: 0 2px 8px rgba(0,0,0,0.3);">
                                                    <i class="ph-duotone ph-play f-s-20 text-primary"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-3">
                                        <h6 class="card-title f-s-14 f-w-600 mb-2">{{ Str::limit($video->title, 50) }}</h6>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted f-s-12">{{ $video->user->name }}</small>
                                            <div class="d-flex gap-2">
                                                <livewire:social.social-view-counter :model="$video" :size="'sm'" />
                                                <livewire:social.social-like-button :model="$video" :size="'sm'" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-4">
                                <i class="ph-duotone ph-eye f-s-32 text-muted mb-2"></i>
                                <p class="text-muted f-s-14 mb-0">Nessun video disponibile</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Snap Recenti -->
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph-duotone ph-lightning me-2"></i>
                        Snap Recenti
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        @forelse($recentSnaps as $snap)
                            <div class="col-12">
                                <div class="card border-0 shadow-sm hover-effect">
                                    <div class="position-relative">
                                        <div style="cursor: pointer;" wire:click="openVideoModal({{ $snap->video->id }})">
                                            <img src="{{ $snap->video->thumbnail_url }}" 
                                                 class="card-img-top" 
                                                 alt="{{ $snap->video->title }}" 
                                                 style="height: 100px; object-fit: cover;">
                                            <div class="position-absolute top-50 start-50 translate-middle">
                                                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center" 
                                                     style="width: 30px; height: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.3);">
                                                    <i class="ph-duotone ph-play f-s-12 text-primary"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body p-2">
                                        <h6 class="card-title f-s-11 f-w-600 mb-1">{{ Str::limit($snap->video->title, 25) }}</h6>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted f-s-10">{{ $snap->user->name }}</small>
                                            <small class="text-muted f-s-10">{{ $snap->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-4">
                                <i class="ph-duotone ph-lightning f-s-32 text-muted mb-2"></i>
                                <p class="text-muted f-s-14 mb-0">Nessun snap disponibile</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modali -->
    @if($showVideoModal && $selectedVideoId)
        <livewire:media.video-modal :videoId="$selectedVideoId" />
    @endif

    @if($showPhotoModal && $selectedPhotoId)
        <livewire:media.photo-modal :photoId="$selectedPhotoId" />
    @endif
</div>
