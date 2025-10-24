<div>
    @if ($videos && $videos->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <a href="{{ route('videos.index') }}" class="text-decoration-none text-primary d-flex align-items-center">
                            <i class="ph-duotone ph-video f-s-16 me-2"></i>
                            {{ __('home.videos_section.title') }}
                        </a>
                    </h5>
                    @if ($videos->chunk(2)->count() > 1)
                        <div class="d-flex">
                            <button class="btn btn-sm bg-light-primary text-primary me-2 border-0" type="button" data-bs-target="#videosCarousel" data-bs-slide="prev">
                                <span class="f-s-12">‹</span>
                            </button>
                            <button class="btn btn-sm bg-light-primary text-primary border-0" type="button" data-bs-target="#videosCarousel" data-bs-slide="next">
                                <span class="f-s-12">›</span>
                            </button>
                        </div>
                    @endif
                </div>
                <div class="card-body p-0">
                    <div id="videosCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
                        @if ($videos->chunk(2)->count() > 1)
                            <div class="carousel-indicators">
                                @foreach ($videos->chunk(2) as $index => $videoChunk)
                                    <button type="button" data-bs-target="#videosCarousel" data-bs-slide-to="{{ $index }}" 
                                            class="{{ $index === 0 ? 'active' : '' }}" aria-current="{{ $index === 0 ? 'true' : 'false' }}" 
                                            aria-label="Slide {{ $index + 1 }}"></button>
                                @endforeach
                            </div>
                        @endif
                        <div class="carousel-inner">
                            @foreach ($videos->chunk(2) as $chunkIndex => $videoChunk)
                                <div class="carousel-item {{ $chunkIndex === 0 ? 'active' : '' }}">
                                    <div class="row g-3 p-3">
                                        @foreach ($videoChunk as $video)
                                            <div class="col-12 col-md-6">
                                                <div class="card h-100 border-0 shadow-sm">
                                                    <div class="position-relative">
                                                        <a href="{{ route('videos.show', $video->id) }}" class="position-relative d-block">
                                                            <img src="{{ $video->thumbnail_url }}" class="card-img-top" alt="{{ $video->title }}" style="height: 200px; object-fit: cover;">
                                                            <div class="position-absolute top-50 start-50 translate-middle">
                                                                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; box-shadow: 0 2px 10px rgba(0,0,0,0.3);">
                                                                    <i class="ph-duotone ph-play f-s-20 text-primary"></i>
                                                                </div>
                                                            </div>
                                                        </a>
                                                        <div class="position-absolute top-0 end-0 m-2">
                                                            <span class="badge bg-dark bg-opacity-75">{{ $video->duration ?? 'N/A' }}</span>
                                                        </div>
                                                    </div>
                                                    <div class="card-body">
                                                        <h6 class="card-title f-s-14 f-w-600 mb-2">{{ Str::limit($video->title, 50) }}</h6>
                                                        <p class="card-text text-muted f-s-12 mb-2">
                                                            <i class="ph-duotone ph-map-pin f-s-12 me-1"></i>
                                                            {{ $video->created_at->diffForHumans() }}
                                                        </p>
                                                        <div class="d-flex justify-content-between align-items-center">
                                                            <small class="text-muted">
                                                                <i class="ph-duotone ph-clock f-s-12 me-1"></i>
                                                                {{ $video->duration ?? 'N/A' }}
                                                            </small>
                                                            <a href="{{ route('videos.show', $video->id) }}" class="btn btn-sm btn-outline-primary">
                                                                <i class="ph-duotone ph-eye f-s-12 me-1"></i>
                                                                {{ __('home.videos_section.watch') }}
                                                            </a>
                                                        </div>
                                                        <!-- Social Actions -->
                                                        <div class="d-flex justify-content-end mt-2">
                                                            
                                                            
                                                            
                                                        </div>
                                                        <!-- Avatar utente cliccabile -->
                                                        <div class="d-flex align-items-center mt-3 pt-2 border-top">
                                                            <a href="{{ route('profile.show', $video->user->id) }}" class="text-decoration-none d-flex align-items-center">
                                                                <img src="{{ $video->user->profile_photo_url }}" class="rounded-circle me-2" alt="{{ $video->user->name }}" style="width: 32px; height: 32px; object-fit: cover;">
                                                                <span class="text-muted f-s-12">{{ $video->user->name }}</span>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
