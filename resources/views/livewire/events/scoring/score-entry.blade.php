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
                                <p class="mb-0 opacity-75">Inserimento Punteggi</p>
                            </div>
                            <a href="{{ route('events.scoring.dashboard', $event) }}" class="btn btn-light">
                                <i class="ph ph-arrow-left me-2"></i>Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Lock Alert --}}
        @if($isLocked)
        <div class="row mb-3">
            <div class="col-12">
                <div class="alert alert-warning d-flex align-items-center">
                    <i class="ph ph-lock-simple f-s-24 me-3"></i>
                    <div>
                        <strong>Evento Completato</strong><br>
                        La classifica è stata generata. Non è possibile modificare i punteggi.
                    </div>
                </div>
            </div>
        </div>
        @endif

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
                    <a href="{{ route('events.scoring.scores', $event) }}" class="btn btn-primary">
                        <i class="ph ph-pencil-line me-2"></i>Punteggi
                    </a>
                    <a href="{{ route('events.scoring.rankings', $event) }}" class="btn btn-light-primary">
                        <i class="ph ph-ranking me-2"></i>Classifica
                    </a>
                </div>
            </div>
        </div>

        {{-- Round Management --}}
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="ph-duotone ph-timer me-2"></i>
                            Turni ({{ $rounds->count() }})
                        </h5>
                        <button wire:click="openRoundModal" class="btn btn-sm btn-primary">
                            <i class="ph ph-plus me-2"></i>Aggiungi Turno
                        </button>
                    </div>
                    <div class="card-body">
                        @if($rounds->count() > 0)
                            <div class="d-flex gap-2 flex-wrap mb-3">
                                @foreach($rounds as $round)
                                    <button wire:click="$set('selectedRound', {{ $round->round_number }})" 
                                            class="btn {{ $selectedRound == $round->round_number ? 'btn-primary' : 'btn-light-primary' }}">
                                        {{ $round->name }}
                                    </button>
                                @endforeach
                            </div>

                            <div class="d-flex gap-2">
                                @foreach($rounds as $round)
                                    @if($selectedRound == $round->round_number)
                                        <button wire:click="editRound({{ $round->id }})" class="btn btn-sm btn-light-warning">
                                            <i class="ph ph-pencil me-1"></i>Modifica
                                        </button>
                                        <button wire:click="deleteRound({{ $round->id }})" 
                                                class="btn btn-sm btn-light-danger"
                                                onclick="return confirm('Eliminare questo turno?')">
                                            <i class="ph ph-trash me-1"></i>Elimina
                                        </button>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mb-0">Nessun turno configurato. Verrà creato automaticamente "Turno Unico".</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Score Entry --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph-duotone ph-pencil-line me-2"></i>
                            Punteggi - Turno {{ $selectedRound }}
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($participants->count() > 0)
                            {{-- Mobile View --}}
                            <div class="d-lg-none">
                                <div class="row g-3">
                                    @foreach($participants as $participant)
                                        <div class="col-12">
                                            <div class="card border">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center gap-3 mb-3">
                                                        @if($participant->user)
                                                            <img src="{{ $participant->user->profile_photo_url ?? asset('assets/images/avatar/default-avatar.webp') }}" 
                                                                 alt="{{ $participant->display_name }}"
                                                                 class="rounded-circle"
                                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                                        @else
                                                            <div class="bg-light-secondary rounded-circle d-flex align-items-center justify-content-center"
                                                                 style="width: 50px; height: 50px;">
                                                                <i class="ph ph-user f-s-24 text-secondary"></i>
                                                            </div>
                                                        @endif
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-0">
                                                                @if($participant->performance_order)
                                                                    <span class="badge bg-light-primary me-2">#{{ $participant->performance_order }}</span>
                                                                @endif
                                                                {{ $participant->display_name }}
                                                            </h6>
                                                        </div>
                                                    </div>

                                                    <div class="mb-2">
                                                        <label class="form-label small">Punteggio (0.0 - 10.0)</label>
                                                        <div class="input-group">
                                                            <input type="number" 
                                                                   wire:model="scores.{{ $participant->id }}"
                                                                   class="form-control" 
                                                                   step="0.1" 
                                                                   min="0" 
                                                                   max="10"
                                                                   placeholder="Es: 9.5">
                                                            <button wire:click="saveScore({{ $participant->id }})" 
                                                                    class="btn btn-primary">
                                                                <i class="ph ph-check"></i>
                                                            </button>
                                                        </div>
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
                                            <th style="width: 80px;">#</th>
                                            <th>Partecipante</th>
                                            <th style="width: 200px;">Punteggio (0.0 - 10.0)</th>
                                            <th style="width: 100px;">Azioni</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($participants as $participant)
                                            <tr>
                                                <td>
                                                    @if($participant->performance_order)
                                                        <span class="badge bg-light-primary">#{{ $participant->performance_order }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($participant->user)
                                                            <img src="{{ $participant->user->profile_photo_url ?? asset('assets/images/avatar/default-avatar.webp') }}" 
                                                                 alt="{{ $participant->display_name }}"
                                                                 class="rounded-circle me-2"
                                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                                        @else
                                                            <div class="bg-light-secondary rounded-circle me-2 d-flex align-items-center justify-content-center"
                                                                 style="width: 40px; height: 40px;">
                                                                <i class="ph ph-user text-secondary"></i>
                                                            </div>
                                                        @endif
                                                        <strong>{{ $participant->display_name }}</strong>
                                                    </div>
                                                </td>
                                                <td>
                                                    <input type="number" 
                                                           wire:model="scores.{{ $participant->id }}"
                                                           class="form-control" 
                                                           step="0.1" 
                                                           min="0" 
                                                           max="10"
                                                           placeholder="Es: 9.5">
                                                </td>
                                                <td>
                                                    <button wire:click="saveScore({{ $participant->id }})" 
                                                            class="btn btn-primary">
                                                        <i class="ph ph-check me-1"></i>Salva
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="alert alert-light-info mt-3 mb-0">
                                <i class="ph ph-info me-2"></i>
                                <strong>Nota:</strong> I punteggi vengono salvati automaticamente. Scala 0.0 - 10.0 con un decimale (es: 9.5).
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="ph-duotone ph-users f-s-60 text-muted mb-3"></i>
                                <h5 class="text-muted">Nessun partecipante</h5>
                                <p class="text-muted">Aggiungi partecipanti prima di inserire i punteggi!</p>
                                <a href="{{ route('events.scoring.participants', $event) }}" class="btn btn-primary">
                                    <i class="ph ph-user-plus me-2"></i>Aggiungi Partecipanti
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Round Modal --}}
    @if($showRoundModal)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $editingRound ? 'Modifica Turno' : 'Aggiungi Turno' }}</h5>
                        <button type="button" class="btn-close" wire:click="$set('showRoundModal', false)"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label">Numero Turno *</label>
                                <input type="number" wire:model="round_number" class="form-control" min="1" required>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label">Nome Turno *</label>
                                <input type="text" wire:model="round_name" class="form-control" required>
                                <small class="text-muted">Es: "Primo Turno", "Semifinale", "Finale"</small>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Tipo Punteggio *</label>
                                <select wire:model="scoring_type" class="form-select">
                                    <option value="average">Media (consigliato)</option>
                                    <option value="sum">Somma</option>
                                    <option value="best_of">Migliore</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" wire:click="$set('showRoundModal', false)">
                            Annulla
                        </button>
                        <button type="button" class="btn btn-primary" wire:click="saveRound">
                            <i class="ph ph-check me-2"></i>{{ $editingRound ? 'Aggiorna' : 'Crea' }} Turno
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @script
    <script>
        Livewire.on('swal:success', (data) => {
            Swal.fire({
                icon: 'success',
                title: data[0].title || 'Successo!',
                text: data[0].text || '',
                confirmButtonClass: 'btn btn-primary',
                timer: 2000,
                toast: true,
                position: 'top-end',
                showConfirmButton: false
            });
        });

        Livewire.on('swal:warning', (data) => {
            Swal.fire({
                icon: 'warning',
                title: data[0].title || 'Attenzione',
                text: data[0].text || '',
                confirmButtonClass: 'btn btn-warning'
            });
        });
    </script>
    @endscript
</div>
