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
            <small>{{ __('articles.by') }} {{ $article->user->name }}</small>
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
                <!-- Like -->
                <button class="btn btn-sm btn-outline-primary like-btn" 
                        data-article-id="{{ $article->id }}"
                        data-liked="{{ auth()->check() && $article->isLikedBy(auth()->user()) ? 'true' : 'false' }}">
                    <i class="ti ti-heart {{ auth()->check() && $article->isLikedBy(auth()->user()) ? 'text-danger' : '' }}"></i>
                    <span class="likes-count">{{ $article->likes_count }}</span>
                </button>

                <!-- Commenti -->
                <a href="{{ route('articles.show', $article) }}#comments" class="btn btn-sm btn-outline-secondary">
                    <i class="ti ti-message-circle"></i>
                    {{ $article->comments_count }}
                </a>

                <!-- Segnala -->
                @if(auth()->check())
                    <button class="btn btn-sm btn-outline-warning report-btn" 
                            data-article-id="{{ $article->id }}"
                            data-reported="{{ auth()->check() && $article->isReportedByUser(auth()->user()) ? 'true' : 'false' }}">
                        <i class="ti ti-flag"></i>
                    </button>
                @endif
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

    // Report functionality
    document.querySelectorAll('.report-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const articleId = this.dataset.articleId;
            const isReported = this.dataset.reported === 'true';

            if (isReported) {
                showNotification('{{ __('articles.already_reported') }}', 'warning');
                return;
            }

            // Show report modal
            showReportModal(articleId);
        });
    });
});

function showReportModal(articleId) {
    const modal = `
        <div class="modal fade" id="reportModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('articles.report_article') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <form id="reportForm">
                            <div class="mb-3">
                                <label class="form-label">{{ __('articles.report_reason') }}</label>
                                <select name="reason" class="form-select" required>
                                    <option value="">{{ __('articles.select_reason') }}</option>
                                    <option value="spam">{{ __('articles.spam') }}</option>
                                    <option value="inappropriate">{{ __('articles.inappropriate') }}</option>
                                    <option value="copyright">{{ __('articles.copyright') }}</option>
                                    <option value="fake_news">{{ __('articles.fake_news') }}</option>
                                    <option value="other">{{ __('articles.other') }}</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">{{ __('articles.report_description') }}</label>
                                <textarea name="description" class="form-control" rows="3" 
                                          placeholder="{{ __('articles.report_description_placeholder') }}"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('articles.cancel') }}</button>
                        <button type="button" class="btn btn-warning" onclick="submitReport(${articleId})">{{ __('articles.report') }}</button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Remove existing modal
    const existingModal = document.getElementById('reportModal');
    if (existingModal) {
        existingModal.remove();
    }

    // Add new modal
    document.body.insertAdjacentHTML('beforeend', modal);
    
    const modalElement = document.getElementById('reportModal');
    const bsModal = new bootstrap.Modal(modalElement);
    bsModal.show();

    // Clean up on hide
    modalElement.addEventListener('hidden.bs.modal', function() {
        modalElement.remove();
    });
}

function submitReport(articleId) {
    const form = document.getElementById('reportForm');
    const formData = new FormData(form);

    fetch(`/articles/${articleId}/reports`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            reason: formData.get('reason'),
            description: formData.get('description')
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update button state
            const reportBtn = document.querySelector(`[data-article-id="${articleId}"].report-btn`);
            if (reportBtn) {
                reportBtn.dataset.reported = 'true';
            }
            
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('reportModal'));
            modal.hide();
            
            showNotification(data.message, 'success');
        } else {
            showNotification(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('{{ __('articles.error_processing_request') }}', 'error');
    });
}

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
