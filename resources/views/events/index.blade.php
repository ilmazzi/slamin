@extends('layout.master')

@section('title', request('filter') ? __('dashboard.' . request('filter') . '_events') : __('events.events_poetry_slam'))
@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/leafletmaps/leaflet.css') }}">
<style>
/* Assicura che il footer sia sempre visibile */
footer {
    position: fixed !important;
    bottom: 0 !important;
    left: 0 !important;
    width: 100% !important;
    z-index: 1000 !important;
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
}

/* Assicura che il footer non si sovrapponga alla sidebar */
@media (min-width: 1200px) {
    footer {
        padding-left: 20rem !important;
    }
}

@media (max-width: 1199px) {
    footer {
        padding-left: 4.5rem !important;
    }
}
.custom-marker { background: transparent; border: none; }

/* Mobile-First Responsive Styles */
@media (max-width: 576px) {
    .card-body {
        padding: 1rem !important;
    }

    .form-select-sm {
        font-size: 0.875rem;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }

    .badge {
        font-size: 0.625rem;
    }

    .f-s-10 {
        font-size: 0.625rem !important;
    }

    .f-s-12 {
        font-size: 0.75rem !important;
    }

    .f-s-14 {
        font-size: 0.875rem !important;
    }

    .f-s-16 {
        font-size: 1rem !important;
    }

    .f-s-20 {
        font-size: 1.25rem !important;
    }

    /* Map controls mobile optimization */
    .map-controls .btn {
        width: 35px;
        height: 35px;
        padding: 0.25rem;
    }

    /* Map controls visibility */
    .map-controls {
        z-index: 1001 !important;
    }

    .map-controls .btn {
        z-index: 1002 !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2) !important;
    }

    .map-controls .btn:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3) !important;
    }

    /* Event card mobile optimization */
    .card.h-100 {
        min-height: auto;
    }

    /* Social actions mobile optimization */
    .social-like-btn,
    .social-view-counter,
    .social-comment-btn {
        padding: 0.25rem !important;
    }

    .social-like-btn img,
    .social-view-counter i,
    .social-comment-btn i {
        width: 18px !important;
        height: 18px !important;
        font-size: 18px !important;
    }
}

/* Tablet optimization */
@media (min-width: 577px) and (max-width: 768px) {
    .card-body {
        padding: 1.25rem !important;
    }

    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.875rem;
    }
}


</style>
@endsection

@section('breadcrumb-title')
<h3>
    @if(request('filter'))
        @switch(request('filter'))
            @case('past')
                {{ __('dashboard.past_events') }}
                @break
            @case('future')
                {{ __('dashboard.future_events') }}
                @break
            @case('organized')
                {{ __('dashboard.organized_events') }}
                @break
            @case('invitations')
                {{ __('dashboard.pending_invitations') }}
                @break
            @default
                {{ __('events.events_poetry_slam') }}
        @endswitch
    @else
        {{ __('events.events_poetry_slam') }}
    @endif
</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('events.dashboard') }}</a></li>
<li class="breadcrumb-item active">{{ __('events.events') }}</li>
@endsection

