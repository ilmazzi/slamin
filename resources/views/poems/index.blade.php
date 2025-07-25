@extends('layout.app')

@section('title', __('poems.title'))

@section('content')
<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb -->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">{{ __('poems.title') }}</div>
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="ph-duotone ph-house"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('poems.title') }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <!-- Filtri e Ricerca -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('poems.index') }}" method="GET" class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" 
                                       placeholder="{{ __('poems.placeholders.search') }}" 
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <select name="category" class="form-select">
                                    <option value="">{{ __('poems.filters.filter_by_category') }}</option>
                                    @foreach(config('poems.categories') as $key => $category)
                                        <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="language" class="form-select">
                                    <option value="">{{ __('poems.filters.filter_by_language') }}</option>
                                    @foreach(config('poems.languages') as $key => $language)
                                        <option value="{{ $key }}" {{ request('language') == $key ? 'selected' : '' }}>
                                            {{ $language }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select name="sort" class="form-select">
                                    @foreach(__('poems.filters.sort_options') as $key => $option)
                                        <option value="{{ $key }}" {{ request('sort', 'recent') == $key ? 'selected' : '' }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ph-duotone ph-magnifying-glass me-2"></i>
                                    {{ __('poems.actions.search') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Azioni -->
        @auth
        <div class="row mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <a href="{{ route('poems.create') }}" class="btn btn-primary">
                            <i class="ph-duotone ph-plus me-2"></i>
                            {{ __('poems.actions.create') }}
                        </a>
                        <a href="{{ route('poems.my-poems') }}" class="btn btn-outline-primary ms-2">
                            <i class="ph-duotone ph-book-open me-2"></i>
                            {{ __('poems.my_poems') }}
                        </a>
                        <a href="{{ route('poems.drafts') }}" class="btn btn-outline-secondary ms-2">
                            <i class="ph-duotone ph-file-text me-2"></i>
                            {{ __('poems.filters.drafts') }}
                        </a>
                    </div>
                    <div>
                        <a href="{{ route('poems.bookmarks') }}" class="btn btn-outline-warning me-2">
                            <i class="ph-duotone ph-bookmark me-2"></i>
                            {{ __('poems.filters.bookmarks') }}
                        </a>
                        <a href="{{ route('poems.liked') }}" class="btn btn-outline-danger">
                            <i class="ph-duotone ph-heart me-2"></i>
                            {{ __('poems.filters.liked') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endauth

        <!-- Lista Poesie -->
        <div class="row">
            @forelse($poems as $poem)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card hover-effect">
                    @if($poem->thumbnail_path)
                    <img src="{{ $poem->thumbnail_url }}" class="card-img-top" alt="{{ $poem->title }}" style="height: 200px; object-fit: cover;">
                    @endif
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title f-w-600 mb-0">
                                <a href="{{ route('poems.show', $poem) }}" class="text-decoration-none">
                                    {{ $poem->title }}
                                </a>
                            </h5>
                            @if($poem->is_featured)
                            <span class="badge bg-warning">
                                <i class="ph-duotone ph-star me-1"></i>
                                {{ __('poems.filters.featured') }}
                            </span>
                            @endif
                        </div>
                        
                        <p class="card-text text-muted f-s-14 mb-2">
                            <i class="ph-duotone ph-user f-s-12 me-1"></i>
                            {{ $poem->user->name }}
                        </p>
                        
                        @if($poem->description)
                        <p class="card-text">{{ Str::limit($poem->description, 100) }}</p>
                        @endif
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex gap-2">
                                <span class="badge bg-light text-dark">
                                    <i class="ph-duotone ph-tag f-s-12 me-1"></i>
                                    {{ config('poems.categories.' . $poem->category, $poem->category) }}
                                </span>
                                <span class="badge bg-light text-dark">
                                    <i class="ph-duotone ph-book f-s-12 me-1"></i>
                                    {{ config('poems.poem_types.' . $poem->poem_type, $poem->poem_type) }}
                                </span>
                            </div>
                            <small class="text-muted">
                                <i class="ph-duotone ph-calendar f-s-12 me-1"></i>
                                {{ $poem->published_at->diffForHumans() }}
                            </small>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex gap-3">
                                <span class="text-muted f-s-14">
                                    <i class="ph-duotone ph-eye f-s-12 me-1"></i>
                                    {{ $poem->view_count }}
                                </span>
                                <span class="text-muted f-s-14">
                                    <i class="ph-duotone ph-heart f-s-12 me-1"></i>
                                    {{ $poem->like_count }}
                                </span>
                                <span class="text-muted f-s-14">
                                    <i class="ph-duotone ph-chat-circle f-s-12 me-1"></i>
                                    {{ $poem->comment_count }}
                                </span>
                            </div>
                            <a href="{{ route('poems.show', $poem) }}" class="btn btn-sm btn-primary">
                                <i class="ph-duotone ph-arrow-right f-s-14 me-1"></i>
                                {{ __('poems.actions.read') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="ph-duotone ph-book-open f-s-48 text-muted mb-3"></i>
                        <h5 class="text-muted">{{ __('poems.no_poems_found') }}</h5>
                        <p class="text-muted">{{ __('poems.no_poems_description') }}</p>
                        @auth
                        <a href="{{ route('poems.create') }}" class="btn btn-primary">
                            <i class="ph-duotone ph-plus me-2"></i>
                            {{ __('poems.actions.create') }}
                        </a>
                        @endauth
                    </div>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Paginazione -->
        @if($poems->hasPages())
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $poems->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('css')
<style>
.card.hover-effect:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}
</style>
@endsection 