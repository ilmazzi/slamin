<div>
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">
                                <i class="ph ph-shield-check text-primary me-2"></i>Coda Moderazione
                            </h4>
                            <p class="text-muted mb-0">Approva o rifiuta contenuti in attesa</p>
                        </div>
                        <a href="{{ route('forum.moderate.reports') }}" class="btn btn-light-warning">
                            <i class="ph ph-flag me-2"></i>Segnalazioni Utenti
                            @php
                                $pendingReports = \App\Models\ForumReport::where('status', 'pending')->count();
                            @endphp
                            @if($pendingReports > 0)
                                <span class="badge bg-warning ms-2">{{ $pendingReports }}</span>
                            @endif
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Filters --}}
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Subreddit</label>
                    <select wire:model.live="selectedSubreddit" class="form-select">
                        <option value="all">Tutti i Subreddit</option>
                        @foreach($moderatedSubreddits as $subreddit)
                            <option value="{{ $subreddit->id }}">r/{{ $subreddit->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Tipo Contenuto</label>
                    <div class="btn-group w-100">
                        <button wire:click="setFilter('contentType', 'all')" 
                                class="btn {{ $contentType === 'all' ? 'btn-primary' : 'btn-light-primary' }}">
                            Tutti
                        </button>
                        <button wire:click="setFilter('contentType', 'posts')" 
                                class="btn {{ $contentType === 'posts' ? 'btn-primary' : 'btn-light-primary' }}">
                            Post
                        </button>
                        <button wire:click="setFilter('contentType', 'comments')" 
                                class="btn {{ $contentType === 'comments' ? 'btn-primary' : 'btn-light-primary' }}">
                            Commenti
                        </button>
                    </div>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Stato</label>
                    <div class="btn-group w-100">
                        <button wire:click="setFilter('actionFilter', 'pending')" 
                                class="btn {{ $actionFilter === 'pending' ? 'btn-warning' : 'btn-light-warning' }}">
                            In Attesa
                        </button>
                        <button wire:click="setFilter('actionFilter', 'approved')" 
                                class="btn {{ $actionFilter === 'approved' ? 'btn-success' : 'btn-light-success' }}">
                            Approvati
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Pending Posts --}}
    @if(($contentType === 'all' || $contentType === 'posts') && $posts->count() > 0)
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="ph ph-article me-2"></i>Post ({{ $posts->count() }})
                </h5>
            </div>
            <div class="card-body p-0">
                @foreach($posts as $post)
                    <div class="border-bottom p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="flex-grow-1">
                                <div class="mb-2">
                                    <span class="badge bg-light-primary text-primary">r/{{ $post->subreddit->name }}</span>
                                    <span class="text-muted small ms-2">
                                        da <strong>{{ $post->user->name }}</strong> • {{ $post->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <h6 class="mb-2">{{ $post->title }}</h6>
                                @if($post->type === 'text' && $post->content)
                                    <p class="text-muted small mb-0">{{ Str::limit(strip_tags($post->content), 200) }}</p>
                                @elseif($post->type === 'link')
                                    <a href="{{ $post->url }}" target="_blank" class="small">{{ $post->url }}</a>
                                @elseif($post->type === 'image')
                                    <img src="{{ $post->image_url }}" class="img-fluid rounded" style="max-height: 150px;">
                                @endif
                            </div>
                            @if($actionFilter === 'pending')
                                <div class="ms-3">
                                    <button wire:click="approvePost({{ $post->id }})" 
                                            class="btn btn-sm btn-success mb-1">
                                        <i class="ph ph-check"></i> Approva
                                    </button>
                                    <button wire:click="rejectPost({{ $post->id }})" 
                                            wire:confirm="Eliminare questo post?"
                                            class="btn btn-sm btn-danger">
                                        <i class="ph ph-x"></i> Rifiuta
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Pending Comments --}}
    @if(($contentType === 'all' || $contentType === 'comments') && $comments->count() > 0)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="ph ph-chat-circle me-2"></i>Commenti ({{ $comments->count() }})
                </h5>
            </div>
            <div class="card-body p-0">
                @foreach($comments as $comment)
                    <div class="border-bottom p-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="mb-2">
                                    <span class="badge bg-light-primary text-primary">r/{{ $comment->post->subreddit->name }}</span>
                                    <span class="text-muted small ms-2">
                                        da <strong>{{ $comment->user->name }}</strong> • {{ $comment->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <div class="small text-muted mb-1">
                                    in risposta a: <strong>{{ Str::limit($comment->post->title, 50) }}</strong>
                                </div>
                                <p class="mb-0">{{ Str::limit($comment->content, 200) }}</p>
                            </div>
                            @if($actionFilter === 'pending')
                                <div class="ms-3">
                                    <button wire:click="approveComment({{ $comment->id }})" 
                                            class="btn btn-sm btn-success mb-1">
                                        <i class="ph ph-check"></i> Approva
                                    </button>
                                    <button wire:click="deleteComment({{ $comment->id }})" 
                                            wire:confirm="Eliminare questo commento?"
                                            class="btn btn-sm btn-danger">
                                        <i class="ph ph-trash"></i> Elimina
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Empty State --}}
    @if($posts->count() === 0 && $comments->count() === 0)
        <div class="card">
            <div class="card-body text-center py-5">
                <i class="ph ph-check-circle f-s-48 text-success mb-3"></i>
                <h5 class="text-muted">Nessun elemento in attesa!</h5>
                <p class="text-muted">Ottimo lavoro! La coda di moderazione è vuota.</p>
            </div>
        </div>
    @endif
</div>
