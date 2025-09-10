<!-- Header Section starts -->
<header class="header-main">
    <div class="container-fluid">
        <!-- Desktop Layout -->
        <div class="row d-none d-md-flex">
            <div class="col-3 d-flex align-items-center header-left p-0">
                <span class="header-toggle">
                    <i class="ph ph-squares-four"></i>
                </span>
            </div>

            <!-- Global Search Bar - Desktop -->
            <div class="col-6 d-flex align-items-center justify-content-center header-center p-0">
                <div class="global-search-container position-relative w-100">
                    <form action="{{ route('search.index') }}" method="GET" class="d-flex w-100" id="globalSearchForm">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="ph ph-magnifying-glass text-muted"></i>
                            </span>
                            <input type="text"
                                   class="form-control border-start-0"
                                   name="q"
                                   id="globalSearchInput"
                                   placeholder="{{ __('search.search_placeholder') }}"
                                   autocomplete="off">
                            <button class="btn btn-outline-secondary border-start-0" type="submit">
                                <i class="ph ph-arrow-right"></i>
                            </button>
                        </div>

                        <!-- Search Results Dropdown -->
                        <div class="dropdown-menu w-100 search-results-dropdown" id="searchResultsDropdown" style="max-height: 400px; overflow-y: auto; position: absolute; top: 100%; left: 0; right: 0; z-index: 1050; display: none;">
                            <div class="search-loading text-center p-3" id="searchLoading" style="display: none;">
                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                    <span class="visually-hidden">{{ __('search.loading') }}</span>
                                </div>
                                <span class="ms-2">{{ __('search.loading') }}</span>
                            </div>

                            <div class="search-results" id="searchResults" style="display: none;">
                                <!-- Results will be populated here -->
                            </div>

                            <div class="search-empty text-center p-3" id="searchEmpty" style="display: none;">
                                <i class="ph ph-magnifying-glass display-6 text-muted mb-2"></i>
                                <p class="text-muted mb-0">{{ __('search.no_results') }}</p>
                            </div>

                            <div class="search-placeholder text-center p-3" id="searchPlaceholder">
                                <i class="ph ph-magnifying-glass display-6 text-muted mb-2"></i>
                                <p class="text-muted mb-0">{{ __('search.start_typing') }}</p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-3 d-flex align-items-center justify-content-end header-right p-0">
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
                                    <a href="#" class="d-flex align-items-center text-decoration-none">
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

        <!-- Mobile Layout -->
        <div class="row d-md-none">
            <!-- Mobile Header Row 1: Toggle + Icons -->
            <div class="col-12 d-flex align-items-center justify-content-between p-2">
                <div class="d-flex align-items-center">
                    <span class="header-toggle me-3">
                        <i class="ph ph-squares-four"></i>
                    </span>
                </div>

                <div class="d-flex align-items-center">
                    @auth
                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}" class="d-block head-icon bg-light-dark rounded-circle f-s-22 p-2 me-2"
                       data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('dashboard.dashboard') }}">
                        <i class="ph ph-gauge"></i>
                    </a>

                    <!-- Notifications -->
                    <a aria-controls="notificationcanvasRight"
                       class="d-block head-icon position-relative bg-light-dark rounded-circle f-s-22 p-2 me-2"
                       data-bs-target="#notificationcanvasRight"
                       data-bs-toggle="offcanvas"
                       href="#"
                       role="button"
                       id="notificationTrigger"
                       data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('notifications.notifications') }}">
                        <img id="notificationIcon" src="{{ asset('assets/images/bell.png') }}" alt="{{ __('common.notifications') }}" style="width: 20px; height: 20px;">
                        <span id="notificationBadge" class="position-absolute translate-middle badge rounded-pill bg-danger badge-notification" style="display: none;">0</span>
                    </a>
                    @endauth

                    <!-- Theme Toggle -->
                    <div class="sun-logo head-icon bg-light-dark rounded-circle f-s-22 p-2 me-2"
                         data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('common.dark_theme') }}">
                        <i class="ph ph-moon-stars"></i>
                    </div>
                    <div class="moon-logo head-icon bg-light-dark rounded-circle f-s-22 p-2 me-2"
                         data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('common.light_theme') }}">
                        <i class="ph ph-sun-dim"></i>
                    </div>

                    <!-- Language Selector -->
                    <div class="flex-shrink-0 dropdown" id="lang_selector_mobile">
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
                </div>
            </div>

            <!-- Mobile Header Row 2: Search Bar -->
            <div class="col-12 px-2 pb-2">
                <div class="global-search-container position-relative w-100">
                    <form action="{{ route('search.index') }}" method="GET" class="d-flex w-100" id="globalSearchFormMobile">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="ph ph-magnifying-glass text-muted"></i>
                            </span>
                            <input type="text"
                                   class="form-control border-start-0"
                                   name="q"
                                   id="globalSearchInputMobile"
                                   placeholder="{{ __('search.search_placeholder') }}"
                                   autocomplete="off">
                            <button class="btn btn-outline-secondary border-start-0" type="submit">
                                <i class="ph ph-arrow-right"></i>
                            </button>
                        </div>

                        <!-- Search Results Dropdown - Mobile -->
                        <div class="dropdown-menu w-100 search-results-dropdown" id="searchResultsDropdownMobile" style="max-height: 300px; overflow-y: auto; position: absolute; top: 100%; left: 0; right: 0; z-index: 1050; display: none;">
                            <div class="search-loading text-center p-3" id="searchLoadingMobile" style="display: none;">
                                <div class="spinner-border spinner-border-sm text-primary" role="status">
                                    <span class="visually-hidden">{{ __('search.loading') }}</span>
                                </div>
                                <span class="ms-2">{{ __('search.loading') }}</span>
                            </div>

                            <div class="search-results" id="searchResultsMobile" style="display: none;">
                                <!-- Results will be populated here -->
                            </div>

                            <div class="search-empty text-center p-3" id="searchEmptyMobile" style="display: none;">
                                <i class="ph ph-magnifying-glass display-6 text-muted mb-2"></i>
                                <p class="text-muted mb-0">{{ __('search.no_results') }}</p>
                            </div>

                            <div class="search-placeholder text-center p-3" id="searchPlaceholderMobile">
                                <i class="ph ph-magnifying-glass display-6 text-muted mb-2"></i>
                                <p class="text-muted mb-0">{{ __('search.start_typing') }}</p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- Header Section ends -->

