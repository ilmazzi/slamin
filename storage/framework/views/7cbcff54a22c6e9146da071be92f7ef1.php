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

unset($__defined_vars, $__key, $__value); ?>

<?php
    $viewCount = $content->views_count ?? $content->view_count ?? 0;
    $contentType = strtolower(class_basename($content));

    // Assicurati che il tipo sia supportato dal controller
    $supportedTypes = ['video', 'photo', 'poem', 'article', 'event'];
    if (!in_array($contentType, $supportedTypes)) {
        $contentType = 'video'; // fallback
    }
?>

<div class="post-icon social-view-counter"
     data-content-type="<?php echo e($contentType); ?>"
     data-content-id="<?php echo e($content->id); ?>"
     style="display: flex; flex-direction: column; align-items: center; gap: 4px;">
    <i class="ti ti-eye f-s-30"></i>
    <p class="text-secondary view-count"><?php echo e(number_format($viewCount)); ?></p>
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

            // Incrementa le visualizzazioni solo se il tipo è supportato
            const supportedTypes = ['video', 'photo', 'poem', 'article', 'event'];
            if (!supportedTypes.includes(contentType)) {
                console.warn('Tipo di contenuto non supportato per le visualizzazioni:', contentType);
                return;
            }

            fetch('/api/social/views/increment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
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
                    // Aggiorna il contatore
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
}
</script>
<?php /**PATH C:\xampp\htdocs\slamin\resources\views/components/social-view-counter.blade.php ENDPATH**/ ?>