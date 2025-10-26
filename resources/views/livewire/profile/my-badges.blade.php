<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="mb-1 f-w-700">
                        <i class="ph ph-trophy me-2 text-warning"></i>
                        Trophy Case
                    </h3>
                    <p class="text-muted mb-0">La tua collezione completa di badge - sblocca nuovi achievement!</p>
                </div>
                <a href="{{ route('profile.show', Auth::user()) }}" class="btn btn-outline-primary">
                    <i class="ph ph-arrow-left me-2"></i>
                    Torna al Profilo
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="ph ph-medal f-s-48 text-primary mb-2"></i>
                    <h3 class="mb-0 f-w-700">{{ $badges->count() }}</h3>
                    <small class="text-muted">Badge Sbloccati</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="ph ph-star f-s-48 text-warning mb-2"></i>
                    <h3 class="mb-0 f-w-700">{{ $badges->sum(fn($b) => $b->badge->points ?? 0) }}</h3>
                    <small class="text-muted">Punti Totali</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="ph ph-ranking f-s-48 text-success mb-2"></i>
                    <h3 class="mb-0 f-w-700">{{ Auth::user()->userPoints->level ?? 1 }}</h3>
                    <small class="text-muted">Livello Attuale</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <i class="ph ph-lock-open f-s-48 text-info mb-2"></i>
                    <h3 class="mb-0 f-w-700">{{ \App\Models\Badge::active()->count() - $badges->count() }}</h3>
                    <small class="text-muted">Da Sbloccare</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Trophy Case Grid -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 f-w-600">
                        <i class="ph ph-grid-four me-2"></i>
                        Collezione Completa
                    </h5>
                </div>
                <div class="card-body">
                    @livewire('profile.badge-display-wall-grid', ['user' => Auth::user()])
                </div>
            </div>
        </div>
    </div>

    <!-- Badge Management Section -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-gradient-primary text-white">
                    <h5 class="mb-0">
                        <i class="ph ph-gear me-2"></i>
                        Gestisci Badge in Evidenza
                    </h5>
                    <small>Scegli i 3 badge da mostrare nel tuo profilo (Stack Cards)</small>
                </div>
                <div class="card-body">
                    @if($badges && $badges->count() > 0)
                    <div class="row g-3">
                        <!-- Position 1 -->
                        <div class="col-md-4">
                            <label class="form-label f-w-600">
                                <i class="ph ph-star me-1 text-warning"></i>
                                Badge Posizione 1
                            </label>
                            <select class="form-select" wire:model.live="profilePosition1" wire:change="setProfilePosition($event.target.value, 1)">
                                <option value="">Seleziona badge...</option>
                                @foreach($badges as $userBadge)
                                    @if($userBadge->badge)
                                    <option value="{{ $userBadge->id }}">{{ $userBadge->badge->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <!-- Position 2 -->
                        <div class="col-md-4">
                            <label class="form-label f-w-600">
                                <i class="ph ph-star me-1 text-warning"></i>
                                Badge Posizione 2
                            </label>
                            <select class="form-select" wire:model.live="profilePosition2" wire:change="setProfilePosition($event.target.value, 2)">
                                <option value="">Seleziona badge...</option>
                                @foreach($badges as $userBadge)
                                    @if($userBadge->badge)
                                    <option value="{{ $userBadge->id }}">{{ $userBadge->badge->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <!-- Position 3 -->
                        <div class="col-md-4">
                            <label class="form-label f-w-600">
                                <i class="ph ph-star me-1 text-warning"></i>
                                Badge Posizione 3
                            </label>
                            <select class="form-select" wire:model.live="profilePosition3" wire:change="setProfilePosition($event.target.value, 3)">
                                <option value="">Seleziona badge...</option>
                                @foreach($badges as $userBadge)
                                    @if($userBadge->badge)
                                    <option value="{{ $userBadge->id }}">{{ $userBadge->badge->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="alert alert-info mt-3 mb-0">
                        <i class="ph ph-info me-2"></i>
                        <strong>Suggerimento:</strong> Questi 3 badge appariranno come Stack Cards nel tuo profilo. Scegli i tuoi preferiti!
                    </div>
                    @else
                    <div class="text-center py-4 text-muted">
                        <i class="ph ph-medal f-s-48 mb-3"></i>
                        <p>Guadagna badge interagendo con la piattaforma!</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
