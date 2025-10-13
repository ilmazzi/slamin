@extends('layout.master')

@section('main-content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="ph-duotone ph-translate text-primary me-2"></i>
                        {{ __('admin_general.translation_management') }}
                    </h1>
                    <p class="text-muted mb-0">{{ __('admin_general.translation_management_description') }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.translations.create') }}" class="btn btn-primary">
                        <i class="ph-duotone ph-plus me-1"></i>
                        {{ __('admin_general.add_language') }}
                    </a>
                    <a href="{{ route('admin.translations.hardcoded') }}" class="btn btn-outline-info">
                        <i class="ph-duotone ph-code me-1"></i>
                        {{ __('admin_general.hardcoded_texts') }}
                    </a>
                    <button type="button" class="btn btn-success" onclick="syncAllLanguagesComplete()">
                        <i class="ph-duotone ph-globe me-1"></i>
                        Sincronizza Tutte le Lingue
                    </button>
                    <button type="button" class="btn btn-outline-warning" onclick="clearCache()">
                        <i class="ph-duotone ph-trash me-1"></i>
                        {{ __('admin_general.clear_cache') }}
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card hover-effect">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="mb-1 text-primary">{{ $languageStats['total_keys'] ?? 0 }}</h4>
                                <p class="text-muted mb-0 f-s-14">{{ __('admin_general.total_keys') }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="mb-1 text-success">{{ $languageStats['total_translated'] ?? 0 }}</h4>
                                <p class="text-muted mb-0 f-s-14">{{ __('admin_general.translated_keys') }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="mb-1 text-warning">{{ $languageStats['total_missing'] ?? 0 }}</h4>
                                <p class="text-muted mb-0 f-s-14">{{ __('admin_general.missing_keys') }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <h4 class="mb-1 text-info">{{ count($languages) }}</h4>
                            <p class="text-muted mb-0 f-s-14">{{ __('admin_general.available_languages') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- API Translation Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card hover-effect">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ph-duotone ph-robot me-2"></i>
                        {{ __('admin_general.auto_translation') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <form id="apiTranslationForm">
                                @csrf
                                <div class="mb-3">
                                    <label for="apiLanguage" class="form-label">{{ __('admin_general.language') }}</label>
                                    <select class="form-select" id="apiLanguage" name="language" required>
                                        <option value="">{{ __('admin_general.select_locale') }}</option>
                                        <option value="en">English</option>
                                        <option value="es">Español</option>
                                        <option value="fr">Français</option>
                                        <option value="de">Deutsch</option>
                                        <option value="pt">Português</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="apiFile" class="form-label">{{ __('admin_general.translation_files') }}</label>
                                    <select class="form-select" id="apiFile" name="file" required>
                                        <option value="">{{ __('admin_general.select_group') }}</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="apiProvider" class="form-label">{{ __('admin_general.translation_provider') }}</label>
                                    <select class="form-select" id="apiProvider" name="provider" required>
                                        <option value="">{{ __('admin_general.select_group') }}</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="apiKey" class="form-label">{{ __('admin_general.translation_api_key') }}</label>
                                    <input type="text" class="form-control" id="apiKey" name="api_key" placeholder="Optional for LibreTranslate">
                                    <div class="form-text">{{ __('admin_general.translation_api_key_help') }}</div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="forceTranslation" name="force">
                                        <label class="form-check-label" for="forceTranslation">
                                            {{ __('admin_general.force_translation') }}
                                        </label>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-primary" onclick="testApiConnection()">
                                        <i class="ph-duotone ph-check-circle me-1"></i>
                                        {{ __('admin_general.test_connection') }}
                                    </button>
                                    <button type="submit" class="btn btn-success">
                                        <i class="ph-duotone ph-translate me-1"></i>
                                        {{ __('admin_general.translate_page') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6">
                            <div id="apiStatus" class="alert alert-info" style="display: none;">
                                <i class="ph-duotone ph-info me-2"></i>
                                <span id="apiStatusText">Ready to translate</span>
                            </div>
                            <div id="apiOutput" class="mt-3" style="display: none;">
                                <h6>{{ __('admin_general.translation_output') }}</h6>
                                <pre class="bg-light p-3 rounded" id="apiOutputText"></pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Languages List -->
    <div class="row">
        <div class="col-12">
            <div class="card hover-effect">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ph-duotone ph-globe me-2"></i>
                        {{ __('admin_general.available_languages') }}
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($languages && count($languages) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 20%;">{{ __('admin_general.language') }}</th>
                                        <th style="width: 15%;">{{ __('admin_general.code') }}</th>
                                        <th style="width: 15%;">{{ __('admin_general.progress') }}</th>
                                        <th style="width: 20%;">{{ __('admin_general.translated') }}</th>
                                        <th style="width: 20%;">{{ __('admin_general.missing') }}</th>
                                        <th style="width: 10%;">{{ __('admin_general.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($languages as $language)
                                    @php
                                        $stats = $languageStats[$language] ?? ['total_keys' => 0, 'translated_keys' => 0, 'missing_keys' => 0, 'progress_percentage' => 0];
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="ph-duotone ph-flag me-2 text-primary"></i>
                                                <strong>{{ ucfirst($language) }}</strong>
                                                @if($language === 'it')
                                                    <span class="badge bg-primary ms-2">{{ __('admin_general.reference') }}</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <code class="text-secondary">{{ strtoupper($language) }}</code>
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar {{ $stats['progress_percentage'] == 100 ? 'bg-success' : ($stats['progress_percentage'] > 50 ? 'bg-warning' : 'bg-danger') }}"
                                                     role="progressbar"
                                                     style="width: {{ $stats['progress_percentage'] }}%"
                                                     aria-valuenow="{{ $stats['progress_percentage'] }}"
                                                     aria-valuemin="0"
                                                     aria-valuemax="100">
                                                    {{ $stats['progress_percentage'] }}%
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">{{ $stats['translated_keys'] }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning">{{ $stats['missing_keys'] }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="{{ route('admin.translations.smart', $language) }}"
                                                   class="btn btn-sm btn-primary"
                                                   title="Gestisci Traduzioni">
                                                    <i class="ph-duotone ph-pencil f-s-14"></i>
                                                </a>
                                                @if($language !== 'it')
                                                    <button type="button"
                                                            class="btn btn-outline-danger"
                                                            onclick="deleteLanguage('{{ $language }}')"
                                                            title="{{ __('admin_general.delete') }}">
                                                        <i class="ph-duotone ph-trash f-s-14"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ph-duotone ph-translate f-s-48 text-muted mb-3"></i>
                            <h5 class="text-muted">{{ __('admin_general.no_languages_found') }}</h5>
                            <p class="text-muted">{{ __('admin_general.no_languages_description') }}</p>
                            <a href="{{ route('admin.translations.create') }}" class="btn btn-primary">
                                <i class="ph-duotone ph-plus me-1"></i>
                                {{ __('admin_general.add_first_language') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modali -->
@include('admin.translations.modals.delete')

<script>
function deleteLanguage(language) {
    Swal.fire({
        title: '{{ __('admin_general.confirm_delete') }}',
        text: '{{ __('admin_general.delete_language_confirm') }}: ' + language.toUpperCase(),
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: '{{ __('admin_general.delete') }}',
        cancelButtonText: '{{ __('admin_general.cancel') }}'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`{{ route('admin.translations.destroy', 'LANGUAGE_PLACEHOLDER') }}`.replace('LANGUAGE_PLACEHOLDER', language), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text().then(text => {
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        throw new Error('Invalid JSON response: ' + text.substring(0, 200));
                    }
                });
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: '{{ __('admin_general.success') }}',
                        text: data.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ __('admin_general.error') }}',
                        text: data.message
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: '{{ __('admin_general.error') }}',
                    text: '{{ __('admin_general.unknown_error') }}'
                });
            });
        }
    });
}

function syncAllLanguagesComplete() {
    Swal.fire({
        title: 'Sincronizzazione Completa',
        text: 'Vuoi sincronizzare tutte le lingue partendo dall\'italiano? Questa operazione potrebbe richiedere alcuni minuti.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sì, Sincronizza',
        cancelButtonText: 'Annulla',
        confirmButtonColor: '#28a745'
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostra loading
            Swal.fire({
                title: 'Sincronizzazione in corso...',
                text: 'Sto sincronizzando tutte le lingue, attendi...',
                icon: 'info',
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch('{{ route("admin.translations.sync-all") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text().then(text => {
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        throw new Error('Invalid JSON response: ' + text.substring(0, 200));
                    }
                });
            })
            .then(data => {
                if (data.success) {
                    let message = data.message + '\n\n';
                    if (data.results) {
                        message += 'Risultati per lingua:\n';
                        Object.keys(data.results).forEach(lang => {
                            const result = data.results[lang];
                            message += `• ${lang.toUpperCase()}: ${result.files_processed} file, ${result.keys_added} chiavi aggiunte, ${result.keys_updated} chiavi aggiornate\n`;
                            if (result.errors && result.errors.length > 0) {
                                message += `  Errori: ${result.errors.join(', ')}\n`;
                            }
                        });
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Sincronizzazione Completata!',
                        text: message,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: '{{ __('admin_general.error') }}',
                        text: data.message
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: '{{ __('admin_general.error') }}',
                    text: '{{ __('admin_general.unknown_error') }}'
                });
            });
        }
    });
}

function clearCache() {
    if (confirm('{{ __('admin_general.clear_cache_confirm') }}')) {
        fetch('{{ route("admin.translations.clear-cache") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text().then(text => {
                try {
                    return JSON.parse(text);
                } catch (e) {
                    throw new Error('Invalid JSON response: ' + text.substring(0, 200));
                }
            });
        })
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '{{ __('admin_general.success') }}',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '{{ __('admin_general.error') }}',
                    text: data.message
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: '{{ __('admin_general.error') }}',
                text: '{{ __('admin_general.unknown_error') }}'
            });
        });
    }
}

