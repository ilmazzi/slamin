@extends('layout.master')
@section('title', __('dashboard.dashboard') . ' - Slam In')

@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/fullcalendar/fullcalendar.bundle.css') }}">
@endsection

@section('main-content')
    <div class="container-fluid">

        <!-- User Welcome Card semplificata -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card hover-effect b-e-4-primary">

                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class=" mb-1 f-w-600">{{ __('dashboard.welcome', ['name' => $user->getDisplayName()]) }}</h4>
                                <p class="text-primary-50 mb-2 f-s-14">{{ $user->getName() }}</p>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($user->getRoleNames() as $role)
                                        <span class="badge bg-light-success text-dark f-s-12">
                                            {{ __('auth.role_' . $role) ?: ucfirst($role) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="bg-white-500 h-50 w-50 d-flex-center rounded-circle ms-auto">
                                    <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($user) }}"
                                         alt="{{ $user->getDisplayName() }}"
                                         class="rounded-circle"
                                         style="width: 90px; height: 90px; object-fit: cover;">
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
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="card-title mb-0 f-w-600">
                                <i class="ph ph-chart-bar me-2 text-primary"></i>{{ __('dashboard.statistics') }}
                            </h6>
                            <a href="{{ route('user-stats.index') }}" class="btn btn-sm btn-outline-primary">
                                <i class="ph ph-chart-line me-1"></i>{{ __('dashboard.view_detailed_stats') }}
                            </a>
                        </div>
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

                            <!-- Statistica 5 - Inviti ai Gruppi in Attesa -->
                            <div class="col-6">
                                <a href="{{ route('group-invitations.index') }}" class="text-decoration-none">
                                    <div class="card hover-effect equal-card b-t-4-primary">
                                        <div class="card-body eshop-cards text-center pa-15">
                                            <div class="bg-light-primary h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                                                <i class="ph ph-users f-s-18 text-primary"></i>
                                            </div>
                                            <span class="ripple-effect"></span>
                                            <div class="overflow-hidden">
                                                <h4 class="text-primary mb-1 f-w-600">{{ $stats['pending_group_invitations'] }}</h4>
                                                <p class="f-w-500 text-dark f-s-12 mb-1">{{ __('dashboard.group_invitations') }}</p>
                                                <span class="badge bg-light-primary f-s-10">{{ __('dashboard.groups') }}</span>
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

            <!-- Quick Actions dinamiche dal controller -->
            @foreach($quickActions as $action)
                <div class="col-6 col-md-4 col-lg-2 mb-3">
                    <a href="{{ $action['url'] }}" class="card quick-action-card h-100 text-decoration-none">
                        <div class="card-body text-center">
                            <div class="bg-light-{{ $action['color'] }} h-60 w-60 d-flex-center rounded-circle m-auto mb-3">
                                <i class="{{ $action['icon'] }} text-{{ $action['color'] }}" style="font-size: 28px;"></i>
                            </div>
                            <h6>{{ __('dashboard.' . $action['key']) }}</h6>
                            <small>{{ __('dashboard.' . $action['key'] . '_desc') }}</small>
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
                                                    <i class="ph ph-calendar me-1"></i>{{ $event->start_datetime ? $event->start_datetime->format('d/m/Y H:i') : 'Data non disponibile' }}
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

        <!-- Sezione Attività Completa -->
        <div class="row mt-4">
            <!-- Attività Recenti - A tutta larghezza -->
            <div class="col-12">
                <div class="card hover-effect equal-card">
                    <!-- Solo ribbon importante per novità -->
                    <div class="ribbon-top top-left ribbon-primary">
                        <i class="ph ph-sparkle f-s-12"></i>
                    </div>
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0 f-w-600">
                            <i class="ph ph-activity me-2 text-primary"></i>{{ __('dashboard.recent_activity') }}
                        </h6>
                        <div class="d-flex gap-2">
                            <button class="btn btn-light-primary btn-sm" id="refreshActivities">
                                <i class="ph ph-arrow-clockwise"></i>
                            </button>
                            <a href="{{ route('profile.activity') }}" class="btn btn-light-primary btn-sm">
                                <i class="ph ph-eye me-1"></i>{{ __('dashboard.view_all_activity') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body pa-20">
                        @if(count($recentActivity) > 0)
                            <div class="row g-3">
                                @foreach($recentActivity as $activity)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card hover-effect h-100 {{ $activity['url'] ? 'cursor-pointer activity-card-clickable' : '' }}"
                                             @if($activity['url']) data-url="{{ $activity['url'] }}" @endif>
                                            @if($activity['has_thumbnail'])
                                                <div class="position-relative">
                                                    <img src="{{ $activity['thumbnail'] }}" alt="{{ $activity['title'] }}"
                                                         class="card-img-top" style="height: 120px; object-fit: cover;">
                                                    <div class="position-absolute top-0 end-0 m-2">
                                                        <span class="badge bg-{{ $activity['content_type_color'] }} f-s-10">
                                                            {{ $activity['content_type'] }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="card-body pa-15">
                                                <div class="d-flex align-items-start mb-3">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="bg-light-{{ $activity['color'] }} h-40 w-40 d-flex-center rounded-circle">
                                                            <i class="{{ $activity['icon'] }} text-{{ $activity['color'] }} f-s-16"></i>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <h6 class="mb-1 fw-600 f-s-14 text-dark">
                                                            @if($activity['url'])
                                                                <span class="text-primary" style="border-bottom: 1px dotted #007bff;">
                                                                    {{ $activity['title'] }}
                                                                    <i class="ph ph-arrow-square-out f-s-12 ms-1"></i>
                                                                </span>
                                                            @else
                                                                {{ $activity['title'] }}
                                                            @endif
                                                        </h6>
                                                        <p class="mb-1 f-s-13 text-muted">
                                                            {{ $activity['message'] }}
                                                        </p>
                                                        <small class="text-muted f-s-12">
                                                            <i class="ph ph-clock me-1"></i>{{ $activity['time'] }}
                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    @if(!$activity['has_thumbnail'])
                                                        <span class="badge bg-{{ $activity['content_type_color'] }} f-s-11">
                                                            {{ $activity['content_type'] }}
                                                        </span>
                                                    @endif
                                                    <span class="badge bg-light-{{ $activity['color'] }} text-{{ $activity['color'] }} f-s-11">
                                                        {{ ucfirst($activity['action']) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="bg-light-primary h-80 w-80 d-flex-center rounded-circle m-auto mb-3">
                                    <i class="ph ph-activity f-s-32 text-primary"></i>
                                </div>
                                <h5 class="text-muted mb-2">{{ __('dashboard.no_recent_activity') }}</h5>
                                <p class="text-muted f-s-14 mb-0">{{ __('dashboard.start_activity_message') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

            <!-- Inviti in Sospeso -->
            @if(auth()->user()->eventInvitations()->where('status', 'pending')->count() > 0)
            <div class="col-lg-4">
                <div class="card hover-effect equal-card">
                    <div class="ribbon-top top-left ribbon-success">
                        <i class="ph ph-envelope f-s-12"></i>
                    </div>
                    <div class="card-header">
                        <h6 class="card-title mb-0 f-w-600">
                            <i class="ph ph-envelope me-2 text-success"></i>{{ __('dashboard.pending_invitations') }}
                        </h6>
                    </div>
                    <div class="card-body pa-20">
                        @foreach(auth()->user()->eventInvitations()->where('status', 'pending')->with('event')->take(3)->get() as $invitation)
                            <div class="d-flex align-items-center mb-3 pb-2 border-bottom">
                                                                    <div class="flex-shrink-0">
                                        <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($invitation->event->organizer) }}"
                                             alt="{{ $invitation->event->organizer->getDisplayName() }}"
                                             class="h-35 w-35 rounded-circle"
                                             style="object-fit: cover;">
                                    </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="mb-1 fw-500 f-s-14">
                                        <a href="{{ route('events.show', $invitation->event) }}" class="text-decoration-none hover-effect">
                                            {{ $invitation->event->title }}
                                        </a>
                                    </p>
                                    <small class="text-muted f-s-12">
                                        <i class="ph ph-calendar me-1"></i>{{ $invitation->event->start_datetime ? $invitation->event->start_datetime->format('d/m/Y H:i') : 'Data non disponibile' }}
                                    </small>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="d-flex gap-1">
                                        <form action="{{ route('event-invitations.accept', ['event' => $invitation->event, 'invitation' => $invitation->id]) }}" method="POST" class="d-inline invitation-form" data-invitation-id="{{ $invitation->id }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success btn-sm" title="{{ __('invitations.accept') }}">
                                                <i class="ph ph-check f-s-12"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('event-invitations.decline', ['event' => $invitation->event, 'invitation' => $invitation->id]) }}" method="POST" class="d-inline invitation-form" data-invitation-id="{{ $invitation->id }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-danger btn-sm" title="{{ __('invitations.decline') }}">
                                                <i class="ph ph-x f-s-12"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="text-center mt-3">
                            <a href="{{ route('notifications.index') }}" class="btn btn-light-success btn-sm">
                                <i class="ph ph-eye me-1"></i>{{ __('dashboard.view_all_invitations') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Inviti ai Gruppi in Sospeso -->
            @if(auth()->user()->groupInvitations()->where('status', 'pending')->count() > 0)
            <div class="col-lg-4">
                <div class="card hover-effect equal-card">
                    <div class="ribbon-top top-left ribbon-primary">
                        <i class="ph ph-users f-s-12"></i>
                    </div>
                    <div class="card-header">
                                                    <h6 class="card-title mb-0 f-w-600">
                                <i class="ph ph-users me-2 text-primary"></i>{{ __('dashboard.group_invitations') }}
                            </h6>
                    </div>
                    <div class="card-body pa-20">
                        @foreach(auth()->user()->groupInvitations()->where('status', 'pending')->with(['group', 'invitedBy'])->take(3)->get() as $invitation)
                            <div class="d-flex align-items-center mb-3 pb-2 border-bottom">
                                <div class="flex-shrink-0">
                                    @if($invitation->invitedBy)
                                        <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($invitation->invitedBy) }}"
                                             alt="{{ $invitation->invitedBy->getDisplayName() }}"
                                             class="h-35 w-35 rounded-circle"
                                             style="object-fit: cover;">
                                    @else
                                        <div class="bg-light-primary h-35 w-35 d-flex-center rounded-circle">
                                            <i class="ph ph-user text-primary f-s-14"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="mb-1 fw-500 f-s-14">
                                        <a href="{{ route('groups.show', $invitation->group) }}" class="text-decoration-none hover-effect">
                                            {{ $invitation->group->name }}
                                        </a>
                                    </p>
                                    <small class="text-muted f-s-12">
                                        <i class="ph ph-user me-1"></i>
                                        @if($invitation->invitedBy)
                                            <a href="{{ route('user.show', $invitation->invitedBy) }}" class="text-decoration-none hover-effect">
                                                {{ $invitation->invitedBy->getDisplayName() }}
                                            </a>
                                        @else
                                            {{ __('dashboard.user_not_found') }}
                                        @endif
                                    </small>
                                </div>
                                <div class="flex-shrink-0">
                                    <div class="d-flex gap-1">
                                        <form action="{{ route('group-invitations.accept', $invitation) }}" method="POST" class="d-inline group-invitation-form" data-invitation-id="{{ $invitation->id }}">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-sm" title="{{ __('dashboard.accept') }}">
                                                <i class="ph ph-check f-s-12"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('group-invitations.decline', $invitation) }}" method="POST" class="d-inline group-invitation-form" data-invitation-id="{{ $invitation->id }}">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm" title="{{ __('dashboard.decline') }}">
                                                <i class="ph ph-x f-s-12"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="text-center mt-3">
                            <a href="{{ route('group-invitations.index') }}" class="btn btn-light-primary btn-sm">
                                <i class="ph ph-eye me-1"></i>{{ __('dashboard.view_all_group_invitations') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif

        </div>


    </div>
@endsection

@push('scripts')
<script src="{{ asset('assets/vendor/fullcalendar/global.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestione inviti
    const invitationForms = document.querySelectorAll('.invitation-form');
    invitationForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const invitationId = this.getAttribute('data-invitation-id');
            const invitationRow = this.closest('.d-flex.align-items-center');

            // Nascondi immediatamente la riga dell'invito
            if (invitationRow) {
                invitationRow.style.opacity = '0.5';
                invitationRow.style.pointerEvents = 'none';
            }

            // Disabilita i pulsanti per evitare doppi click
            const buttons = this.querySelectorAll('button');
            buttons.forEach(button => {
                button.disabled = true;
                button.innerHTML = '<i class="ph ph-spinner ph-spin f-s-12"></i>';
            });
        });
    });

    // Calendar
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
                            title: data.in_wishlist ? '{{ __('dashboard.added_to_wishlist') }}' : '{{ __('dashboard.removed_from_wishlist') }}',
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
                        title: '{{ __('dashboard.error') }}',
                        text: '{{ __('dashboard.error_message') }}',
                        confirmButtonText: '{{ __('dashboard.ok') }}'
                    });
                }
            });
        }
    });

    // Gestione form inviti ai gruppi
    const groupInvitationForms = document.querySelectorAll('.group-invitation-form');
    groupInvitationForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const invitationId = this.getAttribute('data-invitation-id');
            const invitationRow = this.closest('.d-flex.align-items-center');

            // Disable form and show loading state
            if (invitationRow) {
                invitationRow.style.opacity = '0.5';
                invitationRow.style.pointerEvents = 'none';
            }

            // Submit form
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: new URLSearchParams(new FormData(this))
            })
            .then(response => response.json())
                        .then(data => {
                if (data.success) {
                    // Show success notification
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'success',
                            title: '{{ __('dashboard.success') }}',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            // If there's a redirect URL (for accept), redirect
                            if (data.redirect_url) {
                                window.location.href = data.redirect_url;
                            } else {
                                // Remove invitation row for decline
                                if (invitationRow) {
                                    invitationRow.style.transition = 'opacity 0.3s ease';
                                    invitationRow.style.opacity = '0';
                                    setTimeout(() => {
                                        invitationRow.remove();

                                        // Check if no more invitations
                                        const remainingInvitations = document.querySelectorAll('.group-invitation-form').length;
                                        if (remainingInvitations === 0) {
                                            // Hide the entire group invitations section
                                            const groupInvitationsSection = document.querySelector('.col-lg-4:has(.ribbon-primary)');
                                            if (groupInvitationsSection) {
                                                groupInvitationsSection.remove();
                                            }
                                        }
                                    }, 300);
                                }
                            }
                        });
                    } else {
                        // Fallback if Swal is not available
                        if (data.redirect_url) {
                            window.location.href = data.redirect_url;
                        } else {
                            location.reload();
                        }
                    }
                } else {
                    // Re-enable form
                    if (invitationRow) {
                        invitationRow.style.opacity = '1';
                        invitationRow.style.pointerEvents = 'auto';
                    }

                    // Show error notification
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: '{{ __('dashboard.error') }}',
                            text: data.message || '{{ __('dashboard.error_message') }}',
                            confirmButtonText: '{{ __('dashboard.ok') }}'
                        });
                    }
                }
            })
            .catch(error => {
                console.error('Error handling group invitation:', error);

                // Re-enable form
                if (invitationRow) {
                    invitationRow.style.opacity = '1';
                    invitationRow.style.pointerEvents = 'auto';
                }

                // Show error notification
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ __('dashboard.error') }}',
                        text: '{{ __('dashboard.error_message') }}',
                        confirmButtonText: '{{ __('dashboard.ok') }}'
                    });
                }
            });
        });
    });
});

// Gestione click sulle card delle attività
document.addEventListener('DOMContentLoaded', function() {
    const activityCards = document.querySelectorAll('.activity-card-clickable');

    activityCards.forEach(card => {
        card.addEventListener('click', function(e) {
            // Evita il click se si clicca su un link o un pulsante
            if (e.target.tagName === 'A' || e.target.tagName === 'BUTTON' || e.target.closest('a') || e.target.closest('button')) {
                return;
            }

            const url = this.getAttribute('data-url');
            if (url) {
                window.location.href = url;
            }
        });

        // Aggiungi effetto hover per feedback visivo
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-2px)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});

</script>

<style>
.activity-card-clickable {
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.activity-card-clickable:hover {
    box-shadow: 0 4px 12px rgba(0, 123, 255, 0.15);
    transform: translateY(-2px);
}

.activity-card-clickable .card-body {
    position: relative;
}

.activity-card-clickable::after {
    content: '';
    position: absolute;
    top: 10px;
    right: 10px;
    width: 8px;
    height: 8px;
    background-color: #007bff;
    border-radius: 50%;
    opacity: 0.6;
}
</style>
@endpush
