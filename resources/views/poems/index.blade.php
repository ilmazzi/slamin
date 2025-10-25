@extends('layout.master')

@php
use App\Helpers\PlaceholderHelper;
@endphp

@section('title', __('poems.title'))

@section('main-content')
<div class="page-wrapper">
    <div class="page-content">
        <!-- Breadcrumb start -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title">{{ __('poems.title') }}</h4>
                
            </div>
        </div>
        <!-- Breadcrumb end -->

        <!-- Filtri e Ricerca -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('poems.index') }}" method="GET" class="row g-3">
                            <div class="col-12 col-md-4">
                                <input type="text" name="search" class="form-control"
                                       placeholder="{{ __('poems.placeholders.search') }}"
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-6 col-md-2">
                                <select name="category" class="form-select">
                                    <option value="">{{ __('poems.filters.filter_by_category') }}</option>
                                    @foreach(config('poems.categories') as $key => $category)
                                        <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 col-md-2">
                                <select name="language" class="form-select">
                                    <option value="">{{ __('poems.filters.filter_by_language') }}</option>
                                    @foreach(config('poems.languages') as $key => $language)
                                        <option value="{{ $key }}" {{ request('language') == $key ? 'selected' : '' }}>
                                            {{ $language }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 col-md-2">
                                <select name="sort" class="form-select">
                                    @foreach(__('poems.filters.sort_options') as $key => $option)
                                        <option value="{{ $key }}" {{ request('sort', 'recent') == $key ? 'selected' : '' }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-6 col-md-2">
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
                <div class="d-flex flex-column flex-md-row justify-content-center align-items-center gap-2">
                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <a href="{{ route('poems.create') }}" class="btn btn-primary">
                            <i class="ph ph-pen-nib"></i>
                            {{ __('poems.actions.create') }}
                        </a>
                        <a href="{{ route('poems.my-poems') }}" class="btn btn-outline-primary">
                            <i class="ph-duotone ph-book-open me-2"></i>
                            {{ __('poems.my_poems.title') }}
                        </a>
                        <a href="{{ route('poems.drafts') }}" class="btn btn-outline-secondary">
                            <i class="ph-duotone ph-file-text me-2"></i>
                            {{ __('poems.filters.drafts') }}
                        </a>
                    </div>
                    <div class="d-flex flex-column flex-sm-row gap-2">
                        <a href="{{ route('poems.bookmarks') }}" class="btn btn-outline-warning">
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
            <div class="col-12 col-sm-6 col-lg-4 mb-4">
                <div class="card hover-effect h-100">
                    @if($poem->thumbnail_url)
                        <img src="{{ $poem->thumbnail_url }}" class="card-img-top" alt="{{ $poem->title }}" style="height: 200px; object-fit: cover;">
                    @else
                        {!! poem_placeholder_html(0, 200, 'card-img-top', route('poems.show', $poem->slug)) !!}
                    @endif
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="card-title f-w-600 mb-0 flex-grow-1">
                                @if($poem->id && $poem->slug)
                                    <a href="{{ route('poems.show', $poem->slug) }}" class="text-decoration-none">
                                        {{ $poem->title ?: __('poems.untitled') }}
                                    </a>
                                @else
                                    <span class="text-muted">{{ $poem->title ?: __('poems.untitled') }}</span>
                                @endif
                            </h5>
                            @if($poem->is_featured)
                            <span class="badge bg-warning ms-2">
                                <i class="ph-duotone ph-star me-1"></i>
                                {{ __('poems.filters.featured') }}
                            </span>
                            @endif
                        </div>

                        <p class="card-text text-muted f-s-14 mb-2">
                            <i class="ph-duotone ph-user f-s-12 me-1"></i>
                            <a href="{{ route('user.show', $poem->user) }}" class="text-decoration-none hover-effect">
                                {{ $poem->user->getDisplayName() }}
                            </a>
                        </p>

                        @if($poem->description)
                        <p class="card-text flex-grow-1">{{ Str::limit($poem->description, 100) }}</p>
                        @endif

                        <div class="mt-auto">
                            <!-- Social Buttons -->
                            @auth
                            <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
                                <livewire:social.social-view-counter :content="$poem" type="poem" size="xs" :key="'poem-view-'.$poem->id" />
                                <livewire:social.social-like-button :content="$poem" type="poem" size="xs" :key="'poem-like-'.$poem->id" />
                                <livewire:social.social-comment-button :content="$poem" type="poem" size="xs" :key="'poem-comment-'.$poem->id" />
                                <x-report-button :content="$poem" type="poem" size="sm" />
                            </div>
                            @endauth

                            <!-- Action Buttons -->
                            <div class="d-flex gap-2 justify-content-center align-items-center">
                                @if($poem->id && $poem->slug)
                                    <a href="{{ route('poems.show', $poem->slug) }}" class="btn btn-primary px-4 flex-fill">
                                        <i class="ph-bold ph-read-cv-logo me-2"></i>
                                        {{ __('poems.actions.read') }}
                                    </a>
                                @else
                                    <button class="btn btn-secondary px-4 flex-fill" disabled>
                                        <i class="ph-bold ph-read-cv-logo me-2"></i>
                                        {{ __('poems.actions.read') }}
                                    </button>
                                @endif
                            </div>
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
                            <i class="ph ph-pen-nib"></i>
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