@section('main-content')
<div class="container-fluid">

    <!-- Map Container (Always Visible) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <div id="eventsMap" style="height: 300px; border-radius: 10px; overflow: hidden; position: relative;">
                        <!-- Map Controls Overlay -->
                        <div class="map-controls position-absolute top-0 end-0 p-2" style="z-index: 1001; pointer-events: auto;">
                            <button class="btn btn-primary btn-sm mb-1 d-block" onclick="centerOnUser()" title="{{ __('events.center_on_my_position') }}" style="z-index: 1002;">
                                <i class="ph ph-map-pin f-s-14"></i>
                            </button>
                            <button class="btn btn-primary btn-sm mb-1 d-block" onclick="refreshEvents()" title="{{ __('events.refresh_events') }}" style="z-index: 1002;">
                                <i class="ph ph-arrow-clockwise f-s-14"></i>
                            </button>
                            <button class="btn btn-primary btn-sm d-block" onclick="showAllEvents()" title="{{ __('events.show_all_events') }}" style="z-index: 1002;">
                                <i class="ph ph-globe f-s-14"></i>
                            </button>
                        </div>
                    </div>
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
                        <!-- Mobile-First Filters -->
                        <div class="row g-3">
                            <!-- Search - Full width on mobile -->
                            <div class="col-12">
                                <div class="input-group">
                                    <span class="input-group-text bg-light-primary border-end-0">
                                        <i class="ph ph-magnifying-glass text-muted"></i>
                                    </span>
                                    <input type="text" name="search" class="form-control border-start-0"
                                           placeholder="{{ __('events.search_events') }}"
                                           value="{{ request('search') }}">
                                </div>
                            </div>

                            <!-- City Filter - Full width on mobile, half on tablet -->
                            <div class="col-12 col-sm-6">
                                <select name="city" class="form-select">
                                    <option value="">{{ __('events.filter_by_city') }}</option>
                                    @foreach($events->pluck('city')->unique()->filter() as $city)
                                        <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>
                                            {{ $city }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Type Filter - Full width on mobile, half on tablet -->
                            <div class="col-12 col-sm-6">
                                <select name="type" class="form-select">
                                    <option value="">{{ __('events.all_types') }}</option>
                                    <option value="public" {{ request('type') === 'public' ? 'selected' : '' }}>{{ __('events.public_events') }}</option>
                                    <option value="private" {{ request('type') === 'private' ? 'selected' : '' }}>{{ __('events.private_events') }}</option>
                                </select>
                            </div>
                            </div>
                        </div>

                        <!-- Second Row: Quick Filters and Action Buttons -->
                        <div class="row g-3 mt-4 mb-2">
                            <div class="col-lg-9 col-md-12">
                                <div class="d-flex flex-wrap gap-2 align-items-center p-2">
                                    <button type="button" class="btn btn-light-primary btn-sm" data-filter="today">
                                        <i class="ph ph-calendar me-1"></i> {{ __('events.today') }}
                                    </button>
                                    <button type="button" class="btn btn-light-info btn-sm" data-filter="tomorrow">
                                        <i class="ph ph-calendar-plus me-1"></i> {{ __('events.tomorrow') }}
                                    </button>
                                    <button type="button" class="btn btn-light-success btn-sm" data-filter="weekend">
                                        <i class="ph ph-calendar-check me-1"></i> {{ __('events.weekend') }}
                                    </button>
                                    <button type="button" class="btn btn-light-warning btn-sm" data-filter="free">
                                        <i class="ph ph-currency-circle-dollar me-1"></i> {{ __('events.free_events') }}
                                    </button>
                                    <button type="button" class="btn btn-light-secondary btn-sm" data-filter="nearby">
                                        <i class="ph ph-map-pin me-1"></i> {{ __('events.nearby') }}
                                    </button>
                                    @auth
                                        <button type="button" class="btn btn-light-primary btn-sm" data-filter="my">
                                            <i class="ph ph-user me-1"></i> {{ __('events.my_events') }}
                                        </button>
                                        <button type="button" class="btn btn-light-warning btn-sm" data-filter="private">
                                            <i class="ph ph-lock me-1"></i> {{ __('events.my_private_events') }}
                                        </button>
                                    @endauth
                                    <button type="button" class="btn btn-light-danger btn-sm" data-filter="past">
                                        <i class="ph ph-clock-counter-clockwise me-1"></i> {{ __('events.past_events') }}
                                    </button>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-12">
                                <div class="d-flex gap-2 justify-content-end p-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ph ph-funnel me-1"></i>{{ __('common.filter') }}
                                    </button>
                                    @auth
                                        @can('events.create.public')
                                            <a href="{{ route('events.create') }}" class="btn btn-success">
                                                <i class="ph ph-plus me-1"></i>{{ __('common.create') }}
                                            </a>
                                        @endcan
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Events Grid with Pagination Controls -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center mb-3 gap-2">
                <h5 class="mb-0">{{ __('events.events_list') }}</h5>
                <div class="d-flex align-items-center gap-2">
                    <label class="form-label mb-0 f-s-14">{{ __('events.show') }}:</label>
                    <select class="form-select form-select-sm" style="width: auto;" onchange="changePerPage(this.value)">
                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                        <option value="20" {{ request('per_page', 10) == 20 ? 'selected' : '' }}>20</option>
                        <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row" id="eventsGrid">
        @forelse($events->take(request('per_page', 10)) as $event)
            <div class="col-12 col-sm-6 col-lg-4 mb-4">
                <div class="card h-100 position-relative">
                    <!-- Past Event Ribbon -->
                    @if($event->start_datetime && $event->start_datetime < now())
                        <div class="arrow-ribbon arrow-left ribbon-danger" style="z-index: 5000;">
                            <span>{{ __('events.past_event') }}</span>
                        </div>
                    @endif

                    <!-- Event Status Badge -->
                    <div class="position-absolute top-0 end-0 p-2" style="z-index: 3;">
                        <div class="d-flex flex-column gap-1">
                            @if($event->is_public)
                                <span class="badge bg-success f-s-10">{{ __('events.public') }}</span>
                            @else
                                <span class="badge bg-warning f-s-10">{{ __('events.private') }}</span>
                            @endif
                            @if($event->acceptsRequests())
                                <span class="badge bg-primary f-s-10">
                                    <i class="ph ph-check me-1"></i>{{ __('events.apply_to_event') }}
                                </span>
                            @endif
                            @if($event->category)
                                <span class="badge {{ $event->category_color_class }} f-s-10">
                                    {{ $event->getCategoryDisplayName() }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Event Image with Overlay Info -->
                    <div class="position-relative overflow-hidden" style="height: 200px;">
                        @if($event->image_url)
                            <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="position-absolute w-100 h-100" style="object-fit: cover;">
                            <div class="position-absolute w-100 h-100" style="background: linear-gradient(135deg, rgba(15, 98, 106, 0.7) 0%, rgba(12, 78, 85, 0.7) 100%);"></div>
                        @else
                            <div class="position-absolute w-100 h-100 d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #0f626a 0%, #0c4e55 100%);">
                                <div class="text-center text-white">
                                    <i class="ph ph-calendar f-s-48 mb-2"></i>
                                    <div class="f-s-14 f-w-500">{{ $event->title }}</div>
                                </div>
                            </div>
                        @endif
                        <div class="position-absolute bottom-0 start-0 text-white p-2 w-100" style="z-index: 2;">
                            @if($event->is_online)
                                <h6 class="mb-1 text-white f-s-12">{{ __('events.online_event') }}</h6>
                                <small class="text-white-50 f-s-10"><i class="ph ph-globe me-1"></i>{{ __('events.virtual_event') }}</small>
                            @else
                            <h6 class="mb-1 text-white f-s-12">{{ Str::limit($event->venue_name, 30) }}</h6>
                            <small class="text-white-50 f-s-10"><i class="ph ph-map-pin me-1"></i>{{ $event->city }}</small>
                            @endif
                        </div>
                    </div>

                    <div class="card-body d-flex flex-column p-3">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="flex-grow-1 me-2">
                                <h6 class="card-title mb-2 fw-bold f-s-14">
                                    <a href="{{ route('events.show', $event) }}" class="text-decoration-none text-dark">
                                        {{ Str::limit($event->title, 50) }}
                                    </a>
                                </h6>
                                @if($event->subtitle)
                                    <h6 class="text-muted mb-2 f-s-12">{{ Str::limit($event->subtitle, 40) }}</h6>
                                @endif
                                
                                {{-- Winner Display for Completed Poetry Slam --}}
                                @if($event->category === 'poetry_slam' && $event->status === 'completed' && $event->rankings()->exists())
                                    @php
                                        $winner = $event->rankings()->where('position', 1)->with(['participant.user', 'badge'])->first();
                                    @endphp
                                    @if($winner && $winner->participant)
                                        <div class="mb-2 p-2 bg-light-warning rounded">
                                            <div class="d-flex align-items-center gap-2">
                                                <span class="badge bg-gradient-warning">🥇</span>
                                                <div class="flex-grow-1">
                                                    <strong class="d-block f-s-12">Vincitore:</strong>
                                                    <span class="f-s-11">{{ $winner->participant->display_name }}</span>
                                                    <span class="badge bg-light-secondary f-s-10 ms-1">{{ number_format($winner->total_score, 1) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif

                                <p class="text-muted mb-2 f-s-12">
                                    {{ Str::limit($event->description, 60) }}
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="bg-light-primary text-center d-flex flex-column align-items-center justify-content-center" style="min-width: 45px; min-height: 45px; font-size: 11px; border-radius: 6px;">
                                    <div class="fw-bold f-s-12">{{ $event->start_datetime ? $event->start_datetime->format('d') : '--' }}</div>
                                    <div class="f-s-10">{{ $event->start_datetime ? $event->start_datetime->format('M') : '--' }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex align-items-center text-muted mb-1">
                                <i class="ph ph-clock me-1 f-s-12"></i>
                                <span class="f-s-12">{{ $event->start_datetime ? $event->start_datetime->format('H:i') : '--:--' }} - {{ $event->end_datetime ? $event->end_datetime->format('H:i') : '--:--' }}</span>
                            </div>
                            <div class="d-flex align-items-center text-muted mb-1">
                                <i class="ph ph-user me-1 f-s-12"></i>
                                <span class="f-s-12">
                                    <a href="{{ route('user.show', $event->organizer) }}" class="text-decoration-none hover-effect">
                                        {{ $event->organizer->getDisplayName() }}
                                    </a>
                                </span>
                            </div>
                            @if($event->is_online)
                                <div class="d-flex align-items-center text-muted mb-1">
                                    <i class="ph ph-globe me-1 f-s-12"></i>
                                    <span class="f-s-12">{{ $event->timezone }}</span>
                                </div>
                            @else
                                <div class="d-flex align-items-center text-muted mb-1">
                                    <i class="ph ph-map-pin me-1 f-s-12"></i>
                                    <span class="f-s-12">{{ $event->city }}, {{ $event->country }}</span>
                                </div>
                            @endif
                        </div>

                        <div class="mt-auto">
                            <!-- Event Info -->
                            <div class="d-flex flex-wrap align-items-center gap-1 mb-3">
                                @if($event->entry_fee > 0)
                                    <span class="badge bg-warning f-s-10">{{ __('events.entry_fee') }}: €{{ $event->entry_fee }}</span>
                                @else
                                    <span class="badge bg-success f-s-10">{{ __('events.free') }}</span>
                                @endif
                                @if($event->max_participants)
                                    <small class="text-muted f-s-10">{{ __('events.max_participants') }}: {{ $event->max_participants }}</small>
                                @endif
                                @if($event->status === 'completed')
                                    <span class="badge bg-light-success f-s-10">
                                        <i class="ph ph-check-circle me-1"></i>Completato
                                    </span>
                                @endif
                            </div>

                            <!-- Social Actions -->
                            @if(Auth::check())
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <x-social-like-button :content="$event" type="event" />
                                    <x-social-view-counter :content="$event" type="event" />
                                    <x-social-comment-button :content="$event" type="event" />
                                    <x-report-button :content="$event" type="event" />
                                </div>
                            @endif

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2">
                                <a href="{{ route('events.show', $event) }}" class="btn btn-primary btn-sm flex-fill">
                                    <i class="ti ti-eye me-1"></i>{{ __('common.view') }}
                                </a>
                                @can('events.manage.own')
                                    @if(Auth::user()->hasRole(['admin', 'moderator']) || ($event->organizer_id === Auth::id() && $event->start_datetime && $event->start_datetime >= now()))
                                        <button type="button" class="btn btn-light-danger btn-sm"
                                                onclick="confirmDeleteEvent({{ $event->id }}, '{{ addslashes($event->title) }}')"
                                                title="{{ __('events.delete_event') }}">
                                            <i class="ti ti-trash"></i>
                                        </button>
                                    @endif
                                @endcan
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
                        <i class="ph ph-calendar-x f-s-48 text-muted mb-3"></i>
                        <h5 class="text-muted">{{ __('events.no_events_found') }}</h5>
                        <p class="text-muted">{{ __('events.try_adjusting_filters') }}</p>
                        @auth
                            @can('events.create.public')
                                <a href="{{ route('events.create') }}" class="btn btn-primary">
                                    <i class="ph ph-plus me-1"></i>{{ __('events.create_first_event') }}
                                </a>
                            @endcan
                        @endauth
                    </div>
                </div>
            </div>
        @endforelse
                                </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-6 col-md-3 mb-3">
            <div class="card hover-effect equal-card">
                <div class="card-body text-center py-3">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-light-primary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="ph ph-calendar f-s-20"></i>
                        </div>
                    </div>
                    <h5 class="mb-1 f-s-16">{{ $statistics['total_events'] }}</h5>
                    <p class="text-muted mb-0 f-s-12">{{ __('events.total_events') }}</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="card hover-effect equal-card">
                <div class="card-body text-center py-3">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-light-info d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="ph ph-globe f-s-20"></i>
                        </div>
                    </div>
                    <h5 class="mb-1 f-s-16">{{ $statistics['public_events'] }}</h5>
                    <p class="text-muted mb-0 f-s-12">{{ __('events.public_events_count') }}</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="card hover-effect equal-card">
                <div class="card-body text-center py-3">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-light-success d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="ph ph-clock f-s-20"></i>
                        </div>
                    </div>
                    <h5 class="mb-1 f-s-16">{{ $statistics['upcoming_events'] }}</h5>
                    <p class="text-muted mb-0 f-s-12">{{ __('events.upcoming_events_count') }}</p>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3 mb-3">
            <div class="card hover-effect equal-card">
                <div class="card-body text-center py-3">
                    <div class="d-flex align-items-center justify-content-center mb-2">
                        <div class="rounded-circle bg-light-warning d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="ph ph-buildings f-s-20"></i>
                        </div>
                    </div>
                    <h5 class="mb-1 f-s-16">{{ $statistics['venues_count'] }}</h5>
                    <p class="text-muted mb-0 f-s-12">{{ __('events.venues_count') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Event Details Modal -->
    <div class="modal fade" id="eventDetailsModal" tabindex="-1" aria-labelledby="eventDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                    <h5 class="modal-title" id="eventDetailsModalLabel">{{ __('events.event_details') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('common.close') }}"></button>
            </div>
                <div class="modal-body" id="eventDetailsModalBody">
                    <!-- Content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('events.close') }}</button>
                    <a href="#" class="btn btn-primary" id="eventDetailsModalLink">{{ __('events.view_complete_details') }}</a>
                </div>
        </div>
    </div>
</div>

<!-- Delete Event Modal -->
<div class="modal fade" id="deleteEventModal" tabindex="-1" aria-labelledby="deleteEventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="deleteEventModalLabel">
                    <i class="ph ph-warning me-2"></i>{{ __('events.delete_event_title') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('common.close') }}"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('events.confirm_delete_event', ['title' => '']) }} <strong id="deleteEventTitle"></strong></p>
                <div class="alert alert-warning">
                    <i class="ph ph-warning me-2"></i>
                    <strong>{{ __('events.warning') }}</strong> {{ __('events.delete_action_warning') }}
                    <ul class="mb-0 mt-2">
                        <li>{{ __('events.delete_warning_participants') }}</li>
                        <li>{{ __('events.delete_warning_invitations') }}</li>
                        <li>{{ __('events.delete_warning_favorites') }}</li>
                        <li>{{ __('events.delete_warning_festival') }}</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('events.cancel') }}</button>
                <form id="deleteEventForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="ph ph-trash me-2"></i>{{ __('events.delete_permanently') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function changePerPage(value) {
    const url = new URL(window.location);
    url.searchParams.set('per_page', value);
    window.location.href = url.toString();
}

function confirmDeleteEvent(eventId, eventTitle) {
    // Set the event title in the modal
    const titleElement = document.getElementById('deleteEventTitle');
    if (titleElement) {
        titleElement.textContent = eventTitle;
    }

    // Set the form action
    const formElement = document.getElementById('deleteEventForm');
    if (formElement) {
        formElement.action = `/events/${eventId}`;
    }

    // Show the modal
    const modalElement = document.getElementById('deleteEventModal');
    if (modalElement) {
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
    }
}
</script>
@endsection

@push('scripts')
<script src="{{ asset('assets/vendor/leafletmaps/leaflet.js') }}"></script>
<script>
// Variabili globali per la mappa
let map = null;
let markers = [];

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize map
            initMap();

    // Quick filter functionality
    document.querySelectorAll('[data-filter]').forEach(filter => {
        filter.addEventListener('click', function() {
            const filterType = this.dataset.filter;
            applyQuickFilter(filterType);
        });
    });

    // Live Search
    let searchTimeout;
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            document.getElementById('filterForm').submit();
        }, 500);
    });
    }
});

