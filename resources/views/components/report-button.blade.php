@props(['content', 'type', 'size' => 'sm'])

@php
    $isReported = $content->isReportedByUser();
    $reportCount = $content->active_reports_count;
@endphp

<div class="report-button-container">
    @if($isReported)
        <!-- Pulsante per rimuovere la segnalazione -->
        <div class="report-remove-btn"
             data-type="{{ $type }}"
             data-id="{{ $content->id }}"
             title="Rimuovi segnalazione"
             style="cursor: pointer; display: flex; flex-direction: column; align-items: center; gap: 2px; padding: 8px; border-radius: 8px; transition: all 0.2s;"
             onmouseover="this.style.backgroundColor='rgba(0,0,0,0.05)'"
             onmouseout="this.style.backgroundColor='transparent'">
            <i class="ti ti-flag f-s-24 text-warning"></i>
            <span class="text-secondary f-s-12">Segnalato</span>
        </div>
    @else
        <!-- Pulsante per segnalare -->
        <div class="report-btn"
             data-type="{{ $type }}"
             data-id="{{ $content->id }}"
             title="Segnala contenuto"
             style="cursor: pointer; display: flex; flex-direction: column; align-items: center; gap: 2px; padding: 8px; border-radius: 8px; transition: all 0.2s;"
             onmouseover="this.style.backgroundColor='rgba(0,0,0,0.05)'"
             onmouseout="this.style.backgroundColor='transparent'">
            <i class="ti ti-flag f-s-24 text-muted"></i>
            <span class="text-secondary f-s-12">Segnala</span>
        </div>
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
