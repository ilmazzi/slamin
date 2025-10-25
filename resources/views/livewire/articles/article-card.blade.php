<div class="card hover-effect h-100">
    @if($article->featured_image_url)
        <img src="{{ $article->featured_image_url }}" 
             class="card-img-top" 
             style="height: 200px; object-fit: cover;"
             alt="{{ $article->title }}">
    @else
        {!! article_placeholder_html(0, 200, 'card-img-top', route('articles.show', $article->slug)) !!}
    @endif
    
    <div class="card-body d-flex flex-column">
        <!-- Category Badge -->
        @if($article->category)
            <div class="mb-2">
                <span class="badge bg-primary f-s-12">
                    {{ $article->category->name }}
                </span>
            </div>
        @endif

        <!-- Title -->
        <h6 class="card-title f-s-16 f-w-600 mb-2">
            <a href="{{ route('articles.show', $article->slug) }}" 
               class="text-decoration-none text-dark hover-text-primary">
                {{ $article->title }}
            </a>
        </h6>

        <!-- Excerpt -->
        <p class="card-text f-s-14 text-muted mb-3 flex-grow-1">
            {{ Str::limit($article->excerpt, 120) }}
        </p>

        <!-- Meta Info -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center gap-2">
                @if($article->user)
                    <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($article->user) }}" 
                         alt="{{ $article->user->name }}" 
                         class="rounded-circle" 
                         style="width: 24px; height: 24px; object-fit: cover;">
                    <small class="text-muted f-s-12">{{ $article->user->name }}</small>
                @endif
            </div>
            <small class="text-muted f-s-11">
                <i class="ph ph-calendar me-1"></i>
                {{ $article->published_at ? $article->published_at->format('d/m/Y') : $article->created_at->format('d/m/Y') }}
            </small>
        </div>

        <!-- Stats & Actions -->
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <livewire:social.social-view-counter :content="$article" type="article" size="xs" :key="'article-view-'.$article->id" />
                <livewire:social.social-like-button :content="$article" type="article" size="xs" :key="'article-like-'.$article->id" />
                <livewire:social.social-comment-button :content="$article" type="article" size="xs" :key="'article-comment-'.$article->id" />
            </div>
            <a href="{{ route('articles.show', $article->slug) }}" 
               class="btn btn-primary btn-sm">
                {{ __('articles.read') }}
            </a>
        </div>

        <!-- Tags -->
        @if($article->tags->count() > 0)
            <div class="mt-2">
                @foreach($article->tags->take(3) as $tag)
                    <span class="badge bg-secondary f-s-10 me-1">
                        {{ $tag->name }}
                    </span>
                @endforeach
                @if($article->tags->count() > 3)
                    <span class="badge bg-secondary f-s-10">
                        +{{ $article->tags->count() - 3 }}
                    </span>
                @endif
            </div>
        @endif
    </div>
</div>
