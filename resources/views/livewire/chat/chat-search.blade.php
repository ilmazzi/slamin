<div class="chat-search-container" wire:key="chat-search">
    <!-- Search Input -->
    <div class="search-input-container position-relative">
        <div class="input-group">
            <span class="input-group-text">
                <i class="ti ti-search"></i>
            </span>
            <input type="text" 
                   class="form-control" 
                   placeholder="{{ __('chat.search_users') }}"
                   wire:model.live="searchQuery"
                   @keydown.escape="resetSearch()">
            @if($searchQuery)
                <button type="button" 
                        class="btn btn-outline-secondary"
                        wire:click="resetSearch()"
                        title="{{ __('chat.clear_search') }}">
                    <i class="ti ti-x"></i>
                </button>
            @endif
        </div>

        <!-- Loading Spinner -->
        @if($isSearching)
            <div class="position-absolute top-50 end-0 translate-middle-y me-3">
                <div class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">{{ __('chat.searching') }}</span>
                </div>
            </div>
        @endif
    </div>

    <!-- Search Results -->
    @if($searchResults && count($searchResults) > 0)
        <div class="search-results mt-3" 
             x-data="chatSearch()" 
             x-init="init()"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100">
            
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-bottom py-2">
                    <h6 class="mb-0">
                        <i class="ti ti-users me-2"></i>
                        {{ __('chat.search_results') }}
                        <span class="badge bg-primary ms-2">{{ count($searchResults) }}</span>
                    </h6>
                </div>
                
                <div class="list-group list-group-flush">
                    @foreach($searchResults as $user)
                        <div class="list-group-item list-group-item-action d-flex align-items-center py-3"
                             wire:key="user-{{ $user['id'] }}">
                            
                            <!-- User Avatar -->
                            <div class="me-3">
                                <img src="{{ $user['avatar_url'] }}" 
                                     alt="{{ $user['name'] }}" 
                                     class="rounded-circle" 
                                     style="width: 40px; height: 40px;">
                            </div>
                            
                            <!-- User Info -->
                            <div class="flex-grow-1">
                                <h6 class="mb-1 f-w-500">{{ $user['name'] }}</h6>
                                @if($user['nickname'])
                                    <small class="text-muted">@{{ $user['nickname'] }}</small>
                                @endif
                            </div>
                            
                            <!-- Start Chat Button -->
                            <div>
                                <button type="button" 
                                        class="btn btn-sm btn-primary"
                                        wire:click="startChat({{ $user['id'] }})"
                                        wire:loading.attr="disabled"
                                        wire:target="startChat">
                                    <span wire:loading.remove wire:target="startChat">
                                        <i class="ti ti-brand-hipchat me-1"></i>
                                        {{ __('chat.start_chat') }}
                                    </span>
                                    <span wire:loading wire:target="startChat">
                                        <div class="spinner-border spinner-border-sm me-1"></div>
                                        {{ __('chat.creating') }}
                                    </span>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @elseif($searchQuery && strlen($searchQuery) >= 2 && !$isSearching)
        <!-- No Results -->
        <div class="no-results mt-3" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100">
            
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <i class="ti ti-search f-s-48 text-muted mb-3"></i>
                    <h6 class="text-muted mb-2">{{ __('chat.no_users_found') }}</h6>
                    <p class="text-muted small mb-0">
                        {{ __('chat.no_users_found_desc') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Recent Chats (when no search) -->
    @if(empty($searchQuery))
        <div class="recent-chats mt-3">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light border-bottom py-2">
                    <h6 class="mb-0">
                        <i class="ti ti-clock me-2"></i>
                        {{ __('chat.recent_chats') }}
                    </h6>
                </div>
                
                <div class="list-group list-group-flush">
                    <!-- This would be populated with recent chat rooms -->
                    <div class="list-group-item text-center py-4">
                        <i class="ti ti-message-circle f-s-24 text-muted mb-2"></i>
                        <p class="text-muted small mb-0">{{ __('chat.no_recent_chats') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

@push('styles')
<style>
.chat-search-container {
    max-height: 70vh;
    overflow-y: auto;
}

.search-input-container {
    position: sticky;
    top: 0;
    background: white;
    z-index: 10;
    padding-bottom: 1rem;
    border-bottom: 1px solid #dee2e6;
    margin-bottom: 1rem;
}

.search-results {
    animation: slideDown 0.3s ease-out;
}

.no-results {
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.list-group-item {
    transition: all 0.2s ease;
    border: none;
    border-bottom: 1px solid #f8f9fa;
}

.list-group-item:last-child {
    border-bottom: none;
}

.list-group-item:hover {
    background-color: #f8f9fa;
    transform: translateX(5px);
}

.list-group-item-action:focus {
    background-color: #e9ecef;
    border-color: transparent;
}

/* Mobile optimization */
@media (max-width: 768px) {
    .chat-search-container {
        max-height: 60vh;
    }
    
    .list-group-item {
        padding: 0.75rem;
    }
    
    .list-group-item:hover {
        transform: none;
    }
}
</style>
@endpush

@push('scripts')
<script>
function chatSearch() {
    return {
        init() {
            // Auto-focus search input
            const searchInput = this.$el.querySelector('input[type="text"]');
            if (searchInput) {
                searchInput.focus();
            }
        }
    }
}

// Listen for Livewire events
document.addEventListener('livewire:init', () => {
    // Reset search when navigating away
    Livewire.on('resetSearch', () => {
        // This is handled by the Livewire component
    });
});
</script>
@endpush
