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
                    <div class="position-absolute w-100 h-100" style="background: linear-gradient(135deg, #0f626a 0%, #0c4e55 100%);"></div>
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
                        <span class="fs-5">{{ __('events.organized_by') }} {{ $event->organizer->name }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">

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
                    <span class="badge bg-light-primary">
                        {{ $event->invitations->where('status', 'accepted')->count() + $event->requests->where('status', 'accepted')->count() }}
                        @if($event->max_participants)
                            / {{ $event->max_participants }}
                        @endif
                    </span>
                </div>
                <div class="card-body">
                    @php
                        $acceptedInvitations = $event->invitations->where('status', 'accepted');
                        $acceptedRequests = $event->requests->where('status', 'accepted');
                    @endphp

                    @if($acceptedInvitations->count() + $acceptedRequests->count() > 0)
                        <div class="row">
                            <!-- Invited Participants -->
                            @foreach($acceptedInvitations as $invitation)
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center">
                                                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: bold;">
                                    {{ substr($invitation->invitedUser->name, 0, 2) }}
                                </div>
                                        <div>
                                            <h6 class="mb-0">{{ $invitation->invitedUser->name }}</h6>
                                            <small class="text-muted">{{ ucfirst($invitation->role) }} (Invitato)</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Requested Participants -->
                            @foreach($acceptedRequests as $request)
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center">
                                                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: bold;">
                                    {{ substr($request->user->name, 0, 2) }}
                                </div>
                                        <div>
                                            <h6 class="mb-0">{{ $request->user->name }}</h6>
                                            <small class="text-muted">{{ ucfirst($request->requested_role) }} (Applicato)</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="ph ph-users-three display-4 text-muted mb-3"></i>
                            <p class="text-muted">{{ __('events.no_participants') }}</p>
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
                                    <a href="{{ route('invitations.show', $userInvitation) }}" class="btn btn-light-primary w-100 mb-2">
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
                            <a href="{{ route('poetry.test.real-login') }}" class="btn btn-light-primary w-100">
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
                        <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; font-weight: bold;">
                            {{ substr($event->organizer->name, 0, 2) }}
                        </div>
                        <div>
                            <h6 class="mb-0 text-white">{{ $event->organizer->name }}</h6>
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
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ph ph-hand-waving me-2"></i>Richiesta Partecipazione - {{ $event->title }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('events.apply', $event) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Ruolo Richiesto *</label>
                        <select name="requested_role" class="form-select" required>
                            <option value="">Seleziona ruolo...</option>
                            @if(auth()->user()->hasRole('poet'))
                                <option value="performer">Performer</option>
                            @endif
                            @if(auth()->user()->hasRole('judge'))
                                <option value="judge">Judge</option>
                            @endif
                            @if(auth()->user()->hasRole('technician'))
                                <option value="technician">Technician</option>
                            @endif
                            <option value="host">Host</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Messaggio di Presentazione *</label>
                        <textarea name="message" class="form-control" rows="4"
                                  placeholder="Presentati e spiega perché vuoi partecipare a questo evento..." required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Esperienza (Opzionale)</label>
                        <textarea name="experience" class="form-control" rows="3"
                                  placeholder="Descrivi la tua esperienza nel poetry slam..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Link Portfolio (Opzionale)</label>
                        <input type="url" name="portfolio_links[]" class="form-control"
                               placeholder="https://youtube.com/watch?v=...">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Annulla</button>
                    <button type="submit" class="btn btn-light-success">
                        <i class="ph ph-paper-plane me-2"></i>Invia Richiesta
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
