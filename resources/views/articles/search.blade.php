@extends('layout.master')
@section('main-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
                        <h4 class="mb-0 f-s-18 f-w-600">{{ __('articles.search_results') }}</h4>
                        <div class="d-flex flex-column flex-sm-row gap-2 align-items-start align-items-sm-center">
                            <span class="text-muted f-s-14">{{ __('articles.found') }} {{ $articles->total() }} {{ __('articles.articles') }}</span>
                            <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="ti ti-arrow-left me-1"></i> {{ __('articles.back_to_articles') }}
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Mobile-First Search Form -->
                    <form action="{{ route('articles.search') }}" method="GET" class="mb-4">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="q" value="{{ $query }}"
                                           placeholder="{{ __('articles.search_placeholder') }}">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="ti ti-search me-1"></i> {{ __('articles.search') }}
                                    </button>
                                </div>
                            </div>
                            <div class="col-12 col-md-3">
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
                            <div class="col-12 col-md-3">
                                <button type="submit" class="btn btn-outline-primary w-100">
                                    <i class="ti ti-filter me-1"></i> {{ __('articles.apply_filters') }}
                                </button>
                            </div>
                        </div>
                    </form>

                    @if($articles->count() > 0)
                        <!-- Mobile-First Articles Grid -->
                        <div class="row g-3">
                            @foreach($articles as $article)
                                <div class="col-12 col-sm-6 col-lg-4">
                                    @include('articles.partials.article-card', ['article' => $article])
                                </div>
                            @endforeach
                        </div>

                        <!-- Mobile-First Pagination -->
                        @if($articles->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $articles->appends(request()->query())->links() }}
                            </div>
                        @endif
                    @else
                        <!-- Mobile-First No Results -->
                        <div class="text-center py-5">
                            <i class="ph ph-magnifying-glass text-muted f-s-48"></i>
                            <h4 class="mt-3 text-muted f-s-18">{{ __('articles.no_search_results') }}</h4>
                            <p class="text-muted f-s-14 mb-4">{{ __('articles.try_different_keywords') }}</p>
                            <a href="{{ route('articles.index') }}" class="btn btn-primary">
                                <i class="ti ti-arrow-left me-2"></i>{{ __('articles.browse_all_articles') }}
                            </a>
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
document.addEventListener('DOMContentLoaded', function() {
    // Mobile-First Search Enhancements
    const searchForm = document.querySelector('form');
    const searchInput = searchForm.querySelector('input[name="q"]');

    // Auto-submit on Enter key
    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchForm.submit();
        }
    });

    // Mobile-friendly search suggestions (if needed)
    if (window.innerWidth < 768) {
        searchInput.setAttribute('autocomplete', 'off');
        searchInput.setAttribute('autocorrect', 'off');
        searchInput.setAttribute('autocapitalize', 'off');
    }
});
</script>
@endpush
