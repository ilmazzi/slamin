<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h3 class="mb-1 f-w-700">
                        <i class="ph ph-trophy me-2 text-warning"></i>
                        Trophy Case
                    </h3>
                    <p class="text-muted mb-0 d-none d-md-block">La tua collezione completa di badge - sblocca nuovi achievement!</p>
                </div>
                <a href="{{ route('profile.show') }}" class="btn btn-primary btn-sm">
                    <i class="ph ph-arrow-left me-2"></i>
                    <span class="d-none d-sm-inline">Torna al Profilo</span>
                    <span class="d-inline d-sm-none">Profilo</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Overview -->
    <div class="row mb-4 g-3">
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-3 px-2">
                    <i class="ph ph-medal text-primary mb-2 trophy-stat-icon"></i>
                    <h3 class="mb-0 f-w-700 trophy-stat-value">{{ $badges->count() }}</h3>
                    <small class="text-muted d-block trophy-stat-label">Badge Sbloccati</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-3 px-2">
                    <i class="ph ph-star text-warning mb-2 trophy-stat-icon"></i>
                    <h3 class="mb-0 f-w-700 trophy-stat-value">{{ $badges->sum(fn($b) => $b->badge->points ?? 0) }}</h3>
                    <small class="text-muted d-block trophy-stat-label">Punti Totali</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-3 px-2">
                    <i class="ph ph-chart-line text-success mb-2 trophy-stat-icon"></i>
                    <h3 class="mb-0 f-w-700 trophy-stat-value">{{ Auth::user()->userPoints->level ?? 1 }}</h3>
                    <small class="text-muted d-block trophy-stat-label">Livello Attuale</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-3 px-2">
                    <i class="ph ph-lock-open text-info mb-2 trophy-stat-icon"></i>
                    <h3 class="mb-0 f-w-700 trophy-stat-value">{{ \App\Models\Badge::active()->count() - $badges->count() }}</h3>
                    <small class="text-muted d-block trophy-stat-label">Da Sbloccare</small>
                </div>
            </div>
        </div>
    </div>

    <style>
        .trophy-stat-icon {
            font-size: 2rem;
        }
        
        .trophy-stat-value {
            font-size: 1.5rem;
        }
        
        .trophy-stat-label {
            font-size: 0.75rem;
        }
        
        @media (min-width: 768px) {
            .trophy-stat-icon {
                font-size: 3rem;
            }
            
            .trophy-stat-value {
                font-size: 2rem;
            }
            
            .trophy-stat-label {
                font-size: 0.875rem;
            }
        }
    </style>

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

</div>
