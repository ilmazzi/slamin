@extends('layout.master')

@section('main-content')
<div class="container-fluid">
    <!-- Search Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-light-primary">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h4 class="mb-1">
                                <i class="ph ph-magnifying-glass me-2"></i>
                                {{ __('search.search_results') }}
                            </h4>
                            @if(!empty($query))
                                <p class="text-muted mb-0">
                                    {{ __('search.results_for') }} "<strong>{{ $query }}</strong>"
                                    - {{ $totalResults }} {{ __('search.results_found') }}
                                </p>
                            @endif
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="btn-group" role="group">
                                <a href="{{ route('search.index', ['q' => $query, 'type' => 'all']) }}"
                                   class="btn {{ $type === 'all' ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                                    {{ __('search.all') }}
                                </a>
                                <a href="{{ route('search.index', ['q' => $query, 'type' => 'poems']) }}"
                                   class="btn {{ $type === 'poems' ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                                    {{ __('search.poems') }}
                                </a>
                                <a href="{{ route('search.index', ['q' => $query, 'type' => 'events']) }}"
                                   class="btn {{ $type === 'events' ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                                    {{ __('search.events') }}
                                </a>
                                <a href="{{ route('search.index', ['q' => $query, 'type' => 'videos']) }}"
                                   class="btn {{ $type === 'videos' ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                                    {{ __('search.videos') }}
                                </a>
                                <a href="{{ route('search.index', ['q' => $query, 'type' => 'gigs']) }}"
                                   class="btn {{ $type === 'gigs' ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                                    {{ __('search.gigs') }}
                                </a>
                                @auth
                                <a href="{{ route('search.index', ['q' => $query, 'type' => 'users']) }}"
                                   class="btn {{ $type === 'users' ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                                    {{ __('search.users') }}
                                </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(empty($query))
        <!-- Search Form -->
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card card-light-primary">
                    <div class="card-body text-center">
                        <i class="ph ph-magnifying-glass display-1 text-primary mb-3"></i>
                        <h4 class="mb-3">{{ __('search.start_searching') }}</h4>
                        <p class="text-muted mb-4">{{ __('search.search_description') }}</p>

                        <form action="{{ route('search.index') }}" method="GET" class="d-flex">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light">
                                    <i class="ph ph-magnifying-glass"></i>
                                </span>
                                <input type="text"
                                       class="form-control"
                                       name="q"
                                       placeholder="{{ __('search.search_placeholder') }}"
                                       value="{{ $query }}">
                                <button class="btn btn-primary" type="submit">
                                    {{ __('search.search') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <!-- Search Results -->
        <div class="row">
            @if($totalResults > 0)
                @foreach($results as $category => $categoryResults)
                    @if($categoryResults['count'] > 0)
                        <div class="col-12 mb-4">
                            <div class="card card-light-{{ $category === 'poems' ? 'info' : ($category === 'events' ? 'success' : ($category === 'videos' ? 'warning' : ($category === 'gigs' ? 'primary' : 'secondary'))) }}">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0">
                                            <i class="ph ph-{{ $category === 'poems' ? 'pen-nib' : ($category === 'events' ? 'calendar' : ($category === 'videos' ? 'video' : ($category === 'gigs' ? 'briefcase' : 'users'))) }} me-2"></i>
                                            {{ __('search.' . $category) }}
                                            <span class="badge bg-primary ms-2">{{ $categoryResults['count'] }}</span>
                                        </h6>
                                        @if($categoryResults['total'] > $categoryResults['count'])
                                            <a href="{{ route('search.index', ['q' => $query, 'type' => $category]) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                {{ __('search.view_all') }} ({{ $categoryResults['total'] }})
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        @foreach($categoryResults['data'] as $item)
                                            <div class="col-md-6 col-lg-4 mb-3">
                                                <div class="card hover-effect">
                                                    <div class="card-body">
                                                        @if($category === 'poems')
                                                            <h6 class="card-title">
                                                                <a href="{{ route('poems.show', $item->slug) }}" class="text-decoration-none">
                                                                    {{ Str::limit($item->title, 50) }}
                                                                </a>
                                                            </h6>
                                                            <p class="card-text text-muted small">
                                                                {{ Str::limit(strip_tags($item->content), 100) }}
                                                            </p>
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <small class="text-muted">
                                                                    <i class="ph ph-user me-1"></i>
                                                                    {{ $item->user->name }}
                                                                </small>
                                                                <small class="text-muted">
                                                                    <i class="ph ph-calendar me-1"></i>
                                                                    {{ $item->published_at->format('d/m/Y') }}
                                                                </small>
                                                            </div>
                                                        @elseif($category === 'events')
                                                            <h6 class="card-title">
                                                                <a href="{{ route('events.show', $item->id) }}" class="text-decoration-none">
                                                                    {{ Str::limit($item->title, 50) }}
                                                                </a>
                                                            </h6>
                                                            <p class="card-text text-muted small">
                                                                {{ Str::limit($item->description, 100) }}
                                                            </p>
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <small class="text-muted">
                                                                    <i class="ph ph-map-pin me-1"></i>
                                                                    {{ $item->city }}
                                                                </small>
                                                                <small class="text-muted">
                                                                    <i class="ph ph-calendar me-1"></i>
                                                                    {{ $item->start_datetime->format('d/m/Y') }}
                                                                </small>
                                                            </div>
                                                        @elseif($category === 'videos')
                                                            <h6 class="card-title">
                                                                <a href="{{ route('videos.show', $item->id) }}" class="text-decoration-none">
                                                                    {{ Str::limit($item->title, 50) }}
                                                                </a>
                                                            </h6>
                                                            <p class="card-text text-muted small">
                                                                {{ Str::limit($item->description, 100) }}
                                                            </p>
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <small class="text-muted">
                                                                    <i class="ph ph-user me-1"></i>
                                                                    {{ $item->user->name }}
                                                                </small>
                                                                <small class="text-muted">
                                                                    <i class="ph ph-clock me-1"></i>
                                                                    {{ gmdate('i:s', $item->duration) }}
                                                                </small>
                                                            </div>
                                                        @elseif($category === 'gigs')
                                                            <h6 class="card-title">
                                                                <a href="{{ route('gigs.show', $item->id) }}" class="text-decoration-none">
                                                                    {{ Str::limit($item->title, 50) }}
                                                                </a>
                                                            </h6>
                                                            <p class="card-text text-muted small">
                                                                {{ Str::limit($item->description, 100) }}
                                                            </p>
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <small class="text-muted">
                                                                    <i class="ph ph-map-pin me-1"></i>
                                                                    {{ $item->location }}
                                                                </small>
                                                                <small class="text-muted">
                                                                    <i class="ph ph-calendar me-1"></i>
                                                                    {{ $item->deadline->format('d/m/Y') }}
                                                                </small>
                                                            </div>
                                                        @elseif($category === 'users')
                                                            <div class="d-flex align-items-center">
                                                                <div class="me-3">
                                                                    <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($item) }}"
                                                                         alt="{{ $item->name }}"
                                                                         class="rounded-circle"
                                                                         style="width: 40px; height: 40px;">
                                                                </div>
                                                                <div>
                                                                    <h6 class="card-title mb-1">
                                                                        <a href="{{ route('profile.show', $item->id) }}" class="text-decoration-none">
                                                                            {{ $item->name }}
                                                                        </a>
                                                                    </h6>
                                                                    <small class="text-muted">
                                                                        <i class="ph ph-envelope me-1"></i>
                                                                        {{ $item->email }}
                                                                    </small>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <!-- No Results -->
                <div class="col-12">
                    <div class="card card-light-secondary">
                        <div class="card-body text-center">
                            <i class="ph ph-magnifying-glass display-1 text-muted mb-3"></i>
                            <h4 class="mb-3">{{ __('search.no_results_found') }}</h4>
                            <p class="text-muted mb-4">{{ __('search.try_different_keywords') }}</p>
                            <a href="{{ route('search.index') }}" class="btn btn-primary">
                                <i class="ph ph-arrow-left me-2"></i>
                                {{ __('search.new_search') }}
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif
</div>
@endsection

