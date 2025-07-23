@extends('layout.master')

@section('title', 'Impostazioni - Admin')

@section('main-content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">
                <i class="ph ph-gear me-2"></i>
                Impostazioni
            </h4>
            <ul class="app-line-breadcrumbs mb-3">
                <li class="">
                    <a href="{{ route('dashboard') }}" class="f-s-14 f-w-500">
                        <span>
                            <i class="ph-duotone ph-house f-s-16"></i> Dashboard
                        </span>
                    </a>
                </li>
                <li class="active">
                    <a href="#" class="f-s-14 f-w-500">Impostazioni</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Settings Form -->
    <form id="settingsForm" action="{{ route('admin.settings.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            @foreach($groups as $groupKey => $groupName)
            <div class="col-lg-6 mb-4">
                <div class="card hover-effect">
                    <div class="card-header">
                        <h5 class="mb-0 f-w-600 text-dark">
                            <i class="ph ph-{{ $groupKey === 'upload' ? 'upload' : ($groupKey === 'video' ? 'video-camera' : 'gear') }} me-2"></i>
                            {{ $groupName }}
                        </h5>
                    </div>
                    <div class="card-body pa-30">
                        @if(isset($settings[$groupKey]))
                            @foreach($settings[$groupKey] as $key => $setting)
                                <div class="mb-4">
                                    <label class="form-label f-w-600">{{ $setting['display_name'] }}</label>

                                    @if($setting['type'] === 'boolean')
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox"
                                                   name="settings[{{ $key }}]" value="1"
                                                   id="setting_{{ $key }}"
                                                   {{ $setting['value'] ? 'checked' : '' }}>
                                            <label class="form-check-label" for="setting_{{ $key }}">
                                                Abilitato
                                            </label>
                                        </div>
                                    @elseif($setting['type'] === 'json')
                                        <textarea class="form-control" name="settings[{{ $key }}]"
                                                  rows="3" placeholder="Inserisci JSON valido">{{ is_array($setting['value']) ? json_encode($setting['value'], JSON_PRETTY_PRINT) : $setting['value'] }}</textarea>
                                    @elseif($setting['type'] === 'integer')
                                        <input type="number" class="form-control"
                                               name="settings[{{ $key }}]"
                                               value="{{ $setting['value'] }}"
                                               min="0">
                                    @else
                                        <input type="text" class="form-control"
                                               name="settings[{{ $key }}]"
                                               value="{{ $setting['value'] }}">
                                    @endif

                                    @if($setting['description'])
                                        <small class="text-muted f-s-12">{{ $setting['description'] }}</small>
                                    @endif
                                </div>
                            @endforeach
                        @else
                            <div class="text-center text-muted">
                                <i class="ph ph-info f-s-24 mb-2"></i>
                                <p>Nessuna impostazione disponibile per questo gruppo</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Action Buttons -->
        <div class="row">
            <div class="col-12">
                <div class="card hover-effect">
                    <div class="card-body pa-30">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <button type="button" class="btn btn-warning hover-effect me-2" onclick="resetSettings()">
                                    <i class="ph ph-arrow-clockwise me-2"></i>
                                    Reset ai Default
                                </button>
                                <button type="button" class="btn btn-info hover-effect" onclick="refreshSettings()">
                                    <i class="ph ph-arrows-clockwise me-2"></i>
                                    Ricarica
                                </button>
                            </div>
                            <div>
                                <a href="{{ route('dashboard') }}" class="btn btn-secondary hover-effect me-2">
                                    <i class="ph ph-x me-2"></i>
                                    Annulla
                                </a>
                                <button type="submit" class="btn btn-primary hover-effect">
                                    <i class="ph ph-check me-2"></i>
                                    Salva Impostazioni
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thumbnail Management Section -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card hover-effect">
                    <div class="card-header">
                        <h5 class="mb-0 f-w-600 text-dark">
                            <i class="ph ph-image me-2"></i>
                            Gestione Thumbnail Video
                        </h5>
                    </div>
                    <div class="card-body pa-30">
                        <!-- Statistics -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="eshop-card bg-primary text-white">
                                    <div class="eshop-card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="eshop-card-icon">
                                                <i class="ph ph-video-camera f-s-24"></i>
                                            </div>
                                            <div class="eshop-card-content">
                                                <h6 class="mb-1">Totale Video</h6>
                                                <h4 class="mb-0" id="totalVideos">-</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="eshop-card bg-success text-white">
                                    <div class="eshop-card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="eshop-card-icon">
                                                <i class="ph ph-check-circle f-s-24"></i>
                                            </div>
                                            <div class="eshop-card-content">
                                                <h6 class="mb-1">Con Thumbnail</h6>
                                                <h4 class="mb-0" id="withThumbnail">-</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="eshop-card bg-warning text-white">
                                    <div class="eshop-card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="eshop-card-icon">
                                                <i class="ph ph-warning f-s-24"></i>
                                            </div>
                                            <div class="eshop-card-content">
                                                <h6 class="mb-1">Senza Thumbnail</h6>
                                                <h4 class="mb-0" id="withoutThumbnail">-</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="eshop-card bg-info text-white">
                                    <div class="eshop-card-body">
                                        <div class="d-flex align-items-center">
                                            <div class="eshop-card-icon">
                                                <i class="ph ph-percent f-s-24"></i>
                                            </div>
                                            <div class="eshop-card-content">
                                                <h6 class="mb-1">Percentuale</h6>
                                                <h4 class="mb-0" id="percentage">-</h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-3 mb-4">
                            <button type="button" class="btn btn-success hover-effect" onclick="generateMissingThumbnails()">
                                <i class="ph ph-plus-circle me-2"></i>
                                Genera Thumbnail Mancanti
                            </button>
                            <button type="button" class="btn btn-warning hover-effect" onclick="regenerateAllThumbnails()">
                                <i class="ph ph-arrow-clockwise me-2"></i>
                                Rigenera Tutte le Thumbnail
                            </button>
                            <button type="button" class="btn btn-info hover-effect" onclick="refreshThumbnailStats()">
                                <i class="ph ph-arrows-clockwise me-2"></i>
                                Aggiorna Statistiche
                            </button>
                        </div>

                        <!-- Progress Bar -->
                        <div class="progress mb-3" id="thumbnailProgress" style="display: none;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                 role="progressbar"
                                 style="width: 0%"
                                 id="thumbnailProgressBar">0%</div>
                        </div>

                        <!-- Results -->
                        <div id="thumbnailResults" style="display: none;">
                            <h6 class="mb-3">Risultati:</h6>
                            <div id="thumbnailResultsContent"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('script')
<script>
// Carica statistiche thumbnail all'avvio
document.addEventListener('DOMContentLoaded', function() {
    refreshThumbnailStats();
});

// Submit form con AJAX
document.getElementById('settingsForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    // Show loading
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="ph ph-spinner ph-spin me-2"></i>Salvando...';
    submitBtn.disabled = true;

    fetch(this.action, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Success message
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Successo!',
                    text: data.message,
                    timer: 3000,
                    showConfirmButton: false
                });
            } else {
                alert(data.message);
            }
        } else {
            // Error message
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Errore!',
                    text: data.message,
                    footer: data.errors ? data.errors.join('<br>') : ''
                });
            } else {
                alert('Errore: ' + data.message);
            }
        }
    })
    .catch(error => {
        console.error('Errore:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Errore!',
                text: 'Errore durante il salvataggio: ' + error.message
            });
        } else {
            alert('Errore durante il salvataggio: ' + error.message);
        }
    })
    .finally(() => {
        // Reset button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// Reset settings
function resetSettings() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Conferma Reset',
            text: 'Sei sicuro di voler reimpostare tutte le impostazioni ai valori di default? Questa azione non può essere annullata.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sì, resetta!',
            cancelButtonText: 'Annulla'
        }).then((result) => {
            if (result.isConfirmed) {
                performReset();
            }
        });
    } else {
        if (confirm('Sei sicuro di voler reimpostare tutte le impostazioni ai valori di default?')) {
            performReset();
        }
    }
}

