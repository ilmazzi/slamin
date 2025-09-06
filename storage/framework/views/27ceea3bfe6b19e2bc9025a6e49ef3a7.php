

<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="ph ph-shield-x f-s-48 text-danger mb-3"></i>
                        <h1 class="display-4 text-danger fw-bold">403</h1>
                        <h2 class="h3 text-dark mb-3">Accesso Negato</h2>
                        <p class="text-muted mb-4">
                            Non hai i permessi per accedere a questa risorsa. 
                            Se ritieni che questo sia un errore, contatta l'amministratore.
                        </p>
                    </div>
                    
                    <div class="d-flex justify-content-center gap-3">
                        <a href="<?php echo e(route('home')); ?>" class="btn btn-primary">
                            <i class="ph ph-house me-2"></i>Torna alla Home
                        </a>
                        <a href="<?php echo e(url()->previous()); ?>" class="btn btn-outline-secondary">
                            <i class="ph ph-arrow-left me-2"></i>Torna Indietro
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/errors/error_403.blade.php ENDPATH**/ ?>