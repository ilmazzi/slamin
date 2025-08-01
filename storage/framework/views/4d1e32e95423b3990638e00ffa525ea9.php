<?php $__env->startSection('title', $user->getDisplayName() . ' - ' . __('profile.profile') . ' - Slamin'); ?>

<?php $__env->startSection('css'); ?>
<style>
/* Stili per i pulsanti delle azioni */
.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 0.375rem;
}

.gap-2 {
    gap: 0.5rem !important;
}

/* Effetti per l'anteprima video */
.video-preview {
    transition: all 0.3s ease;
}

.video-preview:hover {
    transform: scale(1.02);
}

.video-preview:hover .play-button {
    background-color: #667eea !important;
    transform: scale(1.1);
}

.video-preview:hover .play-button i {
    color: white !important;
}

/* Effetti per thumbnail con play button */
.position-relative[onclick] {
    transition: all 0.3s ease;
}

.position-relative[onclick]:hover {
    transform: scale(1.02);
}

.position-relative[onclick]:hover .play-button {
    background-color: #667eea !important;
    transform: scale(1.1);
}

.position-relative[onclick]:hover .play-button i {
    color: white !important;
}

.play-button {
    transition: all 0.3s ease;
}
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('main-content'); ?>
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title"><?php echo e($user->getDisplayName()); ?></h4>
            <ul class="app-line-breadcrumbs mb-3">
                <li class="">
                    <a href="/" class="f-s-14 f-w-500">
                        <span>
                            <i class="ph-duotone ph-house f-s-16"></i> <?php echo e(__('dashboard.dashboard')); ?>

                        </span>
                    </a>
                </li>
                <li class="active">
                    <a href="#" class="f-s-14 f-w-500"><?php echo e(__('profile.breadcrumb_profile')); ?></a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Mobile Layout: Photo and Cover at Top -->
    <div class="row d-md-none">
        <div class="col-12">
            <!-- Profile Header Mobile -->
            <div class="card mb-3">
                <div class="card-body p-0">
                    <div class="profile-container">
                        <div class="image-details">
                            <div class="profile-image" style="background-image: url('<?php echo e($user->banner_image_url); ?>')">
                                <?php if($isOwnProfile): ?>
                                <div class="banner-edit">
                                    <input type="file" id="bannerUploadMobile" accept=".png, .jpg, .jpeg" onchange="uploadBannerImage(this)">
                                    <label for="bannerUploadMobile" class="btn btn-light btn-sm">
                                        <i class="ti ti-photo-heart me-1"></i><?php echo e(__('profile.change_banner')); ?>

                                    </label>
                                </div>
                                <?php endif; ?>
                            </div>
                            <div class="profile-pic">
                                <div class="avatar-upload">
                                    <?php if($isOwnProfile): ?>
                                    <div class="avatar-edit">
                                        <input type="file" id="imageUploadMobile" accept=".png, .jpg, .jpeg" onchange="uploadProfilePhoto(this)">
                                        <label for="imageUploadMobile"><i class="ti ti-photo-heart"></i></label>
                                    </div>
                                    <?php endif; ?>
                                    <div class="avatar-preview">
                                        <div id="imgPreviewMobile">
                                            <?php if($user->profile_photo): ?>
                                                <img src="<?php echo e($user->profile_photo_url); ?>" alt="Profile Photo" class="img-fluid">
                                            <?php else: ?>
                                                <div class="bg-light-primary h-120 w-120 d-flex-center b-r-50">
                                                    <span class="text-primary fw-bold f-s-24"><?php echo e(substr($user->getDisplayName(), 0, 2)); ?></span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="person-details">
                            <h5 class="f-w-600"><?php echo e($user->getDisplayName()); ?></h5>
                            <?php if($user->nickname && $user->nickname !== $user->name): ?>
                            <p class="text-muted"><?php echo e($user->nickname); ?></p>
                            <?php endif; ?>

                            <!-- Roles -->
                            <div class="mb-3">
                                <?php $__currentLoopData = $user->getRoleNames(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="badge bg-primary me-1 f-s-12"><?php echo e(__('auth.role_' . $role)); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>

                            <?php if(!$isOwnProfile): ?>
                            <div class="my-2">
                                <button type="button" class="btn btn-primary b-r-22" onclick="followUser(<?php echo e($user->id); ?>)">
                                    <i class="ti ti-user-plus me-2"></i><?php echo e(__('profile.follow')); ?>

                                </button>
                                <button type="button" class="btn btn-outline-primary b-r-22 ms-2" onclick="sendMessage(<?php echo e($user->id); ?>)">
                                    <i class="ti ti-message-circle me-2"></i><?php echo e(__('profile.send_message')); ?>

                                </button>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Layout: Additional Sections -->
    <div class="row d-md-none">
        <div class="col-12">
            <!-- About Me Mobile -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5><?php echo e(__('profile.about_me')); ?></h5>
                </div>
                <div class="card-body">
                    <?php if($user->bio): ?>
                    <p class="text-muted f-s-13"><?php echo e($user->bio); ?></p>
                    <?php else: ?>
                    <p class="text-muted f-s-13"><?php echo e(__('profile.no_bio_available')); ?></p>
                    <?php endif; ?>

                    <div class="about-list">
                        <?php if($user->email): ?>
                        <div>
                            <span class="fw-medium"><i class="ti ti-mail"></i> <?php echo e(__('profile.email')); ?></span>
                            <span class="float-end f-s-13 text-secondary"><?php echo e($user->email); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if($user->phone): ?>
                        <div>
                            <span class="fw-medium"><i class="ti ti-phone"></i> <?php echo e(__('profile.phone')); ?></span>
                            <span class="float-end f-s-13 text-secondary"><?php echo e($user->phone); ?></span>
                        </div>
                        <?php endif; ?>
                        <?php if($user->location): ?>
                        <div>
                            <span class="fw-semibold"><i class="ti ti-map-pin"></i> <?php echo e(__('profile.location')); ?></span>
                            <span class="float-end f-s-13 text-secondary"><?php echo e($user->location); ?></span>
                        </div>
                        <?php endif; ?>
                        <div>
                            <span class="fw-medium"><i class="ti ti-calendar"></i> <?php echo e(__('profile.member_since')); ?></span>
                            <span class="float-end f-s-13 text-secondary"><?php echo e($user->created_at->format('M Y')); ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics Mobile -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5><?php echo e(__('profile.statistics')); ?></h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="card card-light-primary hover-effect equal-card">
                                <div class="card-body eshop-cards text-center pa-15">
                                    <div class="bg-light-primary h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                                        <i class="ti ti-calendar-plus f-s-18 text-primary"></i>
                                    </div>
                                    <h4 class="text-primary mb-1 f-w-600"><?php echo e($stats['total_events']); ?></h4>
                                    <p class="f-w-500 text-dark f-s-12 mb-0"><?php echo e(__('profile.organized_events')); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card card-light-success hover-effect equal-card">
                                <div class="card-body eshop-cards text-center pa-15">
                                    <div class="bg-light-success h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                                        <i class="ti ti-users f-s-18 text-success"></i>
                                    </div>
                                    <h4 class="text-success mb-1 f-w-600"><?php echo e($stats['participated_events']); ?></h4>
                                    <p class="f-w-500 text-dark f-s-12 mb-0"><?php echo e(__('profile.participated_events')); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card card-light-warning hover-effect equal-card">
                                <div class="card-body eshop-cards text-center pa-15">
                                    <div class="bg-light-warning h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                                        <i class="ti ti-video-camera f-s-18 text-warning"></i>
                                    </div>
                                    <h4 class="text-warning mb-1 f-w-600"><?php echo e($stats['total_videos']); ?></h4>
                                    <p class="f-w-500 text-dark f-s-12 mb-0"><?php echo e(__('profile.uploaded_videos')); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card card-light-info hover-effect equal-card">
                                <div class="card-body eshop-cards text-center pa-15">
                                    <div class="bg-light-info h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                                        <i class="ti ti-clock f-s-18 text-info"></i>
                                    </div>
                                    <h4 class="text-info mb-1 f-w-600"><?php echo e($stats['pending_requests']); ?></h4>
                                    <p class="f-w-500 text-dark f-s-12 mb-0"><?php echo e(__('profile.pending_requests')); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Future Events Mobile -->
            <div class="card mb-3">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><?php echo e(__('profile.organized_events_title')); ?></h5>
                        <a href="#" class="btn btn-sm btn-primary hover-effect">
                            <?php echo e(__('profile.view_all_events')); ?>

                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if($recentEvents->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                <?php $__currentLoopData = $recentEvents->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <div>
                                            <h6 class="mb-0 f-w-600 f-s-14"><?php echo e($event->title); ?></h6>
                                            <small class="text-muted f-s-12"><?php echo e($event->start_datetime->format('d/m/Y H:i')); ?></small>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="badge bg-primary f-s-11"><?php echo e($event->status); ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-4">
                        <div class="bg-light-primary h-50 w-50 d-flex-center rounded-circle m-auto mb-2">
                            <i class="ti ti-calendar-x f-s-24 text-primary"></i>
                        </div>
                        <p class="text-muted f-s-14 mb-0"><?php echo e(__('profile.no_organized_events')); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Participated Events Mobile -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5><?php echo e(__('profile.participated_events_title')); ?></h5>
                </div>
                <div class="card-body">
                    <?php if($participatedEvents->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                <?php $__currentLoopData = $participatedEvents->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $participation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <div>
                                            <h6 class="mb-0 f-w-600 f-s-14"><?php echo e($participation->event->title); ?></h6>
                                            <small class="text-muted f-s-12"><?php echo e($participation->event->start_datetime->format('d/m/Y H:i')); ?></small>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="badge bg-success f-s-11"><?php echo e(__('profile.participated')); ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-4">
                        <div class="bg-light-success h-50 w-50 d-flex-center rounded-circle m-auto mb-2">
                            <i class="ti ti-users-slash f-s-24 text-success"></i>
                        </div>
                        <p class="text-muted f-s-14 mb-0"><?php echo e(__('profile.no_participated_events')); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Desktop Layout -->
    <div class="row d-none d-md-flex">
        <div class="col-lg-3">
            <!-- Profile Tabs -->
            <div class="card">
                <div class="card-body">
                    <div class="tab-wrapper">
                        <ul class="profile-app-tabs">
                            <li class="tab-link fw-medium f-s-16 f-w-600 active" data-tab="1">
                                <i class="ti ti-user fw-bold"></i> <?php echo e(__('profile.profile')); ?>

                            </li>
                            <li class="tab-link fw-medium f-s-16 f-w-600" data-tab="2">
                                <i class="ti ti-video-camera fw-bold"></i> <?php echo e(__('profile.my_videos')); ?>

                                <?php if($videos->count() > 0): ?>
                                <span class="badge rounded-pill bg-warning badge-notification">
                                    <?php echo e($videos->count()); ?>

                                </span>
                                <?php endif; ?>
                            </li>
                            <li class="tab-link fw-medium f-s-16 f-w-600" data-tab="3">
                                <i class="ti ti-calendar-plus fw-bold"></i> <?php echo e(__('profile.organized_events')); ?>

                                <?php if($recentEvents->count() > 0): ?>
                                <span class="badge rounded-pill bg-primary badge-notification">
                                    <?php echo e($recentEvents->count()); ?>

                                </span>
                                <?php endif; ?>
                            </li>
                            <li class="tab-link fw-medium f-s-16 f-w-600" data-tab="4">
                                <i class="ti ti-activity fw-bold"></i> <?php echo e(__('profile.my_activities')); ?>

                                <?php if($recentActivity->count() > 0): ?>
                                <span class="badge rounded-pill bg-info badge-notification">
                                    <?php echo e($recentActivity->count()); ?>

                                </span>
                                <?php endif; ?>
                            </li>
                            <?php if($isOwnProfile): ?>
                            <li class="tab-link fw-medium f-s-16 f-w-600" data-tab="5">
                                <i class="ti ti-settings fw-bold"></i> <?php echo e(__('profile.settings')); ?>

                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <?php if($isOwnProfile): ?>
            <div class="card d-lg-block d-none">
                <div class="card-header">
                    <h5><?php echo e(__('profile.quick_actions')); ?></h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-column gap-2">
                        <a href="<?php echo e(route('profile.edit')); ?>" class="btn btn-primary hover-effect">
                            <i class="ti ti-edit me-2"></i><?php echo e(__('profile.modify_profile')); ?>

                        </a>
                        <a href="<?php echo e(route('profile.videos')); ?>" class="btn btn-success hover-effect">
                            <i class="ti ti-video-camera me-2"></i><?php echo e(__('profile.manage_videos')); ?>

                        </a>
                        <a href="<?php echo e(route('profile.activity')); ?>" class="btn btn-info hover-effect">
                            <i class="ti ti-activity me-2"></i><?php echo e(__('profile.view_all_activity')); ?>

                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-5 col-xxl-6 col-box-5">
            <!-- Profile Content -->
            <div class="content-wrapper">
                <!-- Tab 1: Profile -->
                <div id="tab-1" class="tabs-content active">
                    <div class="profile-content">
                        <!-- Profile Header -->
                        <div class="card">
                            <div class="card-body">
                                <div class="profile-container">
                                    <div class="image-details">
                                        <div class="profile-image" style="background-image: url('<?php echo e($user->banner_image_url); ?>')">
                                            <?php if($isOwnProfile): ?>
                                            <div class="banner-edit">
                                                <input type="file" id="bannerUpload" accept=".png, .jpg, .jpeg" onchange="uploadBannerImage(this)">
                                                <label for="bannerUpload" class="btn btn-light btn-sm">
                                                    <i class="ti ti-photo-heart me-1"></i><?php echo e(__('profile.change_banner')); ?>

                                                </label>
                                            </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="profile-pic">
                                            <div class="avatar-upload">
                                                <?php if($isOwnProfile): ?>
                                                <div class="avatar-edit">
                                                    <input type="file" id="imageUpload" accept=".png, .jpg, .jpeg" onchange="uploadProfilePhoto(this)">
                                                    <label for="imageUpload"><i class="ti ti-photo-heart"></i></label>
                                                </div>
                                                <?php endif; ?>
                                                <div class="avatar-preview">
                                                    <div id="imgPreview">
                                                        <?php if($user->profile_photo): ?>
                                                            <img src="<?php echo e($user->profile_photo_url); ?>" alt="Profile Photo" class="img-fluid">
                                                        <?php else: ?>
                                                            <div class="bg-light-primary h-120 w-120 d-flex-center b-r-50">
                                                                <span class="text-primary fw-bold f-s-24"><?php echo e(substr($user->getDisplayName(), 0, 2)); ?></span>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="person-details">
                                        <h5 class="f-w-600"><?php echo e($user->getDisplayName()); ?></h5>
                                        <?php if($user->nickname && $user->nickname !== $user->name): ?>
                                        <p class="text-muted"><?php echo e($user->nickname); ?></p>
                                        <?php endif; ?>

                                        <!-- Roles -->
                                        <div class="mb-3">
                                            <?php $__currentLoopData = $user->getRoleNames(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <span class="badge bg-primary me-1 f-s-12"><?php echo e(__('auth.role_' . $role)); ?></span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>

                                        <!-- Statistics removed from desktop profile header - now in sidebar -->

                                        <?php if(!$isOwnProfile): ?>
                                        <div class="my-2">
                                            <button type="button" class="btn btn-primary b-r-22" onclick="followUser(<?php echo e($user->id); ?>)">
                                                <i class="ti ti-user-plus me-2"></i><?php echo e(__('profile.follow')); ?>

                                            </button>
                                            <button type="button" class="btn btn-outline-primary b-r-22 ms-2" onclick="sendMessage(<?php echo e($user->id); ?>)">
                                                <i class="ti ti-message-circle me-2"></i><?php echo e(__('profile.send_message')); ?>

                                            </button>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- About Me -->
                        <div class="card">
                            <div class="card-header">
                                <h5><?php echo e(__('profile.about_me')); ?></h5>
                            </div>
                            <div class="card-body">
                                <?php if($user->bio): ?>
                                <p class="text-muted f-s-13"><?php echo e($user->bio); ?></p>
                                <?php else: ?>
                                <p class="text-muted f-s-13"><?php echo e(__('profile.no_bio_available')); ?></p>
                                <?php endif; ?>

                                <div class="about-list">
                                    <?php if($user->email): ?>
                                    <div>
                                        <span class="fw-medium"><i class="ti ti-mail"></i> <?php echo e(__('profile.email')); ?></span>
                                        <span class="float-end f-s-13 text-secondary"><?php echo e($user->email); ?></span>
                                    </div>
                                    <?php endif; ?>
                                    <?php if($user->phone): ?>
                                    <div>
                                        <span class="fw-medium"><i class="ti ti-phone"></i> <?php echo e(__('profile.phone')); ?></span>
                                        <span class="float-end f-s-13 text-secondary"><?php echo e($user->phone); ?></span>
                                    </div>
                                    <?php endif; ?>
                                    <?php if($user->location): ?>
                                    <div>
                                        <span class="fw-semibold"><i class="ti ti-map-pin"></i> <?php echo e(__('profile.location')); ?></span>
                                        <span class="float-end f-s-13 text-secondary"><?php echo e($user->location); ?></span>
                                    </div>
                                    <?php endif; ?>
                                    <div>
                                        <span class="fw-medium"><i class="ti ti-calendar"></i> <?php echo e(__('profile.member_since')); ?></span>
                                        <span class="float-end f-s-13 text-secondary"><?php echo e($user->created_at->format('M Y')); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab 2: Videos -->
                <div id="tab-2" class="tabs-content">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><?php echo e(__('profile.my_videos')); ?></h5>
                                <?php if($isOwnProfile): ?>
                                <a href="<?php echo e(route('profile.videos')); ?>" class="btn btn-sm btn-warning hover-effect">
                                    <?php echo e(__('profile.manage_videos')); ?>

                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if($videos->count() > 0): ?>
                            <div class="row">
                                <?php $__currentLoopData = $videos->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-md-6 mb-3">
                                    <div class="card hover-effect">
                                        <div class="position-relative">
                                            <?php if($video->thumbnail_url && $video->thumbnail_url !== asset('assets/images/placeholder/placholder-1.jpg')): ?>
                                                <!-- <?php echo e(__('common.thumbnail')); ?> con overlay play -->
                                                <div class="position-relative" style="cursor: pointer;" onclick="window.location.href='<?php echo e(route('videos.show', $video)); ?>'">
                                                    <img src="<?php echo e($video->thumbnail_url); ?>" alt="<?php echo e($video->title); ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                                                    <!-- Overlay play button -->
                                                    <div class="position-absolute top-50 start-50 translate-middle">
                                                        <div class="play-button bg-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; box-shadow: 0 4px 15px rgba(0,0,0,0.3); transition: all 0.3s ease;">
                                                            <i class="ph-duotone ph-play f-s-24 text-primary"></i>
                                                        </div>
                                                    </div>
                                                    <!-- Duration overlay -->
                                                    <div class="position-absolute bottom-0 start-0 end-0 p-3" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                                                        <small class="text-white f-s-12">
                                                            <i class="ph-duotone ph-clock me-1"></i>
                                                            <?php if($video->duration && $video->duration > 0): ?>
                                                                <?php echo e($video->formatted_duration); ?>

                                                            <?php else: ?>
                                                                <span title="<?php echo e(__('videos.duration_unavailable')); ?>">--:--</span>
                                                            <?php endif; ?>
                                                        </small>
                                                    </div>
                                                    <!-- Views badge -->
                                                    <div class="position-absolute top-0 end-0 m-2">
                                                        <span class="badge bg-dark f-s-11"><?php echo e($video->view_count ?? $video->views); ?> <?php echo e(__('profile.views')); ?></span>
                                                    </div>
                                                </div>
                                            <?php elseif($video->peertube_uuid): ?>
                                                <!-- Anteprima video con overlay play -->
                                                <div class="card-img-top video-preview bg-gradient-primary d-flex align-items-center justify-content-center position-relative"
                                                     style="height: 200px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); cursor: pointer;"
                                                     onclick="window.location.href='<?php echo e(route('videos.show', $video)); ?>'">
                                                    <div class="position-absolute top-50 start-50 translate-middle">
                                                        <div class="play-button bg-white rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; box-shadow: 0 4px 15px rgba(0,0,0,0.3); transition: all 0.3s ease;">
                                                            <i class="ph-duotone ph-play f-s-24 text-primary"></i>
                                                        </div>
                                                    </div>
                                                    <div class="position-absolute bottom-0 start-0 end-0 p-3" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                                                        <small class="text-white f-s-12">
                                                            <i class="ph-duotone ph-clock me-1"></i>
                                                            <?php if($video->duration && $video->duration > 0): ?>
                                                                <?php echo e($video->formatted_duration); ?>

                                                            <?php else: ?>
                                                                <span title="<?php echo e(__('videos.duration_unavailable')); ?>">--:--</span>
                                                            <?php endif; ?>
                                                        </small>
                                                    </div>
                                                    <!-- Views badge -->
                                                    <div class="position-absolute top-0 end-0 m-2">
                                                        <span class="badge bg-dark f-s-11"><?php echo e($video->view_count ?? $video->views); ?> <?php echo e(__('profile.views')); ?></span>
                                                    </div>
                                                </div>
                                            <?php else: ?>
                                                <!-- Fallback per video senza thumbnail -->
                                                <div class="position-relative" style="cursor: pointer;" onclick="window.location.href='<?php echo e(route('videos.show', $video)); ?>'">
                                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                                        <div class="text-center">
                                                            <i class="ph-duotone ph-video-camera f-s-48 text-muted mb-2"></i>
                                                            <div class="play-button bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 60px; height: 60px; box-shadow: 0 4px 15px rgba(0,0,0,0.3); transition: all 0.3s ease;">
                                                                <i class="ph-duotone ph-play f-s-24 text-white"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Views badge -->
                                                    <div class="position-absolute top-0 end-0 m-2">
                                                        <span class="badge bg-dark f-s-11"><?php echo e($video->view_count ?? $video->views); ?> <?php echo e(__('profile.views')); ?></span>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="card-body pa-15">
                                            <h6 class="card-title f-w-600 f-s-14 mb-1"><?php echo e($video->title); ?></h6>
                                            <?php if($video->description): ?>
                                            <p class="text-muted f-s-12 mb-2"><?php echo e(Str::limit($video->description, 60)); ?></p>
                                            <?php endif; ?>
                                            <small class="text-muted f-s-11"><?php echo e($video->created_at->diffForHumans()); ?></small>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <?php else: ?>
                            <div class="text-center py-4">
                                <div class="bg-light-warning h-50 w-50 d-flex-center rounded-circle m-auto mb-2">
                                    <i class="ti ti-video-camera-slash f-s-24 text-warning"></i>
                                </div>
                                <p class="text-muted f-s-14 mb-0"><?php echo e(__('profile.no_videos_uploaded')); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Tab 3: Organized Events -->
                <div id="tab-3" class="tabs-content">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><?php echo e(__('profile.organized_events_title')); ?></h5>
                                <a href="#" class="btn btn-sm btn-primary hover-effect">
                                    <?php echo e(__('profile.view_all_events')); ?>

                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if($recentEvents->count() > 0): ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <tbody>
                                        <?php $__currentLoopData = $recentEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $event): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <div>
                                                    <h6 class="mb-0 f-w-600 f-s-14"><?php echo e($event->title); ?></h6>
                                                    <small class="text-muted f-s-12"><?php echo e($event->start_datetime->format('d/m/Y H:i')); ?></small>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <span class="badge bg-primary f-s-11"><?php echo e($event->status); ?></span>
                                            </td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php else: ?>
                            <div class="text-center py-4">
                                <div class="bg-light-primary h-50 w-50 d-flex-center rounded-circle m-auto mb-2">
                                    <i class="ti ti-calendar-x f-s-24 text-primary"></i>
                                </div>
                                <p class="text-muted f-s-14 mb-0"><?php echo e(__('profile.no_organized_events')); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Tab 4: Activities -->
                <div id="tab-4" class="tabs-content">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0"><?php echo e(__('profile.recent_activity')); ?></h5>
                                <?php if($isOwnProfile): ?>
                                <a href="<?php echo e(route('profile.activity')); ?>" class="btn btn-sm btn-info hover-effect">
                                    <?php echo e(__('profile.view_all_activity')); ?>

                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if($recentActivity->count() > 0): ?>
                            <?php $__currentLoopData = $recentActivity->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                                <div class="flex-shrink-0">
                                    <div class="bg-light-<?php echo e($activity['color']); ?> h-35 w-35 d-flex-center rounded-circle">
                                        <i class="ti <?php echo e($activity['icon']); ?> text-<?php echo e($activity['color']); ?> f-s-14"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <p class="mb-0 fw-500 f-s-14"><?php echo e($activity['title']); ?></p>
                                    <small class="text-muted f-s-12"><?php echo e($activity['date']->diffForHumans()); ?></small>
                                </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php else: ?>
                            <div class="text-center py-4">
                                <div class="bg-light-info h-50 w-50 d-flex-center rounded-circle m-auto mb-2">
                                    <i class="ti ti-activity-slash f-s-24 text-info"></i>
                                </div>
                                <p class="text-muted f-s-14 mb-0"><?php echo e(__('profile.no_recent_activity')); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Tab 5: Settings (Only for own profile) -->
                <?php if($isOwnProfile): ?>
                <div id="tab-5" class="tabs-content">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0"><?php echo e(__('profile.settings')); ?></h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <a href="<?php echo e(route('profile.edit')); ?>" class="card card-light-primary hover-effect text-decoration-none">
                                        <div class="card-body text-center py-3">
                                            <i class="ti ti-edit f-s-30 text-primary mb-2"></i>
                                            <h6 class="mb-1"><?php echo e(__('profile.modify_profile')); ?></h6>
                                            <small class="text-muted"><?php echo e(__('profile.edit_my_profile')); ?></small>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <a href="<?php echo e(route('profile.videos')); ?>" class="card card-light-success hover-effect text-decoration-none">
                                        <div class="card-body text-center py-3">
                                            <i class="ti ti-video-camera f-s-30 text-success mb-2"></i>
                                            <h6 class="mb-1"><?php echo e(__('profile.my_videos')); ?></h6>
                                            <small class="text-muted"><?php echo e(__('profile.view_my_videos')); ?></small>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <a href="<?php echo e(route('profile.activity')); ?>" class="card card-light-warning hover-effect text-decoration-none">
                                        <div class="card-body text-center py-3">
                                            <i class="ti ti-activity f-s-30 text-warning mb-2"></i>
                                            <h6 class="mb-1"><?php echo e(__('profile.my_activities')); ?></h6>
                                            <small class="text-muted"><?php echo e(__('profile.view_my_activities')); ?></small>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <a href="#" class="card card-light-info hover-effect text-decoration-none">
                                        <div class="card-body text-center py-3">
                                            <i class="ti ti-bell f-s-30 text-info mb-2"></i>
                                            <h6 class="mb-1"><?php echo e(__('profile.notifications')); ?></h6>
                                            <small class="text-muted"><?php echo e(__('profile.manage_notifications')); ?></small>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-lg-4 col-xxl-3 col-box-4 order-lg--1">
            <!-- Statistics Cards -->
            <div class="card mb-3">
                <div class="card-header">
                    <h5><?php echo e(__('profile.statistics')); ?></h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="card card-light-primary hover-effect equal-card">
                                <div class="card-body eshop-cards text-center pa-15">
                                    <div class="bg-light-primary h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                                        <i class="ti ti-calendar-plus f-s-18 text-primary"></i>
                                    </div>
                                    <h4 class="text-primary mb-1 f-w-600"><?php echo e($stats['total_events']); ?></h4>
                                    <p class="f-w-500 text-dark f-s-12 mb-0"><?php echo e(__('profile.organized_events')); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card card-light-success hover-effect equal-card">
                                <div class="card-body eshop-cards text-center pa-15">
                                    <div class="bg-light-success h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                                        <i class="ti ti-users f-s-18 text-success"></i>
                                    </div>
                                    <h4 class="text-success mb-1 f-w-600"><?php echo e($stats['participated_events']); ?></h4>
                                    <p class="f-w-500 text-dark f-s-12 mb-0"><?php echo e(__('profile.participated_events')); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card card-light-warning hover-effect equal-card">
                                <div class="card-body eshop-cards text-center pa-15">
                                    <div class="bg-light-warning h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                                        <i class="ti ti-video-camera f-s-18 text-warning"></i>
                                    </div>
                                    <h4 class="text-warning mb-1 f-w-600"><?php echo e($stats['total_videos']); ?></h4>
                                    <p class="f-w-500 text-dark f-s-12 mb-0"><?php echo e(__('profile.uploaded_videos')); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card card-light-info hover-effect equal-card">
                                <div class="card-body eshop-cards text-center pa-15">
                                    <div class="bg-light-info h-40 w-40 d-flex-center rounded-circle m-auto mb-2">
                                        <i class="ti ti-clock f-s-18 text-info"></i>
                                    </div>
                                    <h4 class="text-info mb-1 f-w-600"><?php echo e($stats['pending_requests']); ?></h4>
                                    <p class="f-w-500 text-dark f-s-12 mb-0"><?php echo e(__('profile.pending_requests')); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Participated Events -->
            <div class="card">
                <div class="card-header">
                    <h5><?php echo e(__('profile.participated_events_title')); ?></h5>
                </div>
                <div class="card-body">
                    <?php if($participatedEvents->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <tbody>
                                <?php $__currentLoopData = $participatedEvents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $participation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <div>
                                            <h6 class="mb-0 f-w-600 f-s-14"><?php echo e($participation->event->title); ?></h6>
                                            <small class="text-muted f-s-12"><?php echo e($participation->event->start_datetime->format('d/m/Y H:i')); ?></small>
                                        </div>
                                    </td>
                                    <td class="text-end">
                                        <span class="badge bg-success f-s-11"><?php echo e(__('profile.participated')); ?></span>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="text-center py-4">
                        <div class="bg-light-success h-50 w-50 d-flex-center rounded-circle m-auto mb-2">
                            <i class="ti ti-users-slash f-s-24 text-success"></i>
                        </div>
                        <p class="text-muted f-s-14 mb-0"><?php echo e(__('profile.no_participated_events')); ?></p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Hidden file input for profile photo -->
<?php if($isOwnProfile): ?>
<input type="file" id="profile-photo-input" style="display: none;" accept="image/*" onchange="uploadProfilePhoto(this)">
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Tab functionality
document.addEventListener('DOMContentLoaded', function() {
    const tabLinks = document.querySelectorAll('.tab-link');
    const tabContents = document.querySelectorAll('.tabs-content');

    tabLinks.forEach(link => {
        link.addEventListener('click', function() {
            const tabId = this.getAttribute('data-tab');

            // Remove active class from all tabs and contents
            tabLinks.forEach(l => l.classList.remove('active'));
            tabContents.forEach(c => c.classList.remove('active'));

            // Add active class to clicked tab and corresponding content
            this.classList.add('active');
            document.getElementById('tab-' + tabId).classList.add('active');
        });
    });
});

function followUser(userId) {
    // Implementazione follow
    Swal.fire('Info', '<?php echo e(__('profile.follow_development')); ?>', 'info');
}

function sendMessage(userId) {
    // Implementazione messaggi
    Swal.fire('Info', '<?php echo e(__('profile.messages_development')); ?>', 'info');
}

function uploadProfilePhoto(input) {
    if (input.files && input.files[0]) {
        const formData = new FormData();
        formData.append('profile_photo', input.files[0]);
        formData.append('_token', '<?php echo e(csrf_token()); ?>');
        formData.append('_method', 'PUT');

        fetch('<?php echo e(route("profile.update")); ?>', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                Swal.fire('Errore', data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Errore', 'Errore durante il caricamento della foto', 'error');
        });
    }
}

function uploadBannerImage(input) {
    if (input.files && input.files[0]) {
        const formData = new FormData();
        formData.append('banner_image', input.files[0]);
        formData.append('_token', '<?php echo e(csrf_token()); ?>');
        formData.append('_method', 'PUT');

        fetch('<?php echo e(route("profile.update")); ?>', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                Swal.fire('Errore', data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Errore', 'Errore durante il caricamento dell\'immagine', 'error');
        });
    }
}

// Hide loader as fallback
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        const loader = document.querySelector('.loader-wrapper');
        if (loader) {
            loader.style.display = 'none';
        }
    }, 1000);
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/profile/show.blade.php ENDPATH**/ ?>