@extends('layout.master')

@section('main-content')
<div class="container-fluid">
    <!-- Header con pulsante Crea Articolo -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">{{ __('articles.articles') }}</h1>
                @auth
                @if(auth()->user()->can('articles.create'))
                    <a href="{{ route('articles.create') }}" class="btn btn-primary">
                        <i class="ph-duotone ph-plus me-2"></i>
                        {{ __('articles.create_article') }}
                    </a>
                @endif
                @endauth
            </div>
        </div>
    </div>

    <!-- Sezione Ricerca e Filtri -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <!-- Ricerca -->
                        <div class="col-md-6">
                            <form action="{{ route('articles.index') }}" method="GET" class="d-flex">
                                <input type="text" name="search" class="form-control me-2"
                                       placeholder="{{ __('articles.search_placeholder') }}"
                                       value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary">
                                    <i class="ph ph-magnifying-glass"></i>
                                </button>
                            </form>
                        </div>

                        <!-- Ordinamento -->
                        <div class="col-md-6 text-end">
                            <div class="btn-group" role="group">
                                <a href="{{ route('articles.index') }}?{{ http_build_query(array_merge(request()->except('sort'), ['sort' => 'newest'])) }}"
                                   class="btn btn-sm {{ request('sort', 'newest') === 'newest' ? 'btn-primary' : 'btn-outline-primary' }}">
                                    {{ __('articles.sort_newest') }}
                                </a>
                                <a href="{{ route('articles.index') }}?{{ http_build_query(array_merge(request()->except('sort'), ['sort' => 'oldest'])) }}"
                                   class="btn btn-sm {{ request('sort') === 'oldest' ? 'btn-primary' : 'btn-outline-primary' }}">
                                    {{ __('articles.sort_oldest') }}
                                </a>
                                <a href="{{ route('articles.index') }}?{{ http_build_query(array_merge(request()->except('sort'), ['sort' => 'popular'])) }}"
                                   class="btn btn-sm {{ request('sort') === 'popular' ? 'btn-primary' : 'btn-outline-primary' }}">
                                    {{ __('articles.sort_popular') }}
                                </a>
                                <a href="{{ route('articles.index') }}?{{ http_build_query(array_merge(request()->except('sort'), ['sort' => 'title'])) }}"
                                   class="btn btn-sm {{ request('sort') === 'title' ? 'btn-primary' : 'btn-outline-primary' }}">
                                    {{ __('articles.sort_title') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Filtri Categorie e Tag -->
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <h6 class="mb-2">{{ __('articles.categories') }}</h6>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('articles.index') }}"
                                   class="badge bg-primary text-decoration-none {{ !request('category') ? 'bg-primary' : 'bg-light text-dark' }}">
                                    {{ __('articles.all_categories') }}
                                </a>
                                @foreach($categories as $cat)
                                    <a href="{{ route('articles.index') }}?{{ http_build_query(array_merge(request()->except('category'), ['category' => $cat->slug])) }}"
                                       class="badge text-decoration-none {{ request('category') === $cat->slug ? 'bg-primary' : 'bg-light text-dark' }}">
                                        {{ $cat->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-2">{{ __('articles.popular_tags') }}</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($tags->take(10) as $tag)
                                    <a href="{{ route('articles.index') }}?{{ http_build_query(array_merge(request()->except('tag'), ['tag' => $tag->slug])) }}"
                                       class="badge bg-secondary text-decoration-none">
                                        {{ $tag->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Contenuto principale -->
        <div class="col-lg-8">
            @if(!$showAllArticles)
                <!-- Layout normale: Featured + Recent -->
                <!-- Sezione 1: Articolo Featured Orizzontale + 2 Articoli Recenti -->
                <div class="mb-5">
                    <!-- Articolo Featured Orizzontale -->
                    @if($featuredArticles->count() > 0)
                        @php $featured1 = $featuredArticles->get(0); @endphp
                        <div class="card mb-4">
                            <div class="card-body p-0">
                                <div class="position-relative">
                                    @if($featured1->featured_image)
                                        <img src="{{ Storage::url($featured1->featured_image) }}"
                                             class="w-100" style="height: 300px; object-fit: cover;"
                                             alt="{{ $featured1->title }}">
                                    @else
                                        <div class="w-100 d-flex align-items-center justify-content-center"
                                             style="height: 300px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            <div class="text-center text-white">
                                                <i class="ph ph-newspaper f-s-48 mb-2"></i>
                                                <div class="f-s-16 f-w-600">{{ __('articles.featured_article') }}</div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="position-absolute bottom-0 start-0 w-100 p-4"
                                         style="background: linear-gradient(transparent, rgba(0,0,0,0.8));">
                                        <div class="d-flex justify-content-between align-items-end">
                                            <div class="text-white">
                                                <span class="badge bg-primary mb-2">{{ __('articles.featured') }}</span>
                                                <h2 class="mb-2">{{ $featured1->title }}</h2>
                                                <p class="mb-2">{{ Str::limit($featured1->excerpt, 150) }}</p>
                                                <div class="d-flex align-items-center text-white-50">
                                                    <small>{{ __('articles.by') }}
                                                        <a href="{{ route('user.show', $featured1->user) }}" class="text-white-50 text-decoration-none">
                                                            <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($featured1->user) }}"
                                                                 class="rounded-circle me-1" style="width: 16px; height: 16px;"
                                                                 alt="{{ $featured1->user->name }}">
                                                            {{ $featured1->user->name }}
                                                        </a>
                                                    </small>
                                                    <span class="mx-2">•</span>
                                                    <small>{{ $featured1->published_at->format('d/m/Y') }}</small>
                                                    <span class="mx-2">•</span>
                                                    <small>{{ __('articles.read_time', ['minutes' => $featured1->read_time]) }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-3">
                                    <a href="{{ route('articles.show', $featured1) }}" class="btn btn-primary">
                                        {{ __('articles.read_more') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- 2 Articoli Recenti -->
                    <div class="row">
                        <div class="col-md-6">
                            @if($recentArticles->count() > 0)
                                @include('articles.partials.article-card', ['article' => $recentArticles->first()])
                            @endif
                        </div>
                        <div class="col-md-6">
                            @if($recentArticles->count() > 1)
                                @include('articles.partials.article-card', ['article' => $recentArticles->get(1)])
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sezione 2: Articolo Featured Orizzontale + 2 Articoli Recenti -->
                <div class="mb-5">
                    <!-- Articolo Featured Orizzontale -->
                    @if($featuredArticles->count() > 1)
                        @php $featured2 = $featuredArticles->get(1); @endphp
                        <div class="card mb-4">
                            <div class="card-body p-0">
                                <div class="position-relative">
                                    @if($featured2->featured_image)
                                        <img src="{{ Storage::url($featured2->featured_image) }}"
                                             class="w-100" style="height: 300px; object-fit: cover;"
                                             alt="{{ $featured2->title }}">
                                    @else
                                        <div class="w-100 d-flex align-items-center justify-content-center"
                                             style="height: 300px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            <div class="text-center text-white">
                                                <i class="ph ph-newspaper f-s-48 mb-2"></i>
                                                <div class="f-s-16 f-w-600">{{ __('articles.featured_article') }}</div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="position-absolute bottom-0 start-0 w-100 p-4"
                                         style="background: linear-gradient(transparent, rgba(0,0,0,0.8));">
                                        <div class="d-flex justify-content-between align-items-end">
                                            <div class="text-white">
                                                <span class="badge bg-primary mb-2">{{ __('articles.featured') }}</span>
                                                <h2 class="mb-2">{{ $featured2->title }}</h2>
                                                <p class="mb-2">{{ Str::limit($featured2->excerpt, 150) }}</p>
                                                <div class="d-flex align-items-center text-white-50">
                                                    <small>{{ __('articles.by') }}
                                                        <a href="{{ route('user.show', $featured2->user) }}" class="text-white-50 text-decoration-none">
                                                            <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($featured2->user) }}"
                                                                 class="rounded-circle me-1" style="width: 16px; height: 16px;"
                                                                 alt="{{ $featured2->user->name }}">
                                                            {{ $featured2->user->name }}
                                                        </a>
                                                    </small>
                                                    <span class="mx-2">•</span>
                                                    <small>{{ $featured2->published_at->format('d/m/Y') }}</small>
                                                    <span class="mx-2">•</span>
                                                    <small>{{ __('articles.read_time', ['minutes' => $featured2->read_time]) }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-3">
                                    <a href="{{ route('articles.show', $featured2) }}" class="btn btn-primary">
                                        {{ __('articles.read_more') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- 2 Articoli Recenti -->
                    <div class="row">
                        <div class="col-md-6">
                            @if($recentArticles->count() > 2)
                                @include('articles.partials.article-card', ['article' => $recentArticles->get(2)])
                            @endif
                        </div>
                        <div class="col-md-6">
                            @if($recentArticles->count() > 3)
                                @include('articles.partials.article-card', ['article' => $recentArticles->get(3)])
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sezione 3: Articolo Featured Orizzontale + 2 Articoli Recenti -->
                <div class="mb-5">
                    <!-- Articolo Featured Orizzontale -->
                    @if($featuredArticles->count() > 2)
                        @php $featured3 = $featuredArticles->get(2); @endphp
                        <div class="card mb-4">
                            <div class="card-body p-0">
                                <div class="position-relative">
                                    @if($featured3->featured_image)
                                        <img src="{{ Storage::url($featured3->featured_image) }}"
                                             class="w-100" style="height: 300px; object-fit: cover;"
                                             alt="{{ $featured3->title }}">
                                    @else
                                        <div class="w-100 d-flex align-items-center justify-content-center"
                                             style="height: 300px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            <div class="text-center text-white">
                                                <i class="ph ph-newspaper f-s-48 mb-2"></i>
                                                <div class="f-s-16 f-w-600">{{ __('articles.featured_article') }}</div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="position-absolute bottom-0 start-0 w-100 p-4"
                                         style="background: linear-gradient(transparent, rgba(0,0,0,0.8));">
                                        <div class="d-flex justify-content-between align-items-end">
                                            <div class="text-white">
                                                <span class="badge bg-primary mb-2">{{ __('articles.featured') }}</span>
                                                <h2 class="mb-2">{{ $featured3->title }}</h2>
                                                <p class="mb-2">{{ Str::limit($featured3->excerpt, 150) }}</p>
                                                <div class="d-flex align-items-center text-white-50">
                                                    <small>{{ __('articles.by') }}
                                                        <a href="{{ route('user.show', $featured3->user) }}" class="text-white-50 text-decoration-none">
                                                            <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($featured3->user) }}"
                                                                 class="rounded-circle me-1" style="width: 16px; height: 16px;"
                                                                 alt="{{ $featured3->user->name }}">
                                                            {{ $featured3->user->name }}
                                                        </a>
                                                    </small>
                                                    <span class="mx-2">•</span>
                                                    <small>{{ $featured3->published_at->format('d/m/Y') }}</small>
                                                    <span class="mx-2">•</span>
                                                    <small>{{ __('articles.read_time', ['minutes' => $featured3->read_time]) }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-3">
                                    <a href="{{ route('articles.show', $featured3) }}" class="btn btn-primary">
                                        {{ __('articles.read_more') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- 2 Articoli Recenti -->
                    <div class="row">
                        <div class="col-md-6">
                            @if($recentArticles->count() > 4)
                                @include('articles.partials.article-card', ['article' => $recentArticles->get(4)])
                            @endif
                        </div>
                        <div class="col-md-6">
                            @if($recentArticles->count() > 5)
                                @include('articles.partials.article-card', ['article' => $recentArticles->get(5)])
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <!-- Layout con tutti gli articoli quando vengono applicati filtri -->
                <div class="mb-4">
                    <h4 class="mb-3">
                        @if(request('search'))
                            {{ __('articles.search_results_for') }}: "{{ request('search') }}"
                        @elseif(request('category'))
                            {{ __('articles.articles_in_category') }}: {{ $categories->firstWhere('slug', request('category'))->name ?? '' }}
                        @elseif(request('tag'))
                            {{ __('articles.articles_with_tag') }}: {{ $tags->firstWhere('slug', request('tag'))->name ?? '' }}
                        @endif
                        ({{ $recentArticles->total() }} {{ __('articles.articles_found') }})
                    </h4>
                </div>

                <!-- Lista di tutti gli articoli -->
                <div class="row">
                    @forelse($recentArticles as $article)
                        <div class="col-md-6 col-lg-4 mb-4">
                            @include('articles.partials.article-card', ['article' => $article])
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="ph ph-newspaper text-muted" style="font-size: 4rem;"></i>
                                <h4 class="mt-3 text-muted">{{ __('articles.no_articles_found') }}</h4>
                                <p class="text-muted">{{ __('articles.try_different_filters') }}</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Paginazione -->
                @if($recentArticles->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $recentArticles->appends(request()->query())->links() }}
                    </div>
                @endif
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

            <!-- Articoli Recenti -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ph ph-clock me-2"></i>
                        {{ __('articles.recent_articles') }}
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($recentArticles->take(5) as $sidebarArticle)
                        <div class="d-flex align-items-start mb-3">
                            <div class="flex-shrink-0 me-3">
                                @if($sidebarArticle->featured_image)
                                    <img src="{{ Storage::url($sidebarArticle->featured_image) }}"
                                         class="rounded" style="width: 60px; height: 60px; object-fit: cover;"
                                         alt="{{ $sidebarArticle->title }}">
                                @else
                                    <div class="rounded d-flex align-items-center justify-content-center"
                                         style="width: 60px; height: 60px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                        <i class="ph ph-newspaper text-white f-s-20"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1">
                                    <a href="{{ route('articles.show', $sidebarArticle) }}" class="text-decoration-none">
                                        {{ Str::limit($sidebarArticle->title, 50) }}
                                    </a>
                                </h6>
                                <small class="text-muted">
                                    {{ $sidebarArticle->published_at->format('d/m/Y') }}
                                </small>
                            </div>
                        </div>
                        @if(!$loop->last)
                            <hr class="my-3">
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Tag popolari -->
            @if($tags->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('articles.popular_tags') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($tags->take(10) as $tag)
                                <a href="{{ route('articles.index', ['tag' => $tag->id]) }}"
                                   class="badge bg-light text-dark text-decoration-none">
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
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestione ricerca
    const searchForm = document.getElementById('searchForm');
    const searchInput = searchForm.querySelector('input[name="search"]');

    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchForm.submit();
        }
    });
});
</script>
@endpush
