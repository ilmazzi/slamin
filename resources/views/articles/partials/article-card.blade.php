<div class="card h-100 hover-effect">
    @if($article->featured_image)
        <img src="{{ Storage::url($article->featured_image) }}"
             class="card-img-top" style="height: 180px; object-fit: cover;"
             alt="{{ $article->title }}">
    @else
        <div class="card-img-top d-flex align-items-center justify-content-center"
             style="height: 180px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
            <div class="text-center text-white">
                <i class="ph ph-newspaper f-s-32 mb-2"></i>
                <div class="f-s-14 f-w-600">{{ __('articles.article') }}</div>
            </div>
        </div>
    @endif
    <div class="card-body d-flex flex-column">
        <div class="d-flex justify-content-between align-items-start mb-2">
            <div class="d-flex flex-wrap gap-1">
                @if($article->category)
                    <span class="badge f-s-11" style="background-color: {{ $article->category->color }}">
                        {{ $article->category->name }}
                    </span>
                @endif
                @if($article->featured)
                    <span class="badge bg-warning f-s-11">{{ __('articles.featured') }}</span>
                @endif
            </div>
            @if(isset($position) && auth()->check() && auth()->user()->hasPermissionTo('articles.manage_layout'))
                <button class="btn btn-sm btn-outline-secondary" onclick="editLayoutPosition('{{ $position }}', {{ $article->id }})">
                    <i class="ti ti-edit f-s-12"></i>
                </button>
            @endif
        </div>

        <h6 class="card-title f-s-16 f-w-600 mb-2">
            <a href="{{ route('articles.show', $article) }}" class="text-decoration-none">
                {{ Str::limit($article->title, 60) }}
            </a>
        </h6>

        @if($article->excerpt)
            <p class="card-text text-muted f-s-13 mb-3 flex-grow-1">{{ Str::limit($article->excerpt, 100) }}</p>
        @endif

        <div class="d-flex flex-wrap align-items-center text-muted mb-3 f-s-12">
            <span class="me-2">{{ __('articles.by') }}
                <a href="{{ route('user.show', $article->user) }}" class="text-decoration-none">
                    <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($article->user) }}"
                         class="rounded-circle me-1" style="width: 14px; height: 14px;"
                         alt="{{ $article->user->name }}">
                    {{ Str::limit($article->user->name, 15) }}
                </a>
            </span>
            <span class="mx-1">•</span>
            <span>{{ $article->published_at->format('d/m/Y') }}</span>
            <span class="mx-1">•</span>
            <span>{{ __('articles.read_time', ['minutes' => $article->read_time]) }}</span>
        </div>

        <!-- Tag - Mobile-First -->
        @if($article->tags->count() > 0)
            <div class="mb-3">
                <div class="d-flex flex-wrap gap-1">
                    @foreach($article->tags->take(2) as $tag)
                        <span class="badge bg-light text-dark f-s-11">{{ $tag->name }}</span>
                    @endforeach
                    @if($article->tags->count() > 2)
                        <small class="text-muted f-s-11">+{{ $article->tags->count() - 2 }}</small>
                    @endif
                </div>
            </div>
        @endif

        <!-- Statistiche - Mobile-First -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="d-flex align-items-center text-muted f-s-12">
                <i class="ti ti-eye me-1"></i>
                <span>{{ $article->views_count }}</span>
                <span class="mx-2">•</span>
                <i class="ti ti-message-circle me-1"></i>
                <span>{{ $article->comments_count }}</span>
            </div>
        </div>

        <!-- Azioni social - Mobile-First -->
        <div class="d-flex justify-content-between align-items-center mt-auto">
            <div class="d-flex gap-1">
                <!-- Like Button (Sistema Unificato) -->
                <x-social-like-button :content="$article" type="article" />

                <!-- Commenti (Sistema Unificato) -->
                <x-social-comment-button :content="$article" type="article" />

                <!-- Report Button (Sistema Unificato) -->
                <x-report-button :content="$article" type="article" />
            </div>

            <div class="d-flex gap-1">
                <a href="{{ route('articles.show', $article) }}" class="btn btn-primary btn-sm">
                    {{ __('articles.read_more') }}
                </a>
                
                @auth
                @endauth
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mobile-First Card Interactions
    const articleCards = document.querySelectorAll('.card.hover-effect');

    articleCards.forEach(card => {
        // Add touch-friendly interactions for mobile
        if ('ontouchstart' in window) {
            card.addEventListener('touchstart', function() {
                this.style.transform = 'scale(0.98)';
            });

            card.addEventListener('touchend', function() {
                this.style.transform = 'scale(1)';
            });
        }
    });

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
});

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
