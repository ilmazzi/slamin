<!-- Menu Navigation starts -->
<nav class="vertical-sidebar">
    <div class="app-logo">
        <a class="logo d-inline-block" href="/">
            <!-- Logo orizzontale per desktop -->
            <img alt="Slam In" src="{{ asset('../assets/images/Logo_orizzontale_nerosubianco.png') }}" class="logo-full w-75">
            <!-- Loghino per mobile/sidebar collassata -->
            <img alt="Slam In" src="{{ asset('../assets/images/Loghino_nerosubianco.png') }}" class="logo-icon">
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
                                <li class="no-sub {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                                    <a href="{{ route('dashboard') }}">
                                        <svg stroke="currentColor" stroke-width="1.5">
                                            <use xlink:href="../assets/svg/_sprite.svg#home"></use>
                                        </svg>
                                        {{ __('dashboard.dashboard') }}
                                    </a>
                                </li>
                                @endauth

                                <!-- Eventi Section -->
                                <li class="no-sub {{ request()->routeIs('events.*') ? 'active' : '' }}">
                                    <a href="{{ route('events.index') }}">
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
                                </li>

                                @auth
                                <!-- Gigs Section - DISABILITATO (non implementato) -->
                                <li class="no-sub nav-item disabled">
                                    <a href="#" class="nav-link disabled" style="pointer-events: none; opacity: 0.6;">
                                        <i class="ph-duotone ph-microphone-stage text-muted f-s-20 me-2"></i>
                                        <span class="text-muted">Gigs</span>
                                    </a>
                                </li>
                                @endauth

                                <!-- News Section - DISABILITATO (non implementato) -->
                                <li class="no-sub nav-item disabled">
                                    <a href="#" class="nav-link disabled" style="pointer-events: none; opacity: 0.6;">
                                        <i class="ph-duotone ph-newspaper text-muted f-s-20 me-2"></i>
                                        <span class="text-muted">News</span>
                                    </a>
                                </li>

                                <!-- Media Section -->
                                <li class="no-sub {{ request()->routeIs('media.*') ? 'active' : '' }}">
                                    <a href="{{ route('media.index') }}">
                                        <i class="ph-duotone ph-video-camera f-s-20 me-2"></i>
                                        Media
                                    </a>
                                </li>



                                <!-- Poesie Section - DISABILITATO (non implementato) -->
                                <li class="no-sub nav-item disabled">
                                    <a href="#" class="nav-link disabled" style="pointer-events: none; opacity: 0.6;">
                                        <i class="ph-duotone ph-book-open text-muted f-s-20 me-2"></i>
                                        <span class="text-muted">Poesie</span>
                                    </a>
                                </li>

                                @auth
                                <!-- Profile Section - Solo per utenti autenticati -->
                                <li class="no-sub {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                                    <a href="{{ route('profile.show') }}">
                                        <svg stroke="currentColor" stroke-width="1.5">
                                            <use xlink:href="../assets/svg/_sprite.svg#document"></use>
                                        </svg>
                                        Il Mio Profilo
                                    </a>
                                </li>

                                @if(auth()->user()->hasRole(['admin', 'moderator']))
                                <!-- Permissions Management Section - Solo per admin/moderator -->
                                <li class="menu-title">
                                    <span>Amministrazione</span>
                                </li>
                                <li class="no-sub {{ request()->routeIs('permissions.*') ? 'active' : '' }}">
                                    <a href="{{ route('permissions.index') }}">
                                        <svg stroke="currentColor" stroke-width="1.5">
                                            <use xlink:href="../assets/svg/_sprite.svg#briefcase"></use>
                                        </svg>
                                        Gestione Permessi
                                    </a>
                                </li>

                                <!-- System Settings - Solo per admin/moderator -->
                                <li class="no-sub {{ request()->routeIs('admin.settings.*') || request()->routeIs('admin.carousels.*') || request()->routeIs('admin.translations.*') || request()->routeIs('admin.peertube.*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.settings.index') }}">
                                        <i class="ph-duotone ph-gear f-s-20 me-2"></i>
                                        Impostazioni
                                    </a>
                                </li>

                                <!-- PeerTube Configuration - Solo per admin/moderator -->
                                <li class="no-sub {{ request()->routeIs('admin.peertube.*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.peertube.index') }}">
                                        <i class="ph-duotone ph-video-camera f-s-20 me-2"></i>
                                        PeerTube
                                    </a>
                                </li>

                                <!-- Kanban Board - Solo per admin/moderator -->
                                <li class="no-sub {{ request()->routeIs('admin.kanban.*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.kanban.index') }}">
                                        <i class="ph-duotone ph-kanban f-s-20 me-2"></i>
                                        Kanban Board
                                    </a>
                                </li>
                                @endif
                                @else
                                <!-- Guest Menu - Login in basso -->
                                <li class="menu-title">
                                    <span>{{ __('sidebar.guest_menu_title') }}</span>
                                </li>
                                <li class="no-sub">
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

