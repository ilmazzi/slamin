@extends('layout.master')

@section('title', "Traduzioni {$language}/{$file}")

@section('main-content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">
                <i class="ph ph-pencil me-2"></i>
                Modifica Traduzioni
            </h4>
            <ul class="app-line-breadcrumbs mb-2">
                <li class="">
                    <a href="{{ route('dashboard') }}" class="f-s-14 f-w-500">
                        <span>
                            <i class="ph-duotone ph-house f-s-16"></i> Dashboard
                        </span>
                    </a>
                </li>
                <li class="">
                    <a href="{{ route('admin.translations.index') }}" class="f-s-14 f-w-500">
                        <span>
                            <i class="ph-duotone ph-translate f-s-16"></i> {{ __('admin.translation_management') }}
                        </span>
                    </a>
                </li>
                <li class="active">
                    <a href="#" class="f-s-14 f-w-500">{{ strtoupper($language) }} - {{ $file }}.php</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Header compatto -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center p-2 bg-light-secondary rounded">
                <div class="d-flex align-items-center gap-3">
                    <small class="text-muted">
                        <i class="ph ph-flag me-1"></i>{{ $language === 'it' ? 'Italiano' : strtoupper($language) }}
                    </small>
                    <small class="text-muted">
                        <i class="ph ph-file me-1"></i>{{ $file }}.php
                    </small>
                    @if(isset($missingKeys) && count($missingKeys) > 0)
                        <span class="badge bg-warning text-dark f-s-11">
                            <i class="ph ph-warning me-1"></i>{{ count($missingKeys) }} mancanti
                        </span>
                    @endif
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-light-primary btn-sm" onclick="copyFromReference()">
                        <i class="ph ph-copy me-1"></i>Copia
                    </button>
                    <button type="button" class="btn btn-light-danger btn-sm" onclick="clearAllTranslations()">
                        <i class="ph ph-trash me-1"></i>Svuota
                    </button>
                    <button type="button" class="btn btn-primary btn-sm" onclick="showUntranslated()">
                        <i class="ph ph-eye me-1"></i>Non Tradotte
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress bar compatta -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <small class="text-muted">Progresso</small>
                <small class="text-muted" id="progressText">
                    {{ count($referenceTranslations) > 0 ? round((count(array_filter($translations, function($value) { return !empty($value); })) / count($referenceTranslations)) * 100) : 0 }}%
                </small>
            </div>
            <div class="progress" style="height: 4px;">
                <div class="progress-bar bg-success" id="progressBar"
                     style="width: {{ count($referenceTranslations) > 0 ? (count(array_filter($translations, function($value) { return !empty($value); })) / count($referenceTranslations)) * 100 : 0 }}%">
                </div>
            </div>
        </div>
    </div>

    <!-- Translation Form -->
    <form id="translationForm" action="{{ route('admin.translations.update', [$language, $file]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent py-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0 f-s-14">
                                <i class="ph ph-list me-1"></i>Chiavi di Traduzione
                            </h6>
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-light-primary btn-sm" onclick="toggleAllTranslations()">
                                    <i class="ph ph-eye me-1"></i>Mostra/Nascondi
                                </button>
                                <a href="{{ route('admin.translations.index') }}" class="btn btn-light-secondary btn-sm">
                                    <i class="ph ph-arrow-left me-1"></i>Indietro
                                </a>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="ph ph-floppy-disk me-1"></i>Salva
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="accordion app-accordion accordion-primary" id="translationAccordion">
                            @foreach($referenceTranslations as $key => $referenceValue)
                            @php
                                $isMissing = isset($missingKeys) && in_array($key, $missingKeys);
                                $isTranslated = !empty($translations[$key]) && !$isMissing;
                            @endphp
                            <div class="accordion-item border-0 border-bottom">
                                <h2 class="accordion-header">
                                    <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }} py-2" type="button" 
                                            data-bs-toggle="collapse" data-bs-target="#collapse{{ $loop->index }}"
                                            aria-expanded="{{ $loop->first ? 'true' : 'false' }}" 
                                            aria-controls="collapse{{ $loop->index }}">
                                        <div class="d-flex justify-content-between align-items-center w-100 me-2">
                                            <span class="fw-semibold f-s-13 {{ $isMissing ? 'text-warning' : '' }}">{{ $key }}</span>
                                            <span class="badge {{ $isMissing ? 'bg-warning text-dark' : ($isTranslated ? 'bg-light-success text-success' : 'bg-light-primary text-primary') }} f-s-10">
                                                {{ $isMissing ? 'Mancante' : ($isTranslated ? 'Tradotta' : 'Da tradurre') }}
                                            </span>
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapse{{ $loop->index }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                                     data-bs-parent="#translationAccordion">
                                    <div class="accordion-body py-2">
                                        <div class="row g-2">
                                            <div class="col-md-6">
                                                <label class="form-label f-s-11 f-w-500 mb-1">
                                                    <i class="ph ph-flag me-1"></i>Riferimento (Italiano)
                                                </label>
                                                <div class="form-control-plaintext bg-light-secondary p-2 rounded f-s-12" style="min-height: 40px;">
                                                    {{ $referenceValue }}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label f-s-11 f-w-500 mb-1">
                                                    <i class="ph ph-translate me-1"></i>Traduzione ({{ strtoupper($language) }})
                                                </label>
                                                <textarea name="translations[{{ $key }}]"
                                                          class="form-control f-s-12"
                                                          rows="2"
                                                          placeholder="Inserisci la traduzione..."
                                                          data-key="{{ $key }}"
                                                          data-reference="{{ $referenceValue }}"
                                                          onchange="updateProgress()">{{ $translations[$key] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <!-- Suggerimenti compatti -->
    <div class="row mt-3">
        <div class="col-12">
            <div class="alert alert-light-primary py-2">
                <div class="d-flex align-items-start">
                    <i class="ph ph-lightbulb me-2 mt-1 text-primary f-s-14"></i>
                    <div class="f-s-12">
                        <strong class="text-primary">Suggerimenti:</strong> Usa il testo italiano come riferimento, mantieni la stessa lunghezza quando possibile, controlla grammatica e ortografia.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let allExpanded = true;

function toggleAllTranslations() {
    const accordionButtons = document.querySelectorAll('.accordion-button');
    const toggleButton = event.target.closest('button');
    
    if (allExpanded) {
        // Collapse all
        accordionButtons.forEach(button => {
            if (!button.classList.contains('collapsed')) {
                button.click();
            }
        });
        toggleButton.innerHTML = '<i class="ph ph-eye me-1"></i>Mostra Tutte';
    } else {
        // Expand all
        accordionButtons.forEach(button => {
            if (button.classList.contains('collapsed')) {
                button.click();
            }
        });
        toggleButton.innerHTML = '<i class="ph ph-eye me-1"></i>Nascondi Tutte';
    }
    
    allExpanded = !allExpanded;
}

function copyFromReference() {
    if (confirm('Vuoi copiare tutte le traduzioni dall\'italiano? Questo sovrascriverà le traduzioni esistenti.')) {
        const textareas = document.querySelectorAll('textarea[name^="translations"]');
        textareas.forEach(textarea => {
            const reference = textarea.getAttribute('data-reference');
            textarea.value = reference;
        });
        updateProgress();
        showNotification('Traduzioni copiate dall\'italiano', 'success');
    }
}

function clearAllTranslations() {
    if (confirm('Vuoi svuotare tutte le traduzioni? Questa azione non può essere annullata.')) {
        const textareas = document.querySelectorAll('textarea[name^="translations"]');
        textareas.forEach(textarea => {
            textarea.value = '';
        });
        updateProgress();
        showNotification('Tutte le traduzioni sono state svuotate', 'warning');
    }
}

function showUntranslated() {
    const accordionButtons = document.querySelectorAll('.accordion-button');
    const textareas = document.querySelectorAll('textarea[name^="translations"]');
    let untranslatedCount = 0;
    
    // First collapse all
    accordionButtons.forEach(button => {
        if (!button.classList.contains('collapsed')) {
            button.click();
        }
    });
    
    // Wait a bit for collapse animation, then expand untranslated ones
    setTimeout(() => {
        textareas.forEach((textarea, index) => {
            const button = accordionButtons[index];
            const badge = button.querySelector('.badge');
            
            // Check if it's missing or empty
            if (!textarea.value.trim() || (badge && badge.textContent.includes('Mancante'))) {
                if (button.classList.contains('collapsed')) {
                    button.click();
                    untranslatedCount++;
                }
            }
        });
        
        if (untranslatedCount > 0) {
            showNotification(`Mostrate ${untranslatedCount} chiavi non tradotte/mancanti`, 'info');
        } else {
            showNotification('Tutte le chiavi sono già tradotte!', 'success');
        }
    }, 300);
}

function updateProgress() {
    const textareas = document.querySelectorAll('textarea[name^="translations"]');
    const totalKeys = textareas.length;
    let translatedCount = 0;
    
    textareas.forEach(textarea => {
        if (textarea.value.trim()) {
            translatedCount++;
        }
    });
    
    const progress = totalKeys > 0 ? (translatedCount / totalKeys) * 100 : 0;
    
    // Update progress bar
    const progressBar = document.getElementById('progressBar');
    if (progressBar) {
        progressBar.style.width = progress + '%';
    }
    
    // Update counters
    const translatedCountElement = document.getElementById('translatedCount');
    if (translatedCountElement) {
        translatedCountElement.textContent = translatedCount;
    }
    
    const progressTextElement = document.getElementById('progressText');
    if (progressTextElement) {
        progressTextElement.textContent = Math.round(progress) + '%';
    }
    
    // Update badges
    textareas.forEach((textarea, index) => {
        const accordionButtons = document.querySelectorAll('.accordion-button');
        if (accordionButtons[index]) {
            const button = accordionButtons[index];
            const badge = button.querySelector('.badge');
            const keyName = button.querySelector('.fw-semibold').textContent;
            
            if (badge) {
                // Check if this key was originally missing
                const wasMissing = badge.textContent.includes('Mancante');
                
                if (textarea.value.trim()) {
                    if (wasMissing) {
                        badge.textContent = 'Tradotta';
                        badge.className = 'badge bg-light-success text-success f-s-10';
                        button.querySelector('.fw-semibold').classList.remove('text-warning');
                    } else {
                        badge.textContent = 'Tradotta';
                        badge.className = 'badge bg-light-success text-success f-s-10';
                    }
                } else {
                    if (wasMissing) {
                        badge.textContent = 'Mancante';
                        badge.className = 'badge bg-warning text-dark f-s-10';
                        button.querySelector('.fw-semibold').classList.add('text-warning');
                    } else {
                        badge.textContent = 'Da tradurre';
                        badge.className = 'badge bg-light-primary text-primary f-s-10';
                    }
                }
            }
        }
    });
}

function showNotification(message, type = 'info') {
    // Simple notification - you can replace with SweetAlert or other library
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'success' ? 'success' : type === 'warning' ? 'warning' : 'info'} alert-dismissible fade show position-fixed`;
    alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alertDiv);
    
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.remove();
        }
    }, 3000);
}

// Initialize progress on page load
document.addEventListener('DOMContentLoaded', function() {
    updateProgress();
});
</script>
@endsection
