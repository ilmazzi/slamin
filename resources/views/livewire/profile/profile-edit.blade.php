<div>
    <!-- Profile Preview Card -->
    <div class="card mb-4 overflow-hidden border-0 shadow">
        <div class="position-relative" style="height: 200px; background: rgba(var(--primary), 1);">
            @if($bannerPreview)
                <img src="{{ $bannerPreview }}" alt="Banner" class="w-100 h-100 position-absolute top-0 start-0" style="object-fit: cover; opacity: 0.9;">
            @elseif($user->banner_image)
                <img src="{{ $user->getBannerImageUrlAttribute() }}" alt="Banner" class="w-100 h-100 position-absolute top-0 start-0" style="object-fit: cover; opacity: 0.9;">
            @endif
            
            <!-- Avatar Preview -->
            <div class="position-absolute bottom-0 start-50 translate-middle-x" style="margin-bottom: -60px;">
                @if($avatarPreview)
                    <img src="{{ $avatarPreview }}" alt="Avatar" class="rounded-circle border border-4 border-white shadow" style="width: 120px; height: 120px; object-fit: cover;">
                @else
                    <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($user) }}" alt="Avatar" class="rounded-circle border border-4 border-white shadow" style="width: 120px; height: 120px; object-fit: cover;">
                @endif
            </div>
        </div>
        
        <div class="card-body text-center pt-5 pb-3" style="margin-top: 60px;">
            <h4 class="fw-bold mb-1">{{ $user->getDisplayName() }}</h4>
            <p class="text-muted f-s-14 mb-3">{{ __('profile.edit_your_profile_info') }}</p>
            
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Edit Form Card -->
    <div class="card shadow">
        <div class="card-header bg-white border-bottom border-3 border-primary">
            <h5 class="mb-0 fw-bold">
                <i class="ph ph-pencil-simple me-2 text-primary"></i>
                {{ __('profile.edit_profile') }}
            </h5>
        </div>
        
        <div class="card-body">
            <form wire:submit.prevent="save">
                <!-- Avatar and Banner Upload Section -->
                <div class="row mb-4">
                    <div class="col-12 mb-3">
                        <h6 class="f-w-600 mb-3">
                            <i class="ph ph-image-square me-2 text-primary"></i>
                            {{ __('profile.images') }}
                        </h6>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">
                            <i class="ph ph-user-circle me-1 text-primary"></i>
                            {{ __('profile.avatar') }}
                        </label>
                        <input type="file" class="form-control @error('avatar') is-invalid @enderror" wire:model="avatar" accept="image/*">
                        <small class="text-muted f-s-11">JPG, PNG, WEBP - Max 2MB</small>
                        @error('avatar') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        @if($avatarPreview || $user->avatar)
                            <button type="button" class="btn btn-sm btn-outline-danger mt-2" wire:click="removeAvatar">
                                <i class="ph ph-trash me-1"></i>{{ __('profile.remove') }}
                            </button>
                        @endif
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">
                            <i class="ph ph-image me-1 text-primary"></i>
                            {{ __('profile.banner') }}
                        </label>
                        <input type="file" class="form-control @error('banner') is-invalid @enderror" wire:model="banner" accept="image/*">
                        <small class="text-muted f-s-11">JPG, PNG, WEBP - Max 5MB</small>
                        @error('banner') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        @if($bannerPreview || $user->banner)
                            <button type="button" class="btn btn-sm btn-outline-danger mt-2" wire:click="removeBanner">
                                <i class="ph ph-trash me-1"></i>{{ __('profile.remove') }}
                            </button>
                        @endif
                    </div>
                </div>
                
                <hr class="my-4">

                <!-- Basic Information -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="f-w-600 mb-3">{{ __('profile.basic_information') }}</h6>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">{{ __('profile.name') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" wire:model="name" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="nickname" class="form-label">{{ __('profile.nickname') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nickname') is-invalid @enderror" 
                               id="nickname" wire:model="nickname" required>
                        @error('nickname') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">{{ __('profile.email') }} <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" wire:model="email" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">{{ __('profile.phone') }}</label>
                        <input type="tel" class="form-control @error('phone') is-invalid @enderror" 
                               id="phone" wire:model="phone">
                        @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="location" class="form-label">{{ __('profile.location') }}</label>
                        <input type="text" class="form-control @error('location') is-invalid @enderror" 
                               id="location" wire:model="location">
                        @error('location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="birth_date" class="form-label">{{ __('profile.birth_date') }}</label>
                        <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                               id="birth_date" wire:model="birth_date">
                        @error('birth_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="website" class="form-label">{{ __('profile.website') }}</label>
                        <input type="url" class="form-control @error('website') is-invalid @enderror" 
                               id="website" wire:model="website" placeholder="https://example.com">
                        @error('website') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-12 mb-3">
                        <label for="bio" class="form-label">{{ __('profile.bio') }}</label>
                        <textarea class="form-control @error('bio') is-invalid @enderror" 
                                  id="bio" wire:model="bio" rows="4" 
                                  placeholder="{{ __('profile.bio_placeholder') }}"></textarea>
                        @error('bio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Social Links -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="f-w-600 mb-3">{{ __('profile.social_links') }}</h6>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="social_facebook" class="form-label">
                            <i class="ph ph-facebook-logo me-1"></i>Facebook
                        </label>
                        <input type="url" class="form-control @error('social_facebook') is-invalid @enderror" 
                               id="social_facebook" wire:model="social_facebook" placeholder="https://facebook.com/username">
                        @error('social_facebook') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="social_instagram" class="form-label">
                            <i class="ph ph-instagram-logo me-1"></i>Instagram
                        </label>
                        <input type="url" class="form-control @error('social_instagram') is-invalid @enderror" 
                               id="social_instagram" wire:model="social_instagram" placeholder="https://instagram.com/username">
                        @error('social_instagram') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="social_twitter" class="form-label">
                            <i class="ph ph-twitter-logo me-1"></i>Twitter
                        </label>
                        <input type="url" class="form-control @error('social_twitter') is-invalid @enderror" 
                               id="social_twitter" wire:model="social_twitter" placeholder="https://twitter.com/username">
                        @error('social_twitter') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="social_youtube" class="form-label">
                            <i class="ph ph-youtube-logo me-1"></i>YouTube
                        </label>
                        <input type="url" class="form-control @error('social_youtube') is-invalid @enderror" 
                               id="social_youtube" wire:model="social_youtube" placeholder="https://youtube.com/channel/...">
                        @error('social_youtube') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="social_linkedin" class="form-label">
                            <i class="ph ph-linkedin-logo me-1"></i>LinkedIn
                        </label>
                        <input type="url" class="form-control @error('social_linkedin') is-invalid @enderror" 
                               id="social_linkedin" wire:model="social_linkedin" placeholder="https://linkedin.com/in/username">
                        @error('social_linkedin') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <!-- Privacy Settings -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="f-w-600 mb-3">{{ __('profile.privacy_settings') }}</h6>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_public" wire:model="is_public">
                            <label class="form-check-label" for="is_public">
                                {{ __('profile.public_profile') }}
                            </label>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="show_email" wire:model="show_email">
                            <label class="form-check-label" for="show_email">
                                {{ __('profile.show_email') }}
                            </label>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="show_phone" wire:model="show_phone">
                            <label class="form-check-label" for="show_phone">
                                {{ __('profile.show_phone') }}
                            </label>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="show_birth_date" wire:model="show_birth_date">
                            <label class="form-check-label" for="show_birth_date">
                                {{ __('profile.show_birth_date') }}
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="ph ph-check me-1"></i>{{ __('profile.save') }}
                    </button>
                    <a href="{{ route('profile.show') }}" class="btn btn-secondary">
                        <i class="ph ph-x me-1"></i>{{ __('profile.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>


