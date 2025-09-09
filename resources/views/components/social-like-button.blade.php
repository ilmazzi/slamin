@props(['content', 'type' => 'content', 'size' => 'md'])

@php
    $isLiked = auth()->check() ? $content->isLikedBy(auth()->user()) : false;
    $likeCount = $content->like_count ?? 0;
    $contentType = strtolower(class_basename($content));

    // Dimensioni
    $sizeStyles = [
        'sm' => 'min-width: 50px; padding: 6px; gap: 2px;',
        'md' => 'min-width: 60px; padding: 8px; gap: 2px;',
        'lg' => 'min-width: 70px; padding: 10px; gap: 2px;'
    ];
    $iconSizes = [
        'sm' => 'width: 20px; height: 20px;',
        'md' => 'width: 24px; height: 24px;',
        'lg' => 'width: 28px; height: 28px;'
    ];
    $textSizes = [
        'sm' => 'f-s-10',
        'md' => 'f-s-12',
        'lg' => 'f-s-14'
    ];
    $buttonStyle = $sizeStyles[$size] ?? $sizeStyles['md'];
    $iconStyle = $iconSizes[$size] ?? $iconSizes['md'];
    $textClass = $textSizes[$size] ?? $textSizes['md'];
@endphp

@if(auth()->check())
<div class="social-like-btn"
     data-content-type="{{ $contentType }}"
     data-content-id="{{ $content->id }}"
     onclick="toggleSocialLike(this)"
     title="{{ $isLiked ? 'Rimuovi like' : 'Metti like' }}"
     style="cursor: pointer; display: flex; flex-direction: column; align-items: center; border-radius: 8px; transition: all 0.2s; {{ $buttonStyle }}"
     onmouseover="this.style.backgroundColor='rgba(0,0,0,0.05)'"
     onmouseout="this.style.backgroundColor='transparent'">
    <img src="{{ asset('assets/images/like.png') }}" alt="{{ __('common.like') }}" style="{{ $iconStyle }} {{ $isLiked ? 'filter: brightness(0) saturate(100%) invert(27%) sepia(51%) saturate(2878%) hue-rotate(346deg) brightness(104%) contrast(97%);' : 'filter: brightness(0) saturate(100%) invert(60%) sepia(0%) saturate(0%) hue-rotate(0deg) brightness(89%) contrast(86%);' }}">
    <span class="text-secondary like-count {{ $textClass }}">{{ number_format($likeCount) }}</span>
</div>
@else
<div class="social-like-counter"
     style="display: flex; flex-direction: column; align-items: center; border-radius: 8px; {{ $buttonStyle }}">
    <img src="{{ asset('assets/images/like.png') }}" alt="{{ __('common.like') }}" style="{{ $iconStyle }} filter: brightness(0) saturate(100%) invert(60%) sepia(0%) saturate(0%) hue-rotate(0deg) brightness(89%) contrast(86%); opacity: 0.6;">
    <span class="text-secondary like-count {{ $textClass }}">{{ number_format($likeCount) }}</span>
</div>
@endif

<script>
function toggleSocialLike(button) {
    const contentType = button.dataset.contentType;
    const contentId = button.dataset.contentId;
    const likeCountSpan = button.querySelector('.like-count');
    const heartIcon = button.querySelector('img');

    // Verifica se l'utente è autenticato
    const isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};

    if (!isAuthenticated) {
        // Reindirizza al login
        window.location.href = '{{ route("login") }}';
        return;
    }

    // Disabilita il pulsante durante la richiesta
    button.style.pointerEvents = 'none';

    // Ottieni il token CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

    fetch('/api/social/likes/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
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
                heartIcon.style.filter = 'brightness(0) saturate(100%) invert(27%) sepia(51%) saturate(2878%) hue-rotate(346deg) brightness(104%) contrast(97%);';
            } else {
                heartIcon.style.filter = 'brightness(0) saturate(100%) invert(60%) sepia(0%) saturate(0%) hue-rotate(0deg) brightness(89%) contrast(86%);';
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
