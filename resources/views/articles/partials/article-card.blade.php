<div class="card h-100">
    @if($article->featured_image)
        <img src="{{ Storage::url($article->featured_image) }}"
             class="card-img-top" style="height: 200px; object-fit: cover;"
             alt="{{ $article->title }}">
    @endif
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div>
                @if($article->category)
                    <span class="badge" style="background-color: {{ $article->category->color }}">
                        {{ $article->category->name }}
                    </span>
                @endif
                @if($article->featured)
                    <span class="badge bg-warning ms-1">{{ __('articles.featured') }}</span>
                @endif
            </div>
            @if(isset($position) && auth()->check() && auth()->user()->hasPermissionTo('articles.manage_layout'))
                <button class="btn btn-sm btn-outline-secondary" onclick="editLayoutPosition('{{ $position }}', {{ $article->id }})">
                    <i class="ti ti-edit"></i>
                </button>
            @endif
        </div>

        <h5 class="card-title">
            <a href="{{ route('articles.show', $article) }}" class="text-decoration-none">
                {{ $article->title }}
            </a>
        </h5>

        @if($article->excerpt)
            <p class="card-text text-muted">{{ Str::limit($article->excerpt, 120) }}</p>
        @endif

        <div class="d-flex align-items-center text-muted mb-3">
            <small>{{ __('articles.by') }}
                <a href="{{ route('user.show', $article->user) }}" class="text-decoration-none">
                    <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($article->user) }}"
                         class="rounded-circle me-1" style="width: 16px; height: 16px;"
                         alt="{{ $article->user->name }}">
                    {{ $article->user->name }}
                </a>
            </small>
            <span class="mx-2">•</span>
            <small>{{ $article->published_at->format('d/m/Y') }}</small>
            <span class="mx-2">•</span>
            <small>{{ __('articles.read_time', ['minutes' => $article->read_time]) }}</small>
        </div>

        <!-- Tag -->
        @if($article->tags->count() > 0)
            <div class="mb-3">
                @foreach($article->tags->take(3) as $tag)
                    <span class="badge bg-light text-dark me-1">{{ $tag->name }}</span>
                @endforeach
                @if($article->tags->count() > 3)
                    <small class="text-muted">+{{ $article->tags->count() - 3 }}</small>
                @endif
            </div>
        @endif

        <!-- Statistiche -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center text-muted">
                <i class="ti ti-eye me-1"></i>
                <small>{{ $article->views_count }} {{ __('articles.views') }}</small>
                <span class="mx-2">•</span>
                <i class="ti ti-message-circle me-1"></i>
                <small>{{ $article->comments_count }} {{ __('articles.comments') }}</small>
            </div>
        </div>

        <!-- Azioni social -->
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex gap-2">
                <!-- Like Button (Sistema Unificato) -->
                <x-social-like-button :content="$article" type="article" />

                <!-- Commenti -->
                <a href="{{ route('articles.show', $article) }}#comments" class="btn btn-sm btn-outline-secondary">
                    <i class="ti ti-message-circle"></i>
                    {{ $article->comments_count }}
                </a>

                <!-- Report Button (Sistema Unificato) -->
                <x-report-button :content="$article" type="article" />
            </div>

            <a href="{{ route('articles.show', $article) }}" class="btn btn-primary btn-sm">
                {{ __('articles.read_more') }}
            </a>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Like functionality
    document.querySelectorAll('.like-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            if (!{{ auth()->check() ? 'true' : 'false' }}) {
                window.location.href = '{{ route('login') }}';
                return;
            }

            const articleId = this.dataset.articleId;
            const isLiked = this.dataset.liked === 'true';

            fetch(`/articles/${articleId}/likes/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update button state
                    this.dataset.liked = data.liked;
                    const icon = this.querySelector('i');
                    const count = this.querySelector('.likes-count');

                    if (data.liked) {
                        icon.classList.add('text-danger');
                    } else {
                        icon.classList.remove('text-danger');
                    }

                    count.textContent = data.likes_count;

                    // Show notification
                    showNotification(data.message, 'success');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('{{ __('articles.error_processing_request') }}', 'error');
            });
        });
    });

    // Report functionality rimossa perché ora gestita dal componente report-button
});

// Funzione showReportModal rimossa perché ora gestita dal componente report-button

// Funzione submitReport rimossa perché ora gestita dal componente report-button

function showNotification(message, type) {
    // Use SweetAlert or similar notification system
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            text: message,
            icon: type,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    } else {
        alert(message);
    }
}
</script>
@endpush
