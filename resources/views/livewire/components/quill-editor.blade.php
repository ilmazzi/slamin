<div class="quill-editor-container" 
     x-data="quillEditor()" 
     x-init="init()"
     wire:key="quill-editor-{{ $editorId }}">
     
    <!-- Quill Editor Container -->
    <div id="{{ $editorId }}" 
         class="quill-editor" 
         style="height: {{ $height }};"
         wire:ignore></div>
    
    <!-- Hidden textarea for form submission -->
    @if($wireModel)
        <textarea wire:model="{{ $wireModel }}" 
                  style="display: none;"></textarea>
    @endif
</div>

<style>
.quill-editor-container {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    overflow: hidden;
}

.quill-editor .ql-toolbar {
    border-bottom: 1px solid #dee2e6;
    background-color: #f8f9fa;
}

.quill-editor .ql-container {
    border: none;
    font-family: inherit;
}

.quill-editor .ql-editor {
    padding: 1rem;
    line-height: 1.6;
}

.quill-editor .ql-editor.ql-blank::before {
    color: #6c757d;
    font-style: normal;
    left: 1rem;
    right: 1rem;
}

/* Toolbar styling */
.quill-editor .ql-toolbar .ql-formats {
    margin-right: 15px;
}

.quill-editor .ql-toolbar button {
    width: 28px;
    height: 28px;
    border-radius: 4px;
    margin: 1px;
}

.quill-editor .ql-toolbar button:hover {
    background-color: #e9ecef;
}

.quill-editor .ql-toolbar button.ql-active {
    background-color: #0d6efd;
    color: white;
}

/* Custom button styling */
.quill-editor .ql-toolbar button i {
    font-size: 14px;
}

/* Mobile optimization */
@media (max-width: 768px) {
    .quill-editor .ql-toolbar {
        padding: 0.5rem;
    }
    
    .quill-editor .ql-toolbar button {
        width: 24px;
        height: 24px;
    }
    
    .quill-editor .ql-editor {
        padding: 0.75rem;
        font-size: 16px; /* Prevent zoom on iOS */
    }
}
</style>

<script>
function quillEditor() {
    return {
        quill: null,
        isInitialized: false,

        init() {
            this.$nextTick(() => {
                this.initializeQuill();
            });
        },

        initializeQuill() {
            if (this.isInitialized) return;

            const container = document.getElementById('{{ $editorId }}');
            if (!container) return;

            // Check if Quill is available
            if (typeof Quill === 'undefined') {
                console.error('Quill is not loaded. Please include Quill.js');
                return;
            }

            const toolbarConfig = @js($this->getToolbarConfig());
            
            this.quill = new Quill(container, {
                theme: 'snow',
                placeholder: '{{ $placeholder }}',
                modules: {
                    toolbar: toolbarConfig
                }
            });

            // Set initial content
            if ('{{ $content }}') {
                this.quill.root.innerHTML = '{{ $content }}';
            }

            // Listen for content changes
            this.quill.on('text-change', () => {
                const content = this.quill.root.innerHTML;
                @this.set('content', content);
                
                // Update hidden textarea if wireModel exists
                @if($wireModel)
                    const textarea = this.$el.querySelector('textarea');
                    if (textarea) {
                        textarea.value = content;
                        textarea.dispatchEvent(new Event('input', { bubbles: true }));
                    }
                @endif
            });

            this.isInitialized = true;
        },

        setContent(content) {
            if (this.quill) {
                this.quill.root.innerHTML = content;
            }
        },

        getContent() {
            if (this.quill) {
                return this.quill.root.innerHTML;
            }
            return '';
        },

        clearContent() {
            if (this.quill) {
                this.quill.setText('');
            }
        }
    }
}

// Listen for Livewire events
document.addEventListener('livewire:init', () => {
    Livewire.on('quill-set-content', (editorId, content) => {
        const container = document.getElementById(editorId);
        if (container && container.__quill) {
            container.__quill.root.innerHTML = content;
        }
    });

    Livewire.on('quill-get-content', (editorId) => {
        const container = document.getElementById(editorId);
        if (container && container.__quill) {
            const content = container.__quill.root.innerHTML;
            // Dispatch content back to Livewire
            Livewire.dispatch('quill-content-received', { editorId, content });
        }
    });

    Livewire.on('quill-clear-content', (editorId) => {
        const container = document.getElementById(editorId);
        if (container && container.__quill) {
            container.__quill.setText('');
        }
    });

    Livewire.on('quill-content-updated', (wireModel, content) => {
        // This is handled by the component itself
    });
});
</script>
