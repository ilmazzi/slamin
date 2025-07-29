@extends('layout.app')

@section('title', 'Segnala Contenuto')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card hover-effect">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="ph-duotone ph-flag me-2"></i>
                        Segnala Contenuto
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Informazioni sul contenuto -->
                    <div class="alert alert-info">
                        <h6 class="alert-heading">Contenuto da segnalare:</h6>
                        <p class="mb-0">
                            <strong>{{ $content->getContentTypeAttribute() }}:</strong>
                            {{ $content->getReportableTitleAttribute() }}
                        </p>
                    </div>

                    <form id="reportForm" method="POST" action="{{ route('reports.store') }}">
                        @csrf
                        <input type="hidden" name="type" value="{{ $type }}">
                        <input type="hidden" name="id" value="{{ $id }}">

                        <!-- Ragione della segnalazione -->
                        <div class="mb-3">
                            <label for="reason" class="form-label">Motivo della segnalazione *</label>
                            <select name="reason" id="reason" class="form-select" required>
                                <option value="">Seleziona un motivo</option>
                                @foreach(App\Http\Controllers\ReportController::getReasons() as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('reason')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Descrizione -->
                        <div class="mb-3">
                            <label for="description" class="form-label">Descrizione (opzionale)</label>
                            <textarea name="description" id="description" class="form-control" rows="4"
                                      placeholder="Fornisci ulteriori dettagli sulla segnalazione..."></textarea>
                            <div class="form-text">
                                Aiutaci a capire meglio il problema. La tua descrizione sarà visibile solo agli amministratori.
                            </div>
                            @error('description')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Pulsanti -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-danger">
                                <i class="ph-duotone ph-flag me-2"></i>
                                {{ __('videos.send') }} Segnalazione
                            </button>
                            <a href="{{ url()->previous() }}" class="btn btn-secondary">
                                <i class="ph-duotone ph-x me-2"></i>
                                Annulla
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Informazioni aggiuntive -->
            <div class="card hover-effect mt-3">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="ph-duotone ph-info me-2"></i>
                        Informazioni sulla segnalazione
                    </h6>
                    <ul class="list-unstyled mb-0">
                        <li><i class="ph-duotone ph-check-circle text-success me-2"></i>Le segnalazioni sono anonime per gli altri utenti</li>
                        <li><i class="ph-duotone ph-check-circle text-success me-2"></i>Gli amministratori esamineranno ogni segnalazione</li>
                        <li><i class="ph-duotone ph-check-circle text-success me-2"></i>Puoi segnalare un contenuto una sola volta</li>
                        <li><i class="ph-duotone ph-check-circle text-success me-2"></i>Le segnalazioni false possono portare a sanzioni</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#reportForm').on('submit', function(e) {
        e.preventDefault();

        const form = $(this);
        const submitBtn = form.find('button[type="submit"]');
        const originalText = submitBtn.html();

        // Disabilita il pulsante
        submitBtn.prop('disabled', true).html('<i class="ph-duotone ph-spinner ph-spin me-2"></i>Invio in corso...');

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    toastr.success(response.message);
                    setTimeout(() => {
                        window.history.back();
                    }, 1500);
                } else {
                    toastr.error(response.message);
                    submitBtn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                if (response && response.message) {
                    toastr.error(response.message);
                } else {
                    toastr.error('Errore durante l\'invio della segnalazione');
                }
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });
});
</script>
@endpush
