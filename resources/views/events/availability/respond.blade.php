@extends('layout.master')
@section('title', 'Disponibilità - ' . $event->title)

@section('css')
<style>
/* Mobile-First Responsive Styles for Availability Response */
@media (max-width: 576px) {
    .card-body {
        padding: 1rem !important;
    }
    
    .card-header {
        padding: 0.75rem 1rem !important;
    }
    
    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
    
    .badge {
        font-size: 0.625rem;
    }
    
    .form-floating > label {
        font-size: 0.875rem;
    }
    
    .form-control {
        font-size: 0.875rem;
    }
    
    .btn {
        font-size: 0.875rem;
    }
    
    .btn-group.flex-column .btn {
        width: 100%;
        margin-bottom: 0.5rem;
        border-radius: 0.375rem !important;
    }
    
    .btn-group.flex-column .btn:last-child {
        margin-bottom: 0;
    }
    
    .btn-group.flex-column .btn:first-child {
        border-radius: 0.375rem 0.375rem 0 0 !important;
    }
    
    .btn-group.flex-column .btn:last-child {
        border-radius: 0 0 0.375rem 0.375rem !important;
    }
    
    .legend-item {
        margin-bottom: 1rem;
    }
    
    .legend-item:last-child {
        margin-bottom: 0;
    }
}

@media (min-width: 768px) {
    .btn-group.flex-column .btn {
        width: auto;
        margin-bottom: 0;
        border-radius: 0 !important;
    }
    
    .btn-group.flex-column .btn:first-child {
        border-radius: 0.375rem 0 0 0.375rem !important;
    }
    
    .btn-group.flex-column .btn:last-child {
        border-radius: 0 0.375rem 0.375rem 0 !important;
    }
    
    .legend-item {
        margin-bottom: 0;
    }
}
</style>
@endsection

