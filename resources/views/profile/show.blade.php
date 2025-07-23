@extends('layout.master')

@section('title', $user->getDisplayName() . ' - ' . __('profile.profile') . ' - Slamin')

@section('css')
@endsection

@section('main-content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">{{ $user->getDisplayName() }}</h4>
            <ul class="app-line-breadcrumbs mb-3">
                <li class="">
                    <a href="{{ route('dashboard') }}" class="f-s-14 f-w-500">
                        <span>
                            <i class="ph-duotone ph-house f-s-16"></i> {{ __('dashboard.dashboard') }}
                        </span>
                    </a>
                </li>
                <li class="active">
                    <a href="#" class="f-s-14 f-w-500">{{ __('profile.breadcrumb_profile') }}</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Profile Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card hover-effect">
                <div class="card-body pa-30">
                    <div class="row align-items-center">
                        <!-- Profile Photo -->
                        <div class="col-md-3 text-center">
                            <div class="position-relative d-inline-block">
                                <div class="bg-light-primary h-120 w-120 d-flex-center b-r-50 position-relative overflow-hidden">
                                    <img src="{{ $user->profile_photo_url }}" alt="Profile Photo" class="img-fluid b-r-50">
                                </div>
                                @if($isOwnProfile)
                                <div class="position-absolute bottom-0 end-0">
                                    <button class="btn btn-primary btn-sm rounded-circle" onclick="document.getElementById('profile-photo-input').click()">
                                        <i class="ph ph-camera f-s-14"></i>
                                    </button>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Profile Info -->
                        <div class="col-md-6">
                            <div class="text-center text-md-start">
                                <h3 class="mb-2 f-w-600">{{ $user->getDisplayName() }}</h3>
                                @if($user->nickname && $user->nickname !== $user->name)
                                <p class="text-muted mb-2 f-s-14">{{ $user->nickname }}</p>
                                @endif

                                <!-- Roles -->
                                <div class="mb-3">
                                    @foreach($user->getRoleNames() as $role)
                                    <span class="badge bg-primary me-1 f-s-12">{{ __('auth.role_' . $role) }}</span>
                                    @endforeach
                                </div>

                                <!-- Bio -->
                                @if($user->bio)
                                <p class="text-muted f-s-14 mb-3">{{ $user->bio }}</p>
                                @endif

                                <!-- Location -->
                                @if($user->location)
                                <p class="text-muted f-s-14 mb-0">
                                    <i class="ph ph-map-pin me-1"></i>{{ $user->location }}
                                </p>
                                @endif
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="col-md-3 text-center text-md-end">
                            @if($isOwnProfile)
                            <div class="d-flex flex-column gap-2">
                                <a href="{{ route('profile.edit') }}" class="btn btn-primary hover-effect">
                                    <i class="ph ph-pencil me-2"></i>{{ __('profile.modify_profile') }}
                                </a>
                                <a href="{{ route('profile.videos') }}" class="btn btn-success hover-effect">
                                    <i class="ph ph-video-camera me-2"></i>{{ __('profile.my_videos') }}
                                </a>
                                <a href="{{ route('profile.activity') }}" class="btn btn-info hover-effect">
                                    <i class="ph ph-activity me-2"></i>{{ __('profile.my_activities') }}
                                </a>
                            </div>
                            @else
                            <div class="d-flex flex-column gap-2">
                                <button class="btn btn-primary hover-effect" onclick="followUser({{ $user->id }})">
                                    <i class="ph ph-user-plus me-2"></i>{{ __('profile.follow') }}
                                </button>
                                <button class="btn btn-outline-primary hover-effect" onclick="sendMessage({{ $user->id }})">
                                    <i class="ph ph-message-circle me-2"></i>{{ __('profile.send_message') }}
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-6 col-md-3">
            <div class="card hover-effect equal-card b-t-4-primary">
                <div class="card-body eshop-cards text-center pa-20">
                    <div class="bg-light-primary h-45 w-45 d-flex-center rounded-circle m-auto mb-2">
                        <i class="ph ph-calendar-plus f-s-20 text-primary"></i>
                    </div>
                    <span class="ripple-effect"></span>
                    <div class="overflow-hidden">
                        <h3 class="text-primary mb-1 f-w-600">{{ $stats['total_events'] }}</h3>
                        <p class="f-w-500 text-dark f-s-13 mb-1">{{ __('profile.organized_events') }}</p>
                        <span class="badge bg-light-primary f-s-11">{{ __('profile.role_organizer') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card hover-effect equal-card b-t-4-success">
                <div class="card-body eshop-cards text-center pa-20">
                    <div class="bg-light-success h-45 w-45 d-flex-center rounded-circle m-auto mb-2">
                        <i class="ph ph-users f-s-20 text-success"></i>
                    </div>
                    <span class="ripple-effect"></span>
                    <div class="overflow-hidden">
                        <h3 class="text-success mb-1 f-w-600">{{ $stats['participated_events'] }}</h3>
                        <p class="f-w-500 text-dark f-s-13 mb-1">{{ __('profile.participated_events') }}</p>
                        <span class="badge bg-light-success f-s-11">{{ __('profile.role_participant') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card hover-effect equal-card b-t-4-warning">
                <div class="card-body eshop-cards text-center pa-20">
                    <div class="bg-light-warning h-45 w-45 d-flex-center rounded-circle m-auto mb-2">
                        <i class="ph ph-video-camera f-s-20 text-warning"></i>
                    </div>
                    <span class="ripple-effect"></span>
                    <div class="overflow-hidden">
                        <h3 class="text-warning mb-1 f-w-600">{{ $stats['total_videos'] }}</h3>
                        <p class="f-w-500 text-dark f-s-13 mb-1">{{ __('profile.uploaded_videos') }}</p>
                        <span class="badge bg-light-warning f-s-11">{{ __('profile.role_video') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-md-3">
            <div class="card hover-effect equal-card b-t-4-info">
                <div class="card-body eshop-cards text-center pa-20">
                    <div class="bg-light-info h-45 w-45 d-flex-center rounded-circle m-auto mb-2">
                        <i class="ph ph-clock f-s-20 text-info"></i>
                    </div>
                    <span class="ripple-effect"></span>
                    <div class="overflow-hidden">
                        <h3 class="text-info mb-1 f-w-600">{{ $stats['pending_requests'] }}</h3>
                        <p class="f-w-500 text-dark f-s-13 mb-1">{{ __('profile.pending_requests') }}</p>
                        <span class="badge bg-light-info f-s-11">{{ __('profile.role_pending') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row">
        <!-- Recent Videos -->
        <div class="col-lg-6 mb-4">
            <div class="card hover-effect">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 f-w-600 text-dark">
                            <i class="ph ph-video-camera me-2 text-warning"></i>
                            {{ __('profile.recent_videos') }}
                        </h5>
                        @if($isOwnProfile)
                        <a href="{{ route('profile.videos') }}" class="btn btn-sm btn-warning hover-effect">
                            {{ __('profile.manage_videos') }}
                        </a>
                        @else
                        <a href="#" class="btn btn-sm btn-warning hover-effect">
                            {{ __('profile.view_all_videos') }}
                        </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if($videos->count() > 0)
                    <div class="row">
                        @foreach($videos->take(6) as $video)
                        <div class="col-md-6 mb-3">
                            <div class="card hover-effect">
                                <div class="position-relative">
                                    <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}" class="card-img-top">
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-dark f-s-11">{{ $video->views }} visualizzazioni</span>
                                    </div>
                                </div>
                                <div class="card-body pa-15">
                                    <h6 class="card-title f-w-600 f-s-14 mb-1">{{ $video->title }}</h6>
                                    @if($video->description)
                                    <p class="text-muted f-s-12 mb-2">{{ Str::limit($video->description, 60) }}</p>
                                    @endif
                                    <small class="text-muted f-s-11">{{ $video->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-4">
                        <div class="bg-light-warning h-50 w-50 d-flex-center rounded-circle m-auto mb-2">
                            <i class="ph ph-video-camera-slash f-s-24 text-warning"></i>
                        </div>
                        <p class="text-muted f-s-14 mb-0">{{ __('profile.no_videos_uploaded') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="col-lg-6 mb-4">
            <div class="card hover-effect">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 f-w-600 text-dark">
                            <i class="ph ph-activity me-2 text-info"></i>
                            {{ __('profile.recent_activity') }}
                        </h5>
                        @if($isOwnProfile)
                        <a href="{{ route('profile.activity') }}" class="btn btn-sm btn-info hover-effect">
                            {{ __('profile.view_all_activity') }}
                        </a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if($recentActivity->count() > 0)
                    @foreach($recentActivity->take(5) as $activity)
                    <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                        <div class="flex-shrink-0">
                            <div class="bg-light-{{ $activity['color'] }} h-35 w-35 d-flex-center rounded-circle">
                                <i class="ph {{ $activity['icon'] }} text-{{ $activity['color'] }} f-s-14"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <p class="mb-0 fw-500 f-s-14">{{ $activity['title'] }}</p>
                            <small class="text-muted f-s-12">{{ $activity['date']->diffForHumans() }}</small>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="text-center py-4">
                        <div class="bg-light-info h-50 w-50 d-flex-center rounded-circle m-auto mb-2">
                            <i class="ph ph-activity-slash f-s-24 text-info"></i>
                        </div>
                        <p class="text-muted f-s-14 mb-0">{{ __('profile.no_recent_activity') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Events Section -->
    <div class="row">
        <!-- Organized Events -->
        <div class="col-lg-6 mb-4">
            <div class="card hover-effect">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 f-w-600 text-dark">
                            <i class="ph ph-calendar-plus me-2 text-primary"></i>
                            {{ __('profile.organized_events_title') }}
                        </h5>
                        <a href="#" class="btn btn-sm btn-primary hover-effect">
                            {{ __('profile.view_all_events') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($recentEvents->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                @foreach($recentEvents as $event)
                                <tr>
                                    <td>
                                        <div>
                                            <h6 class="mb-0 f-w-600 f-s-14">{{ $event->title }}</h6>
                                            <small class="text-muted f-s-12">{{ $event->start_datetime->format('d/m/Y H:i') }}</small>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="badge bg-primary f-s-11">{{ $event->status }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <div class="bg-light-primary h-50 w-50 d-flex-center rounded-circle m-auto mb-2">
                            <i class="ph ph-calendar-x f-s-24 text-primary"></i>
                        </div>
                        <p class="text-muted f-s-14 mb-0">{{ __('profile.no_organized_events') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Participated Events -->
        <div class="col-lg-6 mb-4">
            <div class="card hover-effect">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 f-w-600 text-dark">
                            <i class="ph ph-users me-2 text-success"></i>
                            {{ __('profile.participated_events_title') }}
                        </h5>
                        <a href="#" class="btn btn-sm btn-success hover-effect">
                            {{ __('profile.view_all_events') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($participatedEvents->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                @foreach($participatedEvents as $participation)
                                <tr>
                                    <td>
                                        <div>
                                            <h6 class="mb-0 f-w-600 f-s-14">{{ $participation->event->title }}</h6>
                                            <small class="text-muted f-s-12">{{ $participation->event->start_datetime->format('d/m/Y H:i') }}</small>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="badge bg-success f-s-11">Partecipato</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <div class="bg-light-success h-50 w-50 d-flex-center rounded-circle m-auto mb-2">
                            <i class="ph ph-users-slash f-s-24 text-success"></i>
                        </div>
                        <p class="text-muted f-s-14 mb-0">{{ __('profile.no_participated_events') }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden file input for profile photo -->
@if($isOwnProfile)
<input type="file" id="profile-photo-input" style="display: none;" accept="image/*" onchange="uploadProfilePhoto(this)">
@endif

@endsection

@section('script')
<script>
function followUser(userId) {
    // Implementazione follow
    Swal.fire('Info', '{{ __('profile.follow_development') }}', 'info');
}

function sendMessage(userId) {
    // Implementazione messaggi
    Swal.fire('Info', '{{ __('profile.messages_development') }}', 'info');
}

function uploadProfilePhoto(input) {
    if (input.files && input.files[0]) {
        const formData = new FormData();
        formData.append('profile_photo', input.files[0]);
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'PUT');

        fetch('{{ route("profile.update") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                Swal.fire('Errore', data.message, 'error');
            }
        });
    }
}

// Hide loader as fallback
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const loader = document.querySelector('.loader-wrapper');
        if (loader) {
            loader.style.display = 'none';
        }
    }, 1000);
});
</script>
@endsection
