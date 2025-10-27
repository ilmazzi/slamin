<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="ph-duotone ph-pencil me-2 text-warning"></i>
                        {{ __('groups.edit_group') }}: {{ $group->name }}
                    </h4>
                </div>
                <div class="card-body">
                    <form wire:submit="save">
                        <!-- Nome del gruppo -->
                        <div class="mb-3">
                            <label for="name" class="form-label">
                                <i class="ph-duotone ph-tag me-1"></i>
                                {{ __('groups.name') }} <span class="text-danger">*</span>
                            </label>
                            <input type="text"
                                   wire:model="name"
                                   class="form-control @error('name') is-invalid @enderror"
                                   id="name"
                                   placeholder="{{ __('groups.group_name_placeholder') }}"
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Descrizione -->
                        <div class="mb-3">
                            <label for="description" class="form-label">
                                <i class="ph-duotone ph-text-aa me-1"></i>
                                {{ __('groups.description') }}
                            </label>
                            <textarea wire:model="description"
                                      class="form-control @error('description') is-invalid @enderror"
                                      id="description"
                                      rows="4"
                                      placeholder="{{ __('groups.group_description_placeholder') }}"></textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Immagine del gruppo -->
                        <div class="mb-3">
                            <label for="image" class="form-label">
                                <i class="ph-duotone ph-image me-1"></i>
                                {{ __('groups.image') }}
                            </label>

                            <!-- Immagine attuale -->
                            @if($existingImage && !$removeExistingImage)
                            <div class="mb-3">
                                <label class="form-label">{{ __('groups.current_image') }}:</label>
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('storage/' . $existingImage) }}"
                                         alt="{{ $group->name }}"
                                         class="rounded me-3 image-thumbnail">
                                    <button type="button" class="btn btn-danger btn-sm" wire:click="removeExistingImageConfirm">
                                        <i class="ph-duotone ph-trash me-1"></i>
                                        {{ __('groups.remove_image') }}
                                    </button>
                                </div>
                            </div>
                            @endif

                            <input type="file"
                                   wire:model="image"
                                   class="form-control @error('image') is-invalid @enderror"
                                   id="image"
                                   accept="image/*">
                            <div class="form-text">
                                {{ __('groups.image_help_text') }}
                            </div>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror

                            <!-- Anteprima nuova immagine -->
                            @if ($image)
                                <div class="mt-3">
                                    <div class="card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h6 class="mb-0">
                                                <i class="ph-duotone ph-eye me-1"></i>
                                                {{ __('groups.new_image_preview') }}
                                            </h6>
                                            <button type="button" class="btn btn-sm btn-danger" wire:click="removeImage">
                                                <i class="ph-duotone ph-x"></i>
                                            </button>
                                        </div>
                                        <div class="card-body text-center">
                                            <img src="{{ $image->temporaryUrl() }}" alt="{{ __('groups.preview') }}" class="img-fluid rounded image-preview">
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Visibilità -->
                        <div class="mb-4">
                            <label for="visibility" class="form-label">
                                <i class="ph-duotone ph-eye me-1"></i>
                                {{ __('groups.visibility') }} <span class="text-danger">*</span>
                            </label>
                            <select wire:model="visibility"
                                    class="form-select @error('visibility') is-invalid @enderror"
                                    id="visibility"
                                    required>
                                <option value="">{{ __('groups.select_visibility') }}</option>
                                <option value="public">{{ __('groups.visibility_public') }}</option>
                                <option value="private">{{ __('groups.visibility_private') }}</option>
                            </select>
                            <div class="form-text">
                                <strong>{{ __('groups.visibility_public') }}:</strong>
                                {{ __('groups.tips.public_visibility') }}
                            </div>
                            <div class="form-text">
                                <strong>{{ __('groups.visibility_private') }}:</strong>
                                {{ __('groups.tips.private_visibility') }}
                            </div>
                            @error('visibility')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Social Links -->
                        <div class="mb-4">
                            <h6 class="mb-3">
                                <i class="ph-duotone ph-share-network me-2"></i>
                                {{ __('groups.social_links') }}
                            </h6>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="website" class="form-label">
                                        <i class="ph-duotone ph-globe me-1"></i>
                                        {{ __('groups.website') }}
                                    </label>
                                    <input type="url"
                                           wire:model="website"
                                           class="form-control @error('website') is-invalid @enderror"
                                           id="website"
                                           placeholder="{{ __('groups.website_placeholder') }}">
                                    @error('website')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="social_facebook" class="form-label">
                                        <i class="ph-duotone ph-facebook-logo me-1"></i>
                                        {{ __('groups.facebook') }}
                                    </label>
                                    <input type="url"
                                           wire:model="social_facebook"
                                           class="form-control @error('social_facebook') is-invalid @enderror"
                                           id="social_facebook"
                                           placeholder="{{ __('groups.facebook_placeholder') }}">
                                    @error('social_facebook')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="social_instagram" class="form-label">
                                        <i class="ph-duotone ph-instagram-logo me-1"></i>
                                        {{ __('groups.instagram') }}
                                    </label>
                                    <input type="url"
                                           wire:model="social_instagram"
                                           class="form-control @error('social_instagram') is-invalid @enderror"
                                           id="social_instagram"
                                           placeholder="{{ __('groups.instagram_placeholder') }}">
                                    @error('social_instagram')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="social_youtube" class="form-label">
                                        <i class="ph-duotone ph-youtube-logo me-1"></i>
                                        {{ __('groups.youtube') }}
                                    </label>
                                    <input type="url"
                                           wire:model="social_youtube"
                                           class="form-control @error('social_youtube') is-invalid @enderror"
                                           id="social_youtube"
                                           placeholder="{{ __('groups.youtube_placeholder') }}">
                                    @error('social_youtube')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="social_twitter" class="form-label">
                                        <i class="ph-duotone ph-twitter-logo me-1"></i>
                                        {{ __('groups.twitter') }}
                                    </label>
                                    <input type="url"
                                           wire:model="social_twitter"
                                           class="form-control @error('social_twitter') is-invalid @enderror"
                                           id="social_twitter"
                                           placeholder="{{ __('groups.twitter_placeholder') }}">
                                    @error('social_twitter')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="social_tiktok" class="form-label">
                                        <i class="ph-duotone ph-tiktok-logo me-1"></i>
                                        {{ __('groups.tiktok') }}
                                    </label>
                                    <input type="url"
                                           wire:model="social_tiktok"
                                           class="form-control @error('social_tiktok') is-invalid @enderror"
                                           id="social_tiktok"
                                           placeholder="{{ __('groups.tiktok_placeholder') }}">
                                    @error('social_tiktok')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="social_linkedin" class="form-label">
                                        <i class="ph-duotone ph-linkedin-logo me-1"></i>
                                        {{ __('groups.linkedin') }}
                                    </label>
                                    <input type="url"
                                           wire:model="social_linkedin"
                                           class="form-control @error('social_linkedin') is-invalid @enderror"
                                           id="social_linkedin"
                                           placeholder="{{ __('groups.linkedin_placeholder') }}">
                                    @error('social_linkedin')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-text">
                                <i class="ph-duotone ph-info me-1"></i>
                                {{ __('groups.social_links_help') }}
                            </div>
                        </div>

                        <!-- Pulsanti -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                <i class="ph-duotone ph-check me-2"></i>
                                <span wire:loading.remove>{{ __('groups.update') }}</span>
                                <span wire:loading>{{ __('groups.updating') }}</span>
                            </button>
                            <a href="{{ route('groups.show', $group) }}" class="btn btn-secondary">
                                <i class="ph-duotone ph-arrow-left me-2"></i>
                                {{ __('groups.cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

