<div>
    <div class="container-fluid">
        {{-- Navigation Tabs --}}
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.gamification.badges') }}" class="btn btn-light-primary">
                        <i class="ph ph-trophy me-2"></i>Badge
                    </a>
                    <a href="{{ route('admin.gamification.user-badges') }}" class="btn btn-primary">
                        <i class="ph ph-users-three me-2"></i>Badge Utenti
                    </a>
                    <a href="{{ route('admin.gamification.levels') }}" class="btn btn-light-primary">
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
                    <div class="card-header">
                        <h4 class="mb-0">
                            <i class="ph-duotone ph-users-three me-2"></i>
                            {{ __('gamification.user_badges_management') }}
                        </h4>
                    </div>

                    <div class="card-body">
                        {{-- Filters - Mobile First --}}
                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-4">
                                <label class="form-label">Filtra per Badge</label>
                                <select wire:model.live="filterBadge" class="form-select">
                                    <option value="">Tutti i badge</option>
                                    @foreach($badges as $badge)
                                        <option value="{{ $badge->id }}">{{ $badge->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label">Cerca Utente</label>
                                <input type="text" wire:model.live.debounce.300ms="filterUser" class="form-control" 
                                       placeholder="Nome, nickname o email...">
                            </div>
                            <div class="col-12 col-md-4 d-flex align-items-end">
                                <button wire:click="$set('filterBadge', ''); $set('filterUser', '')" class="btn btn-light-secondary w-100">
                                    <i class="ph ph-x me-2"></i>Pulisci Filtri
                                </button>
                            </div>
                        </div>

                        @if($userBadges && $userBadges->count() > 0)
                            {{-- Mobile Card View --}}
                            <div class="d-lg-none">
                                <div class="row g-3">
                                    @foreach($userBadges as $userBadge)
                                        <div class="col-12">
                                            <div class="card border">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-start gap-3">
                                                        {{-- User Avatar --}}
                                                        @if($userBadge->user)
                                                        <img src="{{ $userBadge->user->profile_photo_url ?? asset('assets/images/avatar/default-avatar.webp') }}" 
                                                             alt="{{ $userBadge->user->getDisplayName() }}" 
                                                             class="rounded-circle flex-shrink-0" 
                                                             style="width: 50px; height: 50px; object-fit: cover;">
                                                        @endif

                                                        <div class="flex-grow-1">
                                                            {{-- User Info --}}
                                                            @if($userBadge->user)
                                                                <h6 class="mb-1">{{ $userBadge->user->getDisplayName() }}</h6>
                                                                <p class="text-muted small mb-2">{{ $userBadge->user->email }}</p>
                                                            @else
                                                                <p class="text-muted">Utente eliminato</p>
                                                            @endif

                                                            {{-- Badge Info --}}
                                                            @if($userBadge->badge)
                                                                <div class="d-flex align-items-center gap-2 mb-2">
                                                                    <img src="{{ $userBadge->badge->icon_url }}" 
                                                                         alt="{{ $userBadge->badge->name }}" 
                                                                         style="width: 32px; height: 32px;">
                                                                    <div>
                                                                        <strong class="d-block">{{ $userBadge->badge->name }}</strong>
                                                                        <small class="text-muted">
                                                                            <i class="ph ph-star-four me-1"></i>{{ $userBadge->badge->points }} punti
                                                                        </small>
                                                                    </div>
                                                                </div>
                                                            @endif

                                                            {{-- Meta Info --}}
                                                            <div class="d-flex flex-wrap gap-2 mb-2">
                                                                @if($userBadge->show_in_sidebar)
                                                                    <span class="badge bg-light-primary">
                                                                        <i class="ph ph-sidebar me-1"></i>Sidebar
                                                                    </span>
                                                                @endif
                                                                @if($userBadge->show_in_profile)
                                                                    <span class="badge bg-light-success">
                                                                        <i class="ph ph-user me-1"></i>Profilo
                                                                    </span>
                                                                @endif
                                                                @if(!$userBadge->show_in_sidebar && !$userBadge->show_in_profile)
                                                                    <span class="badge bg-light-secondary">
                                                                        <i class="ph ph-eye-slash me-1"></i>Nascosto
                                                                    </span>
                                                                @endif
                                                                @if($userBadge->awardedBy)
                                                                    <span class="badge bg-light-warning">
                                                                        <i class="ph ph-user me-1"></i>{{ Str::limit($userBadge->awardedBy->getDisplayName(), 15) }}
                                                                    </span>
                                                                @else
                                                                    <span class="badge bg-light-info">
                                                                        <i class="ph ph-robot me-1"></i>Automatico
                                                                    </span>
                                                                @endif
                                                            </div>

                                                            <small class="text-muted d-block">
                                                                <i class="ph ph-calendar me-1"></i>
                                                                {{ $userBadge->earned_at->format('d/m/Y H:i') }}
                                                                ({{ $userBadge->earned_at->diffForHumans() }})
                                                            </small>

                                                            {{-- Remove Button --}}
                                                            <button wire:click="removeBadge({{ $userBadge->id }})" 
                                                                    class="btn btn-light-danger w-100 mt-3"
                                                                    onclick="return confirm('Sei sicuro di voler rimuovere questo badge da {{ $userBadge->user?->getDisplayName() }}?')">
                                                                <i class="ph ph-trash me-2"></i>Rimuovi Badge
                                                            </button>
                                                        </div>
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
                                            <th>Dove Visibile</th>
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
                                                    <div class="d-flex flex-wrap gap-1">
                                                        @if($userBadge->show_in_sidebar)
                                                            <span class="badge bg-light-primary">
                                                                <i class="ph ph-sidebar me-1"></i>Sidebar
                                                            </span>
                                                        @endif
                                                        @if($userBadge->show_in_profile)
                                                            <span class="badge bg-light-success">
                                                                <i class="ph ph-user me-1"></i>Profilo
                                                            </span>
                                                        @endif
                                                        @if(!$userBadge->show_in_sidebar && !$userBadge->show_in_profile)
                                                            <span class="badge bg-light-secondary">
                                                                <i class="ph ph-eye-slash me-1"></i>Nascosto
                                                            </span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($userBadge->awardedBy)
                                                        <span class="badge bg-light-warning">
                                                            <i class="ph ph-user me-1"></i>{{ Str::limit($userBadge->awardedBy->getDisplayName(), 15) }}
                                                        </span>
                                                    @else
                                                        <span class="badge bg-light-info">
                                                            <i class="ph ph-robot me-1"></i>Automatico
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <button wire:click="removeBadge({{ $userBadge->id }})" 
                                                            class="btn btn-light-danger"
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
