<!-- Header Section starts -->
<header class="header-main">
<style>
/* Header spacing optimization */
.header-main .header-left .header-searchbar {
    max-width: 300px;
    margin-right: 1rem;
}

.header-main .header-right ul {
    gap: 0.5rem;
}

.header-main .header-right ul li .head-icon {
    width: 35px;
    height: 35px;
    padding: 0.4rem;
}

/* Responsive adjustments */
@media (max-width: 1200px) {
    .header-main .header-left .header-searchbar {
        max-width: 250px;
    }
}

@media (max-width: 992px) {
    .header-main .header-left .header-searchbar {
        max-width: 200px;
    }

    .header-main .header-right ul {
        gap: 0.3rem;
    }

    .header-main .header-right ul li .head-icon {
        width: 32px;
        height: 32px;
        padding: 0.3rem;
    }
}
</style>
    <div class="container-fluid">
        <div class="row">
            <div class="col-8 col-sm-6 d-flex align-items-center header-left p-0">
                <span class="header-toggle ">
                    <i class="ph ph-list"></i>
                </span>

                <div class="header-searchbar w-100">
                    <form action="{{ route('search.index') }}" method="GET" class="mx-sm-3 app-form app-icon-form">
                        <div class="position-relative">
                            <input aria-label="Search" class="form-control" placeholder="{{ __('search.search_placeholder') }}" name="q" type="search">
                            <i class="ti ti-search text-dark"></i>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-4 col-sm-6 d-flex align-items-center justify-content-end header-right p-0">
                <ul class="d-flex align-items-center">

                    @auth
                    <!-- {{ __('dashboard.dashboard') }} - Solo per utenti autenticati -->
                    <li class="header-dashboard">
                        <a href="{{ route('dashboard') }}" class="d-block head-icon bg-light-dark rounded-circle f-s-22 p-2"
                           data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('dashboard.dashboard') }}">
                            <x-icon name="dashboard" size="20" class="me-2" />
                        </a>
                    </li>

                    <!-- Shortcuts - Solo per utenti autenticati -->
                    <li class="header-shortcuts">
                        <div class="flex-shrink-0 dropdown">
                            <a aria-expanded="false" class="d-block head-icon bg-light-dark rounded-circle f-s-22 p-2"
                               data-bs-toggle="dropdown"
                               href="#" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('common.shortcuts') }}">
                                <x-icon name="shortcuts" size="20" class="me-2" />
                            </a>
                            <ul class="dropdown-menu header-card border-0">
                                <li class="dropdown-header">
                                    <h6 class="mb-0">
                                        <x-icon name="shortcuts" size="20" class="me-2" />{{ __('common.shortcuts') }}
                                    </h6>
                                </li>
                                <li class="dropdown-divider"></li>
                                <!-- 1. Scrivi Poesia - per poeti e admin -->
                                @can('poems.create')
                                <li class="dropdown-item">
                                    <a href="{{ route('poems.create') }}" class="d-flex align-items-center text-decoration-none">
                                        <x-icon name="poetry" size="20" class="me-2 text-info" />
                                        <span>{{ __('dashboard.write_poem') }}</span>
                                    </a>
                                </li>
                                @endcan
                                <!-- 2. Crea Evento - per organizer e admin -->
                                @can('events.create.public')
                                <li class="dropdown-item">
                                    <a href="{{ route('events.create') }}" class="d-flex align-items-center text-decoration-none">
                                        <x-icon name="event" size="20" class="me-2 text-success" />
                                        <span>{{ __('dashboard.organize_event') }}</span>
                                    </a>
                                </li>
                                @endcan
                                <!-- 3. Carica Video - per poeti e admin -->
                                @can('videos.upload')
                                <li class="dropdown-item">
                                    <a href="{{ route('videos.upload') }}" class="d-flex align-items-center text-decoration-none">
                                        <x-icon name="media" size="20" class="me-2 text-warning" />
                                        <span>{{ __('dashboard.upload_performance') }}</span>
                                    </a>
                                </li>
                                @endcan
                                <!-- 4. Scrivi Articolo - per organizer, venue_owner e admin -->
                                @can('articles.create')
                                <li class="dropdown-item">
                                    <a href="{{ route('articles.create') }}" class="d-flex align-items-center text-decoration-none">
                                        <x-icon name="article" size="20" class="me-2 text-primary" />
                                        <span>{{ __('dashboard.write_article') }}</span>
                                    </a>
                                </li>
                                @endcan
                            </ul>
                        </div>
                    </li>

                    <!-- Notifications - Solo per utenti autenticati -->
                    <li class="header-notification">
                        <a aria-controls="notificationcanvasRight"
                           class="d-block head-icon position-relative bg-light-dark rounded-circle f-s-22 p-2"
                           data-bs-target="#notificationcanvasRight"
                           data-bs-toggle="offcanvas"
                           href="#"
                           role="button"
                           id="notificationTrigger"
                           data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('notifications.notifications') }}">
                            <img id="notificationIcon" src="{{ asset('assets/images/bell.png') }}" alt="{{ __('common.notifications') }}" style="width: 20px; height: 20px;">
                            <!-- Dynamic notification badge -->
                            <span id="notificationBadge" class="position-absolute translate-middle badge rounded-pill bg-danger badge-notification" style="display: none;">0</span>
                        </a>
                        <div aria-labelledby="notificationcanvasRightLabel"
                             class="offcanvas offcanvas-end header-notification-canvas"
                             id="notificationcanvasRight" tabindex="-1">
                            <div class="offcanvas-header">
                                <h5 class="offcanvas-title" id="notificationcanvasRightLabel">
                                    <x-icon name="notification" size="20" class="me-2" />{{ __('notifications.notifications') }}
                                    <span id="notificationCount" class="badge bg-primary ms-2">0</span>
                                </h5>
                                <div class="d-flex align-items-center">
                                    <button class="btn btn-outline-primary btn-sm me-2" onclick="markAllNotificationsRead()" title="{{ __('notifications.mark_all_read') }}">
                                        <i class="ph ph-check-circle"></i>
                                    </button>
                                    <button aria-label="{{ __('common.close') }}" class="btn-close" data-bs-dismiss="offcanvas" type="button"></button>
                                </div>
                            </div>
                            <div class="offcanvas-body app-scroll p-0" id="notificationsContainer">
                                <!-- Loading state -->
                                <div id="notificationsLoading" class="text-center p-4">
                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Caricamento...</span>
                                    </div>
                                    <p class="mt-2 text-muted">Caricamento notifiche...</p>
                                </div>

                                <!-- Notifications will be loaded here -->
                                <div id="notificationsList" style="display: none;">
                                    <!-- Dynamic notifications loaded via JavaScript -->
                                </div>

                                <!-- Empty state -->
                                <div id="notificationsEmpty" class="text-center p-4" style="display: none;">
                                    <i class="ph ph-bell-slash display-4 text-muted mb-3"></i>
                                    <h6 class="text-muted">{{ __('notifications.no_notifications') }}</h6>
                                    <p class="text-muted small">{{ __('notifications.notifications_placeholder') }}</p>
                                </div>

                                <!-- Footer actions -->
                                <div class="p-3 border-top" id="notificationsFooter" style="display: none;">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <a href="{{ route('notifications.index') }}" class="btn btn-outline-primary btn-sm w-100">
                                                <i class="ph ph-list me-1"></i>Vedi Tutte
                                            </a>
                                        </div>
                                        <div class="col-6">
                                            <button class="btn btn-outline-secondary btn-sm w-100" onclick="clearOldNotifications()">
                                                <i class="ph ph-trash me-1"></i>Pulisci
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endauth



                    <!-- Theme Toggle - Per tutti gli utenti -->
                    <li class="header-dark">
                        <div class="sun-logo head-icon bg-light-dark rounded-circle f-s-22 p-2"
                             data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('common.dark_theme') }}">
                            <i class="ph ph-moon-stars"></i>
                        </div>
                        <div class="moon-logo head-icon bg-light-dark rounded-circle f-s-22 p-2"
                             data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('common.light_theme') }}">
                            <i class="ph ph-sun-dim"></i>
                        </div>
                    </li>

                    <!-- Language Selector - Per tutti gli utenti -->
                    <li class="header-language">
                        <div class="flex-shrink-0 dropdown" id="lang_selector">
                            <a aria-expanded="false" class="d-block head-icon ps-0"
                               data-bs-toggle="dropdown"
                               href="#" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('common.language_selector') }}">
                                <div class="lang-flag lang-{{ app()->getLocale() }}">
                                    <span class="flag rounded-circle overflow-hidden">
                                        <i class="flag-icon flag-icon-{{ \App\Providers\LanguageServiceProvider::getFlagCode(app()->getLocale()) }}"></i>
                                    </span>
                                </div>
                            </a>
                            <ul class="dropdown-menu language-dropdown header-card border-0">
                                @foreach($availableLanguages as $code => $name)
                                <li class="lang lang-{{ $code }} {{ app()->getLocale() == $code ? 'selected' : '' }} dropdown-item p-2" data-bs-placement="top" data-bs-toggle="tooltip" title="{{ strtoupper($code) }}">
                                    <a href="{{ url()->current() }}?lang={{ $code }}" class="d-flex align-items-center text-decoration-none">
                                        <i class="flag-icon flag-icon-{{ \App\Providers\LanguageServiceProvider::getFlagCode($code) }} flag-icon-squared rounded-circle f-s-20"></i>
                                        <span class="ps-2">{{ $name }}</span>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>

                    @auth
                    <!-- Wiki - Prossimamente - Solo per utenti autenticati
                    <li class="header-wiki">
                        <a href="#" class="d-block head-icon bg-light-dark rounded-circle f-s-22 p-2 disabled"
                           data-bs-toggle="tooltip" data-bs-placement="bottom" title="Wiki (Prossimamente)"
                           style="opacity: 0.5; cursor: not-allowed;">
                            <i class="ph ph-book-open"></i>
                        </a>
                    </li>-->

                    <!-- Corsi - Prossimamente - Solo per utenti autenticati
                    <li class="header-courses">
                        <a href="#" class="d-block head-icon bg-light-dark rounded-circle f-s-22 p-2 disabled"
                           data-bs-toggle="tooltip" data-bs-placement="bottom" title="Corsi (Prossimamente)"
                           style="opacity: 0.5; cursor: not-allowed;">
                            <i class="ph ph-graduation-cap"></i>
                        </a>
                    </li>-->

                    <!-- Forum - Prossimamente - Solo per utenti autenticati
                    <li class="header-forum">
                        <a href="#" class="d-block head-icon bg-light-dark rounded-circle f-s-22 p-2 disabled"
                           data-bs-toggle="tooltip" data-bs-placement="bottom" title="Forum (Prossimamente)"
                           style="opacity: 0.5; cursor: not-allowed;">
                            <i class="ph ph-chats-circle"></i>
                        </a>
                    </li>-->
                    @endauth

                </ul>
            </div>
        </div>

    </div>
</header>
<!-- Header Section ends -->
