<?php $__env->startSection('main-content'); ?>
<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0"><?php echo e(__('chat.title')); ?></h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('dashboard')); ?>"><?php echo e(__('sidebar.dashboard')); ?></a></li>
                            <li class="breadcrumb-item active"><?php echo e(__('chat.title')); ?></li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <!-- Chat start -->
        <div class="row position-relative chat-container-box">
            <div class="col-lg-4 col-xxl-3  box-col-5">
                <div class="chat-div">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex align-items-center">
                                <span class="chatdp h-30 w-30 d-flex-center b-r-50 position-relative bg-danger">
                                    <img src="<?php echo e(\App\Helpers\AvatarHelper::getUserAvatarUrl(auth()->user())); ?>" alt="" class="img-fluid b-r-50">
                                    <span class="position-absolute top-0 end-0 p-1 bg-success border border-light rounded-circle"></span>
                                </span>
                                <div class="flex-grow-1 ps-2">
                                    <div class="fs-6"><?php echo e(auth()->user()->name); ?></div>
                                    <div class="text-muted f-s-12"><?php echo e(auth()->user()->role ?? 'User'); ?></div>
                                </div>
                                <div>
                                    <div class="btn-group dropdown-icon-none">
                                        <a role="button" data-bs-placement="top" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
                                            <i class="ti ti-settings fs-5"></i>
                                        </a>
                                        <ul class="dropdown-menu" data-popper-placement="bottom-start">
                                            <li><a class="dropdown-item" href="#"><i class="ti ti-brand-hipchat"></i> <span class="f-s-13">Chat Settings</span></a></li>
                                            <li><a class="dropdown-item" href="#"><i class="ti ti-phone-call"></i> <span class="f-s-13">Contact Settings</span></a></li>
                                            <li><a class="dropdown-item" href="#"><i class="ti ti-settings"></i> <span class="f-s-13">Settings</span></a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="close-togglebtn">
                                    <a class="ms-2 close-toggle" role="button"><i class="ti ti-align-justified fs-5"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chat-tab-wrapper">
                                <ul class="tabs chat-tabs">
                                    <li class="tab-link active" data-tab="1"><i class="ph-fill ph-chat-circle-text f-s-18 me-2"></i>Chat</li>
                                    <li class="tab-link" data-tab="2"><i class="ph-fill ph-wechat-logo f-s-18 me-2"></i>Updates</li>
                                    <li class="tab-link" data-tab="3"><i class="ph-fill ph-phone-call f-s-18 me-2"></i>Contact</li>
                                </ul>
                            </div>
                            <div class="content-wrapper">
                                <!-- tab 1 -->
                                <div id="tab-1" class="tabs-content active">
                                    <div class="tab-wrapper">
                                        <div class="mt-3">
                                            <ul class="nav nav-tabs app-tabs-primary tab-light-primary chat-status-tab border-0 justify-content-between mb-0 pb-0" id="Basic" role="tablist">
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link active" id="private-tab" data-bs-toggle="tab" data-bs-target="#private-tab-pane" type="button" role="tab" aria-controls="private-tab-pane" aria-selected="false" tabindex="-1">
                                                        <i class="ph-fill ph-lock-key-open me-2"></i>Private
                                                    </button>
                                                </li>
                                                <li class="nav-item" role="presentation">
                                                    <button class="nav-link" id="groups-tab" data-bs-toggle="tab" data-bs-target="#groups-tab-pane" type="button" role="tab" aria-controls="groups-tab-pane" aria-selected="false" tabindex="-1">
                                                        <i class="ph-fill ph-users-three me-2"></i>Group
                                                    </button>
                                                </li>
                                            </ul>
                                            <div class="tab-content" id="BasicContent">
                                                <!-- Private Chat -->
                                                <div class="tab-pane fade show active" id="private-tab-pane" role="tabpanel" aria-labelledby="private-tab" tabindex="0">
                                                    <div class="chat-contact" id="privateChatsList">
                                                        <?php $__empty_1 = true; $__currentLoopData = $chats->where('type', 'private'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                            <?php
                                                                $otherUser = $chat->participants->where('user_id', '!=', auth()->id())->first()->user ?? null;
                                                                $unreadCount = $chat->getUnreadCount(auth()->user());
                                                            ?>
                                                            <div class="chat-contactbox" data-chat-id="<?php echo e($chat->id); ?>" onclick="loadChat(<?php echo e($chat->id); ?>)">
                                                                <div class="position-absolute">
                                                                    <span class="h-30 w-30 d-flex-center b-r-50 position-relative bg-primary">
                                                                        <img src="<?php echo e(\App\Helpers\AvatarHelper::getUserAvatarUrl($otherUser)); ?>" alt="" class="img-fluid b-r-50">
                                                                        <span class="position-absolute top-0 end-0 p-1 bg-success border border-light rounded-circle"></span>
                                                                    </span>
                                                                </div>
                                                                <div class="flex-grow-1 text-start mg-s-50">
                                                                    <p class="mb-0 f-w-500 text-dark txt-ellipsis-1"><?php echo e($otherUser ? $otherUser->name : __('chat.unknown_user')); ?></p>
                                                                    <p class="text-secondary mb-0 f-s-12 mb-0 chat-message">
                                                                        <i class="ti ti-checks"></i>
                                                                        <?php if($chat->lastMessage): ?>
                                                                            <?php echo e(Str::limit($chat->lastMessage->message, 30)); ?>

                                                                        <?php else: ?>
                                                                            <?php echo e(__('chat.no_messages')); ?>

                                                                        <?php endif; ?>
                                                                        <?php if($unreadCount > 0): ?>
                                                                            <span class="badge bg-danger ms-1"><?php echo e($unreadCount); ?></span>
                                                                        <?php endif; ?>
                                                                    </p>
                                                                </div>
                                                                <div>
                                                                    <p class="f-s-12 chat-time">
                                                                        <?php if($chat->lastMessage): ?>
                                                                            <?php echo e($chat->lastMessage->created_at->diffForHumans()); ?>

                                                                        <?php else: ?>
                                                                            <?php echo e(__('chat.no_messages')); ?>

                                                                        <?php endif; ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                            <div class="text-center py-3">
                                                                <p class="text-muted"><?php echo e(__('chat.no_private_chats')); ?></p>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <!-- Group Chat -->
                                                <div class="tab-pane fade" id="groups-tab-pane" role="tabpanel" aria-labelledby="groups-tab" tabindex="0">
                                                    <div class="chat-contact chat-group-list" id="groupChatsList">
                                                        <?php $__empty_1 = true; $__currentLoopData = $chats->where('type', 'group'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                            <?php
                                                                $unreadCount = $chat->getUnreadCount(auth()->user());
                                                            ?>
                                                            <div class="chat-contactbox" data-chat-id="<?php echo e($chat->id); ?>" onclick="loadChat(<?php echo e($chat->id); ?>)">
                                                                <div class="position-absolute">
                                                                    <ul class="avatar-group">
                                                                        <li class="text-bg-warning h-45 w-45 d-flex-center b-r-50">
                                                                            <?php echo e(substr($chat->name, 0, 1)); ?>

                                                                        </li>
                                                                        <li class="text-bg-secondary h-35 w-35 d-flex-center b-r-50" data-bs-toggle="tooltip" data-bs-title="2 More">
                                                                            <?php echo e($chat->participants->count()); ?>+
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                                <div class="flex-grow-1 text-start mg-s-75">
                                                                    <p class="mb-0 f-w-500 text-dark txt-ellipsis-1"><?php echo e($chat->name); ?></p>
                                                                    <p class="text-secondary f-s-12 chat-message">
                                                                        <?php if($chat->lastMessage): ?>
                                                                            <?php echo e(Str::limit($chat->lastMessage->message, 30)); ?>

                                                                        <?php else: ?>
                                                                            <?php echo e(__('chat.no_messages')); ?>

                                                                        <?php endif; ?>
                                                                        <?php if($unreadCount > 0): ?>
                                                                            <span class="badge bg-danger ms-1"><?php echo e($unreadCount); ?></span>
                                                                        <?php endif; ?>
                                                                    </p>
                                                                </div>
                                                                <div>
                                                                    <p class="f-s-12 chat-time">
                                                                        <?php if($chat->lastMessage): ?>
                                                                            <?php echo e($chat->lastMessage->created_at->diffForHumans()); ?>

                                                                        <?php else: ?>
                                                                            <?php echo e(__('chat.no_messages')); ?>

                                                                        <?php endif; ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                            <div class="text-center py-3">
                                                                <p class="text-muted"><?php echo e(__('chat.no_group_chats')); ?></p>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                                <div class="float-end">
                                                    <div class="btn-group dropup dropdown-icon-none">
                                                        <button class="btn btn-primary icon-btn b-r-22 dropdown-toggle active" type="button" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
                                                            <i class="ti ti-plus"></i>
                                                        </button>
                                                        <ul class="dropdown-menu" data-popper-placement="bottom-start">
                                                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#createPrivateChatModal">
                                                                <i class="ti ti-brand-hipchat"></i> <span class="f-s-13"><?php echo e(__('chat.create_private')); ?></span>
                                                            </a></li>
                                                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#createGroupChatModal">
                                                                <i class="ti ti-phone-call"></i> <span class="f-s-13"><?php echo e(__('chat.create_group')); ?></span>
                                                            </a></li>
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
                                                    <img src="<?php echo e(asset('assets/images/avatar/16.png')); ?>" alt="" class="img-fluid b-r-50">
                                                </span>
                                            </div>
                                            <div class="flex-grow-1 text-start ps-2">
                                                <span>Bette Hagenes</span>
                                                <p class="f-s-12 text-secondary mb-0">2:30AM</p>
                                            </div>
                                        </div>
                                        <!-- Altri aggiornamenti... -->
                                    </div>
                                    <div class="float-end">
                                        <div class="btn-group dropdown-icon-none">
                                            <button class="btn btn-primary icon-btn b-r-22 dropdown-toggle active" type="button" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
                                                <i class="ti ti-plus"></i>
                                            </button>
                                            <ul class="dropdown-menu" data-popper-placement="bottom-start">
                                                <li><a class="dropdown-item" href="#"><i class="ti ti-brand-hipchat"></i> <span class="f-s-13">New Chat</span></a></li>
                                                <li><a class="dropdown-item" href="#"><i class="ti ti-phone-call"></i> <span class="f-s-13">New Contact</span></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- tab 3 -->
                                <div id="tab-3" class="tabs-content">
                                    <div class="chat-contact tabcontent chat-contact-list">
                                        <div class="d-flex align-items-center py-3">
                                            <div>
                                                <span class="h-40 w-40 d-flex-center b-r-50 position-relative bg-info">
                                                    <img src="<?php echo e(asset('assets/images/avatar/13.png')); ?>" alt="" class="img-fluid b-r-50">
                                                    <span class="position-absolute top-0 end-0 p-1 bg-success border border-light rounded-circle"></span>
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
                                        <!-- Altri contatti... -->
                                    </div>
                                    <div class="float-end">
                                        <div class="btn-group dropdown-icon-none">
                                            <button class="btn btn-primary icon-btn b-r-22 dropdown-toggle active" type="button" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
                                                <i class="ti ti-plus"></i>
                                            </button>
                                            <ul class="dropdown-menu" data-popper-placement="bottom-start">
                                                <li><a class="dropdown-item" href="#"><i class="ti ti-brand-hipchat"></i> <span class="f-s-13">New Chat</span></a></li>
                                                <li><a class="dropdown-item" href="#"><i class="ti ti-phone-call"></i> <span class="f-s-13">New Contact</span></a></li>
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
                <div class="card chat-container-content-box">
                    <div class="card-header">
                        <div class="chat-header d-flex align-items-center" id="chatHeader">
                            <div class="d-lg-none">
                                <a class="me-3 toggle-btn" role="button"><i class="ti ti-align-justified"></i></a>
                            </div>
                            <span class="profileimg h-30 w-30 d-flex-center b-r-50 position-relative bg-light">
                                <img src="<?php echo e(\App\Helpers\AvatarHelper::getUserAvatarUrl(auth()->user())); ?>" alt="" class="img-fluid b-r-50">
                                <span class="position-absolute top-0 end-0 p-1 bg-success border border-light rounded-circle"></span>
                            </span>
                            <div class="flex-grow-1 ps-2 pe-2">
                                <div class="fs-6" id="chatHeaderName"><?php echo e(__('chat.select_chat')); ?></div>
                                <div class="text-muted f-s-12 text-success" id="chatHeaderStatus"><?php echo e(__('chat.select_chat_description')); ?></div>
                            </div>
                            <button type="button" class="btn btn-success h-45 w-45 icon-btn b-r-22 me-sm-2" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <i class="ti ti-phone-call f-s-20"></i>
                            </button>
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-body p-0">
                                        <div class="call">
                                            <div class="call-div">
                                                <img src="<?php echo e(asset('assets/images/profile-app/32.jpg')); ?>" class="w-100" alt="">
                                                <div class="call-caption">
                                                    <h2 class="text-white" id="callUserName">User</h2>
                                                    <div class="d-flex justify-content-center">
                                                        <span class="bg-success h-40 w-40 d-flex-center b-r-50 animate__animated animate__1 animate__shakeY animate__infinite call-btn pointer-events-auto" data-bs-dismiss="modal">
                                                            <i class="ti ti-phone-call"></i>
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
                            <button type="button" class="btn btn-primary h-45 w-45 icon-btn b-r-22 me-sm-2" data-bs-toggle="modal" data-bs-target="#exampleModal1">
                                <i class="ti ti-video f-s-20"></i>
                            </button>
                            <div class="modal fade" id="exampleModal1" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-body p-0">
                                        <div class="call">
                                            <div class="call-div pointer-events-auto">
                                                <img src="<?php echo e(asset('assets/images/profile-app/25.jpg')); ?>" class="w-100" alt="">
                                                <div class="call-caption">
                                                    <div class="d-flex justify-content-center align-items-center">
                                                        <span class="bg-white h-35 w-35 d-flex-center b-r-50 ms-4">
                                                            <i class="ti ti-microphone text-dark"></i>
                                                        </span>
                                                        <span data-bs-dismiss="modal" class="bg-danger h-45 w-45 d-flex-center b-r-50 ms-4 animate__pulse animate__animated animate__infinite animate__faster call-btn pointer-events-auto">
                                                            <i class="ti ti-phone"></i>
                                                        </span>
                                                        <span class="bg-white h-35 w-35 d-flex-center b-r-50 ms-4">
                                                            <i class="ti ti-phone-pause text-dark"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="video-div">
                                                <img src="<?php echo e(asset('assets/images/profile-app/31.jpg')); ?>" class="w-100 rounded" alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <button class="btn btn-secondary h-45 w-45 icon-btn b-r-22 me-sm-2" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
                                    <i class="ti ti-settings f-s-20"></i>
                                </button>
                                <ul class="dropdown-menu" data-popper-placement="bottom-start">
                                    <li><a class="dropdown-item" href="#"><i class="ti ti-brand-hipchat"></i> <span class="f-s-13">Chat Settings</span></a></li>
                                    <li><a class="dropdown-item" href="#"><i class="ti ti-phone-call"></i> <span class="f-s-13">Contact Settings</span></a></li>
                                    <li><a class="dropdown-item" href="#"><i class="ti ti-settings"></i> <span class="f-s-13">Settings</span></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body chat-body">
                        <div class="chat-container" id="chatMessages">
                            <div class="text-center py-5">
                                <i class="ph-duotone ph-chat-circle-text f-s-50 text-muted mb-3"></i>
                                <h5 class="text-muted"><?php echo e(__('chat.select_chat_to_start')); ?></h5>
                                <p class="text-muted"><?php echo e(__('chat.select_chat_description')); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="chat-footer d-flex">
                            <div class="app-form flex-grow-1">
                                <div class="input-group">
                                    <span class="input-group-text bg-secondary ms-2 me-2 b-r-10">
                                        <a class="emoji-btn d-flex-center" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Emoji" role="button">
                                            <i class="ti ti-mood-smile f-s-18"></i>
                                        </a>
                                    </span>
                                    <input type="text" class="form-control b-r-6" id="messageInput" placeholder="<?php echo e(__('chat.type_message')); ?>" aria-label="Recipient's username">
                                    <button class="btn btn-sm btn-primary ms-2 me-2 b-r-4" type="button" onclick="sendMessage()">
                                        <i class="ti ti-send"></i> <span><?php echo e(__('chat.send_message')); ?></span>
                                    </button>
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
                                <label for="fileInput" class="bg-secondary h-50 w-50 d-flex-center b-r-10 ms-1" role="button" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Paperclip">
                                    <i class="ti ti-paperclip f-s-18"></i>
                                </label>
                                <input type="file" id="fileInput" style="display: none;" accept="image/*,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx">
                            </div>
                            <div>
                                <div class="btn-group dropdown-icon-none d-sm-none">
                                    <a class="h-35 w-35 d-flex-center ms-1" role="button" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
                                        <i class="ti ti-dots-vertical"></i>
                                    </a>
                                    <ul class="dropdown-menu" data-popper-placement="bottom-start">
                                        <li><a class="dropdown-item" href="#"><i class="ti ti-microphone"></i> <span class="f-s-13">Microphone</span></a></li>
                                        <li><a class="dropdown-item" href="#"><i class="ti ti-camera-plus"></i> <span class="f-s-13">camera</span></a></li>
                                        <li><a class="dropdown-item" href="#"><i class="ti ti-paperclip"></i> <span class="f-s-13">paperclip</span></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Chat end -->
    </div>
</div>

<!-- Modal per creare chat privata -->
<div class="modal fade" id="createPrivateChatModal" tabindex="-1" aria-labelledby="createPrivateChatModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createPrivateChatModalLabel"><?php echo e(__('chat.create_private_chat')); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="searchUser" class="form-label"><?php echo e(__('chat.search_user')); ?></label>
                    <input type="text" class="form-control" id="searchUser" placeholder="<?php echo e(__('chat.type_to_search')); ?>">
                </div>
                <div id="searchResults" class="list-group"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal"><?php echo e(__('common.cancel')); ?></button>
                <button type="button" class="btn btn-primary" id="createPrivateChatBtn" disabled><?php echo e(__('chat.create')); ?></button>
            </div>
        </div>
    </div>
</div>

<!-- Modal per creare chat di gruppo -->
<div class="modal fade" id="createGroupChatModal" tabindex="-1" aria-labelledby="createGroupChatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createGroupChatModalLabel"><?php echo e(__('chat.create_group_chat')); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="groupName" class="form-label"><?php echo e(__('chat.group_name')); ?></label>
                    <input type="text" class="form-control" id="groupName" placeholder="<?php echo e(__('chat.enter_group_name')); ?>">
                </div>
                <div class="mb-3">
                    <label for="searchGroupUsers" class="form-label"><?php echo e(__('chat.add_participants')); ?></label>
                    <input type="text" class="form-control" id="searchGroupUsers" placeholder="<?php echo e(__('chat.type_to_search')); ?>">
                </div>
                <div id="groupSearchResults" class="list-group mb-3"></div>
                <div id="selectedUsers" class="mb-3">
                    <label class="form-label"><?php echo e(__('chat.selected_participants')); ?></label>
                    <div id="selectedUsersList" class="d-flex flex-wrap gap-2"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal"><?php echo e(__('common.cancel')); ?></button>
                <button type="button" class="btn btn-primary" id="createGroupChatBtn" disabled><?php echo e(__('chat.create')); ?></button>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
let currentChatId = null;
let selectedUsers = [];
let pollingInterval;

// Inizializzazione
$(document).ready(function() {
    // Gestione tab
    $('.tab-link').on('click', function() {
        $('.tab-link').removeClass('active');
        $(this).addClass('active');

        const tabId = $(this).data('tab');
        $('.tabs-content').removeClass('active');
        $(`#tab-${tabId}`).addClass('active');
    });

    // Ricerca utenti per chat privata
    $('#searchUser').on('input', function() {
        const query = $(this).val();
        if (query.length >= 2) {
            searchUsers(query, 'searchResults');
        } else {
            $('#searchResults').empty();
        }
    });

    // Ricerca utenti per chat di gruppo
    $('#searchGroupUsers').on('input', function() {
        const query = $(this).val();
        if (query.length >= 2) {
            searchUsers(query, 'groupSearchResults');
        } else {
            $('#groupSearchResults').empty();
        }
    });

    // Creazione chat privata
    $('#createPrivateChatBtn').on('click', function() {
        const selectedUserId = $(this).data('user-id');
        if (selectedUserId) {
            createPrivateChat(selectedUserId);
        }
    });

    // Creazione chat di gruppo
    $('#createGroupChatBtn').on('click', function() {
        const groupName = $('#groupName').val();
        if (groupName && selectedUsers.length > 0) {
            createGroupChat(groupName, selectedUsers);
        }
    });

    // Gestione invio messaggio
    $('#messageInput').on('keypress', function(e) {
        if (e.which === 13 && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });

    // Gestione upload file
    $('#fileInput').on('change', function() {
        const fileName = this.files[0]?.name;
        if (fileName) {
            // Mostra nome file selezionato
            console.log('File selezionato:', fileName);
        }
    });

    // Inizia polling
    startPolling();
});

// Funzione polling per aggiornamenti automatici
function startPolling() {
    // Polling ogni 5 secondi se c'è una chat attiva
    pollingInterval = setInterval(function() {
        if (currentChatId) {
            loadMessages(currentChatId);
        }
    }, 5000);
}

// Carica una chat
function loadChat(chatId) {
    currentChatId = chatId;

    // Aggiorna UI
    $('.chat-contactbox').removeClass('active');
    $(`[data-chat-id="${chatId}"]`).addClass('active');

    // Carica messaggi
    loadMessages(chatId);

    // Aggiorna header
    updateChatHeader(chatId);
}

// Aggiorna header della chat
function updateChatHeader(chatId) {
    const chatBox = $(`[data-chat-id="${chatId}"]`);
    const chatName = chatBox.find('.chat-name').text() || 'Chat';
    const chatStatus = chatBox.find('.chat-status').text() || 'Online';

    $('#chatHeaderName').text(chatName);
    $('#chatHeaderStatus').text(chatStatus);
}

// Carica messaggi
function loadMessages(chatId) {
    $.get(`<?php echo e(route('chat.messages', ':chatId')); ?>`.replace(':chatId', chatId))
        .done(function(response) {
            if (response.success) {
                displayMessages(response.messages);
            }
        })
        .fail(function() {
            console.error('Errore nel caricamento dei messaggi');
        });
}

// Mostra messaggi
function displayMessages(messages) {
    const container = $('#chatMessages');
    container.empty();

    if (messages.length === 0) {
        container.html(`
            <div class="text-center py-5">
                <i class="ph-duotone ph-chat-circle-text f-s-50 text-muted mb-3"></i>
                <h5 class="text-muted"><?php echo e(__('chat.no_messages')); ?></h5>
            </div>
        `);
        return;
    }

    // Aggiungi badge "Today"
    container.append('<div class="text-center"><span class="badge text-light-secondary">Today</span></div>');

    messages.forEach(function(message) {
        const messageHtml = createMessageHtml(message);
        container.append(messageHtml);
    });

    // Scroll to bottom
    container.scrollTop(container[0].scrollHeight);
}

// Crea HTML per un messaggio
function createMessageHtml(message) {
    const isOwn = message.user_id == <?php echo e(auth()->id()); ?>;
    const messageClass = isOwn ? 'chat-box-right' : 'chat-box';
    const avatarClass = isOwn ? 'end-0 top-0 bg-danger' : 'start-0 bg-light';
    const avatarImg = isOwn ?
        '<?php echo e(asset("assets/images/avatar/09.png")); ?>' :
        (message.user.avatar ? `<?php echo e(asset('storage/')); ?>/${message.user.avatar}` : '<?php echo e(asset("assets/images/avatar/14.png")); ?>');

    let content = '';

    if (message.file_path) {
        if (message.is_image) {
            content = `<img src="${message.file_url}" alt="image" class="img-fluid rounded" style="max-width: 200px;">`;
        } else {
            content = `
                <div class="d-flex align-items-center">
                    <i class="ti ti-file-text me-2"></i>
                    <a href="${message.file_url}" target="_blank" class="text-decoration-none">
                        ${message.file_name}
                    </a>
                </div>`;
        }
    } else {
        content = `<p class="chat-text">${message.message}</p>`;
    }

    return `
        <div class="position-relative">
            ${!isOwn ? `<div class="chatdp h-25 w-25 b-r-50 position-absolute ${avatarClass}">
                <img src="${avatarImg}" alt="" class="img-fluid b-r-50">
            </div>` : ''}
            <div class="${messageClass}">
                <div>
                    ${content}
                    <p class="text-muted"><i class="ti ti-checks text-primary"></i> ${message.created_at}</p>
                </div>
            </div>
            ${isOwn ? `<div class="chatdp h-25 w-25 b-r-50 position-absolute ${avatarClass}">
                <img src="${avatarImg}" alt="" class="img-fluid b-r-50">
            </div>` : ''}
        </div>`;
}

// Invia messaggio
function sendMessage() {
    const message = $('#messageInput').val().trim();
    const fileInput = $('#fileInput')[0];

    if (!message && !fileInput.files.length) return;

    const formData = new FormData();
    formData.append('message', message);
    if (fileInput.files.length) {
        formData.append('file', fileInput.files[0]);
    }

    $.ajax({
        url: `<?php echo e(route('chat.messages.store', ':chatId')); ?>`.replace(':chatId', currentChatId),
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    .done(function(response) {
        if (response.success) {
            $('#messageInput').val('');
            $('#fileInput').val('');
            loadMessages(currentChatId);
        }
    })
    .fail(function() {
        console.error('Errore nell\'invio del messaggio');
    });
}

// Ricerca utenti
function searchUsers(query, targetId) {
    $.get(`<?php echo e(route('chat.users.search')); ?>?q=${encodeURIComponent(query)}`)
        .done(function(response) {
            if (response.success) {
                displaySearchResults(response.users, targetId);
            }
        })
        .fail(function() {
            console.error('Errore nella ricerca utenti');
        });
}

// Mostra risultati ricerca
function displaySearchResults(users, targetId) {
    const container = $(`#${targetId}`);
    container.empty();

    users.forEach(function(user) {
        const item = `
            <div class="list-group-item list-group-item-action d-flex align-items-center" data-user-id="${user.id}">
                <img src="${user.avatar_url || '<?php echo e(asset("assets/images/avatar/1.png")); ?>'}"
                     alt="user-img" class="rounded-circle h-25 w-25 me-3">
                <div>
                    <h6 class="mb-0">${user.name}</h6>
                    <small class="text-muted">${user.email}</small>
                </div>
            </div>`;
        container.append(item);
    });

    // Gestione click sui risultati
    container.find('.list-group-item').on('click', function() {
        const userId = $(this).data('user-id');
        const userName = $(this).find('h6').text();

        if (targetId === 'searchResults') {
            // Chat privata
            $('#createPrivateChatBtn').data('user-id', userId).prop('disabled', false);
            $('#searchResults .list-group-item').removeClass('active');
            $(this).addClass('active');
        } else {
            // Chat di gruppo
            if (!selectedUsers.find(u => u.id == userId)) {
                selectedUsers.push({id: userId, name: userName});
                updateSelectedUsersList();
            }
        }
    });
}

// Aggiorna lista utenti selezionati
function updateSelectedUsersList() {
    const container = $('#selectedUsersList');
    container.empty();

    selectedUsers.forEach(function(user) {
        const badge = `
            <span class="badge bg-primary d-flex align-items-center">
                ${user.name}
                <button type="button" class="btn-close btn-close-white ms-2"
                        onclick="removeSelectedUser(${user.id})"></button>
            </span>`;
        container.append(badge);
    });

    $('#createGroupChatBtn').prop('disabled', selectedUsers.length === 0);
}

// Rimuovi utente selezionato
function removeSelectedUser(userId) {
    selectedUsers = selectedUsers.filter(u => u.id != userId);
    updateSelectedUsersList();
}

// Crea chat privata
function createPrivateChat(userId) {
    $.post('<?php echo e(route("chat.create.private")); ?>', {
        user_id: userId,
        _token: $('meta[name="csrf-token"]').attr('content')
    })
    .done(function(response) {
        if (response.success) {
            $('#createPrivateChatModal').modal('hide');
            location.reload();
        }
    })
    .fail(function() {
        console.error('Errore nella creazione della chat');
    });
}

// Crea chat di gruppo
function createGroupChat(name, userIds) {
    $.post('<?php echo e(route("chat.create.group")); ?>', {
        name: name,
        user_ids: userIds.map(u => u.id),
        _token: $('meta[name="csrf-token"]').attr('content')
    })
    .done(function(response) {
        if (response.success) {
            $('#createGroupChatModal').modal('hide');
            location.reload();
        }
    })
    .fail(function() {
        console.error('Errore nella creazione della chat');
    });
}

// Aggiorna header chat
function updateChatHeader(chatId) {
    // Trova la chat selezionata
    const chatItem = $(`[data-chat-id="${chatId}"]`);
    const chatName = chatItem.find('.f-w-500').text();

    // Aggiorna header
    $('#chatHeaderName').text(chatName);
    $('#chatHeaderStatus').text('<?php echo e(__("chat.online")); ?>');
    $('#callUserName').text(chatName);
}

// Inizia polling
function startPolling() {
    pollingInterval = setInterval(function() {
        if (currentChatId) {
            loadMessages(currentChatId);
        }
    }, 5000); // Poll ogni 5 secondi
}

// Cleanup al cambio pagina
$(window).on('beforeunload', function() {
    if (pollingInterval) {
        clearInterval(pollingInterval);
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.master', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\slamin\resources\views/chat.blade.php ENDPATH**/ ?>