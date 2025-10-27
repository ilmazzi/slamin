<div>
    <!-- Mobile First Layout -->
    <div class="container-fluid">
        

        <!-- 1. WELCOME CARD - Mobile First -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card project-profit-card position-relative">
                    <div class="card-body bg-light-primary">
                        <!-- Floating Avatar -->
                        <div class="dashboard-avatar-float">
                            <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($user) }}"
                                 alt="{{ $user->getDisplayName() }}"
                                 class="rounded-circle shadow">
                        </div>
                      
                        <div class="dashboard-welcome-content">
                            <h4 class="text-dark mb-1 f-w-600">{{__('dashboard.welcome')}} {{ $user->getDisplayName() }}</h4>
                            <p class="f-w-500 mb-2 f-s-14 text-primary-50">{{ $user->getName() }}</p>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($user->getRoleNames() as $role)
                                    <span class="badge bg-primary f-s-12">
                                        {{ __('dashboard.role_' . $role) ?: ucfirst($role) }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <style>
            .dashboard-avatar-float {
                position: absolute;
                top: 50%;
                right: 20px;
                transform: translateY(-50%);
                z-index: 10;
            }
            
            .dashboard-avatar-float img {
                width: 80px;
                height: 80px;
                object-fit: cover;
                border: 4px solid white;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            }
            
            .dashboard-welcome-content {
                max-width: calc(100% - 100px);
                padding-right: 10px;
            }
            
            @media (max-width: 576px) {
                .dashboard-avatar-float {
                    right: 15px;
                }
                
                .dashboard-avatar-float img {
                    width: 70px;
                    height: 70px;
                    border-width: 3px;
                }
                
                .dashboard-welcome-content {
                    max-width: calc(100% - 90px);
                }
            }
        </style>
        <!-- 2. CALENDARIO - Mobile First con visualizzazioni intelligenti -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card hover-effect equal-card">
                    <div class="card-header ">
                        <!-- Header per visualizzazione mensile -->
                        <div class="d-md-none {{ $currentView !== 'month' ? 'd-none' : '' }}">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0 f-w-600 text-primary">CALENDARIO EVENTI</h6>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-light-primary btn-sm" wire:click="previousMonth">
                                        <span class="f-s-12 f-w-600">‹</span>
                                    </button>
                                    <button class="btn btn-light-primary btn-sm" wire:click="nextMonth">
                                        <span class="f-s-12 f-w-600">›</span>
                                    </button>
                                </div>
                            </div>
                            <div class="text-center mb-3">
                                <h5 class="mb-0 f-w-600 text-dark">
                                    {{ now()->setMonth($currentMonth)->setYear($currentYear)->format('F Y') }}
                                </h5>
                            </div>
                        </div>
                        
                        <!-- Toggle visualizzazioni - Solo su mobile -->
                        <div class="d-md-none">
                            <div class="btn-group btn-group-sm w-100" role="group">
                                <button type="button" class="btn {{ $currentView === 'list' ? 'btn-primary' : 'btn-light-primary' }} btn-sm" wire:click="switchView('list')">
                                    <i class="ph ph-list"></i>
                                </button>
                                <button type="button" class="btn {{ $currentView === 'week' ? 'btn-primary' : 'btn-light-primary' }} btn-sm" wire:click="switchView('week')">
                                    <i class="ph ph-calendar"></i>
                                </button>
                                <button type="button" class="btn {{ $currentView === 'month' ? 'btn-primary' : 'btn-light-primary' }} btn-sm" wire:click="switchView('month')">
                                    <i class="ph ph-calendar-blank"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pa-20">
                        
                        <!-- VISUALIZZAZIONE LISTA - Mobile (Default) -->
                        <div id="calendar-list-view" class="d-md-none {{ $currentView !== 'list' ? 'd-none' : '' }}">
                            <!-- Controlli navigazione lista -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <button type="button" class="btn btn-primary btn-sm f-s-10 f-md-12 {{ $listPage <= 1 ? 'disabled' : '' }}" wire:click="previousListPage" >
                                    <i class="ph ph-chevron-left f-s-10 f-md-12"></i> <span class="d-none d-md-inline">{{ __('dashboard.previous') }}</span><span class="d-md-none">Prec</span>
                                </button>
                                <span class="f-s-12 f-md-14 f-w-600 text-center">
                                    {{ __('dashboard.page') }} {{ $listPage }}
                                </span>
                                <button class="btn btn-primary btn-sm f-s-10 f-md-12" wire:click="nextListPage">
                                    <span class="d-none d-md-inline">{{ __('dashboard.next') }}</span><span class="d-md-none">Succ</span> <i class="ph ph-chevron-right f-s-10 f-md-12"></i>
                                </button>
                            </div>
                            
                            <div class="d-grid gap-3">
                                @php
                                    $allEvents = collect($calendarEvents)->merge(collect($wishlistEvents))->sortBy('start');
                                    $groupedEvents = $allEvents->groupBy('start');
                                    $eventsPerPage = 5;
                                    $startIndex = ($listPage - 1) * $eventsPerPage;
                                    $paginatedEvents = $groupedEvents->slice($startIndex, $eventsPerPage);
                                @endphp
                                
                                @foreach($paginatedEvents as $date => $events)
                                    <div class="card hover-effect">
                                        <div class="card-body p-3" style="overflow-x: hidden;">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="mb-0 f-w-600 text-primary">
                                                    {{ \Carbon\Carbon::parse($date)->format('d F Y') }}
                                                </h6>
                                                <span class="badge bg-primary f-s-10">{{ $events->count() }} eventi</span>
                                            </div>
                                            
                                            <div class="d-grid gap-2" style="overflow-x: hidden;">
                                                @foreach($events as $event)
                                                    <div class="event-card-mobile p-2 rounded" 
                                                         style="background: linear-gradient(135deg, rgba({{ ($event['color'] ?? 'secondary') === 'primary' ? '13,110,253' : (($event['color'] ?? 'secondary') === 'secondary' ? '108,117,125' : '255,193,7') }}, 0.1), rgba({{ ($event['color'] ?? 'secondary') === 'primary' ? '13,110,253' : (($event['color'] ?? 'secondary') === 'secondary' ? '108,117,125' : '255,193,7') }}, 0.05)); overflow-x: hidden; max-width: 100%;">
                                                        
                                                        <div class="d-flex align-items-start gap-2" style="overflow-x: hidden; width: 100%;">
                                                            <!-- Immagine evento -->
                                                            <div class="flex-shrink-0">
                                                                @if(isset($event['image']) && $event['image'])
                                                                    <img src="{{ $event['image'] }}" alt="{{ $event['title'] ?? 'Evento' }}" 
                                                                         class="rounded-circle" 
                                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                                @else
                                                                    <div class="bg-{{ $event['color'] ?? 'secondary' }} rounded-circle d-flex align-items-center justify-content-center" 
                                                                         style="width: 40px; height: 40px;">
                                                                        <i class="ph ph-calendar text-white f-s-16"></i>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            
                                                            <!-- Contenuto evento -->
                                                            <div class="flex-grow-1 event-content-wrapper">
                                                                <!-- Titolo e badge -->
                                                                <div class="d-flex align-items-start gap-2 mb-1">
                                                                    <h6 class="mb-0 f-s-13 f-w-600 text-dark cursor-pointer flex-grow-1 event-title" 
                                                                        wire:click="viewEvent({{ $event['id'] ?? 0 }})">
                                                                        {{ $event['title'] ?? __('dashboard.event_without_title') }}
                                                                    </h6>
                                                                    <span class="badge bg-{{ $event['color'] ?? 'secondary' }} f-s-9 flex-shrink-0">
                                                                        {{ $event['type'] === 'organized' ? __('dashboard.organized') : ($event['type'] === 'participating' ? __('dashboard.participating') : __('dashboard.wish')) }}
                                                                    </span>
                                                                </div>
                                                                
                                                                <!-- Info e azioni -->
                                                                <div class="d-flex flex-column gap-1">
                                                                    <!-- Ora -->
                                                                    <div class="d-flex align-items-center gap-1">
                                                                        <i class="ph ph-clock f-s-12 text-muted flex-shrink-0"></i>
                                                                        <span class="f-s-11 text-muted">{{ $event['time'] ?? __('dashboard.time_not_available') }}</span>
                                                                    </div>
                                                                    
                                                                    <!-- Location -->
                                                                    <div class="d-flex align-items-center gap-1 event-location-wrapper">
                                                                        <i class="ph ph-map-pin f-s-12 text-muted flex-shrink-0"></i>
                                                                        <span class="f-s-11 text-muted event-location-text">{{ $event['venue'] ?? __('dashboard.location_not_available') }}{{ isset($event['city']) ? ', ' . $event['city'] : '' }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            
                                            <style>
                                                .event-card-mobile {
                                                    transition: transform 0.2s ease, box-shadow 0.2s ease;
                                                    overflow-x: hidden !important;
                                                    max-width: 100% !important;
                                                }
                                                
                                                .event-card-mobile:active {
                                                    transform: scale(0.98);
                                                }
                                                
                                                .event-card-mobile .d-flex {
                                                    overflow-x: hidden;
                                                    width: 100%;
                                                }
                                                
                                                .event-content-wrapper {
                                                    min-width: 0;
                                                    max-width: 100%;
                                                    overflow: hidden;
                                                }
                                                
                                                .event-title {
                                                    min-width: 0;
                                                    max-width: 100%;
                                                    overflow: hidden;
                                                    text-overflow: ellipsis;
                                                    white-space: nowrap;
                                                }
                                                
                                                .event-location-wrapper {
                                                    min-width: 0;
                                                    overflow: hidden;
                                                }
                                                
                                                .event-location-text {
                                                    overflow: hidden;
                                                    text-overflow: ellipsis;
                                                    white-space: nowrap;
                                                }
                                            </style>
                                        </div>
                                    </div>
                                @endforeach
                                
                                @if($allEvents->isEmpty())
                                    <div class="text-center py-4">
                                        <i class="ph ph-calendar f-s-48 text-muted mb-3"></i>
                                        <h6 class="text-muted">{{ __('dashboard.no_events_this_month') }}</h6>
                                        <p class="text-muted small">{{ __('dashboard.no_events_scheduled') }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- VISUALIZZAZIONE SETTIMANALE - Mobile -->
                        <div id="calendar-week-view" class="d-md-none {{ $currentView !== 'week' ? 'd-none' : '' }}">
                            <!-- Controlli navigazione settimana -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <button class="btn btn-outline-primary btn-sm f-s-10 f-md-12" wire:click="previousWeek">
                                    <i class="ph ph-chevron-left f-s-10 f-md-12"></i> <span class="d-none d-md-inline">{{ __('dashboard.previous_week') }}</span><span class="d-md-none">Prec</span>
                                </button>
                                <span class="f-s-12 f-md-14 f-w-600 text-center">
                                    {{ __('dashboard.week') }} {{ $weekPage === 0 ? __('dashboard.current') : ($weekPage > 0 ? '+' . $weekPage : $weekPage) }}
                                </span>
                                <button class="btn btn-outline-primary btn-sm f-s-10 f-md-12" wire:click="nextWeek">
                                    <span class="d-none d-md-inline">{{ __('dashboard.next_week') }}</span><span class="d-md-none">Succ</span> <i class="ph ph-chevron-right f-s-10 f-md-12"></i>
                                </button>
                            </div>
                            
                            <div class="d-grid gap-2">
                                @php
                                    // Calcola la settimana in base alla paginazione
                                    $today = now();
                                    $currentWeekStart = $today->copy()->startOfWeek()->addDay()->addWeeks($weekPage);
                                    $weekDays = [];
                                    for($i = 0; $i < 7; $i++) {
                                        $weekDays[] = $currentWeekStart->copy()->addDays($i);
                                    }
                                @endphp
                                
                                @foreach($weekDays as $day)
                                    @php
                                        $dayEvents = collect($calendarEvents)->where('start', $day->format('Y-m-d'))->merge(
                                            collect($wishlistEvents)->where('start', $day->format('Y-m-d'))
                                        );
                                    @endphp
                                    
                                    <div class="card hover-effect {{ $day->isToday() ? 'border-warning' : '' }}">
                                        <div class="card-body p-3">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="mb-0 f-w-600 {{ $day->isToday() ? 'text-warning' : 'text-dark' }}">
                                                    {{ $day->format('l, d F') }}
                                                </h6>
                                                @if($dayEvents->count() > 0)
                                                    <span class="badge bg-light-primary text-primary f-s-10">{{ $dayEvents->count() }}</span>
                                                @endif
                                            </div>
                                            
                                            @if($dayEvents->count() > 0)
                                                <div class="d-grid gap-2">
                                                    @foreach($dayEvents as $event)
                                                        <div class="d-flex align-items-center p-2 rounded bg-light-primary">
                                                            <div class="me-2">
                                                                <div class="w-2 h-2 rounded-circle bg-{{ $event['color'] ?? 'secondary' }}"></div>
                                                            </div>
                                                            <div class="flex-grow-1">
                                                                <div class="f-s-12 f-w-600 cursor-pointer" wire:click="viewEvent({{ $event['id'] ?? 0 }})">
                                                                    {{ Str::limit($event['title'] ?? __('dashboard.event_without_title'), 25) }}
                                                                </div>
                                                                <div class="f-s-11 text-muted">{{ $event['time'] ?? __('dashboard.time_not_available') }}</div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="text-center py-2">
                                                    <span class="text-muted f-s-12">{{ __('dashboard.no_events') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        
                        <!-- VISUALIZZAZIONE MENSILE - Mobile (Compatto) -->
                        <div id="calendar-month-view" class="d-md-none {{ $currentView !== 'month' ? 'd-none' : '' }}">
                            <div class="row g-1">
                                <!-- Header giorni della settimana -->
                                <div class="col-12">
                                    <div class="row g-1 mb-2">
                                        <div class="col text-center p-1 f-s-10 f-w-600 bg-light-secondary">L</div>
                                        <div class="col text-center p-1 f-s-10 f-w-600 bg-light-secondary">M</div>
                                        <div class="col text-center p-1 f-s-10 f-w-600 bg-light-secondary">M</div>
                                        <div class="col text-center p-1 f-s-10 f-w-600 bg-light-secondary">G</div>
                                        <div class="col text-center p-1 f-s-10 f-w-600 bg-light-secondary">V</div>
                                        <div class="col text-center p-1 f-s-10 f-w-600 bg-light-secondary">S</div>
                                        <div class="col text-center p-1 f-s-10 f-w-600 bg-light-secondary">D</div>
                                    </div>
                                </div>

                                <!-- Giorni del mese -->
                                @php
                                    $firstDay = now()->setMonth($currentMonth)->setYear($currentYear)->startOfMonth();
                                    $lastDay = now()->setMonth($currentMonth)->setYear($currentYear)->endOfMonth();
                                    $startDay = $firstDay->copy()->startOfWeek()->addDay();
                                    $endDay = $lastDay->copy()->endOfWeek()->addDay();
                                    $currentDate = $startDay->copy();
                                @endphp

                                @while($currentDate->lte($endDay))
                                    <div class="col-12">
                                        <div class="row g-1">
                                            @for($i = 0; $i < 7; $i++)
                                                @php
                                                    $isCurrentMonth = $currentDate->month == $currentMonth;
                                                    $isToday = $currentDate->isToday();
                                                    // Get all events for the day
                                                    $allDayEvents = $calendarEvents->where('start', $currentDate->format('Y-m-d'))->merge(
                                                        $wishlistEvents->where('start', $currentDate->format('Y-m-d'))
                                                    );
                                                    
                                                    // Apply filter
                                                    if ($eventFilter === 'all') {
                                                        $dayEvents = $allDayEvents;
                                                    } else {
                                                        $dayEvents = $allDayEvents->where('type', $eventFilter);
                                                    }
                                                @endphp
                                                
                                                <div class="col border rounded p-1 {{ $isCurrentMonth ? '' : 'text-muted bg-light-primary' }} {{ $isToday ? 'bg-light-warning' : '' }} {{ $dayEvents->count() > 0 ? 'cursor-pointer' : '' }}" 
                                                     style="height: 40px; min-height: 40px;" 
                                                     @if($dayEvents->count() > 0) 
                                                        wire:click="selectDay('{{ $currentDate->format('Y-m-d') }}')"
                                                     @endif>
                                                    <div class="d-flex justify-content-between align-items-center h-100">
                                                        <span class="f-s-10 f-w-600">{{ $currentDate->day }}</span>
                                                        @if($dayEvents->count() > 0)
                                                            <div class="d-flex align-items-center gap-1">
                                                                <div class="rounded-circle bg-primary" style="width: 8px; height: 8px; min-width: 8px; min-height: 8px;"></div>
                                                                <span class="f-s-8 f-w-600 text-muted">{{ $dayEvents->count() }}</span>
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
                        </div>
                        
                        <!-- VISUALIZZAZIONE MENSILE - Desktop -->
                        <div id="calendar-desktop-view" class="d-none d-md-block">
                            <!-- Controlli navigazione mese -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <button class="btn btn-light-primary btn-sm" wire:click="previousMonth">
                                    <i class="ph ph-chevron-left"></i> {{ __('dashboard.previous_month') }}
                                </button>
                                <h5 class="mb-0 f-w-600 text-center">
                                    {{ now()->setMonth($currentMonth)->setYear($currentYear)->format('F Y') }}
                                </h5>
                                <button class="btn btn-light-primary btn-sm" wire:click="nextMonth">
                                    {{ __('dashboard.next_month') }} <i class="ph ph-chevron-right"></i>
                                </button>
                            </div>
                            
                            <!-- Griglia del calendario desktop -->
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
                                    $startDay = $firstDay->copy()->startOfWeek()->addDay();
                                    $endDay = $lastDay->copy()->endOfWeek()->addDay();
                                    $currentDate = $startDay->copy();
                                @endphp

                                @while($currentDate->lte($endDay))
                                    <div class="col-12">
                                        <div class="row g-1">
                                            @for($i = 0; $i < 7; $i++)
                                                @php
                                                    $isCurrentMonth = $currentDate->month == $currentMonth;
                                                    $isToday = $currentDate->isToday();
                                                    // Get all events for the day
                                                    $allDayEvents = $calendarEvents->where('start', $currentDate->format('Y-m-d'))->merge(
                                                        $wishlistEvents->where('start', $currentDate->format('Y-m-d'))
                                                    );
                                                    
                                                    // Apply filter
                                                    if ($eventFilter === 'all') {
                                                        $dayEvents = $allDayEvents;
                                                    } else {
                                                        $dayEvents = $allDayEvents->where('type', $eventFilter);
                                                    }
                                                @endphp
                                                
                                                <div class="col border rounded p-2 {{ $isCurrentMonth ? '' : 'text-muted bg-light-primary' }} {{ $isToday ? 'bg-light-warning' : '' }}" style="height: 120px; min-height: 120px; position: relative; overflow: hidden;">
                                                    <!-- Numero giorno sempre visibile -->
                                                    <div class="position-absolute top-0 start-0 p-1">
                                                        <span class="f-s-10 f-md-14 f-w-600 text-muted">{{ $currentDate->day }}</span>
                                                    </div>
                                                    
                                                    <!-- Eventi del giorno con slider -->
                                                    @if($dayEvents->count() > 0)
                                                        <div class="position-absolute top-0 end-0 p-1">
                                                            <span class="badge bg-{{ $dayEvents->first()['color'] ?? 'secondary' }} f-s-8 f-md-10 px-2">
                                                                    {{ $dayEvents->first()['type'] === 'organized' ? __('dashboard.organized') : ($dayEvents->first()['type'] === 'participating' ? __('dashboard.participating') : __('dashboard.wish')) }}
                                                            </span>
                                                        </div>
                                                        
                                                        <!-- Slider degli eventi -->
                                                        <div class="position-absolute w-100 h-100 top-0 start-0" style="overflow: hidden;">
                                                            @foreach($dayEvents as $index => $event)
                                                                <div class="event-card position-absolute w-100 h-100 d-flex flex-column justify-content-between p-2 {{ $index === 0 ? 'active' : '' }}" 
                                                                     style="background: {{ isset($event['image']) && $event['image'] ? 'linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.6)), url(' . $event['image'] . ')' : 'linear-gradient(135deg, #f8f9fa, #e9ecef)' }}; background-size: cover; background-position: center; color: {{ isset($event['image']) && $event['image'] ? 'white' : '#495057' }}; transition: transform 0.3s ease;"
                                                                     data-slide="{{ $index }}">
                                                                    
                                                                    <!-- Header con icona o immagine -->
                                                                    <div class="d-flex justify-content-center">
                                                                        @if(isset($event['image']) && $event['image'])
                                                                            <img src="{{ $event['image'] }}" alt="{{ $event['title'] ?? __('dashboard.event_without_title') }}" 
                                                                                 class="rounded-circle" 
                                                                                 style="width: 32px; height: 32px; object-fit: cover;">
                                                                        @else
                                                                            <div class="bg-light-{{ $event['color'] ?? 'secondary' }} rounded-circle d-flex align-items-center justify-content-center border border-{{ $event['color'] ?? 'secondary' }} border-opacity-50" 
                                                                                 style="width: 32px; height: 32px;">
                                                                                <i class="ph ph-calendar f-s-14 f-md-18 text-{{ $event['color'] ?? 'secondary' }}"></i>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    
                                                                    <!-- Contenuto principale -->
                                                                    <div class="text-center">
                                                                        <div class="f-s-10 f-md-13 f-w-600 text-truncate" style="max-width: 100%; {{ isset($event['image']) && $event['image'] ? 'text-shadow: 0 1px 2px rgba(0,0,0,0.8);' : '' }}">
                                                                            {{ Str::limit($event['title'] ?? __('dashboard.event_without_title'), 15) }}
                                                                        </div>
                                                                        <div class="f-s-9 f-md-11 {{ isset($event['image']) && $event['image'] ? 'text-white-50' : 'text-secondary' }}" style="{{ isset($event['image']) && $event['image'] ? 'text-shadow: 0 1px 2px rgba(0,0,0,0.8);' : '' }}">
                                                                            {{ $event['time'] ?? __('dashboard.time_not_available') }}
                                                                        </div>
                                                                        @if(isset($event['venue']) && $event['venue'])
                                                                            <div class="f-s-8 f-md-10 {{ isset($event['image']) && $event['image'] ? 'text-white-50' : 'text-secondary' }} mt-1" style="{{ isset($event['image']) && $event['image'] ? 'text-shadow: 0 1px 2px rgba(0,0,0,0.8);' : '' }}">
                                                                                <i class="ph ph-map-pin f-s-8"></i> {{ Str::limit($event['venue'], 12) }}
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                    
                                                                    <!-- Footer con indicatori slider -->
                                                                    @if($dayEvents->count() > 1)
                                                                        <div class="d-flex justify-content-center gap-1">
                                                                            @foreach($dayEvents as $indicatorIndex => $indicatorEvent)
                                                                                <div class="slider-indicator rounded-circle {{ $indicatorIndex === 0 ? 'active' : '' }}" 
                                                                                     style="width: 4px; height: 4px; background: {{ $indicatorIndex === 0 ? 'white' : 'rgba(255,255,255,0.5)' }};"></div>
                                                                            @endforeach
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                        
                                                        <!-- Click handler per l'evento -->
                                                        <div class="position-absolute w-100 h-100 top-0 start-0" 
                                                             style="z-index: 10; cursor: pointer;" 
                                                             wire:click="viewEvent({{ $dayEvents->first()['id'] ?? 0 }})"></div>
                                                    @endif
                                                </div>
                                                
                                                @php $currentDate->addDay(); @endphp
                                            @endfor
                                        </div>
                                    </div>
                                @endwhile
                            </div>
                        </div>

                        <!-- Legenda eventi -->
                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="d-flex flex-wrap gap-3 justify-content-center">
                                    <div class="d-flex align-items-center gap-2 cursor-pointer" 
                                         wire:click="filterEvents('organized')" 
                                         style="opacity: {{ $eventFilter === 'organized' || $eventFilter === 'all' ? '1' : '0.5' }}">
                                        <div class="rounded-circle" style="width: 12px; height: 12px; background-color: #007bff;"></div>
                                        <small class="text-muted f-s-12">{{ __('dashboard.organized') }}</small>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 cursor-pointer" 
                                         wire:click="filterEvents('participating')" 
                                         style="opacity: {{ $eventFilter === 'participating' || $eventFilter === 'all' ? '1' : '0.5' }}">
                                        <div class="rounded-circle" style="width: 12px; height: 12px; background-color: #6c757d;"></div>
                                        <small class="text-muted f-s-12">{{ __('dashboard.participating') }}</small>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 cursor-pointer" 
                                         wire:click="filterEvents('wishlist')" 
                                         style="opacity: {{ $eventFilter === 'wishlist' || $eventFilter === 'all' ? '1' : '0.5' }}">
                                        <div class="rounded-circle" style="width: 12px; height: 12px; background-color: #ffc107;"></div>
                                        <small class="text-muted f-s-12">{{ __('dashboard.wish') }}</small>
                                    </div>
                                    <div class="d-flex align-items-center gap-2 cursor-pointer" 
                                         wire:click="filterEvents('all')" 
                                         style="opacity: {{ $eventFilter === 'all' ? '1' : '0.5' }}">
                                        <div class="rounded-circle" style="width: 12px; height: 12px; background-color: #28a745;"></div>
                                        <small class="text-muted f-s-12">{{ __('dashboard.all') }}</small>
                                    </div>
                                </div>
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
                    <div class="card-header ">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="card-title mb-0 f-w-600">
                                <i class="ph ph-chart-line me-2 text-primary"></i>{{ __('dashboard.statistics') }}
                            </h6>
                            <a href="{{ route('user-stats.index') }}" class="btn btn-light-primary btn-sm f-s-10 f-md-12">
                                <i class="ph ph-chart-line me-1 f-s-10 f-md-12"></i><span class="d-none d-md-inline">{{ __('dashboard.view_detailed_stats') }}</span><span class="d-md-none">Dettagli</span>
                            </a>
                        </div>
                    </div>
                    <div class="card-body pa-15">
                        <!-- Mobile: 3x2 grid, Desktop: 6x1 grid - STATISTICHE COMPATTE -->
                        <div class="row g-2">
                            <!-- Statistica 1 - Eventi Passati -->
                            <div class="col-4 col-lg-2">
                                <a href="{{ route('events.index', ['filter' => 'past']) }}" class="text-decoration-none">
                                    <div class="card hover-effect b-b-3-secondary">
                                        <div class="card-body text-center pa-10">
                                            <div class="bg-light-secondary rounded-circle d-flex-center mx-auto mb-2" style="width: 40px; height: 40px;">
                                                <i class="ph ph-calendar-check f-s-16 text-secondary"></i>
                                            </div>
                                            <h6 class="mb-0 f-s-14 f-w-600 text-dark">{{ $stats['past_events'] ?? 0 }}</h6>
                                            <small class="text-muted f-s-11">{{ __('dashboard.past') }}</small>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Statistica 2 - Eventi Futuri -->
                            <div class="col-4 col-lg-2">
                                <a href="{{ route('events.index', ['filter' => 'upcoming']) }}" class="text-decoration-none">
                                    <div class="card hover-effect b-b-3-primary">
                                        <div class="card-body text-center pa-10">
                                            <div class="bg-light-primary rounded-circle d-flex-center mx-auto mb-2" style="width: 40px; height: 40px;">
                                                <i class="ph ph-calendar f-s-16 text-primary"></i>
                                            </div>
                                            <h6 class="mb-0 f-s-14 f-w-600 text-dark">{{ $stats['future_events'] ?? 0 }}</h6>
                                            <small class="text-muted f-s-11">{{ __('dashboard.future') }}</small>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Statistica 3 - Eventi Organizzati -->
                            <div class="col-4 col-lg-2">
                                <a href="{{ route('events.index', ['filter' => 'organized']) }}" class="text-decoration-none">
                                    <div class="card hover-effect b-b-3-success">
                                        <div class="card-body text-center pa-10">
                                            <div class="bg-light-success rounded-circle d-flex-center mx-auto mb-2" style="width: 40px; height: 40px;">
                                                <i class="ph ph-users f-s-16 text-success"></i>
                                            </div>
                                            <h6 class="mb-0 f-s-14 f-w-600 text-dark">{{ $stats['organized_events'] ?? 0 }}</h6>
                                            <small class="text-muted f-s-11">{{ __('dashboard.organized') }}</small>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Statistica 4 - Inviti Pendenti -->
                            <div class="col-4 col-lg-2">
                                <a href="{{ route('events.index', ['filter' => 'invitations']) }}" class="text-decoration-none">
                                    <div class="card hover-effect b-b-3-warning">
                                        <div class="card-body text-center pa-10">
                                            <div class="bg-light-warning rounded-circle d-flex-center mx-auto mb-2" style="width: 40px; height: 40px;">
                                                <i class="ph ph-envelope f-s-16 text-warning"></i>
                                            </div>
                                            <h6 class="mb-0 f-s-14 f-w-600 text-dark">{{ $stats['pending_invitations'] ?? 0 }}</h6>
                                                <small class="text-muted f-s-11">{{ __('dashboard.pending_invitations') }}</small>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Statistica 5 - Poesie -->
                            <div class="col-4 col-lg-2">
                                <a href="{{ route('poems.index') }}" class="text-decoration-none">
                                    <div class="card hover-effect b-b-3-info">
                                        <div class="card-body text-center pa-10">
                                            <div class="bg-light-info rounded-circle d-flex-center mx-auto mb-2" style="width: 40px; height: 40px;">
                                                <i class="ph ph-book-open f-s-16 text-info"></i>
                                            </div>
                                            <h6 class="mb-0 f-s-14 f-w-600 text-dark">{{ $stats['poems'] ?? 0 }}</h6>
                                            <small class="text-muted f-s-11">{{ __('dashboard.poems') }}</small>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Statistica 6 - Gruppi -->
                            <div class="col-4 col-lg-2">
                                <a href="{{ route('groups.index') }}" class="text-decoration-none">
                                    <div class="card hover-effect b-b-3-danger">
                                        <div class="card-body text-center pa-10">
                                            <div class="bg-light-danger rounded-circle d-flex-center mx-auto mb-2" style="width: 40px; height: 40px;">
                                                <i class="ph ph-users-three f-s-16 text-danger"></i>
                                            </div>
                                            <h6 class="mb-0 f-s-14 f-w-600 text-dark">{{ $stats['groups'] ?? 0 }}</h6>
                                            <small class="text-muted f-s-11">{{ __('dashboard.groups') }}</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4. AZIONI RAPIDE - Mobile First, eleganti e ricche di informazioni -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card hover-effect equal-card">
                    <div class="card-header">
                        <h6 class="card-title mb-0 f-w-600">
                            <i class="ph ph-lightning me-2 text-warning"></i>{{ __('dashboard.quick_actions') }}
                        </h6>
                    </div>
                    <div class="card-body pa-20">
                        <div class="row g-3">
                            @foreach($quickActions as $action)
                                <div class="col-12 col-md-6 col-lg-4">
                                    <a href="{{ $action['url'] ?? '#' }}" class="text-decoration-none">
                                        <div class="card hover-effect b-s-4-{{ $action['color'] ?? 'secondary' }}">
                                            <div class="card-body d-flex align-items-center pa-15">
                                                <div class="bg-light-{{ $action['color'] ?? 'secondary' }} h-50 w-50 d-flex-center rounded-circle me-3">
                                                    <i class="ph {{ $action['icon'] ?? 'ph-question' }} f-s-20 text-{{ $action['color'] ?? 'secondary' }}"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 f-s-16 f-w-600 text-dark">{{ $action['title'] ?? __('dashboard.action') }}</h6>
                                                    <p class="mb-0 f-s-13 text-muted">{{ $action['description'] ?? __('dashboard.description_not_available') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. EVENTI PROSSIMI E ATTIVITÀ SOCIALI - Mobile First -->
        <div class="row mb-4">
            <!-- EVENTI PROSSIMI -->
            <div class="col-12 col-lg-6">
                <div class="card hover-effect equal-card">
                    <div class="card-header">
                        <h6 class="card-title mb-0 f-w-600">
                            <i class="ph ph-calendar-check me-2 text-primary"></i>{{ __('dashboard.future_events') }}
                        </h6>
                    </div>
                    <div class="card-body pa-20">
                        @if(count($upcomingEvents) > 0)
                            <div class="d-grid gap-3">
                                @foreach($upcomingEvents as $event)
                                    <div class="card hover-effect b-s-4-{{ $event['type'] === 'organized' ? 'primary' : ($event['type'] === 'participating' ? 'secondary' : 'primary') }}">
                                        <div class="card-body pa-15">
                                            <div class="d-flex align-items-start">
                                                <!-- Icona evento -->
                                                <div class="bg-light-{{ $event['type'] === 'organized' ? 'primary' : ($event['type'] === 'participating' ? 'secondary' : 'primary') }} d-flex-center rounded-circle me-3" style="width: 50px; height: 50px; min-width: 50px; min-height: 50px;">
                                                    <i class="ph ph-calendar f-s-20 text-{{ $event['type'] === 'organized' ? 'primary' : ($event['type'] === 'participating' ? 'secondary' : 'primary') }}"></i>
                                                </div>
                                                
                                                <!-- Contenuto evento -->
                                                <div class="flex-grow-1 me-3">
                                                    <div class="d-flex align-items-start justify-content-between mb-1">
                                                        <h6 class="mb-0 f-s-16 f-w-600 text-dark">{{ $event['title'] ?? __('dashboard.event_without_title') }}</h6>
                                                        <span class="badge bg-light-{{ $event['type'] === 'organized' ? 'primary' : ($event['type'] === 'participating' ? 'secondary' : 'primary') }} text-{{ $event['type'] === 'organized' ? 'primary' : ($event['type'] === 'participating' ? 'secondary' : 'primary') }} f-s-10">
                                                            {{ $event['type'] === 'organized' ? __('dashboard.organized') : ($event['type'] === 'participating' ? __('dashboard.participating') : __('dashboard.wish')) }}
                                                        </span>
                                                    </div>
                                                    <div class="d-flex align-items-center mb-1">
                                                        <i class="ph ph-calendar f-s-14 text-muted me-2"></i>
                                                        <span class="f-s-13 text-muted">{{ $event['date'] ?? __('dashboard.date_not_available') }}</span>
                                                    </div>
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="ph ph-map-pin f-s-14 text-muted me-2"></i>
                                                        <span class="f-s-13 text-muted">{{ $event['venue'] ?? __('dashboard.location_not_available') }}{{ isset($event['city']) ? ', ' . $event['city'] : '' }}</span>
                                                    </div>
                                                    
                                                    <!-- Bottone azione -->
                                                    <div>
                                                        <a href="{{ $event['url'] ?? '#' }}" class="btn btn-{{ $event['type'] === 'organized' ? 'primary' : ($event['type'] === 'participating' ? 'secondary' : 'primary') }} btn-sm">
                                                            <i class="ph ph-arrow-right me-1"></i>{{ __('dashboard.view') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="ph ph-calendar f-s-48 text-muted mb-3"></i>
                                    <h6 class="text-muted">{{ __('dashboard.no_upcoming_events') }}</h6>
                                <p class="text-muted small">{{ __('dashboard.no_events_scheduled') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- ATTIVITÀ SOCIALI -->
            <div class="col-12 col-lg-6">
                <div class="card hover-effect equal-card">
                    <div class="card-header">
                        <h6 class="card-title mb-0 f-w-600">
                            <i class="ph ph-users me-2 text-primary"></i>{{ __('dashboard.social_activities') }}
                        </h6>
                    </div>
                    <div class="card-body pa-20">
                        <div class="d-grid gap-3">
                            <!-- Amici Online -->
                            <div class="card hover-effect b-s-4-primary">
                                <div class="card-body d-flex align-items-center pa-15">
                                    <div class="bg-light-primary h-50 w-50 d-flex-center rounded-circle me-3">
                                        <i class="ph ph-user-circle f-s-20 text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 f-s-16 f-w-600 text-dark">{{ __('dashboard.online_friends') }}</h6>
                                        <p class="mb-0 f-s-13 text-muted">{{ __('dashboard.3_active_friends_now') }}</p>
                                    </div>
                                    <div class="ms-3">
                                        <span class="badge bg-light-primary text-primary f-s-12">3</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Gruppi Attivi -->
                            <div class="card hover-effect b-s-4-secondary">
                                <div class="card-body d-flex align-items-center pa-15">
                                    <div class="bg-light-secondary h-50 w-50 d-flex-center rounded-circle me-3">
                                        <i class="ph ph-users-three f-s-20 text-secondary"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 f-s-16 f-w-600 text-dark">{{ __('dashboard.active_groups') }}</h6>
                                        <p class="mb-0 f-s-13 text-muted">{{ __('dashboard.2_groups_with_new_activities') }}</p>
                                    </div>
                                    <div class="ms-3">
                                        <span class="badge bg-light-secondary text-secondary f-s-12">2</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Inviti Ricevuti -->
                            <div class="card hover-effect b-s-4-primary">
                                <div class="card-body d-flex align-items-center pa-15">
                                    <div class="bg-light-primary h-50 w-50 d-flex-center rounded-circle me-3">
                                        <i class="ph ph-envelope f-s-20 text-primary"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 f-s-16 f-w-600 text-dark">{{ __('dashboard.received_invitations') }}</h6>
                                        <p class="mb-0 f-s-13 text-muted">{{ __('dashboard.1_new_invitation_to_reply') }}</p>
                                    </div>
                                    <div class="ms-3">
                                        <span class="badge bg-light-primary text-primary f-s-12">1</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Messaggi Non Letti -->
                            <div class="card hover-effect b-s-4-secondary">
                                <div class="card-body d-flex align-items-center pa-15">
                                    <div class="bg-light-secondary h-50 w-50 d-flex-center rounded-circle me-3">
                                        <i class="ph ph-chat-circle f-s-20 text-secondary"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1 f-s-16 f-w-600 text-dark">{{ __('dashboard.messages') }}</h6>
                                        <p class="mb-0 f-s-13 text-muted">{{ __('dashboard.5_unread_messages') }}</p>
                                    </div>
                                    <div class="ms-3">
                                        <span class="badge bg-light-secondary text-secondary f-s-12">5</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 6. CONTENUTI SPECIFICI PER RUOLO - Mobile First -->
        @if(!empty($roleContent))
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card hover-effect equal-card">
                        <div class="card-header">
                            <h6 class="card-title mb-0 f-w-600">
                                <i class="ph ph-star me-2 text-warning"></i>{{ __('dashboard.role_specific_content') }}
                            </h6>
                        </div>
                        <div class="card-body pa-20">
                            <div class="row g-3">
                                @foreach($roleContent as $content)
                                    <div class="col-12 col-md-6 col-lg-4">
                                        <div class="card hover-effect b-s-4-{{ $content['color'] }}">
                                            <div class="card-body pa-15">
                                                <div class="d-flex align-items-center mb-2">
                                                    <div class="bg-light-{{ $content['color'] }} h-40 w-40 d-flex-center rounded-circle me-3">
                                                        <i class="ph {{ $content['icon'] }} f-s-16 text-{{ $content['color'] }}"></i>
                                                    </div>
                                                    <h6 class="mb-0 f-s-14 f-w-600 text-dark">{{ $content['title'] ?? __('dashboard.content') }}</h6>
                                                </div>
                                                <p class="mb-2 f-s-12 text-muted">{{ $content['description'] }}</p>
                                                @if(isset($content['count']))
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="badge bg-{{ $content['color'] }} f-s-10">{{ $content['count'] }}</span>
                                                        @if(isset($content['url']))
                                                            <a href="{{ $content['url'] }}" class="btn btn-{{ $content['color'] }} btn-sm">
                                                                    <i class="ph ph-arrow-right me-1"></i>{{ __('dashboard.view') }}
                                                            </a>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- 7. ATTIVITÀ RECENTI - Mobile First, eleganti e ricche di informazioni -->
        @if(!empty($recentActivity))
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card hover-effect equal-card">
                        <div class="card-header">
                            <h6 class="card-title mb-0 f-w-600">
                                <i class="ph ph-clock me-2 text-primary"></i>{{ __('dashboard.recent_activity') }}
                            </h6>
                        </div>
                        <div class="card-body pa-20">
                            <div class="d-grid gap-3">
                                @foreach($recentActivity as $activity)
                                    <div class="card hover-effect b-s-4-{{ $activity['color'] }}">
                                        <div class="card-body d-flex align-items-center pa-15">
                                            <div class="bg-light-{{ $activity['color'] }} h-50 w-50 d-flex-center rounded-circle me-3">
                                                <i class="ph {{ $activity['icon'] }} f-s-20 text-{{ $activity['color'] }}"></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 f-s-16 f-w-600 text-dark">{{ $activity['title'] ?? __('dashboard.activity') }}</h6>
                                                <p class="mb-1 f-s-13 text-muted">{{ $activity['message'] }}</p>
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <small class="text-muted f-s-11">{{ $activity['time'] }}</small>
                                                    @if(isset($activity['type']))
                                                        <span class="badge bg-light-{{ $activity['color'] }} text-{{ $activity['color'] }} f-s-10">
                                                            {{ $activity['type'] }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Modal per eventi del giorno - Solo mobile -->
<div class="modal fade" id="dayEventsModal" tabindex="-1" aria-labelledby="dayEventsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="dayEventsModalLabel">
                    {{ __('dashboard.events_for_day') }} {{ $selectedDay ? \Carbon\Carbon::parse($selectedDay)->format('d/m/Y') : '' }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if($selectedDayEvents && $selectedDayEvents->count() > 0)
                    <div class="d-grid gap-3">
                        @foreach($selectedDayEvents as $event)
                            <div class="d-flex align-items-center p-3 rounded bg-light-primary">
                                <div class="me-3">
                                    <div class="w-3 h-3 rounded-circle bg-{{ $event['color'] ?? 'secondary' }}"></div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <h6 class="mb-0 f-s-14 f-w-600 text-dark cursor-pointer" 
                                            wire:click="viewEvent({{ $event['id'] ?? 0 }})"
                                            data-bs-dismiss="modal">
                                            {{ $event['title'] ?? __('dashboard.event_without_title') }}
                                        </h6>
                                        <span class="badge bg-{{ $event['color'] ?? 'secondary' }} f-s-9">
                                            {{ $event['type'] === 'organized' ? __('dashboard.organized') : ($event['type'] === 'participating' ? __('dashboard.participating') : __('dashboard.wish')) }}
                                        </span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <i class="ph ph-clock f-s-12 text-muted me-1"></i>
                                        <span class="f-s-12 text-muted">{{ $event['time'] ?? __('dashboard.time_not_available') }}</span>
                                        @if(isset($event['venue']) && $event['venue'])
                                            <span class="mx-2 text-muted">•</span>
                                            <i class="ph ph-map-pin f-s-12 text-muted me-1"></i>
                                            <span class="f-s-12 text-muted">{{ $event['venue'] }}{{ isset($event['city']) ? ', ' . $event['city'] : '' }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="ph ph-calendar-x f-s-48 text-muted mb-3"></i>
                        <p class="text-muted mb-0">{{ __('dashboard.no_events_for_this_day') }}</p>
                    </div>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('dashboard.close') }}</button>
            </div>
        </div>
    </div>
</div>

    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Gestione notificazioni
        window.addEventListener('notify', event => {
            const data = event.detail;
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: data.type,
                    title: data.type === 'success' ? __('dashboard.success') : (data.type === 'error' ? __('dashboard.error') : __('dashboard.information')),
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    });

    // Gestione modal eventi giorno
    let dayEventsModal = null;
    
    document.addEventListener('livewire:init', () => {
        // Inizializza il modal una sola volta
        dayEventsModal = new bootstrap.Modal(document.getElementById('dayEventsModal'));
        
        Livewire.on('open-modal', () => {
            // Apri il modal programmaticamente
            dayEventsModal.show();
        });
    });


    // Funzione per cambiare visualizzazione calendario
    function switchCalendarView(view) {
        // Nascondi tutte le visualizzazioni
        document.getElementById('calendar-list-view').classList.add('d-none');
        document.getElementById('calendar-week-view').classList.add('d-none');
        document.getElementById('calendar-month-view').classList.add('d-none');
        
        // Rimuovi classe active da tutti i bottoni
        document.querySelectorAll('.btn-group .btn').forEach(btn => {
            btn.classList.remove('active', 'btn-primary');
            btn.classList.add('btn-outline-primary');
        });
        
        // Mostra la visualizzazione selezionata
        if (view === 'list') {
            document.getElementById('calendar-list-view').classList.remove('d-none');
            document.querySelector('.btn-group .btn:nth-child(1)').classList.add('active', 'btn-primary');
            document.querySelector('.btn-group .btn:nth-child(1)').classList.remove('btn-outline-primary');
        } else if (view === 'week') {
            document.getElementById('calendar-week-view').classList.remove('d-none');
            document.querySelector('.btn-group .btn:nth-child(2)').classList.add('active', 'btn-primary');
            document.querySelector('.btn-group .btn:nth-child(2)').classList.remove('btn-outline-primary');
        } else if (view === 'month') {
            document.getElementById('calendar-month-view').classList.remove('d-none');
            document.querySelector('.btn-group .btn:nth-child(3)').classList.add('active', 'btn-primary');
            document.querySelector('.btn-group .btn:nth-child(3)').classList.remove('btn-outline-primary');
        }
    }

    // Funzione per gestire slider eventi nel calendario desktop
    function initEventSliders() {
        // Trova tutte le celle del calendario con eventi
        document.querySelectorAll('#calendar-desktop-view .col[style*="position: relative"]').forEach(cell => {
            const eventCards = cell.querySelectorAll('.event-card');
            if (eventCards.length > 1) {
                let currentSlide = 0;
                const totalSlides = eventCards.length;
                
                // Funzione per mostrare uno slide specifico
                function showEventSlide(slideIndex) {
                    eventCards.forEach((card, index) => {
                        if (index === slideIndex) {
                            card.style.transform = 'translateX(0)';
                            card.classList.add('active');
                        } else {
                            card.style.transform = 'translateX(100%)';
                            card.classList.remove('active');
                        }
                    });
                    
                    // Aggiorna indicatori
                    const indicators = cell.querySelectorAll('.slider-indicator');
                    indicators.forEach((indicator, index) => {
                        if (index === slideIndex) {
                            indicator.style.background = 'white';
                            indicator.classList.add('active');
                        } else {
                            indicator.style.background = 'rgba(255,255,255,0.5)';
                            indicator.classList.remove('active');
                        }
                    });
                }
                
                // Auto-scroll ogni 3 secondi
                const autoScroll = setInterval(() => {
                    currentSlide = (currentSlide + 1) % totalSlides;
                    showEventSlide(currentSlide);
                }, 3000);
                
                // Pausa auto-scroll quando si passa sopra la cella
                cell.addEventListener('mouseenter', () => {
                    clearInterval(autoScroll);
                });
                
                // Riprendi auto-scroll quando si esce dalla cella
                cell.addEventListener('mouseleave', () => {
                    autoScroll = setInterval(() => {
                        currentSlide = (currentSlide + 1) % totalSlides;
                        showEventSlide(currentSlide);
                    }, 3000);
                });
                
                // Click sugli indicatori per cambiare slide
                cell.querySelectorAll('.slider-indicator').forEach((indicator, index) => {
                    indicator.addEventListener('click', (e) => {
                        e.stopPropagation();
                        currentSlide = index;
                        showEventSlide(currentSlide);
                    });
                });
                
                // Inizializza il primo slide
                showEventSlide(0);
            }
        });
    }

    // Inizializza gli slider quando il DOM è caricato
    document.addEventListener('DOMContentLoaded', function() {
        initEventSliders();
    });

    // Reinizializza gli slider dopo aggiornamenti Livewire
    document.addEventListener('livewire:navigated', function() {
        initEventSliders();
    });
</script>
@endpush
