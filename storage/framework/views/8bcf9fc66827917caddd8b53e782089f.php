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
                                                    <?php else: ?>
                                                        <a href="<?php echo e(route('login')); ?>" class="btn btn-sm btn-outline-danger" title="<?php echo e(__('auth.login_required')); ?>">
                                                            <img src="<?php echo e(asset('assets/images/like.png')); ?>" alt="Like" style="width: 16px; height: 16px; filter: brightness(0) saturate(100%) invert(60%) sepia(0%) saturate(0%) hue-rotate(0deg) brightness(89%) contrast(86%);">
                                                        </a>
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
        <?php if($mostPopularVideo): ?>
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
                                            <div class="position-relative overflow-hidden rounded-3" style="aspect-ratio: 16/9; cursor: pointer;" onclick="openVideoModal(<?php echo e($mostPopularVideo->id ?? 0); ?>)">
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
                                                <div class="position-absolute top-50 start-50 translate-middle" style="cursor: pointer;" onclick="openVideoModal(<?php echo e($mostPopularVideo->id ?? 0); ?>)">
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
                                    <?php if(auth()->guard()->check()): ?>
                                    <button type="button" class="btn <?php echo e($user->is_followed_by_current_user ?? false ? 'btn-success' : 'btn-primary'); ?> b-r-22" onclick="event.stopPropagation(); followUser(<?php echo e($user->id); ?>)" id="followBtn<?php echo e($user->id); ?>">
                                        <i class="ti <?php echo e($user->is_followed_by_current_user ?? false ? 'ti-user-check' : 'ti-user'); ?>"></i>
                                        <span id="followText<?php echo e($user->id); ?>"><?php echo e($user->is_followed_by_current_user ?? false ? 'Following' : 'Follow'); ?></span>
                                    </button>
                                    <?php else: ?>
                                    <div class="text-center">
                                        <div class="social-counter" style="display: flex; flex-direction: column; align-items: center; gap: 4px; padding: 8px; border-radius: 8px;">
                                            <i class="ti ti-user f-s-24 text-muted" style="opacity: 0.6;"></i>
                                            <span class="text-secondary f-s-12">Follow</span>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>
        <?php else: ?>
        <!-- No Videos Available Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card hover-effect border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <div class="bg-light-primary h-80 w-80 d-flex-center rounded-circle m-auto mb-3">
                            <i class="ph-duotone ph-video-camera-slash f-s-48 text-primary"></i>
                        </div>
                        <h4 class="text-dark f-w-600 mb-2">Nessun video disponibile</h4>
                        <p class="text-muted mb-3">Al momento non ci sono video popolari da mostrare.</p>
                        <?php if(auth()->guard()->check()): ?>
                        <a href="<?php echo e(route('videos.upload')); ?>" class="btn btn-primary">
                            <i class="ph-duotone ph-upload me-2"></i>
                            Carica il primo video
                        </a>
                        <?php else: ?>
                        <a href="<?php echo e(route('login')); ?>" class="btn btn-primary">
                            <i class="ph-duotone ph-sign-in me-2"></i>
                            Accedi per caricare video
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
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
                                                    <p class="text-muted f-s-12 mb-1">
                                                        <a href="<?php echo e(route('user.show', $poem->user)); ?>" class="text-decoration-none hover-effect">
                                                            <?php echo e($poem->user->getDisplayName()); ?>

                                                        </a>
                                                    </p>
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
                                                    <p class="text-muted f-s-12 mb-1">
                                                        <a href="<?php echo e(route('user.show', $poem->user)); ?>" class="text-decoration-none hover-effect">
                                                            <?php echo e($poem->user->getDisplayName()); ?>

                                                        </a>
                                                    </p>
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
                                <?php else: ?>
                                <a href="<?php echo e(route('login')); ?>" class="btn btn-info btn-sm" title="<?php echo e(__('auth.login_required')); ?>">
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

