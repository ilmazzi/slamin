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
                                    <span class="badge bg-primary f-s-14">
                                        <?php echo e(__('articles.current_featured')); ?>: <?php echo e($articles->where('featured', true)->count()); ?>/3
                                    </span>
                                </div>
                                <?php if($articles->where('featured', true)->count() >= 3): ?>
                                    <div class="alert alert-warning py-2 mb-0">
                                        <i class="ph ph-warning me-2"></i>
                                        <span class="f-s-13"><?php echo e(__('articles.featured_limit_reached')); ?></span>
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
                        <div class="col-12 col-sm-6 col-md-3">
                            <label class="form-label f-s-14 f-w-500"><?php echo e(__('articles.status')); ?></label>
                            <select class="form-select form-select-sm" id="statusFilter">
                                <option value=""><?php echo e(__('common.all')); ?></option>
                                <option value="published"><?php echo e(__('articles.published')); ?></option>
                                <option value="draft"><?php echo e(__('articles.draft')); ?></option>
                                <option value="pending"><?php echo e(__('articles.pending')); ?></option>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <label class="form-label f-s-14 f-w-500"><?php echo e(__('articles.category')); ?></label>
                            <select class="form-select form-select-sm" id="categoryFilter">
                                <option value=""><?php echo e(__('common.all')); ?></option>
                                <?php $__currentLoopData = $categories ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($category->id); ?>"><?php echo e($category->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <label class="form-label f-s-14 f-w-500"><?php echo e(__('articles.featured')); ?></label>
                            <select class="form-select form-select-sm" id="featuredFilter">
                                <option value=""><?php echo e(__('common.all')); ?></option>
                                <option value="1"><?php echo e(__('articles.featured')); ?></option>
                                <option value="0"><?php echo e(__('articles.not_featured')); ?></option>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-md-3">
                            <label class="form-label f-s-14 f-w-500"><?php echo e(__('common.search')); ?></label>
                            <input type="text" class="form-control form-control-sm" id="searchFilter" placeholder="<?php echo e(__('articles.search_articles')); ?>">
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Mobile-First Articles Grid -->
                    <div class="row g-3" id="articlesGrid">
                        <?php $__currentLoopData = $articles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-12 col-sm-6 col-lg-4">
                            <div class="card hover-effect h-100">
                                <!-- Article Image -->
                                <div class="position-relative">
                                    <?php if($article->featured_image): ?>
                                        <img src="<?php echo e(Storage::url($article->featured_image)); ?>"
                                             class="card-img-top" style="height: 200px; object-fit: cover;"
                                             alt="<?php echo e($article->title); ?>">
                                    <?php else: ?>
                                        <div class="card-img-top d-flex align-items-center justify-content-center"
                                             style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            <i class="ph ph-newspaper text-white f-s-32"></i>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Status Badge -->
                                    <div class="position-absolute top-0 start-0 m-2">
                                        <span class="badge <?php echo e($article->status === 'published' ? 'bg-success' : ($article->status === 'draft' ? 'bg-secondary' : 'bg-warning')); ?> f-s-11">
                                            <?php echo e(ucfirst($article->status)); ?>

                                        </span>
                                    </div>

                                    <!-- Featured Badge -->
                                    <?php if($article->featured): ?>
                                    <div class="position-absolute top-0 end-0 m-2">
                                        <span class="badge bg-warning f-s-11">
                                            <i class="ph ph-star me-1"></i>Featured
                                        </span>
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <div class="card-body d-flex flex-column">
                                    <!-- Article Title -->
                                    <h6 class="card-title f-s-16 f-w-600 mb-2"><?php echo e(Str::limit($article->title, 60)); ?></h6>

                                    <!-- Article Meta -->
                                    <div class="mb-3">
                                        <small class="text-muted f-s-12">
                                            <i class="ph ph-user me-1"></i>
                                            <?php echo e($article->user->name ?? 'N/A'); ?>

                                        </small>
                                        <br>
                                        <small class="text-muted f-s-12">
                                            <i class="ph ph-calendar me-1"></i>
                                            <?php echo e($article->created_at->format('d/m/Y')); ?>

                                        </small>
                                        <br>
                                        <small class="text-muted f-s-12">
                                            <i class="ph ph-eye me-1"></i>
                                            <?php echo e($article->views_count ?? 0); ?> visualizzazioni
                                        </small>
                                    </div>

                                    <!-- Article Actions -->
                                    <div class="mt-auto">
                                        <div class="d-flex flex-column flex-sm-row gap-2">
                                            <!-- Primary Actions -->
                                            <div class="d-flex gap-1 flex-grow-1">
                                                <a href="<?php echo e(route('articles.show', $article->slug)); ?>"
                                                   class="btn btn-outline-primary btn-sm flex-fill"
                                                   target="_blank" title="Visualizza">
                                                    <i class="ph ph-eye f-s-14"></i>
                                                    <span class="d-none d-sm-inline">Vedi</span>
                                                </a>
                                                <a href="<?php echo e(route('admin.articles.edit', $article->id)); ?>"
                                                   class="btn btn-outline-info btn-sm flex-fill"
                                                   title="Modifica">
                                                    <i class="ph ph-pencil f-s-14"></i>
                                                    <span class="d-none d-sm-inline">Modifica</span>
                                                </a>
                                            </div>

                                            <!-- Secondary Actions -->
                                            <div class="d-flex gap-1">
                                                <!-- Featured Toggle -->
                                                <button class="btn btn-sm <?php echo e($article->featured ? 'btn-warning' : 'btn-outline-warning'); ?>"
                                                        onclick="toggleFeatured(<?php echo e($article->id); ?>, <?php echo e($article->featured ? 'false' : 'true'); ?>)"
                                                        title="<?php echo e($article->featured ? 'Rimuovi da featured' : 'Rendi featured'); ?>">
                                                    <i class="ph ph-star f-s-14"></i>
                                                </button>

                                                <!-- Status Toggle -->
                                                <button class="btn btn-sm <?php echo e($article->status === 'published' ? 'btn-success' : 'btn-outline-success'); ?>"
                                                        onclick="toggleStatus(<?php echo e($article->id); ?>, '<?php echo e($article->status); ?>')"
                                                        title="<?php echo e($article->status === 'published' ? 'Metti in bozza' : 'Pubblica'); ?>">
                                                    <i class="ph ph-<?php echo e($article->status === 'published' ? 'eye-slash' : 'eye'); ?> f-s-14"></i>
                                                </button>

                                                <!-- Delete -->
                                                <button class="btn btn-outline-danger btn-sm"
                                                        onclick="deleteArticle(<?php echo e($article->id); ?>)"
                                                        title="Elimina">
                                                    <i class="ph ph-trash f-s-14"></i>
                                                </button>
                                            </div>
                                        </div>
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

<?php $__env->startPush('scripts'); ?>
<script>
// Mobile-First Articles Management
document.addEventListener('DOMContentLoaded', function() {
    // Mobile-specific adjustments
    const isMobile = window.innerWidth < 768;

    if (isMobile) {
        // Make buttons more touch-friendly on mobile
        const buttons = document.querySelectorAll('.btn-sm');
        buttons.forEach(btn => {
            btn.style.minHeight = '44px';
            btn.style.minWidth = '44px';
        });

        // Adjust card spacing for mobile
        const cards = document.querySelectorAll('.card.hover-effect');
        cards.forEach(card => {
            card.classList.add('mb-3');
        });
    }

    // Responsive adjustments
    function adjustMobileLayout() {
        const isMobile = window.innerWidth < 768;
        const articleCards = document.querySelectorAll('.card.hover-effect');

        if (isMobile) {
            articleCards.forEach(card => {
                card.classList.add('mb-3');
            });
        } else {
            articleCards.forEach(card => {
                card.classList.remove('mb-3');
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