// API Translation Functions
let providers = [];
let files = [];

// Load providers and files on page load
document.addEventListener('DOMContentLoaded', function() {
    loadProviders();
    loadFiles();
});

function loadProviders() {
    fetch('{{ route("admin.translations.api.providers") }}')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text().then(text => {
                try {
                    return JSON.parse(text);
                } catch (e) {
                    throw new Error('Invalid JSON response: ' + text.substring(0, 200));
                }
            });
        })
        .then(data => {
            providers = data.providers;
            const select = document.getElementById('apiProvider');
            select.innerHTML = '<option value="">{{ __('admin_general.select_group') }}</option>';

            Object.keys(providers).forEach(key => {
                const option = document.createElement('option');
                option.value = key;
                option.textContent = providers[key].name;
                select.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error loading providers:', error);
            showApiStatus('Error loading providers: ' + error.message, 'error');
        });
}

function loadFiles() {
    fetch('{{ route("admin.translations.api.files") }}')
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text().then(text => {
                try {
                    return JSON.parse(text);
                } catch (e) {
                    throw new Error('Invalid JSON response: ' + text.substring(0, 200));
                }
            });
        })
        .then(data => {
            files = data.files;
            const select = document.getElementById('apiFile');
            select.innerHTML = '<option value="">{{ __('admin_general.select_group') }}</option>';

            files.forEach(file => {
                const option = document.createElement('option');
                option.value = file.name.replace('.php', '');
                option.textContent = file.display_name;
                select.appendChild(option);
            });
        })
        .catch(error => {
            console.error('Error loading files:', error);
            showApiStatus('Error loading files: ' + error.message, 'error');
        });
}

