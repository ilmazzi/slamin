@extends('layout.master')

@section('title', __('premium.payment_successful'))

@section('main-content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">{{ __('premium.payment_successful') }}</h4>
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
                        <a href="#" class="f-s-14 f-w-500">{{ __('premium.payment_successful') }}</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Success Message -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-light-success">
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <i class="ph-duotone ph-check-circle f-s-64 text-success"></i>
                        </div>
                        <h3 class="card-title mb-3">{{ __('premium.payment_successful') }}</h3>
                        <p class="card-text text-muted mb-4">
                            Il tuo abbonamento <strong>{{ $subscription->package->name }}</strong> è stato attivato con successo!
                        </p>
                        <div class="alert alert-success">
                            <i class="ph-duotone ph-check-circle f-s-16 me-2"></i>
                            Ora puoi caricare fino a {{ $subscription->package->video_limit }} video
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Subscription Details -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="card card-light-primary">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ph-duotone ph-receipt f-s-16 me-2"></i>
                            Dettagli Abbonamento
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Pacchetto</label>
                                <p class="mb-0">{{ $subscription->package->name }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Prezzo</label>
                                <p class="mb-0">€{{ $subscription->package->formatted_price }} {{ __('premium.price_monthly') }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Data Inizio</label>
                                <p class="mb-0">{{ $subscription->start_date->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Data Fine</label>
                                <p class="mb-0">{{ $subscription->end_date->format('d/m/Y H:i') }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Stato</label>
                                <p class="mb-0">
                                    <span class="badge bg-success">{{ __('premium.subscription_active') }}</span>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-medium">Giorni Rimanenti</label>
                                <p class="mb-0">{{ $subscription->days_remaining }} {{ __('premium.days') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="col-lg-4">
                <div class="card card-light-info mb-4">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="ph-duotone ph-lightning f-s-16 me-2"></i>
                            Azioni Rapide
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('videos.upload') }}" class="btn btn-primary">
                                <i class="ph-duotone ph-upload me-1"></i>
                                {{ __('videos.upload_video') }}
                            </a>
                            <a href="{{ route('profile.videos') }}" class="btn btn-outline-primary">
                                <i class="ph-duotone ph-video-camera me-1"></i>
                                {{ __('videos.my_videos') }}
                            </a>
                            <a href="{{ route('premium.manage') }}" class="btn btn-outline-secondary">
                                <i class="ph-duotone ph-gear me-1"></i>
                                {{ __('premium.manage_subscription') }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Package Features -->
                <div class="card card-light-success">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="ph-duotone ph-star f-s-16 me-2"></i>
                            {{ __('premium.features') }}
                        </h6>
                    </div>
                    <div class="card-body">
                        @if($subscription->package->features)
                            @foreach($subscription->package->features as $feature => $enabled)
                                @if($enabled)
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="ph-duotone ph-check-circle f-s-16 text-success me-2"></i>
                                        <small>{{ __('premium.' . $feature) }}</small>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Next Steps -->
        <div class="row">
            <div class="col-12">
                <div class="card card-light-warning">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ph-duotone ph-arrow-right-circle f-s-16 me-2"></i>
                            Prossimi Passi
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center mb-3">
                                <div class="mb-3">
                                    <i class="ph-duotone ph-upload f-s-32 text-primary"></i>
                                </div>
                                <h6>1. Carica il tuo primo video</h6>
                                <p class="text-muted small">Ora puoi caricare fino a {{ $subscription->package->video_limit }} video</p>
                            </div>
                            <div class="col-md-4 text-center mb-3">
                                <div class="mb-3">
                                    <i class="ph-duotone ph-users f-s-32 text-success"></i>
                                </div>
                                <h6>2. Condividi con la community</h6>
                                <p class="text-muted small">I tuoi video saranno visibili a tutti gli utenti</p>
                            </div>
                            <div class="col-md-4 text-center mb-3">
                                <div class="mb-3">
                                    <i class="ph-duotone ph-chart-line f-s-32 text-info"></i>
                                </div>
                                <h6>3. Monitora le statistiche</h6>
                                <p class="text-muted small">Traccia visualizzazioni, like e commenti</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="{{ route('videos.upload') }}" class="btn btn-primary btn-lg me-3">
                    <i class="ph-duotone ph-upload me-2"></i>
                    {{ __('videos.upload_video') }}
                </a>
                <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="ph-duotone ph-house me-2"></i>
                    {{ __('dashboard.dashboard') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
