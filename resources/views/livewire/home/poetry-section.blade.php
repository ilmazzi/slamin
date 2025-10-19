<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            <a href="{{ route('poems.index') }}" class="text-decoration-none text-primary d-flex align-items-center">
                <i class="ph-duotone ph-book-open f-s-16 me-2"></i>
                Poesie
            </a>
        </h5>
        <div class="d-flex align-items-center">
            <span class="text-dark f-s-12 me-2 f-w-600">Nuove</span>
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" role="switch" 
                       wire:click="toggleContent('{{ $contentType === 'new' ? 'popular' : 'new' }}')"
                       {{ $contentType === 'popular' ? 'checked' : '' }}>
            </div>
            <span class="text-dark f-s-12 ms-2 f-w-600">Popolari</span>
        </div>
    </div>
    <div class="card-body">
        @if ($poems && $poems->count() > 0)
            <div class="row">
                @foreach ($poems as $poem)
                    <div class="col-12 mb-3">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-0">
                                <!-- Immagine della poesia o placeholder -->
                                <div class="position-relative">
                                    <a href="{{ route('poems.show', $poem->id) }}" class="text-decoration-none">
                                        @if ($poem->thumbnail_url)
                                            <img src="{{ $poem->thumbnail_url }}" class="w-100" alt="{{ $poem->title }}" style="height: 120px; object-fit: cover; border-radius: 8px 8px 0 0;">
                                        @else
                                            {!! \App\Helpers\PlaceholderHelper::getPoemPlaceholderHtml(0, 120, 'w-100', route('poems.show', $poem->id)) !!}
                                        @endif
                                    </a>
                                </div>
                                
                                <!-- Contenuto -->
                                <div class="p-3">
                                    <div class="d-flex align-items-start">
                                        <div class="me-3">
                                            @if ($poem->user->profile_photo_url)
                                                <img src="{{ $poem->user->profile_photo_url }}" class="rounded-circle" alt="{{ $poem->user->name }}" style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                                    <i class="ph-duotone ph-user f-s-16 text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="card-title f-s-14 f-w-600 mb-1">{{ Str::limit($poem->title, 50) }}</h6>
                                            <p class="card-text text-muted f-s-12 mb-2">
                                                <i class="ph-duotone ph-user f-s-12 me-1"></i>
                                                {{ $poem->user->name }}
                                            </p>
                                            <p class="card-text f-s-12 mb-2">{{ Str::limit(strip_tags($poem->content), 80) }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    <i class="ph-duotone ph-clock f-s-12 me-1"></i>
                                                    {{ $poem->created_at->diffForHumans() }}
                                                </small>
                                                <a href="{{ route('poems.show', $poem->id) }}" class="btn btn-sm btn-light-primary">
                                                    <i class="ph-duotone ph-eye f-s-12 me-1"></i>
                                                    Leggi
                                                </a>
                                            </div>
                                            <div class="d-flex justify-content-end mt-2">
                                                <x-social-view-counter :model="$poem" />
                                                <x-social-like-button :model="$poem" />
                                                <x-social-comment-button :model="$poem" />
                                            </div>
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
                <i class="ph-duotone ph-book-open f-s-48 text-muted mb-3"></i>
                <p class="text-muted">Nessuna poesia disponibile</p>
            </div>
        @endif
    </div>
</div>
