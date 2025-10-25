<div>
    <div class="container-fluid">
        <!-- Header -->
        <div class="page-title-box">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="page-title mb-0">
                        <i class="ph ph-images me-2"></i>{{ __('photos.photo_gallery') }}
                    </h4>
                    @if($user)
                        <p class="text-secondary mb-0">
                            {{ __('photos.photos_by') }} <strong>{{ $user->name }}</strong>
                        </p>
                    @endif
                </div>
                @auth
                    @if($isOwnPhotos)
                        <a href="{{ route('photos.create') }}" class="btn btn-primary">
                            <i class="ph ph-plus me-1"></i>{{ __('photos.upload_photo') }}
                        </a>
                    @endif
                @endauth
            </div>
        </div>

    <!-- Search Bar -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="ph ph-magnifying-glass"></i>
                                </span>
                                <input type="text" 
                                       class="form-control" 
                                       wire:model.live.debounce.300ms="search"
                                       placeholder="{{ __('media.search_photos_placeholder') }}">
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <div class="d-flex align-items-center justify-content-md-end">
                                <label class="form-label me-2 mb-0">{{ __('media.photos_per_page') }}:</label>
                                <select class="form-select form-select-sm" 
                                        wire:model.live="perPage" 
                                        style="width: auto;">
                                    <option value="12">12</option>
                                    <option value="24">24</option>
                                    <option value="48">48</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($photos->count() > 0)
        <!-- Photo Grid -->
        <div class="row g-3">
            @foreach($photos as $photo)
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <div class="card hover-effect h-100">
                        <a href="#" onclick="Livewire.dispatch('openPhotoModal', { photoId: {{ $photo->id }} })" class="text-decoration-none">
                            <div class="position-relative" style="padding-top: 100%; overflow: hidden;">
                                <img src="{{ $photo->thumbnail_url }}" 
                                     alt="{{ $photo->alt_text ?: $photo->title }}"
                                     class="position-absolute top-0 start-0 w-100 h-100"
                                     style="object-fit: cover;"
                                     loading="lazy">
                                
                                <!-- Overlay on hover -->
                                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-end p-2"
                                     style="background: linear-gradient(transparent, rgba(0,0,0,0.7)); opacity: 0; transition: opacity 0.3s;"
                                     onmouseover="this.style.opacity='1'"
                                     onmouseout="this.style.opacity='0'">
                                    <div class="text-white w-100">
                                        @if($photo->title)
                                            <h6 class="mb-1 f-s-12 f-w-600 text-truncate">{{ $photo->title }}</h6>
                                        @endif
                                        <small class="f-s-10 opacity-75">
                                            <i class="ph ph-calendar me-1"></i>
                                            {{ $photo->created_at->format('d/m/Y') }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </a>
                        
                        <!-- Card Body -->
                        <div class="card-body p-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="flex-grow-1">
                                    @if($photo->title)
                                        <h6 class="card-title f-s-12 f-w-600 mb-1 text-truncate">{{ $photo->title }}</h6>
                                    @endif
                                    <small class="text-muted f-s-10">
                                        <i class="ph ph-user me-1"></i>
                                        {{ $photo->user->name }}
                                    </small>
                                </div>
                                
                                <!-- Social Actions -->
                                <div class="d-flex gap-1">
                                    
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $photos->links() }}
                </div>
            </div>
        </div>
    @else
        <!-- No Photos -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        @if($search)
                            <i class="ph ph-magnifying-glass f-s-48 text-muted mb-3"></i>
                            <h5 class="text-muted mb-3">{{ __('media.no_photos_found') }}</h5>
                            <p class="text-secondary mb-4">
                                {{ __('media.no_photos_match_search') }} "{{ $search }}"
                            </p>
                            <button class="btn btn-outline-primary" wire:click="$set('search', '')">
                                <i class="ph ph-arrow-counter-clockwise me-1"></i>
                                {{ __('media.clear_search') }}
                            </button>
                        @else
                            <i class="ph ph-images f-s-48 text-muted mb-3"></i>
                            <h5 class="text-muted mb-3">
                                @if($isOwnPhotos)
                                    {{ __('photos.no_photos_upload') }}
                                @else
                                    {{ __('photos.no_photos_yet') }}
                                @endif
                            </h5>
                            @auth
                                @if($isOwnPhotos)
                                    <a href="{{ route('photos.create') }}" class="btn btn-primary">
                                        <i class="ph ph-plus me-1"></i>
                                        {{ __('photos.upload_first_photo') }}
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
    </div>


<style>
.hover-effect {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.hover-effect:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.card img {
    transition: transform 0.3s ease;
}

.card:hover img {
    transform: scale(1.05);
}
</style>

</div>