function initMap() {


    // Inizializza la mappa
    map = L.map('eventsMap').setView([41.9028, 12.4964], 10);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Carica gli eventi con i filtri correnti
    loadEventsWithCurrentFilters();
}

function loadEventsWithCurrentFilters() {
    const params = {};

    // Ottieni i parametri correnti dall'URL
    const urlParams = new URLSearchParams(window.location.search);

    // Applica i filtri correnti
    if (urlParams.has('search')) {
        params.search = urlParams.get('search');
    }
    if (urlParams.has('date_from')) {
        params.date_from = urlParams.get('date_from');
    }
    if (urlParams.has('date_to')) {
        params.date_to = urlParams.get('date_to');
    }
    if (urlParams.has('free_only')) {
        params.free_only = urlParams.get('free_only');
    }
    if (urlParams.has('filter')) {
        params.filter = urlParams.get('filter');
    }
    if (urlParams.has('city')) {
        params.city = urlParams.get('city');
    }
    if (urlParams.has('type')) {
        params.type = urlParams.get('type');
    }

    // Applica coordinate solo se esplicitamente specificate o se è il filtro 'nearby'
    if (urlParams.has('lat') && urlParams.has('lng')) {
        params.latitude = parseFloat(urlParams.get('lat'));
        params.longitude = parseFloat(urlParams.get('lng'));
        // Centra la mappa sulla posizione del filtro
        map.setView([params.latitude, params.longitude], 12);
    } else if (urlParams.has('filter') && urlParams.get('filter') === 'nearby') {
        // Per il filtro nearby, usa la posizione dell'utente se disponibile
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                params.latitude = position.coords.latitude;
                params.longitude = position.coords.longitude;
                params.radius = urlParams.get('radius') || '10';

                loadEventsOnMapWithFilter(params);
            }, function(error) {

                loadEventsOnMapWithFilter(params);
            });
            return;
        }
    }


    loadEventsOnMapWithFilter(params);
}

