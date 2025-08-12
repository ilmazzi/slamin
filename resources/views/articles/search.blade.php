@extends('layout.master')
@section('main-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">{{ __('articles.search_results') }}</h4>
                        <div class="d-flex gap-2">
                            <span class="text-muted">{{ __('articles.found') }} {{ $articles->total() }} {{ __('articles.articles') }}</span>
                            <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-arrow-left"></i> {{ __('articles.back_to_articles') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Form di ricerca -->
                    <form action="{{ route('articles.search') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="q" value="{{ $query }}" 
                                           placeholder="{{ __('articles.search_placeholder') }}">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="ti ti-search"></i> {{ __('articles.search') }}
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" name="sort">
                                    <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>
                                        {{ __('articles.sort_recent') }}
                                    </option>
                                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>
                                        {{ __('articles.sort_popular') }}
                                    </option>
                                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>
                                        {{ __('articles.sort_oldest') }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-outline-primary w-100">
                                    <i class="ti ti-filter"></i> {{ __('articles.apply_filters') }}
                                </button>
                            </div>
                        </div>
                    </form>

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
                                {{ $articles->appends(request()->query())->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="ti ti-search display-1 text-muted"></i>
                            <h5 class="mt-3">{{ __('articles.no_results_found') }}</h5>
                            <p class="text-muted">{{ __('articles.no_results_description') }}</p>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('articles.index') }}" class="btn btn-primary">
                                    <i class="ti ti-arrow-left"></i> {{ __('articles.browse_all_articles') }}
                                </a>
                                @if(auth()->check())
                                    <a href="{{ route('articles.create') }}" class="btn btn-outline-primary">
                                        <i class="ti ti-plus"></i> {{ __('articles.create_article') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Auto-submit form quando cambia l'ordinamento
document.querySelector('select[name="sort"]').addEventListener('change', function() {
    this.form.submit();
});

// Evidenzia i termini di ricerca nei risultati
document.addEventListener('DOMContentLoaded', function() {
    const searchTerm = '{{ $query }}';
    if (searchTerm) {
        const titles = document.querySelectorAll('.card-title a');
        const excerpts = document.querySelectorAll('.card-text');
        
        titles.forEach(title => {
            title.innerHTML = title.innerHTML.replace(
                new RegExp(searchTerm, 'gi'),
                match => `<mark>${match}</mark>`
            );
        });
        
        excerpts.forEach(excerpt => {
            excerpt.innerHTML = excerpt.innerHTML.replace(
                new RegExp(searchTerm, 'gi'),
                match => `<mark>${match}</mark>`
            );
        });
    }
});
</script>
@endpush
