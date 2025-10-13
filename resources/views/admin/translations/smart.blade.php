@extends('layout.master')

@section('main-content')
<div class="container-fluid" id="smart-translation-app">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="ph-duotone ph-magic-wand text-primary me-2"></i>
                        Smart Translation - {{ strtoupper($language) }}
                    </h1>
                    <div class="keyboard-shortcuts-bar mb-0">
                        <div class="d-flex align-items-center flex-wrap">
                            <i class="ph ph-keyboard me-2 f-s-16 text-primary"></i>
                            <div class="d-flex align-items-center flex-wrap gap-2">
                                <strong class="text-dark">Comandi Rapidi:</strong>
                                <span class="shortcut-item">
                                    <kbd class="shortcut-key">Ctrl/Cmd + S</kbd> 
                                    <span class="shortcut-text">Salva</span>
                                </span>
                                <span class="shortcut-separator">•</span>
                                <span class="shortcut-item">
                                    <kbd class="shortcut-key">Ctrl/Cmd + F</kbd> 
                                    <span class="shortcut-text">Cerca</span>
                                </span>
                                <span class="shortcut-separator">•</span>
                                <span class="shortcut-item">
                                    <kbd class="shortcut-key">Ctrl/Cmd + R</kbd> 
                                    <span class="shortcut-text">Revisiona</span>
                                </span>
                                <span class="shortcut-separator">•</span>
                                <span class="shortcut-item">
                                    <kbd class="shortcut-key">Alt + ↑/↓</kbd> 
                                    <span class="shortcut-text">Naviga</span>
                                </span>
                                <span class="shortcut-separator">•</span>
                                <span class="shortcut-item">
                                    <kbd class="shortcut-key">Esc</kbd> 
                                    <span class="shortcut-text">Esci Focus</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="{{ route('admin.translations.index') }}" class="btn btn-light-secondary">
                        <i class="ph ph-arrow-left me-1"></i>
                        Indietro
                    </a>
                    <button type="button" class="btn btn-light-primary" onclick="toggleFocusMode()">
                        <i class="ph ph-eye me-1"></i>
                        <span id="focus-mode-text">Modalità Focus</span>
                    </button>
                    <button type="button" class="btn btn-success" onclick="saveAllTranslations()">
                        <i class="ph ph-floppy-disk me-1"></i>
                        Salva Tutto
                    </button>
                    <button type="button" class="btn btn-warning" onclick="findUnusedKeys()">
                        <i class="ph ph-trash me-1"></i>
                        Chiavi Non Usate
                    </button>
                    <button type="button" class="btn btn-info" onclick="findMissingKeys()">
                        <i class="ph ph-plus-circle me-1"></i>
                        Chiavi Mancanti
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Compact Statistics -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card hover-effect">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-2">
                            <div class="border-end">
                                <h4 class="mb-1 text-primary" id="stat-total">{{ $stats['total_keys'] }}</h4>
                                <p class="text-muted mb-0 f-s-12">Totale</p>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="border-end">
                                <h4 class="mb-1 text-success" id="stat-translated">{{ $stats['translated_keys'] }}</h4>
                                <p class="text-muted mb-0 f-s-12">Tradotte</p>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="border-end">
                                <h4 class="mb-1 text-warning" id="stat-missing">{{ $stats['missing_keys'] }}</h4>
                                <p class="text-muted mb-0 f-s-12">Mancanti</p>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="border-end">
                                <h4 class="mb-1 text-info" id="stat-reviewed">{{ $stats['reviewed_keys'] }}</h4>
                                <p class="text-muted mb-0 f-s-12">Revisionate</p>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="border-end">
                                <h4 class="mb-1 text-secondary" id="stat-progress">{{ $stats['progress_percentage'] }}%</h4>
                                <p class="text-muted mb-0 f-s-12">Progresso</p>
                            </div>
                        </div>
                        <div class="col-2">
                            <h4 class="mb-1 text-purple" id="stat-review-progress">{{ $stats['review_percentage'] }}%</h4>
                            <p class="text-muted mb-0 f-s-12">Revisione</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Bars -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card hover-effect">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="f-s-14 fw-semibold">Progresso Traduzione</span>
                        <span class="badge bg-light-primary" id="progress-badge">{{ $stats['progress_percentage'] }}%</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-primary" id="progress-bar" role="progressbar" 
                             style="width: {{ $stats['progress_percentage'] }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card hover-effect">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="f-s-14 fw-semibold">Progresso Revisione</span>
                        <span class="badge bg-light-info" id="review-badge">{{ $stats['review_percentage'] }}%</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-info" id="review-bar" role="progressbar" 
                             style="width: {{ $stats['review_percentage'] }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & File Selector -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card hover-effect">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label f-s-14 fw-semibold">
                                <i class="ph ph-file me-1"></i>
                                File
                            </label>
                            <select id="fileSelector" class="form-select" onchange="changeFile()">
                                @foreach($translationFiles as $fileKey => $fileDisplayName)
                                    <option value="{{ $fileKey }}" {{ $selectedFile == $fileKey ? 'selected' : '' }}>
                                        {{ $fileDisplayName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label f-s-14 fw-semibold">
                                <i class="ph ph-funnel me-1"></i>
                                Filtra per stato
                            </label>
                            <select id="filterStatus" class="form-select" onchange="applyFilters()">
                                <option value="all">Tutte le chiavi</option>
                                <option value="missing">Solo mancanti</option>
                                <option value="translated">Solo tradotte</option>
                                <option value="reviewed">Solo revisionate</option>
                                <option value="not-reviewed">Non revisionate</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label f-s-14 fw-semibold">
                                <i class="ph ph-magnifying-glass me-1"></i>
                                Cerca
                            </label>
                            <input type="text" id="searchInput" class="form-control" 
                                   placeholder="Cerca per chiave o testo..." 
                                   onkeyup="applyFilters()">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label f-s-14 fw-semibold">
                                <i class="ph ph-sort-ascending me-1"></i>
                                Ordina
                            </label>
                            <select id="sortOrder" class="form-select" onchange="applyFilters()">
                                <option value="key-asc">Chiave A-Z</option>
                                <option value="key-desc">Chiave Z-A</option>
                                <option value="status">Per stato</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Auto-save indicator -->
    <div id="autosave-indicator" class="position-fixed bottom-0 end-0 m-3 d-none">
        <div class="alert alert-success alert-dismissible fade show mb-0" role="alert">
            <i class="ph ph-check-circle me-2"></i>
            <span id="autosave-text">Salvato automaticamente</span>
        </div>
    </div>

    <!-- Translations List -->
    <div class="row">
        <div class="col-12">
            <div class="card hover-effect" id="translations-container">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="ph ph-list-bullets me-2"></i>
                        Traduzioni
                        <span class="badge bg-light-primary ms-2" id="visible-count">{{ count($translationData) }}</span>
                    </h5>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-light-info" onclick="expandAll()">
                            <i class="ph ph-arrows-out me-1"></i>
                            Espandi Tutto
                        </button>
                        <button class="btn btn-sm btn-light-secondary" onclick="collapseAll()">
                            <i class="ph ph-arrows-in me-1"></i>
                            Comprimi Tutto
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="translations-list">
                        @foreach($translationData as $key => $data)
                        <div class="translation-item border-bottom p-3 {{ $data['is_missing'] ? 'bg-light-warning' : ($data['is_reviewed'] ? 'bg-light-success' : '') }}" 
                             data-key="{{ $key }}"
                             data-status="{{ $data['is_missing'] ? 'missing' : ($data['is_translated'] ? 'translated' : 'empty') }}"
                             data-reviewed="{{ $data['is_reviewed'] ? 'true' : 'false' }}">
                            
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <!-- Key & Reference -->
                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <code class="text-primary f-s-13 fw-semibold">{{ $key }}</code>
                                        
                                        <!-- Status Badges -->
                                        @if($data['is_reviewed'])
                                            <span class="badge bg-success">
                                                <i class="ph ph-check-circle me-1"></i>
                                                Revisionata
                                            </span>
                                        @endif
                                        @if($data['is_missing'])
                                            <span class="badge bg-warning">
                                                <i class="ph ph-warning me-1"></i>
                                                Mancante
                                            </span>
                                        @endif
                                        
                                        <!-- Review Info -->
                                        @if($data['reviewed_at'])
                                            <span class="text-muted f-s-11">
                                                <i class="ph ph-clock me-1"></i>
                                                {{ $data['reviewed_at'] }}
                                                @if($data['reviewed_by_name'])
                                                    da {{ $data['reviewed_by_name'] }}
                                                @endif
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <!-- Reference Text (Collapsible) -->
                                    <div class="reference-text mb-2">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <span class="text-muted f-s-12 fw-semibold">
                                                <i class="ph ph-flag-italy me-1"></i>
                                                Italiano:
                                            </span>
                                            <button class="btn btn-sm btn-link p-0 toggle-reference" onclick="toggleReference(this)">
                                                <i class="ph ph-caret-down"></i>
                                            </button>
                                        </div>
                                        <div class="reference-content bg-light p-2 rounded f-s-12 text-muted" style="display: none;">
                                            <pre class="mb-0">{{ $data['reference'] }}</pre>
                                        </div>
                                    </div>
                                    
                                    <!-- Translation Textarea -->
                                    <textarea class="form-control translation-input f-s-13"
                                              data-key="{{ $key }}"
                                              rows="2"
                                              placeholder="Inserisci la traduzione..."
                                              style="min-height: 60px;">{{ $data['translation'] }}</textarea>
                                    
                                    <!-- Notes (if reviewed) -->
                                    @if($data['notes'])
                                        <div class="mt-2">
                                            <span class="text-muted f-s-11">
                                                <i class="ph ph-note me-1"></i>
                                                Note: {{ $data['notes'] }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Actions -->
                                <div class="d-flex flex-column gap-2">
                                    <button class="btn btn-sm {{ $data['is_reviewed'] ? 'btn-success' : 'btn-light-success' }} mark-reviewed-btn"
                                            onclick="toggleReviewed('{{ $key }}', this)"
                                            title="Segna come revisionata (Ctrl/Cmd + R)">
                                        <i class="ph {{ $data['is_reviewed'] ? 'ph-check-circle' : 'ph-circle' }}"></i>
                                    </button>
                                    <button class="btn btn-sm btn-light-primary copy-from-italian-btn"
                                            onclick="copyFromItalian('{{ $key }}')"
                                            title="Copia dall'italiano">
                                        <i class="ph ph-copy"></i>
                                    </button>
                                    <button class="btn btn-sm btn-light-info find-usage-btn"
                                            onclick="findKeyUsage('{{ $key }}')"
                                            title="Dove viene usata questa chiave">
                                        <i class="ph ph-magnifying-glass"></i>
                                    </button>
                                    <button class="btn btn-sm btn-light-secondary expand-btn"
                                            onclick="expandTextarea(this)"
                                            title="Espandi/Comprimi">
                                        <i class="ph ph-arrows-out-simple"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    @if(count($translationData) === 0)
                        <div class="text-center py-5">
                            <i class="ph-duotone ph-translate f-s-48 text-muted mb-3"></i>
                            <h5 class="text-muted">Nessuna traduzione trovata</h5>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal per mostrare l'utilizzo delle chiavi -->
<div class="modal fade" id="usageModal" tabindex="-1" aria-labelledby="usageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="usageModalLabel">
                    <i class="ph ph-magnifying-glass me-2"></i>
                    Utilizzo della chiave: <code id="usage-key-name"></code>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="usage-loading" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Caricamento...</span>
                    </div>
                    <p class="mt-2 text-muted">Ricerca in corso...</p>
                </div>
                <div id="usage-content" style="display: none;">
                    <div class="alert alert-info">
                        <i class="ph ph-info me-2"></i>
                        Trovate <strong id="usage-count">0</strong> occorrenze
                    </div>
                    <div id="usage-list">
                        <!-- Contenuto dinamico -->
                    </div>
                </div>
                <div id="usage-error" class="alert alert-danger" style="display: none;">
                    <i class="ph ph-warning me-2"></i>
                    <span id="usage-error-message"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Chiudi</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal per gestire le chiavi non utilizzate -->
<div class="modal fade" id="unusedKeysModal" tabindex="-1" aria-labelledby="unusedKeysModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="unusedKeysModalLabel">
                    <i class="ph ph-trash me-2"></i>
                    Chiavi Non Utilizzate - <code id="unused-file-name"></code>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="unused-loading" class="text-center py-4">
                    <div class="spinner-border text-warning" role="status">
                        <span class="visually-hidden">Analisi in corso...</span>
                    </div>
                    <p class="mt-2 text-muted">Sto analizzando tutte le chiavi...</p>
                </div>
                <div id="unused-content" style="display: none;">
                    <div class="alert alert-warning">
                        <i class="ph ph-warning me-2"></i>
                        Trovate <strong id="unused-count">0</strong> chiavi non utilizzate su <strong id="total-count">0</strong> totali
                    </div>
                    <div class="alert alert-info">
                        <i class="ph ph-info me-2"></i>
                        <strong>Nota:</strong> Prima della rimozione viene creato automaticamente un backup delle chiavi in <code>storage/app/translation-backups/</code>
                    </div>
                    <div class="d-flex gap-2 mb-3">
                        <button type="button" class="btn btn-sm btn-light-secondary" onclick="selectAllUnused()">
                            <i class="ph ph-check-square me-1"></i> Seleziona Tutto
                        </button>
                        <button type="button" class="btn btn-sm btn-light-secondary" onclick="deselectAllUnused()">
                            <i class="ph ph-square me-1"></i> Deseleziona Tutto
                        </button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeSelectedKeys()">
                            <i class="ph ph-trash me-1"></i> Rimuovi Selezionate
                        </button>
                    </div>
                    <div id="unused-list" class="max-height-400 overflow-auto">
                        <!-- Contenuto dinamico -->
                    </div>
                </div>
                <div id="unused-error" class="alert alert-danger" style="display: none;">
                    <i class="ph ph-warning me-2"></i>
                    <span id="unused-error-message"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Chiudi</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal per gestire le chiavi mancanti -->
<div class="modal fade" id="missingKeysModal" tabindex="-1" aria-labelledby="missingKeysModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="missingKeysModalLabel">
                    <i class="ph ph-plus-circle me-2"></i>
                    Chiavi Mancanti - <code id="missing-file-name"></code>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="missing-loading" class="text-center py-4">
                    <div class="spinner-border text-info" role="status">
                        <span class="visually-hidden">Analisi in corso...</span>
                    </div>
                    <p class="mt-2 text-muted">Sto analizzando il codice per trovare chiavi mancanti...</p>
                </div>
                <div id="missing-content" style="display: none;">
                    <div class="alert alert-info">
                        <i class="ph ph-info me-2"></i>
                        Trovate <strong id="missing-count">0</strong> chiavi mancanti nel codice
                    </div>
                    <div class="d-flex gap-2 mb-3">
                        <button type="button" class="btn btn-sm btn-light-secondary" onclick="selectAllMissing()">
                            <i class="ph ph-check-square me-1"></i> Seleziona Tutto
                        </button>
                        <button type="button" class="btn btn-sm btn-light-secondary" onclick="deselectAllMissing()">
                            <i class="ph ph-square me-1"></i> Deseleziona Tutto
                        </button>
                        <button type="button" class="btn btn-sm btn-success" onclick="createSelectedKeys()">
                            <i class="ph ph-plus-circle me-1"></i> Crea Selezionate
                        </button>
                    </div>
                    <div id="missing-list" class="max-height-400 overflow-auto">
                        <!-- Contenuto dinamico -->
                    </div>
                </div>
                <div id="missing-error" class="alert alert-danger" style="display: none;">
                    <i class="ph ph-warning me-2"></i>
                    <span id="missing-error-message"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Chiudi</button>
            </div>
        </div>
    </div>
</div>

<!-- Focus Mode Overlay -->
<div id="focus-mode-overlay" class="d-none">
    <div class="focus-mode-container">
        <div class="focus-mode-header">
            <div class="d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="ph ph-eye me-2"></i>
                    Modalità Focus
                </h4>
                <button class="btn btn-sm btn-outline-light" onclick="toggleFocusMode()">
                    <i class="ph ph-x"></i>
                </button>
            </div>
            <div class="mt-2">
                <span class="badge bg-light text-dark" id="focus-progress">0 / 0</span>
            </div>
        </div>
        
        <div class="focus-mode-body" id="focus-mode-content">
            <!-- Contenuto dinamico -->
        </div>
        
        <div class="focus-mode-footer">
            <button class="btn btn-secondary" onclick="focusPrevious()">
                <i class="ph ph-caret-left me-1"></i>
                Precedente
            </button>
            <button class="btn btn-success" onclick="focusMarkReviewed()">
                <i class="ph ph-check-circle me-1"></i>
                Segna e Avanti
            </button>
            <button class="btn btn-primary" onclick="focusNext()">
                Successivo
                <i class="ph ph-caret-right ms-1"></i>
            </button>
        </div>
    </div>
</div>

<style>
.translation-item {
    transition: all 0.2s ease;
}

.translation-item:hover {
    background-color: rgba(0, 123, 255, 0.05) !important;
}

.translation-input {
    font-family: 'Courier New', monospace;
    resize: vertical;
}

.translation-input:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.reference-content pre {
    white-space: pre-wrap;
    word-wrap: break-word;
}

#focus-mode-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.95);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.focus-mode-container {
    background: white;
    border-radius: 12px;
    max-width: 900px;
    width: 100%;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
}

.focus-mode-header {
    padding: 20px;
    border-bottom: 1px solid #dee2e6;
}

.focus-mode-body {
    flex: 1;
    padding: 30px;
    overflow-y: auto;
}

.focus-mode-footer {
    padding: 20px;
    border-top: 1px solid #dee2e6;
    display: flex;
    justify-content: space-between;
    gap: 10px;
}

.bg-light-warning {
    background-color: rgba(255, 193, 7, 0.1) !important;
}

.bg-light-success {
    background-color: rgba(25, 135, 84, 0.1) !important;
}

.bg-light-info {
    background-color: rgba(13, 202, 240, 0.1) !important;
}

.text-purple {
    color: #6f42c1 !important;
}

.bg-light-purple {
    background-color: rgba(111, 66, 193, 0.1) !important;
}

/* Keyboard Shortcuts Bar */
.keyboard-shortcuts-bar {
    background: #f8f9fa !important;
    border: 1px solid #dee2e6 !important;
    border-radius: 8px !important;
    padding: 12px 16px !important;
    margin-bottom: 1rem !important;
}

.keyboard-shortcuts-bar .text-dark {
    color: #212529 !important;
    font-weight: 600 !important;
}

.shortcut-item {
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.shortcut-key {
    background-color: #ffffff !important;
    border: 1px solid #adb5bd !important;
    border-radius: 4px !important;
    padding: 3px 6px !important;
    font-size: 11px !important;
    font-family: 'SF Mono', Monaco, 'Cascadia Code', 'Roboto Mono', Consolas, 'Courier New', monospace !important;
    color: #495057 !important;
    font-weight: 600 !important;
    box-shadow: 0 1px 2px rgba(0,0,0,0.05) !important;
}

.shortcut-text {
    color: #495057 !important;
    font-size: 13px !important;
    font-weight: 500 !important;
}

.shortcut-separator {
    color: #6c757d !important;
    font-weight: bold !important;
    margin: 0 4px !important;
}

/* Mobile optimization */
@media (max-width: 768px) {
    .keyboard-shortcuts-bar {
        padding: 10px 12px !important;
    }
    
    .keyboard-shortcuts-bar .d-flex {
        flex-direction: column !important;
        align-items: flex-start !important;
        gap: 8px !important;
    }
    
    .shortcut-key {
        font-size: 10px !important;
        padding: 2px 4px !important;
    }
    
    .shortcut-text {
        font-size: 12px !important;
    }
}

kbd {
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 3px;
    padding: 2px 6px;
    font-size: 11px;
    font-family: monospace;
}

.toggle-reference {
    text-decoration: none !important;
    color: #6c757d;
}

.toggle-reference:hover {
    color: #0d6efd;
}

@media (max-width: 768px) {
    .focus-mode-container {
        max-width: 100%;
        max-height: 100vh;
        border-radius: 0;
    }
    
    .focus-mode-footer {
        flex-direction: column;
    }
}
</style>

<script>
// Configurazione
const CONFIG = {
    language: '{{ $language }}',
    file: '{{ $selectedFile }}',
    autoSaveDelay: 2000, // 2 secondi
    csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content')
};

// Stato dell'applicazione
let appState = {
    currentFilter: 'all',
    currentSort: 'key-asc',
    searchQuery: '',
    focusMode: false,
    focusIndex: 0,
    focusItems: [],
    autoSaveTimers: {},
    unsavedChanges: new Set()
};

// Inizializzazione
document.addEventListener('DOMContentLoaded', function() {
    initializeKeyboardShortcuts();
    initializeAutoSave();
    console.log('Smart Translation System initialized');
});

// === FILTRI E RICERCA ===
function applyFilters() {
    const filter = document.getElementById('filterStatus').value;
    const search = document.getElementById('searchInput').value.toLowerCase();
    const sort = document.getElementById('sortOrder').value;
    
    appState.currentFilter = filter;
    appState.searchQuery = search;
    appState.currentSort = sort;
    
    const items = Array.from(document.querySelectorAll('.translation-item'));
    let visibleCount = 0;
    
    // Applica filtri
    items.forEach(item => {
        const key = item.dataset.key.toLowerCase();
        const status = item.dataset.status;
        const reviewed = item.dataset.reviewed === 'true';
        const text = item.textContent.toLowerCase();
        
        let show = true;
        
        // Filtro per stato
        if (filter === 'missing' && status !== 'missing') show = false;
        if (filter === 'translated' && status !== 'translated') show = false;
        if (filter === 'reviewed' && !reviewed) show = false;
        if (filter === 'not-reviewed' && reviewed) show = false;
        
        // Filtro per ricerca
        if (search && !key.includes(search) && !text.includes(search)) show = false;
        
        item.style.display = show ? 'block' : 'none';
        if (show) visibleCount++;
    });
    
    // Applica ordinamento
    sortItems(items, sort);
    
    // Aggiorna contatore
    document.getElementById('visible-count').textContent = visibleCount;
}

function sortItems(items, sortType) {
    const container = document.getElementById('translations-list');
    const visibleItems = items.filter(item => item.style.display !== 'none');
    
    visibleItems.sort((a, b) => {
        const keyA = a.dataset.key;
        const keyB = b.dataset.key;
        
        switch(sortType) {
            case 'key-asc':
                return keyA.localeCompare(keyB);
            case 'key-desc':
                return keyB.localeCompare(keyA);
            case 'status':
                const statusOrder = { 'missing': 0, 'empty': 1, 'translated': 2 };
                const statusA = statusOrder[a.dataset.status] || 3;
                const statusB = statusOrder[b.dataset.status] || 3;
                return statusA - statusB;
            default:
                return 0;
        }
    });
    
    visibleItems.forEach(item => container.appendChild(item));
}

// === AUTO-SAVE ===
function initializeAutoSave() {
    document.querySelectorAll('.translation-input').forEach(input => {
        input.addEventListener('input', function() {
            const key = this.dataset.key;
            appState.unsavedChanges.add(key);
            
            // Clear existing timer
            if (appState.autoSaveTimers[key]) {
                clearTimeout(appState.autoSaveTimers[key]);
            }
            
            // Set new timer
            appState.autoSaveTimers[key] = setTimeout(() => {
                autoSaveTranslation(key, this.value);
            }, CONFIG.autoSaveDelay);
        });
    });
}

function autoSaveTranslation(key, value) {
    fetch('{{ route("admin.translations.auto-save") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': CONFIG.csrfToken,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            language: CONFIG.language,
            file: CONFIG.file,
            key: key,
            value: value
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            appState.unsavedChanges.delete(key);
            showAutoSaveIndicator();
            updateStats();
        }
    })
    .catch(error => {
        console.error('Auto-save error:', error);
    });
}

function showAutoSaveIndicator() {
    const indicator = document.getElementById('autosave-indicator');
    indicator.classList.remove('d-none');
    setTimeout(() => {
        indicator.classList.add('d-none');
    }, 2000);
}

// === REVISIONE ===
function toggleReviewed(key, button) {
    const item = button.closest('.translation-item');
    const isReviewed = item.dataset.reviewed === 'true';
    const url = isReviewed 
        ? '{{ route("admin.translations.unmark-reviewed") }}'
        : '{{ route("admin.translations.mark-reviewed") }}';
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': CONFIG.csrfToken,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            language: CONFIG.language,
            file: CONFIG.file,
            key: key
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Aggiorna UI
            item.dataset.reviewed = !isReviewed;
            
            if (!isReviewed) {
                item.classList.add('bg-light-success');
                button.classList.remove('btn-outline-success');
                button.classList.add('btn-success');
                button.querySelector('i').classList.remove('ph-circle');
                button.querySelector('i').classList.add('ph-check-circle');
            } else {
                item.classList.remove('bg-light-success');
                button.classList.remove('btn-success');
                button.classList.add('btn-outline-success');
                button.querySelector('i').classList.remove('ph-check-circle');
                button.querySelector('i').classList.add('ph-circle');
            }
            
            updateStats();
            
            Swal.fire({
                icon: 'success',
                title: isReviewed ? 'Revisione rimossa' : 'Segnata come revisionata',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1500
            });
        }
    })
    .catch(error => {
        console.error('Toggle reviewed error:', error);
    });
}

