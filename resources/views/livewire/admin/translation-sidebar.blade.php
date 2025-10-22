<div class="translation-sidebar-wrapper">
    <!-- Floating Toggle Button -->
    <button 
        wire:click="toggleSidebar"
        class="translation-toggle-btn"
        title="Traduzioni Pagina"
        style="position: fixed; bottom: 20px; right: 20px; z-index: 9999; width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; box-shadow: 0 4px 15px rgba(0,0,0,0.3); cursor: pointer; transition: all 0.3s ease; display: flex; align-items: center; justify-content: center;">
        <i class="ph-duotone ph-translate" style="font-size: 28px; color: white;"></i>
    </button>
    
    <!-- Sidebar Overlay -->
    @if($isOpen)
    <div 
        wire:click="toggleSidebar"
        style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 10000; backdrop-filter: blur(2px);">
    </div>
    @endif
    
    <!-- Sidebar -->
    <div 
        class="translation-sidebar"
        style="position: fixed; top: 0; right: 0; height: 100vh; width: 450px; background: white; box-shadow: -4px 0 20px rgba(0,0,0,0.2); z-index: 10001; transform: translateX({{ $isOpen ? '0' : '100%' }}); transition: transform 0.3s ease; display: flex; flex-direction: column;">
        
        <!-- Header -->
        <div style="padding: 20px; border-bottom: 1px solid #e9ecef; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-1" style="color: white;">
                        <i class="ph-duotone ph-translate me-2"></i>
                        Traduzioni Pagina
                    </h5>
                    <small style="color: rgba(255,255,255,0.8);">
                        Lingua: {{ strtoupper($currentLocale) }} · {{ count($filteredTranslations) }} chiavi
                    </small>
                </div>
                <button 
                    wire:click="toggleSidebar"
                    class="btn btn-link text-white p-0"
                    style="font-size: 24px; line-height: 1;">
                    <i class="ph-duotone ph-x"></i>
                </button>
            </div>
        </div>
        
        <!-- Search -->
        <div style="padding: 15px; border-bottom: 1px solid #e9ecef; background: #f8f9fa;">
            <input 
                type="text" 
                wire:model.live.debounce.300ms="searchTerm"
                class="form-control form-control-sm"
                placeholder="🔍 Cerca traduzione..."
                style="border-radius: 20px;">
        </div>
        
        <!-- Translations List -->
        <div style="flex: 1; overflow-y: auto; padding: 15px;">
            @if(count($filteredTranslations) > 0)
                @foreach($filteredTranslations as $key => $translation)
                <div class="translation-item mb-3 p-3" 
                     style="background: #f8f9fa; border-radius: 8px; border-left: 3px solid #667eea;">
                    
                    <!-- Translation Key -->
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <code style="font-size: 11px; color: #667eea; background: white; padding: 2px 8px; border-radius: 4px;">
                            {{ $translation['key'] }}
                        </code>
                        @if($editingKey !== $key)
                        <button 
                            wire:click="edit('{{ $key }}')"
                            class="btn btn-sm btn-link p-0"
                            title="Modifica">
                            <i class="ph-duotone ph-pencil-simple" style="color: #667eea;"></i>
                        </button>
                        @endif
                    </div>
                    
                    <!-- Value Display/Edit -->
                    @if($editingKey === $key)
                        <div>
                            <textarea 
                                wire:model="editingValue"
                                class="form-control form-control-sm mb-2"
                                rows="3"
                                style="font-size: 13px; resize: vertical;"
                                autofocus></textarea>
                            
                            <div class="d-flex gap-2">
                                <button 
                                    wire:click="save"
                                    class="btn btn-success btn-sm flex-grow-1"
                                    wire:loading.attr="disabled">
                                    <span wire:loading.remove wire:target="save">
                                        <i class="ph-duotone ph-check me-1"></i>
                                        Salva
                                    </span>
                                    <span wire:loading wire:target="save">
                                        <span class="spinner-border spinner-border-sm me-1"></span>
                                        Salvataggio...
                                    </span>
                                </button>
                                <button 
                                    wire:click="cancelEdit"
                                    class="btn btn-secondary btn-sm">
                                    <i class="ph-duotone ph-x"></i>
                                </button>
                            </div>
                        </div>
                    @else
                        <div style="font-size: 14px; color: #495057; line-height: 1.5; word-break: break-word;">
                            {{ $translation['value'] }}
                        </div>
                    @endif
                </div>
                @endforeach
            @else
                <div class="text-center text-muted py-5">
                    <i class="ph-duotone ph-magnifying-glass f-s-48 mb-3" style="opacity: 0.3;"></i>
                    <p class="mb-0">Nessuna traduzione trovata</p>
                </div>
            @endif
        </div>
        
        <!-- Footer -->
        <div style="padding: 15px; border-top: 1px solid #e9ecef; background: #f8f9fa; text-align: center;">
            <small class="text-muted">
                <i class="ph-duotone ph-info me-1"></i>
                Le modifiche vengono salvate nei file di lingua
            </small>
        </div>
    </div>
    
    <!-- Styles -->
    <style>
    .translation-toggle-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
    }
    
    .translation-item:hover {
        background: #e9ecef !important;
    }
    
    /* Scrollbar personalizzata */
    .translation-sidebar > div:nth-child(4)::-webkit-scrollbar {
        width: 8px;
    }
    
    .translation-sidebar > div:nth-child(4)::-webkit-scrollbar-track {
        background: #f1f1f1;
    }
    
    .translation-sidebar > div:nth-child(4)::-webkit-scrollbar-thumb {
        background: #667eea;
        border-radius: 10px;
    }
    
    .translation-sidebar > div:nth-child(4)::-webkit-scrollbar-thumb:hover {
        background: #764ba2;
    }
    </style>
    
    @script
    <script>
    // Ascolta eventi di aggiornamento traduzione
    Livewire.on('translationUpdated', (event) => {
        const key = event.key;
        const newValue = event.value;
        
        // Trova tutti gli elementi con quella traduzione e aggiornali
        document.querySelectorAll(`[data-i18n-key="${key}"]`).forEach(el => {
            el.textContent = newValue;
            // Visual feedback
            el.style.transition = 'all 0.3s ease';
            el.style.backgroundColor = '#d4edda';
            setTimeout(() => {
                el.style.backgroundColor = '';
            }, 1000);
        });
        
        console.log('Translation updated:', key, newValue);
    });
    
    // Notifiche
    Livewire.on('notify', (event) => {
        const type = event.type;
        const message = event.message;
        
        // Usa Toastify se disponibile
        if (typeof Toastify !== 'undefined') {
            Toastify({
                text: message,
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: type === 'success' ? "#28a745" : "#dc3545",
            }).showToast();
        } else {
            alert(message);
        }
    });
    </script>
    @endscript
</div>
