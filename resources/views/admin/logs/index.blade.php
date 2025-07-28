@extends('layout.app')

@section('title', 'Log di Attività - Admin')

@section('content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Log di Attività</li>
                    </ol>
                </div>
                <h4 class="page-title">
                    <i class="ph-duotone ph-list-checks me-2"></i>
                    Log di Attività
                </h4>
            </div>
        </div>
    </div>

    <!-- Statistiche -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card card-light-primary">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-1">{{ number_format($stats['total_logs']) }}</h4>
                            <p class="text-muted mb-0">Log Totali</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ph-duotone ph-list-checks f-s-24 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-light-success">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-1">{{ number_format($stats['today_logs']) }}</h4>
                            <p class="text-muted mb-0">Oggi</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ph-duotone ph-calendar-check f-s-24 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-light-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-1">{{ number_format($stats['error_logs']) }}</h4>
                            <p class="text-muted mb-0">Errori</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ph-duotone ph-warning-circle f-s-24 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card card-light-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h4 class="mb-1">{{ number_format($stats['this_month_logs']) }}</h4>
                            <p class="text-muted mb-0">Questo Mese</p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="ph-duotone ph-calendar f-s-24 text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtri -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="ph-duotone ph-funnel f-s-18 me-2"></i>
                        Filtri
                    </h4>
                </div>
                <div class="card-body">
                    <form id="filterForm" method="GET">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">Categoria</label>
                                <select name="category" class="form-select">
                                    <option value="">Tutte le categorie</option>
                                    @foreach($categories as $key => $name)
                                        <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Livello</label>
                                <select name="level" class="form-select">
                                    <option value="">Tutti i livelli</option>
                                    @foreach($levels as $key => $name)
                                        <option value="{{ $key }}" {{ request('level') == $key ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Utente</label>
                                <select name="user_id" class="form-select">
                                    <option value="">Tutti gli utenti</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Azione</label>
                                <input type="text" name="action" class="form-control" placeholder="Cerca azione..." value="{{ request('action') }}">
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <label class="form-label">Data da</label>
                                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Data a</label>
                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="ph-duotone ph-magnifying-glass me-1"></i>
                                    Filtra
                                </button>
                                <a href="{{ route('admin.logs.index') }}" class="btn btn-light me-2">
                                    <i class="ph-duotone ph-arrow-clockwise me-1"></i>
                                    Reset
                                </a>
                                <button type="button" class="btn btn-success" onclick="exportLogs()">
                                    <i class="ph-duotone ph-download me-1"></i>
                                    Esporta
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabella Log -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">
                        <i class="ph-duotone ph-list f-s-18 me-2"></i>
                        Log di Attività
                    </h4>
                    <div>
                        <button type="button" class="btn btn-warning btn-sm" onclick="clearOldLogs()">
                            <i class="ph-duotone ph-trash me-1"></i>
                            Pulisci Vecchi Log
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Data/Ora</th>
                                    <th>Utente</th>
                                    <th>Azione</th>
                                    <th>Categoria</th>
                                    <th>Livello</th>
                                    <th>Descrizione</th>
                                    <th>IP</th>
                                    <th>Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($logs as $log)
                                    <tr>
                                        <td>{{ $log->id }}</td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                                            </small>
                                        </td>
                                        <td>
                                            @if($log->user)
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ $log->user->avatar ?? '/assets/images/avatar/default.png' }}"
                                                         class="rounded-circle me-2" width="32" height="32">
                                                    <div>
                                                        <div class="fw-bold">{{ $log->user->name }}</div>
                                                        <small class="text-muted">{{ $log->user->email }}</small>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-muted">Sistema</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">{{ $log->action }}</span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $log->category_badge_class }}">{{ $log->category }}</span>
                                        </td>
                                        <td>
                                            <span class="badge {{ $log->level_badge_class }}">{{ $log->level }}</span>
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 200px;" title="{{ $log->description }}">
                                                {{ $log->description }}
                                            </div>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $log->ip_address }}</small>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                    onclick="showLogDetails({{ $log->id }})">
                                                <i class="ph-duotone ph-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="ph-duotone ph-list-checks f-s-48 mb-3"></i>
                                                <p>Nessun log trovato</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginazione -->
                    @if($logs->hasPages())
                        <div class="d-flex justify-content-center mt-3">
                            {{ $logs->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiche Aggiuntive -->
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="ph-duotone ph-chart-bar f-s-18 me-2"></i>
                        Top Azioni
                    </h4>
                </div>
                <div class="card-body">
                    @forelse($topActions as $action)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-truncate">{{ $action->action }}</span>
                            <span class="badge bg-primary">{{ $action->count }}</span>
                        </div>
                    @empty
                        <p class="text-muted">Nessun dato disponibile</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="ph-duotone ph-users f-s-18 me-2"></i>
                        Top Utenti
                    </h4>
                </div>
                <div class="card-body">
                    @forelse($topUsers as $userLog)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <img src="{{ $userLog->user->avatar ?? '/assets/images/avatar/default.png' }}"
                                     class="rounded-circle me-2" width="24" height="24">
                                <span class="text-truncate">{{ $userLog->user->name }}</span>
                            </div>
                            <span class="badge bg-success">{{ $userLog->count }}</span>
                        </div>
                    @empty
                        <p class="text-muted">Nessun dato disponibile</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Dettagli Log -->
<div class="modal fade" id="logDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dettagli Log</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="logDetailsContent">
                <!-- Contenuto caricato via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Pulisci Log -->
<div class="modal fade" id="clearLogsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pulisci Vecchi Log</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Seleziona quanti giorni di log mantenere:</p>
                <select id="clearDays" class="form-select">
                    <option value="7">7 giorni</option>
                    <option value="15">15 giorni</option>
                    <option value="30" selected>30 giorni</option>
                    <option value="60">60 giorni</option>
                    <option value="90">90 giorni</option>
                </select>
                <div class="alert alert-warning mt-3">
                    <i class="ph-duotone ph-warning-circle me-2"></i>
                    <strong>Attenzione:</strong> Questa azione eliminerà permanentemente tutti i log più vecchi del periodo selezionato.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-warning" onclick="confirmClearLogs()">Pulisci Log</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showLogDetails(logId) {
    fetch(`/admin/logs/${logId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const log = data.log;
                const content = `
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informazioni Base</h6>
                            <table class="table table-sm">
                                <tr><td><strong>ID:</strong></td><td>${log.id}</td></tr>
                                <tr><td><strong>Data:</strong></td><td>${new Date(log.created_at).toLocaleString('it-IT')}</td></tr>
                                <tr><td><strong>Utente:</strong></td><td>${log.user ? log.user.name : 'Sistema'}</td></tr>
                                <tr><td><strong>Email:</strong></td><td>${log.user ? log.user.email : '-'}</td></tr>
                                <tr><td><strong>Azione:</strong></td><td>${log.action}</td></tr>
                                <tr><td><strong>Categoria:</strong></td><td>${log.category}</td></tr>
                                <tr><td><strong>Livello:</strong></td><td>${log.level}</td></tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Dettagli Tecnici</h6>
                            <table class="table table-sm">
                                <tr><td><strong>IP:</strong></td><td>${log.ip_address || '-'}</td></tr>
                                <tr><td><strong>URL:</strong></td><td>${log.url || '-'}</td></tr>
                                <tr><td><strong>Metodo:</strong></td><td>${log.method || '-'}</td></tr>
                                <tr><td><strong>Status Code:</strong></td><td>${log.status_code || '-'}</td></tr>
                                <tr><td><strong>Tempo Risposta:</strong></td><td>${log.response_time ? log.response_time + 'ms' : '-'}</td></tr>
                                <tr><td><strong>User Agent:</strong></td><td>${log.user_agent || '-'}</td></tr>
                            </table>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>Descrizione</h6>
                            <p>${log.description}</p>
                        </div>
                    </div>
                    ${log.details ? `
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>Dettagli</h6>
                            <pre class="bg-light p-3 rounded">${JSON.stringify(log.details, null, 2)}</pre>
                        </div>
                    </div>
                    ` : ''}
                `;
                document.getElementById('logDetailsContent').innerHTML = content;
                new bootstrap.Modal(document.getElementById('logDetailsModal')).show();
            }
        })
        .catch(error => {
            console.error('Errore nel caricamento dettagli log:', error);
            alert('Errore nel caricamento dei dettagli');
        });
}

function exportLogs() {
    const form = document.getElementById('filterForm');
    const formData = new FormData(form);

    fetch('/admin/logs/export', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Crea e scarica il file CSV
            const csvContent = convertToCSV(data.data);
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);
            link.setAttribute('href', url);
            link.setAttribute('download', data.filename);
            link.style.visibility = 'hidden';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    })
    .catch(error => {
        console.error('Errore nell\'export:', error);
        alert('Errore nell\'export dei log');
    });
}

function convertToCSV(data) {
    if (data.length === 0) return '';

    const headers = Object.keys(data[0]);
    const csvRows = [headers.join(',')];

    for (const row of data) {
        const values = headers.map(header => {
            const value = row[header];
            return `"${String(value).replace(/"/g, '""')}"`;
        });
        csvRows.push(values.join(','));
    }

    return csvRows.join('\n');
}

function clearOldLogs() {
    new bootstrap.Modal(document.getElementById('clearLogsModal')).show();
}

function confirmClearLogs() {
    const days = document.getElementById('clearDays').value;

    fetch('/admin/logs/clear-old', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ days: days })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Errore nella pulizia dei log');
        }
    })
    .catch(error => {
        console.error('Errore nella pulizia:', error);
        alert('Errore nella pulizia dei log');
    });
}
</script>
@endpush
