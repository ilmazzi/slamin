@extends('layout.master')

@section('title', 'Dettagli Invito')

@section('main-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="ph-duotone ph-envelope me-2 text-primary"></i>
                            Dettagli Invito
                        </h4>
                        <a href="{{ route('group-invitations.index') }}" class="btn btn-outline-primary">
                            <i class="ph-duotone ph-arrow-left me-2"></i>
                            Torna agli Inviti
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Informazioni Gruppo -->
                        <div class="col-md-6">
                            <div class="card card-light-primary">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="ph-duotone ph-users me-2"></i>
                                        Gruppo
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="flex-shrink-0 me-3">
                                            @if($invitation->group->avatar)
                                                <img src="{{ asset('storage/' . $invitation->group->avatar) }}"
                                                     class="rounded-circle"
                                                     width="64"
                                                     height="64"
                                                     alt="{{ $invitation->group->name }}">
                                            @else
                                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center"
                                                     style="width: 64px; height: 64px;">
                                                    <i class="ph-duotone ph-users text-white" style="font-size: 32px;"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <h5 class="mb-1">{{ $invitation->group->name }}</h5>
                                            <p class="text-muted mb-0">{{ $invitation->group->description ?: 'Nessuna descrizione' }}</p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-6">
                                            <small class="text-muted">Membri</small>
                                            <div class="fw-medium">{{ $invitation->group->members()->count() }}</div>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Creato il</small>
                                            <div class="fw-medium">{{ $invitation->group->created_at->format('d/m/Y') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informazioni Invito -->
                        <div class="col-md-6">
                            <div class="card card-light-info">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="ph-duotone ph-info me-2"></i>
                                        Dettagli Invito
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <small class="text-muted">Stato</small>
                                        <div>
                                            @if($invitation->isPending())
                                                @if($invitation->isExpired())
                                                    <span class="badge bg-danger">Scaduto</span>
                                                @else
                                                    <span class="badge bg-warning">In Attesa</span>
                                                @endif
                                            @elseif($invitation->isAccepted())
                                                <span class="badge bg-success">Accettato</span>
                                            @elseif($invitation->isDeclined())
                                                <span class="badge bg-secondary">Rifiutato</span>
                                            @else
                                                <span class="badge bg-light text-dark">{{ ucfirst($invitation->status) }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <small class="text-muted">Inviato il</small>
                                        <div class="fw-medium">{{ $invitation->created_at->format('d/m/Y H:i') }}</div>
                                    </div>
                                    @if($invitation->expires_at)
                                        <div class="mb-3">
                                            <small class="text-muted">Scade il</small>
                                            <div class="fw-medium">{{ $invitation->expires_at->format('d/m/Y H:i') }}</div>
                                        </div>
                                    @endif
                                    @if($invitation->message)
                                        <div class="mb-3">
                                            <small class="text-muted">Messaggio</small>
                                            <div class="fw-medium">{{ $invitation->message }}</div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informazioni Utenti -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card card-light-success">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="ph-duotone ph-user me-2"></i>
                                        @if($invitation->user_id === auth()->id())
                                            Tu (Destinatario)
                                        @else
                                            Destinatario
                                        @endif
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if($invitation->user)
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($invitation->user) }}"
                                                     class="rounded-circle"
                                                     width="48"
                                                     height="48"
                                                     alt="{{ $invitation->user->name }}">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $invitation->user->name }}</h6>
                                                <p class="text-muted mb-0">{{ $invitation->user->getPrivacySafeIdentifier() }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-muted">Utente non trovato</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card card-light-warning">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="ph-duotone ph-user-plus me-2"></i>
                                        @if($invitation->invited_by === auth()->id())
                                            Tu (Invitante)
                                        @else
                                            Invitante
                                        @endif
                                    </h5>
                                </div>
                                <div class="card-body">
                                    @if($invitation->invitedBy)
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($invitation->invitedBy) }}"
                                                     class="rounded-circle"
                                                     width="48"
                                                     height="48"
                                                     alt="{{ $invitation->invitedBy->name }}">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1">{{ $invitation->invitedBy->name }}</h6>
                                                <p class="text-muted mb-0">{{ $invitation->invitedBy->email }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <p class="text-muted">Utente non trovato</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Azioni -->
                    @if($invitation->user_id === auth()->id() && $invitation->isPending() && !$invitation->isExpired())
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card card-light-primary">
                                    <div class="card-header">
                                        <h5 class="mb-0">
                                            <i class="ph-duotone ph-check-circle me-2"></i>
                                            Rispondi all'Invito
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex gap-3">
                                            <form action="{{ route('group-invitations.accept', $invitation) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success">
                                                    <i class="ph-duotone ph-check me-2"></i>
                                                    Accetta Invito
                                                </button>
                                            </form>
                                            <form action="{{ route('group-invitations.decline', $invitation) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-secondary">
                                                    <i class="ph-duotone ph-x me-2"></i>
                                                    Rifiuta Invito
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if($invitation->invited_by === auth()->id() && $invitation->isPending())
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card card-light-warning">
                                    <div class="card-header">
                                        <h5 class="mb-0">
                                            <i class="ph-duotone ph-gear me-2"></i>
                                            Gestisci Invito
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex gap-3">
                                            @if($invitation->isExpired())
                                                <form action="{{ route('group-invitations.resend', $invitation) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-warning">
                                                        <i class="ph-duotone ph-arrow-clockwise me-2"></i>
                                                        Rinvia Invito
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('group-invitations.cancel', $invitation) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Sei sicuro di voler cancellare questo invito?')">
                                                    <i class="ph-duotone ph-trash me-2"></i>
                                                    Cancella Invito
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
