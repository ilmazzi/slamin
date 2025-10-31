<div>
    @script
    <script>
        Livewire.on('swal:toast', (data) => {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
            Toast.fire({
                icon: data[0].icon || 'success',
                title: data[0].title || 'Fatto!'
            });
        });
    </script>
    @endscript

    {{-- Back to Forum --}}
    <div class="mb-3">
        <a href="{{ route('forum.index') }}" class="btn btn-sm btn-light-primary">
            <i class="ph ph-arrow-left me-2"></i>Torna al Forum
        </a>
    </div>

    {{-- Subreddit Header --}}
    <div class="card mb-4" style="background: linear-gradient(135deg, {{ $subreddit->color }}22 0%, {{ $subreddit->color }}11 100%);">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="mb-2">
                        <i class="ph ph-chats-circle me-2" style="color: {{ $subreddit->color }};"></i>
                        r/{{ $subreddit->name }}
                    </h2>
                    <p class="text-muted mb-2">{{ $subreddit->description }}</p>
                    <div class="d-flex gap-3 small text-muted">
                        <span><i class="ph ph-users me-1"></i>{{ number_format($subreddit->subscribers_count) }} {{ __('forum.subscribers') }}</span>
                        <span><i class="ph ph-chat-circle me-1"></i>{{ number_format($subreddit->posts_count) }} {{ __('forum.posts') }}</span>
                    </div>
                </div>
                <div class="col-md-4 text-end">
                    @auth
                        <button wire:click="subscribe" 
                                class="btn {{ $isSubscribed ? 'btn-light-danger' : 'btn-primary' }} mb-2">
                            <i class="ph {{ $isSubscribed ? 'ph-bell-slash' : 'ph-bell' }} me-2"></i>
                            {{ $isSubscribed ? __('forum.unsubscribe') : __('forum.subscribe') }}
                        </button>
                        <a href="{{ route('forum.post.create', ['subreddit' => $subreddit->slug]) }}" 
                           class="btn btn-success d-block">
                            <i class="ph ph-plus-circle me-2"></i>{{ __('forum.create_post') }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-light-primary">
                            <i class="ph ph-sign-in me-2"></i>{{ __('forum.login_to_subscribe') }}
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            {{-- Sort Options --}}
            <div class="card mb-3">
                <div class="card-body py-3">
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

                    @if($sortBy === 'top')
                        <div class="mt-2">
                            <div class="btn-group btn-group-sm w-100" role="group">
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
            @forelse($posts as $post)
                <div class="card mb-3 hover-effect">
                    <div class="card-body">
                        <div class="row">
                            {{-- Vote buttons --}}
                            <div class="col-auto">
                                @livewire('forum.forum-vote-button', ['voteable' => $post], key('post-vote-'.$post->id))
                            </div>

                            {{-- Post content --}}
                            <div class="col">
                                <div class="d-flex align-items-start mb-2">
                                    <span class="text-muted small">
                                        {{ __('forum.posted_by') }} 
                                        <a href="{{ route('user.show', $post->user->id) }}">{{ $post->user->name }}</a>
                                        • {{ $post->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                <h5 class="mb-2">
                                    <a href="{{ route('forum.post.show', ['subreddit' => $subreddit->slug, 'post' => $post->id]) }}" 
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
                        <h5 class="text-muted">{{ __('forum.no_posts_in_subreddit') }}</h5>
                        @auth
                            <a href="{{ route('forum.post.create', ['subreddit' => $subreddit->slug]) }}" 
                               class="btn btn-primary mt-3">
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

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- About Subreddit --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="ph ph-info me-2"></i>{{ __('forum.about_subreddit') }}
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-3">{{ $subreddit->description }}</p>
                    <div class="d-grid gap-2">
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">{{ __('forum.created') }}</span>
                            <strong>{{ $subreddit->created_at->format('M d, Y') }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">{{ __('forum.subscribers') }}</span>
                            <strong>{{ number_format($subreddit->subscribers_count) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">{{ __('forum.posts') }}</span>
                            <strong>{{ number_format($subreddit->posts_count) }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Subreddit Rules --}}
            @if($subreddit->rules)
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="ph ph-list-checks me-2"></i>{{ __('forum.subreddit_rules') }}
                        </h6>
                    </div>
                    <div class="card-body">
                        {!! nl2br(e($subreddit->rules)) !!}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
