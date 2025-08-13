@props(['content', 'type' => 'content'])

@php
    $commentCount = $content->comment_count ?? 0;
    $contentType = strtolower(class_basename($content));
    $comments = $content->approvedComments ?? collect();
@endphp

<div class="card hover-effect mt-3 social-comments-section"
     data-content-type="{{ $contentType }}"
     data-content-id="{{ $content->id }}">
    <div class="card-header">
        <h6 class="card-title mb-0">
            <i class="ti ti-brand-hipchat f-s-16 me-2"></i>
            Commenti (<span class="comment-count">{{ number_format($commentCount) }}</span>)
        </h6>
    </div>
    <div class="card-body">
        <!-- Form per aggiungere commento -->
        @if(auth()->check())
        <div class="mb-4">
            <form class="comment-form">
                <div class="mb-3">
                    <textarea class="form-control"
                              name="content"
                              rows="3"
                              placeholder="Scrivi un commento..."
                              maxlength="1000"
                              required></textarea>
                    <div class="form-text">
                        <span class="char-count">0</span>/1000 caratteri
                    </div>
                </div>
                <button type="submit" class="btn btn-primary hover-effect">
                    <i class="ti ti-send me-1"></i>
                    Pubblica Commento
                </button>
            </form>
        </div>
        @else
        <div class="alert alert-info">
            <i class="ti ti-info-circle f-s-16 me-2"></i>
            <a href="{{ route('login') }}" class="text-decoration-none">Accedi</a> per lasciare un commento e interagire con il contenuto.
        </div>
        @endif

        <!-- Lista commenti -->
        <div class="comments-list">
            @foreach($comments as $comment)
                <div class="comment-box mb-3" data-comment-id="{{ $comment->id }}">
                    <div class="d-flex align-items-start">
                        <a href="{{ route('user.show', $comment->user) }}" class="text-decoration-none">
                            <div class="h-45 w-45 d-flex-center b-r-50 overflow-hidden bg-primary me-3">
                                <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($comment->user) }}" alt="{{ $comment->user->name }}" class="img-fluid">
                            </div>
                        </a>
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div>
                                    <div class="fw-bold">
                                        <a href="{{ route('user.show', $comment->user) }}" class="text-decoration-none hover-effect">
                                            {{ $comment->user->getDisplayName() }}
                                        </a>
                                    </div>
                                    <div class="text-muted f-s-12">{{ $comment->created_at->diffForHumans() }}</div>
                                </div>
                                @if(auth()->check() && (auth()->id() === $comment->user_id || auth()->user()->hasRole('admin')))
                                <div class="dropdown">
                                    <button class="btn btn-link p-0" type="button" data-bs-toggle="dropdown">
                                        <i class="ti ti-dots-vertical f-s-16"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item text-danger" href="#" onclick="deleteComment({{ $comment->id }})">
                                            <i class="ti ti-trash f-s-14 me-2"></i>Elimina
                                        </a></li>
                                    </ul>
                                </div>
                                @endif
                            </div>
                            <div class="comment-content">{{ $comment->content }}</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<script>
// Gestione form commenti
document.addEventListener('DOMContentLoaded', function() {
    const commentForms = document.querySelectorAll('.comment-form');

    commentForms.forEach(form => {
        const textarea = form.querySelector('textarea');
        const charCount = form.querySelector('.char-count');
        const commentsSection = form.closest('.social-comments-section');
        const contentType = commentsSection.dataset.contentType;
        const contentId = commentsSection.dataset.contentId;
        const commentsList = commentsSection.querySelector('.comments-list');
        const commentCountSpan = commentsSection.querySelector('.comment-count');

        // Contatore caratteri
        textarea.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });

        // Submit form
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const content = textarea.value.trim();
            if (!content) return;

            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.disabled = true;

            // Ottieni il token CSRF
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
            
            fetch('/api/social/comments', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    commentable_type: contentType,
                    commentable_id: contentId,
                    content: content
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Aggiungi il nuovo commento alla lista
                    const newComment = createCommentElement(data.comment);
                    commentsList.insertBefore(newComment, commentsList.firstChild);

                    // Aggiorna contatore
                    commentCountSpan.textContent = data.comment_count.toLocaleString();

                    // Reset form
                    textarea.value = '';
                    charCount.textContent = '0';

                    showToast('Commento pubblicato con successo!', 'success');
                } else {
                    showToast(data.message || 'Errore durante la pubblicazione', 'error');
                }
            })
            .catch(error => {
                console.error('Errore:', error);
                showToast('Errore di connessione', 'error');
            })
            .finally(() => {
                submitBtn.disabled = false;
            });
        });
    });
});

// Crea elemento commento
function createCommentElement(comment) {
    const div = document.createElement('div');
    div.className = 'comment-box mb-3';
    div.dataset.commentId = comment.id;

    const userName = comment.user.name;
    const userInitials = userName.substring(0, 2).toUpperCase();
    const userAvatar = comment.user.avatar_url ?
        `<img src="${comment.user.avatar_url}" alt="${userName}" class="img-fluid">` :
        `<span class="text-white fw-bold">${userInitials}</span>`;

    div.innerHTML = `
        <div class="d-flex align-items-start">
            <a href="/user/${comment.user.id}" class="text-decoration-none">
                <div class="h-45 w-45 d-flex-center b-r-50 overflow-hidden bg-primary me-3">
                    ${userAvatar}
                </div>
            </a>
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <div class="fw-bold">
                            <a href="/user/${comment.user.id}" class="text-decoration-none hover-effect">
                                ${comment.user.name}
                            </a>
                        </div>
                        <div class="text-muted f-s-12">Adesso</div>
                    </div>
                    <div class="dropdown">
                        <button class="btn btn-link p-0" type="button" data-bs-toggle="dropdown">
                            <i class="ti ti-dots-vertical f-s-16"></i>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item text-danger" href="#" onclick="deleteComment(${comment.id})">
                                <i class="ti ti-trash f-s-14 me-2"></i>Elimina
                            </a></li>
                        </ul>
                    </div>
                </div>
                <div class="comment-content">${comment.content}</div>
            </div>
        </div>
    `;

    return div;
}

// Elimina commento
function deleteComment(commentId) {
    if (!confirm('Sei sicuro di voler eliminare questo commento?')) return;

    fetch(`/api/social/comments/${commentId}`, {
        method: 'DELETE',
        headers: {
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const commentElement = document.querySelector(`[data-comment-id="${commentId}"]`);
            if (commentElement) {
                commentElement.remove();

                // Aggiorna contatore
                const commentsSection = commentElement.closest('.social-comments-section');
                const commentCountSpan = commentsSection.querySelector('.comment-count');
                const currentCount = parseInt(commentCountSpan.textContent.replace(/,/g, ''));
                commentCountSpan.textContent = (currentCount - 1).toLocaleString();
            }

            showToast('Commento eliminato con successo!', 'success');
        } else {
            showToast(data.message || 'Errore durante l\'eliminazione', 'error');
        }
    })
    .catch(error => {
        console.error('Errore:', error);
        showToast('Errore di connessione', 'error');
    });
}

function showToast(message, type = 'info') {
    if (typeof toastr !== 'undefined') {
        toastr[type](message);
    } else if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: type === 'success' ? 'Successo!' : 'Errore',
            text: message,
            icon: type,
            timer: 2000,
            showConfirmButton: false
        });
    } else {
        alert(message);
    }
}
</script>
