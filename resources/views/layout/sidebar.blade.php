<!-- Menu Navigation starts -->
<nav>
    <div class="app-logo">
        <a class="logo d-inline-block d-flex align-items-center" href="{{ route('dashboard') }}">
            <div class="d-flex align-items-center">
                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                    <i class="ph ph-microphone text-white fs-5"></i>
                </div>
                <div class="d-flex flex-column">
                    <span class="fw-bold text-primary" style="font-size: 16px; line-height: 1;">Slam</span>
                    <span class="fw-bold text-secondary" style="font-size: 14px; line-height: 1;">In</span>
                </div>
            </div>
        </a>

        <span class="bg-light-primary toggle-semi-nav d-flex-center">
            <i class="ti ti-chevron-right"></i>
        </span>

        @auth
        <div class="d-flex align-items-center nav-profile p-3">
            <span class="h-45 w-45 d-flex-center b-r-10 position-relative bg-primary m-auto">
                <span class="text-white fw-bold" style="font-size: 16px;">
                    {{ substr(auth()->user()->name, 0, 2) }}
                </span>
                <span class="position-absolute top-0 end-0 p-1 bg-success border border-light rounded-circle"></span>
            </span>
            <div class="flex-grow-1 ps-2">
                <h6 class="text-primary mb-0">{{ auth()->user()->name }}</h6>
                <p class="text-muted f-s-12 mb-0">
                    @if(auth()->user()->getRoleNames()->count() > 0)
                        {{ ucfirst(auth()->user()->getRoleNames()->first()) }}
                    @else
                        Poetry Slam User
                    @endif
                </p>
            </div>

            <div class="dropdown profile-menu-dropdown">
                <a aria-expanded="false" data-bs-auto-close="true" data-bs-placement="top" data-bs-toggle="dropdown" role="button">
                    <i class="ti ti-settings fs-5"></i>
                </a>
                <ul class="dropdown-menu">
                    <li class="dropdown-item">
                        <a class="f-w-500" href="{{ route('dashboard') }}">
                            <i class="ph ph-user-circle pe-1 f-s-20"></i> Dashboard
                        </a>
                    </li>

                    <li class="app-divider-v dotted py-1"></li>

                    <li class="dropdown-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link p-0 mb-0 text-danger f-w-500" style="text-decoration: none;">
                                <i class="ph ph-sign-out pe-1 f-s-20"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        @else
        <div class="d-flex align-items-center nav-profile p-3">
            <div class="text-center w-100">
                <a href="{{ route('login') }}" class="btn btn-primary btn-sm">
                    <i class="ph ph-sign-in me-1"></i> Accedi
                </a>
            </div>
        </div>
        @endauth
    </div>
    <div class="app-nav" id="app-simple-bar">
        <ul class="main-nav p-0 mt-2">
            @auth
            <!-- Dashboard -->
            <li class="menu-title">
                <span>{{ __('dashboard.dashboard') }}</span>
            </li>
            <li>
                <a href="{{ route('dashboard') }}">
                    <i class="ph ph-house"></i>
                    {{ __('dashboard.dashboard') }}
                </a>
            </li>

            <!-- Poetry Slam Events Section -->
            <li class="menu-title">
                <span>{{ __('events.events_poetry_slam') }}</span>
            </li>
            <li>
                <a aria-expanded="false" data-bs-toggle="collapse" href="#events">
                    <i class="ph ph-calendar-dots"></i>
                    {{ __('events.events') }}
                    @if(auth()->user()->organizedEvents()->where('start_datetime', '>', now())->count() > 0)
                        <span class="badge bg-primary badge-notification ms-2">
                            {{ auth()->user()->organizedEvents()->where('start_datetime', '>', now())->count() }}
                        </span>
                    @endif
                </a>
                <ul class="collapse" id="events">
                    <li><a href="{{ route('events.index') }}">
                        <i class="ph ph-calendar me-2"></i>{{ __('events.all_events') }}
                    </a></li>
                    @can('create', App\Models\Event::class)
                        <li><a href="{{ route('events.create') }}">
                            <i class="ph ph-plus me-2"></i>{{ __('events.create_event') }}
                        </a></li>
                    @endcan
                    <li><a href="{{ route('events.index', ['filter' => 'upcoming']) }}">
                        <i class="ph ph-clock me-2"></i>{{ __('events.upcoming_events') }}
                    </a></li>
                </ul>
            </li>

            <li>
                <a aria-expanded="false" data-bs-toggle="collapse" href="#invitations">
                    <i class="ph ph-envelope"></i>
                    {{ __('events.invitations') }} & {{ __('events.requests') }}
                    @php
                        $pendingInvitations = auth()->user()->receivedInvitations()->where('status', \App\Models\EventInvitation::STATUS_PENDING)->count();
                        $pendingRequests = \App\Models\EventRequest::whereHas('event', function($q) {
                            $q->where('organizer_id', auth()->id());
                        })->where('status', \App\Models\EventRequest::STATUS_PENDING)->count();
                        $pendingCount = $pendingInvitations + $pendingRequests;
                    @endphp
                    @if($pendingCount > 0)
                        <span class="badge bg-warning badge-notification ms-2">{{ $pendingCount }}</span>
                    @endif
                </a>
                <ul class="collapse" id="invitations">
                    <li><a href="{{ route('invitations.index') }}">
                        <i class="ph ph-envelope-open me-2"></i>I Miei Inviti
                    </a></li>
                    <li><a href="{{ route('requests.index') }}">
                        <i class="ph ph-hand-waving me-2"></i>Richieste Ricevute
                    </a></li>
                </ul>
            </li>

            <li>
                <a href="{{ route('notifications.index') }}">
                    <i class="ph ph-bell"></i>
                    Notifiche
                    @if(auth()->user()->notifications()->where('is_read', false)->count() > 0)
                        <span class="badge bg-danger badge-notification ms-2">
                            {{ auth()->user()->notifications()->where('is_read', false)->count() }}
                        </span>
                    @endif
                </a>
            </li>
            @else
            <!-- Guest Menu -->
            <li class="menu-title">
                <span>Poetry Slam</span>
            </li>
            <li>
                <a href="{{ route('events.index') }}">
                    <i class="ph ph-calendar"></i>
                    {{ __('events.events') }}
                </a>
            </li>
            <li>
                <a href="{{ route('login') }}">
                    <i class="ph ph-sign-in"></i>
                    Accedi
                </a>
            </li>
            @endauth
        </ul>
    </div>
</nav>
<!-- Menu Navigation ends -->
