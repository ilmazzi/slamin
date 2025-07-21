@extends('layout.master')

@section('title', __('videos.upload_limit_reached'))

@section('main-content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">{{ __('videos.upload_limit_reached') }}</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a href="{{ route('dashboard') }}" class="f-s-14 f-w-500">
                            <span>
                                <i class="ph-duotone ph-house f-s-16"></i> {{ __('dashboard.dashboard') }}
                            </span>
                        </a>
                    </li>
                    <li class="">
                        <a href="{{ route('videos.index') }}" class="f-s-14 f-w-500">
                            <span>
                                <i class="ph-duotone ph-video-camera f-s-16"></i> {{ __('videos.videos') }}
                            </span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="#" class="f-s-14 f-w-500">{{ __('videos.upload_limit') }}</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Limit Reached Message -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-light-warning">
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <i class="ph-duotone ph-warning-circle f-s-64 text-warning"></i>
                        </div>
                        <h3 class="card-title mb-3">{{ __('videos.limit_reached_title') }}</h3>
                        <p class="card-text text-muted mb-4">
                            {{ __('videos.limit_reached_message') }}
                        </p>
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="alert alert-info">
                                    <div class="d-flex align-items-center">
                                        <i class="ph-duotone ph-info f-s-20 me-3"></i>
                                        <div>
                                            <strong>{{ __('videos.current_usage') }}</strong><br>
                                            {{ $user->current_video_count }} / {{ $user->current_video_limit }} {{ __('videos.videos_used') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Upgrade Options -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ph-duotone ph-arrow-up-circle f-s-16 me-2"></i>
                            {{ __('videos.upgrade_options') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card card-light-primary hover-effect">
                                    <div class="card-body text-center p-4">
                                        <div class="mb-3">
                                            <i class="ph-duotone ph-crown f-s-48 text-primary"></i>
                                        </div>
                                        <h5 class="card-title mb-3">{{ __('videos.upgrade_to_premium') }}</h5>
                                        <p class="card-text text-muted mb-4">
                                            {{ __('videos.upgrade_benefits') }}
                                        </p>
                                        <a href="{{ route('premium.index') }}" class="btn btn-primary">
                                            <i class="ph-duotone ph-arrow-right me-1"></i>
                                            {{ __('videos.view_packages') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card card-light-info hover-effect">
                                    <div class="card-body text-center p-4">
                                        <div class="mb-3">
                                            <i class="ph-duotone ph-video-camera f-s-48 text-info"></i>
                                        </div>
                                        <h5 class="card-title mb-3">{{ __('videos.manage_videos') }}</h5>
                                        <p class="card-text text-muted mb-4">
                                            {{ __('videos.manage_existing_videos') }}
                                        </p>
                                        <a href="{{ route('videos.index') }}" class="btn btn-info">
                                            <i class="ph-duotone ph-folder-open me-1"></i>
                                            {{ __('videos.view_my_videos') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Current Plan Info -->
        <div class="row">
            <div class="col-12">
                <div class="card card-light-secondary">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ph-duotone ph-user f-s-16 me-2"></i>
                            {{ __('videos.current_plan_info') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h6 class="mb-2">{{ __('videos.your_current_plan') }}</h6>
                                <p class="mb-0">
                                    @if($user->hasPremiumSubscription())
                                        <span class="badge bg-success">{{ $user->activeSubscription->package->name }}</span>
                                        <small class="ms-2 text-muted">
                                            {{ __('videos.expires') }}: {{ $user->activeSubscription->end_date->format('d/m/Y') }}
                                        </small>
                                    @else
                                        <span class="badge bg-secondary">{{ __('premium.free_package') }}</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <div class="d-flex align-items-center justify-content-md-end">
                                    <div class="me-4">
                                        <small class="text-muted d-block">{{ __('videos.video_limit') }}</small>
                                        <strong>{{ $user->current_video_limit }}</strong>
                                    </div>
                                    <div class="me-4">
                                        <small class="text-muted d-block">{{ __('videos.videos_used') }}</small>
                                        <strong class="text-warning">{{ $user->current_video_count }}</strong>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">{{ __('videos.videos_remaining') }}</small>
                                        <strong class="text-danger">0</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="{{ route('dashboard') }}" class="btn btn-secondary me-2">
                    <i class="ph-duotone ph-arrow-left me-1"></i>
                    {{ __('common.back_to_dashboard') }}
                </a>
                <a href="{{ route('premium.index') }}" class="btn btn-primary">
                    <i class="ph-duotone ph-crown me-1"></i>
                    {{ __('videos.upgrade_now') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