@section('main-content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card hover-effect">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">
                            <i class="ph ph-calendar-check me-2 text-warning"></i>
                            Indica la tua Disponibilità
                        </h5>
                        <small class="text-muted">{{ $event->title }}</small>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('events.show', $event) }}" class="btn btn-light-primary btn-sm">
                            <i class="ph ph-arrow-left me-1"></i>Torna all'Evento
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Info -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card hover-effect">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-8">
                            <h6 class="mb-2">{{ $event->title }}</h6>
                            <p class="text-muted mb-2 mb-md-0">{{ $event->description }}</p>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="d-flex flex-column flex-md-column gap-2 mb-3 mb-md-0">
                                <div class="d-flex flex-wrap gap-2">
                                    @if($event->availability_deadline)
                                        <span class="badge bg-warning">
                                            <i class="ph ph-clock me-1"></i>
                                            Scadenza: {{ $event->availability_deadline->format('d/m/Y H:i') }}
                                        </span>
                                    @endif
                                    <span class="badge bg-info">
                                        <i class="ph ph-list-checks me-1"></i>
                                        {{ $event->activeAvailabilityOptions->count() }} Opzioni
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Instructions -->
    @if($event->availability_instructions)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-info">
                <div class="card-header bg-light-info">
                    <h6 class="mb-0">
                        <i class="ph ph-info me-2"></i>Istruzioni
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $event->availability_instructions }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Legend -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-light">
                <div class="card-body">
                    <h6 class="mb-3">
                        <i class="ph ph-info-circle me-2"></i>Legenda
                    </h6>
                    <div class="row g-3">
                        <div class="col-12 col-md-4">
                            <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                                <button class="btn btn-success btn-sm me-2" disabled>
                                    <i class="ph ph-heart"></i>
                                </button>
                                <span class="text-center text-md-start"><strong>Preferita</strong> - Questa è la mia scelta migliore</span>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                                <button class="btn btn-warning btn-sm me-2" disabled>
                                    <i class="ph ph-check-circle"></i>
                                </button>
                                <span class="text-center text-md-start"><strong>Disponibile</strong> - Posso partecipare</span>
                            </div>
                        </div>
                        <div class="col-12 col-md-4">
                            <div class="d-flex align-items-center justify-content-center justify-content-md-start">
                                <button class="btn btn-danger btn-sm me-2" disabled>
                                    <i class="ph ph-x-circle"></i>
                                </button>
                                <span class="text-center text-md-start"><strong>Non disponibile</strong> - Non posso partecipare</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Availability Options -->
    <div class="row">
        <div class="col-12">
            <div class="card hover-effect">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph ph-list-checks me-2"></i>
                        Opzioni di Disponibilità
                    </h6>
                </div>
                <div class="card-body">
                    @if($event->activeAvailabilityOptions->count() > 0)
                        <form id="availabilityForm">
                            @csrf
                            <div class="row">
                                @foreach($event->activeAvailabilityOptions as $option)
                                    <div class="col-12 col-md-6 col-lg-4 mb-4">
                                        <div class="card border-light hover-effect">
                                            <div class="card-body">
                                                <h6 class="card-title mb-3">
                                                    <i class="ph ph-calendar me-2 text-primary"></i>
                                                    {{ $option->formatted_datetime }}
                                                </h6>

                                                @if($option->description)
                                                    <p class="text-muted small mb-3">{{ $option->description }}</p>
                                                @endif

                                                <div class="d-grid gap-2">
                                                    <div class="btn-group d-flex flex-column flex-md-row" role="group">
                                                        <input type="radio"
                                                               class="btn-check"
                                                               name="option_{{ $option->id }}"
                                                               id="preferred_{{ $option->id }}"
                                                               value="preferred"
                                                               {{ isset($responsesMap[$option->id]) && $responsesMap[$option->id]->status === 'preferred' ? 'checked' : '' }}>
                                                        <label class="btn btn-outline-success" for="preferred_{{ $option->id }}">
                                                            <i class="ph ph-heart me-1"></i>Preferita
                                                        </label>

                                                        <input type="radio"
                                                               class="btn-check"
                                                               name="option_{{ $option->id }}"
                                                               id="available_{{ $option->id }}"
                                                               value="available"
                                                               {{ isset($responsesMap[$option->id]) && $responsesMap[$option->id]->status === 'available' ? 'checked' : '' }}>
                                                        <label class="btn btn-outline-warning" for="available_{{ $option->id }}">
                                                            <i class="ph ph-check-circle me-1"></i>Disponibile
                                                        </label>

                                                        <input type="radio"
                                                               class="btn-check"
                                                               name="option_{{ $option->id }}"
                                                               id="unavailable_{{ $option->id }}"
                                                               value="unavailable"
                                                               {{ isset($responsesMap[$option->id]) && $responsesMap[$option->id]->status === 'unavailable' ? 'checked' : '' }}>
                                                        <label class="btn btn-outline-danger" for="unavailable_{{ $option->id }}">
                                                            <i class="ph ph-x-circle me-1"></i>Non disponibile
                                                        </label>
                                                    </div>

                                                    <div class="form-floating">
                                                        <textarea class="form-control"
                                                                  id="notes_{{ $option->id }}"
                                                                  name="notes_{{ $option->id }}"
                                                                  placeholder="Note opzionali..."
                                                                  style="height: 80px">{{ isset($responsesMap[$option->id]) ? $responsesMap[$option->id]->notes : '' }}</textarea>
                                                        <label for="notes_{{ $option->id }}">Note (opzionali)</label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="ph ph-check me-2"></i>Salva Disponibilità
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="text-center py-5">
                            <i class="ph ph-calendar-x fs-1 text-muted mb-3"></i>
                            <h6 class="text-muted">Nessuna opzione di disponibilità</h6>
                            <p class="text-muted">L'organizzatore non ha ancora aggiunto opzioni di disponibilità per questo evento.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('availabilityForm');
    if (!form) {
        console.log('Form not found, skipping availability form setup');
        return;
    }

    console.log('Form found, setting up availability form');

    form.addEventListener('submit', function(e) {
    e.preventDefault();
    console.log('Form submitted, collecting responses...');

    const formData = new FormData(this);
    const responses = [];

    // Raccogli tutte le risposte
    const optionInputs = document.querySelectorAll('input[name^="option_"]:checked');
    optionInputs.forEach(input => {
        const optionId = input.name.replace('option_', '');
        const notesElement = document.getElementById('notes_' + optionId);
        const notes = notesElement ? notesElement.value : '';

        responses.push({
            option_id: parseInt(optionId),
            status: input.value,
            notes: notes
        });
    });

    console.log('Collected responses:', responses);

    if (responses.length === 0) {
        alert('Seleziona almeno un\'opzione di disponibilità.');
        return;
    }

    console.log('Sending AJAX request...');
    // Invia la richiesta
    fetch('{{ route("events.availability.store-response", $event) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ responses: responses })
    })
    .then(response => {
        console.log('Response status:', response.status);
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            // Mostra messaggio di successo
            Swal.fire({
                icon: 'success',
                title: 'Disponibilità salvata!',
                text: 'Le tue preferenze sono state salvate con successo.',
                confirmButtonText: 'OK'
            }).then(() => {
                // Ricarica la pagina per mostrare le risposte salvate
                location.reload();
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Errore',
                text: data.message || 'Errore durante il salvataggio della disponibilità.',
                confirmButtonText: 'OK'
            });
        }
    })
    .catch(error => {
        console.error('AJAX Error:', error);
        console.error('Error details:', error.message);
        Swal.fire({
            icon: 'error',
            title: 'Errore',
            text: 'Errore durante il salvataggio della disponibilità.',
            confirmButtonText: 'OK'
        });
    });
    }); // Chiusura della funzione form.addEventListener

    // Aggiungi feedback visivo quando si seleziona un'opzione
    document.querySelectorAll('input[type="radio"]').forEach(radio => {
        radio.addEventListener('change', function() {
            // Rimuovi la classe 'selected' da tutti i label dello stesso gruppo
            const groupName = this.name;
            document.querySelectorAll(`input[name="${groupName}"]`).forEach(r => {
                r.nextElementSibling.classList.remove('selected');
            });

            // Aggiungi la classe 'selected' al label selezionato
            this.nextElementSibling.classList.add('selected');
        });
    });

    // Inizializza lo stato visivo delle opzioni già selezionate
    document.querySelectorAll('input[type="radio"]:checked').forEach(radio => {
        radio.nextElementSibling.classList.add('selected');
    });
});
</script>
@endpush

<style>
.btn-check:checked + .btn {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.btn.selected {
    transform: scale(1.05);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

.card.hover-effect:hover {
    transform: translateY(-2px);
    transition: transform 0.2s ease;
}
</style>


