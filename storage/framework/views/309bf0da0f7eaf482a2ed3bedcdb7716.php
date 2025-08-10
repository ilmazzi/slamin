<?php $__env->startSection('title', 'Slam in - Chat'); ?>

<?php $__env->startSection('main-content'); ?>
<meta name="current-user-id" content="<?php echo e(auth()->id()); ?>">

<div class="row position-relative chat-container-box">
    <div class="col-lg-4 col-xxl-3  box-col-5">
        <div class="chat-div">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                <span class="chatdp h-45 w-45 d-flex-center b-r-50 position-relative bg-danger">
                    <?php echo getUserAvatarHtml(auth()->user(), 'h-45 w-45', 'b-r-50'); ?>


                                         <span
                                class="position-absolute top-0 end-0 p-1 <?php echo e(auth()->user()->presence_class); ?> border border-light rounded-circle"
                                title="<?php echo e(auth()->user()->presence_label); ?>">
                </span>
                        </span>



                        <div class="flex-grow-1 ps-2">
                            <div class="fs-6"> <?php echo e(auth()->user()->name); ?></div>
                            <div class="text-muted f-s-12"> <?php echo e(auth()->user()->nickname); ?></div>
                        </div>
                        <div>
                            <div class="btn-group dropdown-icon-none">
                                <a role="button" data-bs-placement="top" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
                                    <i class="ti ti-settings fs-5"></i>
                                </a>
                                <ul class="dropdown-menu" data-popper-placement="bottom-start">
                                    <li><a class="dropdown-item" href="#"><i class="ti ti-brand-hipchat"></i> <span
                                        class="f-s-13">Chat Settings</span></a>
                                    <li><a class="dropdown-item" href="#"><i class="ti ti-brand-hipchat"></i> <span
                                                class="f-s-13">Chat Settings</span></a>
                                    </li>
                                    <li><a class="dropdown-item" href="#"><i class="ti ti-phone-call"></i> <span
                                                class="f-s-13">Contact Settings</span></a>
                                    </li>
                                    <li><a class="dropdown-item" href="#"><i class="ti ti-settings"></i> <span
                                                class="f-s-13">Settings</span></a>
                                    </li>
                                </ul>
                            </div>


                        </div>
                        <div class="close-togglebtn">
                            <a class="ms-2 toggle-btn" role="button">
                                <i class="ti ti-align-justified fs-5"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chat-tab-wrapper">
                        <ul class="tabs chat-tabs">
                            <li class="tab-link active" data-tab="1"><i class="ph-fill  ph-chat-circle-text f-s-18 me-2"></i>Chat</li>
                            <li class="tab-link" data-tab="2"><i class="ph-fill  ph-wechat-logo f-s-18 me-2"></i>Updates</li>
                            <li class="tab-link" data-tab="3"><i class="ph-fill  ph-phone-call f-s-18 me-2"></i>Contact</li>
                        </ul>
                    </div>
                    <div class="content-wrapper">

                        <!-- tab 1 -->

                        <div id="tab-1" class="tabs-content active">
                            <div class="tab-wrapper">
                                <div class="mt-3">
                                    <ul class="nav nav-tabs app-tabs-primary tab-light-primary chat-status-tab border-0 justify-content-between mb-0 pb-0" id="Basic"
                                        role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="private-tab" data-bs-toggle="tab"
                                                    data-bs-target="#private-tab-pane" type="button" role="tab"
                                                    aria-controls="private-tab-pane" aria-selected="false"
                                                    tabindex="-1"><i class="ph-fill  ph-lock-key-open me-2"></i>Private</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="groups-tab" data-bs-toggle="tab"
                                                    data-bs-target="#groups-tab-pane" type="button" role="tab"
                                                    aria-controls="groups-tab-pane" aria-selected="false" tabindex="-1"><i class="ph-fill  ph-users-three me-2"></i>Group </button>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="BasicContent">
                                        <!-- Private Chat -->
                                        <div class="tab-pane fade show active" id="private-tab-pane" role="tabpanel"
                                             aria-labelledby="private-tab" tabindex="0">
                                            <div class="chat-contact">
                                                <?php $__empty_1 = true; $__currentLoopData = $contacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <a href="<?php echo e(route('chat.index', ['room' => $contact['chat_room_id']])); ?>" class="text-decoration-none">
                                                <div class="chat-contactbox">
                                                    <div class="position-absolute">
                                                        <?php $user = \App\Models\User::find($contact['id']); ?>
                                                        <span class="h-45 w-45 d-flex-center b-r-10 position-relative m-auto"
                                                              data-user-id="<?php echo e($user->id); ?>">
                                                            <img alt="avatar" class="img-fluid b-r-10"
                                                                 src="<?php echo e(\App\Helpers\AvatarHelper::getUserAvatarUrl($user)); ?>">
                                                            <span class="position-absolute top-0 end-0 p-1 border border-light rounded-circle bg-secondary"
                                                                  data-presence-dot></span>
                                </span>
                                                    </div>
                                                    <div class="flex-grow-1 text-start mg-s-50">
                                                        <p class="mb-0 f-w-500 text-dark txt-ellipsis-1"><?php echo e($contact['name']); ?></p>
                                                        <p class="text-secondary mb-0 f-s-12 mb-0 chat-message">
                                                            <i class="ti ti-checks"></i> <?php echo e($contact['last_message'] ?: 'Nessun messaggio'); ?>

                                                        </p>
                                                        <!-- Typing indicator -->
                                                        <div class="typing-indicator-contact d-none" data-room-id="<?php echo e($contact['chat_room_id']); ?>">
                                                            <small class="text-info">
                                                                <i class="ti ti-pencil me-1"></i>sta scrivendo...
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <div>
                                                        <p class="f-s-12 chat-time"><?php echo e($contact['last_message_time'] ?: '--'); ?></p>
                                                    </div>
                                                </div>
                                                </a>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <div class="text-center py-4">
                                                    <p class="text-muted">Nessun contatto trovato</p>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <!-- Group Chat -->
                                        <div class="tab-pane fade" id="groups-tab-pane" role="tabpanel"
                                             aria-labelledby="groups-tab" tabindex="0">
                                            <div class="chat-contact chat-group-list">
                                                <div class="chat-contactbox">
                                                    <div class="position-absolute">
                                                        <ul class="avatar-group">
                                                            <li class="text-bg-warning h-45 w-45 d-flex-center b-r-50">
                                                                A
                                                            </li>
                                                            <li class="text-bg-secondary h-35 w-35 d-flex-center b-r-50"
                                                                data-bs-toggle="tooltip" data-bs-title="2 More">
                                                                2+
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="flex-grow-1 text-start mg-s-75">
                                                        <p class="mb-0 f-w-500 text-dark txt-ellipsis-1">Office Group</p>
                                                        <p class="text-secondary f-s-12 chat-message">Hi! Bette How are you?</p>
                                                    </div>
                                                    <div>
                                                        <p class="f-s-12 chat-time">2:30AM</p>
                                                    </div>
                                                </div>
                                                <div class="chat-contactbox">
                                                    <div class="position-absolute">
                                                        <ul class="avatar-group">
                                                            <li class="h-45 w-45 d-flex-center overflow-hidden b-r-50 bg-primary">
                                                                <img src="https://phplaravel-1384472-5380003.cloudwaysapps.com/../assets/images/avatar/16.png " alt="" class="img-fluid">
                                                            </li>
                                                            <li class="text-bg-secondary h-35 w-35 d-flex-center b-r-50"
                                                                data-bs-toggle="tooltip" data-bs-title="2 More">
                                                                4+
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="flex-grow-1 text-start mg-s-75">
                                                        <p class="mb-0 f-w-500 text-dark txt-ellipsis-1">Markting Group</p>
                                                        <p class="text-secondary f-s-12 chat-message">Hi! Work is done</p>
                                                    </div>
                                                    <div>
                                                        <p class="f-s-12 chat-time">7:24AM</p>
                                                    </div>
                                                </div>
                                                <div class="chat-contactbox">
                                                    <div class="position-absolute">
                                                        <ul class="avatar-group">
                                                            <li class="h-45 w-45 d-flex-center overflow-hidden b-r-50 bg-info">
                                                                <img src="https://phplaravel-1384472-5380003.cloudwaysapps.com/../assets/images/avatar/15.png " alt="" class="img-fluid">
                                                            </li>
                                                            <li class="text-bg-secondary h-35 w-35 d-flex-center b-r-50"
                                                                data-bs-toggle="tooltip" data-bs-title="2 More">
                                                                10+
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="flex-grow-1 text-start mg-s-75">
                                                        <p class="mb-0 f-w-500 text-dark txt-ellipsis-1">Developer Group</p>
                                                        <p class="text-secondary f-s-12 chat-message"> I'm waiting </p>
                                                    </div>
                                                    <div>
                                                        <p class="f-s-12 chat-time">2min</p>
                                                    </div>
                                                </div>
                                                <div class="chat-contactbox">
                                                    <div class="position-absolute">
                                                        <ul class="avatar-group">
                                                            <li class="text-bg-danger h-45 w-45 d-flex-center overflow-hidden b-r-50">
                                                                AD
                                                            </li>
                                                            <li class="text-bg-secondary h-35 w-35 d-flex-center b-r-50"
                                                                data-bs-toggle="tooltip" data-bs-title="2 More">
                                                                2+
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="flex-grow-1 text-start mg-s-75">
                                                        <p class="mb-0 f-w-500 text-dark txt-ellipsis-1">Designer Group</p>
                                                        <p class="text-secondary f-s-12 chat-message">Awesome! 🤩 I like it </p>
                                                    </div>
                                                    <div>
                                                        <p class="f-s-12 chat-time">2day</p>
                                                    </div>
                                                </div>
                                                <div class="chat-contactbox">
                                                    <div class="position-absolute">
                                                        <ul class="avatar-group">
                                                            <li class="h-45 w-45 d-flex-center overflow-hidden b-r-50 bg-dark">
                                                                <img src="https://phplaravel-1384472-5380003.cloudwaysapps.com/../assets/images/avatar/14.png " alt="" class="img-fluid">
                                                            </li>
                                                            <li class="text-bg-secondary h-35 w-35 d-flex-center b-r-50"
                                                                data-bs-toggle="tooltip" data-bs-title="2 More">
                                                                15+
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="flex-grow-1 text-start mg-s-75">
                                                        <p class="mb-0 f-w-500 text-dark txt-ellipsis-1">Friend's Group</p>
                                                        <p class="text-secondary f-s-12 chat-message">Bye! see you soon </p>
                                                    </div>
                                                    <div>
                                                        <p class="f-s-12 chat-time">12:30PM</p>
                                                    </div>
                                                </div>
                                                <div class="chat-contactbox">
                                                    <div class="position-absolute">
                                                        <ul class="avatar-group">
                                                            <li class="text-bg-danger h-45 w-45 d-flex-center overflow-hidden b-r-50">
                                                                <img src="https://phplaravel-1384472-5380003.cloudwaysapps.com/../assets/images/avatar/10.png" alt="" class="img-fluid">
                                                            </li>
                                                            <li class="text-bg-secondary h-35 w-35 d-flex-center b-r-50"
                                                                data-bs-toggle="tooltip" data-bs-title="2 More">
                                                                25+
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="flex-grow-1 text-start mg-s-75">
                                                        <p class="mb-0 f-w-500 text-dark txt-ellipsis-1">client Group</p>
                                                        <p class="text-muted text-success f-s-12 chat-message">Typing...</p>
                                                    </div>
                                                    <div>
                                                        <p class="f-s-12 chat-time">Now</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="float-end">
                                            <div class="btn-group dropup  dropdown-icon-none">
                                                <button class="btn btn-primary icon-btn b-r-22 dropdown-toggle active" type="button"
                                                        data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
                                                    <i class="ti ti-plus"></i>
                                                </button>
                                                <ul class="dropdown-menu" data-popper-placement="bottom-start">
                                                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#newChatModal"><i class="ti ti-brand-hipchat"></i> <span
                                                                class="f-s-13">New Chat</span></a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="#"><i class="ti ti-phone-call"></i> <span
                                                                class="f-s-13">New Contact</span></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- tab 2 -->

                        <div id="tab-2" class="tabs-content">
                            <div class="chat-contact tabcontent">
                                <div class="updates-box">
                                    <div class="b-2-success b-r-50 p-1">
                        <span class="h-40 w-40 d-flex-center b-r-50 position-relative bg-primary">
                          <img src="https://phplaravel-1384472-5380003.cloudwaysapps.com/../assets/images/avatar/16.png" alt="" class="img-fluid b-r-50">
                        </span>
                                    </div>
                                    <div class="flex-grow-1 text-start ps-2">
                                        <span>Bette Hagenes</span>
                                        <p class="f-s-12 text-secondary mb-0">2:30AM</p>
                                    </div>
                                </div>
                                <div class="updates-box">
                                    <div class="b-2-secondary b-r-50 p-1">
                        <span class="h-40 w-40 d-flex-center b-r-50 position-relative bg-info">
                          <img src="https://phplaravel-1384472-5380003.cloudwaysapps.com/../assets/images/avatar/6.png" alt="" class="img-fluid b-r-50">
                        </span>
                                    </div>
                                    <div class="flex-grow-1 text-start ps-2">
                                        <span>Jessica</span>
                                        <p class="f-s-12 text-secondary mb-0">2min</p>
                                    </div>
                                </div>
                                <div class="updates-box">
                                    <div class="b-2-secondary b-r-50 p-1">
                        <span class="h-40 w-40 d-flex-center b-r-50 position-relative bg-dark">
                          <img src="https://phplaravel-1384472-5380003.cloudwaysapps.com/../assets/images/avatar/5.png" alt="" class="img-fluid b-r-50">
                        </span>
                                    </div>
                                    <div class="flex-grow-1 text-start ps-2">
                                        <span>Jerry Ladies</span>
                                        <p class="f-s-12 text-secondary mb-0">7:00AM</p>
                                    </div>
                                </div>
                                <div class="updates-box">
                                    <div class="b-2-success b-r-50 p-1">
                        <span class="h-40 w-40 d-flex-center b-r-50 position-relative bg-warning">
                          <img src="https://phplaravel-1384472-5380003.cloudwaysapps.com/../assets/images/avatar/4.png" alt="" class="img-fluid b-r-50">
                        </span>
                                    </div>
                                    <div class="flex-grow-1 text-start ps-2">
                                        <span>Emery McKenzie</span>
                                        <p class="f-s-12 text-secondary mb-0">5:26PM</p>
                                    </div>
                                </div>
                                <div class="updates-box">
                                    <div class="b-2-success b-r-50 p-1">
                        <span class="h-40 w-40 d-flex-center b-r-50 position-relative bg-primary">
                          <img src="https://phplaravel-1384472-5380003.cloudwaysapps.com/../assets/images/avatar/3.png" alt="" class="img-fluid b-r-50">
                        </span>
                                    </div>
                                    <div class="flex-grow-1 text-start ps-2">
                                        <span>Mark Walsh</span>
                                        <p class="f-s-12 text-secondary mb-0">1:26PM</p>
                                    </div>
                                </div>
                                <div class="updates-box">
                                    <div class="b-2-secondary b-r-50 p-1">
                        <span class="h-40 w-40 d-flex-center b-r-50 position-relative bg-dark">
                          <img src="https://phplaravel-1384472-5380003.cloudwaysapps.com/../assets/images/avatar/2.png" alt="" class="img-fluid b-r-50">
                        </span>
                                    </div>
                                    <div class="flex-grow-1 text-start ps-2">
                                        <span>Noah Davis</span>
                                        <p class="f-s-12 text-secondary mb-0">6:22PM</p>
                                    </div>
                                </div>
                                <div class="updates-box">
                                    <div class="b-2-secondary b-r-50 p-1">
                        <span class="h-40 w-40 d-flex-center b-r-50 position-relative bg-primary">
                          <img src="https://phplaravel-1384472-5380003.cloudwaysapps.com/../assets/images/avatar/1.png" alt="" class="img-fluid b-r-50">
                        </span>
                                    </div>
                                    <div class="flex-grow-1 text-start ps-2">
                        <span>
                          Isla White</span>
                                        <p class="f-s-12 text-secondary mb-0">6:10PM</p>
                                    </div>
                                </div>
                                <div class="updates-box">
                                    <div class="b-2-secondary b-r-50 p-1">
                        <span class="h-40 w-40 d-flex-center b-r-50 position-relative bg-secondary">
                          <img src="https://phplaravel-1384472-5380003.cloudwaysapps.com/../assets/images/avatar/10.png" alt="" class="img-fluid b-r-50">
                        </span>
                                    </div>
                                    <div class="flex-grow-1 text-start ps-2">
                                        <span>Fleta Walsh</span>
                                        <p class="f-s-12 text-secondary mb-0">5:26PM</p>
                                    </div>
                                </div>
                                <div class="updates-box">
                                    <div class="b-2-secondary b-r-50 p-1">
                        <span class="h-40 w-40 d-flex-center b-r-50 position-relative bg-secondary">
                          <img src="https://phplaravel-1384472-5380003.cloudwaysapps.com/../assets/images/avatar/11.png" alt="" class="img-fluid b-r-50">
                        </span>
                                    </div>
                                    <div class="flex-grow-1 text-start ps-2">
                                        <span>Pete Sakes</span>
                                        <p class="f-s-12 text-secondary mb-0">3:26PM</p>
                                    </div>
                                </div>
                            </div>

                            <div class="float-end">
                                <div class="btn-group dropdown-icon-none">
                                    <button class="btn btn-primary icon-btn b-r-22 dropdown-toggle active" type="button"
                                            data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
                                        <i class="ti ti-plus"></i>
                                    </button>
                                    <ul class="dropdown-menu" data-popper-placement="bottom-start">
                                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#newChatModal"><i class="ti ti-brand-hipchat"></i> <span
                                                    class="f-s-13">New Chat</span></a>
                                        </li>
                                        <li><a class="dropdown-item" href="#"><i class="ti ti-phone-call"></i> <span
                                                    class="f-s-13">New Contact</span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- tab 3 -->

                        <div id="tab-3" class="tabs-content">
                            <div class="chat-contact tabcontent chat-contact-list">
                                <div class=" d-flex align-items-center py-3">
                                    <div>
                        <span class="h-40 w-40 d-flex-center b-r-50 position-relative bg-info">
                          <img src="https://phplaravel-1384472-5380003.cloudwaysapps.com/../assets/images/avatar/13.png" alt="" class="img-fluid b-r-50">
                          <span
                              class="position-absolute top-0 end-0 p-1 bg-success border border-light rounded-circle"></span>
                        </span>
                                    </div>
                                    <div class="flex-grow-1 ps-2">
                                        <p class="contact-name text-dark mb-0 f-w-500">Bette Hagenes</p>
                                        <p class="mb-0 text-secondary f-s-13">+978356479</p>
                                    </div>
                                    <div>
                        <span class="h-35 w-35 text-outline-success d-flex-center b-r-50">
                          <i class="ti ti-phone-call"></i>
                        </span>
                                    </div>
                                    <div>
                        <span class="h-35 w-35 text-outline-primary d-flex-center b-r-50 ms-1">
                          <i class="ti ti-video"></i>
                        </span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center py-3">
                                    <div>
                        <span class="h-40 w-40 d-flex-center b-r-50 position-relative bg-danger">
                          <img src="https://phplaravel-1384472-5380003.cloudwaysapps.com/../assets/images/avatar/12.png" alt="" class="img-fluid b-r-50">
                          <span
                              class="position-absolute top-0 end-0 p-1 bg-success border border-light rounded-circle"></span>
                        </span>
                                    </div>
                                    <div class="flex-grow-1 ps-2">
                                        <p class="contact-name text-dark mb-0 f-w-500">Fleta Walsh</p>
                                        <p class="mb-0 text-secondary f-s-13">+988456479</p>
                                    </div>
                                    <div>
                        <span class="h-35 w-35 text-outline-success d-flex-center b-r-50">
                          <i class="ti ti-phone-call"></i>
                        </span>
                                    </div>
                                    <div>
                        <span class="h-35 w-35 text-outline-primary d-flex-center b-r-50 ms-1">
                          <i class="ti ti-video"></i>
                        </span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center py-3">
                                    <div>
                        <span class="h-40 w-40 d-flex-center b-r-50 position-relative bg-warning">
                          <img src="https://phplaravel-1384472-5380003.cloudwaysapps.com/../assets/images/avatar/11.png" alt="" class="img-fluid b-r-50">
                          <span
                              class="position-absolute top-0 end-0 p-1 bg-success border border-light rounded-circle"></span>
                        </span>
                                    </div>
                                    <div class="flex-grow-1 ps-2">
                                        <p class="contact-name text-dark mb-0 f-w-500">Lenora Bogisich</p>
                                        <p class="mb-0 text-secondary f-s-13">+4583546479</p>
                                    </div>
                                    <div>
                        <span class="h-35 w-35 text-outline-success d-flex-center b-r-50">
                          <i class="ti ti-phone-call"></i>
                        </span>
                                    </div>
                                    <div>
                        <span class="h-35 w-35 text-outline-primary d-flex-center b-r-50 ms-1">
                          <i class="ti ti-video"></i>
                        </span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center py-3">
                                    <div>
                        <span class="h-40 w-40 d-flex-center b-r-50 position-relative bg-success">
                          <img src="https://phplaravel-1384472-5380003.cloudwaysapps.com/../assets/images/avatar/10.png" alt="" class="img-fluid b-r-50">
                          <span
                              class="position-absolute top-0 end-0 p-1 bg-secondary border border-light rounded-circle"></span>
                        </span>
                                    </div>
                                    <div class="flex-grow-1 ps-2">
                                        <p class="contact-name text-dark mb-0 f-w-500">Emery McKenzie</p>
                                        <p class="mb-0 text-secondary f-s-13">+378356479</p>
                                    </div>
                                    <div>
                        <span class="h-35 w-35 text-outline-success d-flex-center b-r-50">
                          <i class="ti ti-phone-call"></i>
                        </span>
                                    </div>
                                    <div>
                        <span class="h-35 w-35 text-outline-primary d-flex-center b-r-50 ms-1">
                          <i class="ti ti-video"></i>
                        </span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center py-3">
                                    <div>
                        <span class="h-40 w-40 d-flex-center b-r-50 position-relative bg-danger">
                          <img src="https://phplaravel-1384472-5380003.cloudwaysapps.com/../assets/images/avatar/08.png" alt="" class="img-fluid b-r-50">
                          <span
                              class="position-absolute top-0 end-0 p-1 bg-success border border-light rounded-circle"></span>
                        </span>
                                    </div>
                                    <div class="flex-grow-1 ps-2">
                                        <p class="contact-name text-dark mb-0 f-w-500">Elmer</p>
                                        <p class="mb-0 text-secondary f-s-13">+678356270</p>
                                    </div>
                                    <div>
                        <span class="h-35 w-35 text-outline-success d-flex-center b-r-50">
                          <i class="ti ti-phone-call"></i>
                        </span>
                                    </div>
                                    <div>
                        <span class="h-35 w-35 text-outline-primary d-flex-center b-r-50 ms-1">
                          <i class="ti ti-video"></i>
                        </span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center py-3">
                                    <div>
                        <span class="h-40 w-40 d-flex-center b-r-50 position-relative bg-success">
                          <img src="https://phplaravel-1384472-5380003.cloudwaysapps.com/../assets/images/avatar/09.png" alt="" class="img-fluid b-r-50">
                          <span
                              class="position-absolute top-0 end-0 p-1 bg-success border border-light rounded-circle"></span>
                        </span>
                                    </div>
                                    <div class="flex-grow-1 ps-2">
                                        <p class="contact-name text-dark mb-0 f-w-500">Mark Walsh</p>
                                        <p class="mb-0 text-secondary f-s-13">+780356479</p>
                                    </div>
                                    <div>
                        <span class="h-35 w-35 text-outline-success d-flex-center b-r-50">
                          <i class="ti ti-phone-call"></i>
                        </span>
                                    </div>
                                    <div>
                        <span class="h-35 w-35 text-outline-primary d-flex-center b-r-50 ms-1">
                          <i class="ti ti-video"></i>
                        </span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center py-3">
                                    <div>
                        <span class="h-40 w-40 d-flex-center b-r-50 position-relative bg-warning">
                          <img src="https://phplaravel-1384472-5380003.cloudwaysapps.com/../assets/images/avatar/07.png" alt="" class="img-fluid b-r-50">
                          <span
                              class="position-absolute top-0 end-0 p-1 bg-success border border-light rounded-circle"></span>
                        </span>
                                    </div>
                                    <div class="flex-grow-1 ps-2">
                                        <p class="contact-name text-dark mb-0 f-w-500">Sue Flay</p>
                                        <p class="mb-0 text-secondary f-s-13">+780356479</p>
                                    </div>
                                    <div>
                        <span class="h-35 w-35 text-outline-success d-flex-center b-r-50">
                          <i class="ti ti-phone-call"></i>
                        </span>
                                    </div>
                                    <div>
                        <span class="h-35 w-35 text-outline-primary d-flex-center b-r-50 ms-1">
                          <i class="ti ti-video"></i>
                        </span>
                                    </div>
                                </div>

                            </div>
                            <div class="float-end">
                                <div class="btn-group dropdown-icon-none">
                                    <button class="btn btn-primary icon-btn b-r-22 dropdown-toggle active" type="button"
                                            data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
                                        <i class="ti ti-plus"></i>
                                    </button>
                                    <ul class="dropdown-menu" data-popper-placement="bottom-start">
                                        <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#newChatModal"><i class="ti ti-brand-hipchat"></i> <span
                                                    class="f-s-13">New Chat</span></a>
                                        </li>
                                        <li><a class="dropdown-item" href="#"><i class="ti ti-phone-call"></i> <span
                                                    class="f-s-13">New Contact</span></a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8 col-xxl-9 box-col-7">
        <div class="card chat-container-content-box" data-chat-room="<?php echo e($selectedRoom?->id); ?>">
            <div class="card-header bg-white border-bottom" style="z-index: 1020;">
                <div class="chat-header d-flex align-items-center">
                    <div class="d-lg-none">
                        <a class="me-3 toggle-btn" role="button" data-bs-toggle="offcanvas" data-bs-target="#chatListOffcanvas" aria-controls="chatListOffcanvas">
                            <i class="ti ti-align-justified"></i>
                        </a>
                    </div>
                    <?php if($selectedContact): ?>
                        <?php $user = \App\Models\User::find($selectedContact['id']); ?>
                        <span class="h-45 w-45 d-flex-center b-r-10 position-relative m-auto"
                              data-user-id="<?php echo e($user->id); ?>">
                            <img alt="avatar" class="img-fluid b-r-10"
                                 src="<?php echo e(\App\Helpers\AvatarHelper::getUserAvatarUrl($user)); ?>">
                            <span class="position-absolute top-0 end-0 p-1 border border-light rounded-circle bg-secondary"
                                  data-presence-dot></span>
                        </span>
                    <?php else: ?>
                <span class="profileimg h-45 w-45 d-flex-center b-r-50 position-relative">
                            <i class="ti ti-user f-s-20 text-muted"></i>
                </span>
                    <?php endif; ?>
                    <div class="flex-grow-1 ps-2 pe-2">
                        <?php if($selectedContact): ?>
                            <div class="fs-6"><?php echo e($selectedContact['name']); ?></div>
                            <div class="text-muted f-s-12" data-presence-label data-user-id="<?php echo e($selectedContact['id']); ?>">
                                <?php echo e(ucfirst($selectedContact['status'])); ?>

                            </div>
                            <!-- Typing indicator in header -->
                            <div class="typing-indicator-header d-none" data-room-id="<?php echo e($selectedRoom?->id); ?>">
                                <small class="text-info">
                                    <i class="ti ti-pencil me-1"></i>sta scrivendo...
                                </small>
                            </div>
                        <?php else: ?>
                            <div class="fs-6">Seleziona una chat</div>
                            <div class="text-muted f-s-12">Clicca su un contatto per iniziare</div>
                        <?php endif; ?>
                    </div>
                    <button type="button" class="btn btn-success h-45 w-45 icon-btn b-r-22 me-sm-2"
                            data-bs-toggle="modal" data-bs-target="#exampleModal">
                        <i class="ti ti-phone-call f-s-20"></i>
                    </button>
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-body p-0">
                                <div class="call">
                                    <div class="call-div">
                                        <img src="https://phplaravel-1384472-5380003.cloudwaysapps.com/../assets/images/profile-app/32.jpg" class="w-100" alt="">
                                        <div class="call-caption">
                                            <h2 class="text-white">Jerry Ladies</h2>
                                            <div class="d-flex justify-content-center">
                            <span
                                class="bg-success h-40 w-40 d-flex-center b-r-50 animate__animated animate__1 animate__shakeY animate__infinite call-btn pointer-events-auto" data-bs-dismiss="modal">
                              <i class="ti ti-phone-call "></i>
                            </span>
                                                <span class="bg-danger h-40 w-40 d-flex-center b-r-50 ms-4 call-btn pointer-events-auto" data-bs-dismiss="modal">
                              <i class="ti ti-phone"></i>
                            </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary h-45 w-45 icon-btn b-r-22 me-sm-2"
                            data-bs-toggle="modal" data-bs-target="#exampleModal1">
                        <i class="ti ti-video f-s-20"></i>
                    </button>
                    <div class="modal fade" id="exampleModal1" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-body p-0">
                                <div class="call">
                                    <div class="call-div pointer-events-auto">
                                        <img src="https://phplaravel-1384472-5380003.cloudwaysapps.com/../assets/images/profile-app/25.jpg" class="w-100" alt="">

                                        <div class="call-caption">
                                            <div class="d-flex justify-content-center align-items-center">

                            <span class="bg-white h-35 w-35 d-flex-center b-r-50 ms-4">
                              <i class="ti ti-microphone text-dark"></i>
                            </span>
                                                <span data-bs-dismiss="modal"
                                                      class="bg-danger h-45 w-45 d-flex-center b-r-50 ms-4 animate__pulse animate__animated animate__infinite animate__faster call-btn pointer-events-auto">
                              <i class="ti ti-phone"></i>
                            </span>
                                                <span class="bg-white h-35 w-35 d-flex-center b-r-50 ms-4">
                              <i class="ti ti-phone-pause text-dark"></i>
                            </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="video-div">
                                        <img src="https://phplaravel-1384472-5380003.cloudwaysapps.com/../assets/images/profile-app/31.jpg" class="w-100 rounded" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <button class="btn btn-secondary h-45 w-45 icon-btn b-r-22 me-sm-2"
                                data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
                            <i class="ti ti-settings f-s-20"></i>
                        </button>
                        <ul class="dropdown-menu" data-popper-placement="bottom-start">
                            <li><a class="dropdown-item" href="#"><i class="ti ti-brand-hipchat"></i> <span
                                        class="f-s-13">Chat Settings</span></a>
                            </li>
                            <li><a class="dropdown-item" href="#"><i class="ti ti-phone-call"></i> <span
                                        class="f-s-13">Contact Settings</span></a>
                            </li>
                            <li><a class="dropdown-item" href="#"><i class="ti ti-settings"></i> <span
                                        class="f-s-13">Settings</span></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-body chat-body" >
                <div class="chat-container" data-chat-messages>
                    <?php if($selectedRoom && count($messages) > 0): ?>
                        <?php $currentDate = ''; ?>
                        <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php if($message['date'] !== $currentDate): ?>
                                <?php $currentDate = $message['date']; ?>
                    <div class="text-center">
                                    <span class="badge text-light-secondary"><?php echo e($message['date']); ?></span>
                    </div>
                            <?php endif; ?>

                            <?php if($message['is_own']): ?>
                    <div class="position-relative">
                        <div class="chat-box-right d-flex flex-column align-items-end" style="margin-right: 60px;">
                            <div class="chat-text bg-primary text-white p-3 rounded-3 mb-2 shadow-sm" style="max-width: 85%;">
                                <?php echo e($message['content']); ?>

                            </div>
                            <p class="text-muted f-s-12"><i class="ti ti-checks text-primary"></i> <?php echo e($message['time']); ?></p>
                        </div>
                        <div class="chatdp h-45 w-45 b-r-50 position-absolute end-0 top-0 bg-danger">
                            <?php $user = \App\Models\User::find($message['sender_id']); ?>
                            <span class="h-45 w-45 d-flex-center b-r-10 position-relative m-auto"
                                  data-user-id="<?php echo e($user->id); ?>">
                                <img alt="avatar" class="img-fluid b-r-10"
                                     src="<?php echo e(\App\Helpers\AvatarHelper::getUserAvatarUrl($user)); ?>">
                                <span class="position-absolute top-0 end-0 p-1 border border-light rounded-circle bg-secondary"
                                      data-presence-dot></span>
                            </span>
                        </div>
                    </div>
                        <?php else: ?>
                    <div class="position-relative">
                        <div class="chatdp h-45 w-45 b-r-50 position-absolute start-0 bg-light">
                            <?php $user = \App\Models\User::find($message['sender_id']); ?>
                            <span class="h-45 w-45 d-flex-center b-r-10 position-relative m-auto"
                                  data-user-id="<?php echo e($user->id); ?>">
                                <img alt="avatar" class="img-fluid b-r-10"
                                     src="<?php echo e(\App\Helpers\AvatarHelper::getUserAvatarUrl($user)); ?>">
                                <span class="position-absolute top-0 end-0 p-1 border border-light rounded-circle bg-secondary"
                                      data-presence-dot></span>
                            </span>
                        </div>
                        <div class="chat-box d-flex flex-column align-items-start" style="margin-left: 60px;">
                            <div class="chat-text bg-light text-dark p-3 rounded-3 mb-2 shadow-sm border" style="max-width: 85%;">
                                <?php echo e($message['content']); ?>

                            </div>
                            <p class="text-muted f-s-12"><i class="ti ti-checks text-primary"></i> <?php echo e($message['time']); ?></p>
                        </div>
                    </div>
                        <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php elseif($selectedRoom): ?>
                        <div class="text-center py-4">
                            <p class="text-muted">Nessun messaggio ancora</p>
                            <p class="text-muted f-s-12">Inizia la conversazione!</p>
                            </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <p class="text-muted">Seleziona una chat per iniziare</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <!-- Typing Indicator -->
            <div class="typing-indicator d-none" data-typing-indicator>
                <div class="d-flex align-items-center p-2 bg-light border-top">
                    <div class="typing-dots me-2">
                        <span class="typing-dot"></span>
                        <span class="typing-dot"></span>
                        <span class="typing-dot"></span>
                    </div>
                    <span class="text-muted f-s-12" data-typing-text>Qualcuno sta scrivendo...</span>
                </div>
            </div>

            <div class="card-footer bg-white border-top" style="z-index: 1010;">
                <form class="chat-footer d-flex" data-chat-form action="<?php echo e($selectedRoom ? route('chat.store', $selectedRoom->id) : '#'); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="app-form flex-grow-1">
                        <div class="input-group">
                            <span class="input-group-text bg-secondary ms-2 me-2 b-r-10 ">
                                <a class="emoji-btn d-flex-center" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Emoji" role="button">
                                    <i class="ti ti-mood-smile f-s-18"></i>
                                </a>
                            </span>
                            <input type="text" class="form-control b-r-6" placeholder="Type a message" aria-label="message" data-chat-input name="content">
                            <button class="btn btn-sm btn-primary ms-2 me-2 b-r-4" type="submit"><i class="ti ti-send"></i> <span>Send</span></button>
                        </div>
                    </div>
                    <div class="d-none d-sm-block">
                        <a class="bg-secondary h-50 w-50 d-flex-center b-r-10 ms-1" role="button" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Microphone">
                            <i class="ti ti-microphone f-s-18"></i>
                        </a>
                    </div>
                    <div class="d-none d-sm-block">
                        <a class="bg-secondary h-50 w-50 d-flex-center b-r-10 ms-1" role="button" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Camera">
                            <i class="ti ti-camera-plus f-s-18"></i>
                        </a>
                    </div>
                    <div class="d-none d-sm-block">
                        <a class="bg-secondary h-50 w-50 d-flex-center b-r-10 ms-1" role="button" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Paperclip">
                            <i class="ti ti-paperclip f-s-18"></i>
                        </a>
                    </div>
                    <div>
                        <div class="btn-group dropdown-icon-none d-sm-none">
                            <a class="h-35 w-35 d-flex-center ms-1" role="button" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
                                <i class="ti ti-dots-vertical"></i>
                            </a>
                            <ul class="dropdown-menu" data-popper-placement="bottom-start">
                                <li><a class="dropdown-item" href="#"><i class="ti ti-microphone"></i> <span class="f-s-13">Microphone</span></a></li>
                                <li><a class="dropdown-item" href="#"> <i class="ti ti-camera-plus"></i> <span class="f-s-13">camera</span></a></li>
                                <li><a class="dropdown-item" href="#"><i class="ti ti-paperclip"></i> <span class="f-s-13">paperclip</span></a></li>
                            </ul>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Chat end -->



