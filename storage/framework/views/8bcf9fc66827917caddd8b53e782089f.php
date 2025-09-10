<?php
use App\Helpers\PlaceholderHelper;
?>

<?php $__env->startSection('title', 'Slam in - Home'); ?>

<?php $__env->startSection('css'); ?>

    <style>
        /* Stili aggiuntivi per lo slider degli eventi */
        .events-slider {
            position: relative;
            margin: 0 -10px;
        }

        .events-slider .autoplay-item {
            padding: 0 10px;
            height: auto;
        }

        .events-slider .card {
            height: 100%;
            transition: transform 0.3s ease;
        }

        .events-slider .card:hover {
            transform: translateY(-5px);
        }

        /* Forza altezza uniforme per le card nello slider */
        .events-slider .slick-track {
            display: flex !important;
        }

        .events-slider .slick-slide {
            height: inherit;
        }

        .events-slider .slick-slide > div {
            height: 100%;
        }

        .events-slider .autoplay-item {
            height: 100%;
        }

        /* Forza altezza uniforme delle card */
        .events-slider .slick-slide {
            height: auto !important;
        }

        .events-slider .slick-slide > div {
            height: 100% !important;
        }

        .events-slider .autoplay-item {
            height: 100% !important;
        }

        .events-slider .card {
            height: 100% !important;
            display: flex !important;
            flex-direction: column !important;
        }

        .events-slider .card-body {
            height: 100% !important;
            display: flex !important;
            flex-direction: column !important;
            flex: 1 !important;
        }

        .events-slider .card-body > *:not(:last-child) {
            flex-shrink: 0 !important;
        }

        .events-slider .d-flex.justify-content-between {
            margin-top: auto !important;
            flex-shrink: 0 !important;
        }

        /* Forza altezza minima per le card */
        .events-slider .card {
            min-height: 400px !important;
        }

        /* Stili per il videos slider */
        .center-mode {
            position: relative;
            overflow: hidden;
        }

        .center-mode .slick-list {
            overflow: hidden;
            margin: 0 60px;
        }

        .center-mode .slick-track {
            display: flex;
            align-items: center;
        }

        .center-mode .slick-slide {
            opacity: 0.5;
            transition: opacity 0.3s ease;
        }

        .center-mode .slick-slide.slick-center {
            opacity: 1;
        }

        .center-mode .slick-slide .item {
            padding: 0 10px;
        }

        .center-mode .slick-slide .item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }


        /* Frecce personalizzate */
        .center-mode .slick-prev,
        .center-mode .slick-next {
            z-index: 10;
            width: 40px;
            height: 40px;
            background: rgba(0, 0, 0, 0.5);
            border-radius: 50%;
            color: white;
        }

        .center-mode .slick-prev:hover,
        .center-mode .slick-next:hover {
            background: rgba(0, 0, 0, 0.7);
        }

        .center-mode .slick-prev {
            left: 10px;
        }

        .center-mode .slick-next {
            right: 10px;
        }

        .center-mode .slick-prev:before,
        .center-mode .slick-next:before {
            font-size: 20px;
            color: white;
        }

    </style>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('main-content'); ?>
    <div class="page-content">
        <div class="container-fluid">

            <!-- Hero Carousel -->
            <?php if($carousels && $carousels->count() > 0): ?>
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body p-0">
                                <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel"
                                    data-bs-interval="5000">
                                    <?php if($carousels && $carousels->count() > 1): ?>
                                        <div class="carousel-indicators">
                                            <?php $__currentLoopData = $carousels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $carousel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <button type="button" data-bs-target="#heroCarousel"
                                                    data-bs-slide-to="<?php echo e($index); ?>"
                                                    class="bg-primary <?php echo e($index === 0 ? 'active' : ''); ?>"
                                                    aria-current="<?php echo e($index === 0 ? 'true' : 'false'); ?>"
                                                    aria-label="Slide <?php echo e($index + 1); ?>"></button>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="carousel-inner">
                                        <?php $__currentLoopData = $carousels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $carousel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="carousel-item <?php echo e($index === 0 ? 'active' : ''); ?>">
                                                <?php if($carousel->video_path && $carousel->videoUrl): ?>
                                                    <video class="d-block w-100" autoplay muted loop
                                                        style="height: 400px; object-fit: cover;">
                                                        <source src="<?php echo e($carousel->videoUrl); ?>" type="video/mp4">
                                                    </video>
                                                    <div class="carousel-caption d-none d-md-block bg-white rounded-3 p-4 shadow" style="position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); max-width: 80%; text-align: center;">
                                                        <h5 class="f-w-600 f-s-24 mb-3 text-dark"><?php echo e($carousel->content_title ?? $carousel->title); ?></h5>
                                                        <?php if($carousel->content_description ?? $carousel->description): ?>
                                                            <p class="mb-4 f-s-16 text-primary"><?php echo e($carousel->content_description ?? $carousel->description); ?></p>
                                                        <?php endif; ?>
                                                        <?php if($carousel->content_url ?? $carousel->link_url): ?>
                                                            <a href="<?php echo e($carousel->content_url ?? $carousel->link_url); ?>" class="btn btn-primary btn-lg hover-effect">
                                                                <i class="ph-duotone ph-arrow-right f-s-16 me-2"></i>
                                                                <?php echo e($carousel->link_text ?? 'Visualizza'); ?>

                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php elseif($carousel->image_path && $carousel->imageUrl): ?>
                                                    <img src="<?php echo e($carousel->imageUrl); ?>" class="d-block w-100"
                                                        alt="<?php echo e($carousel->title); ?>"
                                                        style="height: 400px; object-fit: cover;">
                                                    <div class="carousel-caption d-none d-md-block bg-white rounded-3 p-4 shadow" style="position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); max-width: 80%; text-align: center;">
                                                        <h5 class="f-w-600 f-s-24 mb-3 text-dark"><?php echo e($carousel->content_title ?? $carousel->title); ?></h5>
                                                        <?php if($carousel->content_description ?? $carousel->description): ?>
                                                            <p class="mb-4 f-s-16 text-primary"><?php echo e($carousel->content_description ?? $carousel->description); ?></p>
                                                        <?php endif; ?>
                                                        <?php if($carousel->content_url ?? $carousel->link_url): ?>
                                                            <a href="<?php echo e($carousel->content_url ?? $carousel->link_url); ?>" class="btn btn-primary btn-lg hover-effect">
                                                                <i class="ph-duotone ph-arrow-right f-s-16 me-2"></i>
                                                                <?php echo e($carousel->link_text ?? 'Visualizza'); ?>

                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php elseif($carousel->content_image_url): ?>
                                                    <img src="<?php echo e($carousel->content_image_url); ?>" class="d-block w-100"
                                                        alt="<?php echo e($carousel->content_title ?? $carousel->title); ?>"
                                                        style="height: 400px; object-fit: cover;">
                                                    <div class="carousel-caption d-none d-md-block bg-white rounded-3 p-4 shadow" style="position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); max-width: 80%; text-align: center;">
                                                        <h5 class="f-w-600 f-s-24 mb-3 text-dark"><?php echo e($carousel->content_title ?? $carousel->title); ?></h5>
                                                        <?php if($carousel->content_description ?? $carousel->description): ?>
                                                            <p class="mb-4 f-s-16 text-primary"><?php echo e($carousel->content_description ?? $carousel->description); ?></p>
                                                        <?php endif; ?>
                                                        <?php if($carousel->content_url ?? $carousel->link_url): ?>
                                                            <a href="<?php echo e($carousel->content_url ?? $carousel->link_url); ?>" class="btn btn-primary btn-lg hover-effect">
                                                                <i class="ph-duotone ph-arrow-right f-s-16 me-2"></i>
                                                                <?php echo e($carousel->link_text ?? 'Visualizza'); ?>

                                                            </a>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php else: ?>
                                                    <!-- Placeholder personalizzato con contenuto del carosello -->
                                                    <?php if($carousel->content_type === 'poem'): ?>
                                                        <?php
                                                            $poemColor = App\Models\PlaceholderSetting::getSettings()->poem_placeholder_color;
                                                        ?>
                                                        <div class="d-block w-100 d-flex align-items-center justify-content-center position-relative" style="height: 400px; background-color: <?php echo e($poemColor); ?>;">
                                                            <div class="text-center text-white" style="margin-top: -120px;">
                                                                <div style="font-size: 80px;">📖</div>
                                                            </div>
                                                        </div>
                                                        <div class="carousel-caption d-none d-md-block bg-white rounded-3 p-4 shadow" style="position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); max-width: 80%; text-align: center;">
                                                            <h5 class="f-w-600 f-s-24 mb-3 text-dark"><?php echo e($carousel->content_title ?? $carousel->title ?? 'Poesia senza titolo'); ?></h5>
                                                            <?php if($carousel->content_description ?? $carousel->description): ?>
                                                                <p class="mb-4 f-s-16 text-primary"><?php echo e(Str::limit($carousel->content_description ?? $carousel->description, 100)); ?></p>
                                                            <?php endif; ?>
                                                            <?php if($carousel->content_url ?? $carousel->link_url): ?>
                                                                <a href="<?php echo e($carousel->content_url ?? $carousel->link_url); ?>" class="btn btn-primary btn-lg hover-effect">
                                                                    <i class="ph-duotone ph-arrow-right f-s-16 me-2"></i>
                                                                    <?php echo e($carousel->link_text ?? 'Leggi poesia'); ?>

                                                                </a>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php elseif($carousel->content_type === 'article'): ?>
                                                        <?php
                                                            $articleColor = App\Models\PlaceholderSetting::getSettings()->article_placeholder_color;
                                                        ?>
                                                        <div class="d-block w-100 d-flex align-items-center justify-content-center position-relative" style="height: 400px; background-color: <?php echo e($articleColor); ?>;">
                                                            <div class="text-center text-white" style="margin-top: -120px;">
                                                                <div style="font-size: 80px;">📰</div>
                                                            </div>
                                                        </div>
                                                        <div class="carousel-caption d-none d-md-block bg-white rounded-3 p-4 shadow" style="position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); max-width: 80%; text-align: center;">
                                                            <h5 class="f-w-600 f-s-24 mb-3 text-dark"><?php echo e($carousel->content_title ?? $carousel->title ?? 'Articolo senza titolo'); ?></h5>
                                                            <?php if($carousel->content_description ?? $carousel->description): ?>
                                                                <p class="mb-4 f-s-16 text-primary"><?php echo e(Str::limit($carousel->content_description ?? $carousel->description, 100)); ?></p>
                                                            <?php endif; ?>
                                                            <?php if($carousel->content_url ?? $carousel->link_url): ?>
                                                                <a href="<?php echo e($carousel->content_url ?? $carousel->link_url); ?>" class="btn btn-primary btn-lg hover-effect">
                                                                    <i class="ph-duotone ph-arrow-right f-s-16 me-2"></i>
                                                                    <?php echo e($carousel->link_text ?? 'Leggi articolo'); ?>

                                                                </a>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="d-block w-100 bg-gradient-primary d-flex align-items-center justify-content-center" style="height: 400px;">
                                                            <div class="text-center text-white" style="margin-top: -120px;">
                                                                <i class="ph-duotone ph-image f-s-48"></i>
                                                            </div>
                                                        </div>
                                                        <div class="carousel-caption d-none d-md-block bg-white rounded-3 p-4 shadow" style="position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); max-width: 80%; text-align: center;">
                                                            <h5 class="f-w-600 f-s-24 mb-3 text-dark"><?php echo e($carousel->title); ?></h5>
                                                            <?php if($carousel->description): ?>
                                                                <p class="mb-4 f-s-16 text-primary"><?php echo e($carousel->description); ?></p>
                                                            <?php endif; ?>
                                                            <?php if($carousel->link_url): ?>
                                                                <a href="<?php echo e($carousel->link_url); ?>" class="btn btn-primary btn-lg hover-effect">
                                                                    <i class="ph-duotone ph-arrow-right f-s-16 me-2"></i>
                                                                    <?php echo e($carousel->link_text ?? 'Visualizza'); ?>

                                                                </a>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                    <?php if($carousels && $carousels->count() > 1): ?>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel"
                                            data-bs-slide="prev" style="background: none; border: none; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                            <i class="ph ph-arrow-left f-s-32 text-white" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8);"></i>
                                            <span class="visually-hidden"><?php echo e(__('home.carousel.previous')); ?></span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel"
                                            data-bs-slide="next" style="background: none; border: none; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                                            <i class="ph ph-arrow-right f-s-32 text-white" style="text-shadow: 2px 2px 4px rgba(0,0,0,0.8);"></i>
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
            <?php if($recentEvents && $recentEvents->count() > 0): ?>
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="ph-duotone ph-calendar f-s-16 me-2"></i>
                                    <?php echo e(__('home.upcoming_events')); ?>

                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="events-slider app-arrow" id="events-slider">
                                    <?php $__currentLoopData = $recentEvents->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="autoplay-item">
                                            <div class="card overflow-hidden hover-effect h-100">
                                                <?php if($event->image_url): ?>
                                                    <img src="<?php echo e($event->image_url); ?>" class="card-img-top"
                                                        alt="<?php echo e($event->title); ?>"
                                                        style="height: 200px; object-fit: cover;">
                                                <?php else: ?>
                                                    <?php
                                                        $fallbackImages = [
                                                            'assets/images/background/default-event-1.webp',
                                                            'assets/images/background/default-event-2.webp',
                                                            'assets/images/background/default-event-3.webp',
                                                            'assets/images/background/default-event-4.webp',
                                                        ];
                                                        $randomImage = $fallbackImages[array_rand($fallbackImages)];
                                                    ?>
                                                    <img src="<?php echo e(asset($randomImage)); ?>" class="card-img-top"
                                                        alt="<?php echo e($event->title); ?>"
                                                        style="height: 200px; object-fit: cover;">
                                                <?php endif; ?>
                                                <div class="card-body d-flex flex-column">
                                                    <h5 class="card-title f-w-600"><?php echo e($event->title); ?></h5>
                                                    <p class="card-text text-muted f-s-14">
                                                        <i class="ph-duotone ph-map-pin f-s-12 me-1"></i>
                                                        <?php echo e($event->venue_name); ?>

                                                    </p>

                                                    <?php if($event->description): ?>
                                                        <p class="card-text"><?php echo e(Str::limit($event->description, 80)); ?></p>
                                                    <?php endif; ?>
                                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                                        <p class="card-text">
                                                            <small class="text-body-secondary">
                                                                <i class="ph-duotone ph-calendar f-s-12 me-1"></i>
                                                                <?php echo e($event->start_datetime->format('d/m/Y H:i')); ?>


                                                            </small>
                                                        </p>
                                                        <div class="d-flex gap-1 justify-content-end">
                                                            <?php if(auth()->guard()->check()): ?>

                                                                <a href="#" role="button" class="btn btn-sm py-1 px-2 d-flex align-items-center"
                                                                    data-event-id="<?php echo e($event->id); ?>"
                                                                    title="Aggiungi/<?php echo e(__('wishlist.remove_from_wishlist')); ?>">
                                                                    <img src="<?php echo e(asset('assets/images/like.png')); ?>"
                                                                        alt="Like" style="width: 25px; height: 25px;">
                                                                </a>
                                                            <?php else: ?>
                                                                <a href="<?php echo e(route('login')); ?>" role="button"
                                                                    class="btn btn-sm py-1 px-2 d-flex align-items-center"
                                                                    title="<?php echo e(__('auth.login_required')); ?>">
                                                                    <img src="<?php echo e(asset('assets/images/like.png')); ?>"
                                                                        alt="Like" style="width: 25px; height: 25px;">
                                                                </a>
                                                            <?php endif; ?>


                                                            <a href="<?php echo e(route('events.show', $event)); ?>" role="button"
                                                                class="btn btn-sm btn-primary py-1 px-2 d-flex align-items-center">
                                                                <i class="ph-duotone ph-info f-s-12 me-1"></i><?php echo e(__('home.details')); ?>

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


            <!-- Video Slider Section -->
                <div class="row mb-4">
                    <div class="col-12">
                    <div class="card equal-card">
                        <div class="card-header">
                            <h5><?php echo e(__('home.new_videos')); ?></h5>
                            </div>
                            <div class="card-body">
                            <div class="events-slider app-arrow" id="videos-slider">
                                <?php $__currentLoopData = $recentVideos->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="autoplay-item">
                                            <div class="card overflow-hidden hover-effect h-100">
                                                <?php if($video->thumbnail_path): ?>
                                                    <div class="position-relative" style="cursor: pointer;" onclick="openVideoModal(<?php echo e($video->id); ?>)">
                                                        <img src="<?php echo e($video->thumbnail_url); ?>" class="card-img-top"
                                                            alt="<?php echo e($video->title); ?>"
                                                            style="height: 200px; object-fit: cover;">
                                                        <div class="position-absolute top-50 start-50 translate-middle">
                                                            <div class="play-button bg-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
                                                                <i class="ph-duotone ph-play f-s-24 text-primary"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    <?php
                                                        $fallbackImages = [
                                                            'assets/images/background/default-video-1.webp',
                                                            'assets/images/background/default-video-2.webp',
                                                            'assets/images/background/default-video-3.webp',
                                                            'assets/images/background/default-video-4.webp',
                                                        ];
                                                        $randomImage = $fallbackImages[array_rand($fallbackImages)];
                                                    ?>
                                                    <a href="<?php echo e(route('videos.show', $video)); ?>"><img src="<?php echo e(asset($randomImage)); ?>" class="card-img-top"
                                                        alt="<?php echo e($video->title); ?>"
                                                        style="height: 200px; object-fit: cover;"></a>
                                                <?php endif; ?>
                                                <div class="card-body d-flex flex-column">
                                                    <h5 class="card-title f-w-600"><?php echo e($video->title); ?></h5>
                                                    <p class="card-text text-muted f-s-14">
                                                        <i class="ph-duotone ph-user f-s-12 me-1"></i>
                                                        <?php echo e($video->user->getDisplayName()); ?>

                                                    </p>

                                                    <?php if($video->description): ?>
                                                        <p class="card-text"><?php echo e(Str::limit($video->description, 80)); ?></p>
                                                    <?php endif; ?>
                                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                                        <?php if (isset($component)) { $__componentOriginal74a3c73fa2014a1304a7d68280593565 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal74a3c73fa2014a1304a7d68280593565 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.social-view-counter','data' => ['content' => $video,'type' => 'video','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('social-view-counter'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['content' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($video),'type' => 'video','size' => 'sm']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal74a3c73fa2014a1304a7d68280593565)): ?>
