@extends('layout.master')

@section('main-content')
<div class="container-fluid">
    <!-- Mobile-First Header -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
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

    <!-- Mobile-First Search and Filters -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Mobile-First Search -->
                    <div class="mb-3">
                        <form action="{{ route('articles.index') }}" method="GET" class="d-flex gap-2">
                            <input type="text" name="search" class="form-control flex-grow-1"
                                   placeholder="{{ __('articles.search_placeholder') }}"
                                   value="{{ request('search') }}">
                            <button type="submit" class="btn btn-primary">
                                <i class="ph ph-magnifying-glass"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Mobile-First Sorting -->
                    <div class="mb-3">
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('articles.index') }}?{{ http_build_query(array_merge(request()->except('sort'), ['sort' => 'newest'])) }}"
                               class="btn btn-sm {{ request('sort', 'newest') === 'newest' ? 'btn-primary' : 'btn-light' }}">
                                {{ __('articles.sort_newest') }}
                            </a>
                            <a href="{{ route('articles.index') }}?{{ http_build_query(array_merge(request()->except('sort'), ['sort' => 'oldest'])) }}"
                               class="btn btn-sm {{ request('sort') === 'oldest' ? 'btn-primary' : 'btn-light' }}">
                                {{ __('articles.sort_oldest') }}
                            </a>
                            <a href="{{ route('articles.index') }}?{{ http_build_query(array_merge(request()->except('sort'), ['sort' => 'popular'])) }}"
                               class="btn btn-sm {{ request('sort') === 'popular' ? 'btn-primary' : 'btn-light' }}">
                                {{ __('articles.sort_popular') }}
                            </a>
                            <a href="{{ route('articles.index') }}?{{ http_build_query(array_merge(request()->except('sort'), ['sort' => 'title'])) }}"
                               class="btn btn-sm {{ request('sort') === 'title' ? 'btn-primary' : 'btn-light' }}">
                                {{ __('articles.sort_title') }}
                            </a>
                        </div>
                    </div>

                    <!-- Mobile-First Category and Tag Filters -->
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <h6 class="mb-2 f-s-14 f-w-600">{{ __('articles.categories') }}</h6>
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('articles.index') }}"
                                   class="badge {{ !request('category') ? 'bg-primary' : 'bg-light text-dark' }} text-decoration-none">
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
                        <div class="col-12 col-sm-6">
                            <h6 class="mb-2 f-s-14 f-w-600">{{ __('articles.popular_tags') }}</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($tags->take(8) as $tag)
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

    <!-- Mobile-First Content Layout -->
    <div class="row">
        <!-- Main Content - Mobile-First -->
        <div class="col-12 col-lg-8">
            @if(!$showAllArticles)
                <!-- Mobile-First Featured Articles -->
                @if($featuredArticles->count() > 0)
                    <div class="mb-4">
                        <h4 class="mb-3 f-s-18 f-w-600">{{ __('articles.featured_articles') }}</h4>

                        <!-- Featured Article 1 - Mobile-First -->
                        @php $featured1 = $featuredArticles->get(0); @endphp
                        <div class="card mb-4 hover-effect">
                            <div class="card-body p-0">
                                <div class="position-relative">
                                    @if($featured1->featured_image)
                                        <img src="{{ Storage::url($featured1->featured_image) }}"
                                             class="w-100" style="height: 200px; object-fit: cover;"
                                             alt="{{ $featured1->title }}">
                                    @else
                                        <div class="w-100 d-flex align-items-center justify-content-center"
                                             style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            <div class="text-center text-white">
                                                <i class="ph ph-newspaper f-s-32 mb-2"></i>
                                                <div class="f-s-14 f-w-600">{{ __('articles.featured_article') }}</div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="position-absolute bottom-0 start-0 w-100 p-3"
                                         style="background: linear-gradient(transparent, rgba(0,0,0,0.8));">
                                        <div class="text-white">
                                            <span class="badge bg-primary mb-2">{{ __('articles.featured') }}</span>
                                            <h5 class="mb-2 f-s-16 f-w-600">{{ $featured1->title }}</h5>
                                            <p class="mb-2 f-s-14">{{ Str::limit($featured1->excerpt, 100) }}</p>
                                            <div class="d-flex flex-wrap align-items-center text-white-50 f-s-12">
                                                <span>{{ __('articles.by') }}
                                                    <a href="{{ route('user.show', $featured1->user) }}" class="text-white-50 text-decoration-none">
                                                        <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($featured1->user) }}"
                                                             class="rounded-circle me-1" style="width: 16px; height: 16px;"
                                                             alt="{{ $featured1->user->name }}">
                                                        {{ $featured1->user->name }}
                                                    </a>
                                                </span>
                                                <span class="mx-2">•</span>
                                                <span>{{ $featured1->published_at->format('d/m/Y') }}</span>
                                                <span class="mx-2">•</span>
                                                <span>{{ __('articles.read_time', ['minutes' => $featured1->read_time]) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-3">
                                    <a href="{{ route('articles.show', $featured1) }}" class="btn btn-primary btn-sm">
                                        {{ __('articles.read_more') }}
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Featured Articles - Mobile-First Grid -->
                        @if($featuredArticles->count() > 1)
                            <div class="row g-3">
                                @foreach($featuredArticles->skip(1)->take(2) as $featured)
                                    <div class="col-12 col-sm-6">
                                        <div class="card h-100 hover-effect">
                                            <div class="card-body p-0">
                                                <div class="position-relative">
                                                    @if($featured->featured_image)
                                                        <img src="{{ Storage::url($featured->featured_image) }}"
                                                             class="w-100" style="height: 150px; object-fit: cover;"
                                                             alt="{{ $featured->title }}">
                                                    @else
                                                        <div class="w-100 d-flex align-items-center justify-content-center"
                                                             style="height: 150px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                            <i class="ph ph-newspaper text-white f-s-24"></i>
                                                        </div>
                                                    @endif
                                                    <div class="position-absolute bottom-0 start-0 w-100 p-2"
                                                         style="background: linear-gradient(transparent, rgba(0,0,0,0.8));">
                                                        <span class="badge bg-primary">{{ __('articles.featured') }}</span>
                                                    </div>
                                                </div>
                                                <div class="p-3">
                                                    <h6 class="f-s-14 f-w-600 mb-2">
                                                        <a href="{{ route('articles.show', $featured) }}" class="text-decoration-none">
                                                            {{ Str::limit($featured->title, 60) }}
                                                        </a>
                                                    </h6>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <small class="text-muted f-s-12">
                                                            {{ $featured->published_at->format('d/m/Y') }}
                                                        </small>
                                                        <a href="{{ route('articles.show', $featured) }}" class="btn btn-primary btn-sm">
                                                            {{ __('articles.read_more') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Mobile-First Recent Articles -->
                <div class="mb-4">
                    <h4 class="mb-3 f-s-18 f-w-600">{{ __('articles.recent_articles') }}</h4>
                    <div class="row g-3">
                        @foreach($recentArticles->take(6) as $article)
                            <div class="col-12 col-sm-6 col-lg-4">
                                @include('articles.partials.article-card', ['article' => $article])
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <!-- Mobile-First All Articles Layout -->
                <div class="mb-4">
                    <h4 class="mb-3 f-s-18 f-w-600">
                        @if(request('search'))
                            {{ __('articles.search_results_for') }}: "{{ request('search') }}"
                        @elseif(request('category'))
                            {{ __('articles.articles_in_category') }}: {{ $categories->firstWhere('slug', request('category'))->name ?? '' }}
                        @elseif(request('tag'))
                            {{ __('articles.articles_with_tag') }}: {{ $tags->firstWhere('slug', request('tag'))->name ?? '' }}
                        @endif
                        <span class="text-muted f-s-14">({{ $recentArticles->total() }} {{ __('articles.articles_found') }})</span>
                    </h4>
                </div>

                <!-- Mobile-First Articles Grid -->
                <div class="row g-3">
                    @forelse($recentArticles as $article)
                        <div class="col-12 col-sm-6 col-lg-4">
                            @include('articles.partials.article-card', ['article' => $article])
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="ph ph-newspaper text-muted f-s-48"></i>
                                <h4 class="mt-3 text-muted f-s-18">{{ __('articles.no_articles_found') }}</h4>
                                <p class="text-muted f-s-14">{{ __('articles.try_different_filters') }}</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Mobile-First Pagination -->
                @if($recentArticles->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $recentArticles->appends(request()->query())->links() }}
                    </div>
                @endif
            @endif
        </div>

        <!-- Mobile-First Sidebar -->
        <div class="col-12 col-lg-4 mt-4 mt-lg-0">
            <!-- Mobile-First Search Widget -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0 f-s-16 f-w-600">{{ __('articles.search_articles') }}</h5>
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
                            <i class="ti ti-search me-2"></i> {{ __('articles.search_articles') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Mobile-First Recent Articles Sidebar -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0 f-s-16 f-w-600">
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
                                         class="rounded" style="width: 50px; height: 50px; object-fit: cover;"
                                         alt="{{ $sidebarArticle->title }}">
                                @else
                                    <div class="rounded d-flex align-items-center justify-content-center"
                                         style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                        <i class="ph ph-newspaper text-white f-s-16"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 f-s-14 f-w-600">
                                    <a href="{{ route('articles.show', $sidebarArticle) }}" class="text-decoration-none">
                                        {{ Str::limit($sidebarArticle->title, 45) }}
                                    </a>
                                </h6>
                                <small class="text-muted f-s-12">
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

            <!-- Mobile-First Popular Tags -->
            @if($tags->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0 f-s-16 f-w-600">{{ __('articles.popular_tags') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($tags->take(8) as $tag)
                                <a href="{{ route('articles.index', ['tag' => $tag->id]) }}"
                                   class="badge bg-light text-dark text-decoration-none f-s-12">
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
    // Mobile-First Search Handling
    const searchForm = document.getElementById('searchForm');
    const searchInput = searchForm.querySelector('input[name="search"]');

    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchForm.submit();
        }
    });

    // Mobile-First Responsive Adjustments
    function adjustMobileLayout() {
        const isMobile = window.innerWidth < 768;
        const featuredCards = document.querySelectorAll('.card.hover-effect');

        if (isMobile) {
            featuredCards.forEach(card => {
                card.classList.add('mb-3');
            });
        } else {
            featuredCards.forEach(card => {
                card.classList.remove('mb-3');
            });
        }
    }

    // Initial adjustment
    adjustMobileLayout();

    // Adjust on resize
    window.addEventListener('resize', adjustMobileLayout);
});
</script>
@endpush
