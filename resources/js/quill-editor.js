// Quill.js Editor per Poesie
// Quill è caricato come script globale dal layout master

// Funzione per inizializzare l'editor Quill
window.initQuillEditor = function(selector, options = {}) {
    const defaultOptions = {
        theme: 'snow',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'align': ['', 'center', 'right'] }],
                ['blockquote'],
                ['link'],
                [{ 'indent': '-1'}, { 'indent': '+1' }],
                ['clean']
            ]
        },
        formats: ['header', 'bold', 'italic', 'underline', 'list', 'bullet', 'align', 'blockquote', 'link', 'indent'],
        preserveWhitespace: true
    };

    const mergedOptions = { ...defaultOptions, ...options };
    return new Quill(selector, mergedOptions);
};

// Funzione per sincronizzare Quill con un textarea
window.syncQuillWithTextarea = function(quill, textareaId) {
    const textarea = document.getElementById(textareaId);
    if (!textarea) return;

    // Sincronizza Quill con il textarea
    quill.on('text-change', function() {
        const content = quill.root.innerHTML;
        textarea.value = content;
    });

    // Carica contenuto esistente se presente
    const existingContent = textarea.value;
    if (existingContent) {
        quill.root.innerHTML = existingContent;
    }
};

// Funzione per sincronizzare tutti gli editor prima dell'invio del form
window.syncAllQuillEditors = function(formSelector) {
    const form = document.querySelector(formSelector);
    if (!form) return;

    form.addEventListener('submit', function(e) {
        // Sincronizza tutti gli editor Quill
        document.querySelectorAll('.ql-editor').forEach(function(quillElement) {
            const quillInstance = quillElement.__quill;
            if (quillInstance) {
                const textarea = quillElement.closest('.card-body, .col-12').querySelector('textarea');
                if (textarea) {
                    textarea.value = quillInstance.root.innerHTML;
                }
            }
        });
    });
};

// Esporta per uso globale
window.Quill = Quill;