function testApiConnection() {
    const provider = document.getElementById('apiProvider').value;
    const apiKey = document.getElementById('apiKey').value;

    if (!provider) {
        showApiStatus('Please select a provider', 'warning');
        return;
    }

    showApiStatus('Testing connection...', 'info');

    fetch('{{ route("admin.translations.api.test") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            provider: provider,
            api_key: apiKey
        })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text().then(text => {
            try {
                return JSON.parse(text);
            } catch (e) {
                throw new Error('Invalid JSON response: ' + text.substring(0, 200));
            }
        });
    })
    .then(data => {
        if (data.success) {
            showApiStatus(data.message, 'success');
        } else {
            showApiStatus(data.message, 'error');
        }
    })
    .catch(error => {
        showApiStatus('Connection test failed: ' + error.message, 'error');
    });
}

function translatePage() {
    const form = document.getElementById('apiTranslationForm');
    const formData = new FormData(form);

    const data = {
        language: formData.get('language'),
        file: formData.get('file'),
        provider: formData.get('provider'),
        api_key: formData.get('api_key'),
        force: formData.get('force') === 'on'
    };

    if (!data.language || !data.file || !data.provider) {
        showApiStatus('Please fill all required fields', 'warning');
        return;
    }

    showApiStatus('Translating...', 'info');
    hideApiOutput();

    fetch('{{ route("admin.translations.api.translate-page") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.text().then(text => {
            try {
                return JSON.parse(text);
            } catch (e) {
                throw new Error('Invalid JSON response: ' + text.substring(0, 200));
            }
        });
    })
    .then(data => {
        if (data.success) {
            showApiStatus('Translation completed successfully!', 'success');
            showApiOutput(data.output);

            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'Translation Complete',
                text: `File ${data.file}.php translated to ${data.language.toUpperCase()}`,
                timer: 3000,
                showConfirmButton: false
            });
        } else {
            showApiStatus('Translation failed: ' + data.message, 'error');
            showApiOutput(data.output);
        }
    })
    .catch(error => {
        showApiStatus('Translation error: ' + error.message, 'error');
    });
}

function showApiStatus(message, type) {
    const statusDiv = document.getElementById('apiStatus');
    const statusText = document.getElementById('apiStatusText');

    statusDiv.className = `alert alert-${type === 'success' ? 'success' : type === 'error' ? 'danger' : type === 'warning' ? 'warning' : 'info'}`;
    statusText.textContent = message;
    statusDiv.style.display = 'block';
}

function showApiOutput(output) {
    const outputDiv = document.getElementById('apiOutput');
    const outputText = document.getElementById('apiOutputText');

    outputText.textContent = output;
    outputDiv.style.display = 'block';
}

function hideApiOutput() {
    document.getElementById('apiOutput').style.display = 'none';
}

// Form submission
document.getElementById('apiTranslationForm').addEventListener('submit', function(e) {
    e.preventDefault();
    translatePage();
});
</script>
@endsection
