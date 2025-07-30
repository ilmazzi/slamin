@extends('layout.master')

@section('title', 'Log di Errore - Admin')

@section('main-content')
<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">Log di Errore</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a href="{{ route('dashboard') }}" class="f-s-14 f-w-500">
                            <span>
                                <i class="ph-duotone ph-house f-s-16"></i> Dashboard
                            </span>
                        </a>
                    </li>
                    <li class="">
                        <a href="{{ route('admin.logs.index') }}" class="f-s-14 f-w-500">Logs</a>
                    </li>
                    <li class="active">
                        <a href="#" class="f-s-14 f-w-500">Errori</a>
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
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">File</label>
                        <select name="file" class="form-select">
                            <option value="all" {{ $file == 'all' ? 'selected' : '' }}>Tutti i file</option>
                            <option value="laravel" {{ $file == 'laravel' ? 'selected' : '' }}>Laravel.log</option>
                            <option value="errors" {{ $file == 'errors' ? 'selected' : '' }}>Errors.log</option>
                            <option value="security" {{ $file == 'security' ? 'selected' : '' }}>Security.log</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="ph ph-magnifying-glass me-1"></i>Filtra
                        </button>
                        <a href="{{ route('admin.logs.errors') }}" class="btn btn-light">
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
                                <h6 class="mb-1">Errori Totali</h6>
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
                                    <i class="ph ph-file-text"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1">File Analizzati</h6>
                                <h4 class="mb-0">{{ $stats['files_analyzed'] }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Log di Errore -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="ph ph-warning me-2"></i>Log di Errore
                    </h5>
                    <div>
                        <a href="{{ route('admin.logs.download', ['type' => 'errors', 'hours' => $hours, 'level' => $level]) }}" class="btn btn-success btn-sm">
                            <i class="ph ph-download me-1"></i>Scarica
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if(count($errorLogs) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Data/Ora</th>
                                    <th>Livello</th>
                                    <th>File</th>
                                    <th>Messaggio</th>
                                    <th>Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($errorLogs as $log)
                                    <tr>
                                        <td>
                                            <small class="text-muted">{{ $log['datetime'] }}</small>
                                        </td>
                                        <td>
                                            @if($log['level'] == 'critical')
                                                <span class="badge bg-danger">Critical</span>
                                            @elseif($log['level'] == 'error')
                                                <span class="badge bg-danger">Error</span>
                                            @elseif($log['level'] == 'warning')
                                                <span class="badge bg-warning">Warning</span>
                                            @else
                                                <span class="badge bg-secondary">{{ ucfirst($log['level']) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $log['file'] }}</small>
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 300px;" title="{{ $log['message'] }}">
                                                {{ $log['message'] }}
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary" onclick="showLogDetails('{{ addslashes($log['message']) }}', '{{ addslashes($log['full_line']) }}')">
                                                <i class="ph ph-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="ph ph-check-circle text-success" style="font-size: 3rem;"></i>
                        <h5 class="mt-3">Nessun errore trovato</h5>
                        <p class="text-muted">Non ci sono errori per i filtri selezionati</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal per dettagli log -->
<div class="modal fade" id="logDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dettagli Log</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Messaggio</label>
                    <div class="form-control-plaintext" id="logMessage"></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Riga Completa</label>
                    <pre class="form-control-plaintext bg-light p-3" id="logFullLine"></pre>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
            </div>
        </div>
    </div>
</div>

<script>
function showLogDetails(message, fullLine) {
    document.getElementById('logMessage').textContent = message;
    document.getElementById('logFullLine').textContent = fullLine;
    new bootstrap.Modal(document.getElementById('logDetailsModal')).show();
}
</script>
@endsection
