<?php $__env->startSection('title', __('user_stats.title')); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">

    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card hover-effect b-e-4-primary">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2 f-w-600">
                                <i class="ph ph-chart-line me-2 text-primary"></i>
                                <?php echo e(__('user_stats.title')); ?>

                            </h2>
                            <p class="text-primary-50 mb-0 f-s-14">
                                <?php echo e(__('user_stats.subtitle', ['name' => $user->getDisplayName()])); ?>

                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <!-- Time Period Filter -->
                            <div class="btn-group" role="group">
                                <input type="radio" class="btn-check" name="timeframe" id="timeframe_1m" value="1_month"
                                       <?php echo e($timeframe === '1_month' ? 'checked' : ''); ?>>
                                <label class="btn btn-outline-primary btn-sm" for="timeframe_1m"><?php echo e(__('user_stats.1_month')); ?></label>

                                <input type="radio" class="btn-check" name="timeframe" id="timeframe_3m" value="3_months"
                                       <?php echo e($timeframe === '3_months' ? 'checked' : ''); ?>>
                                <label class="btn btn-outline-primary btn-sm" for="timeframe_3m"><?php echo e(__('user_stats.3_months')); ?></label>

                                <input type="radio" class="btn-check" name="timeframe" id="timeframe_12m" value="12_months"
                                       <?php echo e($timeframe === '12_months' ? 'checked' : ''); ?>>
                                <label class="btn btn-outline-primary btn-sm" for="timeframe_12m"><?php echo e(__('user_stats.12_months')); ?></label>

                                <input type="radio" class="btn-check" name="timeframe" id="timeframe_all" value="all_time"
                                       <?php echo e($timeframe === 'all_time' ? 'checked' : ''); ?>>
                                <label class="btn btn-outline-primary btn-sm" for="timeframe_all"><?php echo e(__('user_stats.all_time')); ?></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Statistics -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card hover-effect equal-card">
                <div class="card-header">
                    <h5 class="card-title mb-0 f-w-600">
                        <i class="ph ph-files me-2 text-info"></i><?php echo e(__('user_stats.content_statistics')); ?>

                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Poems -->
                        <div class="col-lg-4 col-md-6">
                            <div class="card hover-effect equal-card b-t-4-info">
                                <div class="card-body eshop-cards text-center pa-20">
                                    <div class="bg-light-info h-50 w-50 d-flex-center rounded-circle m-auto mb-3">
                                        <i class="ph ph-pen-nib f-s-24 text-info"></i>
                                    </div>
                                    <h3 class="text-info mb-2 f-w-600"><?php echo e($stats['content']['poems']['total']); ?></h3>
                                    <p class="f-w-500 text-dark f-s-14 mb-2"><?php echo e(__('user_stats.poems')); ?></p>
                                    <div class="d-flex justify-content-between f-s-12">
                                        <span class="text-success"><?php echo e($stats['content']['poems']['published']); ?> <?php echo e(__('user_stats.published')); ?></span>
                                        <span class="text-warning"><?php echo e($stats['content']['poems']['drafts']); ?> <?php echo e(__('user_stats.drafts')); ?></span>
                                    </div>
                                    <div class="mt-2">
                                        <small class="text-muted"><?php echo e($stats['content']['poems']['views']); ?> <?php echo e(__('user_stats.total_views')); ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Videos -->
                        <div class="col-lg-4 col-md-6">
                            <div class="card hover-effect equal-card b-t-4-warning">
                                <div class="card-body eshop-cards text-center pa-20">
                                    <div class="bg-light-warning h-50 w-50 d-flex-center rounded-circle m-auto mb-3">
                                        <i class="ph ph-video f-s-24 text-warning"></i>
                                    </div>
                                    <h3 class="text-warning mb-2 f-w-600"><?php echo e($stats['content']['videos']['total']); ?></h3>
                                    <p class="f-w-500 text-dark f-s-14 mb-2"><?php echo e(__('user_stats.videos')); ?></p>
                                    <div class="d-flex justify-content-between f-s-12">
                                        <span class="text-success"><?php echo e($stats['content']['videos']['published']); ?> <?php echo e(__('user_stats.published')); ?></span>
                                        <span class="text-muted"><?php echo e($stats['content']['videos']['total'] - $stats['content']['videos']['published']); ?> <?php echo e(__('user_stats.private')); ?></span>
                                    </div>
                                    <div class="mt-2">
                                        <small class="text-muted"><?php echo e($stats['content']['videos']['views']); ?> <?php echo e(__('user_stats.total_views')); ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Articles -->
                        <div class="col-lg-4 col-md-6">
                            <div class="card hover-effect equal-card b-t-4-primary">
                                <div class="card-body eshop-cards text-center pa-20">
                                    <div class="bg-light-primary h-50 w-50 d-flex-center rounded-circle m-auto mb-3">
                                        <i class="ph ph-newspaper f-s-24 text-primary"></i>
                                    </div>
                                    <h3 class="text-primary mb-2 f-w-600"><?php echo e($stats['content']['articles']['total']); ?></h3>
                                    <p class="f-w-500 text-dark f-s-14 mb-2"><?php echo e(__('user_stats.articles')); ?></p>
                                    <div class="d-flex justify-content-between f-s-12">
                                        <span class="text-success"><?php echo e($stats['content']['articles']['published']); ?> <?php echo e(__('user_stats.published')); ?></span>
                                        <span class="text-muted"><?php echo e($stats['content']['articles']['total'] - $stats['content']['articles']['published']); ?> <?php echo e(__('user_stats.drafts')); ?></span>
                                    </div>
                                    <div class="mt-2">
                                        <small class="text-muted"><?php echo e($stats['content']['articles']['views']); ?> <?php echo e(__('user_stats.total_views')); ?></small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Engagement Statistics -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card hover-effect equal-card">
                <div class="card-header">
                    <h5 class="card-title mb-0 f-w-600">
                        <i class="ph ph-heart me-2 text-danger"></i><?php echo e(__('user_stats.engagement_statistics')); ?>

                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Likes Received -->
                        <div class="col-lg-3 col-md-6">
                            <div class="card hover-effect equal-card b-t-4-danger">
                                <div class="card-body eshop-cards text-center pa-15">
                                    <div class="bg-light-danger h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                                        <i class="ph ph-heart f-s-18 text-danger"></i>
                                    </div>
                                    <h4 class="text-danger mb-1 f-w-600"><?php echo e($stats['engagement']['received']['poem_likes'] + $stats['engagement']['received']['video_likes']); ?></h4>
                                    <p class="f-w-500 text-dark f-s-12 mb-1"><?php echo e(__('user_stats.likes_received')); ?></p>
                                    <div class="f-s-10">
                                        <span class="badge bg-light-info"><?php echo e($stats['engagement']['received']['poem_likes']); ?> <?php echo e(__('user_stats.poems')); ?></span>
                                        <span class="badge bg-light-warning"><?php echo e($stats['engagement']['received']['video_likes']); ?> <?php echo e(__('user_stats.videos')); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Comments Received -->
                        <div class="col-lg-3 col-md-6">
                            <div class="card hover-effect equal-card b-t-4-success">
                                <div class="card-body eshop-cards text-center pa-15">
                                    <div class="bg-light-success h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                                        <i class="ph ph-chat-circle f-s-18 text-success"></i>
                                    </div>
                                    <h4 class="text-success mb-1 f-w-600"><?php echo e($stats['engagement']['received']['poem_comments'] + $stats['engagement']['received']['video_comments']); ?></h4>
                                    <p class="f-w-500 text-dark f-s-12 mb-1"><?php echo e(__('user_stats.comments_received')); ?></p>
                                    <div class="f-s-10">
                                        <span class="badge bg-light-info"><?php echo e($stats['engagement']['received']['poem_comments']); ?> <?php echo e(__('user_stats.poems')); ?></span>
                                        <span class="badge bg-light-warning"><?php echo e($stats['engagement']['received']['video_comments']); ?> <?php echo e(__('user_stats.videos')); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Snaps Received -->
                        <div class="col-lg-3 col-md-6">
                            <div class="card hover-effect equal-card b-t-4-warning">
                                <div class="card-body eshop-cards text-center pa-15">
                                    <div class="bg-light-warning h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                                        <i class="ph ph-hand-clap f-s-18 text-warning"></i>
                                    </div>
                                    <h4 class="text-warning mb-1 f-w-600"><?php echo e($stats['engagement']['received']['video_snaps']); ?></h4>
                                    <p class="f-w-500 text-dark f-s-12 mb-1"><?php echo e(__('user_stats.snaps_received')); ?></p>
                                    <span class="badge bg-light-warning f-s-10"><?php echo e(__('user_stats.videos')); ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Engagement Given -->
                        <div class="col-lg-3 col-md-6">
                            <div class="card hover-effect equal-card b-t-4-primary">
                                <div class="card-body eshop-cards text-center pa-15">
                                    <div class="bg-light-primary h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                                        <i class="ph ph-hand-heart f-s-18 text-primary"></i>
                                    </div>
                                    <h4 class="text-primary mb-1 f-w-600"><?php echo e($stats['engagement']['given']['poem_likes'] + $stats['engagement']['given']['video_likes'] + $stats['engagement']['given']['poem_comments'] + $stats['engagement']['given']['video_comments']); ?></h4>
                                    <p class="f-w-500 text-dark f-s-12 mb-1"><?php echo e(__('user_stats.engagement_given')); ?></p>
                                    <span class="badge bg-light-primary f-s-10"><?php echo e(__('user_stats.total_interactions')); ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Statistics -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card hover-effect equal-card">
                <div class="card-header">
                    <h5 class="card-title mb-0 f-w-600">
                        <i class="ph ph-calendar me-2 text-secondary"></i><?php echo e(__('user_stats.event_statistics')); ?>

                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Events Created -->
                        <div class="col-lg-6 col-md-6">
                            <div class="card hover-effect equal-card b-t-4-secondary">
                                <div class="card-body eshop-cards text-center pa-20">
                                    <div class="bg-light-secondary h-50 w-50 d-flex-center rounded-circle m-auto mb-3">
                                        <i class="ph ph-calendar-plus f-s-24 text-secondary"></i>
                                    </div>
                                    <h3 class="text-secondary mb-2 f-w-600"><?php echo e($stats['events']['created']['total']); ?></h3>
                                    <p class="f-w-500 text-dark f-s-14 mb-2"><?php echo e(__('user_stats.events_created')); ?></p>
                                    <div class="d-flex justify-content-between f-s-12">
                                        <span class="text-success"><?php echo e($stats['events']['created']['past']); ?> <?php echo e(__('user_stats.completed')); ?></span>
                                        <span class="text-warning"><?php echo e($stats['events']['created']['upcoming']); ?> <?php echo e(__('user_stats.upcoming')); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Events Participated -->
                        <div class="col-lg-6 col-md-6">
                            <div class="card hover-effect equal-card b-t-4-info">
                                <div class="card-body eshop-cards text-center pa-20">
                                    <div class="bg-light-info h-50 w-50 d-flex-center rounded-circle m-auto mb-3">
                                        <i class="ph ph-users f-s-24 text-info"></i>
                                    </div>
                                    <h3 class="text-info mb-2 f-w-600"><?php echo e($stats['events']['participated']['total']); ?></h3>
                                    <p class="f-w-500 text-dark f-s-14 mb-2"><?php echo e(__('user_stats.events_participated')); ?></p>
                                    <div class="d-flex justify-content-between f-s-12">
                                        <span class="text-success"><?php echo e($stats['events']['participated']['past']); ?> <?php echo e(__('user_stats.attended')); ?></span>
                                        <span class="text-warning"><?php echo e($stats['events']['participated']['upcoming']); ?> <?php echo e(__('user_stats.planned')); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Location Statistics -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card hover-effect equal-card">
                <div class="card-header">
                    <h5 class="card-title mb-0 f-w-600">
                        <i class="ph ph-map-pin me-2 text-success"></i><?php echo e(__('user_stats.location_statistics')); ?>

                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="card hover-effect equal-card b-t-4-success">
                                <div class="card-body eshop-cards text-center pa-20">
                                    <div class="bg-light-success h-50 w-50 d-flex-center rounded-circle m-auto mb-3">
                                        <i class="ph ph-buildings f-s-24 text-success"></i>
                                    </div>
                                    <h3 class="text-success mb-2 f-w-600"><?php echo e($stats['locations']['unique_venues']); ?></h3>
                                    <p class="f-w-500 text-dark f-s-14 mb-0"><?php echo e(__('user_stats.unique_venues')); ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-6 mb-3">
                            <div class="card hover-effect equal-card b-t-4-primary">
                                <div class="card-body eshop-cards text-center pa-20">
                                    <div class="bg-light-primary h-50 w-50 d-flex-center rounded-circle m-auto mb-3">
                                        <i class="ph ph-map-pin f-s-24 text-primary"></i>
                                    </div>
                                    <h3 class="text-primary mb-2 f-w-600"><?php echo e($stats['locations']['unique_cities']); ?></h3>
                                    <p class="f-w-500 text-dark f-s-14 mb-0"><?php echo e(__('user_stats.unique_cities')); ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-12">
                            <div class="card hover-effect equal-card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0 f-w-600"><?php echo e(__('user_stats.top_locations')); ?></h6>
                                </div>
                                <div class="card-body pa-15">
                                    <?php if($stats['locations']['locations']->count() > 0): ?>
                                        <div class="list-group list-group-flush">
                                            <?php $__currentLoopData = $stats['locations']['locations']->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="list-group-item d-flex justify-content-between align-items-center px-0 py-2">
                                                    <div>
                                                        <small class="f-w-500"><?php echo e($location['location']); ?></small>
                                                        <br>
                                                        <small class="text-muted"><?php echo e($location['events_count']); ?> <?php echo e(__('user_stats.events')); ?></small>
                                                    </div>
                                                    <span class="badge bg-light-primary"><?php echo e($location['events_count']); ?></span>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-muted text-center mb-0"><?php echo e(__('user_stats.no_locations')); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Group Statistics (if user is member of groups) -->
    <?php if($groupStats && count($groupStats) > 0): ?>
    <div class="row mb-4">
        <div class="col-12">
            <div class="card hover-effect equal-card">
                <div class="card-header">
                    <h5 class="card-title mb-0 f-w-600">
                        <i class="ph ph-users-three me-2 text-warning"></i><?php echo e(__('user_stats.group_statistics')); ?>

                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <?php $__currentLoopData = $groupStats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupStat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="card hover-effect equal-card b-t-4-warning">
                                <div class="card-body eshop-cards pa-20">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-light-warning h-40 w-40 d-flex-center rounded-circle me-3">
                                            <i class="ph ph-users f-s-18 text-warning"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 f-w-600"><?php echo e($groupStat['group']->name); ?></h6>
                                            <span class="badge bg-light-warning f-s-10"><?php echo e(__('user_stats.role_' . $groupStat['role'])); ?></span>
                                        </div>
                                    </div>
                                    <div class="row text-center">
                                        <div class="col-4">
                                            <div class="f-s-12 text-muted"><?php echo e(__('user_stats.events')); ?></div>
                                            <div class="f-w-600"><?php echo e($groupStat['total_events']); ?></div>
                                        </div>
                                        <div class="col-4">
                                            <div class="f-s-12 text-muted"><?php echo e(__('user_stats.members')); ?></div>
                                            <div class="f-w-600"><?php echo e($groupStat['members_count']); ?></div>
                                        </div>
                                        <div class="col-4">
                                            <div class="f-s-12 text-muted"><?php echo e(__('user_stats.this_period')); ?></div>
                                            <div class="f-w-600"><?php echo e($groupStat['events_created']); ?></div>
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

    <!-- Performance Statistics (for future poetry slam results) -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card hover-effect equal-card">
                <div class="card-header">
                    <h5 class="card-title mb-0 f-w-600">
                        <i class="ph ph-trophy me-2 text-warning"></i><?php echo e(__('user_stats.performance_statistics')); ?>

                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-lg-3 col-md-6">
                            <div class="card hover-effect equal-card b-t-4-warning">
                                <div class="card-body eshop-cards text-center pa-20">
                                    <div class="bg-light-warning h-50 w-50 d-flex-center rounded-circle m-auto mb-3">
                                        <i class="ph ph-trophy f-s-24 text-warning"></i>
                                    </div>
                                    <h3 class="text-warning mb-2 f-w-600"><?php echo e($stats['performance']['slam_wins']); ?></h3>
                                    <p class="f-w-500 text-dark f-s-14 mb-0"><?php echo e(__('user_stats.slam_wins')); ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="card hover-effect equal-card b-t-4-secondary">
                                <div class="card-body eshop-cards text-center pa-20">
                                    <div class="bg-light-secondary h-50 w-50 d-flex-center rounded-circle m-auto mb-3">
                                        <i class="ph ph-medal f-s-24 text-secondary"></i>
                                    </div>
                                    <h3 class="text-secondary mb-2 f-w-600"><?php echo e($stats['performance']['slam_second_places']); ?></h3>
                                    <p class="f-w-500 text-dark f-s-14 mb-0"><?php echo e(__('user_stats.second_places')); ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="card hover-effect equal-card b-t-4-info">
                                <div class="card-body eshop-cards text-center pa-20">
                                    <div class="bg-light-info h-50 w-50 d-flex-center rounded-circle m-auto mb-3">
                                        <i class="ph ph-medal f-s-24 text-info"></i>
                                    </div>
                                    <h3 class="text-info mb-2 f-w-600"><?php echo e($stats['performance']['slam_third_places']); ?></h3>
                                    <p class="f-w-500 text-dark f-s-14 mb-0"><?php echo e(__('user_stats.third_places')); ?></p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <div class="card hover-effect equal-card b-t-4-primary">
                                <div class="card-body eshop-cards text-center pa-20">
                                    <div class="bg-light-primary h-50 w-50 d-flex-center rounded-circle m-auto mb-3">
                                        <i class="ph ph-chart-bar f-s-24 text-primary"></i>
                                    </div>
                                    <h3 class="text-primary mb-2 f-w-600"><?php echo e($stats['performance']['total_slam_participations']); ?></h3>
                                    <p class="f-w-500 text-dark f-s-14 mb-0"><?php echo e(__('user_stats.total_participations')); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if($stats['performance']['total_slam_participations'] === 0): ?>
                    <div class="text-center mt-4">
                        <div class="bg-light-warning p-4 rounded">
                            <i class="ph ph-info f-s-24 text-warning mb-2"></i>
                            <p class="text-warning mb-0"><?php echo e(__('user_stats.performance_coming_soon')); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Temporal Charts Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card hover-effect equal-card">
                <div class="card-header">
                    <h5 class="card-title mb-0 f-w-600">
                        <i class="ph ph-chart-line me-2 text-primary"></i><?php echo e(__('user_stats.temporal_analysis')); ?>

                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Content Creation Chart -->
                        <div class="col-lg-6 mb-4">
                            <div class="card hover-effect equal-card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0 f-w-600"><?php echo e(__('user_stats.content_creation')); ?></h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="contentChart" height="200"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Engagement Chart -->
                        <div class="col-lg-6 mb-4">
                            <div class="card hover-effect equal-card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0 f-w-600"><?php echo e(__('user_stats.engagement_over_time')); ?></h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="engagementChart" height="200"></canvas>
                                </div>
                            </div>
                        </div>

                        <!-- Event Participation Chart -->
                        <div class="col-lg-12">
                            <div class="card hover-effect equal-card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0 f-w-600"><?php echo e(__('user_stats.event_participation')); ?></h6>
                                </div>
                                <div class="card-body">
                                    <canvas id="eventsChart" height="150"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Time period filter functionality
    const timeframeRadios = document.querySelectorAll('input[name="timeframe"]');
    timeframeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.checked) {
                const url = new URL(window.location);
                url.searchParams.set('timeframe', this.value);
                window.location.href = url.toString();
            }
        });
    });

    // Chart data from PHP
    const temporalData = <?php echo json_encode($temporalData, 15, 512) ?>;

    // Content Creation Chart
    const contentCtx = document.getElementById('contentChart').getContext('2d');
    new Chart(contentCtx, {
        type: 'line',
        data: {
            labels: Object.keys(temporalData.poems),
            datasets: [
                {
                    label: '<?php echo e(__("user_stats.poems")); ?>',
                    data: Object.values(temporalData.poems),
                    borderColor: '#0d6efd',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    tension: 0.4
                },
                {
                    label: '<?php echo e(__("user_stats.videos")); ?>',
                    data: Object.values(temporalData.videos),
                    borderColor: '#fd7e14',
                    backgroundColor: 'rgba(253, 126, 20, 0.1)',
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });

    // Engagement Chart
    const engagementCtx = document.getElementById('engagementChart').getContext('2d');
    new Chart(engagementCtx, {
        type: 'line',
        data: {
            labels: Object.keys(temporalData.engagement.likes_received),
            datasets: [
                {
                    label: '<?php echo e(__("user_stats.likes_received")); ?>',
                    data: Object.values(temporalData.engagement.likes_received),
                    borderColor: '#dc3545',
                    backgroundColor: 'rgba(220, 53, 69, 0.1)',
                    tension: 0.4
                },
                {
                    label: '<?php echo e(__("user_stats.comments_received")); ?>',
                    data: Object.values(temporalData.engagement.comments_received),
                    borderColor: '#198754',
                    backgroundColor: 'rgba(25, 135, 84, 0.1)',
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });

    // Events Chart
    const eventsCtx = document.getElementById('eventsChart').getContext('2d');
    new Chart(eventsCtx, {
        type: 'line',
        data: {
            labels: Object.keys(temporalData.events.organized),
            datasets: [
                {
                    label: '<?php echo e(__("user_stats.events_created")); ?>',
                    data: Object.values(temporalData.events.organized),
                    borderColor: '#6c757d',
                    backgroundColor: 'rgba(108, 117, 125, 0.1)',
                    tension: 0.4
                },
                {
                    label: '<?php echo e(__("user_stats.events_participated")); ?>',
                    data: Object.values(temporalData.events.participated),
                    borderColor: '#0dcaf0',
                    backgroundColor: 'rgba(13, 202, 240, 0.1)',
                    tension: 0.4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    position: 'top',
                }
            }
        }
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/user-stats/index.blade.php ENDPATH**/ ?>