<!-- New Chat Modal -->
<div class="modal fade" id="newChatModal" tabindex="-1" aria-labelledby="newChatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newChatModalLabel">
                    <i class="ti ti-brand-hipchat me-2"></i>Nuova Chat Privata
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="userSearch" class="form-label">Cerca utenti</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="ti ti-search"></i>
                        </span>
                        <input type="text" class="form-control" id="userSearch" placeholder="Digita il nome dell'utente..." autocomplete="off">
                    </div>
                </div>

                <div id="searchResults" class="mt-3">
                    <!-- I risultati della ricerca appariranno qui -->
                </div>

                <div id="loadingSpinner" class="text-center d-none">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Caricamento...</span>
                    </div>
                </div>

                <div id="noResults" class="text-center d-none">
                    <p class="text-muted">Nessun utente trovato</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Offcanvas per la lista delle chat su mobile -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="chatListOffcanvas" aria-labelledby="chatListOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="chatListOffcanvasLabel">
            <i class="ti ti-message-circle me-2"></i>
            Chat
        </h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
        <!-- Lista semplificata dei contatti -->
        <div class="p-3">
            <h6 class="mb-3">
                <i class="ph-fill ph-chat-circle-text me-2"></i>
                Contatti
            </h6>

            <div class="chat-contact">
                <?php $__empty_1 = true; $__currentLoopData = $contacts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contact): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="chat-contactbox p-2 border-bottom contact-item"
                     data-contact-id="<?php echo e($contact['id']); ?>"
                     data-chat-room="<?php echo e($contact['chat_room_id']); ?>"
                     data-contact-name="<?php echo e($contact['name']); ?>"
                     style="cursor: pointer;">
                    <div class="d-flex align-items-center">
                        <div class="position-relative me-3">
                            <?php $user = \App\Models\User::find($contact['id']); ?>
                            <span class="h-40 w-40 d-flex-center b-r-10 position-relative" data-user-id="<?php echo e($user->id); ?>">
                                <img alt="avatar" class="img-fluid b-r-10" src="<?php echo e(\App\Helpers\AvatarHelper::getUserAvatarUrl($user)); ?>">
                                <span class="position-absolute top-0 end-0 p-1 border border-light rounded-circle bg-secondary" data-presence-dot></span>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-1 f-w-500 text-dark"><?php echo e($contact['name']); ?></p>
                            <p class="text-secondary mb-0 f-s-12">
                                <i class="ti ti-checks"></i> <?php echo e($contact['last_message'] ?: 'Nessun messaggio'); ?>

                            </p>
                            <!-- Typing indicator mobile -->
                            <div class="typing-indicator-contact d-none" data-room-id="<?php echo e($contact['chat_room_id']); ?>">
                                <small class="text-info">
                                    <i class="ti ti-pencil me-1"></i>sta scrivendo...
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="text-center py-4">
                    <i class="ti ti-message-circle fs-1 text-muted"></i>
                    <p class="text-muted mt-2">Nessun contatto disponibile</p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<style>
