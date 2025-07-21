<!-- Menu Navigation starts -->
<nav class="vertical-sidebar">
    <div class="app-logo">
        <a class="logo d-inline-block" href="{{ route('dashboard') }}">
            <img alt="Poetry Slam" src="{{ asset('../assets/images/logo.png') }}" class="w-75">
        </a>

        <span class="bg-light-primary toggle-semi-nav d-flex-center">
            <i class="ti ti-chevron-right"></i>
        </span>

        @auth
        <div class="d-flex align-items-center nav-profile p-3">
            <span class="h-45 w-45 d-flex-center b-r-10 position-relative bg-primary m-auto">
                @if(auth()->user()->profile_photo)
                    <img alt="avatar" class="img-fluid b-r-10" src="{{ auth()->user()->profile_photo_url }}">
                @else
                    <span class="text-white fw-bold" style="font-size: 16px;">
                        {{ substr(auth()->user()->getDisplayName(), 0, 2) }}
                    </span>
                @endif
                <span class="position-absolute top-0 end-0 p-1 bg-success border border-light rounded-circle"></span>
            </span>
            <div class="flex-grow-1 ps-2">
                <h6 class="text-primary mb-0 text-truncate" style="max-width: 150px;">{{ auth()->user()->getDisplayName() }}</h6>
                <p class="text-muted f-s-12 mb-0 text-truncate" style="max-width: 150px;">
                    @if(auth()->user()->getRoleNames()->count() > 0)
                        @php
                            $role = auth()->user()->getRoleNames()->first();
                            $roleDisplay = match($role) {
                                'admin' => 'Amministratore',
                                'moderatore' => 'Moderatore',
                                'organizzatore' => 'Organizzatore',
                                'poeta' => 'Poeta',
                                'giudice' => 'Giudice',
                                'spettatore' => 'Spettatore',
                                default => ucfirst($role)
                            };
                        @endphp
                        {{ $roleDisplay }}
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
                        <a class="f-w-500" href="{{ route('profile.show') }}">
                            <i class="ph-duotone ph-user-circle pe-1 f-s-20"></i> Il Mio Profilo
                        </a>
                    </li>
                    <li class="dropdown-item">
                        <a class="f-w-500" href="{{ route('profile.edit') }}">
                            <i class="ph-duotone ph-gear pe-1 f-s-20"></i> Impostazioni
                        </a>
                    </li>
                    <li class="dropdown-item">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <a class="f-w-500" href="#">
                                    <i class="ph-duotone ph-detective pe-1 f-s-20"></i> Modalità Privata
                                </a>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="form-check form-switch">
                                    <input class="form-check-input form-check-primary" id="incognitoSwitch" type="checkbox">
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="dropdown-item">
                        <a class="mb-0 text-secondary f-w-500" href="{{ route('register') }}">
                            <i class="ph-bold ph-plus pe-1 f-s-20"></i> Aggiungi Account
                        </a>
                    </li>

                    <li class="app-divider-v dotted py-1"></li>

                    <li class="dropdown-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link p-0 mb-0 text-danger f-w-500" style="text-decoration: none;">
                                <i class="ph-duotone ph-sign-out pe-1 f-s-20"></i> {{ __('sidebar.logout') }}
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
    <div class="app-nav simplebar-scrollable-y" id="app-simple-bar" data-simplebar="init">
        <div class="simplebar-wrapper" style="margin: 0px;">
            <div class="simplebar-height-auto-observer-wrapper">
                <div class="simplebar-height-auto-observer"></div>
            </div>
            <div class="simplebar-mask">
                <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                    <div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content" style="height: 100%; overflow: hidden scroll;">
                        <div class="simplebar-content" style="padding: 0px;">
                            <ul class="main-nav p-0 mt-2" style="margin-left: 0px;">
                                @auth
                                <li class="menu-title">
                                    <span>{{ __('dashboard.dashboard') }}</span>
                                </li>
                                <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                    <a href="{{ route('dashboard') }}">
                                        <svg stroke="currentColor" stroke-width="1.5">
                                            <use xlink:href="../assets/svg/_sprite.svg#home"></use>
                                        </svg>
                                        {{ __('dashboard.dashboard') }}
                                    </a>
                                </li>

                                <!-- Poetry Slam Events Section -->
                                <li class="menu-title">
                                    <span>{{ __('events.events_poetry_slam') }}</span>
                                </li>
                                <li class="{{ request()->routeIs('events.*') ? 'active' : '' }}">
                                    <a aria-expanded="{{ request()->routeIs('events.*') ? 'true' : 'false' }}" data-bs-toggle="collapse" href="#events">
                                        <svg stroke="currentColor" stroke-width="1.5">
                                            <use xlink:href="../assets/svg/_sprite.svg#stack"></use>
                                        </svg>
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
                                            <a href="{{ route('events.index') }}">{{ __('events.all_events') }}</a>
                                        </li>
                                        <li class="{{ $isMyEvents ? 'active' : '' }}">
                                            <a href="{{ route('events.index', ['filter' => 'my']) }}">{{ __('sidebar.my_events') }}</a>
                                        </li>
                                        @can('create', App\Models\Event::class)
                                            <li class="{{ $isCreateEvent ? 'active' : '' }}">
                                                <a href="{{ route('events.create') }}">{{ __('events.create_event') }}</a>
                                            </li>
                                        @endcan
                                        <li class="{{ $isUpcomingEvents ? 'active' : '' }}">
                                            <a href="{{ route('events.index', ['filter' => 'upcoming']) }}">{{ __('events.upcoming_events') }}</a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="{{ request()->routeIs('invitations.*') || request()->routeIs('requests.*') ? 'active' : '' }}">
                                    <a aria-expanded="{{ request()->routeIs('invitations.*') || request()->routeIs('requests.*') ? 'true' : 'false' }}" data-bs-toggle="collapse" href="#invitations">
                                        <svg stroke="currentColor" stroke-width="1.5">
                                            <use xlink:href="../assets/svg/_sprite.svg#chat-bubble"></use>
                                        </svg>
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
                                            <a href="{{ route('invitations.index') }}">{{ __('sidebar.my_invitations') }}</a>
                                        </li>
                                        <li class="{{ request()->routeIs('requests.index') ? 'active' : '' }}">
                                            <a href="{{ route('requests.index') }}">{{ __('sidebar.received_requests') }}</a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="{{ request()->routeIs('notifications.index') ? 'active' : '' }}">
                                    <a href="{{ route('notifications.index') }}">
                                        <svg stroke="currentColor" stroke-width="1.5">
                                            <use xlink:href="../assets/svg/_sprite.svg#exclamation-circle"></use>
                                        </svg>
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
                                        <svg stroke="currentColor" stroke-width="1.5">
                                            <use xlink:href="../assets/svg/_sprite.svg#document"></use>
                                        </svg>
                                        Il Mio Profilo
                                    </a>
                                    <ul class="collapse {{ request()->routeIs('profile.*') ? 'show' : '' }}" id="profile">
                                        <li class="{{ request()->routeIs('profile.show') ? 'active' : '' }}">
                                            <a href="{{ route('profile.show') }}">Visualizza Profilo</a>
                                        </li>
                                        <li class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                                            <a href="{{ route('profile.edit') }}">Modifica Profilo</a>
                                        </li>
                                        <li class="{{ request()->routeIs('profile.videos') ? 'active' : '' }}">
                                            <a href="{{ route('profile.videos') }}">I Miei Video</a>
                                        </li>
                                        <li class="{{ request()->routeIs('profile.activity') ? 'active' : '' }}">
                                            <a href="{{ route('profile.activity') }}">Le Mie Attività</a>
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
                                        <svg stroke="currentColor" stroke-width="1.5">
                                            <use xlink:href="../assets/svg/_sprite.svg#briefcase"></use>
                                        </svg>
                                        Gestione Permessi
                                    </a>
                                    <ul class="collapse {{ request()->routeIs('permissions.*') ? 'show' : '' }}" id="permissions">
                                        <li class="{{ request()->routeIs('permissions.index') ? 'active' : '' }}">
                                            <a href="{{ route('permissions.index') }}">Dashboard</a>
                                        </li>
                                        <li class="{{ request()->routeIs('permissions.roles') ? 'active' : '' }}">
                                            <a href="{{ route('permissions.roles') }}">Ruoli</a>
                                        </li>
                                        <li class="{{ request()->routeIs('permissions.permissions') ? 'active' : '' }}">
                                            <a href="{{ route('permissions.permissions') }}">Permessi</a>
                                        </li>
                                        <li class="{{ request()->routeIs('permissions.users') ? 'active' : '' }}">
                                            <a href="{{ route('permissions.users') }}">Utenti</a>
                                        </li>
                                    </ul>
                                </li>

                                <!-- Carousel Management -->
                                <li class="{{ request()->routeIs('admin.carousels.*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.carousels.index') }}">
                                        <svg stroke="currentColor" stroke-width="1.5">
                                            <use xlink:href="../assets/svg/_sprite.svg#squares"></use>
                                        </svg>
                                        Gestione Carosello
                                    </a>
                                </li>

                                <!-- System Settings -->
                                <li class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.settings.index') }}">
                                        <svg stroke="currentColor" stroke-width="1.5">
                                            <use xlink:href="../assets/svg/_sprite.svg#gear"></use>
                                        </svg>
                                        Impostazioni Sistema
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
                                        <svg stroke="currentColor" stroke-width="1.5">
                                            <use xlink:href="../assets/svg/_sprite.svg#stack"></use>
                                        </svg>
                                        {{ __('events.events') }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('login') }}">
                                        <svg stroke="currentColor" stroke-width="1.5">
                                            <use xlink:href="../assets/svg/_sprite.svg#window"></use>
                                        </svg>
                                        {{ __('sidebar.login') }}
                                    </a>
                                </li>
                                @endauth
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="simplebar-placeholder" style="width: 288px; height: 1261px;"></div>
    </div>
    <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
        <div class="simplebar-scrollbar" style="width: 0px; display: none; transform: translate3d(0px, 0px, 0px);"></div>
    </div>
    <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
        <div class="simplebar-scrollbar" style="height: 975px; display: block; transform: translate3d(0px, 0px, 0px);"></div>
    </div>

    <div class="menu-navs">
        <span class="menu-previous d-none"><i class="ti ti-chevron-left"></i></span>
        <span class="menu-next d-none"><i class="ti ti-chevron-right"></i></span>
    </div>
</nav>
<!-- Menu Navigation ends -->

