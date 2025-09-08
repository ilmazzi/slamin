@extends('layout.master')

@section('title', 'Dashboard Admin')

@section('main-content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">
                <i class="ph ph-chart-line me-2"></i>
                Dashboard Admin
            </h4>
            <ul class="app-line-breadcrumbs mb-3">
                <li class="active">
                    <a href="#" class="f-s-14 f-w-500">Dashboard</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Statistiche Generali -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph ph-chart-bar me-2"></i>
                        Statistiche Generali
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar avatar-md bg-primary text-white rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="ph ph-users f-s-20"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">Utenti Totali</h6>
                                    <h4 class="mb-0 text-primary">{{ number_format($stats['total_users']) }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar avatar-md bg-success text-white rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="ph ph-calendar f-s-20"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">Eventi Totali</h6>
                                    <h4 class="mb-0 text-success">{{ number_format($stats['total_events']) }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar avatar-md bg-info text-white rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="ph ph-briefcase f-s-20"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">Gig Totali</h6>
                                    <h4 class="mb-0 text-info">{{ number_format($stats['total_gigs']) }}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="avatar avatar-md bg-warning text-white rounded-circle d-flex align-items-center justify-content-center">
                                        <i class="ph ph-credit-card f-s-20"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">Pagamenti Totali</h6>
                                    <h4 class="mb-0 text-warning">{{ number_format($stats['total_payments']) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiche Dettagliate -->
    <div class="row mb-4">
        <!-- Statistiche Utenti -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph ph-users me-2"></i>
                        Statistiche Utenti
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center">
                                <h6 class="text-muted mb-1">Nuovi Oggi</h6>
                                <h4 class="text-primary mb-0">{{ $userStats['new_today'] }}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h6 class="text-muted mb-1">Questa Settimana</h6>
                                <h4 class="text-success mb-0">{{ $userStats['new_this_week'] }}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h6 class="text-muted mb-1">Questo Mese</h6>
                                <h4 class="text-info mb-0">{{ $userStats['new_this_month'] }}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h6 class="text-muted mb-1">Utenti Attivi</h6>
                                <h4 class="text-warning mb-0">{{ $userStats['active_users'] }}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h6 class="text-muted mb-1">Premium</h6>
                                <h4 class="text-danger mb-0">{{ $userStats['premium_users'] }}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h6 class="text-muted mb-1">Traduttori</h6>
                                <h4 class="text-secondary mb-0">{{ $userStats['translators'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiche Pagamenti -->
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph ph-credit-card me-2"></i>
                        Statistiche Pagamenti
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center">
                                <h6 class="text-muted mb-1">Ricavi Totali</h6>
                                <h4 class="text-success mb-0">€{{ number_format($paymentStats['total_revenue'], 2) }}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h6 class="text-muted mb-1">Oggi</h6>
                                <h4 class="text-primary mb-0">€{{ number_format($paymentStats['today_revenue'], 2) }}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h6 class="text-muted mb-1">Questa Settimana</h6>
                                <h4 class="text-info mb-0">€{{ number_format($paymentStats['this_week_revenue'], 2) }}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h6 class="text-muted mb-1">Questo Mese</h6>
                                <h4 class="text-warning mb-0">€{{ number_format($paymentStats['this_month_revenue'], 2) }}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h6 class="text-muted mb-1">In Attesa</h6>
                                <h4 class="text-warning mb-0">{{ $paymentStats['pending_payments'] }}</h4>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h6 class="text-muted mb-1">Completati</h6>
                                <h4 class="text-success mb-0">{{ $paymentStats['completed_payments'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sezioni di Gestione -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph ph-gear me-2"></i>
                        Gestione Sistema
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('admin.settings.upload.index') }}" class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center py-4">
                                <i class="ph ph-upload f-s-30 mb-2"></i>
                                <span class="fw-bold">Impostazioni Upload</span>
                                <small class="text-muted">Limiti e tipi di file</small>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('admin.settings.payment.index') }}" class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center justify-content-center py-4">
                                <i class="ph ph-credit-card f-s-30 mb-2"></i>
                                <span class="fw-bold">Impostazioni Pagamenti</span>
                                <small class="text-muted">Stripe e PayPal</small>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('admin.payment-accounts.index') }}" class="btn btn-outline-info w-100 h-100 d-flex flex-column align-items-center justify-content-center py-4">
                                <i class="ph ph-users f-s-30 mb-2"></i>
                                <span class="fw-bold">Conti di Pagamento</span>
                                <small class="text-muted">Gestione account utenti</small>
                            </a>
                        </div>
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('events.index') }}" class="btn btn-outline-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center py-4">
                                <i class="ph ph-calendar f-s-30 mb-2"></i>
                                <span class="fw-bold">Gestione Eventi</span>
                                <small class="text-muted">Eventi e gig</small>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Attività Recente -->
    <div class="row">
        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph ph-clock me-2"></i>
                        Utenti Recenti
                    </h5>
                </div>
                <div class="card-body">
                    @if($recentActivity['recent_users']->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentActivity['recent_users'] as $user)
                                <div class="list-group-item d-flex align-items-center px-0">
                                    <div class="avatar avatar-sm bg-light rounded-circle me-3">
                                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="rounded-circle">
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-0">{{ $user->name }}</h6>
                                        <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center">Nessun utente recente</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph ph-credit-card me-2"></i>
                        Pagamenti Recenti
                    </h5>
                </div>
                <div class="card-body">
                    @if($recentActivity['recent_payments']->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentActivity['recent_payments'] as $payment)
                                <div class="list-group-item d-flex align-items-center justify-content-between px-0">
                                    <div>
                                        <h6 class="mb-0">€{{ number_format($payment->amount, 2) }}</h6>
                                        <small class="text-muted">{{ $payment->created_at->diffForHumans() }}</small>
                                    </div>
                                    <span class="badge bg-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'pending' ? 'warning' : 'danger') }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center">Nessun pagamento recente</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
