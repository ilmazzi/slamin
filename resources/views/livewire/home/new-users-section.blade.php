<div>
    @if ($newUsers && $newUsers->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <h5 class="mb-3">
                <a href="{{ route('groups.index') }}" class="text-decoration-none text-primary d-flex align-items-center">
                    <i class="ph-duotone ph-user-plus f-s-16 me-2"></i>
                    Nuovi utenti
                </a>
            </h5>
            <div class="row">
                @foreach ($newUsers as $user)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body p-0">
                                <!-- Banner Image -->
                                <div class="position-relative">
                                    <a href="{{ route('profile.show', $user->id) }}" class="text-decoration-none">
                                        @if ($user->banner_image_url)
                                            <img src="{{ $user->banner_image_url }}" class="w-100" alt="{{ $user->name }}" style="height: 100px; object-fit: cover; border-radius: 8px 8px 0 0;">
                                        @else
                                            <div class="bg-light-primary d-flex align-items-center justify-content-center" style="height: 100px; border-radius: 8px 8px 0 0;">
                                                <i class="ph-duotone ph-image f-s-24 text-primary"></i>
                                            </div>
                                        @endif
                                    </a>
                                    
                                    <!-- Avatar -->
                                    <div class="position-absolute" style="bottom: -25px; left: 20px;">
                                        @if ($user->profile_photo_url)
                                            <img src="{{ $user->profile_photo_url }}" class="rounded-circle border border-white" alt="{{ $user->name }}" style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="rounded-circle bg-light border border-white d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                <i class="ph-duotone ph-user f-s-20 text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Content -->
                                <div class="p-3" style="margin-top: 30px;">
                                    <a href="{{ route('profile.show', $user->id) }}" class="text-decoration-none">
                                        <h5 class="f-w-600 mb-1 f-s-14 text-dark">{{ Str::limit($user->name, 18) }}</h5>
                                    </a>
                                    <p class="text-muted f-s-11 mb-3">
                                        @if($user->bio)
                                            {{ Str::limit($user->bio, 30) }}
                                        @else
                                            @php
                                                $roles = $user->getRoleNames();
                                                $roleText = $roles->isNotEmpty() ? $roles->implode(', ') : 'Utente';
                                            @endphp
                                            {{ Str::limit($roleText, 30) }}
                                        @endif
                                    </p>
                                    
                                    <!-- Statistics -->
                                    <div class="d-flex justify-content-between mb-3">
                                        <div class="text-center">
                                            <h4 class="text-primary f-s-14 f-w-600 mb-0">{{ $user->poems_count }}</h4>
                                            <p class="text-secondary f-s-9 mb-0">Poesie</p>
                                        </div>
                                        <div class="text-center">
                                            <h4 class="text-primary f-s-14 f-w-600 mb-0">{{ $user->articles_count }}</h4>
                                            <p class="text-secondary f-s-9 mb-0">Articoli</p>
                                        </div>
                                        <div class="text-center">
                                            <h4 class="text-primary f-s-14 f-w-600 mb-0">{{ $user->likes_count + $user->comments_count + $user->views_count }}</h4>
                                            <p class="text-secondary f-s-9 mb-0">Interazioni</p>
                                        </div>
                                    </div>
                                    
                                    <!-- Button -->
                                    <button class="btn btn-primary b-r-22 w-100 f-s-12" wire:click="followUser({{ $user->id }})">
                                        <i class="ph-duotone ph-user-plus f-s-10 me-1"></i>
                                        Segui
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>
