@extends('layout.master')

@section('title', __('events.events_poetry_slam'))
@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/leafletmaps/leaflet.css') }}">
@endsection

@section('breadcrumb-title')
<h3>{{ __('events.events_poetry_slam') }}</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('events.dashboard') }}</a></li>
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

    <!-- Quick Navigation Links -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph ph-navigation-arrow me-2"></i>
                        {{ __('events.quick_navigation') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('events.index') }}" class="card card-light-primary hover-effect text-decoration-none">
                                <div class="card-body text-center py-3">
                                    <i class="ph-duotone ph-list f-s-30 text-primary mb-2"></i>
                                    <h6 class="mb-1">{{ __('events.all_events') }}</h6>
                                    <small class="text-muted">{{ __('events.view_all_events') }}</small>
                                </div>
                            </a>
                        </div>
                        @auth
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('events.index', ['filter' => 'my']) }}" class="card card-light-info hover-effect text-decoration-none">
                                <div class="card-body text-center py-3">
                                    <i class="ph-duotone ph-calendar f-s-30 text-info mb-2"></i>
                                    <h6 class="mb-1">{{ __('sidebar.my_events') }}</h6>
                                    <small class="text-muted">{{ __('events.view_my_events') }}</small>
                                </div>
                            </a>
                        </div>
                        @can('create', App\Models\Event::class)
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('events.create') }}" class="card card-light-success hover-effect text-decoration-none">
                                <div class="card-body text-center py-3">
                                    <i class="ph-duotone ph-plus-circle f-s-30 text-success mb-2"></i>
                                    <h6 class="mb-1">{{ __('events.create_event') }}</h6>
                                    <small class="text-muted">{{ __('events.create_new_event') }}</small>
                                </div>
                            </a>
                        </div>
                        @endcan
                        @endauth
                        <div class="col-lg-3 col-md-6">
                            <a href="{{ route('events.index', ['filter' => 'upcoming']) }}" class="card card-light-warning hover-effect text-decoration-none">
                                <div class="card-body text-center py-3">
                                    <i class="ph-duotone ph-clock f-s-30 text-warning mb-2"></i>
                                    <h6 class="mb-1">{{ __('events.upcoming_events') }}</h6>
                                    <small class="text-muted">{{ __('events.view_upcoming') }}</small>
                                </div>
                            </a>
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
                                    <option value="">{{ __('events.all_types') }}</option>
                                    <option value="public" {{ request('type') == 'public' ? 'selected' : '' }}>{{ __('events.public_events') }}</option>
                                    <option value="private" {{ request('type') == 'private' ? 'selected' : '' }}>{{ __('events.private_events') }}</option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-md-4">
                                <input type="number" name="radius" class="form-control"
                                       placeholder="{{ __('events.radius_km') }}" value="{{ request('radius', 50) }}" min="1" max="200">
                            </div>
                            <div class="col-lg-2 col-md-4">
                                <select name="type" class="form-select">
                                    <option value="">{{ __('events.all_events') }}</option>
                                    <option value="public" {{ request('type') === 'public' ? 'selected' : '' }}>{{ __('events.public_events_only') }}</option>
                                    <option value="private" {{ request('type') === 'private' ? 'selected' : '' }}>{{ __('events.private_events_only') }}</option>
                                </select>
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
                                        <i class="ph ph-calendar me-1"></i> {{ __('events.today') }}
                                    </span>
                                    <span class="bg-light-info rounded px-3 py-2" data-filter="tomorrow" style="cursor: pointer;">
                                        <i class="ph ph-calendar-plus me-1"></i> {{ __('events.tomorrow') }}
                                    </span>
                                    <span class="bg-light-success rounded px-3 py-2" data-filter="weekend" style="cursor: pointer;">
                                        <i class="ph ph-calendar-check me-1"></i> {{ __('events.weekend') }}
                                    </span>
                                    <span class="bg-light-warning rounded px-3 py-2" data-filter="free" style="cursor: pointer;">
                                        <i class="ph ph-currency-circle-dollar me-1"></i> {{ __('events.free_events') }}
                                    </span>
                                    <span class="bg-light-secondary rounded px-3 py-2" data-filter="nearby" style="cursor: pointer;">
                                        <i class="ph ph-map-pin me-1"></i> {{ __('events.nearby') }}
                                    </span>
                                    @auth
                                        <span class="bg-light-primary rounded px-3 py-2" data-filter="my" style="cursor: pointer;">
                                            <i class="ph ph-user me-1"></i> {{ __('events.my_events') }}
                                        </span>
                                        <span class="bg-light-warning rounded px-3 py-2" data-filter="private" style="cursor: pointer;">
                                            <i class="ph ph-lock me-1"></i> {{ __('events.my_private_events') }}
                                        </span>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Filter functionality -->
                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Quick filter functionality
                        document.querySelectorAll('[data-filter]').forEach(filter => {
                            filter.addEventListener('click', function() {
                                const filterType = this.dataset.filter;
                                applyQuickFilter(filterType);
                            });
                        });
                    });

                                        function applyQuickFilter(filterType) {
                        const mapContainer = document.getElementById('mapContainer');
                        const isMapVisible = mapContainer.style.display !== 'none';

                        if (isMapVisible) {
                            // Se la mappa è aperta, applica filtri alla mappa
                            applyFilterToMap(filterType);
                        } else {
                            // Se la lista è aperta, applica filtri alla lista
                            applyFilterToList(filterType);
                        }
                    }

                    function applyFilterToList(filterType) {
                        const form = document.getElementById('filterForm');
                        const now = new Date();

                        // Clear existing values
                        form.querySelector('[name="search"]').value = '';

                        switch(filterType) {
                            case 'today':
                                const today = now.toISOString().split('T')[0];
                                addHiddenInput(form, 'date_from', today);
                                addHiddenInput(form, 'date_to', today);
                                break;

                            case 'tomorrow':
                                const tomorrow = new Date(now);
                                tomorrow.setDate(tomorrow.getDate() + 1);
                                const tomorrowStr = tomorrow.toISOString().split('T')[0];
                                addHiddenInput(form, 'date_from', tomorrowStr);
                                addHiddenInput(form, 'date_to', tomorrowStr);
                                break;

                            case 'weekend':
                                const saturday = new Date(now);
                                const sunday = new Date(now);
                                const daysUntilSaturday = (6 - now.getDay()) % 7;
                                saturday.setDate(now.getDate() + daysUntilSaturday);
                                sunday.setDate(saturday.getDate() + 1);
                                addHiddenInput(form, 'date_from', saturday.toISOString().split('T')[0]);
                                addHiddenInput(form, 'date_to', sunday.toISOString().split('T')[0]);
                                break;

                            case 'free':
                                addHiddenInput(form, 'free_only', '1');
                                break;

                            case 'nearby':
                                if (navigator.geolocation) {
                                    navigator.geolocation.getCurrentPosition(function(position) {
                                        addHiddenInput(form, 'lat', position.coords.latitude);
                                        addHiddenInput(form, 'lng', position.coords.longitude);
                                        addHiddenInput(form, 'radius', '10');
                                        form.submit();
                                    });
                                    return;
                                }
                                break;
                            case 'my':
                                addHiddenInput(form, 'filter', 'my');
                                break;
                            case 'private':
                                addHiddenInput(form, 'filter', 'my_private');
                                break;
                        }

                        form.submit();
                    }

                    function applyFilterToMap(filterType) {
                        const center = map.getCenter();
                        const params = {
                            latitude: center.lat,
                            longitude: center.lng,
                            radius: 200
                        };

                        const now = new Date();

                        switch(filterType) {
                                                         case 'today':
                                 params.date_from = now.toISOString().split('T')[0];
                                 params.date_to = now.toISOString().split('T')[0];
                                 console.log('Today filter applied:', params.date_from);
                                 break;

                                                         case 'tomorrow':
                                 const tomorrow = new Date(now);
                                 tomorrow.setDate(tomorrow.getDate() + 1);
                                 params.date_from = tomorrow.toISOString().split('T')[0];
                                 params.date_to = tomorrow.toISOString().split('T')[0];
                                 console.log('Tomorrow filter applied:', params.date_from);
                                 break;

                                                         case 'weekend':
                                 const saturday = new Date(now);
                                 const sunday = new Date(now);
                                 const daysUntilSaturday = (6 - now.getDay()) % 7;
                                 saturday.setDate(now.getDate() + daysUntilSaturday);
                                 sunday.setDate(saturday.getDate() + 1);
                                 params.date_from = saturday.toISOString().split('T')[0];
                                 params.date_to = sunday.toISOString().split('T')[0];
                                 console.log('Weekend filter applied:', params.date_from, 'to', params.date_to);
                                 break;

                                                         case 'free':
                                 params.free_only = '1';
                                 console.log('Free filter applied');
                                 break;

                            case 'nearby':
                                params.radius = '10';
                                break;
                        }

                                                 console.log('Applying filter to map:', filterType, params);
                         loadEventsOnMapWithFilter(params);
                    }

                    function addHiddenInput(form, name, value) {
                        // Remove existing hidden input with same name
                        const existing = form.querySelector(`input[name="${name}"]`);
                        if (existing && existing.type === 'hidden') {
                            existing.remove();
                        }

                        // Add new hidden input
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = name;
                        input.value = value;
                        form.appendChild(input);
                    }
                    </script>
                </div>
            </div>
        </div>
    </div>

    <!-- Map Container (Hidden by default) -->
    <div class="row mb-4" id="mapContainer" style="display: none;">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-0">
                    <div id="eventsMap" style="height: 500px; border-radius: 10px; overflow: hidden; position: relative;">
                    <div class="map-controls position-absolute top-0 end-0 p-3" style="z-index: 1000;">
                        <button class="btn btn-light btn-sm mb-2 d-block" onclick="centerOnUser()" title="Centra sulla mia posizione (richiede HTTPS)">
                            <i class="ph ph-crosshairs"></i>
                        </button>
                        <button class="btn btn-light btn-sm mb-2 d-block" onclick="refreshEvents()" title="Aggiorna eventi">
                            <i class="ph ph-arrow-clockwise"></i>
                        </button>
                        <button class="btn btn-light btn-sm d-block" onclick="showAllEvents()" title="Mostra tutti gli eventi">
                            <i class="ph ph-globe"></i>
                        </button>
                    </div>
                </div>
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
                    <div class="position-absolute top-0 end-0 m-3" style="z-index: 3;">
                        @if($event->is_public)
                            <span class="badge bg-success">{{ __('events.public') }}</span>
                        @else
                            <span class="badge bg-warning">{{ __('events.private') }}</span>
                        @endif

                        @if($event->acceptsRequests())
                            <span class="badge bg-info ms-1" data-bs-toggle="tooltip" data-bs-placement="left" title="{{ __('events.apply_to_event') }}">
                                <i class="ph ph-hand-waving me-1"></i>{{ __('events.apply') }}
                            </span>
                        @endif
                        
                        <!-- Category Badge -->
                        @if($event->category)
                            <span class="badge {{ $event->category_color_class }} ms-1" data-bs-toggle="tooltip" data-bs-placement="left" title="{{ __('events.category') }}">
                                {{ __('events.category_' . $event->category) }}
                            </span>
                        @endif
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

                                                <!-- Participants Preview -->
                        @php
                            $acceptedInvitations = $event->invitations->where('status', 'accepted');
                            $acceptedRequests = $event->requests->where('status', 'accepted');
                            $totalConfirmed = $acceptedInvitations->count() + $acceptedRequests->count();
                            $maxParticipants = $event->max_participants;
                            $spotsLeft = $maxParticipants ? $maxParticipants - $totalConfirmed : null;
                        @endphp

                        <div class="mb-3">
                            <div class="d-flex align-items-center justify-content-between mb-2">
                                <small class="text-muted fw-semibold" data-bs-toggle="tooltip" data-bs-placement="top"
                                       title="{{ __('events.participants_preview') }}">
                                    <i class="ph ph-users me-1"></i>{{ __('events.participants') }}
                                </small>
                                <div class="d-flex align-items-center gap-1">
                                    @if($maxParticipants)
                                        <span class="badge bg-primary">{{ $totalConfirmed }}/{{ $maxParticipants }}</span>
                                        @if($spotsLeft > 0)
                                            <span class="badge bg-success" style="font-size: 10px;">{{ __('events.participants_spots_left') }}: {{ $spotsLeft }}</span>
                                        @elseif($spotsLeft === 0)
                                            <span class="badge bg-warning" style="font-size: 10px;">{{ __('events.participants_full') }}</span>
                                        @endif
                                    @else
                                        <span class="badge bg-success">{{ $totalConfirmed }}</span>
                                    @endif
                                </div>
                            </div>

                            @if($totalConfirmed > 0)
                                <!-- Show first 3 participants -->
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($acceptedInvitations->take(3) as $invitation)
                                        <div class="d-flex align-items-center bg-light-success rounded px-2 py-1" style="font-size: 11px;">
                                            <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-1" style="width: 16px; height: 16px; font-size: 8px; font-weight: bold;">
                                                {{ substr($invitation->invitedUser->getDisplayName(), 0, 1) }}
                                            </div>
                                            <span class="text-success fw-semibold">{{ ucfirst($invitation->role) }}</span>
                                        </div>
                                    @endforeach

                                    @foreach($acceptedRequests->take(3 - $acceptedInvitations->count()) as $request)
                                        <div class="d-flex align-items-center bg-light-success rounded px-2 py-1" style="font-size: 11px;">
                                            <div class="rounded-circle bg-success text-white d-flex align-items-center justify-content-center me-1" style="width: 16px; height: 16px; font-size: 8px; font-weight: bold;">
                                                {{ substr($request->user->getDisplayName(), 0, 1) }}
                                            </div>
                                            <span class="text-success fw-semibold">{{ ucfirst($request->requested_role) }}</span>
                                        </div>
                                    @endforeach

                                    @if($totalConfirmed > 3)
                                        <span class="badge bg-light text-muted" style="font-size: 10px;">+{{ $totalConfirmed - 3 }}</span>
                                    @endif
                                </div>

                                <!-- Role summary -->
                                @php
                                    $roleStats = collect();
                                    foreach($acceptedInvitations as $inv) {
                                        $roleStats->put($inv->role, $roleStats->get($inv->role, 0) + 1);
                                    }
                                    foreach($acceptedRequests as $req) {
                                        $roleStats->put($req->requested_role, $roleStats->get($req->requested_role, 0) + 1);
                                    }
                                @endphp
                                @if($roleStats->count() > 0)
                                    <div class="mt-2">
                                        <small class="text-muted">{{ __('events.participants_roles_summary') }}:</small>
                                        <div class="d-flex flex-wrap gap-1 mt-1">
                                            @foreach($roleStats->take(3) as $role => $count)
                                                <span class="badge bg-light-primary" style="font-size: 9px;">{{ ucfirst($role) }}: {{ $count }}</span>
                                            @endforeach
                                            @if($roleStats->count() > 3)
                                                <span class="badge bg-light text-muted" style="font-size: 9px;">+{{ $roleStats->count() - 3 }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @else
                                <small class="text-muted">{{ __('events.no_participants') }}</small>
                                @if($event->acceptsRequests())
                                    <div class="mt-1">
                                        <small class="text-success fw-semibold">
                                            <i class="ph ph-hand-waving me-1"></i>{{ __('events.participants_accepting_applications') }}
                                        </small>
                                    </div>
                                @endif
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
                                            <button class="btn btn-light-success btn-sm w-100" data-bs-toggle="modal" data-bs-target="#applyModal" data-event-id="{{ $event->id }}"
                                                    data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('events.participants_click_to_apply') }}">
                                                <i class="ph ph-hand-waving me-1"></i>{{ __('events.apply') }}
                                            </button>
                                        @else
                                            <button class="btn btn-light-secondary btn-sm w-100" disabled>
                                                <i class="ph ph-check"></i>
                                            </button>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-light-primary btn-sm w-100">
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
// Traduzioni JavaScript
const translations = {
    show_map: '{{ __('events.show_map') }}',
    show_list: '{{ __('events.show_list') }}',
};
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
            this.innerHTML = `<i class="ph ph-list me-1"></i>${translations.show_list}`;
            initMap();
        } else {
            mapContainer.style.display = 'none';
            eventsGrid.style.display = 'flex';
            this.innerHTML = `<i class="ph ph-map-pin me-1"></i>${translations.show_map}`;
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

    // Portfolio Links functionality
    const addPortfolioLinkBtn = document.getElementById('addPortfolioLink');
    if (addPortfolioLinkBtn) {
        addPortfolioLinkBtn.addEventListener('click', function() {
            const container = document.getElementById('portfolioLinks');
            const input = document.createElement('input');
            input.type = 'url';
            input.name = 'portfolio_links[]';
            input.className = 'form-control mb-2';
            input.placeholder = 'https://youtube.com/watch?v=... o https://instagram.com/...';
            container.appendChild(input);
        });
    }

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

    // Inizializza mappa con controlli di zoom
    map = L.map('eventsMap', {
        zoomControl: true,
        scrollWheelZoom: true,
        doubleClickZoom: true,
        boxZoom: true,
        keyboard: true
    }).setView([41.9028, 12.4964], 6); // Default: centro Italia

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 18
    }).addTo(map);

    // Prova a ottenere la posizione dell'utente
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(
            function(position) {
                const userLat = position.coords.latitude;
                const userLng = position.coords.longitude;

                // Centra la mappa sulla posizione dell'utente
                map.setView([userLat, userLng], 12);

                // Aggiungi un marker per la posizione dell'utente
                L.marker([userLat, userLng], {
                    icon: L.divIcon({
                        className: 'user-location-marker',
                        html: '<div style="background: #007bff; width: 12px; height: 12px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 10px rgba(0,123,255,0.5);"></div>',
                        iconSize: [18, 18],
                        iconAnchor: [9, 9]
                    })
                }).addTo(map).bindPopup('La tua posizione');

                // Carica eventi vicino alla posizione dell'utente
                loadEventsOnMap(userLat, userLng);
            },
            function(error) {
                console.warn('Geolocation error:', error);
                let message = '';
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        message = 'Geolocalizzazione negata. Puoi attivarla nelle impostazioni del browser.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        message = 'Posizione non disponibile.';
                        break;
                    case error.TIMEOUT:
                        message = 'Timeout nella richiesta di geolocalizzazione.';
                        break;
                    default:
                        message = 'Geolocalizzazione non disponibile (richiede HTTPS). Mostra eventi di default.';
                        break;
                }
                showNotification(message, 'info');
                // Fallback: usa coordinate Italia centrale
                map.setView([41.9028, 12.4964], 6);
                loadEventsOnMap(41.9028, 12.4964);
            },
            {
                enableHighAccuracy: true,
                timeout: 10000,
                maximumAge: 300000 // 5 minuti
            }
        );
    } else {
        console.warn('Geolocation not supported');
        showNotification('Il tuo browser non supporta la geolocalizzazione.', 'info');
        // Fallback: usa coordinate Italia centrale
        map.setView([41.9028, 12.4964], 6);
        loadEventsOnMap(41.9028, 12.4964);
    }
}

