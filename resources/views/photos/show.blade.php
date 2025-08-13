@extends('layout.master')

@section('title', ($photo->title ?: 'Foto di ' . $photo->user->getDisplayName()) . ' - Slam In')

@section('main-content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <!-- Titolo su mobile, breadcrumb su desktop -->
                <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between">

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0 small">
                            <li class="breadcrumb-item">
                                <a href="{{ route('home') }}" class="text-decoration-none">
                                    <i class="ph ph-house me-1"></i>{{ __('common.home') }}
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('media.index') }}" class="text-decoration-none">
                                    <i class="ph ph-images me-1"></i>{{ __('media.media') }}
                                </a>
                            </li>
                            <li class="breadcrumb-item active text-truncate" style="max-width: 200px;">
                                {{ $photo->title ?: 'Foto di ' . $photo->user->getDisplayName() }}
                            </li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Photo Display -->
            <div class="card mb-4">
                <div class="card-body p-0">
                    <div class="position-relative">
                        <img src="{{ $photo->image_url }}"
                             alt="{{ $photo->alt_text ?: ($photo->title ?: 'Foto di ' . $photo->user->getDisplayName()) }}"
                             class="img-fluid w-100"
                             style="max-height: 600px; object-fit: contain;">

                        <!-- Photo Overlay Info -->
                        <div class="position-absolute bottom-0 start-0 end-0 p-3" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                            <div class="d-flex justify-content-between align-items-end text-white">
                                <div>
                                    @if($photo->title)
                                        <h4 class="mb-1 text-white">{{ $photo->title }}</h4>
                                    @endif
                                    <p class="mb-0">
                                        <i class="ph ph-user me-1"></i>
                                        <a href="{{ route('user.show', $photo->user) }}" class="text-decoration-none text-white hover-effect">
                                            {{ $photo->user->getDisplayName() }}
                                        </a>
                                    </p>
                                </div>
                                <div class="text-end">
                                    <small class="d-block">
                                        <i class="ph ph-calendar me-1"></i>{{ $photo->created_at->format('d/m/Y H:i') }}
                                    </small>
                                    <small class="d-block">
                                        <i class="ph ph-eye me-1"></i>{{ number_format($photo->view_count) }} {{ __('media.views') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Photo Details -->
            @if($photo->description)
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph ph-file-text me-2"></i>{{ __('media.description') }}
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $photo->description }}</p>
                </div>
            </div>
            @endif

            <!-- Social Interactions -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph ph-heart me-2"></i>{{ __('media.interactions') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                @auth
                                    <button class="btn btn-link p-0 me-2" onclick="toggleLike('photo', {{ $photo->id }})" id="likeBtn{{ $photo->id }}">
                                        <i class="ph {{ $photo->isLikedBy(auth()->user()) ? 'ph-heart-fill text-danger' : 'ph-heart' }} f-s-20"></i>
                                    </button>
                                @else
                                    <i class="ph ph-heart f-s-20 text-muted me-2"></i>
                                @endauth
                                <span class="fw-bold" id="likeCount{{ $photo->id }}">{{ number_format($photo->like_count) }}</span>
                            </div>
                            <small class="text-muted">{{ __('media.likes') }}</small>
                        </div>
                        <div class="col-4">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                @auth
                                    <button class="btn btn-link p-0 me-2" onclick="openCommentsModal('photo', {{ $photo->id }})">
                                        <i class="ph ph-chat-circle f-s-20"></i>
                                    </button>
                                @else
                                    <i class="ph ph-chat-circle f-s-20 text-muted me-2"></i>
                                @endauth
                                <span class="fw-bold" id="commentCount{{ $photo->id }}">{{ number_format($photo->comment_count) }}</span>
                            </div>
                            <small class="text-muted">{{ __('media.comments') }}</small>
                        </div>
                        <div class="col-4">
                            <div class="d-flex align-items-center justify-content-center mb-2">
                                <i class="ph ph-eye f-s-20 text-muted me-2"></i>
                                <span class="fw-bold">{{ number_format($photo->view_count) }}</span>
                            </div>
                            <small class="text-muted">{{ __('media.views') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph ph-chat-circle me-2"></i>{{ __('media.comments') }}
                        <span class="badge bg-primary ms-2" id="commentCountBadge{{ $photo->id }}">{{ number_format($photo->comment_count) }}</span>
                    </h6>
                </div>
                <div class="card-body">
                    @auth
                        <!-- Comment Form -->
                        <div class="mb-4">
                            <form class="comment-form" data-content-type="photo" data-content-id="{{ $photo->id }}">
                                <div class="d-flex gap-2">
                                    <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl(auth()->user()) }}"
                                         alt="{{ auth()->user()->getDisplayName() }}"
                                         class="rounded-circle"
                                         style="width: 40px; height: 40px; object-fit: cover;">
                                    <div class="flex-grow-1">
                                        <textarea class="form-control" rows="2" placeholder="{{ __('media.write_comment') }}" maxlength="500"></textarea>
                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <small class="text-muted">
                                                <span class="char-count">0</span>/500
                                            </small>
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="ph ph-paper-plane me-1"></i>{{ __('media.post_comment') }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <i class="ph ph-info me-2"></i>
                            {{ __('media.login_to_comment') }}
                        </div>
                    @endauth

                    <!-- Comments List -->
                    <div class="comments-list" id="commentsList{{ $photo->id }}">
                        @foreach($photo->getComments() as $comment)
                            <div class="d-flex gap-2 mb-3">
                                <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($comment->user) }}"
                                     alt="{{ $comment->user->getDisplayName() }}"
                                     class="rounded-circle"
                                     style="width: 40px; height: 40px; object-fit: cover;">
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <div>
                                            <a href="{{ route('user.show', $comment->user) }}" class="text-decoration-none hover-effect fw-bold">
                                                {{ $comment->user->getDisplayName() }}
                                            </a>
                                            <small class="text-muted ms-2">{{ $comment->created_at->diffForHumans() }}</small>
                                        </div>
                                        @if(auth()->id() === $comment->user_id || (auth()->user() && auth()->user()->hasRole('admin')))
                                            <button class="btn btn-link p-0 text-danger" onclick="deleteComment({{ $comment->id }})">
                                                <i class="ph ph-trash f-s-12"></i>
                                            </button>
                                        @endif
                                    </div>
                                    <p class="mb-0">{{ $comment->content }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($photo->comment_count === 0)
                        <div class="text-center py-4">
                            <i class="ph ph-chat-circle text-muted f-s-48 mb-3"></i>
                            <p class="text-muted mb-0">{{ __('media.no_comments_yet') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Author Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph ph-user me-2"></i>{{ __('media.author') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($photo->user) }}"
                             alt="{{ $photo->user->getDisplayName() }}"
                             class="rounded-circle me-3"
                             style="width: 60px; height: 60px; object-fit: cover;">
                        <div>
                            <h6 class="mb-1">
                                <a href="{{ route('user.show', $photo->user) }}" class="text-decoration-none hover-effect">
                                    {{ $photo->user->getDisplayName() }}
                                </a>
                            </h6>
                            <small class="text-muted">
                                {{ $photo->user->photos_count }} {{ __('media.photos') }}
                            </small>
                        </div>
                    </div>
                    @auth
                        @if(auth()->id() !== $photo->user_id)
                            <button type="button" class="btn btn-primary w-100" onclick="followUser({{ $photo->user->id }})" id="followButton{{ $photo->user->id }}">
                                <i class="ph ph-user me-1"></i>
                                {{ $photo->user->is_followed_by_current_user ?? false ? __('profile.following') : __('profile.follow') }}
                            </button>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Photo Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph ph-info me-2"></i>{{ __('media.photo_info') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <small class="text-muted d-block">{{ __('media.uploaded') }}</small>
                            <strong>{{ $photo->created_at->format('d/m/Y') }}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">{{ __('media.dimensions') }}</small>
                            <strong>{{ $photo->width ?? 'N/A' }}x{{ $photo->height ?? 'N/A' }}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">{{ __('media.file_size') }}</small>
                            <strong>{{ $photo->file_size ?? 'N/A' }}</strong>
                        </div>
                        <div class="col-6">
                            <small class="text-muted d-block">{{ __('media.format') }}</small>
                            <strong>{{ strtoupper(pathinfo($photo->image_path, PATHINFO_EXTENSION)) }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Photos -->
            @if($relatedPhotos->count() > 0)
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph ph-images me-2"></i>{{ __('media.related_photos') }}
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        @foreach($relatedPhotos as $relatedPhoto)
                            <div class="col-6">
                                <a href="{{ route('photos.show', $relatedPhoto) }}" class="text-decoration-none">
                                    <div class="position-relative rounded overflow-hidden" style="height: 120px;">
                                        <img src="{{ $relatedPhoto->thumbnail_url }}"
                                             alt="{{ $relatedPhoto->title ?: 'Foto di ' . $relatedPhoto->user->getDisplayName() }}"
                                             class="w-100 h-100"
                                             style="object-fit: cover;">
                                        <div class="position-absolute bottom-0 start-0 end-0 p-2" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                                            <small class="text-white">{{ $relatedPhoto->user->getDisplayName() }}</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Comments Modal -->
<div class="modal fade" id="commentsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('media.comments') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="commentsModalBody">
                <!-- Comments will be loaded here -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Like functionality
function toggleLike(type, id) {
    if (!isAuthenticated) {
        window.location.href = '{{ route("login") }}';
        return;
    }

    fetch(`/api/${type}s/${id}/like`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const likeBtn = document.getElementById(`likeBtn${id}`);
            const likeCount = document.getElementById(`likeCount${id}`);

            if (data.liked) {
                likeBtn.innerHTML = '<i class="ph ph-heart-fill text-danger f-s-20"></i>';
            } else {
                likeBtn.innerHTML = '<i class="ph ph-heart f-s-20"></i>';
            }

            likeCount.textContent = data.like_count.toLocaleString();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Errore durante l\'operazione', 'error');
    });
}

// Follow functionality
function followUser(userId) {
    if (!isAuthenticated) {
        window.location.href = '{{ route("login") }}';
        return;
    }

    fetch('/api/follow', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ user_id: userId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const followBtn = document.getElementById(`followButton${userId}`);
            if (data.following) {
                followBtn.innerHTML = '<i class="ph ph-user me-1"></i>{{ __("profile.following") }}';
                followBtn.classList.remove('btn-primary');
                followBtn.classList.add('btn-success');
            } else {
                followBtn.innerHTML = '<i class="ph ph-user me-1"></i>{{ __("profile.follow") }}';
                followBtn.classList.remove('btn-success');
                followBtn.classList.add('btn-primary');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Errore durante l\'operazione', 'error');
    });
}

// Comments functionality
function openCommentsModal(type, id) {
    if (!isAuthenticated) {
        window.location.href = '{{ route("login") }}';
        return;
    }

    const modal = new bootstrap.Modal(document.getElementById('commentsModal'));
    modal.show();

    // Load comments
    loadComments(type, id);
}

function loadComments(type, id) {
    fetch(`/api/${type}s/${id}/comments`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayComments(data.comments, `commentsModalBody`);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showCommentsError();
        });
}

