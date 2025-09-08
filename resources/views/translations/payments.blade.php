@extends('layout.master')

@section('main-content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-light-header">
                <h1 class="card-title mb-0">
                    <i class="fas fa-credit-card text-primary me-2"></i>
                    I Miei Pagamenti
                </h1>
                <p class="card-text text-muted">
                    Gestisci tutti i tuoi pagamenti per le traduzioni
                </p>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="row mb-4">
        <div class="col-12">
            <ul class="nav nav-tabs" id="paymentTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="client-tab" data-bs-toggle="tab" data-bs-target="#client-payments" type="button" role="tab">
                        <i class="fas fa-shopping-cart me-1"></i>Pagamenti Effettuati
                        <span class="badge bg-primary ms-2">{{ $paymentsAsClient->total() }}</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="translator-tab" data-bs-toggle="tab" data-bs-target="#translator-payments" type="button" role="tab">
                        <i class="fas fa-money-bill-wave me-1"></i>Pagamenti Ricevuti
                        <span class="badge bg-success ms-2">{{ $paymentsAsTranslator->total() }}</span>
                    </button>
                </li>
            </ul>
        </div>
    </div>

    <!-- Tab Content -->
    <div class="tab-content" id="paymentTabsContent">
        <!-- Pagamenti Effettuati -->
        <div class="tab-pane fade show active" id="client-payments" role="tabpanel">
            <div class="row">
                @forelse($paymentsAsClient as $payment)
                <div class="col-lg-6 mb-4">
                    <div class="card hover-effect">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-book text-primary me-1"></i>
                                    {{ $payment->poem->title }}
                                </h6>
                                <span class="badge
                                    @if($payment->status === 'completed') bg-success
                                    @elseif($payment->status === 'pending') bg-warning
                                    @elseif($payment->status === 'failed') bg-danger
                                    @else bg-secondary
                                    @endif">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">Traduttore</small>
                                    <p class="mb-1"><strong>{{ $payment->translator->name }}</strong></p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Importo</small>
                                    <p class="mb-1"><strong class="text-success">{{ number_format($payment->amount, 2) }} €</strong></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">Data</small>
                                    <p class="mb-1">{{ $payment->created_at->format('d/m/Y') }}</p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">ID</small>
                                    <p class="mb-1">#{{ $payment->id }}</p>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('translations.payment.success', $payment) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>Dettagli
                                </a>
                                <a href="{{ route('poems.show', $payment->poem->slug) }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-book me-1"></i>Poesia
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="card hover-effect">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-credit-card text-muted mb-3" style="font-size: 3rem;"></i>
                            <h5 class="text-muted">Nessun pagamento effettuato</h5>
                            <p class="text-muted">Non hai ancora effettuato nessun pagamento per traduzioni.</p>
                            <a href="{{ route('gigs.index') }}" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i>Cerca Traduzioni
                            </a>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Paginazione -->
            @if($paymentsAsClient->hasPages())
            <div class="d-flex justify-content-center">
                {{ $paymentsAsClient->links() }}
            </div>
            @endif
        </div>

        <!-- Pagamenti Ricevuti -->
        <div class="tab-pane fade" id="translator-payments" role="tabpanel">
            <div class="row">
                @forelse($paymentsAsTranslator as $payment)
                <div class="col-lg-6 mb-4">
                    <div class="card hover-effect">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="card-title mb-0">
                                    <i class="fas fa-book text-primary me-1"></i>
                                    {{ $payment->poem->title }}
                                </h6>
                                <span class="badge
                                    @if($payment->status === 'completed') bg-success
                                    @elseif($payment->status === 'pending') bg-warning
                                    @elseif($payment->status === 'failed') bg-danger
                                    @else bg-secondary
                                    @endif">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">Cliente</small>
                                    <p class="mb-1"><strong>{{ $payment->client->name }}</strong></p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">Importo</small>
                                    <p class="mb-1"><strong class="text-success">{{ number_format($payment->amount, 2) }} €</strong></p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <small class="text-muted">Data</small>
                                    <p class="mb-1">{{ $payment->created_at->format('d/m/Y') }}</p>
                                </div>
                                <div class="col-6">
                                    <small class="text-muted">ID</small>
                                    <p class="mb-1">#{{ $payment->id }}</p>
                                </div>
                            </div>
                            <div class="mt-3">
                                <a href="{{ route('translations.payment.success', $payment) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>Dettagli
                                </a>
                                <a href="{{ route('poems.show', $payment->poem->slug) }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-book me-1"></i>Poesia
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12">
                    <div class="card hover-effect">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-money-bill-wave text-muted mb-3" style="font-size: 3rem;"></i>
                            <h5 class="text-muted">Nessun pagamento ricevuto</h5>
                            <p class="text-muted">Non hai ancora ricevuto nessun pagamento per traduzioni.</p>
                            <a href="{{ route('gigs.index') }}" class="btn btn-primary">
                                <i class="fas fa-search me-1"></i>Cerca Lavori
                            </a>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>

            <!-- Paginazione -->
            @if($paymentsAsTranslator->hasPages())
            <div class="d-flex justify-content-center">
                {{ $paymentsAsTranslator->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestisci cambio tab
    const tabButtons = document.querySelectorAll('#paymentTabs button[data-bs-toggle="tab"]');
    tabButtons.forEach(button => {
        button.addEventListener('shown.bs.tab', function (event) {
            // Aggiorna URL senza ricaricare la pagina
            const target = event.target.getAttribute('data-bs-target');
            const tabName = target.replace('#', '');
            window.history.pushState(null, null, `#${tabName}`);
        });
    });

    // Ripristina tab attivo dall'URL
    const hash = window.location.hash;
    if (hash) {
        const tabButton = document.querySelector(`button[data-bs-target="${hash}"]`);
        if (tabButton) {
            const tab = new bootstrap.Tab(tabButton);
            tab.show();
        }
    }
});
</script>
@endpush
@endsection

