<div>
    {{-- Header --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h2 class="mb-0">
                        <i class="ph ph-chats-circle me-2 text-primary"></i>
                        {{ __('forum.forum_title') }}
                    </h2>
                    <p class="text-muted mb-0">{{ __('forum.forum_subtitle') }}</p>
                </div>
                <div class="col-md-6 text-end">
                    @auth
                        <a href="{{ route('forum.post.create') }}" class="btn btn-primary">
                            <i class="ph ph-plus-circle me-2"></i>{{ __('forum.create_post') }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-light-primary">
                            <i class="ph ph-sign-in me-2"></i>{{ __('forum.login_to_post') }}
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Main Content --}}
        <div class="col-lg-8">
            {{-- Search & Sort --}}
            <div class="card mb-3">
                <div class="card-body py-3">
                    <div class="row align-items-center g-2">
                        <div class="col-md-6">
                            <input type="text" wire:model.live.debounce.300ms="search" 
                                   class="form-control" 
                                   placeholder="{{ __('forum.search_posts') }}">
                        </div>
                        <div class="col-md-6">
                            <div class="btn-group w-100" role="group">
                                <button type="button" wire:click="setSortBy('hot')" 
                                        class="btn btn-sm {{ $sortBy === 'hot' ? 'btn-primary' : 'btn-light-primary' }}">
                                    <i class="ph ph-fire"></i> {{ __('forum.sort_hot') }}
                                </button>
                                <button type="button" wire:click="setSortBy('new')" 
                                        class="btn btn-sm {{ $sortBy === 'new' ? 'btn-primary' : 'btn-light-primary' }}">
                                    <i class="ph ph-clock"></i> {{ __('forum.sort_new') }}
                                </button>
                                <button type="button" wire:click="setSortBy('top')" 
                                        class="btn btn-sm {{ $sortBy === 'top' ? 'btn-primary' : 'btn-light-primary' }}">
                                    <i class="ph ph-chart-line-up"></i> {{ __('forum.sort_top') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    @if($sortBy === 'top')
                        <div class="mt-2">
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" wire:click="setTimeframe('today')" 
                                        class="btn {{ $timeframe === 'today' ? 'btn-success' : 'btn-light-success' }}">
                                    {{ __('forum.today') }}
                                </button>
                                <button type="button" wire:click="setTimeframe('week')" 
                                        class="btn {{ $timeframe === 'week' ? 'btn-success' : 'btn-light-success' }}">
                                    {{ __('forum.this_week') }}
                                </button>
                                <button type="button" wire:click="setTimeframe('month')" 
                                        class="btn {{ $timeframe === 'month' ? 'btn-success' : 'btn-light-success' }}">
                                    {{ __('forum.this_month') }}
                                </button>
                                <button type="button" wire:click="setTimeframe('year')" 
                                        class="btn {{ $timeframe === 'year' ? 'btn-success' : 'btn-light-success' }}">
                                    {{ __('forum.this_year') }}
                                </button>
                                <button type="button" wire:click="setTimeframe('all')" 
                                        class="btn {{ $timeframe === 'all' ? 'btn-success' : 'btn-light-success' }}">
                                    {{ __('forum.all_time') }}
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Posts List --}}
            <div class="posts-list">
                @forelse($posts as $post)
                    <div class="card mb-3 hover-effect">
                        <div class="card-body">
                            <div class="row">
                                {{-- Vote buttons --}}
                                <div class="col-auto">
                                    @livewire('forum-vote-button', ['voteable' => $post], key('post-vote-'.$post->id))
                                </div>

                                {{-- Post content --}}
                                <div class="col">
                                    <div class="d-flex align-items-start mb-2">
                                        <a href="{{ route('forum.subreddit.show', $post->subreddit->slug) }}" 
                                           class="badge bg-light-primary text-primary me-2">
                                            r/{{ $post->subreddit->name }}
                                        </a>
                                        <span class="text-muted small">
                                            {{ __('forum.posted_by') }} 
                                            <a href="{{ route('profile.show', $post->user->id) }}">{{ $post->user->name }}</a>
                                            • {{ $post->created_at->diffForHumans() }}
                                        </span>
                                    </div>

                                    <h5 class="mb-2">
                                        <a href="{{ route('forum.post.show', ['subreddit' => $post->subreddit->slug, 'post' => $post->id]) }}" 
                                           class="text-dark text-decoration-none">
                                            @if($post->is_sticky)
                                                <i class="ph ph-push-pin text-success"></i>
                                            @endif
                                            @if($post->is_locked)
                                                <i class="ph ph-lock text-warning"></i>
                                            @endif
                                            {{ $post->title }}
                                        </a>
                                    </h5>

                                    @if($post->type === 'text' && $post->content)
                                        <p class="text-muted mb-2">{{ Str::limit(strip_tags($post->content), 200) }}</p>
                                    @elseif($post->type === 'link' && $post->url)
                                        <a href="{{ $post->url }}" target="_blank" class="text-primary">
                                            <i class="ph ph-link me-1"></i>{{ Str::limit($post->url, 60) }}
                                        </a>
                                    @elseif($post->type === 'image' && $post->image_path)
                                        <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="img-fluid rounded" style="max-height: 300px;">
                                    @endif

                                    <div class="d-flex gap-3 mt-2 text-muted small">
                                        <span>
                                            <i class="ph ph-chat-circle"></i> {{ $post->comments_count }} {{ __('forum.comments') }}
                                        </span>
                                        <span>
                                            <i class="ph ph-eye"></i> {{ $post->views_count }} {{ __('forum.views') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <i class="ph ph-chats-circle f-s-48 text-muted mb-3"></i>
                            <h5 class="text-muted">{{ __('forum.no_posts_found') }}</h5>
                            @auth
                                <a href="{{ route('forum.post.create') }}" class="btn btn-primary mt-3">
                                    <i class="ph ph-plus-circle me-2"></i>{{ __('forum.create_first_post') }}
                                </a>
                            @endauth
                        </div>
                    </div>
                @endforelse

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $posts->links() }}
                </div>
            </div>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Popular Subreddits --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph ph-chart-line-up me-2"></i>{{ __('forum.popular_subreddits') }}
                    </h6>
                </div>
                <div class="list-group list-group-flush">
                    @foreach($popularSubreddits as $subreddit)
                        <a href="{{ route('forum.subreddit.show', $subreddit->slug) }}" 
                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                <strong>r/{{ $subreddit->name }}</strong>
                                <p class="mb-0 small text-muted">{{ Str::limit($subreddit->description, 60) }}</p>
                            </div>
                            <span class="badge bg-light-primary text-primary">
                                {{ number_format($subreddit->subscribers_count) }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Forum Rules --}}
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph ph-info me-2"></i>{{ __('forum.forum_rules') }}
                    </h6>
                </div>
                <div class="card-body">
                    <ol class="mb-0">
                        <li>{{ __('forum.rule_1') }}</li>
                        <li>{{ __('forum.rule_2') }}</li>
                        <li>{{ __('forum.rule_3') }}</li>
                        <li>{{ __('forum.rule_4') }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
