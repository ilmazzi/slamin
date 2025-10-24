<div>
    <div class="container-fluid">
        {{-- Event Info Header --}}
        <div class="row mb-3">
            <div class="col-12">
                <div class="card bg-gradient-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                            <div>
                                <h4 class="text-white mb-1">{{ $event->title }}</h4>
                                <p class="mb-0 opacity-75">
                                    <i class="ph ph-calendar me-2"></i>
                                    @if($event->start_datetime)
                                        {{ $event->start_datetime->format('d/m/Y H:i') }}
                                    @else
                                        {{ __('events.scoring.data_to_define') }}
                                    @endif
                                </p>
                            </div>
                            <a href="{{ route('events.show', $event) }}" class="btn btn-light">
                                <i class="ph ph-arrow-left me-2"></i>{{ __('events.scoring.return_to_event') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Navigation Tabs --}}
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('events.scoring.dashboard', $event) }}" class="btn btn-primary">
                        <i class="ph ph-chart-pie me-2"></i>{{ __('events.scoring.dashboard') }}
                    </a>
                    <a href="{{ route('events.scoring.participants', $event) }}" class="btn btn-light-primary">
                        <i class="ph ph-users me-2"></i>{{ __('events.scoring.participants') }}
                    </a>
                    <a href="{{ route('events.scoring.scores', $event) }}" class="btn btn-light-primary">
                        <i class="ph ph-pencil-line me-2"></i>{{ __('events.scoring.scores') }}
                    </a>
                    <a href="{{ route('events.scoring.rankings', $event) }}" class="btn btn-light-primary">
                        <i class="ph ph-ranking me-2"></i>{{ __('events.scoring.rankings') }}
                    </a>
                </div>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card bg-light-primary">
                    <div class="card-body text-center">
                        <i class="ph-duotone ph-users f-s-40 text-primary mb-2"></i>
                        <h3 class="mb-1">{{ $stats['total_participants'] }}</h3>
                        <p class="text-muted small mb-0">{{ __('events.scoring.participants') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card bg-light-success">
                    <div class="card-body text-center">
                        <i class="ph-duotone ph-check-circle f-s-40 text-success mb-2"></i>
                        <h3 class="mb-1">{{ $stats['performed_participants'] }}</h3>
                        <p class="text-muted small mb-0">{{ __('events.scoring.performed_participants') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card bg-light-info">
                    <div class="card-body text-center">
                        <i class="ph-duotone ph-timer f-s-40 text-info mb-2"></i>
                        <h3 class="mb-1">{{ $stats['total_rounds'] }}</h3>
                        <p class="text-muted small mb-0">{{ __('events.scoring.rounds') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card bg-light-warning">
                    <div class="card-body text-center">
                        <i class="ph-duotone ph-pencil-line f-s-40 text-warning mb-2"></i>
                        <h3 class="mb-1">{{ $stats['total_scores'] }}</h3>
                        <p class="text-muted small mb-0">{{ __('events.scoring.scores') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph-duotone ph-lightning me-2"></i>
                            {{ __('events.scoring.quick_actions') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12 col-md-6 col-lg-3">
                                <a href="{{ route('events.scoring.participants', $event) }}" class="btn btn-light-primary w-100 py-3">
                                    <i class="ph ph-user-plus f-s-30 d-block mb-2"></i>
                                        <strong>{{ __('events.scoring.manage_participants') }}</strong>
                                    <p class="small mb-0 text-muted">{{ $stats['total_participants'] }} {{ __('events.scoring.registered') }}</p>
                                </a>
                            </div>
                            <div class="col-12 col-md-6 col-lg-3">
                                <a href="{{ route('events.scoring.scores', $event) }}" class="btn btn-light-info w-100 py-3">
                                    <i class="ph ph-pencil-line f-s-30 d-block mb-2"></i>
                                    <strong>{{ __('events.scoring.insert_scores') }}</strong>
                                    <p class="small mb-0 text-muted">{{ $stats['total_scores'] }} voti</p>
                                </a>
                            </div>
                            <div class="col-12 col-md-6 col-lg-3">
                                <a href="{{ route('events.scoring.rankings', $event) }}" class="btn btn-light-warning w-100 py-3">
                                    <i class="ph ph-ranking f-s-30 d-block mb-2"></i>
                                    <strong>{{ __('events.scoring.view_rankings') }}</strong>
                                    <p class="small mb-0 text-muted">
                                        @if($stats['has_rankings'])
                                            {{ __('events.scoring.rankings_ready') }}
                                        @else
                                            {{ __('events.scoring.rankings_not_calculated') }}
                                        @endif
                                    </p>
                                </a>
                            </div>
                            <div class="col-12 col-md-6 col-lg-3">
                                <button class="btn btn-light-success w-100 py-3" disabled>
                                    <i class="ph ph-trophy f-s-30 d-block mb-2"></i>
                                    <strong>{{ __('events.scoring.assigned_badges') }}</strong>
                                    <p class="small mb-0 text-muted">{{ $stats['winners_count'] }} {{ __('events.scoring.winners') }}</p>
                                </button>
                            </div>
                        </div>

                        @if(!$stats['has_rankings'] && $stats['total_scores'] > 0)
                        <div class="alert alert-light-info mt-4 mb-0">
                            <i class="ph ph-info me-2"></i>
                            <strong>{{ __('events.scoring.scores_inserted') }}</strong>
                            {{ __('events.scoring.go_to_rankings') }}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
