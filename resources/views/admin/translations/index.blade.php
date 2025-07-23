@extends('layout.master')

@section('title', __('admin.translation_management'))

@section('main-content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">
                <i class="ph ph-translate me-2"></i>
                {{ __('admin.translation_management') }}
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
                    <a href="#" class="f-s-14 f-w-500">{{ __('admin.translation_management') }}</a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Header Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card hover-effect">
                <div class="card-body bg-light-primary">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <p class="text-muted mb-0 f-s-14">{{ __('admin.translation_management_description') }}</p>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex flex-column flex-md-row gap-2">
                                <button type="button" class="btn btn-warning" onclick="syncLanguages()">
                                    <i class="ph ph-arrows-clockwise me-2"></i>Sincronizza Lingue
                                </button>
                                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addLanguageModal">
                                    <i class="ph ph-plus me-2"></i>{{ __('admin.add_language') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Languages Overview Cards -->
    <div class="row mb-4">
        <div class="12">
            <h5 class="mb-3 f-w-600 text-primary">
                <i class="ph ph-globe me-2"></i>Lingue Disponibili
            </h5>
        </div>
        @foreach($languages as $code => $name)
        <div class="col-12 col-md-6 col-lg-4 mb-3">
            <div class="card hover-effect">
                                <div class="card-header position-relative overflow-hidden bg-light-primary">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <div>
                                <h6 class="mb-0 f-w-600 text-primary">{{ $name }}</h6>
                                <small class="text-muted f-s-12">{{ strtoupper($code) }}</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <i class="flag-icon flag-icon-{{ 
                                $code == 'en' ? 'gbr' : 
                                ($code == 'de' ? 'deu' : 
                                ($code == 'es' ? 'esp' : 
                                ($code == 'fr' ? 'fra' : 'ita'))) 
                            }} me-2" style="font-size: 24px;"></i>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Delete Button (outside flag area) -->
                    @if($code !== 'it')
                    <div class="d-flex justify-content-end mb-2">
                        
                        <button type="button" class="btn btn-light-danger icon-btn b-r-4"onclick="deleteLanguage('{{ $code }}', '{{ $name }}')"
                        title="Elimina lingua {{ $name }}"><i
                            class="ti ti-trash"></i></button>
                    </div>
                    @endif
                    
                    <!-- Status Badges -->
                    @if($code === 'it')
                        @if(isset($missingKeys[$code]) && !empty($missingKeys[$code]))
                            @php
                                $totalMissing = 0;
                                foreach($missingKeys[$code] as $file => $keys) {
                                    $totalMissing += count($keys);
                                }
                            @endphp
                            @if($totalMissing > 0)
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <span class="badge bg-warning text-dark f-s-12">
                                        <i class="ph ph-warning me-1"></i>{{ $totalMissing }} da completare
                                    </span>
                                    <span class="badge bg-primary f-s-12">
                                        <i class="ph ph-star me-1"></i>Riferimento
                                    </span>
                                </div>
                            @else
                                <span class="badge bg-primary f-s-12 mb-3">
                                    <i class="ph ph-star me-1"></i>Riferimento Completo
                                </span>
                            @endif
                        @else
                            <span class="badge bg-primary f-s-12 mb-3">
                                <i class="ph ph-star me-1"></i>Riferimento
                            </span>
                        @endif
                    @else
                        @if(isset($missingKeys[$code]) && !empty($missingKeys[$code]))
                            @php
                                $totalMissing = 0;
                                foreach($missingKeys[$code] as $file => $keys) {
                                    $totalMissing += count($keys);
                                }
                            @endphp
                            @if($totalMissing > 0)
                                <div class="d-flex align-items-center gap-2 mb-3">
                                    <span class="badge bg-warning text-dark f-s-12">
                                        <i class="ph ph-warning me-1"></i>{{ $totalMissing }} mancanti
                                    </span>
                                    <span class="badge bg-success f-s-12">
                                        <i class="ph ph-arrows-clockwise me-1"></i>Sincronizzata
                                    </span>
                                </div>
                            @else
                                <span class="badge bg-success f-s-12 mb-3">
                                    <i class="ph ph-check me-1"></i>Sincronizzata
                                </span>
                            @endif
                        @else
                            <span class="badge bg-success f-s-12 mb-3">
                                <i class="ph ph-check me-1"></i>Sincronizzata
                            </span>
                        @endif
                    @endif

                    <!-- File Selection Dropdown -->
                    <div class="mb-3">
                        <label class="form-label f-s-12 f-w-500">Seleziona File:</label>
                        <select class="form-select form-select-sm" onchange="selectFile('{{ $code }}', this.value)">
                            <option value="">Scegli un file...</option>
                            @foreach($translationFiles as $file => $displayName)
                                @php
                                    $missingCount = 0;
                                    if (isset($missingKeys[$code][$file])) {
                                        $missingCount = count($missingKeys[$code][$file]);
                                    }
                                @endphp
                                <option value="{{ $file }}" data-missing="{{ $missingCount }}">
                                    {{ $displayName }} ({{ $file }}.php) 
                                    @if($missingCount > 0)
                                        @if($code === 'it')
                                            - {{ $missingCount }} da completare
                                        @else
                                            - {{ $missingCount }} mancanti
                                        @endif
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Selected File Info -->
                    <div id="file-info-{{ $code }}" class="mb-3" style="display: none;">
                        <div class="alert alert-light-primary p-2">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong id="selected-file-{{ $code }}" class="text-primary"></strong>
                                    <br>
                                    <small id="missing-count-{{ $code }}"></small>
                                </div>
                                <button type="button" class="btn btn-primary btn-sm" 
                                        id="edit-btn-{{ $code }}" onclick="goToTranslation('{{ $code }}', '')">
                                    <i class="ph ph-pencil me-1"></i>Modifica
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    @if($code === 'it')
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light-primary btn-sm flex-fill" 
                                onclick="showMissingKeys('{{ $code }}')">
                            <i class="ph ph-warning me-1"></i>Dettagli
                        </button>
                        <button type="button" class="btn btn-light-primary btn-sm flex-fill" 
                                onclick="showIncompleteKeys('{{ $code }}')">
                            <i class="ph ph-list me-1"></i>Da Completare
                        </button>
                    </div>
                    @else
                    <div class="d-flex gap-2">
                        <button type="button" class="btn btn-light-primary btn-sm flex-fill" 
                                onclick="syncSingleLanguage('{{ $code }}')">
                            <i class="ph ph-arrows-clockwise me-1"></i>Sincronizza
                        </button>
                        <button type="button" class="btn btn-light-primary btn-sm flex-fill" 
                                onclick="showMissingKeys('{{ $code }}')">
                            <i class="ph ph-warning me-1"></i>Dettagli
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>


</div>

<!-- Add Language Modal -->
<div class="modal fade" id="addLanguageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ph ph-plus-circle me-2"></i>Aggiungi Nuova Lingua
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.translations.create-language') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="language_code" class="form-label">Codice Lingua *</label>
                        <input type="text" name="language_code" id="language_code" class="form-control"
                               placeholder="es. en, fr, de" maxlength="2" required>
                        <small class="text-muted">Codice ISO 639-1 (2 caratteri)</small>
                    </div>
                    <div class="mb-3">
                        <label for="language_name" class="form-label">Nome Lingua *</label>
                        <input type="text" name="language_name" id="language_name" class="form-control"
                               placeholder="es. English, Français, Deutsch" required>
                        <small class="text-muted">Nome completo della lingua</small>
                    </div>
                    <div class="alert alert-info">
                        <i class="ph ph-info me-2"></i>
                        <strong>Nota:</strong> La nuova lingua sarà creata copiando tutte le traduzioni dall'italiano.
                        Potrai poi modificarle individualmente.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="ph ph-plus me-2"></i>Crea Lingua
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Missing Keys Modal -->
<div class="modal fade" id="missingKeysModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ph ph-warning me-2"></i>Chiavi Mancanti
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="missingKeysContent"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Chiudi</button>
                <a href="#" id="syncLanguageBtn" class="btn btn-warning">
                    <i class="ph ph-arrows-clockwise me-2"></i>Sincronizza Questa Lingua
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-uppercase language code
    const languageCodeInput = document.getElementById('language_code');
    if (languageCodeInput) {
        languageCodeInput.addEventListener('input', function() {
            this.value = this.value.toLowerCase();
        });
    }
});

