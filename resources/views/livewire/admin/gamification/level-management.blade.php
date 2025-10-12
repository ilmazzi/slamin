<div>
    <div class="container-fluid">
        {{-- Navigation Tabs --}}
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.gamification.badges') }}" class="btn btn-light-primary">
                        <i class="ph ph-trophy me-2"></i>Badge
                    </a>
                    <a href="{{ route('admin.gamification.user-badges') }}" class="btn btn-light-primary">
                        <i class="ph ph-users-three me-2"></i>Badge Utenti
                    </a>
                    <a href="{{ route('admin.gamification.levels') }}" class="btn btn-primary">
                        <i class="ph ph-chart-line me-2"></i>Livelli
                    </a>
                    <a href="{{ route('admin.gamification.leaderboards') }}" class="btn btn-light-primary">
                        <i class="ph ph-ranking me-2"></i>Classifiche
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="ph-duotone ph-chart-line me-2"></i>
                            {{ __('gamification.levels_management') }}
                        </h4>
                        <button wire:click="create" class="btn btn-primary">
                            <i class="ph ph-plus me-2"></i>
                            {{ __('gamification.create_level') }}
                        </button>
                    </div>

                    <div class="card-body">
                        @if($levels && $levels->count() > 0)
                            {{-- Mobile Card View --}}
                            <div class="d-lg-none">
                                <div class="row g-3">
                                    @foreach($levels as $lvl)
                                        <div class="col-12">
                                            <div class="card border-primary">
                                                <div class="card-body">
                                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                                        <div>
                                                            <h5 class="mb-1">
                                                                <span class="badge bg-light-primary me-2">{{ $lvl->level }}</span>
                                                                {{ $lvl->name }}
                                                            </h5>
                                                            @if($lvl->description)
                                                                <p class="text-muted small mb-0">{{ $lvl->description }}</p>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="row g-2 mb-3">
                                                        <div class="col-6">
                                                            <div class="p-3 bg-light-warning rounded text-center">
                                                                <i class="ph ph-star-four f-s-24 text-warning d-block mb-1"></i>
                                                                <strong class="d-block">{{ number_format($lvl->required_points) }}</strong>
                                                                <small class="text-muted">Punti</small>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="p-3 bg-light-info rounded text-center">
                                                                <i class="ph ph-trophy f-s-24 text-info d-block mb-1"></i>
                                                                <strong class="d-block">{{ $lvl->required_badges }}</strong>
                                                                <small class="text-muted">Badge</small>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="d-flex gap-2">
                                                        <button wire:click="edit({{ $lvl->id }})" class="btn btn-light-primary flex-fill">
                                                            <i class="ph ph-pencil me-2"></i>Modifica
                                                        </button>
                                                        <button wire:click="delete({{ $lvl->id }})" 
                                                                class="btn btn-light-danger flex-fill"
                                                                onclick="return confirm('Sei sicuro di voler eliminare questo livello?')">
                                                            <i class="ph ph-trash me-2"></i>Elimina
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Desktop Table View --}}
                            <div class="table-responsive d-none d-lg-block">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th style="width: 80px;">Livello</th>
                                            <th>Nome</th>
                                            <th>Descrizione</th>
                                            <th style="width: 120px;">Punti</th>
                                            <th style="width: 120px;">Badge</th>
                                            <th style="width: 80px;">Ordine</th>
                                            <th style="width: 150px;">Azioni</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($levels as $lvl)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-light-primary f-s-16">{{ $lvl->level }}</span>
                                                </td>
                                                <td><strong>{{ $lvl->name }}</strong></td>
                                                <td>
                                                    <span class="text-muted">{{ Str::limit($lvl->description, 50) }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light-warning">
                                                        <i class="ph ph-star-four me-1"></i>{{ number_format($lvl->required_points) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light-info">
                                                        <i class="ph ph-trophy me-1"></i>{{ $lvl->required_badges }}
                                                    </span>
                                                </td>
                                                <td>{{ $lvl->order }}</td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <button wire:click="edit({{ $lvl->id }})" class="btn btn-light-primary">
                                                            <i class="ph ph-pencil"></i>
                                                        </button>
                                                        <button wire:click="delete({{ $lvl->id }})" 
                                                                class="btn btn-light-danger"
                                                                onclick="return confirm('Sei sicuro di voler eliminare questo livello?')">
                                                            <i class="ph ph-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="ph-duotone ph-chart-line f-s-60 text-muted mb-3"></i>
                                <h5 class="text-muted">Nessun livello configurato</h5>
                                <p class="text-muted">Crea il tuo primo livello per iniziare!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Create/Edit Modal --}}
    @if($showModal)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            {{ $isEditing ? __('gamification.edit_level') : __('gamification.create_level') }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                    </div>
                    <div class="modal-body">
                        <form wire:submit.prevent="save">
                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label">{{ __('gamification.level') }} *</label>
                                    <input type="number" wire:model="levelNumber" class="form-control" min="1" required>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">{{ __('gamification.level_name') }} *</label>
                                    <input type="text" wire:model="name" class="form-control" required>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">{{ __('gamification.level_description') }}</label>
                                    <textarea wire:model="description" class="form-control" rows="2"></textarea>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">{{ __('gamification.required_points') }} *</label>
                                    <input type="number" wire:model="required_points" class="form-control" min="0" required>
                                    <small class="text-muted">Punti totali necessari</small>
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label">{{ __('gamification.required_badges') }} *</label>
                                    <input type="number" wire:model="required_badges" class="form-control" min="0" required>
                                    <small class="text-muted">Badge minimi necessari</small>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">{{ __('gamification.badge_order') }}</label>
                                    <input type="number" wire:model="order" class="form-control" min="0">
                                    <small class="text-muted">Ordine di visualizzazione</small>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light-secondary" wire:click="$set('showModal', false)">
                            {{ __('gamification.cancel') }}
                        </button>
                        <button type="button" class="btn btn-primary" wire:click="save">
                            <i class="ph ph-check me-2"></i>
                            {{ __('gamification.save_level') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Toast Scripts --}}
    @script
    <script>
        Livewire.on('swal:success', (data) => {
            Swal.fire({
                icon: 'success',
                title: data[0].title || 'Successo!',
                text: data[0].text || '',
                confirmButtonText: 'OK',
                confirmButtonClass: 'btn btn-primary',
                timer: 3000
            });
        });
    </script>
    @endscript
</div>
