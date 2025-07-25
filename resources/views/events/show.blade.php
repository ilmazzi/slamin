@extends('layout.master')

@section('title', $event->title)
@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/leafletmaps/leaflet.css') }}">
@endsection

@section('breadcrumb-title')
<h3>{{ $event->title }}</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item"><a href="{{ route('events.index') }}">Eventi</a></li>
<li class="breadcrumb-item active">{{ $event->title }}</li>
@endsection

@section('main-content')
<div class="container-fluid">

    <!-- Event Hero Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="position-relative overflow-hidden rounded-3" style="height: 400px;">
                @if($event->image_url)
                    <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="position-absolute w-100 h-100" style="object-fit: cover;">
                    <div class="position-absolute w-100 h-100" style="background: linear-gradient(135deg, rgba(15, 98, 106, 0.7) 0%, rgba(12, 78, 85, 0.7) 100%);"></div>
                @else
                    <div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #0f626a 0%, #0c4e55 100%);">
                        <div class="text-center text-white">
                            <i class="ph ph-calendar f-s-72 mb-3"></i>
                            <div class="f-s-24 f-w-600">{{ $event->title }}</div>
                        </div>
                    </div>
                @endif
                @if($event->is_public)
                    <span class="badge bg-success position-absolute top-0 end-0 m-4 fs-6">
                        <i class="ph ph-globe me-1"></i> {{ __('events.event_public_badge') }}
                    </span>
                @else
                    <span class="badge bg-warning position-absolute top-0 end-0 m-4 fs-6">
                        <i class="ph ph-lock me-1"></i> {{ __('events.event_private_badge') }}
                    </span>
                @endif
                
                <!-- Category Badge -->
                @if($event->category)
                    <span class="badge {{ $event->category_color_class }} position-absolute top-0 start-0 m-4 fs-6">
                        <i class="ph ph-tag me-1"></i> {{ __('events.category_' . $event->category) }}
                    </span>
                @endif

                <div class="position-absolute bottom-0 start-0 text-white p-4 w-100" style="z-index: 2;">
                    <h1 class="display-4 fw-bold mb-3 text-white">{{ $event->title }}</h1>
                    <div class="d-flex align-items-center mb-2">
                        <i class="ph ph-calendar-check me-2 fs-5"></i>
                        <span class="fs-5">{{ $event->start_datetime->format('d F Y, H:i') }}</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <i class="ph ph-map-pin me-2 fs-5"></i>
                        <span class="fs-5">{{ $event->venue_name }}, {{ $event->city }}</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="ph ph-user me-2 fs-5"></i>
                        <span class="fs-5">{{ __('events.organized_by') }} {{ $event->organizer->getDisplayName() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">

            <!-- Private Event Notice -->
            @if(!$event->is_public)
                <div class="alert alert-info mb-4">
                    <div class="d-flex align-items-center">
                        <i class="ph ph-info-circle me-3 fs-4"></i>
                        <div>
                            <h6 class="mb-1">{{ __('events.private_event_notice_title') }}</h6>
                            <p class="mb-0">{{ __('events.private_event_notice_text') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Event Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph ph-info me-2"></i>{{ __('events.event_information') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($event->category)
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="ph ph-tag me-2 text-muted"></i>
                                <div>
                                    <small class="text-muted d-block">{{ __('events.category') }}</small>
                                    <span class="badge {{ $event->category_color_class }} fs-6">{{ __('events.category_' . $event->category) }}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        @if($event->entry_fee > 0)
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="ph ph-currency-eur me-2 text-muted"></i>
                                <div>
                                    <small class="text-muted d-block">{{ __('events.entry_fee') }}</small>
                                    <span class="fw-semibold">{{ number_format($event->entry_fee, 2) }}€</span>
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="ph ph-currency-eur me-2 text-muted"></i>
                                <div>
                                    <small class="text-muted d-block">{{ __('events.entry_fee') }}</small>
                                    <span class="badge bg-success">Gratuito</span>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Event Description -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph ph-file-text me-2"></i>{{ __('events.description_event') }}
                    </h5>
                </div>
                <div class="card-body">
                    <p class="fs-6 lh-lg">{{ $event->description }}</p>

                    @if($event->tags)
                        <div class="mt-4">
                            <h6 class="mb-2">Tags:</h6>
                            @foreach($event->tags as $tag)
                                <span class="badge bg-light text-dark me-2 mb-2">#{{ $tag }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Requirements -->
            @if($event->requirements)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph ph-list-checks me-2"></i>{{ __('events.requirements') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <p class="mb-0">{{ $event->requirements }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Event Timeline -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph ph-clock me-2"></i>{{ __('events.timeline_event') }}
                    </h5>
                </div>
                <div class="card-body">
                    @if($event->registration_deadline)
                    <div class="border-start border-4 ps-3 mb-3" style="border-color: rgb(15, 98, 106) !important;">
                        <h6 class="mb-1" style="color: rgb(15, 98, 106);">{{ __('events.deadline_registration') }}</h6>
                        <p class="text-muted mb-0">{{ $event->registration_deadline->format('d F Y, H:i') }}</p>
                    </div>
                    @endif

                    <div class="border-start border-4 ps-3 mb-3" style="border-color: rgb(15, 98, 106) !important;">
                        <h6 class="mb-1" style="color: rgb(15, 98, 106);">Inizio Evento</h6>
                        <p class="text-muted mb-0">{{ $event->start_datetime->format('d F Y, H:i') }}</p>
                    </div>

                    <div class="border-start border-4 ps-3 mb-3" style="border-color: rgb(15, 98, 106) !important;">
                        <h6 class="mb-1" style="color: rgb(15, 98, 106);">Fine Evento</h6>
                        <p class="text-muted mb-0">{{ $event->end_datetime->format('d F Y, H:i') }}</p>
                        <small class="text-muted">{{ __('events.duration') }}: {{ $event->duration }} {{ __('events.duration_hours') }}</small>
                    </div>
                </div>
            </div>

            <!-- Participants -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ph ph-users me-2"></i>{{ __('events.participants') }}
                    </h5>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-light-primary">
                            {{ $event->invitations->where('status', 'accepted')->count() + $event->requests->where('status', 'accepted')->count() }}
                            @if($event->max_participants)
                                / {{ $event->max_participants }}
                            @endif
                        </span>
                        @auth
                            @if($event->organizer_id === auth()->id())
                                <a href="{{ route('events.manage', $event) }}" class="btn btn-sm btn-light-primary">
                                    <i class="ph ph-gear me-1"></i>Gestisci
                                </a>
                            @endif
                        @endauth
                    </div>
                </div>
                <div class="card-body">
                    @php
                        $acceptedInvitations = $event->invitations->where('status', 'accepted');
                        $acceptedRequests = $event->requests->where('status', 'accepted');
                        $pendingInvitations = $event->invitations->where('status', 'pending');
                        $pendingRequests = $event->requests->where('status', 'pending');
                    @endphp

                    <!-- Confirmed Participants -->
                    @if($acceptedInvitations->count() + $acceptedRequests->count() > 0)
                        <div class="mb-4">
                            <h6 class="mb-3 text-success">
                                <i class="ph ph-check-circle me-2"></i>{{ __('events.confirmed_participants') }}
                            </h6>
                            <div class="row">
                                <!-- Invited Participants -->
                                @foreach($acceptedInvitations as $invitation)
                                    <div class="col-md-6 mb-3">
                                        <div class="card card-light-success border-0">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-3 overflow-hidden" style="width: 45px; height: 45px; font-weight: bold; font-size: 16px;">
                                                        @if($invitation->invitedUser->profile_photo)
                                                            <img src="{{ $invitation->invitedUser->profile_photo_url }}" alt="{{ $invitation->invitedUser->getDisplayName() }}" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                                                        @else
                                                            {{ substr($invitation->invitedUser->getDisplayName(), 0, 2) }}
                                                        @endif
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1 fw-bold">{{ $invitation->invitedUser->getDisplayName() }}</h6>
                                                        <div class="d-flex align-items-center gap-2 mb-1">
                                                            <span class="badge bg-success">{{ ucfirst($invitation->role) }}</span>
                                                            <span class="badge bg-light-secondary">{{ __('events.participant_invited') }}</span>
                                                        </div>
                                                        @if($invitation->compensation)
                                                            <small class="text-muted">
                                                                <i class="ph ph-currency-eur me-1"></i>{{ $invitation->compensation }}
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Requested Participants -->
                                @foreach($acceptedRequests as $request)
                                    <div class="col-md-6 mb-3">
                                        <div class="card card-light-success border-0">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-3 overflow-hidden" style="width: 45px; height: 45px; font-weight: bold; font-size: 16px;">
                                                        @if($request->user->profile_photo)
                                                            <img src="{{ $request->user->profile_photo_url }}" alt="{{ $request->user->getDisplayName() }}" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                                                        @else
                                                            {{ substr($request->user->getDisplayName(), 0, 2) }}
                                                        @endif
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1 fw-bold">{{ $request->user->getDisplayName() }}</h6>
                                                        <div class="d-flex align-items-center gap-2 mb-1">
                                                            <span class="badge bg-success">{{ ucfirst($request->requested_role) }}</span>
                                                            <span class="badge bg-light-warning">{{ __('events.participant_applied') }}</span>
                                                        </div>
                                                        @if($request->experience)
                                                            <small class="text-muted">
                                                                <i class="ph ph-star me-1"></i>{{ Str::limit($request->experience, 50) }}
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Pending Participants -->
                    @if($pendingInvitations->count() + $pendingRequests->count() > 0)
                        <div class="mb-4">
                            <h6 class="mb-3 text-warning">
                                <i class="ph ph-clock me-2"></i>{{ __('events.pending_participants') }}
                            </h6>
                            <div class="row">
                                <!-- Pending Invitations -->
                                @foreach($pendingInvitations as $invitation)
                                    <div class="col-md-6 mb-3">
                                        <div class="card card-light-warning border-0">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-warning text-white d-flex align-items-center justify-content-center me-3 overflow-hidden" style="width: 45px; height: 45px; font-weight: bold; font-size: 16px;">
                                                        @if($invitation->invitedUser->profile_photo)
                                                            <img src="{{ $invitation->invitedUser->profile_photo_url }}" alt="{{ $invitation->invitedUser->getDisplayName() }}" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                                                        @else
                                                            {{ substr($invitation->invitedUser->getDisplayName(), 0, 2) }}
                                                        @endif
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1 fw-bold">{{ $invitation->invitedUser->getDisplayName() }}</h6>
                                                        <div class="d-flex align-items-center gap-2 mb-1">
                                                            <span class="badge bg-warning">{{ ucfirst($invitation->role) }}</span>
                                                            <span class="badge bg-light-secondary">{{ __('events.participant_invited') }}</span>
                                                        </div>
                                                        @if($invitation->expires_at)
                                                            <small class="text-muted">
                                                                <i class="ph ph-timer me-1"></i>Scade {{ $invitation->expires_at->diffForHumans() }}
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <!-- Pending Requests -->
                                @foreach($pendingRequests as $request)
                                    <div class="col-md-6 mb-3">
                                        <div class="card card-light-warning border-0">
                                            <div class="card-body p-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="rounded-circle bg-warning text-white d-flex align-items-center justify-content-center me-3 overflow-hidden" style="width: 45px; height: 45px; font-weight: bold; font-size: 16px;">
                                                        @if($request->user->profile_photo)
                                                            <img src="{{ $request->user->profile_photo_url }}" alt="{{ $request->user->getDisplayName() }}" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                                                        @else
                                                            {{ substr($request->user->getDisplayName(), 0, 2) }}
                                                        @endif
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1 fw-bold">{{ $request->user->getDisplayName() }}</h6>
                                                        <div class="d-flex align-items-center gap-2 mb-1">
                                                            <span class="badge bg-warning">{{ ucfirst($request->requested_role) }}</span>
                                                            <span class="badge bg-light-warning">{{ __('events.participant_applied') }}</span>
                                                        </div>
                                                        @if($request->message)
                                                            <small class="text-muted">
                                                                <i class="ph ph-chat-circle me-1"></i>{{ Str::limit($request->message, 50) }}
                                                            </small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- No Participants -->
                    @if($acceptedInvitations->count() + $acceptedRequests->count() + $pendingInvitations->count() + $pendingRequests->count() === 0)
                        <div class="text-center py-4">
                            <i class="ph ph-users-three display-4 text-muted mb-3"></i>
                            <p class="text-muted mb-3">{{ __('events.no_participants') }}</p>
                            @auth
                                @if($canApply)
                                    <button class="btn btn-light-success" data-bs-toggle="modal" data-bs-target="#applyModal">
                                        <i class="ph ph-hand-waving me-2"></i>{{ __('events.first_participant') }}
                                    </button>
                                @endif
                            @endauth
                        </div>
                    @endif

                    <!-- Role Statistics -->
                    @if($event->invitations->count() + $event->requests->count() > 0)
                        <div class="mt-4 pt-3 border-top">
                            <h6 class="mb-3">{{ __('events.participant_stats') }}</h6>
                            <div class="row g-2">
                                @php
                                    $roleStats = collect();
                                    foreach($event->invitations as $inv) {
                                        $roleStats->put($inv->role, $roleStats->get($inv->role, 0) + 1);
                                    }
                                    foreach($event->requests as $req) {
                                        $roleStats->put($req->requested_role, $roleStats->get($req->requested_role, 0) + 1);
                                    }
                                @endphp
                                @foreach($roleStats as $role => $count)
                                    <div class="col-auto">
                                        <span class="badge bg-light-primary">{{ ucfirst($role) }}: {{ $count }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Location Map -->
            @if($event->latitude && $event->longitude)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph ph-map-pin me-2"></i>{{ __('events.location') }}
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div id="eventMap" style="height: 200px; border-radius: 10px; overflow: hidden;"></div>
                </div>
                <div class="card-footer">
                    <p class="mb-0">
                        <strong>{{ $event->venue_name }}</strong><br>
                        {{ $event->venue_address }}<br>
                        {{ $event->city }}, {{ $event->country }}
                    </p>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">

            <!-- Action Buttons -->
            <div class="position-sticky" style="top: 20px;">
                <div class="card mb-4">
                    <div class="card-body">
                        @auth
                            @if($event->organizer_id === auth()->id())
                                <!-- Organizer Actions -->
                                <a href="{{ route('events.manage', $event) }}" class="btn btn-light-primary w-100 mb-2">
                                    <i class="ph ph-gear me-2"></i>{{ __('events.manage_event_action') }}
                                </a>
                                <a href="{{ route('events.edit', $event) }}" class="btn btn-light-secondary w-100 mb-2">
                                    <i class="ph ph-pencil me-2"></i>{{ __('events.edit_event_action') }}
                                </a>
                                <button class="btn btn-light-danger w-100" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                    <i class="ph ph-trash me-2"></i>{{ __('events.delete_event_action') }}
                                </button>

                            @elseif($hasInvitation)
                                <!-- User has invitation -->
                                @if($userInvitation->status === 'pending')
                                    <div class="alert alert-info mb-3">
                                        <i class="ph ph-envelope me-2"></i>Hai ricevuto un invito per questo evento!
                                    </div>
                                    <a href="{{ route('invitations.index') }}" class="btn btn-light-primary w-100 mb-2">
                                        <i class="ph ph-envelope-open me-2"></i>Gestisci Invito
                                    </a>
                                @elseif($userInvitation->status === 'accepted')
                                    <div class="alert alert-success mb-3">
                                        <i class="ph ph-check-circle me-2"></i>Hai accettato l'invito! Sei un partecipante confermato.
                                    </div>
                                @elseif($userInvitation->status === 'declined')
                                    <div class="alert alert-secondary mb-3">
                                        <i class="ph ph-x-circle me-2"></i>Hai rifiutato l'invito per questo evento.
                                    </div>
                                @endif

                            @elseif($hasRequest)
                                <!-- User has request -->
                                @if($userRequest->status === 'pending')
                                    <div class="alert alert-warning mb-3">
                                        <i class="ph ph-clock me-2"></i>La tua richiesta è in attesa di approvazione.
                                    </div>
                                    <form action="{{ route('requests.cancel', $userRequest) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-light-danger w-100">
                                            <i class="ph ph-x me-2"></i>Cancella Richiesta
                                        </button>
                                    </form>
                                @elseif($userRequest->status === 'accepted')
                                    <div class="alert alert-success mb-3">
                                        <i class="ph ph-party-popper me-2"></i>La tua richiesta è stata accettata! Sei un partecipante confermato.
                                    </div>
                                @elseif($userRequest->status === 'declined')
                                    <div class="alert alert-danger mb-3">
                                        <i class="ph ph-x-circle me-2"></i>La tua richiesta è stata rifiutata.
                                    </div>
                                @endif

                            @elseif($canApply)
                                <!-- User can apply -->
                                <button class="btn btn-light-success w-100 mb-2" data-bs-toggle="modal" data-bs-target="#applyModal">
                                    <i class="ph ph-hand-waving me-2"></i>Richiedi Partecipazione
                                </button>
                                <small class="text-muted">Questo evento accetta richieste di partecipazione</small>

                            @else
                                <!-- Cannot apply -->
                                <div class="alert alert-secondary mb-3">
                                    <i class="ph ph-lock me-2"></i>Non puoi richiedere di partecipare a questo evento.
                                </div>
                            @endif

                            <!-- Always show share button -->
                            <button class="btn btn-light-primary w-100 mt-2" onclick="shareEvent()">
                                <i class="ph ph-share me-2"></i>Condividi Evento
                            </button>

                        @else
                            <!-- Not logged in -->
                            <div class="alert alert-info mb-3">
                                <i class="ph ph-sign-in me-2"></i>Accedi per partecipare a questo evento
                            </div>
                            <a href="{{ route('login') }}" class="btn btn-light-primary w-100">
                                <i class="ph ph-sign-in me-2"></i>Accedi
                            </a>
                        @endauth
                    </div>
                </div>
            </div>

            <!-- Event Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph ph-info me-2"></i>{{ __('events.event_info') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="border-start border-4 ps-3 mb-3" style="border-color: rgb(15, 98, 106) !important;">
                        <h6 class="mb-1" style="color: rgb(15, 98, 106);">{{ __('events.date_time') }}</h6>
                        <p class="mb-0">{{ $event->start_datetime->format('d F Y') }}</p>
                        <small class="text-muted">{{ $event->start_datetime->format('H:i') }} - {{ $event->end_datetime->format('H:i') }}</small>
                    </div>

                    <div class="border-start border-4 ps-3 mb-3" style="border-color: rgb(15, 98, 106) !important;">
                        <h6 class="mb-1" style="color: rgb(15, 98, 106);">{{ __('events.duration') }}</h6>
                        <p class="mb-0">{{ $event->duration }} {{ __('events.duration_hours') }}</p>
                    </div>

                    @if($event->entry_fee > 0)
                    <div class="border-start border-4 ps-3 mb-3" style="border-color: rgb(15, 98, 106) !important;">
                        <h6 class="mb-1" style="color: rgb(15, 98, 106);">{{ __('events.cost') }}</h6>
                        <p class="mb-0">€{{ number_format($event->entry_fee, 2) }}</p>
                    </div>
                    @else
                    <div class="border-start border-4 ps-3 mb-3" style="border-color: rgb(40, 167, 69) !important;">
                        <h6 class="mb-1" style="color: rgb(40, 167, 69);">{{ __('events.free') }}</h6>
                        <p class="mb-0">{{ __('events.no_fee') }}</p>
                    </div>
                    @endif

                    @if($event->max_participants)
                    <div class="border-start border-4 ps-3 mb-3" style="border-color: rgb(15, 98, 106) !important;">
                        <h6 class="mb-1" style="color: rgb(15, 98, 106);">Partecipanti</h6>
                        <p class="mb-0">
                            {{ $event->invitations->where('status', 'accepted')->count() + $event->requests->where('status', 'accepted')->count() }} / {{ $event->max_participants }}
                        </p>
                        <div class="progress mt-2" style="height: 6px;">
                            <div class="progress-bar bg-light-primary" style="width: {{ (($event->invitations->where('status', 'accepted')->count() + $event->requests->where('status', 'accepted')->count()) / $event->max_participants) * 100 }}%"></div>
                        </div>
                    </div>
                    @endif

                    @if($event->registration_deadline)
                    <div class="border-start border-4 ps-3 mb-3" style="border-color: rgb(15, 98, 106) !important;">
                        <h6 class="mb-1" style="color: rgb(15, 98, 106);">{{ __('events.deadline_registration') }}</h6>
                        <p class="mb-0">{{ $event->registration_deadline->format('d F Y, H:i') }}</p>
                        @if($event->registration_deadline > now())
                            <small style="color: rgb(40, 167, 69);">{{ $event->registration_deadline->diffForHumans() }}</small>
                        @else
                            <small style="color: rgb(220, 53, 69);">{{ __('events.expired') }}</small>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            <!-- Organizer Info -->
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center me-3 overflow-hidden" style="width: 40px; height: 40px; font-weight: bold;">
                            @if($event->organizer->profile_photo)
                                <img src="{{ $event->organizer->profile_photo_url }}" alt="{{ $event->organizer->getDisplayName() }}" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                {{ substr($event->organizer->getDisplayName(), 0, 2) }}
                            @endif
                        </div>
                        <div>
                            <h6 class="mb-0 text-white">{{ $event->organizer->getDisplayName() }}</h6>
                            <small class="text-white-50">Organizzatore</small>
                        </div>
                    </div>
                    @if($event->organizer->bio)
                        <p class="small text-white-75">{{ Str::limit($event->organizer->bio, 100) }}</p>
                    @endif

                    @auth
                        @if($event->organizer_id !== auth()->id())
                            <button class="btn btn-light btn-sm w-100">
                                <i class="ph ph-chat-circle me-2"></i>Contatta Organizzatore
                            </button>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Event Stats -->
            <div class="row g-3">
                <div class="col-6">
                    <div class="card">
                        <div class="card-body text-center bg-light-primary">
                            <div class="fs-5 fw-bold">{{ $event->invitations->count() }}</div>
                            <small>{{ __('events.invitations_sent') }}</small>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card">
                        <div class="card-body text-center bg-light-success">
                            <div class="fs-5 fw-bold">{{ $event->requests->count() }}</div>
                            <small>{{ __('events.requests_received') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Apply Modal -->
@auth
@if($canApply)
<div class="modal fade" id="applyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light-success">
                <h5 class="modal-title text-success">
                    <i class="ph ph-hand-waving me-2"></i>{{ __('events.participant_apply_title') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('events.apply', $event) }}" method="POST" id="applyForm">
                @csrf
                <div class="modal-body">
                    <!-- Event Info -->
                    <div class="alert alert-info mb-4">
                        <div class="d-flex align-items-center">
                            <i class="ph ph-info-circle me-3 fs-4"></i>
                            <div>
                                <h6 class="mb-1">{{ $event->title }}</h6>
                                <p class="mb-0 small">
                                    <i class="ph ph-calendar me-1"></i>{{ $event->start_datetime->format('d F Y, H:i') }}<br>
                                    <i class="ph ph-map-pin me-1"></i>{{ $event->venue_name }}, {{ $event->city }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Role Selection -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="ph ph-user-circle me-2"></i>{{ __('events.participant_apply_role') }} *
                        </label>
                        <select name="requested_role" class="form-select form-select-lg" required>
                            <option value="">{{ __('events.participant_apply_role_help') }}</option>
                            @if(auth()->user()->hasRole('poet'))
                                <option value="performer" data-description="Interpreterai le tue poesie o quelle di altri artisti">
                                    🎭 Performer
                                </option>
                            @endif
                            @if(auth()->user()->hasRole('judge'))
                                <option value="judge" data-description="Valuterai le performance degli artisti">
                                    ⚖️ Judge
                                </option>
                            @endif
                            @if(auth()->user()->hasRole('technician'))
                                <option value="technician" data-description="Gestirai audio, luci e supporto tecnico">
                                    🔧 Technician
                                </option>
                            @endif
                            <option value="host" data-description="Presenterai l'evento e gestirai il pubblico">
                                🎤 Host
                            </option>
                        </select>
                        <div id="roleDescription" class="form-text mt-2"></div>
                    </div>

                    <!-- Personal Message -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="ph ph-chat-circle-text me-2"></i>{{ __('events.participant_apply_message') }} *
                        </label>
                        <textarea name="message" class="form-control" rows="4"
                                  placeholder="{{ __('events.participant_apply_message_help') }}" required></textarea>
                        <div class="form-text">
                            <i class="ph ph-lightbulb me-1"></i>{{ __('events.participant_apply_message_suggestion') }}
                        </div>
                    </div>

                    <!-- Experience -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="ph ph-star me-2"></i>{{ __('events.participant_apply_experience') }}
                        </label>
                        <textarea name="experience" class="form-control" rows="3"
                                  placeholder="{{ __('events.participant_apply_experience_help') }}"></textarea>
                        <div class="form-text">
                            <i class="ph ph-info me-1"></i>{{ __('events.participant_apply_experience_optional') }}
                        </div>
                    </div>

                    <!-- Portfolio Links -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="ph ph-link me-2"></i>{{ __('events.participant_links') }} (Opzionale)
                        </label>
                        <div id="portfolioLinks">
                            <div class="input-group mb-2">
                                <span class="input-group-text">
                                    <i class="ph ph-link"></i>
                                </span>
                                <input type="url" name="portfolio_links[]" class="form-control"
                                       placeholder="{{ __('events.participant_links_placeholder') }}">
                                <button type="button" class="btn btn-outline-secondary" onclick="addPortfolioLink()">
                                    <i class="ph ph-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="form-text">
                            <i class="ph ph-video-camera me-1"></i>{{ __('events.participant_links_help') }}
                        </div>
                    </div>

                    <!-- Terms -->
                    <div class="alert alert-warning">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="termsAccepted" required>
                            <label class="form-check-label" for="termsAccepted">
                                                                <small>
                                    {{ __('events.participant_terms_accept') }}
                                </small>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">
                        <i class="ph ph-x me-2"></i>{{ __('events.participant_apply_cancel') }}
                    </button>
                    <button type="submit" class="btn btn-light-success" id="submitBtn">
                        <i class="ph ph-paper-plane me-2"></i>{{ __('events.participant_apply_send') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endauth

<!-- Delete Modal -->
@auth
@if($event->organizer_id === auth()->id())
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    <i class="ph ph-warning me-2"></i>Elimina Evento
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Sei sicuro di voler eliminare questo evento?</p>
                <p class="text-muted">Questa azione non può essere annullata. Tutti i partecipanti riceveranno una notifica di cancellazione.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Annulla</button>
                <form action="{{ route('events.destroy', $event) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-light-danger">
                        <i class="ph ph-trash me-2"></i>Elimina Definitivamente
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endauth
@endsection

@section('script')
@if($event->latitude && $event->longitude)
<script src="{{ asset('assets/vendor/leafletmaps/leaflet.js') }}"></script>
<script>
// Clear event draft from localStorage if coming from successful creation
@if(session('success') && strpos(session('success'), 'creato') !== false)
    localStorage.removeItem('eventDraft');
    console.log('Event creation draft cleared from localStorage');
@endif
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map
    const map = L.map('eventMap').setView([{{ $event->latitude }}, {{ $event->longitude }}], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Add marker for the event
    L.marker([{{ $event->latitude }}, {{ $event->longitude }}])
        .addTo(map)
        .bindPopup(`
            <div class="p-2">
                <h6>{{ $event->venue_name }}</h6>
                <p class="mb-0">{{ $event->venue_address }}</p>
            </div>
        `)
        .openPopup();
});
</script>
@endif

<script>
// Role description functionality
document.addEventListener('DOMContentLoaded', function() {
    const roleSelect = document.querySelector('select[name="requested_role"]');
    const roleDescription = document.getElementById('roleDescription');

    if (roleSelect && roleDescription) {
        roleSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const description = selectedOption.getAttribute('data-description');

            if (description) {
                roleDescription.innerHTML = `<i class="ph ph-info-circle me-1"></i>${description}`;
                roleDescription.style.display = 'block';
            } else {
                roleDescription.style.display = 'none';
            }
        });
    }

    // Form validation
    const applyForm = document.getElementById('applyForm');
    const submitBtn = document.getElementById('submitBtn');

    if (applyForm && submitBtn) {
        applyForm.addEventListener('submit', function(e) {
            const message = document.querySelector('textarea[name="message"]').value.trim();
            const role = document.querySelector('select[name="requested_role"]').value;
            const terms = document.getElementById('termsAccepted').checked;

                        if (!role) {
                e.preventDefault();
                showNotification('{{ __("events.participant_apply_validation_role") }}', 'error');
                return;
            }

            if (message.length < 10) {
                e.preventDefault();
                showNotification('{{ __("events.participant_apply_validation_message") }}', 'error');
                return;
            }

            if (!terms) {
                e.preventDefault();
                showNotification('{{ __("events.participant_apply_validation_terms") }}', 'error');
                return;
            }

            // Disable submit button to prevent double submission
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="ph ph-spinner ph-spin me-2"></i>{{ __("events.participant_apply_sending") }}';
        });
    }
});

// Add portfolio link functionality
function addPortfolioLink() {
    const portfolioLinks = document.getElementById('portfolioLinks');
    const newLink = document.createElement('div');
    newLink.className = 'input-group mb-2';
    newLink.innerHTML = `
        <span class="input-group-text">
            <i class="ph ph-link"></i>
        </span>
                                        <input type="url" name="portfolio_links[]" class="form-control"
                                       placeholder="{{ __('events.participant_links_placeholder') }}">
        <button type="button" class="btn btn-outline-danger" onclick="removePortfolioLink(this)">
            <i class="ph ph-minus"></i>
        </button>
    `;
    portfolioLinks.appendChild(newLink);
}

function removePortfolioLink(button) {
    button.closest('.input-group').remove();
}

function shareEvent() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $event->title }}',
            text: '{{ Str::limit($event->description, 100) }}',
            url: window.location.href
        });
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(window.location.href);
        showNotification('Link copiato negli appunti!', 'success');
    }
}

function showNotification(message, type) {
    const alert = document.createElement('div');
    alert.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
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
