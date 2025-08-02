<?php $__env->startSection('title', 'Slam in - Home'); ?>

<?php $__env->startSection('css'); ?>
<!-- Slick CSS -->
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/vendor/slick/slick.css')); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/vendor/slick/slick-theme.css')); ?>">
<style>
/* Stili aggiuntivi per lo slider degli eventi */
.events-slider {
    position: relative;
    margin: 0 -10px;
}

.events-slider .autoplay-item {
    padding: 0 10px;
}

.events-slider .card {
    height: 100%;
    transition: transform 0.3s ease;
}

.events-slider .card:hover {
    transform: translateY(-5px);
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<!-- Slick JS -->
<script src="<?php echo e(asset('assets/vendor/slick/slick.min.js')); ?>"></script>
<script src="<?php echo e(asset('assets/js/slick.js')); ?>"></script>

<script>
// Aspetta che tutto sia caricato
window.addEventListener('load', function() {
    // Verifica se jQuery è disponibile
    if (typeof $ === 'undefined') {
        console.error('jQuery non è caricato!');
        return;
    }

    // Verifica se Slick è disponibile
    if (typeof $.fn.slick === 'undefined') {
        console.error('Slick non è caricato!');
        return;
    }

    // Debug: verifica se lo slider esiste
    const $slider = $('.events-slider');
    console.log('Slider found:', $slider.length);

    if ($slider.length > 0) {
        // Inizializza lo slider degli eventi
        $slider.slick({
            slidesToShow: 2,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 3000,
            arrows: true,
            dots: false,
            infinite: true,
            speed: 500,
            responsive: [
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 576,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        });

        console.log('Slider initialized successfully');
    } else {
        console.error('Slider not found!');
    }

    // <?php echo e(__('wishlist.wishlist')); ?> è gestita globalmente da WishlistManager
    // Non serve codice duplicato qui
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('main-content'); ?>
<div class="page-content">
    <div class="container-fluid">

        <!-- Hero Carousel -->
        <?php if($carousels->count() > 0): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body p-0">
                        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
                            <?php if($carousels->count() > 1): ?>
                            <div class="carousel-indicators">
                                <?php $__currentLoopData = $carousels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $carousel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="<?php echo e($index); ?>"
                                        class="bg-primary <?php echo e($index === 0 ? 'active' : ''); ?>" aria-current="<?php echo e($index === 0 ? 'true' : 'false'); ?>"
                                        aria-label="Slide <?php echo e($index + 1); ?>"></button>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <?php endif; ?>
                            <div class="carousel-inner">
                                <?php $__currentLoopData = $carousels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $carousel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="carousel-item <?php echo e($index === 0 ? 'active' : ''); ?>">
                                    <?php if($carousel->video_path && $carousel->videoUrl): ?>
                                        <video class="d-block w-100" autoplay muted loop style="height: 400px; object-fit: cover;">
                                            <source src="<?php echo e($carousel->videoUrl); ?>" type="video/mp4">
                                        </video>
                                    <?php elseif($carousel->image_path && $carousel->imageUrl): ?>
                                        <img src="<?php echo e($carousel->imageUrl); ?>" class="d-block w-100" alt="<?php echo e($carousel->title); ?>" style="height: 400px; object-fit: cover;">
                                    <?php else: ?>
                                        <!-- Fallback per media mancante -->
                                        <div class="d-block w-100 bg-gradient-primary d-flex align-items-center justify-content-center" style="height: 400px;">
                                            <div class="text-center text-white">
                                                <i class="ph-duotone ph-image f-s-48 mb-3"></i>
                                                <h5 class="f-w-600"><?php echo e($carousel->title); ?></h5>
                                                <?php if($carousel->description): ?>
                                                    <p class="mb-0"><?php echo e($carousel->description); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="carousel-caption d-none d-md-block bg-light-success bg-opacity-75 rounded-3 p-4 mx-auto">
                                        <h5 class="f-w-600 f-s-24 mb-3 text-dark"><?php echo e($carousel->title); ?></h5>
                                        <?php if($carousel->description): ?>
                                            <p class="mb-4 f-s-16 text-primary"><?php echo e($carousel->description); ?></p>
                                        <?php endif; ?>
                                        <?php if($carousel->link_url && $carousel->link_text): ?>
                                            <a href="<?php echo e($carousel->link_url); ?>" class="btn btn-primary btn-lg hover-effect">
                                                <i class="ph-duotone ph-arrow-right f-s-16 me-2"></i>
                                                <?php echo e($carousel->link_text); ?>

                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <?php if($carousels->count() > 1): ?>
                            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                                <i class="ph ph-arrow-circle-left f-s-24 text-primary"></i>
                                <span class="visually-hidden"><?php echo e(__('home.carousel.previous')); ?></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                                <i class="ph ph-arrow-circle-right f-s-24 text-primary"></i>
                                <span class="visually-hidden"><?php echo e(__('home.carousel.next')); ?></span>
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Upcoming Events Slider Section -->
        <?php if($recentEvents->count() > 0): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="ph-duotone ph-calendar f-s-16 me-2"></i>
                            Prossimi Eventi
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="events-slider app-arrow" id="events-slider">
                            <?php $__currentLoopData = $recentEvents->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="autoplay-item">
                                <div class="card overflow-hidden hover-effect">
                                    <?php if($event->image_url): ?>
                                        <img src="<?php echo e($event->image_url); ?>" class="card-img-top" alt="<?php echo e($event->title); ?>" style="height: 200px; object-fit: cover;">
                                    <?php else: ?>
                                        <?php
                                            $fallbackImages = [
                                                'assets/images/background/default-event-1.webp',
                                                'assets/images/background/default-event-2.webp',
                                                'assets/images/background/default-event-3.webp',
                                                'assets/images/background/default-event-4.webp'
                                            ];
                                            $randomImage = $fallbackImages[array_rand($fallbackImages)];
                                        ?>
                                        <img src="<?php echo e(asset($randomImage)); ?>" class="card-img-top" alt="<?php echo e($event->title); ?>" style="height: 200px; object-fit: cover;">
                                    <?php endif; ?>
                                    <div class="card-body">
                                        <h5 class="card-title f-w-600"><?php echo e($event->title); ?></h5>
                                        <p class="card-text text-muted f-s-14">
                                            <i class="ph-duotone ph-map-pin f-s-12 me-1"></i>
                                            <?php echo e($event->venue_name); ?>

                                        </p>
                                        <?php if($event->description): ?>
                                            <p class="card-text"><?php echo e(Str::limit($event->description, 80)); ?></p>
                                        <?php endif; ?>
                                                                                    <div class="d-flex justify-content-between align-items-center">
                                                <p class="card-text">
                                                    <small class="text-body-secondary">
                                                        <i class="ph-duotone ph-calendar f-s-12 me-1"></i>
                                                        <?php echo e($event->start_datetime->format('d/m/Y H:i')); ?>

                                                    </small>
                                                </p>
                                                <div class="d-flex gap-1">
                                                    <?php if(auth()->guard()->check()): ?>
                                                        <button class="btn btn-sm btn-outline-danger wishlist-toggle" data-event-id="<?php echo e($event->id); ?>" title="Aggiungi/<?php echo e(__('wishlist.remove_from_wishlist')); ?>">
                                                            <img src="<?php echo e(asset('assets/images/like.png')); ?>" alt="Like" style="width: 16px; height: 16px; filter: brightness(0) saturate(100%) invert(60%) sepia(0%) saturate(0%) hue-rotate(0deg) brightness(89%) contrast(86%);">
                                                        </button>
                                                    <?php endif; ?>
                                                    <a href="<?php echo e(route('events.show', $event)); ?>" class="btn btn-sm btn-warning">
                                                        <i class="ph-duotone ph-info f-s-14 me-1"></i>Dettagli
                                                    </a>
                                                    <?php if (isset($component)) { $__componentOriginalcab7032bfdfb17b0d85d7225950dd852 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcab7032bfdfb17b0d85d7225950dd852 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.report-button','data' => ['content' => $event,'type' => 'event','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('report-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['content' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($event),'type' => 'event','size' => 'sm']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcab7032bfdfb17b0d85d7225950dd852)): ?>
<?php $attributes = $__attributesOriginalcab7032bfdfb17b0d85d7225950dd852; ?>
<?php unset($__attributesOriginalcab7032bfdfb17b0d85d7225950dd852); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcab7032bfdfb17b0d85d7225950dd852)): ?>
<?php $component = $__componentOriginalcab7032bfdfb17b0d85d7225950dd852; ?>
<?php unset($__componentOriginalcab7032bfdfb17b0d85d7225950dd852); ?>
<?php endif; ?>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Most Popular <?php echo e(__('common.video')); ?> Section -->
        <?php if($mostPopularVideo && $mostPopularVideo->exists): ?>
        <div class="row mb-4">
            <div class="col-12">
                <div class="card hover-effect border-0 shadow-sm">
                    <div class="card-body p-0">
                        <div class="position-relative">
                            <div class="p-3 p-md-4">
                                <!-- Mobile First Layout -->
                                <div class="row">
                                    <!-- <?php echo e(__('common.video')); ?> <?php echo e(__('common.thumbnail')); ?> Column -->
                                    <div class="col-12 col-lg-6 mb-3 mb-lg-0">
                                        <div class="position-relative">
                                            <div class="position-relative overflow-hidden rounded-3" style="aspect-ratio: 16/9;">
                                                <?php if($mostPopularVideo->thumbnail_url && $mostPopularVideo->thumbnail_url !== asset('assets/images/placeholder/placholder-1.jpg')): ?>
                                                    <img src="<?php echo e($mostPopularVideo->thumbnail_url); ?>" alt="<?php echo e($mostPopularVideo->title); ?>" class="w-100 h-100" style="object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-gradient-light">
                                                        <div class="text-center">
                                                            <i class="ph-duotone ph-video-camera f-s-48 text-muted mb-2"></i>
                                                            <p class="text-muted f-s-14 mb-0">Anteprima non disponibile</p>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="position-absolute top-50 start-50 translate-middle">
                                                    <div class="bg-white bg-opacity-90 rounded-circle p-3 p-md-4 d-flex-center" style="width: 70px; height: 70px;">
                                                        <i class="ph-duotone ph-play f-s-24 f-s-md-36 text-primary"></i>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Popular Badge -->
                                            <div class="position-absolute top-0 end-0 m-2 m-md-3">
                                                <span class="badge bg-warning text-dark f-s-11 fw-bold px-2 px-md-3 py-1 py-md-2 rounded-pill shadow-sm">
                                                    <i class="ph-duotone ph-trophy f-s-12 me-1"></i>
                                                    Più Popolare
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Content Column -->
                                    <div class="col-12 col-lg-6">
                                        <div class="h-100 d-flex flex-column justify-content-between">
                                            <!-- Title and Description -->
                                            <div class="mb-3">
                                                <h4 class="text-dark f-w-700 mb-2 f-s-18 f-s-md-20"><?php echo e($mostPopularVideo->title); ?></h4>
                                                <?php if($mostPopularVideo->description): ?>
                                                    <p class="text-muted mb-3 f-s-14"><?php echo e(Str::limit($mostPopularVideo->description, 120)); ?></p>
                                                <?php endif; ?>

                                                <!-- Author Info -->
                                                <a href="<?php echo e(route('user.show', $mostPopularVideo->user)); ?>" class="text-decoration-none hover-effect">
                                                    <div class="d-flex align-items-center mb-3 p-2 rounded-3 transition-all">
                                                        <?php if($mostPopularVideo->user->profile_photo): ?>
                                                            <div class="h-40 w-40 d-flex-center b-r-50 overflow-hidden me-3">
                                                                <img src="<?php echo e($mostPopularVideo->user->profile_photo_url); ?>" alt="<?php echo e($mostPopularVideo->user->name); ?>" class="w-100 h-100" style="object-fit: cover;">
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="h-40 w-40 d-flex-center b-r-50 overflow-hidden bg-gradient-primary me-3">
                                                                <span class="text-white fw-bold f-s-16"><?php echo e(substr($mostPopularVideo->user->name, 0, 2)); ?></span>
                                                            </div>
                                                        <?php endif; ?>
                                                        <div>
                                                            <h6 class="mb-0 f-w-600 f-s-14 text-dark"><?php echo e($mostPopularVideo->user->name); ?></h6>
                                                            <small class="text-muted f-s-11">Autore del video</small>
                                                        </div>
                                                        <div class="ms-auto">
                                                            <i class="ph-duotone ph-arrow-right f-s-16 text-muted"></i>
                                                        </div>
                                                    </div>
                                                </a>

                                                <!-- Watch Button -->
                                                <div class="d-flex gap-2">

                                                    <a href="<?php echo e(route('videos.show', $mostPopularVideo)); ?>" class="btn btn-primary btn-sm hover-effect f-w-600 px-3 py-2 rounded-pill shadow-sm d-flex align-items-center justify-content-center">
                                                        <i class="ph-duotone ph-play f-s-14 me-1"></i>
                                                        Guarda <?php echo e(__('common.video')); ?>

                                                    </a>
                                                    <?php if (isset($component)) { $__componentOriginalcab7032bfdfb17b0d85d7225950dd852 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcab7032bfdfb17b0d85d7225950dd852 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.report-button','data' => ['content' => $mostPopularVideo,'type' => 'video','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('report-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['content' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($mostPopularVideo),'type' => 'video','size' => 'sm']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcab7032bfdfb17b0d85d7225950dd852)): ?>
<?php $attributes = $__attributesOriginalcab7032bfdfb17b0d85d7225950dd852; ?>
<?php unset($__attributesOriginalcab7032bfdfb17b0d85d7225950dd852); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcab7032bfdfb17b0d85d7225950dd852)): ?>
<?php $component = $__componentOriginalcab7032bfdfb17b0d85d7225950dd852; ?>
<?php unset($__componentOriginalcab7032bfdfb17b0d85d7225950dd852); ?>
<?php endif; ?>
                                                </div>
                                            </div>

                                            <!-- Statistics -->
                                            <div class="row g-2">
                                                <div class="col-6 col-md-3">
                                                    <div class="text-center p-2 rounded-3 txt-bg-success">
                                                        <div class="d-flex-center mb-1">
                                                            <i class="ph-duotone ph-eye f-s-16 f-s-md-18 text-info"></i>
                                                        </div>
                                                        <h6 class="mb-1 text-dark f-w-700 f-s-12 f-s-md-14"><?php echo e(number_format($mostPopularVideo->view_count)); ?></h6>
                                                        <small class="text-muted f-s-10">Visualizzazioni</small>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-3">
                                                    <div class="text-center p-2 rounded-3 txt-bg-success">
                                                        <div class="d-flex-center mb-1">
                                                            <i class="ph-duotone ph-thumbs-up f-s-16 f-s-md-18 text-success"></i>
                                                        </div>
                                                        <h6 class="mb-1 text-dark f-w-700 f-s-12 f-s-md-14"><?php echo e(number_format($mostPopularVideo->like_count)); ?></h6>
                                                        <small class="text-muted f-s-10">Mi Piace</small>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-3">
                                                    <div class="text-center p-2 rounded-3 txt-bg-success">
                                                        <div class="d-flex-center mb-1">
                                                            <i class="ph-duotone ph-chat-circle f-s-16 f-s-md-18 text-warning"></i>
                                                        </div>
                                                        <h6 class="mb-1 text-dark f-w-700 f-s-12 f-s-md-14"><?php echo e(number_format($mostPopularVideo->comment_count)); ?></h6>
                                                        <small class="text-muted f-s-10"><?php echo e(__('common.comments_section')); ?></small>
                                                    </div>
                                                </div>
                                                <div class="col-6 col-md-3">
                                                    <div class="text-center p-2 rounded-3 txt-bg-success">
                                                        <div class="d-flex-center mb-1">
                                                            <img src="<?php echo e(asset('assets/images/snap.png')); ?>" alt="Snap" style="width: 16px; height: 16px; filter: brightness(0) saturate(100%) invert(27%) sepia(51%) saturate(2878%) hue-rotate(346deg) brightness(104%) contrast(97%);">
                                                        </div>
                                                        <h6 class="mb-1 text-dark f-w-700 f-s-12 f-s-md-14"><?php echo e(number_format($mostPopularVideo->snaps()->count())); ?></h6>
                                                        <small class="text-muted f-s-10"><?php echo e(__('common.snap')); ?></small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Stats Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card card-light-primary">
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-md-3 col-6 mb-3">
                                <div class="eshop-cards">
                                    <div class="eshop-cards-body">
                                        <div class="eshop-cards-icon bg-gradient-primary">
                                            <i class="ph-duotone ph-video-camera f-s-24 text-white"></i>
                                        </div>
                                        <h4 class="mb-1 text-primary"><?php echo e(number_format($stats['total_videos'])); ?></h4>
                                        <p class="mb-0 text-muted"><?php echo e(__('home.stats.total_videos')); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="eshop-cards">
                                    <div class="eshop-cards-body">
                                        <div class="eshop-cards-icon bg-gradient-success">
                                            <i class="ph-duotone ph-eye f-s-24 text-white"></i>
                                        </div>
                                        <h4 class="mb-1 text-success"><?php echo e(number_format($stats['total_views'])); ?></h4>
                                        <p class="mb-0 text-muted"><?php echo e(__('home.stats.total_views')); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="eshop-cards">
                                    <div class="eshop-cards-body">
                                        <div class="eshop-cards-icon bg-gradient-warning">
                                            <i class="ph-duotone ph-calendar f-s-24 text-white"></i>
                                        </div>
                                        <h4 class="mb-1 text-warning"><?php echo e(number_format($stats['total_events'])); ?></h4>
                                        <p class="mb-0 text-muted"><?php echo e(__('home.stats.total_events')); ?></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 col-6 mb-3">
                                <div class="eshop-cards">
                                    <div class="eshop-cards-body">
                                        <div class="eshop-cards-icon bg-gradient-info">
                                            <i class="ph-duotone ph-users f-s-24 text-white"></i>
                                        </div>
                                        <h4 class="mb-1 text-info"><?php echo e(number_format($stats['total_users'])); ?></h4>
                                        <p class="mb-0 text-muted"><?php echo e(__('home.stats.total_users')); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Entry Section - Nuovi Utenti Registrati -->
        <?php if($newUsers->count() > 0): ?>

        <div class="row mb-4">
            <div class="col-12">
                <h5 class="text-primary mb-3">
                    <i class="ph-duotone ph-user-plus f-s-16 me-2"></i>
                    Nuovi Utenti
                </h5>
            </div>
            <?php $__currentLoopData = $newUsers->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="card">
                    <div class="card-body">
                        <div class="profile-container" onclick="window.location.href='<?php echo e(route('user.show', $user)); ?>'" style="cursor: pointer;">
                            <div class="image-details">
                                <div class="profile-image">
                                    <img src="<?php echo e($user->banner_image_url ?? asset('assets/images/avatar/default-banner.webp?v=1')); ?>" alt="<?php echo e($user->name); ?>" class="w-100 h-100" style="object-fit: cover;">
                                </div>
                                <div class="profile-pic">
                                    <div class="avatar-upload">
                                        <div class="avatar-preview">
                                            <div id="imgPreview">

                                                    <img src="<?php echo e($user->profile_photo_url); ?>" alt="<?php echo e($user->name); ?>" class="w-100 h-100" style="object-fit: cover;">

                                                    <div class="bg-gradient-success rounded-circle d-flex align-items-center justify-content-center w-100 h-100">
                                                        <span class="text-white fw-bold f-s-20"><?php echo e(strtoupper(substr(trim($user->name), 0, 2)) ?: 'U'); ?></span>
                                                    </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="person-details">
                                <h5 class="f-w-600"><?php echo e($user->name); ?>

                                    <?php if($user->verified): ?>
                                        <img src="https://phplaravel-1384472-5380003.cloudwaysapps.com/../assets/images/profile-app/01.png" class="w-20 h-20" alt="instagram-check-mark">
                                    <?php endif; ?>
                                </h5>
                                <p><?php echo e($user->city ?? 'Località non specificata'); ?></p>
                                <div class="details">
                                    <div>
                                        <h4 class="text-primary"><?php echo e($user->videos_count); ?></h4>
                                        <p class="text-secondary"><?php echo e(__('common.video')); ?></p>
                                    </div>
                                    <div>
                                        <h4 class="text-primary"><?php echo e($user->followers_count ?? 0); ?></h4>
                                        <p class="text-secondary">Follower</p>
                                    </div>
                                    <div>
                                        <h4 class="text-primary"><?php echo e($user->following_count ?? 0); ?></h4>
                                        <p class="text-secondary">Following</p>
                                    </div>
                                </div>
                                <div class="my-2">
                                    <button type="button" class="btn btn-primary b-r-22" onclick="event.stopPropagation(); followUser(<?php echo e($user->id); ?>)">
                                        <i class="ti ti-user"></i>
                                        Follow
                                    </button>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>
        <?php endif; ?>

        <!-- Poetry and Articles Section -->
        <div class="row">
            <!-- Poetry Section (Left) -->
            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card ">
                    <div class="card-header bg-gradient-info text-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ph-duotone ph-book-open f-s-16 me-2"></i>
                            Poesia
                        </h5>
                        <div class="d-flex align-items-center justify-content-center">
                            <span id="poetryToggleLabelLeft" class="text-primary f-s-12 me-2"><?php echo e(__('common.popular')); ?></span>
                            <div class="form-check form-switch mx-2">
                                <input class="form-check-input" type="checkbox" id="poetryToggle" onchange="togglePoetryContent(this.checked ? 'new' : 'popular')">
                            </div>
                            <span id="poetryToggleLabelRight" class="text-muted f-s-12 ms-2"><?php echo e(__('common.new')); ?></span>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- New Poetry Content -->
                        <div id="newPoetryContent">
                            <div class="row">
                                <?php $__currentLoopData = $recentPoems ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $poem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-12 mb-3">
                                    <div class="card  hover-effect border-info">
                                        <div class="card-body pa-15">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="position-relative">
                                                        <div class="rounded overflow-hidden" style="width: 60px; height: 60px;">
                                                            <?php if($poem->thumbnail_path): ?>
                                                                <img src="<?php echo e($poem->thumbnail_url); ?>" alt="<?php echo e($poem->title); ?>" class="w-100 h-100" style="object-fit: cover;">
                                                            <?php else: ?>
                                                                <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-gradient-light">
                                                                    <i class="ph-duotone ph-book-open f-s-20 text-muted"></i>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="position-absolute top-0 start-0 end-0 bottom-0 bg-dark opacity-20"></div>
                                                        <div class="position-absolute top-50 start-50 translate-middle">
                                                            <i class="ph-duotone ph-book-open f-s-12 text-white"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="card-title f-w-600 f-s-14 mb-1 text-primary"><?php echo e(Str::limit($poem->title, 40)); ?></h6>
                                                    <p class="text-muted f-s-12 mb-1"><?php echo e($poem->user->name); ?></p>
                                                    <div class="d-flex align-items-center">
                                                        <small class="text-muted f-s-11 me-3">
                                                            <i class="ph-duotone ph-eye f-s-10 me-1"></i><?php echo e(number_format($poem->view_count)); ?>

                                                        </small>
                                                        <small class="text-muted f-s-11">
                                                            <i class="ph-duotone ph-clock f-s-10 me-1"></i><?php echo e($poem->created_at->diffForHumans()); ?>

                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <a href="<?php echo e(route('poems.show', $poem)); ?>" class="btn btn-sm btn-gradient-info hover-effect">
                                                        <i class="ph-duotone ph-book-open f-s-12"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        <!-- Popular Poetry Content (Hidden by default) -->
                        <div id="popularPoetryContent" style="display: none;">
                            <div class="row">
                                <?php $__currentLoopData = $popularPoems ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $poem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-12 mb-3">
                                    <div class="card  hover-effect border-info">
                                        <div class="card-body pa-15">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="position-relative">
                                                        <div class="rounded overflow-hidden" style="width: 60px; height: 60px;">
                                                            <?php if($poem->thumbnail_path): ?>
                                                                <img src="<?php echo e($poem->thumbnail_url); ?>" alt="<?php echo e($poem->title); ?>" class="w-100 h-100" style="object-fit: cover;">
                                                            <?php else: ?>
                                                                <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-gradient-light">
                                                                    <i class="ph-duotone ph-book-open f-s-20 text-muted"></i>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="position-absolute top-0 start-0 end-0 bottom-0 bg-dark opacity-20"></div>
                                                        <div class="position-absolute top-50 start-50 translate-middle">
                                                            <i class="ph-duotone ph-book-open f-s-12 text-white"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="card-title f-w-600 f-s-14 mb-1 text-primary"><?php echo e(Str::limit($poem->title, 40)); ?></h6>
                                                    <p class="text-muted f-s-12 mb-1"><?php echo e($poem->user->name); ?></p>
                                                    <div class="d-flex align-items-center">
                                                        <small class="text-muted f-s-11 me-3">
                                                            <i class="ph-duotone ph-eye f-s-10 me-1"></i><?php echo e(number_format($poem->view_count)); ?>

                                                        </small>
                                                        <small class="text-muted f-s-11">
                                                            <i class="ph-duotone ph-thumbs-up f-s-10 me-1"></i><?php echo e(number_format($poem->like_count)); ?>

                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <a href="<?php echo e(route('poems.show', $poem)); ?>" class="btn btn-sm btn-gradient-info hover-effect">
                                                        <i class="ph-duotone ph-book-open f-s-12"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        <!-- Footer with link to all poems -->
                        <div class="text-center mt-3">
                            <div class="d-flex gap-2 justify-content-center">
                                <?php if(auth()->guard()->check()): ?>
                                <a href="<?php echo e(route('poems.create')); ?>" class="btn btn-info btn-sm">
                                    <i class="ph-duotone ph-plus f-s-12 me-1"></i>
                                    Crea poesia
                                </a>
                                <?php endif; ?>
                                <a href="<?php echo e(route('poems.index')); ?>" class="btn btn-outline-info btn-sm">
                                    <i class="ph-duotone ph-arrow-right f-s-12 me-1"></i>
                                    Vedi tutte le poesie
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Articles Section (Right) -->
            <div class="col-lg-6 col-md-12 mb-4">
                <div class="card">
                    <div class="card-header bg-gradient-warning text-white d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="ph-duotone ph-newspaper f-s-16 me-2"></i>
                            Articoli
                        </h5>
                            <div class="d-flex align-items-center justify-content-center">
                                <span id="articlesToggleLabelLeft" class="text-primary f-s-12 me-2"><?php echo e(__('common.popular')); ?></span>
                                <div class="form-check form-switch mx-2">
                                    <input class="form-check-input" type="checkbox" id="articlesToggle" onchange="toggleArticlesContent(this.checked ? 'new' : 'popular')">
                                </div>
                                <span id="articlesToggleLabelRight" class="text-muted f-s-12 ms-2"><?php echo e(__('common.new')); ?></span>
                            </div>
                    </div>
                    <div class="card-body">
                        <!-- New Articles Content -->
                        <div id="newArticlesContent">
                            <div class="row">
                                <?php $__currentLoopData = $recentArticles ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-12 mb-3">
                                    <div class="card  hover-effect border-warning">
                                        <div class="card-body pa-15">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="rounded overflow-hidden" style="width: 60px; height: 60px;">
                                                        <?php if($article->image_path): ?>
                                                            <img src="<?php echo e(asset('storage/' . $article->image_path)); ?>" alt="<?php echo e($article->title); ?>" class="w-100 h-100" style="object-fit: cover;">
                                                        <?php else: ?>
                                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-gradient-light">
                                                                <i class="ph-duotone ph-newspaper f-s-20 text-muted"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="card-title f-w-600 f-s-14 mb-1 text-warning"><?php echo e(Str::limit($article->title, 40)); ?></h6>
                                                    <p class="text-muted f-s-12 mb-1"><?php echo e($article->author->name ?? 'Redazione'); ?></p>
                                                    <div class="d-flex align-items-center">
                                                        <small class="text-muted f-s-11 me-3">
                                                            <i class="ph-duotone ph-eye f-s-10 me-1"></i><?php echo e(number_format($article->view_count ?? 0)); ?>

                                                        </small>
                                                        <small class="text-muted f-s-11">
                                                            <i class="ph-duotone ph-clock f-s-10 me-1"></i><?php echo e($article->created_at->diffForHumans()); ?>

                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <a href="<?php echo e(route('articles.show', $article)); ?>" class="btn btn-sm btn-gradient-warning hover-effect">
                                                        <i class="ph-duotone ph-arrow-right f-s-12"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>

                        <!-- Popular Articles Content (Hidden by default) -->
                        <div id="popularArticlesContent" style="display: none;">
                            <div class="row">
                                <?php $__currentLoopData = $popularArticles ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-12 mb-3">
                                    <div class="card  hover-effect border-warning">
                                        <div class="card-body pa-15">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="rounded overflow-hidden" style="width: 60px; height: 60px;">
                                                        <?php if($article->image_path): ?>
                                                            <img src="<?php echo e(asset('storage/' . $article->image_path)); ?>" alt="<?php echo e($article->title); ?>" class="w-100 h-100" style="object-fit: cover;">
                                                        <?php else: ?>
                                                            <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-gradient-light">
                                                                <i class="ph-duotone ph-newspaper f-s-20 text-muted"></i>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="card-title f-w-600 f-s-14 mb-1 text-warning"><?php echo e(Str::limit($article->title, 40)); ?></h6>
                                                    <p class="text-muted f-s-12 mb-1"><?php echo e($article->author->name ?? 'Redazione'); ?></p>
                                                    <div class="d-flex align-items-center">
                                                        <small class="text-muted f-s-11 me-3">
                                                            <i class="ph-duotone ph-eye f-s-10 me-1"></i><?php echo e(number_format($article->view_count ?? 0)); ?>

                                                        </small>
                                                        <small class="text-muted f-s-11">
                                                            <i class="ph-duotone ph-thumbs-up f-s-10 me-1"></i><?php echo e(number_format($article->like_count ?? 0)); ?>

                                                        </small>
                                                    </div>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <a href="<?php echo e(route('articles.show', $article)); ?>" class="btn btn-sm btn-gradient-warning hover-effect">
                                                        <i class="ph-duotone ph-arrow-right f-s-12"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Inizializza il carosello Bootstrap
    const carousel = document.getElementById('heroCarousel');
    if (carousel) {
        console.log('Carousel trovato, inizializzazione...');

        // Prova prima con l'approccio standard
        try {
            const bsCarousel = new bootstrap.Carousel(carousel, {
                interval: 5000, // 5 secondi
                ride: 'carousel', // Avvia automaticamente
                wrap: true, // Loop infinito
                keyboard: true, // Controlli da tastiera
                pause: 'hover' // Pausa al hover
            });
            console.log('Carousel inizializzato con successo!');
        } catch (error) {
            console.log('Errore inizializzazione Bootstrap:', error);

            // Fallback: carosello manuale
            console.log('Tentativo con fallback manuale...');
            initManualCarousel();
        }

        // Debug: mostra informazioni sul carosello
        const slides = carousel.querySelectorAll('.carousel-item');
        console.log('Numero di slide trovate:', slides.length);

        slides.forEach((slide, index) => {
            console.log(`Slide ${index + 1}:`, slide.classList.contains('active') ? 'ATTIVA' : 'inattiva');
        });
    } else {
        console.log('Carousel non trovato nella pagina');
    }

    // Funzione fallback per carosello manuale
    function initManualCarousel() {
        const carousel = document.getElementById('heroCarousel');
        const slides = carousel.querySelectorAll('.carousel-item');
        const indicators = carousel.querySelectorAll('.carousel-indicators button');
        const prevBtn = carousel.querySelector('.carousel-control-prev');
        const nextBtn = carousel.querySelector('.carousel-control-next');

        let currentSlide = 0;
        let interval;

        function showSlide(index) {
            // Nascondi tutte le slide
            slides.forEach(slide => slide.classList.remove('active'));
            indicators.forEach(indicator => indicator.classList.remove('active'));

            // Mostra la slide corrente
            slides[index].classList.add('active');
            if (indicators[index]) {
                indicators[index].classList.add('active');
            }

            currentSlide = index;
        }

        function nextSlide() {
            const next = (currentSlide + 1) % slides.length;
            showSlide(next);
        }

        function prevSlide() {
            const prev = (currentSlide - 1 + slides.length) % slides.length;
            showSlide(prev);
        }

        // Event listeners
        if (nextBtn) {
            nextBtn.addEventListener('click', nextSlide);
        }
        if (prevBtn) {
            prevBtn.addEventListener('click', prevSlide);
        }

        indicators.forEach((indicator, index) => {
            indicator.addEventListener('click', () => showSlide(index));
        });

        // Auto-scroll
        interval = setInterval(nextSlide, 5000);

        // Pausa al hover
        carousel.addEventListener('mouseenter', () => clearInterval(interval));
        carousel.addEventListener('mouseleave', () => {
            interval = setInterval(nextSlide, 5000);
        });

        console.log('Carousel manuale inizializzato!');
    }

    // Toggle functions for Poetry and Articles sections
    window.togglePoetryContent = function(type) {
        const newContent = document.getElementById('newPoetryContent');
        const popularContent = document.getElementById('popularPoetryContent');
        const toggle = document.getElementById('poetryToggle');
        const labelLeft = document.getElementById('poetryToggleLabelLeft');
        const labelRight = document.getElementById('poetryToggleLabelRight');

        if (type === 'new') {
            newContent.style.display = 'block';
            popularContent.style.display = 'none';
            toggle.checked = true;
            // Evidenzia "New" e disattiva "Popolari"
            labelLeft.classList.remove('text-primary');
            labelLeft.classList.add('text-muted');
            labelRight.classList.remove('text-muted');
            labelRight.classList.add('text-primary');
        } else {
            newContent.style.display = 'none';
            popularContent.style.display = 'block';
            toggle.checked = false;
            // Evidenzia "Popolari" e disattiva "New"
            labelLeft.classList.remove('text-muted');
            labelLeft.classList.add('text-primary');
            labelRight.classList.remove('text-primary');
            labelRight.classList.add('text-muted');
        }
    };

    window.toggleArticlesContent = function(type) {
        const newContent = document.getElementById('newArticlesContent');
        const popularContent = document.getElementById('popularArticlesContent');
        const toggle = document.getElementById('articlesToggle');
        const labelLeft = document.getElementById('articlesToggleLabelLeft');
        const labelRight = document.getElementById('articlesToggleLabelRight');

        if (type === 'new') {
            newContent.style.display = 'block';
            popularContent.style.display = 'none';
            toggle.checked = true;
            // Evidenzia "New" e disattiva "Popolari"
            labelLeft.classList.remove('text-primary');
            labelLeft.classList.add('text-muted');
            labelRight.classList.remove('text-muted');
            labelRight.classList.add('text-primary');
        } else {
            newContent.style.display = 'none';
            popularContent.style.display = 'block';
            toggle.checked = false;
            // Evidenzia "Popolari" e disattiva "New"
            labelLeft.classList.remove('text-muted');
            labelLeft.classList.add('text-primary');
            labelRight.classList.remove('text-primary');
            labelRight.classList.add('text-muted');
        }
    };

    // Funzione per seguire un utente
    window.followUser = function(userId) {
        // Per ora mostra un alert, in futuro implementare la logica di follow
        alert('Funzionalità Follow in sviluppo per l\'utente ID: ' + userId);

        // TODO: Implementare chiamata AJAX per follow/unfollow
        // fetch('/api/follow/' + userId, {
        //     method: 'POST',
        //     headers: {
        //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        //         'Content-Type': 'application/json',
        //     }
        // })
        // .then(response => response.json())
        // .then(data => {
        //     if (data.success) {
        //         // Aggiorna il bottone
        //         const button = event.target;
        //         button.innerHTML = data.following ? '<i class="ti ti-user-check"></i> Following' : '<i class="ti ti-user"></i> Follow';
        //         button.classList.toggle('btn-success', data.following);
        //         button.classList.toggle('btn-primary', !data.following);
        //     }
        // });
    };

    // Stili personalizzati per gli switch
    document.addEventListener('DOMContentLoaded', function() {
        // Aggiungi stili CSS personalizzati
        const style = document.createElement('style');
        style.textContent = `
            .form-check-input:checked {
                background-color: #fff;
                border-color: #fff;
            }
            .form-check-input:focus {
                border-color: #fff;
                box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.25);
            }
            .form-check-label {
                cursor: pointer;
                user-select: none;
            }
            #articlesToggleLabelLeft, #articlesToggleLabelRight, #poetryToggleLabelLeft, #poetryToggleLabelRight {
                cursor: pointer;
                user-select: none;
                transition: opacity 0.2s ease;
            }
            #articlesToggleLabelLeft:hover, #articlesToggleLabelRight:hover, #poetryToggleLabelLeft:hover, #poetryToggleLabelRight:hover {
                opacity: 0.8;
            }
        `;
        document.head.appendChild(style);

                        // Inizializza lo stato del toggle degli articoli
        const articlesToggle = document.getElementById('articlesToggle');
        const articlesLabelLeft = document.getElementById('articlesToggleLabelLeft');
        const articlesLabelRight = document.getElementById('articlesToggleLabelRight');

                if (articlesToggle && articlesLabelLeft && articlesLabelRight) {
            // Imposta lo stato iniziale: "Popolari" attivo, "New" inattivo
            articlesToggle.checked = false;
            articlesLabelLeft.classList.add('text-primary');
            articlesLabelRight.classList.add('text-muted');

            // Aggiungi event listener per i click sulle etichette
            articlesLabelLeft.addEventListener('click', function() {
                articlesToggle.checked = false;
                toggleArticlesContent('popular');
            });

            articlesLabelRight.addEventListener('click', function() {
                articlesToggle.checked = true;
                toggleArticlesContent('new');
            });
        }

        // Inizializza lo stato del toggle della poesia
        const poetryToggle = document.getElementById('poetryToggle');
        const poetryLabelLeft = document.getElementById('poetryToggleLabelLeft');
        const poetryLabelRight = document.getElementById('poetryToggleLabelRight');

                if (poetryToggle && poetryLabelLeft && poetryLabelRight) {
            // Imposta lo stato iniziale: "Popolari" attivo, "New" inattivo
            poetryToggle.checked = false;
            poetryLabelLeft.classList.add('text-primary');
            poetryLabelRight.classList.add('text-muted');

            // Aggiungi event listener per i click sulle etichette
            poetryLabelLeft.addEventListener('click', function() {
                poetryToggle.checked = false;
                togglePoetryContent('popular');
            });

            poetryLabelRight.addEventListener('click', function() {
                poetryToggle.checked = true;
                togglePoetryContent('new');
            });
        }
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/home.blade.php ENDPATH**/ ?>