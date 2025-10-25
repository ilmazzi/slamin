<div class="container-fluid">
    <div class="row">
        <!-- Main Content -->
        <div class="col-12 col-lg-8">
            <article class="card">
                <!-- Featured Image -->
                @if($article->featured_image_url)
                    <img src="{{ $article->featured_image_url }}" 
                         class="card-img-top" 
                         style="height: 400px; object-fit: cover;"
                         alt="{{ $article->title }}">
                @endif

                <div class="card-body">
                    <!-- Article Header -->
                    <div class="mb-4">
                        <!-- Category & Featured Badge -->
                        <div class="d-flex align-items-center gap-2 mb-3">
                            @if($article->category)
                                <span class="badge bg-primary">
                                    {{ $article->category->name }}
                                </span>
                            @endif
                            @if($article->featured)
                                <span class="badge bg-warning">
                                    <i class="ph ph-star me-1"></i>
                                    {{ __('articles.featured') }}
                                </span>
                            @endif
                        </div>

                        <!-- Title -->
                        <h1 class="h2 f-w-600 mb-3">{{ $article->title }}</h1>

                        <!-- Meta Info -->
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3 mb-4">
                            <div class="d-flex align-items-center gap-3">
                                @if($article->user)
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($article->user) }}" 
                                             alt="{{ $article->user->name }}" 
                                             class="rounded-circle" 
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                        <div>
                                            <div class="f-s-14 f-w-600">{{ $article->user->name }}</div>
                                            <small class="text-muted f-s-12">{{ __('articles.author') }}</small>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="d-flex align-items-center gap-3 text-muted f-s-14">
                                <span>
                                    <i class="ph ph-calendar me-1"></i>
                                    {{ $article->published_at ? $article->published_at->format('d/m/Y H:i') : $article->created_at->format('d/m/Y H:i') }}
                                </span>
                                <span>
                                    <i class="ph ph-eye me-1"></i>
                                    {{ number_format($article->views_count ?? 0) }}
                                </span>
                            </div>
                        </div>

                        <!-- Excerpt -->
                        @if($article->excerpt)
                            <div class="alert alert-info border-0 mb-4">
                                <p class="mb-0 f-s-16 f-w-500">{{ $article->excerpt }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Article Content -->
                    <div class="article-content">
                        {!! $article->content !!}
                    </div>

                    <!-- Tags -->
                    @if($article->tags->count() > 0)
                        <div class="mt-4 pt-4 border-top">
                            <h6 class="mb-3">{{ __('articles.tags') }}</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($article->tags as $tag)
                                    <a href="{{ route('articles.index', ['tag' => $tag->slug]) }}" 
                                       class="badge bg-primary text-decoration-none">
                                        {{ $tag->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Social Actions -->
                    <div class="mt-4 pt-4 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-3">
                                <!-- Like Button -->
                                @if(Auth::check())
                                    <livewire:social.social-like-button :content="$article" type="article" size="md" />
                                @else
                                    <button class="btn btn-primary btn-sm" disabled>
                                        <i class="ph ph-heart me-1"></i>
                                        {{ number_format($article->likes_count ?? 0) }}
                                    </button>
                                @endif

                                <!-- Comments Button -->
                                <button wire:click="toggleComments" 
                                        class="btn btn-primary btn-sm">
                                    <i class="ph ph-chat-circle me-1"></i>
                                    {{ number_format($article->comments_count ?? 0) }}
                                    {{ __('articles.comments') }}
                                </button>

                                <!-- Share Button -->
                                <button class="btn btn-primary btn-sm" 
                                        onclick="navigator.share ? navigator.share({title: '{{ $article->title }}', url: '{{ route('articles.show', $article->slug) }}'}) : navigator.clipboard.writeText('{{ route('articles.show', $article->slug) }}')">
                                    <i class="ph ph-share me-1"></i>
                                    {{ __('articles.share') }}
                                </button>
                            </div>

                            <!-- Author Actions -->
                            @auth
                                @if(Auth::id() === $article->user_id || Auth::user()->can('articles.moderate'))
                                    <div class="dropdown">
                                        <button class="btn btn-secondary btn-sm dropdown-toggle" 
                                                type="button" 
                                                data-bs-toggle="dropdown">
                                            <i class="ph ph-gear me-1"></i>
                                            {{ __('articles.actions') }}
                                        </button>
                                        <ul class="dropdown-menu">
                                            @if(Auth::id() === $article->user_id)
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('articles.edit', $article) }}">
                                                        <i class="ph ph-pencil me-2"></i>
                                                        {{ __('articles.edit') }}
                                                    </a>
                                                </li>
                                            @endif
                                            @if(Auth::user()->can('articles.moderate'))
                                                @if($article->featured)
                                                    <li>
                                                        <form action="{{ route('articles.unfeature', $article) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="ph ph-star-slash me-2"></i>
                                                                {{ __('articles.unfeature') }}
                                                            </button>
                                                        </form>
                                                    </li>
                                                @else
                                                    <li>
                                                        <form action="{{ route('articles.feature', $article) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item">
                                                                <i class="ph ph-star me-2"></i>
                                                                {{ __('articles.feature') }}
                                                            </button>
                                                        </form>
                                                    </li>
                                                @endif
                                            @endif
                                        </ul>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                </div>
            </article>

            <!-- Comments Section -->
            @if($showComments)
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph ph-chat-circle me-2"></i>
                            {{ __('articles.comments') }}
                            <span class="badge bg-primary ms-2">{{ number_format($article->comments_count ?? 0) }}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        @livewire('social.comment-section', ['contentId' => $article->id, 'contentType' => 'article'], key('comment-section-article-'.$article->id))
                    </div>
                </div>
            @endif

            <!-- Related Articles -->
            @if($relatedArticles->count() > 0)
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph ph-newspaper me-2"></i>
                            {{ __('articles.related_articles') }}
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach($relatedArticles as $relatedArticle)
                                <div class="col-md-6">
                                    <div class="d-flex gap-3">
                                        @if($relatedArticle->featured_image_url)
                                            <img src="{{ $relatedArticle->featured_image_url }}" 
                                                 class="rounded" 
                                                 style="width: 80px; height: 80px; object-fit: cover;"
                                                 alt="{{ $relatedArticle->title }}">
                                        @else
                                            {!! article_placeholder_html(0, 80, 'rounded', route('articles.show', $relatedArticle->slug), '80px', '80px') !!}
                                        @endif
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">
                                                <a href="{{ route('articles.show', $relatedArticle->slug) }}" 
                                                   class="text-decoration-none text-dark hover-text-primary">
                                                    {{ Str::limit($relatedArticle->title, 60) }}
                                                </a>
                                            </h6>
                                            <p class="text-muted f-s-12 mb-2">{{ Str::limit($relatedArticle->excerpt, 80) }}</p>
                                            <small class="text-muted f-s-11">
                                                <i class="ph ph-calendar me-1"></i>
                                                {{ $relatedArticle->published_at ? $relatedArticle->published_at->format('d/m/Y') : $relatedArticle->created_at->format('d/m/Y') }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-12 col-lg-4">
            <!-- Author Info -->
            @if($article->user)
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">{{ __('articles.about_author') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($article->user) }}" 
                                 alt="{{ $article->user->name }}" 
                                 class="rounded-circle" 
                                 style="width: 60px; height: 60px; object-fit: cover;">
                            <div>
                                <h6 class="mb-1">{{ $article->user->name }}</h6>
                                <small class="text-muted">{{ __('articles.author') }}</small>
                            </div>
                        </div>
                        @if($article->user->bio)
                            <p class="f-s-14 text-muted mb-3">{{ Str::limit($article->user->bio, 150) }}</p>
                        @endif
                        <a href="{{ route('user.show', $article->user) }}" 
                           class="btn btn-primary btn-sm">
                            {{ __('articles.view_profile') }}
                        </a>
                    </div>
                </div>
            @endif

            <!-- Article Stats -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">{{ __('articles.article_stats') }}</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="f-s-20 f-w-600 text-primary">{{ number_format($article->views_count ?? 0) }}</div>
                            <small class="text-muted">{{ __('articles.views') }}</small>
                        </div>
                        <div class="col-4">
                            <div class="f-s-20 f-w-600 text-danger">{{ number_format($article->likes_count ?? 0) }}</div>
                            <small class="text-muted">{{ __('articles.likes') }}</small>
                        </div>
                        <div class="col-4">
                            <div class="f-s-20 f-w-600 text-success">{{ number_format($article->comments_count ?? 0) }}</div>
                            <small class="text-muted">{{ __('articles.comments') }}</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Share Article -->
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">{{ __('articles.share_article') }}</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-primary btn-sm" 
                                onclick="navigator.share ? navigator.share({title: '{{ $article->title }}', url: '{{ route('articles.show', $article->slug) }}'}) : navigator.clipboard.writeText('{{ route('articles.show', $article->slug) }}')">
                            <i class="ph ph-share me-2"></i>
                            {{ __('articles.share') }}
                        </button>
                        <button class="btn btn-primary btn-sm" 
                                onclick="navigator.clipboard.writeText('{{ route('articles.show', $article->slug) }}')">
                            <i class="ph ph-copy me-2"></i>
                            {{ __('articles.copy_link') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
