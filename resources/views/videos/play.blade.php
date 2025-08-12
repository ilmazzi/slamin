@extends('layout.master')

@section('title', $video->title)

@section('main-content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">{{ $video->title }}</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a href="{{ route('dashboard') }}" class="f-s-14 f-w-500">
                            <span>
                                <i class="ph-duotone ph-house f-s-16"></i> {{ __('dashboard.dashboard') }}
                            </span>
                        </a>
                    </li>
                    <li class="">
                        <a href="{{ route('profile.videos') }}" class="f-s-14 f-w-500">
                            <span>
                                <i class="ph-duotone ph-video-camera f-s-16"></i> {{ __('videos.videos') }}
                            </span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="#" class="f-s-14 f-w-500">{{ $video->title }}</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- {{ __('common.video') }} Player -->
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body p-0">
                        <video id="videoPlayer" controls class="w-100" style="max-height: 500px;">
                            <source src="{{ Storage::url($video->file_path) }}" type="video/mp4">
                            Il tuo browser non supporta la riproduzione video.
                        </video>
                    </div>
                </div>

                <!-- {{ __('common.video') }} Info -->
                <div class="card mt-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <h5 class="card-title mb-1">{{ $video->title }}</h5>
                                <p class="text-muted mb-0">
                                    <i class="ph-duotone ph-eye f-s-14 me-1"></i>
                                    <span id="viewCount">{{ $video->view_count }}</span> {{ __('videos.views') }}
                                </p>
                            </div>
                            <div class="d-flex gap-2">
                                @if($video->is_public)
                                    <span class="badge bg-success">
                                        <i class="ph-duotone ph-globe f-s-12 me-1"></i>{{ __('videos.public_badge') }}
                                    </span>
                                @else
                                    <span class="badge bg-warning">
                                        <i class="ph-duotone ph-lock f-s-12 me-1"></i>{{ __('videos.private_badge') }}
                                    </span>
                                @endif

                                @if($video->moderation_status === 'approved')
                                    <span class="badge bg-success">
                                        <i class="ph-duotone ph-check-circle f-s-12 me-1"></i>{{ __('videos.approved_badge') }}
                                    </span>
                                @elseif($video->moderation_status === 'pending')
                                    <span class="badge bg-warning">
                                        <i class="ph-duotone ph-clock f-s-12 me-1"></i>{{ __('videos.pending_badge') }}
                                    </span>
                                @else
                                    <span class="badge bg-danger">
                                        <i class="ph-duotone ph-x-circle f-s-12 me-1"></i>{{ __('videos.rejected_badge') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if($video->description)
                            <p class="card-text">{{ $video->description }}</p>
                        @endif

                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="ph-duotone ph-calendar f-s-12 me-1"></i>
                                {{ __('videos.uploaded_on') }} {{ $video->created_at->format('d/m/Y') }}
                            </small>

                            @if(auth()->id() === $video->user_id)
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-info" onclick="incrementVideoViews()">
                                        <i class="ph-duotone ph-eye f-s-14"></i> {{ __('videos.test_views') }}
                                    </button>
                                    @if(auth()->check() && (auth()->id() === $video->user_id || auth()->user()->hasRole('admin')))
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteVideo({{ $video->id }})">
                                        <i class="ph-duotone ph-trash f-s-14"></i> {{ __('videos.delete_video') }}
                                    </button>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- {{ __('common.video') }} Stats -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="ph-duotone ph-chart-line f-s-16 me-2"></i>
                            Statistiche
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="border-end">
                                    <h5 class="mb-1" id="viewCountStats">{{ $video->view_count }}</h5>
                                    <small class="text-muted">{{ __('videos.views') }}</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border-end">
                                    <h5 class="mb-1">{{ $video->like_count }}</h5>
                                    <small class="text-muted">{{ __('videos.like') }}</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <h5 class="mb-1">{{ $video->comment_count }}</h5>
                                <small class="text-muted">{{ __('common.comments_section') }}</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- {{ __('common.video') }} Details -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="ph-duotone ph-info f-s-16 me-2"></i>
                            Dettagli
                        </h6>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled mb-0">
                            @if($video->duration)
                                <li class="mb-2">
                                    <i class="ph-duotone ph-clock f-s-14 me-2 text-muted"></i>
                                    Durata: {{ $video->formatted_duration }}
                                </li>
                            @endif
                            @if($video->file_size)
                                <li class="mb-2">
                                    <i class="ph-duotone ph-hard-drive f-s-14 me-2 text-muted"></i>
                                    Dimensione: {{ $video->formatted_file_size }}
                                </li>
                            @endif
                            @if($video->resolution)
                                <li class="mb-2">
                                    <i class="ph-duotone ph-monitor f-s-14 me-2 text-muted"></i>
                                    Risoluzione: {{ $video->resolution }}
                                </li>
                            @endif
                            <li class="mb-2">
                                <i class="ph-duotone ph-user f-s-14 me-2 text-muted"></i>
                                Autore: <a href="{{ route('user.show', $video->user) }}" class="text-decoration-none hover-effect">{{ $video->user->getDisplayName() }}</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

<script>
// Funzione globale per incrementare le visualizzazioni
function incrementVideoViews() {
     }} {{ $video->id }}');

    fetch('{{ route("videos.increment-views", $video) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
        }
    })
    .then(response => {
        
        return response.json();
    })
    .then(data => {
        
        if (data.success) {
            document.getElementById('viewCount').textContent = data.view_count;
            document.getElementById('viewCountStats').textContent = data.view_count;
            
        }
    })
    .catch(error => {
        console.error('Errore nell\'incremento delle visualizzazioni:', error);
    });
}

function deleteVideo(videoId) {
    if (confirm('{{ __('videos.delete_confirm_message') }}')) {
        fetch(`/profile/videos/${videoId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            }
        })
        .then(response => {
            if (response.ok) {
                window.location.href = '{{ route("profile.videos") }}';
            } else {
                alert('Errore durante l\'eliminazione del video');
            }
        })
        .catch(error => {
            console.error('Errore:', error);
            alert('Errore durante l\'eliminazione del video');
        });
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const videoPlayer = document.getElementById('videoPlayer');
    let viewIncremented = false;

    // Incrementa le visualizzazioni quando il video inizia a riprodursi
    videoPlayer.addEventListener('play', function() {
        if (!viewIncremented) {
            incrementVideoViews();
            viewIncremented = true;
        }
    });
});
</script>
