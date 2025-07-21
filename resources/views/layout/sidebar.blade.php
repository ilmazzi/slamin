<!-- Menu Navigation starts -->
<nav>
    <div class="app-logo">
        <a class="logo d-inline-block" href="{{ route('dashboard') }}">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Slam In Logo" style="width: 200px; height: 200px; object-fit: contain;">
        </a>

        <span class="bg-light-primary toggle-semi-nav d-flex-center">
            <i class="ti ti-chevron-right"></i>
        </span>

        @auth
        <div class="d-flex align-items-center nav-profile p-3">
            <span class="h-45 w-45 d-flex-center b-r-10 position-relative bg-primary m-auto">
                <span class="text-white fw-bold" style="font-size: 16px;">
                    {{ substr(auth()->user()->getDisplayName(), 0, 2) }}
                </span>
                <span class="position-absolute top-0 end-0 p-1 bg-success border border-light rounded-circle"></span>
            </span>
            <div class="flex-grow-1 ps-2">
                <h6 class="text-primary mb-0">{{ auth()->user()->getDisplayName() }}</h6>
                <p class="text-muted f-s-12 mb-0">
                    @if(auth()->user()->getRoleNames()->count() > 0)
                        {{ ucfirst(auth()->user()->getRoleNames()->first()) }}
                    @else
                        {{ __('sidebar.slam_in_user') }}
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
                            <i class="ph ph-user-circle pe-1 f-s-20"></i> {{ __('sidebar.dashboard') }}
                        </a>
                    </li>

                    <li class="dropdown-item">
                        <a class="f-w-500" href="{{ route('profile.show') }}">
                            <i class="ph ph-user pe-1 f-s-20"></i> Il Mio Profilo
                        </a>
                    </li>

                    <li class="dropdown-item">
                        <a class="f-w-500" href="{{ route('profile.edit') }}">
                            <i class="ph ph-user-edit pe-1 f-s-20"></i> Modifica Profilo
                        </a>
                    </li>

                    <li class="app-divider-v dotted py-1"></li>

                    <li class="dropdown-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link p-0 mb-0 text-danger f-w-500" style="text-decoration: none;">
                                <i class="ph ph-sign-out pe-1 f-s-20"></i> {{ __('sidebar.logout') }}
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
                    <i class="ph ph-sign-in me-1"></i> {{ __('sidebar.login') }}
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
            <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}">
                    <i class="ph ph-house"></i>
                    {{ __('dashboard.dashboard') }}
                </a>
            </li>

            <!-- Poetry Slam Events Section -->
            <li class="menu-title">
                <span>{{ __('events.events_poetry_slam') }}</span>
            </li>
            <li class="{{ request()->routeIs('events.*') ? 'active' : '' }}">
                <a aria-expanded="{{ request()->routeIs('events.*') ? 'true' : 'false' }}" data-bs-toggle="collapse" href="#events">
                    <i class="ph ph-calendar-dots"></i>
                    {{ __('events.events') }}
                    @if(auth()->user()->organizedEvents()->where('start_datetime', '>', now())->count() > 0)
                        <span class="badge bg-primary badge-notification ms-2">
                            {{ auth()->user()->organizedEvents()->where('start_datetime', '>', now())->count() }}
                        </span>
                    @endif
                </a>
                <ul class="collapse {{ request()->routeIs('events.*') ? 'show' : '' }}" id="events">
                                        @php
                        $currentFilter = request()->get('filter');
                        $isAllEvents = request()->routeIs('events.index') && ($currentFilter === null || $currentFilter === '');
                        $isMyEvents = request()->routeIs('events.index') && $currentFilter === 'my';
                        $isUpcomingEvents = request()->routeIs('events.index') && $currentFilter === 'upcoming';
                        $isCreateEvent = request()->routeIs('events.create');
                    @endphp
                    <li class="{{ $isAllEvents ? 'active' : '' }}">
                        <a href="{{ route('events.index') }}">
                            <i class="ph ph-calendar me-2"></i>{{ __('events.all_events') }}
                        </a>
                    </li>
                    <li class="{{ $isMyEvents ? 'active' : '' }}">
                        <a href="{{ route('events.index', ['filter' => 'my']) }}">
                            <i class="ph ph-user me-2"></i>{{ __('sidebar.my_events') }}
                        </a>
                    </li>
                    @can('create', App\Models\Event::class)
                        <li class="{{ $isCreateEvent ? 'active' : '' }}">
                            <a href="{{ route('events.create') }}">
                                <i class="ph ph-plus me-2"></i>{{ __('events.create_event') }}
                            </a>
                        </li>
                    @endcan
                    <li class="{{ $isUpcomingEvents ? 'active' : '' }}">
                        <a href="{{ route('events.index', ['filter' => 'upcoming']) }}">
                            <i class="ph ph-clock me-2"></i>{{ __('events.upcoming_events') }}
                        </a>
                    </li>
                </ul>
            </li>

            <li class="{{ request()->routeIs('invitations.*') || request()->routeIs('requests.*') ? 'active' : '' }}">
                <a aria-expanded="{{ request()->routeIs('invitations.*') || request()->routeIs('requests.*') ? 'true' : 'false' }}" data-bs-toggle="collapse" href="#invitations">
                    <i class="ph ph-envelope"></i>
                    {{ __('sidebar.invitations_and_requests') }}
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
                <ul class="collapse {{ request()->routeIs('invitations.*') || request()->routeIs('requests.*') ? 'show' : '' }}" id="invitations">
                    <li class="{{ request()->routeIs('invitations.index') ? 'active' : '' }}">
                        <a href="{{ route('invitations.index') }}">
                            <i class="ph ph-envelope-open me-2"></i>{{ __('sidebar.my_invitations') }}
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('requests.index') ? 'active' : '' }}">
                        <a href="{{ route('requests.index') }}">
                            <i class="ph ph-hand-waving me-2"></i>{{ __('sidebar.received_requests') }}
                        </a>
                    </li>
                </ul>
            </li>

            <li class="{{ request()->routeIs('notifications.index') ? 'active' : '' }}">
                <a href="{{ route('notifications.index') }}">
                    <i class="ph ph-bell"></i>
                    {{ __('sidebar.notifications') }}
                    @if(auth()->user()->notifications()->where('is_read', false)->count() > 0)
                        <span class="badge bg-danger badge-notification ms-2">
                            {{ auth()->user()->notifications()->where('is_read', false)->count() }}
                        </span>
                    @endif
                </a>
            </li>

            <!-- Profile Section -->
            <li class="menu-title">
                <span>Profilo</span>
            </li>
            <li class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <a aria-expanded="{{ request()->routeIs('profile.*') ? 'true' : 'false' }}" data-bs-toggle="collapse" href="#profile">
                    <i class="ph ph-user-circle"></i>
                    Il Mio Profilo
                </a>
                <ul class="collapse {{ request()->routeIs('profile.*') ? 'show' : '' }}" id="profile">
                    <li class="{{ request()->routeIs('profile.show') ? 'active' : '' }}">
                        <a href="{{ route('profile.show') }}">
                            <i class="ph ph-user me-2"></i>Visualizza Profilo
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                        <a href="{{ route('profile.edit') }}">
                            <i class="ph ph-user-edit me-2"></i>Modifica Profilo
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('profile.videos') ? 'active' : '' }}">
                        <a href="{{ route('profile.videos') }}">
                            <i class="ph ph-video-camera me-2"></i>I Miei Video
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('profile.activity') ? 'active' : '' }}">
                        <a href="{{ route('profile.activity') }}">
                            <i class="ph ph-activity me-2"></i>Le Mie Attività
                        </a>
                    </li>
                </ul>
            </li>

            @if(auth()->user()->hasRole(['admin', 'moderator']))
            <!-- Permissions Management Section -->
            <li class="menu-title">
                <span>Amministrazione</span>
            </li>
            <li class="{{ request()->routeIs('permissions.*') ? 'active' : '' }}">
                <a aria-expanded="{{ request()->routeIs('permissions.*') ? 'true' : 'false' }}" data-bs-toggle="collapse" href="#permissions">
                    <i class="ph ph-shield-check"></i>
                    Gestione Permessi
                </a>
                <ul class="collapse {{ request()->routeIs('permissions.*') ? 'show' : '' }}" id="permissions">
                    <li class="{{ request()->routeIs('permissions.index') ? 'active' : '' }}">
                        <a href="{{ route('permissions.index') }}">
                            <i class="ph ph-chart-line me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('permissions.roles') ? 'active' : '' }}">
                        <a href="{{ route('permissions.roles') }}">
                            <i class="ph ph-users me-2"></i>Ruoli
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('permissions.permissions') ? 'active' : '' }}">
                        <a href="{{ route('permissions.permissions') }}">
                            <i class="ph ph-shield me-2"></i>Permessi
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('permissions.users') ? 'active' : '' }}">
                        <a href="{{ route('permissions.users') }}">
                            <i class="ph ph-user-circle me-2"></i>Utenti
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Carousel Management -->
            <li class="{{ request()->routeIs('admin.carousels.*') ? 'active' : '' }}">
                <a href="{{ route('admin.carousels.index') }}">
                    <i class="ph ph-images"></i>
                    Gestione Carosello
                </a>
            </li>
            @endif
            @else
            <!-- Guest Menu -->
            <li class="menu-title">
                <span>{{ __('sidebar.guest_menu_title') }}</span>
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
                    {{ __('sidebar.login') }}
                </a>
            </li>
            @endauth
        </ul>
    </div>
</nav>
<!-- Menu Navigation ends -->
