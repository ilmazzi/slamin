@props(['content', 'type' => 'content'])

@php
    $isLiked = $content->isLikedBy(auth()->user() ?? null);
    $likeCount = $content->like_count ?? 0;
    $contentType = strtolower(class_basename($content));
@endphp

<div class="social-like-btn" 
     data-content-type="{{ $contentType }}"
     data-content-id="{{ $content->id }}"
     onclick="toggleSocialLike(this)"
     title="{{ $isLiked ? 'Rimuovi like' : 'Metti like' }}"
     style="cursor: pointer; display: flex; flex-direction: column; align-items: center; gap: 2px; padding: 8px; border-radius: 8px; transition: all 0.2s;"
     onmouseover="this.style.backgroundColor='rgba(0,0,0,0.05)'"
     onmouseout="this.style.backgroundColor='transparent'">
    <i class="ti ti-heart f-s-24 {{ $isLiked ? 'text-primary' : 'text-muted' }}"></i>
    <span class="text-secondary like-count f-s-12">{{ number_format($likeCount) }}</span>
</div>

<script>
function toggleSocialLike(button) {
    const contentType = button.dataset.contentType;
    const contentId = button.dataset.contentId;
    const likeCountSpan = button.querySelector('.like-count');
    const heartIcon = button.querySelector('i');
    
    // Disabilita il pulsante durante la richiesta
    button.style.pointerEvents = 'none';
    
    fetch('/api/social/likes/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            likeable_type: contentType,
            likeable_id: contentId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Aggiorna l'aspetto del pulsante
            if (data.liked) {
                heartIcon.classList.remove('text-muted');
                heartIcon.classList.add('text-primary');
            } else {
                heartIcon.classList.remove('text-primary');
                heartIcon.classList.add('text-muted');
            }
            
            // Aggiorna il contatore
            likeCountSpan.textContent = data.like_count.toLocaleString();
            
                         // Nessuna notifica per i like - azione silenziosa
         } else {
             // Solo per errori gravi
             console.error('Errore like:', data.message);
         }
    })
    .catch(error => {
        console.error('Errore connessione like:', error);
    })
    .finally(() => {
        button.style.pointerEvents = 'auto';
    });
}


</script> 