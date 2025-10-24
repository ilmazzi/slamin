<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <a href="{{ route('articles.index') }}" class="text-decoration-none text-primary d-flex align-items-center">
                <i class="ph-duotone ph-newspaper f-s-16 me-2"></i>
                {{ __('home.articles_section.title') }}
            </a>
        </h5>
        <div class="d-flex align-items-center">
            <span class="text-dark f-s-12 me-2 f-w-600">{{ __('home.articles_section.new') }}</span>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" 
                       wire:click="toggleContent('{{ $contentType === 'new' ? 'popular' : 'new' }}')"
                       {{ $contentType === 'popular' ? 'checked' : '' }}>
            </div>
                <span class="text-dark f-s-12 ms-2 f-w-600">{{ __('home.articles_section.popular') }}</span>
        </div>
    </div>
    <div class="card-body">
        @if ($articles && $articles->count() > 0)
            <div class="row">
                @foreach ($articles as $article)
                    <div class="col-12 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-0">
                                <!-- Immagine dell'articolo o placeholder -->
                                <div class="position-relative">
                                    <a href="{{ route('articles.show', $article->slug) }}" class="text-decoration-none">
                                        @if ($article->featured_image_url)
                                            <img src="{{ $article->featured_image_url }}" class="w-100" alt="{{ $article->title }}" style="height: 120px; object-fit: cover; border-radius: 8px 8px 0 0;">
                                        @else
                                            {!! \App\Helpers\PlaceholderHelper::getArticlePlaceholderHtml(0, 120, 'w-100', route('articles.show', $article->slug)) !!}
                                        @endif
                                    </a>
                                </div>
                                
                                <!-- Contenuto -->
                                <div class="p-3">
                                    <div class="d-flex align-items-start">
                                        <div class="me-3">
                                        <img src="{{ $article->user->profile_photo_url }}" class="rounded-circle" alt="{{ $article->user->name }}" style="width: 40px; height: 40px; object-fit: cover;">
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="card-title f-s-14 f-w-600 mb-1">{{ Str::limit($article->title, 60) }}</h6>
                                            <p class="card-text text-muted f-s-12 mb-2">
                                                <i class="ph-duotone ph-user f-s-12 me-1"></i>
                                                {{ $article->user->name }}
                                            </p>
                                            <p class="card-text f-s-12 mb-2">{{ Str::limit($article->excerpt, 100) }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    <i class="ph-duotone ph-clock f-s-12 me-1"></i>
                                                    {{ $article->created_at->diffForHumans() }}
                                                </small>
                                                <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="ph-duotone ph-eye f-s-12 me-1"></i>
                                                    {{ __('home.articles_section.read') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-4">
                <i class="ph-duotone ph-newspaper f-s-48 text-muted mb-3"></i>
                <p class="text-muted">{{ __('home.articles_section.no_articles_available') }}</p>
            </div>
        @endif
    </div>
</div>
