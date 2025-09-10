<?php if(isset($article) && $article): ?>
<div class="article-preview-card">
    <div class="d-flex align-items-center">
        <div class="flex-shrink-0 me-3">
            <?php if($article->featured_image_url): ?>
                <img src="<?php echo e($article->featured_image_url); ?>" 
                     alt="<?php echo e($article->title); ?>" 
                     class="rounded" 
                     style="width: 60px; height: 60px; object-fit: cover;">
            <?php else: ?>
                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                     style="width: 60px; height: 60px;">
                    <i class="ph ph-newspaper text-muted"></i>
                </div>
            <?php endif; ?>
        </div>
        <div class="flex-grow-1">
            <h6 class="mb-1 f-s-14 f-w-600"><?php echo e(Str::limit($article->title, 50)); ?></h6>
            <p class="mb-1 text-muted f-s-12"><?php echo e(Str::limit($article->excerpt, 80)); ?></p>
            <div class="d-flex align-items-center gap-2">
                <small class="text-muted f-s-11">
                    <i class="ph ph-user me-1"></i><?php echo e($article->user->name ?? 'N/A'); ?>

                </small>
                <small class="text-muted f-s-11">
                    <i class="ph ph-calendar me-1"></i><?php echo e($article->published_at->format('d/m/Y')); ?>

                </small>
            </div>
        </div>
        <div class="flex-shrink-0">
            <button type="button" 
                    class="btn btn-sm btn-outline-danger" 
                    onclick="removeArticleFromPosition('<?php echo e($article->id); ?>')"
                    title="Rimuovi articolo">
                <i class="ph ph-x"></i>
            </button>
        </div>
    </div>
</div>
<?php else: ?>
<div class="text-center text-muted py-3">
    <i class="ph ph-plus-circle f-s-24 mb-2"></i>
    <p class="small mb-0">Nessun articolo selezionato</p>
</div>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\slamin\resources\views/articles/layout/article-preview.blade.php ENDPATH**/ ?>