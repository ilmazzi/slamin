@extends('layout.master')

@section('main-content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1">
                        <i class="ph-duotone ph-list-bullets text-primary me-2"></i>
                        {{ __('admin.translation_queue') }}
                    </h1>
                    <p class="text-muted mb-0">{{ __('admin.translation_queue_description') }}</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.translations.index') }}" class="btn btn-outline-secondary">
                        <i class="ph-duotone ph-arrow-left me-1"></i>
                        {{ __('admin.back_to_translations') }}
                    </a>
                    <button type="button" class="btn btn-outline-warning" onclick="cleanProcessed()">
                        <i class="ph-duotone ph-trash me-1"></i>
                        {{ __('admin.clean_processed') }}
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
                                <p class="text-muted mb-0 f-s-14">{{ __('admin.total_items') }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="mb-1 text-warning">{{ $stats['pending'] }}</h4>
                                <p class="text-muted mb-0 f-s-14">{{ __('admin.pending_items') }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="border-end">
                                <h4 class="mb-1 text-success">{{ $stats['processed'] }}</h4>
                                <p class="text-muted mb-0 f-s-14">{{ __('admin.processed_items') }}</p>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <h4 class="mb-1 text-info">{{ $stats['recent'] }}</h4>
                            <p class="text-muted mb-0 f-s-14">{{ __('admin.recent_items') }}</p>
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
                    <form method="GET" action="{{ route('admin.translations.queue') }}" id="filterForm">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="status" class="form-label f-s-14">{{ __('admin.status') }}</label>
                                <select name="status" id="status" class="form-select form-select-sm">
                                    <option value="">{{ __('admin.all_statuses') }}</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                        {{ __('admin.pending') }}
                                    </option>
                                    <option value="processed" {{ request('status') == 'processed' ? 'selected' : '' }}>
                                        {{ __('admin.processed') }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="context" class="form-label f-s-14">{{ __('admin.context') }}</label>
                                <input type="text" name="context" id="context" class="form-control form-control-sm"
                                       placeholder="{{ __('admin.search_by_context') }}"
                                       value="{{ request('context') }}">
                            </div>
                            <div class="col-md-4">
                                <label for="search" class="form-label f-s-14">{{ __('admin.search') }}</label>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text"><i class="ph-duotone ph-magnifying-glass f-s-12"></i></span>
                                    <input type="text" name="search" id="search" class="form-control"
                                           placeholder="{{ __('admin.search_in_texts') }}"
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
                                    <a href="{{ route('admin.translations.queue') }}" class="btn btn-outline-secondary btn-sm">
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

    <!-- Tabella Coda -->
    <div class="row">
        <div class="col-12">
            <div class="card hover-effect">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ph-duotone ph-list-bullets me-2"></i>
                        {{ __('admin.queue_items') }}
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($queue->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 5%;">{{ __('admin.status') }}</th>
                                        <th style="width: 40%;">{{ __('admin.original_text') }}</th>
                                        <th style="width: 15%;">{{ __('admin.context') }}</th>
                                        <th style="width: 20%;">{{ __('admin.file_location') }}</th>
                                        <th style="width: 10%;">{{ __('admin.created_at') }}</th>
                                        <th style="width: 10%;">{{ __('admin.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($queue as $item)
                                    <tr class="{{ $item->isProcessed() ? 'table-success' : '' }}">
                                        <td class="text-center">
                                            @if($item->isProcessed())
                                                <i class="ph-duotone ph-check-circle text-success f-s-16"
                                                   title="{{ __('admin.processed') }}"></i>
                                            @else
                                                <i class="ph-duotone ph-clock text-warning f-s-16"
                                                   title="{{ __('admin.pending') }}"></i>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="text-muted f-s-12" style="max-height: 60px; overflow-y: auto;">
                                                {{ Str::limit($item->original_text, 150) }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary f-s-11">{{ $item->context ?: '-' }}</span>
                                        </td>
                                        <td>
                                            <small class="text-muted f-s-11">
                                                {{ $item->relative_path ?: '-' }}
                                                @if($item->line_number)
                                                    <br><span class="text-info">Linea {{ $item->line_number }}</span>
                                                @endif
                                            </small>
                                        </td>
                                        <td>
                                            <small class="text-muted f-s-11">
                                                {{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : 'N/A' }}
                                            </small>
                                        </td>
                                        <td>
                                            @if(!$item->isProcessed())
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <button type="button" class="btn btn-outline-primary"
                                                            onclick="convertToTranslation({{ $item->id }})"
                                                            title="{{ __('admin.convert_to_translation') }}">
                                                        <i class="ph-duotone ph-arrow-right f-s-14"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-outline-success"
                                                            onclick="markAsProcessed({{ $item->id }})"
                                                            title="{{ __('admin.mark_as_processed') }}">
                                                        <i class="ph-duotone ph-check f-s-14"></i>
                                                    </button>
                                                </div>
                                            @else
                                                <span class="text-muted f-s-12">{{ __('admin.processed') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginazione -->
                        <div class="d-flex justify-content-between align-items-center p-3 border-top">
                            <div class="text-muted f-s-14">
                                {{ __('admin.showing') }} {{ $queue->firstItem() }} {{ __('admin.to') }} {{ $queue->lastItem() }}
                                {{ __('admin.of') }} {{ $queue->total() }} {{ __('admin.results') }}
                            </div>
                            <div>
                                {{ $queue->appends(request()->query())->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ph-duotone ph-list-bullets f-s-48 text-muted mb-3"></i>
                            <h5 class="text-muted">{{ __('admin.no_queue_items') }}</h5>
                            <p class="text-muted">{{ __('admin.no_queue_items_description') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modali -->
@include('admin.translations.modals.convert')
@include('admin.translations.modals.mark-processed')

<script>
// Funzioni JavaScript per le azioni
function convertToTranslation(id) {
    // Mostra modale di conversione
    const modal = new bootstrap.Modal(document.getElementById('convertModal'));
    modal.show();

    // Imposta l'ID dell'elemento
    document.getElementById('convertItemId').value = id;
}

function markAsProcessed(id) {
    if (confirm('{{ __('admin.mark_as_processed_confirm') }}')) {
        fetch('{{ route("admin.translations.mark-processed") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: id })
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
                }).then(() => {
                    location.reload();
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

function cleanProcessed() {
    if (confirm('{{ __('admin.clean_processed_confirm') }}')) {
        fetch('{{ route("admin.translations.clean-processed") }}', {
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
                }).then(() => {
                    location.reload();
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
document.getElementById('status').addEventListener('change', function() {
    document.getElementById('filterForm').submit();
});
</script>
@endsection