// === UTILITÀ ===
function copyFromItalian(key) {
    const item = document.querySelector(`.translation-item[data-key="${key}"]`);
    const referenceText = item.querySelector('.reference-content pre').textContent;
    const textarea = item.querySelector('.translation-input');
    textarea.value = referenceText;
    textarea.dispatchEvent(new Event('input'));
}

function toggleReference(button) {
    const content = button.closest('.reference-text').querySelector('.reference-content');
    const icon = button.querySelector('i');
    
    if (content.style.display === 'none') {
        content.style.display = 'block';
        icon.classList.remove('ph-caret-down');
        icon.classList.add('ph-caret-up');
    } else {
        content.style.display = 'none';
        icon.classList.remove('ph-caret-up');
        icon.classList.add('ph-caret-down');
    }
}

function expandTextarea(button) {
    const textarea = button.closest('.translation-item').querySelector('.translation-input');
    const icon = button.querySelector('i');
    
    if (textarea.rows === 2) {
        textarea.rows = 10;
        icon.classList.remove('ph-arrows-out-simple');
        icon.classList.add('ph-arrows-in-simple');
    } else {
        textarea.rows = 2;
        icon.classList.remove('ph-arrows-in-simple');
        icon.classList.add('ph-arrows-out-simple');
    }
}

