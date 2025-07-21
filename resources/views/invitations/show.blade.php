@extends('layout.master')

@section('title', 'Dettagli Invito - Slamin')

@section('main-content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">Dettagli Invito</h4>
            <ul class="app-line-breadcrumbs mb-3">
                <li class="">
                    <a href="{{ route('dashboard') }}" class="f-s-14 f-w-500">
                        <span>
                            <i class="ph-duotone ph-house f-s-16"></i> Dashboard
                        </span>
                    </a>
                </li>
                <li class="">
                    <a href="{{ route('invitations.index') }}" class="f-s-14 f-w-500">I Miei Inviti</a>
                </li>
                <li class="active">
                    <a href="#" class="f-s-14 f-w-500">Dettagli Invito</a>
                </li>
            </ul>
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

    <!-- Invitation Details -->
    <div class="row">
        <div class="col-lg-8">
            <!-- Event Information -->
            <div class="card hover-effect mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph-duotone ph-calendar me-2"></i>
                        Informazioni Evento
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1">Titolo Evento</h6>
                            <p class="mb-0 f-w-600">{{ $invitation->event->title ?? 'Evento non trovato' }}</p>
                        </div>
                                                <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1">Organizzatore</h6>
                            <div class="d-flex align-items-center">
                                @if($invitation->event && $invitation->event->organizer)
                                    @if($invitation->event->organizer->profile_photo)
                                        <img src="{{ $invitation->event->organizer->profile_photo_url }}"
                                             alt="{{ $invitation->event->organizer->name }}"
                                             class="rounded-circle me-2"
                                             style="width: 32px; height: 32px; object-fit: cover;">
                                    @else
                                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2"
                                             style="width: 32px; height: 32px; font-size: 14px; font-weight: bold;">
                                            {{ substr($invitation->event->organizer->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <span class="f-w-600">{{ $invitation->event->organizer->name }}</span>
                                @else
                                    <span class="f-w-600 text-muted">Organizzatore non disponibile</span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1">Data e Ora</h6>
                            <p class="mb-0 f-w-600">
                                <i class="ph-duotone ph-calendar me-1"></i>
                                {{ $invitation->event->start_date ? $invitation->event->start_date->format('d/m/Y H:i') : 'Non specificata' }}
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1">Luogo</h6>
                            <p class="mb-0 f-w-600">
                                <i class="ph-duotone ph-map-pin me-1"></i>
                                {{ $invitation->event->location ?? 'Non specificato' }}
                            </p>
                        </div>
                        <div class="col-12 mb-3">
                            <h6 class="text-muted mb-1">Descrizione</h6>
                            <p class="mb-0">{{ $invitation->event ? ($invitation->event->description ?? 'Nessuna descrizione disponibile') : 'Evento non trovato' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Invitation Status -->
            <div class="card hover-effect mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph-duotone ph-envelope me-2"></i>
                        Stato Invito
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1">Stato</h6>
                            @if($invitation->status === 'pending')
                                <span class="badge bg-warning">In Attesa</span>
                            @elseif($invitation->status === 'accepted')
                                <span class="badge bg-success">Accettato</span>
                            @elseif($invitation->status === 'declined')
                                <span class="badge bg-danger">Rifiutato</span>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1">Data Invito</h6>
                            <p class="mb-0 f-w-600">{{ $invitation->created_at ? $invitation->created_at->format('d/m/Y H:i') : 'Non disponibile' }}</p>
                        </div>
                        @if($invitation->expires_at)
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1">Scade il</h6>
                            <p class="mb-0 f-w-600 {{ $invitation->expires_at->isPast() ? 'text-danger' : '' }}">
                                {{ $invitation->expires_at->format('d/m/Y H:i') }}
                                @if($invitation->expires_at->isPast())
                                    <span class="badge bg-danger ms-2">Scaduto</span>
                                @endif
                            </p>
                        </div>
                        @endif
                        @if($invitation->responded_at)
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted mb-1">Risposta il</h6>
                            <p class="mb-0 f-w-600">{{ $invitation->responded_at ? $invitation->responded_at->format('d/m/Y H:i') : 'Non disponibile' }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Action Buttons -->
            <div class="card hover-effect mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph-duotone ph-gear me-2"></i>
                        Azioni
                    </h5>
                </div>
                <div class="card-body">
                    @if($invitation->status === 'pending' && (!$invitation->expires_at || !$invitation->expires_at->isPast()))
                        <div class="d-grid gap-2">
                            <a href="{{ route('invitations.accept', $invitation) }}"
                               class="btn btn-success hover-effect">
                                <i class="ph-duotone ph-check-circle me-2"></i>
                                Accetta Invito
                            </a>
                            <a href="{{ route('invitations.decline', $invitation) }}"
                               class="btn btn-danger hover-effect">
                                <i class="ph-duotone ph-x-circle me-2"></i>
                                Rifiuta Invito
                            </a>
                        </div>
                    @else
                        <div class="text-center">
                            @if($invitation->status === 'accepted')
                                <div class="icon-box bg-success mx-auto mb-3">
                                    <i class="ph-duotone ph-check-circle text-white"></i>
                                </div>
                                <h6 class="text-success">Invito Accettato</h6>
                                <p class="text-muted">Hai accettato di partecipare a questo evento.</p>
                            @elseif($invitation->status === 'declined')
                                <div class="icon-box bg-danger mx-auto mb-3">
                                    <i class="ph-duotone ph-x-circle text-white"></i>
                                </div>
                                <h6 class="text-danger">Invito Rifiutato</h6>
                                <p class="text-muted">Hai rifiutato di partecipare a questo evento.</p>
                            @elseif($invitation->expires_at && $invitation->expires_at->isPast())
                                <div class="icon-box bg-warning mx-auto mb-3">
                                    <i class="ph-duotone ph-clock text-white"></i>
                                </div>
                                <h6 class="text-warning">Invito Scaduto</h6>
                                <p class="text-muted">Questo invito è scaduto e non può più essere accettato.</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card hover-effect">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph-duotone ph-arrow-right me-2"></i>
                        Azioni Rapide
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                                                @if($invitation->event)
                            <a href="{{ route('events.show', $invitation->event) }}"
                               class="btn btn-outline-primary hover-effect">
                                <i class="ph-duotone ph-eye me-2"></i>
                                Visualizza Evento
                            </a>
                        @else
                            <button class="btn btn-outline-secondary hover-effect" disabled>
                                <i class="ph-duotone ph-eye me-2"></i>
                                Evento non disponibile
                            </button>
                        @endif
                        <a href="{{ route('invitations.index') }}"
                           class="btn btn-outline-secondary hover-effect">
                            <i class="ph-duotone ph-arrow-left me-2"></i>
                            Torna agli Inviti
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
