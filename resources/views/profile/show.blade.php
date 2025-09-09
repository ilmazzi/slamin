@extends('layout.master')

@section('title', $user->getDisplayName() . ' - ' . __('profile.profile') . ' - Slamin')

@section('css')
<!-- Slick CSS -->
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/slick/slick.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/slick/slick-theme.css') }}">
<!-- Flag Icons -->
<link rel="stylesheet" href="{{ asset('assets/vendor/flag-icons-master/flag-icon.css') }}">
<style>
/* Stili per i pulsanti delle azioni */
.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 0.375rem;
}

.gap-2 {
    gap: 0.5rem !important;
}

/* Effetti per l'anteprima video */
.video-preview {
    transition: all 0.3s ease;
}

.video-preview:hover {
    transform: scale(1.02);
}

.video-preview:hover .play-button {
    background-color: #667eea !important;
    transform: scale(1.1);
}

.video-preview:hover .play-button i {
    color: white !important;
}

/* Effetti per thumbnail con play button */
.position-relative[onclick] {
    transition: all 0.3s ease;
}

.position-relative[onclick]:hover {
    transform: scale(1.02);
}

.position-relative[onclick]:hover .play-button {
    background-color: #667eea !important;
    transform: scale(1.1);
}

.position-relative[onclick]:hover .play-button i {
    color: white !important;
}

.play-button {
    transition: all 0.3s ease;
}

/* Stili per lo slider delle foto nel profilo */
.profile-photos-slider {
    position: relative;
    margin: 0 -10px;
}

.profile-photos-slider .photo-slide {
    padding: 0 10px;
}

.profile-photos-slider .photo-card {
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    transition: transform 0.3s ease;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.profile-photos-slider .photo-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
}

.profile-photos-slider .photo-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: linear-gradient(transparent, rgba(0, 0, 0, 0.8));
    color: white;
    padding: 20px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.profile-photos-slider .photo-card:hover .photo-overlay {
    opacity: 1;
}

.profile-photos-slider .photo-title {
    font-size: 16px;
    font-weight: 600;
    margin-bottom: 5px;
}

.profile-photos-slider .photo-description {
    font-size: 14px;
    opacity: 0.9;
    margin-bottom: 0;
}
</style>
@endsection

