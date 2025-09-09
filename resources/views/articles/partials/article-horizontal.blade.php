<div class="card mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                @if($article->featured_image)
                    <img src="{{ Storage::url($article->featured_image) }}"
                         class="img-fluid rounded" style="height: 200px; object-fit: cover; width: 100%;"
                         alt="{{ $article->title }}">
                @endif
            </div>
            <div class="col-md-8">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        @if($article->category)
                            <span class="badge" style="background-color: {{ $article->category->color }}">
                                {{ $article->category->name }}
                            </span>
                        @endif
                        @if($article->featured)
                            <span class="badge bg-warning ms-1">{{ __('articles.featured') }}</span>
                        @endif
                    </div>
                    @if(isset($position) && auth()->check() && auth()->user()->hasPermissionTo('articles.manage_layout'))
                        <button class="btn btn-sm btn-outline-secondary" onclick="editLayoutPosition('{{ $position }}', {{ $article->id }})">
                            <i class="ti ti-edit"></i>
                        </button>
                    @endif
                </div>

                <h4 class="card-title">
                    <a href="{{ route('articles.show', $article) }}" class="text-decoration-none">
                        {{ $article->title }}
                    </a>
                </h4>

                @if($article->excerpt)
                    <p class="card-text text-muted">{{ Str::limit($article->excerpt, 200) }}</p>
                @endif

                <div class="d-flex align-items-center text-muted mb-3">
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
                    <small>{{ __('articles.read_time', ['minutes' => $article->read_time]) }}</small>
                </div>

                <!-- Tag -->
                @if($article->tags->count() > 0)
                    <div class="mb-3">
                        @foreach($article->tags->take(5) as $tag)
                            <span class="badge bg-light text-dark me-1">{{ $tag->name }}</span>
                        @endforeach
                        @if($article->tags->count() > 5)
                            <small class="text-muted">+{{ $article->tags->count() - 5 }}</small>
                        @endif
                    </div>
                @endif

                <!-- Statistiche -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center text-muted">
                        <i class="ti ti-eye me-1"></i>
                        <small>{{ $article->views_count }} {{ __('articles.views') }}</small>
                        <span class="mx-2">•</span>
                        <i class="ti ti-message-circle me-1"></i>
                        <small>{{ $article->comments_count }} {{ __('articles.comments') }}</small>
                        <span class="mx-2">•</span>
                        <i class="ti ti-heart me-1"></i>
                        <small>{{ $article->likes_count }} {{ __('articles.likes') }}</small>
                    </div>
                </div>

                <!-- Azioni social -->
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex gap-2">
                        <!-- Like Button (Sistema Unificato) -->
                        <x-social-like-button :content="$article" type="article" />

                        <!-- Commenti (Sistema Unificato) -->
                        <x-social-comment-button :content="$article" type="article" />

                        <!-- Report Button (Sistema Unificato) -->
                        <x-report-button :content="$article" type="article" />
                    </div>

                    <a href="{{ route('articles.show', $article) }}" class="btn btn-primary">
                        {{ __('articles.read_more') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