function findKeyUsage(key) {
    // Mostra la modale
    const modal = new bootstrap.Modal(document.getElementById('usageModal'));
    document.getElementById('usage-key-name').textContent = key;
    
    // Mostra loading
    document.getElementById('usage-loading').style.display = 'block';
    document.getElementById('usage-content').style.display = 'none';
    document.getElementById('usage-error').style.display = 'none';
    
    modal.show();
    
    // Cerca l'utilizzo
    fetch('{{ route("admin.translations.find-usage") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': CONFIG.csrfToken,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            key: key,
            file: CONFIG.file
        })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('usage-loading').style.display = 'none';
        
        if (data.success) {
            document.getElementById('usage-count').textContent = data.count;
            document.getElementById('usage-content').style.display = 'block';
            
            const usageList = document.getElementById('usage-list');
            
            if (data.usage.length === 0) {
                usageList.innerHTML = `
                    <div class="alert alert-warning">
                        <i class="ph ph-warning me-2"></i>
                        Questa chiave non sembra essere utilizzata nel codice.
                    </div>
                `;
            } else {
                usageList.innerHTML = data.usage.map(usage => `
                    <div class="card mb-3">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge ${usage.type === 'blade' ? 'bg-info' : 'bg-primary'}">
                                    ${usage.type.toUpperCase()}
                                </span>
                                <code class="text-primary">${usage.file}</code>
                            </div>
                            <span class="badge bg-light-secondary">Linea ${usage.line}</span>
                        </div>
                        <div class="card-body">
                            <pre class="mb-0" style="background: #f8f9fa; padding: 10px; border-radius: 4px; font-size: 12px;">${usage.context.map(line => 
                                `<span class="${line.highlight ? 'bg-warning' : ''}" style="display: block; padding: 2px 0;">${String(line.number).padStart(3, ' ')}: ${escapeHtml(line.content)}</span>`
                            ).join('')}</pre>
                        </div>
                    </div>
                `).join('');
            }
        } else {
            document.getElementById('usage-error').style.display = 'block';
            document.getElementById('usage-error-message').textContent = data.message;
        }
    })
    .catch(error => {
        document.getElementById('usage-loading').style.display = 'none';
        document.getElementById('usage-error').style.display = 'block';
        document.getElementById('usage-error-message').textContent = 'Errore durante la ricerca: ' + error.message;
    });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function findUnusedKeys() {
    // Mostra la modale
    const modal = new bootstrap.Modal(document.getElementById('unusedKeysModal'));
    document.getElementById('unused-file-name').textContent = CONFIG.file;
    
    // Mostra loading
    document.getElementById('unused-loading').style.display = 'block';
    document.getElementById('unused-content').style.display = 'none';
    document.getElementById('unused-error').style.display = 'none';
    
    modal.show();
    
    // Cerca le chiavi non utilizzate
    fetch('{{ route("admin.translations.find-unused") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': CONFIG.csrfToken,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            file: CONFIG.file
        })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('unused-loading').style.display = 'none';
        
        if (data.success) {
            document.getElementById('unused-count').textContent = data.count;
            document.getElementById('total-count').textContent = data.total_keys;
            document.getElementById('unused-content').style.display = 'block';
            
            const unusedList = document.getElementById('unused-list');
            
            if (data.unused_keys.length === 0) {
                unusedList.innerHTML = `
                    <div class="alert alert-success">
                        <i class="ph ph-check-circle me-2"></i>
                        Perfetto! Non ci sono chiavi non utilizzate in questo file.
                    </div>
                `;
            } else {
                unusedList.innerHTML = data.unused_keys.map(key => `
                    <div class="card mb-2">
                        <div class="card-body p-3">
                            <div class="form-check">
                                <input class="form-check-input unused-key-checkbox" type="checkbox" value="${key.key}" id="unused-${key.key}">
                                <label class="form-check-label w-100" for="unused-${key.key}">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <code class="text-primary f-s-12">${key.key}</code>
                                            <div class="text-muted f-s-11 mt-1">${escapeHtml(key.value).substring(0, 100)}${key.value.length > 100 ? '...' : ''}</div>
                                        </div>
                                        <span class="badge bg-light-warning">Non utilizzata</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                `).join('');
            }
        } else {
            document.getElementById('unused-error').style.display = 'block';
            document.getElementById('unused-error-message').textContent = data.message;
        }
    })
    .catch(error => {
        document.getElementById('unused-loading').style.display = 'none';
        document.getElementById('unused-error').style.display = 'block';
        document.getElementById('unused-error-message').textContent = 'Errore durante l\'analisi: ' + error.message;
    });
}