/* CSS minimo per header e footer fissi su mobile */
@media (max-width: 991.98px) {
    .card-header {
        position: fixed !important;
        top: 60px;
        left: 0;
        right: 0;
        z-index: 1020;
    }

    .card-footer {
        position: fixed !important;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 1010;
    }

    .chat-body {
        margin-top: 120px;
        margin-bottom: 80px;
    }
}
</style>

<?php $__env->startPush('scripts'); ?>
<script>
console.log('Chat script loaded!');

// Typing indicator management
class TypingManager {
    constructor() {
        this.typingTimeout = null;
        this.typingDelay = 1000; // 1 secondo di delay
        this.typingDuration = 5000; // 5 secondi di durata
        this.currentRoom = null;
        this.isTyping = false;

        this.typingIndicator = document.querySelector('[data-typing-indicator]');
        this.typingText = document.querySelector('[data-typing-text]');
        this.chatInput = document.querySelector('[data-chat-input]');

        this.init();
    }

    init() {
        console.log('TypingManager.init() - Starting initialization...');

        if (!this.chatInput) {
            console.error('TypingManager.init() - Chat input not found!');
            return;
        }

        console.log('TypingManager.init() - Chat input found:', this.chatInput);

        // Event listeners per input
        this.chatInput.addEventListener('input', () => this.handleInput());
        this.chatInput.addEventListener('keydown', (e) => this.handleKeyDown(e));
        this.chatInput.addEventListener('blur', () => this.stopTyping());

        // Ottieni room ID corrente
        this.currentRoom = this.getCurrentRoomId();
        console.log('TypingManager.init() - Current room ID:', this.currentRoom);

        if (!this.currentRoom) {
            console.error('TypingManager.init() - No room ID found! Typing indicator will not work.');
            return;
        }

        // Ascolta eventi typing da altri utenti
        this.listenToTypingEvents();

        console.log('TypingManager.init() - Initialization completed successfully');
    }

