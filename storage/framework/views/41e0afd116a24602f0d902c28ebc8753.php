<script>
window.SearchConfig = {
    apiUrl: '<?php echo e(route("search.api")); ?>',
    searchUrl: '<?php echo e(route("search.index")); ?>',
    csrfToken: '<?php echo e(csrf_token()); ?>'
};
</script>


<?php /**PATH C:\xampp\htdocs\slamin\resources\views/layout/search-config.blade.php ENDPATH**/ ?>