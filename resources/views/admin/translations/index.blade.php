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
                        {{ __('admin.translation_management') }}
                    </h1>
                    <p class="text-muted mb-0">{{ __('admin.translation_management_description') }}</p>
                </div>
                <div>
                    <a href="{{ route('admin.translations.create') }}" class="btn btn-primary">
                        <i class="ph-duotone ph-plus me-1"></i>
                        {{ __('admin.add_language') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="mb-1 text-primary">{{ count($languages) }}</h4>
                                <p class="text-muted mb-0 f-s-14">{{ __('admin.available_languages') }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="mb-1 text-success">{{ $languageStats['total_translated'] }}</h4>
                                <p class="text-muted mb-0 f-s-14">{{ __('admin.total_translated') }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="mb-1 text-warning">{{ $languageStats['total_missing'] }}</h4>
                                <p class="text-muted mb-0 f-s-14">{{ __('admin.total_missing') }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <h4 class="mb-1 text-info">{{ $languageStats['total_keys'] }}</h4>
                            <p class="text-muted mb-0 f-s-14">{{ __('admin.total_keys') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Languages List -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph-duotone ph-list-bullets me-2"></i>
                        {{ __('admin.available_languages') }}
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if(count($languages) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 20%;">{{ __('admin.language') }}</th>
                                        <th style="width: 15%;">{{ __('admin.code') }}</th>
                                        <th style="width: 15%;">{{ __('admin.translated') }}</th>
                                        <th style="width: 15%;">{{ __('admin.missing') }}</th>
                                        <th style="width: 15%;">{{ __('admin.progress') }}</th>
                                        <th style="width: 20%;">{{ __('admin.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($languages as $lang)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="me-2">{{ __('admin.language_' . $lang) ?: ucfirst($lang) }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <code class="text-primary">{{ strtoupper($lang) }}</code>
                                        </td>
                                        <td>
                                            <span class="badge bg-success">{{ $languageStats[$lang]['translated_keys'] ?? 0 }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-warning">{{ $languageStats[$lang]['missing_keys'] ?? 0 }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $percentage = $languageStats[$lang]['progress_percentage'] ?? 0;
                                            @endphp
                                            <div class="d-flex align-items-center">
                                                <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                    <div class="progress-bar
                                                        @if($percentage >= 80) bg-success
                                                        @elseif($percentage >= 50) bg-warning
                                                        @else bg-danger
                                                        @endif"
                                                        style="width: {{ $percentage }}%"></div>
                                                </div>
                                                <small class="text-muted">{{ $percentage }}%</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('admin.translations.show', $lang) }}" class="btn btn-outline-primary" title="{{ __('admin.edit_translations') }}">
                                                    <i class="ph-duotone ph-pencil f-s-14"></i>
                                                </a>
                                                @if($lang !== 'it')
                                                <button type="button" class="btn btn-outline-danger" onclick="deleteLanguage('{{ $lang }}')" title="{{ __('admin.delete_language') }}">
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
                            <h5 class="text-muted">{{ __('admin.no_languages_found') }}</h5>
                            <p class="text-muted">{{ __('admin.no_languages_description') }}</p>
                            <a href="{{ route('admin.translations.create') }}" class="btn btn-primary">
                                <i class="ph-duotone ph-plus me-1"></i>
                                {{ __('admin.add_first_language') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Sync Actions -->
    @if(count($languages) > 1)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph-duotone ph-arrows-clockwise me-2"></i>
                        {{ __('admin.sync_actions') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <i class="ph-duotone ph-arrows-clockwise f-s-24 text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ __('admin.sync_all_languages') }}</h6>
                                    <p class="text-muted mb-2 f-s-14">{{ __('admin.sync_all_languages_description') }}</p>
                                    <button type="button" class="btn btn-outline-primary btn-sm" onclick="syncAllLanguages()">
                                        <i class="ph-duotone ph-arrows-clockwise me-1"></i>
                                        {{ __('admin.sync_now') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3">
                                    <i class="ph-duotone ph-info f-s-24 text-info"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ __('admin.translation_info') }}</h6>
                                    <p class="text-muted mb-2 f-s-14">{{ __('admin.translation_info_description') }}</p>
                                    <small class="text-muted">
                                        <i class="ph-duotone ph-lightbulb me-1"></i>
                                        {{ __('admin.translation_tip') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Delete Language Modal -->
<div class="modal fade" id="deleteLanguageModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('admin.delete_language') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('admin.delete_language_warning') }}</p>
                <div class="alert alert-warning">
                    <i class="ph-duotone ph-warning-circle me-2"></i>
                    {{ __('admin.delete_language_irreversible') }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('admin.cancel') }}</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteLanguage">{{ __('admin.delete') }}</button>
            </div>
        </div>
    </div>
</div>

<script>
let languageToDelete = null;

function deleteLanguage(language) {
    languageToDelete = language;
    const modal = new bootstrap.Modal(document.getElementById('deleteLanguageModal'));
    modal.show();
}

document.getElementById('confirmDeleteLanguage').addEventListener('click', function() {
    if (languageToDelete) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.translations.destroy", ":language") }}'.replace(':language', languageToDelete);

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

function syncAllLanguages() {
    if (confirm('{{ __('admin.sync_confirm') }}')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.translations.sync") }}';

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';

        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
