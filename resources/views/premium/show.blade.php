@extends('layout.master')

@section('title', $package->name . ' - ' . __('premium.premium'))

@section('main-content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">{{ $package->name }}</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a href="{{ route('dashboard') }}" class="f-s-14 f-w-500">
                            <span>
                                <i class="ph-duotone ph-house f-s-16"></i> {{ __('dashboard.dashboard') }}
                            </span>
                        </a>
                    </li>
                    <li class="">
                        <a href="{{ route('premium.index') }}" class="f-s-14 f-w-500">{{ __('premium.packages') }}</a>
                    </li>
                    <li class="active">
                        <a href="#" class="f-s-14 f-w-500">{{ $package->name }}</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="row">
            <!-- Package Details -->
            <div class="col-lg-8">
                <div class="card card-light-primary hover-effect">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ph-duotone ph-info f-s-16 me-2"></i>
                            {{ __('premium.features') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Package Header -->
                        <div class="text-center mb-4">
                            <div class="mb-3">
                                @if($package->slug === 'free')
                                    <i class="ph-duotone ph-user f-s-64 text-secondary"></i>
                                @elseif($package->slug === 'poet_basic')
                                    <i class="ph-duotone ph-pen-nib f-s-64 text-primary"></i>
                                @elseif($package->slug === 'poet_pro')
                                    <i class="ph-duotone ph-pen-nib-straight f-s-64 text-warning"></i>
                                @elseif($package->slug === 'poet_elite')
                                    <i class="ph-duotone ph-crown f-s-64 text-success"></i>
                                @elseif($package->slug === 'organizer')
                                    <i class="ph-duotone ph-users f-s-64 text-info"></i>
                                @endif
                            </div>
                            <h3 class="mb-2">{{ $package->name }}</h3>
                            <p class="text-muted mb-0">{{ $package->description }}</p>
                        </div>

                        <!-- Features Grid -->
                        @if($package->features)
                        <div class="row">
                            @foreach($package->features as $feature => $enabled)
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    @if($enabled)
                                        <i class="ph-duotone ph-check-circle f-s-20 text-success me-3"></i>
                                        <span class="fw-medium">{{ __('premium.' . $feature) }}</span>
                                    @else
                                        <i class="ph-duotone ph-x-circle f-s-20 text-muted me-3"></i>
                                        <span class="text-muted">{{ __('premium.' . $feature) }}</span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <!-- Video Limit Highlight -->
                        <div class="alert alert-info mt-4">
                            <div class="d-flex align-items-center">
                                <i class="ph-duotone ph-video-camera f-s-24 me-3"></i>
                                <div>
                                    <h6 class="mb-1">{{ __('premium.video_limit') }}</h6>
                                    <p class="mb-0">{{ $package->video_limit }} {{ __('videos.videos_allowed') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Purchase Card -->
            <div class="col-lg-4">
                <div class="card card-light-success hover-effect">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ph-duotone ph-shopping-cart f-s-16 me-2"></i>
                            {{ __('premium.purchase') }}
                        </h5>
                    </div>
                    <div class="card-body text-center">
                        <!-- Price -->
                        <div class="mb-4">
                            @if($package->isFree())
                                <div class="h2 text-success mb-2">{{ __('premium.free_package') }}</div>
                                <p class="text-muted">{{ __('premium.current_plan') }}</p>
                            @else
                                <div class="h2 text-primary mb-2">€{{ $package->formatted_price }}</div>
                                <p class="text-muted">{{ __('premium.price_monthly') }}</p>
                            @endif
                        </div>

                        <!-- Current Status -->
                        @if($currentSubscription)
                        <div class="alert alert-warning mb-4">
                            <h6 class="mb-2">{{ __('premium.subscription_status') }}</h6>
                            <p class="mb-1">
                                <strong>{{ $currentSubscription->package->name }}</strong>
                            </p>
                            <p class="mb-0 small">
                                {{ __('premium.days_remaining') }}: {{ $currentSubscription->days_remaining }}
                            </p>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            @if($package->isFree())
                                <button class="btn btn-secondary" disabled>
                                    {{ __('premium.current_plan') }}
                                </button>
                            @elseif($currentSubscription && $currentSubscription->package_id === $package->id)
                                <button class="btn btn-secondary" disabled>
                                    {{ __('premium.current_plan') }}
                                </button>
                            @else
                                <a href="{{ route('premium.checkout', $package) }}" class="btn btn-primary">
                                    <i class="ph-duotone ph-credit-card me-1"></i>
                                    {{ __('premium.purchase') }}
                                </a>
                            @endif

                            <a href="{{ route('premium.index') }}" class="btn btn-outline-secondary">
                                <i class="ph-duotone ph-arrow-left me-1"></i>
                                {{ __('common.back') }}
                            </a>
                        </div>

                        <!-- Security Notice -->
                        <div class="mt-4">
                            <small class="text-muted">
                                <i class="ph-duotone ph-shield-check me-1"></i>
                                {{ __('premium.payment_methods_answer') }}
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Current Usage -->
                <div class="card card-light-info mt-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="ph-duotone ph-chart-bar f-s-16 me-2"></i>
                            {{ __('videos.current_videos') }}
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="h4 text-primary mb-1">{{ $user->current_video_count }}</div>
                                <small class="text-muted">{{ __('videos.current_videos') }}</small>
                            </div>
                            <div class="col-6">
                                <div class="h4 text-success mb-1">{{ $user->remaining_video_uploads }}</div>
                                <small class="text-muted">{{ __('videos.videos_remaining') }}</small>
                            </div>
                        </div>
                        <div class="progress mt-3" style="height: 8px;">
                            @php
                                $percentage = $user->current_video_limit > 0 ?
                                    ($user->current_video_count / $user->current_video_limit) * 100 : 0;
                            @endphp
                            <div class="progress-bar bg-{{ $percentage > 80 ? 'danger' : ($percentage > 60 ? 'warning' : 'success') }}"
                                 style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
