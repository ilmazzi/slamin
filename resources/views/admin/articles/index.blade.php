@extends('layout.master')

@section('main-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('admin.dashboard') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('articles.manage_articles') }}</li>
                    </ol>
                </div>
                <h4 class="page-title">{{ __('articles.manage_articles') }}</h4>
            </div>
        </div>
    </div>

    <!-- Pannello Controllo Featured -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ph ph-star me-2"></i>
                        {{ __('articles.featured_management') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="alert alert-info">
                                <i class="ph ph-info me-2"></i>
                                <strong>{{ __('articles.featured_limit_info') }}</strong><br>
                                {{ __('articles.featured_limit_description') }}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <span class="badge bg-primary f-s-16">
                                        {{ __('articles.current_featured') }}: {{ $articles->where('featured', true)->count() }}/3
                                    </span>
                                </div>
                                @if($articles->where('featured', true)->count() >= 3)
                                    <div class="alert alert-warning mb-0">
                                        <i class="ph ph-warning me-2"></i>
                                        {{ __('articles.featured_limit_reached') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">{{ __('articles.all_articles') }}</h5>
                        <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">
                            <i class="ph ph-plus me-2"></i>
                            {{ __('articles.create_article') }}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>{{ __('articles.title') }}</th>
                                    <th>{{ __('articles.author') }}</th>
                                    <th>{{ __('articles.category') }}</th>
                                    <th>{{ __('articles.status') }}</th>
                                    <th>{{ __('articles.featured') }}</th>
                                    <th>{{ __('articles.views') }}</th>
                                    <th>{{ __('articles.created_at') }}</th>
                                    <th>{{ __('admin.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($articles as $article)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    @if($article->featured_image)
                                                        <img src="{{ Storage::url($article->featured_image) }}"
                                                             class="rounded" style="width: 40px; height: 40px; object-fit: cover;"
                                                             alt="{{ $article->title }}">
                                                    @else
                                                        <div class="rounded d-flex align-items-center justify-content-center"
                                                             style="width: 40px; height: 40px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                            <i class="ph ph-newspaper text-white f-s-16"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0">{{ Str::limit($article->title, 50) }}</h6>
                                                    <small class="text-muted">{{ Str::limit($article->excerpt, 60) }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $article->user->name }}</td>
                                        <td>
                                            @if($article->category)
                                                <span class="badge bg-light text-dark">{{ $article->category->name }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @switch($article->status)
                                                @case('published')
                                                    <span class="badge bg-success">{{ __('articles.published') }}</span>
                                                    @break
                                                @case('draft')
                                                    <span class="badge bg-secondary">{{ __('articles.draft') }}</span>
                                                    @break
                                                @case('pending')
                                                    <span class="badge bg-warning">{{ __('articles.pending') }}</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ $article->status }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            @if($article->featured)
                                                <span class="badge bg-warning">
                                                    <i class="ph ph-star me-1"></i>
                                                    {{ __('articles.featured') }}
                                                </span>
                                            @else
                                                <span class="badge bg-light text-dark">
                                                    <i class="ph ph-star-off me-1"></i>
                                                    {{ __('articles.not_featured') }}
                                                </span>
                                            @endif
                                        </td>
                                        <td>{{ number_format($article->views_count) }}</td>
                                        <td>{{ $article->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('articles.show', $article) }}" class="btn btn-sm btn-primary" title="{{ __('articles.view') }}">
                                                    <i class="ph ph-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.articles.edit', $article) }}" class="btn btn-sm btn-secondary" title="{{ __('articles.edit') }}">
                                                    <i class="ph ph-pencil"></i>
                                                </a>

                                                <!-- Gestione Featured -->
                                                @if($article->featured)
                                                    <button type="button" class="btn btn-sm btn-warning feature-toggle"
                                                            data-article-id="{{ $article->id }}"
                                                            data-action="unfeature"
                                                            title="{{ __('articles.unfeature') }}">
                                                        <i class="ph ph-minus-circle"></i>
                                                    </button>
                                                @else
                                                    @if($articles->where('featured', true)->count() < 3)
                                                        <button type="button" class="btn btn-sm btn-success feature-toggle"
                                                                data-article-id="{{ $article->id }}"
                                                                data-action="feature"
                                                                title="{{ __('articles.feature') }}">
                                                            <i class="ph ph-plus-circle"></i>
                                                        </button>
                                                    @else
                                                        <button class="btn btn-sm btn-secondary" disabled title="{{ __('articles.featured_limit_reached') }}">
                                                            <i class="ph ph-star"></i>
                                                        </button>
                                                    @endif
                                                @endif

                                                <!-- Gestione Pubblicazione -->
                                                @if($article->status === 'published')
                                                    <form action="{{ route('articles.unpublish', $article) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-warning" title="{{ __('articles.unpublish') }}">
                                                            <i class="ph ph-eye-slash"></i>
                                                        </button>
                                                    </form>
                                                @else
                                                    <form action="{{ route('articles.publish', $article) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success" title="{{ __('articles.publish') }}">
                                                            <i class="ph ph-eye"></i>
                                                        </button>
                                                    </form>
                                                @endif

                                                <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" class="d-inline" onsubmit="return confirm('{{ __('articles.confirm_delete') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="{{ __('articles.delete') }}">
                                                        <i class="ph ph-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestione feature/unfeature
    document.querySelectorAll('.feature-toggle').forEach(button => {
        button.addEventListener('click', function() {
            const articleId = this.dataset.articleId;
            const action = this.dataset.action;
            const button = this;

            // Disabilita il pulsante durante la richiesta
            button.disabled = true;

            // Crea il form data
            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');

            // Determina l'URL
            let url;
            if (action === 'feature') {
                url = `/articles/${articleId}/feature`;
            } else {
                url = `/articles/${articleId}/unfeature`;
            }

            console.log('Invio richiesta:', action, 'per articolo:', articleId);

            // Esegui la richiesta
            fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                console.log('Risposta ricevuta:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Dati ricevuti:', data);

                if (data.success) {
                    console.log('Operazione completata con successo');
                    // Aggiorna la pagina per mostrare i cambiamenti
                    window.location.reload();
                } else {
                    console.log('Operazione fallita:', data.message);
                    // Mostra l'errore specifico
                    alert('Errore: ' + data.message);
                    button.disabled = false;
                }
            })
            .catch(error => {
                console.error('Errore durante la richiesta:', error);
                // Se c'è un errore di rete o altro, ricarica la pagina
                alert('Errore durante l\'operazione. La pagina verrà ricaricata.');
                window.location.reload();
            });
        });
    });
});
</script>
@endpush
