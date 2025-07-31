@props(['content', 'type' => 'content'])

@php
    $viewCount = $content->view_count ?? 0;
    $contentType = strtolower(class_basename($content));
@endphp

<div class="post-icon social-view-counter" 
     data-content-type="{{ $contentType }}"
     data-content-id="{{ $content->id }}"
     style="display: flex; flex-direction: column; align-items: center; gap: 4px;">
    <i class="ti ti-eye f-s-30"></i>
    <p class="text-secondary view-count">{{ number_format($viewCount) }}</p>
</div>

<script>
// Incrementa le visualizzazioni quando il contenuto viene visualizzato
// Usa un flag per evitare richieste multiple
if (!window.socialViewsInitialized) {
    window.socialViewsInitialized = true;
    
    document.addEventListener('DOMContentLoaded', function() {
        const viewCounters = document.querySelectorAll('.social-view-counter');
        
        viewCounters.forEach(counter => {
        const contentType = counter.dataset.contentType;
        const contentId = counter.dataset.contentId;
        const viewCountSpan = counter.querySelector('.view-count');
        
        // Incrementa le visualizzazioni
        fetch('/api/social/views/increment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                viewable_type: contentType,
                viewable_id: contentId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Aggiorna il contatore
                viewCountSpan.textContent = data.view_count.toLocaleString();
            }
        })
        .catch(error => {
            console.error('Errore incremento visualizzazioni:', error);
        });
    });
    });
}
</script> 