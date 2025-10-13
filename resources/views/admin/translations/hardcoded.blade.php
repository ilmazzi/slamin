@extends('layout.master')

@section('main-content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="ph-duotone ph-code text-primary me-2"></i>
                        {{ __('admin_general.hardcoded_texts') }}
                    </h1>
                    <p class="text-muted mb-0">{{ __('admin_general.hardcoded_texts_description') }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.translations.index') }}" class="btn btn-outline-secondary">
                        <i class="ph-duotone ph-arrow-left me-1"></i>
                        {{ __('admin_general.back_to_translations') }}
                    </a>
                    <button type="button" class="btn btn-outline-info" onclick="refreshHardcodedTexts()">
                        <i class="ph-duotone ph-arrow-clockwise me-1"></i>
                        {{ __('admin_general.refresh_scan') }}
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
                                <h4 class="mb-1 text-warning">{{ count($hardcodedTexts) }}</h4>
                                <p class="text-muted mb-0 f-s-14">{{ __('admin_general.hardcoded_found') }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="mb-1 text-info">{{ count(array_unique(array_column($hardcodedTexts, 'file'))) }}</h4>
                                <p class="text-muted mb-0 f-s-14">{{ __('admin_general.files_affected') }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="mb-1 text-success">{{ count($languages) }}</h4>
                                <p class="text-muted mb-0 f-s-14">{{ __('admin_general.available_languages') }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <h4 class="mb-1 text-primary">{{ __('admin_general.ready_to_convert') }}</h4>
                            <p class="text-muted mb-0 f-s-14">{{ __('admin_general.convert_to_keys') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtri -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card hover-effect">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="fileFilter" class="form-label f-s-14">{{ __('admin_general.filter_by_file') }}</label>
                            <select name="file" id="fileFilter" class="form-select form-select-sm" onchange="filterByFile()">
                                <option value="">{{ __('admin_general.all_files') }}</option>
                                @foreach(array_unique(array_column($hardcodedTexts, 'file')) as $file)
                                    <option value="{{ $file }}">{{ $file }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="searchText" class="form-label f-s-14">{{ __('admin_general.search_text') }}</label>
                            <input type="text" id="searchText" class="form-control form-control-sm"
                                   placeholder="{{ __('admin_general.search_placeholder') }}" onkeyup="filterByText()">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label f-s-14">{{ __('admin_general.actions') }}</label>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-primary btn-sm" onclick="convertAllVisible()">
                                    <i class="ph-duotone ph-check-circle me-1"></i>
                                    {{ __('admin_general.convert_all_visible') }}
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearFilters()">
                                    <i class="ph-duotone ph-x me-1"></i>
                                    {{ __('admin_general.clear_filters') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista Testi Hardcoded -->
    <div class="row">
        <div class="col-12">
            <div class="card hover-effect">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ph-duotone ph-list-bullets me-2"></i>
                        {{ __('admin_general.hardcoded_texts_list') }}
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($hardcodedTexts && count($hardcodedTexts) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0" id="hardcodedTable">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 5%;">
                                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                                        </th>
                                        <th style="width: 35%;">{{ __('admin_general.text') }}</th>
                                        <th style="width: 20%;">{{ __('admin_general.file') }}</th>
                                        <th style="width: 10%;">{{ __('admin_general.line') }}</th>
                                        <th style="width: 20%;">{{ __('admin_general.suggested_key') }}</th>
                                        <th style="width: 10%;">{{ __('admin_general.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($hardcodedTexts as $index => $item)
                                    <tr class="hardcoded-row" data-file="{{ $item['file'] }}" data-text="{{ strtolower($item['text']) }}">
                                        <td>
                                            <input type="checkbox" class="row-checkbox" value="{{ $index }}">
                                        </td>
                                        <td>
                                            <div class="text-muted f-s-12" style="max-height: 60px; overflow-y: auto;">
                                                "{{ $item['text'] }}"
                                            </div>
                                        </td>
                                        <td>
                                            <code class="text-primary f-s-12">{{ $item['file'] }}</code>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $item['line'] }}</span>
                                        </td>
                                        <td>
                                            <code class="text-info f-s-12">{{ $item['suggested_key'] }}</code>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-outline-primary"
                                                        onclick="convertSingle({{ $index }})"
                                                        title="{{ __('admin_general.convert') }}">
                                                    <i class="ph-duotone ph-arrow-right f-s-14"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-info"
                                                        onclick="previewText({{ $index }})"
                                                        title="{{ __('admin_general.preview') }}">
                                                    <i class="ph-duotone ph-eye f-s-14"></i>
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
                            <i class="ph-duotone ph-check-circle f-s-48 text-success mb-3"></i>
                            <h5 class="text-success">{{ __('admin_general.no_hardcoded_found') }}</h5>
                            <p class="text-muted">{{ __('admin_general.no_hardcoded_description') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modale per Conversione Singola -->
<div class="modal fade" id="convertModal" tabindex="-1" aria-labelledby="convertModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="convertModalLabel">
                    <i class="ph-duotone ph-arrow-right text-primary me-2"></i>
                    {{ __('admin_general.convert_to_translation') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="convertForm">
                    <div class="mb-3">
                        <label class="form-label f-s-14">{{ __('admin_general.original_text') }}</label>
                        <div class="form-control-plaintext bg-light p-2 rounded" id="originalText"></div>
                    </div>
                    <div class="mb-3">
                        <label for="translationKey" class="form-label f-s-14">{{ __('admin_general.translation_key') }}</label>
                        <input type="text" class="form-control" id="translationKey" required>
                        <div class="form-text">{{ __('admin_general.key_help') }}</div>
                    </div>
                    <div class="mb-3">
                        <label for="translationFile" class="form-label f-s-14">{{ __('admin_general.translation_file') }}</label>
                        <select class="form-select" id="translationFile" required>
                            @foreach($languages as $language)
                                <optgroup label="{{ strtoupper($language) }}">
                                    @foreach(['admin', 'common', 'dashboard', 'home', 'auth'] as $file)
                                        <option value="{{ $language }}_{{ $file }}">{{ $file }}.php</option>
                                    @endforeach
                                </optgroup>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="translationValue" class="form-label f-s-14">{{ __('admin_general.translation_value') }}</label>
                        <textarea class="form-control" id="translationValue" rows="3" required></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    {{ __('admin_general.cancel') }}
                </button>
                <button type="button" class="btn btn-primary" onclick="confirmConvert()">
                    <i class="ph-duotone ph-check me-1"></i>
                    {{ __('admin_general.convert') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let hardcodedTexts = @json($hardcodedTexts);

function filterByFile() {
    const fileFilter = document.getElementById('fileFilter').value;
    const rows = document.querySelectorAll('.hardcoded-row');

    rows.forEach(row => {
        if (fileFilter === '' || row.dataset.file === fileFilter) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function filterByText() {
    const searchText = document.getElementById('searchText').value.toLowerCase();
    const rows = document.querySelectorAll('.hardcoded-row');

    rows.forEach(row => {
        if (searchText === '' || row.dataset.text.includes(searchText)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

function clearFilters() {
    document.getElementById('fileFilter').value = '';
    document.getElementById('searchText').value = '';
    document.querySelectorAll('.hardcoded-row').forEach(row => {
        row.style.display = '';
    });
}

function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.row-checkbox');

    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

function convertSingle(index) {
    const item = hardcodedTexts[index];
    document.getElementById('originalText').textContent = item.text;
    document.getElementById('translationKey').value = item.suggested_key;
    document.getElementById('translationValue').value = item.text;

    // Imposta il file basato sul file originale
    const fileName = item.file.split('/').pop().replace('.blade.php', '').replace('.php', '');
    document.getElementById('translationFile').value = 'it_' + fileName;

    new bootstrap.Modal(document.getElementById('convertModal')).show();
}

function confirmConvert() {
    const key = document.getElementById('translationKey').value;
    const fileParts = document.getElementById('translationFile').value.split('_');
    const language = fileParts[0];
    const file = fileParts[1];
    const value = document.getElementById('translationValue').value;
    const originalText = document.getElementById('originalText').textContent;

    fetch('{{ route("admin.translations.convert-to-key") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            text: originalText,
            suggested_key: key,
            file: file,
            language: language,
            value: value
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

function convertAllVisible() {
    const checkedBoxes = document.querySelectorAll('.row-checkbox:checked');
    if (checkedBoxes.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: '{{ __('admin_general.no_selection') }}',
            text: '{{ __('admin_general.select_texts_to_convert') }}'
        });
        return;
    }

    // Implementa la conversione di massa
    Swal.fire({
        icon: 'info',
        title: '{{ __('admin_general.bulk_convert') }}',
        text: '{{ __('admin_general.bulk_convert_description') }}'
    });
}

function previewText(index) {
    const item = hardcodedTexts[index];
    Swal.fire({
        title: '{{ __('admin_general.text_preview') }}',
        html: `
            <div class="text-start">
                <p><strong>{{ __('admin_general.text') }}:</strong> "${item.text}"</p>
                <p><strong>{{ __('admin_general.file') }}:</strong> <code>${item.file}</code></p>
                <p><strong>{{ __('admin_general.line') }}:</strong> <span class="badge bg-secondary">${item.line}</span></p>
                <p><strong>{{ __('admin_general.suggested_key') }}:</strong> <code>${item.suggested_key}</code></p>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: '{{ __('admin_general.convert') }}',
        cancelButtonText: '{{ __('admin_general.close') }}'
    }).then((result) => {
        if (result.isConfirmed) {
            convertSingle(index);
        }
    });
}

function refreshHardcodedTexts() {
    Swal.fire({
        title: '{{ __('admin_general.refreshing') }}',
        text: '{{ __('admin_general.scanning_files') }}',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    location.reload();
}
</script>
@endsection
