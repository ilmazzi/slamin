@extends('layout.master')

@section('title', __('events.manage_event') . ' ' . $event->title)
@section('css')

@endsection

@section('breadcrumb-title')
<h3>{{ __('events.manage_event') }}</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item"><a href="{{ route('events.index') }}">{{ __('events.events') }}</a></li>
<li class="breadcrumb-item"><a href="{{ route('events.show', $event) }}">{{ $event->title }}</a></li>
<li class="breadcrumb-item active">{{ __('common.manage') }}</li>
@endsection

@section('main-content')
<div class="container-fluid">

    <!-- Dashboard Header -->
    <div class="row m-1 mb-4">
        <div class="col-12">
            <div class="card bg-primary text-white">
                <div class="card-body p-4">
        <div class="row align-items-center">
            <div class="col-md-8">
                            <h2 class="text-white mb-3 fw-bold">{{ $event->title }}</h2>
                            @if($event->start_datetime->isPast())
                                <span class="badge bg-white text-primary fs-6 px-3 py-2">
                                    <i class="ph ph-clock me-2"></i>Evento Concluso
                                </span>
                            @elseif($event->start_datetime->diffInDays(now()) <= 7)
                                <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                                    <i class="ph ph-warning me-2"></i>Evento Imminente
                                </span>
                            @endif
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('events.show', $event) }}" class="btn btn-white text-primary me-2 px-4">
                                <i class="ph ph-eye me-2"></i>Vedi Evento
                            </a>
                            <a href="{{ route('events.edit', $event) }}" class="btn btn-light-white text-white px-4">
                                <i class="ph ph-pencil me-2"></i>Modifica
                            </a>
                    </div>
                        </div>
                    </div>
                        </div>
                    </div>
                        </div>

    <!-- Statistics Row -->
    <div class="row m-1 mb-4">
        <div class="col-12">
            <div class="row g-4">
                    <div class="col-6 col-md-3">
                        <div class="card">
                            @php $pendingInvites = $event->pendingInvitations->count(); @endphp
                            <span class="bg-warning h-50 w-50 d-flex-center rounded-circle m-auto eshop-icon-box">
                                <i class="ph ph-envelope f-s-24"></i>
                            </span>
                            <div class="card-body eshop-cards">
                                <span class="ripple-effect"></span>
                                <div class="overflow-hidden">
                                    <h3 class="text-warning mb-0">{{ $pendingInvites }}</h3>
                                    <p class="mg-b-35 f-w-600 text-dark-800 txt-ellipsis-1">Inviti Pendenti</p>
                                    <span class="badge bg-light-warning">📨 Attesa</span>
                    </div>
                </div>
            </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card">
                            @php $pendingRequests = $event->pendingRequests->count(); @endphp
                            <span class="bg-info h-50 w-50 d-flex-center rounded-circle m-auto eshop-icon-box">
                                <i class="ph ph-hand-waving f-s-24"></i>
                            </span>
                            <div class="card-body eshop-cards">
                                <span class="ripple-effect"></span>
                                <div class="overflow-hidden">
                                    <h3 class="text-info mb-0">{{ $pendingRequests }}</h3>
                                    <p class="mg-b-35 f-w-600 text-dark-800 txt-ellipsis-1">Richieste</p>
                                    <span class="badge bg-light-info">🙋 Candidature</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card">
                            @php $confirmed = $event->acceptedInvitations->count() + $event->acceptedRequests->count(); @endphp
                            <span class="bg-success h-50 w-50 d-flex-center rounded-circle m-auto eshop-icon-box">
                                <i class="ph ph-check-circle f-s-24"></i>
                            </span>
                            <div class="card-body eshop-cards">
                                <span class="ripple-effect"></span>
                                <div class="overflow-hidden">
                                    <h3 class="text-success mb-0">{{ $confirmed }}</h3>
                                    <p class="mg-b-35 f-w-600 text-dark-800 txt-ellipsis-1">Confermati</p>
                                    <span class="badge bg-light-success">✅ Partecipanti</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="card">
                            @php
                                $daysToEvent = $event->start_datetime->diffInDays(now());
                                $isPast = $event->start_datetime->isPast();
                            @endphp
                            <span class="bg-{{ $isPast ? 'secondary' : 'primary' }} h-50 w-50 d-flex-center rounded-circle m-auto eshop-icon-box">
                                <i class="ph ph-calendar-{{ $isPast ? 'x' : 'check' }} f-s-24"></i>
                            </span>
                            <div class="card-body eshop-cards">
                                <span class="ripple-effect"></span>
                                <div class="overflow-hidden">
                                    <h3 class="text-{{ $isPast ? 'secondary' : 'primary' }} mb-0">
                                        {{ abs(ceil($daysToEvent)) }}
                                    </h3>
                                    <p class="mg-b-35 f-w-600 text-dark-800 txt-ellipsis-1">
                                        {{ $isPast ? 'Giorni Fa' : 'Giorni Rimasti' }}
                                    </p>
                                    <span class="badge bg-light-{{ $isPast ? 'secondary' : 'primary' }}">
                                        {{ $isPast ? '🕒 Passato' : '⏰ Imminente' }}
                                    </span>
                                </div>
                            </div>
                                            </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row m-1 mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0"><i class="ph ph-lightning me-2"></i>Azioni Rapide</h6>
                </div>
                <div class="card-body">
        <div class="row g-3">
                        <div class="col-md-3 col-6">
                <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#inviteModal">
                    <i class="ph ph-envelope me-2"></i>Invita Artisti
                </button>
            </div>
                        <div class="col-md-3 col-6">
                            <button class="btn btn-light-primary w-100" onclick="bulkAcceptRequests()">
                    <i class="ph ph-check-circle me-2"></i>Accetta Tutte
                </button>
            </div>
                        <div class="col-md-3 col-6">
                            <button class="btn btn-light-secondary w-100" onclick="exportParticipants()">
                    <i class="ph ph-download me-2"></i>Esporta Lista
                </button>
            </div>
                        <div class="col-md-3 col-6">
                            <button class="btn btn-light-success w-100" onclick="sendUpdateNotification()">
                    <i class="ph ph-megaphone me-2"></i>Notifica Update
                </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="row m-1">
        <!-- Left Column: Pending Actions -->
        <div class="col-lg-8">

            <!-- Pending Requests -->
            @if($event->pendingRequests->count() > 0)
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ph ph-hand-waving me-2"></i>Richieste di Partecipazione
                        <span class="badge bg-warning ms-2">{{ $event->pendingRequests->count() }}</span>
                    </h5>

                    <!-- Bulk Actions -->
                    <div class="alert alert-primary d-none" id="bulkActionsRequests">
                        <div class="d-flex align-items-center justify-content-between">
                            <span><i class="ph ph-selection-all me-2"></i><span id="selectedRequestsCount">0</span> richieste selezionate</span>
                            <div class="d-flex gap-2">
                                <button class="btn btn-light-success btn-sm" onclick="bulkActionRequests('accept')">
                                <i class="ph ph-check me-1"></i>Accetta
                            </button>
                                <button class="btn btn-light-danger btn-sm" onclick="bulkActionRequests('decline')">
                                <i class="ph ph-x me-1"></i>Rifiuta
                            </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @foreach($event->pendingRequests as $request)
                        <div class="participant-item" data-request-id="{{ $request->id }}">
                            <div class="d-flex align-items-start">
                                <div class="form-check me-3">
                                    <input type="checkbox" class="form-check-input request-checkbox" value="{{ $request->id }}">
                                </div>

                                <div class="participant-avatar me-3">
                                    {{ substr($request->user->name, 0, 2) }}
                                </div>

                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h6 class="mb-1">{{ $request->user->name }}</h6>
                                            <span class="role-badge">{{ ucfirst($request->requested_role) }}</span>
                                            <small class="text-muted ms-2">
                                                <i class="ph ph-clock me-1"></i>{{ $request->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-light-success btn-sm" onclick="quickResponse({{ $request->id }}, 'accept')">
                                                <i class="ph ph-check me-1"></i>Accetta
                                            </button>
                                            <button class="btn btn-light-danger btn-sm" onclick="quickResponse({{ $request->id }}, 'decline')">
                                                <i class="ph ph-x me-1"></i>Rifiuta
                                            </button>
                                            <button class="btn btn-light-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#requestDetailModal" data-request-id="{{ $request->id }}">
                                                <i class="ph ph-eye"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="message-preview">
                                        {{ Str::limit($request->message, 150) }}
                                    </div>

                                    @if($request->experience)
                                        <small class="text-muted">
                                            <strong>Esperienza:</strong> {{ Str::limit($request->experience, 100) }}
                                        </small>
                                    @endif

                                    @if($request->portfolio_links)
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                <strong>Portfolio:</strong>
                                                @foreach($request->portfolio_links as $link)
                                                    <a href="{{ $link }}" target="_blank" class="me-2">
                                                        <i class="ph ph-link me-1"></i>Link {{ $loop->iteration }}
                                                    </a>
                                                @endforeach
                                            </small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Pending Invitations -->
            @if($event->pendingInvitations->count() > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph ph-envelope me-2"></i>Inviti in Attesa di Risposta
                        <span class="badge bg-primary ms-2">{{ $event->pendingInvitations->count() }}</span>
                    </h5>
                </div>
                <div class="card-body p-3">
                    <div class="list-group list-group-flush">
                    @foreach($event->pendingInvitations as $invitation)
                            <div class="list-group-item">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="h-45 w-45 d-flex-center b-r-50 overflow-hidden text-bg-warning">
                                    {{ substr($invitation->invitedUser->name, 0, 2) }}
                                </div>
                                    </div>
                                    <div class="col text-truncate">
                                        <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $invitation->invitedUser->name }}</h6>
                                                <div class="mb-2">
                                                    <span class="badge bg-light-info">{{ ucfirst($invitation->role) }}</span>
                                            <small class="text-muted ms-2">
                                                Invitato {{ $invitation->created_at->diffForHumans() }}
                                            </small>
                                                </div>
                                            @if($invitation->expires_at)
                                                    <div class="mb-2">
                                                        <small class="text-warning">
                                                    <i class="ph ph-clock me-1"></i>Scade {{ $invitation->expires_at->diffForHumans() }}
                                                </small>
                                                    </div>
                                            @endif
                                                @if($invitation->message)
                                                    <div class="alert alert-light-primary p-2 mt-2">
                                                        <small><em>"{{ $invitation->message }}"</em></small>
                                        </div>
                                                @endif
                                            </div>
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-outline-primary btn-sm" onclick="resendInvitation({{ $invitation->id }})">
                                                <i class="ph ph-arrow-clockwise me-1"></i>Reinvia
                                            </button>
                                                                                <button class="btn btn-light-danger btn-sm" onclick="cancelInvitation({{ $invitation->id }})">
                                                <i class="ph ph-x me-1"></i>Cancella
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Confirmed Participants -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph ph-users me-2"></i>Partecipanti Confermati
                        <span class="badge bg-success ms-2">{{ $event->acceptedInvitations->count() + $event->acceptedRequests->count() }}</span>
                    </h5>
                </div>
                <div class="card-body p-3">
                    @php
                        $confirmedParticipants = collect();
                        $confirmedParticipants = $confirmedParticipants->merge(
                            $event->acceptedInvitations->map(function($invitation) {
                                return [
                                    'user' => $invitation->invitedUser,
                                    'role' => $invitation->role,
                                    'type' => 'invited',
                                    'confirmed_at' => $invitation->responded_at
                                ];
                            })
                        );
                        $confirmedParticipants = $confirmedParticipants->merge(
                            $event->acceptedRequests->map(function($request) {
                                return [
                                    'user' => $request->user,
                                    'role' => $request->requested_role,
                                    'type' => 'requested',
                                    'confirmed_at' => $request->reviewed_at
                                ];
                            })
                        );
                    @endphp

                    @if($confirmedParticipants->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($confirmedParticipants as $participant)
                                <div class="list-group-item">
                                    <div class="row">
                                        <div class="col-auto">
                                            <div class="h-45 w-45 d-flex-center b-r-50 overflow-hidden text-bg-success">
                                                {{ substr($participant['user']->name, 0, 2) }}
                                            </div>
                                        </div>
                                        <div class="col">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                <h6 class="mb-1">{{ $participant['user']->name }}</h6>
                                                    <div class="mb-1">
                                                        <span class="badge bg-light-primary">{{ ucfirst($participant['role']) }}</span>
                                                        <span class="badge bg-light-success ms-1">
                                                            {{ $participant['type'] === 'invited' ? '📨 Invitato' : '🙋 Richiesta' }}
                                                    </span>
                                                </div>
                                                <small class="text-muted">
                                                    Confermato {{ $participant['confirmed_at']->diffForHumans() }}
                                                </small>
                                            </div>
                                            <button class="btn btn-outline-secondary btn-sm" onclick="contactParticipant('{{ $participant['user']->id }}')">
                                                <i class="ph ph-chat-circle"></i>
                                            </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="ph ph-users-three display-4 text-muted mb-3"></i>
                            <p class="text-muted">Nessun partecipante confermato ancora</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Right Column: Timeline & Analytics -->
        <div class="col-lg-4 mt-4 mt-lg-0">

            <!-- Event Timeline -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph ph-clock me-2"></i>Timeline Evento
                    </h6>
                </div>
                <div class="card-body p-3">
                    <ul class="app-timeline-box">
                    @if($event->registration_deadline && $event->registration_deadline > now())
                            <li class="timeline-section">
                                <div class="timeline-icon">
                                    <span class="text-light-danger h-35 w-35 d-flex-center b-r-50">
                                        <i class="ph ph-clock f-s-20"></i>
                                    </span>
                                </div>
                                <div class="timeline-content bg-light-danger b-1-danger">
                                    <div class="d-flex justify-content-between align-items-center timeline-flex">
                                        <h6 class="mb-1 text-danger">Scadenza Iscrizioni</h6>
                                        <span class="badge bg-danger">Urgente</span>
                                    </div>
                            <p class="text-muted mb-0">{{ $event->registration_deadline->format('d/m/Y H:i') }}</p>
                            <small class="text-danger">{{ $event->registration_deadline->diffForHumans() }}</small>
                        </div>
                            </li>
                    @endif

                        <li class="timeline-section">
                            <div class="timeline-icon">
                                <span class="text-light-primary h-35 w-35 d-flex-center b-r-50">
                                    <i class="ph ph-play f-s-20"></i>
                                </span>
                            </div>
                            <div class="timeline-content bg-light-primary b-1-primary">
                                <div class="d-flex justify-content-between align-items-center timeline-flex">
                                    <h6 class="mb-1 text-primary">Inizio Evento</h6>
                                    <span class="badge bg-primary">{{ $event->start_datetime->diffForHumans() }}</span>
                                </div>
                        <p class="text-muted mb-0">{{ $event->start_datetime->format('d/m/Y H:i') }}</p>
                    </div>
                        </li>

                        <li class="timeline-section">
                            <div class="timeline-icon">
                                <span class="text-light-success h-35 w-35 d-flex-center b-r-50">
                                    <i class="ph ph-check f-s-20"></i>
                                </span>
                            </div>
                            <div class="timeline-content bg-light-success b-1-success">
                                <div class="d-flex justify-content-between align-items-center timeline-flex">
                                    <h6 class="mb-1 text-success">Fine Evento</h6>
                                    <span class="badge bg-success">{{ $event->duration }}h</span>
                                </div>
                        <p class="text-muted mb-0">{{ $event->end_datetime->format('d/m/Y H:i') }}</p>
                    </div>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph ph-chart-bar me-2"></i>Statistiche Rapide
                    </h6>
                </div>
                <div class="card-body p-3">
                    <div class="row g-3 text-center">
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <div class="h4 text-primary mb-1">{{ $event->invitations->count() }}</div>
                                <small class="text-muted">Inviti Totali</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <div class="h4 text-success mb-1">{{ $event->requests->count() }}</div>
                                <small class="text-muted">Richieste Totali</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <div class="h4 text-warning mb-1">
                                    {{ $event->acceptedInvitations->count() }}
                                </div>
                                <small class="text-muted">Inviti Accettati</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-3">
                                <div class="h4 text-info mb-1">
                                    {{ round(($event->acceptedInvitations->count() / max($event->invitations->count(), 1)) * 100) }}%
                                </div>
                                <small class="text-muted">Tasso Accettazione</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Available Artists -->
            @if($availableArtists->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph ph-user-plus me-2"></i>Artisti Disponibili
                        <span class="badge bg-secondary ms-2">{{ $availableArtists->count() }}</span>
                    </h6>
                </div>
                <div class="card-body">
                    @foreach($availableArtists->take(5) as $artist)
                        <div class="d-flex align-items-center mb-3">
                            <div class="participant-avatar me-3" style="width: 35px; height: 35px; font-size: 0.9rem;">
                                {{ substr($artist->name, 0, 2) }}
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 small">{{ $artist->name }}</h6>
                                <small class="text-muted">{{ $artist->role_display_name }}</small>
                            </div>
                            <button class="btn btn-outline-primary btn-sm" onclick="quickInvite({{ $artist->id }})">
                                <i class="ph ph-plus"></i>
                            </button>
                        </div>
                    @endforeach

                    @if($availableArtists->count() > 5)
                        <button class="btn btn-light-secondary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#inviteModal">
                            Vedi tutti ({{ $availableArtists->count() - 5 }} altri)
                        </button>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

    <!-- Floating Action Button -->
    <div class="position-fixed" style="bottom: 30px; right: 30px; z-index: 1000;">
        <button class="btn btn-primary rounded-circle p-3 position-relative"
                data-bs-toggle="modal" data-bs-target="#inviteModal"
                title="Invita Artisti"
                style="width: 60px; height: 60px;">
            <i class="ph ph-envelope f-s-20"></i>
        @if($event->pendingRequests->count() > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    {{ $event->pendingRequests->count() }}
                </span>
        @endif
    </button>
</div>

<!-- Invite Modal -->
<div class="modal fade" id="inviteModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ph ph-envelope me-2"></i>Invita Artisti
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="inviteForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Seleziona Artisti *</label>
                            <div style="max-height: 200px; overflow-y: auto; border: 1px solid #e9ecef; border-radius: 5px; padding: 10px;">
                                @foreach($availableArtists as $artist)
                                    <div class="form-check">
                                        <input type="checkbox" name="invited_user_ids[]" value="{{ $artist->id }}" class="form-check-input" id="artist_{{ $artist->id }}">
                                        <label for="artist_{{ $artist->id }}" class="form-check-label d-flex align-items-center">
                                            <div class="participant-avatar me-2" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                                {{ substr($artist->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <div>{{ $artist->name }}</div>
                                                <small class="text-muted">{{ $artist->role_display_name }}</small>
                                            </div>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Ruolo *</label>
                                <select name="role" class="form-select" required>
                                    <option value="performer">Performer</option>
                                    <option value="judge">Judge</option>
                                    <option value="technician">Technician</option>
                                    <option value="host">Host</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Compenso (€)</label>
                                <input type="number" name="compensation" class="form-control" min="0" step="0.01" placeholder="0.00">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Scadenza Invito</label>
                                <input type="datetime-local" name="expires_at" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Messaggio Personalizzato</label>
                        <textarea name="message" class="form-control" rows="4" placeholder="Scrivi un messaggio personalizzato per gli invitati..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ph ph-paper-plane me-2"></i>Invia Inviti
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Request Detail Modal -->
<div class="modal fade" id="requestDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dettagli Richiesta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="requestDetailContent">
                <!-- Content loaded dynamically -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                                    <button type="button" class="btn btn-light-danger me-2" onclick="respondToRequest('decline')">
                    <i class="ph ph-x me-2"></i>Rifiuta
                </button>
                    <button type="button" class="btn btn-light-success" onclick="respondToRequest('accept')">
                    <i class="ph ph-check me-2"></i>Accetta
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
let selectedRequests = [];
let currentRequestId = null;

document.addEventListener('DOMContentLoaded', function() {

    // Request checkboxes
    document.querySelectorAll('.request-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                selectedRequests.push(this.value);
            } else {
                selectedRequests = selectedRequests.filter(id => id !== this.value);
            }
            updateBulkActions();
        });
    });

    // Invite form
    document.getElementById('inviteForm').addEventListener('submit', function(e) {
        e.preventDefault();
        sendInvitations();
    });

    // Request detail modal
    document.getElementById('requestDetailModal').addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const requestId = button.getAttribute('data-request-id');
        loadRequestDetail(requestId);
    });

    // Auto-refresh pending items every 30 seconds
    setInterval(refreshPendingItems, 30000);
});

function updateBulkActions() {
    const bulkActions = document.getElementById('bulkActionsRequests');
    const count = selectedRequests.length;

    if (count > 0) {
        bulkActions.classList.remove('d-none');
        document.getElementById('selectedRequestsCount').textContent = count;
    } else {
        bulkActions.classList.add('d-none');
    }
}

function quickResponse(requestId, action) {
    const data = {
        action: action,
        message: action === 'accept' ? 'Richiesta accettata!' : 'Richiesta non accettata.'
    };

    fetch(`/requests/${requestId}/quick-response`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCSRFToken() || ''
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            removeParticipantItem(requestId);
            updateStats();
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        showNotification('Errore di connessione', 'error');
    });
}

