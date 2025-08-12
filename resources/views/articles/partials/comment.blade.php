<div class="comment mb-3" data-comment-id="{{ $comment->id }}">
    <div class="d-flex">
        <img src="{{ $comment->user->profile->avatar_url ?? asset('assets/images/avatar/default.png') }}" 
             class="rounded-circle me-3" style="width: 40px; height: 40px;" 
             alt="{{ $comment->user->name }}">
        <div class="flex-grow-1">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <strong>{{ $comment->user->name }}</strong>
                    <small class="text-muted ms-2">{{ $comment->created_at->diffForHumans() }}</small>
                    @if($comment->is_reply)
                        <small class="text-muted ms-2">→ {{ __('articles.reply_to') }} {{ $comment->parent->user->name }}</small>
                    @endif
                </div>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="ti ti-dots-vertical"></i>
                    </button>
                    <ul class="dropdown-menu">
                        @if(auth()->check() && auth()->user()->can('update', $comment))
                            <li><a class="dropdown-item" href="#" onclick="editComment({{ $comment->id }})">
                                <i class="ti ti-edit"></i> {{ __('articles.edit') }}
                            </a></li>
                        @endif
                        @if(auth()->check() && auth()->user()->can('delete', $comment))
                            <li><a class="dropdown-item text-danger" href="#" onclick="deleteComment({{ $comment->id }})">
                                <i class="ti ti-trash"></i> {{ __('articles.delete') }}
                            </a></li>
                        @endif
                        @if(auth()->check() && auth()->user()->hasPermissionTo('articles.moderate_comments'))
                            @if($comment->is_pending)
                                <li><a class="dropdown-item" href="#" onclick="approveComment({{ $comment->id }})">
                                    <i class="ti ti-check"></i> {{ __('articles.approve') }}
                                </a></li>
                                <li><a class="dropdown-item" href="#" onclick="rejectComment({{ $comment->id }})">
                                    <i class="ti ti-x"></i> {{ __('articles.reject') }}
                                </a></li>
                            @endif
                        @endif
                    </ul>
                </div>
            </div>
            
            <div class="comment-content mb-2">
                <p class="mb-0">{{ $comment->content }}</p>
            </div>
            
            <div class="d-flex gap-2">
                <!-- Like commento -->
                <button class="btn btn-sm btn-outline-primary comment-like-btn" 
                        data-comment-id="{{ $comment->id }}"
                        data-liked="{{ auth()->check() && $comment->likes()->where('user_id', auth()->id())->exists() ? 'true' : 'false' }}">
                    <i class="ti ti-heart {{ auth()->check() && $comment->likes()->where('user_id', auth()->id())->exists() ? 'text-danger' : '' }}"></i>
                    <span class="comment-likes-count">{{ $comment->likes_count }}</span>
                </button>

                <!-- Rispondi -->
                @if(auth()->check())
                    <button class="btn btn-sm btn-outline-secondary" onclick="showReplyForm({{ $comment->id }})">
                        <i class="ti ti-message-circle"></i> {{ __('articles.reply') }}
                    </button>
                @endif
            </div>

            <!-- Form risposta (nascosto) -->
            @if(auth()->check())
                <div id="replyForm{{ $comment->id }}" class="mt-3" style="display: none;">
                    <form class="reply-form" data-parent-id="{{ $comment->id }}">
                        <div class="mb-2">
                            <textarea name="content" class="form-control" rows="2" 
                                      placeholder="{{ __('articles.write_reply') }}" required></textarea>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm">
                                {{ __('articles.reply') }}
                            </button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="hideReplyForm({{ $comment->id }})">
                                {{ __('articles.cancel') }}
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Risposte -->
            @if($comment->replies->count() > 0)
                <div class="replies mt-3 ms-4">
                    @foreach($comment->replies as $reply)
                        @include('articles.partials.comment', ['comment' => $reply])
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Like comment functionality
    document.querySelectorAll('.comment-like-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (!{{ auth()->check() ? 'true' : 'false' }}) {
                window.location.href = '{{ route('login') }}';
                return;
            }

            const commentId = this.dataset.commentId;
            const isLiked = this.dataset.liked === 'true';

            fetch(`/articles/comments/${commentId}/like`, {
                method: isLiked ? 'DELETE' : 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.dataset.liked = !isLiked;
                    const icon = this.querySelector('i');
                    const count = this.querySelector('.comment-likes-count');
                    
                    if (!isLiked) {
                        icon.classList.add('text-danger');
                    } else {
                        icon.classList.remove('text-danger');
                    }
                    
                    count.textContent = data.likes_count;
                    showNotification(data.message, 'success');
                }
            });
        });
    });

    // Reply form functionality
    document.querySelectorAll('.reply-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const parentId = this.dataset.parentId;
            const formData = new FormData(this);
            
            fetch('{{ route('articles.comments.store', $article) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    content: formData.get('content'),
                    parent_id: parentId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.reset();
                    hideReplyForm(parentId);
                    
                    if (data.status === 'approved') {
                        addReplyToList(data.comment, parentId);
                    }
                    
                    showNotification(data.message, 'success');
                } else {
                    showNotification(data.message, 'error');
                }
            });
        });
    });
});

