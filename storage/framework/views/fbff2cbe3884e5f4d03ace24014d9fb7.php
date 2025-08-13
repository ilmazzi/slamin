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
    $isLiked = auth()->check() ? $content->isLikedBy(auth()->user()) : false;
    $likeCount = $content->like_count ?? 0;
    $contentType = strtolower(class_basename($content));
?>

<?php if(auth()->check()): ?>
<div class="social-like-btn"
     data-content-type="<?php echo e($contentType); ?>"
     data-content-id="<?php echo e($content->id); ?>"
     onclick="toggleSocialLike(this)"
     title="<?php echo e($isLiked ? 'Rimuovi like' : 'Metti like'); ?>"
     style="cursor: pointer; display: flex; flex-direction: column; align-items: center; gap: 2px; padding: 8px; border-radius: 8px; transition: all 0.2s;"
     onmouseover="this.style.backgroundColor='rgba(0,0,0,0.05)'"
     onmouseout="this.style.backgroundColor='transparent'">
    <img src="<?php echo e(asset('assets/images/like.png')); ?>" alt="Like" style="width: 24px; height: 24px; <?php echo e($isLiked ? 'filter: brightness(0) saturate(100%) invert(27%) sepia(51%) saturate(2878%) hue-rotate(346deg) brightness(104%) contrast(97%);' : 'filter: brightness(0) saturate(100%) invert(60%) sepia(0%) saturate(0%) hue-rotate(0deg) brightness(89%) contrast(86%);'); ?>">
    <span class="text-secondary like-count f-s-12"><?php echo e(number_format($likeCount)); ?></span>
</div>
<?php else: ?>
<div class="social-like-counter"
     style="display: flex; flex-direction: column; align-items: center; gap: 2px; padding: 8px; border-radius: 8px;">
    <img src="<?php echo e(asset('assets/images/like.png')); ?>" alt="Like" style="width: 24px; height: 24px; filter: brightness(0) saturate(100%) invert(60%) sepia(0%) saturate(0%) hue-rotate(0deg) brightness(89%) contrast(86%); opacity: 0.6;">
    <span class="text-secondary like-count f-s-12"><?php echo e(number_format($likeCount)); ?></span>
</div>
<?php endif; ?>

<script>
function toggleSocialLike(button) {
    const contentType = button.dataset.contentType;
    const contentId = button.dataset.contentId;
    const likeCountSpan = button.querySelector('.like-count');
    const heartIcon = button.querySelector('img');

    // Verifica se l'utente è autenticato
    const isAuthenticated = <?php echo e(auth()->check() ? 'true' : 'false'); ?>;

    if (!isAuthenticated) {
        // Reindirizza al login
        window.location.href = '<?php echo e(route("login")); ?>';
        return;
    }

    // Disabilita il pulsante durante la richiesta
    button.style.pointerEvents = 'none';

    // Ottieni il token CSRF
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '<?php echo e(csrf_token()); ?>';
    
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
<?php /**PATH C:\xampp\htdocs\slamin\resources\views/components/social-like-button.blade.php ENDPATH**/ ?>