@extends('layout.master')
@section('title', __('dashboard.dashboard') . ' - Slam In')

@section('main-content')
    <div class="container-fluid">

        <!-- User Welcome Card semplificata -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card bg-primary text-white hover-effect">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="text-white mb-1 f-w-600">{{ __('dashboard.welcome', ['name' => $user->getDisplayName()]) }}</h4>
                                <p class="text-white-50 mb-2 f-s-14">{{ $user->email }}</p>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($user->getRoleNames() as $role)
                                        <span class="badge bg-light text-dark f-s-12">
                                            {{ __('auth.role_' . $role) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <div class="bg-white-500 h-50 w-50 d-flex-center rounded-circle ms-auto">
                                    <i class="ph ph-user f-s-24 text-primary"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards compatte -->
        <div class="row mb-3">
            <div class="col-6 col-md-3">
                <div class="card hover-effect equal-card b-t-4-primary">
                    <div class="card-body eshop-cards text-center pa-20">
                        <div class="bg-light-primary h-45 w-45 d-flex-center rounded-circle m-auto mb-2">
                            <i class="ph ph-article f-s-20 text-primary"></i>
                        </div>
                        <span class="ripple-effect"></span>
                        <div class="overflow-hidden">
                            <h3 class="text-primary mb-1 f-w-600">{{ $stats['organized_events'] }}</h3>
                            <p class="f-w-500 text-dark-800 f-s-13 mb-1">Eventi Organizzati</p>
                            <span class="badge bg-light-primary f-s-11">🎭 Organizer</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card hover-effect equal-card b-t-4-danger">
                    <div class="card-body eshop-cards text-center pa-20">
                        <div class="bg-light-danger h-45 w-45 d-flex-center rounded-circle m-auto mb-2">
                            <i class="ph ph-heart f-s-20 text-danger"></i>
                        </div>
                        <span class="ripple-effect"></span>
                        <div class="overflow-hidden">
                            <h3 class="text-danger mb-1 f-w-600">{{ $stats['participated_events'] }}</h3>
                            <p class="f-w-500 text-dark-800 f-s-13 mb-1">Eventi Partecipati</p>
                            <span class="badge bg-light-danger f-s-11">🎤 Partecipante</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card hover-effect equal-card b-t-4-success">
                    <div class="card-body eshop-cards text-center pa-20">
                        <div class="bg-light-success h-45 w-45 d-flex-center rounded-circle m-auto mb-2">
                            <i class="ph ph-users f-s-20 text-success"></i>
                        </div>
                        <span class="ripple-effect"></span>
                        <div class="overflow-hidden">
                            <h3 class="text-success mb-1 f-w-600">{{ $stats['pending_invitations'] }}</h3>
                            <p class="f-w-500 text-dark-800 f-s-13 mb-1">Inviti in Attesa</p>
                            <span class="badge bg-light-success f-s-11">📨 Inviti</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card hover-effect equal-card b-t-4-warning">
                    <div class="card-body eshop-cards text-center pa-20">
                        <div class="bg-light-warning h-45 w-45 d-flex-center rounded-circle m-auto mb-2">
                            <i class="ph ph-bell f-s-20 text-warning"></i>
                        </div>
                        <span class="ripple-effect"></span>
                        <div class="overflow-hidden">
                            <h3 class="text-warning mb-1 f-w-600">{{ $stats['unread_notifications'] }}</h3>
                            <p class="f-w-500 text-dark-800 f-s-13 mb-1">Notifiche Non Lette</p>
                            <span class="badge bg-light-warning f-s-11">🔔 Notifiche</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Titolo Azioni Rapide -->
        <div class="text-center mb-3">
            <h5 class="text-primary mb-2 f-w-600">
                <i class="ph ph-lightning me-2"></i>{{ __('dashboard.quick_actions') }}
            </h5>
            <hr class="w-25 mx-auto border-primary border-2 opacity-25">
        </div>

        <!-- Quick Actions compatte -->
        <div class="row mb-4">
            @php
                $actionCards = [
                    [
                        'key' => 'organize_event',
                        'color' => 'success',
                        'icon' => 'ph ph-calendar-plus',
                        'link' => route('events.create')
                    ],
                    [
                        'key' => 'find_events',
                        'color' => 'warning',
                        'icon' => 'ph ph-search',
                        'link' => route('events.index')
                    ],
                    [
                        'key' => 'create_post',
                        'color' => 'primary',
                        'icon' => 'ph ph-plus-circle',
                        'link' => '#'
                    ],
                    [
                        'key' => 'write_poem',
                        'color' => 'danger',
                        'icon' => 'ph ph-pen-nib',
                        'link' => '#'
                    ],
                    [
                        'key' => 'upload_performance',
                        'color' => 'info',
                        'icon' => 'ph ph-upload',
                        'link' => '#'
                    ],
                    [
                        'key' => 'manage_venue',
                        'color' => 'secondary',
                        'icon' => 'ph ph-buildings',
                        'link' => '#'
                    ]
                ];
            @endphp

            @foreach($actionCards as $action)
                <div class="col-6 col-md-4 col-lg-2 mb-3">
                    <a href="{{ $action['link'] }}" class="card hover-effect h-100 text-decoration-none">
                        <div class="card-body text-center pa-15">
                            <div class="bg-light-{{ $action['color'] }} h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                                <i class="{{ $action['icon'] }} text-{{ $action['color'] }} f-s-18"></i>
                            </div>
                            <h6 class="mb-1 fw-bold text-dark f-s-13">{{ __('dashboard.' . $action['key']) }}</h6>
                            <small class="text-muted f-s-11">{{ __('dashboard.' . $action['key'] . '_desc') }}</small>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <!-- Dashboard Overview -->
        <div class="row">
            <!-- Recent Activity compatta -->
            <div class="col-lg-6 mb-4">
                <div class="card hover-effect equal-card">
                    <!-- Solo ribbon importante per novità -->
                    <div class="ribbon-top top-left ribbon-primary">
                        <i class="ph ph-sparkle f-s-12"></i>
                    </div>
                    <div class="card-header">
                        <h6 class="card-title mb-0 f-w-600">
                            <i class="ph ph-bell me-2 text-primary"></i>{{ __('dashboard.recent_activity') }}
                        </h6>
                    </div>
                    <div class="card-body pa-20">
                        @if(count($recentActivity) > 0)
                            @foreach($recentActivity as $activity)
                                <div class="d-flex align-items-center mb-2 pb-2 border-bottom">
                                    <div class="flex-shrink-0">
                                        <div class="bg-light-primary h-35 w-35 d-flex-center rounded-circle">
                                            <i class="ph ph-bell text-primary f-s-14"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="mb-0 fw-500 f-s-14">{{ $activity['message'] }}</p>
                                        <small class="text-muted f-s-12">{{ $activity['time'] }}</small>
                                    </div>
                                </div>
                            @endforeach
                            <div class="text-center mt-3">
                                <a href="#" class="btn btn-light-primary btn-sm">
                                    <i class="ph ph-eye me-1"></i>{{ __('dashboard.view_all_activity') }}
                                </a>
                            </div>
                        @else
                            <div class="text-center py-3">
                                <div class="bg-light-primary h-50 w-50 d-flex-center rounded-circle m-auto mb-2">
                                    <i class="ph ph-bell-slash f-s-24 text-primary"></i>
                                </div>
                                <p class="text-muted f-s-14 mb-0">{{ __('dashboard.no_recent_activity') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Upcoming Events compatta -->
            <div class="col-lg-6 mb-4">
                <div class="card hover-effect equal-card">
                    <div class="card-header">
                        <h6 class="card-title mb-0 f-w-600">
                            <i class="ph ph-calendar me-2 text-warning"></i>{{ __('dashboard.upcoming_events') }}
                        </h6>
                    </div>
                    <div class="card-body pa-20">
                        @if(count($upcomingEvents) > 0)
                            @foreach($upcomingEvents as $event)
                                <div class="d-flex align-items-center mb-2 pb-2 border-bottom">
                                    <div class="flex-shrink-0">
                                        <div class="bg-light-warning h-35 w-35 d-flex-center rounded-circle">
                                            <i class="ph ph-calendar-check text-warning f-s-14"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-0 fw-600 f-s-14">{{ $event['title'] }}</h6>
                                        <p class="mb-0 text-muted f-s-12">{{ $event['date'] }}</p>
                                        <small class="text-secondary f-s-11">{{ $event['venue'] }}</small>
                                    </div>
                                </div>
                            @endforeach
                            <div class="text-center mt-3">
                                <a href="#" class="btn btn-light-warning btn-sm">
                                    <i class="ph ph-calendar me-1"></i>{{ __('dashboard.view_calendar') }}
                                </a>
                            </div>
                        @else
                            <div class="text-center py-3">
                                <div class="bg-light-warning h-50 w-50 d-flex-center rounded-circle m-auto mb-2">
                                    <i class="ph ph-calendar-x f-s-24 text-warning"></i>
                                </div>
                                <p class="text-muted f-s-14 mb-0">{{ __('dashboard.no_upcoming_events') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Role-Specific Sections compatte -->
        @if(isset($roleContent['poet']))
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card card-light-success hover-effect">
                        <div class="card-body text-center pa-25">
                            <div class="bg-success h-50 w-50 d-flex-center rounded-circle m-auto mb-2">
                                <i class="ph ph-pen-nib f-s-24 text-white"></i>
                            </div>
                            <h6 class="text-success f-w-600 mb-1">{{ __('dashboard.poet_section') }}</h6>
                            <p class="text-muted f-s-13 mb-0">Sezione specifica per poeti in sviluppo...</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(isset($roleContent['venue_owner']))
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card card-light-info hover-effect">
                        <div class="card-body text-center pa-25">
                            <div class="bg-info h-50 w-50 d-flex-center rounded-circle m-auto mb-2">
                                <i class="ph ph-buildings f-s-24 text-white"></i>
                            </div>
                            <h6 class="text-info f-w-600 mb-1">{{ __('dashboard.venue_section') }}</h6>
                            <p class="text-muted f-s-13 mb-0">Sezione gestione venue in sviluppo...</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(isset($roleContent['organizer']))
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card card-light-danger hover-effect">
                        <div class="card-body text-center pa-25">
                            <div class="bg-danger h-50 w-50 d-flex-center rounded-circle m-auto mb-2">
                                <i class="ph ph-calendar-plus f-s-24 text-white"></i>
                            </div>
                            <h6 class="text-danger f-w-600 mb-1">{{ __('dashboard.organizer_section') }}</h6>
                            <p class="text-muted f-s-13 mb-0">Sezione organizzatori eventi in sviluppo...</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
