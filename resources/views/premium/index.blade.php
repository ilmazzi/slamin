@extends('layout.master')

@section('title', __('premium.premium') . ' - ' . __('premium.packages'))

@section('main-content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">{{ __('premium.premium') }}</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a href="{{ route('dashboard') }}" class="f-s-14 f-w-500">
                            <span>
                                <i class="ph-duotone ph-house f-s-16"></i> {{ __('dashboard.dashboard') }}
                            </span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="#" class="f-s-14 f-w-500">{{ __('premium.packages') }}</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-light-primary">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            <i class="ph-duotone ph-crown f-s-48 text-warning"></i>
                        </div>
                        <h3 class="card-title mb-2">{{ __('premium.upgrade_required') }}</h3>
                        <p class="card-text text-muted mb-0">
                            {{ __('premium.upgrade_message') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Status -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-light-info">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h6 class="mb-2">
                                    <i class="ph-duotone ph-user f-s-16 me-2"></i>
                                    {{ __('premium.current_plan') }}
                                </h6>
                                <p class="mb-0 text-muted">
                                    @if($user->hasPremiumSubscription())
                                        <span class="badge bg-success">{{ $user->activeSubscription->package->name }}</span>
                                        <small class="ms-2">{{ __('premium.days_remaining') }}: {{ $user->activeSubscription->days_remaining }}</small>
                                    @else
                                        <span class="badge bg-secondary">{{ __('premium.free_package') }}</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <div class="d-flex align-items-center justify-content-md-end">
                                    <div class="me-3">
                                        <small class="text-muted d-block">{{ __('videos.current_videos') }}</small>
                                        <strong>{{ $user->current_video_count }}</strong>
                                    </div>
                                    <div class="me-3">
                                        <small class="text-muted d-block">{{ __('premium.video_limit') }}</small>
                                        <strong>{{ $user->current_video_limit }}</strong>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">{{ __('videos.videos_remaining') }}</small>
                                        <strong class="text-{{ $user->canUploadMoreVideos() ? 'success' : 'danger' }}">
                                            {{ $user->remaining_video_uploads }}
                                        </strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Premium Pricing Plans -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('premium.packages') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row simple-pricing-container">
                        @foreach($packages as $package)
                        <div class="col-md-6 col-xl-3 p-3">
                            <div class="simple-pricing-card card hover-effect">
                                <div class="card-body">
                                    <div class="simple-price-header text-center">
                                        <div class="mb-3">
                                            @if($package->slug === 'free')
                                                <i class="ph-duotone ph-user f-s-32 text-secondary"></i>
                                            @elseif($package->slug === 'poet_basic')
                                                <i class="ph-duotone ph-pen-nib f-s-32 text-success"></i>
                                            @elseif($package->slug === 'poet_pro')
                                                <i class="ph-duotone ph-pen-nib-straight f-s-32 text-warning"></i>
                                            @elseif($package->slug === 'poet_elite')
                                                <i class="ph-duotone ph-crown f-s-32 text-success"></i>
                                            @elseif($package->slug === 'organizer')
                                                <i class="ph-duotone ph-users f-s-32 text-info"></i>
                                            @endif
                                        </div>
                                        <h4 class="mb-0">{{ $package->name }}</h4>
                                        @if($package->slug === 'poet_pro')
                                            <span class="badge bg-warning text-dark mt-2">{{ __('premium.most_popular') }}</span>
                                        @elseif($package->slug === 'poet_elite')
                                            <span class="badge bg-success text-white mt-2">{{ __('premium.best_value') }}</span>
                                        @endif
                                    </div>
                                    <div class="simple-price-body">
                                        <div class="simple-price-value text-center b-r-5 {{ $package->isFree() ? 'bg-light-secondary' : 'bg-light-success' }} d-block">
                                            @if($package->isFree())
                                                <span class="f-s-24 f-w-600 text-center">{{ __('premium.free_package') }}</span>
                                            @else
                                                <span class="f-s-24 f-w-600 text-center">€{{ $package->formatted_price }}/</span>
                                                <span class="f-s-12 f-w-600">{{ __('premium.price_monthly') }}</span>
                                            @endif
                                        </div>

                                        <div class="simple-price-content">
                                            <!-- Video Limit -->
                                            <div class="d-flex mb-3">
                                                <span>
                                                    <i class="ph-bold ph-video-camera bg-success p-1 b-r-100 f-s-12 text-white"></i>
                                                </span>
                                                <p class="ms-2 mb-0">{{ $package->video_limit }} {{ __('videos.videos_allowed') }}</p>
                                            </div>
                                            <div class="app-divider-v px-2"></div>

                                            <!-- Features -->
                                            @if($package->features)
                                                @foreach(array_slice($package->features, 0, 3) as $feature => $enabled)
                                                    @if($enabled)
                                                        <div class="d-flex">
                                                            <span>
                                                                <i class="ph-bold ph-check bg-success p-1 b-r-100 f-s-12 text-white"></i>
                                                            </span>
                                                            <p class="ms-2 mb-0">{{ __('premium.' . $feature) }}</p>
                                                        </div>
                                                        <div class="app-divider-v px-2"></div>
                                                    @endif
                                                @endforeach
                                                @if(count($package->features) > 3)
                                                    <div class="d-flex">
                                                        <span>
                                                            <i class="ph-bold ph-plus bg-info p-1 b-r-100 f-s-12 text-white"></i>
                                                        </span>
                                                        <p class="ms-2 mb-0">+{{ count($package->features) - 3 }} {{ __('premium.features') }}</p>
                                                    </div>
                                                    <div class="app-divider-v px-2"></div>
                                                @endif
                                            @endif

                                            <!-- Action Button -->
                                            @if($package->isFree())
                                                <button type="button" class="btn btn-secondary b-r-5 w-100 p-2" disabled>
                                                    {{ __('premium.current_plan') }}
                                                </button>
                                            @else
                                                <a href="{{ route('premium.show', $package) }}" class="btn btn-success b-r-5 w-100 p-2">
                                                    <i class="ph-duotone ph-arrow-right me-1"></i>
                                                    {{ __('premium.select_plan') }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Compare Link -->
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="{{ route('premium.compare') }}" class="btn btn-outline-primary">
                    <i class="ph-duotone ph-arrows-left-right me-1"></i>
                    {{ __('premium.compare_packages') }}
                </a>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card card-light-secondary">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ph-duotone ph-question f-s-16 me-2"></i>
                            {{ __('premium.frequently_asked_questions') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>{{ __('premium.how_many_videos') }}</h6>
                                <p class="text-muted small">{{ __('premium.how_many_videos_answer') }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>{{ __('premium.can_i_upgrade') }}</h6>
                                <p class="text-muted small">{{ __('premium.can_i_upgrade_answer') }}</p>
                            </div>
                        </div>
                        <div class="text-center mt-3">
                            <a href="{{ route('premium.faq') }}" class="btn btn-sm btn-outline-secondary">
                                {{ __('premium.faq') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
