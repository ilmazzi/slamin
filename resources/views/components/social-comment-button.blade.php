@props(['content', 'type' => 'content', 'size' => 'md'])

@php
    $commentCount = $content->comment_count ?? 0;
    $contentType = strtolower(class_basename($content));
    $uniqueId = $contentType . '_' . $content->id;

    // Dimensioni
    $sizeStyles = [
        'sm' => 'min-width: 50px; padding: 6px; gap: 2px;',
        'md' => 'min-width: 60px; padding: 8px; gap: 2px;',
        'lg' => 'min-width: 70px; padding: 10px; gap: 2px;'
    ];
    $iconSizes = [
        'sm' => 'f-s-16',
        'md' => 'f-s-20',
        'lg' => 'f-s-24'
    ];
    $textSizes = [
        'sm' => 'f-s-10',
        'md' => 'f-s-12',
        'lg' => 'f-s-14'
    ];
    $buttonStyle = $sizeStyles[$size] ?? $sizeStyles['md'];
    $iconClass = $iconSizes[$size] ?? $iconSizes['md'];
    $textClass = $textSizes[$size] ?? $textSizes['md'];
@endphp

<div class="social-comment-btn"
     data-content-type="{{ $contentType }}"
     data-content-id="{{ $content->id }}"
     data-unique-id="{{ $uniqueId }}"
     onclick="showCommentsModal_{{ $uniqueId }}(event)"
     title="{{ __('common.comments') }}"
     style="cursor: pointer; display: flex; flex-direction: column; align-items: center; border-radius: 8px; transition: all 0.2s; {{ $buttonStyle }}"
     onmouseover="this.style.backgroundColor='rgba(0,0,0,0.05)'"
     onmouseout="this.style.backgroundColor='transparent'">
    <i class="ph-duotone ph-chat-circle {{ $iconClass }}"></i>
    <span class="comment-count {{ $textClass }}">{{ number_format($commentCount) }}</span>
</div>

<script>
// Funzione unica per questo componente
function showCommentsModal_{{ $uniqueId }}(event) {
    event.stopPropagation(); // Previene l'apertura del modal
    openCommentsModal_{{ $uniqueId }}('{{ $contentType }}', {{ $content->id }});
}

// Apre il modal dei commenti
async function openCommentsModal_{{ $uniqueId }}(mediaType, mediaId) {
    const modalId = 'commentsModal_{{ $uniqueId }}';

    // Crea il modal se non esiste
    if (!document.getElementById(modalId)) {
        createCommentsModal_{{ $uniqueId }}();
    }

    // Imposta i valori nel form
    document.getElementById('commentMediaType_{{ $uniqueId }}').value = mediaType;
    document.getElementById('commentMediaId_{{ $uniqueId }}').value = mediaId;

    // Aggiorna il titolo del modal
    const modalTitle = document.getElementById('commentsModalLabel_{{ $uniqueId }}');
    const typeNames = {
        'video': 'Video',
        'photo': 'Foto',
        'article': 'Articolo',
        'poem': 'Poesia',
        'event': 'Evento'
    };
    modalTitle.innerHTML = `<i class="ph-duotone ph-chat-circle me-2"></i>Commenti ${typeNames[mediaType] || mediaType}`;

    // Mostra loading
    document.getElementById('commentsLoading_{{ $uniqueId }}').style.display = 'block';
    document.getElementById('commentsError_{{ $uniqueId }}').style.display = 'none';
    document.getElementById('commentsContainer_{{ $uniqueId }}').style.display = 'none';

    // Apri il modal
    const modal = new bootstrap.Modal(document.getElementById(modalId));
    modal.show();

    // Carica i commenti
    await loadComments_{{ $uniqueId }}(mediaType, mediaId);
}

