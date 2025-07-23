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
            <ul class="app-line-breadcrumbs mb-3">
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

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-2">
                        <i class="ph ph-pencil me-2"></i>Modifica Traduzioni
                    </h2>
                    <p class="text-muted mb-0">
                        Lingua: <strong>{{ $language === 'it' ? 'Italiano' : strtoupper($language) }}</strong> |
                        File: <strong>{{ $file }}.php</strong>
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.translations.index') }}" class="btn btn-light-secondary">
                        <i class="ph ph-arrow-left me-2"></i>Torna alla Lista
                    </a>
                    <button type="submit" form="translationForm" class="btn btn-primary">
                        <i class="ph ph-floppy-disk me-2"></i>Salva Traduzioni
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Translation Form -->
    <form id="translationForm" action="{{ route('admin.translations.update', [$language, $file]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Translation Keys -->
            <div class="col-lg-8">
                <div class="card hover-effect border-0 shadow-sm">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">
                                <i class="ph ph-list me-2"></i>Chiavi di Traduzione
                            </h5>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-light-secondary btn-sm" onclick="toggleAllTranslations()">
                                    <i class="ph ph-eye me-1"></i>Mostra/Nascondi Tutte
                                </button>
                                <button type="button" class="btn btn-light-secondary btn-sm" onclick="copyFromReference()">
                                    <i class="ph ph-copy me-1"></i>Copia da Italiano
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach($referenceTranslations as $key => $referenceValue)
                            <div class="col-12">
                                <div class="card card-light-primary border-0">
                                    <div class="card-header bg-transparent">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0 text-primary">{{ $key }}</h6>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox"
                                                       id="toggle_{{ $loop->index }}"
                                                       onchange="toggleTranslation({{ $loop->index }})" checked>
                                                <label class="form-check-label" for="toggle_{{ $loop->index }}">
                                                    <small>Mostra</small>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body" id="translation_{{ $loop->index }}">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label class="form-label">
                                                    <i class="ph ph-flag me-1"></i>Riferimento (Italiano)
                                                </label>
                                                <div class="form-control-plaintext bg-light p-2 rounded">
                                                    {{ $referenceValue }}
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">
                                                    <i class="ph ph-translate me-1"></i>Traduzione ({{ strtoupper($language) }})
                                                </label>
                                                <textarea name="translations[{{ $key }}]"
                                                          class="form-control"
                                                          rows="2"
                                                          placeholder="Inserisci la traduzione..."
                                                          data-key="{{ $key }}"
                                                          data-reference="{{ $referenceValue }}">{{ $translations[$key] ?? '' }}</textarea>
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

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="card position-sticky" style="top: 20px;">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="ph ph-info me-2"></i>Informazioni
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6>Statistiche</h6>
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="text-center p-2 rounded bg-light">
                                        <div class="h4 mb-1 text-primary">{{ count($referenceTranslations) }}</div>
                                        <small class="text-muted">Chiavi Totali</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center p-2 rounded bg-light">
                                        <div class="h4 mb-1 text-success" id="translatedCount">
                                            {{ count(array_filter($translations, function($value) { return !empty($value); })) }}
                                        </div>
                                        <small class="text-muted">Tradotte</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <h6>Progresso</h6>
                            <div class="progress mb-2">
                                <div class="progress-bar bg-success" id="progressBar"
                                     style="width: {{ count($referenceTranslations) > 0 ? (count(array_filter($translations, function($value) { return !empty($value); })) / count($referenceTranslations)) * 100 : 0 }}%">
                                </div>
                            </div>
                            <small class="text-muted" id="progressText">
                                {{ count($referenceTranslations) > 0 ? round((count(array_filter($translations, function($value) { return !empty($value); })) / count($referenceTranslations)) * 100) : 0 }}% completato
                            </small>
                        </div>

                        <div class="mb-3">
                            <h6>Azioni Rapide</h6>
                            <div class="d-grid gap-2">
                                <button type="button" class="btn btn-outline-primary btn-sm" onclick="copyFromReference()">
                                    <i class="ph ph-copy me-1"></i>Copia da Italiano
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearAllTranslations()">
                                    <i class="ph ph-trash me-1"></i>Svuota Tutte
                                </button>
                                <button type="button" class="btn btn-outline-info btn-sm" onclick="showUntranslated()">
                                    <i class="ph ph-eye me-1"></i>Mostra Non Tradotte
                                </button>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <h6 class="text-info">
                                <i class="ph ph-lightbulb me-2"></i>Suggerimenti
                            </h6>
                            <ul class="mb-0 small">
                                <li>Usa il testo italiano come riferimento</li>
                                <li>Mantieni la stessa lunghezza quando possibile</li>
                                <li>Controlla la grammatica e l'ortografia</li>
                                <li>Salva spesso per non perdere le modifiche</li>
                            </ul>
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
let allTranslations = document.querySelectorAll('textarea[name^="translations["]');
let translatedCount = {{ count(array_filter($translations, function($value) { return !empty($value); })) }};
let totalCount = {{ count($referenceTranslations) }};

