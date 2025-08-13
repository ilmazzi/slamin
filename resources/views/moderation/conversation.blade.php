@extends('layout.master')

@section('title', 'Conversazione Moderazione')

@section('main-content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-0 f-w-600">
                        <i class="ph-duotone ph-chat-circle me-2"></i>
                        Conversazione Moderazione
                    </h4>
                    <p class="text-muted mb-0">
                        Segnalazione: {{ $report->content_type }} "{{ $report->content_title }}"
                    </p>
                </div>
                <div>
                    @if($isModerator)
                        <a href="{{ route('admin.moderation.pending') }}" class="btn btn-outline-secondary me-2">
                            <i class="ph-duotone ph-arrow-left me-2"></i>
                            Torna alla Moderazione
                        </a>
                    @endif
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                        <i class="ph-duotone ph-house me-2"></i>
                        Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Informazioni sulla segnalazione -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph-duotone ph-info me-2"></i>
                        Dettagli Segnalazione
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Contenuto:</strong> {{ $report->content_type }}
                            </div>
                            <div class="mb-3">
                                <strong>Titolo:</strong> {{ $report->content_title }}
                            </div>
                            <div class="mb-3">
                                <strong>Motivo:</strong> 
                                <span class="badge bg-warning">{{ $report->reason_text }}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Segnalato da:</strong> {{ $report->user->name }}
                            </div>
                            <div class="mb-3">
                                <strong>Data:</strong> {{ $report->created_at->format('d/m/Y H:i') }}
                            </div>
                            <div class="mb-3">
                                <strong>Status:</strong> 
                                <span class="badge bg-{{ $report->status === 'pending' ? 'warning' : ($report->status === 'investigating' ? 'info' : 'success') }}">
                                    {{ ucfirst($report->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @if($report->description)
                        <div class="mt-3">
                            <strong>Descrizione:</strong>
                            <p class="mb-0 mt-2">{{ $report->description }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Conversazione -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph-duotone ph-chat-circle me-2"></i>
                        Timeline Conversazione
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Timeline dei messaggi -->
                    <div id="messages-timeline" class="mb-4" style="max-height: 500px; overflow-y: auto;">
                        @foreach($messages as $message)
                            <div class="message-item mb-3 {{ $message->is_internal ? 'internal-message' : '' }}">
                                <div class="d-flex align-items-start">
                                    <div class="avatar-sm me-3">
                                        <div class="avatar-md bg-light d-flex align-items-center justify-content-center">
                                            <i class="{{ $message->icon }} {{ $message->type_class }} f-s-16"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <div>
                                                <strong class="{{ $message->type_class }}">{{ $message->author_name }}</strong>
                                                @if($message->is_internal)
                                                    <span class="badge bg-secondary ms-2">Interno</span>
                                                @endif
                                            </div>
                                            <small class="text-muted">{{ $message->created_at->format('d/m/Y H:i') }}</small>
                                        </div>
                                        <div class="message-content p-3 rounded {{ $message->is_author ? 'bg-primary text-white' : ($message->is_moderator || $message->is_admin ? 'bg-light' : 'bg-secondary text-white') }}">
                                            {!! nl2br(e($message->message)) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Form per inviare messaggio -->
                    <div class="message-form">
                        <form id="message-form" class="mt-4">
                            @csrf
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <textarea 
                                            id="message-text" 
                                            name="message" 
                                            class="form-control" 
                                            rows="3" 
                                            placeholder="Scrivi il tuo messaggio..."
                                            maxlength="2000"
                                            required
                                        ></textarea>
                                        <div class="form-text">
                                            <span id="char-count">0</span>/2000 caratteri
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    @if($isModerator)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" id="is-internal" name="is_internal">
                                            <label class="form-check-label" for="is-internal">
                                                Messaggio interno
                                            </label>
                                        </div>
                                    @endif
                                    <button type="submit" class="btn btn-primary w-100" id="send-button">
                                        <i class="ph-duotone ph-paper-plane-right me-2"></i>
                                        Invia
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const messageForm = document.getElementById('message-form');
    const messageText = document.getElementById('message-text');
    const charCount = document.getElementById('char-count');
    const sendButton = document.getElementById('send-button');
    const messagesTimeline = document.getElementById('messages-timeline');
    const isInternal = document.getElementById('is-internal');

    // Contatore caratteri
    messageText.addEventListener('input', function() {
        const count = this.value.length;
        charCount.textContent = count;
        
        if (count > 1800) {
            charCount.classList.add('text-danger');
        } else {
            charCount.classList.remove('text-danger');
        }
    });

    // Invia messaggio
    messageForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const message = messageText.value.trim();
        if (!message) return;

        // Disabilita il pulsante
        sendButton.disabled = true;
        sendButton.innerHTML = '<i class="ph-duotone ph-spinner ph-spin me-2"></i>Invio...';

        // Prepara i dati
        const formData = new FormData();
        formData.append('message', message);
        formData.append('_token', '{{ csrf_token() }}');
        
        if (isInternal && isInternal.checked) {
            formData.append('is_internal', '1');
        }

        // Invia richiesta
        fetch('{{ route("moderation.conversation.message", $report->id) }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Aggiungi il messaggio alla timeline
                addMessageToTimeline(data.data.message);
                
                // Pulisci il form
                messageText.value = '';
                charCount.textContent = '0';
                charCount.classList.remove('text-danger');
                
                if (isInternal) {
                    isInternal.checked = false;
                }
                
                // Scroll to bottom
                messagesTimeline.scrollTop = messagesTimeline.scrollHeight;
            } else {
                showNotification('Errore: ' + data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Errore durante l\'invio del messaggio', 'error');
        })
        .finally(() => {
            // Riabilita il pulsante
            sendButton.disabled = false;
            sendButton.innerHTML = '<i class="ph-duotone ph-paper-plane-right me-2"></i>Invia';
        });
    });

    // Funzione per aggiungere messaggio alla timeline
    function addMessageToTimeline(messageData) {
        const messageHtml = `
            <div class="message-item mb-3 ${messageData.is_internal ? 'internal-message' : ''}">
                <div class="d-flex align-items-start">
                    <div class="avatar-sm me-3">
                        <div class="avatar-md bg-light d-flex align-items-center justify-content-center">
                            <i class="${messageData.icon} ${messageData.type_class} f-s-16"></i>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <div>
                                <strong class="${messageData.type_class}">${messageData.author_name}</strong>
                                ${messageData.is_internal ? '<span class="badge bg-secondary ms-2">Interno</span>' : ''}
                            </div>
                            <small class="text-muted">${messageData.created_at}</small>
                        </div>
                        <div class="message-content p-3 rounded ${messageData.type === 'author' ? 'bg-primary text-white' : (messageData.type === 'moderator' || messageData.type === 'admin' ? 'bg-light' : 'bg-secondary text-white')}">
                            ${messageData.message.replace(/\n/g, '<br>')}
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        messagesTimeline.insertAdjacentHTML('beforeend', messageHtml);
    }

    // Funzione per mostrare notifiche
    function showNotification(message, type = 'success') {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: type === 'success' ? 'Successo!' : 'Errore!',
                text: message,
                icon: type,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        } else {
            alert(message);
        }
    }

    // Scroll to bottom on load
    messagesTimeline.scrollTop = messagesTimeline.scrollHeight;
});
</script>

<style>
.internal-message {
    opacity: 0.8;
    border-left: 3px solid #6c757d;
    padding-left: 10px;
}

.message-content {
    word-wrap: break-word;
}

#messages-timeline::-webkit-scrollbar {
    width: 6px;
}

#messages-timeline::-webkit-scrollbar-track {
    background: #f1f1f1;
}

#messages-timeline::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 3px;
}

#messages-timeline::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
@endpush
@endsection
