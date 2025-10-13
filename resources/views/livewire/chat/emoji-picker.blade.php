<div class="emoji-picker-container position-absolute bottom-100 start-0 mb-2" 
     x-data="emojiPicker()" 
     x-show="$wire.isOpen"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 transform scale-95"
     x-transition:enter-end="opacity-100 transform scale-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100 transform scale-100"
     x-transition:leave-end="opacity-0 transform scale-95"
     style="z-index: 1050; max-width: 320px;"
     @click.away="$wire.close()"
     wire:key="emoji-picker">

    <div class="card shadow-lg border-0">
        <!-- Header -->
        <div class="card-header bg-light border-bottom py-2">
            <div class="d-flex align-items-center justify-content-between">
                <h6 class="mb-0">{{ __('chat.emoji_picker') }}</h6>
                <button type="button" 
                        class="btn btn-sm btn-outline-secondary"
                        @click="$wire.close()">
                    <i class="ti ti-x"></i>
                </button>
            </div>
        </div>

        <!-- Search -->
        <div class="p-3 border-bottom">
            <div class="input-group input-group-sm">
                <span class="input-group-text">
                    <i class="ti ti-search"></i>
                </span>
                <input type="text" 
                       class="form-control" 
                       placeholder="{{ __('chat.search_emojis') }}"
                       wire:model.live="searchQuery"
                       @keydown.escape="$wire.close()">
            </div>
        </div>

        <!-- Categories (only show if not searching) -->
        <div class="p-2 border-bottom" x-show="!$wire.searchQuery">
            <div class="d-flex gap-1 flex-wrap">
                @foreach(['smileys', 'animals', 'food', 'activities', 'travel', 'objects', 'symbols', 'flags'] as $category)
                    <button type="button" 
                            class="btn btn-sm {{ $selectedCategory === $category ? 'btn-primary' : 'btn-outline-secondary' }}"
                            wire:click="selectCategory('{{ $category }}')"
                            data-bs-toggle="tooltip" 
                            title="{{ __('chat.categories.' . $category) }}">
                        @switch($category)
                            @case('smileys')
                                <i class="ti ti-mood-smile"></i>
                                @break
                            @case('animals')
                                <i class="ti ti-paw"></i>
                                @break
                            @case('food')
                                <i class="ti ti-apple"></i>
                                @break
                            @case('activities')
                                <i class="ti ti-ball-football"></i>
                                @break
                            @case('travel')
                                <i class="ti ti-plane"></i>
                                @break
                            @case('objects')
                                <i class="ti ti-gift"></i>
                                @break
                            @case('symbols')
                                <i class="ti ti-heart"></i>
                                @break
                            @case('flags')
                                <i class="ti ti-flag"></i>
                                @break
                        @endswitch
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Emoji Grid -->
        <div class="emoji-grid p-3" style="max-height: 300px; overflow-y: auto;">
            @if($searchQuery)
                <!-- Search Results -->
                <div class="d-flex flex-wrap gap-1">
                    @foreach($this->filteredEmojis as $emoji)
                        <button type="button" 
                                class="btn btn-sm btn-outline-secondary emoji-btn"
                                wire:click="selectEmoji('{{ $emoji }}')"
                                data-bs-toggle="tooltip" 
                                title="{{ $emoji }}"
                                style="font-size: 20px; width: 40px; height: 40px;">
                            {{ $emoji }}
                        </button>
                    @endforeach
                </div>
            @else
                <!-- Category Emojis -->
                <div class="d-flex flex-wrap gap-1">
                    @foreach($this->filteredEmojis as $emoji)
                        <button type="button" 
                                class="btn btn-sm btn-outline-secondary emoji-btn"
                                wire:click="selectEmoji('{{ $emoji }}')"
                                data-bs-toggle="tooltip" 
                                title="{{ $emoji }}"
                                style="font-size: 20px; width: 40px; height: 40px;">
                            {{ $emoji }}
                        </button>
                    @endforeach
                </div>
            @endif

            @if($searchQuery && $this->filteredEmojis->isEmpty())
                <div class="text-center text-muted py-4">
                    <i class="ti ti-search f-s-24 mb-2"></i>
                    <p class="mb-0">{{ __('chat.no_emojis_found') }}</p>
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="card-footer bg-light text-center py-2">
            <small class="text-muted">
                {{ __('chat.emoji_picker_footer') }}
            </small>
        </div>
    </div>
</div>

@push('styles')
<style>
.emoji-picker-container {
    max-width: 320px;
}

.emoji-grid {
    scrollbar-width: thin;
    scrollbar-color: #dee2e6 #f8f9fa;
}

.emoji-grid::-webkit-scrollbar {
    width: 6px;
}

.emoji-grid::-webkit-scrollbar-track {
    background: #f8f9fa;
    border-radius: 3px;
}

.emoji-grid::-webkit-scrollbar-thumb {
    background: #dee2e6;
    border-radius: 3px;
}

.emoji-grid::-webkit-scrollbar-thumb:hover {
    background: #adb5bd;
}

.emoji-btn {
    transition: all 0.2s ease;
    border: 1px solid transparent !important;
}

.emoji-btn:hover {
    background-color: #e9ecef !important;
    border-color: #adb5bd !important;
    transform: scale(1.1);
}

.emoji-btn:active {
    transform: scale(0.95);
}

/* Mobile optimization */
@media (max-width: 768px) {
    .emoji-picker-container {
        max-width: 280px;
        left: 10px !important;
        right: 10px !important;
    }
    
    .emoji-btn {
        width: 35px !important;
        height: 35px !important;
        font-size: 18px !important;
    }
    
    .emoji-grid {
        max-height: 250px;
    }
}
</style>
@endpush

@push('scripts')
<script>
function emojiPicker() {
    return {
        init() {
            // Initialize tooltips when the picker opens
            this.$watch('$wire.isOpen', (isOpen) => {
                if (isOpen) {
                    this.$nextTick(() => {
                        if (typeof bootstrap !== 'undefined') {
                            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                            tooltipTriggerList.map(function (tooltipTriggerEl) {
                                return new bootstrap.Tooltip(tooltipTriggerEl);
                            });
                        }
                    });
                }
            });
        }
    }
}

// Listen for emoji selection events
document.addEventListener('livewire:init', () => {
    Livewire.on('emoji-selected', (emoji) => {
        // Insert emoji into the message input
        const messageInput = document.querySelector('[wire\\:model\\.live="newMessage"]');
        if (messageInput) {
            const start = messageInput.selectionStart;
            const end = messageInput.selectionEnd;
            const text = messageInput.value;
            messageInput.value = text.substring(0, start) + emoji + text.substring(end);
            messageInput.selectionStart = messageInput.selectionEnd = start + emoji.length;
            messageInput.focus();
            
            // Trigger input event to update Livewire
            messageInput.dispatchEvent(new Event('input', { bubbles: true }));
        }
    });
});
</script>
@endpush