// Carica i commenti usando il sistema unificato
async function loadComments_{{ $uniqueId }}(mediaType, mediaId) {
    try {
        const response = await fetch(`/api/social/comments?commentable_type=${mediaType}&commentable_id=${mediaId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const data = await response.json();

        if (response.ok && data.success) {
            displayComments_{{ $uniqueId }}(data.comments);
        } else {
            throw new Error(data.message || 'Errore nel caricamento dei commenti');
        }
    } catch (error) {
        console.error('Errore caricamento commenti:', error);
        showCommentsError_{{ $uniqueId }}(error.message);
    } finally {
        document.getElementById('commentsLoading_{{ $uniqueId }}').style.display = 'none';
    }
}

// Visualizza i commenti
function displayComments_{{ $uniqueId }}(comments) {
    const commentsList = document.getElementById('commentsList_{{ $uniqueId }}');

    if (comments.length === 0) {
        commentsList.innerHTML = `
            <div class="text-center py-4">
                <i class="ph-duotone ph-chat-circle f-s-48 text-muted mb-3"></i>
                <p class="text-muted mb-0">Nessun commento ancora</p>
                <p class="text-muted f-s-14">Sii il primo a commentare!</p>
            </div>
        `;
    } else {
        let html = '';
        comments.forEach(comment => {
            const userAvatar = comment.user && comment.user.avatar_url ?
                `<img src="${comment.user.avatar_url}" alt="${comment.user.name}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">` :
                `<div class="h-40 w-40 d-flex-center rounded-circle bg-light-primary">
                    <i class="ph-duotone ph-user f-s-16 text-primary"></i>
                </div>`;

            html += `
                <div class="d-flex mb-3">
                    <div class="flex-shrink-0 me-3">
                        ${userAvatar}
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <h6 class="mb-0 f-s-14 f-w-600">
                                ${comment.user ?
                                    `<a href="/user/${comment.user.id}" class="text-decoration-none hover-effect">${comment.user.name}</a>` :
                                    'Utente'
                                }
                            </h6>
                            <small class="text-muted f-s-12">${new Date(comment.created_at).toLocaleDateString('it-IT')}</small>
                        </div>
                        <p class="mb-0 f-s-13">${comment.content}</p>
                    </div>
                </div>
            `;
        });
        commentsList.innerHTML = html;
    }

    document.getElementById('commentsContainer_{{ $uniqueId }}').style.display = 'block';
}

// Mostra errore nei commenti
function showCommentsError_{{ $uniqueId }}(message) {
    document.getElementById('commentsError_{{ $uniqueId }}').innerHTML = `
        <div class="alert alert-danger">
            <i class="ph-duotone ph-warning-circle me-2"></i>
            ${message}
        </div>
    `;
    document.getElementById('commentsError_{{ $uniqueId }}').style.display = 'block';
}

// Crea il modal dei commenti se non esiste
function createCommentsModal_{{ $uniqueId }}() {
    const modalHtml = `
        <div class="modal fade" id="commentsModal_{{ $uniqueId }}" tabindex="-1" aria-labelledby="commentsModalLabel_{{ $uniqueId }}" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="commentsModalLabel_{{ $uniqueId }}">
                            <i class="ph-duotone ph-chat-circle me-2"></i>Commenti
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Loading -->
                        <div id="commentsLoading_{{ $uniqueId }}" class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Caricamento...</span>
                            </div>
                            <p class="mt-2 text-muted">Caricamento commenti...</p>
                        </div>

                        <!-- Error -->
                        <div id="commentsError_{{ $uniqueId }}" style="display: none;"></div>

                        <!-- Comments Container -->
                        <div id="commentsContainer_{{ $uniqueId }}" style="display: none;">
                            <!-- Lista commenti -->
                            <div id="commentsList_{{ $uniqueId }}" class="mb-3">
                                <!-- I commenti verranno caricati qui -->
                            </div>

                            <!-- Form per nuovo commento -->
                            <div class="border-top pt-3">
                                <h6 class="mb-3">
                                    <i class="ph-duotone ph-plus-circle me-2"></i>
                                    Aggiungi un commento
                                </h6>
                                <form id="newCommentForm_{{ $uniqueId }}">
                                    <input type="hidden" id="commentMediaType_{{ $uniqueId }}" value="">
                                    <input type="hidden" id="commentMediaId_{{ $uniqueId }}" value="">
                                    <div class="mb-3">
                                        <textarea class="form-control" id="commentContent_{{ $uniqueId }}" rows="3" placeholder="Scrivi il tuo commento..." maxlength="500"></textarea>
                                        <div class="form-text">
                                            <span id="commentCharCount_{{ $uniqueId }}">0</span>/500 caratteri
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <button type="submit" class="btn btn-primary" id="submitCommentBtn_{{ $uniqueId }}">
                                            <i class="ph-duotone ph-paper-plane-right me-1"></i>
                                            Invia commento
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                            Chiudi
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', modalHtml);

    // Aggiungi event listeners per il form
    setupCommentForm_{{ $uniqueId }}();
}

// Configura il form per i commenti
function setupCommentForm_{{ $uniqueId }}() {
    const form = document.getElementById('newCommentForm_{{ $uniqueId }}');
    const textarea = document.getElementById('commentContent_{{ $uniqueId }}');
    const charCount = document.getElementById('commentCharCount_{{ $uniqueId }}');

    if (!form || !textarea || !charCount) return;

    // Contatore caratteri
    textarea.addEventListener('input', function() {
        const count = this.value.length;
        charCount.textContent = count;

        if (count > 450) {
            charCount.classList.add('text-warning');
        } else {
            charCount.classList.remove('text-warning');
        }
    });

    // Submit form
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        await submitComment_{{ $uniqueId }}();
    });
}

