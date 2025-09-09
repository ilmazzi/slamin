<!-- Menu Navigation starts -->
<nav class="vertical-sidebar">
    <div class="app-logo">
        <a class="logo d-inline-block" href="/">
            <!-- Loghino per sidebar collassata -->
            <img alt="Slam In" src="<?php echo e(asset('../assets/images/Loghino_nerosubianco.png')); ?>" class="logo-icon">
            <!-- Logo orizzontale per sidebar espansa -->
            <img alt="Slam In" src="<?php echo e(asset('../assets/images/Logo_orizzontale_nerosubianco.png')); ?>" class="logo-full">
        </a>

        <span class="bg-light-primary toggle-semi-nav d-flex-center">
            <i class="ti ti-chevron-right"></i>
        </span>

        <?php if(auth()->guard()->check()): ?>
        <div class="d-flex align-items-center nav-profile p-3">
            <a href="<?php echo e(route('profile.show')); ?>" class="text-decoration-none d-flex align-items-center flex-grow-1" style="cursor: pointer;">
                <span class="h-45 w-45 d-flex-center b-r-10 position-relative m-auto">
                    <img alt="avatar" class="img-fluid b-r-10" src="<?php echo e(\App\Helpers\AvatarHelper::getUserAvatarUrl(auth()->user())); ?>">
                    <span
                    class="position-absolute top-0 end-0 p-1 <?php echo e(auth()->user()->presence_class); ?> border border-light rounded-circle"
                    title="<?php echo e(auth()->user()->presence_label); ?>">
                </span>
                </span>
                <div class="flex-grow-1 ps-2">
                    <h6 class="text-primary mb-0 text-truncate" style="max-width: 150px;"><?php echo e(auth()->user()->getDisplayName()); ?></h6>
                    <p class="text-muted f-s-12 mb-0 text-truncate" style="max-width: 150px;">
                        <?php if(auth()->user()->getRoleNames()->count() > 0): ?>
                            <?php
                                $role = auth()->user()->getRoleNames()->first();
                                $roleDisplay = match($role) {
                                    'admin' => 'Amministratore',
                                    'moderatore' => 'Moderatore',
                                    'organizzatore' => __('events.organizer'),
                                    'poeta' => 'Poeta',
                                    'giudice' => 'Giudice',
                                    'spettatore' => 'Spettatore',
                                    default => ucfirst($role)
                                };
                            ?>
                            <?php echo e($roleDisplay); ?>

                        <?php else: ?>
                            <?php echo e(__('sidebar.slam_in_user')); ?>

                        <?php endif; ?>
                    </p>
                </div>
            </a>

            <div class="dropdown profile-menu-dropdown">
                <a aria-expanded="false" data-bs-auto-close="true" data-bs-placement="top" data-bs-toggle="dropdown" role="button">
                    <i class="ti ti-settings fs-5"></i>
                </a>
                <ul class="dropdown-menu">
                    <li class="dropdown-item">
                        <a class="f-w-500" href="<?php echo e(route('profile.edit')); ?>">
                            <i class="ph-duotone ph-gear pe-1 f-s-20"></i> <?php echo e(__('sidebar.settings')); ?>

                        </a>
                    </li>
                    <li class="dropdown-item">
                        <a class="f-w-500" href="<?php echo e(route('profile.payment-accounts.index')); ?>">
                            <i class="ph-duotone ph-credit-card pe-1 f-s-20"></i> Conti di Pagamento
                        </a>
                    </li>
                    <li class="dropdown-item">
                        <a class="f-w-500" href="<?php echo e(route('profile.languages.index')); ?>">
                            <i class="ph-duotone ph-translate pe-1 f-s-20"></i> <?php echo e(__('languages.title')); ?>

                        </a>
                    </li>

                    <?php if(auth()->user()?->hasRole('admin')): ?>
                    <li class="dropdown-item">
                        <a class="f-w-500" href="#" data-bs-toggle="offcanvas" data-bs-target="#customizerOptions" aria-controls="customizerOptions">
                            <i class="ph-duotone ph-palette pe-1 f-s-20"></i> <?php echo e(__('common.customize_layout')); ?>

                        </a>
                    </li>
                    <?php endif; ?>

                    <li class="dropdown-item">
                        <a class="mb-0 text-secondary f-w-500" href="<?php echo e(route('register')); ?>">
                            <i class="ph-bold ph-plus pe-1 f-s-20"></i> <?php echo e(__('sidebar.add_account')); ?>

                        </a>
                    </li>

                    <li class="app-divider-v dotted py-1"></li>

                    <li class="dropdown-item">
                        <form action="<?php echo e(route('logout')); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-link p-0 mb-0 text-danger f-w-500" style="text-decoration: none;">
                                <i class="ph-duotone ph-sign-out pe-1 f-s-20"></i> <?php echo e(__('sidebar.logout_button')); ?>

                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
        <?php else: ?>
        <!-- Login/Register Buttons per utenti non autenticati - Nella sezione profilo -->
        <div class="d-flex align-items-center nav-profile p-3">
            <div class="d-flex flex-column gap-2 w-100">
                <a href="<?php echo e(route('login')); ?>" class="btn btn-primary w-100">
                    <i class="ph ph-sign-in me-2"></i> <?php echo e(__('auth.login')); ?>

                </a>
                <a href="<?php echo e(route('register')); ?>" class="btn btn-outline-primary w-100">
                    <i class="ph ph-user-plus me-2"></i> <?php echo e(__('auth.register')); ?>

                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <div class="app-nav simplebar-scrollable-y" id="app-simple-bar" data-simplebar="init">
        <div class="simplebar-wrapper" style="margin: 0px;">
            <div class="simplebar-height-auto-observer-wrapper">
                <div class="simplebar-height-auto-observer"></div>
            </div>
            <div class="simplebar-mask">
                <div class="simplebar-offset" style="right: 0px; bottom: 0px;">
                    <div class="simplebar-content-wrapper" tabindex="0" role="region" aria-label="scrollable content" style="height: 100%; overflow: hidden scroll;">
                        <div class="simplebar-content" style="padding: 0px;">
                            <ul class="main-nav p-0 mt-2" style="margin-left: 0px;">


                                <!-- Eventi Section -->
                                <li class="no-sub <?php echo e(request()->routeIs('events.*') ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('events.index')); ?>">
                                        <svg stroke="currentColor" stroke-width="1.5">
                                            <use xlink:href="../assets/svg/_sprite.svg#stack"></use>
                                        </svg>
                                        <?php echo e(__('events.events')); ?>

                                        <?php if(auth()->guard()->check()): ?>
                                        <?php if(auth()->user()->organizedEvents()->where('start_datetime', '>', now())->count() > 0): ?>
                                            <span class="badge bg-primary badge-notification ms-2">
                                                <?php echo e(auth()->user()->organizedEvents()->where('start_datetime', '>', now())->count()); ?>

                                            </span>
                                        <?php endif; ?>
                                        <?php endif; ?>
                                    </a>
                                </li>

                                <?php if(auth()->guard()->check()): ?>
                                <?php if (! (auth()->user()?->hasRole('audience'))): ?>
                                <!-- Gigs Section -->
                                <li class="no-sub <?php echo e(request()->routeIs('gigs.*') ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('gigs.index')); ?>">
                                        <i class="ph-duotone ph-briefcase f-s-20 me-2"></i>
                                        <?php echo e(__('gigs.title')); ?>

                                        <?php if(auth()->user()->gigs()->open()->count() > 0): ?>
                                            <span class="badge bg-success badge-notification ms-2">
                                                <?php echo e(auth()->user()->gigs()->open()->count()); ?>

                                            </span>
                                        <?php endif; ?>
                                    </a>
                                </li>
                                <?php endif; ?>
                                <?php endif; ?>



                                <!-- <?php echo e(__('common.media_section')); ?> Section -->
                                <li class="no-sub <?php echo e(request()->routeIs('media.*') ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('media.index')); ?>">
                                        <i class="ph-duotone ph-video-camera f-s-20 me-2"></i>
                                        <?php echo e(__('common.media_section')); ?>

                                    </a>
                                </li>

                                <!-- Articoli Section -->
                                <li class="no-sub <?php echo e(request()->routeIs('articles.*') ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('articles.index')); ?>">
                                        <i class="ph-duotone ph-newspaper f-s-20 me-2"></i>
                                        <?php echo e(__('common.articles_section_menu')); ?>

                                        <?php if(auth()->guard()->check()): ?>
                                        <?php if(auth()->user()->can('articles.view')): ?>
                                            <?php
                                                $draftArticlesCount = \App\Models\Article::where('user_id', auth()->id())->where('status', 'draft')->count();
                                            ?>
                                            <?php if($draftArticlesCount > 0): ?>
                                                <span class="badge bg-warning badge-notification ms-2">
                                                    <?php echo e($draftArticlesCount); ?>

                                                </span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <?php endif; ?>
                                    </a>
                                </li>


                                <!-- Poesie Section -->
                                <li class="no-sub <?php echo e(request()->routeIs('poems.*') ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('poems.index')); ?>">
                                        <i class="ph-duotone ph-book-open f-s-20 me-2"></i>
                                        <?php echo e(__('poems.title')); ?>

                                        <?php if(auth()->guard()->check()): ?>
                                        <?php if(auth()->user()->poems()->drafts()->count() > 0): ?>
                                            <span class="badge bg-warning badge-notification ms-2">
                                                <?php echo e(auth()->user()->poems()->drafts()->count()); ?>

                                            </span>
                                        <?php endif; ?>
                                        <?php endif; ?>
                                    </a>
                                </li>


                                <?php if(auth()->guard()->check()): ?>
                                <!-- Gruppi Section - Solo per poeti e organizzatori -->
                                <?php if(auth()->user()->can('groups.create')): ?>
                                <li class="no-sub <?php echo e(request()->routeIs('groups.*') ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('groups.index')); ?>">
                                        <i class="ph-duotone ph-users f-s-20 me-2"></i>
                                        <?php echo e(__('common.groups_section_menu')); ?>

                                        <?php if(auth()->user()->getGroupsCountAttribute() > 0): ?>
                                            <span class="badge bg-info badge-notification ms-2">
                                                <?php echo e(auth()->user()->getGroupsCountAttribute()); ?>

                                            </span>
                                        <?php endif; ?>
                                    </a>
                                </li>
                                <?php endif; ?>


                                <li class="menu-title d-none d-lg-block"><span>PROSSIMAMENTE</span></li>


                                <?php if(auth()->guard()->check()): ?>
                                <!-- <?php echo e(__('common.didactic')); ?> Section - DISABILITATO (non implementato) -->
                                <li class="no-sub nav-item disabled d-none d-lg-block">
                                    <a href="#" class="nav-link disabled" style="pointer-events: none; opacity: 0.6;">
                                        <i class="ph-duotone ph-microphone-stage text-muted f-s-20 me-2"></i>
                                        <span class="text-muted"><?php echo e(__('common.didactic')); ?></span>
                                    </a>
                                </li>
                                <?php endif; ?>
                                <?php if(auth()->guard()->check()): ?>
                                <!-- <?php echo e(__('common.forum')); ?> Section - DISABILITATO (non implementato) -->
                                <li class="no-sub nav-item disabled d-none d-lg-block">
                                    <a href="#" class="nav-link disabled" style="pointer-events: none; opacity: 0.6;">
                                        <i class="ph-duotone ph-microphone-stage text-muted f-s-20 me-2"></i>
                                        <span class="text-muted"><?php echo e(__('common.forum')); ?></span>
                                    </a>
                                </li>
                                <?php endif; ?>
                                <?php if(auth()->guard()->check()): ?>
                                <!-- <?php echo e(__('common.fan_support')); ?> Section - DISABILITATO (non implementato) -->
                                <li class="no-sub nav-item disabled d-none d-lg-block">
                                    <a href="#" class="nav-link disabled" style="pointer-events: none; opacity: 0.6;">
                                        <i class="ph-duotone ph-microphone-stage text-muted f-s-20 me-2"></i>
                                        <span class="text-muted"><?php echo e(__('common.fan_support')); ?></span>
                                    </a>
                                </li>
                                <?php endif; ?>
                                <?php if(auth()->guard()->check()): ?>
                                <!-- <?php echo e(__('common.wiki')); ?> Section - DISABILITATO (non implementato) -->
                                <li class="no-sub nav-item disabled d-none d-lg-block">
                                    <a href="#" class="nav-link disabled" style="pointer-events: none; opacity: 0.6;">
                                        <i class="ph-duotone ph-microphone-stage text-muted f-s-20 me-2"></i>
                                        <span class="text-muted"><?php echo e(__('common.wiki')); ?></span>
                                    </a>
                                </li>
                                <?php endif; ?>






                                <?php if(auth()->user()?->hasRole(['admin', 'moderator'])): ?>
                                <!-- Permissions Management Section - Solo per admin/moderator -->
                                <li class="menu-title">
                                    <span><?php echo e(__('sidebar.administration')); ?></span>
                                </li>

                                <!-- Admin Dashboard - Solo per admin -->
                                <?php if(auth()->user()?->hasRole('admin')): ?>
                                <li class="no-sub <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('admin.dashboard')); ?>">
                                        <i class="ph-duotone ph-chart-line f-s-20 me-2"></i>
                                        Dashboard Admin
                                    </a>
                                </li>
                                <?php endif; ?>

                                <li class="no-sub <?php echo e(request()->routeIs('permissions.*') ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('permissions.index')); ?>">
                                        <svg stroke="currentColor" stroke-width="1.5">
                                            <use xlink:href="../assets/svg/_sprite.svg#briefcase"></use>
                                        </svg>
                                        <?php echo e(__('sidebar.permissions_management')); ?>

                                    </a>
                                </li>

                                                                <!-- Moderation <?php echo e(__('dashboard.dashboard')); ?> - Solo per admin/moderator -->
                                <li class="no-sub <?php echo e(request()->routeIs('admin.moderation.*') ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('admin.moderation.index')); ?>" title="<?php echo e(__('sidebar.moderation_tooltip')); ?>">
                                        <i class="ph-duotone ph-shield-check f-s-20 me-2"></i>
                                        <?php echo e(__('sidebar.moderation')); ?>

                                        <?php
                                            $pendingCount = \App\Models\Video::pending()->count() +
                                                          \App\Models\Poem::pending()->count() +
                                                          \App\Models\Event::pending()->count() +
                                                          \App\Models\Photo::pending()->count() +
                                                          \App\Models\Carousel::pending()->count() +
                                                          \App\Models\Article::pending()->count() +
                                                          \App\Models\Report::active()->count();
                                        ?>
                                        <?php if($pendingCount > 0): ?>
                                            <span class="badge bg-warning badge-notification ms-2">
                                                <?php echo e($pendingCount); ?>

                                            </span>
                                        <?php endif; ?>
                                    </a>
                                </li>

                                <!-- System Settings - Solo per admin/moderator -->
                                <li class="no-sub <?php echo e(request()->routeIs('admin.settings.*') || request()->routeIs('admin.carousels.*') || request()->routeIs('admin.translations.*') || request()->routeIs('admin.peertube.*') ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('admin.settings.index')); ?>">
                                        <i class="ph-duotone ph-gear f-s-20 me-2"></i>
                                        <?php echo e(__('sidebar.settings')); ?>

                                    </a>
                                </li>

                                <!-- Payment Accounts Management - Solo per admin -->
                                <?php if(auth()->user()?->hasRole('admin')): ?>
                                <li class="no-sub <?php echo e(request()->routeIs('admin.payment-accounts.*') ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('admin.payment-accounts.index')); ?>">
                                        <i class="ph-duotone ph-credit-card f-s-20 me-2"></i>
                                        Conti di Pagamento
                                        <?php
                                            $pendingVerification = \App\Models\User::whereNotNull('paypal_email')
                                                ->where('paypal_verified', false)
                                                ->count();
                                        ?>
                                        <?php if($pendingVerification > 0): ?>
                                            <span class="badge bg-warning badge-notification ms-2">
                                                <?php echo e($pendingVerification); ?>

                                            </span>
                                        <?php endif; ?>
                                    </a>
                                </li>
                                <?php endif; ?>

                                <!-- PeerTube Configuration - Solo per admin/moderator -->
                                <li class="no-sub <?php echo e(request()->routeIs('admin.peertube.*') ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('admin.peertube.index')); ?>">
                                        <i class="ph-duotone ph-video-camera f-s-20 me-2"></i>
                                        PeerTube
                                    </a>
                                </li>

                                <!-- <?php echo e(__('common.kanban_board')); ?> - Solo per admin/moderator -->
                                <li class="no-sub <?php echo e(request()->routeIs('admin.kanban.*') ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('admin.kanban.index')); ?>">
                                        <i class="ph-duotone ph-kanban f-s-20 me-2"></i>
                                        <?php echo e(__('common.kanban_board')); ?>

                                    </a>
                                </li>

                                <!-- Articles Management - Solo per admin/moderator -->
                                <li class="no-sub <?php echo e(request()->routeIs('admin.articles.*') ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('admin.articles.index')); ?>">
                                        <i class="ph-duotone ph-newspaper f-s-20 me-2"></i>
                                        <?php echo e(__('articles.articles_management')); ?>

                                    </a>
                                </li>

                                <!-- System Logs - Solo per admin/moderator -->
                                <li class="no-sub <?php echo e(request()->routeIs('admin.logs.*') ? 'active' : ''); ?>">
                                    <a href="<?php echo e(route('admin.logs.index')); ?>">
                                        <i class="ph-duotone ph-newspaper f-s-20 me-2"></i>
                                        <?php echo e(__('sidebar.system_logs')); ?>

                                    </a>
                                </li>
                                <?php endif; ?>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="simplebar-placeholder" style="width: 288px; height: 1261px;"></div>
    </div>



    <div class="menu-navs">
        <span class="menu-previous d-none"><i class="ti ti-chevron-left"></i></span>
        <span class="menu-next d-none"><i class="ti ti-chevron-right"></i></span>
    </div>
    </nav>
    <!-- Menu Navigation ends -->

    <!-- Admin Customizer - Solo per admin -->
    <?php if(auth()->user()?->hasRole('admin')): ?>
    <div id="customizer"></div>
    <?php endif; ?>

<?php /**PATH C:\xampp\htdocs\slamin\resources\views/layout/sidebar.blade.php ENDPATH**/ ?>