    getCurrentRoomId() {
        // Prova prima dai parametri URL
        const urlParams = new URLSearchParams(window.location.search);
        let roomId = urlParams.get('room');

        // Se non trovato nei parametri, prova dall'URL path
        if (!roomId) {
            const pathParts = window.location.pathname.split('/');
            const roomIndex = pathParts.indexOf('chat');
            if (roomIndex !== -1 && pathParts[roomIndex + 1]) {
                roomId = pathParts[roomIndex + 1];
            }
        }

        console.log('getCurrentRoomId - URL params:', window.location.search);
        console.log('getCurrentRoomId - Path:', window.location.pathname);
        console.log('getCurrentRoomId - Found room ID:', roomId);

        return roomId;
    }

    handleInput() {
        console.log('TypingManager.handleInput() - Input event triggered');
        console.log('TypingManager.handleInput() - Current room:', this.currentRoom);

        if (!this.currentRoom) {
            console.warn('TypingManager.handleInput() - No room ID, ignoring input');
            return;
        }

        if (!this.isTyping) {
            console.log('TypingManager.handleInput() - Starting typing...');
            this.startTyping();
        } else {
            console.log('TypingManager.handleInput() - Already typing, resetting timeout...');
        }

        // Reset timeout
        this.resetTypingTimeout();
    }

