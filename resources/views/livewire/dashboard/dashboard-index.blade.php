<div>
    <!-- Mobile First Layout -->
    <div class="container-fluid">
        
        <!-- 1. WELCOME CARD - Mobile First -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card hover-effect b-e-4-primary">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <!-- Mobile: Stack vertically, Desktop: Side by side -->
                            <div class="col-12 col-md-8">
                                <h4 class="mb-1 f-w-600">{{ __('dashboard.welcome', ['name' => $user->getDisplayName()]) }}</h4>
                                <p class="text-primary-50 mb-2 f-s-14">{{ $user->getName() }}</p>
                                <div class="d-flex flex-wrap gap-1">
                                    @foreach($user->getRoleNames() as $role)
                                        <span class="badge bg-light-success text-dark f-s-12">
                                            {{ __('auth.role_' . $role) ?: ucfirst($role) }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                            <!-- Mobile: Center avatar, Desktop: Right align -->
                            <div class="col-12 col-md-4 text-center text-md-end mt-3 mt-md-0">
                                <div class="bg-white-500 h-50 w-50 d-flex-center rounded-circle mx-auto ms-md-auto">
                                    <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($user) }}"
                                         alt="{{ $user->getDisplayName() }}"
                                         class="rounded-circle"
                                         style="width: 90px; height: 90px; object-fit: cover;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. STATISTICHE - Mobile First Grid 2x2 -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card hover-effect equal-card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="card-title mb-0 f-w-600">
                                <i class="ph ph-chart-bar me-2 text-primary"></i>{{ __('dashboard.statistics') }}
                            </h6>
                            <a href="{{ route('user-stats.index') }}" class="btn btn-sm btn-outline-primary d-none d-md-inline-block">
                                <i class="ph ph-chart-line me-1"></i>{{ __('dashboard.view_detailed_stats') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body pa-20">
                        <!-- Mobile: 2x2 grid, Desktop: 2x3 grid -->
                        <div class="row g-3">
                            <!-- Statistica 1 - Eventi Passati -->
                            <div class="col-6 col-lg-4">
                                <a href="{{ route('events.index', ['filter' => 'past']) }}" class="text-decoration-none">
                                    <div class="card hover-effect equal-card b-t-4-secondary">
                                        <div class="card-body eshop-cards text-center pa-15">
                                            <div class="bg-light-secondary h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                                                <i class="ph ph-clock-counter-clockwise f-s-18 text-secondary"></i>
                                            </div>
                                            <span class="ripple-effect"></span>
                                            <div class="overflow-hidden">
                                                <h4 class="text-secondary mb-1 f-w-600">{{ $stats['past_events'] }}</h4>
                                                <p class="f-w-500 text-dark f-s-12 mb-1">{{ __('dashboard.past_events') }}</p>
                                                <span class="badge bg-light-secondary f-s-10">{{ __('dashboard.role_history') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Statistica 2 - Eventi Futuri -->
                            <div class="col-6 col-lg-4">
                                <a href="{{ route('events.index', ['filter' => 'future']) }}" class="text-decoration-none">
                                    <div class="card hover-effect equal-card b-t-4-warning">
                                        <div class="card-body eshop-cards text-center pa-15">
                                            <div class="bg-light-warning h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                                                <i class="ph ph-calendar-check f-s-18 text-warning"></i>
                                            </div>
                                            <span class="ripple-effect"></span>
                                            <div class="overflow-hidden">
                                                <h4 class="text-warning mb-1 f-w-600">{{ $stats['future_events'] }}</h4>
                                                <p class="f-w-500 text-dark f-s-12 mb-1">{{ __('dashboard.future_events') }}</p>
                                                <span class="badge bg-light-warning f-s-10">{{ __('dashboard.role_upcoming') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Statistica 3 - Eventi Organizzati -->
                            <div class="col-6 col-lg-4">
                                <a href="{{ route('events.index', ['filter' => 'organized']) }}" class="text-decoration-none">
                                    <div class="card hover-effect equal-card b-t-4-primary">
                                        <div class="card-body eshop-cards text-center pa-15">
                                            <div class="bg-light-primary h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                                                <i class="ph ph-article f-s-18 text-primary"></i>
                                            </div>
                                            <span class="ripple-effect"></span>
                                            <div class="overflow-hidden">
                                                <h4 class="text-primary mb-1 f-w-600">{{ $stats['organized_events'] }}</h4>
                                                <p class="f-w-500 text-dark f-s-12 mb-1">{{ __('dashboard.organized_events') }}</p>
                                                <span class="badge bg-light-primary f-s-10">{{ __('dashboard.role_organizer') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Statistica 4 - Inviti in Attesa -->
                            <div class="col-6 col-lg-4">
                                <a href="{{ route('events.index', ['filter' => 'invitations']) }}" class="text-decoration-none">
                                    <div class="card hover-effect equal-card b-t-4-success">
                                        <div class="card-body eshop-cards text-center pa-15">
                                            <div class="bg-light-success h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                                                <i class="ph ph-envelope f-s-18 text-success"></i>
                                            </div>
                                            <span class="ripple-effect"></span>
                                            <div class="overflow-hidden">
                                                <h4 class="text-success mb-1 f-w-600">{{ $stats['pending_invitations'] }}</h4>
                                                <p class="f-w-500 text-dark f-s-12 mb-1">{{ __('dashboard.pending_invitations') }}</p>
                                                <span class="badge bg-light-success f-s-10">{{ __('dashboard.role_invitations') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Statistica 5 - Inviti ai Gruppi -->
                            <div class="col-6 col-lg-4">
                                <a href="{{ route('group-invitations.index') }}" class="text-decoration-none">
                                    <div class="card hover-effect equal-card b-t-4-primary">
                                        <div class="card-body eshop-cards text-center pa-15">
                                            <div class="bg-light-primary h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                                                <i class="ph ph-users f-s-18 text-primary"></i>
                                            </div>
                                            <span class="ripple-effect"></span>
                                            <div class="overflow-hidden">
                                                <h4 class="text-primary mb-1 f-w-600">{{ $stats['pending_group_invitations'] }}</h4>
                                                <p class="f-w-500 text-dark f-s-12 mb-1">{{ __('dashboard.group_invitations') }}</p>
                                                <span class="badge bg-light-primary f-s-10">{{ __('dashboard.groups') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Statistica 6 - Notifiche -->
                            <div class="col-6 col-lg-4">
                                <a href="{{ route('notifications.index') }}" class="text-decoration-none">
                                    <div class="card hover-effect equal-card b-t-4-info">
                                        <div class="card-body eshop-cards text-center pa-15">
                                            <div class="bg-light-info h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                                                <i class="ph ph-bell f-s-18 text-info"></i>
                                            </div>
                                            <span class="ripple-effect"></span>
                                            <div class="overflow-hidden">
                                                <h4 class="text-info mb-1 f-w-600">{{ $stats['unread_notifications'] }}</h4>
                                                <p class="f-w-500 text-dark f-s-12 mb-1">Notifiche</p>
                                                <span class="badge bg-light-info f-s-10">Nuove</span>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. CALENDARIO - Mobile First -->
        <div class="row mb-4">
            <div class="col-12 col-lg-8">
                <div class="card hover-effect equal-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="card-title mb-0 f-w-600">
                            <i class="ph ph-calendar me-2 text-warning"></i>{{ __('dashboard.my_calendar') }}
                        </h6>
                        <div class="d-flex gap-2">
                            <button class="btn btn-light-warning btn-sm" wire:click="previousMonth">
                                <i class="ph ph-caret-left"></i>
                            </button>
                            <button class="btn btn-light-warning btn-sm" wire:click="nextMonth">
                                <i class="ph ph-caret-right"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body pa-20">
                        <!-- Placeholder per calendario Livewire -->
                        <div class="text-center py-5">
                            <i class="ph ph-calendar f-s-48 text-muted mb-3"></i>
                            <h6 class="text-muted">Calendario in sviluppo</h6>
                            <p class="text-muted small">Il calendario interattivo sarà disponibile presto</p>
                        </div>
                        
                        <!-- Bottoni azione -->
                        <div class="text-center mt-3">
                            <div class="d-flex gap-2 justify-content-center flex-wrap">
                                <a href="{{ route('events.create') }}" class="btn btn-success btn-sm">
                                    <i class="ph ph-plus me-1"></i>{{ __('dashboard.create_event_button') }}
                                </a>
                                <a href="{{ route('calendar') }}" class="btn btn-light-warning btn-sm">
                                    <i class="ph ph-calendar me-1"></i>{{ __('dashboard.view_full_calendar') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 4. AZIONI RAPIDE - Mobile First -->
            <div class="col-12 col-lg-4">
                <div class="card hover-effect equal-card">
                    <div class="card-header">
                        <h6 class="card-title mb-0 f-w-600">
                            <i class="ph ph-lightning me-2 text-primary"></i>{{ __('dashboard.quick_actions') }}
                        </h6>
                    </div>
                    <div class="card-body pa-20">
                        <div class="d-grid gap-2">
                            @foreach($quickActions as $action)
                                <a href="{{ $action['url'] }}" class="btn btn-outline-{{ $action['color'] }} btn-sm d-flex align-items-center justify-content-start">
                                    <i class="{{ $action['icon'] }} me-2"></i>
                                    <span>{{ __('dashboard.' . $action['key']) }}</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. ATTIVITÀ RECENTI - Mobile First -->
        <div class="row mb-4">
            <div class="col-12 col-lg-6">
                <div class="card hover-effect equal-card">
                    <div class="card-header">
                        <h6 class="card-title mb-0 f-w-600">
                            <i class="ph ph-clock me-2 text-info"></i>{{ __('dashboard.recent_activity') }}
                        </h6>
                    </div>
                    <div class="card-body pa-20">
                        @if(count($recentActivity) > 0)
                            <div class="timeline">
                                @foreach($recentActivity as $activity)
                                    <div class="timeline-item d-flex align-items-start mb-3">
                                        <div class="timeline-marker bg-{{ $activity['color'] }} rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 32px; height: 32px; min-width: 32px;">
                                            <i class="{{ $activity['icon'] }} f-s-14 text-white"></i>
                                        </div>
                                        <div class="timeline-content flex-grow-1">
                                            <p class="mb-1 f-s-14">{{ $activity['message'] }}</p>
                                            <small class="text-muted">{{ $activity['time'] }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="ph ph-clock f-s-48 text-muted mb-3"></i>
                                <h6 class="text-muted">{{ __('dashboard.no_recent_activity') }}</h6>
                                <p class="text-muted small">{{ __('dashboard.start_activity_message') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- 6. EVENTI PROSSIMI - Mobile First -->
            <div class="col-12 col-lg-6">
                <div class="card hover-effect equal-card">
                    <div class="card-header">
                        <h6 class="card-title mb-0 f-w-600">
                            <i class="ph ph-calendar-check me-2 text-success"></i>{{ __('dashboard.future_events') }}
                        </h6>
                    </div>
                    <div class="card-body pa-20">
                        @if(count($upcomingEvents) > 0)
                            @foreach($upcomingEvents as $event)
                                <div class="d-flex align-items-center mb-3 p-3 border rounded">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 f-s-14">{{ $event['title'] }}</h6>
                                        <p class="mb-1 f-s-12 text-muted">{{ $event['date'] }}</p>
                                        <p class="mb-0 f-s-12 text-muted">{{ $event['venue'] }}, {{ $event['city'] }}</p>
                                    </div>
                                    <div class="ms-3">
                                        <span class="badge bg-{{ $event['type'] === 'organized' ? 'primary' : ($event['type'] === 'participating' ? 'success' : 'warning') }} f-s-10">
                                            {{ $event['type'] === 'organized' ? 'Organizzato' : ($event['type'] === 'participating' ? 'Partecipo' : 'Wishlist') }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <i class="ph ph-calendar f-s-48 text-muted mb-3"></i>
                                <h6 class="text-muted">Nessun evento prossimo</h6>
                                <p class="text-muted small">Non hai eventi in programma</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- 7. CONTENUTI SPECIFICI PER RUOLO - Mobile First -->
        @if(!empty($roleContent))
            <div class="row mb-4">
                <div class="col-12">
                    @foreach($roleContent as $role => $content)
                        <div class="card hover-effect equal-card mb-3">
                            <div class="card-header">
                                <h6 class="card-title mb-0 f-w-600">
                                    <i class="ph ph-user me-2 text-primary"></i>{{ ucfirst($role) }} Dashboard
                                </h6>
                            </div>
                            <div class="card-body pa-20">
                                @if($role === 'poet')
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <h6 class="f-s-14 mb-3">Eventi Prossimi</h6>
                                            @if($content['upcoming_events']->count() > 0)
                                                @foreach($content['upcoming_events'] as $event)
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="ph ph-calendar me-2 text-primary"></i>
                                                        <a href="{{ route('events.show', $event) }}" class="text-decoration-none f-s-12">{{ $event->title }}</a>
                                                    </div>
                                                @endforeach
                                            @else
                                                <p class="text-muted f-s-12">Nessun evento prossimo</p>
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <h6 class="f-s-14 mb-3">Statistiche Performance</h6>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="f-s-12">Eventi totali:</span>
                                                <span class="f-s-12 f-w-600">{{ $content['performance_stats']['total_events'] }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="f-s-12">Richieste in attesa:</span>
                                                <span class="f-s-12 f-w-600">{{ $content['performance_stats']['pending_applications'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($role === 'organizer')
                                    <div class="row">
                                        <div class="col-12 col-md-6">
                                            <h6 class="f-s-14 mb-3">I Miei Eventi</h6>
                                            @if($content['my_events']->count() > 0)
                                                @foreach($content['my_events'] as $event)
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="ph ph-calendar me-2 text-primary"></i>
                                                        <a href="{{ route('events.show', $event) }}" class="text-decoration-none f-s-12">{{ $event->title }}</a>
                                                    </div>
                                                @endforeach
                                            @else
                                                <p class="text-muted f-s-12">Nessun evento organizzato</p>
                                            @endif
                                        </div>
                                        <div class="col-12 col-md-6">
                                            <h6 class="f-s-14 mb-3">Statistiche Organizer</h6>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="f-s-12">Eventi totali:</span>
                                                <span class="f-s-12 f-w-600">{{ $content['organizer_stats']['total_events'] }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between mb-2">
                                                <span class="f-s-12">Eventi pubblicati:</span>
                                                <span class="f-s-12 f-w-600">{{ $content['organizer_stats']['published_events'] }}</span>
                                            </div>
                                            <div class="d-flex justify-content-between">
                                                <span class="f-s-12">Partecipanti totali:</span>
                                                <span class="f-s-12 f-w-600">{{ $content['organizer_stats']['total_participants'] }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

    <!-- Livewire Scripts -->
    <script>
        document.addEventListener('livewire:init', () => {
            // Listen for notifications
            Livewire.on('showNotification', (data) => {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: data.type,
                        title: data.type === 'success' ? 'Successo' : (data.type === 'error' ? 'Errore' : 'Info'),
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            });
        });
    </script>
</div>