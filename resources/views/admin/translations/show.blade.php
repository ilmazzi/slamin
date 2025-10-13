@extends('layout.master')

@section('main-content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="ph-duotone ph-flag text-primary me-2"></i>
                        {{ __('admin_general.manage_translations') }} - {{ strtoupper($language) }}
                    </h1>
                    <p class="text-muted mb-0">{{ __('admin_general.manage_translations_description') }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.translations.index') }}" class="btn btn-outline-secondary">
                        <i class="ph-duotone ph-arrow-left me-1"></i>
                        {{ __('admin_general.back_to_languages') }}
                    </a>
                    <button type="button" class="btn btn-outline-warning" onclick="syncTranslations()">
                        <i class="ph-duotone ph-arrow-clockwise me-1"></i>
                        {{ __('admin_general.sync_with_italian') }}
                    </button>
                    <button type="button" class="btn btn-outline-info" onclick="copyFromItalian()">
                        <i class="ph-duotone ph-copy me-1"></i>
                        {{ __('admin_general.copy_from_italian') }}
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
                                <h4 class="mb-1 text-primary">{{ $stats['total_keys'] }}</h4>
                                <p class="text-muted mb-0 f-s-14">{{ __('admin_general.total_keys') }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="mb-1 text-success">{{ $stats['translated_keys'] }}</h4>
                                <p class="text-muted mb-0 f-s-14">{{ __('admin_general.translated_keys') }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="mb-1 text-warning">{{ $stats['missing_keys'] }}</h4>
                                <p class="text-muted mb-0 f-s-14">{{ __('admin_general.missing_keys') }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <h4 class="mb-1 text-info">{{ $stats['progress_percentage'] }}%</h4>
                            <p class="text-muted mb-0 f-s-14">{{ __('admin_general.progress') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- File Selector -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card hover-effect">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="fileSelector" class="form-label f-s-14">{{ __('admin_general.translation_file') }}</label>
                            <select name="file" id="fileSelector" class="form-select" onchange="changeFile()">
                                @foreach($translationFiles as $fileKey => $fileDisplayName)
                                    <option value="{{ $fileKey }}" {{ $selectedFile == $fileKey ? 'selected' : '' }}>
                                        {{ $fileDisplayName }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label f-s-14">{{ __('admin_general.actions') }}</label>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary" onclick="saveTranslations()">
                                    <i class="ph-duotone ph-floppy-disk me-1"></i>
                                    {{ __('admin_general.save_translations') }}
                                </button>
                                <button type="button" class="btn btn-outline-danger" onclick="clearAllTranslations()">
                                    <i class="ph-duotone ph-trash me-1"></i>
                                    {{ __('admin_general.clear_all') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Translations Table -->
    <div class="row">
        <div class="col-12">
            <div class="card hover-effect">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ph-duotone ph-list-bullets me-2"></i>
                        {{ __('admin_general.translations_for_file') }}: {{ $translationFiles[$selectedFile] ?? $selectedFile }}
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($translationData && count($translationData) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 25%;">{{ __('admin_general.key') }}</th>
                                        <th style="width: 35%;">{{ __('admin_general.italian_reference') }}</th>
                                        <th style="width: 35%;">{{ __('admin_general.translation') }}</th>
                                        <th style="width: 5%;">{{ __('admin_general.status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($translationData as $key => $data)
                                    <tr class="{{ $data['is_missing'] ? 'table-warning' : ($data['is_translated'] ? 'table-success' : '') }}">
                                        <td>
                                            <code class="text-primary f-s-12">{{ $key }}</code>
                                        </td>
                                        <td>
                                            <div class="text-muted f-s-12" style="max-height: 60px; overflow-y: auto;">
                                                {{ $data['reference'] }}
                                            </div>
                                        </td>
                                        <td>
                                            <textarea class="form-control form-control-sm translation-input"
                                                      data-key="{{ $key }}"
                                                      rows="2"
                                                      style="min-height: 40px;">{{ $data['translation'] }}</textarea>
                                        </td>
                                        <td class="text-center">
                                            @if($data['is_missing'])
                                                <i class="ph-duotone ph-warning text-warning" title="{{ __('admin_general.missing') }}"></i>
                                            @elseif($data['is_translated'])
                                                <i class="ph-duotone ph-check-circle text-success" title="{{ __('admin_general.translated') }}"></i>
                                            @else
                                                <i class="ph-duotone ph-minus text-muted" title="{{ __('admin_general.empty') }}"></i>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ph-duotone ph-translate f-s-48 text-muted mb-3"></i>
                            <h5 class="text-muted">{{ __('admin_general.no_translations_found') }}</h5>
                            <p class="text-muted">{{ __('admin_general.no_translations_description') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function changeFile() {
    const file = document.getElementById('fileSelector').value;
    const url = new URL(window.location);
    url.searchParams.set('file', file);
    window.location.href = url.toString();
}

function saveTranslations() {
    const translations = {};
    const inputs = document.querySelectorAll('.translation-input');

    inputs.forEach(input => {
        const key = input.dataset.key;
        const value = input.value.trim();
        if (key && value) {
            translations[key] = value;
        }
    });

    fetch('{{ route("admin.translations.update", $language) }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            file: '{{ $selectedFile }}',
            translations: translations
        })
    })
    .then(response => response.json())
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

function syncTranslations() {
    if (confirm('{{ __('admin_general.sync_confirm') }}')) {
        fetch('{{ route("admin.translations.sync") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                language: '{{ $language }}'
            })
        })
        .then(response => response.json())
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
}

function copyFromItalian() {
    if (confirm('{{ __('admin_general.copy_from_italian_confirm') }}')) {
        fetch('{{ route("admin.translations.copy-from-italian", $language) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                file: '{{ $selectedFile }}'
            })
        })
        .then(response => response.json())
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
}

function clearAllTranslations() {
    if (confirm('{{ __('admin_general.clear_all_confirm') }}')) {
        fetch('{{ route("admin.translations.clear-all", $language) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                file: '{{ $selectedFile }}'
            })
        })
        .then(response => response.json())
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
}
</script>
@endsection
