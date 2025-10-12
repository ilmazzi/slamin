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
                        <a class="nav-link" href="{{ route('admin.gamification.user-badges') }}">
                            <i class="ph ph-users-three me-2"></i>Badge Utenti
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.gamification.levels') }}">
                            <i class="ph ph-chart-line me-2"></i>Livelli
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('admin.gamification.leaderboards') }}">
                            <i class="ph ph-ranking me-2"></i>Classifiche
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        {{-- Stats Cards --}}
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="card bg-light-primary">
                    <div class="card-body text-center">
                        <i class="ph-duotone ph-trophy f-s-40 text-primary mb-2"></i>
                        <h3 class="mb-1">{{ number_format($stats['total_badges_awarded']) }}</h3>
                        <p class="text-muted small mb-0">Badge Assegnati</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card bg-light-warning">
                    <div class="card-body text-center">
                        <i class="ph-duotone ph-star-four f-s-40 text-warning mb-2"></i>
                        <h3 class="mb-1">{{ number_format($stats['total_points_awarded']) }}</h3>
                        <p class="text-muted small mb-0">Punti Totali</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card bg-light-info">
                    <div class="card-body text-center">
                        <i class="ph-duotone ph-users-three f-s-40 text-info mb-2"></i>
                        <h3 class="mb-1">{{ number_format($stats['total_active_users']) }}</h3>
                        <p class="text-muted small mb-0">Utenti Attivi</p>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card bg-light-success">
                    <div class="card-body text-center">
                        <i class="ph-duotone ph-chart-line f-s-40 text-success mb-2"></i>
                        <h3 class="mb-1">{{ number_format($stats['avg_points_per_user'] ?? 0) }}</h3>
                        <p class="text-muted small mb-0">Media Punti</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            {{-- Top by Points --}}
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph-duotone ph-star-four me-2 text-warning"></i>
                            Top per Punti
                        </h5>
                    </div>
                    <div class="card-body">
                        @forelse($topByPoints as $index => $userPoints)
                            <div class="d-flex align-items-center {{ !$loop->last ? 'mb-3 pb-3 border-bottom' : '' }}">
                                <div class="flex-shrink-0 me-3">
                                    <div class="badge {{ $index < 3 ? 'bg-gradient-warning' : 'bg-light-secondary' }} rounded-circle" 
                                         style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                        {{ $index + 1 }}
                                    </div>
                                </div>
                                @if($userPoints->user)
                                <img src="{{ $userPoints->user->profile_photo_url ?? asset('assets/images/avatar/default-avatar.webp') }}" 
                                     alt="{{ $userPoints->user->getDisplayName() }}" 
                                     class="rounded-circle flex-shrink-0 me-3" 
                                     style="width: 40px; height: 40px; object-fit: cover;">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ $userPoints->user->getDisplayName() }}</h6>
                                    <small class="text-muted">{{ number_format($userPoints->total_points) }} punti</small>
                                </div>
                                @endif
                            </div>
                        @empty
                            <p class="text-muted text-center mb-0">Nessun dato disponibile</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Top by Level --}}
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph-duotone ph-chart-line me-2 text-info"></i>
                            Top per Livello
                        </h5>
                    </div>
                    <div class="card-body">
                        @forelse($topByLevel as $index => $userPoints)
                            <div class="d-flex align-items-center {{ !$loop->last ? 'mb-3 pb-3 border-bottom' : '' }}">
                                <div class="flex-shrink-0 me-3">
                                    <div class="badge {{ $index < 3 ? 'bg-gradient-info' : 'bg-light-secondary' }} rounded-circle" 
                                         style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                        {{ $index + 1 }}
                                    </div>
                                </div>
                                @if($userPoints->user)
                                <img src="{{ $userPoints->user->profile_photo_url ?? asset('assets/images/avatar/default-avatar.webp') }}" 
                                     alt="{{ $userPoints->user->getDisplayName() }}" 
                                     class="rounded-circle flex-shrink-0 me-3" 
                                     style="width: 40px; height: 40px; object-fit: cover;">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ $userPoints->user->getDisplayName() }}</h6>
                                    <small class="text-muted">
                                        Livello {{ $userPoints->level }}
                                        @if($userPoints->current_level)
                                            - {{ $userPoints->current_level->name }}
                                        @endif
                                    </small>
                                </div>
                                @endif
                            </div>
                        @empty
                            <p class="text-muted text-center mb-0">Nessun dato disponibile</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Top by Badges --}}
            <div class="col-12 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph-duotone ph-trophy me-2 text-success"></i>
                            Top per Badge
                        </h5>
                    </div>
                    <div class="card-body">
                        @forelse($topByBadges as $index => $userPoints)
                            <div class="d-flex align-items-center {{ !$loop->last ? 'mb-3 pb-3 border-bottom' : '' }}">
                                <div class="flex-shrink-0 me-3">
                                    <div class="badge {{ $index < 3 ? 'bg-gradient-success' : 'bg-light-secondary' }} rounded-circle" 
                                         style="width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;">
                                        {{ $index + 1 }}
                                    </div>
                                </div>
                                @if($userPoints->user)
                                <img src="{{ $userPoints->user->profile_photo_url ?? asset('assets/images/avatar/default-avatar.webp') }}" 
                                     alt="{{ $userPoints->user->getDisplayName() }}" 
                                     class="rounded-circle flex-shrink-0 me-3" 
                                     style="width: 40px; height: 40px; object-fit: cover;">
                                <div class="flex-grow-1">
                                    <h6 class="mb-0">{{ $userPoints->user->getDisplayName() }}</h6>
                                    <small class="text-muted">{{ $userPoints->badges_count }} badge</small>
                                </div>
                                @endif
                            </div>
                        @empty
                            <p class="text-muted text-center mb-0">Nessun dato disponibile</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Recent Badges Awarded --}}
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph-duotone ph-clock-clockwise me-2"></i>
                            Badge Recenti
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($recentBadges && $recentBadges->count() > 0)
                            {{-- Mobile View --}}
                            <div class="d-lg-none">
                                <div class="row g-3">
                                    @foreach($recentBadges as $userBadge)
                                        <div class="col-12">
                                            <div class="card border">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center gap-3">
                                                        @if($userBadge->user)
                                                        <img src="{{ $userBadge->user->profile_photo_url ?? asset('assets/images/avatar/default-avatar.webp') }}" 
                                                             alt="{{ $userBadge->user->getDisplayName() }}" 
                                                             class="rounded-circle flex-shrink-0" 
                                                             style="width: 40px; height: 40px; object-fit: cover;">
                                                        @endif
                                                        
                                                        @if($userBadge->badge)
                                                        <img src="{{ $userBadge->badge->icon_url }}" 
                                                             alt="{{ $userBadge->badge->name }}" 
                                                             style="width: 32px; height: 32px;"
                                                             class="flex-shrink-0">
                                                        @endif

                                                        <div class="flex-grow-1">
                                                            @if($userBadge->user)
                                                                <strong class="d-block">{{ $userBadge->user->getDisplayName() }}</strong>
                                                            @endif
                                                            @if($userBadge->badge)
                                                                <small class="text-muted d-block">{{ $userBadge->badge->name }}</small>
                                                            @endif
                                                            <small class="text-muted">{{ $userBadge->earned_at->diffForHumans() }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Desktop View --}}
                            <div class="table-responsive d-none d-lg-block">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Utente</th>
                                            <th>Badge</th>
                                            <th>Data</th>
                                            <th>Tipo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentBadges as $userBadge)
                                            <tr>
                                                <td>
                                                    @if($userBadge->user)
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $userBadge->user->profile_photo_url ?? asset('assets/images/avatar/default-avatar.webp') }}" 
                                                             alt="{{ $userBadge->user->getDisplayName() }}" 
                                                             class="rounded-circle me-2" 
                                                             style="width: 32px; height: 32px; object-fit: cover;">
                                                        <strong>{{ $userBadge->user->getDisplayName() }}</strong>
                                                    </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($userBadge->badge)
                                                    <div class="d-flex align-items-center">
                                                        <img src="{{ $userBadge->badge->icon_url }}" 
                                                             alt="{{ $userBadge->badge->name }}" 
                                                             style="width: 24px; height: 24px;"
                                                             class="me-2">
                                                        {{ $userBadge->badge->name }}
                                                    </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $userBadge->earned_at->format('d/m/Y H:i') }}
                                                    <br><small class="text-muted">{{ $userBadge->earned_at->diffForHumans() }}</small>
                                                </td>
                                                <td>
                                                    @if($userBadge->awarded_by)
                                                        <span class="badge bg-light-warning">
                                                            <i class="ph ph-user me-1"></i>Manuale
                                                        </span>
                                                    @else
                                                        <span class="badge bg-light-info">
                                                            <i class="ph ph-robot me-1"></i>Automatico
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="ph-duotone ph-clock-clockwise f-s-60 text-muted mb-3"></i>
                                <h5 class="text-muted">Nessun badge recente</h5>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
