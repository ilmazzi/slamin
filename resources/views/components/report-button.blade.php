@props(['content', 'type', 'size' => 'sm'])

@php
    $isReported = $content->isReportedByUser();
    $reportCount = $content->active_reports_count;
@endphp

<div class="report-button-container">
    @if($isReported)
        <!-- Pulsante per rimuovere la segnalazione -->
        <button type="button"
                class="btn btn-{{ $size }} btn-outline-warning report-remove-btn"
                data-type="{{ $type }}"
                data-id="{{ $content->id }}"
                title="Rimuovi segnalazione">
            <i class="ph-duotone ph-flag-simple"></i>
            <span class="ms-1">Segnalato</span>
        </button>
    @else
        <!-- Pulsante per segnalare -->
        <button type="button"
                class="btn btn-{{ $size }} btn-outline-secondary report-btn"
                data-type="{{ $type }}"
                data-id="{{ $content->id }}"
                title="Segnala contenuto">
            <i class="ph-duotone ph-flag"></i>
            <span class="ms-1">Segnala</span>
        </button>
    @endif

    @if($reportCount > 0)
        <span class="badge bg-warning ms-1" title="{{ $reportCount }} segnalazioni attive">
            {{ $reportCount }}
        </span>
    @endif
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Gestione segnalazione
    $('.report-btn').on('click', function() {
        const type = $(this).data('type');
        const id = $(this).data('id');

        window.location.href = `{{ route('reports.create') }}?type=${type}&id=${id}`;
    });

    // Gestione rimozione segnalazione
    $('.report-remove-btn').on('click', function() {
        const type = $(this).data('type');
        const id = $(this).data('id');
        const button = $(this);

        if (confirm('Sei sicuro di voler rimuovere la tua segnalazione?')) {
            $.ajax({
                url: '{{ route('reports.remove') }}',
                method: 'POST',
                data: {
                    type: type,
                    id: id,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                        // Ricarica la pagina per aggiornare il pulsante
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function() {
                    toastr.error('Errore durante la rimozione della segnalazione');
                }
            });
        }
    });
});
</script>
@endpush
