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
                @if(isset($layoutArticles) && !empty($layoutArticles))
                    <div class="mb-4">
                        <h4 class="mb-3 f-s-18 f-w-600">
                            <i class="ph ph-star me-2"></i>
                            {{ __('articles.editor_picks') }}
                        </h4>

                        <!-- Layout Articles - Editor Controlled -->
                        <!-- Banner Article -->
                        @if(isset($layoutArticles['banner']))
                            <div class="row g-3 mb-4">
                                <!-- Banner Article -->
                                <div class="col-12">
                                    @php $bannerArticle = $layoutArticles['banner']; @endphp
                                    <div class="card hover-effect">
                                        <div class="position-relative">
                                            @if($bannerArticle->featured_image_url)
                                                <img src="{{ $bannerArticle->featured_image_url }}"
                                                     class="card-img-top" style="height: 300px; object-fit: cover;"
                                                     alt="{{ $bannerArticle->title }}">
                                            @else
                                                {!! article_placeholder_html(0, 300, 'card-img-top', route('articles.show', $bannerArticle->slug)) !!}
                                            @endif
                                            <div class="position-absolute top-0 start-0 m-3">
                                                <span class="badge bg-primary">
                                                    <i class="ph ph-star me-1"></i>{{ __('articles.banner') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <h4 class="card-title f-s-20 f-w-600 mb-3">{{ $bannerArticle->title }}</h4>
                                            <p class="card-text f-s-16 text-muted mb-3">{{ Str::limit($bannerArticle->excerpt, 200) }}</p>

                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex gap-3 text-muted f-s-14">
                                                    <span><i class="ph ph-user me-1"></i>{{ $bannerArticle->user->name ?? 'N/A' }}</span>
                                                    <span><i class="ph ph-calendar me-1"></i>{{ $bannerArticle->published_at ? $bannerArticle->published_at->format('d/m/Y') : $bannerArticle->created_at->format('d/m/Y') }}</span>
                                                    
                                                    
                                                    
                                                </div>
                                                <a href="{{ route('articles.show', $bannerArticle->slug) }}" class="btn btn-primary">
                                                    {{ __('articles.read_more') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Featured Article 1 (Horizontal) -->
                        @if(isset($layoutArticles['horizontal1']))
                            <div class="row g-3 mb-4">
                                <!-- Featured Article -->
                                <div class="col-12">
                                    @php $featured1 = $layoutArticles['horizontal1']; @endphp
                                    <div class="card hover-effect">
                                        <div class="position-relative">
                                            @if($featured1->featured_image_url)
                                                <img src="{{ $featured1->featured_image_url }}"
                                                     class="card-img-top" style="height: 250px; object-fit: cover;"
                                                     alt="{{ $featured1->title }}">
                                            @else
                                                {!! article_placeholder_html(0, 250, 'card-img-top', route('articles.show', $featured1->slug)) !!}
                                            @endif
                                            <div class="position-absolute top-0 start-0 m-3">
                                                <span class="badge bg-warning">
                                                    <i class="ph ph-star me-1"></i>{{ __('articles.featured') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title f-s-18 f-w-600 mb-3">{{ $featured1->title }}</h5>
                                            <p class="card-text f-s-14 text-muted mb-3">{{ Str::limit($featured1->excerpt, 150) }}</p>

                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex gap-3 text-muted f-s-12">
                                                    <span><i class="ph ph-user me-1"></i>{{ $featured1->user->name ?? 'N/A' }}</span>
                                                    <span><i class="ph ph-calendar me-1"></i>{{ $featured1->published_at->format('d/m/Y') }}</span>
                                                    
                                                    
                                                    
                                                </div>
                                                <a href="{{ route('articles.show', $featured1->slug) }}" class="btn btn-primary">
                                                    {{ __('articles.read_more') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- 2 Recent Articles -->
                                @if($recentArticles->count() >= 2)
                                    <div class="col-12 col-sm-6">
                                        @php $recent1 = $recentArticles->get(0); @endphp
                                        <div class="card hover-effect h-100">
                                            <div class="position-relative">
                                                @if($recent1->featured_image_url)
                        <img src="{{ $recent1->featured_image_url }}"
                                                     class="card-img-top" style="height: 140px; object-fit: cover;"
                                                     alt="{{ $recent1->title }}">
                    @else
                        {!! article_placeholder_html(0, 200, 'card-img-top', route('articles.show', $recent1->slug)) !!}
                    @endif
                                                @if($recent1->category)
                                                <div class="position-absolute top-0 start-0 m-2">
                                                    <span class="badge bg-primary">{{ $recent1->category->name }}</span>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h6 class="card-title f-s-15 f-w-600 mb-2">{{ Str::limit($recent1->title, 50) }}</h6>
                                                <p class="card-text f-s-13 text-muted mb-3">{{ Str::limit($recent1->excerpt, 70) }}</p>

                                                <div class="mt-auto">
                                                    <div class="d-flex justify-content-between align-items-center text-muted f-s-11 mb-2">
                                                        <span><i class="ph ph-user me-1"></i>{{ $recent1->user->name ?? 'N/A' }}</span>
                                                        <span><i class="ph ph-calendar me-1"></i>{{ $recent1->published_at->format('d/m/Y') }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="d-flex gap-2 text-muted f-s-11">
                                                            
                                                            
                                                            
                                                        </div>
                                                        <a href="{{ route('articles.show', $recent1->slug) }}" class="btn btn-outline-primary btn-sm">
                                                            {{ __('articles.read_more') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-6">
                                        @php $recent2 = $recentArticles->get(1); @endphp
                                        <div class="card hover-effect h-100">
                                            <div class="position-relative">
                                                @if($recent2->featured_image_url)
                        <img src="{{ $recent2->featured_image_url }}"
                                                     class="card-img-top" style="height: 140px; object-fit: cover;"
                                                     alt="{{ $recent2->title }}">
                    @else
                        {!! article_placeholder_html(0, 200, 'card-img-top', route('articles.show', $recent2->slug)) !!}
                    @endif
                                                @if($recent2->category)
                                                <div class="position-absolute top-0 start-0 m-2">
                                                    <span class="badge bg-primary">{{ $recent2->category->name }}</span>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h6 class="card-title f-s-15 f-w-600 mb-2">{{ Str::limit($recent2->title, 50) }}</h6>
                                                <p class="card-text f-s-13 text-muted mb-3">{{ Str::limit($recent2->excerpt, 70) }}</p>

                                                <div class="mt-auto">
                                                    <div class="d-flex justify-content-between align-items-center text-muted f-s-11 mb-2">
                                                        <span><i class="ph ph-user me-1"></i>{{ $recent2->user->name ?? 'N/A' }}</span>
                                                        <span><i class="ph ph-calendar me-1"></i>{{ $recent2->published_at->format('d/m/Y') }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="d-flex gap-2 text-muted f-s-11">
                                                            
                                                            
                                                            
                                                        </div>
                                                        <a href="{{ route('articles.show', $recent2->slug) }}" class="btn btn-outline-primary btn-sm">
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

                        <!-- Featured Article 2 + 2 Recent -->
                        @if($featuredArticles->count() >= 2)
                            <div class="row g-3 mb-4">
                                <!-- Featured Article -->
                                <div class="col-12">
                                    @php $featured2 = $featuredArticles->get(1); @endphp
                                    <div class="card hover-effect">
                                        <div class="position-relative">
                                            @if($featured2->featured_image_url)
                        <img src="{{ $featured2->featured_image_url }}"
                                                 class="card-img-top" style="height: 250px; object-fit: cover;"
                                                 alt="{{ $featured2->title }}">
                    @else
                        {!! article_placeholder_html(0, 200, 'card-img-top', route('articles.show', $featured2->slug)) !!}
                    @endif
                                            <div class="position-absolute top-0 start-0 m-3">
                                                <span class="badge bg-warning">
                                                    <i class="ph ph-star me-1"></i>{{ __('articles.featured') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title f-s-18 f-w-600 mb-3">{{ $featured2->title }}</h5>
                                            <p class="card-text f-s-14 text-muted mb-3">{{ Str::limit($featured2->excerpt, 150) }}</p>

                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex gap-3 text-muted f-s-12">
                                                    <span><i class="ph ph-user me-1"></i>{{ $featured2->user->name ?? 'N/A' }}</span>
                                                    <span><i class="ph ph-calendar me-1"></i>{{ $featured2->published_at->format('d/m/Y') }}</span>
                                                    
                                                    
                                                    
                                                </div>
                                                <a href="{{ route('articles.show', $featured2->slug) }}" class="btn btn-primary">
                                                    {{ __('articles.read_more') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- 2 Recent Articles -->
                                @if($recentArticles->count() >= 4)
                                    <div class="col-12 col-sm-6">
                                        @php $recent3 = $recentArticles->get(2); @endphp
                                        <div class="card hover-effect h-100">
                                            <div class="position-relative">
                                                @if($recent3->featured_image_url)
                        <img src="{{ $recent3->featured_image_url }}"
                                                     class="card-img-top" style="height: 140px; object-fit: cover;"
                                                     alt="{{ $recent3->title }}">
                    @else
                        {!! article_placeholder_html(0, 200, 'card-img-top', route('articles.show', $recent3->slug)) !!}
                    @endif
                                                @if($recent3->category)
                                                <div class="position-absolute top-0 start-0 m-2">
                                                    <span class="badge bg-primary">{{ $recent3->category->name }}</span>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h6 class="card-title f-s-15 f-w-600 mb-2">{{ Str::limit($recent3->title, 50) }}</h6>
                                                <p class="card-text f-s-13 text-muted mb-3">{{ Str::limit($recent3->excerpt, 70) }}</p>

                                                <div class="mt-auto">
                                                    <div class="d-flex justify-content-between align-items-center text-muted f-s-11 mb-2">
                                                        <span><i class="ph ph-user me-1"></i>{{ $recent3->user->name ?? 'N/A' }}</span>
                                                        <span><i class="ph ph-calendar me-1"></i>{{ $recent3->published_at->format('d/m/Y') }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="d-flex gap-2 text-muted f-s-11">
                                                            
                                                            
                                                            
                                                        </div>
                                                        <a href="{{ route('articles.show', $recent3->slug) }}" class="btn btn-outline-primary btn-sm">
                                                            {{ __('articles.read_more') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-6">
                                        @php $recent4 = $recentArticles->get(3); @endphp
                                        <div class="card hover-effect h-100">
                                            <div class="position-relative">
                                                @if($recent4->featured_image_url)
                        <img src="{{ $recent4->featured_image_url }}"
                                                     class="card-img-top" style="height: 140px; object-fit: cover;"
                                                     alt="{{ $recent4->title }}">
                    @else
                        {!! article_placeholder_html(0, 200, 'card-img-top', route('articles.show', $recent4->slug)) !!}
                    @endif
                                                @if($recent4->category)
                                                <div class="position-absolute top-0 start-0 m-2">
                                                    <span class="badge bg-primary">{{ $recent4->category->name }}</span>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h6 class="card-title f-s-15 f-w-600 mb-2">{{ Str::limit($recent4->title, 50) }}</h6>
                                                <p class="card-text f-s-13 text-muted mb-3">{{ Str::limit($recent4->excerpt, 70) }}</p>

                                                <div class="mt-auto">
                                                    <div class="d-flex justify-content-between align-items-center text-muted f-s-11 mb-2">
                                                        <span><i class="ph ph-user me-1"></i>{{ $recent4->user->name ?? 'N/A' }}</span>
                                                        <span><i class="ph ph-calendar me-1"></i>{{ $recent4->published_at->format('d/m/Y') }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="d-flex gap-2 text-muted f-s-11">
                                                            
                                                            
                                                            
                                                        </div>
                                                        <a href="{{ route('articles.show', $recent4->slug) }}" class="btn btn-outline-primary btn-sm">
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

                        <!-- Featured Article 3 + 2 Recent -->
                        @if($featuredArticles->count() >= 3)
                            <div class="row g-3 mb-4">
                                <!-- Featured Article -->
                                <div class="col-12">
                                    @php $featured3 = $featuredArticles->get(2); @endphp
                                    <div class="card hover-effect">
                                        <div class="position-relative">
                                            @if($featured3->featured_image_url)
                        <img src="{{ $featured3->featured_image_url }}"
                                                 class="card-img-top" style="height: 250px; object-fit: cover;"
                                                 alt="{{ $featured3->title }}">
                    @else
                        {!! article_placeholder_html(0, 200, 'card-img-top', route('articles.show', $featured3->slug)) !!}
                    @endif
                                            <div class="position-absolute top-0 start-0 m-3">
                                                <span class="badge bg-warning">
                                                    <i class="ph ph-star me-1"></i>{{ __('articles.featured') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title f-s-18 f-w-600 mb-3">{{ $featured3->title }}</h5>
                                            <p class="card-text f-s-14 text-muted mb-3">{{ Str::limit($featured3->excerpt, 150) }}</p>

                                            <div class="d-flex justify-content-between align-items-center">
                                                <div class="d-flex gap-3 text-muted f-s-12">
                                                    <span><i class="ph ph-user me-1"></i>{{ $featured3->user->name ?? 'N/A' }}</span>
                                                    <span><i class="ph ph-calendar me-1"></i>{{ $featured3->published_at->format('d/m/Y') }}</span>
                                                    
                                                    
                                                    
                                                </div>
                                                <a href="{{ route('articles.show', $featured3->slug) }}" class="btn btn-primary">
                                                    {{ __('articles.read_more') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- 2 Recent Articles -->
                                @if($recentArticles->count() >= 6)
                                    <div class="col-12 col-sm-6">
                                        @php $recent5 = $recentArticles->get(4); @endphp
                                        <div class="card hover-effect h-100">
                                            <div class="position-relative">
                                                @if($recent5->featured_image_url)
                        <img src="{{ $recent5->featured_image_url }}"
                                                     class="card-img-top" style="height: 140px; object-fit: cover;"
                                                     alt="{{ $recent5->title }}">
                    @else
                        {!! article_placeholder_html(0, 200, 'card-img-top', route('articles.show', $recent5->slug)) !!}
                    @endif
                                                @if($recent5->category)
                                                <div class="position-absolute top-0 start-0 m-2">
                                                    <span class="badge bg-primary">{{ $recent5->category->name }}</span>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h6 class="card-title f-s-15 f-w-600 mb-2">{{ Str::limit($recent5->title, 50) }}</h6>
                                                <p class="card-text f-s-13 text-muted mb-3">{{ Str::limit($recent5->excerpt, 70) }}</p>

                                                <div class="mt-auto">
                                                    <div class="d-flex justify-content-between align-items-center text-muted f-s-11 mb-2">
                                                        <span><i class="ph ph-user me-1"></i>{{ $recent5->user->name ?? 'N/A' }}</span>
                                                        <span><i class="ph ph-calendar me-1"></i>{{ $recent5->published_at->format('d/m/Y') }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="d-flex gap-2 text-muted f-s-11">
                                                            
                                                            
                                                            
                                                        </div>
                                                        <a href="{{ route('articles.show', $recent5->slug) }}" class="btn btn-outline-primary btn-sm">
                                                            {{ __('articles.read_more') }}
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-sm-6">
                                        @php $recent6 = $recentArticles->get(5); @endphp
                                        <div class="card hover-effect h-100">
                                            <div class="position-relative">
                                                @if($recent6->featured_image_url)
                        <img src="{{ $recent6->featured_image_url }}"
                                                     class="card-img-top" style="height: 140px; object-fit: cover;"
                                                     alt="{{ $recent6->title }}">
                    @else
                        {!! article_placeholder_html(0, 200, 'card-img-top', route('articles.show', $recent6->slug)) !!}
                    @endif
                                                @if($recent6->category)
                                                <div class="position-absolute top-0 start-0 m-2">
                                                    <span class="badge bg-primary">{{ $recent6->category->name }}</span>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="card-body d-flex flex-column">
                                                <h6 class="card-title f-s-15 f-w-600 mb-2">{{ Str::limit($recent6->title, 50) }}</h6>
                                                <p class="card-text f-s-13 text-muted mb-3">{{ Str::limit($recent6->excerpt, 70) }}</p>

                                                <div class="mt-auto">
                                                    <div class="d-flex justify-content-between align-items-center text-muted f-s-11 mb-2">
                                                        <span><i class="ph ph-user me-1"></i>{{ $recent6->user->name ?? 'N/A' }}</span>
                                                        <span><i class="ph ph-calendar me-1"></i>{{ $recent6->published_at->format('d/m/Y') }}</span>
                                                    </div>
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <div class="d-flex gap-2 text-muted f-s-11">
                                                            
                                                            
                                                            
                                                        </div>
                                                        <a href="{{ route('articles.show', $recent6->slug) }}" class="btn btn-outline-primary btn-sm">
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
                            <div class="card hover-effect h-100">
                                <div class="position-relative">
                                    @if($article->featured_image_url)
                                        <img src="{{ $article->featured_image_url }}"
                                             class="card-img-top" style="height: 200px; object-fit: cover;"
                                             alt="{{ $article->title }}">
                                    @else
                                        {!! article_placeholder_html(0, 200, 'card-img-top', route('articles.show', $article->slug)) !!}
                                    @endif
                                    @if($article->featured)
                                        <div class="position-absolute top-0 start-0 m-2">
                                            <span class="badge bg-warning">{{ __('articles.featured') }}</span>
                                        </div>
                                    @endif
                                    @if($article->category)
                                        <div class="position-absolute top-0 end-0 m-2">
                                            <span class="badge bg-primary">{{ $article->category->name }}</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title f-s-16 f-w-600 mb-2">{{ Str::limit($article->title, 60) }}</h5>
                                    <p class="card-text f-s-14 text-muted mb-3 flex-grow-1">{{ Str::limit($article->excerpt, 100) }}</p>
                                    
                                    <div class="mt-auto">
                                        <div class="d-flex justify-content-between align-items-center text-muted f-s-12 mb-2">
                                            <span><i class="ph ph-user me-1"></i>{{ $article->user->name ?? 'N/A' }}</span>
                                            <span><i class="ph ph-calendar me-1"></i>{{ $article->published_at ? $article->published_at->format('d/m/Y') : $article->created_at->format('d/m/Y') }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex gap-2 text-muted f-s-12">
                                                
                                                
                                                
                                            </div>
                                            <div class="btn-group btn-group-sm">
                                                <a href="{{ route('articles.show', $article) }}" class="btn btn-outline-primary">
                                                    <i class="ph ph-eye"></i>
                                                </a>
                                                @can('update', $article)
                                                    <a href="{{ route('articles.edit', $article) }}" class="btn btn-outline-secondary">
                                                        <i class="ph ph-pencil"></i>
                                                    </a>
                                                @endcan
                                                @if($article->canBeDeletedBy(auth()->user()))
                                                    <button type="button" class="btn btn-outline-danger" 
                                                            onclick="deleteArticle({{ json_encode($article->slug) }})">
                                                        <i class="ph ph-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                    @foreach($recentArticles->take(5) as $sidebarArticle)
                        <div class="d-flex align-items-start mb-3">
                            <div class="flex-shrink-0 me-3">
                                @if($sidebarArticle->featured_image_url)
                        <img src="{{ $sidebarArticle->featured_image_url }}"
                                     class="rounded" style="width: 50px; height: 50px; object-fit: cover;"
                                     alt="{{ $sidebarArticle->title }}">
                    @else
                        {!! article_placeholder_html(50, 50, 'rounded', route('articles.show', $sidebarArticle->slug)) !!}
                    @endif
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 f-s-14 f-w-600">
                                    <a href="{{ route('articles.show', $sidebarArticle) }}" class="text-decoration-none">
                                        {{ Str::limit($sidebarArticle->title, 45) }}
                                    </a>
                                </h6>
                                <small class="text-muted f-s-12">
                                    {{ $sidebarArticle->published_at ? $sidebarArticle->published_at->format('d/m/Y') : $sidebarArticle->created_at->format('d/m/Y') }}
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

<!-- Delete Article Modal -->
<div class="modal fade" id="deleteArticleModal" tabindex="-1" aria-labelledby="deleteArticleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteArticleModalLabel">{{ __('articles.delete_article') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>{{ __('articles.delete_confirmation') }}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('common.cancel') }}</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">{{ __('common.delete') }}</button>
            </div>
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

// Delete Article Function with SweetAlert2 (Global scope)
window.deleteArticle = function(articleSlug) {
    console.log('Deleting article:', articleSlug);
    Swal.fire({
        title: 'Elimina Articolo',
        text: 'Sei sicuro di voler eliminare questo articolo?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Elimina',
        cancelButtonText: 'Annulla'
    }).then(function(result) {
        if (result.isConfirmed) {
            fetch('/articles/' + articleSlug, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                if (data.success) {
                    Swal.fire({
                        title: 'Successo',
                        text: data.message,
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(function() {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Errore',
                        text: data.message || 'Errore durante l\'eliminazione',
                        icon: 'error'
                    });
                }
            })
            .catch(function(error) {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Errore',
                    text: 'Errore durante l\'eliminazione',
                    icon: 'error'
                });
            });
        }
    });
}
</script>
@endpush