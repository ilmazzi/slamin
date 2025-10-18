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

        <!-- 2. CALENDARIO - Mobile First con visualizzazioni intelligenti -->
        @include('livewire.dashboard.calendar-mobile')
        
        <!-- 3. STATISTICHE COMPATTE - Mobile First, molto più piccole -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card hover-effect equal-card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="card-title mb-0 f-w-600">
                                <i class="ph ph-chart-line me-2 text-info"></i>{{ __('dashboard.statistics') }}
                            </h6>
                            <a href="{{ route('user-stats.index') }}" class="btn btn-light-info btn-sm">
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
                                            <div class="bg-light-secondary rounded-circle d-flex-center mx-auto mb-2" style="width: 40px; height: 40px;">
                                                <i class="ph ph-calendar-check f-s-16 text-secondary"></i>
                                            </div>
                                            <h6 class="mb-0 f-s-14 f-w-600 text-dark">{{ $stats['past_events'] ?? 0 }}</h6>
                                            <small class="text-muted f-s-11">Passati</small>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Statistica 2 - Eventi Futuri -->
                            <div class="col-4 col-lg-2">
                                <a href="{{ route('events.index', ['filter' => 'upcoming']) }}" class="text-decoration-none">
                                    <div class="card hover-effect b-t-3-primary">
                                        <div class="card-body text-center pa-10">
                                            <div class="bg-light-primary rounded-circle d-flex-center mx-auto mb-2" style="width: 40px; height: 40px;">
                                                <i class="ph ph-calendar f-s-16 text-primary"></i>
                                            </div>
                                            <h6 class="mb-0 f-s-14 f-w-600 text-dark">{{ $stats['future_events'] ?? 0 }}</h6>
                                            <small class="text-muted f-s-11">Futuri</small>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Statistica 3 - Eventi Organizzati -->
                            <div class="col-4 col-lg-2">
                                <a href="{{ route('events.index', ['filter' => 'organized']) }}" class="text-decoration-none">
                                    <div class="card hover-effect b-t-3-success">
                                        <div class="card-body text-center pa-10">
                                            <div class="bg-light-success rounded-circle d-flex-center mx-auto mb-2" style="width: 40px; height: 40px;">
                                                <i class="ph ph-users f-s-16 text-success"></i>
                                            </div>
                                            <h6 class="mb-0 f-s-14 f-w-600 text-dark">{{ $stats['organized_events'] ?? 0 }}</h6>
                                            <small class="text-muted f-s-11">Organizzati</small>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Statistica 4 - Inviti Pendenti -->
                            <div class="col-4 col-lg-2">
                                <a href="{{ route('events.index', ['filter' => 'invitations']) }}" class="text-decoration-none">
                                    <div class="card hover-effect b-t-3-warning">
                                        <div class="card-body text-center pa-10">
                                            <div class="bg-light-warning rounded-circle d-flex-center mx-auto mb-2" style="width: 40px; height: 40px;">
                                                <i class="ph ph-envelope f-s-16 text-warning"></i>
                                            </div>
                                            <h6 class="mb-0 f-s-14 f-w-600 text-dark">{{ $stats['pending_invitations'] ?? 0 }}</h6>
                                            <small class="text-muted f-s-11">Inviti</small>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Statistica 5 - Poesie -->
                            <div class="col-4 col-lg-2">
                                <a href="{{ route('poems.index') }}" class="text-decoration-none">
                                    <div class="card hover-effect b-t-3-info">
                                        <div class="card-body text-center pa-10">
                                            <div class="bg-light-info rounded-circle d-flex-center mx-auto mb-2" style="width: 40px; height: 40px;">
                                                <i class="ph ph-book-open f-s-16 text-info"></i>
                                            </div>
                                            <h6 class="mb-0 f-s-14 f-w-600 text-dark">{{ $stats['poems'] ?? 0 }}</h6>
                                            <small class="text-muted f-s-11">Poesie</small>
                                        </div>
                                    </div>
                                </a>
                            </div>

                            <!-- Statistica 6 - Gruppi -->
                            <div class="col-4 col-lg-2">
                                <a href="{{ route('groups.index') }}" class="text-decoration-none">
                                    <div class="card hover-effect b-t-3-danger">
                                        <div class="card-body text-center pa-10">
                                            <div class="bg-light-danger rounded-circle d-flex-center mx-auto mb-2" style="width: 40px; height: 40px;">
                                                <i class="ph ph-users-three f-s-16 text-danger"></i>
                                            </div>
                                            <h6 class="mb-0 f-s-14 f-w-600 text-dark">{{ $stats['groups'] ?? 0 }}</h6>
                                            <small class="text-muted f-s-11">Gruppi</small>
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
                                    <a href="{{ $action['url'] }}" class="text-decoration-none">
                                        <div class="card hover-effect b-s-4-{{ $action['color'] }}">
                                            <div class="card-body d-flex align-items-center pa-15">
                                                <div class="bg-light-{{ $action['color'] }} h-50 w-50 d-flex-center rounded-circle me-3">
                                                    <i class="ph {{ $action['icon'] }} f-s-20 text-{{ $action['color'] }}"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 f-s-16 f-w-600 text-dark">{{ $action['title'] }}</h6>
                                                    <p class="mb-0 f-s-13 text-muted">{{ $action['description'] }}</p>
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
                                        <div class="card-body d-flex align-items-center pa-15">
                                            <!-- Icona evento -->
                                            <div class="bg-light-{{ $event['type'] === 'organized' ? 'primary' : ($event['type'] === 'participating' ? 'secondary' : 'primary') }} h-50 w-50 d-flex-center rounded-circle me-3">
                                                <i class="ph ph-calendar f-s-20 text-{{ $event['type'] === 'organized' ? 'primary' : ($event['type'] === 'participating' ? 'secondary' : 'primary') }}"></i>
                                            </div>
                                            
                                            <!-- Contenuto evento -->
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-start justify-content-between mb-1">
                                                    <h6 class="mb-0 f-s-16 f-w-600 text-dark">{{ $event['title'] }}</h6>
                                                    <span class="badge bg-light-{{ $event['type'] === 'organized' ? 'primary' : ($event['type'] === 'participating' ? 'secondary' : 'primary') }} text-{{ $event['type'] === 'organized' ? 'primary' : ($event['type'] === 'participating' ? 'secondary' : 'primary') }} f-s-10">
                                                        {{ $event['type'] === 'organized' ? 'Organizzato' : ($event['type'] === 'participating' ? 'Partecipo' : 'Wishlist') }}
                                                    </span>
                                                </div>
                                                <div class="d-flex align-items-center mb-1">
                                                    <i class="ph ph-calendar f-s-14 text-muted me-2"></i>
                                                    <span class="f-s-13 text-muted">{{ $event['date'] }}</span>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <i class="ph ph-map-pin f-s-14 text-muted me-2"></i>
                                                    <span class="f-s-13 text-muted">{{ $event['venue'] }}, {{ $event['city'] }}</span>
                                                </div>
                                            </div>
                                            
                                            <!-- Bottone azione -->
                                            <div class="ms-3">
                                                <a href="{{ $event['url'] }}" class="btn btn-{{ $event['type'] === 'organized' ? 'primary' : ($event['type'] === 'participating' ? 'secondary' : 'primary') }} btn-sm">
                                                    <i class="ph ph-arrow-right me-1"></i>Vedi
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

            <!-- ATTIVITÀ SOCIALI -->
            <div class="col-12 col-lg-6">
                <div class="card hover-effect equal-card">
                    <div class="card-header">
                        <h6 class="card-title mb-0 f-w-600">
                            <i class="ph ph-users me-2 text-primary"></i>Attività Sociali
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
                                        <h6 class="mb-1 f-s-16 f-w-600 text-dark">Amici Online</h6>
                                        <p class="mb-0 f-s-13 text-muted">3 amici attivi ora</p>
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
                                        <h6 class="mb-1 f-s-16 f-w-600 text-dark">Gruppi Attivi</h6>
                                        <p class="mb-0 f-s-13 text-muted">2 gruppi con nuove attività</p>
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
                                        <h6 class="mb-1 f-s-16 f-w-600 text-dark">Inviti Ricevuti</h6>
                                        <p class="mb-0 f-s-13 text-muted">1 nuovo invito da rispondere</p>
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
                                        <h6 class="mb-1 f-s-16 f-w-600 text-dark">Messaggi</h6>
                                        <p class="mb-0 f-s-13 text-muted">5 messaggi non letti</p>
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
                                                    <h6 class="mb-0 f-s-14 f-w-600 text-dark">{{ $content['title'] }}</h6>
                                                </div>
                                                <p class="mb-2 f-s-12 text-muted">{{ $content['description'] }}</p>
                                                @if(isset($content['count']))
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <span class="badge bg-{{ $content['color'] }} f-s-10">{{ $content['count'] }}</span>
                                                        @if(isset($content['url']))
                                                            <a href="{{ $content['url'] }}" class="btn btn-{{ $content['color'] }} btn-sm">
                                                                <i class="ph ph-arrow-right me-1"></i>Vedi
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
                                <i class="ph ph-clock me-2 text-info"></i>{{ __('dashboard.recent_activity') }}
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
                                                <h6 class="mb-1 f-s-16 f-w-600 text-dark">{{ $activity['title'] }}</h6>
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
                    title: data.type === 'success' ? 'Successo' : (data.type === 'error' ? 'Errore' : 'Info'),
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        });
    });
</script>
@endpush
