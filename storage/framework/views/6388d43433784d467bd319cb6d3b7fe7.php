<?php $__env->startSection('title', __('permissions.modify') . ' Slide Carosello'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="page-content">
    <div class="container-fluid">

        <!-- Header -->
        <div class="row m-1">
            <div class="col-12">
                <h4 class="main-title"><?php echo e(__('permissions.modify')); ?> Slide Carosello</h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="">
                        <a href="<?php echo e(route('dashboard')); ?>" class="f-s-14 f-w-500">
                            <span>
                                <i class="ph-duotone ph-house f-s-16"></i> <?php echo e(__('dashboard.dashboard')); ?>

                            </span>
                        </a>
                    </li>
                    <li class="">
                        <a href="<?php echo e(route('admin.carousels.index')); ?>" class="f-s-14 f-w-500">
                            <span>
                                <i class="ph-duotone ph-images f-s-16"></i> Carosello
                            </span>
                        </a>
                    </li>
                    <li class="active">
                        <a href="#" class="f-s-14 f-w-500"><?php echo e(__('permissions.modify')); ?> Slide</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Form -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="ph-duotone ph-pencil-circle f-s-16 me-2"></i>
                            <?php echo e(__('permissions.modify')); ?> Slide: <?php echo e($carousel->title); ?>

                        </h5>
                    </div>
                    <div class="card-body">
                        <form action="<?php echo e(route('admin.carousels.update', $carousel)); ?>" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            <div class="row">
                                <div class="col-md-8">
                                    <!-- Basic Info -->
                                    <div class="mb-3">
                                        <label for="title" class="form-label">Titolo *</label>
                                        <input type="text" class="form-control" id="title" name="title"
                                               value="<?php echo e(old('title', $carousel->title)); ?>" required maxlength="255">
                                        <div class="form-text">Titolo della slide che apparirà nel carosello</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Descrizione</label>
                                        <textarea class="form-control" id="description" name="description"
                                                  rows="3" maxlength="1000"><?php echo e(old('description', $carousel->description)); ?></textarea>
                                        <div class="form-text">Descrizione opzionale che apparirà sotto il titolo</div>
                                    </div>

                                    <!-- <?php echo e(__('common.media_section')); ?> Upload -->
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Immagine</label>
                                        
                                        <?php if($carousel->isContentReference() && $carousel->content_image_url): ?>
                                            <!-- Toggle per scegliere tra foto originale e caricata -->
                                            <div class="mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="use_original_image" name="use_original_image" value="1" checked>
                                                    <label class="form-check-label" for="use_original_image">
                                                        Usa immagine originale del contenuto
                                                    </label>
                                                </div>
                                                <div class="form-text">Se attivo, usa l'immagine del contenuto originale. Se disattivo, puoi caricare una nuova immagine.</div>
                                            </div>
                                            
                                            <!-- Mostra immagine originale -->
                                            <div class="mb-3" id="original-image-preview">
                                                <small class="text-muted">Immagine originale del contenuto:</small>
                                                <img src="<?php echo e($carousel->content_image_url); ?>" alt="Original" class="img-thumbnail ms-2" style="height: 80px;">
                                            </div>
                                            
                                            <!-- Campo per caricare nuova immagine (nascosto di default) -->
                                            <div id="custom-image-upload" style="display: none;">
                                                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                                <div class="form-text">Carica una nuova immagine personalizzata</div>
                                            </div>
                                        <?php else: ?>
                                            <!-- Upload normale per contenuti non referenziati -->
                                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                            <div class="form-text">Lascia vuoto per mantenere l'immagine attuale</div>
                                        <?php endif; ?>
                                        
                                        <?php if($carousel->image_path): ?>
                                            <div class="mt-2">
                                                <small class="text-muted">Immagine attuale:</small>
                                                <img src="<?php echo e($carousel->imageUrl); ?>" alt="Current" class="img-thumbnail ms-2" style="height: 50px;">
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="mb-3">
                                        <label for="video" class="form-label"><?php echo e(__('common.video')); ?> (Opzionale)</label>
                                        <input type="file" class="form-control" id="video" name="video" accept="video/*">
                                        <div class="form-text">Lascia vuoto per mantenere il video attuale</div>
                                        <?php if($carousel->video_path): ?>
                                            <div class="mt-2">
                                                <small class="text-muted"><?php echo e(__('common.video')); ?> attuale:</small>
                                                <video class="ms-2" style="height: 50px;" controls>
                                                    <source src="<?php echo e($carousel->videoUrl); ?>" type="video/mp4">
                                                </video>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Link -->
                                    <div class="mb-3">
                                        <label for="link_url" class="form-label">URL Link</label>
                                        <input type="url" class="form-control" id="link_url" name="link_url"
                                               value="<?php echo e(old('link_url', $carousel->link_url)); ?>" placeholder="https://...">
                                        <div class="form-text">URL opzionale per il link del pulsante</div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="link_text" class="form-label">Testo Link</label>
                                        <input type="text" class="form-control" id="link_text" name="link_text"
                                               value="<?php echo e(old('link_text', $carousel->link_text)); ?>" maxlength="100" placeholder="Scopri di più">
                                        <div class="form-text">Testo del pulsante (richiede URL)</div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <!-- Settings -->
                                    <div class="card card">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0">
                                                <i class="ph-duotone ph-gear f-s-16 me-2"></i>
                                                Impostazioni
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="order" class="form-label">Ordine</label>
                                                <input type="number" class="form-control" id="order" name="order"
                                                       value="<?php echo e(old('order', $carousel->order)); ?>" min="0">
                                                <div class="form-text">Ordine di visualizzazione (0 = primo)</div>
                                            </div>

                                            <div class="mb-3">
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" id="is_active"
                                                           name="is_active" value="1" <?php echo e(old('is_active', $carousel->is_active) ? 'checked' : ''); ?>>
                                                    <label class="form-check-label" for="is_active">
                                                        Slide Attiva
                                                    </label>
                                                </div>
                                                <div class="form-text">Mostra questa slide nel carosello</div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="start_date" class="form-label">Data Inizio</label>
                                                <input type="datetime-local" class="form-control" id="start_date"
                                                       name="start_date" value="<?php echo e(old('start_date', $carousel->start_date ? $carousel->start_date->format('Y-m-d\TH:i') : '')); ?>">
                                                <div class="form-text">Quando iniziare a mostrare la slide</div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="end_date" class="form-label">Data Fine</label>
                                                <input type="datetime-local" class="form-control" id="end_date"
                                                       name="end_date" value="<?php echo e(old('end_date', $carousel->end_date ? $carousel->end_date->format('Y-m-d\TH:i') : '')); ?>">
                                                <div class="form-text">Quando smettere di mostrare la slide</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Current Status -->
                                    <div class="card card mt-3">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0">
                                                <i class="ph-duotone ph-info-circle f-s-16 me-2"></i>
                                                <?php echo e(__('invitations.status')); ?> Attuale
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-2">
                                                <strong><?php echo e(__('invitations.status')); ?>:</strong>
                                                <?php if($carousel->isCurrentlyActive()): ?>
                                                    <span class="badge bg-success ms-2">Attivo</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger ms-2">Inattivo</span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="mb-2">
                                                <strong>Ordine:</strong> <?php echo e($carousel->order); ?>

                                            </div>
                                            <div class="mb-2">
                                                <strong>Creata:</strong> <?php echo e($carousel->created_at->format('d/m/Y H:i')); ?>

                                            </div>
                                            <div class="mb-0">
                                                <strong>Aggiornata:</strong> <?php echo e($carousel->updated_at->format('d/m/Y H:i')); ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="d-flex justify-content-between">
                                        <a href="<?php echo e(route('admin.carousels.index')); ?>" class="btn btn-secondary hover-effect">
                                            <i class="ph-duotone ph-arrow-left f-s-16 me-2"></i>
                                            Annulla
                                        </a>
                                        <button type="submit" class="btn btn-success hover-effect">
                                            <i class="ph-duotone ph-check-circle f-s-16 me-2"></i>
                                            Aggiorna Slide
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
(function() {
    'use strict';
    
    // Gestione toggle per immagine originale
    const useOriginalImageToggle = document.getElementById('use_original_image');
    const originalImagePreview = document.getElementById('original-image-preview');
    const customImageUpload = document.getElementById('custom-image-upload');
    
    if (useOriginalImageToggle && originalImagePreview && customImageUpload) {
        useOriginalImageToggle.addEventListener('change', function() {
            if (this.checked) {
                // Usa immagine originale
                originalImagePreview.style.display = 'block';
                customImageUpload.style.display = 'none';
                // Disabilita il campo file
                const fileInput = customImageUpload.querySelector('input[type="file"]');
                if (fileInput) {
                    fileInput.disabled = true;
                    fileInput.value = '';
                }
            } else {
                // Usa immagine personalizzata
                originalImagePreview.style.display = 'none';
                customImageUpload.style.display = 'block';
                // Abilita il campo file
                const fileInput = customImageUpload.querySelector('input[type="file"]');
                if (fileInput) {
                    fileInput.disabled = false;
                }
            }
        });
        
        // Inizializza lo stato
        useOriginalImageToggle.dispatchEvent(new Event('change'));
    }
    
})();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/admin/carousels/edit.blade.php ENDPATH**/ ?>