function loadEventsOnMap(lat = 45.59614070, lng = 8.91219860) {
    loadEventsOnMapWithFilter({
        latitude: lat,
        longitude: lng
    });
}

function loadEventsOnMapWithFilter(params) {


    // Clear existing markers
    markers.forEach(marker => map.removeLayer(marker));
    markers = [];

    // Build URL with parameters
    let url;
    if (params.latitude && params.longitude) {
        // Se abbiamo coordinate, usa l'endpoint /api/events/near
        url = new URL('/api/events/near', window.location.origin);
    } else {
        // Se non abbiamo coordinate, usa l'endpoint /api/events (senza filtro di posizione)
        url = new URL('/api/events', window.location.origin);
    }

    Object.keys(params).forEach(key => {
        if (params[key] !== null && params[key] !== undefined) {
            url.searchParams.append(key, params[key]);
        }
    });

    fetch(url)
        .then(response => {

        return response.json();
    })
    .then(events => {



            if (events.length === 0) {

                showNotification('{{ __('events.no_events_with_filters') }}', 'info');
                return;
            }

            events.forEach((event, index) => {


            if (event.latitude && event.longitude) {
                    // Determina il colore del marker basato sulla categoria
                    let markerColor = '#6c757d'; // Default secondary (grigio)
                    if (event.category_color_class) {
                        // Mappa le classi CSS ai colori esatti delle categorie (corrispondenti al modello Event)
                        const colorMap = {
                            'bg-primary': '#007bff',          // Concert
                            'bg-secondary': '#6c757d',        // Open Mic
                            'bg-success': '#28a745',          // Festival
                            'bg-danger': '#dc3545',           // Poetry Slam
                            'bg-warning': '#ffc107',          // Workshop
                            'bg-info': '#17a2b8',             // Conference
                            'bg-light': '#f8f9fa',            // Light
                            'bg-dark': '#343a40',             // Book Presentation
                            'bg-primary-600': '#0056b3',      // Poetry Art (blu scuro)
                            'bg-outline-primary': '#0d6efd',  // Reading (blu con bordo)
                            'bg-success-600': '#1e7e34',      // Residency (verde scuro)
                            'bg-warning-600': '#e0a800'       // Spoken Word (giallo scuro)
                        };
                        markerColor = colorMap[event.category_color_class] || '#6c757d';
                    }

                    console.log(`Marker color for ${event.title}: ${markerColor}`);

                    // Gestione marker sovrapposti - sposta leggermente i marker alla stessa posizione
                    let lat = parseFloat(event.latitude);
                    let lng = parseFloat(event.longitude);

                    // Controlla se ci sono altri marker alla stessa posizione
                    const existingMarkersAtPosition = markers.filter(marker => {
                        const markerLat = marker.getLatLng().lat;
                        const markerLng = marker.getLatLng().lng;
                        return Math.abs(markerLat - lat) < 0.0001 && Math.abs(markerLng - lng) < 0.0001;
                    });

                    if (existingMarkersAtPosition.length > 0) {
                        // Sposta il marker di una piccola quantità per renderlo visibile
                        const offset = 0.0002; // Circa 20 metri
                        lat += (existingMarkersAtPosition.length * offset);
                        lng += (existingMarkersAtPosition.length * offset);
                        console.log(`Offset applied to marker: ${existingMarkersAtPosition.length}`);
                    }

                    // Crea icona personalizzata con colore ma stile standard
                    const customIcon = L.divIcon({
                        className: 'custom-marker',
                        html: `<div style="background-color: ${markerColor}; width: 20px; height: 20px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.3);"></div>`,
                        iconSize: [20, 20],
                        iconAnchor: [10, 10]
                    });

                    const marker = L.marker([lat, lng], {
                        icon: customIcon
                    }).addTo(map);

                    console.log(`Marker created for event: ${event.title}`);

                    // Add click handler to open modal instead of popup
                    marker.on('click', function() {
                        openEventDetailsModal(event);
                    });

                markers.push(marker);
                } else {
                    console.log(`Event ${event.title} has no coordinates`);
                }
            });

            console.log(`Total markers added: ${markers.length}`);

            // Fit map to show all markers
            if (markers.length > 0) {
                const group = new L.featureGroup(markers);
                map.fitBounds(group.getBounds().pad(0.1));
            }
    })
    .catch(error => {
            console.error('Error loading events:', error);
            showNotification('{{ __('events.error_loading_events') }}', 'error');
    });
}

