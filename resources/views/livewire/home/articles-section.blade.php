<div class="card">
    <div class="card-header bg-gradient-warning text-white d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <a href="{{ route('articles.index') }}" class="text-decoration-none text-white d-flex align-items-center">
                <i class="ph-duotone ph-newspaper f-s-16 me-2"></i>
                Articoli
            </a>
        </h5>
        <div class="btn-group btn-group-sm" role="group">
            <button type="button" class="btn {{ $contentType === 'new' ? 'btn-light' : 'btn-outline-light' }}" 
                    wire:click="toggleContent('new')">
                Nuovi
            </button>
            <button type="button" class="btn {{ $contentType === 'popular' ? 'btn-light' : 'btn-outline-light' }}" 
                    wire:click="toggleContent('popular')">
                Popolari
            </button>
        </div>
    </div>
    <div class="card-body">
        @if ($articles && $articles->count() > 0)
            <div class="row">
                @foreach ($articles as $article)
                    <div class="col-12 mb-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-start">
                                    <div class="me-3">
                                        @if ($article->user->avatar_url)
                                            <img src="{{ $article->user->avatar_url }}" class="rounded-circle" alt="{{ $article->user->name }}" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                <i class="ph-duotone ph-user f-s-16 text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <h6 class="card-title f-s-14 f-w-600 mb-1">{{ Str::limit($article->title, 60) }}</h6>
                                        <p class="card-text text-muted f-s-12 mb-2">
                                            <i class="ph-duotone ph-user f-s-12 me-1"></i>
                                            {{ $article->user->name }}
                                        </p>
                                        <p class="card-text f-s-12 mb-2">{{ Str::limit($article->excerpt, 100) }}</p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <small class="text-muted">
                                                <i class="ph-duotone ph-clock f-s-12 me-1"></i>
                                                {{ $article->created_at->diffForHumans() }}
                                            </small>
                                            <a href="{{ route('articles.show', $article->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="ph-duotone ph-eye f-s-12 me-1"></i>
                                                Leggi
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-4">
                <i class="ph-duotone ph-newspaper f-s-48 text-muted mb-3"></i>
                <p class="text-muted">Nessun articolo disponibile</p>
            </div>
        @endif
    </div>
</div>
