@extends('layout.master')

@section('main-content')
<div class="page-content">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">?? I Miei Inviti</h4>
                    <div class="page-title-right">
                        
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <span class="text-muted text-uppercase fw-medium text-truncate d-block">Inviti Totali</span>
                                <h4 class="fs-4 fw-semibold mb-3">{{ $invitations->total() }}</h4>
                            </div>
                            <div class="flex-shrink-0 text-end">
                                <i class="ph ph-envelope-simple text-primary fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <span class="text-muted text-uppercase fw-medium text-truncate d-block">In Attesa</span>
                                <h4 class="fs-4 fw-semibold mb-3 text-warning">{{ $invitations->where('status', 'pending')->count() }}</h4>
                            </div>
                            <div class="flex-shrink-0 text-end">
                                <i class="ph ph-clock text-warning fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <span class="text-muted text-uppercase fw-medium text-truncate d-block">Accettati</span>
                                <h4 class="fs-4 fw-semibold mb-3 text-success">{{ $invitations->where('status', 'accepted')->count() }}</h4>
                            </div>
                            <div class="flex-shrink-0 text-end">
                                <i class="ph ph-check-circle text-success fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card card-animate">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <span class="text-muted text-uppercase fw-medium text-truncate d-block">Rifiutati</span>
                                <h4 class="fs-4 fw-semibold mb-3 text-danger">{{ $invitations->where('status', 'declined')->count() }}</h4>
                            </div>
                            <div class="flex-shrink-0 text-end">
                                <i class="ph ph-x-circle text-danger fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invitations List -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="card-title mb-0">?? Inviti Ricevuti</h4>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="location.reload()">
                                    <i class="ph ph-arrows-clockwise"></i> Aggiorna
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($invitations->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Evento</th>
                                            <th>Ruolo</th>
                                            <th>Organizzatore</th>
                                            <th>Data Evento</th>
                                            <th>Stato</th>
                                            <th>Azioni</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($invitations as $invitation)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($invitation->event->image && !empty($invitation->event->image))
                                                            <img src="{{ $invitation->event->image }}" alt="Event Image" class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                            <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; display: none;">
                                                                <i class="ph ph-calendar text-muted"></i>
                                                            </div>
                                                        @else
                                                            <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                                <i class="ph ph-calendar text-muted"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <h6 class="mb-0">{{ $invitation->event->title }}</h6>
                                                            <small class="text-muted">{{ $invitation->event->city }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary">{{ ucfirst($invitation->role) }}</span>
                                                    @if($invitation->compensation)
                                                        <br><small class="text-success">€{{ $invitation->compensation }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-xs me-2">
                                                            <span class="avatar-title rounded-circle bg-primary">
                                                                {{ substr($invitation->inviter->name, 0, 1) }}
                                                            </span>
                                                        </div>
                                                        <span>{{ $invitation->inviter->name }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <strong>{{ $invitation->event->start_datetime->format('d/m/Y') }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ $invitation->event->start_datetime->format('H:i') }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    @switch($invitation->status)
                                                        @case('pending')
                                                            <span class="badge bg-warning">
                                                                <i class="ph ph-clock me-1"></i>In Attesa
                                                            </span>
                                                            @if($invitation->expires_at && $invitation->expires_at->isPast())
                                                                <br><small class="text-danger">Scaduto</small>
                                                            @endif
                                                            @break
                                                        @case('accepted')
                                                            <span class="badge bg-success">
                                                                <i class="ph ph-check-circle me-1"></i>Accettato
                                                            </span>
                                                            @break
                                                        @case('declined')
                                                            <span class="badge bg-danger">
                                                                <i class="ph ph-x-circle me-1"></i>Rifiutato
                                                            </span>
                                                            @break
                                                    @endswitch
                                                </td>
                                                <td>
                                                    @if($invitation->status === 'pending')
                                                        <div class="d-flex gap-1">
                                                            <a href="{{ route('invitations.accept', $invitation) }}"
                                                               class="btn btn-success btn-sm"
                                                               onclick="return confirm('Confermi di accettare questo invito?')">
                                                                <i class="ph ph-check"></i> Accetta
                                                            </a>
                                                            <a href="{{ route('invitations.decline', $invitation) }}"
                                                               class="btn btn-danger btn-sm"
                                                               onclick="return confirm('Confermi di rifiutare questo invito?')">
                                                                <i class="ph ph-x"></i> Rifiuta
                                                            </a>
                                                        </div>
                                                    @else
                                                        <a href="{{ route('events.show', $invitation->event) }}"
                                                           class="btn btn-outline-primary btn-sm">
                                                            <i class="ph ph-eye"></i> Vedi Evento
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="d-flex justify-content-center mt-4">
                                {{ $invitations->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="ph ph-envelope-simple text-muted" style="font-size: 4rem;"></i>
                                </div>
                                <h5 class="text-muted">Nessun invito ricevuto</h5>
                                <p class="text-muted">Non hai ancora ricevuto inviti per eventi poetry slam.</p>
                                <a href="{{ route('events.index') }}" class="btn btn-primary">
                                    <i class="ph ph-calendar-plus"></i> Cerca Eventi
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-refresh every 30 seconds for pending invitations
    setInterval(function() {
        const pendingCount = document.querySelectorAll('.badge.bg-warning').length;
        if (pendingCount > 0) {
            // Only refresh if there are pending invitations
            location.reload();
        }
    }, 30000);
</script>
@endpush
