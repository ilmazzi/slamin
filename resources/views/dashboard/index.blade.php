@extends('layout.master')
@section('title', __('dashboard.dashboard') . ' - Slam In')
@section('css')
    <!-- apexcharts css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/apexcharts/apexcharts.css') }}">
@endsection

@section('main-content')
    <div class="container-fluid">
        <style>
        /* Personalizzazioni colori teal per Slam In */
        .bg-primary-teal {
            background-color: rgb(15, 98, 106) !important;
        }

        .text-primary-teal {
            color: rgb(15, 98, 106) !important;
        }

        .bg-light-primary-teal {
            background-color: rgba(15, 98, 106, 0.1) !important;
            color: rgb(15, 98, 106) !important;
        }

        .border-primary-teal {
            border-color: rgb(15, 98, 106) !important;
        }

        /* User Welcome Section */
        .user-welcome-card {
            background: linear-gradient(135deg, rgb(15, 98, 106) 0%, rgb(12, 78, 85) 100%);
            border: none;
            color: white;
        }

        .user-roles .badge {
            margin-right: 0.5rem;
        }

        /* Quick Actions Cards - Clean & Professional */
        .quick-action-card {
            transition: all 0.3s ease;
            border: 2px solid rgba(15, 98, 106, 0.1);
            text-decoration: none !important;
            color: #333;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            position: relative;
        }

        .quick-action-card:hover {
            transform: translateY(-5px);
            border-color: var(--card-color);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            color: #333;
            text-decoration: none !important;
        }

        .quick-action-icon {
            width: 70px;
            height: 70px;
            background: var(--card-color);
            color: white;
            border-radius: 50%;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
        }

        .quick-action-card:hover .quick-action-icon {
            transform: scale(1.1);
            box-shadow: 0 8px 20px var(--card-color-shadow);
        }

        /* Icon colors with shadow variants */
        .action-create {
            --card-color: rgb(15, 98, 106);
            --card-color-shadow: rgba(15, 98, 106, 0.3);
        }

        .action-write {
            --card-color: #e74c3c;
            --card-color-shadow: rgba(231, 76, 60, 0.3);
        }

        .action-upload {
            --card-color: #3498db;
            --card-color-shadow: rgba(52, 152, 219, 0.3);
        }

        .action-organize {
            --card-color: #2ecc71;
            --card-color-shadow: rgba(46, 204, 113, 0.3);
        }

        .action-find {
            --card-color: #f39c12;
            --card-color-shadow: rgba(243, 156, 18, 0.3);
        }

        .action-manage {
            --card-color: #9b59b6;
            --card-color-shadow: rgba(155, 89, 182, 0.3);
        }



        /* Language Switcher in Header */
        .header-language .dropdown-toggle {
            border: none;
            background: transparent;
            color: inherit;
        }

        .header-language .dropdown-menu {
            border: none;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }
            </style>

        <!-- User Welcome Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card user-welcome-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h2 class="text-white mb-2">{{ __('dashboard.welcome', ['name' => $user->name]) }}</h2>
                                <p class="text-white-50 mb-2">{{ $user->email }}</p>
                                <div class="user-roles">
                                    @foreach($user->getRoleNames() as $role)
                                        <span class="badge bg-light text-dark me-2">
                                            {{ __('auth.role_' . $role) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-4 text-end">
                                <span class="h1 text-white-50">🎭</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-6 col-md-3">
                <div class="card">
                    <span class="bg-primary-teal h-50 w-50 d-flex-center rounded-circle m-auto eshop-icon-box">
                        <i class="ph ph-article f-s-24"></i>
                    </span>
                    <div class="card-body eshop-cards">
                        <span class="ripple-effect"></span>
                        <div class="overflow-hidden">
                            <h3 class="text-primary-teal mb-0">{{ $stats['organized_events'] }}</h3>
                            <p class="mg-b-35 f-w-600 text-dark-800 txt-ellipsis-1">Eventi Organizzati</p>
                            <span class="badge bg-light-primary-teal">🎭 Organizer</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card">
                    <span class="bg-danger h-50 w-50 d-flex-center rounded-circle m-auto eshop-icon-box">
                        <i class="ph ph-heart f-s-24"></i>
                    </span>
                    <div class="card-body eshop-cards">
                        <span class="ripple-effect"></span>
                        <div class="overflow-hidden">
                            <h3 class="text-danger mb-0">{{ $stats['participated_events'] }}</h3>
                            <p class="mg-b-35 f-w-600 text-dark-800 txt-ellipsis-1">Eventi Partecipati</p>
                            <span class="badge bg-light-danger">🎤 Partecipante</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card">
                    <span class="bg-success h-50 w-50 d-flex-center rounded-circle m-auto eshop-icon-box">
                        <i class="ph ph-users f-s-24"></i>
                    </span>
                    <div class="card-body eshop-cards">
                        <span class="ripple-effect"></span>
                        <div class="overflow-hidden">
                            <h3 class="text-success mb-0">{{ $stats['pending_invitations'] }}</h3>
                            <p class="mg-b-35 f-w-600 text-dark-800 txt-ellipsis-1">Inviti in Attesa</p>
                            <span class="badge bg-light-success">📨 Inviti</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 col-md-3">
                <div class="card">
                    <span class="bg-warning h-50 w-50 d-flex-center rounded-circle m-auto eshop-icon-box">
                        <i class="ph ph-calendar f-s-24"></i>
                    </span>
                    <div class="card-body eshop-cards">
                        <span class="ripple-effect"></span>
                        <div class="overflow-hidden">
                            <h3 class="text-warning mb-0">{{ $stats['unread_notifications'] }}</h3>
                            <p class="mg-b-35 f-w-600 text-dark-800 txt-ellipsis-1">Notifiche Non Lette</p>
                            <span class="badge bg-light-warning">🔔 Notifiche</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

                <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <h4 class="mb-4 text-primary-teal border-bottom border-primary-teal pb-2">
                    <i class="ph ph-lightning me-2"></i>{{ __('dashboard.quick_actions') }}
                </h4>
            </div>

            @php
                $actionColors = [
                    'create_post' => 'action-create',
                    'write_poem' => 'action-write',
                    'upload_performance' => 'action-upload',
                    'organize_event' => 'action-organize',
                    'find_events' => 'action-find',
                    'manage_venue' => 'action-manage'
                ];

                $actionLinks = [
                    'create_post' => '#', // TODO: Add route when posts system is implemented
                    'write_poem' => '#', // TODO: Add route when poems system is implemented
                    'upload_performance' => '#', // TODO: Add route when performances system is implemented
                    'organize_event' => route('events.create'),
                    'find_events' => route('events.index'),
                    'manage_venue' => '#' // TODO: Add route when venues system is implemented
                ];
            @endphp

            @foreach($quickActions as $action)
                <div class="col-6 col-md-4 col-lg-3 mb-4">
                    <a href="{{ $actionLinks[$action['key']] ?? '#' }}" class="quick-action-card {{ $actionColors[$action['key']] ?? 'action-create' }} d-block h-100 text-decoration-none">
                        <div class="p-4 text-center position-relative">
                            <div class="quick-action-icon m-auto mb-3">
                                <i class="{{ $action['icon'] }}"></i>
                            </div>
                            <h6 class="mb-1 fw-bold text-dark">{{ __('dashboard.' . $action['key']) }}</h6>
                            <small class="text-muted">{{ __('dashboard.' . $action['key'] . '_desc') }}</small>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <!-- Main Content Row -->
        <div class="row">
            <!-- Recent Activity -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ph ph-bell me-2"></i>{{ __('dashboard.recent_activity') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(count($recentActivity) > 0)
                            @foreach($recentActivity as $activity)
                                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                    <div class="flex-shrink-0">
                                        <span class="bg-light-primary-teal h-40 w-40 d-flex-center rounded-circle">
                                            <i class="ph ph-bell"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <p class="mb-1">{{ $activity['message'] }}</p>
                                        <small class="text-muted">{{ $activity['time'] }}</small>
                                    </div>
                                </div>
                            @endforeach
                            <div class="text-center">
                                <a href="#" class="btn btn-outline-primary btn-sm">
                                    {{ __('dashboard.view_all_activity') }}
                                </a>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="ph ph-bell-slash f-s-48 text-muted mb-3 d-block"></i>
                                <p class="text-muted">{{ __('dashboard.no_recent_activity') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Upcoming Events -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ph ph-calendar me-2"></i>{{ __('dashboard.upcoming_events') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @if(count($upcomingEvents) > 0)
                            @foreach($upcomingEvents as $event)
                                <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                    <div class="flex-shrink-0">
                                        <span class="bg-light-warning h-40 w-40 d-flex-center rounded-circle">
                                            <i class="ph ph-calendar-check"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1 ms-3">
                                        <h6 class="mb-1">{{ $event['title'] }}</h6>
                                        <p class="mb-1 text-muted">{{ $event['date'] }}</p>
                                        <small>{{ $event['venue'] }}</small>
                                    </div>
                                </div>
                            @endforeach
                            <div class="text-center">
                                <a href="#" class="btn btn-outline-warning btn-sm">
                                    {{ __('dashboard.view_calendar') }}
                                </a>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="ph ph-calendar-x f-s-48 text-muted mb-3 d-block"></i>
                                <p class="text-muted">{{ __('dashboard.no_upcoming_events') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Role-Specific Sections -->
        @if(isset($roleContent['poet']))
            <div class="row mb-4">
                <div class="col-12">
                    <h4 class="mb-3 text-primary-teal border-bottom border-primary-teal pb-2">
                        <i class="ph ph-pen-nib me-2"></i>{{ __('dashboard.poet_section') }}
                    </h4>
                </div>
                <!-- TODO: Poet-specific content -->
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="ph ph-pen-nib f-s-48 text-primary-teal mb-3"></i>
                            <h5 class="text-primary-teal">{{ __('dashboard.poet_section') }}</h5>
                            <p class="text-muted">Sezione specifica per poeti in sviluppo...</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(isset($roleContent['venue_owner']))
            <div class="row mb-4">
                <div class="col-12">
                    <h4 class="mb-3 text-primary-teal border-bottom border-primary-teal pb-2">
                        <i class="ph ph-buildings me-2"></i>{{ __('dashboard.venue_section') }}
                    </h4>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="ph ph-buildings f-s-48 text-primary-teal mb-3"></i>
                            <h5 class="text-primary-teal">{{ __('dashboard.venue_section') }}</h5>
                            <p class="text-muted">Sezione gestione venue in sviluppo...</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(isset($roleContent['organizer']))
            <div class="row mb-4">
                <div class="col-12">
                    <h4 class="mb-3 text-primary-teal border-bottom border-primary-teal pb-2">
                        <i class="ph ph-calendar-plus me-2"></i>{{ __('dashboard.organizer_section') }}
                    </h4>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="ph ph-calendar-plus f-s-48 text-primary-teal mb-3"></i>
                            <h5 class="text-primary-teal">{{ __('dashboard.organizer_section') }}</h5>
                            <p class="text-muted">Sezione organizzatori eventi in sviluppo...</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
