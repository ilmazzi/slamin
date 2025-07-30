@extends('layout.master')

@section('title', __('admin.edit_translations_title', ['language' => strtoupper($language), 'file' => $file]))

@section('css')
<style>
    .translation-row {
        transition: all 0.2s ease;
    }
    .translation-row:hover {
        background-color: var(--bs-light);
    }
    .translation-row.missing {
        background-color: rgba(255, 193, 7, 0.1);
    }
    .translation-row.translated {
        background-color: rgba(25, 135, 84, 0.05);
    }
    .translation-row.untranslated {
        background-color: rgba(13, 110, 253, 0.05);
    }
    .search-highlight {
        background-color: yellow;
        padding: 1px 2px;
        border-radius: 2px;
    }
    .key-badge {
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 10px;
        background-color: var(--bs-light);
        color: var(--bs-secondary);
        font-family: monospace;
    }
    .status-badge {
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 10px;
    }
    .reference-text {
        background-color: var(--bs-light);
        border: 1px solid var(--bs-border-color);
        border-radius: 4px;
        padding: 8px;
        font-size: 12px;
        min-height: 40px;
        word-wrap: break-word;
    }
    .translation-input {
        border: 1px solid var(--bs-border-color);
        border-radius: 4px;
        padding: 8px;
        font-size: 12px;
        min-height: 40px;
        resize: vertical;
    }
    .translation-input:focus {
        border-color: var(--bs-primary);
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
    .filter-buttons .btn {
        font-size: 11px;
        padding: 4px 8px;
    }
    .search-box {
        max-width: 300px;
    }
</style>
@endsection

@section('breadcrumb-title')
<h3>{{ __('admin.edit_translations_title', ['language' => strtoupper($language), 'file' => $file]) }}</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('dashboard.dashboard') }}</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.translations.index') }}">{{ __('admin.translation_management') }}</a></li>
<li class="breadcrumb-item active">{{ strtoupper($language) }} - {{ $file }}</li>
@endsection

