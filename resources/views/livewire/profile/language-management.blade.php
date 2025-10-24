<div>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <x-icon name="settings" size="20" class="me-2" />
                {{ __('languages.manage_languages') }}
            </h5>
            <button class="btn btn-primary btn-sm" wire:click="showAddForm">
                <i class="ph ph-plus me-1"></i>
                {{ __('languages.add_language') }}
            </button>
        </div>
        
        <div class="card-body">
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Form per aggiungere/modificare lingua -->
            @if($showForm)
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">
                        {{ $editingLanguage ? __('languages.edit_language') : __('languages.add_language') }}
                    </h6>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="save">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="searchLanguage" class="form-label">{{ __('languages.language_name') }}</label>
                                <div class="position-relative">
                                    <input type="text" 
                                           class="form-control @error('language_name') is-invalid @enderror" 
                                           id="searchLanguage" 
                                           wire:model.live="searchLanguage"
                                           placeholder="Cerca una lingua..." 
                                           autocomplete="off">
                                    
                                    @if($showLanguageDropdown && count($this->filteredLanguages) > 0)
                                    <div class="list-group position-absolute w-100" style="z-index: 1000; max-height: 200px; overflow-y: auto;">
                                        @foreach($this->filteredLanguages as $lang)
                                        <button type="button" 
                                                class="list-group-item list-group-item-action"
                                                wire:click="selectLanguage('{{ $lang['name'] }}', '{{ $lang['code'] }}')">
                                            <strong>{{ $lang['name'] }}</strong> 
                                            <span class="badge bg-light-primary text-primary ms-2">{{ $lang['code'] }}</span>
                                        </button>
                                        @endforeach
                                    </div>
                                    @endif
                                    
                                    @error('language_name') 
                                        <div class="invalid-feedback">{{ $message }}</div> 
                                    @enderror
                                </div>
                                
                                @if($language_name && $language_code)
                                <div class="mt-2">
                                    <span class="badge bg-success">
                                        <i class="ph ph-check me-1"></i>
                                        {{ $language_name }} ({{ $language_code }})
                                    </span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="type" class="form-label">{{ __('languages.type') }}</label>
                                <select class="form-select @error('type') is-invalid @enderror" 
                                        id="type" wire:model.live="type" required>
                                    <option value="native">{{ __('languages.native') }}</option>
                                    <option value="spoken">{{ __('languages.spoken') }}</option>
                                    <option value="written">{{ __('languages.written') }}</option>
                                </select>
                                @error('type') 
                                    <div class="invalid-feedback">{{ $message }}</div> 
                                @enderror
                                
                                @if($type === 'native')
                                <div class="alert alert-info mt-2 mb-0">
                                    <i class="ph ph-info me-2"></i>
                                    Per le lingue native non è necessario specificare il livello.
                                </div>
                                @endif
                            </div>
                            
                            @if($type !== 'native')
                            <div class="col-md-6 mb-3">
                                <label for="level" class="form-label">{{ __('languages.level') }}</label>
                                <select class="form-select @error('level') is-invalid @enderror" 
                                        id="level" wire:model="level" required>
                                    <option value="">{{ __('languages.select_level') }}</option>
                                    <option value="excellent">{{ __('languages.excellent') }}</option>
                                    <option value="good">{{ __('languages.good') }}</option>
                                    <option value="poor">{{ __('languages.poor') }}</option>
                                </select>
                                @error('level') 
                                    <div class="invalid-feedback">{{ $message }}</div> 
                                @enderror
                            </div>
                            @endif
                        </div>

                        @error('language')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                {{ $editingLanguage ? __('common.update') : __('common.add') }}
                            </button>
                            <button type="button" class="btn btn-secondary" wire:click="resetForm">
                                {{ __('common.cancel') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            <!-- Lista delle lingue -->
            @if($languages->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>{{ __('languages.language') }}</th>
                            <th>{{ __('languages.type') }}</th>
                            <th>{{ __('languages.level') }}</th>
                            <th class="text-end">{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($languages as $language)
                        <tr>
                            <td>
                                <strong>{{ $language->language_name }}</strong>
                                <small class="text-muted d-block">{{ $language->language_code }}</small>
                            </td>
                            <td>
                                <span class="badge bg-{{ $language->type === 'native' ? 'primary' : ($language->type === 'spoken' ? 'success' : 'info') }}">
                                    {{ $language->type_display }}
                                </span>
                            </td>
                            <td>
                                @if($language->level)
                                    <span class="badge bg-{{ $language->level === 'excellent' ? 'success' : ($language->level === 'good' ? 'warning' : 'danger') }}">
                                        {{ $language->level_display }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" wire:click="editLanguage({{ $language->id }})">
                                        <i class="ph ph-pencil"></i>
                                    </button>
                                    <button class="btn btn-outline-danger" 
                                            onclick="confirm('{{ __('languages.confirm_delete') }}') && @this.deleteLanguage({{ $language->id }})">
                                        <i class="ph ph-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-4">
                <i class="ph ph-globe f-s-48 text-muted mb-3"></i>
                <h6 class="text-muted">{{ __('languages.no_languages') }}</h6>
                <p class="text-muted">{{ __('languages.add_first_language') }}</p>
                <button class="btn btn-primary" wire:click="showAddForm">
                    <i class="ph ph-plus me-1"></i>
                    {{ __('languages.add_language') }}
                </button>
            </div>
            @endif
        </div>
    </div>
</div>


