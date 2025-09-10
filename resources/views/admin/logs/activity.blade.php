@extends('layout.master')

@section('title', 'Log Attività - Admin')

@section('main-content')
<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">Log di Attività</h4>
                
            </div>
        </div>

        <!-- Statistiche -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card hover-effect equal-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="widget-icon bg-light-primary text-primary">
                                    <i class="ph ph-list"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Totale Log</h6>
                                <h4 class="mb-0">{{ $stats['total'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @foreach($stats['by_level'] as $levelStat)
                <div class="col-xl-3 col-md-6">
                    <div class="card hover-effect equal-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0">
                                    <div class="widget-icon bg-light-{{ $levelStat->level == 'error' ? 'danger' : ($levelStat->level == 'warning' ? 'warning' : 'info') }} text-{{ $levelStat->level == 'error' ? 'danger' : ($levelStat->level == 'warning' ? 'warning' : 'info') }}">
                                        <i class="ph ph-{{ $levelStat->level == 'error' ? 'warning' : ($levelStat->level == 'warning' ? 'warning-circle' : 'info') }}"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-1">{{ ucfirst($levelStat->level) }}</h6>
                                    <h4 class="mb-0">{{ $levelStat->count }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Filtri -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">Periodo</label>
                        <select name="hours" class="form-select">
                            <option value="1" {{ $hours == 1 ? 'selected' : '' }}>1 ora</option>
                            <option value="6" {{ $hours == 6 ? 'selected' : '' }}>6 ore</option>
                            <option value="24" {{ $hours == 24 ? 'selected' : '' }}>24 ore</option>
                            <option value="168" {{ $hours == 168 ? 'selected' : '' }}>1 settimana</option>
                            <option value="0" {{ $hours == 0 ? 'selected' : '' }}>Tutti</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Livello</label>
                        <select name="level" class="form-select">
                            <option value="all" {{ $level == 'all' ? 'selected' : '' }}>Tutti</option>
                            <option value="critical" {{ $level == 'critical' ? 'selected' : '' }}>Critical</option>
                            <option value="error" {{ $level == 'error' ? 'selected' : '' }}>Error</option>
                            <option value="warning" {{ $level == 'warning' ? 'selected' : '' }}>Warning</option>
                            <option value="info" {{ $level == 'info' ? 'selected' : '' }}>Info</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Categoria</label>
                        <select name="category" class="form-select">
                            <option value="all" {{ $category == 'all' ? 'selected' : '' }}>Tutte</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ $category == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Utente</label>
                        <select name="user" class="form-select">
                            <option value="all" {{ $user == 'all' ? 'selected' : '' }}>Tutti</option>
                            @foreach($users as $userData)
                                <option value="{{ $userData['id'] }}" {{ $user == $userData['id'] ? 'selected' : '' }}>{{ $userData['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="ph ph-magnifying-glass me-1"></i>Filtra
                        </button>
                        <a href="{{ route('admin.logs.activity') }}" class="btn btn-light me-2">
                            <i class="ph ph-arrow-clockwise me-1"></i>Reset
                        </a>
                        <a href="{{ route('admin.logs.download', ['type' => 'activity', 'hours' => $hours, 'level' => $level]) }}" class="btn btn-success">
                            <i class="ph ph-download me-1"></i>Scarica
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabella Log -->
        <div class="card hover-effect">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0">
                    <i class="ph ph-list me-2"></i>Log di Attività
                </h6>
                <div class="d-flex gap-2">
                    <span class="badge bg-light-primary">{{ $logs->total() }} log totali</span>
                </div>
            </div>
            <div class="card-body">
                @if($logs->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Data/Ora</th>
                                    <th>Livello</th>
                                    <th>Categoria</th>
                                    <th>Azione</th>
                                    <th>Utente</th>
                                    <th>Descrizione</th>
                                    <th>IP</th>
                                    <th>Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logs as $log)
                                    <tr>
                                        <td>
                                            <small>{{ $log->created_at->format('d/m/Y H:i:s') }}</small>
                                        </td>
                                        <td>
                                            @switch($log->level)
                                                @case('critical')
                                                    <span class="badge bg-danger">Critical</span>
                                                    @break
                                                @case('error')
                                                    <span class="badge bg-danger">Error</span>
                                                    @break
                                                @case('warning')
                                                    <span class="badge bg-warning">Warning</span>
                                                    @break
                                                @case('info')
                                                    <span class="badge bg-info">Info</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $log->level }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            <span class="badge bg-light-primary">{{ $log->category }}</span>
                                        </td>
                                        <td>
                                            <code>{{ $log->action }}</code>
                                        </td>
                                        <td>
                                            @if($log->user)
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm me-2">
                                                        <img src="{{ $log->user->profile_photo_url ?? '/assets/images/avatar/default.png' }}"
                                                             class="rounded-circle" width="24" height="24">
                                                    </div>
                                                    <div>
                                                        <div class="fw-semibold">{{ $log->user->name }}</div>
                                                        <small class="text-muted">{{ $log->user->getPrivacySafeIdentifier() }}</small>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">Guest</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span title="{{ $log->description }}">
                                                {{ Str::limit($log->description, 60) }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $log->ip_address }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.logs.show', $log->id) }}" class="btn btn-sm btn-light" title="Visualizza dettagli">
                                                <i class="ph ph-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginazione -->
                    <div class="d-flex justify-content-center mt-4">
                        {{ $logs->appends(request()->query())->links() }}
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="ph ph-check-circle text-success" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">Nessun log trovato</h5>
                        <p class="text-muted">Non ci sono log di attività per i filtri selezionati</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Grafico Categorie -->
        @if($stats['by_category']->count() > 0)
        <div class="card hover-effect mt-4">
            <div class="card-header">
                <h6 class="card-title mb-0">
                    <i class="ph ph-chart-pie me-2"></i>Log per Categoria
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($stats['by_category']->take(6) as $categoryStat)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="d-flex justify-content-between align-items-center p-3 border rounded">
                                <div>
                                    <h6 class="mb-1">{{ $categoryStat->category }}</h6>
                                    <small class="text-muted">{{ $categoryStat->count }} log</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-primary">{{ round(($categoryStat->count / $stats['total']) * 100, 1) }}%</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
