<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">
    <!-- Mobile-First Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
                <div>
                    <h4 class="page-title f-s-18 f-w-600 mb-2"><?php echo e(__('articles.manage_articles')); ?></h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>" class="f-s-14"><?php echo e(__('admin.dashboard')); ?></a></li>
                        <li class="breadcrumb-item active f-s-14"><?php echo e(__('articles.manage_articles')); ?></li>
                    </ol>
                </div>
                <div>
                    <a href="<?php echo e(route('admin.articles.create')); ?>" class="btn btn-primary btn-sm">
                        <i class="ph ph-plus me-2"></i>
                        <?php echo e(__('articles.create_article')); ?>

                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile-First Featured Management Panel -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card hover-effect">
                <div class="card-header">
                    <h5 class="card-title mb-0 f-s-16 f-w-600">
                        <i class="ph ph-star me-2"></i>
                        <?php echo e(__('articles.featured_management')); ?>

                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <div class="alert alert-info py-3">
                                <i class="ph ph-info me-2"></i>
                                <strong class="f-s-14"><?php echo e(__('articles.featured_limit_info')); ?></strong><br>
                                <span class="f-s-13"><?php echo e(__('articles.featured_limit_description')); ?></span>
                            </div>
                        </div>
                        <div class="col-12 col-sm-6">
                            <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center gap-2">
                                <div>
                                    <?php
                                        $featuredCount = $articles->where('featured', true)->count();
                                        $isLimitReached = $featuredCount >= 3;
                                    ?>
                                    <span class="badge <?php echo e($isLimitReached ? 'bg-warning' : 'bg-primary'); ?> f-s-14">
                                        <i class="ph ph-star me-1"></i>
                                        <?php echo e(__('articles.current_featured')); ?>: <?php echo e($featuredCount); ?>/3
                                    </span>
                                </div>
                                <?php if($isLimitReached): ?>
                                    <div class="alert alert-warning py-2 mb-0">
                                        <i class="ph ph-warning me-2"></i>
                                        <span class="f-s-13"><?php echo e(__('articles.featured_limit_reached')); ?></span>
                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-success py-2 mb-0">
                                        <i class="ph ph-check-circle me-2"></i>
                                        <span class="f-s-13"><?php echo e(__('articles.featured_slots_available', ['count' => 3 - $featuredCount])); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile-First Articles Management -->
    <div class="row">
        <div class="col-12">
            <div class="card hover-effect">
                <div class="card-header">
                    <div class="d-flex flex-column flex-sm-row justify-content-between align-items-start align-items-sm-center gap-3">
                        <h5 class="card-title mb-0 f-s-16 f-w-600"><?php echo e(__('articles.all_articles')); ?></h5>
                        <div class="d-flex gap-2">
                            <button class="btn btn-outline-secondary btn-sm" onclick="toggleFilters()">
                                <i class="ph ph-funnel me-2"></i>
                                <span class="d-none d-sm-inline">Filtri</span>
                            </button>
                            <a href="<?php echo e(route('admin.articles.create')); ?>" class="btn btn-primary btn-sm">
                                <i class="ph ph-plus me-2"></i>
                                <span class="d-none d-sm-inline"><?php echo e(__('articles.create_article')); ?></span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Mobile-First Filters Section -->
                <div class="card-body border-bottom" id="filtersSection" style="display: none;">
                    <div class="row g-3">
                        <div class="col-12 col-sm-6">
                            <label class="form-label f-s-14 f-w-500"><?php echo e(__('articles.status')); ?></label>
                            <select class="form-select form-select-sm" id="statusFilter">
                                <option value=""><?php echo e(__('common.all')); ?></option>
                                <option value="published"><?php echo e(__('articles.published')); ?></option>
                                <option value="draft"><?php echo e(__('articles.draft')); ?></option>
                                <option value="pending"><?php echo e(__('articles.pending')); ?></option>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label f-s-14 f-w-500"><?php echo e(__('articles.category')); ?></label>
                            <select class="form-select form-select-sm" id="categoryFilter">
                                <option value=""><?php echo e(__('common.all')); ?></option>
                                <?php $__currentLoopData = $categories ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label f-s-14 f-w-500"><?php echo e(__('articles.featured')); ?></label>
                            <select class="form-select form-select-sm" id="featuredFilter">
                                <option value=""><?php echo e(__('common.all')); ?></option>
                                <option value="1"><?php echo e(__('articles.featured')); ?></option>
                                <option value="0"><?php echo e(__('articles.not_featured')); ?></option>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label class="form-label f-s-14 f-w-500"><?php echo e(__('common.search')); ?></label>
                            <input type="text" class="form-control form-control-sm" id="searchFilter" placeholder="<?php echo e(__('articles.search_articles')); ?>">
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <!-- Mobile-First Articles List -->
                    <div class="list-group list-group-flush" id="articlesList">
                        <?php $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="list-group-item list-group-item-action p-0 border-0 border-bottom">
                            <div class="d-flex flex-column p-3">
                                <!-- Header Row -->
                                <div class="d-flex align-items-start gap-3 mb-3">
                                    <!-- Article Image -->
                                    <div class="flex-shrink-0">
                                        <?php if($article->featured_image): ?>
                                            <img src="<?php echo e(Storage::url($article->featured_image)); ?>"
                                                 class="rounded" style="width: 50px; height: 50px; object-fit: cover;"
                                                 alt="<?php echo e($article->title); ?>">
                                        <?php else: ?>
                                            <div class="rounded d-flex align-items-center justify-content-center"
                                                 style="width: 50px; height: 50px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                                <i class="ph ph-newspaper text-white f-s-16"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Article Title and Badges -->
                                    <div class="flex-grow-1" style="min-width: 0; max-width: calc(100% - 60px);">
                                        <h6 class="mb-1 f-s-16 f-w-600" style="word-wrap: break-word; line-height: 1.4; hyphens: auto;"><?php echo e($article->title); ?></h6>
                                        
                                        <!-- Badges Row -->
                                        <div class="d-flex flex-wrap gap-1 mb-2">
                                            <span class="badge <?php echo e($article->status === 'published' ? 'bg-success' : ($article->status === 'draft' ? 'bg-secondary' : 'bg-warning')); ?> f-s-10">
                                                <?php echo e(ucfirst($article->status)); ?>

                                            </span>
                                            
                                            <?php if($article->featured): ?>
                                            <span class="badge bg-warning f-s-10">
                                                <i class="ph ph-star me-1"></i>Featured
                                            </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Article Meta -->
                                <div class="d-flex flex-wrap gap-3 text-muted f-s-12 mb-3">
                                    <span><i class="ph ph-user me-1"></i><?php echo e($article->user->name ?? 'N/A'); ?></span>
                                    <span><i class="ph ph-calendar me-1"></i><?php echo e($article->created_at->format('d/m/Y')); ?></span>
                                    <span><i class="ph ph-eye me-1"></i><?php echo e($article->views_count ?? 0); ?> visualizzazioni</span>
                                </div>

                                <!-- Actions Row -->
                                <div class="d-flex flex-wrap gap-1">
                                    <!-- Primary Actions -->
                                    <div class="d-flex gap-1">
                                        <a href="<?php echo e(route('articles.show', $article->slug)); ?>"
                                           class="btn btn-light btn-sm"
                                           target="_blank" title="Visualizza">
                                            <i class="ph ph-eye f-s-12"></i>
                                        </a>
                                        <a href="<?php echo e(route('admin.articles.edit', $article->id)); ?>"
                                           class="btn btn-light btn-sm"
                                           title="Modifica">
                                            <i class="ph ph-pencil f-s-12"></i>
                                        </a>
                                    </div>

                                    <!-- Secondary Actions -->
                                    <div class="d-flex gap-1">
                                        <!-- Featured Toggle -->
                                        <?php
                                            $featuredCount = $articles->where('featured', true)->count();
                                            $canToggleFeatured = $article->featured || $featuredCount < 3;
                                        ?>
                                        <button class="btn btn-sm <?php echo e($article->featured ? 'btn-warning' : 'btn-light'); ?> <?php echo e(!$canToggleFeatured ? 'disabled' : ''); ?>"
                                                onclick="<?php echo e($canToggleFeatured ? 'toggleFeatured(' . $article->id . ', ' . ($article->featured ? 'false' : 'true') . ')' : ''); ?>"
                                                title="<?php echo e($article->featured ? 'Rimuovi da featured' : ($canToggleFeatured ? 'Rendi featured' : 'Limite di 3 articoli featured raggiunto')); ?>"
                                                <?php echo e(!$canToggleFeatured ? 'disabled' : ''); ?>>
                                            <i class="ph ph-star f-s-12"></i>
                                        </button>

                                        <!-- Status Toggle -->
                                        <button class="btn btn-sm <?php echo e($article->status === 'published' ? 'btn-success' : 'btn-light'); ?>"
                                                onclick="toggleStatus(<?php echo e($article->id); ?>, '<?php echo e($article->status); ?>')"
                                                title="<?php echo e($article->status === 'published' ? 'Metti in bozza' : 'Pubblica'); ?>">
                                            <i class="ph ph-<?php echo e($article->status === 'published' ? 'eye-slash' : 'eye'); ?> f-s-12"></i>
                                        </button>

                                        <!-- Delete -->
                                        <button class="btn btn-light btn-sm"
                                                onclick="deleteArticle(<?php echo e($article->id); ?>)"
                                                title="Elimina">
                                            <i class="ph ph-trash f-s-12"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <!-- No Articles Message -->
                    <?php if($articles->count() === 0): ?>
                    <div class="text-center py-5">
                        <i class="ph ph-newspaper f-s-48 text-muted mb-3"></i>
                        <h5 class="f-s-18 f-w-600 text-muted">Nessun articolo trovato</h5>
                        <p class="text-muted f-s-14">Crea il tuo primo articolo per iniziare!</p>
                        <a href="<?php echo e(route('admin.articles.create')); ?>" class="btn btn-primary">
                            <i class="ph ph-plus me-2"></i>
                            <?php echo e(__('articles.create_article')); ?>

                        </a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