<!-- Video Player Modal a Tutta Pagina -->
<div class="custom-modal" id="videoPlayerModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999; background: rgba(0,0,0,0.85); backdrop-filter: blur(15px);">
    <div class="modal-content" style="position: relative; width: 100%; height: 100%; display: flex; flex-direction: column;">
        <div class="modal-header" style="background: rgba(0,0,0,0.8); border-bottom: 1px solid rgba(255,255,255,0.1); padding: 1rem; display: flex; justify-content: space-between; align-items: center;">
            <h5 class="modal-title text-white" id="videoPlayerModalLabel">Video Player</h5>
            <button type="button" class="btn-close btn-close-white" onclick="closeVideoModal()" aria-label="Close"></button>
        </div>
        <div class="modal-body" style="flex: 1; padding: 0; position: relative;">
            <!-- Loading indicator -->
            <div class="text-center position-absolute top-50 start-50 translate-middle" id="modalVideoLoading">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Caricamento video...</span>
                </div>
                <p class="mt-2 text-white">Caricamento video...</p>
            </div>

            <!-- Error message -->
            <div class="alert alert-danger position-absolute top-50 start-50 translate-middle" id="modalVideoError" style="display: none; z-index: 1000;">
                <i class="ph-duotone ph-warning f-s-16 me-2"></i>
                <span id="modalErrorMessage">Errore nel caricamento del video</span>
            </div>

            <!-- Video Container -->
            <div class="video-container position-relative d-flex align-items-center justify-content-center" id="modalVideoContainer" style="display: none; padding: 20px;">
                <div class="w-100" style="max-width: 1200px;">
                    <div class="video-container position-relative">
                        <!-- Video Player HTML5 Nativo -->
                        <video
                            id="modalVideoPlayer"
                            class="w-100"
                            style="height: 500px; max-height: 500px; object-fit: cover; border-radius: 12px; box-shadow: 0 15px 40px rgba(0,0,0,0.6); background: #000;"
                            preload="metadata"
                            controls>
                            Il tuo browser non supporta la riproduzione video.
                        </video>

                        <!-- Snap Markers sulla Progress Bar del Player -->
                        <div class="snap-markers-overlay position-absolute" id="modalSnapMarkers" style="bottom: 0; left: 0; right: 0; height: 40px; pointer-events: none;">
                            <!-- Snap markers verranno aggiunti dinamicamente -->
                        </div>
                    </div>
                </div>

                <!-- Pulsante per creare snap con scritta sotto (solo per utenti autenticati) -->
                <?php if(auth()->guard()->check()): ?>
                <div class="position-absolute" id="modalFloatingSnapButton" style="opacity: 1; transition: opacity 0.3s ease; z-index: 10000; top: 20px; right: 20px; display: flex; flex-direction: column; align-items: center; gap: 8px;">
                    <button type="button" class="btn btn-gradient-success hover-effect rounded-circle shadow-lg"
                            style="width: 60px; height: 60px;"
                            onclick="toggleSnapForm()">
                        <img src="<?php echo e(asset('assets/images/snap.png')); ?>" alt="Snap" style="width: 28px; height: 28px; filter: brightness(0) invert(1);">
                    </button>
                    <div class="snap-label" style="color: white; font-size: 11px; text-align: center; white-space: nowrap; text-shadow: 0 1px 2px rgba(0,0,0,0.8); font-weight: 500;">
                        Crea snap
                    </div>
                </div>
                <?php endif; ?>

                <!-- Form inline per creare snap (solo per utenti autenticati) -->
                <?php if(auth()->guard()->check()): ?>
                <div class="position-absolute" id="modalSnapForm" style="display: none; z-index: 10001; top: 20px; right: 20px; background: rgba(0,0,0,0.9); border-radius: 12px; padding: 20px; min-width: 300px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1);">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="text-white mb-0">Crea Snap</h6>
                        <button type="button" class="btn-close btn-close-white" onclick="toggleSnapForm()"></button>
                    </div>
                    <form id="inlineSnapForm">
                        <div class="mb-3">
                            <label for="inlineSnapTitle" class="form-label text-white" style="font-size: 12px;">Titolo (opzionale)</label>
                            <input type="text" class="form-control form-control-sm" id="inlineSnapTitle"  style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white;">
                        </div>
                        <div class="mb-3">
                            <label for="inlineSnapDescription" class="form-label text-white" style="font-size: 12px;">Descrizione (opzionale)</label>
                            <textarea class="form-control form-control-sm" id="inlineSnapDescription" rows="2"  style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; resize: none;"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-white" style="font-size: 12px;">Timestamp: <span id="inlineCurrentTime" class="text-warning">00:00</span></label>
                            <input type="hidden" id="inlineSnapTimestamp" value="0">
                            <input type="hidden" id="inlineSnapVideoId" value="">
                        </div>
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm btn-secondary" onclick="toggleSnapForm()">Annulla</button>
                            <button type="button" class="btn btn-sm btn-primary" onclick="createInlineSnap()">Crea Snap</button>
                        </div>
                    </form>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
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
        // Verifica se l'utente è autenticato
        const isAuthenticated = <?php echo e(auth()->check() ? 'true' : 'false'); ?>;

        if (!isAuthenticated) {
            window.location.href = '<?php echo e(route("login")); ?>';
            return;
        }

        const button = document.getElementById('followBtn' + userId);
        const text = document.getElementById('followText' + userId);

        // Disabilita il pulsante durante la richiesta
        button.disabled = true;

        fetch('/api/follow/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                user_id: userId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Aggiorna il pulsante
                if (data.following) {
                    button.innerHTML = '<i class="ti ti-user-check"></i><span id="followText' + userId + '">Following</span>';
                    button.classList.remove('btn-primary');
                    button.classList.add('btn-success');
                } else {
                    button.innerHTML = '<i class="ti ti-user"></i><span id="followText' + userId + '">Follow</span>';
                    button.classList.remove('btn-success');
                    button.classList.add('btn-primary');
                }

                // Aggiorna i contatori se presenti
                const followersElement = document.querySelector(`[data-user-id="${userId}"] .followers-count`);
                if (followersElement && data.followers_count !== undefined) {
                    followersElement.textContent = data.followers_count;
                }
            } else {
                console.error('Errore follow:', data.message);
            }
        })
        .catch(error => {
            console.error('Errore connessione follow:', error);
        })
        .finally(() => {
            button.disabled = false;
        });
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

