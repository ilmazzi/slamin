@props(['inputId' => 'emoji-input', 'buttonId' => 'emoji-btn'])

<div class="emoji-picker-container position-relative">
    <!-- Emoji Button -->
    <button type="button" class="emoji-btn d-flex-center" id="{{ $buttonId }}" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Emoji" role="button">
        <i class="ti ti-mood-smile f-s-18"></i>
    </button>

    <!-- Emoji Picker Dropdown -->
    <div class="emoji-picker-dropdown d-none" id="emoji-picker-{{ $buttonId }}">
        <div class="emoji-picker-header d-flex justify-content-between align-items-center p-2 border-bottom">
            <span class="f-s-14 f-w-500">Emoji</span>
            <button type="button" class="btn-close btn-close-sm" onclick="closeEmojiPicker('{{ $buttonId }}')"></button>
        </div>

        <div class="emoji-picker-search p-2">
            <input type="text" class="form-control form-control-sm" placeholder="Cerca emoji..." onkeyup="searchEmojis(this.value, '{{ $buttonId }}')">
        </div>

        <div class="emoji-picker-content p-2" style="max-height: 300px; overflow-y: auto;">
            <div class="emoji-categories mb-2">
                <button class="btn btn-sm btn-outline-primary me-1" onclick="showEmojiCategory('smileys', '{{ $buttonId }}')">😊</button>
                <button class="btn btn-sm btn-outline-primary me-1" onclick="showEmojiCategory('animals', '{{ $buttonId }}')">🐶</button>
                <button class="btn btn-sm btn-outline-primary me-1" onclick="showEmojiCategory('food', '{{ $buttonId }}')">🍕</button>
                <button class="btn btn-sm btn-outline-primary me-1" onclick="showEmojiCategory('activities', '{{ $buttonId }}')">⚽</button>
                <button class="btn btn-sm btn-outline-primary me-1" onclick="showEmojiCategory('travel', '{{ $buttonId }}')">✈️</button>
                <button class="btn btn-sm btn-outline-primary me-1" onclick="showEmojiCategory('objects', '{{ $buttonId }}')">💡</button>
                <button class="btn btn-sm btn-outline-primary me-1" onclick="showEmojiCategory('symbols', '{{ $buttonId }}')">❤️</button>
                <button class="btn btn-sm btn-outline-primary me-1" onclick="showEmojiCategory('flags', '{{ $buttonId }}')">🏁</button>
            </div>

            <div class="emoji-grid" id="emoji-grid-{{ $buttonId }}">
                <!-- Le emoji verranno caricate qui dinamicamente -->
            </div>
        </div>
    </div>
</div>

<style>
.emoji-picker-container {
    position: relative;
}

.emoji-picker-dropdown {
    position: absolute;
    bottom: 100%;
    left: 0;
    width: 320px;
    background: white;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    z-index: 1050;
}

.emoji-grid {
    display: grid;
    grid-template-columns: repeat(8, 1fr);
    gap: 4px;
}

.emoji-item {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 20px;
    transition: background-color 0.2s;
}

.emoji-item:hover {
    background-color: #f8f9fa;
}

.emoji-categories button {
    font-size: 16px;
    padding: 4px 8px;
}

.emoji-picker-search input {
    border-radius: 20px;
    border: 1px solid #dee2e6;
}

.emoji-picker-header {
    background-color: #f8f9fa;
    border-radius: 8px 8px 0 0;
}
</style>
