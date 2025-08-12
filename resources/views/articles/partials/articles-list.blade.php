@if($articles->count() > 0)
    <div class="row">
        @foreach($articles as $article)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100">
                    @if($article->featured_image)
                        <img src="{{ Storage::url($article->featured_image) }}" 
                             class="card-img-top" style="height: 200px; object-fit: cover;" 
                             alt="{{ $article->title }}">
                    @endif
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            @if($article->category)
                                <span class="badge bg-primary">{{ $article->category->name }}</span>
                            @endif
                            @if($article->featured)
                                <span class="badge bg-warning">{{ __('articles.featured') }}</span>
                            @endif
                        </div>
                        
                        <h5 class="card-title">
                            <a href="{{ route('articles.show', $article) }}" class="text-decoration-none">
                                {{ Str::limit($article->title, 60) }}
                            </a>
                        </h5>
                        
                        @if($article->excerpt)
                            <p class="card-text text-muted">{{ Str::limit($article->excerpt, 100) }}</p>
                        @endif
                        
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <img src="{{ $article->user->profile->avatar_url ?? asset('assets/images/avatar/default.png') }}" 
                                     class="rounded-circle me-2" style="width: 25px; height: 25px;">
                                <small class="text-muted">{{ $article->user->name }}</small>
                            </div>
                            <small class="text-muted">
                                <i class="ti ti-calendar"></i> {{ $article->published_at->format('d/m/Y') }}
                            </small>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex gap-3 text-muted">
                                <small><i class="ti ti-eye"></i> {{ $article->views_count }}</small>
                                <small><i class="ti ti-heart"></i> {{ $article->likes_count }}</small>
                                <small><i class="ti ti-message-circle"></i> {{ $article->comments_count }}</small>
                            </div>
                            <a href="{{ route('articles.show', $article) }}" class="btn btn-sm btn-primary">
                                {{ __('articles.read_more') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
    @if($articles->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $articles->links() }}
        </div>
    @endif
@else
    <div class="text-center py-5">
        <i class="ti ti-article display-1 text-muted"></i>
        <h5 class="mt-3">{{ __('articles.no_articles_found') }}</h5>
        <p class="text-muted">{{ __('articles.no_articles_description') }}</p>
        @if(auth()->check())
            <a href="{{ route('articles.create') }}" class="btn btn-primary">
                <i class="ti ti-plus"></i> {{ __('articles.create_first_article') }}
            </a>
        @endif
    </div>
@endif
