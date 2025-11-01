<!-- Header Section starts -->
<header class="header-main">
    <div class="container-fluid">
        <div class="row">
            <div class="col-8 col-sm-6 d-flex align-items-center header-left p-0">
                <span class="header-toggle ">
                    <i class="ph ph-list"></i>
                </span>

                <!-- Mobile: Solo lente di ingrandimento -->
                <div class="d-block d-md-none">
                    <button class="btn btn-link text-dark" id="searchToggle" data-bs-toggle="collapse" data-bs-target="#mobileSearchBar">
                        <i class="ti ti-search"></i>
                    </button>
                </div>

                <!-- Desktop: Barra di ricerca normale -->
                <div class="header-searchbar w-100 d-none d-md-block">
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
                        <a href="{{ route('dashboard') }}" class="d-block head-icon bg-light-dark rounded-circle f-s-22 p-2 d-flex align-items-center justify-content-center"
                           data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('dashboard.dashboard') }}" style="width: 40px; height: 40px;">
                            <img src="{{ asset('assets/images/dashboard.svg') }}" alt="Dashboard" style="width: 20px; height: 20px;">
                        </a>
                    </li>

                    <!-- Shortcuts - Solo per utenti autenticati -->
                    <li class="header-shortcuts">
                        <div class="flex-shrink-0 dropdown">
                            <a aria-expanded="false" class="d-block head-icon bg-light-dark rounded-circle f-s-22 p-2 d-flex align-items-center justify-content-center"
                               data-bs-toggle="dropdown"
                               href="#" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('header.shortcuts') }}" style="width: 40px; height: 40px;">
                                <i class="ph ph-lightning"></i>
                            </a>
                            <ul class="dropdown-menu header-card border-0">
                                <li class="dropdown-header">
                                    <h6 class="mb-0">
                                        <x-icon name="shortcuts" size="20" class="me-2" />{{ __('header.shortcuts') }}
                                    </h6>
                                </li>
                                <li class="dropdown-divider"></li>
                                <!-- 1. Scrivi Poesia - per tutti gli utenti autenticati -->
                                @auth
                                <li class="dropdown-item">
                                    <a href="{{ route('poems.create') }}" class="d-flex align-items-center text-decoration-none">
                                        <x-icon name="poetry" size="20" class="me-2 text-info" />
                                        <span>{{ __('dashboard.write_poem') }}</span>
                                    </a>
                                </li>
                                @endauth
                                <!-- 2. Crea Evento - per tutti gli utenti autenticati -->
                                @auth
                                <li class="dropdown-item">
                                    <a href="{{ route('events.create') }}" class="d-flex align-items-center text-decoration-none">
                                        <x-icon name="event" size="20" class="me-2 text-success" />
                                        <span>{{ __('dashboard.organize_event') }}</span>
                                    </a>
                                </li>
                                @endauth
                                <!-- 3. Carica Video - per tutti gli utenti autenticati -->
                                @auth
                                <li class="dropdown-item">
                                    <a href="{{ route('videos.upload') }}" class="d-flex align-items-center text-decoration-none">
                                        <x-icon name="media" size="20" class="me-2 text-warning" />
                                        <span>{{ __('dashboard.upload_performance') }}</span>
                                    </a>
                                </li>
                                @endauth
                                <!-- 4. Scrivi Articolo - per tutti gli utenti autenticati -->
                                @auth
                                <li class="dropdown-item">
                                    <a href="{{ route('articles.create') }}" class="d-flex align-items-center text-decoration-none">
                                        <x-icon name="article" size="20" class="me-2 text-primary" />
                                        <span>{{ __('dashboard.write_article') }}</span>
                                    </a>
                                </li>
                                @endauth
                                <!-- 5. Carica Foto - per tutti gli utenti autenticati -->
                                @auth
                                <li class="dropdown-item">
                                    <a href="{{ route('photos.create') }}" class="d-flex align-items-center text-decoration-none">
                                        <i class="ph ph-camera text-secondary me-2" style="font-size: 20px;"></i>
                                        <span>{{ __('dashboard.upload_photo') }}</span>
                                    </a>
                                </li>
                                @endauth
                            </ul>
                        </div>
                    </li>

                    <!-- Notifications - Solo per utenti autenticati -->
                    @auth
                    @livewire('notifications.notification-center')
                    @endauth



                    <!-- Theme Toggle - Per tutti gli utenti -->
                    <li class="header-dark">
                        <div class="sun-logo head-icon bg-light-dark rounded-circle f-s-22 p-2 d-flex align-items-center justify-content-center"
                             data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('header.dark_theme') }}" style="width: 40px; height: 40px;">
                            <i class="ph ph-moon-stars"></i>
                        </div>
                        <div class="moon-logo head-icon bg-light-dark rounded-circle f-s-22 p-2 d-flex align-items-center justify-content-center"
                             data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('header.light_theme') }}" style="width: 40px; height: 40px;">
                            <i class="ph ph-sun-dim"></i>
                        </div>
                    </li>

                    <!-- Language Selector - Per tutti gli utenti -->
                    <li class="header-language">
                        <div class="flex-shrink-0 dropdown" id="lang_selector">
                            <a aria-expanded="false" class="d-block head-icon ps-0"
                               data-bs-toggle="dropdown"
                               href="#" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ __('header.language_selector') }}">
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

                    <!-- Wiki - Prossimamente - Solo per utenti autenticati
                    <!--
                    <li class="header-wiki">
                        <a href="#" class="d-block head-icon bg-light-dark rounded-circle f-s-22 p-2 disabled d-flex align-items-center justify-content-center"
                           data-bs-toggle="tooltip" data-bs-placement="bottom" title="Wiki (Prossimamente)"
                           style="opacity: 0.5; cursor: not-allowed; width: 40px; height: 40px;">
                            <i class="ph ph-book-open"></i>
                        </a>
                    </li>-->

                    <!-- Corsi - Prossimamente - Solo per utenti autenticati
                    <li class="header-courses">
                        <a href="#" class="d-block head-icon bg-light-dark rounded-circle f-s-22 p-2 disabled d-flex align-items-center justify-content-center"
                           data-bs-toggle="tooltip" data-bs-placement="bottom" title="Corsi (Prossimamente)"
                           style="opacity: 0.5; cursor: not-allowed; width: 40px; height: 40px;">
                            <i class="ph ph-graduation-cap"></i>
                        </a>
                    </li>-->

                    <!-- Forum - Prossimamente - Solo per utenti autenticati
                    <li class="header-forum">
                        <a href="#" class="d-block head-icon bg-light-dark rounded-circle f-s-22 p-2 disabled d-flex align-items-center justify-content-center"
                           data-bs-toggle="tooltip" data-bs-placement="bottom" title="Forum (Prossimamente)"
                           style="opacity: 0.5; cursor: not-allowed; width: 40px; height: 40px;">
                            <i class="ph ph-chats-circle"></i>
                        </a>
                    </li>-->

                    <!-- Impostazioni - Solo per utenti autenticati -->
                    
                    <!-- Profilo Utente - Solo per utenti autenticati -->
                    

                    @endauth

                </ul>
            </div>
        </div>

    </div>
</header>

<!-- Mobile Search Bar - Appare sotto la topbar -->
<div class="collapse d-md-none" id="mobileSearchBar">
    <div class="bg-white border-bottom p-3">
        <form action="{{ route('search.index') }}" method="GET" class="app-form">
            <div class="position-relative">
                <input aria-label="Search" class="form-control" placeholder="{{ __('search.search_placeholder') }}" name="q" type="search">
                <i class="ti ti-search text-dark position-absolute top-50 end-0 translate-middle-y me-3"></i>
            </div>
        </form>
    </div>
</div>
<!-- Header Section ends -->