function bulkActionRequests(action) {
    if (selectedRequests.length === 0) return;

    const message = prompt(`Inserisci un messaggio per ${action === 'accept' ? 'accettare' : 'rifiutare'} ${selectedRequests.length} richieste:`);
    if (message === null) return;

    const data = {
        action: action,
        request_ids: selectedRequests,
        response_message: message
    };

    fetch(`/requests/api/events/{{ $event->id }}/bulk-action`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCSRFToken() || ''
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            selectedRequests.forEach(id => removeParticipantItem(id));
            selectedRequests = [];
            updateBulkActions();
            updateStats();
        } else {
            showNotification('Errore nell\'operazione', 'error');
        }
    });
}

function sendInvitations() {
    const formData = new FormData(document.getElementById('inviteForm'));
    const data = {
        event_id: {{ $event->id }},
        invited_user_ids: formData.getAll('invited_user_ids[]'),
        role: formData.get('role'),
        compensation: formData.get('compensation'),
        expires_at: formData.get('expires_at'),
        message: formData.get('message')
    };

    if (data.invited_user_ids.length === 0) {
        showNotification('Seleziona almeno un artista', 'error');
        return;
    }

    fetch('/invitations', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': getCSRFToken() || ''
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
            bootstrap.Modal.getInstance(document.getElementById('inviteModal')).hide();
            setTimeout(() => location.reload(), 1500);
        } else {
            showNotification(data.message || 'Errore nell\'invio', 'error');
        }
    })
    .catch(error => {
        showNotification('Errore di connessione', 'error');
    });
}