// ===== FUNZIONI GLOBALI PER IL MODAL VIDEO =====

// Variabili globali per il modal
let modalVideoPlayer = null;
let modalCurrentVideoTime = 0;
let modalVideoDuration = 0;
let modalSnaps = [];

// Funzione per aprire il modal video
window.openVideoModal = function(videoId) {
    console.log('🎬 Apertura modal video per ID:', videoId);
    console.log('🔍 Debug: Funzione openVideoModal chiamata');

    // Controlla se l'ID del video è valido
    if (!videoId || videoId === 0) {
        console.error('❌ ID video non valido:', videoId);
        return;
    }

    // Mostra il modal personalizzato
    const modal = document.getElementById('videoPlayerModal');
    console.log('🔍 Debug: Modal trovato:', !!modal);

    if (!modal) {
        console.error('❌ Modal video non trovato nel DOM');
        return;
    }

    modal.style.display = 'block';
    document.body.style.overflow = 'hidden'; // Previene lo scroll

    // Carica il video
    loadVideoInModal(videoId);
}

// Funzione per chiudere il modal video
window.closeVideoModal = function() {
    const modal = document.getElementById('videoPlayerModal');
    modal.style.display = 'none';
    document.body.style.overflow = 'auto'; // Ripristina lo scroll

    // Ferma il video se in riproduzione
    const videoPlayer = document.getElementById('modalVideoPlayer');
    if (videoPlayer && !videoPlayer.paused) {
        videoPlayer.pause();
    }

    // Reset variabili
    modalCurrentVideoTime = 0;
    modalVideoDuration = 0;
    modalSnaps = [];
}

