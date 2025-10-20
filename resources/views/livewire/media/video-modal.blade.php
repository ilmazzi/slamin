<div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);" wire:click.self="$parent.closeVideoModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="ph-duotone ph-video me-2"></i>
                    {{ $this->video->title }}
                </h5>
                <button type="button" class="btn-close" wire:click="$parent.closeVideoModal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Video Player -->
                    <div class="col-lg-8">
                        <div class="position-relative">
                            @if($this->video->video_url)
                                <video controls class="w-100" style="max-height: 500px;">
                                    <source src="{{ $this->video->video_url }}" type="video/mp4">
                                    Il tuo browser non supporta il tag video.
                                </video>
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                                    <div class="text-center">
                                        <i class="ph-duotone ph-video f-s-48 text-muted mb-3"></i>
                                        <p class="text-muted">Video non disponibile</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Video Info -->
                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h4 class="mb-1">{{ $this->video->title }}</h4>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $this->video->user->profile_photo_url }}" 
                                             class="rounded-circle me-2" 
                                             alt="{{ $this->video->user->name }}" 
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                        <div>
                                            <h6 class="mb-0">{{ $this->video->user->name }}</h6>
                                            <small class="text-muted">{{ $this->video->created_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <livewire:social.social-view-counter :model="$this->video" :size="'md'" />
                                    <livewire:social.social-like-button :model="$this->video" :size="'md'" />
                                    <livewire:social.social-comment-button :model="$this->video" :size="'md'" />
                                </div>
                            </div>
                            
                            @if($this->video->description)
                                <div class="mb-3">
                                    <p class="mb-0">{{ $this->video->description }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Comments and Snaps -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <ul class="nav nav-tabs card-header-tabs" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#comments-tab" type="button" role="tab">
                                            <i class="ph-duotone ph-chat-circle me-1"></i>
                                            Commenti ({{ $this->video->comments()->where('status', 'approved')->count() }})
                                        </button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#snaps-tab" type="button" role="tab">
                                            <i class="ph-duotone ph-lightning me-1"></i>
                                            Snap ({{ $this->video->snaps()->count() }})
                                        </button>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body p-0">
                                <div class="tab-content">
                                    <!-- Comments Tab -->
                                    <div class="tab-pane fade show active" id="comments-tab" role="tabpanel">
                                        <div class="p-3" style="max-height: 400px; overflow-y: auto;">
                                            <!-- Comments List -->
                                            <div class="comments-list mb-3">
                                                @forelse($this->video->comments()->where('status', 'approved')->with('user')->latest()->take(10)->get() as $comment)
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
                                    
                                    <!-- Snaps Tab -->
                                    <div class="tab-pane fade" id="snaps-tab" role="tabpanel">
                                        <div class="p-3" style="max-height: 400px; overflow-y: auto;">
                                            <!-- Snaps List -->
                                            <div class="snaps-list mb-3">
                                                @forelse($this->video->snaps()->with('user')->latest()->take(10)->get() as $snap)
                                                    <div class="d-flex mb-3">
                                                        <div class="flex-shrink-0 me-3">
                                                            <img src="{{ $snap->user->profile_photo_url }}" 
                                                                 class="rounded-circle" 
                                                                 alt="{{ $snap->user->name }}" 
                                                                 style="width: 32px; height: 32px; object-fit: cover;">
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                                <h6 class="mb-0 f-s-12 f-w-600">{{ $snap->user->name }}</h6>
                                                                <small class="text-muted f-s-10">{{ $snap->created_at->diffForHumans() }}</small>
                                                            </div>
                                                            <p class="mb-0 f-s-12">{{ $snap->content }}</p>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <div class="text-center py-3">
                                                        <i class="ph-duotone ph-lightning f-s-24 text-muted mb-2"></i>
                                                        <p class="text-muted f-s-12 mb-0">Nessun snap ancora</p>
                                                    </div>
                                                @endforelse
                                            </div>
                                            
                                            <!-- Add Snap Form -->
                                            @auth
                                                <div class="border-top pt-3">
                                                    @if(!$showSnapForm)
                                                        <button class="btn btn-outline-primary btn-sm w-100" wire:click="toggleSnapForm">
                                                            <i class="ph-duotone ph-lightning me-1"></i>
                                                            Crea Snap
                                                        </button>
                                                    @else
                                                        <form wire:submit.prevent="createSnap">
                                                            <div class="mb-2">
                                                                <textarea class="form-control form-control-sm" 
                                                                          wire:model="snapContent" 
                                                                          rows="2" 
                                                                          placeholder="Crea uno snap..." 
                                                                          maxlength="500"
                                                                          required></textarea>
                                                            </div>
                                                            <div class="d-flex gap-2">
                                                                <button type="submit" class="btn btn-primary btn-sm">
                                                                    <i class="ph-duotone ph-lightning me-1"></i>
                                                                    Crea
                                                                </button>
                                                                <button type="button" class="btn btn-outline-secondary btn-sm" wire:click="toggleSnapForm">
                                                                    Annulla
                                                                </button>
                                                            </div>
                                                        </form>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="alert alert-info alert-sm">
                                                    <i class="ph-duotone ph-info me-1"></i>
                                                    <a href="{{ route('login') }}" class="text-decoration-none">Accedi</a> per creare snap.
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
    </div>
</div>
