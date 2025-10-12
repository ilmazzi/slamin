<div>
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="ph ph-plus-circle me-2"></i>{{ __('forum.create_post') }}
            </h5>
        </div>
        <div class="card-body">
            <form wire:submit.prevent="createPost">
                {{-- Subreddit Selection --}}
                <div class="mb-4">
                    <label for="subreddit_id" class="form-label">
                        {{ __('forum.select_subreddit') }} <span class="text-danger">*</span>
                    </label>
                    <select wire:model.live="subreddit_id" 
                            id="subreddit_id" 
                            class="form-select @error('subreddit_id') is-invalid @enderror"
                            {{ $selectedSubreddit ? 'disabled' : '' }}>
                        <option value="">{{ __('forum.choose_subreddit') }}</option>
                        @foreach($subreddits as $subreddit)
                            <option value="{{ $subreddit->id }}">r/{{ $subreddit->name }}</option>
                        @endforeach
                    </select>
                    @error('subreddit_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Post Type Selection --}}
                <div class="mb-4">
                    <label class="form-label">{{ __('forum.post_type') }} <span class="text-danger">*</span></label>
                    <div class="btn-group w-100" role="group">
                        <input type="radio" wire:model.live="postType" value="text" class="btn-check" id="type-text" autocomplete="off" checked>
                        <label class="btn btn-light-primary" for="type-text">
                            <i class="ph ph-text-aa me-2"></i>{{ __('forum.text_post') }}
                        </label>

                        <input type="radio" wire:model.live="postType" value="link" class="btn-check" id="type-link" autocomplete="off">
                        <label class="btn btn-light-primary" for="type-link">
                            <i class="ph ph-link me-2"></i>{{ __('forum.link_post') }}
                        </label>

                        <input type="radio" wire:model.live="postType" value="image" class="btn-check" id="type-image" autocomplete="off">
                        <label class="btn btn-light-primary" for="type-image">
                            <i class="ph ph-image me-2"></i>{{ __('forum.image_post') }}
                        </label>
                    </div>
                </div>

                {{-- Title --}}
                <div class="mb-4">
                    <label for="title" class="form-label">
                        {{ __('forum.title') }} <span class="text-danger">*</span>
                    </label>
                    <input type="text" wire:model="title" 
                           id="title" 
                           class="form-control @error('title') is-invalid @enderror" 
                           placeholder="{{ __('forum.enter_title') }}"
                           maxlength="300">
                    <div class="form-text">{{ __('forum.title_hint') }}</div>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Text Content (for text posts) --}}
                @if($postType === 'text')
                    <div class="mb-4">
                        <label for="content" class="form-label">
                            {{ __('forum.content') }} <span class="text-danger">*</span>
                        </label>
                        <textarea wire:model="content" 
                                  id="content" 
                                  class="form-control @error('content') is-invalid @enderror" 
                                  rows="10" 
                                  placeholder="{{ __('forum.write_content') }}"></textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endif

                {{-- URL (for link posts) --}}
                @if($postType === 'link')
                    <div class="mb-4">
                        <label for="url" class="form-label">
                            {{ __('forum.url') }} <span class="text-danger">*</span>
                        </label>
                        <input type="url" wire:model="url" 
                               id="url" 
                               class="form-control @error('url') is-invalid @enderror" 
                               placeholder="https://example.com">
                        @error('url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endif

                {{-- Image Upload (for image posts) --}}
                @if($postType === 'image')
                    <div class="mb-4">
                        <label for="image" class="form-label">
                            {{ __('forum.image') }} <span class="text-danger">*</span>
                        </label>
                        <input type="file" wire:model="image" 
                               id="image" 
                               class="form-control @error('image') is-invalid @enderror" 
                               accept="image/*">
                        <div class="form-text">{{ __('forum.image_hint') }}</div>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        {{-- Image Preview --}}
                        @if($image)
                            <div class="mt-3">
                                <img src="{{ $image->temporaryUrl() }}" class="img-fluid rounded" style="max-height: 300px;">
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Submit Buttons --}}
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="createPost">
                            <i class="ph ph-paper-plane-tilt me-2"></i>{{ __('forum.post') }}
                        </span>
                        <span wire:loading wire:target="createPost">
                            <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                            {{ __('forum.posting') }}...
                        </span>
                    </button>
                    <a href="{{ $selectedSubreddit ? route('forum.subreddit.show', $selectedSubreddit->slug) : route('forum.index') }}" 
                       class="btn btn-light-secondary">
                        {{ __('forum.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