/* Mobile-first article titles */
@media (max-width: 768px) {
    .list-group-item h6 {
        font-size: 16px !important;
        line-height: 1.4 !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
        hyphens: auto !important;
        margin-bottom: 8px !important;
    }
    
    .list-group-item {
        padding: 16px !important;
        border-bottom: 1px solid #e9ecef !important;
    }
    
    .list-group-item:last-child {
        border-bottom: none !important;
    }
}

/* Compact buttons */
.btn-sm {
    padding: 4px 8px !important;
    font-size: 12px !important;
    min-width: 32px !important;
    min-height: 32px !important;
    border-radius: 4px !important;
}

.btn-light {
    background-color: #f8f9fa !important;
    border-color: #dee2e6 !important;
    color: #6c757d !important;
}

.btn-light:hover {
    background-color: #e9ecef !important;
    border-color: #ced4da !important;
    color: #495057 !important;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Mobile-First Articles Management
document.addEventListener('DOMContentLoaded', function() {
    // Mobile-specific adjustments
    const isMobile = window.innerWidth < 768;

    if (isMobile) {
        // Compact buttons for mobile
        const buttons = document.querySelectorAll('.btn-sm');
        buttons.forEach(btn => {
            btn.style.minHeight = '32px';
            btn.style.minWidth = '32px';
            btn.style.padding = '4px 8px';
        });

        // Improve touch targets for mobile
        const links = document.querySelectorAll('a.btn');
        links.forEach(link => {
            link.style.minHeight = '32px';
            link.style.display = 'inline-flex';
            link.style.alignItems = 'center';
            link.style.justifyContent = 'center';
        });

        // Add mobile-specific spacing
        const listItems = document.querySelectorAll('.list-group-item');
        listItems.forEach(item => {
            item.style.padding = '16px';
        });
    }

    // Responsive adjustments
    function adjustMobileLayout() {
        const isMobile = window.innerWidth < 768;
        const listItems = document.querySelectorAll('.list-group-item');

        if (isMobile) {
            // Mobile optimizations
            listItems.forEach(item => {
                item.style.padding = '16px';
            });
            
            // Ensure buttons are compact
            const buttons = document.querySelectorAll('.btn-sm');
            buttons.forEach(btn => {
                btn.style.minHeight = '32px';
                btn.style.minWidth = '32px';
                btn.style.padding = '4px 8px';
            });
        } else {
            // Desktop optimizations
            listItems.forEach(item => {
                item.style.padding = '';
            });
            
            const buttons = document.querySelectorAll('.btn-sm');
            buttons.forEach(btn => {
                btn.style.minHeight = '';
                btn.style.minWidth = '';
                btn.style.padding = '';
            });
        }
    }

    // Initial adjustment
    adjustMobileLayout();

    // Adjust on resize
    window.addEventListener('resize', adjustMobileLayout);

    // Filter functionality
    setupFilters();
});

// Toggle filters section
function toggleFilters() {
    const filtersSection = document.getElementById('filtersSection');
    if (filtersSection.style.display === 'none') {
        filtersSection.style.display = 'block';
    } else {
        filtersSection.style.display = 'none';
    }
}

// Setup filter functionality
function setupFilters() {
    const filters = ['statusFilter', 'categoryFilter', 'featuredFilter', 'searchFilter'];

    filters.forEach(filterId => {
        const element = document.getElementById(filterId);
        if (element) {
            element.addEventListener('change', applyFilters);
            element.addEventListener('input', applyFilters);
        }
    });
}

// Apply filters
function applyFilters() {
    const status = document.getElementById('statusFilter')?.value || '';
    const category = document.getElementById('categoryFilter')?.value || '';
    const featured = document.getElementById('featuredFilter')?.value || '';
    const search = document.getElementById('searchFilter')?.value || '';

    // Implementation for filtering articles
    console.log('Applying filters:', { status, category, featured, search });
}

// Toggle featured status
function toggleFeatured(articleId, featured) {
    console.log('Toggle featured called:', { articleId, featured });

    Swal.fire({
        title: featured ? 'Rendi Featured' : 'Rimuovi da Featured',
        text: featured ? 'Vuoi rendere questo articolo featured?' : 'Vuoi rimuovere questo articolo dai featured?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#ffc107',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sì, Conferma',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Aggiornamento in corso...',
                text: 'Attendi mentre aggiorno lo stato',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            console.log('Fetching:', `/admin/articles/${articleId}/toggle-featured`);
            fetch(route("admin.articles.toggle-featured", articleId), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                },
                body: JSON.stringify({ featured: featured })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Aggiornato!',
                        text: data.message || 'Stato articolo aggiornato con successo',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Errore!',
                        text: data.message || 'Errore durante l\'aggiornamento'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Errore di Connessione',
                    text: 'Impossibile connettersi al server. Riprova più tardi.'
                });
            });
        }
    });
}

