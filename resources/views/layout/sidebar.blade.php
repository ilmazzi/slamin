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
            <a href="{{ route('profile.show') }}" class="text-decoration-none d-flex align-items-center flex-grow-1" style="cursor: pointer;">
                <span class="h-45 w-45 d-flex-center b-r-10 position-relative m-auto">
                    <img alt="avatar" class="img-fluid b-r-10" src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl(auth()->user()) }}">
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
                                    'organizzatore' => __('events.organizer'),
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
            </a>

            <div class="dropdown profile-menu-dropdown">
                <a aria-expanded="false" data-bs-auto-close="true" data-bs-placement="top" data-bs-toggle="dropdown" role="button">
                    <i class="ti ti-settings fs-5"></i>
                </a>
                <ul class="dropdown-menu">
                    <li class="dropdown-item">
                        <a class="f-w-500" href="{{ route('profile.edit') }}">
                            <i class="ph-duotone ph-gear pe-1 f-s-20"></i> {{ __('sidebar.settings') }}
                        </a>
                    </li>
                    <li class="dropdown-item">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <a class="f-w-500" href="#">
                                    <i class="ph-duotone ph-detective pe-1 f-s-20"></i> {{ __('sidebar.private_mode') }}
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
                            <i class="ph-bold ph-plus pe-1 f-s-20"></i> {{ __('sidebar.add_account') }}
                        </a>
                    </li>

                    <li class="app-divider-v dotted py-1"></li>

                    <li class="dropdown-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-link p-0 mb-0 text-danger f-w-500" style="text-decoration: none;">
                                <i class="ph-duotone ph-sign-out pe-1 f-s-20"></i> {{ __('sidebar.logout_button') }}
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        @else
        <!-- Login/Register Buttons per utenti non autenticati - Nella sezione profilo -->
        <div class="d-flex align-items-center nav-profile p-3">
            <div class="d-flex flex-column gap-2 w-100">
                <a href="{{ route('login') }}" class="btn btn-primary w-100">
                    <i class="ph ph-sign-in me-2"></i> {{ __('auth.login') }}
                </a>
                <a href="{{ route('register') }}" class="btn btn-outline-primary w-100">
                    <i class="ph ph-user-plus me-2"></i> {{ __('auth.register') }}
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
                                <!-- {{ __('dashboard.dashboard') }} - Solo per utenti autenticati -->
                                <!-- Dashboard nascosto solo quando siamo nella dashboard -->

                                @unless(request()->routeIs('dashboard'))
                                <li class="no-sub">
                                    <a href="{{ route('dashboard') }}">
                                        <svg stroke="currentColor" stroke-width="1.5">
                                            <use xlink:href="../assets/svg/_sprite.svg#home"></use>
                                        </svg>
                                        {{ __('dashboard.dashboard') }}
                                    </a>
                                </li>
                                @endunless
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
                                @unless(auth()->user()->hasRole('audience'))
                                <!-- Gigs Section -->
                                <li class="no-sub {{ request()->routeIs('gigs.*') ? 'active' : '' }}">
                                    <a href="{{ route('gigs.index') }}">
                                        <i class="ph-duotone ph-briefcase f-s-20 me-2"></i>
                                        {{ __('gigs.title') }}
                                        @if(auth()->user()->gigs()->open()->count() > 0)
                                            <span class="badge bg-success badge-notification ms-2">
                                                {{ auth()->user()->gigs()->open()->count() }}
                                            </span>
                                        @endif
                                    </a>
                                </li>
                                @endunless
                                @endauth



                                <!-- {{ __('common.media_section') }} Section -->
                                <li class="no-sub {{ request()->routeIs('media.*') ? 'active' : '' }}">
                                    <a href="{{ route('media.index') }}">
                                        <i class="ph-duotone ph-video-camera f-s-20 me-2"></i>
                                        {{ __('common.media_section') }}
                                    </a>
                                </li>
 <!-- {{ __('common.news') }} Section - DISABILITATO (non implementato) -->
 <li class="no-sub nav-item disabled d-none d-sm-block">
    <a href="#" class="nav-link disabled" style="pointer-events: none; opacity: 0.6;">
        <i class="ph-duotone ph-newspaper text-muted f-s-20 me-2"></i>
        <span class="text-muted">{{ __('common.news') }}</span>
    </a>
