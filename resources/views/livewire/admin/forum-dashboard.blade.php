<div>
    {{-- Header --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">
                                <i class="ph ph-gauge text-primary me-2"></i>Dashboard Forum
                            </h4>
                            <p class="text-muted mb-0">Panoramica generale e statistiche del forum</p>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.forum.settings') }}" class="btn btn-light-primary">
                                <i class="ph ph-gear me-2"></i>Configurazioni
                            </a>
                            <a href="{{ route('admin.forum.subreddits') }}" class="btn btn-light-success">
                                <i class="ph ph-folders me-2"></i>Gestisci Subreddit
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Statistics Cards --}}
    <div class="row mb-4">
        {{-- Subreddits --}}
        <div class="col-md-3">
            <div class="card hover-effect">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Subreddit</h6>
                            <h3 class="mb-0">{{ number_format($stats['total_subreddits']) }}</h3>
                            <small class="text-success">
                                <i class="ph ph-check-circle"></i> {{ $stats['active_subreddits'] }} attivi
                            </small>
                        </div>
                        <div class="icon-box bg-light-primary rounded">
                            <i class="ph ph-folders f-s-36 text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Posts --}}
        <div class="col-md-3">
            <div class="card hover-effect">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Post</h6>
                            <h3 class="mb-0">{{ number_format($stats['total_posts']) }}</h3>
                            <small class="text-info">
                                <i class="ph ph-trend-up"></i> {{ $stats['posts_today'] }} oggi
                            </small>
                        </div>
                        <div class="icon-box bg-light-success rounded">
                            <i class="ph ph-article f-s-36 text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Comments --}}
        <div class="col-md-3">
            <div class="card hover-effect">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Commenti</h6>
                            <h3 class="mb-0">{{ number_format($stats['total_comments']) }}</h3>
                            <small class="text-info">
                                <i class="ph ph-trend-up"></i> {{ $stats['comments_today'] }} oggi
                            </small>
                        </div>
                        <div class="icon-box bg-light-warning rounded">
                            <i class="ph ph-chat-circle f-s-36 text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Votes & Users --}}
        <div class="col-md-3">
            <div class="card hover-effect">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Utenti Attivi</h6>
                            <h3 class="mb-0">{{ number_format($stats['unique_contributors']) }}</h3>
                            <small class="text-muted">
                                <i class="ph ph-arrow-fat-up"></i> {{ number_format($stats['total_votes']) }} voti
                            </small>
                        </div>
                        <div class="icon-box bg-light-danger rounded">
                            <i class="ph ph-users f-s-36 text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Recent Posts --}}
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph ph-clock-clockwise me-2"></i>Post Recenti
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Post</th>
                                    <th>Autore</th>
                                    <th>Subreddit</th>
                                    <th>Stats</th>
                                    <th>Data</th>
                                    <th>Azioni</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentPosts as $post)
                                    <tr>
                                        <td>
                                            <div>
                                                <a href="{{ route('forum.post.show', ['subreddit' => $post->subreddit->slug, 'post' => $post->id]) }}" 
                                                   target="_blank" class="text-dark">
                                                    {{ Str::limit($post->title, 50) }}
                                                </a>
                                                @if(!$post->isApproved())
                                                    <span class="badge bg-light-warning text-warning">Pending</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('user.show', $post->user->id) }}" target="_blank">
                                                {{ $post->user->name }}
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge bg-light-primary text-primary">
                                                r/{{ $post->subreddit->name }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                <i class="ph ph-arrow-fat-up text-success"></i> {{ $post->score }}
                                                <i class="ph ph-chat-circle ms-2"></i> {{ $post->comments_count }}
                                            </small>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $post->created_at->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            <a href="{{ route('forum.post.show', ['subreddit' => $post->subreddit->slug, 'post' => $post->id]) }}" 
                                               target="_blank" 
                                               class="btn btn-sm btn-light-primary">
                                                <i class="ph ph-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            Nessun post trovato
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Top Subreddits & Pending Items --}}
        <div class="col-lg-5">
            {{-- Top Subreddits --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="ph ph-chart-line-up me-2"></i>Top Subreddit
                    </h5>
                </div>
                <div class="card-body">
                    @foreach($topSubreddits as $subreddit)
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div>
                                <a href="{{ route('forum.subreddit.show', $subreddit->slug) }}" 
                                   target="_blank" class="text-dark fw-bold">
                                    r/{{ $subreddit->name }}
                                </a>
                                <div class="small text-muted">
                                    {{ number_format($subreddit->posts_count) }} post
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-light-primary text-primary">
                                    {{ number_format($subreddit->subscribers_count) }} iscritti
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Pending Moderation --}}
            @if($stats['pending_posts'] > 0 || $stats['pending_comments'] > 0)
                <div class="card">
                    <div class="card-header bg-light-warning">
                        <h5 class="mb-0 text-warning">
                            <i class="ph ph-warning me-2"></i>In Attesa di Moderazione
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($stats['pending_posts'] > 0)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span><i class="ph ph-article me-2"></i>Post in attesa</span>
                                <span class="badge bg-light-warning text-warning">{{ $stats['pending_posts'] }}</span>
                            </div>
                        @endif
                        @if($stats['pending_comments'] > 0)
                            <div class="d-flex justify-content-between align-items-center">
                                <span><i class="ph ph-chat-circle me-2"></i>Commenti in attesa</span>
                                <span class="badge bg-light-warning text-warning">{{ $stats['pending_comments'] }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