// Toggle article status
function toggleStatus(articleId, currentStatus) {
    const newStatus = currentStatus === 'published' ? 'draft' : 'published';
    const actionText = newStatus === 'published' ? 'pubblicare' : 'mettere in bozza';
    const route = newStatus === 'published' ? 'publish' : 'unpublish';

    Swal.fire({
        title: 'Cambia Stato Articolo',
        text: `Vuoi ${actionText} questo articolo?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: newStatus === 'published' ? '#28a745' : '#6c757d',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sì, Conferma',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Aggiornamento in corso...',
                text: 'Attendi mentre aggiorno lo stato',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`/admin/articles/${articleId}/${route}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Aggiornato!',
                        text: data.message || 'Stato articolo aggiornato con successo',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Errore!',
                        text: data.message || 'Errore durante l\'aggiornamento'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Errore di Connessione',
                    text: 'Impossibile connettersi al server. Riprova più tardi.'
                });
            });
        }
    });
}

// Delete article
function deleteArticle(articleId) {
    Swal.fire({
        title: 'Elimina Articolo',
        text: 'Sei sicuro di voler eliminare questo articolo? Questa azione non può essere annullata.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sì, Elimina',
        cancelButtonText: 'Annulla'
    }).then((result) => {
        if (result.isConfirmed) {
            // Show loading
            Swal.fire({
                title: 'Eliminazione in corso...',
                text: 'Attendi mentre elimino l\'articolo',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            fetch(`/admin/articles/${articleId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Eliminato!',
                        text: data.message || 'Articolo eliminato con successo',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Errore!',
                        text: data.message || 'Errore durante l\'eliminazione'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Errore di Connessione',
                    text: 'Impossibile connettersi al server. Riprova più tardi.'
                });
            });
        }
    });
}
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/admin/articles/index.blade.php ENDPATH**/ ?>