function performReset() {
    fetch('{{ route("admin.settings.reset") }}', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'success',
                    title: 'Reset Completato!',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                }).then(() => {
                    location.reload();
                });
            } else {
                alert(data.message);
                location.reload();
            }
        } else {
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Errore!',
                    text: data.message
                });
            } else {
                alert('Errore: ' + data.message);
            }
        }
    })
    .catch(error => {
        console.error('Errore:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Errore!',
                text: 'Errore durante il reset: ' + error.message
            });
        } else {
            alert('Errore durante il reset: ' + error.message);
        }
    });
}

// Refresh settings
function refreshSettings() {
    location.reload();
}

// ===== THUMBNAIL MANAGEMENT FUNCTIONS =====

// Aggiorna statistiche thumbnail
function refreshThumbnailStats() {
    fetch('{{ route("admin.settings.thumbnails") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            action: 'get_stats'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('totalVideos').textContent = data.data.total_videos;
            document.getElementById('withThumbnail').textContent = data.data.with_thumbnail;
            document.getElementById('withoutThumbnail').textContent = data.data.without_thumbnail;
            document.getElementById('percentage').textContent = data.data.percentage + '%';
        }
    })
    .catch(error => {
        console.error('Errore nel caricamento statistiche:', error);
    });
}

// Genera thumbnail mancanti
function generateMissingThumbnails() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Conferma Generazione',
            text: 'Vuoi generare le thumbnail per tutti i video che non ne hanno una?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sì, Genera',
            cancelButtonText: 'Annulla'
        }).then((result) => {
            if (result.isConfirmed) {
                executeThumbnailAction('generate_missing');
            }
        });
    } else {
        if (confirm('Vuoi generare le thumbnail per tutti i video che non ne hanno una?')) {
            executeThumbnailAction('generate_missing');
        }
    }
}

