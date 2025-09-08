@extends('layout.master')

@section('main-content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card-light-header">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h1 class="card-title mb-0">
                            <i class="fas fa-comments text-primary me-2"></i>
                            Negoziazione Traduzione
                        </h1>
                        <p class="card-text text-muted">
                            <i class="fas fa-book me-1"></i>{{ $application->gig->poem->title }} •
                            <i class="fas fa-user me-1"></i>{{ $application->gig->user->name }} ↔ {{ $application->user->name }}
                        </p>
                    </div>
                    <div class="text-end">
                        <span class="badge
                            @if($application->status === 'accepted') bg-success
                            @elseif($application->status === 'rejected') bg-danger
                            @else bg-warning
                            @endif fs-6">
                            {{ ucfirst($application->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Chat -->
        <div class="col-lg-8">
            <div class="card hover-effect">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-comments text-primary me-2"></i>
                        Conversazione
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Messaggi -->
                    <div id="messages-container" class="mb-4" style="max-height: 500px; overflow-y: auto;">
                        @forelse($negotiations as $negotiation)
                        <div class="message mb-3 {{ $negotiation->user_id === auth()->id() ? 'text-end' : 'text-start' }}">
                            <div class="d-flex {{ $negotiation->user_id === auth()->id() ? 'justify-content-end' : 'justify-content-start' }}">
                                <div class="message-bubble {{ $negotiation->user_id === auth()->id() ? 'bg-primary text-white' : 'bg-light' }}" style="max-width: 70%; padding: 12px; border-radius: 12px;">
                                    <!-- Header messaggio -->
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <small class="{{ $negotiation->user_id === auth()->id() ? 'text-white-50' : 'text-muted' }}">
                                            <i class="fas fa-user me-1"></i>{{ $negotiation->user->name }}
                                        </small>
                                        <small class="{{ $negotiation->user_id === auth()->id() ? 'text-white-50' : 'text-muted' }}">
                                            {{ $negotiation->created_at->format('d/m H:i') }}
                                        </small>
                                    </div>

                                    <!-- Tipo messaggio -->
                                    @if($negotiation->message_type !== 'info')
                                    <div class="mb-2">
                                        <span class="badge
                                            @if($negotiation->message_type === 'proposal') bg-success
                                            @elseif($negotiation->message_type === 'accept') bg-success
                                            @elseif($negotiation->message_type === 'reject') bg-danger
                                            @elseif($negotiation->message_type === 'counter') bg-warning
                                            @else bg-secondary
                                            @endif">
                                            @switch($negotiation->message_type)
                                                @case('proposal') Proposta @break
                                                @case('accept') Accettato @break
                                                @case('reject') Rifiutato @break
                                                @case('counter') Controproposta @break
                                                @default Info @break
                                            @endswitch
                                        </span>
                                    </div>
                                    @endif

                                    <!-- Contenuto messaggio -->
                                    <p class="mb-2">{{ $negotiation->message }}</p>

                                    <!-- Dettagli proposta -->
                                    @if($negotiation->proposed_compensation || $negotiation->proposed_deadline)
                                    <div class="mt-2 pt-2 border-top">
                                        @if($negotiation->proposed_compensation)
                                        <small class="{{ $negotiation->user_id === auth()->id() ? 'text-white-50' : 'text-muted' }}">
                                            <i class="fas fa-euro-sign me-1"></i>Compenso: {{ number_format($negotiation->proposed_compensation, 2) }} €
                                        </small><br>
                                        @endif
                                        @if($negotiation->proposed_deadline)
                                        <small class="{{ $negotiation->user_id === auth()->id() ? 'text-white-50' : 'text-muted' }}">
                                            <i class="fas fa-calendar me-1"></i>Scadenza: {{ $negotiation->proposed_deadline->format('d/m/Y') }}
                                        </small>
                                        @endif
                                    </div>
                                    @endif

                                    <!-- Pulsanti di azione per proposte -->
                                    @if(($negotiation->message_type === 'proposal' || $negotiation->message_type === 'counter') &&
                                        $negotiation->user_id !== auth()->id() &&
                                        $application->status === 'pending')
                                    <div class="mt-3 pt-2 border-top">
                                        <div class="d-flex gap-2">
                                            <button class="btn btn-success btn-sm" onclick="acceptProposal({{ $application->id }})">
                                                <i class="fas fa-check me-1"></i>Accetta
                                            </button>
                                            <button class="btn btn-danger btn-sm" onclick="rejectProposal({{ $application->id }})">
                                                <i class="fas fa-times me-1"></i>Rifiuta
                                            </button>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center text-muted py-4">
                            <i class="fas fa-comments mb-2" style="font-size: 2rem;"></i>
                            <p>Nessun messaggio ancora. Inizia la conversazione!</p>
                        </div>
                        @endforelse
                    </div>

                    <!-- Form invio messaggio -->
                    @if($application->status === 'pending' || $application->status === 'accepted')
                    <form id="message-form" class="border-top pt-3">
                        @csrf
                        <div class="row">
                            <div class="col-md-3">
                                <label for="message_type" class="form-label">Tipo Messaggio</label>
                                <select name="message_type" id="message_type" class="form-select">
                                    <option value="info">Messaggio</option>
                                    @if($application->gig->user_id === auth()->id())
                                    <option value="proposal">Proposta</option>
                                    <option value="counter">Controproposta</option>
                                    <option value="accept">Accetta</option>
                                    <option value="reject">Rifiuta</option>
                                    @else
                                    <option value="proposal">Proposta</option>
                                    <option value="counter">Controproposta</option>
                                    @endif
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="proposed_compensation" class="form-label">Compenso (€)</label>
                                <input type="number" name="proposed_compensation" id="proposed_compensation"
                                       class="form-control" step="0.01" min="0"
                                       placeholder="Es. 50.00">
                            </div>
                            <div class="col-md-3">
                                <label for="proposed_deadline" class="form-label">Scadenza</label>
                                <input type="date" name="proposed_deadline" id="proposed_deadline"
                                       class="form-control" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-paper-plane me-1"></i>Invia
                                </button>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-12">
                                <label for="message" class="form-label">Messaggio</label>
                                <textarea name="message" id="message" class="form-control" rows="3"
                                          placeholder="Scrivi il tuo messaggio..." required></textarea>
                            </div>
                        </div>
                    </form>
                    @else
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle me-1"></i>
                        Questa negoziazione è stata chiusa.
                    </div>
                    @endif

                    <!-- Pulsante Pagamento per candidatura accettata -->
                    @if($application->status === 'accepted')
                    <div class="border-top pt-3 mt-3">
                        <div class="text-center">
                            <h6 class="text-success mb-3">
                                <i class="fas fa-check-circle me-2"></i>
                                Candidatura Accettata!
                            </h6>
                            <p class="text-muted mb-3">
                                @if($application->gig->user_id === auth()->id())
                                    La candidatura è stata accettata. Procedi con il pagamento per completare l'ordine.
                                @else
                                    La tua candidatura è stata accettata! Il cliente procederà con il pagamento.
                                @endif
                            </p>

                            @if($application->gig->user_id === auth()->id())
                                <!-- Pulsante per il proprietario del gig (cliente) -->
                                <a href="{{ route('translations.payment.show', $application) }}" class="btn btn-success btn-lg">
                                    <i class="fas fa-credit-card me-2"></i>
                                    Procedi al Pagamento
                                </a>
                            @else
                                <!-- Pulsante per il traduttore per vedere lo stato del pagamento -->
                                <a href="{{ route('translations.payment.show', $application) }}" class="btn btn-info btn-lg">
                                    <i class="fas fa-eye me-2"></i>
                                    Vedi Stato Pagamento
                                </a>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Info Poesia -->
            <div class="card hover-effect mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-book text-primary me-2"></i>
                        Poesia
                    </h5>
                </div>
                <div class="card-body">
                    <h6 class="text-primary mb-2">{{ $application->gig->poem->title }}</h6>
                    <p class="text-muted small mb-3">{{ Str::limit($application->gig->poem->content, 150) }}</p>
                    <div class="d-flex flex-wrap gap-1">
                        @foreach($application->gig->target_languages as $lang)
                        <span class="badge bg-primary">{{ strtoupper($lang) }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Info Gig -->
            <div class="card hover-effect mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle text-primary me-2"></i>
                        Dettagli Gig
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <h6 class="text-success mb-0">
                                <i class="fas fa-euro-sign me-1"></i>{{ number_format($application->gig->compensation, 2) }}
                            </h6>
                            <small class="text-muted">Compenso</small>
                        </div>
                        <div class="col-6">
                            <h6 class="text-warning mb-0">
                                <i class="fas fa-clock me-1"></i>{{ $application->gig->deadline->format('d/m') }}
                            </h6>
                            <small class="text-muted">Scadenza</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Partecipanti -->
            <div class="card hover-effect">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users text-primary me-2"></i>
                        Partecipanti
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-grow-1">
                            <h6 class="mb-0">{{ $application->gig->user->name }}</h6>
                            <small class="text-muted">Autore della poesia</small>
                        </div>
                        <span class="badge bg-primary">Autore</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                            <h6 class="mb-0">{{ $application->user->name }}</h6>
                            <small class="text-muted">Traduttore candidato</small>
                        </div>
                        <span class="badge bg-success">Traduttore</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('message-form');
    const messagesContainer = document.getElementById('messages-container');

    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Mostra loading
            Swal.fire({
                title: 'Invio in corso...',
                text: 'Sto inviando il messaggio',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            const formData = new FormData(form);

            fetch('{{ route("translations.negotiation.store", $application) }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Messaggio Inviato!',
                        text: 'Il tuo messaggio è stato inviato con successo',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Errore',
                        text: 'Errore durante l\'invio del messaggio',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Errore',
                    text: 'Errore durante l\'invio del messaggio',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        });
    }

    // Auto-scroll to bottom
    if (messagesContainer) {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }
});

// Funzioni per accettare/rifiutare proposte
function acceptProposal(applicationId) {
    Swal.fire({
        title: 'Conferma Accettazione',
        text: 'Sei sicuro di voler accettare questa proposta?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sì, accetta',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostra loading
            Swal.fire({
                title: 'Elaborazione...',
                text: 'Sto accettando la proposta',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`/translations/negotiation/${applicationId}/accept`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
                redirect: 'follow'
            })
            .then(response => {
                if (response.ok) {
                    // Se la risposta è un redirect, segui il redirect
                    if (response.redirected) {
                        window.location.href = response.url;
                    } else {
                        Swal.fire({
                            title: 'Successo!',
                            text: 'Proposta accettata con successo',
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            location.reload();
                        });
                    }
                } else {
                    Swal.fire({
                        title: 'Errore',
                        text: 'Errore durante l\'accettazione della proposta',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Errore',
                    text: 'Errore durante l\'accettazione della proposta',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        }
    });
}

function rejectProposal(applicationId) {
    Swal.fire({
        title: 'Conferma Rifiuto',
        text: 'Sei sicuro di voler rifiutare questa proposta?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sì, rifiuta',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            // Mostra loading
            Swal.fire({
                title: 'Elaborazione...',
                text: 'Sto rifiutando la proposta',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`/translations/negotiation/${applicationId}/reject`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            })
            .then(response => {
                if (response.ok) {
                    Swal.fire({
                        title: 'Proposta Rifiutata',
                        text: 'La proposta è stata rifiutata',
                        icon: 'info',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Errore',
                        text: 'Errore durante il rifiuto della proposta',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Errore',
                    text: 'Errore durante il rifiuto della proposta',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            });
        }
    });
}
</script>
@endpush
@endsection