// Funzione per centrare sulla posizione dell'utente
function centerOnUser() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;
                map.setView([userLat, userLng], 12);
                loadEventsOnMap(userLat, userLng);
                showNotification('{{ __('events.map_centered_on_position') }}', 'success');
            },
            function(error) {
                let message = error.code === 1 ?
                    '{{ __('events.geolocation_https_required') }}' :
                    '{{ __('events.cannot_get_position') }}';
                showNotification(message, 'warning');
            }
        );
    }
}

// Funzione per aggiornare gli eventi
function refreshEvents() {
    const center = map.getCenter();
    loadEventsOnMap(center.lat, center.lng);
    showNotification('{{ __('events.events_updated') }}', 'success');
}

// Funzione per mostrare tutti gli eventi (senza filtro geografico)
function showAllEvents() {
    // Rimuovi la logica di distanza temporaneamente
    fetch('/api/events/test')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.events) {
                // Pulisci markers esistenti
                markers.forEach(marker => map.removeLayer(marker));
                markers = [];

                data.events.forEach(event => {
                    if (event.latitude && event.longitude) {
                        L.marker([parseFloat(event.latitude), parseFloat(event.longitude)])
                            .addTo(map)
                            .bindPopup(`
                                <div class="p-2">
                                    <h6>${event.title}</h6>
                                    ${event.is_online ?
                                        `<p class="mb-2"><i class="ph ph-globe me-1"></i>{{ __('events.online_event_label') }}</p>` :
                                        `<p class="mb-2"><i class="ph ph-map-pin me-1"></i>${event.venue_name}, ${event.city}</p>`
                                    }
                                    <a href="/events/${event.id}" class="btn btn-primary btn-sm mt-2">{{ __('common.view_details') }}</a>
                                </div>
                            `);
                    }
                                        });
                        showNotification('{{ __('events.events_shown', ['count' => '']) }}'.replace(':count', data.events.length), 'success');

                        // Centra la mappa se ci sono eventi
                        if (data.events.length > 0) {
                            const firstEvent = data.events[0];
                            map.setView([parseFloat(firstEvent.latitude), parseFloat(firstEvent.longitude)], 10);
                        }
            }
        })
        .catch(error => {
            console.error('Error loading all events:', error);
            showNotification('{{ __('common.loading_error') }} degli eventi', 'error');
        });
}

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

