@extends('layout.master')
@section('title', __('dashboard.dashboard') . ' - Slam In')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/fullcalendar/fullcalendar.bundle.css') }}">
<style>
    .dashboard-calendar .fc-toolbar {
        display: none !important;
    }
    .dashboard-calendar .fc-daygrid-day {
        cursor: pointer;
    }
    .dashboard-calendar .fc-event {
        cursor: pointer;
        font-size: 11px;
        padding: 2px 4px;
    }
    .dashboard-calendar .fc-daygrid-day-number {
        font-size: 12px;
    }
    .dashboard-calendar .fc-col-header-cell {
        font-size: 11px;
        padding: 4px 0;
    }
    .event-organizer {
        background-color: #28a745 !important;
        border-color: #28a745 !important;
    }
    .event-participant {
        background-color: #007bff !important;
        border-color: #007bff !important;
    }
    .event-private {
        background-color: #ffc107 !important;
        border-color: #ffc107 !important;
    }
    .event-wishlisted {
        background-color: #ff6b6b !important;
        border-color: #ff5252 !important;
    }
</style>
@endsection

@section('main-content')
    <div class="container-fluid">

        <!-- User Welcome Card semplificata -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card bg-primary text-white hover-effect">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="text-white mb-1 f-w-600">{{ __('dashboard.welcome', ['name' => $user->getDisplayName()]) }}</h4>
                                <p class="text-white-50 mb-2 f-s-14">{{ $user->email }}</p>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($user->getRoleNames() as $role)
                                        <span class="badge bg-light text-dark f-s-12">
                                            {{ __('auth.role_' . $role) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="bg-white-500 h-50 w-50 d-flex-center rounded-circle ms-auto">
                                    <i class="ph ph-user f-s-24 text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendario e Statistiche in riga -->
        <div class="row mb-4">
            <!-- Calendario a sinistra -->
            <div class="col-lg-8">
                <div class="card hover-effect equal-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0 f-w-600">
                            <i class="ph ph-calendar me-2 text-warning"></i>{{ __('dashboard.my_calendar') }}
                        </h6>
                        <div class="d-flex gap-2">
                            <button class="btn btn-light-warning btn-sm" id="calendarPrev">
                                <i class="ph ph-caret-left"></i>
                            </button>
                            <button class="btn btn-light-warning btn-sm" id="calendarNext">
                                <i class="ph ph-caret-right"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body pa-20">
                        <div id="dashboardCalendar" style="height: 300px;"></div>
                        <div class="text-center mt-3">
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('events.create') }}" class="btn btn-success btn-sm">
                                    <i class="ph ph-plus me-1"></i>{{ __('dashboard.create_event_button') }}
                                </a>
                                <a href="{{ route('calendar') }}" class="btn btn-light-warning btn-sm">
                                    <i class="ph ph-calendar me-1"></i>{{ __('dashboard.view_full_calendar') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiche a destra in griglia 2x2 -->
            <div class="col-lg-4">
                <div class="card hover-effect equal-card">
                    <div class="card-header">
                        <h6 class="card-title mb-0 f-w-600">
                            <i class="ph ph-chart-bar me-2 text-primary"></i>{{ __('dashboard.statistics') }}
                        </h6>
                    </div>
                    <div class="card-body pa-20">
                        <div class="row g-3">
                            <!-- Statistica 1 - Eventi Passati -->
                            <div class="col-6">
                                <a href="{{ route('events.index', ['filter' => 'past']) }}" class="text-decoration-none">
                                    <div class="card hover-effect equal-card b-t-4-secondary">
                                        <div class="card-body eshop-cards text-center pa-15">
                                            <div class="bg-light-secondary h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                                                <i class="ph ph-clock-counter-clockwise f-s-18 text-secondary"></i>
                                            </div>
                                            <span class="ripple-effect"></span>
                                            <div class="overflow-hidden">
                                                <h4 class="text-secondary mb-1 f-w-600">{{ $stats['past_events'] }}</h4>
                                                <p class="f-w-500 text-dark f-s-12 mb-1">{{ __('dashboard.past_events') }}</p>
                                                <span class="badge bg-light-secondary f-s-10">{{ __('dashboard.role_history') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Statistica 2 - Eventi Futuri -->
                            <div class="col-6">
                                <a href="{{ route('events.index', ['filter' => 'future']) }}" class="text-decoration-none">
                                    <div class="card hover-effect equal-card b-t-4-warning">
                                        <div class="card-body eshop-cards text-center pa-15">
                                            <div class="bg-light-warning h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                                                <i class="ph ph-calendar-check f-s-18 text-warning"></i>
                                            </div>
                                            <span class="ripple-effect"></span>
                                            <div class="overflow-hidden">
                                                <h4 class="text-warning mb-1 f-w-600">{{ $stats['future_events'] }}</h4>
                                                <p class="f-w-500 text-dark f-s-12 mb-1">{{ __('dashboard.future_events') }}</p>
                                                <span class="badge bg-light-warning f-s-10">{{ __('dashboard.role_upcoming') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Statistica 3 - Eventi Organizzati -->
                            <div class="col-6">
                                <a href="{{ route('events.index', ['filter' => 'organized']) }}" class="text-decoration-none">
                                    <div class="card hover-effect equal-card b-t-4-primary">
                                        <div class="card-body eshop-cards text-center pa-15">
                                            <div class="bg-light-primary h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                                                <i class="ph ph-article f-s-18 text-primary"></i>
                                            </div>
                                            <span class="ripple-effect"></span>
                                            <div class="overflow-hidden">
                                                <h4 class="text-primary mb-1 f-w-600">{{ $stats['organized_events'] }}</h4>
                                                <p class="f-w-500 text-dark f-s-12 mb-1">{{ __('dashboard.organized_events') }}</p>
                                                <span class="badge bg-light-primary f-s-10">{{ __('dashboard.role_organizer') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Statistica 4 - Inviti in Attesa -->
                            <div class="col-6">
                                <a href="{{ route('events.index', ['filter' => 'invitations']) }}" class="text-decoration-none">
                                    <div class="card hover-effect equal-card b-t-4-success">
                                        <div class="card-body eshop-cards text-center pa-15">
                                            <div class="bg-light-success h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                                                <i class="ph ph-envelope f-s-18 text-success"></i>
                                            </div>
                                            <span class="ripple-effect"></span>
                                            <div class="overflow-hidden">
                                                <h4 class="text-success mb-1 f-w-600">{{ $stats['pending_invitations'] }}</h4>
                                                <p class="f-w-500 text-dark f-s-12 mb-1">{{ __('dashboard.pending_invitations') }}</p>
                                                <span class="badge bg-light-success f-s-10">{{ __('dashboard.role_invitations') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contenuto sotto il calendario e statistiche -->
        <div class="row">
            <!-- Titolo {{ __('invitations.actions') }} Rapide -->
            <div class="col-12 mb-3">
                <div class="text-center">
                    <h5 class="text-primary mb-2 f-w-600">
                        <i class="ph ph-lightning me-2"></i>{{ __('dashboard.quick_actions') }}
                    </h5>
                    <hr class="w-25 mx-auto border-primary border-2 opacity-25">
                </div>
            </div>

            <!-- Quick Actions compatte -->
            @php
                $actionCards = [
                    [
                        'key' => 'organize_event',
                        'color' => 'success',
                        'icon' => 'ph ph-calendar-plus',
                        'link' => route('events.create')
                    ],
                    [
                        'key' => 'find_events',
                        'color' => 'warning',
                        'icon' => 'ph ph-search',
                        'link' => route('events.index')
                    ],
                    [
                        'key' => 'view_wishlist',
                        'color' => 'danger',
                        'icon' => 'ph ph-heart',
                        'link' => route('wishlist.index')
                    ],
                    [
                        'key' => 'view_poems',
                        'color' => 'info',
                        'icon' => 'ph ph-book-open',
                        'link' => route('poems.index')
                    ],
                    [
                        'key' => 'create_post',
                        'color' => 'primary',
                        'icon' => 'ph ph-plus-circle',
                        'link' => '#'
                    ],
                    [
                        'key' => 'write_poem',
                        'color' => 'secondary',
                        'icon' => 'ph ph-pen-nib',
                        'link' => route('poems.create')
                    ],
                    [
                        'key' => 'upload_performance',
                        'color' => 'info',
                        'icon' => 'ph ph-upload',
                        'link' => '#'
                    ],
                    [
                        'key' => 'manage_venue',
                        'color' => 'dark',
                        'icon' => 'ph ph-buildings',
                        'link' => '#'
                    ]
                ];
            @endphp

            @foreach($actionCards as $action)
                <div class="col-6 col-md-4 col-lg-2 mb-3">
                    <a href="{{ $action['link'] }}" class="card hover-effect h-100 text-decoration-none">
                        <div class="card-body text-center pa-15">
                            <div class="bg-light-{{ $action['color'] }} h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                                <i class="{{ $action['icon'] }} text-{{ $action['color'] }} f-s-18"></i>
                            </div>
                            <h6 class="mb-1 fw-bold text-dark f-s-13">{{ __('dashboard.' . $action['key']) }}</h6>
                            <small class="text-muted f-s-11">{{ __('dashboard.' . $action['key'] . '_desc') }}</small>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <!-- {{ __('wishlist.wishlist') }} Slider -->
        @if(auth()->user()->wishlistedEvents()->count() > 0)
        <div class="row mt-4">
            <div class="col-12">
                <div class="card hover-effect equal-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0 f-w-600">
                            <i class="ph-duotone ph-heart me-2 text-danger"></i>{{ __('dashboard.my_wishlist') }}
                        </h6>
                        <a href="{{ route('wishlist.index') }}" class="btn btn-light-danger btn-sm">
                            <i class="ph ph-arrow-right me-1"></i>{{ __('dashboard.view_all') }}
                        </a>
                    </div>
                    <div class="card-body pa-20">
                        <div class="row g-3">
                            @foreach(auth()->user()->wishlistedEvents()->orderBy('start_datetime')->take(6)->get() as $event)
                            <div class="col-md-6 col-lg-4">
                                <div class="card hover-effect h-100">
                                    <div class="card-body pa-15">
                                        <div class="d-flex align-items-start mb-3">
                                            <div class="flex-shrink-0 me-3">
                                                @if($event->image_url)
                                                    <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light-secondary rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                                        <i class="ph ph-calendar text-secondary f-s-20"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-bold text-dark f-s-14">{{ Str::limit($event->title, 30) }}</h6>
                                                <p class="mb-1 text-muted f-s-12">
                                                    <i class="ph ph-map-pin me-1"></i>{{ $event->city }}
                                                </p>
                                                <p class="mb-0 text-muted f-s-12">
                                                    <i class="ph ph-calendar me-1"></i>{{ $event->start_datetime->format('d/m/Y H:i') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-light-{{ $event->getCategoryColorClassAttribute() }} text-{{ $event->getCategoryColorClassAttribute() }} f-s-11">
                                                {{ $event->getCategoryDisplayName() }}
                                            </span>
                                            <div class="d-flex gap-1">
                                                <a href="{{ route('events.show', $event) }}" class="btn btn-light-primary btn-sm">
                                                    <i class="ph ph-eye f-s-12"></i>
                                                </a>
                                                <button class="btn btn-light-danger btn-sm wishlist-toggle" data-event-id="{{ $event->id }}">
                                                    <i class="ph-duotone ph-heart-fill f-s-12"></i>
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
            </div>
        </div>
        @endif

        <!-- Recent Activity e Role-Specific Sections -->
        <div class="row mt-4">
            <!-- Attività Recenti -->
            <div class="col-lg-4">
                <div class="card hover-effect equal-card">
                    <!-- Solo ribbon importante per novità -->
                    <div class="ribbon-top top-left ribbon-primary">
                        <i class="ph ph-sparkle f-s-12"></i>
                    </div>
                    <div class="card-header">
                        <h6 class="card-title mb-0 f-w-600">
                            <i class="ph ph-bell me-2 text-primary"></i>{{ __('dashboard.recent_activity') }}
                        </h6>
                    </div>
                    <div class="card-body pa-20">
                        @if(count($recentActivity) > 0)
                            @foreach($recentActivity as $activity)
                                <div class="d-flex align-items-center mb-2 pb-2 border-bottom">
                                    <div class="flex-shrink-0">
                                        <div class="bg-light-primary h-35 w-35 d-flex-center rounded-circle">
                                            <i class="ph ph-bell text-primary f-s-14"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="mb-0 fw-500 f-s-14">{{ $activity['message'] }}</p>
                                        <small class="text-muted f-s-12">{{ $activity['time'] }}</small>
                                    </div>
                                </div>
                            @endforeach
                            <div class="text-center mt-3">
                                <a href="#" class="btn btn-light-primary btn-sm">
                                    <i class="ph ph-eye me-1"></i>{{ __('dashboard.view_all_activity') }}
                                </a>
                            </div>
                        @else
                            <div class="text-center py-3">
                                <div class="bg-light-primary h-50 w-50 d-flex-center rounded-circle m-auto mb-2">
                                    <i class="ph ph-bell-slash f-s-24 text-primary"></i>
                                </div>
                                <p class="text-muted f-s-14 mb-0">{{ __('dashboard.no_recent_activity') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Role-Specific Sections -->
            <div class="col-lg-8">
                <div class="row g-3">
                    @if(isset($roleContent['poet']))
                        <div class="col-md-6">
                            <div class="card card-light-success hover-effect equal-card">
                                <div class="card-body text-center pa-20">
                                    <div class="bg-success h-45 w-45 d-flex-center rounded-circle m-auto mb-2">
                                        <i class="ph ph-pen-nib f-s-20 text-white"></i>
                                    </div>
                                    <h6 class="text-success f-w-600 mb-1">{{ __('dashboard.poet_section') }}</h6>
                                    <p class="text-muted f-s-12 mb-2">{{ __('dashboard.poet_section_description') }}</p>
                                    <a href="#" class="btn btn-success btn-sm">
                                        <i class="ph ph-arrow-right me-1"></i>{{ __('dashboard.access_section') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(isset($roleContent['organizer']))
                        <div class="col-md-6">
                            <div class="card card-light-danger hover-effect equal-card">
                                <div class="card-body text-center pa-20">
                                    <div class="bg-danger h-45 w-45 d-flex-center rounded-circle m-auto mb-2">
                                        <i class="ph ph-calendar-plus f-s-20 text-white"></i>
                                    </div>
                                    <h6 class="text-danger f-w-600 mb-1">{{ __('dashboard.organizer_section') }}</h6>
                                    <p class="text-muted f-s-12 mb-2">{{ __('dashboard.organizer_section_description') }}</p>
                                    <a href="#" class="btn btn-danger btn-sm">
                                        <i class="ph ph-arrow-right me-1"></i>{{ __('dashboard.access_section') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(isset($roleContent['venue_owner']))
                        <div class="col-md-6">
                            <div class="card card-light-info hover-effect equal-card">
                                <div class="card-body text-center pa-20">
                                    <div class="bg-info h-45 w-45 d-flex-center rounded-circle m-auto mb-2">
                                        <i class="ph ph-buildings f-s-20 text-white"></i>
                                    </div>
                                    <h6 class="text-info f-w-600 mb-1">{{ __('dashboard.venue_section') }}</h6>
                                    <p class="text-muted f-s-12 mb-2">{{ __('dashboard.venue_section_description') }}</p>
                                    <a href="#" class="btn btn-info btn-sm">
                                        <i class="ph ph-arrow-right me-1"></i>{{ __('dashboard.access_section') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>


    </div>
@endsection

@push('scripts')
<script src="{{ asset('assets/vendor/fullcalendar/global.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('dashboardCalendar');

    if (calendarEl) {
        // Check if FullCalendar is available
        if (typeof FullCalendar === 'undefined') {
            console.error('FullCalendar library not loaded');
            calendarEl.innerHTML = `
                <div class="alert alert-warning text-center">
                    <i class="ph ph-warning me-2"></i>
                    {{ __('dashboard.calendar_not_available') }}
                    <br>
                    <small class="text-muted">{{ __('dashboard.calendar_reload_page') }}</small>
                </div>
            `;

            // Show SweetAlert notification
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: '{{ __('dashboard.calendar') }}',
                    text: '{{ __('dashboard.calendar_not_available') }}',
                    confirmButtonText: 'OK'
                });
            }
            return;
        }

        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            height: 'auto',
            headerToolbar: false,
            dayMaxEvents: 2,
            moreLinkClick: 'popover',
            locale: 'it',
            firstDay: 1,
            dayHeaderFormat: { weekday: 'short' },
            dayCellDidMount: function(arg) {
                // Add custom styling for today
                if (arg.date.toDateString() === new Date().toDateString()) {
                    arg.el.style.backgroundColor = '#fff3cd';
                }
            },
            events: function(fetchInfo, successCallback, failureCallback) {
                // Fetch events from API
                Promise.all([
                    fetch('/api/events/calendar'),
                    fetch('/wishlist/calendar')
                ])
                .then(responses => Promise.all(responses.map(r => r.json())))
                .then(([eventsData, wishlistData]) => {
                    const events = eventsData.map(event => ({
                        ...event,
                        className: event.className || 'event-participant'
                    }));

                    const wishlistEvents = wishlistData.map(event => ({
                        ...event,
                        className: 'event-wishlisted'
                    }));

                    successCallback([...events, ...wishlistEvents]);
                })
                .catch(error => {
                    console.error('Error fetching calendar events:', error);
                    failureCallback(error);
                });
            },
            eventClick: function(info) {
                // Navigate to event details
                if (info.event.url) {
                    window.location.href = info.event.url;
                }
            },
            eventDidMount: function(info) {
                // Add tooltip
                const event = info.event;
                const tooltip = new bootstrap.Tooltip(info.el, {
                    title: `${event.title}\n${event.start.toLocaleDateString('it-IT')}`,
                    placement: 'top',
                    trigger: 'hover',
                    container: 'body'
                });
            }
        });

        calendar.render();

        // Navigation buttons
        document.getElementById('calendarPrev').addEventListener('click', function() {
            calendar.prev();
        });

        document.getElementById('calendarNext').addEventListener('click', function() {
            calendar.next();
        });
    }

    // {{ __('wishlist.wishlist') }} toggle functionality
    document.addEventListener('click', function(e) {
        if (e.target.closest('.wishlist-toggle')) {
            e.preventDefault();
            const button = e.target.closest('.wishlist-toggle');
            const eventId = button.dataset.eventId;

            fetch(`/wishlist/${eventId}/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update button appearance
                    const icon = button.querySelector('i');
                    if (data.in_wishlist) {
                        button.className = 'btn btn-light-danger btn-sm wishlist-toggle';
                        icon.className = 'ph-duotone ph-heart-fill f-s-12';
                    } else {
                        button.className = 'btn btn-outline-danger btn-sm wishlist-toggle';
                        icon.className = 'ph-duotone ph-heart f-s-12';

                        // Remove the card from the dashboard
                        const card = button.closest('.col-md-6, .col-lg-4');
                        if (card) {
                            card.style.transition = 'opacity 0.3s ease';
                            card.style.opacity = '0';
                            setTimeout(() => {
                                card.remove();

                                // Check if no more wishlist items
                                const remainingItems = document.querySelectorAll('.wishlist-toggle').length;
                                if (remainingItems === 0) {
                                    // Hide the entire wishlist section
                                    const wishlistSection = document.querySelector('.row.mt-4').previousElementSibling;
                                    if (wishlistSection && wishlistSection.querySelector('.wishlist-slider')) {
                                        wishlistSection.remove();
                                    }
                                }
                            }, 300);
                        }
                    }

                    // Show notification
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: data.in_wishlist ? 'success' : 'info',
                            title: data.in_wishlist ? 'Aggiunto alla wishlist' : 'Rimosso dalla wishlist',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                    }
                }
            })
            .catch(error => {
                console.error('Error toggling wishlist:', error);
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Errore',
                        text: 'Si è verificato un errore. Riprova.',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });
});
</script>
@endpush
