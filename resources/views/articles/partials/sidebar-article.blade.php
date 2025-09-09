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
                <small>{{ __('articles.by') }}
                    <a href="{{ route('user.show', $article->user) }}" class="text-decoration-none">
                        <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($article->user) }}"
                             class="rounded-circle me-1" style="width: 16px; height: 16px;"
                             alt="{{ $article->user->name }}">
                        {{ $article->user->name }}
                    </a>
                </small>
                <span class="mx-2">•</span>
                <small>{{ $article->published_at->format('d/m/Y') }}</small>
                <span class="mx-2">•</span>
                <small>{{ $article->views_count }} {{ __('articles.views') }}</small>
            </div>

            <!-- Azioni social compatte -->
            <div class="d-flex gap-1">
                <!-- Like Button (Sistema Unificato) -->
                <x-social-like-button :content="$article" type="article" />

                <!-- Commenti (Sistema Unificato) -->
                <x-social-comment-button :content="$article" type="article" />

                <!-- Report Button (Sistema Unificato) -->
                <x-report-button :content="$article" type="article" />
            </div>
        </div>
    </div>
</div>
