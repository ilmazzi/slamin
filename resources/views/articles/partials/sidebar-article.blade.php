<div class="border-bottom p-3">
    <div class="d-flex align-items-start">
        @if($article->featured_image)
            <img src="{{ Storage::url($article->featured_image) }}" 
                 class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;" 
                 alt="{{ $article->title }}">
        @endif
        <div class="flex-grow-1">
            <h6 class="mb-1">
                <a href="{{ route('articles.show', $article) }}" class="text-decoration-none">
                    {{ Str::limit($article->title, 50) }}
                </a>
            </h6>
            <div class="d-flex align-items-center text-muted mb-2">
                <small>{{ $article->published_at->format('d/m/Y') }}</small>
                <span class="mx-2">•</span>
                <small>{{ $article->views_count }} {{ __('articles.views') }}</small>
            </div>
            
            <!-- Azioni social compatte -->
            <div class="d-flex gap-1">
                <!-- Like -->
                <button class="btn btn-sm btn-outline-primary like-btn" 
                        data-article-id="{{ $article->id }}"
                        data-liked="{{ auth()->check() && $article->isLikedBy(auth()->user()) ? 'true' : 'false' }}"
                        title="{{ __('articles.like') }}">
                    <i class="ti ti-heart {{ auth()->check() && $article->isLikedBy(auth()->user()) ? 'text-danger' : '' }}" style="font-size: 12px;"></i>
                    <span class="likes-count" style="font-size: 11px;">{{ $article->likes_count }}</span>
                </button>

                <!-- Commenti -->
                <a href="{{ route('articles.show', $article) }}#comments" class="btn btn-sm btn-outline-secondary" 
                   title="{{ __('articles.comment') }}">
                    <i class="ti ti-message-circle" style="font-size: 12px;"></i>
                    <span style="font-size: 11px;">{{ $article->comments_count }}</span>
                </a>

                <!-- Segnala -->
                @if(auth()->check())
                    <button class="btn btn-sm btn-outline-warning report-btn" 
                            data-article-id="{{ $article->id }}"
                            data-reported="{{ auth()->check() && $article->isReportedByUser(auth()->user()) ? 'true' : 'false' }}"
                            title="{{ __('articles.report') }}">
                        <i class="ti ti-flag" style="font-size: 12px;"></i>
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