// Funzione per caricare il video nel modal
window.loadVideoInModal = async function(videoId) {
    const loadingDiv = document.getElementById('modalVideoLoading');
    const errorDiv = document.getElementById('modalVideoError');
    const containerDiv = document.getElementById('modalVideoContainer');
    const videoPlayer = document.getElementById('modalVideoPlayer');

    // Mostra loading
    loadingDiv.style.display = 'block';
    errorDiv.style.display = 'none';
    containerDiv.style.display = 'none';

    try {
        console.log('🎬 Caricamento video nel modal per ID:', videoId);

                // Prima ottieni i dati del video
        console.log('🔍 Debug: Richiesta API per video ID:', videoId);
        const videoResponse = await fetch(`/api/videos/${videoId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            },
            credentials: 'same-origin'
        });

        console.log('🔍 Debug: Status risposta:', videoResponse.status);
        console.log('🔍 Debug: Headers risposta:', Object.fromEntries(videoResponse.headers.entries()));

        // Controlla se la risposta è JSON
        const contentType = videoResponse.headers.get('content-type');
        console.log('🔍 Debug: Content-Type:', contentType);

        if (!contentType || !contentType.includes('application/json')) {
            // Debug: leggi il contenuto della risposta
            const responseText = await videoResponse.text();
            console.error('🔍 Debug: Risposta non-JSON ricevuta:', responseText.substring(0, 500));
            throw new Error('Video non trovato o non disponibile');
        }

        const videoData = await videoResponse.json();
        console.log('🔍 Debug: Dati video ricevuti:', videoData);

        if (!videoData.success) {
            throw new Error(videoData.message || 'Errore nel caricamento del video');
        }

        const video = videoData.video;

        // Imposta il titolo del modal
        document.getElementById('videoPlayerModalLabel').textContent = video.title;

        // Usa sempre il player HTML5 nativo
        videoPlayer.style.display = 'block';

        console.log('🔗 Richiesta URL diretto per video ID:', videoId);

        // Ottieni l'URL diretto del video da PeerTube
        const urlResponse = await fetch(`/videos/${videoId}/peertube-url`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        });
        const urlData = await urlResponse.json();

        // Gestisci il caso in cui il video è ancora in elaborazione
        if (urlData.status === 'processing') {
            throw new Error('Il video è ancora in elaborazione su PeerTube. Riprova tra qualche minuto.');
        }

        if (urlData.success && urlData.files && urlData.files.length > 0) {
            // Usa il primo file disponibile (migliore qualità)
            const videoFile = urlData.files[0];
            console.log('✅ URL video ottenuto:', videoFile.url);

            // Crea l'elemento source
            const source = document.createElement('source');
            source.src = videoFile.url;
            source.type = 'video/mp4';

            // Rimuovi eventuali source esistenti e aggiungi quello nuovo
            videoPlayer.innerHTML = '';
            videoPlayer.appendChild(source);

            // Forza il caricamento del video
            videoPlayer.load();
        } else {
            throw new Error('Nessuna sorgente video disponibile');
        }

        // Imposta l'ID del video per le funzioni snap
        videoPlayer.setAttribute('data-video-id', video.id);

        // Carica gli snap
        loadSnapsForModal(videoId);

        // Nascondi loading e mostra video
        loadingDiv.style.display = 'none';
        containerDiv.style.display = 'block';

        // Inizializza il player
        initializeModalVideoPlayer(video);

    } catch (error) {
        console.error('❌ Errore caricamento video nel modal:', error);
        loadingDiv.style.display = 'none';
        errorDiv.style.display = 'block';

        // Messaggi di errore più specifici
        let errorMessage = 'Errore nel caricamento del video';
        if (error.message.includes('Video non trovato')) {
            errorMessage = 'Video non trovato o non disponibile';
        } else if (error.message.includes('elaborazione')) {
            errorMessage = 'Il video è ancora in elaborazione. Riprova tra qualche minuto.';
        } else if (error.message.includes('JSON')) {
            errorMessage = 'Errore di comunicazione con il server';
        } else {
            errorMessage = error.message;
        }

        document.getElementById('modalErrorMessage').textContent = errorMessage;
    }
}

// Funzione per caricare gli snap nel modal
window.loadSnapsForModal = function(videoId) {
    console.log('🎯 Caricamento snap per video ID:', videoId);

    fetch(`/api/videos/${videoId}/snaps`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                modalSnaps = data.snaps || [];
                console.log('✅ Snap caricati nel modal:', modalSnaps.length);
                updateModalSnapMarkers();
            } else {
                console.log('⚠️ Nessun snap trovato per il video');
                modalSnaps = [];
                updateModalSnapMarkers();
            }
        })
        .catch(error => {
            console.error('❌ Errore caricamento snap:', error);
            modalSnaps = [];
            updateModalSnapMarkers();
        });
}

// Funzione per inizializzare il player del modal
window.initializeModalVideoPlayer = function(video) {
    const videoPlayer = document.getElementById('modalVideoPlayer');
    modalVideoDuration = video.duration || 60;
    modalVideoPlayer = videoPlayer;

    // Event listeners per il player HTML5
    videoPlayer.addEventListener('loadedmetadata', function() {
        console.log('🎬 Video caricato nel modal - Durata:', videoPlayer.duration);
        modalVideoDuration = videoPlayer.duration || modalVideoDuration;
        updateModalSnapMarkers();
    });

    videoPlayer.addEventListener('timeupdate', function() {
        modalCurrentVideoTime = videoPlayer.currentTime;
    });

    videoPlayer.addEventListener('durationchange', function() {
        console.log('🔄 Durata video cambiata nel modal:', videoPlayer.duration);
        modalVideoDuration = videoPlayer.duration;
        updateModalSnapMarkers();
    });

    videoPlayer.addEventListener('canplay', function() {
        console.log('▶️ Video nel modal pronto per la riproduzione');
    });

    videoPlayer.addEventListener('error', function() {
        console.error('❌ Errore nel video del modal:', videoPlayer.error);
        const errorDiv = document.getElementById('modalVideoError');
        if (errorDiv) {
            errorDiv.style.display = 'block';
            document.getElementById('modalErrorMessage').textContent = 'Errore nella riproduzione del video. Riprova più tardi.';
        }
    });

    // Pulsante snap sempre visibile
    const snapButton = document.getElementById('modalFloatingSnapButton');
    if (snapButton) {
        snapButton.style.opacity = '1';
    }
}

// Funzione per aggiornare i marker degli snap nel modal
window.updateModalSnapMarkers = function() {
    const markersContainer = document.getElementById('modalSnapMarkers');
    if (!markersContainer) return;

    markersContainer.innerHTML = '';

    if (!modalSnaps || modalSnaps.length === 0) return;

    console.log('🎯 Aggiornamento snap markers nel modal - Snap:', modalSnaps.length, 'Durata:', modalVideoDuration);

    // Raggruppa gli snap per timestamp
    const snapsByTimestamp = {};
    modalSnaps.forEach(snap => {
        if (!snapsByTimestamp[snap.timestamp]) {
            snapsByTimestamp[snap.timestamp] = [];
        }
        snapsByTimestamp[snap.timestamp].push(snap);
    });

    // Crea i marker
    Object.keys(snapsByTimestamp).forEach(timestamp => {
        const snapsAtTime = snapsByTimestamp[timestamp];
        const snapCount = snapsAtTime.length;
        const firstSnap = snapsAtTime[0];

        const percentage = (timestamp / modalVideoDuration) * 100;
        const leftPosition = percentage + '%';

        const marker = document.createElement('div');
        marker.className = 'snap-marker position-absolute';
        marker.style.cssText = `left: ${leftPosition}; transform: translateX(-50%); pointer-events: auto; cursor: pointer;`;
        marker.setAttribute('data-timestamp', timestamp);
        marker.onclick = () => seekToTimeInModal(timestamp);
        marker.title = `${firstSnap.title || 'Snap'} (${snapCount} snap)`;

        marker.innerHTML = `
            <div class="snap-indicator bg-success rounded-circle d-flex align-items-center justify-content-center"
                 style="width: 30px; height: 30px; border: 2px solid white; box-shadow: 0 3px 6px rgba(0,0,0,0.4);">
                <img src="<?php echo e(asset('assets/images/snap.png')); ?>" alt="Snap" style="width: 16px; height: 16px; filter: brightness(0) invert(1);">
            </div>
            ${snapCount > 1 ? `
                <div class="position-absolute top-0 end-0 bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center"
                     style="width: 24px; height: 24px; font-size: 12px; font-weight: bold; transform: translate(30%, -30%);">
                    ${snapCount}
                </div>
            ` : ''}
            <div class="snap-tooltip position-absolute bottom-100 start-50 translate-middle-x mb-1 bg-dark text-white rounded p-2"
                 style="font-size: 11px; white-space: nowrap; opacity: 0; transition: opacity 0.2s ease; pointer-events: none;">
                <strong>${firstSnap.title || 'Snap'}</strong>
                ${snapCount > 1 ? `<br><small>+${snapCount - 1} altri</small>` : ''}
            </div>
        `;

        markersContainer.appendChild(marker);
    });

    console.log('✅ Snap markers aggiornati nel modal - Posizionati sulla barra di progressione');

    // Aggiungi event listeners per i tooltip
    const snapMarkers = markersContainer.querySelectorAll('.snap-marker');
    snapMarkers.forEach(marker => {
        const tooltip = marker.querySelector('.snap-tooltip');
        if (tooltip) {
            marker.addEventListener('mouseenter', function() {
                tooltip.style.opacity = '1';
            });
            marker.addEventListener('mouseleave', function() {
                tooltip.style.opacity = '0';
            });
        }
    });
}

// Funzione per saltare al tempo specifico nel modal
window.seekToTimeInModal = function(timestamp) {
    if (modalVideoPlayer) {
        modalVideoPlayer.currentTime = timestamp;
        modalVideoPlayer.play();
    }
}

// Funzione per mostrare/nascondere il form inline degli snap
window.toggleSnapForm = function() {
    const snapForm = document.getElementById('modalSnapForm');
    const snapButton = document.getElementById('modalFloatingSnapButton');

    if (snapForm.style.display === 'none') {
        // Mostra il form
        snapForm.style.display = 'block';
        snapButton.style.display = 'none';

        // Aggiorna il tempo corrente
        updateInlineSnapTime();

        console.log('🎯 Form snap aperto');
    } else {
        // Nascondi il form
        snapForm.style.display = 'none';
        snapButton.style.display = 'flex';

        // Pulisci i campi
        document.getElementById('inlineSnapTitle').value = '';
        document.getElementById('inlineSnapDescription').value = '';

        console.log('🎯 Form snap chiuso');
    }
}

// Funzione per aggiornare il tempo nel form inline
window.updateInlineSnapTime = function() {
    const currentTimeElement = document.getElementById('inlineCurrentTime');
    const timestampElement = document.getElementById('inlineSnapTimestamp');
    const videoIdElement = document.getElementById('inlineSnapVideoId');

    if (currentTimeElement && timestampElement && videoIdElement && modalVideoPlayer) {
        const currentTime = Math.floor(modalVideoPlayer.currentTime);
        currentTimeElement.textContent = formatTimestamp(currentTime);
        timestampElement.value = currentTime;
        videoIdElement.value = modalVideoPlayer.getAttribute('data-video-id');
    }
}

// Funzione per formattare il timestamp
window.formatTimestamp = function(seconds) {
    const minutes = Math.floor(seconds / 60);
    const remainingSeconds = seconds % 60;
    return `${minutes.toString().padStart(2, '0')}:${remainingSeconds.toString().padStart(2, '0')}`;
}

// Funzione per creare lo snap dal form inline
window.createInlineSnap = function() {
    const title = document.getElementById('inlineSnapTitle').value.trim();
    const timestamp = parseInt(document.getElementById('inlineSnapTimestamp').value);
    const videoId = document.getElementById('inlineSnapVideoId').value;

    console.log('🎯 Creazione snap inline - title:', title, 'timestamp:', timestamp, 'videoId:', videoId);

    if (timestamp < 0 || !videoId) {
        console.log('❌ Validazione fallita - timestamp:', timestamp, 'videoId:', videoId);
        return;
    }

    fetch(`/api/videos/${videoId}/snaps`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        },
        body: JSON.stringify({ title: title, timestamp: timestamp })
    })
    .then(response => {
        if (response.status === 401) {
            // Utente non autenticato
            return response.json().then(data => {
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    showErrorMessage('Devi essere autenticato per creare uno snap');
                }
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            console.log('✅ Snap creato con successo:', data.snap);

            // Chiudi il form
            toggleSnapForm();

            // Ricarica gli snap nel modal video
            loadVideoSnaps(videoId);

            // Mostra un messaggio di successo
            showSuccessMessage('Snap creato con successo!');
        } else {
            console.log('❌ Errore nella creazione dello snap:', data);
            showErrorMessage(data.message || 'Errore nella creazione dello snap. Riprova.');
        }
    })
    .catch(error => {
        console.error('❌ Errore nella creazione dello snap:', error);
        showErrorMessage('Errore nella creazione dello snap. Riprova.');
    });
}

// Funzione per mostrare messaggio di successo
window.showSuccessMessage = function(message) {
    const successDiv = document.createElement('div');
    successDiv.className = 'position-fixed';
    successDiv.style.cssText = 'top: 20px; right: 20px; z-index: 10002; background: rgba(40, 167, 69, 0.9); color: white; padding: 12px 20px; border-radius: 8px; font-size: 14px; backdrop-filter: blur(10px);';
    successDiv.textContent = message;
    document.body.appendChild(successDiv);

    setTimeout(() => {
        successDiv.remove();
    }, 3000);
}

// Funzione per caricare gli snap di un video
window.loadVideoSnaps = function(videoId) {
    console.log('🎯 Ricaricamento snap per video ID:', videoId);

    fetch(`/api/videos/${videoId}/snaps`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                modalSnaps = data.snaps || [];
                console.log('✅ Snap ricaricati nel modal:', modalSnaps.length);
                updateModalSnapMarkers();
            } else {
                console.log('⚠️ Nessun snap trovato per il video');
                modalSnaps = [];
                updateModalSnapMarkers();
            }
        })
        .catch(error => {
            console.error('❌ Errore ricaricamento snap:', error);
            modalSnaps = [];
            updateModalSnapMarkers();
        });
}

// Funzione per mostrare messaggio di errore
window.showErrorMessage = function(message) {
    const errorDiv = document.createElement('div');
    errorDiv.className = 'position-fixed';
    errorDiv.style.cssText = 'top: 20px; right: 20px; z-index: 10002; background: rgba(220, 53, 69, 0.9); color: white; padding: 12px 20px; border-radius: 8px; font-size: 14px; backdrop-filter: blur(10px);';
    errorDiv.textContent = message;
    document.body.appendChild(errorDiv);

    setTimeout(() => {
        errorDiv.remove();
    }, 3000);
}

// Event listeners per il modal video (da eseguire quando il DOM è pronto)
document.addEventListener('DOMContentLoaded', function() {
    // Gestione chiusura modal video con ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const videoModal = document.getElementById('videoPlayerModal');
            if (videoModal && videoModal.style.display === 'block') {
                closeVideoModal();
            }
        }
    });

    // Gestione click fuori dal modal per chiudere
    const videoModal = document.getElementById('videoPlayerModal');
    if (videoModal) {
        videoModal.addEventListener('click', function(event) {
            if (event.target === this) {
                closeVideoModal();
            }
        });
    }
});
</script>



<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/home.blade.php ENDPATH**/ ?>