@extends('layout.master')

@section('title', 'Richieste Ricevute - Slamin')

@section('main-content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">Richieste Ricevute</h4>
            
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="ph-duotone ph-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="ph-duotone ph-warning me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    </div>
    @endif

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card eshop-card hover-effect">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="icon-box bg-primary">
                                <i class="ph-duotone ph-hand-waving text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Richieste Totali</h6>
                            <h4 class="mb-0 f-w-600">{{ $requests->total() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card eshop-card hover-effect">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="icon-box bg-warning">
                                <i class="ph-duotone ph-clock text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">In Attesa</h6>
                            <h4 class="mb-0 f-w-600">{{ $requests->where('status', 'pending')->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card eshop-card hover-effect">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="icon-box bg-success">
                                <i class="ph-duotone ph-check-circle text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Approvate</h6>
                            <h4 class="mb-0 f-w-600">{{ $requests->where('status', 'accepted')->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card eshop-card hover-effect">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="icon-box bg-danger">
                                <i class="ph-duotone ph-x-circle text-white"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1">Rifiutate</h6>
                            <h4 class="mb-0 f-w-600">{{ $requests->where('status', 'declined')->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Requests List -->
    <div class="row">
        <div class="col-12">
            <div class="card hover-effect">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="ph-duotone ph-list-checks me-2"></i>
                            Richieste di Partecipazione
                        </h5>
                        <button type="button" class="btn btn-outline-primary hover-effect" onclick="location.reload()">
                            <i class="ph-duotone ph-arrows-clockwise me-2"></i>Aggiorna
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($requests->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0">Evento</th>
                                        <th class="border-0">Richiedente</th>
                                        <th class="border-0">Ruolo Richiesto</th>
                                        <th class="border-0">Data Richiesta</th>
                                        <th class="border-0">Stato</th>
                                        <th class="border-0">Azioni</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($requests as $request)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($request->event->image_url)
                                                        <img src="{{ $request->event->image_url }}" alt="{{ $request->event->title }}" class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                            <i class="ph-duotone ph-calendar text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0 f-w-600">{{ $request->event->title }}</h6>
                                                        <small class="text-muted">{{ $request->event->city ?? 'Luogo non specificato' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="me-3">
                                                        @if($request->user->profile_photo)
                                                            <img src="{{ $request->user->profile_photo_url }}" alt="{{ $request->user->name }}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                                        @else
                                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 16px; font-weight: bold;">
                                                                {{ substr($request->user->name, 0, 1) }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 f-w-600">{{ $request->user->name }}</h6>
                                                        <small class="text-muted">{{ $request->user->getPrivacySafeIdentifier() }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">{{ ucfirst($request->requested_role) }}</span>
                                                @if($request->experience_level)
                                                    <br><small class="text-muted">{{ $request->experience_level }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <div>
                                                    <strong>{{ $request->created_at->format('d/m/Y') }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ $request->created_at->format('H:i') }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                @switch($request->status)
                                                    @case('pending')
                                                        <span class="badge bg-warning">
                                                            <i class="ph-duotone ph-clock me-1"></i>In Attesa
                                                        </span>
                                                        @break
                                                    @case('accepted')
                                                        <span class="badge bg-success">
                                                            <i class="ph-duotone ph-check-circle me-1"></i>Approvata
                                                        </span>
                                                        @break
                                                    @case('declined')
                                                        <span class="badge bg-danger">
                                                            <i class="ph-duotone ph-x-circle me-1"></i>Rifiutata
                                                        </span>
                                                        @break
                                                    @case('cancelled')
                                                        <span class="badge bg-secondary">
                                                            <i class="ph-duotone ph-x me-1"></i>Cancellata
                                                        </span>
                                                        @break
                                                @endswitch
                                            </td>
                                            <td>
                                                @if($request->status === 'pending')
                                                    <div class="d-flex gap-2">
                                                        <button type="button"
                                                                class="btn btn-success btn-sm hover-effect"
                                                                onclick="acceptRequest({{ $request->id }})">
                                                            <i class="ph-duotone ph-check me-1"></i> Approva
                                                        </button>
                                                        <button type="button"
                                                                class="btn btn-danger btn-sm hover-effect"
                                                                onclick="declineRequest({{ $request->id }})">
                                                            <i class="ph-duotone ph-x me-1"></i> Rifiuta
                                                        </button>
                                                    </div>
                                                @else
                                                    <span class="text-muted">Nessuna azione disponibile</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="card-footer">
                            {{ $requests->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="icon-box bg-light mx-auto mb-3">
                                <i class="ph-duotone ph-hand-waving text-muted f-s-48"></i>
                            </div>
                            <h5 class="text-muted">Nessuna richiesta ricevuta</h5>
                            <p class="text-muted">Non hai ancora ricevuto richieste di partecipazione ai tuoi eventi.</p>
                            <a href="{{ route('events.index') }}" class="btn btn-primary hover-effect">
                                <i class="ph-duotone ph-calendar me-2"></i>I Miei Eventi
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Response Modal -->
<div class="modal fade" id="responseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rispondi alla Richiesta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="responseForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="organizer_response" class="form-label">Messaggio di risposta (opzionale)</label>
                        <textarea class="form-control" id="organizer_response" name="organizer_response" rows="3" placeholder="Scrivi un messaggio per il richiedente..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <button type="submit" class="btn btn-primary">Conferma</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CSRF Token for AJAX -->
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@push('scripts')
<script>
let currentRequestId = null;
let currentAction = null;

function acceptRequest(requestId) {
    currentRequestId = requestId;
    currentAction = 'accept';
    $('#responseModal').modal('show');
}

function declineRequest(requestId) {
    currentRequestId = requestId;
    currentAction = 'decline';
    $('#responseModal').modal('show');
}

$('#responseForm').on('submit', function(e) {
    e.preventDefault();

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const response = $('#organizer_response').val();

    const url = currentAction === 'accept'
        ? `/requests/${currentRequestId}/accept`
        : `/requests/${currentRequestId}/decline`;

    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json',
        },
        body: JSON.stringify({
            organizer_response: response
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Errore: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Errore durante l\'operazione');
    });

    $('#responseModal').modal('hide');
    $('#organizer_response').val('');
});

// Hide loader as fallback
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const loader = document.querySelector('.loader-wrapper');
        if (loader) {
            loader.style.display = 'none';
        }
    }, 1000);
});
</script>
@endsection
