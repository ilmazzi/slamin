<div>
    @if ($videos && $videos->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header bg-gradient-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <a href="{{ route('videos.index') }}" class="text-decoration-none text-white d-flex align-items-center">
                            <i class="ph-duotone ph-video f-s-16 me-2"></i>
                            Video
                        </a>
                    </h5>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn {{ $contentType === 'recent' ? 'btn-light' : 'btn-outline-light' }}" 
                                wire:click="toggleContent('recent')">
                            Recenti
                        </button>
                        <button type="button" class="btn {{ $contentType === 'popular' ? 'btn-light' : 'btn-outline-light' }}" 
                                wire:click="toggleContent('popular')">
                            Popolari
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($videos as $video)
                            <div class="col-12 col-md-6 col-lg-4 mb-3">
                                <div class="card border-0 shadow-sm h-100">
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
                                            <i class="ph-duotone ph-user f-s-12 me-1"></i>
                                            {{ $video->user->name }}
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="ph-duotone ph-clock f-s-12 me-1"></i>
                                                {{ $video->created_at->diffForHumans() }}
                                            </small>
                                            <a href="{{ route('videos.show', $video->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="ph-duotone ph-eye f-s-12 me-1"></i>
                                                Guarda
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
