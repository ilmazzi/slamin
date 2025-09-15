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
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.translations.create') }}" class="btn btn-primary">
                        <i class="ph-duotone ph-plus me-1"></i>
                        {{ __('admin.add_translation') }}
                    </a>
                    <a href="{{ route('admin.translations.queue') }}" class="btn btn-outline-info">
                        <i class="ph-duotone ph-list-bullets me-1"></i>
                        {{ __('admin.translation_queue') }}
                    </a>
                    <button type="button" class="btn btn-outline-warning" onclick="clearCache()">
                        <i class="ph-duotone ph-trash me-1"></i>
                        {{ __('admin.clear_cache') }}
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
                                <h4 class="mb-1 text-primary">{{ $stats['total'] }}</h4>
                                <p class="text-muted mb-0 f-s-14">{{ __('admin.total_translations') }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="mb-1 text-success">{{ $stats['groups'] }}</h4>
                                <p class="text-muted mb-0 f-s-14">{{ __('admin.translation_groups') }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="mb-1 text-info">{{ $stats['locales'] }}</h4>
                                <p class="text-muted mb-0 f-s-14">{{ __('admin.available_locales') }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <h4 class="mb-1 text-warning">{{ $stats['recent'] }}</h4>
                            <p class="text-muted mb-0 f-s-14">{{ __('admin.recent_translations') }}</p>
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
                    <form method="GET" action="{{ route('admin.translations.index') }}" id="filterForm">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="group" class="form-label f-s-14">{{ __('admin.group') }}</label>
                                <select name="group" id="group" class="form-select form-select-sm">
                                    <option value="">{{ __('admin.all_groups') }}</option>
                                    @foreach($groups as $groupName)
                                        <option value="{{ $groupName }}" {{ request('group') == $groupName ? 'selected' : '' }}>
                                            {{ $groupName }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="locale" class="form-label f-s-14">{{ __('admin.locale') }}</label>
                                <select name="locale" id="locale" class="form-select form-select-sm">
                                    <option value="">{{ __('admin.all_locales') }}</option>
                                    @foreach($locales as $localeCode)
                                        <option value="{{ $localeCode }}" {{ request('locale') == $localeCode ? 'selected' : '' }}>
                                            {{ strtoupper($localeCode) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="search" class="form-label f-s-14">{{ __('admin.search') }}</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="ph-duotone ph-magnifying-glass f-s-12"></i></span>
                                    <input type="text" name="search" id="search" class="form-control"
                                           placeholder="{{ __('admin.search_translations') }}"
                                           value="{{ request('search') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label f-s-14">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="ph-duotone ph-magnifying-glass me-1"></i>
                                        {{ __('admin.filter') }}
                                    </button>
                                    <a href="{{ route('admin.translations.index') }}" class="btn btn-outline-secondary btn-sm">
                                        <i class="ph-duotone ph-arrow-clockwise me-1"></i>
                                        {{ __('admin.reset') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabella Traduzioni -->
    <div class="row">
        <div class="col-12">
            <div class="card hover-effect">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ph-duotone ph-list-bullets me-2"></i>
                        {{ __('admin.translations_list') }}
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($translations && $translations->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 20%;">
                                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'group_name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}"
                                               class="text-decoration-none text-dark">
                                                {{ __('admin.group') }}
                                                @if(request('sort') == 'group_name')
                                                    <i class="ph-duotone ph-arrow-{{ request('direction') == 'asc' ? 'up' : 'down' }} f-s-12"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th style="width: 20%;">
                                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'key_name', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}"
                                               class="text-decoration-none text-dark">
                                                {{ __('admin.key') }}
                                                @if(request('sort') == 'key_name')
                                                    <i class="ph-duotone ph-arrow-{{ request('direction') == 'asc' ? 'up' : 'down' }} f-s-12"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th style="width: 10%;">
                                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'locale', 'direction' => request('direction') == 'asc' ? 'desc' : 'asc']) }}"
                                               class="text-decoration-none text-dark">
                                                {{ __('admin.locale') }}
                                                @if(request('sort') == 'locale')
                                                    <i class="ph-duotone ph-arrow-{{ request('direction') == 'asc' ? 'up' : 'down' }} f-s-12"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th style="width: 35%;">{{ __('admin.value') }}</th>
                                        <th style="width: 15%;">{{ __('admin.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($translations as $translation)
                                    <tr>
                                        <td>
                                            <code class="text-primary f-s-12">{{ $translation->group_name }}</code>
                                        </td>
                                        <td>
                                            <code class="text-secondary f-s-12">{{ $translation->key_name }}</code>
                                        </td>
                                        <td>
                                            <span class="badge bg-primary">{{ strtoupper($translation->locale) }}</span>
                                        </td>
                                        <td>
                                            <div class="text-muted f-s-12" style="max-height: 60px; overflow-y: auto;">
                                                {{ Str::limit($translation->value, 100) }}
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-outline-primary"
                                                        onclick="editTranslation({{ $translation->id }})"
                                                        title="{{ __('admin.edit') }}">
                                                    <i class="ph-duotone ph-pencil f-s-14"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-info"
                                                        onclick="viewTranslation({{ $translation->id }})"
                                                        title="{{ __('admin.view') }}">
                                                    <i class="ph-duotone ph-eye f-s-14"></i>
                                                </button>
                                                <button type="button" class="btn btn-outline-danger"
                                                        onclick="deleteTranslation({{ $translation->id }})"
                                                        title="{{ __('admin.delete') }}">
                                                    <i class="ph-duotone ph-trash f-s-14"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginazione -->
                        <div class="d-flex justify-content-between align-items-center p-3 border-top">
                            <div class="text-muted f-s-14">
                                {{ __('admin.showing') }} {{ $translations->firstItem() }} {{ __('admin.to') }} {{ $translations->lastItem() }}
                                {{ __('admin.of') }} {{ $translations->total() }} {{ __('admin.results') }}
                            </div>
                            <div>
                                {{ $translations->appends(request()->query())->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ph-duotone ph-translate f-s-48 text-muted mb-3"></i>
                            <h5 class="text-muted">{{ __('admin.no_translations_found') }}</h5>
                            <p class="text-muted">{{ __('admin.no_translations_description') }}</p>
                            <a href="{{ route('admin.translations.create') }}" class="btn btn-primary">
                                <i class="ph-duotone ph-plus me-1"></i>
                                {{ __('admin.add_first_translation') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modali -->
@include('admin.translations.modals.edit')
@include('admin.translations.modals.view')
@include('admin.translations.modals.delete')

<script>
// Funzioni JavaScript per le azioni
function editTranslation(id) {
    // Implementazione modale di modifica
    console.log('Edit translation:', id);
}

function viewTranslation(id) {
    // Implementazione modale di visualizzazione
    console.log('View translation:', id);
}

function deleteTranslation(id) {
    // Implementazione modale di eliminazione
    console.log('Delete translation:', id);
}

function clearCache() {
    if (confirm('{{ __('admin.clear_cache_confirm') }}')) {
        fetch('{{ route("admin.translations.clear-cache") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: '{{ __('admin.success') }}',
                    text: data.message,
                    timer: 2000,
                    showConfirmButton: false
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: '{{ __('admin.error') }}',
                    text: data.message
                });
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: '{{ __('admin.error') }}',
                text: '{{ __('admin.unknown_error') }}'
            });
        });
    }
}

// Auto-submit del form quando cambiano i filtri
document.getElementById('group').addEventListener('change', function() {
    document.getElementById('filterForm').submit();
});

document.getElementById('locale').addEventListener('change', function() {
    document.getElementById('filterForm').submit();
});
</script>
@endsection
