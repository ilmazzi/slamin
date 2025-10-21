@extends('layout.master')

@section('title', ($photo->title ?: 'Foto di ' . $photo->user->getDisplayName()) . ' - Slam In')

@section('main-content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <!-- Titolo su mobile, breadcrumb su desktop -->
                <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">

                    <div class="page-title-right">
                        
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Photo Display -->
            <div class="card mb-4">
                <div class="card-body p-0">
                    <div class="position-relative">
                        <img src="{{ $photo->image_url }}"
                             alt="{{ $photo->alt_text ?: ($photo->title ?: 'Foto di ' . $photo->user->getDisplayName()) }}"
                             class="img-fluid w-100"
                             style="max-height: 600px; object-fit: contain;">

                        <!-- Photo Overlay Info -->
                        <div class="position-absolute bottom-0 start-0 end-0 p-3" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                            <div class="d-flex justify-content-between align-items-end text-white">
                                <div>
                                    @if($photo->title)
                                        <h4 class="mb-1 text-white">{{ $photo->title }}</h4>
                                    @endif
                                    <p class="mb-0">
                                        <i class="ph ph-user me-1"></i>
                                        <a href="{{ route('user.show', $photo->user) }}" class="text-decoration-none text-white hover-effect">
                                            {{ $photo->user->getDisplayName() }}
                                        </a>
                                    </p>
                                </div>
                                <div class="text-end">
                                    <small class="d-block">
                                        <i class="ph ph-calendar me-1"></i>{{ $photo->created_at->format('d/m/Y H:i') }}
                                    </small>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Photo Details -->
            @if($photo->description)
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph ph-file-text me-2"></i>{{ __('media.description') }}
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $photo->description }}</p>
                </div>
            </div>
            @endif

            <!-- Social Interactions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph ph-heart me-2"></i>{{ __('media.interactions') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-center gap-3">
                        
                        
                        
                    </div>
                </div>
            </div>

            <!-- Comments Section (Sistema Unificato) -->
            

        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Author Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph ph-user me-2"></i>{{ __('media.author') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($photo->user) }}"
                             alt="{{ $photo->user->getDisplayName() }}"
                             class="rounded-circle me-3"
                             style="width: 60px; height: 60px; object-fit: cover;">
                        <div>
                            <h6 class="mb-1">
                                <a href="{{ route('user.show', $photo->user) }}" class="text-decoration-none hover-effect">
                                    {{ $photo->user->getDisplayName() }}
                                </a>
                            </h6>
                            <small class="text-muted">
                                {{ $photo->user->photos_count }} {{ __('media.photos') }}
                            </small>
                        </div>
                    </div>
                    @auth
                        @if(auth()->id() !== $photo->user_id)
                            <button type="button" class="btn btn-primary w-100" onclick="followUser({{ $photo->user->id }})" id="followButton{{ $photo->user->id }}">
                                <i class="ph ph-user me-1"></i>
                                {{ $photo->user->is_followed_by_current_user ?? false ? __('profile.following') : __('profile.follow') }}
                            </button>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Photo Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph ph-info me-2"></i>{{ __('media.photo_info') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <small class="text-muted d-block">{{ __('media.uploaded') }}</small>
                            <strong>{{ $photo->created_at->format('d/m/Y') }}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">{{ __('media.dimensions') }}</small>
                            <strong>{{ $photo->width ?? 'N/A' }}x{{ $photo->height ?? 'N/A' }}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">{{ __('media.file_size') }}</small>
                            <strong>{{ $photo->file_size ?? 'N/A' }}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">{{ __('media.format') }}</small>
                            <strong>{{ strtoupper(pathinfo($photo->image_path, PATHINFO_EXTENSION)) }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions (Edit/Delete) -->
            @can('update', $photo)
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph ph-gear me-2"></i>{{ __('common.actions') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('photos.edit', $photo) }}" class="btn btn-light">
                            <i class="ph ph-pencil-simple me-2"></i>{{ __('photos.edit_photo') }}
                        </a>
                        @can('delete', $photo)
                        <button type="button" class="btn btn-danger" onclick="deletePhoto()">
                            <i class="ph ph-trash me-2"></i>{{ __('photos.delete_photo') }}
                        </button>
                        @endcan
                    </div>
                </div>
            </div>
            @endcan

            <!-- Related Photos -->
            @if($relatedPhotos->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph ph-images me-2"></i>{{ __('media.related_photos') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        @foreach($relatedPhotos as $relatedPhoto)
                            <div class="col-6">
                                <a href="{{ route('photos.show', $relatedPhoto) }}" class="text-decoration-none">
                                    <div class="position-relative rounded overflow-hidden" style="height: 120px;">
                                        <img src="{{ $relatedPhoto->thumbnail_url }}"
                                             alt="{{ $relatedPhoto->title ?: 'Foto di ' . $relatedPhoto->user->getDisplayName() }}"
                                             class="w-100 h-100"
                                             style="object-fit: cover;">
                                        <div class="position-absolute bottom-0 start-0 end-0 p-2" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                                            <small class="text-white">{{ $relatedPhoto->user->getDisplayName() }}</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
function deletePhoto() {
    Swal.fire({
        title: 'Sei sicuro?',
        text: "{{ __('photos.confirm_delete') }}",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sì, elimina!',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch('{{ route("photos.destroy", $photo) }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Eliminata!', data.message, 'success').then(() => {
                        window.location.href = '{{ route("profile.photos") }}';
                    });
                } else {
                    Swal.fire('Errore!', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Errore eliminazione foto:', error);
                Swal.fire('Errore!', '{{ __("photos.delete_error") }}', 'error');
            });
        }
    });
}
</script>
@endpush

@push('scripts')
<script>

// Follow functionality
function followUser(userId) {
    if (!isAuthenticated) {
        window.location.href = '{{ route("login") }}';
        return;
    }

    fetch('/api/follow', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ user_id: userId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const followBtn = document.getElementById(`followButton${userId}`);
            if (data.following) {
                followBtn.innerHTML = '<i class="ph ph-user me-1"></i>{{ __("profile.following") }}';
                followBtn.classList.remove('btn-primary');
                followBtn.classList.add('btn-success');
            } else {
                followBtn.innerHTML = '<i class="ph ph-user me-1"></i>{{ __("profile.follow") }}';
                followBtn.classList.remove('btn-success');
                followBtn.classList.add('btn-primary');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Errore durante l\'operazione', 'error');
    });
}


// Check if user is authenticated
const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
</script>
@endpush