function loadEventsOnMap(lat = 45.59614070, lng = 8.91219860) {
    loadEventsOnMapWithFilter({
        latitude: lat,
        longitude: lng,
        radius: 200
    });
}

function loadEventsOnMapWithFilter(params) {
    // Pulisci markers esistenti
    markers.forEach(marker => map.removeLayer(marker));
    markers = [];

    fetch('/api/events/near?' + new URLSearchParams(params))
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(events => {
        events.forEach(event => {
            if (event.latitude && event.longitude) {
                const marker = L.marker([event.latitude, event.longitude])
                    .addTo(map)
                    .bindPopup(`
                        <div class="p-2">
                            <h6>${event.title}</h6>
                            ${event.category ? `<span class="badge ${event.category_color_class} mb-2">${event.category_name}</span>` : ''}
                            <p class="mb-1"><i class="ph ph-calendar me-1"></i>${event.start_datetime}</p>
                            <p class="mb-2"><i class="ph ph-map-pin me-1"></i>${event.venue_name}, ${event.city}</p>
                            <small class="text-muted d-block">Organizzato da: ${event.organizer}</small>
                            <a href="${event.url}" class="btn btn-primary btn-sm mt-2">Vedi Dettagli</a>
                        </div>
                    `);
                markers.push(marker);
            }
        });

        // Mostra notifica con numero di eventi trovati
        const filterInfo = Object.keys(params).length > 3 ? ' filtrati' : '';
        showNotification(`${events.length} eventi${filterInfo} trovati`, 'success');
    })
    .catch(error => {
        console.error('Error loading events on map:', error);
        showNotification('Errore nel caricamento degli eventi sulla mappa', 'error');
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
                showNotification('Mappa centrata sulla tua posizione', 'success');
            },
            function(error) {
                let message = error.code === 1 ?
                    'Geolocalizzazione richiede HTTPS. Usa il bottone refresh per eventi nell\'area corrente.' :
                    'Impossibile ottenere la tua posizione';
                showNotification(message, 'warning');
            }
        );
    }
}