function applyQuickFilter(filterType) {
    // Applica i filtri sia alla mappa che alla lista
    applyFilterToMap(filterType);
    applyFilterToList(filterType);
}

function updateEventsList(params) {
    // Costruisci l'URL con i parametri di filtro
    const url = new URL(window.location);

    // Rimuovi parametri esistenti
    url.searchParams.delete('date_from');
    url.searchParams.delete('date_to');
    url.searchParams.delete('free_only');
    url.searchParams.delete('filter');
    url.searchParams.delete('lat');
    url.searchParams.delete('lng');
    url.searchParams.delete('radius');

    // Aggiungi i nuovi parametri
    if (params.date_from) url.searchParams.set('date_from', params.date_from);
    if (params.date_to) url.searchParams.set('date_to', params.date_to);
    if (params.free_only) url.searchParams.set('free_only', params.free_only);
    if (params.filter) url.searchParams.set('filter', params.filter);
    if (params.lat) url.searchParams.set('lat', params.lat);
    if (params.lng) url.searchParams.set('lng', params.lng);
    if (params.radius) url.searchParams.set('radius', params.radius);

    // Aggiorna la pagina con i nuovi filtri
    window.location.href = url.toString();
}

function applyFilterToList(filterType) {
    const now = new Date();
    const params = {};

    switch(filterType) {
        case 'today':
            const today = now.toISOString().split('T')[0];
            params.date_from = today;
            params.date_to = today;
            break;

        case 'tomorrow':
            const tomorrow = new Date(now);
            tomorrow.setDate(tomorrow.getDate() + 1);
            const tomorrowStr = tomorrow.toISOString().split('T')[0];
            params.date_from = tomorrowStr;
            params.date_to = tomorrowStr;
            break;

        case 'weekend':
            const saturday = new Date(now);
            const sunday = new Date(now);
            const daysUntilSaturday = (6 - now.getDay()) % 7;
            saturday.setDate(now.getDate() + daysUntilSaturday);
            sunday.setDate(saturday.getDate() + 1);
            params.date_from = saturday.toISOString().split('T')[0];
            params.date_to = sunday.toISOString().split('T')[0];
            break;

        case 'free':
            params.free_only = '1';
            break;

        case 'nearby':
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    const form = document.getElementById('filterForm');
                    addHiddenInput(form, 'filter', 'nearby');
                    addHiddenInput(form, 'lat', position.coords.latitude);
                    addHiddenInput(form, 'lng', position.coords.longitude);
                    addHiddenInput(form, 'radius', '10');

                    // Crea i parametri per la mappa
                    const mapParams = {
                        filter: 'nearby',
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude,
                        radius: '10'
                    };

                    // Aggiorna la mappa
                    loadEventsOnMapWithFilter(mapParams);

                    // Aggiorna la lista
                    updateEventsList({
                        filter: 'nearby',
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                        radius: '10'
                    });
                });
                return;
            }
            break;

        case 'my':
            params.filter = 'my';
            break;

        case 'private':
            params.filter = 'my_private';
            break;
    }

    // Aggiorna solo la mappa con i nuovi filtri (senza posizione automatica)
    const mapParams = { ...params };

    console.log('Applying filter to map:', mapParams);
    loadEventsOnMapWithFilter(mapParams);

    // Aggiorna anche la lista ricaricando la pagina
    updateEventsList(params);
}

