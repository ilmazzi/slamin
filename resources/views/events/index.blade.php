@extends('layout.master')

@section('title', __('events.events_poetry_slam'))
@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/leafletmaps/leaflet.css') }}">
@endsection

@section('breadcrumb-title')
<h3>{{ __('events.events_poetry_slam') }}</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item">Dashboard</li>
<li class="breadcrumb-item active">{{ __('events.events') }}</li>
@endsection

@section('main-content')
<div class="container-fluid">

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 col-6 mb-3">
            <div class="card">
                <div class="card-body text-center py-4">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <div class="rounded-circle bg-light-primary d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="ph ph-calendar" style="font-size: 24px;"></i>
                        </div>
                    </div>
                    <h4 class="mb-1">{{ $events->total() }}</h4>
                    <p class="text-muted mb-0">{{ __('events.total_events') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card">
                <div class="card-body text-center py-4">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <div class="rounded-circle bg-light-info d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="ph ph-globe" style="font-size: 24px;"></i>
                        </div>
                    </div>
                    <h4 class="mb-1">{{ $events->where('is_public', true)->count() }}</h4>
                    <p class="text-muted mb-0">{{ __('events.public_events_count') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card">
                <div class="card-body text-center py-4">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <div class="rounded-circle bg-light-success d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="ph ph-clock" style="font-size: 24px;"></i>
                        </div>
                    </div>
                    <h4 class="mb-1">{{ $events->where('start_datetime', '>', now())->count() }}</h4>
                    <p class="text-muted mb-0">{{ __('events.upcoming_events_count') }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-6 mb-3">
            <div class="card">
                <div class="card-body text-center py-4">
                    <div class="d-flex align-items-center justify-content-center mb-3">
                        <div class="rounded-circle bg-light-warning d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="ph ph-map-pin" style="font-size: 24px;"></i>
                        </div>
                    </div>
                    <h4 class="mb-1">{{ $events->pluck('city')->unique()->count() }}</h4>
                    <p class="text-muted mb-0">{{ __('events.cities_count') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" id="filterForm">
                                                <div class="row g-3">
                            <div class="col-lg-3 col-md-12">
                                <div class="input-group">
                                    <span class="input-group-text bg-light-primary border-end-0">
                                        <i class="ph ph-magnifying-glass text-muted"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control border-start-0"
                                           placeholder="{{ __('events.search_events') }}"
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-4">
                                <select name="city" class="form-select">
                                    <option value="">{{ __('events.filter_by_city') }}</option>
                                    @foreach($events->pluck('city')->unique()->filter() as $city)
                                        <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                            {{ $city }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-4">
                                <select name="type" class="form-select">
                                    <option value="">{{ __('events.filter_by_type') }}</option>
                                    <option value="public" {{ request('type') == 'public' ? 'selected' : '' }}>{{ __('events.public_events') }}</option>
                                    <option value="private" {{ request('type') == 'private' ? 'selected' : '' }}>{{ __('events.private_events') }}</option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-4">
                                <input type="number" name="radius" class="form-control"
                                       placeholder="{{ __('events.radius_km') }}" value="{{ request('radius', 50) }}" min="1" max="200">
                            </div>
                            <div class="col-lg-3 col-md-12">
                                <div class="d-flex gap-2 justify-content-end flex-wrap">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ph ph-funnel me-1"></i>{{ __('common.filter') }}
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary" id="mapToggle">
                                        <i class="ph ph-map-pin me-1"></i>{{ __('events.show_map') }}
                                    </button>
                                    @auth
                                        @can('create', App\Models\Event::class)
                                            <a href="{{ route('events.create') }}" class="btn btn-success">
                                                <i class="ph ph-plus me-1"></i>{{ __('common.create') }}
                                            </a>
                                        @endcan
                                    @endauth
                                </div>
                            </div>
                        </div>

                        <!-- Quick Filter Tags -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="bg-light-primary rounded px-3 py-2" data-filter="today" style="cursor: pointer;">
                                        <i class="ph ph-calendar me-1"></i> Oggi
                                    </span>
                                    <span class="bg-light-info rounded px-3 py-2" data-filter="tomorrow" style="cursor: pointer;">
                                        <i class="ph ph-calendar-plus me-1"></i> Domani
                                    </span>
                                    <span class="bg-light-success rounded px-3 py-2" data-filter="weekend" style="cursor: pointer;">
                                        <i class="ph ph-calendar-check me-1"></i> Weekend
                                    </span>
                                    <span class="bg-light-warning rounded px-3 py-2" data-filter="free" style="cursor: pointer;">
                                        <i class="ph ph-currency-circle-dollar me-1"></i> Gratis
                                    </span>
                                    <span class="bg-light-secondary rounded px-3 py-2" data-filter="nearby" style="cursor: pointer;">
                                        <i class="ph ph-map-pin me-1"></i> Vicino a me
                                    </span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Container (Hidden by default) -->
    <div class="row mb-4" id="mapContainer" style="display: none;">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <div id="eventsMap" style="height: 400px; border-radius: 10px; overflow: hidden;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Events Grid -->
    <div class="row" id="eventsGrid">
        @forelse($events as $event)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 position-relative">
                    <!-- Event Status Badge -->
                    @if($event->is_public)
                        <span class="badge bg-success position-absolute top-0 end-0 m-3" style="z-index: 3;">Pubblico</span>
                    @else
                        <span class="badge bg-warning position-absolute top-0 end-0 m-3" style="z-index: 3;">Privato</span>
                    @endif

                    <!-- Event Image with Overlay Info -->
                    <div class="position-relative overflow-hidden" style="height: 200px;">
                        @if($event->image_url)
                            <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="position-absolute w-100 h-100" style="object-fit: cover;">
                            <div class="position-absolute w-100 h-100" style="background: linear-gradient(135deg, rgba(15, 98, 106, 0.7) 0%, rgba(12, 78, 85, 0.7) 100%);"></div>
                        @else
                            <div class="position-absolute w-100 h-100 bg-primary" style="background: linear-gradient(135deg, #0f626a 0%, #0c4e55 100%);"></div>
                        @endif
                        <div class="position-absolute bottom-0 start-0 text-white p-3 w-100" style="z-index: 2;">
                            <h6 class="mb-1 text-white">{{ $event->venue_name }}</h6>
                            <small class="text-white-50"><i class="ph ph-map-pin me-1"></i>{{ $event->city }}</small>
                        </div>
                    </div>

                    <div class="card-body d-flex flex-column p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="flex-grow-1">
                                <h5 class="card-title mb-2 fw-bold">
                                    <a href="{{ route('events.show', $event) }}" class="text-decoration-none text-dark">
                                        {{ $event->title }}
                                    </a>
                                </h5>
                                <p class="text-muted mb-2">
                                    {{ Str::limit($event->description, 80) }}
                                </p>
                            </div>
                            <div class="flex-shrink-0 ms-3">
                                <div class="bg-light-primary text-center d-flex flex-column align-items-center justify-content-center" style="min-width: 50px; min-height: 50px; font-size: 12px; border-radius: 8px;">
                                    <div class="fw-bold fs-6">{{ $event->start_datetime->format('d') }}</div>
                                    <div class="small">{{ $event->start_datetime->format('M') }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex align-items-center text-muted mb-2">
                                <i class="ph ph-clock me-2"></i>
                                <span>{{ $event->start_datetime->format('H:i') }} - {{ $event->end_datetime->format('H:i') }}</span>
                            </div>
                            <div class="d-flex align-items-center text-muted mb-2">
                                <i class="ph ph-user me-2"></i>
                                <span>{{ $event->organizer->name }}</span>
                            </div>
                            @if($event->entry_fee > 0)
                                <div class="d-flex align-items-center text-muted">
                                    <i class="ph ph-currency-eur me-2"></i>
                                    <span>€{{ number_format($event->entry_fee, 2) }}</span>
                                </div>
                            @else
                                <div class="d-flex align-items-center text-success">
                                    <i class="ph ph-gift me-2"></i>
                                    <span class="fw-semibold">{{ __('events.free') }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Tags -->
                        @if($event->tags)
                            <div class="mb-3">
                                @foreach(array_slice($event->tags, 0, 3) as $tag)
                                    <span class="badge bg-light text-dark me-1">#{{ $tag }}</span>
                                @endforeach
                                @if(count($event->tags) > 3)
                                    <span class="badge bg-light text-muted">+{{ count($event->tags) - 3 }}</span>
                                @endif
                            </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="mt-auto">
                            <div class="row g-2">
                                <div class="col-8">
                                    <a href="{{ route('events.show', $event) }}" class="btn btn-primary btn-sm w-100">
                                        <i class="ph ph-eye me-1"></i> Dettagli
                                    </a>
                                </div>
                                <div class="col-4">
                                    @auth
                                        @if($event->organizer_id === auth()->id())
                                            <a href="{{ route('events.manage', $event) }}" class="btn btn-light-secondary btn-sm w-100">
                                                <i class="ph ph-gear"></i>
                                            </a>
                                        @elseif($event->acceptsRequests() && !$event->requests()->where('user_id', auth()->id())->exists())
                                            <button class="btn btn-light-success btn-sm w-100" data-bs-toggle="modal" data-bs-target="#applyModal" data-event-id="{{ $event->id }}">
                                                <i class="ph ph-hand-waving"></i>
                                            </button>
                                        @else
                                            <button class="btn btn-light-secondary btn-sm w-100" disabled>
                                                <i class="ph ph-check"></i>
                                            </button>
                                        @endif
                                    @else
                                        <a href="{{ route('poetry.test.real-login') }}" class="btn btn-light-primary btn-sm w-100">
                                            <i class="ph ph-sign-in"></i>
                                        </a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="ph ph-calendar-x display-4 text-muted mb-3"></i>
                        <h4 class="text-muted">{{ __('events.no_events_found') }}</h4>
                        <p class="text-muted mb-4">{{ __('events.no_events_message') }}</p>
                        @auth
                            @can('create', App\Models\Event::class)
                                <a href="{{ route('events.create') }}" class="btn btn-primary">
                                    <i class="ph ph-plus me-2"></i>{{ __('events.create_first_event') }}
                                </a>
                            @endcan
                        @endauth
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($events->hasPages())
        <div class="row">
            <div class="col-12 d-flex justify-content-center">
                {{ $events->appends(request()->query())->links() }}
            </div>
        </div>
    @endif
</div>

<!-- Apply Modal -->
@auth
<div class="modal fade" id="applyModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ph ph-hand-waving me-2"></i>Richiesta Partecipazione
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="applyForm">
                <div class="modal-body">
                    <div id="eventDetails" class="mb-4"></div>

                    <div class="mb-3">
                        <label class="form-label">Ruolo Richiesto *</label>
                        <select name="requested_role" class="form-select" required>
                            <option value="">Seleziona ruolo...</option>
                            <option value="performer">Performer</option>
                            <option value="judge">Judge</option>
                            <option value="technician">Technician</option>
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
                        <div id="portfolioLinks">
                            <input type="url" name="portfolio_links[]" class="form-control mb-2"
                                   placeholder="https://youtube.com/watch?v=...">
                        </div>
                        <button type="button" class="btn btn-light-secondary btn-sm" id="addPortfolioLink">
                            <i class="ph ph-plus me-1"></i>Aggiungi Link
                        </button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Annulla</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ph ph-paper-plane me-2"></i>Invia Richiesta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endauth
@endsection

@section('script')
<script src="{{ asset('assets/vendor/leafletmaps/leaflet.js') }}"></script>
<script>
let map = null;
let markers = [];

document.addEventListener('DOMContentLoaded', function() {

    // Map Toggle
    document.getElementById('mapToggle').addEventListener('click', function() {
        const mapContainer = document.getElementById('mapContainer');
        const eventsGrid = document.getElementById('eventsGrid');

        if (mapContainer.style.display === 'none') {
            mapContainer.style.display = 'block';
            eventsGrid.style.display = 'none';
            this.innerHTML = '<i class="ph ph-list"></i> Lista';
            initMap();
        } else {
            mapContainer.style.display = 'none';
            eventsGrid.style.display = 'flex';
            this.innerHTML = '<i class="ph ph-map-pin"></i> Mappa';
        }
    });

    // Filter Chips
    document.querySelectorAll('.filter-chip').forEach(chip => {
        chip.addEventListener('click', function() {
            const filter = this.dataset.filter;
            // Implement filter logic here
            this.classList.toggle('active');
        });
    });

    // Apply Modal
    @auth
    const applyModal = document.getElementById('applyModal');
    if (applyModal) {
        applyModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const eventId = button.getAttribute('data-event-id');
            loadEventDetails(eventId);
        });

        // Portfolio Links
        document.getElementById('addPortfolioLink').addEventListener('click', function() {
            const container = document.getElementById('portfolioLinks');
            const input = document.createElement('input');
            input.type = 'url';
            input.name = 'portfolio_links[]';
            input.className = 'form-control mb-2';
            input.placeholder = 'https://...';
            container.appendChild(input);
        });

        // Apply Form Submit
        document.getElementById('applyForm').addEventListener('submit', function(e) {
            e.preventDefault();
            submitApplication();
        });
    }
    @endauth

    // Live Search
    let searchTimeout;
    document.querySelector('input[name="search"]').addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            document.getElementById('filterForm').submit();
        }, 500);
    });
});

function initMap() {
    if (map) return;

    map = L.map('eventsMap').setView([41.9028, 12.4964], 6); // Italy center

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Load events on map
    loadEventsOnMap();
}

function loadEventsOnMap() {
    fetch('/api/events/near?' + new URLSearchParams({
        latitude: 41.9028,
        longitude: 12.4964,
        radius: 1000
    }))
    .then(response => response.json())
    .then(events => {
        events.forEach(event => {
            if (event.latitude && event.longitude) {
                const marker = L.marker([event.latitude, event.longitude])
                    .addTo(map)
                    .bindPopup(`
                        <div class="p-2">
                            <h6>${event.title}</h6>
                            <p class="mb-1"><i class="ph ph-calendar me-1"></i>${event.start_datetime}</p>
                            <p class="mb-2"><i class="ph ph-map-pin me-1"></i>${event.venue_name}</p>
                            <a href="${event.url}" class="btn btn-primary btn-sm">Dettagli</a>
                        </div>
                    `);
                markers.push(marker);
            }
        });
    });
}

@auth
function loadEventDetails(eventId) {
    fetch(`/requests/api/events/${eventId}/form-data`)
        .then(response => response.json())
        .then(data => {
            if (data.can_apply) {
                document.getElementById('eventDetails').innerHTML = `
                    <div class="alert alert-light">
                        <h6>${data.event.title}</h6>
                        <p class="mb-1"><i class="ph ph-calendar me-2"></i>${data.event.start_datetime}</p>
                        <p class="mb-1"><i class="ph ph-map-pin me-2"></i>${data.event.venue_name}, ${data.event.city}</p>
                        <p class="mb-0 small text-muted">${data.event.description}</p>
                    </div>
                `;

                // Update role options
                const roleSelect = document.querySelector('select[name="requested_role"]');
                roleSelect.innerHTML = '<option value="">Seleziona ruolo...</option>';
                Object.entries(data.available_roles).forEach(([key, value]) => {
                    roleSelect.innerHTML += `<option value="${key}">${value}</option>`;
                });

                document.getElementById('applyForm').dataset.eventId = eventId;
            } else {
                document.getElementById('eventDetails').innerHTML = `
                    <div class="alert alert-warning">
                        ${data.message}
                    </div>
                `;
            }
        });
}

function submitApplication() {
    const form = document.getElementById('applyForm');
    const eventId = form.dataset.eventId;
    const formData = new FormData(form);

    const data = {
        message: formData.get('message'),
        requested_role: formData.get('requested_role'),
        experience: formData.get('experience'),
        portfolio_links: formData.getAll('portfolio_links[]').filter(link => link.trim() !== '')
    };

    fetch(`/events/${eventId}/apply`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('applyModal')).hide();
            // Show success notification
            showNotification('Richiesta inviata con successo!', 'success');
        } else {
            showNotification('Errore nell\'invio della richiesta', 'error');
        }
    })
    .catch(error => {
        showNotification('Errore di connessione', 'error');
    });
}
@endauth

function showNotification(message, type) {
    // Simple notification system - will be enhanced with real-time notifications
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
