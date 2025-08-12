@extends('layout.master')

@section('main-content')
<div class="container-fluid">
    <div class="row">
        <!-- Contenuto principale -->
        <div class="col-lg-8">
            <!-- Banner principale -->
            @if(isset($layoutArticles['banner']) && $layoutArticles['banner'])
                <div class="card mb-4">
                    <div class="card-body p-0">
                        <div class="position-relative">
                            @if($layoutArticles['banner']->featured_image)
                                <img src="{{ Storage::url($layoutArticles['banner']->featured_image) }}" 
                                     class="w-100" style="height: 300px; object-fit: cover;" 
                                     alt="{{ $layoutArticles['banner']->title }}">
                            @endif
                            <div class="position-absolute bottom-0 start-0 w-100 p-4" 
                                 style="background: linear-gradient(transparent, rgba(0,0,0,0.8));">
                                <div class="d-flex justify-content-between align-items-end">
                                    <div class="text-white">
                                        <h2 class="mb-2">{{ $layoutArticles['banner']->title }}</h2>
                                        <p class="mb-2">{{ Str::limit($layoutArticles['banner']->excerpt, 150) }}</p>
                                        <div class="d-flex align-items-center text-white-50">
                                            <small>{{ __('articles.by') }} {{ $layoutArticles['banner']->user->name }}</small>
                                            <span class="mx-2">•</span>
                                            <small>{{ $layoutArticles['banner']->published_at->format('d/m/Y') }}</small>
                                            <span class="mx-2">•</span>
                                            <small>{{ __('articles.read_time', ['minutes' => $layoutArticles['banner']->read_time]) }}</small>
                                        </div>
                                    </div>
                                    @if(auth()->check() && auth()->user()->hasPermissionTo('articles.manage_layout'))
                                        <button class="btn btn-sm btn-light" onclick="editLayoutPosition('banner', {{ $layoutArticles['banner']->id }})">
                                            <i class="ti ti-edit"></i> {{ __('articles.edit_layout') }}
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="p-3">
                            <a href="{{ route('articles.show', $layoutArticles['banner']) }}" class="btn btn-primary">
                                {{ __('articles.read_more') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Prima riga: 2 colonne -->
            <div class="row mb-4">
                <div class="col-md-6">
                    @if(isset($layoutArticles['column1']) && $layoutArticles['column1'])
                        @include('articles.partials.article-card', ['article' => $layoutArticles['column1'], 'position' => 'column1'])
                    @endif
                </div>
                <div class="col-md-6">
                    @if(isset($layoutArticles['column2']) && $layoutArticles['column2'])
                        @include('articles.partials.article-card', ['article' => $layoutArticles['column2'], 'position' => 'column2'])
                    @endif
                </div>
            </div>

            <!-- Articolo orizzontale 1 -->
            @if(isset($layoutArticles['horizontal1']) && $layoutArticles['horizontal1'])
                @include('articles.partials.article-horizontal', ['article' => $layoutArticles['horizontal1'], 'position' => 'horizontal1'])
            @endif

            <!-- Seconda riga: 2 colonne -->
            <div class="row mb-4">
                <div class="col-md-6">
                    @if($articles->count() > 0)
                        @include('articles.partials.article-card', ['article' => $articles->first()])
                    @endif
                </div>
                <div class="col-md-6">
                    @if($articles->count() > 1)
                        @include('articles.partials.article-card', ['article' => $articles->get(1)])
                    @endif
                </div>
            </div>

            <!-- Articolo orizzontale 2 -->
            @if(isset($layoutArticles['horizontal2']) && $layoutArticles['horizontal2'])
                @include('articles.partials.article-horizontal', ['article' => $layoutArticles['horizontal2'], 'position' => 'horizontal2'])
            @endif

            <!-- Terza riga: 2 colonne -->
            <div class="row mb-4">
                <div class="col-md-6">
                    @if($articles->count() > 2)
                        @include('articles.partials.article-card', ['article' => $articles->get(2)])
                    @endif
                </div>
                <div class="col-md-6">
                    @if($articles->count() > 3)
                        @include('articles.partials.article-card', ['article' => $articles->get(3)])
                    @endif
                </div>
            </div>

            <!-- Paginazione -->
            @if($articles->hasPages())
                <div class="d-flex justify-content-center">
                    {{ $articles->links() }}
                </div>
            @endif
        </div>

        <!-- Sidebar destra -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">{{ __('articles.search_articles') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('articles.index') }}" method="GET" id="searchForm">
                        <div class="mb-3">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="{{ __('articles.search_placeholder') }}" 
                                   value="{{ request('search') }}">
                        </div>
                        
                        @if($categories->count() > 0)
                            <div class="mb-3">
                                <select name="category" class="form-select">
                                    <option value="">{{ __('articles.filter_by_category') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="mb-3">
                            <select name="sort" class="form-select">
                                <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>
                                    {{ __('articles.sort_newest') }}
                                </option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>
                                    {{ __('articles.sort_popular') }}
                                </option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>
                                    {{ __('articles.sort_oldest') }}
                                </option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ti ti-search"></i> {{ __('articles.search_articles') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Toggle nuovi/popolari -->
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('articles.sidebar_articles') }}</h5>
                        <div class="btn-group btn-group-sm" role="group">
                            <input type="radio" class="btn-check" name="sidebar_sort" id="new_articles" value="new" 
                                   {{ request('sidebar_sort', 'new') == 'new' ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary" for="new_articles">{{ __('articles.new_articles') }}</label>
                            
                            <input type="radio" class="btn-check" name="sidebar_sort" id="popular_articles" value="popular" 
                                   {{ request('sidebar_sort') == 'popular' ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary" for="popular_articles">{{ __('articles.popular_articles') }}</label>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="sidebar-articles">
                        @foreach($sidebarArticles as $article)
                            @include('articles.partials.sidebar-article', ['article' => $article])
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Tag popolari -->
            @if($tags->count() > 0)
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('articles.tags') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($tags as $tag)
                                <a href="{{ route('articles.index', ['tag' => $tag->id]) }}" 
                                   class="badge bg-primary text-decoration-none">
                                    {{ $tag->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal per selezione articolo layout -->
@if(auth()->check() && auth()->user()->hasPermissionTo('articles.manage_layout'))
    <div class="modal fade" id="layoutModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('articles.select_article_for_position') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="text" id="articleSearch" class="form-control" 
                               placeholder="{{ __('articles.search_placeholder') }}">
                    </div>
                    <div id="articlesList" class="row">
                        <!-- Articoli caricati via AJAX -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle sidebar sort
    const sidebarSortRadios = document.querySelectorAll('input[name="sidebar_sort"]');
    sidebarSortRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            const url = new URL(window.location);
            url.searchParams.set('sidebar_sort', this.value);
            window.location.href = url.toString();
        });
    });

    // Search form
    const searchForm = document.getElementById('searchForm');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const searchInput = this.querySelector('input[name="search"]');
            if (!searchInput.value.trim()) {
                e.preventDefault();
            }
        });
    }

    // Layout management
    window.editLayoutPosition = function(position, currentArticleId) {
        const modal = new bootstrap.Modal(document.getElementById('layoutModal'));
        modal.show();
        
        // Carica articoli per la selezione
        loadArticlesForLayout(position, currentArticleId);
    };

    function loadArticlesForLayout(position, currentArticleId) {
        const searchTerm = document.getElementById('articleSearch').value;
        
        fetch(`{{ route('articles.layout.articles') }}?search=${searchTerm}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayArticlesForSelection(data.articles, position, currentArticleId);
                }
            });
    }

    function displayArticlesForSelection(articles, position, currentArticleId) {
        const container = document.getElementById('articlesList');
        container.innerHTML = '';

        articles.forEach(article => {
            const articleDiv = document.createElement('div');
            articleDiv.className = 'col-md-6 mb-3';
            articleDiv.innerHTML = `
                <div class="card h-100 ${article.id == currentArticleId ? 'border-primary' : ''}">
                    <div class="card-body">
                        <h6 class="card-title">${article.title}</h6>
                        <p class="card-text small text-muted">${article.excerpt || ''}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">${article.user.name}</small>
                            <button class="btn btn-sm ${article.id == currentArticleId ? 'btn-outline-danger' : 'btn-primary'}" 
                                    onclick="selectArticleForLayout('${position}', ${article.id})">
                                ${article.id == currentArticleId ? '{{ __('articles.remove') }}' : '{{ __('articles.select') }}'}
                            </button>
                        </div>
                    </div>
                </div>
            `;
            container.appendChild(articleDiv);
        });
    }

    window.selectArticleForLayout = function(position, articleId) {
        fetch('{{ route('articles.layout.update') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                position: position,
                article_id: articleId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Errore: ' + data.message);
            }
        });
    };

    // Search in modal
    const articleSearch = document.getElementById('articleSearch');
    if (articleSearch) {
        let searchTimeout;
        articleSearch.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                loadArticlesForLayout();
            }, 500);
        });
    }
});
</script>
@endpush
