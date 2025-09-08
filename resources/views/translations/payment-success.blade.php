@extends('layout.master')

@section('main-content')
<div class="container-fluid">
    <!-- Header Successo -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-light-header text-center">
                <div class="mb-4">
                    <div class="success-icon mx-auto mb-3">
                        <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                    </div>
                    <h1 class="card-title text-success mb-2">
                        Pagamento Completato!
                    </h1>
                    <p class="card-text text-muted">
                        La tua traduzione è stata pagata con successo
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Dettagli Pagamento -->
            <div class="card hover-effect mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-receipt text-primary me-2"></i>
                        Dettagli Pagamento
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">ID Pagamento</label>
                                <p class="mb-0"><strong>#{{ $payment->id }}</strong></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Data Pagamento</label>
                                <p class="mb-0"><strong>{{ $payment->paid_at->format('d/m/Y H:i') }}</strong></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Importo</label>
                                <p class="mb-0"><strong class="text-success">{{ number_format($payment->amount, 2) }} €</strong></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Stato</label>
                                <p class="mb-0">
                                    <span class="badge bg-success">
                                        <i class="fas fa-check me-1"></i>Completato
                                    </span>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Metodo Pagamento</label>
                                <p class="mb-0"><strong>Carta di Credito</strong></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">ID Transazione</label>
                                <p class="mb-0"><strong>{{ $payment->stripe_charge_id ?? 'N/A' }}</strong></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dettagli Traduzione -->
            <div class="card hover-effect mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-book text-primary me-2"></i>
                        Dettagli Traduzione
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Poesia</label>
                                <p class="mb-0"><strong>{{ $payment->poem->title }}</strong></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Autore</label>
                                <p class="mb-0"><strong>{{ $payment->client->name }}</strong></p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Traduttore</label>
                                <p class="mb-0"><strong>{{ $payment->translator->name }}</strong></p>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Lingue Target</label>
                                <p class="mb-0">
                                    @foreach($payment->gigApplication->gig->target_languages as $lang)
                                        <span class="badge bg-primary me-1">{{ strtoupper($lang) }}</span>
                                    @endforeach
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Prossimi Passi -->
            <div class="card hover-effect mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-arrow-right text-primary me-2"></i>
                        Prossimi Passi
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            <div class="step-icon mx-auto mb-2">
                                <i class="fas fa-bell text-warning" style="font-size: 2rem;"></i>
                            </div>
                            <h6>Notifica Inviata</h6>
                            <p class="text-muted small">Il traduttore è stato notificato del pagamento</p>
                        </div>
                        <div class="col-md-4 text-center mb-3">
                            <div class="step-icon mx-auto mb-2">
                                <i class="fas fa-clock text-info" style="font-size: 2rem;"></i>
                            </div>
                            <h6>Lavoro in Corso</h6>
                            <p class="text-muted small">Il traduttore inizierà a lavorare sulla traduzione</p>
                        </div>
                        <div class="col-md-4 text-center mb-3">
                            <div class="step-icon mx-auto mb-2">
                                <i class="fas fa-check-circle text-success" style="font-size: 2rem;"></i>
                            </div>
                            <h6>Consegna</h6>
                            <p class="text-muted small">Riceverai la traduzione completata</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Azioni -->
            <div class="text-center">
                <a href="{{ route('translations.payment.index') }}" class="btn btn-outline-primary me-2">
                    <i class="fas fa-list me-1"></i>I Miei Pagamenti
                </a>
                <a href="{{ route('poems.show', $payment->poem) }}" class="btn btn-primary">
                    <i class="fas fa-eye me-1"></i>Visualizza Poesia
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Mostra messaggio di successo all'apertura della pagina
document.addEventListener('DOMContentLoaded', function() {
    Swal.fire({
        title: 'Pagamento Completato!',
        text: 'La tua traduzione è stata pagata con successo. Il traduttore è stato notificato.',
        icon: 'success',
        confirmButtonText: 'Perfetto!',
        timer: 5000,
        timerProgressBar: true
    });
});
</script>
@endpush
@endsection

