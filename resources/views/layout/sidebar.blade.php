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
                                <!-- Dashboard - Solo per utenti autenticati -->
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
                                @endauth

                                <!-- Eventi Section -->
                                <li class="menu-title">
                                    <span>{{ __('events.events_poetry_slam') }}</span>
                                </li>
                                <li class="{{ request()->routeIs('events.*') ? 'active' : '' }}">
                                    <a aria-expanded="{{ request()->routeIs('events.*') ? 'true' : 'false' }}" data-bs-toggle="collapse" href="#events">
                                        <svg stroke="currentColor" stroke-width="1.5">
                                            <use xlink:href="../assets/svg/_sprite.svg#stack"></use>
                                        </svg>
                                        {{ __('events.events') }}
                                        @auth
                                        @if(auth()->user()->organizedEvents()->where('start_datetime', '>', now())->count() > 0)
                                            <span class="badge bg-primary badge-notification ms-2">
                                                {{ auth()->user()->organizedEvents()->where('start_datetime', '>', now())->count() }}
                                            </span>
                                        @endif
                                        @endauth
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
                                                <i class="ph-duotone ph-list me-2"></i>{{ __('events.all_events') }}
                                            </a>
                                        </li>
                                        @auth
                                        <li class="{{ $isMyEvents ? 'active' : '' }}">
                                            <a href="{{ route('events.index', ['filter' => 'my']) }}">
                                                <i class="ph-duotone ph-calendar me-2"></i>{{ __('sidebar.my_events') }}
                                            </a>
                                        </li>
                                        @can('create', App\Models\Event::class)
                                            <li class="{{ $isCreateEvent ? 'active' : '' }}">
                                                <a href="{{ route('events.create') }}">
                                                    <i class="ph-duotone ph-plus-circle me-2"></i>{{ __('events.create_event') }}
                                                </a>
                                            </li>
                                        @endcan
                                        @endauth
                                        <li class="{{ $isUpcomingEvents ? 'active' : '' }}">
                                            <a href="{{ route('events.index', ['filter' => 'upcoming']) }}">
                                                <i class="ph-duotone ph-clock me-2"></i>{{ __('events.upcoming_events') }}
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                @auth
                                <!-- Gigs Section - DISABILITATO (non implementato) -->
                                <li class="menu-title">
                                    <span>Gigs <span class="badge bg-light-warning text-dark f-s-10">Prossimamente</span></span>
                                </li>
                                <li class="nav-item disabled">
                                    <a href="#" class="nav-link disabled" style="pointer-events: none; opacity: 0.6;">
                                        <i class="ph-duotone ph-microphone-stage text-muted f-s-20 me-2"></i>
                                        <span class="text-muted">Gigs</span>
                                    </a>
                                    <ul class="collapse" id="gigs">
                                        <li class="nav-item disabled">
                                            <a href="#" class="nav-link disabled" style="pointer-events: none; opacity: 0.6;">
                                                <i class="ph-duotone ph-list me-2 text-muted"></i><span class="text-muted">Tutti i Gigs</span>
                                            </a>
                                        </li>
                                        <li class="nav-item disabled">
                                            <a href="#" class="nav-link disabled" style="pointer-events: none; opacity: 0.6;">
                                                <i class="ph-duotone ph-calendar me-2 text-muted"></i><span class="text-muted">I Miei Gigs</span>
                                            </a>
                                        </li>
                                        <li class="nav-item disabled">
                                            <a href="#" class="nav-link disabled" style="pointer-events: none; opacity: 0.6;">
                                                <i class="ph-duotone ph-plus-circle me-2 text-muted"></i><span class="text-muted">Crea Gig</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                @endauth

                                <!-- News Section - DISABILITATO (non implementato) -->
                                <li class="menu-title">
                                    <span>News <span class="badge bg-light-warning text-dark f-s-10">Prossimamente</span></span>
                                </li>
                                <li class="nav-item disabled">
                                    <a href="#" class="nav-link disabled" style="pointer-events: none; opacity: 0.6;">
                                        <i class="ph-duotone ph-newspaper text-muted f-s-20 me-2"></i>
                                        <span class="text-muted">News</span>
                                    </a>
                                    <ul class="collapse" id="news">
                                        <li class="nav-item disabled">
                                            <a href="#" class="nav-link disabled" style="pointer-events: none; opacity: 0.6;">
                                                <i class="ph-duotone ph-list me-2 text-muted"></i><span class="text-muted">Tutti gli Articoli</span>
                                            </a>
                                        </li>
                                        @auth
                                        <li class="nav-item disabled">
                                            <a href="#" class="nav-link disabled" style="pointer-events: none; opacity: 0.6;">
                                                <i class="ph-duotone ph-pencil me-2 text-muted"></i><span class="text-muted">I Miei Articoli</span>
                                            </a>
                                        </li>
                                        <li class="nav-item disabled">
                                            <a href="#" class="nav-link disabled" style="pointer-events: none; opacity: 0.6;">
                                                <i class="ph-duotone ph-plus-circle me-2 text-muted"></i><span class="text-muted">Scrivi Articolo</span>
                                            </a>
                                        </li>
                                        @endauth
                                        <li class="nav-item disabled">
                                            <a href="#" class="nav-link disabled" style="pointer-events: none; opacity: 0.6;">
                                                <i class="ph-duotone ph-clock me-2 text-muted"></i><span class="text-muted">Ultime News</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <!-- Media Section -->
                                <li class="menu-title">
                                    <span>Media</span>
                                </li>
                                <li class="{{ request()->routeIs('videos.*') ? 'active' : '' }}">
                                    <a aria-expanded="{{ request()->routeIs('videos.*') ? 'true' : 'false' }}" data-bs-toggle="collapse" href="#media">
                                        <i class="ph-duotone ph-video-camera f-s-20 me-2"></i>
                                        Media
                                    </a>
                                    <ul class="collapse {{ request()->routeIs('videos.*') ? 'show' : '' }}" id="media">
                                        <li class="{{ request()->routeIs('videos.show') ? 'active' : '' }}">
                                            <a href="{{ route('videos.show', ['video' => 1]) }}">
                                                <i class="ph-duotone ph-list me-2"></i>Tutti i Video
                                            </a>
                                        </li>
                                        @auth
                                        <li class="{{ request()->routeIs('profile.videos') ? 'active' : '' }}">
                                            <a href="{{ route('profile.videos') }}">
                                                <i class="ph-duotone ph-video-camera me-2"></i>I Miei Video
                                            </a>
                                        </li>
                                        <li class="{{ request()->routeIs('videos.upload') ? 'active' : '' }}">
                                            <a href="{{ route('videos.upload') }}">
                                                <i class="ph-duotone ph-upload me-2"></i>Carica Video
                                            </a>
                                        </li>
                                        @endauth
                                        <li>
                                            <a href="{{ route('gallery') }}" class="text-muted">
                                                <i class="ph-duotone ph-images me-2"></i>Galleria
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <!-- Poesie Section - DISABILITATO (non implementato) -->
                                <li class="menu-title">
                                    <span>Poesie <span class="badge bg-light-warning text-dark f-s-10">Prossimamente</span></span>
                                </li>
                                <li class="nav-item disabled">
                                    <a href="#" class="nav-link disabled" style="pointer-events: none; opacity: 0.6;">
                                        <i class="ph-duotone ph-book-open text-muted f-s-20 me-2"></i>
                                        <span class="text-muted">Poesie</span>
                                    </a>
                                    <ul class="collapse" id="poems">
                                        <li class="nav-item disabled">
                                            <a href="#" class="nav-link disabled" style="pointer-events: none; opacity: 0.6;">
                                                <i class="ph-duotone ph-list me-2 text-muted"></i><span class="text-muted">Tutte le Poesie</span>
                                            </a>
                                        </li>
                                        @auth
                                        <li class="nav-item disabled">
                                            <a href="#" class="nav-link disabled" style="pointer-events: none; opacity: 0.6;">
                                                <i class="ph-duotone ph-pencil me-2 text-muted"></i><span class="text-muted">Le Mie Poesie</span>
                                            </a>
                                        </li>
                                        <li class="nav-item disabled">
                                            <a href="#" class="nav-link disabled" style="pointer-events: none; opacity: 0.6;">
                                                <i class="ph-duotone ph-plus-circle me-2 text-muted"></i><span class="text-muted">Scrivi Poesia</span>
                                            </a>
                                        </li>
                                        @endauth
                                        <li class="nav-item disabled">
                                            <a href="#" class="nav-link disabled" style="pointer-events: none; opacity: 0.6;">
                                                <i class="ph-duotone ph-star me-2 text-muted"></i><span class="text-muted">Poesie in Evidenza</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                @auth
                                <!-- Profile Section - Solo per utenti autenticati -->
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
                                            <a href="{{ route('profile.show') }}">
                                                <i class="ph-duotone ph-eye me-2"></i>Visualizza Profilo
                                            </a>
                                        </li>
                                        <li class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                                            <a href="{{ route('profile.edit') }}">
                                                <i class="ph-duotone ph-pencil me-2"></i>Modifica Profilo
                                            </a>
                                        </li>
                                        <li class="{{ request()->routeIs('profile.videos') ? 'active' : '' }}">
                                            <a href="{{ route('profile.videos') }}">
                                                <i class="ph-duotone ph-video-camera me-2"></i>I Miei Video
                                            </a>
                                        </li>
                                        <li class="{{ request()->routeIs('profile.activity') ? 'active' : '' }}">
                                            <a href="{{ route('profile.activity') }}">
                                                <i class="ph-duotone ph-activity me-2"></i>Le Mie Attività
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                @if(auth()->user()->hasRole(['admin', 'moderator']))
                                <!-- Permissions Management Section - Solo per admin/moderator -->
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
                                            <a href="{{ route('permissions.index') }}">
                                                <i class="ph-duotone ph-gauge me-2"></i>Dashboard
                                            </a>
                                        </li>
                                        <li class="{{ request()->routeIs('permissions.roles') ? 'active' : '' }}">
                                            <a href="{{ route('permissions.roles') }}">
                                                <i class="ph-duotone ph-users me-2"></i>Ruoli
                                            </a>
                                        </li>
                                        <li class="{{ request()->routeIs('permissions.permissions') ? 'active' : '' }}">
                                            <a href="{{ route('permissions.permissions') }}">
                                                <i class="ph-duotone ph-shield-check me-2"></i>Permessi
                                            </a>
                                        </li>
                                        <li class="{{ request()->routeIs('permissions.users') ? 'active' : '' }}">
                                            <a href="{{ route('permissions.users') }}">
                                                <i class="ph-duotone ph-user-circle me-2"></i>Utenti
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <!-- System Settings Group - Solo per admin/moderator -->
                                <li class="{{ request()->routeIs('admin.settings.*') || request()->routeIs('admin.carousels.*') || request()->routeIs('admin.translations.*') || request()->routeIs('admin.peertube.*') ? 'active' : '' }}">
                                    <a aria-expanded="{{ request()->routeIs('admin.settings.*') || request()->routeIs('admin.carousels.*') || request()->routeIs('admin.translations.*') || request()->routeIs('admin.peertube.*') ? 'true' : 'false' }}" data-bs-toggle="collapse" href="#system-settings">
                                        <i class="ph-duotone ph-gear"></i>
                                        Impostazioni
                                    </a>
                                    <ul class="collapse {{ request()->routeIs('admin.settings.*') || request()->routeIs('admin.carousels.*') || request()->routeIs('admin.translations.*') || request()->routeIs('admin.peertube.*') ? 'show' : '' }}" id="system-settings">
                                        <li class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.settings.index') }}">
                                                <i class="ph-duotone ph-gear me-2"></i>Impostazioni Generali
                                            </a>
                                        </li>
                                        <li class="{{ request()->routeIs('admin.carousels.*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.carousels.index') }}">
                                                <i class="ph-duotone ph-squares-four me-2"></i>Gestione Carosello
                                            </a>
                                        </li>
                                        <li class="{{ request()->routeIs('admin.translations.*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.translations.index') }}">
                                                <i class="ph-duotone ph-translate me-2"></i>Gestione Traduzioni
                                            </a>
                                        </li>
                                        <li class="{{ request()->routeIs('admin.peertube.*') ? 'active' : '' }}">
                                            <a href="{{ route('admin.peertube.config') }}">
                                                <i class="ph-duotone ph-video-camera me-2"></i>Configurazione PeerTube
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                @endif
                                @else
                                <!-- Guest Menu - Login in basso -->
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
                                @endauth
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="simplebar-placeholder" style="width: 288px; height: 1261px;"></div>
    </div>

    <!-- Login Button per utenti non autenticati - In basso -->
    @guest
    <div class="sidebar-footer p-3 border-top">
        <div class="text-center">
            <a href="{{ route('login') }}" class="btn btn-primary w-100">
                <i class="ph ph-sign-in me-2"></i> {{ __('sidebar.login') }}
            </a>
        </div>
    </div>
    @endguest

    <div class="menu-navs">
        <span class="menu-previous d-none"><i class="ti ti-chevron-left"></i></span>
        <span class="menu-next d-none"><i class="ti ti-chevron-right"></i></span>
    </div>
</nav>
<!-- Menu Navigation ends -->