function quickInvite(artistId) {
    // Controllo sicurezza CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        showNotification('Errore di sicurezza: token CSRF mancante', 'error');
        return;
    }

    const data = {
        event_id: {{ $event->id }},
        invited_user_ids: [artistId],
        role: 'performer',
        message: 'Ti invito a partecipare al mio evento!'
    };

    fetch('/invitations', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken.content
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Invito inviato!', 'success');
        } else {
            showNotification('Errore nell\'invio', 'error');
        }
    });
}

function resendInvitation(invitationId) {
    // Controllo sicurezza CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        showNotification('Errore di sicurezza: token CSRF mancante', 'error');
        return;
    }

    fetch(`/invitations/${invitationId}/resend`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken.content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification(data.message, 'success');
        } else {
            showNotification('Errore nel reinvio', 'error');
        }
    });
}

function cancelInvitation(invitationId) {
    if (confirm('Sei sicuro di voler cancellare questo invito?')) {
        fetch(`/invitations/${invitationId}`, {
            method: 'DELETE',
            headers: {
            'X-CSRF-TOKEN': getCSRFToken() || ''
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Invito cancellato', 'success');
                location.reload();
            } else {
                showNotification('Errore nella cancellazione', 'error');
            }
        });
    }
}

function loadRequestDetail(requestId) {
    currentRequestId = requestId;

    // Find request data from page
    const requestElement = document.querySelector(`[data-request-id="${requestId}"]`);
    if (requestElement) {
        // For now, show basic info. In a real app, you'd fetch full details
        document.getElementById('requestDetailContent').innerHTML = `
            <div class="text-center">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Caricamento...</span>
                </div>
            </div>
        `;

        // Simulate loading
        setTimeout(() => {
            document.getElementById('requestDetailContent').innerHTML = `
                <div class="alert alert-info">
                    <p>Dettagli completi della richiesta verranno caricati qui.</p>
                    <p>Per ora, utilizza i pulsanti di azione rapida nella lista principale.</p>
                </div>
            `;
        }, 1000);
    }
}