<?php $attributes = $__attributesOriginal74a3c73fa2014a1304a7d68280593565; ?>
<?php unset($__attributesOriginal74a3c73fa2014a1304a7d68280593565); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal74a3c73fa2014a1304a7d68280593565)): ?>
<?php $component = $__componentOriginal74a3c73fa2014a1304a7d68280593565; ?>
<?php unset($__componentOriginal74a3c73fa2014a1304a7d68280593565); ?>
<?php endif; ?>
                                                        <div class="d-flex gap-1 justify-content-end">
                                                            <?php if (isset($component)) { $__componentOriginal723641259025d9a0842581325b5584a2 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal723641259025d9a0842581325b5584a2 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.social-like-button','data' => ['content' => $video,'type' => 'video','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('social-like-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['content' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($video),'type' => 'video','size' => 'sm']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal723641259025d9a0842581325b5584a2)): ?>
<?php $attributes = $__attributesOriginal723641259025d9a0842581325b5584a2; ?>
<?php unset($__attributesOriginal723641259025d9a0842581325b5584a2); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal723641259025d9a0842581325b5584a2)): ?>
<?php $component = $__componentOriginal723641259025d9a0842581325b5584a2; ?>
<?php unset($__componentOriginal723641259025d9a0842581325b5584a2); ?>
<?php endif; ?>
                                                            <?php if (isset($component)) { $__componentOriginal7abebe354dff9d15a9ac129dc497114d = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7abebe354dff9d15a9ac129dc497114d = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.social-snap-button','data' => ['content' => $video,'type' => 'video','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('social-snap-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['content' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($video),'type' => 'video','size' => 'sm']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7abebe354dff9d15a9ac129dc497114d)): ?>
<?php $attributes = $__attributesOriginal7abebe354dff9d15a9ac129dc497114d; ?>
<?php unset($__attributesOriginal7abebe354dff9d15a9ac129dc497114d); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7abebe354dff9d15a9ac129dc497114d)): ?>
<?php $component = $__componentOriginal7abebe354dff9d15a9ac129dc497114d; ?>
<?php unset($__componentOriginal7abebe354dff9d15a9ac129dc497114d); ?>
<?php endif; ?>
                                                            <?php if (isset($component)) { $__componentOriginal6f504f396e2242cb757c367dd734f8bb = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal6f504f396e2242cb757c367dd734f8bb = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.social-comment-button','data' => ['content' => $video,'type' => 'video','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('social-comment-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['content' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($video),'type' => 'video','size' => 'sm']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal6f504f396e2242cb757c367dd734f8bb)): ?>
<?php $attributes = $__attributesOriginal6f504f396e2242cb757c367dd734f8bb; ?>
<?php unset($__attributesOriginal6f504f396e2242cb757c367dd734f8bb); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal6f504f396e2242cb757c367dd734f8bb)): ?>
<?php $component = $__componentOriginal6f504f396e2242cb757c367dd734f8bb; ?>
<?php unset($__componentOriginal6f504f396e2242cb757c367dd734f8bb); ?>
<?php endif; ?>

                                                            <?php if (isset($component)) { $__componentOriginalcab7032bfdfb17b0d85d7225950dd852 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcab7032bfdfb17b0d85d7225950dd852 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.report-button','data' => ['content' => $video,'type' => 'video','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('report-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['content' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($video),'type' => 'video','size' => 'sm']); ?>
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
                            <?php echo e(__('home.new_users')); ?>

                        </h5>
                    </div>
                    <?php $__currentLoopData = $newUsers->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="card">
                                <div class="card-body">
                                    <div class="profile-container"
                                        onclick="window.location.href='<?php echo e(route('user.show', $user)); ?>'"
                                        style="cursor: pointer;">
                                        <div class="image-details">
                                            <div class="profile-image">
                                                <img src="<?php echo e($user->banner_image_url ?? asset('assets/images/avatar/default-banner.webp?v=1')); ?>"
                                                    alt="<?php echo e($user->name); ?>" class="w-100 h-100"
                                                    style="object-fit: cover;">
                                            </div>
                                            <div class="profile-pic">
                                                <div class="avatar-upload">
                                                    <div class="avatar-preview">
                                                        <div id="imgPreview">

                                                            <img src="<?php echo e($user->profile_photo_url); ?>"
                                                                alt="<?php echo e($user->name); ?>" class="w-100 h-100"
                                                                style="object-fit: cover;">

                                                            <div
                                                                class="bg-gradient-success rounded-circle d-flex align-items-center justify-content-center w-100 h-100">
                                                                <span
                                                                    class="text-white fw-bold f-s-20"><?php echo e(strtoupper(substr(trim($user->name), 0, 2)) ?: 'U'); ?></span>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="person-details">
                                            <h4 class="f-w-600 mb-1"><?php echo e($user->name); ?>

                                                <?php if($user->nickname): ?>
                                                    <span class="text-muted f-s-14 fw-normal">(<?php echo e($user->nickname); ?>)</span>
                                                <?php endif; ?>
                                                <?php if($user->verified): ?>
                                                    <img src="https://phplaravel-1384472-5380003.cloudwaysapps.com/../assets/images/profile-app/01.png"
                                                        class="w-20 h-20" alt="instagram-check-mark">
                                                <?php endif; ?>
                                            </h4>
                                            <p class="f-s-12 mb-3"><?php echo e($user->city ?? __('home.location_not_specified')); ?></p>
                                            <div class="details">
                                                <div>
                                                    <h4 class="text-primary"><?php echo e($user->poems_count); ?></h4>
                                                    <p class="text-secondary f-s-12"><?php echo e(__('common.poems')); ?></p>
                                                </div>
                                                <div>
                                                    <h4 class="text-primary"><?php echo e($user->articles_count); ?></h4>
                                                    <p class="text-secondary f-s-12"><?php echo e(__('common.articles')); ?></p>
                                                </div>
                                                <div>
                                                    <h4 class="text-primary"><?php echo e(number_format($user->total_interactions)); ?></h4>
                                                    <p class="text-secondary f-s-12"><?php echo e(__('home.interactions')); ?></p>
                                                </div>
                                            </div>
                                            <div class="my-2">
                                                <?php if(auth()->guard()->check()): ?>
                                                    <button type="button"
                                                        class="btn <?php echo e($user->is_followed_by_current_user ?? false ? 'btn-success' : 'btn-primary'); ?> b-r-22"
                                                        onclick="event.stopPropagation(); followUser(<?php echo e($user->id); ?>)"
                                                        id="followBtn<?php echo e($user->id); ?>">
                                                        <i
                                                            class="ti <?php echo e($user->is_followed_by_current_user ?? false ? 'ti-user-check' : 'ti-user'); ?>"></i>
                                                        <span
                                                            id="followText<?php echo e($user->id); ?>"><?php echo e($user->is_followed_by_current_user ?? false ? 'Following' : 'Follow'); ?></span>
                                                    </button>
                                                <?php else: ?>
                                                    <div class="text-center">
                                                        <div class="social-counter"
                                                            style="display: flex; flex-direction: column; align-items: center; gap: 4px; padding: 8px; border-radius: 8px;">
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
                                <h4 class="text-dark f-w-600 mb-2"><?php echo e(__('home.no_videos_available')); ?></h4>
                                <p class="text-muted mb-3"><?php echo e(__('home.no_videos_description')); ?></p>
                                <?php if(auth()->guard()->check()): ?>
                                    <a href="<?php echo e(route('videos.upload')); ?>" class="btn btn-primary">
                                        <i class="ph-duotone ph-upload me-2"></i>
                                        <?php echo e(__('home.upload_first_video')); ?>

                                    </a>
                                <?php else: ?>
                                    <a href="<?php echo e(route('login')); ?>" class="btn btn-primary">
                                        <i class="ph-duotone ph-sign-in me-2"></i>
                                        <?php echo e(__('home.login_to_upload')); ?>

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
                        <div
                            class="card-header bg-gradient-info text-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="ph-duotone ph-book-open f-s-16 me-2"></i>
                                <?php echo e(__('home.poetry_section')); ?>

                            </h5>
                            <div class="d-flex align-items-center justify-content-center">
                                <span id="poetryToggleLabelLeft"
                                    class="text-primary f-s-12 me-2"><?php echo e(__('common.new')); ?></span>
                                <div class="form-check form-switch mx-2">
                                    <input class="form-check-input" type="checkbox" id="poetryToggle"
                                        onchange="togglePoetryContent(this.checked ? 'popular' : 'new')">
                                </div>
                                <span id="poetryToggleLabelRight"
                                    class="text-muted f-s-12 ms-2"><?php echo e(__('common.popular')); ?></span>
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
                                                                <div class="rounded overflow-hidden"
                                                                    style="width: 60px; height: 60px;">
                                                                    <?php if($poem->thumbnail_url): ?>
                                                                        <img src="<?php echo e($poem->thumbnail_url); ?>"
                                                                            alt="<?php echo e($poem->title); ?>" class="w-100 h-100"
                                                                            style="object-fit: cover;">
                                                                        <div
                                                                            class="position-absolute top-0 start-0 end-0 bottom-0 bg-dark opacity-20">
                                                                        </div>
                                                                        <div
                                                                            class="position-absolute top-50 start-50 translate-middle">
                                                                            <i
                                                                                class="ph-duotone ph-book-open f-s-12 text-white"></i>
                                                                        </div>
                                                                    <?php else: ?>
                                                                        <?php echo PlaceholderHelper::getPoemPlaceholderHtml(60, 60, 'w-100 h-100', route('poems.show', $poem->slug)); ?>

                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="card-title f-w-600 f-s-14 mb-1 text-primary">
                                                                <a href="<?php echo e(route('poems.show', $poem->slug)); ?>" class="text-decoration-none hover-effect">
                                                                    <?php echo e($poem->title ?: 'Poesia senza titolo'); ?>

                                                                </a>
                                                            </h6>
                                                            <p class="text-muted f-s-12 mb-1">
                                                                <a href="<?php echo e(route('user.show', $poem->user)); ?>"
                                                                    class="text-decoration-none hover-effect">
                                                                    <?php echo e($poem->user->getDisplayName()); ?>

                                                                </a>
                                                            </p>
                                                            <p class="text-muted f-s-11 mb-2">
                                                                <a href="<?php echo e(route('poems.show', $poem->slug)); ?>" class="text-decoration-none hover-effect">
                                                                    <?php echo e(Str::limit(strip_tags($poem->content), 100)); ?>

                                                                </a>
                                                            </p>
                                                            <?php if($poem->category): ?>
                                                                <span class="badge bg-light-info f-s-10 mb-2"><?php echo e($poem->category); ?></span>
                                                            <?php endif; ?>
                                                            <div class="d-flex align-items-center gap-2">
                                                                <?php if (isset($component)) { $__componentOriginal74a3c73fa2014a1304a7d68280593565 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal74a3c73fa2014a1304a7d68280593565 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.social-view-counter','data' => ['content' => $poem,'type' => 'poem','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('social-view-counter'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['content' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($poem),'type' => 'poem','size' => 'sm']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal74a3c73fa2014a1304a7d68280593565)): ?>
<?php $attributes = $__attributesOriginal74a3c73fa2014a1304a7d68280593565; ?>
<?php unset($__attributesOriginal74a3c73fa2014a1304a7d68280593565); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal74a3c73fa2014a1304a7d68280593565)): ?>
<?php $component = $__componentOriginal74a3c73fa2014a1304a7d68280593565; ?>
<?php unset($__componentOriginal74a3c73fa2014a1304a7d68280593565); ?>
<?php endif; ?>
                                                                <small class="text-muted f-s-11">
                                                                    <i
                                                                        class="ph-duotone ph-clock f-s-10 me-1"></i><?php echo e($poem->created_at->diffForHumans()); ?>

                                                                </small>
                                                            </div>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <?php if($poem->slug): ?>
                                                                <a href="<?php echo e(route('poems.show', $poem->slug)); ?>"
                                                                    class="btn btn-primary btn-sm hover-effect">
                                                                    <i class="ph-duotone ph-book-open f-s-12 me-1"></i>
                                                                    Leggi
                                                                </a>
                                                            <?php else: ?>
                                                                <span class="btn btn-secondary btn-sm" title="<?php echo e(__('common.poem_not_available')); ?>">
                                                                    <i class="ph-duotone ph-book-open f-s-12 me-1"></i>
                                                                    N/A
                                                                </span>
                                                            <?php endif; ?>
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
                                                                <div class="rounded overflow-hidden"
                                                                    style="width: 60px; height: 60px;">
                                                                    <?php if($poem->thumbnail_url): ?>
                                                                        <img src="<?php echo e($poem->thumbnail_url); ?>"
                                                                            alt="<?php echo e($poem->title); ?>" class="w-100 h-100"
                                                                            style="object-fit: cover;">
                                                                        <div
                                                                            class="position-absolute top-0 start-0 end-0 bottom-0 bg-dark opacity-20">
                                                                        </div>
                                                                        <div
                                                                            class="position-absolute top-50 start-50 translate-middle">
                                                                            <i
                                                                                class="ph-duotone ph-book-open f-s-12 text-white"></i>
                                                                        </div>
                                                                    <?php else: ?>
                                                                        <?php echo PlaceholderHelper::getPoemPlaceholderHtml(60, 60, 'w-100 h-100', route('poems.show', $poem->slug)); ?>

                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="card-title f-w-600 f-s-14 mb-1 text-primary">
                                                                <?php echo e(Str::limit($poem->title, 40)); ?></h6>
                                                            <p class="text-muted f-s-12 mb-1">
                                                                <a href="<?php echo e(route('user.show', $poem->user)); ?>"
                                                                    class="text-decoration-none hover-effect">
                                                                    <?php echo e($poem->user->getDisplayName()); ?>

                                                                </a>
                                                            </p>
                                                            <div class="d-flex align-items-center">
                                                                <small class="text-muted f-s-11 me-3">
                                                                    <i
                                                                        class="ph-duotone ph-eye f-s-10 me-1"></i><?php echo e(number_format($poem->views_count)); ?>

                                                                </small>
                                                                <small class="text-muted f-s-11">
                                                                    <i
                                                                        class="ph-duotone ph-thumbs-up f-s-10 me-1"></i><?php echo e(number_format($poem->likes_count)); ?>

                                                                </small>
                                                            </div>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <?php if($poem->slug): ?>
                                                                <a href="<?php echo e(route('poems.show', $poem->slug)); ?>"
                                                                    class="btn btn-primary btn-sm hover-effect">
                                                                    <i class="ph-duotone ph-book-open f-s-12 me-1"></i>
                                                                    Leggi
                                                                </a>
                                                            <?php else: ?>
                                                                <span class="btn btn-secondary btn-sm" title="<?php echo e(__('common.poem_not_available')); ?>">
                                                                    <i class="ph-duotone ph-book-open f-s-12 me-1"></i>
                                                                    N/A
                                                                </span>
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

                <!-- Articles Section (Right) -->
                <div class="col-lg-6 col-md-12 mb-4">
                    <div class="card">
                        <div
                            class="card-header bg-gradient-warning text-white d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="ph-duotone ph-newspaper f-s-16 me-2"></i>
                                <?php echo e(__('home.articles_section')); ?>

                            </h5>
                            <div class="d-flex align-items-center justify-content-center">
                                <span id="articlesToggleLabelLeft"
                                    class="text-primary f-s-12 me-2"><?php echo e(__('common.new')); ?></span>
                                <div class="form-check form-switch mx-2">
                                    <input class="form-check-input" type="checkbox" id="articlesToggle"
                                        onchange="toggleArticlesContent(this.checked ? 'popular' : 'new')">
                                </div>
                                <span id="articlesToggleLabelRight"
                                    class="text-muted f-s-12 ms-2"><?php echo e(__('common.popular')); ?></span>
                            </div>
                        </div>
                        <div class="card-body">
                            <!-- New Articles Content -->
                            <div id="newArticlesContent">
                                <div class="row">
                                    <?php $__currentLoopData = $recentArticles ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $article): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="col-12 mb-3">
                                            <div class="card  hover-effect border-info">
                                                <div class="card-body pa-15">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-3">
                                                            <div class="position-relative">
                                                                <div class="rounded overflow-hidden"
                                                                    style="width: 60px; height: 60px;">
                                                                    <?php if($article->featured_image_url): ?>
                                                                        <img src="<?php echo e($article->featured_image_url); ?>"
                                                                            alt="<?php echo e($article->title); ?>" class="w-100 h-100"
                                                                            style="object-fit: cover;">
                                                                        <div
                                                                            class="position-absolute top-0 start-0 end-0 bottom-0 bg-dark opacity-20">
                                                                        </div>
                                                                        <div
                                                                            class="position-absolute top-50 start-50 translate-middle">
                                                                            <i
                                                                                class="ph-duotone ph-newspaper f-s-12 text-white"></i>
                                                                        </div>
                                                                    <?php else: ?>
                                                                        <?php echo PlaceholderHelper::getArticlePlaceholderHtml(60, 60, 'w-100 h-100', route('articles.show', $article->slug)); ?>

                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="card-title f-w-600 f-s-14 mb-1 text-primary">
                                                                <a href="<?php echo e(route('articles.show', $article->slug)); ?>" class="text-decoration-none hover-effect">
                                                                    <?php echo e($article->title ?: 'Articolo senza titolo'); ?>

                                                                </a>
                                                            </h6>
                                                            <p class="text-muted f-s-12 mb-1">
                                                                <a href="<?php echo e(route('user.show', $article->user)); ?>"
                                                                    class="text-decoration-none hover-effect">
                                                                    <?php echo e($article->user->getDisplayName()); ?>

                                                                </a>
                                                            </p>
                                                            <p class="text-muted f-s-11 mb-2">
                                                                <a href="<?php echo e(route('articles.show', $article->slug)); ?>" class="text-decoration-none hover-effect">
                                                                    <?php echo e(Str::limit($article->excerpt ?: strip_tags($article->content), 100)); ?>

                                                                </a>
                                                            </p>
                                                            <?php if($article->category): ?>
                                                                <span class="badge bg-light-info f-s-10 mb-2"><?php echo e($article->category); ?></span>
                                                            <?php endif; ?>
                                                            <div class="d-flex align-items-center gap-2">
                                                                <?php if (isset($component)) { $__componentOriginal74a3c73fa2014a1304a7d68280593565 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal74a3c73fa2014a1304a7d68280593565 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.social-view-counter','data' => ['content' => $article,'type' => 'article','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('social-view-counter'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['content' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($article),'type' => 'article','size' => 'sm']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal74a3c73fa2014a1304a7d68280593565)): ?>