// Funzione per aggiornare gli eventi
function refreshEvents() {
    const center = map.getCenter();
    loadEventsOnMap(center.lat, center.lng);
    showNotification('Eventi aggiornati', 'success');
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
                                    <p class="mb-2"><i class="ph ph-map-pin me-1"></i>${event.venue_name}, ${event.city}</p>
                                    <a href="/events/${event.id}" class="btn btn-primary btn-sm mt-2">Vedi Dettagli</a>
                                </div>
                            `);
                    }
                                        });
                        showNotification(`Mostrati ${data.events.length} eventi`, 'success');

                        // Centra la mappa se ci sono eventi
                        if (data.events.length > 0) {
                            const firstEvent = data.events[0];
                            map.setView([parseFloat(firstEvent.latitude), parseFloat(firstEvent.longitude)], 10);
                        }
            }
        })
        .catch(error => {
            console.error('Error loading all events:', error);
            showNotification('Errore nel caricamento degli eventi', 'error');
        });
}

@auth
// Handle apply modal events
document.addEventListener('DOMContentLoaded', function() {
    const applyModal = document.getElementById('applyModal');
    if (applyModal) {
        applyModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const eventId = button.getAttribute('data-event-id');

            if (eventId) {
                // Set the event ID in the form
                const form = document.getElementById('applyForm');
                form.setAttribute('action', `/events/${eventId}/apply`);
                form.dataset.eventId = eventId;

                // Update event details in modal
                const eventCard = button.closest('.card');
                const eventTitle = eventCard.querySelector('.card-title a').textContent;
                const eventTime = eventCard.querySelector('.d-flex.align-items-center.text-muted').textContent;
                const eventLocation = eventCard.querySelector('.text-white h6').textContent;
                const eventCity = eventCard.querySelector('.text-white-50').textContent;

                const eventDetails = document.getElementById('eventDetails');
                if (eventDetails) {
                    eventDetails.innerHTML = `
                        <div class="alert alert-info mb-4">
                            <div class="d-flex align-items-center">
                                <i class="ph ph-info-circle me-3 fs-4"></i>
                                <div>
                                    <h6 class="mb-1">${eventTitle}</h6>
                                    <p class="mb-0 small">
                                        <i class="ph ph-calendar me-1"></i>${eventTime}<br>
                                        <i class="ph ph-map-pin me-1"></i>${eventLocation}, ${eventCity}
                                    </p>
                                </div>
                            </div>
                        </div>
                    `;
                }
            }
        });
    }

    // Handle form submission
    const applyForm = document.getElementById('applyForm');
    if (applyForm) {
        applyForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const eventId = this.dataset.eventId;

            // Validate form
            const message = formData.get('message');
            const role = formData.get('requested_role');

            if (!role) {
                showNotification('Seleziona un ruolo per continuare', 'error');
                return;
            }

            if (!message || message.trim().length < 10) {
                showNotification('Il messaggio deve contenere almeno 10 caratteri', 'error');
                return;
            }

            // Disable submit button
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="ph ph-spinner ph-spin me-2"></i>Invio in corso...';

            // Submit form
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text();
            })
            .then(data => {
                // Hide modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('applyModal'));
                modal.hide();

                // Show success message
                showNotification('Richiesta inviata con successo!', 'success');

                // Reset form
                this.reset();

                // Reload page after a short delay to show updated participant count
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            })
            .catch(error => {
                console.error('Error submitting application:', error);
                showNotification('Errore nell\'invio della richiesta. Riprova.', 'error');
            })
            .finally(() => {
                // Re-enable submit button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });
    }
});
@endauth

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

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
