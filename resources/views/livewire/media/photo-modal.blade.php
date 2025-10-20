<div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);" wire:click.self="$parent.closePhotoModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ph-duotone ph-image me-2"></i>
                    {{ $this->photo->title }}
                </h5>
                <button type="button" class="btn-close" wire:click="$parent.closePhotoModal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Photo -->
                    <div class="col-lg-8">
                        <div class="position-relative">
                            <img src="{{ $this->photo->image_url }}" 
                                 class="img-fluid rounded" 
                                 alt="{{ $this->photo->title }}" 
                                 style="max-height: 500px; width: 100%; object-fit: contain;">
                        </div>
                        
                        <!-- Photo Info -->
                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h4 class="mb-1">{{ $this->photo->title }}</h4>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $this->photo->user->profile_photo_url }}" 
                                             class="rounded-circle me-2" 
                                             alt="{{ $this->photo->user->name }}" 
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                        <div>
                                            <h6 class="mb-0">{{ $this->photo->user->name }}</h6>
                                            <small class="text-muted">{{ $this->photo->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <livewire:social.social-view-counter :model="$this->photo" :size="'md'" />
                                    <livewire:social.social-like-button :model="$this->photo" :size="'md'" />
                                    <livewire:social.social-comment-button :model="$this->photo" :size="'md'" />
                                </div>
                            </div>
                            
                            @if($this->photo->description)
                                <div class="mb-3">
                                    <p class="mb-0">{{ $this->photo->description }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Comments -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="ph-duotone ph-chat-circle me-2"></i>
                                    Commenti ({{ $this->photo->comments()->where('status', 'approved')->count() }})
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="p-3" style="max-height: 400px; overflow-y: auto;">
                                    <!-- Comments List -->
                                    <div class="comments-list mb-3">
                                        @forelse($this->photo->comments()->where('status', 'approved')->with('user')->latest()->take(10)->get() as $comment)
                                            <div class="d-flex mb-3">
                                                <div class="flex-shrink-0 me-3">
                                                    <img src="{{ $comment->user->profile_photo_url }}" 
                                                         class="rounded-circle" 
                                                         alt="{{ $comment->user->name }}" 
                                                         style="width: 32px; height: 32px; object-fit: cover;">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                                        <h6 class="mb-0 f-s-12 f-w-600">{{ $comment->user->name }}</h6>
                                                        <small class="text-muted f-s-10">{{ $comment->created_at->diffForHumans() }}</small>
                                                    </div>
                                                    <p class="mb-0 f-s-12">{{ $comment->content }}</p>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-center py-3">
                                                <i class="ph-duotone ph-chat-circle f-s-24 text-muted mb-2"></i>
                                                <p class="text-muted f-s-12 mb-0">Nessun commento ancora</p>
                                            </div>
                                        @endforelse
                                    </div>
                                    
                                    <!-- Add Comment Form -->
                                    @auth
                                        <div class="border-top pt-3">
                                            <form wire:submit.prevent="addComment">
                                                <div class="mb-2">
                                                    <textarea class="form-control form-control-sm" 
                                                              wire:model="newComment" 
                                                              rows="2" 
                                                              placeholder="Aggiungi un commento..." 
                                                              maxlength="500"
                                                              required></textarea>
                                                </div>
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <i class="ph-duotone ph-paper-plane-right me-1"></i>
                                                    Invia
                                                </button>
                                            </form>
                                        </div>
                                    @else
                                        <div class="alert alert-info alert-sm">
                                            <i class="ph-duotone ph-info me-1"></i>
                                            <a href="{{ route('login') }}" class="text-decoration-none">Accedi</a> per commentare.
                                        </div>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