    handleKeyDown(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            this.stopTyping();
        }
    }

    startTyping() {
        if (this.isTyping) return;

        this.isTyping = true;
        this.sendTypingEvent('start');
    }

    stopTyping() {
        if (!this.isTyping) return;

        this.isTyping = false;
        this.sendTypingEvent('stop');

        if (this.typingTimeout) {
            clearTimeout(this.typingTimeout);
            this.typingTimeout = null;
        }
    }

    resetTypingTimeout() {
        if (this.typingTimeout) {
            clearTimeout(this.typingTimeout);
        }

        this.typingTimeout = setTimeout(() => {
            this.stopTyping();
        }, this.typingDuration);
    }

    async sendTypingEvent(action) {
        console.log(`TypingManager.sendTypingEvent(${action}) - Starting...`);
        console.log(`TypingManager.sendTypingEvent(${action}) - Room ID:`, this.currentRoom);

        if (!this.currentRoom) {
            console.error(`TypingManager.sendTypingEvent(${action}) - No room ID, cannot send event`);
            return;
        }

        try {
            const url = `/chat/${this.currentRoom}/typing/${action}`;
            console.log(`TypingManager.sendTypingEvent(${action}) - Sending request to:`, url);

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            console.log(`TypingManager.sendTypingEvent(${action}) - Response status:`, response.status);

            if (!response.ok) {
                console.error(`TypingManager.sendTypingEvent(${action}) - Error:`, response.status);
            } else {
                console.log(`TypingManager.sendTypingEvent(${action}) - Success!`);
            }
        } catch (error) {
            console.error(`TypingManager.sendTypingEvent(${action}) - Exception:`, error);
        }
    }

    listenToTypingEvents() {
        console.log('TypingManager.listenToTypingEvents() - Starting...');
        console.log('TypingManager.listenToTypingEvents() - Echo available:', !!window.Echo);
        console.log('TypingManager.listenToTypingEvents() - Current room:', this.currentRoom);

        if (!window.Echo || !this.currentRoom) {
            console.error('TypingManager.listenToTypingEvents() - Echo or room ID missing');
            if (!window.Echo) console.error('TypingManager.listenToTypingEvents() - Echo not available');
            if (!this.currentRoom) console.error('TypingManager.listenToTypingEvents() - Room ID missing');
            return;
        }

        console.log('TypingManager.listenToTypingEvents() - Setting up typing listeners for room:', this.currentRoom);

        // Ascolta canale privato per la room
        const channelName = `chat.room.${this.currentRoom}`;
        console.log('TypingManager.listenToTypingEvents() - Channel name:', channelName);

        const channel = window.Echo.private(channelName);
        console.log('TypingManager.listenToTypingEvents() - Channel created:', channel);

        channel
            .subscribed(() => {
                console.log('TypingManager.listenToTypingEvents() - Successfully subscribed to channel:', channelName);
            })
            .error((err) => {
                console.error('TypingManager.listenToTypingEvents() - Channel authorization error:', err);
            })
            .listen('.typing.started', (e) => {
                console.log('TypingManager.listenToTypingEvents() - Received .typing.started event:', e);
                this.handleTypingStarted(e);
            })
            .listen('.typing.stopped', (e) => {
                console.log('TypingManager.listenToTypingEvents() - Received .typing.stopped event:', e);
                this.handleTypingStopped(e);
            });

        console.log('TypingManager.listenToTypingEvents() - Typing listeners set up successfully');
    }

    handleTypingStarted(event) {
        console.log('Handling typing started event:', event);
        console.log('Current user ID:', <?php echo e(auth()->id()); ?>);
        console.log('Event user ID:', event.user_id);

        if (event.user_id === <?php echo e(auth()->id()); ?>) {
            console.log('Ignoring own typing event');
            return; // Ignora i propri eventi
        }

        const typingUsers = event.typing_users;
        const currentUserNames = Object.values(typingUsers);

        console.log('Typing users:', typingUsers);
        console.log('User names:', currentUserNames);

        if (currentUserNames.length > 0) {
            this.showTypingIndicator(currentUserNames);
        }
    }

    handleTypingStopped(event) {
        if (event.user_id === <?php echo e(auth()->id()); ?>) return; // Ignora i propri eventi

        const typingUsers = event.typing_users;

        if (Object.keys(typingUsers).length === 0) {
            this.hideTypingIndicator();
        } else {
            const currentUserNames = Object.values(typingUsers);
            this.showTypingIndicator(currentUserNames);
        }
    }

    showTypingIndicator(userNames) {
        console.log('Showing typing indicator for:', userNames);

        // Mostra indicatori nella chat principale
        if (this.typingIndicator) {
            let text = '';
            if (userNames.length === 1) {
                text = `${userNames[0]} sta scrivendo...`;
            } else if (userNames.length === 2) {
                text = `${userNames[0]} e ${userNames[1]} stanno scrivendo...`;
            } else {
                text = `${userNames[0]} e altri stanno scrivendo...`;
            }

            console.log('Setting typing text:', text);
            this.typingText.textContent = text;
            this.typingIndicator.classList.remove('d-none');
            console.log('Typing indicator shown');
        }

        // Mostra indicatori nella lista dei contatti
        const contactIndicators = document.querySelectorAll(`.typing-indicator-contact[data-room-id="${this.currentRoom}"]`);
        contactIndicators.forEach(indicator => {
            indicator.classList.remove('d-none');
        });

        // Mostra indicatori nell'header della chat
        const headerIndicators = document.querySelectorAll(`.typing-indicator-header[data-room-id="${this.currentRoom}"]`);
        headerIndicators.forEach(indicator => {
            indicator.classList.remove('d-none');
        });
    }

    hideTypingIndicator() {
        // Nasconde indicatori nella chat principale
        if (this.typingIndicator) {
            this.typingIndicator.classList.add('d-none');
        }

        // Nasconde indicatori nella lista dei contatti
        const contactIndicators = document.querySelectorAll(`.typing-indicator-contact[data-room-id="${this.currentRoom}"]`);
        contactIndicators.forEach(indicator => {
            indicator.classList.add('d-none');
        });

        // Nasconde indicatori nell'header della chat
        const headerIndicators = document.querySelectorAll(`.typing-indicator-header[data-room-id="${this.currentRoom}"]`);
        headerIndicators.forEach(indicator => {
            indicator.classList.add('d-none');
        });
    }

    // Metodo per aggiornare la stanza corrente (utile per mobile)
    updateCurrentRoom(newRoomId) {
        console.log('TypingManager.updateCurrentRoom() - Updating from', this.currentRoom, 'to', newRoomId);

        // Nasconde tutti gli indicatori precedenti
        this.hideTypingIndicator();

        // Aggiorna la stanza corrente
        this.currentRoom = newRoomId;

        // Reinizializza i listener per la nuova stanza
        if (this.currentRoom) {
            this.listenToTypingEvents();
        }
    }
}

