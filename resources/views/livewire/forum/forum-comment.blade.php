<div class="comment-item mb-3" style="padding-left: {{ $comment->depth * 30 }}px;">
    <div class="card {{ $comment->is_deleted ? 'bg-light' : '' }}">
        <div class="card-body py-2">
            <div class="row">
                {{-- Vote buttons --}}
                <div class="col-auto">
                    @if(!$comment->is_deleted)
                        @livewire('forum.forum-vote-button', ['voteable' => $comment], key('comment-vote-'.$comment->id))
                    @endif
                </div>

                {{-- Comment content --}}
                <div class="col">
                    <div class="d-flex align-items-center mb-2">
                        <a href="{{ route('user.show', $comment->user->id) }}" class="text-decoration-none me-2">
                            <strong>{{ $comment->user->name }}</strong>
                        </a>
                        <span class="text-muted small">
                            • {{ $comment->created_at->diffForHumans() }}
                        </span>
                        @if($comment->is_deleted)
                            <span class="badge bg-light-danger text-danger ms-2 small">
                                {{ __('forum.deleted') }}
                            </span>
                        @endif
                    </div>

                    <div class="comment-content mb-2">
                        @if($comment->is_deleted)
                            <em class="text-muted">{{ $comment->content }}</em>
                        @else
                            {!! nl2br(e($comment->content)) !!}
                        @endif
                    </div>

                    {{-- Comment Actions --}}
                    @if(!$comment->is_deleted)
                        <div class="d-flex gap-3 small">
                            @auth
                                {{-- Reply button --}}
                                @if($comment->canReply() && !$comment->post->is_locked)
                                    <button wire:click="toggleReplyForm" 
                                            class="btn btn-sm btn-light-primary">
                                        <i class="ph ph-arrow-bend-up-left me-1"></i>{{ __('forum.reply') }}
                                    </button>
                                @endif

                                {{-- Delete button (only for author or moderator) --}}
                                @if(auth()->id() === $comment->user_id || auth()->user()->hasRole('admin') || auth()->user()->hasRole('moderator'))
                                    <button wire:click="deleteComment" 
                                            wire:confirm="{{ __('forum.confirm_delete_comment') }}"
                                            class="btn btn-sm btn-light-danger">
                                        <i class="ph ph-trash me-1"></i>{{ __('forum.delete') }}
                                    </button>
                                @endif

                                {{-- Report button --}}
                                <div class="ms-auto">
                                    @livewire('forum.report-button', ['reportable' => $comment], key('report-comment-'.$comment->id))
                                </div>
                            @else
                                <a href="{{ route('login') }}" class="text-muted text-decoration-none">
                                    <i class="ph ph-arrow-bend-up-left me-1"></i>{{ __('forum.reply') }}
                                </a>
                            @endauth
                        </div>
                    @endif

                    {{-- Reply Form --}}
                    @if($showReplyForm)
                        <div class="mt-3">
                            <form wire:submit.prevent="addReply">
                                <textarea wire:model="replyContent" 
                                          class="form-control @error('replyContent') is-invalid @enderror" 
                                          rows="3" 
                                          placeholder="{{ __('forum.write_reply') }}"></textarea>
                                @error('replyContent')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="mt-2">
                                    <button type="submit" class="btn btn-sm btn-primary">
                                        <i class="ph ph-paper-plane-tilt me-1"></i>{{ __('forum.post_reply') }}
                                    </button>
                                    <button type="button" wire:click="toggleReplyForm" class="btn btn-sm btn-light-secondary">
                                        {{ __('forum.cancel') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Nested Replies --}}
    @if($comment->replies->isNotEmpty())
        <div class="replies mt-2">
            @foreach($comment->replies as $reply)
                @livewire('forum.forum-comment', ['comment' => $reply], key('comment-'.$reply->id))
            @endforeach
        </div>
    @endif
</div>
