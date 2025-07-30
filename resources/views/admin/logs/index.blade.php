@extends('layout.master')

@section('title', 'Dashboard Logs - Admin')

@section('main-content')
<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">Dashboard Logs</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a href="{{ route('dashboard') }}" class="f-s-14 f-w-500">
                            <span>
                                <i class="ph-duotone ph-house f-s-16"></i> Dashboard
                            </span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="#" class="f-s-14 f-w-500">Logs</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Filtri -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Periodo</label>
                        <select name="hours" class="form-select">
                            <option value="1" {{ $hours == 1 ? 'selected' : '' }}>Ultima ora</option>
                            <option value="6" {{ $hours == 6 ? 'selected' : '' }}>Ultime 6 ore</option>
                            <option value="24" {{ $hours == 24 ? 'selected' : '' }}>Ultime 24 ore</option>
                            <option value="168" {{ $hours == 168 ? 'selected' : '' }}>Ultima settimana</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Livello</label>
                        <select name="level" class="form-select">
                            <option value="all" {{ $level == 'all' ? 'selected' : '' }}>Tutti</option>
                            <option value="critical" {{ $level == 'critical' ? 'selected' : '' }}>Critical</option>
                            <option value="error" {{ $level == 'error' ? 'selected' : '' }}>Error</option>
                            <option value="warning" {{ $level == 'warning' ? 'selected' : '' }}>Warning</option>
                            <option value="info" {{ $level == 'info' ? 'selected' : '' }}>Info</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Canale</label>
                        <select name="channel" class="form-select">
                            <option value="all" {{ $channel == 'all' ? 'selected' : '' }}>Tutti</option>
                            <option value="system" {{ $channel == 'system' ? 'selected' : '' }}>Sistema</option>
                            <option value="user" {{ $channel == 'user' ? 'selected' : '' }}>Utente</option>
                            <option value="admin" {{ $channel == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="event" {{ $channel == 'event' ? 'selected' : '' }}>Eventi</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="ph ph-magnifying-glass me-1"></i>Filtra
                        </button>
                        <a href="{{ route('admin.logs.index') }}" class="btn btn-light">
                            <i class="ph ph-arrow-clockwise me-1"></i>Reset
                        </a>
                    </div>
                </form>
            </div>
        </div>



        <!-- Statistiche -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card hover-effect equal-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="widget-icon bg-light-danger text-danger">
                                    <i class="ph ph-warning"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Errori</h6>
                                <h4 class="mb-0">{{ $stats['total_errors'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card hover-effect equal-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="widget-icon bg-light-danger text-danger">
                                    <i class="ph ph-exclamation-triangle"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Critical</h6>
                                <h4 class="mb-0">{{ $stats['total_critical'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card hover-effect equal-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="widget-icon bg-light-warning text-warning">
                                    <i class="ph ph-warning-circle"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Warning</h6>
                                <h4 class="mb-0">{{ $stats['total_warnings'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card hover-effect equal-card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                                <div class="widget-icon bg-light-info text-info">
                                    <i class="ph ph-info"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">Info</h6>
                                <h4 class="mb-0">{{ $stats['total_info'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Grafici -->
        <div class="row mb-4">
            <div class="col-lg-6">
                <div class="card hover-effect equal-card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="ph ph-chart-pie me-2"></i>Log per Categoria
                        </h6>
                    </div>
                    <div class="card-body">
                        @if($stats['top_categories']->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Categoria</th>
                                            <th>Conteggio</th>
                                            <th>Percentuale</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $total = $stats['top_categories']->sum('count');
                                        @endphp
                                        @foreach($stats['top_categories'] as $category)
                                            <tr>
                                                <td>
                                                    <span class="badge bg-light-primary">{{ $category->category }}</span>
                                                </td>
                                                <td>{{ $category->count }}</td>
                                                <td>{{ round(($category->count / $total) * 100, 1) }}%</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted mb-0">Nessun dato disponibile</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card hover-effect equal-card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="ph ph-activity me-2"></i>Azioni Più Frequenti
                        </h6>
                    </div>
                    <div class="card-body">
                        @if($stats['top_actions']->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Azione</th>
                                            <th>Conteggio</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($stats['top_actions'] as $action)
                                            <tr>
                                                <td>
                                                    <code>{{ $action->action }}</code>
                                                </td>
                                                <td>{{ $action->count }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted mb-0">Nessun dato disponibile</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Log Recenti -->
        <div class="card hover-effect">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0">
                    <i class="ph ph-list me-2"></i>Log Recenti
                </h6>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.logs.activity') }}" class="btn btn-primary btn-sm">
                        <i class="ph ph-database me-1"></i>Log Attività
                    </a>
                    <a href="{{ route('admin.logs.errors') }}" class="btn btn-warning btn-sm">
                        <i class="ph ph-file-text me-1"></i>Log Errori
                    </a>
                    <a href="{{ route('admin.logs.download', ['type' => 'activity', 'hours' => $hours, 'level' => $level]) }}" class="btn btn-success btn-sm">
                        <i class="ph ph-download me-1"></i>Scarica
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($recentLogs->count() > 0)
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
                                    <th>Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentLogs as $log)
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
                                                <span class="text-primary">{{ $log->user->name }}</span>
                                            @else
                                                <span class="text-muted">Guest</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span title="{{ $log->description }}">
                                                {{ Str::limit($log->description, 50) }}
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.logs.show', $log->id) }}" class="btn btn-sm btn-light">
                                                <i class="ph ph-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="ph ph-check-circle text-success" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">Nessun log trovato</h5>
                        <p class="text-muted">Non ci sono log per i filtri selezionati</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
