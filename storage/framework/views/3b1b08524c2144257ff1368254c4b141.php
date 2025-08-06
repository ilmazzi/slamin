 

<?php $__env->startSection('main-content'); ?>
    <div class="container">
        <h1>Test Broadcast</h1>
        <button id="broadcast-btn">Invia evento</button>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.getElementById('broadcast-btn').addEventListener('click', function () {
        fetch('/api/send-broadcast').then(() => {
            console.log("Evento inviato!");
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/broadcast-test.blade.php ENDPATH**/ ?>