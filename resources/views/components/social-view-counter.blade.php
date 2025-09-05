@props(['content', 'type' => 'content'])

@php
    $viewCount = $content->views_count ?? $content->view_count ?? 0;
    $contentType = strtolower(class_basename($content));

    // Assicurati che il tipo sia supportato dal controller
    $supportedTypes = ['video', 'photo', 'poem', 'article', 'event'];
    if (!in_array($contentType, $supportedTypes)) {
        $contentType = 'video'; // fallback
    }
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
// Usa un flag per evitare richieste multiple per ogni singolo contatore
document.addEventListener('DOMContentLoaded', function() {
    const viewCounters = document.querySelectorAll('.social-view-counter:not([data-views-initialized])');

    viewCounters.forEach(counter => {
        // Marca questo contatore come inizializzato
        counter.setAttribute('data-views-initialized', 'true');

        const contentType = counter.dataset.contentType;
        const contentId = parseInt(counter.dataset.contentId);
        const viewCountSpan = counter.querySelector('.view-count');

        // Incrementa le visualizzazioni solo se il tipo è supportato
        const supportedTypes = ['video', 'photo', 'poem', 'article', 'event'];
        if (!supportedTypes.includes(contentType)) {
            console.warn('Tipo di contenuto non supportato per le visualizzazioni:', contentType);
            return;
        }

        // Verifica che contentId sia un numero valido
        if (isNaN(contentId) || contentId <= 0) {
            console.warn('ID contenuto non valido per le visualizzazioni:', contentId);
            return;
        }

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

        fetch('/api/social/views/increment', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                viewable_type: contentType,
                viewable_id: contentId
            })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Aggiorna sempre il contatore con il numero corrente
                viewCountSpan.textContent = data.view_count.toLocaleString();
            } else {
                console.warn('Incremento visualizzazioni fallito:', data.message);
            }
        })
        .catch(error => {
            console.error('Errore incremento visualizzazioni:', error);
            // Non mostrare errori all'utente, solo nel console per debug
        });
    });
});
</script>
