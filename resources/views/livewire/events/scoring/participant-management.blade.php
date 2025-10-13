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
                                <p class="mb-0 opacity-75">Gestione Partecipanti</p>
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
                    <a href="{{ route('events.scoring.participants', $event) }}" class="btn btn-primary">
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

        {{-- Participants List --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <h5 class="mb-0">
                                <i class="ph-duotone ph-users me-2"></i>
                                Partecipanti ({{ $participants->count() }})
                            </h5>
                            <button wire:click="openAddModal" class="btn btn-primary">
                                <i class="ph ph-plus me-2"></i>Aggiungi
                            </button>
                        </div>
                        <p class="text-muted small mb-0 mt-2">
                            <i class="ph ph-info me-1"></i>
                            Gli utenti vengono aggiunti automaticamente quando accettano inviti o richieste per questo evento Poetry Slam.
                        </p>
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
                                                    <div class="d-flex align-items-start gap-3 mb-3">
                                                        @if($participant->user)
                                                            <img src="{{ $participant->user->profile_photo_url ?? asset('assets/images/avatar/default-avatar.webp') }}" 
                                                                 alt="{{ $participant->display_name }}"
                                                                 class="rounded-circle flex-shrink-0"
                                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                                        @else
                                                            <div class="bg-light-secondary rounded-circle flex-shrink-0 d-flex align-items-center justify-content-center"
                                                                 style="width: 50px; height: 50px;">
                                                                <i class="ph ph-user f-s-24 text-secondary"></i>
                                                            </div>
                                                        @endif
                                                        
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1">
                                                                @if($participant->performance_order)
                                                                    <span class="badge bg-light-primary me-2">#{{ $participant->performance_order }}</span>
                                                                @endif
                                                                {{ $participant->display_name }}
                                                            </h6>
                                                            @if($participant->isGuest())
                                                                <span class="badge bg-light-warning mb-2">
                                                                    <i class="ph ph-user-circle me-1"></i>Ospite
                                                                </span>
                                                            @endif
                                                            @if($participant->guest_email)
                                                                <p class="small text-muted mb-0">{{ $participant->guest_email }}</p>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label small">Stato</label>
                                                        <select wire:change="updateStatus({{ $participant->id }}, $event.target.value)" 
                                                                class="form-select form-select-sm">
                                                            <option value="confirmed" {{ $participant->status === 'confirmed' ? 'selected' : '' }}>Confermato</option>
                                                            <option value="performed" {{ $participant->status === 'performed' ? 'selected' : '' }}>Esibito</option>
                                                            <option value="disqualified" {{ $participant->status === 'disqualified' ? 'selected' : '' }}>Squalificato</option>
                                                            <option value="no_show" {{ $participant->status === 'no_show' ? 'selected' : '' }}>Assente</option>
                                                        </select>
                                                    </div>

                                                    <button wire:click="removeParticipant({{ $participant->id }})" 
                                                            class="btn btn-light-danger w-100"
                                                            onclick="return confirm('Sei sicuro di voler rimuovere questo partecipante?')">
                                                        <i class="ph ph-trash me-2"></i>Rimuovi
                                                    </button>
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
                                            <th>Tipo</th>
                                            <th>Stato</th>
                                            <th>Punteggio</th>
                                            <th style="width: 120px;">Azioni</th>
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
                                                        <div>
                                                            <strong>{{ $participant->display_name }}</strong>
                                                            @if($participant->guest_email)
                                                                <br><small class="text-muted">{{ $participant->guest_email }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($participant->isGuest())
                                                        <span class="badge bg-light-warning">
                                                            <i class="ph ph-user-circle me-1"></i>Ospite
                                                        </span>
                                                    @else
                                                        <span class="badge bg-light-success">
                                                            <i class="ph ph-user-check me-1"></i>Registrato
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <select wire:change="updateStatus({{ $participant->id }}, $event.target.value)" 
                                                            class="form-select form-select-sm">
                                                        <option value="confirmed" {{ $participant->status === 'confirmed' ? 'selected' : '' }}>Confermato</option>
                                                        <option value="performed" {{ $participant->status === 'performed' ? 'selected' : '' }}>Esibito</option>
                                                        <option value="disqualified" {{ $participant->status === 'disqualified' ? 'selected' : '' }}>Squalificato</option>
                                                        <option value="no_show" {{ $participant->status === 'no_show' ? 'selected' : '' }}>Assente</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    @if($participant->ranking)
                                                        <span class="badge bg-gradient-warning">
                                                            {{ number_format($participant->ranking->total_score, 1) }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button wire:click="removeParticipant({{ $participant->id }})" 
                                                            class="btn btn-light-danger"
                                                            onclick="return confirm('Sei sicuro?')">
                                                        <i class="ph ph-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="ph-duotone ph-users f-s-60 text-muted mb-3"></i>
                                <h5 class="text-muted">Nessun partecipante</h5>
                                <p class="text-muted">Aggiungi il primo partecipante all'evento!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Participant Modal --}}
    @if($showAddModal)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Aggiungi Partecipante</h5>
                        <button type="button" class="btn-close" wire:click="$set('showAddModal', false)"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Type Selection --}}
                        <div class="mb-4">
                            <label class="form-label">Tipo Partecipante</label>
                            <div class="d-flex gap-2">
                                <button type="button" 
                                        wire:click="$set('participantType', 'user')"
                                        class="btn {{ $participantType === 'user' ? 'btn-primary' : 'btn-light-primary' }} flex-fill">
                                    <i class="ph ph-user-check me-2"></i>Utente Registrato
                                </button>
                                <button type="button" 
                                        wire:click="$set('participantType', 'guest')"
                                        class="btn {{ $participantType === 'guest' ? 'btn-primary' : 'btn-light-primary' }} flex-fill">
                                    <i class="ph ph-user-circle me-2"></i>Ospite
                                </button>
                            </div>
                        </div>

                        @if($participantType === 'user')
                            {{-- User Search --}}
                            <div class="mb-3">
                                <label class="form-label">Cerca Utente *</label>
                                @if($selectedUser)
                                    <div class="card border">
                                        <div class="card-body p-3">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $selectedUser['avatar'] }}" 
                                                         alt="{{ $selectedUser['display_name'] }}"
                                                         class="rounded-circle me-3"
                                                         style="width: 48px; height: 48px;">
                                                    <strong>{{ $selectedUser['display_name'] }}</strong>
                                                </div>
                                                <button type="button" wire:click="clearSelectedUser" class="btn btn-sm btn-light-danger">
                                                    <i class="ph ph-x"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <input type="text" 
                                           wire:model.live.debounce.300ms="userSearch"
                                           class="form-control"
                                           placeholder="Nome, nickname o email...">
                                    
                                    @if(count($searchResults) > 0)
                                        <div class="list-group mt-2" style="max-height: 300px; overflow-y: auto;">
                                            @foreach($searchResults as $result)
                                                <button type="button" 
                                                        wire:click="selectUser({{ $result['id'] }})"
                                                        class="list-group-item list-group-item-action">
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $result['avatar'] }}" 
                                                             alt="{{ $result['display_name'] }}"
                                                             class="rounded-circle me-3"
                                                             style="width: 40px; height: 40px;">
                                                        <div>
                                                            <h6 class="mb-0">{{ $result['display_name'] }}</h6>
                                                            <small class="text-muted">{{ $result['email'] }}</small>
                                                        </div>
                                                    </div>
                                                </button>
                                            @endforeach
                                        </div>
                                    @endif
                                @endif
                            </div>
                        @else
                            {{-- Guest Fields --}}
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Nome *</label>
                                    <input type="text" wire:model="guest_name" class="form-control" required>
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Email</label>
                                    <input type="email" wire:model="guest_email" class="form-control">
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label">Telefono</label>
                                    <input type="text" wire:model="guest_phone" class="form-control">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Bio</label>
                                    <textarea wire:model="guest_bio" class="form-control" rows="2"></textarea>
                                </div>
                            </div>
                        @endif

                        {{-- Common Fields --}}
                        <div class="row g-3 mt-2">
                            <div class="col-12 col-md-6">
                                <label class="form-label">Ordine Esibizione</label>
                                <input type="number" wire:model="performance_order" class="form-control" min="1">
                                <small class="text-muted">Lascia vuoto per auto-assegnazione</small>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Note</label>
                                <textarea wire:model="notes" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" wire:click="$set('showAddModal', false)">
                            Annulla
                        </button>
                        <button type="button" class="btn btn-primary" wire:click="addParticipant">
                            <i class="ph ph-check me-2"></i>Aggiungi Partecipante
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
                timer: 3000
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
