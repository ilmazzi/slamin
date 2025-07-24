<!-- Header Section starts -->
<header class="header-main">
    <div class="container-fluid">
        <div class="row">
            <div class="col-6 d-flex align-items-center header-left p-0">
                <span class="header-toggle">
                    <i class="ph ph-squares-four"></i>
                </span>
            </div>

            <div class="col-6 d-flex align-items-center justify-content-end header-right p-0">
                <ul class="d-flex align-items-center">

                    @auth
                    <!-- Dashboard - Solo per utenti autenticati -->
                    <li class="header-dashboard">
                        <a href="{{ route('dashboard') }}" class="d-block head-icon bg-light-dark rounded-circle f-s-22 p-2"
                           data-bs-toggle="tooltip" data-bs-placement="bottom" title="Dashboard">
                            <i class="ph ph-gauge"></i>
                        </a>
                    </li>

                    <!-- Shortcuts - Solo per utenti autenticati -->
                    <li class="header-shortcuts">
                        <div class="flex-shrink-0 dropdown">
                            <a aria-expanded="false" class="d-block head-icon bg-light-dark rounded-circle f-s-22 p-2"
                               data-bs-toggle="dropdown"
                               href="#" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Scorciatoie">
                                <i class="ph ph-lightning"></i>
                            </a>
                            <ul class="dropdown-menu header-card border-0">
                                <li class="dropdown-header">
                                    <h6 class="mb-0">
                                        <i class="ph ph-lightning me-2"></i>Scorciatoie
                                    </h6>
                                </li>
                                <li class="dropdown-divider"></li>
                                <li class="dropdown-item">
                                    <a href="{{ route('peertube.upload-video') }}" class="d-flex align-items-center text-decoration-none">
                                        <i class="ph ph-video-camera me-2 text-primary"></i>
                                        <span>Carica Video</span>
                                    </a>
                                </li>
                                <li class="dropdown-item">
                                    <a href="{{ route('events.create') }}" class="d-flex align-items-center text-decoration-none">
                                        <i class="ph ph-calendar-plus me-2 text-success"></i>
                                        <span>Crea Evento</span>
                                    </a>
                                </li>
                                <li class="dropdown-item">
                                    <a href="{{ route('profile.edit') }}" class="d-flex align-items-center text-decoration-none">
                                        <i class="ph ph-user-circle me-2 text-info"></i>
                                        <span>Profilo</span>
                                    </a>
                                </li>
                                @if(auth()->user()->hasRole('admin'))
                                <li class="dropdown-divider"></li>
                                <li class="dropdown-item">
                                    <a href="{{ route('admin.kanban.index') }}" class="d-flex align-items-center text-decoration-none">
                                        <i class="ph ph-shield-check me-2 text-warning"></i>
                                        <span>Admin Panel</span>
                                    </a>
                                </li>
                                @endif
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
                           data-bs-toggle="tooltip" data-bs-placement="bottom" title="Notifiche">
                            <i class="ph ph-bell"></i>
                            <!-- Dynamic notification badge -->
                            <span id="notificationBadge" class="position-absolute translate-middle badge rounded-pill bg-danger badge-notification" style="display: none;">0</span>
                        </a>
                        <div aria-labelledby="notificationcanvasRightLabel"
                             class="offcanvas offcanvas-end header-notification-canvas"
                             id="notificationcanvasRight" tabindex="-1">
                            <div class="offcanvas-header">
                                <h5 class="offcanvas-title" id="notificationcanvasRightLabel">
                                    <i class="ph ph-bell me-2"></i>Notifiche
                                    <span id="notificationCount" class="badge bg-primary ms-2">0</span>
                                </h5>
                                <div class="d-flex align-items-center">
                                    <button class="btn btn-outline-primary btn-sm me-2" onclick="markAllNotificationsRead()" title="Segna tutte come lette">
                                        <i class="ph ph-check-circle"></i>
                                    </button>
                                    <button aria-label="Close" class="btn-close" data-bs-dismiss="offcanvas" type="button"></button>
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
                                <div id="notificationsList" class="head-container" style="display: none;">
                                    <!-- Dynamic notifications loaded via JavaScript -->
                                </div>

                                <!-- Empty state -->
                                <div id="notificationsEmpty" class="text-center p-4" style="display: none;">
                                    <i class="ph ph-bell-slash display-4 text-muted mb-3"></i>
                                    <h6 class="text-muted">Nessuna notifica</h6>
                                    <p class="text-muted small">Le tue notifiche appariranno qui</p>
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
                             data-bs-toggle="tooltip" data-bs-placement="bottom" title="Tema Scuro">
                            <i class="ph ph-moon-stars"></i>
                        </div>
                        <div class="moon-logo head-icon bg-light-dark rounded-circle f-s-22 p-2"
                             data-bs-toggle="tooltip" data-bs-placement="bottom" title="Tema Chiaro">
                            <i class="ph ph-sun-dim"></i>
                        </div>
                    </li>

                    <!-- Language Selector - Per tutti gli utenti -->
                    <li class="header-language">
                        <div class="flex-shrink-0 dropdown" id="lang_selector">
                            <a aria-expanded="false" class="d-block head-icon ps-0"
                               data-bs-toggle="dropdown"
                               href="#" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lingua">
                                <div class="lang-flag lang-{{ app()->getLocale() }}">
                                    <span class="flag rounded-circle overflow-hidden">
                                        <i class="flag-icon flag-icon-{{
                                            app()->getLocale() == 'en' ? 'gbr' :
                                            (app()->getLocale() == 'de' ? 'deu' :
                                            (app()->getLocale() == 'es' ? 'esp' :
                                            (app()->getLocale() == 'fr' ? 'fra' : 'ita')))
                                        }}"></i>
                                    </span>
                                </div>
                            </a>
                            <ul class="dropdown-menu language-dropdown header-card border-0">
                                <li class="lang lang-it {{ app()->getLocale() == 'it' ? 'selected' : '' }} dropdown-item p-2" data-bs-placement="top" data-bs-toggle="tooltip" title="IT">
                                    <a href="{{ url()->current() }}?lang=it" class="d-flex align-items-center text-decoration-none">
                                        <i class="flag-icon flag-icon-ita flag-icon-squared rounded-circle f-s-20"></i>
                                        <span class="ps-2">Italiano</span>
                                    </a>
                                </li>
                                <li class="lang lang-en {{ app()->getLocale() == 'en' ? 'selected' : '' }} dropdown-item p-2" title="EN">
                                    <a href="{{ url()->current() }}?lang=en" class="d-flex align-items-center text-decoration-none">
                                        <i class="flag-icon flag-icon-gbr flag-icon-squared rounded-circle f-s-20"></i>
                                        <span class="ps-2">English</span>
                                    </a>
                                </li>
                                <li class="lang lang-fr {{ app()->getLocale() == 'fr' ? 'selected' : '' }} dropdown-item p-2" title="FR">
                                    <a href="{{ url()->current() }}?lang=fr" class="d-flex align-items-center text-decoration-none">
                                        <i class="flag-icon flag-icon-fra flag-icon-squared rounded-circle f-s-20"></i>
                                        <span class="ps-2">Français</span>
                                    </a>
                                </li>
                                <li class="lang lang-es {{ app()->getLocale() == 'es' ? 'selected' : '' }} dropdown-item p-2" title="ES">
                                    <a href="{{ url()->current() }}?lang=es" class="d-flex align-items-center text-decoration-none">
                                        <i class="flag-icon flag-icon-esp flag-icon-squared rounded-circle f-s-20"></i>
                                        <span class="ps-2">Español</span>
                                    </a>
                                </li>
                                <li class="lang lang-de {{ app()->getLocale() == 'de' ? 'selected' : '' }} dropdown-item p-2" title="DE">
                                    <a href="{{ url()->current() }}?lang=de" class="d-flex align-items-center text-decoration-none">
                                        <i class="flag-icon flag-icon-deu flag-icon-squared rounded-circle f-s-20"></i>
                                        <span class="ps-2">Deutsch</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    @auth
                    <!-- Wiki - Prossimamente - Solo per utenti autenticati -->
                    <li class="header-wiki">
                        <a href="#" class="d-block head-icon bg-light-dark rounded-circle f-s-22 p-2 disabled"
                           data-bs-toggle="tooltip" data-bs-placement="bottom" title="Wiki (Prossimamente)"
                           style="opacity: 0.5; cursor: not-allowed;">
                            <i class="ph ph-book-open"></i>
                        </a>
                    </li>

                    <!-- Corsi - Prossimamente - Solo per utenti autenticati -->
                    <li class="header-courses">
                        <a href="#" class="d-block head-icon bg-light-dark rounded-circle f-s-22 p-2 disabled"
                           data-bs-toggle="tooltip" data-bs-placement="bottom" title="Corsi (Prossimamente)"
                           style="opacity: 0.5; cursor: not-allowed;">
                            <i class="ph ph-graduation-cap"></i>
                        </a>
                    </li>

                    <!-- Forum - Prossimamente - Solo per utenti autenticati -->
                    <li class="header-forum">
                        <a href="#" class="d-block head-icon bg-light-dark rounded-circle f-s-22 p-2 disabled"
                           data-bs-toggle="tooltip" data-bs-placement="bottom" title="Forum (Prossimamente)"
                           style="opacity: 0.5; cursor: not-allowed;">
                            <i class="ph ph-chats-circle"></i>
                        </a>
                    </li>
                    @endauth

                </ul>
            </div>
        </div>
    </div>
</header>
<!-- Header Section ends -->
