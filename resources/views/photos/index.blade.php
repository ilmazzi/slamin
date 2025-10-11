@extends('layout.master')

@section('title', __('photos.photo_gallery'))

@section('main-content')
<div class="container-fluid">
    <!-- Header -->
    <div class="page-title-box">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="page-title mb-0">
                    <i class="ph ph-images me-2"></i>{{ __('photos.photo_gallery') }}
                </h4>
                @if(isset($user))
                    <p class="text-secondary mb-0">
                        {{ __('photos.photos_by') }} <strong>{{ $user->getDisplayName() }}</strong>
                    </p>
                @endif
            </div>
            @auth
                @if(!isset($user) || $user->id === auth()->id())
                    <a href="{{ route('photos.create') }}" class="btn btn-primary">
                        <i class="ph ph-plus me-1"></i>{{ __('photos.upload_photo') }}
                    </a>
                @endif
            @endauth
        </div>
    </div>

    @if($photos->count() > 0)
        <!-- Photo Grid -->
        <div class="row g-3">
            @foreach($photos as $photo)
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <div class="card hover-effect h-100">
                        <a href="{{ route('photos.show', $photo) }}" class="text-decoration-none">
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
                                            <p class="mb-1 fw-bold small text-truncate">{{ $photo->title }}</p>
                                        @endif
                                        <p class="mb-0 small">
                                            <i class="ph ph-user me-1"></i>{{ $photo->user->getDisplayName() }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </a>

                        @if($photo->title || $photo->description)
                            <div class="card-body p-2">
                                @if($photo->title)
                                    <h6 class="mb-1 text-truncate">{{ $photo->title }}</h6>
                                @endif
                                @if($photo->description)
                                    <p class="text-secondary small mb-0 text-truncate">{{ $photo->description }}</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="ph ph-images text-secondary" style="font-size: 64px;"></i>
                        <h5 class="mt-3">{{ __('photos.no_photos') }}</h5>
                        <p class="text-secondary mb-3">
                            @if(isset($user) && $user->id === auth()->id())
                                {{ __('photos.no_photos_upload') }}
                            @else
                                {{ __('photos.no_photos_yet') }}
                            @endif
                        </p>
                        @auth
                            @if(!isset($user) || $user->id === auth()->id())
                                <a href="{{ route('photos.create') }}" class="btn btn-primary">
                                    <i class="ph ph-plus me-1"></i>{{ __('photos.upload_first_photo') }}
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
// Lazy loading images
document.addEventListener('DOMContentLoaded', function() {
    if ('loading' in HTMLImageElement.prototype) {
        // Browser supports native lazy loading
        const images = document.querySelectorAll('img[loading="lazy"]');
        images.forEach(img => {
            img.src = img.src;
        });
    } else {
        // Fallback for browsers that don't support lazy loading
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/lazysizes@5/lazysizes.min.js';
        document.body.appendChild(script);
    }
});
</script>
@endpush