</li>


                                <!-- Poesie Section -->
                                <li class="no-sub {{ request()->routeIs('poems.*') ? 'active' : '' }}">
                                    <a href="{{ route('poems.index') }}">
                                        <i class="ph-duotone ph-book-open f-s-20 me-2"></i>
                                        {{ __('poems.title') }}
                                        @auth
                                        @if(auth()->user()->poems()->drafts()->count() > 0)
                                            <span class="badge bg-warning badge-notification ms-2">
                                                {{ auth()->user()->poems()->drafts()->count() }}
                                            </span>
                                        @endif
                                        @endauth
                                    </a>
                                </li>

                             

                                @auth
                                <!-- Gruppi Section - Solo per poeti e organizzatori -->
                                @if(auth()->user()->can('groups.create'))
                                <li class="no-sub {{ request()->routeIs('groups.*') ? 'active' : '' }}">
                                    <a href="{{ route('groups.index') }}">
                                        <i class="ph-duotone ph-users f-s-20 me-2"></i>
                                        {{ __('groups.title') }}
                                        @if(auth()->user()->getGroupsCountAttribute() > 0)
                                            <span class="badge bg-info badge-notification ms-2">
                                                {{ auth()->user()->getGroupsCountAttribute() }}
                                            </span>
                                        @endif
                                    </a>
                                </li>
                                @endif
                                <li class="menu-title"><span>PROSSIMAMENTE</span></li>


                                @auth
                                <!-- {{ __('common.didactic') }} Section - DISABILITATO (non implementato) -->
                                <li class="no-sub nav-item disabled d-none d-sm-block">
                                    <a href="#" class="nav-link disabled" style="pointer-events: none; opacity: 0.6;">
                                        <i class="ph-duotone ph-microphone-stage text-muted f-s-20 me-2"></i>
                                        <span class="text-muted">{{ __('common.didactic') }}</span>
                                    </a>
                                </li>
                                @endauth
                                @auth
                                <!-- {{ __('common.forum') }} Section - DISABILITATO (non implementato) -->
                                <li class="no-sub nav-item disabled d-none d-sm-block">
                                    <a href="#" class="nav-link disabled" style="pointer-events: none; opacity: 0.6;">
                                        <i class="ph-duotone ph-microphone-stage text-muted f-s-20 me-2"></i>
                                        <span class="text-muted">{{ __('common.forum') }}</span>
                                    </a>
                                </li>
                                @endauth
                                @auth
                                <!-- {{ __('common.fan_support') }} Section - DISABILITATO (non implementato) -->
                                <li class="no-sub nav-item disabled d-none d-sm-block">
                                    <a href="#" class="nav-link disabled" style="pointer-events: none; opacity: 0.6;">
                                        <i class="ph-duotone ph-microphone-stage text-muted f-s-20 me-2"></i>
                                        <span class="text-muted">{{ __('common.fan_support') }}</span>
                                    </a>
                                </li>
                                @endauth
                                @auth
                                <!-- {{ __('common.wiki') }} Section - DISABILITATO (non implementato) -->
                                <li class="no-sub nav-item disabled d-none d-sm-block">
                                    <a href="#" class="nav-link disabled" style="pointer-events: none; opacity: 0.6;">
                                        <i class="ph-duotone ph-microphone-stage text-muted f-s-20 me-2"></i>
                                        <span class="text-muted">{{ __('common.wiki') }}</span>
                                    </a>
                                </li>
                                @endauth






                                @if(auth()->user()->hasRole(['admin', 'moderator']))
                                <!-- Permissions Management Section - Solo per admin/moderator -->
                                <li class="menu-title">
                                    <span>{{ __('sidebar.administration') }}</span>
                                </li>
                                <li class="no-sub {{ request()->routeIs('permissions.*') ? 'active' : '' }}">
                                    <a href="{{ route('permissions.index') }}">
                                        <svg stroke="currentColor" stroke-width="1.5">
                                            <use xlink:href="../assets/svg/_sprite.svg#briefcase"></use>
                                        </svg>
                                        {{ __('sidebar.permissions_management') }}
                                    </a>
                                </li>

                                                                <!-- Moderation {{ __('dashboard.dashboard') }} - Solo per admin/moderator -->
                                <li class="no-sub {{ request()->routeIs('admin.moderation.*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.moderation.index') }}" title="{{ __('sidebar.moderation_tooltip') }}">
                                        <i class="ph-duotone ph-shield-check f-s-20 me-2"></i>
                                        {{ __('sidebar.moderation') }}
                                        @php
                                            $pendingCount = \App\Models\Video::pending()->count() +
                                                          \App\Models\Poem::pending()->count() +
                                                          \App\Models\Event::pending()->count() +
                                                          \App\Models\Photo::pending()->count() +
                                                          \App\Models\Carousel::pending()->count() +
                                                          \App\Models\Report::active()->count();
                                        @endphp
                                        @if($pendingCount > 0)
                                            <span class="badge bg-warning badge-notification ms-2">
                                                {{ $pendingCount }}
                                            </span>
                                        @endif
                                    </a>
                                </li>

                                <!-- System Settings - Solo per admin/moderator -->
                                <li class="no-sub {{ request()->routeIs('admin.settings.*') || request()->routeIs('admin.carousels.*') || request()->routeIs('admin.translations.*') || request()->routeIs('admin.peertube.*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.settings.index') }}">
                                        <i class="ph-duotone ph-gear f-s-20 me-2"></i>
                                        {{ __('sidebar.settings') }}
                                    </a>
                                </li>

                                <!-- PeerTube Configuration - Solo per admin/moderator -->
                                <li class="no-sub {{ request()->routeIs('admin.peertube.*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.peertube.index') }}">
                                        <i class="ph-duotone ph-video-camera f-s-20 me-2"></i>
                                        PeerTube
                                    </a>
                                </li>

                                <!-- {{ __('common.kanban_board') }} - Solo per admin/moderator -->
                                <li class="no-sub {{ request()->routeIs('admin.kanban.*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.kanban.index') }}">
                                        <i class="ph-duotone ph-kanban f-s-20 me-2"></i>
                                        {{ __('common.kanban_board') }}
                                    </a>
                                </li>

                                <!-- System Logs - Solo per admin/moderator -->
                                <li class="no-sub {{ request()->routeIs('admin.logs.*') ? 'active' : '' }}">
                                    <a href="{{ route('admin.logs.index') }}">
                                        <i class="ph-duotone ph-newspaper f-s-20 me-2"></i>
                                        {{ __('sidebar.system_logs') }}
                                    </a>
                                </li>
                                @endif
                                @endauth
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="simplebar-placeholder" style="width: 288px; height: 1261px;"></div>
    </div>



    <div class="menu-navs">
        <span class="menu-previous d-none"><i class="ti ti-chevron-left"></i></span>
        <span class="menu-next d-none"><i class="ti ti-chevron-right"></i></span>
    </div>
</nav>
<!-- Menu Navigation ends -->

