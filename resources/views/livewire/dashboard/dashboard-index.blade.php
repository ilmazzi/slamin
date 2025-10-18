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
                                        <span class="badge bg-light-info text-dark f-s-12">
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

        <!-- 2. CALENDARIO - PRIMO ELEMENTO, Mobile First -->
        <div class="row mb-4">
            <div class="col-12">
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
                        <!-- Calendario Livewire -->
                        <div>
                            <!-- Header del mese -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0 f-w-600 text-dark">
                                    {{ now()->setMonth($currentMonth)->setYear($currentYear)->format('F Y') }}
                                </h5>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-light-primary btn-sm" wire:click="previousMonth">
                                        <i class="ph ph-caret-left"></i>
                                    </button>
                                    <button class="btn btn-light-primary btn-sm" wire:click="nextMonth">
                                        <i class="ph ph-caret-right"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Griglia del calendario -->
                            <div class="row g-1">
                                <!-- Header giorni della settimana -->
                                <div class="col-12">
                                    <div class="row g-1 mb-2">
                                        <div class="col text-center p-2 f-s-12 f-w-600 bg-light-secondary">Lun</div>
                                        <div class="col text-center p-2 f-s-12 f-w-600 bg-light-secondary">Mar</div>
                                        <div class="col text-center p-2 f-s-12 f-w-600 bg-light-secondary">Mer</div>
                                        <div class="col text-center p-2 f-s-12 f-w-600 bg-light-secondary">Gio</div>
                                        <div class="col text-center p-2 f-s-12 f-w-600 bg-light-secondary">Ven</div>
                                        <div class="col text-center p-2 f-s-12 f-w-600 bg-light-secondary">Sab</div>
                                        <div class="col text-center p-2 f-s-12 f-w-600 bg-light-secondary">Dom</div>
                                    </div>
                                </div>

                                <!-- Giorni del mese -->
                                @php
                                    $firstDay = now()->setMonth($currentMonth)->setYear($currentYear)->startOfMonth();
                                    $lastDay = now()->setMonth($currentMonth)->setYear($currentYear)->endOfMonth();
                                    $startDay = $firstDay->copy()->startOfWeek()->addDay(); // Lunedì
                                    $endDay = $lastDay->copy()->endOfWeek()->addDay(); // Domenica
                                    $currentDate = $startDay->copy();
                                @endphp

                                @while($currentDate->lte($endDay))
                                    <div class="col-12">
                                        <div class="row g-1">
                                            @for($i = 0; $i < 7; $i++)
                                                @php
                                                    $isCurrentMonth = $currentDate->month == $currentMonth;
                                                    $isToday = $currentDate->isToday();
                                                    $dayEvents = collect($calendarEvents)->where('start', $currentDate->format('Y-m-d'))->merge(
                                                        collect($wishlistEvents)->where('start', $currentDate->format('Y-m-d'))
                                                    );
                                                @endphp
                                                
                                                <div class="col border rounded p-2 {{ $isCurrentMonth ? '' : 'text-muted bg-light-primary' }} {{ $isToday ? 'bg-light-warning' : '' }}" style="height: 100px; min-height: 100px;">
                                                    <div class="d-flex flex-column h-100">
                                                        <div class="d-flex justify-content-between align-items-start mb-1">
                                                            <span class="f-s-14 f-w-600">{{ $currentDate->day }}</span>
                                                            @if($dayEvents->count() > 0)
                                                                <small class="text-muted f-s-10">{{ $dayEvents->count() }}</small>
                                                            @endif
                                                        </div>
                                                        
                                                        @if($dayEvents->count() > 0)
                                                            <div class="flex-grow-1 d-flex flex-column gap-1">
                                                                @foreach($dayEvents->take(3) as $event)
                                                                    <div class="badge bg-{{ $event['color'] }} f-s-9 cursor-pointer text-truncate" 
                                                                         title="{{ $event['title'] }} - {{ $event['time'] }}"
                                                                         wire:click="viewEvent({{ $event['id'] }})">
                                                                        {{ Str::limit($event['title'], 15) }}
                                                                    </div>
                                                                @endforeach
                                                                @if($dayEvents->count() > 3)
                                                                    <small class="text-muted f-s-9">+{{ $dayEvents->count() - 3 }}</small>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                @php $currentDate->addDay(); @endphp
                                            @endfor
                                        </div>
                                    </div>
                                @endwhile
                            </div>

                            <!-- Legenda eventi -->
                            <div class="mt-3">
                                <div class="d-flex flex-wrap gap-3 justify-content-center">
                                    <div class="d-flex align-items-center">
                                        <div class="badge bg-primary me-2 f-s-10">Eventi organizzati</div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="badge bg-success me-2 f-s-10">Eventi partecipazione</div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="badge bg-warning me-2 f-s-10">Lista desideri</div>
                                    </div>
                                </div>
                            </div>
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
        </div>

        <!-- 3. STATISTICHE COMPATTE - Mobile First, molto più piccole -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card hover-effect equal-card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="card-title mb-0 f-w-600">
                                <i class="ph ph-chart-bar me-2 text-primary"></i>{{ __('dashboard.statistics') }}
                            </h6>
                            <a href="{{ route('user-stats.index') }}" class="btn btn-sm btn-primary d-none d-md-inline-block">
                                <i class="ph ph-chart-line me-1"></i>{{ __('dashboard.view_detailed_stats') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body pa-15">
                        <!-- Mobile: 3x2 grid, Desktop: 6x1 grid - STATISTICHE COMPATTE -->
                        <div class="row g-2">
                            <!-- Statistica 1 - Eventi Passati -->
                            <div class="col-4 col-lg-2">
                                <a href="{{ route('events.index', ['filter' => 'past']) }}" class="text-decoration-none">
                                    <div class="card hover-effect b-t-3-secondary">
                                        <div class="card-body text-center pa-10">
                                            <div class="bg-light-info h-30 w-30 d-flex-center rounded-circle m-auto mb-1">
                                                <i class="ph ph-clock-counter-clockwise f-s-14 text-secondary"></i>
                                            </div>
                                            <h6 class="text-secondary mb-0 f-w-600 f-s-16">{{ $stats['past_events'] }}</h6>
                                            <p class="f-w-500 text-dark f-s-10 mb-0">{{ __('dashboard.past_events') }}</p>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Statistica 2 - Eventi Futuri -->
                            <div class="col-4 col-lg-2">
                                <a href="{{ route('events.index', ['filter' => 'future']) }}" class="text-decoration-none">
                                    <div class="card hover-effect b-t-3-warning">
                                        <div class="card-body text-center pa-10">
                                            <div class="bg-light-success h-30 w-30 d-flex-center rounded-circle m-auto mb-1">
                                                <i class="ph ph-calendar-check f-s-14 text-warning"></i>
                                            </div>
                                            <h6 class="text-warning mb-0 f-w-600 f-s-16">{{ $stats['future_events'] }}</h6>
                                            <p class="f-w-500 text-dark f-s-10 mb-0">{{ __('dashboard.future_events') }}</p>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Statistica 3 - Eventi Organizzati -->
                            <div class="col-4 col-lg-2">
                                <a href="{{ route('events.index', ['filter' => 'organized']) }}" class="text-decoration-none">
                                    <div class="card hover-effect b-t-3-primary">
                                        <div class="card-body text-center pa-10">
                                            <div class="bg-light-primary h-30 w-30 d-flex-center rounded-circle m-auto mb-1">
                                                <i class="ph ph-article f-s-14 text-primary"></i>
                                            </div>
                                            <h6 class="text-primary mb-0 f-w-600 f-s-16">{{ $stats['organized_events'] }}</h6>
                                            <p class="f-w-500 text-dark f-s-10 mb-0">{{ __('dashboard.organized_events') }}</p>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Statistica 4 - Inviti in Attesa -->
                            <div class="col-4 col-lg-2">
                                <a href="{{ route('events.index', ['filter' => 'invitations']) }}" class="text-decoration-none">
                                    <div class="card hover-effect b-t-3-success">
                                        <div class="card-body text-center pa-10">
                                            <div class="bg-light-secondary h-30 w-30 d-flex-center rounded-circle m-auto mb-1">
                                                <i class="ph ph-envelope f-s-14 text-success"></i>
                                            </div>
                                            <h6 class="text-success mb-0 f-w-600 f-s-16">{{ $stats['pending_invitations'] }}</h6>
                                            <p class="f-w-500 text-dark f-s-10 mb-0">{{ __('dashboard.pending_invitations') }}</p>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Statistica 5 - Inviti ai Gruppi -->
                            <div class="col-4 col-lg-2">
                                <a href="{{ route('group-invitations.index') }}" class="text-decoration-none">
                                    <div class="card hover-effect b-t-3-primary">
                                        <div class="card-body text-center pa-10">
                                            <div class="bg-light-warning h-30 w-30 d-flex-center rounded-circle m-auto mb-1">
                                                <i class="ph ph-users f-s-14 text-primary"></i>
                                            </div>
                                            <h6 class="text-primary mb-0 f-w-600 f-s-16">{{ $stats['pending_group_invitations'] }}</h6>
                                            <p class="f-w-500 text-dark f-s-10 mb-0">{{ __('dashboard.group_invitations') }}</p>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Statistica 6 - Notifiche -->
                            <div class="col-4 col-lg-2">
                                <a href="{{ route('notifications.index') }}" class="text-decoration-none">
                                    <div class="card hover-effect b-t-3-info">
                                        <div class="card-body text-center pa-10">
                                            <div class="bg-light-danger h-30 w-30 d-flex-center rounded-circle m-auto mb-1">
                                                <i class="ph ph-bell f-s-14 text-info"></i>
                                            </div>
                                            <h6 class="text-info mb-0 f-w-600 f-s-16">{{ $stats['unread_notifications'] }}</h6>
                                            <p class="f-w-500 text-dark f-s-10 mb-0">Notifiche</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. AZIONI RAPIDE E ATTIVITÀ RECENTI - Mobile First -->
        <div class="row mb-4">
            <!-- AZIONI RAPIDE - Eleganti e ricche di informazioni -->
            <div class="col-12 col-lg-6">
                <div class="card hover-effect equal-card">
                    <div class="card-header">
                        <h6 class="card-title mb-0 f-w-600">
                            <i class="ph ph-lightning me-2 text-primary"></i>{{ __('dashboard.quick_actions') }}
                        </h6>
                    </div>
                    <div class="card-body pa-20">
                        <div class="d-grid gap-3">
                            @foreach($quickActions as $action)
                                <a href="{{ $action['url'] }}" class="card hover-effect text-decoration-none border-0 bg-light-{{ $action['color'] }}">
                                    <div class="card-body d-flex align-items-center pa-15">
                                        <div class="bg-{{ $action['color'] }} h-40 w-40 d-flex-center rounded-circle me-3">
                                            <i class="{{ $action['icon'] }} f-s-18 text-white"></i>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 f-w-600 text-dark">{{ __('dashboard.' . $action['key']) }}</h6>
                                            <p class="mb-0 f-s-12 text-muted">
                                                @if($action['key'] === 'write_poem')
                                                    Crea e condividi le tue poesie
                                                @elseif($action['key'] === 'organize_event')
                                                    Organizza il tuo evento slam
                                                @elseif($action['key'] === 'upload_performance')
                                                    Carica le tue performance video
                                                @elseif($action['key'] === 'write_article')
                                                    Scrivi articoli e contenuti
                                                @elseif($action['key'] === 'help')
                                                    Ottieni supporto e aiuto
                                                @elseif($action['key'] === 'faq')
                                                    Domande frequenti
                                                @endif
                                            </p>
                                        </div>
                                        <div class="ms-2">
                                            <i class="ph ph-arrow-right f-s-16 text-muted"></i>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- ATTIVITÀ RECENTI - Eleganti e ricche di informazioni -->
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
                                    <div class="card hover-effect mb-3 border-0 bg-light-{{ $activity['color'] }}">
                                        <div class="card-body d-flex align-items-start pa-15">
                                            <div class="bg-{{ $activity['color'] }} rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px; min-width: 36px;">
                                                <i class="{{ $activity['icon'] }} f-s-16 text-white"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 f-s-14 f-w-600 text-dark">{{ $activity['title'] }}</h6>
                                                <p class="mb-1 f-s-13 text-muted">{{ $activity['message'] }}</p>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <small class="text-muted f-s-11">{{ $activity['time'] }}</small>
                                                    @if($activity['content_type'])
                                                        <span class="badge bg-{{ $activity['content_type_color'] }} f-s-10">{{ $activity['content_type'] }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            @if($activity['url'])
                                                <div class="ms-2">
                                                    <a href="{{ $activity['url'] }}" class="btn btn-sm btn-{{ $activity['color'] }}">
                                                        <i class="ph ph-arrow-right f-s-12"></i>
                                                    </a>
                                                </div>
                                            @endif
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
        </div>

        <!-- 5. EVENTI PROSSIMI - Mobile First -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card hover-effect equal-card">
                    <div class="card-header">
                        <h6 class="card-title mb-0 f-w-600">
                            <i class="ph ph-calendar-check me-2 text-success"></i>{{ __('dashboard.future_events') }}
                        </h6>
                    </div>
                    <div class="card-body pa-20">
                        @if(count($upcomingEvents) > 0)
                            <div class="row g-3">
                                @foreach($upcomingEvents as $event)
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="card hover-effect border-0 bg-light-{{ $event['type'] === 'organized' ? 'primary' : ($event['type'] === 'participating' ? 'success' : 'warning') }}">
                                            <div class="card-body pa-15">
                                                <div class="d-flex align-items-start justify-content-between mb-2">
                                                    <h6 class="mb-1 f-s-14 f-w-600 text-dark">{{ $event['title'] }}</h6>
                                                    <span class="badge bg-{{ $event['type'] === 'organized' ? 'primary' : ($event['type'] === 'participating' ? 'success' : 'warning') }} f-s-10">
                                                        {{ $event['type'] === 'organized' ? 'Organizzato' : ($event['type'] === 'participating' ? 'Partecipo' : 'Wishlist') }}
                                                    </span>
                                                </div>
                                                <p class="mb-1 f-s-12 text-muted">
                                                    <i class="ph ph-calendar me-1"></i>{{ $event['date'] }}
                                                </p>
                                                <p class="mb-2 f-s-12 text-muted">
                                                    <i class="ph ph-map-pin me-1"></i>{{ $event['venue'] }}, {{ $event['city'] }}
                                                </p>
                                                <a href="{{ $event['url'] }}" class="btn btn-sm btn-{{ $event['type'] === 'organized' ? 'primary' : ($event['type'] === 'participating' ? 'success' : 'warning') }}">
                                                    <i class="ph ph-arrow-right me-1"></i>Vedi dettagli
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
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

        <!-- 6. CONTENUTI SPECIFICI PER RUOLO - Mobile First -->
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