function applyFilterToMap(filterType) {
    const center = map.getCenter();
    const params = {
        latitude: center.lat,
        longitude: center.lng
    };

    const now = new Date();

    switch(filterType) {
        case 'today':
            params.date_from = now.toISOString().split('T')[0];
            params.date_to = now.toISOString().split('T')[0];
            console.log('Filter: Today');
            break;

        case 'tomorrow':
            const tomorrow = new Date(now);
            tomorrow.setDate(tomorrow.getDate() + 1);
            params.date_from = tomorrow.toISOString().split('T')[0];
            params.date_to = tomorrow.toISOString().split('T')[0];
            console.log('Filter: Tomorrow');
            break;

        case 'weekend':
            const saturday = new Date(now);
            const sunday = new Date(now);
            const daysUntilSaturday = (6 - now.getDay()) % 7;
            saturday.setDate(now.getDate() + daysUntilSaturday);
            sunday.setDate(saturday.getDate() + 1);
            params.date_from = saturday.toISOString().split('T')[0];
            params.date_to = sunday.toISOString().split('T')[0];
            console.log('Filter: Weekend');
            break;

        case 'free':
            params.free_only = '1';
            console.log('Filter: Free only');
            break;

        case 'nearby':
            params.filter = 'nearby';
            params.radius = '10';
            console.log('Filter: Nearby');
            break;

        case 'my':
            params.filter = 'my';
            console.log('Filter: My events');
            break;

        case 'private':
            params.filter = 'my_private';
            console.log('Filter: My private events');
            break;
    }

    console.log('Applying filter to map with params:', params);
    loadEventsOnMapWithFilter(params);
}

