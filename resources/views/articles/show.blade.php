@extends('layout.master')

@section('main-content')
<div class="container-fluid">
    <div class="row">
        <!-- Main Content - Mobile-First -->
        <div class="col-12 col-lg-8">
            @section('breadcrumb-title')
            <h3 class="f-s-18 f-w-600">{{ $article->title }}</h3>
            @endsection

            @section('breadcrumb-items')
            <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('articles.home') }}</a></li>
            <li class="breadcrumb-item"><a href="{{ route('articles.index') }}">{{ __('articles.news') }}</a></li>
            <li class="breadcrumb-item active">{{ Str::limit($article->title, 40) }}</li>
            @endsection

            <!-- Mobile-First Article Card -->
            <div class="card mb-4 hover-effect">
                @if($article->featured_image)
                    <img src="{{ Storage::url($article->featured_image) }}"
                         class="card-img-top" style="height: 250px; object-fit: cover;"
                         alt="{{ $article->title }}">
                @else
                    <div class="card-img-top d-flex align-items-center justify-content-center"
                         style="height: 250px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                        <div class="text-center text-white">
                            <i class="ph ph-newspaper f-s-48 mb-2"></i>
                            <div class="f-s-16 f-w-600">{{ __('articles.article') }}</div>
                        </div>
                    </div>
                @endif
                <div class="card-body">
                    <!-- Mobile-First Article Header -->
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-3">
                        <div class="d-flex flex-wrap gap-2">
                            @if($article->category)
                                <span class="badge f-s-12" style="background-color: {{ $article->category->color }}">
                                    {{ $article->category->name }}
                                </span>
                            @endif
                            @if($article->featured)
                                <span class="badge bg-warning f-s-12">{{ __('articles.featured') }}</span>
                            @endif
                        </div>
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <i class="ti ti-dots-vertical f-s-14"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if(auth()->check() && auth()->user()->can('edit', $article))
                                    <li><a class="dropdown-item" href="{{ route('articles.edit', $article) }}">
                                        <i class="ti ti-edit me-2"></i> {{ __('articles.edit_article') }}
                                    </a></li>
                                @endif
                                @if(auth()->check() && auth()->user()->hasPermissionTo('articles.publish'))
                                    @if($article->isPublished)
                                        <li><a class="dropdown-item" href="#" onclick="unpublishArticle({{ $article->id }})">
                                            <i class="ti ti-eye-off me-2"></i> {{ __('articles.unpublish') }}
                                        </a></li>
                                    @else
                                        <li><a class="dropdown-item" href="#" onclick="publishArticle({{ $article->id }})">
                                            <i class="ti ti-eye me-2"></i> {{ __('articles.publish') }}
                                        </a></li>
                                    @endif
                                @endif
                                @if(auth()->check() && auth()->user()->hasPermissionTo('articles.feature'))
                                    <li><a class="dropdown-item" href="#" onclick="toggleFeatured({{ $article->id }})">
                                        <i class="ti ti-star me-2"></i> {{ $article->featured ? __('articles.unfeature') : __('articles.feature') }}
                                    </a></li>
                                @endif
                                @if(auth()->check() && auth()->user()->can('delete', $article))
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#" onclick="deleteArticle({{ $article->id }})">
                                        <i class="ti ti-trash me-2"></i> {{ __('articles.delete') }}
                                    </a></li>
                                @endif
                            </ul>
                        </div>
                    </div>

                    <!-- Mobile-First Title and Meta -->
                    <h1 class="card-title mb-3 f-s-20 f-w-600">{{ $article->title }}</h1>

                    <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center text-muted mb-4 gap-2">
                        <div class="d-flex align-items-center">
                            <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($article->user) }}"
                                 class="rounded-circle me-2" style="width: 28px; height: 28px;"
                                 alt="{{ $article->user->name }}">
                            <span class="f-s-14">{{ __('articles.by') }}
                                <a href="{{ route('user.show', $article->user) }}" class="text-decoration-none">
                                    {{ Str::limit($article->user->name, 20) }}
                                </a>
                            </span>
                        </div>
                        <div class="d-flex flex-wrap align-items-center gap-2 f-s-12">
                            <span>{{ $article->published_at->format('d/m/Y H:i') }}</span>
                            <span>•</span>
                            <span>{{ __('articles.read_time', ['minutes' => $article->read_time]) }}</span>
                            <span>•</span>
                            <span>{{ $article->views_count }} {{ __('articles.views') }}</span>
                        </div>
                    </div>

                    <!-- Mobile-First Tags -->
                    @if($article->tags->count() > 0)
                        <div class="mb-4">
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($article->tags as $tag)
                                    <span class="badge bg-light text-dark f-s-12">{{ $tag->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Mobile-First Social Actions -->
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3 mb-4">
                        <div class="d-flex flex-wrap gap-2">
                            <!-- Like Button (Sistema Unificato) -->
                            <x-social-like-button :content="$article" type="article" />

                            <!-- View Counter (Sistema Unificato) -->
                            <x-social-view-counter :content="$article" type="article" />

                            <!-- Condividi -->
                            <button class="btn btn-outline-info btn-sm" onclick="shareArticle()">
                                <i class="ti ti-share f-s-14 me-1"></i> {{ __('articles.share_article') }}
                            </button>

                            <!-- Report Button (Sistema Unificato) -->
                            <x-report-button :content="$article" type="article" />
                        </div>

                        <!-- Stampa -->
                        <button class="btn btn-outline-secondary btn-sm" onclick="window.print()">
                            <i class="ti ti-printer f-s-14 me-1"></i> {{ __('articles.print_article') }}
                        </button>
                    </div>

                    <!-- Mobile-First Article Content -->
                    <div class="article-content mb-4">
                        <div class="f-s-14 lh-base">
                            {!! $article->content !!}
                        </div>
                    </div>

                    <!-- Mobile-First Author Section -->
                    <div class="card bg-light-success">
                        <div class="card-body">
                            <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-3">
                                <a href="{{ route('user.show', $article->user) }}" class="text-decoration-none">
                                    <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($article->user) }}"
                                         class="rounded-circle" style="width: 56px; height: 56px;"
                                         alt="{{ $article->user->name }}">
                                </a>
                                <div class="flex-grow-1">
                                    <h6 class="mb-2 f-s-16 f-w-600">{{ __('articles.by') }}
                                        <a href="{{ route('user.show', $article->user) }}" class="text-decoration-none hover-effect">
                                            {{ $article->user->name }}
                                        </a>
                                    </h6>
                                    <p class="text-muted mb-0 f-s-14">{{ $article->user->profile->bio ?? __('articles.no_bio') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Comments Section (Sistema Unificato) -->
            <x-social-comments-section :content="$article" type="article" />
        </div>

        <!-- Mobile-First Sidebar -->
        <div class="col-12 col-lg-4 mt-4 mt-lg-0">
            <!-- Mobile-First Related Articles -->
            @if($relatedArticles->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0 f-s-16 f-w-600">{{ __('articles.related_articles') }}</h5>
                    </div>
                    <div class="card-body p-0">
                        @foreach($relatedArticles as $relatedArticle)
                            <div class="border-bottom p-3">
                                <h6 class="mb-2 f-s-14 f-w-600">
                                    <a href="{{ route('articles.show', $relatedArticle) }}" class="text-decoration-none">
                                        {{ Str::limit($relatedArticle->title, 50) }}
                                    </a>
                                </h6>
                                <div class="d-flex flex-wrap align-items-center text-muted f-s-12 gap-2">
                                    <span>{{ $relatedArticle->published_at->format('d/m/Y') }}</span>
                                    <span>•</span>
                                    <span>{{ $relatedArticle->views_count }} {{ __('articles.views') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Mobile-First Article Statistics -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0 f-s-16 f-w-600">{{ __('articles.article_stats') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center g-3">
                        <div class="col-4">
                            <div class="h5 mb-1 f-w-600">{{ $article->views_count }}</div>
                            <small class="text-muted f-s-12">{{ __('articles.views') }}</small>
                        </div>
                        <div class="col-4">
                            <div class="h5 mb-1 f-w-600">{{ $article->likes_count }}</div>
                            <small class="text-muted f-s-12">{{ __('articles.likes') }}</small>
                        </div>
                        <div class="col-4">
                            <div class="h5 mb-1 f-w-600">{{ $article->comments_count }}</div>
                            <small class="text-muted f-s-12">{{ __('articles.comments') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile-First Social Sharing -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 f-s-16 f-w-600">{{ __('articles.share_article') }}</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}"
                           target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="ti ti-brand-facebook me-2"></i> {{ __('articles.share_on_facebook') }}
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($article->title) }}"
                           target="_blank" class="btn btn-outline-info btn-sm">
                            <i class="ti ti-brand-twitter me-2"></i> {{ __('articles.share_on_twitter') }}
                        </a>
                        <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ urlencode(request()->url()) }}"
                           target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="ti ti-brand-linkedin me-2"></i> {{ __('articles.share_on_linkedin') }}
                        </a>
                        <button class="btn btn-outline-success btn-sm" onclick="copyLink()">
                            <i class="ti ti-copy me-2"></i> {{ __('articles.copy_link') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile-First Enhancements
    const isMobile = window.innerWidth < 768;

    if (isMobile) {
        // Mobile-specific adjustments
        const articleContent = document.querySelector('.article-content');
        if (articleContent) {
            // Ensure content is readable on mobile
            articleContent.style.fontSize = '16px';
            articleContent.style.lineHeight = '1.6';
        }

        // Mobile-friendly image handling
        const articleImages = document.querySelectorAll('.article-content img');
        articleImages.forEach(img => {
            img.style.maxWidth = '100%';
            img.style.height = 'auto';
            img.classList.add('img-fluid');
        });
    }

    // Responsive adjustments
    function adjustMobileLayout() {
        const isMobile = window.innerWidth < 768;
        const articleCard = document.querySelector('.card.hover-effect');

        if (isMobile && articleCard) {
            articleCard.classList.add('mb-3');
        } else if (articleCard) {
            articleCard.classList.remove('mb-3');
        }
    }

    // Initial adjustment
    adjustMobileLayout();

    // Adjust on resize
    window.addEventListener('resize', adjustMobileLayout);
});

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
