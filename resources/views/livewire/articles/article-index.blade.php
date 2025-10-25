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

    <!-- Mobile-First Sidebar Toggle Button -->
    <div class="row mb-3 d-lg-none">
        <div class="col-12">
            <button class="btn btn-outline-primary btn-sm w-100" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarCollapse" aria-expanded="false" aria-controls="sidebarCollapse">
                <i class="ph ph-funnel me-2"></i>
                {{ __('articles.show_filters') }}
                <i class="ph ph-chevron-down ms-2"></i>
            </button>
        </div>
    </div>

    <!-- Mobile-First Content Layout -->
    <div class="row">
        <!-- Main Content - Mobile-First -->
        <div class="col-12 col-lg-8 order-1 order-lg-1">
            @if(!$showAllArticles)
                <!-- Editor-Controlled Layout -->
                @if(isset($layoutArticles) && !empty($layoutArticles))
                    <div class="mb-4">
                        <h4 class="mb-3 f-s-18 f-w-600">
                            <i class="ph ph-star me-2"></i>
                            {{ __('articles.editor_picks') }}
                        </h4>

                        <!-- Layout Articles - Editor Controlled -->
                        <!-- Banner Article -->
                        @if(isset($layoutArticles['banner']))
                            <div class="row g-3 mb-4">
                                <!-- Banner Article -->
                                <div class="col-12">
                                    @php $bannerArticle = $layoutArticles['banner'][0]['article']; @endphp
                                    <div class="card hover-effect">
                                        <div class="position-relative">
                                            @if($bannerArticle->featured_image_url)
                                                <img src="{{ $bannerArticle->featured_image_url }}"
                                                     class="card-img-top" style="height: 300px; object-fit: cover;"
                                                     alt="{{ $bannerArticle->title }}">
                                            @else
                                                {!! article_placeholder_html(0, 300, 'card-img-top', route('articles.show', $bannerArticle->slug)) !!}
                                            @endif
                                            <div class="position-absolute top-0 start-0 m-3">
                                                <span class="badge bg-primary">
                                                    <i class="ph ph-star me-1"></i>{{ __('articles.banner') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <h4 class="card-title f-s-20 f-w-600 mb-3">{{ $bannerArticle->title }}</h4>
                                            <p class="card-text f-s-16 text-muted mb-3">{{ Str::limit($bannerArticle->excerpt, 200) }}</p>

                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex gap-3 text-muted f-s-14">
                                                    <span><i class="ph ph-user me-1"></i>{{ $bannerArticle->user->name ?? 'N/A' }}</span>
                                                    <span><i class="ph ph-calendar me-1"></i>{{ $bannerArticle->published_at ? $bannerArticle->published_at->format('d/m/Y') : $bannerArticle->created_at->format('d/m/Y') }}</span>
                                                    <span><i class="ph ph-eye me-1"></i>{{ number_format($bannerArticle->views_count ?? 0) }}</span>
                                                </div>
                                                <a href="{{ route('articles.show', $bannerArticle->slug) }}" class="btn btn-primary">
                                                    {{ __('articles.read_more') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Featured Articles Grid -->
                        @if(isset($layoutArticles['featured']))
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <h5 class="mb-3 f-s-16 f-w-600">
                                        <i class="ph ph-star me-2"></i>{{ __('articles.featured_articles') }}
                                    </h5>
                                </div>
                                @foreach($layoutArticles['featured'] as $layoutItem)
                                    @php $article = $layoutItem['article']; @endphp
                                    <div class="col-md-6">
                                        <div class="card hover-effect h-100">
                                            @if($article->featured_image_url)
                                                <img src="{{ $article->featured_image_url }}" 
                                                     class="card-img-top" style="height: 200px; object-fit: cover;"
                                                     alt="{{ $article->title }}">
                                            @else
                                                {!! article_placeholder_html(0, 200, 'card-img-top', route('articles.show', $article->slug)) !!}
                                            @endif
                                            <div class="card-body d-flex flex-column">
                                                <h6 class="card-title f-s-16 f-w-600 mb-2">{{ $article->title }}</h6>
                                                <p class="card-text f-s-14 text-muted mb-3 flex-grow-1">{{ Str::limit($article->excerpt, 100) }}</p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted f-s-12">
                                                        <i class="ph ph-user me-1"></i>{{ $article->user->name ?? 'N/A' }}
                                                    </small>
                                                    <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-sm btn-primary">
                                                        {{ __('articles.read') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Latest Articles -->
                        @if(isset($layoutArticles['latest']))
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    <h5 class="mb-3 f-s-16 f-w-600">
                                        <i class="ph ph-clock me-2"></i>{{ __('articles.latest_articles') }}
                                    </h5>
                                </div>
                                @foreach($layoutArticles['latest'] as $layoutItem)
                                    @php $article = $layoutItem['article']; @endphp
                                    <div class="col-md-6 col-lg-4">
                                        <div class="card hover-effect h-100">
                                            @if($article->featured_image_url)
                                                <img src="{{ $article->featured_image_url }}" 
                                                     class="card-img-top" style="height: 150px; object-fit: cover;"
                                                     alt="{{ $article->title }}">
                                            @else
                                                {!! article_placeholder_html(0, 150, 'card-img-top', route('articles.show', $article->slug)) !!}
                                            @endif
                                            <div class="card-body d-flex flex-column">
                                                <h6 class="card-title f-s-14 f-w-600 mb-2">{{ $article->title }}</h6>
                                                <p class="card-text f-s-12 text-muted mb-3 flex-grow-1">{{ Str::limit($article->excerpt, 80) }}</p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <small class="text-muted f-s-11">
                                                        <i class="ph ph-calendar me-1"></i>{{ $article->published_at ? $article->published_at->format('d/m/Y') : $article->created_at->format('d/m/Y') }}
                                                    </small>
                                                    <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-sm btn-outline-primary">
                                                        {{ __('articles.read') }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <!-- Show All Articles Toggle -->
                        <div class="text-center mb-4">
                            <button wire:click="toggleShowAll" class="btn btn-primary">
                                <i class="ph ph-list me-2"></i>
                                {{ __('articles.show_all_articles') }}
                            </button>
                        </div>
                    </div>
                @else
                    <!-- No Layout Articles - Show All -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h4 class="mb-0">{{ __('articles.all_articles') }}</h4>
                            <button wire:click="toggleShowAll" class="btn btn-sm btn-outline-primary">
                                <i class="ph ph-layout me-2"></i>
                                {{ __('articles.editor_layout') }}
                            </button>
                        </div>
                        @if($this->articles->count() > 0)
                            <div class="row g-3">
                                @foreach($this->articles as $article)
                                    <div class="col-md-6 col-lg-4">
                                        <livewire:articles.article-card :article="$article" :key="'article-'.$article->id" />
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4">
                                {{ $this->articles->links() }}
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="ph ph-newspaper f-s-48 text-muted mb-3"></i>
                                <h5 class="text-muted">{{ __('articles.no_articles_found') }}</h5>
                                <p class="text-secondary">{{ __('articles.no_articles_description') }}</p>
                            </div>
                        @endif
                    </div>
                @endif
            @else
                <!-- Show All Articles Mode -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">{{ __('articles.all_articles') }}</h4>
                        <button wire:click="toggleShowAll" class="btn btn-sm btn-outline-primary">
                            <i class="ph ph-layout me-2"></i>
                            {{ __('articles.editor_layout') }}
                        </button>
                    </div>
                    @if($articles->count() > 0)
                        <div class="row g-3">
                            @foreach($articles as $article)
                                <div class="col-md-6 col-lg-4">
                                    <livewire:articles.article-card :article="$article" :key="'article-'.$article->id" />
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4">
                            {{ $articles->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="ph ph-newspaper f-s-48 text-muted mb-3"></i>
                            <h5 class="text-muted">{{ __('articles.no_articles_found') }}</h5>
                            <p class="text-secondary">{{ __('articles.no_articles_description') }}</p>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Sidebar - Mobile-First -->
        <div class="col-12 col-lg-4 order-2 order-lg-2">
            <div class="collapse d-lg-block" id="sidebarCollapse">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph ph-funnel me-2"></i>
                            {{ __('articles.filters') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Search -->
                        <div class="mb-3">
                            <label class="form-label">{{ __('articles.search') }}</label>
                            <input type="text" 
                                   class="form-control" 
                                   wire:model.live.debounce.300ms="search"
                                   placeholder="{{ __('articles.search_placeholder') }}">
                        </div>

                        <!-- Category Filter -->
                        <div class="mb-3">
                            <label class="form-label">{{ __('articles.category') }}</label>
                            <select class="form-select" wire:model.live="category">
                                <option value="">{{ __('articles.all_categories') }}</option>
                                @foreach($this->categories as $cat)
                                    <option value="{{ $cat->slug }}">{{ $cat->name }} ({{ $cat->articles_count }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Tag Filter -->
                        <div class="mb-3">
                            <label class="form-label">{{ __('articles.tags') }}</label>
                            <select class="form-select" wire:model.live="tag">
                                <option value="">{{ __('articles.all_tags') }}</option>
                                @foreach($this->tags as $tag)
                                    <option value="{{ $tag->slug }}">{{ $tag->name }} ({{ $tag->articles_count }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Sort -->
                        <div class="mb-3">
                            <label class="form-label">{{ __('articles.sort_by') }}</label>
                            <select class="form-select" wire:model.live="sort">
                                <option value="newest">{{ __('articles.newest') }}</option>
                                <option value="oldest">{{ __('articles.oldest') }}</option>
                                <option value="popular">{{ __('articles.most_popular') }}</option>
                                <option value="title">{{ __('articles.title') }}</option>
                            </select>
                        </div>

                        <!-- Clear Filters -->
                        <button wire:click="clearFilters" class="btn btn-outline-secondary w-100">
                            <i class="ph ph-arrow-counter-clockwise me-2"></i>
                            {{ __('articles.clear_filters') }}
                        </button>
                    </div>
                </div>

                <!-- Categories Widget -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0">{{ __('articles.categories') }}</h6>
                    </div>
                    <div class="card-body">
                        @foreach($this->categories->take(10) as $category)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <a href="{{ route('articles.browse.category', $category->slug) }}" 
                                   class="text-decoration-none">
                                    {{ $category->name }}
                                </a>
                                <span class="badge bg-light text-dark">{{ $category->articles_count }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Tags Widget -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0">{{ __('articles.popular_tags') }}</h6>
                    </div>
                    <div class="card-body">
                        @foreach($this->tags->take(15) as $tag)
                            <a href="{{ route('articles.browse.tag', $tag->slug) }}" 
                               class="badge bg-light text-dark text-decoration-none me-1 mb-1">
                                {{ $tag->name }} ({{ $tag->articles_count }})
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
