@extends('layout.master')

@section('main-content')
<div class="container-fluid">
    <div class="row">
        <!-- Contenuto principale -->
        <div class="col-lg-8">
            @section('breadcrumb-title')
<h3>{{ $article->title }}</h3>
@endsection

@section('breadcrumb-items')
<li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('articles.home') }}</a></li>
<li class="breadcrumb-item"><a href="{{ route('articles.index') }}">{{ __('articles.news') }}</a></li>
<li class="breadcrumb-item active">{{ $article->title }}</li>
@endsection

            <!-- Articolo principale -->
            <div class="card mb-4">
                @if($article->featured_image)
                    <img src="{{ Storage::url($article->featured_image) }}"
                         class="card-img-top" style="height: 400px; object-fit: cover;"
                         alt="{{ $article->title }}">
                @endif
                <div class="card-body">
                    <!-- Header articolo -->
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            @if($article->category)
                                <span class="badge" style="background-color: {{ $article->category->color }}">
                                    {{ $article->category->name }}
                                </span>
                            @endif
                            @if($article->featured)
                                <span class="badge bg-warning ms-1">{{ __('articles.featured') }}</span>
                            @endif
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="ti ti-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu">
                                @if(auth()->check() && auth()->user()->can('edit', $article))
                                    <li><a class="dropdown-item" href="{{ route('articles.edit', $article) }}">
                                        <i class="ti ti-edit"></i> {{ __('articles.edit_article') }}
                                    </a></li>
                                @endif
                                @if(auth()->check() && auth()->user()->hasPermissionTo('articles.publish'))
                                    @if($article->isPublished)
                                        <li><a class="dropdown-item" href="#" onclick="unpublishArticle({{ $article->id }})">
                                            <i class="ti ti-eye-off"></i> {{ __('articles.unpublish') }}
                                        </a></li>
                                    @else
                                        <li><a class="dropdown-item" href="#" onclick="publishArticle({{ $article->id }})">
                                            <i class="ti ti-eye"></i> {{ __('articles.publish') }}
                                        </a></li>
                                    @endif
                                @endif
                                @if(auth()->check() && auth()->user()->hasPermissionTo('articles.feature'))
                                    <li><a class="dropdown-item" href="#" onclick="toggleFeatured({{ $article->id }})">
                                        <i class="ti ti-star"></i> {{ $article->featured ? __('articles.unfeature') : __('articles.feature') }}
                                    </a></li>
                                @endif
                                @if(auth()->check() && auth()->user()->can('delete', $article))
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#" onclick="deleteArticle({{ $article->id }})">
                                        <i class="ti ti-trash"></i> {{ __('articles.delete') }}
                                    </a></li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    <!-- Titolo e meta -->
                    <h1 class="card-title mb-3">{{ $article->title }}</h1>

                    <div class="d-flex align-items-center text-muted mb-4">
                        <div class="d-flex align-items-center me-3">
                            <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($article->user) }}"
                                 class="rounded-circle me-2" style="width: 32px; height: 32px;"
                                 alt="{{ $article->user->name }}">
                            <span>{{ __('articles.by') }}
                                <a href="{{ route('user.show', $article->user) }}" class="text-decoration-none">
                                    {{ $article->user->name }}
                                </a>
                            </span>
                        </div>
                        <span class="mx-2">•</span>
                        <span>{{ $article->published_at->format('d/m/Y H:i') }}</span>
                        <span class="mx-2">•</span>
                        <span>{{ __('articles.read_time', ['minutes' => $article->read_time]) }}</span>
                        <span class="mx-2">•</span>
                        <span>{{ $article->views_count }} {{ __('articles.views') }}</span>
                    </div>

                    <!-- Tag -->
                    @if($article->tags->count() > 0)
                        <div class="mb-4">
                            @foreach($article->tags as $tag)
                                <span class="badge bg-light text-dark me-1">{{ $tag->name }}</span>
                            @endforeach
                        </div>
                    @endif

                    <!-- Azioni social -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="d-flex gap-2">
                            <!-- Like -->
                            <button class="btn btn-outline-primary like-btn"
                                    data-article-id="{{ $article->id }}"
                                    data-liked="{{ auth()->check() && $article->isLikedBy(auth()->user()) ? 'true' : 'false' }}">
                                <i class="ti ti-heart {{ auth()->check() && $article->isLikedBy(auth()->user()) ? 'text-danger' : '' }}"></i>
                                <span class="likes-count">{{ $article->likes_count }}</span>
                                {{ __('articles.likes') }}
                            </button>

                            <!-- Commenti -->
                            <a href="#comments" class="btn btn-outline-secondary">
                                <i class="ti ti-message-circle"></i>
                                {{ $article->comments_count }} {{ __('articles.comments') }}
                            </a>

                            <!-- Condividi -->
                            <button class="btn btn-outline-info" onclick="shareArticle()">
                                <i class="ti ti-share"></i> {{ __('articles.share_article') }}
                            </button>

                            <!-- Segnala -->
                            @if(auth()->check())
                                <button class="btn btn-outline-warning report-btn"
                                        data-article-id="{{ $article->id }}"
                                        data-reported="{{ auth()->check() && $article->isReportedByUser(auth()->user()) ? 'true' : 'false' }}">
                                    <i class="ti ti-flag"></i> {{ __('articles.report') }}
                                </button>
                            @endif
                        </div>

                        <!-- Stampa -->
                        <button class="btn btn-outline-secondary" onclick="window.print()">
                            <i class="ti ti-printer"></i> {{ __('articles.print_article') }}
                        </button>
                    </div>

                    <!-- Contenuto articolo -->
                    <div class="article-content mb-4">
                        {!! $article->content !!}
                    </div>

                    <!-- Autore -->
                    <div class="card bg-light">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <img src="{{ $article->user->profile->avatar_url ?? asset('assets/images/avatar/default.png') }}"
                                     class="rounded-circle me-3" style="width: 64px; height: 64px;"
                                     alt="{{ $article->user->name }}">
                                <div>
                                    <h6 class="mb-1">{{ __('articles.by') }} {{ $article->user->name }}</h6>
                                    <p class="text-muted mb-0">{{ $article->user->profile->bio ?? __('articles.no_bio') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sezione commenti -->
            <div id="comments" class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('articles.comments') }} ({{ $article->comments_count }})</h5>
                </div>
                <div class="card-body">
                    @if(auth()->check())
                        <!-- Form nuovo commento -->
                        <div class="mb-4">
                            <form id="commentForm">
                                <div class="mb-3">
                                    <textarea name="content" class="form-control" rows="3"
                                              placeholder="{{ __('articles.write_comment') }}" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    {{ __('articles.post_comment') }}
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="alert alert-info">
                            <a href="{{ route('login') }}">{{ __('articles.login_to_comment') }}</a>
                        </div>
                    @endif

                    <!-- Lista commenti -->
                    <div id="commentsList">
                        @foreach($article->comments as $comment)
                            @include('articles.partials.comment', ['comment' => $comment])
                        @endforeach
                    </div>

                    @if($article->comments->count() == 0)
                        <p class="text-muted text-center py-4">{{ __('articles.no_comments') }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Articoli correlati -->
            @if($relatedArticles->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('articles.related_articles') }}</h5>
                    </div>
                    <div class="card-body p-0">
                        @foreach($relatedArticles as $relatedArticle)
                            <div class="border-bottom p-3">
                                <h6 class="mb-1">
                                    <a href="{{ route('articles.show', $relatedArticle) }}" class="text-decoration-none">
                                        {{ Str::limit($relatedArticle->title, 60) }}
                                    </a>
                                </h6>
                                <div class="d-flex align-items-center text-muted">
                                    <small>{{ $relatedArticle->published_at->format('d/m/Y') }}</small>
                                    <span class="mx-2">•</span>
                                    <small>{{ $relatedArticle->views_count }} {{ __('articles.views') }}</small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Statistiche articolo -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('articles.article_stats') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="h4 mb-1">{{ $article->views_count }}</div>
                            <small class="text-muted">{{ __('articles.views') }}</small>
                        </div>
                        <div class="col-4">
                            <div class="h4 mb-1">{{ $article->likes_count }}</div>
                            <small class="text-muted">{{ __('articles.likes') }}</small>
                        </div>
                        <div class="col-4">
                            <div class="h4 mb-1">{{ $article->comments_count }}</div>
                            <small class="text-muted">{{ __('articles.comments') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Condivisione social -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('articles.share_article') }}</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                           target="_blank" class="btn btn-outline-primary">
                            <i class="ti ti-brand-facebook"></i> {{ __('articles.share_on_facebook') }}
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($article->title) }}"
                           target="_blank" class="btn btn-outline-info">
                            <i class="ti ti-brand-twitter"></i> {{ __('articles.share_on_twitter') }}
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}"
                           target="_blank" class="btn btn-outline-primary">
                            <i class="ti ti-brand-linkedin"></i> {{ __('articles.share_on_linkedin') }}
                        </a>
                        <button class="btn btn-outline-success" onclick="copyLink()">
                            <i class="ti ti-copy"></i> {{ __('articles.copy_link') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal per segnalazione -->
@if(auth()->check())
    <div class="modal fade" id="reportModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('articles.report_article') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="reportForm">
                        <div class="mb-3">
                            <label class="form-label">{{ __('articles.report_reason') }}</label>
                            <select name="reason" class="form-select" required>
                                <option value="">{{ __('articles.select_reason') }}</option>
                                <option value="spam">{{ __('articles.spam') }}</option>
                                <option value="inappropriate">{{ __('articles.inappropriate') }}</option>
                                <option value="copyright">{{ __('articles.copyright') }}</option>
                                <option value="fake_news">{{ __('articles.fake_news') }}</option>
                                <option value="other">{{ __('articles.other') }}</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{{ __('articles.report_description') }}</label>
                            <textarea name="description" class="form-control" rows="3"
                                      placeholder="{{ __('articles.report_description_placeholder') }}"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('articles.cancel') }}</button>
                    <button type="button" class="btn btn-warning" onclick="submitReport()">{{ __('articles.report') }}</button>
                </div>
            </div>
        </div>
    </div>
@endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Like functionality
    const likeBtn = document.querySelector('.like-btn');
    if (likeBtn) {
        likeBtn.addEventListener('click', function() {
            if (!{{ auth()->check() ? 'true' : 'false' }}) {
                window.location.href = '{{ route('login') }}';
                return;
            }

            const articleId = this.dataset.articleId;
            const isLiked = this.dataset.liked === 'true';

            fetch(`/articles/${articleId}/likes/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.dataset.liked = data.liked;
                    const icon = this.querySelector('i');
                    const count = this.querySelector('.likes-count');

                    if (data.liked) {
                        icon.classList.add('text-danger');
                    } else {
                        icon.classList.remove('text-danger');
                    }

                    count.textContent = data.likes_count;
                    showNotification(data.message, 'success');
                }
            });
        });
    }

    // Report functionality
    const reportBtn = document.querySelector('.report-btn');
    if (reportBtn) {
        reportBtn.addEventListener('click', function() {
            const isReported = this.dataset.reported === 'true';
            if (isReported) {
                showNotification('{{ __('articles.already_reported') }}', 'warning');
                return;
            }

            const modal = new bootstrap.Modal(document.getElementById('reportModal'));
            modal.show();
        });
    }

    // Comment form
    const commentForm = document.getElementById('commentForm');
    if (commentForm) {
        commentForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('{{ route('articles.comments.store', $article) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    content: formData.get('content')
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reset form
                    this.reset();

                    // Add new comment to list
                    if (data.status === 'approved') {
                        addCommentToList(data.comment);
                    }

                    showNotification(data.message, 'success');
                } else {
                    showNotification(data.message, 'error');
                }
            });
        });
    }
});

function addCommentToList(comment) {
    const commentsList = document.getElementById('commentsList');
    const commentHtml = `
        <div class="comment mb-3" data-comment-id="${comment.id}">
            <div class="d-flex">
                <img src="${comment.user.avatar_url || '/assets/images/avatar/default.png'}"
                     class="rounded-circle me-3" style="width: 40px; height: 40px;"
                     alt="${comment.user.name}">
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <strong>${comment.user.name}</strong>
                            <small class="text-muted ms-2">${comment.created_at}</small>
                        </div>
                    </div>
                    <p class="mb-2">${comment.content}</p>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary" onclick="likeComment(${comment.id})">
                            <i class="ti ti-heart"></i> 0
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" onclick="replyToComment(${comment.id})">
                            <i class="ti ti-message-circle"></i> {{ __('articles.reply') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;

    commentsList.insertAdjacentHTML('afterbegin', commentHtml);
}

function shareArticle() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $article->title }}',
            text: '{{ $article->excerpt }}',
            url: window.location.href
        });
    } else {
        copyLink();
    }
}

