@extends('layout.master')

@php
use App\Helpers\PlaceholderHelper;
@endphp

@section('main-content')
<div class="container-fluid">
    <!-- Mobile-First Header -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
                <h1 class="h3 mb-0">{{ __('articles.articles') }}</h1>
                @auth
                @if(auth()->user()->can('articles.create'))
                    <a href="{{ route('articles.create') }}" class="btn btn-primary">
                        <i class="ph-duotone ph-plus me-2"></i>
                        {{ __('articles.create_article') }}
                    </a>
                @endif
                @endauth
            </div>
        </div>
    </div>

    <!-- Mobile-First Sidebar Toggle Button -->
    <div class="row mb-3 d-lg-none">
        <div class="col-12">
            <button class="btn btn-outline-primary btn-sm w-100" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarCollapse" aria-expanded="false" aria-controls="sidebarCollapse">
                <i class="ph ph-funnel me-2"></i>
                {{ __('articles.show_filters') }}
                <i class="ph ph-chevron-down ms-2"></i>
            </button>
        </div>
    </div>

    <!-- Mobile-First Content Layout -->
    <div class="row">
        <!-- Main Content - Mobile-First -->
        <div class="col-12 col-lg-8 order-1 order-lg-1">
            @if(!$showAllArticles)
                <!-- Editor-Controlled Layout -->
                @if(isset($layoutArticles) && count($layoutArticles) > 0)
                    <div class="mb-4">
                        <h4 class="mb-3 f-s-18 f-w-600">
                            <i class="ph ph-star me-2"></i>
                            {{ __('articles.editor_picks') }}
                        </h4>

                        <!-- Layout Articles - Editor Controlled -->

                        <!-- Featured Article 1 (Horizontal) -->
                        @if(isset($layoutArticles['horizontal1']))
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    @php $article = $layoutArticles['horizontal1']; @endphp
                                    <div class="card hover-effect">
                                        <div class="position-relative">
                                            @if($article->featured_image_url)
                                                <img src="{{ $article->featured_image_url }}"
                                                     class="card-img-top" style="height: 250px; object-fit: cover;"
                                                     alt="{{ $article->title }}">
                                            @else
                                                {!! PlaceholderHelper::getArticlePlaceholderHtml(0, 250, 'card-img-top', route('articles.show', $article->slug)) !!}
                                            @endif
                                            <div class="position-absolute top-0 start-0 m-3">
                                                <span class="badge bg-warning">
                                                    <i class="ph ph-star me-1"></i>{{ __('articles.featured') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title f-s-18 f-w-600 mb-3">{{ $article->title }}</h5>
                                            <p class="card-text f-s-14 text-muted mb-3">{{ Str::limit($article->excerpt, 150) }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex gap-3 text-muted f-s-12">
                                                    <span><i class="ph ph-user me-1"></i>{{ $article->user->name ?? 'N/A' }}</span>
                                                    <span><i class="ph ph-calendar me-1"></i>{{ $article->published_at->format('d/m/Y') }}</span>
                                                    <x-social-view-counter :content="$article" type="article" size="sm" />
                                                    <x-social-like-button :content="$article" type="article" size="sm" />
                                                    <x-social-comment-button :content="$article" type="article" size="sm" />
                                                </div>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-primary">
                                                        {{ __('articles.read_more') }}
                                                    </a>
                                                    @auth
                                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                                                onclick="deleteArticle({{ $article->id }}, '{{ addslashes($article->title) }}')"
                                                                title="{{ __('articles.delete') }}">
                                                            <i class="ph ph-trash f-s-12"></i>
                                                        </button>
                                                    @endauth
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Column Articles 1 & 2 -->
                        @if(isset($layoutArticles['column1']) || isset($layoutArticles['column2']))
                            <div class="row g-3 mb-4">
                                @if(isset($layoutArticles['column1']))
                                    <div class="col-12 col-sm-6">
                                        @php $article = $layoutArticles['column1']; @endphp
                                        <div class="card hover-effect h-100">
                                            <div class="position-relative">
                                                @if($article->featured_image_url)
                                                    <img src="{{ $article->featured_image_url }}"
                                                         class="card-img-top" style="height: 140px; object-fit: cover;"
                                                         alt="{{ $article->title }}">
                                                @else
                                                    {!! PlaceholderHelper::getArticlePlaceholderHtml(0, 200, 'card-img-top', route('articles.show', $article->slug)) !!}
                                                @endif
                                                @if($article->category)
                                                    <div class="position-absolute top-0 start-0 m-2">
                                                        <span class="badge bg-primary">{{ $article->category->name }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h6 class="card-title f-s-15 f-w-600 mb-2">{{ Str::limit($article->title, 50) }}</h6>
                                                <p class="card-text f-s-13 text-muted mb-3">{{ Str::limit($article->excerpt, 70) }}</p>
                                                <div class="mt-auto">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="d-flex gap-2 text-muted f-s-11">
                                                            <x-social-view-counter :content="$article" type="article" size="sm" />
                                                            <x-social-like-button :content="$article" type="article" size="sm" />
                                                            <x-social-comment-button :content="$article" type="article" size="sm" />
                                                        </div>
                                                        <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-outline-primary btn-sm">
                                                            {{ __('articles.read_more') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if(isset($layoutArticles['column2']))
                                    <div class="col-12 col-sm-6">
                                        @php $article = $layoutArticles['column2']; @endphp
                                        <div class="card hover-effect h-100">
                                            <div class="position-relative">
                                                @if($article->featured_image_url)
                                                    <img src="{{ $article->featured_image_url }}"
                                                         class="card-img-top" style="height: 140px; object-fit: cover;"
                                                         alt="{{ $article->title }}">
                                                @else
                                                    {!! PlaceholderHelper::getArticlePlaceholderHtml(0, 200, 'card-img-top', route('articles.show', $article->slug)) !!}
                                                @endif
                                                @if($article->category)
                                                    <div class="position-absolute top-0 start-0 m-2">
                                                        <span class="badge bg-primary">{{ $article->category->name }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h6 class="card-title f-s-15 f-w-600 mb-2">{{ Str::limit($article->title, 50) }}</h6>
                                                <p class="card-text f-s-13 text-muted mb-3">{{ Str::limit($article->excerpt, 70) }}</p>
                                                <div class="mt-auto">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="d-flex gap-2 text-muted f-s-11">
                                                            <x-social-view-counter :content="$article" type="article" size="sm" />
                                                            <x-social-like-button :content="$article" type="article" size="sm" />
                                                            <x-social-comment-button :content="$article" type="article" size="sm" />
                                                        </div>
                                                        <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-outline-primary btn-sm">
                                                            {{ __('articles.read_more') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Featured Article 2 (Horizontal) -->
                        @if(isset($layoutArticles['horizontal2']))
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    @php $article = $layoutArticles['horizontal2']; @endphp
                                    <div class="card hover-effect">
                                        <div class="position-relative">
                                            @if($article->featured_image_url)
                                                <img src="{{ $article->featured_image_url }}"
                                                     class="card-img-top" style="height: 250px; object-fit: cover;"
                                                     alt="{{ $article->title }}">
                                            @else
                                                {!! PlaceholderHelper::getArticlePlaceholderHtml(0, 200, 'card-img-top', route('articles.show', $article->slug)) !!}
                                            @endif
                                            <div class="position-absolute top-0 start-0 m-3">
                                                <span class="badge bg-warning">
                                                    <i class="ph ph-star me-1"></i>{{ __('articles.featured') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title f-s-18 f-w-600 mb-3">{{ $article->title }}</h5>
                                            <p class="card-text f-s-14 text-muted mb-3">{{ Str::limit($article->excerpt, 150) }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex gap-3 text-muted f-s-12">
                                                    <span><i class="ph ph-user me-1"></i>{{ $article->user->name ?? 'N/A' }}</span>
                                                    <span><i class="ph ph-calendar me-1"></i>{{ $article->published_at->format('d/m/Y') }}</span>
                                                    <x-social-view-counter :content="$article" type="article" size="sm" />
                                                    <x-social-like-button :content="$article" type="article" size="sm" />
                                                    <x-social-comment-button :content="$article" type="article" size="sm" />
                                                </div>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-primary">
                                                        {{ __('articles.read_more') }}
                                                    </a>
                                                    @auth
                                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                                                onclick="deleteArticle({{ $article->id }}, '{{ addslashes($article->title) }}')"
                                                                title="{{ __('articles.delete') }}">
                                                            <i class="ph ph-trash f-s-12"></i>
                                                        </button>
                                                    @endauth
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Column Articles 3 & 4 -->
                        @if(isset($layoutArticles['column3']) || isset($layoutArticles['column4']))
                            <div class="row g-3 mb-4">
                                @if(isset($layoutArticles['column3']))
                                    <div class="col-12 col-sm-6">
                                        @php $article = $layoutArticles['column3']; @endphp
                                        <div class="card hover-effect h-100">
                                            <div class="position-relative">
                                                @if($article->featured_image_url)
                                                    <img src="{{ $article->featured_image_url }}"
                                                         class="card-img-top" style="height: 140px; object-fit: cover;"
                                                         alt="{{ $article->title }}">
                                                @else
                                                    {!! PlaceholderHelper::getArticlePlaceholderHtml(0, 200, 'card-img-top', route('articles.show', $article->slug)) !!}
                                                @endif
                                                @if($article->category)
                                                    <div class="position-absolute top-0 start-0 m-2">
                                                        <span class="badge bg-primary">{{ $article->category->name }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h6 class="card-title f-s-15 f-w-600 mb-2">{{ Str::limit($article->title, 50) }}</h6>
                                                <p class="card-text f-s-13 text-muted mb-3">{{ Str::limit($article->excerpt, 70) }}</p>
                                                <div class="mt-auto">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="d-flex gap-2 text-muted f-s-11">
                                                            <x-social-view-counter :content="$article" type="article" size="sm" />
                                                            <x-social-like-button :content="$article" type="article" size="sm" />
                                                            <x-social-comment-button :content="$article" type="article" size="sm" />
                                                        </div>
                                                        <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-outline-primary btn-sm">
                                                            {{ __('articles.read_more') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if(isset($layoutArticles['column4']))
                                    <div class="col-12 col-sm-6">
                                        @php $article = $layoutArticles['column4']; @endphp
                                        <div class="card hover-effect h-100">
                                            <div class="position-relative">
                                                @if($article->featured_image_url)
                                                    <img src="{{ $article->featured_image_url }}"
                                                         class="card-img-top" style="height: 140px; object-fit: cover;"
                                                         alt="{{ $article->title }}">
                                                @else
                                                    {!! PlaceholderHelper::getArticlePlaceholderHtml(0, 200, 'card-img-top', route('articles.show', $article->slug)) !!}
                                                @endif
                                                @if($article->category)
                                                    <div class="position-absolute top-0 start-0 m-2">
                                                        <span class="badge bg-primary">{{ $article->category->name }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h6 class="card-title f-s-15 f-w-600 mb-2">{{ Str::limit($article->title, 50) }}</h6>
                                                <p class="card-text f-s-13 text-muted mb-3">{{ Str::limit($article->excerpt, 70) }}</p>
                                                <div class="mt-auto">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="d-flex gap-2 text-muted f-s-11">
                                                            <x-social-view-counter :content="$article" type="article" size="sm" />
                                                            <x-social-like-button :content="$article" type="article" size="sm" />
                                                            <x-social-comment-button :content="$article" type="article" size="sm" />
                                                        </div>
                                                        <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-outline-primary btn-sm">
                                                            {{ __('articles.read_more') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Featured Article 3 (Horizontal) -->
                        @if(isset($layoutArticles['horizontal3']))
                            <div class="row g-3 mb-4">
                                <div class="col-12">
                                    @php $article = $layoutArticles['horizontal3']; @endphp
                                    <div class="card hover-effect">
                                        <div class="position-relative">
                                            @if($article->featured_image_url)
                                                <img src="{{ $article->featured_image_url }}"
                                                     class="card-img-top" style="height: 250px; object-fit: cover;"
                                                     alt="{{ $article->title }}">
                                            @else
                                                {!! PlaceholderHelper::getArticlePlaceholderHtml(0, 200, 'card-img-top', route('articles.show', $article->slug)) !!}
                                            @endif
                                            <div class="position-absolute top-0 start-0 m-3">
                                                <span class="badge bg-warning">
                                                    <i class="ph ph-star me-1"></i>{{ __('articles.featured') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title f-s-18 f-w-600 mb-3">{{ $article->title }}</h5>
                                            <p class="card-text f-s-14 text-muted mb-3">{{ Str::limit($article->excerpt, 150) }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex gap-3 text-muted f-s-12">
                                                    <span><i class="ph ph-user me-1"></i>{{ $article->user->name ?? 'N/A' }}</span>
                                                    <span><i class="ph ph-calendar me-1"></i>{{ $article->published_at->format('d/m/Y') }}</span>
                                                    <x-social-view-counter :content="$article" type="article" size="sm" />
                                                    <x-social-like-button :content="$article" type="article" size="sm" />
                                                    <x-social-comment-button :content="$article" type="article" size="sm" />
                                                </div>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-primary">
                                                        {{ __('articles.read_more') }}
                                                    </a>
                                                    @auth
                                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                                                onclick="deleteArticle({{ $article->id }}, '{{ addslashes($article->title) }}')"
                                                                title="{{ __('articles.delete') }}">
                                                            <i class="ph ph-trash f-s-12"></i>
                                                        </button>
                                                    @endauth
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Column Articles 5 & 6 -->
                        @if(isset($layoutArticles['column5']) || isset($layoutArticles['column6']))
                            <div class="row g-3 mb-4">
                                @if(isset($layoutArticles['column5']))
                                    <div class="col-12 col-sm-6">
                                        @php $article = $layoutArticles['column5']; @endphp
                                        <div class="card hover-effect h-100">
                                            <div class="position-relative">
                                                @if($article->featured_image_url)
                                                    <img src="{{ $article->featured_image_url }}"
                                                         class="card-img-top" style="height: 140px; object-fit: cover;"
                                                         alt="{{ $article->title }}">
                                                @else
                                                    {!! PlaceholderHelper::getArticlePlaceholderHtml(0, 200, 'card-img-top', route('articles.show', $article->slug)) !!}
                                                @endif
                                                @if($article->category)
                                                    <div class="position-absolute top-0 start-0 m-2">
                                                        <span class="badge bg-primary">{{ $article->category->name }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h6 class="card-title f-s-15 f-w-600 mb-2">{{ Str::limit($article->title, 50) }}</h6>
                                                <p class="card-text f-s-13 text-muted mb-3">{{ Str::limit($article->excerpt, 70) }}</p>
                                                <div class="mt-auto">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="d-flex gap-2 text-muted f-s-11">
                                                            <x-social-view-counter :content="$article" type="article" size="sm" />
                                                            <x-social-like-button :content="$article" type="article" size="sm" />
                                                            <x-social-comment-button :content="$article" type="article" size="sm" />
                                                        </div>
                                                        <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-outline-primary btn-sm">
                                                            {{ __('articles.read_more') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                @if(isset($layoutArticles['column6']))
                                    <div class="col-12 col-sm-6">
                                        @php $article = $layoutArticles['column6']; @endphp
                                        <div class="card hover-effect h-100">
                                            <div class="position-relative">
                                                @if($article->featured_image_url)
                                                    <img src="{{ $article->featured_image_url }}"
                                                         class="card-img-top" style="height: 140px; object-fit: cover;"
                                                         alt="{{ $article->title }}">
                                                @else
                                                    {!! PlaceholderHelper::getArticlePlaceholderHtml(0, 200, 'card-img-top', route('articles.show', $article->slug)) !!}
                                                @endif
                                                @if($article->category)
                                                    <div class="position-absolute top-0 start-0 m-2">
                                                        <span class="badge bg-primary">{{ $article->category->name }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h6 class="card-title f-s-15 f-w-600 mb-2">{{ Str::limit($article->title, 50) }}</h6>
                                                <p class="card-text f-s-13 text-muted mb-3">{{ Str::limit($article->excerpt, 70) }}</p>
                                                <div class="mt-auto">
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="d-flex gap-2 text-muted f-s-11">
                                                            <x-social-view-counter :content="$article" type="article" size="sm" />
                                                            <x-social-like-button :content="$article" type="article" size="sm" />
                                                            <x-social-comment-button :content="$article" type="article" size="sm" />
                                                        </div>
                                                        <a href="{{ route('articles.show', $article->slug) }}" class="btn btn-outline-primary btn-sm">
                                                            {{ __('articles.read_more') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @endif
            @else
                <!-- Mobile-First All Articles Layout -->
                <div class="mb-4">
                    <h4 class="mb-3 f-s-18 f-w-600">
                        @if(request('search'))
                            {{ __('articles.search_results_for') }}: "{{ request('search') }}"
                        @elseif(request('category'))
                            {{ __('articles.articles_in_category') }}: {{ $categories->firstWhere('slug', request('category'))->name ?? '' }}
                        @elseif(request('tag'))
                            {{ __('articles.articles_with_tag') }}: {{ $tags->firstWhere('slug', request('tag'))->name ?? '' }}
                        @endif
                        <span class="text-muted f-s-14">({{ $recentArticles->total() }} {{ __('articles.articles_found') }})</span>
                    </h4>
                </div>

                <!-- Mobile-First Articles Grid -->
                <div class="row g-3">
                    @forelse($recentArticles as $article)
                        <div class="col-12 col-sm-6 col-lg-4">
                            @include('articles.partials.article-card', ['article' => $article])
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <i class="ph ph-newspaper text-muted f-s-48"></i>
                                <h4 class="mt-3 text-muted f-s-18">{{ __('articles.no_articles_found') }}</h4>
                                <p class="text-muted f-s-14">{{ __('articles.try_different_filters') }}</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Mobile-First Pagination -->
                @if($recentArticles->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $recentArticles->appends(request()->query())->links() }}
                    </div>
                @endif
            @endif
        </div>

        <!-- Mobile-First Sidebar -->
        <div class="col-12 col-lg-4 order-2 order-lg-2 collapse d-lg-block" id="sidebarCollapse">
            <!-- Mobile-First Search Widget -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0 f-s-16 f-w-600">{{ __('articles.search_articles') }}</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('articles.index') }}" method="GET" id="searchForm">
                        <div class="mb-3">
                            <input type="text" name="search" class="form-control"
                                   placeholder="{{ __('articles.search_placeholder') }}"
                                   value="{{ request('search') }}">
                        </div>

                        @if($categories->count() > 0)
                            <div class="mb-3">
                                <select name="category" class="form-select">
                                    <option value="">{{ __('articles.filter_by_category') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <div class="mb-3">
                            <select name="sort" class="form-select">
                                <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>
                                    {{ __('articles.sort_newest') }}
                                </option>
                                <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>
                                    {{ __('articles.sort_popular') }}
                                </option>
                                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>
                                    {{ __('articles.sort_oldest') }}
                                </option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ti ti-search me-2"></i> {{ __('articles.search_articles') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Mobile-First Recent Articles Sidebar -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0 f-s-16 f-w-600">
                        <i class="ph ph-clock me-2"></i>
                        {{ __('articles.recent_articles') }}
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($sidebarRecentArticles->take(10) as $sidebarArticle)
                        <div class="d-flex align-items-start mb-3">
                            <div class="flex-shrink-0 me-3">
                                @if($sidebarArticle->featured_image_url)
                                    <img src="{{ $sidebarArticle->featured_image_url }}"
                                         class="rounded" style="width: 50px; height: 50px; object-fit: cover;"
                                         alt="{{ $sidebarArticle->title }}">
                                @else
                                    {!! PlaceholderHelper::getArticlePlaceholderHtml(50, 50, 'rounded', route('articles.show', $sidebarArticle->slug)) !!}
                                @endif
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 f-s-14 f-w-600">
                                    <a href="{{ route('articles.show', $sidebarArticle) }}" class="text-decoration-none">
                                        {{ Str::limit($sidebarArticle->title, 45) }}
                                    </a>
                                </h6>
                                <small class="text-muted f-s-12">
                                    {{ $sidebarArticle->published_at->format('d/m/Y') }}
                                </small>
                            </div>
                        </div>
                        @if(!$loop->last)
                            <hr class="my-3">
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Mobile-First Categories -->
            @if($categories->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0 f-s-16 f-w-600">{{ __('articles.categories') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            <a href="{{ route('articles.index') }}"
                               class="badge {{ !request('category') ? 'bg-primary' : 'bg-light text-dark' }} text-decoration-none f-s-12">
                                {{ __('articles.all_categories') }}
                            </a>
                            @foreach($categories as $category)
                                <a href="{{ route('articles.index', ['category' => $category->slug]) }}"
                                   class="badge text-decoration-none f-s-12 {{ request('category') === $category->slug ? 'bg-primary' : 'bg-light text-dark' }}">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Mobile-First Popular Tags -->
            @if($tags->count() > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0 f-s-16 f-w-600">{{ __('articles.popular_tags') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($tags->take(8) as $tag)
                                <a href="{{ route('articles.index', ['tag' => $tag->slug]) }}"
                                   class="badge bg-light text-dark text-decoration-none f-s-12">
                                    {{ $tag->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Mobile-First Logo Responsive Fix */
@media (max-width: 767px) {
    #sidebarCollapse .app-logo .logo-full {
        max-width: 150px !important;
        width: 150px !important;
        height: auto !important;
    }

    #sidebarCollapse .app-logo .logo-icon {
        max-width: 80px !important;
        width: 80px !important;
        height: auto !important;
    }

    #sidebarCollapse .app-logo .logo {
        padding: 0.5rem !important;
        text-align: center !important;
    }

    #sidebarCollapse .nav-profile {
        padding: 0.5rem !important;
    }

    #sidebarCollapse .nav-profile .h-45 {
        height: 35px !important;
        width: 35px !important;
    }

    #sidebarCollapse .nav-profile h6 {
        font-size: 0.875rem !important;
    }

    #sidebarCollapse .nav-profile p {
        font-size: 0.75rem !important;
    }
}

/* Desktop logo normal size */
@media (min-width: 768px) {
    #sidebarCollapse .app-logo .logo-full {
        max-width: 200px !important;
        width: 200px !important;
    }

    #sidebarCollapse .app-logo .logo-icon {
        max-width: 120px !important;
        width: 120px !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile-First Search Handling
    const searchForm = document.getElementById('searchForm');
    const searchInput = searchForm.querySelector('input[name="search"]');

    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            searchForm.submit();
        }
    });

    // Mobile-First Sidebar Toggle Handling
    const sidebarToggle = document.querySelector('[data-bs-toggle="collapse"]');
    const sidebarCollapse = document.getElementById('sidebarCollapse');

    if (sidebarToggle && sidebarCollapse) {
        sidebarCollapse.addEventListener('show.bs.collapse', function() {
            sidebarToggle.innerHTML = '<i class="ph ph-funnel me-2"></i>{{ __("articles.hide_filters") }}<i class="ph ph-chevron-up ms-2"></i>';
        });

        sidebarCollapse.addEventListener('hide.bs.collapse', function() {
            sidebarToggle.innerHTML = '<i class="ph ph-funnel me-2"></i>{{ __("articles.show_filters") }}<i class="ph ph-chevron-down ms-2"></i>';
        });
    }

    // Mobile-First Responsive Adjustments
    function adjustMobileLayout() {
        const isMobile = window.innerWidth < 768;
        const featuredCards = document.querySelectorAll('.card.hover-effect');
        const sidebarCards = document.querySelectorAll('#sidebarCollapse .card');

        if (isMobile) {
            featuredCards.forEach(card => {
                card.classList.add('mb-3');
            });
            sidebarCards.forEach(card => {
                card.classList.add('mb-3');
            });
        } else {
            featuredCards.forEach(card => {
                card.classList.remove('mb-3');
            });
            sidebarCards.forEach(card => {
                card.classList.remove('mb-3');
            });
        }
    }

    // Initial adjustment
    adjustMobileLayout();

    // Adjust on resize
    window.addEventListener('resize', adjustMobileLayout);
});
</script>
@endpush

<!-- Delete Article Modal -->
<div class="modal fade" id="deleteArticleModal" tabindex="-1" aria-labelledby="deleteArticleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="deleteArticleModalLabel">
                    <i class="ph ph-warning me-2"></i>{{ __('articles.delete_article_title') }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('common.close') }}"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('articles.confirm_delete') }} <strong id="deleteArticleTitle"></strong>?</p>
                <div class="alert alert-warning">
                    <i class="ph ph-warning me-2"></i>
                    <strong>{{ __('articles.warning') }}</strong> {{ __('articles.delete_action_warning') }}
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('articles.cancel') }}</button>
                <form id="deleteArticleForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="ph ph-trash me-2"></i>{{ __('articles.delete_permanently') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