function respondToRequest(action) {
    if (!currentRequestId) return;

    const message = prompt(`Inserisci un messaggio per ${action === 'accept' ? 'accettare' : 'rifiutare'} questa richiesta:`);
    if (message === null) return;

    quickResponse(currentRequestId, action);
    bootstrap.Modal.getInstance(document.getElementById('requestDetailModal')).hide();
}

function removeParticipantItem(requestId) {
    const element = document.querySelector(`[data-request-id="${requestId}"]`);
    if (element) {
        element.style.transition = 'all 0.3s ease';
        element.style.opacity = '0';
        element.style.transform = 'translateX(-100%)';
        setTimeout(() => element.remove(), 300);
    }
}

function updateStats() {
    // Update stats in real-time
    fetch(`/requests/api/events/{{ $event->id }}/statistics`)
        .then(response => response.json())
        .then(data => {
            // Update stats displays
            console.log('Stats updated:', data);
        });
}

function refreshPendingItems() {
    // Auto-refresh pending items
    fetch(window.location.href + '?ajax=1')
        .then(response => response.text())
        .then(html => {
            // In a real implementation, you'd update only the changed parts
            console.log('Auto-refresh completed');
        });
}

function exportParticipants() {
    window.open(`/events/{{ $event->id }}/export`, '_blank');
}

