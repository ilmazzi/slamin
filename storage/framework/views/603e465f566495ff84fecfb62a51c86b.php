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
    $viewCount = $content->view_count ?? 0;
?>

<div title="Visualizzazioni" style="display: flex; flex-direction: column; align-items: center; gap: 2px; padding: 8px; border-radius: 8px;">
    <i class="ti ti-eye f-s-24 text-muted"></i>
    <span class="text-secondary f-s-12"><?php echo e(number_format($viewCount)); ?></span>
</div> 
<?php /**PATH C:\xampp\htdocs\slamin\resources\views/components/social-view-display.blade.php ENDPATH**/ ?>