// Aggiorna il contatore delle traduzioni
function updateTranslationCount() {
    let count = 0;
    allTranslations.forEach(textarea => {
        if (textarea.value.trim() !== '') {
            count++;
        }
    });
    translatedCount = count;

    document.getElementById('translatedCount').textContent = count;
    document.getElementById('progressBar').style.width = (count / totalCount * 100) + '%';
    document.getElementById('progressText').textContent = Math.round(count / totalCount * 100) + '% completato';
}

// Toggle singola traduzione
function toggleTranslation(index) {
    const element = document.getElementById('translation_' + index);
    const checkbox = document.getElementById('toggle_' + index);

    if (checkbox.checked) {
        element.style.display = 'block';
    } else {
        element.style.display = 'none';
    }
}

// Toggle tutte le traduzioni
function toggleAllTranslations() {
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    const isAnyHidden = Array.from(checkboxes).some(cb => !cb.checked);

    checkboxes.forEach(checkbox => {
        checkbox.checked = !isAnyHidden;
        toggleTranslation(checkbox.id.replace('toggle_', ''));
    });
}

// Copia da riferimento (italiano)
function copyFromReference() {
    if (confirm('Vuoi copiare tutte le traduzioni dall\'italiano? Questo sovrascriverà le traduzioni esistenti.')) {
        allTranslations.forEach(textarea => {
            const reference = textarea.getAttribute('data-reference');
            textarea.value = reference;
        });
        updateTranslationCount();
    }
}

// Svuota tutte le traduzioni
function clearAllTranslations() {
    if (confirm('Vuoi svuotare tutte le traduzioni? Questa azione non può essere annullata.')) {
        allTranslations.forEach(textarea => {
            textarea.value = '';
        });
        updateTranslationCount();
    }
}

// Mostra solo quelle non tradotte
function showUntranslated() {
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');

    checkboxes.forEach((checkbox, index) => {
        const textarea = document.querySelector(`textarea[name="translations[${checkbox.getAttribute('data-key')}]"]`);
        const isEmpty = !textarea || textarea.value.trim() === '';

        checkbox.checked = isEmpty;
        toggleTranslation(index);
    });
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Aggiorna contatore quando cambia il testo
    allTranslations.forEach(textarea => {
        textarea.addEventListener('input', updateTranslationCount);
    });

    // Aggiungi data-key agli checkbox
    document.querySelectorAll('input[type="checkbox"]').forEach((checkbox, index) => {
        const textarea = document.querySelector(`textarea[name="translations[${Object.keys(@json($referenceTranslations))[index]}]"]`);
        if (textarea) {
            checkbox.setAttribute('data-key', textarea.getAttribute('data-key'));
        }
    });

    // Auto-save ogni 30 secondi
    setInterval(() => {
        const form = document.getElementById('translationForm');
        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        }).then(response => {
            if (response.ok) {
                console.log('Auto-save completato');
            }
        }).catch(error => {
            console.error('Errore auto-save:', error);
        });
    }, 30000);
});
</script>
@endsection