function selectAllUnused() {
    document.querySelectorAll('.unused-key-checkbox').forEach(checkbox => {
        checkbox.checked = true;
    });
}

function deselectAllUnused() {
    document.querySelectorAll('.unused-key-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
}

function removeSelectedKeys() {
    const selectedKeys = Array.from(document.querySelectorAll('.unused-key-checkbox:checked'))
        .map(checkbox => checkbox.value);
    
    if (selectedKeys.length === 0) {
        Swal.fire({
            title: 'Nessuna selezione',
            text: 'Seleziona almeno una chiave da rimuovere.',
            icon: 'warning'
        });
        return;
    }
    
    Swal.fire({
        title: 'Conferma rimozione',
        text: `Sei sicuro di voler rimuovere ${selectedKeys.length} chiavi non utilizzate?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sì, rimuovi',
        cancelButtonText: 'Annulla',
        confirmButtonColor: '#dc3545'
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostra loading
            Swal.fire({
                title: 'Rimozione in corso...',
                text: 'Sto rimuovendo le chiavi selezionate.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            fetch('{{ route("admin.translations.remove-unused") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': CONFIG.csrfToken,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    file: CONFIG.file,
                    keys: selectedKeys
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Successo!',
                        text: `${data.removed_count} chiavi rimosse con successo.`,
                        icon: 'success'
                    }).then(() => {
                        // Chiudi la modale e ricarica la pagina
                        bootstrap.Modal.getInstance(document.getElementById('unusedKeysModal')).hide();
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Errore',
                        text: data.message,
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Errore',
                    text: 'Errore durante la rimozione: ' + error.message,
                    icon: 'error'
                });
            });
        }
    });
}

function expandAll() {
    document.querySelectorAll('.reference-content').forEach(content => {
        content.style.display = 'block';
    });
    document.querySelectorAll('.toggle-reference i').forEach(icon => {
        icon.classList.remove('ph-caret-down');
        icon.classList.add('ph-caret-up');
    });
}

function collapseAll() {
    document.querySelectorAll('.reference-content').forEach(content => {
        content.style.display = 'none';
    });
    document.querySelectorAll('.toggle-reference i').forEach(icon => {
        icon.classList.remove('ph-caret-up');
        icon.classList.add('ph-caret-down');
    });
}

function changeFile() {
    const file = document.getElementById('fileSelector').value;
    const url = new URL(window.location);
    url.searchParams.set('file', file);
    window.location.href = url.toString();
}

function saveAllTranslations() {
    const translations = {};
    document.querySelectorAll('.translation-input').forEach(input => {
        const key = input.dataset.key;
        const value = input.value.trim();
        if (key && value) {
            translations[key] = value;
        }
    });
    
    fetch('{{ route("admin.translations.update", $language) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': CONFIG.csrfToken,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            file: CONFIG.file,
            translations: translations
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: 'Salvato!',
                text: 'Tutte le traduzioni sono state salvate con successo',
                timer: 2000,
                showConfirmButton: false
            });
            appState.unsavedChanges.clear();
        }
    })
    .catch(error => {
        Swal.fire({
            icon: 'error',
            title: 'Errore',
            text: 'Si è verificato un errore durante il salvataggio'
        });
    });
}

function updateStats() {
    fetch(`{{ route("admin.translations.detailed-stats") }}?language=${CONFIG.language}&file=${CONFIG.file}`)
        .then(response => response.json())
        .then(stats => {
            document.getElementById('stat-total').textContent = stats.total_keys;
            document.getElementById('stat-translated').textContent = stats.translated_keys;
            document.getElementById('stat-missing').textContent = stats.missing_keys;
            document.getElementById('stat-reviewed').textContent = stats.reviewed_keys;
            document.getElementById('stat-progress').textContent = stats.progress_percentage + '%';
            document.getElementById('stat-review-progress').textContent = stats.review_percentage + '%';
            
            document.getElementById('progress-bar').style.width = stats.progress_percentage + '%';
            document.getElementById('progress-badge').textContent = stats.progress_percentage + '%';
            document.getElementById('review-bar').style.width = stats.review_percentage + '%';
            document.getElementById('review-badge').textContent = stats.review_percentage + '%';
        });
}

// === FOCUS MODE ===
function toggleFocusMode() {
    appState.focusMode = !appState.focusMode;
    const overlay = document.getElementById('focus-mode-overlay');
    
    if (appState.focusMode) {
        // Raccogli gli elementi visibili
        appState.focusItems = Array.from(document.querySelectorAll('.translation-item'))
            .filter(item => item.style.display !== 'none');
        appState.focusIndex = 0;
        
        overlay.classList.remove('d-none');
        showFocusItem(0);
        document.getElementById('focus-mode-text').textContent = 'Esci Focus';
    } else {
        overlay.classList.add('d-none');
        document.getElementById('focus-mode-text').textContent = 'Modalità Focus';
    }
}

function showFocusItem(index) {
    if (index < 0 || index >= appState.focusItems.length) return;
    
    const item = appState.focusItems[index];
    const key = item.dataset.key;
    const reference = item.querySelector('.reference-content pre').textContent;
    const translation = item.querySelector('.translation-input').value;
    const isReviewed = item.dataset.reviewed === 'true';
    
    const content = document.getElementById('focus-mode-content');
    content.innerHTML = `
        <div class="mb-4">
            <h5 class="mb-3">
                <code class="text-primary">${key}</code>
            </h5>
            
            <div class="mb-4">
                <label class="form-label fw-semibold">
                    <i class="ph ph-flag-italy me-1"></i>
                    Italiano (Riferimento)
                </label>
                <div class="bg-light p-3 rounded">
                    <pre class="mb-0 f-s-14">${reference}</pre>
                </div>
            </div>
            
            <div>
                <label class="form-label fw-semibold">
                    <i class="ph ph-translate me-1"></i>
                    Traduzione
                </label>
                <textarea class="form-control" id="focus-translation" rows="6" 
                          style="font-size: 14px;">${translation}</textarea>
            </div>
        </div>
    `;
    
    document.getElementById('focus-progress').textContent = `${index + 1} / ${appState.focusItems.length}`;
    
    // Focus sul textarea
    setTimeout(() => {
        document.getElementById('focus-translation').focus();
    }, 100);
}

function focusNext() {
    if (appState.focusIndex < appState.focusItems.length - 1) {
        // Salva la traduzione corrente
        saveFocusTranslation();
        appState.focusIndex++;
        showFocusItem(appState.focusIndex);
    } else {
        Swal.fire({
            icon: 'success',
            title: 'Completato!',
            text: 'Hai raggiunto la fine delle traduzioni',
            confirmButtonText: 'OK'
        }).then(() => {
            toggleFocusMode();
        });
    }
}

function focusPrevious() {
    if (appState.focusIndex > 0) {
        saveFocusTranslation();
        appState.focusIndex--;
        showFocusItem(appState.focusIndex);
    }
}

function focusMarkReviewed() {
    saveFocusTranslation();
    
    const item = appState.focusItems[appState.focusIndex];
    const key = item.dataset.key;
    
    toggleReviewed(key, item.querySelector('.mark-reviewed-btn'));
    
    // Vai al successivo
    setTimeout(() => {
        focusNext();
    }, 500);
}

function saveFocusTranslation() {
    const textarea = document.getElementById('focus-translation');
    if (!textarea) return;
    
    const item = appState.focusItems[appState.focusIndex];
    const key = item.dataset.key;
    const value = textarea.value;
    
    // Aggiorna il valore nella lista principale
    const mainTextarea = item.querySelector('.translation-input');
    mainTextarea.value = value;
    mainTextarea.dispatchEvent(new Event('input'));
}

// === KEYBOARD SHORTCUTS ===
function initializeKeyboardShortcuts() {
    document.addEventListener('keydown', function(e) {
        // Ctrl/Cmd + S: Salva tutto
        if ((e.ctrlKey || e.metaKey) && e.key === 's') {
            e.preventDefault();
            saveAllTranslations();
        }
        
        // Ctrl/Cmd + F: Focus sulla ricerca
        if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
            e.preventDefault();
            document.getElementById('searchInput').focus();
        }
        
        // Ctrl/Cmd + R: Segna come revisionata (solo in focus mode)
        if ((e.ctrlKey || e.metaKey) && e.key === 'r' && appState.focusMode) {
            e.preventDefault();
            focusMarkReviewed();
        }
        
        // Freccia su/giù in focus mode
        if (appState.focusMode) {
            if (e.key === 'ArrowUp' && e.altKey) {
                e.preventDefault();
                focusPrevious();
            }
            if (e.key === 'ArrowDown' && e.altKey) {
                e.preventDefault();
                focusNext();
            }
        }
        
        // Esc: Esci da focus mode
        if (e.key === 'Escape' && appState.focusMode) {
            toggleFocusMode();
        }
    });
}

function findMissingKeys() {
    // Mostra la modale
    const modal = new bootstrap.Modal(document.getElementById('missingKeysModal'));
    document.getElementById('missing-file-name').textContent = 'Tutti i file';
    
    // Mostra loading
    document.getElementById('missing-loading').style.display = 'block';
    document.getElementById('missing-content').style.display = 'none';
    document.getElementById('missing-error').style.display = 'none';
    
    modal.show();
    
    // Cerca le chiavi mancanti in TUTTI i file
    fetch('{{ route("admin.translations.find-all-missing") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': CONFIG.csrfToken,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            // Non inviamo il file per cercare in tutti i file
        })
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('missing-loading').style.display = 'none';
        
        if (data.success) {
            document.getElementById('missing-count').textContent = data.count;
            document.getElementById('missing-content').style.display = 'block';
            
            const missingList = document.getElementById('missing-list');
            
            if (data.missing_keys.length === 0) {
                missingList.innerHTML = `
                    <div class="alert alert-success">
                        <i class="ph ph-check-circle me-2"></i>
                        Perfetto! Non ci sono chiavi mancanti in questo file.
                    </div>
                `;
            } else {
                missingList.innerHTML = data.missing_keys.map(key => `
                    <div class="card mb-2">
                        <div class="card-body p-3">
                            <div class="form-check">
                                <input class="form-check-input missing-key-checkbox" type="checkbox" value="${key.key}" data-file="${key.file}" id="missing-${key.file}-${key.key}">
                                <label class="form-check-label w-100" for="missing-${key.file}-${key.key}">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <div class="mb-1">
                                                <span class="badge bg-light-secondary f-s-10">${key.file}.php</span>
                                                <code class="text-primary f-s-12 ms-2">${key.key}</code>
                                            </div>
                                            <div class="mt-2">
                                                <label class="form-label f-s-11">Valore suggerito:</label>
                                                <input type="text" class="form-control form-control-sm missing-key-value" 
                                                       value="${escapeHtml(key.suggested_value)}" 
                                                       data-key="${key.key}"
                                                       data-file="${key.file}">
                                            </div>
                                        </div>
                                        <span class="badge bg-light-info">Mancante</span>
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                `).join('');
            }
        } else {
            document.getElementById('missing-error').style.display = 'block';
            document.getElementById('missing-error-message').textContent = data.message;
        }
    })
    .catch(error => {
        document.getElementById('missing-loading').style.display = 'none';
        document.getElementById('missing-error').style.display = 'block';
        document.getElementById('missing-error-message').textContent = 'Errore durante l\'analisi: ' + error.message;
    });
}

function selectAllMissing() {
    document.querySelectorAll('.missing-key-checkbox').forEach(checkbox => {
        checkbox.checked = true;
    });
}

function deselectAllMissing() {
    document.querySelectorAll('.missing-key-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
}

function createSelectedKeys() {
    const selectedKeys = Array.from(document.querySelectorAll('.missing-key-checkbox:checked'))
        .map(checkbox => {
            const key = checkbox.value;
            const file = checkbox.getAttribute('data-file');
            const valueInput = document.querySelector(`input[data-key="${key}"][data-file="${file}"]`);
            return {
                key: key,
                file: file,
                value: valueInput ? valueInput.value : ''
            };
        });
    
    if (selectedKeys.length === 0) {
        Swal.fire({
            title: 'Nessuna selezione',
            text: 'Seleziona almeno una chiave da creare.',
            icon: 'warning'
        });
        return;
    }
    
    // Verifica che tutti i valori siano compilati
    const emptyValues = selectedKeys.filter(item => !item.value.trim());
    if (emptyValues.length > 0) {
        Swal.fire({
            title: 'Valori mancanti',
            text: 'Compila tutti i valori delle chiavi selezionate.',
            icon: 'warning'
        });
        return;
    }
    
    Swal.fire({
        title: 'Conferma creazione',
        text: `Sei sicuro di voler creare ${selectedKeys.length} nuove chiavi?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sì, crea',
        cancelButtonText: 'Annulla',
        confirmButtonColor: '#28a745'
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostra loading
            Swal.fire({
                title: 'Creazione in corso...',
                text: 'Sto creando le chiavi selezionate.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
            
            // Crea le chiavi una per una
            let createdCount = 0;
            let errorCount = 0;
            
            const createNextKey = (index) => {
                if (index >= selectedKeys.length) {
                    // Tutte le chiavi sono state processate
                    if (errorCount === 0) {
                        Swal.fire({
                            title: 'Successo!',
                            text: `${createdCount} chiavi create con successo.`,
                            icon: 'success'
                        }).then(() => {
                            // Chiudi la modale e ricarica la pagina
                            bootstrap.Modal.getInstance(document.getElementById('missingKeysModal')).hide();
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'Completato con errori',
                            text: `${createdCount} chiavi create, ${errorCount} errori.`,
                            icon: 'warning'
                        });
                    }
                    return;
                }
                
                const keyData = selectedKeys[index];
                
                fetch('{{ route("admin.translations.create-key") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': CONFIG.csrfToken,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        file: keyData.file,
                        key: keyData.key,
                        value: keyData.value
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        createdCount++;
                    } else {
                        errorCount++;
                        console.error('Errore creando chiave:', keyData.key, data.message);
                    }
                    createNextKey(index + 1);
                })
                .catch(error => {
                    errorCount++;
                    console.error('Errore creando chiave:', keyData.key, error);
                    createNextKey(index + 1);
                });
            };
            
            createNextKey(0);
        }
    });
}
</script>
@endsection