// Invia nuovo commento
async function submitComment_{{ $uniqueId }}() {
    // Verifica se l'utente è autenticato
    const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};

    if (!isAuthenticated) {
        window.location.href = '{{ route("login") }}';
        return;
    }

    const mediaType = document.getElementById('commentMediaType_{{ $uniqueId }}').value;
    const mediaId = document.getElementById('commentMediaId_{{ $uniqueId }}').value;
    const content = document.getElementById('commentContent_{{ $uniqueId }}').value.trim();
    const submitBtn = document.getElementById('submitCommentBtn_{{ $uniqueId }}');

    if (!content) {
        alert('Inserisci un commento');
        return;
    }

    // Disabilita il pulsante durante l'invio
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="ph-duotone ph-spinner f-s-12 me-1"></i>Invio...';

    try {
        const response = await fetch('/api/social/comments', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                commentable_type: mediaType,
                commentable_id: mediaId,
                content: content
            })
        });

        const data = await response.json();

        if (response.ok) {
            // Aggiungi il nuovo commento alla lista
            const commentsList = document.getElementById('commentsList_{{ $uniqueId }}');

            // Crea il nuovo commento con avatar
            const userName = data.comment.user.name;
            const userInitials = userName.substring(0, 2).toUpperCase();
            const userAvatar = data.comment.user.avatar_url ?
                `<img src="${data.comment.user.avatar_url}" alt="${userName}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">` :
                `<div class="h-40 w-40 d-flex-center rounded-circle bg-light-primary">
                    <i class="ph-duotone ph-user f-s-16 text-primary"></i>
                </div>`;

            const newCommentHtml = `
                <div class="d-flex mb-3">
                    <div class="flex-shrink-0 me-3">
                        ${userAvatar}
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <h6 class="mb-0 f-s-14 f-w-600">${userName}</h6>
                            <small class="text-muted f-s-12">Ora</small>
                        </div>
                        <p class="mb-0 f-s-13">${data.comment.content}</p>
                    </div>
                </div>
            `;

            // Se non ci sono commenti, rimuovi il messaggio "nessun commento"
            if (commentsList.querySelector('.text-center')) {
                commentsList.innerHTML = '';
            }

            commentsList.insertAdjacentHTML('afterbegin', newCommentHtml);

            // Reset form
            document.getElementById('commentContent_{{ $uniqueId }}').value = '';
            document.getElementById('commentCharCount_{{ $uniqueId }}').textContent = '0';

            // Aggiorna il contatore nel pulsante commenti
            const commentButton = document.querySelector(`.social-comment-btn[data-unique-id="{{ $uniqueId }}"] .comment-count`);
            if (commentButton && data.comment_count !== undefined) {
                commentButton.textContent = data.comment_count;
            }

            // Mostra messaggio di successo
            if (typeof toastr !== 'undefined') {
                toastr.success('Commento pubblicato con successo!');
            } else {
                alert('Commento pubblicato con successo!');
            }
        } else {
            throw new Error(data.message || 'Errore durante la pubblicazione');
        }
    } catch (error) {
        console.error('Errore invio commento:', error);
        alert('Errore durante la pubblicazione del commento: ' + error.message);
    } finally {
        // Riabilita il pulsante
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<i class="ph-duotone ph-paper-plane-right me-1"></i>Invia commento';
    }
}
</script>