function sendUpdateNotification() {
    const message = prompt('Inserisci il messaggio di aggiornamento:');
    if (message) {
        // Send notification to all participants
        fetch(`/events/{{ $event->id }}/notify`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCSRFToken() || ''
            },
            body: JSON.stringify({ message })
        })
        .then(response => response.json())
        .then(data => {
            showNotification('Notifica inviata a tutti i partecipanti', 'success');
        });
    }
}

function contactParticipant(userId) {
    // Open chat or contact modal
    showNotification('Funzione chat in sviluppo', 'info');
}

function bulkAcceptRequests() {
    const checkboxes = document.querySelectorAll('.request-checkbox');
    checkboxes.forEach(cb => {
        cb.checked = true;
        if (!selectedRequests.includes(cb.value)) {
            selectedRequests.push(cb.value);
        }
    });
    updateBulkActions();

    if (selectedRequests.length > 0) {
        bulkActionRequests('accept');
    }
}

// Helper function per CSRF token sicuro
function getCSRFToken() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (!csrfToken) {
        console.error('CSRF token not found');
        return null;
    }
    return csrfToken.content;
}

function showNotification(message, type) {
    const alert = document.createElement('div');
    alert.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : 'info'} alert-dismissible fade show position-fixed`;
    alert.style.top = '20px';
    alert.style.right = '20px';
    alert.style.zIndex = '9999';
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alert);

    setTimeout(() => {
        alert.remove();
    }, 5000);
}
</script>
@endsection
