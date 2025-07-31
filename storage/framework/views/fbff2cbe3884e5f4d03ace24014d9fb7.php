<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames((['content', 'type' => 'content']));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter((['content', 'type' => 'content']), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars); ?>

<?php
    $isLiked = $content->isLikedBy(auth()->user() ?? null);
    $likeCount = $content->like_count ?? 0;
    $contentType = strtolower(class_basename($content));
?>

<div class="social-like-btn" 
     data-content-type="<?php echo e($contentType); ?>"
     data-content-id="<?php echo e($content->id); ?>"
     onclick="toggleSocialLike(this)"
     title="<?php echo e($isLiked ? 'Rimuovi like' : 'Metti like'); ?>"
     style="cursor: pointer; display: flex; flex-direction: column; align-items: center; gap: 2px; padding: 8px; border-radius: 8px; transition: all 0.2s;"
     onmouseover="this.style.backgroundColor='rgba(0,0,0,0.05)'"
     onmouseout="this.style.backgroundColor='transparent'">
    <i class="ti ti-heart f-s-24 <?php echo e($isLiked ? 'text-primary' : 'text-muted'); ?>"></i>
    <span class="text-secondary like-count f-s-12"><?php echo e(number_format($likeCount)); ?></span>
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


</script> <?php /**PATH C:\xampp\htdocs\slamin\resources\views/components/social-like-button.blade.php ENDPATH**/ ?>