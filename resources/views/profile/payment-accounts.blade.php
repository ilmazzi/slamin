@extends('layout.master')

@section('title', 'Conti di Pagamento')

@section('main-content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">Conti di Pagamento</h4>
                
            </div>
        </div>

        <!-- Status Overview -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card hover-effect equal-card">
                    <div class="card-body text-center py-3">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <div class="rounded-circle {{ $user->stripe_connect_status === 'active' ? 'bg-light-success' : 'bg-light-warning' }} d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="ph-duotone ph-credit-card f-s-20 {{ $user->stripe_connect_status === 'active' ? 'text-success' : 'text-warning' }}"></i>
                            </div>
                        </div>
                        <h6 class="mb-1 f-s-16">Stripe Connect</h6>
                        <span class="badge {{ $user->stripe_connect_status === 'active' ? 'bg-success' : 'bg-warning' }} f-s-12">
                            {{ $user->stripe_connect_status === 'active' ? 'Attivo' : 'In Attesa' }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card hover-effect equal-card">
                    <div class="card-body text-center py-3">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <div class="rounded-circle {{ $user->paypal_verified ? 'bg-light-success' : 'bg-light-warning' }} d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="ph-duotone ph-paypal-logo f-s-20 {{ $user->paypal_verified ? 'text-success' : 'text-warning' }}"></i>
                            </div>
                        </div>
                        <h6 class="mb-1 f-s-16">PayPal</h6>
                        <span class="badge {{ $user->paypal_verified ? 'bg-success' : 'bg-warning' }} f-s-12">
                            {{ $user->paypal_verified ? 'Verificato' : 'Non Verificato' }}
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card hover-effect equal-card">
                    <div class="card-body text-center py-3">
                        <div class="d-flex align-items-center justify-content-center mb-2">
                            <div class="rounded-circle bg-light-info d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                <i class="ph-duotone ph-gear f-s-20 text-info"></i>
                            </div>
                        </div>
                        <h6 class="mb-1 f-s-16">Metodo Preferito</h6>
                        <span class="badge bg-info f-s-12">{{ ucfirst($user->preferred_payout_method) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('error'))
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-danger">
                    <i class="ph-duotone ph-warning me-2"></i>
                    {{ session('error') }}
                </div>
            </div>
        </div>
        @endif

        @if(session('success'))
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-success">
                    <i class="ph-duotone ph-check-circle me-2"></i>
                    {{ session('success') }}
                </div>
            </div>
        </div>
        @endif

        <div class="row">
            <!-- Stripe Connect Configuration -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ph-duotone ph-credit-card f-s-16 me-2"></i>
                            Stripe Connect
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($user->stripe_connect_account_id)
                            <div class="mb-3">
                                <h6>Account ID: <code>{{ $user->stripe_connect_account_id }}</code></h6>
                                <p class="text-muted small">Collegato il {{ $user->stripe_connected_at->format('d/m/Y H:i') }}</p>
                            </div>

                            @if($user->stripe_connect_status === 'active')
                                <div class="alert alert-success">
                                    <i class="ph-duotone ph-check-circle me-2"></i>
                                    <strong>Account Attivo!</strong> Puoi ricevere pagamenti automaticamente.
                                </div>

                                <div class="d-flex gap-2">
                                    <a href="{{ route('profile.payment-accounts.update-stripe-status') }}" class="btn btn-outline-primary">
                                        <i class="ph-duotone ph-arrow-clockwise me-1"></i>Aggiorna Stato
                                    </a>
                                    <form method="POST" action="{{ route('profile.payment-accounts.disconnect') }}" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="account_type" value="stripe">
                                        <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Sei sicuro di voler disconnettere Stripe?')">
                                            <i class="ph-duotone ph-x me-1"></i>Disconnetti
                                        </button>
                                    </form>
                                </div>
                            @elseif($user->stripe_connect_status === 'pending')
                                <div class="alert alert-warning">
                                    <i class="ph-duotone ph-warning me-2"></i>
                                    <strong>Configurazione Incompleta!</strong> Completa l'onboarding per ricevere pagamenti.
                                </div>

                                <div class="d-flex gap-2">
                                        <form method="GET" action="{{ route('profile.payment-accounts.stripe-onboarding') }}" class="d-inline">
                                            <button type="submit" class="btn btn-warning">
                                                <i class="ph-duotone ph-arrow-right me-1"></i>Completa Configurazione
                                            </button>
                                        </form>
                                    <form method="GET" action="{{ route('profile.payment-accounts.update-stripe-status') }}" class="d-inline">
                                        <button type="submit" class="btn btn-outline-primary">
                                            <i class="ph-duotone ph-arrow-clockwise me-1"></i>Aggiorna Stato
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="alert alert-danger">
                                    <i class="ph-duotone ph-x-circle me-2"></i>
                                    <strong>Account Limitato!</strong> Contatta il supporto per assistenza.
                                </div>

                                <div class="d-flex gap-2">
                                    <form method="GET" action="{{ route('profile.payment-accounts.update-stripe-status') }}" class="d-inline">
                                        <button type="submit" class="btn btn-outline-primary">
                                            <i class="ph-duotone ph-arrow-clockwise me-1"></i>Aggiorna Stato
                                        </button>
                                    </form>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-4">
                                <i class="ph-duotone ph-plus-circle f-s-48 text-primary mb-3"></i>
                                <h6>Collega il tuo account Stripe</h6>
                                <p class="text-muted">Ricevi pagamenti automaticamente sul tuo conto bancario</p>
                                    <form method="POST" action="{{ route('profile.payment-accounts.create-stripe') }}" class="d-inline" id="stripe-form">
                                        @csrf
                                        <button type="submit" class="btn btn-primary" id="stripe-btn">
                                            <i class="ph-duotone ph-plus me-1"></i>Collega Stripe
                                        </button>
                                    </form>

                                    <script>
                                    document.getElementById('stripe-form').addEventListener('submit', function(e) {
                                        const btn = document.getElementById('stripe-btn');
                                        btn.innerHTML = '<i class="ph-duotone ph-spinner f-s-16 me-1"></i>Configurazione...';
                                        btn.disabled = true;

                                        // Re-enable button after 10 seconds as fallback
                                        setTimeout(() => {
                                            btn.innerHTML = '<i class="ph-duotone ph-plus me-1"></i>Collega Stripe';
                                            btn.disabled = false;
                                        }, 10000);
                                    });
                                    </script>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- PayPal Configuration -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ph-duotone ph-paypal-logo f-s-16 me-2"></i>
                            PayPal
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($user->paypal_email)
                            <div class="mb-3">
                                <h6>Email: <code>{{ $user->paypal_email }}</code></h6>
                                <p class="text-muted small">Collegato il {{ $user->paypal_connected_at->format('d/m/Y H:i') }}</p>
                            </div>

                            @if($user->paypal_verified)
                                <div class="alert alert-success">
                                    <i class="ph-duotone ph-check-circle me-2"></i>
                                    <strong>Account Verificato!</strong> Puoi ricevere pagamenti via PayPal.
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="ph-duotone ph-clock me-2"></i>
                                    <strong>In Verifica!</strong> L'admin verificherà il tuo account a breve.
                                </div>
                            @endif

                            <form method="POST" action="{{ route('profile.payment-accounts.disconnect') }}" class="d-inline">
                                @csrf
                                <input type="hidden" name="account_type" value="paypal">
                                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Sei sicuro di voler disconnettere PayPal?')">
                                    <i class="ph-duotone ph-x me-1"></i>Disconnetti
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('profile.payment-accounts.setup-paypal') }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Email PayPal</label>
                                    <input type="email" class="form-control" name="paypal_email"
                                           placeholder="tua-email@paypal.com" required>
                                    <div class="form-text">Inserisci l'email associata al tuo account PayPal</div>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ph-duotone ph-paypal-logo me-1"></i>Collega PayPal
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Bank Details for Manual Payouts -->
        <div class="row mt-4">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ph-duotone ph-bank f-s-16 me-2"></i>
                            Dettagli Bancari (Payout Manuali)
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.payment-accounts.setup-bank') }}">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Nome Banca</label>
                                    <input type="text" class="form-control" name="bank_name"
                                           value="{{ $user->bank_name }}" placeholder="es. Intesa Sanpaolo">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Intestatario Conto</label>
                                    <input type="text" class="form-control" name="bank_account_holder"
                                           value="{{ $user->bank_account_holder }}" placeholder="Nome Cognome">
                                </div>
                                <div class="col-md-8 mb-3">
                                    <label class="form-label">IBAN</label>
                                    <input type="text" class="form-control" name="bank_iban"
                                           value="{{ $user->bank_iban }}" placeholder="IT60X0542811101000000123456">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">SWIFT/BIC</label>
                                    <input type="text" class="form-control" name="bank_swift"
                                           value="{{ $user->bank_swift }}" placeholder="es. BCITITMM">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="ph-duotone ph-floppy-disk me-1"></i>Salva Dettagli
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Payout Preferences -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ph-duotone ph-gear f-s-16 me-2"></i>
                            Preferenze Payout
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.payment-accounts.set-preferred-method') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Metodo di Payout Preferito</label>
                                <select class="form-select" name="preferred_payout_method">
                                    <option value="stripe" {{ $user->preferred_payout_method === 'stripe' ? 'selected' : '' }}>
                                        Stripe Connect (Automatico)
                                    </option>
                                    <option value="paypal" {{ $user->preferred_payout_method === 'paypal' ? 'selected' : '' }}>
                                        PayPal (Manuale)
                                    </option>
                                    <option value="manual" {{ $user->preferred_payout_method === 'manual' ? 'selected' : '' }}>
                                        Bonifico Bancario (Manuale)
                                    </option>
                                </select>
                                <div class="form-text">Scegli come preferisci ricevere i pagamenti</div>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="ph-duotone ph-check me-1"></i>Salva Preferenze
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Section -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="ph-duotone ph-info f-s-16 me-2"></i>
                            Informazioni sui Metodi di Pagamento
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <div class="col-md-4">
                                <div class="text-center">
                                    <div class="rounded-circle bg-light-primary d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 50px; height: 50px;">
                                        <i class="ph-duotone ph-credit-card f-s-18 text-primary"></i>
                                    </div>
                                    <h6 class="mb-3 f-w-600">Stripe Connect</h6>
                                    <ul class="list-unstyled text-start">
                                        <li class="mb-2 d-flex align-items-center">
                                            <i class="ph-duotone ph-check text-success me-2"></i>
                                            <span class="f-s-14">Trasferimenti automatici</span>
                                        </li>
                                        <li class="mb-2 d-flex align-items-center">
                                            <i class="ph-duotone ph-check text-success me-2"></i>
                                            <span class="f-s-14">1-2 giorni lavorativi</span>
                                        </li>
                                        <li class="mb-2 d-flex align-items-center">
                                            <i class="ph-duotone ph-check text-success me-2"></i>
                                            <span class="f-s-14">Nessun costo</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <div class="rounded-circle bg-light-info d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 50px; height: 50px;">
                                        <i class="ph-duotone ph-paypal-logo f-s-18 text-info"></i>
                                    </div>
                                    <h6 class="mb-3 f-w-600">PayPal</h6>
                                    <ul class="list-unstyled text-start">
                                        <li class="mb-2 d-flex align-items-center">
                                            <i class="ph-duotone ph-check text-success me-2"></i>
                                            <span class="f-s-14">Setup semplice</span>
                                        </li>
                                        <li class="mb-2 d-flex align-items-center">
                                            <i class="ph-duotone ph-check text-success me-2"></i>
                                            <span class="f-s-14">3-5 giorni lavorativi</span>
                                        </li>
                                        <li class="mb-2 d-flex align-items-center">
                                            <i class="ph-duotone ph-check text-success me-2"></i>
                                            <span class="f-s-14">Gestione manuale</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <div class="rounded-circle bg-light-secondary d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 50px; height: 50px;">
                                        <i class="ph-duotone ph-bank f-s-18 text-secondary"></i>
                                    </div>
                                    <h6 class="mb-3 f-w-600">Bonifico Bancario</h6>
                                    <ul class="list-unstyled text-start">
                                        <li class="mb-2 d-flex align-items-center">
                                            <i class="ph-duotone ph-check text-success me-2"></i>
                                            <span class="f-s-14">Diretto sul conto</span>
                                        </li>
                                        <li class="mb-2 d-flex align-items-center">
                                            <i class="ph-duotone ph-check text-success me-2"></i>
                                            <span class="f-s-14">3-5 giorni lavorativi</span>
                                        </li>
                                        <li class="mb-2 d-flex align-items-center">
                                            <i class="ph-duotone ph-check text-success me-2"></i>
                                            <span class="f-s-14">Gestione manuale</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
