@extends('layout.master')

@section('title', __('photos.my_photos') . ' - Slamin')

@section('main-content')
<div class="container-fluid">
    <!-- Header -->
    <div class="page-title-box">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="page-title mb-0">
                <i class="ph ph-images me-2"></i>{{ __('photos.my_photos') }}
            </h4>
            <div class="d-flex gap-2">
                <a href="{{ route('profile.show') }}" class="btn btn-light">
                    <i class="ph ph-arrow-left me-1"></i>{{ __('common.back_to_profile') }}
                </a>
                <a href="{{ route('photos.create') }}" class="btn btn-primary">
                    <i class="ph ph-plus me-1"></i>{{ __('photos.upload_photo') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card card-light-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ph ph-images text-primary f-s-40"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-0">{{ $photos->total() }}</h3>
                            <p class="text-secondary mb-0">{{ __('photos.total_photos') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-light-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ph ph-check-circle text-success f-s-40"></i>
    </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-0">{{ $user->photos()->approved()->count() }}</h3>
                            <p class="text-secondary mb-0">{{ __('photos.approved_photos') }}</p>
            </div>
        </div>
    </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-light-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <i class="ph ph-clock text-warning f-s-40"></i>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h3 class="mb-0">{{ $user->photos()->where('status', 'pending')->count() }}</h3>
                            <p class="text-secondary mb-0">{{ __('photos.pending_photos') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($photos->count() > 0)
        <!-- Photos Grid -->
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
                                
                                <!-- Status Badge -->
                                @if($photo->status !== 'approved')
                                    <span class="badge bg-{{ $photo->status === 'pending' ? 'warning' : 'danger' }} position-absolute top-0 end-0 m-2">
                                        {{ __('photos.' . $photo->status) }}
                                    </span>
    @endif
                            </div>
                        </a>

                        <div class="card-body p-2">
                            @if($photo->title)
                                <h6 class="mb-1 text-truncate">{{ $photo->title }}</h6>
                            @endif
                            <small class="text-secondary d-block">
                                <i class="ph ph-calendar me-1"></i>{{ $photo->created_at->format('d/m/Y') }}
                            </small>
                        </div>

                        <div class="card-footer p-2 bg-light">
                            <div class="d-flex gap-1">
                                <a href="{{ route('photos.edit', $photo) }}" 
                                   class="btn btn-sm btn-light flex-grow-1"
                                   title="{{ __('photos.edit_photo') }}">
                                    <i class="ph ph-pencil-simple"></i>
                                </a>
                                <button type="button" 
                                        class="btn btn-sm btn-danger" 
                                        onclick="deletePhoto({{ $photo->id }})"
                                        title="{{ __('photos.delete_photo') }}">
                                    <i class="ph ph-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
</div>

        <!-- Pagination -->
        <div class="row mt-4">
            <div class="col-12">
                {{ $photos->links() }}
            </div>
                </div>
    @else
        <!-- Empty State -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="ph ph-images text-secondary" style="font-size: 64px;"></i>
                        <h5 class="mt-3">{{ __('photos.no_photos') }}</h5>
                        <p class="text-secondary mb-3">{{ __('photos.no_photos_upload') }}</p>
                        <a href="{{ route('photos.create') }}" class="btn btn-primary">
                            <i class="ph ph-plus me-1"></i>{{ __('photos.upload_first_photo') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
async function deletePhoto(photoId) {
    if (!confirm('{{ __("photos.confirm_delete") }}')) {
        return;
    }

    const button = event.target.closest('button');
    button.disabled = true;
    button.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

    try {
        const response = await fetch(`/profile/photos/${photoId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        });

        const result = await response.json();

        if (result.success) {
            // Reload the page to update the list
            window.location.reload();
        } else {
            alert(result.message || '{{ __("photos.delete_error") }}');
            button.disabled = false;
            button.innerHTML = '<i class="ph ph-trash"></i>';
        }
    } catch (error) {
        alert('{{ __("photos.delete_error") }}');
        button.disabled = false;
        button.innerHTML = '<i class="ph ph-trash"></i>';
    }
}
</script>
@endpush
