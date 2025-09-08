@extends('layout.master')

@section('title', 'I Miei Pagamenti')

@section('main-content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">I Miei Pagamenti</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a href="{{ route('dashboard') }}" class="f-s-14 f-w-500">
                            <span>
                                <i class="ph-duotone ph-house f-s-16"></i> Dashboard
                            </span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="#" class="f-s-14 f-w-500">Pagamenti</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card card-light-success">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="ph-duotone ph-currency-eur f-s-32 text-success"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Totale Guadagnato</h6>
                                <h4 class="mb-0">€{{ number_format($stats['total_earned'], 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-light-primary">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="ph-duotone ph-check-circle f-s-32 text-primary"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">Pagamenti Ricevuti</h6>
                                <h4 class="mb-0">€{{ number_format($stats['total_paid_out'], 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-light-warning">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="ph-duotone ph-clock f-s-32 text-warning"></i>
                            </div>
                            <div>
                                <h6 class="mb-1">In Attesa</h6>
                                <h4 class="mb-0">€{{ number_format($stats['pending_payout'], 2) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payout Method Status -->
        @if(!$user->payout_method_configured)
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-warning">
                    <div class="d-flex align-items-center">
                        <i class="ph-duotone ph-warning f-s-24 me-3"></i>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">Metodo di Pagamento Non Configurato</h6>
                            <p class="mb-0">Configura un metodo di pagamento per ricevere i tuoi guadagni automaticamente.</p>
                        </div>
                        <div>
                            <a href="{{ route('translator.payouts.setup') }}" class="btn btn-warning">
                                <i class="ph-duotone ph-gear me-1"></i>Configura
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="row mb-4">
            <div class="col-12">
                <div class="alert alert-success">
                    <div class="d-flex align-items-center">
                        <i class="ph-duotone ph-check-circle f-s-24 me-3"></i>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">Metodo di Pagamento Configurato</h6>
                            <p class="mb-0">Riceverai i pagamenti automaticamente sul tuo account configurato.</p>
                        </div>
                        <div>
                            <a href="{{ route('translator.payouts.setup') }}" class="btn btn-outline-success">
                                <i class="ph-duotone ph-gear me-1"></i>Modifica
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Payments List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ph-duotone ph-receipt f-s-16 me-2"></i>
                            Storico Pagamenti
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($payments->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Poesia</th>
                                            <th>Cliente</th>
                                            <th>Importo</th>
                                            <th>Stato</th>
                                            <th>Data</th>
                                            <th>Azioni</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($payments as $payment)
                                        <tr>
                                            <td>
                                                <div>
                                                    <h6 class="mb-1">{{ $payment->poem->title }}</h6>
                                                    <small class="text-muted">{{ Str::limit($payment->poem->content, 50) }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2">
                                                        <i class="ph-duotone ph-user"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $payment->client->name }}</h6>
                                                        <small class="text-muted">{{ $payment->client->email }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <h6 class="mb-0 text-success">€{{ number_format($payment->translator_amount, 2) }}</h6>
                                                <small class="text-muted">Commissione: €{{ number_format($payment->commission_total, 2) }}</small>
                                            </td>
                                            <td>
                                                @switch($payment->payout_status)
                                                    @case('transferred')
                                                        <span class="badge bg-success">
                                                            <i class="ph-duotone ph-check-circle me-1"></i>Ricevuto
                                                        </span>
                                                        @break
                                                    @case('pending')
                                                        <span class="badge bg-warning">
                                                            <i class="ph-duotone ph-clock me-1"></i>In Attesa
                                                        </span>
                                                        @break
                                                    @case('manual_required')
                                                        <span class="badge bg-info">
                                                            <i class="ph-duotone ph-hand me-1"></i>Manuale
                                                        </span>
                                                        @break
                                                    @case('failed')
                                                        <span class="badge bg-danger">
                                                            <i class="ph-duotone ph-x-circle me-1"></i>Fallito
                                                        </span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">
                                                            <i class="ph-duotone ph-question me-1"></i>Sconosciuto
                                                        </span>
                                                @endswitch
                                            </td>
                                            <td>
                                                <div>
                                                    <h6 class="mb-0">{{ $payment->created_at->format('d/m/Y') }}</h6>
                                                    <small class="text-muted">{{ $payment->created_at->format('H:i') }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="{{ route('translator.payouts.show', $payment) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="ph-duotone ph-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center">
                                {{ $payments->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="ph-duotone ph-receipt f-s-64 text-muted mb-3"></i>
                                <h5 class="text-muted">Nessun pagamento ricevuto</h5>
                                <p class="text-muted">I tuoi pagamenti appariranno qui una volta completate le traduzioni.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