function displayComments(comments, containerId) {
    const container = document.getElementById(containerId);
    if (comments.length === 0) {
        container.innerHTML = '<p class="text-muted text-center">{{ __("media.no_comments_yet") }}</p>';
        return;
    }

    container.innerHTML = comments.map(comment => `
        <div class="d-flex gap-2 mb-3">
            <img src="${comment.user.avatar_url || '/assets/images/avatar/default-avatar.webp'}"
                 alt="${comment.user.name}"
                 class="rounded-circle"
                 style="width: 40px; height: 40px; object-fit: cover;">
            <div class="flex-grow-1">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <div>
                        <a href="/user/${comment.user.id}" class="text-decoration-none hover-effect fw-bold">
                            ${comment.user.name}
                        </a>
                        <small class="text-muted ms-2">${comment.created_at}</small>
                    </div>
                </div>
                <p class="mb-0">${comment.content}</p>
            </div>
        </div>
    `).join('');
}

function showCommentsError() {
    const container = document.getElementById('commentsModalBody');
    container.innerHTML = '<p class="text-danger text-center">{{ __("media.error_loading_comments") }}</p>';
}

// Comment form functionality
document.addEventListener('DOMContentLoaded', function() {
    const commentForms = document.querySelectorAll('.comment-form');

    commentForms.forEach(form => {
        const textarea = form.querySelector('textarea');
        const charCount = form.querySelector('.char-count');
        const contentType = form.dataset.contentType;
        const contentId = form.dataset.contentId;
        const commentsList = document.getElementById(`commentsList${contentId}`);
        const commentCountSpan = document.getElementById(`commentCount${contentId}`);
        const commentCountBadge = document.getElementById(`commentCountBadge${contentId}`);

        // Character counter
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

            fetch('/api/social/comments', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    commentable_type: contentType,
                    commentable_id: contentId,
                    content: content
                })
            })
            .then(response => {
                if (response.status === 419) {
                    throw new Error('CSRF token mismatch');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Add new comment to list
                    const newComment = createCommentElement(data.comment);
                    commentsList.insertBefore(newComment, commentsList.firstChild);

                    // Update counters
                    const newCount = data.comment_count;
                    commentCountSpan.textContent = newCount.toLocaleString();
                    commentCountBadge.textContent = newCount.toLocaleString();

                    // Reset form
                    textarea.value = '';
                    charCount.textContent = '0';

                    showToast('{{ __("media.comment_posted_success") }}', 'success');
                } else {
                    showToast(data.message || '{{ __("media.error_posting_comment") }}', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (error.message === 'CSRF token mismatch') {
                    showToast('{{ __("media.session_expired") }}', 'error');
                } else {
                    showToast('{{ __("media.connection_error") }}', 'error');
                }
            })
            .finally(() => {
                submitBtn.disabled = false;
            });
        });
    });
});

