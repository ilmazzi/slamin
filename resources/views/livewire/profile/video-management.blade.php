<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <x-icon name="media" size="20" class="me-2" />
                {{ __('videos.manage_videos') }}
            </h5>
            <a href="{{ route('videos.create') }}" class="btn btn-primary btn-sm">
                <i class="ph ph-plus me-1"></i>
                {{ __('videos.upload_video') }}
            </a>
        </div>
        
        <div class="card-body">
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Search and Filter -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ph ph-magnifying-glass"></i>
                        </span>
                        <input type="text" class="form-control" 
                               wire:model.live.debounce.300ms="search" 
                               placeholder="{{ __('videos.search_videos') }}">
                    </div>
                </div>
                <div class="col-md-4">
                    <select class="form-select" wire:model.live="status">
                        <option value="all">{{ __('videos.all_videos') }}</option>
                        <option value="approved">{{ __('videos.approved') }}</option>
                        <option value="pending">{{ __('videos.pending') }}</option>
                        <option value="rejected">{{ __('videos.rejected') }}</option>
                    </select>
                </div>
            </div>

            <!-- Videos Grid -->
            @if($videos->count() > 0)
            <div class="row">
                @foreach($videos as $video)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100">
                        <div class="position-relative">
                            @if($video->thumbnail_path)
                                <img src="{{ Storage::url($video->thumbnail_path) }}" 
                                     class="card-img-top" alt="{{ $video->title }}"
                                     style="height: 200px; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                     style="height: 200px;">
                                    <i class="ph ph-video f-s-48 text-muted"></i>
                                </div>
                            @endif
                            
                            <!-- Status Badge -->
                            <span class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-{{ $video->status === 'approved' ? 'success' : ($video->status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ __('videos.' . $video->status) }}
                                </span>
                            </span>
                            
                            <!-- Duration -->
                            @if($video->duration)
                            <span class="position-absolute bottom-0 end-0 m-2">
                                <span class="badge bg-dark bg-opacity-75">
                                    {{ gmdate('i:s', $video->duration) }}
                                </span>
                            </span>
                            @endif
                        </div>
                        
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title f-s-14 f-w-600 mb-2">
                                {{ Str::limit($video->title, 50) }}
                            </h6>
                            
                            @if($video->description)
                            <p class="card-text f-s-12 text-muted mb-3">
                                {{ Str::limit($video->description, 80) }}
                            </p>
                            @endif
                            
                            <!-- Stats -->
                            <div class="d-flex justify-content-between f-s-12 text-muted mb-3">
                                <span>
                                    <i class="ph ph-eye me-1"></i>{{ number_format($video->view_count) }}
                                </span>
                                <span>
                                    <i class="ph ph-heart me-1"></i>{{ number_format($video->like_count) }}
                                </span>
                                <span>
                                    <i class="ph ph-chat-circle me-1"></i>{{ number_format($video->comment_count) }}
                                </span>
                            </div>
                            
                            <!-- Actions -->
                            <div class="mt-auto">
                                <div class="btn-group w-100" role="group">
                                    <a href="{{ route('videos.show', $video) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="ph ph-eye"></i>
                                    </a>
                                    <a href="{{ route('videos.edit', $video) }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="ph ph-pencil"></i>
                                    </a>
                                    <button class="btn btn-outline-{{ $video->status === 'approved' ? 'warning' : 'success' }} btn-sm" 
                                            wire:click="toggleStatus({{ $video->id }})"
                                            title="{{ $video->status === 'approved' ? __('videos.disable') : __('videos.enable') }}">
                                        <i class="ph ph-{{ $video->status === 'approved' ? 'pause' : 'play' }}"></i>
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm" 
                                            onclick="confirm('{{ __('videos.confirm_delete') }}') && @this.deleteVideo({{ $video->id }})"
                                            title="{{ __('common.delete') }}">
                                        <i class="ph ph-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $videos->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="ph ph-video f-s-64 text-muted mb-3"></i>
                <h6 class="text-muted">{{ __('videos.no_videos') }}</h6>
                <p class="text-muted">{{ __('videos.upload_first_video') }}</p>
                <a href="{{ route('videos.create') }}" class="btn btn-primary">
                    <i class="ph ph-plus me-1"></i>
                    {{ __('videos.upload_video') }}
                </a>
            </div>
            @endif
        </div>
    </div>
</div>