// Inizializza typing manager quando la pagina è caricata
let typingManager = null;

// Inizializza l'offcanvas della chat
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing chat offcanvas...');

    // Inizializza typing manager
    const chatInput = document.querySelector('[data-chat-input]');
    console.log('DOMContentLoaded - Chat input found:', chatInput);

    if (chatInput) {
        console.log('DOMContentLoaded - Creating TypingManager...');
        typingManager = new TypingManager();
        console.log('DOMContentLoaded - TypingManager created:', typingManager);
    } else {
        console.error('DOMContentLoaded - Chat input not found, TypingManager not created');
    }

    // Verifica che l'offcanvas sia presente
    const offcanvas = document.getElementById('chatListOffcanvas');
    if (offcanvas) {
        console.log('Chat offcanvas found:', offcanvas);
    } else {
        console.error('Chat offcanvas not found!');
    }

    // Verifica che il toggle button sia presente
    const toggleBtn = document.querySelector('.toggle-btn');
    if (toggleBtn) {
        console.log('Toggle button found:', toggleBtn);
        console.log('Toggle button attributes:', {
            'data-bs-toggle': toggleBtn.getAttribute('data-bs-toggle'),
            'data-bs-target': toggleBtn.getAttribute('data-bs-target')
        });
    } else {
        console.error('Toggle button not found!');
    }

        // Gestione click sui contatti nell'offcanvas
    const contactItems = document.querySelectorAll('#chatListOffcanvas .contact-item');
    contactItems.forEach(item => {
        item.addEventListener('click', function() {
            const contactId = this.getAttribute('data-contact-id');
            const chatRoom = this.getAttribute('data-chat-room');
            const contactName = this.getAttribute('data-contact-name');

            console.log('Contact clicked:', { contactId, chatRoom, contactName });

            // Chiudi l'offcanvas
            const offcanvasInstance = bootstrap.Offcanvas.getInstance(document.getElementById('chatListOffcanvas'));
            if (offcanvasInstance) {
                offcanvasInstance.hide();
            }

            // Replica esattamente il comportamento desktop: redirect completo
            const chatUrl = `<?php echo e(route('chat.index')); ?>?room=${chatRoom}`;
            console.log('Redirecting to:', chatUrl);
            window.location.href = chatUrl;
        });
    });

    // Listener per cambiamenti di URL (utile per mobile)
    if (typingManager) {
        // Controlla se l'URL è cambiato e aggiorna la stanza corrente
        const checkUrlChange = () => {
            const currentRoomId = typingManager.getCurrentRoomId();
            if (currentRoomId && currentRoomId !== typingManager.currentRoom) {
                console.log('URL changed, updating room from', typingManager.currentRoom, 'to', currentRoomId);
                typingManager.updateCurrentRoom(currentRoomId);
            }
        };

        // Controlla ogni 500ms per cambiamenti di URL
        setInterval(checkUrlChange, 500);

        // Listener per navigazione browser (avanti/indietro)
        window.addEventListener('popstate', () => {
            setTimeout(() => {
                checkUrlChange();
            }, 100);
        });
    }
});


</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/chat/index.blade.php ENDPATH**/ ?>