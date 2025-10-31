<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <h4 class="mb-0">
                        <i class="ph ph-users me-2 text-primary"></i>
                        {{ __('groups.community') }}
                    </h4>
                    @can('groups.create')
                    <a href="{{ route('groups.create') }}" class="btn btn-primary">
                        <i class="ph ph-plus me-2"></i>
                        {{ __('groups.create_group') }}
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Switcher -->
    <div class="row mb-3">
        <div class="col-12">
            <div class="card">
                <div class="card-body p-2">
                    <div class="btn-group w-100" role="group">
                        <button type="button" 
                                class="btn {{ $activeTab === 'groups' ? 'btn-primary' : 'btn-light-primary' }}" 
                                wire:click="switchTab('groups')">
                            <i class="ph ph-users me-2"></i>
                            {{ __('groups.groups') }}
                        </button>
                        <button type="button" 
                                class="btn {{ $activeTab === 'users' ? 'btn-primary' : 'btn-light-primary' }}" 
                                wire:click="switchTab('users')">
                            <i class="ph ph-user me-2"></i>
                            {{ __('groups.users') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- GROUPS TAB -->
    @if($activeTab === 'groups')
    <div>
        <!-- Filtri Gruppi -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Ricerca -->
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="ph ph-magnifying-glass"></i>
                                    </span>
                                    <input type="text"
                                           wire:model.live.debounce.300ms="groupSearch"
                                           class="form-control"
                                           placeholder="{{ __('groups.search_placeholder') }}">
                                </div>
                            </div>

                            <!-- Filtro -->
                            <div class="col-md-4">
                                <select wire:model.live="groupFilter" class="form-select">
                                    <option value="">{{ __('groups.filter_all') }}</option>
                                    <option value="my_groups">{{ __('groups.filter_my_groups') }}</option>
                                    <option value="my_admin_groups">{{ __('groups.filter_my_admin_groups') }}</option>
                                    <option value="public">{{ __('groups.filter_public') }}</option>
                                    @if(auth()->user()->hasRole('admin'))
                                    <option value="private">{{ __('groups.filter_private') }}</option>
                                    @endif
                                </select>
                            </div>

                            <!-- Clear -->
                            <div class="col-md-2">
                                <button wire:click="clearGroupFilters" class="btn btn-secondary w-100">
                                    <i class="ph ph-arrow-clockwise me-1"></i>
                                    {{ __('groups.reset_filters') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista Gruppi -->
        <div class="row">
            @forelse($groups as $group)
            <div class="col-12 col-md-6 col-lg-4 mb-3">
                <div class="card hover-effect h-100">
                    <div class="card-body">
                        <!-- Header gruppo -->
                        <div class="d-flex align-items-start mb-3">
                            <div class="flex-shrink-0">
                                @if($group->image)
                                    <div class="rounded-circle overflow-hidden d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 60px; min-width: 60px; min-height: 60px;">
                                        <img src="{{ asset('storage/' . $group->image) }}"
                                             alt="{{ $group->name }}"
                                             class="w-100 h-100 img-cover">
                                    </div>
                                @else
                                    <div class="bg-light-primary rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 60px; min-width: 60px; min-height: 60px;">
                                        <i class="ph-duotone ph-users text-primary f-s-24"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1 ms-3">
                                <h6 class="mb-1 f-w-600">
                                    <a href="{{ route('groups.show', $group) }}" class="text-dark text-decoration-none">
                                        {{ $group->name }}
                                    </a>
                                </h6>
                                <small class="text-muted">
                                    <i class="ph ph-users me-1"></i>
                                    {{ $group->members_count ?? $group->members->count() }} {{ __('groups.members') }}
                                </small>
                            </div>
                        </div>

                        <!-- Descrizione -->
                        @if($group->description)
                        <p class="text-muted f-s-14 mb-3">
                            {{ Str::limit($group->description, 100) }}
                        </p>
                        @endif

                        <!-- Footer -->
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-{{ $group->visibility === 'public' ? 'success' : 'warning' }}">
                                {{ $group->visibility === 'public' ? __('groups.visibility_public') : __('groups.visibility_private') }}
                            </span>
                            <a href="{{ route('groups.show', $group) }}" class="btn btn-primary btn-sm">
                                {{ __('groups.view') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="ph-duotone ph-users text-muted f-s-48"></i>
                    <h6 class="text-muted mt-3">{{ __('groups.no_groups') }}</h6>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Paginazione Gruppi -->
        @if($groups->hasPages())
        <div class="row mt-3">
            <div class="col-12">
                {{ $groups->links() }}
            </div>
        </div>
        @endif
    </div>
    @endif

    <!-- USERS TAB -->
    @if($activeTab === 'users')
    <div>
        <!-- Filtri Utenti -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row g-3">
                            <!-- Ricerca -->
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="ph ph-magnifying-glass"></i>
                                    </span>
                                    <input type="text"
                                           wire:model.live.debounce.300ms="userSearch"
                                           class="form-control"
                                           placeholder="{{ __('groups.search_users_placeholder') }}">
                                </div>
                            </div>

                            <!-- Filtro -->
                            <div class="col-md-4">
                                <select wire:model.live="userFilter" class="form-select">
                                    <option value="">{{ __('groups.filter_all') }}</option>
                                    <option value="poets">{{ __('groups.filter_poets') }}</option>
                                    <option value="organizers">{{ __('groups.filter_organizers') }}</option>
                                    <option value="active">{{ __('groups.filter_active_users') }}</option>
                                </select>
                            </div>

                            <!-- Clear -->
                            <div class="col-md-2">
                                <button wire:click="clearUserFilters" class="btn btn-secondary w-100">
                                    <i class="ph ph-arrow-clockwise me-1"></i>
                                    {{ __('groups.reset_filters') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lista Utenti -->
        <div class="row">
            @forelse($users as $user)
            <div class="col-12 col-md-6 col-lg-4 mb-3">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="profile-container cursor-pointer" onclick="window.location.href='{{ route('user.show', $user->id) }}'">
                            <!-- Banner e Avatar -->
                            <div class="image-details">
                                <div class="profile-image">
                                    <img src="{{ $user->banner_image_url ?? asset('assets/images/avatar/default-banner.webp?v=1') }}"
                                        alt="{{ $user->name }}" class="w-100 h-100 img-cover">
                                </div>
                                <div class="profile-pic">
                                    <div class="avatar-upload">
                                        <div class="avatar-preview">
                                            <div id="imgPreview">
                                                <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($user) }}"
                                                    alt="{{ $user->name }}" class="w-100 h-100 img-cover">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Dettagli Utente -->
                            <div class="person-details">
                                <h4 class="f-w-600 mb-1">{{ $user->name }}
                                    @if ($user->nickname)
                                        <span class="text-muted f-s-14 fw-normal">({{ $user->nickname }})</span>
                                    @endif
                                </h4>
                                <p class="f-s-12 mb-3">{{ $user->city ?? __('groups.location_not_specified') }}</p>
                                
                                <!-- Statistiche -->
                                <div class="details">
                                    <div>
                                        <h4 class="text-primary">{{ $user->poems_count ?? 0 }}</h4>
                                        <p class="text-secondary f-s-12">{{ __('groups.user_poems') }}</p>
                                    </div>
                                    <div>
                                        <h4 class="text-primary">{{ $user->articles_count ?? 0 }}</h4>
                                        <p class="text-secondary f-s-12">{{ __('groups.user_articles') }}</p>
                                    </div>
                                    <div>
                                        <h4 class="text-primary">{{ $user->total_interactions ?? 0 }}</h4>
                                        <p class="text-secondary f-s-12">{{ __('groups.user_interactions') }}</p>
                                    </div>
                                </div>
                                
                                <!-- Pulsanti Azioni -->
                                <div class="d-flex justify-content-center gap-2">
                                    @auth
                                        <button type="button"
                                            class="btn {{ $user->is_followed_by_current_user ?? false ? 'btn-success' : 'btn-primary' }} btn-sm"
                                            onclick="event.stopPropagation(); followUser({{ $user->id }})"
                                            id="followBtn{{ $user->id }}">
                                            <i class="ti {{ $user->is_followed_by_current_user ?? false ? 'ti-user-check' : 'ti-user' }} me-1"></i>
                                            <span id="followText{{ $user->id }}">{{ $user->is_followed_by_current_user ?? false ? __('groups.following') : __('groups.follow') }}</span>
                                        </button>
                                        <button type="button"
                                            class="btn btn-secondary btn-sm"
                                            onclick="event.stopPropagation(); startChat({{ $user->id }})"
                                            id="messageBtn{{ $user->id }}">
                                            <i class="ti ti-message-circle me-1"></i>
                                            <span>{{ __('groups.send_message') }}</span>
                                        </button>
                                    @else
                                        <div class="btn btn-secondary btn-sm opacity-60">
                                            <i class="ti ti-user me-1"></i>
                                            {{ __('groups.follow') }}
                                        </div>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="ph-duotone ph-user text-muted f-s-48"></i>
                    <h6 class="text-muted mt-3">{{ __('groups.no_users') }}</h6>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Paginazione Utenti -->
        @if($users->hasPages())
        <div class="row mt-3">
            <div class="col-12">
                {{ $users->links() }}
            </div>
        </div>
        @endif
    </div>
    @endif
</div>
