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
                                <p class="mb-0 opacity-75">Classifica Finale</p>
                            </div>
                            <a href="{{ route('events.scoring.dashboard', $event) }}" class="btn btn-light">
                                <i class="ph ph-arrow-left me-2"></i>Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('events.scoring.dashboard', $event) }}" class="btn btn-light-primary">
                        <i class="ph ph-chart-pie me-2"></i>Dashboard
                    </a>
                    <a href="{{ route('events.scoring.participants', $event) }}" class="btn btn-light-primary">
                        <i class="ph ph-users me-2"></i>Partecipanti
                    </a>
                    <a href="{{ route('events.scoring.scores', $event) }}" class="btn btn-light-primary">
                        <i class="ph ph-pencil-line me-2"></i>Punteggi
                    </a>
                    <a href="{{ route('events.scoring.rankings', $event) }}" class="btn btn-primary">
                        <i class="ph ph-ranking me-2"></i>Classifica
                    </a>
                </div>
            </div>
        </div>

        {{-- Actions --}}
        @if($canCalculate || $rankings->count() > 0)
        <div class="row mb-3">
            <div class="col-12">
                @if($event->status === 'completed')
                    {{-- Event Completed --}}
                    <div class="card border-success">
                        <div class="card-body text-center py-4">
                            <i class="ph-duotone ph-check-circle f-s-60 text-success mb-3"></i>
                            <h4 class="text-success mb-2">🎊 Evento Completato</h4>
                            <p class="text-muted mb-0">
                                Classifica finale pubblicata e badge assegnati ai vincitori!
                            </p>
                        </div>
                    </div>
                @else
                    {{-- Active Event Actions --}}
                    <div class="card">
                        <div class="card-body">
                            {{-- Stats Info --}}
                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <div class="p-3 bg-light-info rounded text-center">
                                        <i class="ph-duotone ph-users f-s-30 text-info mb-2"></i>
                                        <h5 class="mb-0">{{ $stats['with_scores'] }}/{{ $stats['total_participants'] }}</h5>
                                        <small class="text-muted">Con Punteggi</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-3 bg-light-success rounded text-center">
                                        <i class="ph-duotone ph-trophy f-s-30 text-success mb-2"></i>
                                        <h5 class="mb-0">{{ $stats['badges_awarded'] }}</h5>
                                        <small class="text-muted">Badge Assegnati</small>
                                    </div>
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    @if($canCalculate)
                                        <div class="card bg-light-warning h-100">
                                            <div class="card-body text-center">
                                                <i class="ph-duotone ph-calculator f-s-40 text-warning mb-2"></i>
                                                <h6 class="mb-2">Classifica Parziale</h6>
                                                <p class="text-muted small mb-3">Aggiorna classifica senza chiudere evento</p>
                                                <button wire:click="calculatePartialRankings" class="btn btn-warning w-100">
                                                    <i class="ph ph-calculator me-2"></i>Calcola Parziale
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="col-12 col-md-6">
                                    @if($canCalculate && $stats['with_scores'] > 0)
                                        <div class="card bg-light-success h-100">
                                            <div class="card-body text-center">
                                                <i class="ph-duotone ph-trophy f-s-40 text-success mb-2"></i>
                                                <h6 class="mb-2">Finalizza Evento</h6>
                                                <p class="text-muted small mb-3">Classifica + Badge + Chiusura in 1 click</p>
                                                <button onclick="confirmFinalize()" class="btn btn-success w-100">
                                                    <i class="ph ph-check-circle me-2"></i>Termina Evento
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if(!$canCalculate || $stats['with_scores'] === 0)
                                <div class="alert alert-light-info mt-3 mb-0">
                                    <i class="ph ph-info me-2"></i>
                                    Inserisci i punteggi prima di generare la classifica.
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
        @endif

        {{-- Rankings --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph-duotone ph-ranking me-2"></i>
                            Classifica Finale
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($rankings->count() > 0)
                            {{-- Mobile View --}}
                            <div class="d-lg-none">
                                <div class="row g-3">
                                    @foreach($rankings as $ranking)
                                        <div class="col-12">
                                            <div class="card {{ $ranking->position <= 3 ? 'border-warning' : 'border' }}">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center gap-3">
                                                        {{-- Position Badge --}}
                                                        <div class="flex-shrink-0">
                                                            <div class="badge {{ $ranking->position <= 3 ? 'bg-gradient-warning' : 'bg-light-secondary' }} rounded-circle"
                                                                 style="width: 50px; height: 50px; display: flex; align-items: center; justify-content: center; font-size: 20px;">
                                                                {{ $ranking->medal ?: $ranking->position }}
                                                            </div>
                                                        </div>

                                                        {{-- Participant Info --}}
                                                        @if($ranking->participant)
                                                        <div class="flex-grow-1">
                                                            <div class="d-flex align-items-center mb-2">
                                                                @if($ranking->participant->user)
                                                                    <img src="{{ $ranking->participant->user->profile_photo_url ?? asset('assets/images/avatar/default-avatar.webp') }}" 
                                                                         alt="{{ $ranking->participant->display_name }}"
                                                                         class="rounded-circle me-2"
                                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                                @endif
                                                                <div>
                                                                    <h6 class="mb-0">{{ $ranking->participant->display_name }}</h6>
                                                                    @if($ranking->participant->isGuest())
                                                                        <span class="badge bg-light-warning">Ospite</span>
                                                                    @endif
                                                                </div>
                                                            </div>

                                                            <div class="d-flex gap-2 flex-wrap">
                                                                <span class="badge bg-gradient-warning">
                                                                    <i class="ph ph-star-four me-1"></i>{{ number_format($ranking->total_score, 1) }} punti
                                                                </span>
                                                                
                                                                @if($ranking->badge)
                                                                    <span class="badge {{ $ranking->badge_awarded ? 'bg-light-success' : 'bg-light-secondary' }}">
                                                                        <i class="ph ph-trophy me-1"></i>{{ $ranking->badge->name }}
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Desktop Table --}}
                            <div class="table-responsive d-none d-lg-block">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 80px;">Pos.</th>
                                            <th>Partecipante</th>
                                            <th>Punteggi per Turno</th>
                                            <th style="width: 150px;">Totale</th>
                                            <th>Badge</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($rankings as $ranking)
                                            <tr class="{{ $ranking->position <= 3 ? 'table-warning' : '' }}">
                                                <td>
                                                    <div class="badge {{ $ranking->position <= 3 ? 'bg-gradient-warning' : 'bg-light-secondary' }} rounded-circle"
                                                         style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                                                        {{ $ranking->medal ?: $ranking->position }}
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($ranking->participant)
                                                        <div class="d-flex align-items-center">
                                                            @if($ranking->participant->user)
                                                                <img src="{{ $ranking->participant->user->profile_photo_url ?? asset('assets/images/avatar/default-avatar.webp') }}" 
                                                                     alt="{{ $ranking->participant->display_name }}"
                                                                     class="rounded-circle me-2"
                                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                                            @else
                                                                <div class="bg-light-secondary rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                                     style="width: 40px; height: 40px;">
                                                                    <i class="ph ph-user text-secondary"></i>
                                                                </div>
                                                            @endif
                                                            <div>
                                                                <strong>{{ $ranking->participant->display_name }}</strong>
                                                                @if($ranking->participant->isGuest())
                                                                    <br><span class="badge bg-light-warning">Ospite</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($ranking->round_scores)
                                                        <div class="d-flex gap-2 flex-wrap">
                                                            @foreach($ranking->round_scores as $round => $score)
                                                                <span class="badge bg-light-info">
                                                                    T{{ $round }}: {{ number_format($score, 1) }}
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <h5 class="mb-0">
                                                        <span class="badge bg-gradient-warning">
                                                            {{ number_format($ranking->total_score, 1) }}
                                                        </span>
                                                    </h5>
                                                </td>
                                                <td>
                                                    @if($ranking->badge)
                                                        <div class="d-flex align-items-center gap-2">
                                                            <img src="{{ $ranking->badge->icon_url }}" 
                                                                 alt="{{ $ranking->badge->name }}"
                                                                 style="width: 32px; height: 32px;">
                                                            <div>
                                                                <strong class="d-block">{{ $ranking->badge->name }}</strong>
                                                                @if($ranking->badge_awarded)
                                                                    <span class="badge bg-light-success">
                                                                        <i class="ph ph-check me-1"></i>Assegnato
                                                                    </span>
                                                                @else
                                                                    <span class="badge bg-light-secondary">
                                                                        <i class="ph ph-clock me-1"></i>Da assegnare
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="ph-duotone ph-ranking f-s-60 text-muted mb-3"></i>
                                <h5 class="text-muted">{{ __('gamification.no_rankings') }}</h5>
                                
                                @if($canCalculate)
                                    <p class="text-muted mb-3">Hai {{ $stats['with_scores'] }} partecipanti con punteggi. Calcola la classifica!</p>
                                    <button wire:click="calculatePartialRankings" class="btn btn-warning">
                                        <i class="ph ph-calculator me-2"></i>Calcola Classifica Parziale
                                    </button>
                                @else
                                    <p class="text-muted mb-3">Inserisci i punteggi prima di calcolare la classifica.</p>
                                    <a href="{{ route('events.scoring.scores', $event) }}" class="btn btn-primary">
                                        <i class="ph ph-pencil-line me-2"></i>Vai ai Punteggi
                                    </a>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    @script
    <script>
        function confirmFinalize() {
            Swal.fire({
                title: 'Finalizzare l\'Evento?',
                html: `
                    <div class="text-start">
                        <p class="mb-3">Questa azione completerà definitivamente l'evento:</p>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="ph ph-check text-success me-2"></i> Calcola classifica finale</li>
                            <li class="mb-2"><i class="ph ph-trophy text-warning me-2"></i> Assegna badge ai vincitori</li>
                            <li class="mb-2"><i class="ph ph-lock text-info me-2"></i> Chiude l'evento</li>
                            <li class="mb-2"><i class="ph ph-eye text-primary me-2"></i> Pubblica risultati</li>
                        </ul>
                        <p class="text-danger mb-0"><strong>Attenzione:</strong> Non potrai più modificare i punteggi!</p>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sì, Finalizza!',
                cancelButtonText: 'Annulla',
                confirmButtonClass: 'btn btn-success',
                cancelButtonClass: 'btn btn-light-secondary',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('finalizeEvent');
                }
            });
        }

        Livewire.on('swal:success', (data) => {
            Swal.fire({
                icon: 'success',
                title: data[0].title || 'Successo!',
                text: data[0].text || '',
                confirmButtonClass: 'btn btn-primary',
                timer: 3000
            });
        });

        Livewire.on('swal:error', (data) => {
            Swal.fire({
                icon: 'error',
                title: data[0].title || 'Errore',
                text: data[0].text || '',
                confirmButtonClass: 'btn btn-danger'
            });
        });

        Livewire.on('redirect-after-delay', (data) => {
            setTimeout(() => {
                window.location.href = data[0].url;
            }, data[0].delay || 3000);
        });
    </script>
    @endscript
</div>