@section('main-content')
<div class="container-fluid">
    <!-- Header con statistiche -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card hover-effect">
                <div class="card-body bg-light-primary">
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <h5 class="mb-1">
                                <i class="ph ph-translate me-2"></i>
                                {{ strtoupper($language) }} - {{ $file }}
                            </h5>
                            <p class="text-muted mb-0 f-s-14">
                                Gestisci le traduzioni per il file {{ $file }} in {{ strtoupper($language) }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.translations.index') }}" class="btn btn-light-secondary">
                                    <i class="ph ph-arrow-left me-1"></i>{{ __('admin.back_to_list') }}
                                </a>
                                <button type="button" class="btn btn-warning" onclick="copyAllFromReference()">
                                    <i class="ph ph-copy me-1"></i>Copia Tutto da Riferimento
                                </button>
                                <button type="button" class="btn btn-success" onclick="autoTranslateAll()">
                                    <i class="ph ph-robot me-1"></i>Traduci Automaticamente
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiche e progresso -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="f-s-24 f-w-600 text-primary">{{ count($flattenedReferenceTranslations) }}</div>
                            <div class="f-s-12 text-muted">Chiavi Totali</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="f-s-24 f-w-600 text-success" id="translatedCount">
                                {{ count(array_filter($flattenedTranslations, function($value) { return !empty($value); })) }}
                            </div>
                            <div class="f-s-12 text-muted">Tradotte</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="f-s-24 f-w-600 text-warning" id="missingCount">
                                {{ count($missingKeys ?? []) }}
                            </div>
                            <div class="f-s-12 text-muted">Mancanti</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="f-s-24 f-w-600 text-info" id="progressPercent">
                                {{ count($flattenedReferenceTranslations) > 0 ? round((count(array_filter($flattenedTranslations, function($value) { return !empty($value); })) / count($flattenedReferenceTranslations)) * 100) : 0 }}%
                            </div>
                            <div class="f-s-12 text-muted">Completato</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Controlli di ricerca e filtri -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label f-s-12 f-w-500">Ricerca Chiavi</label>
                            <input type="text" id="searchInput" class="form-control search-box" placeholder="Cerca chiavi di traduzione...">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label f-s-12 f-w-500">Filtra per Stato</label>
                            <div class="filter-buttons">
                                <button type="button" class="btn btn-outline-primary btn-sm active" data-filter="all">
                                    Tutte ({{ count($flattenedReferenceTranslations) }})
                                </button>
                                <button type="button" class="btn btn-outline-success btn-sm" data-filter="translated">
                                    Tradotte (<span id="translatedFilterCount">{{ count(array_filter($flattenedTranslations, function($value) { return !empty($value); })) }}</span>)
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-sm" data-filter="missing">
                                    Mancanti (<span id="missingFilterCount">{{ count($missingKeys ?? []) }}</span>)
                                </button>
                                <button type="button" class="btn btn-outline-info btn-sm" data-filter="untranslated">
                                    Da Tradurre (<span id="untranslatedFilterCount">{{ count($flattenedReferenceTranslations) - count(array_filter($flattenedTranslations, function($value) { return !empty($value); })) - count($missingKeys ?? []) }}</span>)
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-light-primary" onclick="expandAll()">
                                    <i class="ph ph-arrows-out me-1"></i>Espandi Tutto
                                </button>
                                <button type="button" class="btn btn-light-secondary" onclick="collapseAll()">
                                    <i class="ph ph-arrows-in me-1"></i>Comprimi Tutto
                                </button>
                                <button type="button" class="btn btn-light-warning" onclick="showOnlyUntranslated()">
                                    <i class="ph ph-eye me-1"></i>Solo Non Tradotte
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form di traduzione -->
    <form id="translationForm" action="{{ route('admin.translations.update', [$language, $file]) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 f-s-14">
                                <i class="ph ph-list me-1"></i>Chiavi di Traduzione
                            </h6>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-light-warning btn-sm" onclick="clearAll()">
                                    <i class="ph ph-trash me-1"></i>Pulisci Tutto
                                </button>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="ph ph-floppy-disk me-1"></i>Salva Traduzioni
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 30%;" class="f-s-12 f-w-500">Chiave</th>
                                        <th style="width: 35%;" class="f-s-12 f-w-500">Riferimento (Italiano)</th>
                                        <th style="width: 35%;" class="f-s-12 f-w-500">Traduzione ({{ strtoupper($language) }})</th>
                                    </tr>
                                </thead>
                                <tbody id="translationTableBody">
                                    @foreach($flattenedReferenceTranslations as $key => $referenceValue)
                                    @php
                                        $isMissing = isset($missingKeys) && in_array($key, $missingKeys);
                                        $isTranslated = !empty($flattenedTranslations[$key]) && !$isMissing;
                                        $statusClass = $isMissing ? 'missing' : ($isTranslated ? 'translated' : 'untranslated');
                                    @endphp
                                    <tr class="translation-row {{ $statusClass }}" data-key="{{ $key }}" data-status="{{ $isMissing ? 'missing' : ($isTranslated ? 'translated' : 'untranslated') }}">
                                        <td class="align-top py-3">
                                            <div class="d-flex flex-column gap-1">
                                                <span class="key-badge">{{ $key }}</span>
                                                <span class="status-badge {{ $isMissing ? 'bg-warning text-dark' : ($isTranslated ? 'bg-success text-white' : 'bg-primary text-white') }}">
                                                    {{ $isMissing ? 'Mancante' : ($isTranslated ? 'Tradotta' : 'Da tradurre') }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="align-top py-3">
                                            <div class="reference-text">{{ $referenceValue }}</div>
                                        </td>
                                        <td class="align-top py-3">
                                            <textarea name="translations[{{ $key }}]"
                                                      class="form-control translation-input"
                                                      rows="2"
                                                      placeholder="Inserisci la traduzione..."
                                                      data-key="{{ $key }}"
                                                      data-reference="{{ $referenceValue }}"
                                                      onchange="updateProgress()">{{ $flattenedTranslations[$key] ?? '' }}</textarea>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Suggerimenti -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-light-primary">
                <div class="d-flex align-items-start">
                    <i class="ph ph-lightbulb me-2 mt-1 text-primary f-s-14"></i>
                    <div class="f-s-12">
                        <strong class="text-primary">Suggerimenti:</strong>
                        <ul class="mb-0 mt-1">
                            <li>Usa la ricerca per trovare rapidamente le chiavi</li>
                            <li>Filtra per stato per concentrarti su traduzioni specifiche</li>
                            <li>Usa il testo italiano come riferimento</li>
                            <li>Mantieni la stessa lunghezza quando possibile</li>
                            <li>Controlla grammatica e ortografia</li>
                            <li>Salva spesso per non perdere le modifiche</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal per conferma azioni -->
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Conferma Azione</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="confirmModalBody">
                Sei sicuro di voler eseguire questa azione?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" id="confirmModalAction">Conferma</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script>
let currentFilter = 'all';
let searchTerm = '';

// Inizializzazione
document.addEventListener('DOMContentLoaded', function() {
    setupEventListeners();
    updateProgress();
});

function setupEventListeners() {
    // Ricerca
    document.getElementById('searchInput').addEventListener('input', function(e) {
        searchTerm = e.target.value.toLowerCase();
        filterTranslations();
    });

    // Filtri
    document.querySelectorAll('[data-filter]').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('[data-filter]').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentFilter = this.dataset.filter;
            filterTranslations();
        });
    });
}

function filterTranslations() {
    const rows = document.querySelectorAll('.translation-row');
    let visibleCount = 0;

    rows.forEach(row => {
        const key = row.dataset.key.toLowerCase();
        const status = row.dataset.status;
        const matchesSearch = searchTerm === '' || key.includes(searchTerm);
        const matchesFilter = currentFilter === 'all' || status === currentFilter;

        if (matchesSearch && matchesFilter) {
            row.style.display = '';
            visibleCount++;
            
            // Evidenzia il termine di ricerca
            if (searchTerm) {
                highlightSearchTerm(row, searchTerm);
            }
        } else {
            row.style.display = 'none';
        }
    });

    // Aggiorna contatori
    updateFilterCounts();
}

