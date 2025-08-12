@if($articles->count() > 0)
<div class="row">
    @foreach($articles as $article)
    <div class="col-md-6 mb-3">
        <div class="card hover-effect">
            <div class="position-relative">
                @if($article->featured_image)
                    <img src="{{ Storage::url($article->featured_image) }}"
                         alt="{{ $article->title }}" class="card-img-top"
                         style="height: 200px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center"
                         style="height: 200px;">
                        <div class="text-center">
                            <i class="ph ph-newspaper f-s-48 text-muted mb-2"></i>
                            <div class="f-s-16 f-w-600 text-muted">{{ __('articles.article') }}</div>
                        </div>
                    </div>
                @endif
                <!-- Status badge -->
                <div class="position-absolute top-0 start-0 m-2">
                    @if($article->featured)
                        <span class="badge bg-warning f-s-11">{{ __('articles.featured') }}</span>
                    @elseif($article->status === 'published')
                        <span class="badge bg-success f-s-11">{{ __('articles.published') }}</span>
                    @else
                        <span class="badge bg-secondary f-s-11">{{ __('articles.draft') }}</span>
                    @endif
                </div>
                <!-- Views badge -->
                <div class="position-absolute top-0 end-0 m-2">
                    <span class="badge bg-dark f-s-11">{{ $article->views_count ?? 0 }} {{ __('profile.views') }}</span>
                </div>
            </div>
            <div class="card-body pa-15">
                <h6 class="card-title f-w-600 f-s-14 mb-1">
                    <a href="{{ route('articles.show', $article) }}" class="text-decoration-none">
                        {{ $article->title }}
                    </a>
                </h6>
                @if($article->excerpt)
                <p class="text-muted f-s-12 mb-2">{{ Str::limit($article->excerpt, 80) }}</p>
                @endif
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted f-s-11">{{ $article->created_at->diffForHumans() }}</small>
                    <div class="d-flex gap-1">
                        <small class="text-muted f-s-11">
                            <i class="ph ph-heart me-1"></i>{{ $article->likes_count }}
                        </small>
                        <small class="text-muted f-s-11">
                            <i class="ph ph-chat-circle me-1"></i>{{ $article->comments_count }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

<!-- Paginazione -->
@if($articles->hasPages())
<div class="d-flex justify-center mt-4">
    <ul class="pagination app-pagination" id="articles-pagination">
        @if($articles->onFirstPage())
            <li class="page-item disabled">
                <span class="page-link b-r-left">Previous</span>
            </li>
        @else
            <li class="page-item">
                <a class="page-link b-r-left" href="javascript:void(0)" data-page="{{ $articles->currentPage() - 1 }}">Previous</a>
            </li>
        @endif

        @foreach($articles->getUrlRange(1, $articles->lastPage()) as $page => $url)
            <li class="page-item {{ $page == $articles->currentPage() ? 'active' : '' }}">
                <a class="page-link" href="javascript:void(0)" data-page="{{ $page }}">{{ $page }}</a>
            </li>
        @endforeach

        @if($articles->hasMorePages())
            <li class="page-item">
                <a class="page-link" href="javascript:void(0)" data-page="{{ $articles->currentPage() + 1 }}">Next</a>
            </li>
        @else
            <li class="page-item disabled">
                <span class="page-link">Next</span>
            </li>
        @endif
    </ul>
</div>
@endif
@else
<div class="text-center py-4">
    <div class="bg-light-info h-50 w-50 d-flex-center rounded-circle m-auto mb-2">
        <i class="ph ph-newspaper f-s-24 text-muted"></i>
    </div>
    <p class="text-muted f-s-14 mb-0">{{ __('profile.no_articles_written') }}</p>
    @if(auth()->check() && auth()->id() == $user->id)
    <a href="{{ route('articles.create') }}" class="btn btn-sm btn-primary mt-2">
        <i class="ph ph-plus me-1"></i>{{ __('articles.create_first_article') }}
    </a>
    @endif
</div>
@endif