<?php $attributes = $__attributesOriginal74a3c73fa2014a1304a7d68280593565; ?>
<?php unset($__attributesOriginal74a3c73fa2014a1304a7d68280593565); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal74a3c73fa2014a1304a7d68280593565)): ?>
<?php $component = $__componentOriginal74a3c73fa2014a1304a7d68280593565; ?>
<?php unset($__componentOriginal74a3c73fa2014a1304a7d68280593565); ?>
<?php endif; ?>
                                                                <small class="text-muted f-s-11">
                                                                    <i
                                                                        class="ph-duotone ph-clock f-s-10 me-1"></i><?php echo e($article->created_at->diffForHumans()); ?>

                                                                </small>
                                                            </div>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <?php if($article->slug): ?>
                                                                <a href="<?php echo e(route('articles.show', $article->slug)); ?>"
                                                                    class="btn btn-primary btn-sm hover-effect">
                                                                    <i class="ph-duotone ph-newspaper f-s-12 me-1"></i>
                                                                    Leggi
                                                                </a>
                                                            <?php else: ?>
                                                                <span class="btn btn-secondary btn-sm" title="<?php echo e(__('common.article_not_available')); ?>">
                                                                    <i class="ph-duotone ph-newspaper f-s-12 me-1"></i>
                                                                    N/A
                                                                </span>
                                                            <?php endif; ?>
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
                                            <div class="card  hover-effect border-info">
                                                <div class="card-body pa-15">
                                                    <div class="d-flex align-items-center">
                                                        <div class="flex-shrink-0 me-3">
                                                            <div class="position-relative">
                                                                <div class="rounded overflow-hidden"
                                                                    style="width: 60px; height: 60px;">
                                                                    <?php if($article->featured_image_url): ?>
                                                                        <img src="<?php echo e($article->featured_image_url); ?>"
                                                                            alt="<?php echo e($article->title); ?>" class="w-100 h-100"
                                                                            style="object-fit: cover;">
                                                                        <div
                                                                            class="position-absolute top-0 start-0 end-0 bottom-0 bg-dark opacity-20">
                                                                        </div>
                                                                        <div
                                                                            class="position-absolute top-50 start-50 translate-middle">
                                                                            <i
                                                                                class="ph-duotone ph-newspaper f-s-12 text-white"></i>
                                                                        </div>
                                                                    <?php else: ?>
                                                                        <?php echo PlaceholderHelper::getArticlePlaceholderHtml(60, 60, 'w-100 h-100', route('articles.show', $article->slug)); ?>

                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="card-title f-w-600 f-s-14 mb-1 text-primary">
                                                                <a href="<?php echo e(route('articles.show', $article->slug)); ?>" class="text-decoration-none hover-effect">
                                                                    <?php echo e($article->title ?: 'Articolo senza titolo'); ?>

                                                                </a>
                                                            </h6>
                                                            <p class="text-muted f-s-12 mb-1">
                                                                <a href="<?php echo e(route('user.show', $article->user)); ?>"
                                                                    class="text-decoration-none hover-effect">
                                                                    <?php echo e($article->user->getDisplayName()); ?>

                                                                </a>
                                                            </p>
                                                            <p class="text-muted f-s-11 mb-2">
                                                                <a href="<?php echo e(route('articles.show', $article->slug)); ?>" class="text-decoration-none hover-effect">
                                                                    <?php echo e(Str::limit($article->excerpt ?: strip_tags($article->content), 100)); ?>

                                                                </a>
                                                            </p>
                                                            <?php if($article->category): ?>
                                                                <span class="badge bg-light-info f-s-10 mb-2"><?php echo e($article->category); ?></span>
                                                            <?php endif; ?>
                                                            <div class="d-flex align-items-center gap-2">
                                                                <?php if (isset($component)) { $__componentOriginal74a3c73fa2014a1304a7d68280593565 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal74a3c73fa2014a1304a7d68280593565 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.social-view-counter','data' => ['content' => $article,'type' => 'article','size' => 'sm']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('social-view-counter'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['content' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($article),'type' => 'article','size' => 'sm']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal74a3c73fa2014a1304a7d68280593565)): ?>
<?php $attributes = $__attributesOriginal74a3c73fa2014a1304a7d68280593565; ?>
<?php unset($__attributesOriginal74a3c73fa2014a1304a7d68280593565); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal74a3c73fa2014a1304a7d68280593565)): ?>
<?php $component = $__componentOriginal74a3c73fa2014a1304a7d68280593565; ?>
<?php unset($__componentOriginal74a3c73fa2014a1304a7d68280593565); ?>
<?php endif; ?>
                                                                <small class="text-muted f-s-11">
                                                                    <i
                                                                        class="ph-duotone ph-clock f-s-10 me-1"></i><?php echo e($article->created_at->diffForHumans()); ?>

                                                                </small>
                                                            </div>
                                                        </div>
                                                        <div class="flex-shrink-0">
                                                            <?php if($article->slug): ?>
                                                                <a href="<?php echo e(route('articles.show', $article->slug)); ?>"
                                                                    class="btn btn-primary btn-sm hover-effect">
                                                                    <i class="ph-duotone ph-newspaper f-s-12 me-1"></i>
                                                                    Leggi
                                                                </a>
                                                            <?php else: ?>
                                                                <span class="btn btn-secondary btn-sm" title="<?php echo e(__('common.article_not_available')); ?>">
                                                                    <i class="ph-duotone ph-newspaper f-s-12 me-1"></i>
                                                                    N/A
                                                                </span>
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
            </div>

        </div>
    </div>

    <!-- Video Player Modal a Tutta Pagina -->
    <div class="custom-modal" id="videoPlayerModal"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999; background: rgba(0,0,0,0.85); backdrop-filter: blur(15px);">
        <div class="modal-content"
            style="position: relative; width: 100%; height: 100%; display: flex; flex-direction: column;">
            <div class="modal-header"
                style="background: rgba(0,0,0,0.8); border-bottom: 1px solid rgba(255,255,255,0.1); padding: 1rem; display: flex; justify-content: space-between; align-items: center;">
                <h5 class="modal-title text-white" id="videoPlayerModalLabel"><?php echo e(__('home.video_player')); ?></h5>
                <button type="button" class="btn-close btn-close-white" onclick="closeVideoModal()"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body" style="flex: 1; padding: 0; position: relative;">
                <!-- Loading indicator -->
                <div class="text-center position-absolute top-50 start-50 translate-middle" id="modalVideoLoading">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden"><?php echo e(__('home.loading_video')); ?></span>
                    </div>
                    <p class="mt-2 text-white"><?php echo e(__('home.loading_video')); ?></p>
                </div>

                <!-- Error message -->
                <div class="alert alert-danger position-absolute top-50 start-50 translate-middle" id="modalVideoError"
                    style="display: none; z-index: 1000;">
                    <i class="ph-duotone ph-warning f-s-16 me-2"></i>
                    <span id="modalErrorMessage"><?php echo e(__('home.video_loading_error')); ?></span>
                </div>

                <!-- Video Container -->
                <div class="video-container position-relative d-flex align-items-center justify-content-center"
                    id="modalVideoContainer" style="display: none; padding: 20px;">
                    <div class="w-100" style="max-width: 1200px;">
                        <div class="video-container position-relative">
                            <!-- Video Player HTML5 Nativo -->
                            <video id="modalVideoPlayer" class="w-100"
                                style="height: 500px; max-height: 500px; object-fit: cover; border-radius: 12px; box-shadow: 0 15px 40px rgba(0,0,0,0.6); background: #000;"
                                preload="metadata" controls>
                                Il tuo browser non supporta la riproduzione video.
                            </video>

                            <!-- Snap Markers sulla Progress Bar del Player -->
                            <div class="snap-markers-overlay position-absolute" id="modalSnapMarkers"
                                style="bottom: 0; left: 0; right: 0; height: 40px; pointer-events: none;">
                                <!-- Snap markers verranno aggiunti dinamicamente -->
                            </div>
                        </div>
                    </div>

                    <!-- Pulsante per creare snap con scritta sotto (solo per utenti autenticati) -->
                    <?php if(auth()->guard()->check()): ?>
                        <div class="position-absolute" id="modalFloatingSnapButton"
                            style="opacity: 1; transition: opacity 0.3s ease; z-index: 10000; top: 20px; right: 20px; display: flex; flex-direction: column; align-items: center; gap: 8px;">
                            <button type="button" class="btn btn-gradient-success hover-effect rounded-circle shadow-lg"
                                style="width: 60px; height: 60px;" onclick="toggleSnapForm()">
                                <img src="<?php echo e(asset('assets/images/snap.png')); ?>" alt="Snap"
                                    style="width: 28px; height: 28px; filter: brightness(0) invert(1);">
                            </button>
                            <div class="snap-label"
                                style="color: white; font-size: 11px; text-align: center; white-space: nowrap; text-shadow: 0 1px 2px rgba(0,0,0,0.8); font-weight: 500;">
                                <?php echo e(__('home.create_snap')); ?>

                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Form inline per creare snap (solo per utenti autenticati) -->
                    <?php if(auth()->guard()->check()): ?>
                        <div class="position-absolute" id="modalSnapForm"
                            style="display: none; z-index: 10001; top: 20px; right: 20px; background: rgba(0,0,0,0.9); border-radius: 12px; padding: 20px; min-width: 300px; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1);">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="text-white mb-0"><?php echo e(__('home.create_snap_button')); ?></h6>
                                <button type="button" class="btn-close btn-close-white" onclick="toggleSnapForm()"></button>
                            </div>
                            <form id="inlineSnapForm">
                                <div class="mb-3">
                                    <label for="inlineSnapTitle" class="form-label text-white"
                                        style="font-size: 12px;"><?php echo e(__('home.snap_title_optional')); ?></label>
                                    <input type="text" class="form-control form-control-sm" id="inlineSnapTitle"
                                        style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white;">
                                </div>
                                <div class="mb-3">
                                    <label for="inlineSnapDescription" class="form-label text-white"
                                        style="font-size: 12px;"><?php echo e(__('home.snap_description_optional')); ?></label>
                                    <textarea class="form-control form-control-sm" id="inlineSnapDescription" rows="2"
                                        style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; resize: none;"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label text-white" style="font-size: 12px;"><?php echo e(__('home.timestamp')); ?> <span
                                            id="inlineCurrentTime" class="text-warning">00:00</span></label>
                                    <input type="hidden" id="inlineSnapTimestamp" value="0">
                                    <input type="hidden" id="inlineSnapVideoId" value="">
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-secondary"
                                        onclick="toggleSnapForm()"><?php echo e(__('home.cancel')); ?></button>
                                    <button type="button" class="btn btn-sm btn-primary" onclick="createInlineSnap()"><?php echo e(__('home.create_snap_button')); ?></button>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Variabili globali per il modal video
    let modalCurrentVideoTime = 0;
    let modalVideoDuration = 0;
    let modalSnaps = [];

    // Funzione per aprire il modal video
    function openVideoModal(videoId) {
        // Mostra il modal personalizzato
        const modal = document.getElementById('videoPlayerModal');
        modal.style.display = 'block';
        document.body.style.overflow = 'hidden'; // Previene lo scroll

        // Carica il video
        loadVideoInModal(videoId);
    }

    // Funzione per chiudere il modal video
    function closeVideoModal() {
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
    async function loadVideoInModal(videoId) {
        const loadingDiv = document.getElementById('modalVideoLoading');
        const errorDiv = document.getElementById('modalVideoError');
        const containerDiv = document.getElementById('modalVideoContainer');
        const videoPlayer = document.getElementById('modalVideoPlayer');

        // Mostra loading
        loadingDiv.style.display = 'block';
        errorDiv.style.display = 'none';
        containerDiv.style.display = 'none';

        try {
            // Prima ottieni i dati del video
            const videoResponse = await fetch(`/api/videos/${videoId}`);
            const videoData = await videoResponse.json();

            if (!videoData.success) {
                throw new Error(videoData.message || 'Errore nel caricamento del video');
            }

            const video = videoData.video;

            // Imposta il titolo del modal
            document.getElementById('videoPlayerModalLabel').textContent = video.title;

            // Usa sempre il player HTML5 nativo
            videoPlayer.style.display = 'block';

            // Ottieni l'URL diretto del video da PeerTube
            const urlResponse = await fetch(`/videos/${videoId}/peertube-url`);
            const urlData = await urlResponse.json();

            // Gestisci il caso in cui il video è ancora in elaborazione
            if (urlData.status === 'processing') {
                throw new Error('Il video è ancora in elaborazione su PeerTube. Riprova tra qualche minuto.');
            }

            if (urlData.success && urlData.files && urlData.files.length > 0) {
                // Usa il primo file disponibile (migliore qualità)
                const videoFile = urlData.files[0];

                // Crea l'elemento source
                const source = document.createElement('source');
                source.src = videoFile.url;
                source.type = 'video/mp4';

                // Rimuovi eventuali source esistenti e aggiungi quello nuovo
                videoPlayer.innerHTML = '';
                videoPlayer.appendChild(source);

                // Forza il caricamento del video
                videoPlayer.load();

                // Inizializza il player del modal
                initializeModalVideoPlayer(video);
            } else {
                throw new Error('Nessuna sorgente video disponibile');
            }

            // Imposta l'ID del video per le funzioni snap
            videoPlayer.setAttribute('data-video-id', video.id);

            // Carica gli snap
            loadSnapsForModal(videoId);

            // Mostra il container del video
            loadingDiv.style.display = 'none';
            containerDiv.style.display = 'block';

        } catch (error) {
            console.error('Errore caricamento video:', error);
            loadingDiv.style.display = 'none';
            errorDiv.style.display = 'block';
            document.getElementById('modalErrorMessage').textContent = error.message;
        }
    }

    // Chiudi il modal quando si clicca fuori
    document.addEventListener('click', function(event) {
        const modal = document.getElementById('videoPlayerModal');
        if (event.target === modal) {
            closeVideoModal();
        }
    });

    // Chiudi il modal con il tasto ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeVideoModal();
        }
    });

    // Funzione per inizializzare il player del modal
    function initializeModalVideoPlayer(video) {
        const videoPlayer = document.getElementById('modalVideoPlayer');
        modalVideoDuration = video.duration || 60;

        // Event listener per quando i metadati sono caricati
        videoPlayer.addEventListener('loadedmetadata', function() {
            modalVideoDuration = videoPlayer.duration || modalVideoDuration;
            updateModalSnapMarkers();
        });

        // Event listener per quando la durata cambia
        videoPlayer.addEventListener('durationchange', function() {
            modalVideoDuration = videoPlayer.duration;
            updateModalSnapMarkers();
        });
    }

    // Funzione per caricare gli snap nel modal
    function loadSnapsForModal(videoId) {
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
                    updateModalSnapMarkers();
                } else {
                    modalSnaps = [];
                    updateModalSnapMarkers();
                }
            })
            .catch(error => {
                console.error('Errore caricamento snap:', error);
                modalSnaps = [];
                updateModalSnapMarkers();
            });
    }

    // Funzione per aggiornare i marker degli snap nel modal
    function updateModalSnapMarkers() {
        const markersContainer = document.getElementById('modalSnapMarkers');
        if (!markersContainer) return;

        markersContainer.innerHTML = '';

        if (!modalSnaps || modalSnaps.length === 0) return;

        // Raggruppa gli snap per timestamp
        const snapGroups = {};
        modalSnaps.forEach(snap => {
            const timestamp = Math.floor(snap.timestamp);
            if (!snapGroups[timestamp]) {
                snapGroups[timestamp] = [];
            }
            snapGroups[timestamp].push(snap);
        });

        // Crea i marker per ogni gruppo
        Object.keys(snapGroups).forEach(timestamp => {
            const snaps = snapGroups[timestamp];
            const marker = document.createElement('div');
            marker.className = 'snap-marker';
            marker.style.position = 'absolute';
            marker.style.bottom = '0';
            marker.style.left = `${(timestamp / modalVideoDuration) * 100}%`;
            marker.style.width = '4px';
            marker.style.height = '100%';
            marker.style.backgroundColor = snaps.length > 1 ? '#ff6b6b' : '#4ecdc4';
            marker.style.cursor = 'pointer';
            marker.title = `${snaps.length} snap${snaps.length > 1 ? 's' : ''} a ${Math.floor(timestamp / 60)}:${(timestamp % 60).toString().padStart(2, '0')}`;

            marker.addEventListener('click', () => {
                const videoPlayer = document.getElementById('modalVideoPlayer');
                if (videoPlayer) {
                    videoPlayer.currentTime = timestamp;
                }
            });

            markersContainer.appendChild(marker);
        });
    }
    </script>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <!-- slick CSS -->
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/vendor/slick/slick.css')); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo e(asset('assets/vendor/slick/slick-theme.css')); ?>">

    <!-- slick JS -->
    <script src="<?php echo e(asset('assets/vendor/slick/slick.min.js')); ?>"></script>

    <script>
        // Funzione per inizializzare gli slider in modo sicuro
        function initSlidersSafely() {
            // Verifica se jQuery è disponibile
            if (typeof $ === 'undefined') {
                console.error('jQuery non è caricato!');
                return;
            }

            // Verifica se Bootstrap è disponibile
            if (typeof bootstrap === 'undefined') {
                console.error('Bootstrap non è caricato!');
                return;
            }

            // Verifica se Slick è disponibile
            if (typeof $.fn.slick === 'undefined') {
                console.error('Slick non è caricato!');
                return;
            }

            console.log('Slick è disponibile:', typeof $.fn.slick);

            // Funzione per inizializzare uno slider generico
            function initGenericSlider(selector, sliderName) {
                const $slider = $(selector);
                if ($slider.length > 0 && $slider.is(':visible')) {
                    // Controlla se ha contenuto
                    const items = $slider.find('.autoplay-item');
                    if (items.length > 0) {
                        try {
                            // Rimuovi eventuali inizializzazioni precedenti
                            if ($slider.hasClass('slick-initialized')) {
                                $slider.slick('unslick');
                            }

                            // Inizializza con un piccolo delay
                            setTimeout(() => {
                                $slider.slick({
                                    slidesToShow: 2,
                                    slidesToScroll: 1,
                                    autoplay: true,
                                    autoplaySpeed: 3000,
                                    arrows: true,
                                    dots: false,
                                    infinite: true,
                                    speed: 500,
                                    adaptiveHeight: false,
                                    lazyLoad: 'ondemand',
                                    accessibility: false,
                                    focusOnSelect: false,
                                    useCSS: false,
                                    useTransform: false,
                                    waitForAnimate: false,
                                    responsive: [{
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
                                console.log(sliderName + ' inizializzato con successo');
                            }, 100);
                        } catch (error) {
                            console.error('Errore inizializzazione ' + sliderName + ':', error);
                        }
                    } else {
                        console.warn(sliderName + ' non ha contenuto');
                    }
                } else {
                    console.warn(sliderName + ' non trovato o non visibile');
                }
            }

            // Inizializza lo slider degli eventi
            initGenericSlider('#events-slider', 'Events slider');

            // Inizializza lo slider dei video
            initGenericSlider('#videos-slider', 'Videos slider');


            // Inizializza il carosello Bootstrap (solo se esiste)
            const $carousel = $('#heroCarousel');
            if ($carousel.length > 0) {
                try {
                    const bsCarousel = new bootstrap.Carousel($carousel[0], {
                        interval: 5000, // 5 secondi
                        ride: 'carousel', // Avvia automaticamente
                        wrap: true, // Loop infinito
                        keyboard: true, // Controlli da tastiera
                        pause: 'hover' // Pausa al hover
                    });
                    console.log('Bootstrap carousel inizializzato');
                } catch (error) {
                    console.warn('Bootstrap Carousel non disponibile, usando fallback manuale');
                    initManualCarousel();
                }
            }
            // Funzione fallback per carosello manuale
            function initManualCarousel() {
                const carousel = document.getElementById('heroCarousel');
                if (!carousel) return;

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
            }
        }

        // Inizializza quando il DOM è pronto
        $(document).ready(function() {
            console.log('DOM pronto, inizializzo gli slider...');
            setTimeout(initSlidersSafely, 500);
        });

        // Fallback con window.load
        window.addEventListener('load', function() {
            console.log('Window loaded, inizializzo gli slider...');
            setTimeout(initSlidersSafely, 1000);
        });

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
                toggle.checked = false;
                // Evidenzia "<?php echo e(__('common.new_content')); ?>" e disattiva "<?php echo e(__('common.popular_content')); ?>"
                labelLeft.classList.remove('text-muted');
                labelLeft.classList.add('text-primary');
                labelRight.classList.remove('text-primary');
                labelRight.classList.add('text-muted');
            } else {
                newContent.style.display = 'none';
                popularContent.style.display = 'block';
                toggle.checked = true;
                // Evidenzia "<?php echo e(__('common.popular_content')); ?>" e disattiva "<?php echo e(__('common.new_content')); ?>"
                labelLeft.classList.remove('text-primary');
                labelLeft.classList.add('text-muted');
                labelRight.classList.remove('text-muted');
                labelRight.classList.add('text-primary');
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
                toggle.checked = false;
                // Evidenzia "<?php echo e(__('common.new_content')); ?>" e disattiva "<?php echo e(__('common.popular_content')); ?>"
                labelLeft.classList.remove('text-muted');
                labelLeft.classList.add('text-primary');
                labelRight.classList.remove('text-primary');
                labelRight.classList.add('text-muted');
            } else {
                newContent.style.display = 'none';
                popularContent.style.display = 'block';
                toggle.checked = true;
                // Evidenzia "<?php echo e(__('common.popular_content')); ?>" e disattiva "<?php echo e(__('common.new_content')); ?>"
                labelLeft.classList.remove('text-primary');
                labelLeft.classList.add('text-muted');
                labelRight.classList.remove('text-muted');
                labelRight.classList.add('text-primary');
            }
        };

        // Funzione per seguire un utente
        window.followUser = function(userId) {
            // Verifica se l'utente è autenticato
            const isAuthenticated = <?php echo e(auth()->check() ? 'true' : 'false'); ?>;

            if (!isAuthenticated) {
                window.location.href = '<?php echo e(route('login')); ?>';
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
                            button.innerHTML = '<i class="ti ti-user-check"></i><span id="followText' +
                                userId + '">Following</span>';
                            button.classList.remove('btn-primary');
                            button.classList.add('btn-success');
                        } else {
                            button.innerHTML = '<i class="ti ti-user"></i><span id="followText' +
                                userId + '">Follow</span>';
                            button.classList.remove('btn-success');
                            button.classList.add('btn-primary');
                        }

                        // Mostra notifica
                        Swal.fire({
                            icon: 'success',
                            title: 'Successo!',
                            text: data.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire('Errore', data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Errore connessione follow:', error);
                    Swal.fire('Errore', 'Errore durante l\'operazione', 'error');
                })
                .finally(() => {
                    // Riabilita il pulsante
                    button.disabled = false;
                });
        };

        // Funzione per mostrare messaggio di successo
        window.showSuccessMessage = function(message) {
            const successDiv = document.createElement('div');
            successDiv.className = 'position-fixed';
            successDiv.style.cssText =
                'top: 20px; right: 20px; z-index: 10002; background: rgba(40, 167, 69, 0.9); color: white; padding: 12px 20px; border-radius: 8px; font-size: 14px; backdrop-filter: blur(10px);';
            successDiv.textContent = message;
            document.body.appendChild(successDiv);

            setTimeout(() => {
                successDiv.remove();
            }, 3000);
        };

        // Funzione per mostrare messaggio di errore
        window.showErrorMessage = function(message) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'position-fixed';
            errorDiv.style.cssText =
                'top: 20px; right: 20px; z-index: 10002; background: rgba(220, 53, 69, 0.9); color: white; padding: 12px 20px; border-radius: 8px; font-size: 14px; backdrop-filter: blur(10px);';
            errorDiv.textContent = message;
            document.body.appendChild(errorDiv);

            setTimeout(() => {
                errorDiv.remove();
            }, 3000);
        };

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