function highlightSearchTerm(row, term) {
    const keyElement = row.querySelector('.key-badge');
    if (keyElement) {
        const originalText = keyElement.textContent;
        const highlightedText = originalText.replace(new RegExp(term, 'gi'), match => 
            `<span class="search-highlight">${match}</span>`
        );
        keyElement.innerHTML = highlightedText;
    }
}

function updateFilterCounts() {
    const rows = document.querySelectorAll('.translation-row');
    let translatedCount = 0;
    let missingCount = 0;
    let untranslatedCount = 0;

    rows.forEach(row => {
        if (row.style.display !== 'none') {
            const status = row.dataset.status;
            if (status === 'translated') translatedCount++;
            else if (status === 'missing') missingCount++;
            else if (status === 'untranslated') untranslatedCount++;
        }
    });

    document.getElementById('translatedFilterCount').textContent = translatedCount;
    document.getElementById('missingFilterCount').textContent = missingCount;
    document.getElementById('untranslatedFilterCount').textContent = untranslatedCount;
}

function updateProgress() {
    const inputs = document.querySelectorAll('textarea[name^="translations"]');
    let translatedCount = 0;
    let totalCount = inputs.length;

    inputs.forEach(input => {
        if (input.value.trim() !== '') {
            translatedCount++;
        }
    });

    const progressPercent = totalCount > 0 ? Math.round((translatedCount / totalCount) * 100) : 0;
    
    document.getElementById('translatedCount').textContent = translatedCount;
    document.getElementById('progressPercent').textContent = progressPercent + '%';
    
    // Aggiorna anche i contatori dei filtri
    updateFilterCounts();
}

function expandAll() {
    // Per la vista tabellare, non c'è bisogno di espandere
    // Ma possiamo mostrare tutte le righe
    currentFilter = 'all';
    document.querySelectorAll('[data-filter]').forEach(b => b.classList.remove('active'));
    document.querySelector('[data-filter="all"]').classList.add('active');
    filterTranslations();
}

function collapseAll() {
    // Per la vista tabellare, possiamo nascondere tutte le righe
    document.querySelectorAll('.translation-row').forEach(row => {
        row.style.display = 'none';
    });
}

function showOnlyUntranslated() {
    currentFilter = 'untranslated';
    document.querySelectorAll('[data-filter]').forEach(b => b.classList.remove('active'));
    document.querySelector('[data-filter="untranslated"]').classList.add('active');
    filterTranslations();
}

function copyAllFromReference() {
    showConfirmModal(
        'Sei sicuro di voler copiare tutte le traduzioni dal testo di riferimento? Questa azione sovrascriverà tutte le traduzioni esistenti.',
        () => {
            const rows = document.querySelectorAll('.translation-row');
            rows.forEach(row => {
                const referenceText = row.querySelector('.reference-text').textContent;
                const textarea = row.querySelector('textarea');
                textarea.value = referenceText;
            });
            updateProgress();
        }
    );
}

function clearAll() {
    showConfirmModal(
        'Sei sicuro di voler cancellare tutte le traduzioni? Questa azione non può essere annullata.',
        () => {
            const textareas = document.querySelectorAll('textarea[name^="translations"]');
            textareas.forEach(textarea => {
                textarea.value = '';
            });
            updateProgress();
        }
    );
}

function autoTranslateAll() {
    showConfirmModal(
        'Sei sicuro di voler tradurre automaticamente tutte le chiavi non tradotte? Questa operazione potrebbe richiedere alcuni minuti.',
        () => {
            // Implementa la traduzione automatica
            const untranslatedRows = document.querySelectorAll('.translation-row.untranslated');
            untranslatedRows.forEach((row, index) => {
                setTimeout(() => {
                    const referenceText = row.querySelector('.reference-text').textContent;
                    const textarea = row.querySelector('textarea');
                    // Qui andrebbe la chiamata API per la traduzione automatica
                    // Per ora, copiamo il testo di riferimento
                    textarea.value = referenceText;
                    updateProgress();
                }, index * 100); // Ritardo per non sovraccaricare
            });
        }
    );
}

function showConfirmModal(message, action) {
    document.getElementById('confirmModalBody').textContent = message;
    document.getElementById('confirmModalAction').onclick = () => {
        action();
        bootstrap.Modal.getInstance(document.getElementById('confirmModal')).hide();
    };
    new bootstrap.Modal(document.getElementById('confirmModal')).show();
}

// Salvataggio automatico ogni 30 secondi
setInterval(() => {
    const form = document.getElementById('translationForm');
    const formData = new FormData(form);
    
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    }).then(response => {
        if (response.ok) {
            console.log('Salvataggio automatico completato');
        }
    }).catch(error => {
        console.error('Errore nel salvataggio automatico:', error);
    });
}, 30000);
</script>
@endsection