function copyLink() {
    navigator.clipboard.writeText(window.location.href).then(() => {
        showNotification('{{ __('articles.link_copied') }}', 'success');
    });
}

function showReportModal() {
    const modal = new bootstrap.Modal(document.getElementById('reportModal'));
    modal.show();
}

function submitReport() {
    const form = document.getElementById('reportForm');
    const formData = new FormData(form);

    fetch('{{ route('articles.reports.store', $article) }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            reason: formData.get('reason'),
            description: formData.get('description')
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const reportBtn = document.querySelector('.report-btn');
            if (reportBtn) {
                reportBtn.dataset.reported = 'true';
            }

            const modal = bootstrap.Modal.getInstance(document.getElementById('reportModal'));
            modal.hide();

            showNotification(data.message, 'success');
        } else {
            showNotification(data.message, 'error');
        }
    });
}

function publishArticle(articleId) {
    if (confirm('{{ __('articles.confirm_publish_article') }}')) {
        fetch(`/articles/${articleId}/publish`, {
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
}

function unpublishArticle(articleId) {
    if (confirm('{{ __('articles.confirm_unpublish_article') }}')) {
        fetch(`/articles/${articleId}/unpublish`, {
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
}

function toggleFeatured(articleId) {
    fetch(`/articles/${articleId}/toggle-featured`, {
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

function deleteArticle(articleId) {
    if (confirm('{{ __('articles.confirm_delete_article') }}')) {
        fetch(`/articles/${articleId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '{{ route('articles.index') }}';
            }
        });
    }
}

function showNotification(message, type) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
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
</script>
@endpush