function createCommentElement(comment) {
    const div = document.createElement('div');
    div.className = 'd-flex gap-2 mb-3';
    div.innerHTML = `
        <img src="${comment.avatar_url || '/assets/images/avatar/default-avatar.webp'}"
             alt="${comment.user.name}"
             class="rounded-circle"
             style="width: 40px; height: 40px; object-fit: cover;">
        <div class="flex-grow-1">
            <div class="d-flex justify-content-between align-items-start mb-1">
                <div>
                    <a href="/user/${comment.user.id}" class="text-decoration-none hover-effect fw-bold">
                        ${comment.user.name}
                    </a>
                    <small class="text-muted ms-2">Ora</small>
                </div>
            </div>
            <p class="mb-0">${comment.content}</p>
        </div>
    `;
    return div;
}

function deleteComment(commentId) {
    if (!confirm('{{ __("media.confirm_delete_comment") }}')) return;

            fetch(`/api/social/comments/${commentId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Remove comment from DOM
            const commentElement = document.querySelector(`[data-comment-id="${commentId}"]`);
            if (commentElement) {
                commentElement.remove();
            }

            // Update counters
            const commentCountSpan = document.getElementById('commentCount{{ $photo->id }}');
            const commentCountBadge = document.getElementById('commentCountBadge{{ $photo->id }}');
            const newCount = parseInt(commentCountSpan.textContent.replace(/,/g, '')) - 1;
            commentCountSpan.textContent = newCount.toLocaleString();
            commentCountBadge.textContent = newCount.toLocaleString();

            showToast('{{ __("media.comment_deleted_success") }}', 'success');
        } else {
            showToast(data.message || '{{ __("media.error_deleting_comment") }}', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('{{ __("media.connection_error") }}', 'error');
    });
}

// Check if user is authenticated
const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
</script>
@endpush
