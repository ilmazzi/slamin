@extends('layout.master')
@section('main-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
                        <h4 class="mb-0 f-s-18 f-w-600">{{ __('articles.my_articles') }}</h4>
                        <a href="{{ route('articles.create') }}" class="btn btn-primary">
                            <i class="ti ti-plus me-1"></i> {{ __('articles.create_new_article') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Mobile-First Filters -->
                    <div class="row g-3 mb-4">
                        <div class="col-12 col-sm-6 col-md-3">
                            <select class="form-select" id="statusFilter">
                                <option value="">{{ __('articles.all_statuses') }}</option>
                                <option value="draft">{{ __('articles.draft') }}</option>
                                <option value="published">{{ __('articles.published') }}</option>
                                <option value="pending">{{ __('articles.pending_review') }}</option>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <select class="form-select" id="categoryFilter">
                                <option value="">{{ __('articles.all_categories') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <input type="text" class="form-control" id="searchFilter" placeholder="{{ __('articles.search_articles') }}">
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <button class="btn btn-primary w-100" onclick="applyFilters()">
                                <i class="ti ti-filter me-1"></i> {{ __('articles.apply_filters') }}
                            </button>
                        </div>
                    </div>

                    @if($articles->count() > 0)
                        <!-- Mobile-First Articles Grid -->
                        <div class="row g-3">
                            @foreach($articles as $article)
                                <div class="col-12 col-sm-6 col-lg-4">
                                    <div class="card h-100 hover-effect">
                                        @if($article->featured_image)
                                            <img src="{{ Storage::url($article->featured_image) }}"
                                                 class="card-img-top" style="height: 180px; object-fit: cover;"
                                                 alt="{{ $article->title }}">
                                        @else
                                            <div class="card-img-top d-flex align-items-center justify-content-center"
                                                 style="height: 180px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                <div class="text-center text-white">
                                                    <i class="ph ph-newspaper f-s-32 mb-2"></i>
                                                    <div class="f-s-14 f-w-600">{{ __('articles.article') }}</div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="card-body d-flex flex-column">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <div class="d-flex flex-wrap gap-1">
                                                    @if($article->category)
                                                        <span class="badge bg-primary f-s-11">{{ $article->category->name }}</span>
                                                    @endif
                                                    @if($article->featured)
                                                        <span class="badge bg-warning f-s-11">{{ __('articles.featured') }}</span>
                                                    @endif
                                                    <span class="badge {{ $article->status === 'published' ? 'bg-success' : ($article->status === 'pending' ? 'bg-warning' : 'bg-secondary') }} f-s-11">
                                                        {{ __('articles.' . $article->status) }}
                                                    </span>
                                                </div>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                                        <i class="ti ti-dots-vertical f-s-12"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('articles.show', $article) }}" target="_blank">
                                                                <i class="ti ti-eye me-2"></i> {{ __('articles.view') }}
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('articles.edit', $article) }}">
                                                                <i class="ti ti-edit me-2"></i> {{ __('articles.edit') }}
                                                            </a>
                                                        </li>
                                                        @if($article->status === 'published')
                                                            <li>
                                                                <button class="dropdown-item" onclick="unpublishArticle({{ $article->id }})">
                                                                    <i class="ti ti-eye-off me-2"></i> {{ __('articles.unpublish') }}
                                                                </button>
                                                            </li>
                                                        @else
                                                            <li>
                                                                <button class="dropdown-item" onclick="publishArticle({{ $article->id }})">
                                                                    <i class="ti ti-eye me-2"></i> {{ __('articles.publish') }}
                                                                </button>
                                                            </li>
                                                        @endif
                                                        <li>
                                                            <button class="dropdown-item" onclick="toggleFeatured({{ $article->id }})">
                                                                <i class="ti ti-star me-2"></i>
                                                                {{ $article->featured ? __('articles.unfeature') : __('articles.feature') }}
                                                            </button>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <button class="dropdown-item text-danger" onclick="deleteArticle({{ $article->id }}, '{{ addslashes($article->title) }}')">
                                                                <i class="ti ti-trash me-2"></i> {{ __('articles.delete') }}
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <h6 class="card-title f-s-16 f-w-600 mb-2 flex-grow-1">
                                                <a href="{{ route('articles.show', $article) }}" class="text-decoration-none">
                                                    {{ Str::limit($article->title, 60) }}
                                                </a>
                                            </h6>

                                            @if($article->excerpt)
                                                <p class="card-text text-muted f-s-13 mb-3 flex-grow-1">{{ Str::limit($article->excerpt, 100) }}</p>
                                            @endif

                                            <div class="d-flex flex-wrap align-items-center text-muted mb-3 f-s-12">
                                                <span class="me-2">{{ __('articles.by') }}
                                                    <a href="{{ route('user.show', $article->user) }}" class="text-decoration-none">
                                                        <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($article->user) }}"
                                                             class="rounded-circle me-1" style="width: 14px; height: 14px;"
                                                             alt="{{ $article->user->name }}">
                                                        {{ Str::limit($article->user->name, 15) }}
                                                    </a>
                                                </span>
                                                <span class="mx-1">•</span>
                                                <span>{{ $article->published_at ? $article->published_at->format('d/m/Y') : __('articles.not_published') }}</span>
                                            </div>

                                            <!-- Mobile-First Statistics -->
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <div class="d-flex align-items-center text-muted f-s-12">
                                                    <i class="ti ti-eye me-1"></i>
                                                    <span>{{ $article->views_count ?? 0 }}</span>
                                                    <span class="mx-2">•</span>
                                                    <i class="ti ti-message-circle me-1"></i>
                                                    <span>{{ $article->comments_count ?? 0 }}</span>
                                                    <span class="mx-2">•</span>
                                                    <i class="ti ti-heart me-1"></i>
                                                    <span>{{ $article->likes_count ?? 0 }}</span>
                                                </div>
                                            </div>

                                            <!-- Mobile-First Actions -->
                                            <div class="d-flex justify-content-between align-items-center mt-auto">
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('articles.edit', $article) }}" class="btn btn-outline-primary btn-sm">
                                                        <i class="ti ti-edit f-s-12 me-1"></i>
                                                        <span class="d-none d-sm-inline">{{ __('articles.edit') }}</span>
                                                    </a>
                                                    <a href="{{ route('articles.show', $article) }}" class="btn btn-outline-secondary btn-sm">
                                                        <i class="ti ti-eye f-s-12 me-1"></i>
                                                        <span class="d-none d-sm-inline">{{ __('articles.view') }}</span>
                                                    </a>
                                                </div>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary" type="button" data-bs-toggle="dropdown">
                                                        <i class="ti ti-dots-vertical f-s-12"></i>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ route('articles.show', $article) }}" target="_blank">
                                                                <i class="ti ti-external-link me-2"></i> {{ __('articles.open_new_tab') }}
                                                            </a>
                                                        </li>
                                                        @if($article->status === 'published')
                                                            <li>
                                                                <button class="dropdown-item" onclick="unpublishArticle({{ $article->id }})">
                                                                    <i class="ti ti-eye-off me-2"></i> {{ __('articles.unpublish') }}
                                                                </button>
                                                            </li>
                                                        @else
                                                            <li>
                                                                <button class="dropdown-item" onclick="publishArticle({{ $article->id }})">
                                                                    <i class="ti ti-eye me-2"></i> {{ __('articles.publish') }}
                                                                </button>
                                                            </li>
                                                        @endif
                                                        <li>
                                                            <button class="dropdown-item" onclick="toggleFeatured({{ $article->id }})">
                                                                <i class="ti ti-star me-2"></i>
                                                                {{ $article->featured ? __('articles.unfeature') : __('articles.feature') }}
                                                            </button>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <button class="dropdown-item text-danger" onclick="deleteArticle({{ $article->id }}, '{{ addslashes($article->title) }}')">
                                                                <i class="ti ti-trash me-2"></i> {{ __('articles.delete') }}
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Mobile-First Pagination -->
                        @if($articles->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $articles->appends(request()->query())->links() }}
                            </div>
                        @endif
                    @else
                        <!-- Mobile-First No Articles -->
                        <div class="text-center py-5">
                            <i class="ph ph-newspaper text-muted f-s-48"></i>
                            <h4 class="mt-3 text-muted f-s-18">{{ __('articles.no_articles_yet') }}</h4>
                            <p class="text-muted f-s-14 mb-4">{{ __('articles.start_writing_your_first_article') }}</p>
                            <a href="{{ route('articles.create') }}" class="btn btn-primary">
                                <i class="ti ti-plus me-2"></i>{{ __('articles.create_first_article') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Article Modal -->
<div class="modal fade" id="deleteArticleModal" tabindex="-1" aria-labelledby="deleteArticleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="deleteArticleModalLabel">
                    <i class="ph ph-warning me-2"></i>{{ __('articles.delete_article_title') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('common.close') }}"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('articles.confirm_delete') }} <strong id="deleteArticleTitle"></strong>?</p>
                <div class="alert alert-warning">
                    <i class="ph ph-warning me-2"></i>
                    <strong>{{ __('articles.warning') }}</strong> {{ __('articles.delete_action_warning') }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('articles.cancel') }}</button>
                <form id="deleteArticleForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="ph ph-trash me-2"></i>{{ __('articles.delete_permanently') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile-First Filter Enhancements
    const statusFilter = document.getElementById('statusFilter');
    const categoryFilter = document.getElementById('categoryFilter');
    const searchFilter = document.getElementById('searchFilter');

    // Auto-apply filters on mobile for better UX
    if (window.innerWidth < 768) {
        statusFilter.addEventListener('change', applyFilters);
        categoryFilter.addEventListener('change', applyFilters);

        // Debounced search for mobile
        let searchTimeout;
        searchFilter.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(applyFilters, 500);
        });
    }

    // Mobile-friendly filter application
    function applyFilters() {
        const status = statusFilter.value;
        const category = categoryFilter.value;
        const search = searchFilter.value;

        // Build query parameters
        const params = new URLSearchParams();
        if (status) params.append('status', status);
        if (category) params.append('category', category);
        if (search) params.append('search', search);

        // Redirect with filters
        const url = new URL(window.location);
        url.search = params.toString();
        window.location.href = url.toString();
    }

    // Make applyFilters globally available
    window.applyFilters = applyFilters;
});

// Article management functions
function publishArticle(articleId) {
    if (confirm('{{ __("articles.confirm_publish") }}')) {
        // Implementation for publishing article
        console.log('Publishing article:', articleId);
    }
}

function unpublishArticle(articleId) {
    if (confirm('{{ __("articles.confirm_unpublish") }}')) {
        // Implementation for unpublishing article
        console.log('Unpublishing article:', articleId);
    }
}

function toggleFeatured(articleId) {
    if (confirm('{{ __("articles.confirm_toggle_featured") }}')) {
        // Implementation for toggling featured status
        console.log('Toggling featured for article:', articleId);
    }
}

function deleteArticle(articleId, title) {
    // Set the article title in the modal
    const titleElement = document.getElementById('deleteArticleTitle');
    if (titleElement) {
        titleElement.textContent = title;
    }

    // Set the form action
    const formElement = document.getElementById('deleteArticleForm');
    if (formElement) {
        formElement.action = `/articles/${articleId}`;
    }

    // Show the modal
    const modalElement = document.getElementById('deleteArticleModal');
    if (modalElement) {
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
    }
}
</script>
@endpush
