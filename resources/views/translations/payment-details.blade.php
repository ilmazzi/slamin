@extends('layout.master')

@section('main-content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-light-header">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h1 class="card-title mb-0">
                            <i class="fas fa-info-circle text-info me-2"></i>
                            Dettagli Pagamento
                        </h1>
                        <p class="card-text text-muted">
                            <i class="fas fa-book me-1"></i>{{ $application->gig->poem->title }} •
                            <i class="fas fa-user me-1"></i>{{ $application->gig->user->name }}
                        </p>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-success fs-6">
                            <i class="fas fa-check me-1"></i>Candidatura Accettata
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Dettagli Pagamento -->
        <div class="col-lg-8">
            <div class="card hover-effect">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-credit-card text-primary me-2"></i>
                        Stato Pagamento
                    </h5>
                </div>
                <div class="card-body">
                    @if($existingPayment)
                        <!-- Pagamento esistente -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Importo</label>
                                    <h4 class="text-success mb-0">
                                        <i class="fas fa-euro-sign me-1"></i>
                                        {{ number_format($existingPayment->amount, 2) }} {{ $existingPayment->currency }}
                                    </h4>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Stato</label>
                                    <div>
                                        @if($existingPayment->status === 'completed')
                                            <span class="badge bg-success fs-6">
                                                <i class="fas fa-check me-1"></i>Pagato
                                            </span>
                                        @elseif($existingPayment->status === 'processing')
                                            <span class="badge bg-warning fs-6">
                                                <i class="fas fa-clock me-1"></i>In Elaborazione
                                            </span>
                                        @elseif($existingPayment->status === 'failed')
                                            <span class="badge bg-danger fs-6">
                                                <i class="fas fa-times me-1"></i>Fallito
                                            </span>
                                        @else
                                            <span class="badge bg-secondary fs-6">
                                                <i class="fas fa-hourglass-half me-1"></i>In Attesa
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($existingPayment->paid_at)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">Data Pagamento</label>
                                    <p class="mb-0">{{ $existingPayment->paid_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label text-muted">ID Transazione</label>
                                    <p class="mb-0 font-monospace small">{{ $existingPayment->stripe_charge_id }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        @if($existingPayment->status === 'completed')
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Pagamento Completato!</strong> Il pagamento è stato ricevuto con successo.
                        </div>
                        @elseif($existingPayment->status === 'processing')
                        <div class="alert alert-warning">
                            <i class="fas fa-clock me-2"></i>
                            <strong>Pagamento in Elaborazione</strong> Il pagamento è stato avviato e sarà completato a breve.
                        </div>
                        @elseif($existingPayment->status === 'failed')
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Pagamento Fallito</strong>
                            @if($existingPayment->failure_reason)
                                Motivo: {{ $existingPayment->failure_reason }}
                            @endif
                        </div>
                        @else
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>In Attesa di Pagamento</strong> Il cliente deve ancora completare il pagamento.
                        </div>
                        @endif

                    @else
                        <!-- Nessun pagamento ancora -->
                        <div class="text-center py-5">
                            <i class="fas fa-hourglass-half text-muted mb-3" style="font-size: 3rem;"></i>
                            <h5 class="text-muted">In Attesa di Pagamento</h5>
                            <p class="text-muted">
                                Il cliente deve ancora procedere con il pagamento per questa traduzione.
                            </p>
                            <div class="mt-4">
                                <span class="badge bg-warning fs-6">
                                    <i class="fas fa-clock me-1"></i>Pagamento Pending
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Info Poesia -->
            <div class="card hover-effect mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-book text-primary me-2"></i>
                        Poesia da Tradurre
                    </h5>
                </div>
                <div class="card-body">
                    <h6 class="text-primary mb-2">{{ $application->gig->poem->title }}</h6>
                    <p class="text-muted small mb-3">{{ Str::limit($application->gig->poem->content, 150) }}</p>
                    <div class="d-flex flex-wrap gap-1">
                        @foreach($application->gig->target_languages as $lang)
                        <span class="badge bg-primary">{{ strtoupper($lang) }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Info Cliente -->
            <div class="card hover-effect mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user text-primary me-2"></i>
                        Cliente
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-0">{{ $application->gig->user->name }}</h6>
                            <small class="text-muted">Autore della poesia</small>
                        </div>
                        <span class="badge bg-primary">Cliente</span>
                    </div>
                </div>
            </div>

            <!-- Info Gig -->
            <div class="card hover-effect">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Dettagli Gig
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h6 class="text-success mb-0">
                                <i class="fas fa-euro-sign me-1"></i>{{ number_format($application->gig->compensation, 2) }}
                            </h6>
                            <small class="text-muted">Compenso</small>
                        </div>
                        <div class="col-6">
                            <h6 class="text-warning mb-0">
                                <i class="fas fa-clock me-1"></i>{{ $application->gig->deadline->format('d/m') }}
                            </h6>
                            <small class="text-muted">Scadenza</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

