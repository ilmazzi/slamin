@extends('layout.master')

@section('title', 'Configurazione Pagamenti')

@section('main-content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">Configurazione Pagamenti</h4>
                
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Stripe Connect Setup -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ph-duotone ph-credit-card f-s-16 me-2"></i>
                            Stripe Connect (Raccomandato)
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h6 class="mb-3">Ricevi pagamenti automaticamente</h6>
                                <p class="text-muted mb-3">
                                    Collega il tuo account bancario o carta per ricevere i pagamenti automaticamente.
                                    I soldi arrivano direttamente sul tuo conto in 1-2 giorni lavorativi.
                                </p>

                                <div class="mb-3">
                                    <h6 class="mb-2">Vantaggi:</h6>
                                    <ul class="list-unstyled">
                                        <li><i class="ph-duotone ph-check-circle text-success me-2"></i>Trasferimenti automatici</li>
                                        <li><i class="ph-duotone ph-check-circle text-success me-2"></i>Nessun costo per te</li>
                                        <li><i class="ph-duotone ph-check-circle text-success me-2"></i>Supporto multi-valuta</li>
                                        <li><i class="ph-duotone ph-check-circle text-success me-2"></i>Gestione automatica delle tasse</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-4 text-center">
                                @if($user->stripe_connect_account_id)
                                    @if($user->payout_method_configured)
                                        <div class="mb-3">
                                            <i class="ph-duotone ph-check-circle f-s-48 text-success mb-2"></i>
                                            <h6 class="text-success">Account Configurato</h6>
                                            <p class="text-muted small">Riceverai i pagamenti automaticamente</p>
                                        </div>
                                        <a href="{{ route('translator.payouts.update-stripe-status') }}" class="btn btn-outline-primary">
                                            <i class="ph-duotone ph-arrow-clockwise me-1"></i>Aggiorna Stato
                                        </a>
                                    @else
                                        <div class="mb-3">
                                            <i class="ph-duotone ph-warning f-s-48 text-warning mb-2"></i>
                                            <h6 class="text-warning">Configurazione Incompleta</h6>
                                            <p class="text-muted small">Completa la configurazione</p>
                                        </div>
                                        <a href="{{ route('translator.payouts.update-stripe-status') }}" class="btn btn-warning">
                                            <i class="ph-duotone ph-arrow-clockwise me-1"></i>Completa Configurazione
                                        </a>
                                    @endif
                                @else
                                    <div class="mb-3">
                                        <i class="ph-duotone ph-plus-circle f-s-48 text-primary mb-2"></i>
                                        <h6>Configura Account</h6>
                                        <p class="text-muted small">Collega il tuo account bancario</p>
                                    </div>
                                    <a href="{{ route('translator.payouts.create-stripe-account') }}" class="btn btn-primary">
                                        <i class="ph-duotone ph-plus me-1"></i>Configura Stripe
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PayPal Setup -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ph-duotone ph-paypal-logo f-s-16 me-2"></i>
                            PayPal
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h6 class="mb-3">Ricevi pagamenti via PayPal</h6>
                                <p class="text-muted mb-3">
                                    Configura la tua email PayPal per ricevere i pagamenti.
                                    I trasferimenti sono gestiti manualmente dall'admin.
                                </p>

                                <div class="mb-3">
                                    <h6 class="mb-2">Vantaggi:</h6>
                                    <ul class="list-unstyled">
                                        <li><i class="ph-duotone ph-check-circle text-success me-2"></i>Setup semplice</li>
                                        <li><i class="ph-duotone ph-check-circle text-success me-2"></i>Nessuna registrazione aggiuntiva</li>
                                        <li><i class="ph-duotone ph-check-circle text-success me-2"></i>Supporto PayPal</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-4">
                                @if($user->paypal_email)
                                    <div class="mb-3">
                                        <i class="ph-duotone ph-check-circle f-s-48 text-success mb-2"></i>
                                        <h6 class="text-success">PayPal Configurato</h6>
                                        <p class="text-muted small">{{ $user->paypal_email }}</p>
                                    </div>
                                @endif

                                <form method="POST" action="{{ route('translator.payouts.setup-paypal') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label">Email PayPal</label>
                                        <input type="email" class="form-control" name="paypal_email"
                                               value="{{ $user->paypal_email }}" required>
                                    </div>
                                    <button type="submit" class="btn btn-outline-primary">
                                        <i class="ph-duotone ph-paypal-logo me-1"></i>Configura PayPal
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-3">
                            <i class="ph-duotone ph-info f-s-16 me-2"></i>
                            Come Funziona
                        </h6>

                        <div class="mb-3">
                            <h6 class="mb-2">1. Configurazione</h6>
                            <p class="text-muted small">Collega il tuo account bancario o PayPal</p>
                        </div>

                        <div class="mb-3">
                            <h6 class="mb-2">2. Pagamento</h6>
                            <p class="text-muted small">Il cliente paga per la traduzione</p>
                        </div>

                        <div class="mb-3">
                            <h6 class="mb-2">3. Trasferimento</h6>
                            <p class="text-muted small">Ricevi i soldi automaticamente</p>
                        </div>

                        <div class="alert alert-info">
                            <i class="ph-duotone ph-lightbulb me-2"></i>
                            <strong>Suggerimento:</strong> Stripe Connect è più veloce e automatico!
                        </div>
                    </div>
                </div>

                <!-- Current Status -->
                <div class="card">
                    <div class="card-body">
                        <h6 class="mb-3">
                            <i class="ph-duotone ph-gear f-s-16 me-2"></i>
                            Stato Attuale
                        </h6>

                        <div class="mb-2">
                            <span class="badge {{ $user->payout_method_configured ? 'bg-success' : 'bg-warning' }}">
                                {{ $user->payout_method_configured ? 'Configurato' : 'Non Configurato' }}
                            </span>
                        </div>

                        @if($user->stripe_connect_account_id)
                        <div class="mb-2">
                            <span class="badge bg-primary">Stripe Connect</span>
                        </div>
                        @endif

                        @if($user->paypal_email)
                        <div class="mb-2">
                            <span class="badge bg-info">PayPal</span>
                        </div>
                        @endif

                        <div class="mt-3">
                            <a href="{{ route('translator.payouts.index') }}" class="btn btn-outline-secondary">
                                <i class="ph-duotone ph-arrow-left me-1"></i>Torna ai Pagamenti
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