// Funzione per aggiungere campi nascosti al form
function addHiddenInput(form, name, value) {
    // Rimuovi input esistenti con lo stesso nome
    const existingInput = form.querySelector(`input[name="${name}"]`);
    if (existingInput) {
        existingInput.remove();
    }

    // Aggiungi nuovo input
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = name;
    input.value = value;
    form.appendChild(input);
}

// Function to open event details modal
function openEventDetailsModal(event) {
    const modalBody = document.getElementById('eventDetailsModalBody');
    const modalLink = document.getElementById('eventDetailsModalLink');

    // Create modal content with horizontal layout
    let modalContent = `
        <div class="row">
            <div class="col-md-4">
                <img src="${event.image_url || '/assets/images/events/default-event.jpg'}"
                     class="img-fluid rounded"
                     alt="${event.title}"
                     onerror="this.src='/assets/images/events/default-event.jpg'">
            </div>
            <div class="col-md-8">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <h4 class="mb-0">${event.title}</h4>
                    <span class="badge ${event.category_color_class} fs-6">${event.category_name || '{{ __('events.not_available') }}'}</span>
                </div>

                <div class="row mb-3">
                    <div class="col-12">
                        <i class="fas fa-calendar-alt text-primary me-2"></i>
                        <strong>Data e Ora:</strong> ${event.start_datetime}
                    </div>
                </div>
    `;

    if (event.is_online) {
        modalContent += `
                <div class="row mb-3">
                    <div class="col-12">
                        <i class="fas fa-globe text-success me-2"></i>
                        <strong class="text-success">{{ __('events.online_event_label') }}</strong>
                        ${event.timezone ? `<br><small class="text-muted">Fuso orario: ${event.timezone}</small>` : ''}
                    </div>
                </div>
        `;
    } else {
        modalContent += `
                <div class="row mb-3">
                    <div class="col-12">
                        <i class="fas fa-map-marker-alt text-danger me-2"></i>
                        <strong>{{ __('events.location_label') }}</strong> ${event.venue_name || '{{ __('events.not_available') }}'}
                        ${event.city ? `<br><small class="text-muted">${event.city}</small>` : ''}
                    </div>
                </div>
        `;
    }

    modalContent += `
                <div class="row mb-3">
                    <div class="col-12">
                        <i class="fas fa-user text-info me-2"></i>
                        <strong>{{ __('events.organizer_label') }}</strong> ${event.organizer}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-6">
                        <i class="fas fa-users text-warning me-2"></i>
                        <strong>{{ __('events.participants_label') }}</strong> ${event.max_participants || '{{ __('events.unlimited') }}'}
                    </div>
                    <div class="col-6">
                        <i class="fas fa-euro-sign text-success me-2"></i>
                        <strong>{{ __('events.price_label') }}</strong> ${event.entry_fee ? event.entry_fee + '€' : '{{ __('events.free_label') }}'}
                    </div>
                </div>
            </div>
        </div>
    `;

    modalBody.innerHTML = modalContent;
    modalLink.href = event.url;

    // Open the modal
    const modal = new bootstrap.Modal(document.getElementById('eventDetailsModal'));
    modal.show();
}

// Add event listeners for quick filters
document.addEventListener('DOMContentLoaded', function() {
    // Quick filter click handlers
    const quickFilters = document.querySelectorAll('[data-filter]');
    quickFilters.forEach(filter => {
        filter.addEventListener('click', function(e) {
            e.preventDefault();
            const filterType = this.getAttribute('data-filter');
            console.log('Quick filter clicked:', filterType);

            // Update form with quick filter
            const form = document.getElementById('filterForm');

            // For 'past' filter, set the main filter parameter
            if (filterType === 'past') {
                addHiddenInput(form, 'filter', filterType);
            } else {
                addHiddenInput(form, 'quick_filter', filterType);
            }

            // Submit form
            form.submit();
    });
});

    // Remove ALL auto-submit behavior
    const form = document.getElementById('filterForm');

    // Prevent form from auto-submitting on any input change
    form.addEventListener('submit', function(e) {
        // Only allow submit if it's the filter button or quick filter
        const submitter = e.submitter;
        if (!submitter || (submitter.type !== 'submit' && !submitter.hasAttribute('data-filter'))) {
            e.preventDefault();
            return false;
        }
    });

    // Remove any existing change/input listeners that might cause auto-submit
    const inputs = form.querySelectorAll('input, select');
    inputs.forEach(input => {
        // Clone the input to remove all event listeners
        const newInput = input.cloneNode(true);
        input.parentNode.replaceChild(newInput, input);
    });
});
</script>
@endpush