// Rigenera tutte le thumbnail
function regenerateAllThumbnails() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Conferma Rigenerazione',
            text: 'ATTENZIONE: Questa operazione eliminerà tutte le thumbnail esistenti e ne genererà di nuove. Continuare?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sì, Rigenera',
            cancelButtonText: 'Annulla',
            confirmButtonColor: '#ffc107'
        }).then((result) => {
            if (result.isConfirmed) {
                executeThumbnailAction('regenerate_all');
            }
        });
    } else {
        if (confirm('ATTENZIONE: Questa operazione eliminerà tutte le thumbnail esistenti e ne genererà di nuove. Continuare?')) {
            executeThumbnailAction('regenerate_all');
        }
    }
}

// Esegue l'azione thumbnail
function executeThumbnailAction(action) {
    // Mostra progress bar
    document.getElementById('thumbnailProgress').style.display = 'block';
    document.getElementById('thumbnailProgressBar').style.width = '0%';
    document.getElementById('thumbnailProgressBar').textContent = '0%';

    // Disabilita bottoni
    const buttons = document.querySelectorAll('button[onclick*="Thumbnail"]');
    buttons.forEach(btn => btn.disabled = true);

    fetch('{{ route("admin.settings.thumbnails") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            action: action
        })
    })
    .then(response => response.json())
    .then(data => {
        // Aggiorna progress bar
        document.getElementById('thumbnailProgressBar').style.width = '100%';
        document.getElementById('thumbnailProgressBar').textContent = '100%';

        // Mostra risultati
        showThumbnailResults(data);

        // Aggiorna statistiche
        refreshThumbnailStats();

        // Mostra messaggio
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: data.success ? 'success' : 'error',
                title: data.success ? 'Completato!' : 'Errore!',
                text: data.message,
                timer: data.success ? 3000 : null
            });
        } else {
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Errore:', error);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Errore!',
                text: 'Errore durante l\'operazione: ' + error.message
            });
        } else {
            alert('Errore durante l\'operazione: ' + error.message);
        }
    })
    .finally(() => {
        // Nasconde progress bar
        setTimeout(() => {
            document.getElementById('thumbnailProgress').style.display = 'none';
        }, 2000);

        // Riabilita bottoni
        buttons.forEach(btn => btn.disabled = false);
    });
}

// Mostra risultati thumbnail
function showThumbnailResults(data) {
    const resultsDiv = document.getElementById('thumbnailResults');
    const contentDiv = document.getElementById('thumbnailResultsContent');

    if (data.success && data.data && data.data.results) {
        let html = `
            <div class="alert alert-${data.data.errors > 0 ? 'warning' : 'success'} mb-3">
                <strong>Risultati:</strong> ${data.data.total_processed} video processati,
                ${data.data.success} successi, ${data.data.errors} errori
            </div>
        `;

        if (data.data.results.length > 0) {
            html += '<div class="table-responsive"><table class="table table-sm">';
            html += '<thead><tr><th>Video ID</th><th>Titolo</th><th>Stato</th><th>Dettagli</th></tr></thead><tbody>';

            data.data.results.forEach(result => {
                const statusClass = result.status === 'success' ? 'success' :
                                  result.status === 'failed' ? 'warning' : 'danger';
                const statusText = result.status === 'success' ? 'Successo' :
                                 result.status === 'failed' ? 'Fallito' : 'Errore';

                html += `<tr class="table-${statusClass}">`;
                html += `<td>${result.video_id}</td>`;
                html += `<td>${result.title}</td>`;
                html += `<td><span class="badge bg-${statusClass}">${statusText}</span></td>`;
                html += `<td>${result.thumbnail || result.error || '-'}</td>`;
                html += '</tr>';
            });

            html += '</tbody></table></div>';
        }

        contentDiv.innerHTML = html;
        resultsDiv.style.display = 'block';
    } else {
        contentDiv.innerHTML = '<div class="alert alert-info">Nessun risultato disponibile</div>';
        resultsDiv.style.display = 'block';
    }
}
</script>
@endsection