@section('main-content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">{{ $user->getDisplayName() }}</h4>
            <ul class="app-line-breadcrumbs mb-3">
                <li class="">
                    <a href="/" class="f-s-14 f-w-500">
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

    <!-- Mobile Layout: Photo and Cover at Top -->
    <div class="row d-md-none">
        <div class="col-12">
            <!-- Profile Header Mobile -->
            <div class="card mb-3">
                <div class="card-body p-0">
                    <div class="profile-container">
                        <div class="image-details">
                            <div class="profile-image" style="background-image: url('{{ $user->banner_image_url }}')">
                                @if($isOwnProfile)
                                <div class="banner-edit">
                                    <input type="file" id="bannerUploadMobile" accept=".png, .jpg, .jpeg" onchange="uploadBannerImage(this)">
                                    <label for="bannerUploadMobile" class="btn btn-light btn-sm">
                                        <i class="ti ti-photo-heart me-1"></i>{{ __('profile.change_banner') }}
                                    </label>
                                </div>
                                @endif
                            </div>
                            <div class="profile-pic">
                                <div class="avatar-upload">
                                    @if($isOwnProfile)
                                    <div class="avatar-edit">
                                        <input type="file" id="imageUploadMobile" accept=".png, .jpg, .jpeg" onchange="uploadProfilePhoto(this)">
                                        <label for="imageUploadMobile"><i class="ti ti-photo-heart"></i></label>
                                    </div>
                                    @endif
                                    <div class="avatar-preview">
                                                        <div id="imgPreviewMobile">
                    <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($user) }}"
                         alt="{{ __('profile.profile_photo_alt') }}"
                         class="img-fluid h-120 w-120 rounded-circle"
                         style="object-fit: cover;">
                </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="person-details">
                            <h5 class="f-w-600">{{ $user->getDisplayName() }}
                                @if($user->verified_at)
                                <img src="{{ asset('assets/images/profile-app/01.png') }}" class="w-20 h-20" alt="{{ __('profile.verified_check_mark') }}">
                                @endif
                            </h5>
                            @if($user->nickname && $user->nickname !== $user->name)
                            <p>{{ $user->nickname }}</p>
                            @elseif($user->bio)
                            <p>{{ Str::limit($user->bio, 50) }}</p>
                            @else
                            <p>{{ __('profile.member_since') }} {{ $user->created_at->format('M Y') }}</p>
                            @endif

                            <div class="details">
                                <div>
                                    <h4 class="text-primary">{{ $user->videos_count + $user->photos_count + $user->poems_count }}</h4>
                                    <p class="text-secondary">{{ __('profile.posts') }}</p>
                                </div>
                                <div>
                                    <h4 class="text-primary">{{ $user->followers_count }}</h4>
                                    <p class="text-secondary">{{ __('profile.followers') }}</p>
                                </div>
                                <div>
                                    <h4 class="text-primary">{{ $user->following_count }}</h4>
                                    <p class="text-secondary">{{ __('profile.following') }}</p>
                                </div>
                            </div>

                            @if(!$isOwnProfile)
                            <div class="my-2">
                                <button type="button" class="btn btn-primary b-r-22" onclick="followUser({{ $user->id }})" id="followButtonMobile">
                                    <i class="ti ti-user"></i>
                                    {{ $user->is_followed_by_current_user ?? false ? __('profile.following') : __('profile.follow') }}
                                </button>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Layout: Additional Sections -->
    <div class="row d-md-none">
        <div class="col-12">
            <!-- About Me Mobile -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5>{{ __('profile.about_me') }}</h5>
                </div>
                <div class="card-body">
                    @if($user->bio)
                    <p class="text-muted f-s-13">{{ $user->bio }}</p>
                    @else
                    <p class="text-muted f-s-13">{{ __('profile.no_bio_available') }}</p>
                    @endif

                    <div class="about-list">
                        @if($user->nickname)
                        <div>
                            <span class="fw-medium"><i class="ti ti-at"></i> {{ __('profile.nickname') }}</span>
                            <span class="float-end f-s-13 text-secondary">{{ $user->nickname }}</span>
                        </div>
                        @endif
                        @if($user->phone)
                        <div>
                            <span class="fw-medium"><i class="ti ti-phone"></i> {{ __('profile.phone') }}</span>
                            <span class="float-end f-s-13 text-secondary">{{ $user->phone }}</span>
                        </div>
                        @endif
                        @if($user->display_location)
                        <div>
                            <span class="fw-semibold"><i class="ti ti-map-pin"></i> {{ __('profile.location') }}</span>
                            <span class="float-end f-s-13 text-secondary">{{ $user->display_location }}</span>
                        </div>
                        @endif
                        <div>
                            <span class="fw-medium"><i class="ti ti-calendar"></i> {{ __('profile.member_since') }}</span>
                            <span class="float-end f-s-13 text-secondary">{{ $user->created_at->format('M Y') }}</span>
                        </div>
                    </div>

                    <!-- Lingue Conosciute Mobile -->
                    @if($user->languages()->count() > 0)
                    <div class="mt-4">
                        <div class="app-divider-v">
                            <span class="text-primary f-w-600">
                                <i class="ph-duotone ph-translate me-2"></i>
                                {{ __('languages.title') }}
                            </span>
                        </div>
                        <div class="row g-3">
                            @foreach($user->languages_grouped as $languageCode => $languageGroup)
                            <div class="col-12">
                                <div class="card card-light-hover border-0 shadow-sm">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                {!! \App\Helpers\FlagHelper::getFlagIcon($languageCode, '24px') !!}
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-2 fw-semibold text-dark">
                                                    {{ $languageGroup->first()->language_name }}
                                                </h6>
                                                <div class="d-flex flex-wrap gap-2">
                                                    @foreach($languageGroup as $language)
                                                        <span class="badge rounded-pill px-3 py-2 f-s-12 fw-medium
                                                            @if($language->type === 'native') bg-success-subtle text-success border border-success-subtle
                                                            @elseif($language->type === 'spoken') bg-info-subtle text-info border border-info-subtle
                                                            @else bg-warning-subtle text-warning border border-warning-subtle
                                                            @endif">
                                                            {{ $language->competence_description }}
                                                        </span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Statistics Mobile -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5>{{ __('profile.statistics') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="card card-light-primary hover-effect equal-card">
                                <div class="card-body eshop-cards text-center pa-15">
                                    <div class="bg-light-primary h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                                        <i class="ph ph-book-open f-s-18 text-primary"></i>
                                    </div>
                                    <h4 class="text-primary mb-1 f-w-600">{{ $stats['total_poems'] }}</h4>
                                    <p class="f-w-500 text-dark f-s-12 mb-0">{{ __('profile.poems') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card card-light-success hover-effect equal-card">
                                <div class="card-body eshop-cards text-center pa-15">
                                    <div class="bg-light-success h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                                        <i class="ti ti-calendar-plus f-s-18 text-success"></i>
                                    </div>
                                    <h4 class="text-success mb-1 f-w-600">{{ $stats['total_events'] }}</h4>
                                    <p class="f-w-500 text-dark f-s-12 mb-0">{{ __('profile.events') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card card-light-warning hover-effect equal-card">
                                <div class="card-body eshop-cards text-center pa-15">
                                    <div class="bg-light-warning h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                                        <i class="ph ph-newspaper f-s-18 text-warning"></i>
                                    </div>
                                    <h4 class="text-warning mb-1 f-w-600">{{ $stats['total_articles'] }}</h4>
                                    <p class="f-w-500 text-dark f-s-12 mb-0">{{ __('profile.articles') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card card-light-info hover-effect equal-card">
                                <div class="card-body eshop-cards text-center pa-15">
                                    <div class="bg-light-info h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                                        <i class="ph ph-map-pin f-s-18 text-info"></i>
                                    </div>
                                    <h4 class="text-info mb-1 f-w-600">{{ $stats['total_venues'] }}</h4>
                                    <p class="f-w-500 text-dark f-s-12 mb-0">{{ __('profile.venues') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Future Events Mobile -->
            <div class="card mb-3">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('profile.organized_events_title') }}</h5>
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
                                @foreach($recentEvents->take(3) as $event)
                                <tr>
                                    <td>
                                        <div>
                                            <h6 class="mb-0 f-w-600 f-s-14">{{ $event->title }}</h6>
                                            <small class="text-muted f-s-12">
                                                @if($event->start_datetime)
                                                    @if($event->start_datetime)
                                                    {{ $event->start_datetime->format('d/m/Y H:i') }}
                                                @elseif($event->is_availability_based)
                                                    {{ __('events.availability_based_event') }}
                                                @else
                                                    {{ __('events.not_specified') }}
                                                @endif
                                                @elseif($event->is_availability_based)
                                                    {{ __('events.availability_based_event') }}
                                                @else
                                                    {{ __('events.not_specified') }}
                                                @endif
                                            </small>
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
                            <i class="ti ti-calendar-x f-s-24 text-primary"></i>
                        </div>
                        <p class="text-muted f-s-14 mb-0">{{ __('profile.no_organized_events') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Participated Events Mobile -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5>{{ __('profile.participated_events_title') }}</h5>
                </div>
                <div class="card-body">
                    @if($participatedEvents->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                @foreach($participatedEvents->take(3) as $participation)
                                <tr>
                                    <td>
                                        <div>
                                            <h6 class="mb-0 f-w-600 f-s-14">{{ $participation->event->title }}</h6>
                                            <small class="text-muted f-s-12">@if($participation->event->start_datetime)
                                                    {{ $participation->event->start_datetime->format('d/m/Y H:i') }}
                                                @elseif($participation->event->is_availability_based)
                                                    {{ __('events.availability_based_event') }}
                                                @else
                                                    {{ __('events.not_specified') }}
                                                @endif</small>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="badge bg-success f-s-11">{{ __('profile.participated') }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <div class="bg-light-success h-50 w-50 d-flex-center rounded-circle m-auto mb-2">
                            <i class="ti ti-users-slash f-s-24 text-success"></i>
                        </div>
                        <p class="text-muted f-s-14 mb-0">{{ __('profile.no_participated_events') }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions Mobile -->
            @if($isOwnProfile)
            <div class="card mb-3 d-lg-none">
                <div class="card-header">
                    <h5>{{ __('profile.quick_actions') }}</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary hover-effect">
                            <i class="ti ti-edit me-2"></i>{{ __('profile.modify_profile') }}
                        </a>
                        <a href="{{ route('profile.videos') }}" class="btn btn-success hover-effect">
                            <i class="ti ti-video-camera me-2"></i>{{ __('profile.manage_videos') }}
                        </a>
                        <a href="{{ route('articles.create') }}" class="btn btn-warning hover-effect">
                            <i class="ph ph-newspaper me-2"></i>{{ __('articles.create_article') }}
                        </a>
                        <a href="{{ route('profile.activity') }}" class="btn btn-info hover-effect">
                            <i class="ti ti-activity me-2"></i>{{ __('profile.view_my_activities') }}
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Friends Card Mobile (Following) -->
            <div class="card mb-3 d-lg-none">
                <div class="card-header">
                    <h5>{{ __('profile.following') }}</h5>
                </div>
                <div class="card-body profile-friends">
                    @forelse($following as $followedUser)
                    <div class="d-flex align-items-center {{ !$loop->last ? 'mb-3' : '' }}">
                        <div class="h-40 w-40 d-flex-center b-r-50 overflow-hidden bg-light">
                            <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($followedUser) }}"
                                 alt="{{ $followedUser->getDisplayName() }}"
                                 class="img-fluid h-40 w-40 rounded-circle"
                                 style="object-fit: cover;">
                        </div>
                        <div class="flex-grow-1 ps-2">
                            <div class="fw-medium">{{ $followedUser->getDisplayName() }}</div>
                            <div class="text-muted f-s-12">
                                {{ $followedUser->videos_count }} {{ __('profile.videos') }} •
                                {{ $followedUser->photos_count }} {{ __('profile.photos') }} •
                                {{ $followedUser->poems_count }} {{ __('profile.poems') }}
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            @if(auth()->check() && auth()->id() !== $followedUser->id)
                                @if($followedUser->is_followed_by_current_user)
                                    <button class="btn btn-sm btn-outline-secondary unfollow-btn" data-user-id="{{ $followedUser->id }}">
                                        <i class="ti ti-user-minus me-1"></i>{{ __('profile.unfollow') }}
                                    </button>
                                @else
                                    <button class="btn btn-sm btn-primary follow-btn" data-user-id="{{ $followedUser->id }}">
                                        <i class="ti ti-user-plus me-1"></i>{{ __('profile.follow') }}
                                    </button>
                                @endif
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <div class="mb-3">
                            <i class="ti ti-users-slash f-s-24 text-muted"></i>
                        </div>
                        <p class="text-muted f-s-14 mb-0">{{ __('profile.no_following') }}</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Layout -->
    <div class="row d-none d-md-flex">
        <div class="col-lg-3">
            <!-- Profile Tabs -->
            <div class="card">
                <div class="card-body">
                    <div class="tab-wrapper">
                        <ul class="profile-app-tabs">
                            <li class="tab-link fw-medium f-s-16 f-w-600 active" data-tab="1">
                                <i class="ti ti-user fw-bold"></i> {{ __('profile.profile') }}
                            </li>
                            <li class="tab-link fw-medium f-s-16 f-w-600" data-tab="2">
                                <i class="ti ti-device-tv fw-bold"></i> I Miei Media
                                @if($user->photos()->approved()->count() > 0)
                                <span class="badge rounded-pill bg-success badge-notification">
                                    {{ $user->photos()->approved()->count() }}
                                </span>
                                @endif
                            </li>

                            <li class="tab-link fw-medium f-s-16 f-w-600" data-tab="3">
                                <i class="ph ph-newspaper fw-bold"></i> I Miei Articoli
                                @if($stats['total_articles'] > 0)
                                <span class="badge rounded-pill bg-warning badge-notification">
                                    {{ $stats['total_articles'] }}
                                </span>
                                @endif
                            </li>

                            <li class="tab-link fw-medium f-s-16 f-w-600" data-tab="4">
                                <i class="ti ti-calendar fw-bold"></i> {{ __('profile.organized_events_title') }}
                                @if($stats['total_events'] > 0)
                                <span class="badge rounded-pill bg-primary badge-notification">
                                    {{ $stats['total_events'] }}
                                </span>
                                @endif
                            </li>
                            <li class="tab-link fw-medium f-s-16 f-w-600" data-tab="5">
                                <i class="ti ti-activity fw-bold"></i> {{ __('profile.my_activities') }}
                                @if($recentActivity->count() > 0)
                                <span class="badge rounded-pill bg-info badge-notification">
                                    {{ $recentActivity->count() }}
                                </span>
                                @endif
                            </li>
                            @if($isOwnProfile)
                            <li class="tab-link fw-medium f-s-16 f-w-600" data-tab="6">
                                <i class="ti ti-settings fw-bold"></i> {{ __('profile.settings') }}
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            @if($isOwnProfile)
            <div class="card d-lg-block d-none">
                <div class="card-header">
                    <h5>{{ __('profile.quick_actions') }}</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column gap-2">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary hover-effect">
                            <i class="ti ti-edit me-2"></i>{{ __('profile.modify_profile') }}
                        </a>
                        <a href="{{ route('profile.videos') }}" class="btn btn-success hover-effect">
                            <i class="ti ti-video-camera me-2"></i>{{ __('profile.manage_videos') }}
                        </a>
                        <a href="{{ route('articles.create') }}" class="btn btn-warning hover-effect">
                            <i class="ph ph-newspaper me-2"></i>{{ __('articles.create_article') }}
                        </a>
                        <a href="{{ route('profile.activity') }}" class="btn btn-info hover-effect">
                            <i class="ti ti-activity me-2"></i>{{ __('profile.view_all_activity') }}
                        </a>
                        <a href="{{ route('profile.languages.index') }}" class="btn btn-secondary hover-effect">
                            <i class="ph-duotone ph-translate me-2"></i>{{ __('languages.title') }}
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Friends Card (Following) -->
            <div class="card d-lg-block d-none">
                <div class="card-header">
                    <h5>{{ __('profile.following') }}</h5>
                </div>
                <div class="card-body profile-friends">
                    @forelse($following as $followedUser)
                    <div class="d-flex align-items-center {{ !$loop->last ? 'mb-3' : '' }}">
                        <div class="h-40 w-40 d-flex-center b-r-50 overflow-hidden bg-light">
                            <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($followedUser) }}"
                                 alt="{{ $followedUser->getDisplayName() }}"
                                 class="img-fluid h-40 w-40 rounded-circle"
                                 style="object-fit: cover;">
                        </div>
                        <div class="flex-grow-1 ps-2">
                            <div class="fw-medium">{{ $followedUser->getDisplayName() }}</div>
                            <div class="text-muted f-s-12">
                                {{ $followedUser->videos_count + $followedUser->photos_count + $followedUser->poems_count }} contenuti
                            </div>
                        </div>
                        @auth
                        <button type="button" class="btn btn-light-secondary icon-btn b-r-22" onclick="followUser({{ $followedUser->id }})" id="followBtn{{ $followedUser->id }}">
                            <i class="ti {{ $followedUser->is_followed_by_current_user ?? false ? 'ti-user-check' : 'ti-user' }}"></i>
                        </button>
                        @else
                        <div class="btn btn-light-secondary icon-btn b-r-22" style="opacity: 0.6;">
                            <i class="ti ti-user"></i>
                        </div>
                        @endauth
                    </div>
                    @empty
                    <div class="text-center text-muted py-3">
                        <i class="ti ti-users f-s-24 mb-2"></i>
                        <p class="mb-0">{{ __('profile.no_following') }}</p>
                    </div>
                    @endforelse

                    @if($following->count() > 0)
                    <div class="text-center mt-3">
                        <a href="{{ route('profile.following') }}" class="btn btn-outline-primary btn-sm">
                            {{ __('profile.view_all_following') }}
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-5 col-xxl-6 col-box-5">
            <!-- Profile Content -->
            <div class="content-wrapper">
                <!-- Tab 1: Profile -->
                <div id="tab-1" class="tabs-content active">
                    <div class="profile-content">
                        <!-- Profile Header -->
                        <div class="card">
                            <div class="card-body">
                                <div class="profile-container">
                                    <div class="image-details">
                                        <div class="profile-image" style="background-image: url('{{ $user->banner_image_url }}')">
                                            @if($isOwnProfile)
                                            <div class="banner-edit">
                                                <input type="file" id="bannerUpload" accept=".png, .jpg, .jpeg" onchange="uploadBannerImage(this)">
                                                <label for="bannerUpload" class="btn btn-light btn-sm">
                                                    <i class="ti ti-photo-heart me-1"></i>{{ __('profile.change_banner') }}
                                                </label>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="profile-pic">
                                            <div class="avatar-upload">
                                                @if($isOwnProfile)
                                                <div class="avatar-edit">
                                                    <input type="file" id="imageUpload" accept=".png, .jpg, .jpeg" onchange="uploadProfilePhoto(this)">
                                                    <label for="imageUpload"><i class="ti ti-photo-heart"></i></label>
                                                </div>
                                                @endif
                                                <div class="avatar-preview">
                                                                                                            <div id="imgPreview">
                    <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($user) }}"
                         alt="{{ __('profile.profile_photo_alt') }}"
                         class="img-fluid h-120 w-120 rounded-circle"
                         style="object-fit: cover;">
                </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="person-details">
                                        <h5 class="f-w-600">{{ $user->getDisplayName() }}
                                            @if($user->verified_at)
                                            <img src="{{ asset('assets/images/profile-app/01.png') }}" class="w-20 h-20" alt="{{ __('profile.verified_check_mark') }}">
                                            @endif
                                        </h5>
                                        @if($user->nickname && $user->nickname !== $user->name)
                                        <p>{{ $user->nickname }}</p>
                                        @elseif($user->bio)
                                        <p>{{ Str::limit($user->bio, 50) }}</p>
                                        @else
                                        <p>{{ __('profile.member_since') }} {{ $user->created_at->format('M Y') }}</p>
                                        @endif
                                        <div class="details">
                                            <div>
                                                <h4 class="text-primary">{{ $user->videos_count + $user->photos_count + $user->poems_count }}</h4>
                                                <p class="text-secondary">{{ __('profile.posts') }}</p>
                                            </div>
                                            <div>
                                                <h4 class="text-primary">{{ $user->followers_count }}</h4>
                                                <p class="text-secondary">{{ __('profile.followers') }}</p>
                                            </div>
                                            <div>
                                                <h4 class="text-primary">{{ $user->following_count }}</h4>
                                                <p class="text-secondary">{{ __('profile.following') }}</p>
                                            </div>
                                        </div>
                                        @if(!$isOwnProfile)
                                        <div class="my-2">
                                            <button type="button" class="btn btn-primary b-r-22" onclick="followUser({{ $user->id }})" id="followButton">
                                                <i class="ti ti-user"></i>
                                                {{ $user->is_followed_by_current_user ?? false ? __('profile.following') : __('profile.follow') }}
                                            </button>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- About Me -->
                        <div class="card">
                            <div class="card-header">
                                <h5>{{ __('profile.about_me') }}</h5>
                            </div>
                            <div class="card-body">
                                @if($user->bio)
                                <p class="text-muted f-s-13">{{ $user->bio }}</p>
                                @else
                                <p class="text-muted f-s-13">{{ __('profile.no_bio_available') }}</p>
                                @endif

                                <div class="about-list">
                                    @if($user->nickname)
                                    <div>
                                        <span class="fw-medium"><i class="ti ti-at"></i> {{ __('profile.nickname') }}</span>
                                        <span class="float-end f-s-13 text-secondary">{{ $user->nickname }}</span>
                                    </div>
                                    @endif
                                    @if($user->phone)
                                    <div>
                                        <span class="fw-medium"><i class="ti ti-phone"></i> {{ __('profile.phone') }}</span>
                                        <span class="float-end f-s-13 text-secondary">{{ $user->phone }}</span>
                                    </div>
                                    @endif
                                    @if($user->display_location)
                                    <div>
                                        <span class="fw-semibold"><i class="ti ti-map-pin"></i> {{ __('profile.location') }}</span>
                                        <span class="float-end f-s-13 text-secondary">{{ $user->display_location }}</span>
                                    </div>
                                    @endif
                                    <div>
                                        <span class="fw-medium"><i class="ti ti-calendar"></i> {{ __('profile.member_since') }}</span>
                                        <span class="float-end f-s-13 text-secondary">{{ $user->created_at->format('M Y') }}</span>
                                    </div>
                                </div>

                                <!-- Lingue Conosciute -->
                                @if($user->languages()->count() > 0)
                                <div class="mt-4">
                                    <div class="app-divider-v">
                                        <span class="text-primary f-w-600">
                                            <i class="ph-duotone ph-translate me-2"></i>
                                            {{ __('languages.title') }}
                                        </span>
                                    </div>
                                    <div class="row g-3">
                                        @foreach($user->languages_grouped as $languageCode => $languageGroup)
                                        <div class="col-12">
                                            <div class="card card-light-hover border-0 shadow-sm">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-3">
                                                            {!! \App\Helpers\FlagHelper::getFlagIcon($languageCode, '24px') !!}
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-2 fw-semibold text-dark">
                                                                {{ $languageGroup->first()->language_name }}
                                                            </h6>
                                                            <div class="d-flex flex-wrap gap-2">
                                                                @foreach($languageGroup as $language)
                                                                    <span class="badge rounded-pill px-3 py-2 f-s-12 fw-medium
                                                                        @if($language->type === 'native') bg-success-subtle text-success border border-success-subtle
                                                                        @elseif($language->type === 'spoken') bg-info-subtle text-info border border-info-subtle
                                                                        @else bg-warning-subtle text-warning border border-warning-subtle
                                                                        @endif">
                                                                        {{ $language->competence_description }}
                                                                    </span>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Media (Videos & Photos) -->
                <div id="tab-2" class="tabs-content">
                    <!-- Videos Section -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">{{ __('profile.my_videos') }}</h5>
                                @if($isOwnProfile)
                                <a href="{{ route('profile.videos') }}" class="btn btn-sm btn-warning hover-effect">
                                    {{ __('profile.manage_videos') }}
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
                                            @if($video->thumbnail_url && $video->thumbnail_url !== asset('assets/images/placeholder/placholder-1.jpg'))
                                                <!-- {{ __('common.thumbnail') }} con overlay play -->
                                                <div class="position-relative" style="cursor: pointer;" onclick="window.location.href='{{ route('videos.show', $video) }}'">
                                                    <img src="{{ $video->thumbnail_url }}" alt="{{ $video->title }}" class="card-img-top" style="height: 200px; object-fit: cover;">
                                                    <!-- Overlay play button -->
                                                    <div class="position-absolute top-50 start-50 translate-middle">
                                                        <div class="play-button bg-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; box-shadow: 0 4px 15px rgba(0,0,0,0.3); transition: all 0.3s ease;">
                                                            <i class="ph-duotone ph-play f-s-24 text-primary"></i>
                                                        </div>
                                                    </div>
                                                    <!-- Duration overlay -->
                                                    <div class="position-absolute bottom-0 start-0 end-0 p-3" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                                                        <small class="text-white f-s-12">
                                                            <i class="ph-duotone ph-clock me-1"></i>
                                                            @if($video->duration && $video->duration > 0)
                                                                {{ $video->formatted_duration }}
                                                            @else
                                                                <span title="{{ __('videos.duration_unavailable') }}">--:--</span>
                                                            @endif
                                                        </small>
                                                    </div>
                                                    <!-- Views badge -->
                                                    <div class="position-absolute top-0 end-0 m-2">
                                                        <span class="badge bg-dark f-s-11">{{ $video->view_count ?? $video->views }} {{ __('profile.views') }}</span>
                                                    </div>
                                                </div>
                                            @elseif($video->peertube_uuid)
                                                <!-- Anteprima video con overlay play -->
                                                <div class="card-img-top video-preview bg-gradient-primary d-flex align-items-center justify-content-center position-relative"
                                                     style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); cursor: pointer;"
                                                     onclick="window.location.href='{{ route('videos.show', $video) }}'">
                                                    <div class="position-absolute top-50 start-50 translate-middle">
                                                        <div class="play-button bg-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; box-shadow: 0 4px 15px rgba(0,0,0,0.3); transition: all 0.3s ease;">
                                                            <i class="ph-duotone ph-play f-s-24 text-primary"></i>
                                                        </div>
                                                    </div>
                                                    <div class="position-absolute bottom-0 start-0 end-0 p-3" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                                                        <small class="text-white f-s-12">
                                                            <i class="ph-duotone ph-clock me-1"></i>
                                                            @if($video->duration && $video->duration > 0)
                                                                {{ $video->formatted_duration }}
                                                            @else
                                                                <span title="{{ __('videos.duration_unavailable') }}">--:--</span>
                                                            @endif
                                                        </small>
                                                    </div>
                                                    <!-- Views badge -->
                                                    <div class="position-absolute top-0 end-0 m-2">
                                                        <span class="badge bg-dark f-s-11">{{ $video->view_count ?? $video->views }} {{ __('profile.views') }}</span>
                                                    </div>
                                                </div>
                                            @else
                                                <!-- Fallback per video senza thumbnail -->
                                                <div class="position-relative" style="cursor: pointer;" onclick="window.location.href='{{ route('videos.show', $video) }}'">
                                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                                        <div class="text-center">
                                                            <i class="ph-duotone ph-video-camera f-s-48 text-muted mb-2"></i>
                                                            <div class="play-button bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 60px; height: 60px; box-shadow: 0 4px 15px rgba(0,0,0,0.3); transition: all 0.3s ease;">
                                                                <i class="ph-duotone ph-play f-s-24 text-white"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Views badge -->
                                                    <div class="position-absolute top-0 end-0 m-2">
                                                        <span class="badge bg-dark f-s-11">{{ $video->view_count ?? $video->views }} {{ __('profile.views') }}</span>
                                                    </div>
                                                </div>
                                            @endif
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
                                    <i class="ti ti-video-camera-slash f-s-24 text-warning"></i>
                                </div>
                                <p class="text-muted f-s-14 mb-0">{{ __('profile.no_videos_uploaded') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Articles Section -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">{{ __('profile.my_articles') }}</h5>
                                @if($isOwnProfile)
                                <a href="{{ route('articles.create') }}" class="btn btn-sm btn-success hover-effect">
                                    <i class="ph ph-plus me-1"></i>{{ __('articles.create_article') }}
                                </a>
                                @endif
                            </div>
                        </div>
                        <div class="card-body" id="articles-container">
                            @if($articles->count() > 0)
                            <div class="row">
                                @foreach($articles as $article)
                                <div class="col-md-6 mb-3">
                                    <div class="card hover-effect">
                                        <div class="position-relative">
                                            @if($article->featured_image)
                                                <img src="{{ Storage::url($article->featured_image) }}"
                                                     alt="{{ $article->title }}" class="card-img-top"
                                                     style="height: 200px; object-fit: cover;">
                                            @else
                                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                                     style="height: 200px;">
                                                    <div class="text-center">
                                                        <i class="ph ph-newspaper f-s-48 text-muted mb-2"></i>
                                                        <div class="f-s-16 f-w-600 text-muted">{{ __('articles.article') }}</div>
                                                    </div>
                                                </div>
                                            @endif
                                            <!-- Status badge -->
                                            <div class="position-absolute top-0 start-0 m-2">
                                                @if($article->featured)
                                                    <span class="badge bg-warning f-s-11">{{ __('articles.featured') }}</span>
                                                @elseif($article->status === 'published')
                                                    <span class="badge bg-success f-s-11">{{ __('articles.published') }}</span>
                                                @else
                                                    <span class="badge bg-secondary f-s-11">{{ __('articles.draft') }}</span>
                                                @endif
                                            </div>
                                            <!-- Views badge -->
                                            <div class="position-absolute top-0 end-0 m-2">
                                                <span class="badge bg-dark f-s-11">{{ $article->views_count ?? 0 }} {{ __('profile.views') }}</span>
                                            </div>
                                        </div>
                                        <div class="card-body pa-15">
                                            <h6 class="card-title f-w-600 f-s-14 mb-1">
                                                <a href="{{ route('articles.show', $article) }}" class="text-decoration-none">
                                                    {{ $article->title }}
                                                </a>
                                            </h6>
                                            @if($article->excerpt)
                                            <p class="text-muted f-s-12 mb-2">{{ Str::limit($article->excerpt, 80) }}</p>
                                            @endif
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted f-s-11">{{ $article->created_at->diffForHumans() }}</small>
                                                <div class="d-flex gap-1">
                                                    <small class="text-muted f-s-11">
                                                        <i class="ph ph-heart me-1"></i>{{ $article->likes_count }}
                                                    </small>
                                                    <small class="text-muted f-s-11">
                                                        <i class="ph ph-chat-circle me-1"></i>{{ $article->comments_count }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Paginazione -->
                            @if($articles->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                <ul class="pagination app-pagination" id="articles-pagination">
                                    @if($articles->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link b-r-left">Previous</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link b-r-left" href="javascript:void(0)" data-page="{{ $articles->currentPage() - 1 }}">Previous</a>
                                        </li>
                                    @endif

                                    @foreach($articles->getUrlRange(1, $articles->lastPage()) as $page => $url)
                                        <li class="page-item {{ $page == $articles->currentPage() ? 'active' : '' }}">
                                            <a class="page-link" href="javascript:void(0)" data-page="{{ $page }}">{{ $page }}</a>
                                        </li>
                                    @endforeach

                                    @if($articles->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="javascript:void(0)" data-page="{{ $articles->currentPage() + 1 }}">Next</a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link">Next</span>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                            @endif
                            @else
                            <div class="text-center py-4">
                                <div class="bg-light-info h-50 w-50 d-flex-center rounded-circle m-auto mb-2">
                                    <i class="ph ph-newspaper f-s-24 text-info"></i>
                                </div>
                                <p class="text-muted f-s-14 mb-0">{{ __('profile.no_articles_written') }}</p>
                                @if($isOwnProfile)
                                <a href="{{ route('articles.create') }}" class="btn btn-sm btn-primary mt-2">
                                    <i class="ph ph-plus me-1"></i>{{ __('articles.create_first_article') }}
                                </a>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Photos Section -->
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Le Mie Foto</h5>
                                @if($isOwnProfile)
                                <button type="button" class="btn btn-sm btn-primary hover-effect" onclick="openPhotoUploadModal()">
                                    <i class="ph ph-plus me-1"></i>Carica Foto
                                </button>
                                @endif
                            </div>
                        </div>
                        <div class="card-body">
                            @if($user->photos()->approved()->count() > 0)
                                <div class="profile-photos-slider app-arrow" id="profile-photos-slider">
                                    @foreach($user->photos()->approved()->orderBy('created_at', 'desc')->take(10)->get() as $photo)
                                    <div class="photo-slide">
                                        <div class="photo-card hover-effect">
                                            <img src="{{ $photo->image_url }}" class="img-fluid rounded" alt="{{ $photo->alt_text ?: $photo->title ?: 'Foto di ' . $user->getDisplayName() }}" style="width: 100%; height: 400px; object-fit: cover;">
                                            @if($photo->title || $photo->description)
                                            <div class="photo-overlay">
                                                <div class="photo-info">
                                                    @if($photo->title)
                                                    <h6 class="photo-title">{{ $photo->title }}</h6>
                                                    @endif
                                                    @if($photo->description)
                                                    <p class="photo-description">{{ Str::limit($photo->description, 100) }}</p>
                                                    @endif
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-4">
                                    <div class="bg-light-info h-50 w-50 d-flex-center rounded-circle m-auto mb-2">
                                        <i class="ti ti-photo-slash f-s-24 text-info"></i>
                                    </div>
                                    <p class="text-muted f-s-14 mb-0">Nessuna foto caricata</p>
                                    @if($isOwnProfile)
                                    <button type="button" class="btn btn-sm btn-primary mt-2" onclick="openPhotoUploadModal()">
                                        <i class="ph ph-plus me-1"></i>Carica la Prima Foto
                                    </button>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                                <!-- Tab 3: Articles -->
                <div id="tab-3" class="tabs-content">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">{{ __('profile.my_articles') }}</h5>
                                @if($isOwnProfile)
                                <a href="{{ route('articles.create') }}" class="btn btn-sm btn-success hover-effect">
                                    <i class="ph ph-plus me-1"></i>{{ __('articles.create_article') }}
                                </a>
                                @endif
                            </div>
                        </div>
                        <div class="card-body" id="articles-container-tab">
                            @if($articles->count() > 0)
                            <div class="row">
                                @foreach($articles as $article)
                                <div class="col-md-6 mb-3">
                                    <div class="card hover-effect">
                                        <div class="position-relative">
                                            @if($article->featured_image)
                                                <img src="{{ Storage::url($article->featured_image) }}"
                                                     alt="{{ $article->title }}" class="card-img-top"
                                                     style="height: 200px; object-fit: cover;">
                                            @else
                                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                                                     style="height: 200px;">
                                                    <div class="text-center">
                                                        <i class="ph ph-newspaper f-s-48 text-muted mb-2"></i>
                                                        <div class="f-s-16 f-w-600 text-muted">{{ __('articles.article') }}</div>
                                                    </div>
                                                </div>
                                            @endif
                                            <!-- Status badge -->
                                            <div class="position-absolute top-0 start-0 m-2">
                                                @if($article->featured)
                                                    <span class="badge bg-warning f-s-11">{{ __('articles.featured') }}</span>
                                                @elseif($article->status === 'published')
                                                    <span class="badge bg-success f-s-11">{{ __('articles.published') }}</span>
                                                @else
                                                    <span class="badge bg-secondary f-s-11">{{ __('articles.draft') }}</span>
                                                @endif
                                            </div>
                                            <!-- Views badge -->
                                            <div class="position-absolute top-0 end-0 m-2">
                                                <span class="badge bg-dark f-s-11">{{ $article->views_count ?? 0 }} {{ __('profile.views') }}</span>
                                            </div>
                                        </div>
                                        <div class="card-body pa-15">
                                            <h6 class="card-title f-w-600 f-s-14 mb-1">
                                                <a href="{{ route('articles.show', $article) }}" class="text-decoration-none">
                                                    {{ $article->title }}
                                                </a>
                                            </h6>
                                            @if($article->excerpt)
                                            <p class="text-muted f-s-12 mb-2">{{ Str::limit($article->excerpt, 80) }}</p>
                                            @endif
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted f-s-11">{{ $article->created_at->diffForHumans() }}</small>
                                                <div class="d-flex gap-1">
                                                    <small class="text-muted f-s-11">
                                                        <i class="ph ph-heart me-1"></i>{{ $article->likes_count }}
                                                    </small>
                                                    <small class="text-muted f-s-11">
                                                        <i class="ph ph-chat-circle me-1"></i>{{ $article->comments_count }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <!-- Paginazione -->
                            @if($articles->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                <ul class="pagination app-pagination" id="articles-pagination-tab">
                                    @if($articles->onFirstPage())
                                        <li class="page-item disabled">
                                            <span class="page-link b-r-left">Previous</span>
                                        </li>
                                    @else
                                        <li class="page-item">
                                            <a class="page-link b-r-left" href="javascript:void(0)" data-page="{{ $articles->currentPage() - 1 }}">Previous</a>
                                        </li>
                                    @endif

                                    @foreach($articles->getUrlRange(1, $articles->lastPage()) as $page => $url)
                                        <li class="page-item {{ $page == $articles->currentPage() ? 'active' : '' }}">
                                            <a class="page-link" href="javascript:void(0)" data-page="{{ $page }}">{{ $page }}</a>
                                        </li>
                                    @endforeach

                                    @if($articles->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="javascript:void(0)" data-page="{{ $articles->currentPage() + 1 }}">Next</a>
                                        </li>
                                    @else
                                        <li class="page-item disabled">
                                            <span class="page-link">Next</span>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                            @endif
                            @else
                            <div class="text-center py-4">
                                <div class="bg-light-warning h-50 w-50 d-flex-center rounded-circle m-auto mb-2">
                                    <i class="ph ph-newspaper f-s-24 text-warning"></i>
                                </div>
                                <p class="text-muted f-s-14 mb-0">{{ __('profile.no_articles_written') }}</p>
                                @if($isOwnProfile)
                                <a href="{{ route('articles.create') }}" class="btn btn-sm btn-primary mt-2">
                                    <i class="ph ph-plus me-1"></i>{{ __('articles.create_first_article') }}
                                </a>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Tab 4: Organized Events -->
                <div id="tab-4" class="tabs-content">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">{{ __('profile.organized_events_title') }}</h5>
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
                                                    <small class="text-muted f-s-12">@if($event->start_datetime)
                                                    {{ $event->start_datetime->format('d/m/Y H:i') }}
                                                @elseif($event->is_availability_based)
                                                    {{ __('events.availability_based_event') }}
                                                @else
                                                    {{ __('events.not_specified') }}
                                                @endif</small>
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
                                    <i class="ti ti-calendar-x f-s-24 text-primary"></i>
                                </div>
                                <p class="text-muted f-s-14 mb-0">{{ __('profile.no_organized_events') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Tab 5: Activities -->
                <div id="tab-5" class="tabs-content">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">{{ __('profile.recent_activity') }}</h5>
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
                                        <i class="ti {{ $activity['icon'] }} text-{{ $activity['color'] }} f-s-14"></i>
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
                                    <i class="ti ti-activity-slash f-s-24 text-info"></i>
                                </div>
                                <p class="text-muted f-s-14 mb-0">{{ __('profile.no_recent_activity') }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>



                <!-- Tab 6: Settings (only for own profile) -->
                @if($isOwnProfile)
                <div id="tab-6" class="tabs-content">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('profile.settings') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="card card-light-primary hover-effect">
                                        <div class="card-body text-center">
                                            <div class="bg-light-primary h-50 w-50 d-flex-center rounded-circle m-auto mb-3">
                                                <i class="ti ti-edit f-s-24 text-primary"></i>
                                            </div>
                                            <h6 class="mb-2">{{ __('profile.modify_profile') }}</h6>
                                            <p class="text-muted f-s-12 mb-3">{{ __('profile.modify_profile_desc') }}</p>
                                            <a href="{{ route('profile.edit') }}" class="btn btn-primary btn-sm">
                                                <i class="ti ti-edit me-1"></i>{{ __('profile.edit_profile') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card card-light-success hover-effect">
                                        <div class="card-body text-center">
                                            <div class="bg-light-success h-50 w-50 d-flex-center rounded-circle m-auto mb-3">
                                                <i class="ti ti-video-camera f-s-24 text-success"></i>
                                            </div>
                                            <h6 class="mb-2">{{ __('profile.manage_videos') }}</h6>
                                            <p class="text-muted f-s-12 mb-3">{{ __('profile.manage_videos_desc') }}</p>
                                            <a href="{{ route('profile.videos') }}" class="btn btn-success btn-sm">
                                                <i class="ti ti-video-camera me-1"></i>{{ __('profile.manage_videos') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card card-light-info hover-effect">
                                        <div class="card-body text-center">
                                            <div class="bg-light-info h-50 w-50 d-flex-center rounded-circle m-auto mb-3">
                                                <i class="ti ti-activity f-s-24 text-info"></i>
                                            </div>
                                            <h6 class="mb-2">{{ __('profile.view_all_activity') }}</h6>
                                            <p class="text-muted f-s-12 mb-3">{{ __('profile.view_all_activity_desc') }}</p>
                                            <a href="{{ route('profile.activity') }}" class="btn btn-info btn-sm">
                                                <i class="ti ti-activity me-1"></i>{{ __('profile.view_all_activity') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card card-light-warning hover-effect">
                                        <div class="card-body text-center">
                                            <div class="bg-light-warning h-50 w-50 d-flex-center rounded-circle m-auto mb-3">
                                                <i class="ti ti-shield f-s-24 text-warning"></i>
                                            </div>
                                            <h6 class="mb-2">Impostazioni Privacy</h6>
                                            <p class="text-muted f-s-12 mb-3">Gestisci le impostazioni di privacy del tuo profilo</p>
                                            <button class="btn btn-warning btn-sm" onclick="Swal.fire('Info', 'Funzionalità in sviluppo', 'info')">
                                                <i class="ti ti-shield me-1"></i>Impostazioni Privacy
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="card card-light-secondary hover-effect">
                                        <div class="card-body text-center">
                                            <div class="bg-light-secondary h-50 w-50 d-flex-center rounded-circle m-auto mb-3">
                                                <i class="ph-duotone ph-translate f-s-24 text-secondary"></i>
                                            </div>
                                            <h6 class="mb-2">{{ __('languages.title') }}</h6>
                                            <p class="text-muted f-s-12 mb-3">{{ __('languages.manage_description') }}</p>
                                            <a href="{{ route('profile.languages.index') }}" class="btn btn-secondary btn-sm">
                                                <i class="ph-duotone ph-translate me-1"></i>{{ __('languages.title') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="col-lg-4 col-xxl-3 col-box-4 order-lg--1">
            <!-- Statistics Cards -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5>{{ __('profile.statistics') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="card card-light-primary hover-effect equal-card">
                                <div class="card-body eshop-cards text-center pa-15">
                                    <div class="bg-light-primary h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                                        <i class="ph ph-book-open f-s-18 text-primary"></i>
                                    </div>
                                    <h4 class="text-primary mb-1 f-w-600">{{ $stats['total_poems'] }}</h4>
                                    <p class="f-w-500 text-dark f-s-12 mb-0">{{ __('profile.poems') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card card-light-success hover-effect equal-card">
                                <div class="card-body eshop-cards text-center pa-15">
                                    <div class="bg-light-success h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                                        <i class="ti ti-calendar-plus f-s-18 text-success"></i>
                                    </div>
                                    <h4 class="text-success mb-1 f-w-600">{{ $stats['total_events'] }}</h4>
                                    <p class="f-w-500 text-dark f-s-12 mb-0">{{ __('profile.events') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card card-light-warning hover-effect equal-card">
                                <div class="card-body eshop-cards text-center pa-15">
                                    <div class="bg-light-warning h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                                        <i class="ph ph-newspaper f-s-18 text-warning"></i>
                                    </div>
                                    <h4 class="text-warning mb-1 f-w-600">{{ $stats['total_articles'] }}</h4>
                                    <p class="f-w-500 text-dark f-s-12 mb-0">{{ __('profile.articles') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card card-light-info hover-effect equal-card">
                                <div class="card-body eshop-cards text-center pa-15">
                                    <div class="bg-light-info h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                                        <i class="ph ph-map-pin f-s-18 text-info"></i>
                                    </div>
                                    <h4 class="text-info mb-1 f-w-600">{{ $stats['total_venues'] }}</h4>
                                    <p class="f-w-500 text-dark f-s-12 mb-0">{{ __('profile.venues') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Participated Events -->
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('profile.participated_events_title') }}</h5>
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
                                            <small class="text-muted f-s-12">@if($participation->event->start_datetime)
                                                    {{ $participation->event->start_datetime->format('d/m/Y H:i') }}
                                                @elseif($participation->event->is_availability_based)
                                                    {{ __('events.availability_based_event') }}
                                                @else
                                                    {{ __('events.not_specified') }}
                                                @endif</small>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="badge bg-success f-s-11">{{ __('profile.participated') }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <div class="bg-light-success h-50 w-50 d-flex-center rounded-circle m-auto mb-2">
                            <i class="ti ti-users-slash f-s-24 text-success"></i>
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

@push('scripts')
<!-- Slick JS -->
<script src="{{ asset('assets/vendor/slick/slick.min.js') }}"></script>
<script src="{{ asset('assets/js/slick.js') }}"></script>

<script>
// Tab functionality
document.addEventListener('DOMContentLoaded', function() {
    // Tab functionality
    const tabLinks = document.querySelectorAll('.tab-link');
    const tabContents = document.querySelectorAll('.tabs-content');

    tabLinks.forEach(link => {
        link.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');

            // Remove active class from all tabs and contents
            tabLinks.forEach(l => l.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));

            // Add active class to clicked tab and corresponding content
            this.classList.add('active');
            document.getElementById('tab-' + tabId).classList.add('active');
        });
    });

    // Hide loader as fallback
    setTimeout(function() {
        const loader = document.querySelector('.loader-wrapper');
        if (loader) {
            loader.style.display = 'none';
        }
    }, 1000);

    // Inizializzazione dello slider delle foto
    // Verifica se jQuery è disponibile
    if (typeof $ === 'undefined') {
        console.error('jQuery non è caricato!');
        return;
    }

    // Verifica se Slick è disponibile
    if (typeof $.fn.slick === 'undefined') {
        console.error('Slick non è caricato!');
        return;
    }

    // Inizializza lo slider delle foto del profilo
    const $profilePhotosSlider = $('#profile-photos-slider');
    if ($profilePhotosSlider.length > 0) {
        $profilePhotosSlider.slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 5000,
            arrows: true,
            dots: true,
            infinite: true,
            speed: 500,
            fade: true,
            cssEase: 'linear',
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 1,
                        dots: true
                    }
                }
            ]
        });
    }
});

function followUser(userId) {
    // Verifica se l'utente è autenticato
    const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};

    if (!isAuthenticated) {
        window.location.href = '{{ route("login") }}';
        return;
    }

    // Trova entrambi i pulsanti (desktop e mobile)
    const button = document.getElementById('followBtn' + userId);
    const buttonMobile = document.getElementById('followButtonMobile');
    const buttonDesktop = document.getElementById('followButton');

    // Disabilita i pulsanti durante la richiesta
    if (button) button.disabled = true;
    if (buttonMobile) buttonMobile.disabled = true;
    if (buttonDesktop) buttonDesktop.disabled = true;

    fetch('/api/follow/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            user_id: userId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Aggiorna tutti i pulsanti
            const buttonsToUpdate = [button, buttonMobile, buttonDesktop].filter(btn => btn);

            buttonsToUpdate.forEach(btn => {
                const icon = btn.querySelector('i');
                const text = btn.textContent.trim();

                if (data.following) {
                    icon.className = 'ti ti-user-check';
                    btn.classList.remove('btn-outline-primary');
                    btn.classList.add('btn-success');
                    // Aggiorna il testo se presente
                    if (text.includes('{{ __("profile.follow") }}')) {
                        btn.innerHTML = '<i class="ti ti-user-check"></i> {{ __("profile.following") }}';
                    }
                } else {
                    icon.className = 'ti ti-user';
                    btn.classList.remove('btn-success');
                    btn.classList.add('btn-outline-primary');
                    // Aggiorna il testo se presente
                    if (text.includes('{{ __("profile.following") }}')) {
                        btn.innerHTML = '<i class="ti ti-user"></i> {{ __("profile.follow") }}';
                    }
                }
            });

            // Mostra notifica
            Swal.fire({
                icon: 'success',
                title: 'Successo!',
                text: data.message,
                timer: 2000,
                showConfirmButton: false
            });
        } else {
            Swal.fire('Errore', data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Errore connessione follow:', error);
        Swal.fire('Errore', 'Errore durante l\'operazione', 'error');
    })
    .finally(() => {
        // Riabilita tutti i pulsanti
        if (button) button.disabled = false;
        if (buttonMobile) buttonMobile.disabled = false;
        if (buttonDesktop) buttonDesktop.disabled = false;
    });
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
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                Swal.fire('Errore', data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Errore', 'Errore durante il caricamento della foto', 'error');
        });
    }
}

function uploadBannerImage(input) {
    if (input.files && input.files[0]) {
        const formData = new FormData();
        formData.append('banner_image', input.files[0]);
        formData.append('_token', '{{ csrf_token() }}');
        formData.append('_method', 'PUT');

        fetch('{{ route("profile.update") }}', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                Swal.fire('Errore', data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Errore', 'Errore durante il caricamento dell\'immagine', 'error');
        });
    }
}

// Photo Upload Modal Functions
function openPhotoUploadModal() {
    const modal = new bootstrap.Modal(document.getElementById('photoUploadModal'));
    modal.show();
}

function uploadPhoto() {
    const form = document.getElementById('photoUploadForm');
    const formData = new FormData(form);
    const submitBtn = document.getElementById('photoUploadSubmit');
    const loadingDiv = document.getElementById('photoUploadLoading');
    const successDiv = document.getElementById('photoUploadSuccess');
    const errorDiv = document.getElementById('photoUploadError');

    // Reset states
    submitBtn.disabled = true;
    loadingDiv.style.display = 'block';
    successDiv.style.display = 'none';
    errorDiv.style.display = 'none';

    fetch('{{ route("photos.store") }}', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        loadingDiv.style.display = 'none';

        if (data.success) {
            successDiv.style.display = 'block';
            document.getElementById('photoUploadSuccessMessage').textContent = data.message;

            // Reload page after 2 seconds
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            errorDiv.style.display = 'block';
            document.getElementById('photoUploadErrorMessage').textContent = data.message || 'Errore durante il caricamento';
        }
    })
    .catch(error => {
        loadingDiv.style.display = 'none';
        errorDiv.style.display = 'block';
        document.getElementById('photoUploadErrorMessage').textContent = 'Errore di connessione';
        console.error('Error:', error);
    })
    .finally(() => {
        submitBtn.disabled = false;
    });
}

// Paginazione AJAX per gli articoli
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOMContentLoaded - Inizializzazione paginazione');
    initializePaginationEvents();
});

function loadArticlesPage(page, containerId) {
    console.log('loadArticlesPage chiamata con:', page, containerId);
    const container = document.getElementById(containerId);
    if (!container) {
        console.error('Container non trovato:', containerId);
        return;
    }

    // Mostra loading
    container.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Caricamento...</span></div><p class="mt-2 text-muted">Caricamento articoli...</p></div>';

    const apiUrl = `{{ route('api.profile.articles', $user->id ?? auth()->id()) }}?page=${page}`;
    console.log('Chiamata API:', apiUrl);

    // Richiesta AJAX alla route API
    fetch(apiUrl, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        console.log('Risposta API ricevuta:', response.status);
        return response.text();
    })
    .then(html => {
        console.log('HTML ricevuto, lunghezza:', html.length);
        // Aggiorna il contenuto del container
        container.innerHTML = html;

        // Aggiorna l'URL senza ricaricare la pagina
        const url = new URL(window.location);
        url.searchParams.set('page', page);
        window.history.pushState({}, '', url);

        // Aggiorna anche l'altro container se presente
        const otherContainer = container.id === 'articles-container' ?
            document.querySelector('#articles-container-tab') :
            document.querySelector('#articles-container');
        if (otherContainer) {
            otherContainer.innerHTML = html;
        }

        // Re-inizializza gli eventi di paginazione per il nuovo contenuto
        initializePaginationEvents();
    })
    .catch(error => {
        console.error('Errore nel caricamento della pagina:', error);
        container.innerHTML = '<div class="text-center py-4"><div class="bg-light-danger h-50 w-50 d-flex-center rounded-circle m-auto mb-2"><i class="ph ph-alert-circle f-s-24 text-danger"></i></div><p class="text-danger mb-0">Errore nel caricamento degli articoli</p></div>';
    });
}

function initializePaginationEvents() {
    console.log('initializePaginationEvents chiamata');

    // Re-inizializza gli eventi per la paginazione aggiornata
    const articlesPagination = document.getElementById('articles-pagination');
    console.log('articles-pagination trovato:', articlesPagination);

    if (articlesPagination) {
        articlesPagination.addEventListener('click', function(e) {
            console.log('Click su paginazione:', e.target);
            if (e.target.classList.contains('page-link') && !e.target.parentElement.classList.contains('disabled')) {
                e.preventDefault();
                const page = e.target.getAttribute('data-page');
                console.log('Pagina selezionata:', page);
                if (page) {
                    loadArticlesPage(page, 'articles-container');
                }
            }
        });
    }

    const articlesPaginationTab = document.getElementById('articles-pagination-tab');
    console.log('articles-pagination-tab trovato:', articlesPaginationTab);

    if (articlesPaginationTab) {
        articlesPaginationTab.addEventListener('click', function(e) {
            console.log('Click su paginazione tab:', e.target);
            if (e.target.classList.contains('page-link') && !e.target.parentElement.classList.contains('disabled')) {
                e.preventDefault();
                const page = e.target.getAttribute('data-page');
                console.log('Pagina selezionata tab:', page);
                if (page) {
                    loadArticlesPage(page, 'articles-container-tab');
                }
            }
        });
    }
}
</script>
@endpush

<!-- Photo Upload Modal -->
<div class="modal fade" id="photoUploadModal" tabindex="-1" aria-labelledby="photoUploadModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="photoUploadModalLabel">Carica Nuova Foto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('common.close') }}"></button>
            </div>
            <div class="modal-body">
                <form id="photoUploadForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="photoImage" class="form-label">Immagine *</label>
                        <input type="file" class="form-control" id="photoImage" name="image" accept="image/*" required>
                        <div class="form-text">Formati supportati: JPEG, PNG, GIF, WebP. Dimensione massima: 10MB</div>
                    </div>

                    <div class="mb-3">
                        <label for="photoTitle" class="form-label">Titolo</label>
                        <input type="text" class="form-control" id="photoTitle" name="title" maxlength="255">
                    </div>

                    <div class="mb-3">
                        <label for="photoDescription" class="form-label">Descrizione</label>
                        <textarea class="form-control" id="photoDescription" name="description" rows="3" maxlength="1000"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="photoAltText" class="form-label">Testo Alternativo (per accessibilità)</label>
                        <input type="text" class="form-control" id="photoAltText" name="alt_text" maxlength="255">
                    </div>
                </form>

                <!-- Loading State -->
                <div id="photoUploadLoading" style="display: none;" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Caricamento...</span>
                    </div>
                    <p class="mt-2 text-muted">Caricamento foto in corso...</p>
                </div>

                <!-- Success State -->
                <div id="photoUploadSuccess" style="display: none;" class="text-center py-4">
                    <div class="bg-light-success h-50 w-50 d-flex-center rounded-circle m-auto mb-2">
                        <i class="ti ti-check f-s-24 text-success"></i>
                    </div>
                    <p id="photoUploadSuccessMessage" class="text-success mb-0"></p>
                </div>

                <!-- Error State -->
                <div id="photoUploadError" style="display: none;" class="text-center py-4">
                    <div class="bg-light-danger h-50 w-50 d-flex-center rounded-circle m-auto mb-2">
                        <i class="ti ti-alert-circle f-s-24 text-danger"></i>
                    </div>
                    <p id="photoUploadErrorMessage" class="text-danger mb-0"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" id="photoUploadSubmit" onclick="uploadPhoto()">
                    <i class="ph ph-upload me-1"></i>Carica Foto
                </button>
            </div>
        </div>
    </div>
</div>
