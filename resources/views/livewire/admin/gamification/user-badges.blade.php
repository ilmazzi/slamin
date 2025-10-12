<div>
    <div class="container-fluid">
        {{-- Navigation Tabs --}}
        <div class="row mb-3">
            <div class="col-12">
                <ul class="nav nav-pills bg-light p-2 rounded">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.gamification.badges') }}">
                            <i class="ph ph-trophy me-2"></i>Badge
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('admin.gamification.user-badges') }}">
                            <i class="ph ph-users-three me-2"></i>Badge Utenti
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.gamification.levels') }}">
                            <i class="ph ph-chart-line me-2"></i>Livelli
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.gamification.leaderboards') }}">
                            <i class="ph ph-ranking me-2"></i>Classifiche
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">
                            <i class="ph-duotone ph-users-three me-2"></i>
                            {{ __('gamification.user_badges_management') }}
                        </h4>
                    </div>

                    <div class="card-body">
                        {{-- Filters --}}
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Filtra per Badge</label>
                                <select wire:model.live="filterBadge" class="form-select">
                                    <option value="">Tutti i badge</option>
                                    @foreach($badges as $badge)
                                        <option value="{{ $badge->id }}">{{ $badge->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Cerca Utente</label>
                                <input type="text" wire:model.live.debounce.300ms="filterUser" class="form-control" 
                                       placeholder="Nome, nickname o email...">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button wire:click="$set('filterBadge', ''); $set('filterUser', '')" class="btn btn-light-secondary">
                                    <i class="ph ph-x me-2"></i>Pulisci Filtri
                                </button>
                            </div>
                        </div>

                        @if($userBadges && $userBadges->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Avatar</th>
                                            <th wire:click="sortByColumn('user')" style="cursor: pointer;">
                                                Utente
                                                @if($sortBy === 'user')
                                                    <i class="ph ph-caret-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                                @endif
                                            </th>
                                            <th wire:click="sortByColumn('badge')" style="cursor: pointer;">
                                                Badge
                                                @if($sortBy === 'badge')
                                                    <i class="ph ph-caret-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                                @endif
                                            </th>
                                            <th wire:click="sortByColumn('earned_at')" style="cursor: pointer;">
                                                Guadagnato
                                                @if($sortBy === 'earned_at')
                                                    <i class="ph ph-caret-{{ $sortDirection === 'asc' ? 'up' : 'down' }}"></i>
                                                @endif
                                            </th>
                                            <th>Visibile</th>
                                            <th>Assegnato Da</th>
                                            <th style="width: 100px;">Azioni</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($userBadges as $userBadge)
                                            <tr>
                                                <td>
                                                    @if($userBadge->user)
                                                        <img src="{{ $userBadge->user->profile_photo_url ?? asset('assets/images/avatar/default-avatar.webp') }}" 
                                                             alt="{{ $userBadge->user->getDisplayName() }}" 
                                                             class="rounded-circle" 
                                                             style="width: 40px; height: 40px; object-fit: cover;">
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($userBadge->user)
                                                        <strong>{{ $userBadge->user->getDisplayName() }}</strong>
                                                        <br><small class="text-muted">{{ $userBadge->user->email }}</small>
                                                    @else
                                                        <span class="text-muted">Utente eliminato</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($userBadge->badge)
                                                        <div class="d-flex align-items-center">
                                                            <img src="{{ $userBadge->badge->icon_url }}" 
                                                                 alt="{{ $userBadge->badge->name }}" 
                                                                 style="width: 32px; height: 32px;" 
                                                                 class="me-2">
                                                            <div>
                                                                <strong>{{ $userBadge->badge->name }}</strong>
                                                                <br><small class="text-muted">
                                                                    <i class="ph ph-star-four me-1"></i>{{ $userBadge->badge->points }} punti
                                                                </small>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $userBadge->earned_at->format('d/m/Y H:i') }}
                                                    <br><small class="text-muted">{{ $userBadge->earned_at->diffForHumans() }}</small>
                                                </td>
                                                <td>
                                                    @if($userBadge->is_featured)
                                                        <span class="badge bg-success">
                                                            <i class="ph ph-eye me-1"></i>Visibile
                                                        </span>
                                                    @else
                                                        <span class="badge bg-light-secondary">
                                                            <i class="ph ph-eye-slash me-1"></i>Nascosto
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($userBadge->awardedBy)
                                                        <span class="badge bg-light-warning">
                                                            <i class="ph ph-user me-1"></i>{{ $userBadge->awardedBy->getDisplayName() }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-light-info">
                                                            <i class="ph ph-robot me-1"></i>Automatico
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button wire:click="removeBadge({{ $userBadge->id }})" 
                                                            class="btn btn-sm btn-light-danger"
                                                            onclick="return confirm('Sei sicuro di voler rimuovere questo badge da {{ $userBadge->user?->getDisplayName() }}?')">
                                                        <i class="ph ph-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            {{-- Pagination --}}
                            <div class="mt-3">
                                {{ $userBadges->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="ph-duotone ph-trophy f-s-60 text-muted mb-3"></i>
                                <h5 class="text-muted">Nessun badge assegnato</h5>
                                <p class="text-muted">
                                    @if($filterBadge || $filterUser)
                                        Nessun risultato con i filtri selezionati
                                    @else
                                        Non sono ancora stati assegnati badge agli utenti
                                    @endif
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

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

        Livewire.on('swal:error', (data) => {
            Swal.fire({
                icon: 'error',
                title: data[0].title || 'Errore',
                text: data[0].text || '',
                confirmButtonText: 'OK',
                confirmButtonClass: 'btn btn-danger'
            });
        });
    </script>
    @endscript
</div>
