<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="ph-duotone ph-video-camera me-2"></i>{{ __('media.my_media') }}
                </h4>
                <div class="d-flex gap-2">
                    <a href="{{ route('photos.create') }}" class="btn btn-success btn-sm">
                        <i class="ph ph-image me-2"></i>{{ __('media.upload_photo') }}
                    </a>
                    <a href="{{ route('videos.upload') }}" class="btn btn-primary btn-sm">
                        <i class="ph ph-video-camera me-2"></i>{{ __('media.upload_video') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Search -->
                        <div class="col-md-4">
                            <label class="form-label">{{ __('media.search') }}</label>
                            <input type="text" 
                                   class="form-control" 
                                   wire:model.live.debounce.300ms="search"
                                   placeholder="{{ __('media.search_placeholder') }}">
                        </div>

                        <!-- Media Type -->
                        <div class="col-md-4">
                            <label class="form-label">{{ __('media.media_type') }}</label>
                            <select class="form-select" wire:model.live="mediaType">
                                <option value="all">{{ __('media.all_media') }}</option>
                                <option value="videos">{{ __('media.only_videos') }}</option>
                                <option value="photos">{{ __('media.only_photos') }}</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div class="col-md-4">
                            <label class="form-label">{{ __('media.status') }}</label>
                            <select class="form-select" wire:model.live="status">
                                <option value="all">{{ __('media.all_status') }}</option>
                                <option value="approved">{{ __('media.approved') }}</option>
                                <option value="pending">{{ __('media.pending') }}</option>
                                <option value="rejected">{{ __('media.rejected') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Media Grid -->
    <div class="row">
        <div class="col-12">
            @if($this->media->count() > 0)
                <div class="row g-3">
                    @foreach($this->media as $item)
                        <div class="col-md-4 col-lg-3">
                            <div class="card h-100">
                                <!-- Media Preview -->
                                @if($item->media_type === 'video')
                                    <div class="position-relative">
                                        @if($item->thumbnail_url)
                                            <img src="{{ $item->thumbnail_url }}" 
                                                 class="card-img-top" 
                                                 style="height: 200px; object-fit: cover;"
                                                 alt="{{ $item->title }}">
                                        @else
                                            <div class="bg-secondary d-flex align-items-center justify-content-center" 
                                                 style="height: 200px;">
                                                <i class="ph-duotone ph-video-camera f-s-48 text-white"></i>
                                            </div>
                                        @endif
                                        <div class="position-absolute top-0 end-0 m-2">
                                            <span class="badge bg-primary">
                                                <i class="ph ph-video-camera me-1"></i>{{ __('media.video') }}
                                            </span>
                                        </div>
                                    </div>
                                @else
                                    <div class="position-relative">
                                        @if($item->image_url)
                                            <img src="{{ $item->image_url }}" 
                                                 class="card-img-top" 
                                                 style="height: 200px; object-fit: cover;"
                                                 alt="{{ $item->title }}">
                                        @else
                                            <div class="bg-secondary d-flex align-items-center justify-content-center" 
                                                 style="height: 200px;">
                                                <i class="ph-duotone ph-image f-s-48 text-white"></i>
                                            </div>
                                        @endif
                                        <div class="position-absolute top-0 end-0 m-2">
                                            <span class="badge bg-success">
                                                <i class="ph ph-image me-1"></i>{{ __('media.photo') }}
                                            </span>
                                        </div>
                                    </div>
                                @endif

                                <!-- Media Info -->
                                <div class="card-body">
                                    <h6 class="card-title f-s-14 f-w-600 mb-2">{{ $item->title ?? __('media.untitled') }}</h6>
                                    <p class="card-text text-muted f-s-12 mb-3">{{ Str::limit($item->description ?? '', 60) }}</p>
                                    
                                    <!-- Status Badge -->
                                    <div class="mb-3">
                                        @if($item->status === 'approved')
                                            <span class="badge bg-success f-s-11">
                                                <i class="ph ph-check-circle me-1"></i>{{ __('media.approved') }}
                                            </span>
                                        @elseif($item->status === 'pending')
                                            <span class="badge bg-warning f-s-11">
                                                <i class="ph ph-clock me-1"></i>{{ __('media.pending') }}
                                            </span>
                                        @else
                                            <span class="badge bg-danger f-s-11">
                                                <i class="ph ph-x-circle me-1"></i>{{ __('media.rejected') }}
                                            </span>
                                        @endif
                                        <small class="text-muted f-s-11 ms-2">{{ $item->created_at->format('d/m/Y') }}</small>
                                    </div>

                                    <!-- Stats -->
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <span class="text-muted f-s-11">
                                            <i class="ph ph-eye me-1"></i>{{ number_format($item->views_count ?? 0) }}
                                        </span>
                                        <span class="text-muted f-s-11">
                                            <i class="ph ph-heart me-1"></i>{{ number_format($item->likes_count ?? 0) }}
                                        </span>
                                        <span class="text-muted f-s-11">
                                            <i class="ph ph-chat-circle me-1"></i>{{ number_format($item->comments_count ?? 0) }}
                                        </span>
                                    </div>

                                    <!-- Actions -->
                                    <div class="d-flex gap-2">
                                        @if($item->media_type === 'video')
                                            <a href="{{ route('videos.show', $item) }}" 
                                               class="btn btn-primary btn-sm flex-fill">
                                                <i class="ph ph-eye me-1"></i>{{ __('media.view') }}
                                            </a>
                                        @else
                                            <button onclick="Livewire.dispatch('openPhotoModal', { photoId: {{ $item->id }} })" 
                                                    class="btn btn-primary btn-sm flex-fill">
                                                <i class="ph ph-eye me-1"></i>{{ __('media.view') }}
                                            </button>
                                        @endif
                                        <button wire:click="deleteMedia({{ $item->id }}, '{{ $item->media_type }}')"
                                                onclick="return confirm('{{ __('media.confirm_delete') }}')"
                                                class="btn btn-danger btn-sm">
                                            <i class="ph ph-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-4">
                    {{ $this->media->links() }}
                </div>
            @else
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="ph-duotone ph-video-camera f-s-64 text-muted mb-3"></i>
                        <h5 class="text-muted mb-3">{{ __('media.no_media_found') }}</h5>
                        <p class="text-secondary mb-4">{{ __('media.no_media_description') }}</p>
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="{{ route('photos.create') }}" class="btn btn-success">
                                <i class="ph ph-image me-2"></i>{{ __('media.upload_photo') }}
                            </a>
                            <a href="{{ route('videos.upload') }}" class="btn btn-primary">
                                <i class="ph ph-video-camera me-2"></i>{{ __('media.upload_video') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Photo Modal -->
    <livewire:media.photo-modal />
</div>

