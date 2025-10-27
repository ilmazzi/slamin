<div>
    {{-- Back to Subreddit --}}
    <div class="mb-3">
        <a href="{{ route('forum.subreddit.show', $post->subreddit->slug) }}" class="btn btn-sm btn-light-primary">
            <i class="ph ph-arrow-left me-2"></i>{{ __('forum.back_to_subreddit') }}
        </a>
    </div>

    {{-- Moderator Actions --}}
    @livewire('moderator.post-actions', ['post' => $post], key('post-actions-'.$post->id))

    {{-- Post Card --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                {{-- Vote buttons --}}
                <div class="col-auto">
                    @livewire('forum.forum-vote-button', ['voteable' => $post], key('post-vote-'.$post->id))
                </div>

                {{-- Post content --}}
                <div class="col">
                    <div class="d-flex align-items-center mb-3">
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

                    <h3 class="mb-3">
                        @if($post->is_sticky)
                            <i class="ph ph-push-pin text-success"></i>
                        @endif
                        @if($post->is_locked)
                            <i class="ph ph-lock text-warning"></i>
                        @endif
                        {{ $post->title }}
                    </h3>

                    @if($post->type === 'text' && $post->content)
                        <div class="post-content mb-3">
                            {!! nl2br(e($post->content)) !!}
                        </div>
                    @elseif($post->type === 'link' && $post->url)
                        <a href="{{ $post->url }}" target="_blank" class="btn btn-light-primary mb-3">
                            <i class="ph ph-link me-2"></i>{{ $post->url }}
                        </a>
                    @elseif($post->type === 'image' && $post->image_path)
                        <img src="{{ $post->image_url }}" alt="{{ $post->title }}" class="img-fluid rounded mb-3">
                    @endif

                    <div class="d-flex gap-3 text-muted small align-items-center">
                        <span>
                            <i class="ph ph-chat-circle"></i> {{ $post->comments_count }} {{ __('forum.comments') }}
                        </span>
                        <span>
                            <i class="ph ph-eye"></i> {{ $post->views_count }} {{ __('forum.views') }}
                        </span>
                        @auth
                            <div class="ms-auto">
                                @livewire('forum.report-button', ['reportable' => $post], key('report-post-'.$post->id))
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Comments Section --}}
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="ph ph-chat-circle me-2"></i>{{ __('forum.comments') }} ({{ $post->comments_count }})
                </h5>
                <div class="btn-group btn-group-sm" role="group">
                    <button type="button" wire:click="setSortComments('best')" 
                            class="btn {{ $sortComments === 'best' ? 'btn-primary' : 'btn-light-primary' }}">
                        {{ __('forum.best') }}
                    </button>
                    <button type="button" wire:click="setSortComments('top')" 
                            class="btn {{ $sortComments === 'top' ? 'btn-primary' : 'btn-light-primary' }}">
                        {{ __('forum.top') }}
                    </button>
                    <button type="button" wire:click="setSortComments('new')" 
                            class="btn {{ $sortComments === 'new' ? 'btn-primary' : 'btn-light-primary' }}">
                        {{ __('forum.new') }}
                    </button>
                    <button type="button" wire:click="setSortComments('old')" 
                            class="btn {{ $sortComments === 'old' ? 'btn-primary' : 'btn-light-primary' }}">
                        {{ __('forum.old') }}
                    </button>
                </div>
            </div>
        </div>

        {{-- Add Comment Form --}}
        @if(!$post->is_locked)
            <div class="card-body border-bottom">
                @auth
                    <form wire:submit.prevent="addComment">
                        <div class="mb-3">
                            <textarea wire:model="newComment" 
                                      class="form-control @error('newComment') is-invalid @enderror" 
                                      rows="4" 
                                      placeholder="{{ __('forum.write_comment') }}"></textarea>
                            @error('newComment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="ph ph-paper-plane-tilt me-2"></i>{{ __('forum.post_comment') }}
                        </button>
                    </form>
                @else
                    <div class="alert alert-border-primary" role="alert">
                        <i class="ph ph-info me-2"></i>
                        <a href="{{ route('login') }}">{{ __('forum.login') }}</a> {{ __('forum.or') }} 
                        <a href="{{ route('register') }}">{{ __('forum.register') }}</a> 
                        {{ __('forum.to_comment') }}
                    </div>
                @endauth
            </div>
        @else
            <div class="card-body border-bottom">
                <div class="alert alert-border-warning" role="alert">
                    <i class="ph ph-lock me-2"></i>{{ __('forum.post_locked') }}
                </div>
            </div>
        @endif

        {{-- Comments List --}}
        <div class="card-body">
            @forelse($comments as $comment)
                @livewire('forum.forum-comment', ['comment' => $comment], key('comment-'.$comment->id))
            @empty
                <div class="text-center py-5">
                    <i class="ph ph-chat-circle f-s-48 text-muted mb-3"></i>
                    <p class="text-muted">{{ __('forum.no_comments_yet') }}</p>
                    @auth
                        <p class="text-muted">{{ __('forum.be_first_to_comment') }}</p>
                    @endauth
                </div>
            @endforelse
        </div>
    </div>
</div>