<!-- Mobile Header CSS Fixes -->
<style>
/* Fix mobile header icons to be perfectly circular */
@media (max-width: 767.98px) {
    .header-main .head-icon {
        width: 40px !important;
        height: 40px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        border-radius: 50% !important;
        padding: 0 !important;
    }

    .header-main .head-icon i {
        font-size: 20px !important;
        line-height: 1 !important;
    }

    /* Fix search bar z-index and positioning */
    .header-main .search-results-dropdown {
        z-index: 1002 !important; /* Below sidebar (1005) but above header (1001) */
        position: fixed !important;
        top: 65px !important;
        left: 10px !important;
        right: 10px !important;
        width: auto !important;
        max-height: 50vh !important;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15) !important;
        border-radius: 8px !important;
    }

    /* Ensure header has proper z-index */
    .header-main {
        z-index: 1001 !important; /* Keep original header z-index */
    }

    /* Fix mobile header padding */
    .header-main .container-fluid {
        padding: 0.5rem 1rem !important;
    }

    /* Mobile search bar improvements */
    .header-main .input-group {
        border-radius: 8px !important;
        overflow: hidden !important;
    }

    .header-main .input-group .form-control {
        border-radius: 0 !important;
        font-size: 16px !important; /* Prevent zoom on iOS */
    }

    .header-main .input-group-text {
        border-radius: 0 !important;
    }

    .header-main .input-group .btn {
        border-radius: 0 !important;
    }
}
</style>