function showMissingKeys(languageCode, missingKeys) {
    let content = `<h6>Lingua: <strong>${languageCode.toUpperCase()}</strong></h6>`;
    content += '<div class="mt-3">';

    for (const [file, keys] of Object.entries(missingKeys)) {
        content += `<div class="mb-3">`;
        content += `<h6 class="text-primary">${file}.php (${keys.length} chiavi)</h6>`;
        content += `<ul class="list-group list-group-flush">`;
        keys.forEach(key => {
            content += `<li class="list-group-item d-flex justify-content-between align-items-center">`;
            content += `<code>${key}</code>`;
            content += `<a href="{{ route('admin.translations.show', ['LANG', 'FILE']) }}".replace('LANG', languageCode).replace('FILE', file) class="btn btn-sm btn-primary">`;
            content += `<i class="ph ph-pencil me-1"></i>Traduci</a>`;
            content += `</li>`;
        });
        content += `</ul></div>`;
    }

    content += '</div>';

    document.getElementById('missingKeysContent').innerHTML = content;
    document.getElementById('syncLanguageBtn').href = "{{ route('admin.translations.sync') }}";

    new bootstrap.Modal(document.getElementById('missingKeysModal')).show();
}

function deleteLanguage(languageCode, languageName) {
    Swal.fire({
        title: 'Elimina Lingua',
        text: `Sei sicuro di voler eliminare la lingua "${languageName}" (${languageCode.toUpperCase()})?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sì, Elimina',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.translations.delete-language", "LANG") }}'.replace('LANG', languageCode);
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    });
}

function selectFile(language, file) {
    const fileInfo = document.getElementById(`file-info-${language}`);
    const selectedFile = document.getElementById(`selected-file-${language}`);
    const missingCount = document.getElementById(`missing-count-${language}`);
    const editBtn = document.getElementById(`edit-btn-${language}`);
    
    if (file) {
        // Trova l'opzione selezionata per ottenere i dati
        const select = document.querySelector(`select[onchange="selectFile('${language}', this.value)"]`);
        const selectedOption = select.options[select.selectedIndex];
        const missingCountValue = selectedOption.getAttribute('data-missing');
        
        // Mostra le informazioni del file
        selectedFile.textContent = selectedOption.text;
        if (missingCountValue > 0) {
            missingCount.textContent = `${missingCountValue} traduzioni mancanti`;
            missingCount.className = 'text-warning f-s-12';
        } else {
            missingCount.textContent = 'Tutte le traduzioni sono complete';
            missingCount.className = 'text-success f-s-12';
        }
        
        // Aggiorna il pulsante modifica
        editBtn.onclick = function() {
            goToTranslation(language, file);
        };
        
        fileInfo.style.display = 'block';
    } else {
        fileInfo.style.display = 'none';
    }
}

function goToTranslation(language, file) {
    if (file) {
        window.location.href = '{{ route("admin.translations.show", ["LANG", "FILE"]) }}'
            .replace('LANG', language)
            .replace('FILE', file);
    }
}

function syncSingleLanguage(language) {
    Swal.fire({
        title: 'Sincronizza Lingua',
        text: `Sincronizzare la lingua ${language.toUpperCase()} con le chiavi italiane?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#007bff',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sì, Sincronizza',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostra loading
            Swal.fire({
                title: 'Sincronizzazione in corso...',
                text: `Sto sincronizzando la lingua ${language.toUpperCase()}`,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Invia richiesta AJAX
            fetch('{{ route("admin.translations.sync") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ language: language })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Errore nella sincronizzazione');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Successo!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Errore',
                        text: data.message || 'Errore durante la sincronizzazione',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Errore:', error);
                Swal.fire({
                    title: 'Errore',
                    text: 'Errore durante la sincronizzazione: ' + error.message,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        }
    });
}

function showIncompleteKeys(language) {
    // Mostra i dettagli delle chiavi incomplete per l'italiano
    const missingKeys = @json($missingKeys);
    if (missingKeys[language] && Object.keys(missingKeys[language]).length > 0) {
        let content = `<h6>Lingua: <strong>${language.toUpperCase()}</strong> (Riferimento)</h6>`;
        content += '<div class="mt-3">';

        for (const [file, keys] of Object.entries(missingKeys[language])) {
            content += `<div class="mb-3">`;
            content += `<h6 class="text-primary">${file}.php (${keys.length} da completare)</h6>`;
            content += `<ul class="list-group list-group-flush">`;
            keys.slice(0, 10).forEach(key => {
                content += `<li class="list-group-item d-flex justify-content-between align-items-center">`;
                content += `<code>${key}</code>`;
                content += `<a href="{{ route('admin.translations.show', ['LANG', 'FILE']) }}".replace('LANG', language).replace('FILE', file) class="btn btn-sm btn-primary">`;
                content += `<i class="ph ph-pencil me-1"></i>Completa</a>`;
                content += `</li>`;
            });
            if (keys.length > 10) {
                content += `<li class="list-group-item text-muted">... e altre ${keys.length - 10} chiavi</li>`;
            }
            content += `</ul></div>`;
        }

        content += '</div>';

        Swal.fire({
            title: 'Chiavi da Completare',
            html: content,
            width: '800px',
            confirmButtonText: 'Chiudi',
            confirmButtonColor: '#007bff'
        });
    } else {
        Swal.fire({
            title: 'Nessuna chiave da completare',
            text: 'Tutte le traduzioni italiane sono complete!',
            icon: 'success',
            confirmButtonText: 'OK'
        });
    }
}

function showMissingKeys(language) {
    // Mostra i dettagli delle chiavi mancanti per la lingua specifica
    const missingKeys = @json($missingKeys);
    if (missingKeys[language] && Object.keys(missingKeys[language]).length > 0) {
        let content = `<h6>Lingua: <strong>${language.toUpperCase()}</strong></h6>`;
        content += '<div class="mt-3">';

        for (const [file, keys] of Object.entries(missingKeys[language])) {
            content += `<div class="mb-3">`;
            content += `<h6 class="text-primary">${file}.php (${keys.length} chiavi)</h6>`;
            content += `<ul class="list-group list-group-flush">`;
            keys.slice(0, 10).forEach(key => {
                content += `<li class="list-group-item d-flex justify-content-between align-items-center">`;
                content += `<code>${key}</code>`;
                content += `<a href="{{ route('admin.translations.show', ['LANG', 'FILE']) }}".replace('LANG', language).replace('FILE', file) class="btn btn-sm btn-primary">`;
                content += `<i class="ph ph-pencil me-1"></i>Traduci</a>`;
                content += `</li>`;
            });
            if (keys.length > 10) {
                content += `<li class="list-group-item text-muted">... e altre ${keys.length - 10} chiavi</li>`;
            }
            content += `</ul></div>`;
        }

        content += '</div>';

        Swal.fire({
            title: 'Chiavi Mancanti',
            html: content,
            width: '600px',
            confirmButtonText: 'Chiudi'
        });
    } else {
        Swal.fire({
            title: 'Nessuna Chiave Mancante',
            text: `La lingua ${language.toUpperCase()} è completamente sincronizzata!`,
            icon: 'success',
            confirmButtonText: 'OK'
        });
    }
}

function syncLanguages() {
    Swal.fire({
        title: 'Sincronizza Lingue',
        text: 'Sincronizzare tutte le lingue con le chiavi italiane?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#ffc107',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sì, Sincronizza',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostra loading
            Swal.fire({
                title: 'Sincronizzazione in corso...',
                text: 'Sto sincronizzando tutte le lingue',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Invia richiesta AJAX
            fetch('{{ route("admin.translations.sync") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Errore nella sincronizzazione');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Successo!',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Errore',
                        text: data.message || 'Errore durante la sincronizzazione',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Errore:', error);
                Swal.fire({
                    title: 'Errore',
                    text: 'Errore durante la sincronizzazione: ' + error.message,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        }
    });
}
</script>
@endsection