function showReplyForm(commentId) {
    document.getElementById(`replyForm${commentId}`).style.display = 'block';
}

function hideReplyForm(commentId) {
    document.getElementById(`replyForm${commentId}`).style.display = 'none';
}

function addReplyToList(reply, parentId) {
    const parentComment = document.querySelector(`[data-comment-id="${parentId}"]`);
    const repliesContainer = parentComment.querySelector('.replies');
    
    if (!repliesContainer) {
        const newRepliesContainer = document.createElement('div');
        newRepliesContainer.className = 'replies mt-3 ms-4';
        parentComment.querySelector('.flex-grow-1').appendChild(newRepliesContainer);
    }
    
    const replyHtml = `
        <div class="comment mb-3" data-comment-id="${reply.id}">
            <div class="d-flex">
                <img src="${reply.user.avatar_url || '/assets/images/avatar/default.png'}" 
                     class="rounded-circle me-3" style="width: 40px; height: 40px;" 
                     alt="${reply.user.name}">
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <strong>${reply.user.name}</strong>
                            <small class="text-muted ms-2">${reply.created_at}</small>
                            <small class="text-muted ms-2">→ {{ __('articles.reply_to') }} ${parentComment.querySelector('strong').textContent}</small>
                        </div>
                    </div>
                    <div class="comment-content mb-2">
                        <p class="mb-0">${reply.content}</p>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary comment-like-btn" 
                                data-comment-id="${reply.id}" data-liked="false">
                            <i class="ti ti-heart"></i>
                            <span class="comment-likes-count">0</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    const container = parentComment.querySelector('.replies') || parentComment.querySelector('.flex-grow-1');
    container.insertAdjacentHTML('beforeend', replyHtml);
}

function editComment(commentId) {
    const comment = document.querySelector(`[data-comment-id="${commentId}"]`);
    const content = comment.querySelector('.comment-content p');
    const currentText = content.textContent;
    
    const editForm = `
        <div class="edit-form mb-2">
            <textarea class="form-control" rows="3">${currentText}</textarea>
            <div class="d-flex gap-2 mt-2">
                <button class="btn btn-primary btn-sm" onclick="saveCommentEdit(${commentId})">
                    {{ __('articles.save') }}
                </button>
                <button class="btn btn-secondary btn-sm" onclick="cancelCommentEdit(${commentId})">
                    {{ __('articles.cancel') }}
                </button>
            </div>
        </div>
    `;
    
    content.style.display = 'none';
    content.insertAdjacentHTML('afterend', editForm);
}

function saveCommentEdit(commentId) {
    const comment = document.querySelector(`[data-comment-id="${commentId}"]`);
    const textarea = comment.querySelector('.edit-form textarea');
    const newContent = textarea.value;
    
    fetch(`/articles/comments/${commentId}`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            content: newContent
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const content = comment.querySelector('.comment-content p');
            content.textContent = newContent;
            content.style.display = 'block';
            comment.querySelector('.edit-form').remove();
            showNotification(data.message, 'success');
        } else {
            showNotification(data.message, 'error');
        }
    });
}

function cancelCommentEdit(commentId) {
    const comment = document.querySelector(`[data-comment-id="${commentId}"]`);
    const content = comment.querySelector('.comment-content p');
    content.style.display = 'block';
    comment.querySelector('.edit-form').remove();
}

function deleteComment(commentId) {
    if (confirm('{{ __('articles.confirm_delete_comment') }}')) {
        fetch(`/articles/comments/${commentId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.querySelector(`[data-comment-id="${commentId}"]`).remove();
                showNotification(data.message, 'success');
            } else {
                showNotification(data.message, 'error');
            }
        });
    }
}

function approveComment(commentId) {
    fetch(`/articles/comments/${commentId}/approve`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

function rejectComment(commentId) {
    fetch(`/articles/comments/${commentId}/reject`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}
</script>
@endpush
