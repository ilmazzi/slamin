@extends('layout.master')
@section('title', 'Gestione Disponibilità - ' . $event->title)

@section('css')
<style>
/* Mobile-First Responsive Styles for Availability Management */
@media (max-width: 576px) {
    .card-body {
        padding: 1rem !important;
    }
    
    .card-header {
        padding: 0.75rem 1rem !important;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .badge {
        font-size: 0.625rem;
    }
    
    .form-floating > label {
        font-size: 0.875rem;
    }
    
    .form-control {
        font-size: 0.875rem;
    }
    
    .btn {
        font-size: 0.875rem;
    }
    
    .option-row .btn {
        width: 100%;
        margin-top: 0.5rem;
    }
    
    .option-row .col-12:last-child {
        margin-top: 0.5rem;
    }
}

@media (min-width: 768px) {
    .option-row .btn {
        width: auto;
        margin-top: 0;
    }
    
    .option-row .col-12:last-child {
        margin-top: 0;
    }
}
</style>
@endsection

@section('main-content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card hover-effect">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">
                            <i class="ph ph-calendar-check me-2 text-warning"></i>
                            Gestione Disponibilità
                        </h5>
                        <small class="text-muted">{{ $event->title }}</small>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('events.show', $event) }}" class="btn btn-light-primary btn-sm">
                            <i class="ph ph-arrow-left me-1"></i>Torna all'Evento
                        </a>
                        <button class="btn btn-success btn-sm" id="addAvailabilityOptions">
                            <i class="ph ph-plus me-1"></i>Aggiungi Opzioni
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Info -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card hover-effect">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-8">
                            <h6 class="mb-2">{{ $event->title }}</h6>
                            <p class="text-muted mb-2 mb-md-0">{{ $event->description }}</p>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="d-flex flex-column flex-md-column gap-2 mb-3 mb-md-0">
                                <div class="d-flex flex-wrap gap-2">
                                    <span class="badge bg-info">
                                        <i class="ph ph-users me-1"></i>
                                        {{ $event->invitations()->accepted()->count() + $event->requests()->where('status', 'accepted')->count() }} Partecipanti
                                    </span>
                                    @if($event->availability_deadline)
                                        <span class="badge bg-warning">
                                            <i class="ph ph-clock me-1"></i>
                                            Scadenza: {{ $event->availability_deadline->format('d/m/Y H:i') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Instructions -->
    @if($event->availability_instructions)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-info">
                <div class="card-header bg-light-info">
                    <h6 class="mb-0">
                        <i class="ph ph-info me-2"></i>Istruzioni per i Partecipanti
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $event->availability_instructions }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Availability Options -->
    <div class="row">
        <div class="col-12">
            <div class="card hover-effect">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph ph-list-checks me-2"></i>
                        Opzioni di Disponibilità
                        <span class="badge bg-primary ms-2">{{ count($availabilitySummary) }}</span>
                    </h6>
                </div>
                <div class="card-body">
                    @if(count($availabilitySummary) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Data e Ora</th>
                                        <th class="text-center">
                                            <i class="ph ph-heart text-success me-1"></i>Preferite
                                        </th>
                                        <th class="text-center">
                                            <i class="ph ph-check-circle text-warning me-1"></i>Disponibili
                                        </th>
                                        <th class="text-center">
                                            <i class="ph ph-x-circle text-danger me-1"></i>Non Disponibili
                                        </th>
                                        <th class="text-center">Totale Risposte</th>
                                        <th class="text-center">Azioni</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($availabilitySummary as $summary)
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong>{{ $summary['option']->formatted_datetime }}</strong>
                                                    @if($summary['option']->description)
                                                        <br><small class="text-muted">{{ $summary['option']->description }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-success">{{ $summary['preferred_count'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-warning">{{ $summary['available_count'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-danger">{{ $summary['unavailable_count'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-primary">{{ $summary['total_responses'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group btn-group-sm">
                                                    <button class="btn btn-outline-info btn-sm"
                                                            onclick="viewResponses({{ $summary['option']->id }})"
                                                            title="Visualizza risposte">
                                                        <i class="ph ph-eye"></i>
                                                    </button>
                                                    <button class="btn btn-outline-danger btn-sm"
                                                            onclick="deleteOption({{ $summary['option']->id }})"
                                                            title="Elimina opzione">
                                                        <i class="ph ph-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ph ph-calendar-x fs-1 text-muted mb-3"></i>
                            <h6 class="text-muted">Nessuna opzione di disponibilità</h6>
                            <p class="text-muted">Aggiungi delle opzioni di data e ora per permettere ai partecipanti di indicare la loro disponibilità.</p>
                            <button class="btn btn-success" id="addFirstAvailabilityOptions">
                                <i class="ph ph-plus me-1"></i>Aggiungi Prima Opzione
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal per aggiungere opzioni -->
<div class="modal fade" id="addOptionsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ph ph-plus me-2"></i>Aggiungi Opzioni di Disponibilità
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="availabilityOptionsContainer">
                    <!-- Le opzioni verranno aggiunte dinamicamente qui -->
                </div>
                <div class="text-center mt-3">
                    <button type="button" class="btn btn-outline-primary" id="addOptionRow">
                        <i class="ph ph-plus me-1"></i>Aggiungi Altra Opzione
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-success" id="saveAvailabilityOptions">
                    <i class="ph ph-check me-1"></i>Salva Opzioni
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal per visualizzare le risposte -->
<div class="modal fade" id="responsesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ph ph-users me-2"></i>Risposte dei Partecipanti
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="responsesModalBody">
                <!-- Le risposte verranno caricate dinamicamente qui -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/it.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let optionCounter = 0;

    // Inizializza il modal per aggiungere opzioni
    const addAvailabilityOptionsBtn = document.getElementById('addAvailabilityOptions');
    if (addAvailabilityOptionsBtn) {
        addAvailabilityOptionsBtn.addEventListener('click', function() {
            openAddOptionsModal();
        });
    }

    const addFirstAvailabilityOptionsBtn = document.getElementById('addFirstAvailabilityOptions');
    if (addFirstAvailabilityOptionsBtn) {
        addFirstAvailabilityOptionsBtn.addEventListener('click', function() {
            openAddOptionsModal();
        });
    }

    function openAddOptionsModal() {
        // Pulisci il container
        document.getElementById('availabilityOptionsContainer').innerHTML = '';
        optionCounter = 0;

        // Aggiungi la prima riga
        addOptionRow();

        // Mostra il modal
        new bootstrap.Modal(document.getElementById('addOptionsModal')).show();
    }

    // Aggiungi una nuova riga di opzione
    const addOptionRowBtn = document.getElementById('addOptionRow');
    if (addOptionRowBtn) {
        addOptionRowBtn.addEventListener('click', function() {
            addOptionRow();
        });
    }

    function addOptionRow() {
        optionCounter++;
        const container = document.getElementById('availabilityOptionsContainer');

        const row = document.createElement('div');
        row.className = 'row mb-3 option-row';
        row.innerHTML = `
            <div class="col-12 col-md-6 mb-3 mb-md-0">
                <div class="form-floating">
                    <input type="text" class="form-control flatpickr-input"
                           id="datetime_${optionCounter}"
                           placeholder="Seleziona data e ora...">
                    <label for="datetime_${optionCounter}">Data e Ora *</label>
                </div>
            </div>
            <div class="col-12 col-md-5 mb-3 mb-md-0">
                <div class="form-floating">
                    <input type="text" class="form-control"
                           id="description_${optionCounter}"
                           placeholder="Descrizione opzionale...">
                    <label for="description_${optionCounter}">Descrizione (opzionale)</label>
                </div>
            </div>
            <div class="col-12 col-md-1 d-flex align-items-end justify-content-center justify-content-md-end">
                <button type="button" class="btn btn-outline-danger btn-sm w-100 w-md-auto" onclick="removeOptionRow(this)">
                    <i class="ph ph-trash me-1"></i>
                    <span class="d-md-none">Rimuovi</span>
                </button>
            </div>
        `;

        container.appendChild(row);

        // Inizializza flatpickr per la nuova riga
        flatpickr(document.getElementById(`datetime_${optionCounter}`), {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            minDate: "today",
            time_24hr: true,
            locale: "it"
        });
    }

    function removeOptionRow(button) {
        button.closest('.option-row').remove();
    }

    // Salva le opzioni di disponibilità
    const saveAvailabilityOptionsBtn = document.getElementById('saveAvailabilityOptions');
    if (saveAvailabilityOptionsBtn) {
        saveAvailabilityOptionsBtn.addEventListener('click', function() {
            const options = [];
            const rows = document.querySelectorAll('.option-row');

            rows.forEach(row => {
                const datetimeInput = row.querySelector('input.flatpickr-input');
                const descriptionInput = row.querySelector('input[type="text"]:not(.flatpickr-input)');

                console.log('Row found, datetimeInput:', datetimeInput, 'descriptionInput:', descriptionInput);
                console.log('Datetime value:', datetimeInput?.value, 'Description value:', descriptionInput?.value);

                if (datetimeInput && datetimeInput.value) {
                    options.push({
                        datetime: datetimeInput.value,
                        description: descriptionInput?.value || null
                    });
                }
            });

            if (options.length === 0) {
                alert('Aggiungi almeno un\'opzione di data e ora.');
                return;
            }

            // Invia la richiesta
            fetch(`{{ route('events.availability.store-options', $event) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ options: options })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Errore durante il salvataggio delle opzioni.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Errore durante il salvataggio delle opzioni.');
            });
        });
    }

    // Visualizza le risposte per un'opzione
    window.viewResponses = function(optionId) {
        // Mostra il modal
        const modal = new bootstrap.Modal(document.getElementById('responsesModal'));

        // Mostra loading
        document.getElementById('responsesModalBody').innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Caricamento...</span>
                </div>
                <p class="mt-2">Caricamento risposte...</p>
            </div>
        `;

        modal.show();

        // Carica le risposte
        fetch(`{{ route('events.availability.option-responses', [$event, 'PLACEHOLDER']) }}`.replace('PLACEHOLDER', optionId), {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayResponses(data);
            } else {
                showError('Errore nel caricamento delle risposte');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showError('Errore nel caricamento delle risposte');
        });
    }

    function displayResponses(data) {
        const modalBody = document.getElementById('responsesModalBody');

        let html = `
            <div class="mb-3">
                <h6 class="text-primary">
                    <i class="ph ph-calendar me-2"></i>${data.option.datetime}
                </h6>
                ${data.option.description ? `<p class="text-muted mb-0">${data.option.description}</p>` : ''}
            </div>

            <div class="row mb-3">
                <div class="col-3">
                    <div class="text-center">
                        <div class="fs-4 fw-bold text-success">${data.summary.preferred}</div>
                        <small class="text-muted">Preferite</small>
                    </div>
                </div>
                <div class="col-3">
                    <div class="text-center">
                        <div class="fs-4 fw-bold text-warning">${data.summary.available}</div>
                        <small class="text-muted">Disponibili</small>
                    </div>
                </div>
                <div class="col-3">
                    <div class="text-center">
                        <div class="fs-4 fw-bold text-danger">${data.summary.unavailable}</div>
                        <small class="text-muted">Non Disponibili</small>
                    </div>
                </div>
                <div class="col-3">
                    <div class="text-center">
                        <div class="fs-4 fw-bold text-primary">${data.summary.total}</div>
                        <small class="text-muted">Totale</small>
                    </div>
                </div>
            </div>
        `;

        if (data.responses.length > 0) {
            html += `
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Partecipante</th>
                                <th>Stato</th>
                                <th>Note</th>
                                <th>Data Risposta</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            data.responses.forEach(response => {
                html += `
                    <tr>
                        <td>
                            <div>
                                <div class="fw-semibold">${response.user_name}</div>
                                <small class="text-muted">${response.user_email}</small>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-${response.status_color}">${response.status_label}</span>
                        </td>
                        <td>${response.notes || '-'}</td>
                        <td>
                            <small class="text-muted">${response.created_at}</small>
                        </td>
                    </tr>
                `;
            });

            html += `
                        </tbody>
                    </table>
                </div>
            `;
        } else {
            html += `
                <div class="text-center py-4">
                    <i class="ph ph-users fs-1 text-muted mb-3"></i>
                    <h6 class="text-muted">Nessuna risposta</h6>
                    <p class="text-muted">Nessun partecipante ha ancora risposto a questa opzione.</p>
                </div>
            `;
        }

        modalBody.innerHTML = html;
    }

    function showError(message) {
        document.getElementById('responsesModalBody').innerHTML = `
            <div class="text-center py-4">
                <i class="ph ph-warning-circle fs-1 text-danger mb-3"></i>
                <h6 class="text-danger">Errore</h6>
                <p class="text-muted">${message}</p>
            </div>
        `;
    }

    // Elimina un'opzione
    window.deleteOption = function(optionId) {
        if (confirm('Sei sicuro di voler eliminare questa opzione? Questa azione non può essere annullata.')) {
            fetch(`{{ route('events.availability.delete-option', [$event, 'PLACEHOLDER']) }}`.replace('PLACEHOLDER', optionId), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Errore durante l\'eliminazione dell\'opzione.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Errore durante l\'eliminazione dell\'opzione.');
            });
        }
    }

    // Rendi le funzioni globali per i pulsanti onclick
    window.removeOptionRow = removeOptionRow;
});
</script>
@endpush


