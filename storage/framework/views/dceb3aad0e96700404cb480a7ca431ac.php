<?php $__env->startSection('title', 'Impostazioni Placeholder'); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h4 class="main-title">Impostazioni Placeholder</h4>
                    
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ph-duotone ph-palette text-primary me-2"></i>
                        Colori Placeholder
                    </h5>
                    <p class="text-muted mb-0">Personalizza i colori dei placeholder per poesie e articoli</p>
                </div>
                <div class="card-body">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="ph-duotone ph-check-circle me-2"></i>
                            <?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if(session('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="ph-duotone ph-warning-circle me-2"></i>
                            <?php echo e(session('error')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('admin.settings.placeholder.update')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="row">
                            <!-- Colore placeholder poesie -->
                            <div class="col-md-6 mb-4">
                                <div class="card border">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <label for="poem_placeholder_color" class="form-label f-w-500">
                                                <i class="ph-duotone ph-book-open me-2"></i>
                                                Colore Placeholder Poesie
                                            </label>
                                        </div>
                                        
                                        <!-- Anteprima colore -->
                                        <div class="mb-3">
                                            <div class="placeholder-preview" 
                                                 style="width: 100px; height: 100px; background-color: <?php echo e(old('poem_placeholder_color', $settings->poem_placeholder_color)); ?>; border-radius: 8px; margin: 0 auto; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                                <i class="ph-duotone ph-book-open f-s-24"></i>
                                            </div>
                                        </div>

                                        <div class="input-group">
                                            <input type="color" 
                                                   class="form-control form-control-color" 
                                                   id="poem_placeholder_color" 
                                                   name="poem_placeholder_color" 
                                                   value="<?php echo e(old('poem_placeholder_color', $settings->poem_placeholder_color)); ?>"
                                                   onchange="updatePreview('poem_placeholder_color', 'poem')">
                                            <input type="text" 
                                                   class="form-control" 
                                                   placeholder="#6c757d" 
                                                   value="<?php echo e(old('poem_placeholder_color', $settings->poem_placeholder_color)); ?>"
                                                   onchange="updateColorInput('poem_placeholder_color', this.value)">
                                        </div>
                                        
                                        <?php $__errorArgs = ['poem_placeholder_color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="text-danger f-s-12 mt-1"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Colore placeholder articoli -->
                            <div class="col-md-6 mb-4">
                                <div class="card border">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <label for="article_placeholder_color" class="form-label f-w-500">
                                                <i class="ph-duotone ph-newspaper me-2"></i>
                                                Colore Placeholder Articoli
                                            </label>
                                        </div>
                                        
                                        <!-- Anteprima colore -->
                                        <div class="mb-3">
                                            <div class="placeholder-preview" 
                                                 style="width: 100px; height: 100px; background-color: <?php echo e(old('article_placeholder_color', $settings->article_placeholder_color)); ?>; border-radius: 8px; margin: 0 auto; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                                <i class="ph-duotone ph-newspaper f-s-24"></i>
                                            </div>
                                        </div>

                                        <div class="input-group">
                                            <input type="color" 
                                                   class="form-control form-control-color" 
                                                   id="article_placeholder_color" 
                                                   name="article_placeholder_color" 
                                                   value="<?php echo e(old('article_placeholder_color', $settings->article_placeholder_color)); ?>"
                                                   onchange="updatePreview('article_placeholder_color', 'article')">
                                            <input type="text" 
                                                   class="form-control" 
                                                   placeholder="#007bff" 
                                                   value="<?php echo e(old('article_placeholder_color', $settings->article_placeholder_color)); ?>"
                                                   onchange="updateColorInput('article_placeholder_color', this.value)">
                                        </div>
                                        
                                        <?php $__errorArgs = ['article_placeholder_color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="text-danger f-s-12 mt-1"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="<?php echo e(route('admin.settings.index')); ?>" class="btn btn-secondary">
                                <i class="ph-duotone ph-arrow-left me-2"></i>
                                Indietro
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="ph-duotone ph-floppy-disk me-2"></i>
                                Salva Impostazioni
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="ph-duotone ph-info text-info me-2"></i>
                        Informazioni
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="f-w-500">Come funziona</h6>
                        <p class="text-muted f-s-14">
                            I colori selezionati verranno utilizzati come sfondo per i placeholder delle immagini 
                            quando poesie o articoli non hanno un'immagine associata.
                        </p>
                    </div>

                    <div class="mb-3">
                        <h6 class="f-w-500">Dove vengono utilizzati</h6>
                        <ul class="list-unstyled text-muted f-s-14">
                            <li><i class="ph-duotone ph-check-circle text-success me-2"></i>Lista poesie</li>
                            <li><i class="ph-duotone ph-check-circle text-success me-2"></i>Pagina singola poesia</li>
                            <li><i class="ph-duotone ph-check-circle text-success me-2"></i>Lista articoli</li>
                            <li><i class="ph-duotone ph-check-circle text-success me-2"></i>Pagina singola articolo</li>
                            <li><i class="ph-duotone ph-check-circle text-success me-2"></i>Caroselli</li>
                            <li><i class="ph-duotone ph-check-circle text-success me-2"></i>Ricerca contenuti</li>
                        </ul>
                    </div>

                    <div class="alert alert-info">
                        <i class="ph-duotone ph-lightbulb me-2"></i>
                        <strong>Suggerimento:</strong> Scegli colori che si abbinano bene con il design del tuo sito.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function updatePreview(colorInputId, type) {
    const colorInput = document.getElementById(colorInputId);
    const preview = colorInput.closest('.card-body').querySelector('.placeholder-preview');
    preview.style.backgroundColor = colorInput.value;
    
    // Aggiorna anche il campo di testo
    const textInput = colorInput.nextElementSibling;
    textInput.value = colorInput.value;
}

function updateColorInput(colorInputId, hexValue) {
    // Valida il formato hex
    if (/^#[0-9A-Fa-f]{6}$/.test(hexValue)) {
        const colorInput = document.getElementById(colorInputId);
        colorInput.value = hexValue;
        
        // Aggiorna l'anteprima
        const preview = colorInput.closest('.card-body').querySelector('.placeholder-preview');
        preview.style.backgroundColor = hexValue;
    }
}

// Inizializza le anteprime al caricamento della pagina
document.addEventListener('DOMContentLoaded', function() {
    updatePreview('poem_placeholder_color', 'poem');
    updatePreview('article_placeholder_color', 'article');
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/admin/settings/placeholder.blade.php ENDPATH**/ ?>