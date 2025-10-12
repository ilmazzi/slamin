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
                                        Data da definire
                                    @endif
                                </p>
                            </div>
                            <a href="{{ route('events.show', $event) }}" class="btn btn-light">
                                <i class="ph ph-arrow-left me-2"></i>Torna all'Evento
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
                        <i class="ph ph-chart-pie me-2"></i>Dashboard
                    </a>
                    <a href="{{ route('events.scoring.participants', $event) }}" class="btn btn-light-primary">
                        <i class="ph ph-users me-2"></i>Partecipanti
                    </a>
                    <a href="{{ route('events.scoring.scores', $event) }}" class="btn btn-light-primary">
                        <i class="ph ph-pencil-line me-2"></i>Punteggi
                    </a>
                    <a href="{{ route('events.scoring.rankings', $event) }}" class="btn btn-light-primary">
                        <i class="ph ph-ranking me-2"></i>Classifica
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
                        <p class="text-muted small mb-0">Partecipanti</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card bg-light-success">
                    <div class="card-body text-center">
                        <i class="ph-duotone ph-check-circle f-s-40 text-success mb-2"></i>
                        <h3 class="mb-1">{{ $stats['performed_participants'] }}</h3>
                        <p class="text-muted small mb-0">Esibiti</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card bg-light-info">
                    <div class="card-body text-center">
                        <i class="ph-duotone ph-timer f-s-40 text-info mb-2"></i>
                        <h3 class="mb-1">{{ $stats['total_rounds'] }}</h3>
                        <p class="text-muted small mb-0">Turni</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card bg-light-warning">
                    <div class="card-body text-center">
                        <i class="ph-duotone ph-pencil-line f-s-40 text-warning mb-2"></i>
                        <h3 class="mb-1">{{ $stats['total_scores'] }}</h3>
                        <p class="text-muted small mb-0">Punteggi</p>
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
                            Azioni Rapide
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12 col-md-6 col-lg-3">
                                <a href="{{ route('events.scoring.participants', $event) }}" class="btn btn-light-primary w-100 py-3">
                                    <i class="ph ph-user-plus f-s-30 d-block mb-2"></i>
                                    <strong>Gestisci Partecipanti</strong>
                                    <p class="small mb-0 text-muted">{{ $stats['total_participants'] }} iscritti</p>
                                </a>
                            </div>
                            <div class="col-12 col-md-6 col-lg-3">
                                <a href="{{ route('events.scoring.scores', $event) }}" class="btn btn-light-info w-100 py-3">
                                    <i class="ph ph-pencil-line f-s-30 d-block mb-2"></i>
                                    <strong>Inserisci Punteggi</strong>
                                    <p class="small mb-0 text-muted">{{ $stats['total_scores'] }} voti</p>
                                </a>
                            </div>
                            <div class="col-12 col-md-6 col-lg-3">
                                <a href="{{ route('events.scoring.rankings', $event) }}" class="btn btn-light-warning w-100 py-3">
                                    <i class="ph ph-ranking f-s-30 d-block mb-2"></i>
                                    <strong>Visualizza Classifica</strong>
                                    <p class="small mb-0 text-muted">
                                        @if($stats['has_rankings'])
                                            Classifica pronta
                                        @else
                                            Non ancora calcolata
                                        @endif
                                    </p>
                                </a>
                            </div>
                            <div class="col-12 col-md-6 col-lg-3">
                                <button class="btn btn-light-success w-100 py-3" disabled>
                                    <i class="ph ph-trophy f-s-30 d-block mb-2"></i>
                                    <strong>Badge Assegnati</strong>
                                    <p class="small mb-0 text-muted">{{ $stats['winners_count'] }} vincitori</p>
                                </button>
                            </div>
                        </div>

                        @if(!$stats['has_rankings'] && $stats['total_scores'] > 0)
                        <div class="alert alert-light-info mt-4 mb-0">
                            <i class="ph ph-info me-2"></i>
                            <strong>Hai punteggi inseriti!</strong>
                            Vai su "Classifica" per calcolare i risultati finali e assegnare i badge ai vincitori.
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
