@extends('layout.master')

@section('main-content')

<!-- Laravel Echo (compilato) -->
@vite(['resources/js/app.js'])

<!-- Laravel Reverb Client -->
<script src="{{ asset('assets/js/reverb-client.js') }}"></script>
<div class="page-content">
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">{{ __('chat.title') }}</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('sidebar.dashboard') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('chat.title') }}</li>
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
                                    <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl(auth()->user()) }}" alt="" class="img-fluid b-r-50">
                                    <span class="position-absolute top-0 end-0 p-1 bg-{{ auth()->user()->getOnlineStatusColor() }} border border-light rounded-circle online-status-indicator" 
                                          data-user-id="{{ auth()->user()->id }}" 
                                          title="{{ auth()->user()->getLastSeenDisplay() }}">
                                        <i class="ph {{ auth()->user()->getOnlineStatusIcon() }} f-s-8"></i>
                                    </span>
                                </span>
                                <div class="flex-grow-1 ps-2">
                                    <div class="fs-6">{{ auth()->user()->name }}</div>
                                    <div class="text-muted f-s-12">{{ auth()->user()->role ?? 'User' }}</div>
                                </div>
                                <div>
                                    <!-- Online Status Dropdown - Semplificato -->
                                    <div class="btn-group dropdown-icon-none">
                                        <button type="button" class="btn btn-light btn-sm dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="ph {{ auth()->user()->getOnlineStatusIcon() }} fs-5 text-{{ auth()->user()->getOnlineStatusColor() }}"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><h6 class="dropdown-header">Stato Online</h6></li>
                                            <li><a class="dropdown-item" href="#" data-status="online"><i class="ph ph-circle-fill text-success me-2"></i> <span class="f-s-13">Online</span></a></li>
                                            <li><a class="dropdown-item" href="#" data-status="away"><i class="ph ph-clock text-warning me-2"></i> <span class="f-s-13">Assente</span></a></li>
                                            <li><a class="dropdown-item" href="#" data-status="busy"><i class="ph ph-minus-circle text-danger me-2"></i> <span class="f-s-13">Occupato</span></a></li>
                                            <li><a class="dropdown-item" href="#" data-status="invisible"><i class="ph ph-circle text-secondary me-2"></i> <span class="f-s-13">Invisibile</span></a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#privacySettingsModal"><i class="ph ph-shield-check me-2"></i> <span class="f-s-13">Impostazioni Privacy</span></a></li>
                                        </ul>
                                    </div>
                                    <div class="btn-group dropdown-icon-none ms-1">
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
                                                        @forelse($chats->where('type', 'private') as $chat)
                                                            @php
                                                                $otherUser = $chat->participants->where('user_id', '!=', auth()->id())->first()->user ?? null;
                                                                $unreadCount = $chat->getUnreadCount(auth()->user());
                                                            @endphp
                                                            <div class="chat-contactbox" data-chat-id="{{ $chat->id }}" data-user-id="{{ $otherUser ? $otherUser->id : '' }}" onclick="loadChat({{ $chat->id }})">
                                                                <div class="position-absolute">
                                                                                                    <span class="h-30 w-30 d-flex-center b-r-50 position-relative bg-primary">
                                    <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($otherUser) }}" alt="" class="img-fluid b-r-50">
                                    @if($otherUser)
                                        <span class="position-absolute top-0 end-0 p-1 bg-{{ $otherUser->getOnlineStatusColor() }} border border-light rounded-circle online-status-indicator" 
                                              data-user-id="{{ $otherUser->id }}" 
                                              data-status="{{ $otherUser->getOnlineStatusDisplay() }}"
                                              title="{{ $otherUser->getLastSeenDisplay() }}">
                                            <i class="ph {{ $otherUser->getOnlineStatusIcon() }} f-s-8"></i>
                                        </span>
                                    @else
                                        <span class="position-absolute top-0 end-0 p-1 bg-secondary border border-light rounded-circle">
                                            <i class="ph ph-circle f-s-8"></i>
                                        </span>
                                    @endif
                                </span>
                                                                </div>
                                                                <div class="flex-grow-1 text-start mg-s-50">
                                                                    <p class="mb-0 f-w-500 text-dark txt-ellipsis-1">{{ $otherUser ? $otherUser->name : __('chat.unknown_user') }}</p>
                                                                    <p class="text-secondary mb-0 f-s-12 mb-0 chat-message">
                                                                        <i class="ti ti-checks"></i>
                                                                        @if($chat->lastMessage)
                                                                            {{ Str::limit($chat->lastMessage->message, 30) }}
                                                                        @else
                                                                            {{ __('chat.no_messages') }}
                                                                        @endif
                                                                        @if($unreadCount > 0)
                                                                            <span class="badge bg-danger ms-1">{{ $unreadCount }}</span>
                                                                        @endif
                                                                    </p>
                                                                </div>
                                                                <div>
                                                                    <p class="f-s-12 chat-time">
                                                                        @if($chat->lastMessage)
                                                                            {{ $chat->lastMessage->created_at->diffForHumans() }}
                                                                        @else
                                                                            {{ __('chat.no_messages') }}
                                                                        @endif
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        @empty
                                                            <div class="text-center py-3">
                                                                <p class="text-muted">{{ __('chat.no_private_chats') }}</p>
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                                <!-- Group Chat -->
                                                <div class="tab-pane fade" id="groups-tab-pane" role="tabpanel" aria-labelledby="groups-tab" tabindex="0">
                                                    <div class="chat-contact chat-group-list" id="groupChatsList">
                                                        @forelse($chats->where('type', 'group') as $chat)
                                                            @php
                                                                $unreadCount = $chat->getUnreadCount(auth()->user());
                                                            @endphp
                                                            <div class="chat-contactbox" data-chat-id="{{ $chat->id }}" onclick="loadChat({{ $chat->id }})">
                                                                <div class="position-absolute">
                                                                    <ul class="avatar-group">
                                                                        <li class="text-bg-warning h-45 w-45 d-flex-center b-r-50">
                                                                            {{ substr($chat->name, 0, 1) }}
                                                                        </li>
                                                                        <li class="text-bg-secondary h-35 w-35 d-flex-center b-r-50" title="2 More">
                                                                            {{ $chat->participants->count() }}+
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                                <div class="flex-grow-1 text-start mg-s-75">
                                                                    <p class="mb-0 f-w-500 text-dark txt-ellipsis-1">{{ $chat->name }}</p>
                                                                    <p class="text-secondary f-s-12 chat-message">
                                                                        @if($chat->lastMessage)
                                                                            {{ Str::limit($chat->lastMessage->message, 30) }}
                                                                        @else
                                                                            {{ __('chat.no_messages') }}
                                                                        @endif
                                                                        @if($unreadCount > 0)
                                                                            <span class="badge bg-danger ms-1">{{ $unreadCount }}</span>
                                                                        @endif
                                                                    </p>
                                                                </div>
                                                                <div>
                                                                    <p class="f-s-12 chat-time">
                                                                        @if($chat->lastMessage)
                                                                            {{ $chat->lastMessage->created_at->diffForHumans() }}
                                                                        @else
                                                                            {{ __('chat.no_messages') }}
                                                                        @endif
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        @empty
                                                            <div class="text-center py-3">
                                                                <p class="text-muted">{{ __('chat.no_group_chats') }}</p>
                                                            </div>
                                                        @endforelse
                                                    </div>
                                                </div>
                                                <div class="float-end">
                                                    <div class="btn-group dropup dropdown-icon-none">
                                                        <button class="btn btn-primary icon-btn b-r-22 dropdown-toggle active" type="button" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
                                                            <i class="ti ti-plus"></i>
                                                        </button>
                                                        <ul class="dropdown-menu" data-popper-placement="bottom-start">
                                                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#createPrivateChatModal">
                                                                <i class="ti ti-brand-hipchat"></i> <span class="f-s-13">{{ __('chat.create_private') }}</span>
                                                            </a></li>
                                                            <li><a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#createGroupChatModal">
                                                                <i class="ti ti-phone-call"></i> <span class="f-s-13">{{ __('chat.create_group') }}</span>
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
                                                    <img src="{{ asset('assets/images/avatar/16.png') }}" alt="" class="img-fluid b-r-50">
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
                                                    <img src="{{ asset('assets/images/avatar/13.png') }}" alt="" class="img-fluid b-r-50">
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
                                <img src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl(auth()->user()) }}" alt="" class="img-fluid b-r-50">
                                <span class="position-absolute top-0 end-0 p-1 bg-success border border-light rounded-circle" id="chatHeaderIndicator"></span>
                            </span>
                            <div class="flex-grow-1 ps-2 pe-2">
                                <div class="fs-6" id="chatHeaderName">{{ __('chat.select_chat') }}</div>
                                <div class="text-muted f-s-12 text-success" id="chatHeaderStatus">{{ __('chat.select_chat_description') }}</div>
                            </div>
                            <button type="button" class="btn btn-success h-45 w-45 icon-btn b-r-22 me-sm-2 btn-call-audio" onclick="startVoiceCall()">
                                <i class="ti ti-phone-call f-s-20"></i>
                            </button>
                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-body p-0">
                                        <div class="call">
                                            <div class="call-div">
                                                <img src="{{ asset('assets/images/profile-app/32.jpg') }}" class="w-100" alt="">
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
                            <button type="button" class="btn btn-primary h-45 w-45 icon-btn b-r-22 me-sm-2 btn-call-video" onclick="startVideoCall()">
                                <i class="ti ti-video f-s-20"></i>
                            </button>
                            <div class="modal fade" id="exampleModal1" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-body p-0">
                                        <div class="call">
                                            <div class="call-div pointer-events-auto">
                                                <img src="{{ asset('assets/images/profile-app/25.jpg') }}" class="w-100" alt="">
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
                                                <img src="{{ asset('assets/images/profile-app/31.jpg') }}" class="w-100 rounded" alt="">
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
                                    <li><a class="dropdown-item" href="#" onclick="openChatSettings(); return false;"><i class="ti ti-brand-hipchat"></i> <span class="f-s-13">Impostazioni Chat</span></a></li>
                                    <li><a class="dropdown-item" href="#" onclick="showNotification('Funzionalità in sviluppo', 'info'); return false;"><i class="ti ti-phone-call"></i> <span class="f-s-13">Impostazioni Contatto</span></a></li>
                                    <li><a class="dropdown-item" href="#" onclick="showNotification('Funzionalità in sviluppo', 'info'); return false;"><i class="ti ti-settings"></i> <span class="f-s-13">Impostazioni Generali</span></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-body chat-body">
                        <div class="chat-container" id="chatMessages">
                            <div class="text-center py-5">
                                <i class="ph-duotone ph-chat-circle-text f-s-50 text-muted mb-3"></i>
                                <h5 class="text-muted">{{ __('chat.select_chat_to_start') }}</h5>
                                <p class="text-muted">{{ __('chat.select_chat_description') }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="chat-footer d-flex">
                            <div class="app-form flex-grow-1">
                                <div class="input-group">
                                    <span class="input-group-text bg-secondary ms-2 me-2 b-r-10">
                                        <a class="emoji-btn d-flex-center" title="Emoji" role="button">
                                            <i class="ti ti-mood-smile f-s-18"></i>
                                        </a>
                                    </span>
                                    <input type="text" class="form-control b-r-6" id="messageInput" placeholder="{{ __('chat.type_message') }}" aria-label="Recipient's username">
                                    <button class="btn btn-sm btn-primary ms-2 me-2 b-r-4" type="button" onclick="sendMessage()">
                                        <i class="ti ti-send"></i> <span>{{ __('chat.send_message') }}</span>
                                    </button>
                                </div>
                            </div>
                            <div class="d-none d-sm-block">
                                                                    <a class="bg-secondary h-50 w-50 d-flex-center b-r-10 ms-1" role="button" title="Microphone">
                                    <i class="ti ti-microphone f-s-18"></i>
                                </a>
                            </div>
                            <div class="d-none d-sm-block">
                                                                    <a class="bg-secondary h-50 w-50 d-flex-center b-r-10 ms-1" role="button" title="Camera">
                                    <i class="ti ti-camera-plus f-s-18"></i>
                                </a>
                            </div>
                            <div class="d-none d-sm-block">
                                                                    <label for="fileInput" class="bg-secondary h-50 w-50 d-flex-center b-r-10 ms-1" role="button" title="Paperclip">
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
                <h5 class="modal-title" id="createPrivateChatModalLabel">{{ __('chat.create_private_chat') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="searchUser" class="form-label">{{ __('chat.search_user') }}</label>
                    <input type="text" class="form-control" id="searchUser" placeholder="{{ __('chat.type_to_search') }}">
                </div>
                <div id="searchResults" class="list-group"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('common.cancel') }}</button>
                <button type="button" class="btn btn-primary" id="createPrivateChatBtn" disabled>{{ __('chat.create') }}</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal per creare chat di gruppo -->
<div class="modal fade" id="createGroupChatModal" tabindex="-1" aria-labelledby="createGroupChatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createGroupChatModalLabel">{{ __('chat.create_group_chat') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="groupName" class="form-label">{{ __('chat.group_name') }}</label>
                    <input type="text" class="form-control" id="groupName" placeholder="{{ __('chat.enter_group_name') }}">
                </div>
                <div class="mb-3">
                    <label for="searchGroupUsers" class="form-label">{{ __('chat.add_participants') }}</label>
                    <input type="text" class="form-control" id="searchGroupUsers" placeholder="{{ __('chat.type_to_search') }}">
                </div>
                <div id="groupSearchResults" class="list-group mb-3"></div>
                <div id="selectedUsers" class="mb-3">
                    <label class="form-label">{{ __('chat.selected_participants') }}</label>
                    <div id="selectedUsersList" class="d-flex flex-wrap gap-2"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('common.cancel') }}</button>
                <button type="button" class="btn btn-primary" id="createGroupChatBtn" disabled>{{ __('chat.create') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection

<!-- Modal Impostazioni Privacy -->
<div class="modal fade" id="privacySettingsModal" tabindex="-1" aria-labelledby="privacySettingsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="privacySettingsModalLabel">
                    <i class="ph ph-shield-check me-2"></i>Impostazioni Privacy Stato Online
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-4">
                    <label class="form-label fw-bold">Chi può vedere il tuo stato online?</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="privacy_visibility" id="visibility_all" value="all" checked>
                        <label class="form-check-label" for="visibility_all">
                            <i class="ph ph-globe me-2 text-primary"></i>
                            <strong>Tutti</strong>
                            <small class="d-block text-muted">Chiunque può vedere quando sei online</small>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="privacy_visibility" id="visibility_friends" value="friends">
                        <label class="form-check-label" for="visibility_friends">
                            <i class="ph ph-users me-2 text-success"></i>
                            <strong>Solo amici</strong>
                            <small class="d-block text-muted">Solo chi segui o ti segue può vedere il tuo stato</small>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="privacy_visibility" id="visibility_none" value="none">
                        <label class="form-check-label" for="visibility_none">
                            <i class="ph ph-eye-slash me-2 text-secondary"></i>
                            <strong>Nessuno</strong>
                            <small class="d-block text-muted">Il tuo stato online è sempre nascosto</small>
                        </label>
                    </div>
                </div>
                
                <div class="alert alert-info">
                    <i class="ph ph-info-circle me-2"></i>
                    <strong>Nota:</strong> Queste impostazioni si applicano a tutti gli utenti tranne te. Tu puoi sempre vedere il tuo stato.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
                <button type="button" class="btn btn-primary" onclick="savePrivacySettings()">
                    <i class="ph ph-check me-2"></i>Salva Impostazioni
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
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
    
    // Entra nella chat via WebSocket
    if (reverbClient && reverbClient.isConnected) {
        reverbClient.joinChat(chatId);
    }
    
    // Aggiorna il badge dei messaggi non letti (i messaggi vengono marcati come letti)
    setTimeout(updateUnreadMessagesBadge, 1000);
}

// Aggiorna header della chat
function updateChatHeader(chatId) {
    const chatBox = $(`[data-chat-id="${chatId}"]`);
    const chatName = chatBox.find('.f-w-500').text() || 'Chat';
    
    // Ottieni l'ID dell'utente dalla chat
    const userId = chatBox.data('user-id');
    
    // Imposta lo stato iniziale come "Caricamento..."
    $('#chatHeaderName').text(chatName);
    $('#chatHeaderStatus').text('Caricamento...');
    $('#callUserName').text(chatName);
    
    // Se abbiamo l'ID utente, ottieni lo stato reale
    if (userId) {
        fetch(`{{ route('online-status.user-status', ':userId') }}`.replace(':userId', userId), {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const statusText = data.is_online ? '{{ __("chat.online") }}' : '{{ __("chat.offline") }}';
                const statusClass = data.is_online ? 'text-success' : 'text-muted';
                const indicatorClass = data.is_online ? 'bg-success' : 'bg-secondary';
                
                // Aggiorna il testo dello stato
                $('#chatHeaderStatus').text(statusText).removeClass('text-success text-muted').addClass(statusClass);
                
                // Aggiorna il pallino nell'header
                $('#chatHeaderIndicator').removeClass('bg-success bg-warning bg-danger bg-secondary').addClass(indicatorClass);
            } else {
                $('#chatHeaderStatus').text('{{ __("chat.offline") }}').removeClass('text-success text-muted').addClass('text-muted');
                $('#chatHeaderIndicator').removeClass('bg-success bg-warning bg-danger bg-secondary').addClass('bg-secondary');
            }
        })
        .catch(error => {
            console.error('Errore nel caricamento dello stato utente:', error);
            $('#chatHeaderStatus').text('{{ __("chat.offline") }}').removeClass('text-success text-muted').addClass('text-muted');
        });
    } else {
        $('#chatHeaderStatus').text('{{ __("chat.offline") }}').removeClass('text-success text-muted').addClass('text-muted');
    }
}

// Carica messaggi
function loadMessages(chatId) {
    $.get(`{{ route('chat.messages', ':chatId') }}`.replace(':chatId', chatId))
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
                <h5 class="text-muted">{{ __('chat.no_messages') }}</h5>
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
    const isOwn = message.user_id == {{ auth()->id() }};
    const messageClass = isOwn ? 'chat-box-right' : 'chat-box';
    const avatarClass = isOwn ? 'end-0 top-0 bg-danger' : 'start-0 bg-light';
    const avatarImg = isOwn ?
        '{{ asset("assets/images/avatar/09.png") }}' :
        (message.user.avatar ? `{{ asset('storage/') }}/${message.user.avatar}` : '{{ asset("assets/images/avatar/14.png") }}');

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

    // Se abbiamo un file, usa l'approccio tradizionale
    if (fileInput.files.length) {
        const formData = new FormData();
        formData.append('message', message);
        formData.append('file', fileInput.files[0]);

        $.ajax({
            url: `{{ route('chat.messages.store', ':chatId') }}`.replace(':chatId', currentChatId),
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
                
                // Aggiorna il badge dei messaggi non letti dopo l'invio
                setTimeout(updateUnreadMessagesBadge, 500);
            }
        })
        .fail(function() {
            console.error('Errore nell\'invio del messaggio');
        });
    } else {
        // Per messaggi di testo, usa Reverb per invio istantaneo
        if (reverbClient && reverbClient.isConnected) {
            reverbClient.sendMessage(currentChatId, message, 'text');
            $('#messageInput').val('');
            
            // Aggiungi il messaggio immediatamente alla chat
            const messageData = {
                id: Date.now(), // ID temporaneo
                user_id: {{ auth()->id() }},
                message: message,
                message_type: 'text',
                created_at: new Date().toISOString()
            };
            addMessageToChat(messageData);
        } else {
            // Fallback al metodo tradizionale se WebSocket non è disponibile
            const formData = new FormData();
            formData.append('message', message);

            $.ajax({
                url: `{{ route('chat.messages.store', ':chatId') }}`.replace(':chatId', currentChatId),
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
                    loadMessages(currentChatId);
                }
            })
            .fail(function() {
                console.error('Errore nell\'invio del messaggio');
            });
        }
    }
}

// Ricerca utenti
function searchUsers(query, targetId) {
    $.get(`{{ route('chat.users.search') }}?q=${encodeURIComponent(query)}`)
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
                <img src="${user.avatar_url || '{{ asset("assets/images/avatar/1.png") }}'}"
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
    $.post('{{ route("chat.create.private") }}', {
        user_id: userId,
        _token: $('meta[name="csrf-token"]').attr('content')
    })
    .done(function(response) {
        if (response.success) {
            $('#createPrivateChatModal').modal('hide');
            
            // Aggiorna dinamicamente la lista delle chat
            refreshChatList();
            
            // Mostra notifica di successo
            showNotification('Chat privata creata con successo!', 'success');
            
            // Se la chat è stata creata, caricala automaticamente
            if (response.chat_id) {
                setTimeout(() => {
                    loadChat(response.chat_id);
                }, 500);
            }
        } else {
            showNotification('Errore nella creazione della chat', 'error');
        }
    })
    .fail(function() {
        console.error('Errore nella creazione della chat');
        showNotification('Errore nella creazione della chat', 'error');
    });
}

// Crea chat di gruppo
function createGroupChat(name, userIds) {
    $.post('{{ route("chat.create.group") }}', {
        name: name,
        user_ids: userIds.map(u => u.id),
        _token: $('meta[name="csrf-token"]').attr('content')
    })
    .done(function(response) {
        if (response.success) {
            $('#createGroupChatModal').modal('hide');
            
            // Aggiorna dinamicamente la lista delle chat
            refreshChatList();
            
            // Mostra notifica di successo
            showNotification('Chat di gruppo creata con successo!', 'success');
            
            // Se la chat è stata creata, caricala automaticamente
            if (response.chat_id) {
                setTimeout(() => {
                    loadChat(response.chat_id);
                }, 500);
            }
        } else {
            showNotification('Errore nella creazione della chat', 'error');
        }
    })
    .fail(function() {
        console.error('Errore nella creazione della chat');
        showNotification('Errore nella creazione della chat', 'error');
    });
}

// Aggiorna header chat

// Aggiorna dinamicamente la lista delle chat
function refreshChatList() {
    $.get('{{ route("chat.list") }}')
        .done(function(response) {
            if (response.success) {
                updatePrivateChatsList(response.private_chats);
                updateGroupChatsList(response.group_chats);
            }
        })
        .fail(function() {
            console.error('Errore nel caricamento della lista chat');
        });
}

// Aggiorna la lista delle chat private
function updatePrivateChatsList(chats) {
    const container = $('#privateChatsList');
    container.empty();
    
    if (chats.length === 0) {
        container.html('<div class="text-center py-3"><p class="text-muted">{{ __("chat.no_private_chats") }}</p></div>');
        return;
    }
    
    chats.forEach(function(chat) {
        const chatHtml = createPrivateChatHtml(chat);
        container.append(chatHtml);
    });
}

// Aggiorna la lista delle chat di gruppo
function updateGroupChatsList(chats) {
    const container = $('#groupChatsList');
    container.empty();
    
    if (chats.length === 0) {
        container.html('<div class="text-center py-3"><p class="text-muted">{{ __("chat.no_group_chats") }}</p></div>');
        return;
    }
    
    chats.forEach(function(chat) {
        const chatHtml = createGroupChatHtml(chat);
        container.append(chatHtml);
    });
}

// Crea HTML per una chat privata
function createPrivateChatHtml(chat) {
    const otherUser = chat.other_user;
    const unreadCount = chat.unread_count || 0;
    const lastMessage = chat.last_message;
    
    return `
        <div class="chat-contactbox" data-chat-id="${chat.id}" data-user-id="${otherUser ? otherUser.id : ''}" onclick="loadChat(${chat.id})">
            <div class="position-absolute">
                <span class="h-30 w-30 d-flex-center b-r-50 position-relative bg-primary">
                    <img src="${otherUser ? otherUser.avatar_url : '{{ asset("assets/images/avatar/14.png") }}'}" alt="" class="img-fluid b-r-50">
                    ${otherUser ? `
                        <span class="position-absolute top-0 end-0 p-1 bg-${otherUser.online_status_color} border border-light rounded-circle online-status-indicator" 
                              data-user-id="${otherUser.id}" 
                              data-status="${otherUser.online_status_display}"
                              title="${otherUser.last_seen_display}">
                            <i class="ph ${otherUser.online_status_icon} f-s-8"></i>
                        </span>
                    ` : `
                        <span class="position-absolute top-0 end-0 p-1 bg-secondary border border-light rounded-circle">
                            <i class="ph ph-circle f-s-8"></i>
                        </span>
                    `}
                </span>
            </div>
            <div class="flex-grow-1 text-start mg-s-50">
                <p class="mb-0 f-w-500 text-dark txt-ellipsis-1">${otherUser ? otherUser.name : '{{ __("chat.unknown_user") }}'}</p>
                <p class="text-secondary mb-0 f-s-12 mb-0 chat-message">
                    <i class="ti ti-checks"></i>
                    ${lastMessage ? lastMessage.message.substring(0, 30) + (lastMessage.message.length > 30 ? '...' : '') : '{{ __("chat.no_messages") }}'}
                    ${unreadCount > 0 ? `<span class="badge bg-danger ms-1">${unreadCount}</span>` : ''}
                </p>
            </div>
            <div>
                <p class="f-s-12 chat-time">
                    ${lastMessage ? lastMessage.created_at : '{{ __("chat.no_messages") }}'}
                </p>
            </div>
        </div>
    `;
}

// Crea HTML per una chat di gruppo
function createGroupChatHtml(chat) {
    const unreadCount = chat.unread_count || 0;
    const lastMessage = chat.last_message;
    
    return `
        <div class="chat-contactbox" data-chat-id="${chat.id}" onclick="loadChat(${chat.id})">
            <div class="position-absolute">
                <ul class="avatar-group">
                    <li class="text-bg-warning h-45 w-45 d-flex-center b-r-50">
                        ${chat.name.charAt(0)}
                    </li>
                    <li class="text-bg-secondary h-35 w-35 d-flex-center b-r-50" title="${chat.participants_count}+">
                        ${chat.participants_count}+
                    </li>
                </ul>
            </div>
            <div class="flex-grow-1 text-start mg-s-75">
                <p class="mb-0 f-w-500 text-dark txt-ellipsis-1">${chat.name}</p>
                <p class="text-secondary f-s-12 chat-message">
                    ${lastMessage ? lastMessage.message.substring(0, 30) + (lastMessage.message.length > 30 ? '...' : '') : '{{ __("chat.no_messages") }}'}
                    ${unreadCount > 0 ? `<span class="badge bg-danger ms-1">${unreadCount}</span>` : ''}
                </p>
            </div>
            <div>
                <p class="f-s-12 chat-time">
                    ${lastMessage ? lastMessage.created_at : '{{ __("chat.no_messages") }}'}
                </p>
            </div>
        </div>
    `;
}

// Mostra notifiche
function showNotification(message, type = 'info') {
    // Usa SweetAlert2 se disponibile, altrimenti alert normale
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: type === 'success' ? 'Successo!' : type === 'error' ? 'Errore!' : 'Info',
            text: message,
            icon: type,
            timer: type === 'success' ? 2000 : 4000,
            showConfirmButton: false
        });
    } else {
        alert(message);
    }
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

// Online Status Management
let onlineStatusInterval;
let lastSeenInterval;

// Inizializza il sistema di stato online
function initOnlineStatus() {
    // Imposta l'utente come online
    updateUserOnlineStatus('online');
    
    // Aggiorna l'ultima attività ogni 30 secondi
    lastSeenInterval = setInterval(updateLastSeen, 30000);
    
    // Aggiorna lo stato online degli altri utenti ogni 60 secondi
    onlineStatusInterval = setInterval(updateOnlineStatuses, 60000);
    
    // Aggiorna immediatamente
    updateOnlineStatuses();
}

// Aggiorna lo stato online dell'utente corrente
function updateUserOnlineStatus(status) {
    fetch('{{ route("online-status.update-status") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Stato online aggiornato:', data.status);
        }
    })
    .catch(error => {
        console.error('Errore aggiornamento stato online:', error);
    });
}

// Aggiorna l'ultima attività
function updateLastSeen() {
    fetch('{{ route("online-status.update-last-seen") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Ultima attività aggiornata');
        }
    })
    .catch(error => {
        console.error('Errore aggiornamento ultima attività:', error);
    });
}

// Aggiorna lo stato online di tutti gli utenti visibili
function updateOnlineStatuses() {
    const userIds = [];
    $('.online-status-indicator').each(function() {
        userIds.push($(this).data('user-id'));
    });
    
    console.log('Aggiornamento stati online per utenti:', userIds);
    
    if (userIds.length === 0) return;
    
    fetch('{{ route("online-status.multiple-users-status") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        body: JSON.stringify({ user_ids: userIds })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Risposta stati online:', data);
        if (data.success) {
            updateOnlineStatusIndicators(data.statuses);
        }
    })
    .catch(error => {
        console.error('Errore aggiornamento stati online:', error);
    });
}

// Aggiorna gli indicatori di stato online
function updateOnlineStatusIndicators(statuses) {
    console.log('Aggiornamento indicatori con stati:', statuses);
    
    Object.keys(statuses).forEach(userId => {
        const status = statuses[userId];
        const indicator = $(`.online-status-indicator[data-user-id="${userId}"]`);
        
        console.log(`Utente ${userId}:`, status);
        
        if (indicator.length > 0) {
            // Rimuovi tutte le classi di colore possibili
            indicator.removeClass('bg-success bg-warning bg-danger bg-secondary bg-primary');
            
            // Aggiungi la nuova classe di colore
            indicator.addClass(`bg-${status.status_color}`);
            
            // Aggiorna l'icona rimuovendo tutte le classi e aggiungendo quelle nuove
            const icon = indicator.find('i');
            icon.removeClass();
            icon.addClass(`ph ${status.status_icon} f-s-8`);
            
            // Aggiorna il tooltip (solo l'attributo title, senza inizializzare Bootstrap tooltip)
            indicator.attr('title', status.last_seen);
            
            // Aggiorna anche l'attributo data-status
            indicator.attr('data-status', status.status_display);
            
            console.log(`Indicatore aggiornato per utente ${userId}: ${status.status_color} - ${status.status_icon}`);
            console.log(`Classi attuali dell'indicatore:`, indicator.attr('class'));
        } else {
            console.log(`Indicatore non trovato per utente ${userId}`);
        }
        
        // AGGIORNA ANCHE L'HEADER DELLA CHAT SE L'UTENTE È QUELLO SELEZIONATO
        const currentChatUserId = $('.chat-contactbox.active').data('user-id');
        if (currentChatUserId && currentChatUserId == userId) {
            console.log(`Aggiornamento header chat per utente ${userId}`);
            
            // Determina il testo e colore dello stato basandosi sul status_display
            let statusText, statusClass, indicatorClass;
            
            switch(status.status_display) {
                case 'Online':
                    statusText = '{{ __("chat.online") }}';
                    statusClass = 'text-success';
                    indicatorClass = 'bg-success';
                    break;
                case 'Assente':
                    statusText = 'Assente';
                    statusClass = 'text-warning';
                    indicatorClass = 'bg-warning';
                    break;
                case 'Occupato':
                    statusText = 'Occupato';
                    statusClass = 'text-danger';
                    indicatorClass = 'bg-danger';
                    break;
                case 'Invisibile':
                case 'Offline':
                    statusText = '{{ __("chat.offline") }}';
                    statusClass = 'text-muted';
                    indicatorClass = 'bg-secondary';
                    break;
                default:
                    statusText = status.is_online ? '{{ __("chat.online") }}' : '{{ __("chat.offline") }}';
                    statusClass = status.is_online ? 'text-success' : 'text-muted';
                    indicatorClass = status.is_online ? 'bg-success' : 'bg-secondary';
            }
            
            $('#chatHeaderStatus')
                .text(statusText)
                .removeClass('text-success text-warning text-danger text-muted')
                .addClass(statusClass);
            
            // Aggiorna il pallino nell'header
            $('#chatHeaderIndicator')
                .removeClass('bg-success bg-warning bg-danger bg-secondary')
                .addClass(indicatorClass);
        }
    });
}

// Gestione cambio di stato online
function changeOnlineStatus(status) {
    updateUserOnlineStatus(status);
    
    // Aggiorna l'interfaccia utente
    $('.online-status-dropdown .dropdown-item').removeClass('active');
    $(`.online-status-dropdown .dropdown-item[data-status="${status}"]`).addClass('active');
    
    // Aggiorna l'indicatore del proprio stato
    const statusColors = {
        'online': 'success',
        'away': 'warning', 
        'busy': 'danger',
        'invisible': 'secondary'
    };
    
    const statusIcons = {
        'online': 'ph-circle-fill',
        'away': 'ph-clock',
        'busy': 'ph-minus-circle', 
        'invisible': 'ph-circle'
    };
    
    const color = statusColors[status] || 'secondary';
    const icon = statusIcons[status] || 'ph-circle';
    
    // Aggiorna l'indicatore nella sidebar
    const ownIndicator = $('.online-status-indicator[data-user-id="{{ auth()->id() }}"]');
    if (ownIndicator.length > 0) {
        // Aggiorna le classi
        ownIndicator
            .removeClass('bg-success bg-warning bg-danger bg-secondary')
            .addClass(`bg-${color}`)
            .find('i')
            .removeClass()
            .addClass(`ph ${icon} f-s-8`);
    }
    
    // Aggiorna l'icona nel dropdown
    $('.dropdown-toggle i')
        .removeClass()
        .addClass(`ph ${icon} fs-5 text-${color}`);
    
    // AGGIORNA ANCHE L'HEADER SE L'UTENTE STA GUARDANDO LA PROPRIA CHAT
    const currentChatUserId = $('.chat-contactbox.active').data('user-id');
    const currentUserId = {{ auth()->id() }};
    
    if (currentChatUserId && currentChatUserId == currentUserId) {
        console.log('Aggiornamento header per il proprio stato');
        
        const statusText = status === 'invisible' ? '{{ __("chat.offline") }}' : '{{ __("chat.online") }}';
        const statusClass = status === 'invisible' ? 'text-muted' : 'text-success';
        const indicatorClass = status === 'invisible' ? 'bg-secondary' : `bg-${color}`;
        
        $('#chatHeaderStatus')
            .text(statusText)
            .removeClass('text-success text-muted')
            .addClass(statusClass);
        
        $('#chatHeaderIndicator')
            .removeClass('bg-success bg-warning bg-danger bg-secondary')
            .addClass(indicatorClass);
    }
    
    // Mostra notifica
    showNotification(`Stato cambiato in: ${status}`, 'success');
}



// Laravel Reverb Client globale
let reverbClient = null;

// Inizializza quando il documento è pronto
$(document).ready(function() {
    console.log('Inizializzazione sistema chat in tempo reale...');
    
    // Inizializza Reverb client
    initReverbClient();
    
    // Inizializza sistema stato online
    initOnlineStatus();
    
    // Inizializza aggiornamento badge messaggi non letti
    initUnreadMessagesBadge();
    
    // Verifica compatibilità WebRTC e disabilita pulsanti se necessario
    setTimeout(checkWebRTCCompatibility, 1000);
    
    // Gestione cambio di stato online
    $('.dropdown-item[data-status]').click(function(e) {
        e.preventDefault();
        const status = $(this).data('status');
        changeOnlineStatus(status);
    });
    
    // Gestione eventi di visibilità della pagina
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            updateUserOnlineStatus('away');
        } else {
            updateUserOnlineStatus('online');
        }
    });
    
    // Gestione chiusura della pagina
    window.addEventListener('beforeunload', function() {
        updateUserOnlineStatus('offline');
    });
});

// Test manuale dell'API
function testOnlineStatusAPI() {
    const userIds = [];
    $('.online-status-indicator').each(function() {
        userIds.push($(this).data('user-id'));
    });
    
    if (userIds.length > 0) {
        console.log('Test API per utenti:', userIds);
        
        fetch('{{ route("online-status.multiple-users-status") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({ user_ids: userIds })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Test API risposta:', data);
            if (data.success) {
                updateOnlineStatusIndicators(data.statuses);
                
                // Test manuale per verificare che le classi vengano applicate
                setTimeout(() => {
                    $('.online-status-indicator').each(function() {
                        const userId = $(this).data('user-id');
                        const classes = $(this).attr('class');
                        console.log(`Utente ${userId} - Classi attuali:`, classes);
                        
                        // Test visivo: cambia temporaneamente il colore per verificare
                        if (classes.includes('bg-secondary')) {
                            $(this).css('background-color', 'red !important');
                            setTimeout(() => {
                                $(this).css('background-color', '');
                            }, 2000);
                        }
                    });
                }, 1000);
            }
        })
        .catch(error => {
            console.error('Test API errore:', error);
        });
    }
}

// Pulisci gli intervalli quando si lascia la pagina
window.addEventListener('beforeunload', function() {
    if (onlineStatusInterval) {
        clearInterval(onlineStatusInterval);
    }
    if (lastSeenInterval) {
        clearInterval(lastSeenInterval);
    }
    if (unreadMessagesInterval) {
        clearInterval(unreadMessagesInterval);
    }
});

// Gestione pulsanti header chat
$(document).ready(function() {
    // Pulsante chiamata
    $('.btn[data-bs-target="#exampleModal"]').click(function() {
        if (!currentChatId) {
            showNotification('Seleziona prima una chat per effettuare una chiamata', 'warning');
            return false;
        }
        console.log('Avvio chiamata per chat:', currentChatId);
        // Qui implementeremo la logica per la chiamata
    });
    
    // Pulsante videochiamata
    $('.btn[data-bs-target="#exampleModal1"]').click(function() {
        if (!currentChatId) {
            showNotification('Seleziona prima una chat per effettuare una videochiamata', 'warning');
            return false;
        }
        console.log('Avvio videochiamata per chat:', currentChatId);
        // Qui implementeremo la logica per la videochiamata
    });
    
    // Pulsante impostazioni
    $('.btn[data-bs-toggle="dropdown"]').click(function() {
        if (!currentChatId) {
            showNotification('Seleziona prima una chat per accedere alle impostazioni', 'warning');
            return false;
        }
        console.log('Apertura impostazioni per chat:', currentChatId);
    });
});

// Variabile per l'intervallo dei messaggi non letti
let unreadMessagesInterval;

// Inizializza il Reverb client
function initReverbClient() {
    console.log('Inizializzazione Reverb client...');
    
    try {
        // Crea istanza Reverb client
        reverbClient = new ReverbClient();
        
        // Configura callback per messaggi
        reverbClient.onMessage(function(data) {
            console.log('Nuovo messaggio ricevuto:', data);
            
            // Se il messaggio è per la chat corrente, aggiungilo
            if (data.chat_id == currentChatId) {
                addMessageToChat(data);
            }
            
            // Aggiorna la lista chat
            updateChatList();
            
            // Aggiorna il badge dei messaggi non letti
            updateUnreadMessagesBadge();
        });
        
        // Configura callback per stato utente
        reverbClient.onUserStatus(function(user, status) {
            console.log('Cambio stato utente:', user, status);
            
            // Aggiorna gli indicatori di stato
            updateUserStatusIndicator(user.id, status, user.online_status);
            
            // Se l'utente è nella chat corrente, aggiorna l'header
            if (currentChatId) {
                const currentChatUserId = $('.chat-contactbox.active').data('user-id');
                if (currentChatUserId == user.id) {
                    updateChatHeader(currentChatId);
                }
            }
        });
        
        // Configura callback per richieste di chiamata
        reverbClient.onCallRequest(function(data) {
            console.log('Richiesta chiamata ricevuta:', data);
            handleIncomingCall(data);
        });
        
        // Configura callback per risposte alle chiamate
        reverbClient.onCallResponse(function(data) {
            console.log('Risposta chiamata ricevuta:', data);
            handleCallResponse(data);
        });
        
        // Configura callback per segnali WebRTC
        reverbClient.onWebRTCSignal(function(data) {
            console.log('Segnale WebRTC ricevuto:', data);
            handleWebRTCSignal(data);
        });
        
        // Connette a Reverb
        reverbClient.connect();
        
    } catch (error) {
        console.error('Errore nell\'inizializzazione di Reverb:', error);
        showNotification('Errore nella connessione al sistema di chat in tempo reale', 'error');
    }
}

// Inizializza l'aggiornamento del badge dei messaggi non letti
function initUnreadMessagesBadge() {
    console.log('Inizializzazione aggiornamento badge messaggi non letti...');
    
    // Aggiorna immediatamente
    updateUnreadMessagesBadge();
    
    // Imposta intervallo per aggiornamento ogni 30 secondi
    unreadMessagesInterval = setInterval(updateUnreadMessagesBadge, 30000);
}

// Aggiorna il badge dei messaggi non letti nella sidebar
function updateUnreadMessagesBadge() {
    fetch('{{ route("online-status.unread-messages-count") }}', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const unreadCount = data.unread_count;
            const badge = $('.sidebar .badge-notification');
            
            if (unreadCount > 0) {
                // Aggiorna il badge se esiste, altrimenti crealo
                if (badge.length > 0) {
                    badge.text(unreadCount);
                } else {
                    // Crea il badge se non esiste
                    $('.sidebar a[href="{{ route("chat.index") }}"]').append(
                        `<span class="badge bg-danger badge-notification ms-2">${unreadCount}</span>`
                    );
                }
            } else {
                // Rimuovi il badge se non ci sono messaggi non letti
                badge.remove();
            }
            
            console.log(`Badge messaggi non letti aggiornato: ${unreadCount}`);
        }
    })
    .catch(error => {
        console.error('Errore aggiornamento badge messaggi non letti:', error);
    });
}

// Funzioni per gestire le chiamate e le impostazioni
function startVoiceCall() {
    if (!currentChatId) {
        showNotification('Seleziona prima una chat per effettuare una chiamata', 'warning');
        return;
    }
    
    const chatName = $('#chatHeaderName').text();
    const targetUserId = $('.chat-contactbox.active').data('user-id');
    
    if (!targetUserId) {
        showNotification('Impossibile identificare l\'utente da chiamare', 'error');
        return;
    }
    
    // Verifica compatibilità WebRTC
    if (!reverbClient || !reverbClient.isAudioCallSupported()) {
        showNotification('Le chiamate audio non sono supportate in questo browser', 'error');
        return;
    }
    
    console.log(`Avvio chiamata vocale con ${chatName} (User ID: ${targetUserId})`);
    
    // Avvia chiamata via Reverb
    if (reverbClient && reverbClient.isConnected) {
        // Mostra indicatore di caricamento
        Swal.fire({
            title: 'Avvio chiamata...',
            text: 'Richiesta accesso al microfono...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        reverbClient.startCall(targetUserId, 'audio')
            .then(success => {
                Swal.close();
                if (success) {
                    showNotification(`Chiamata in corso con ${chatName}...`, 'info');
                    showCallInterface('audio', true);
                } else {
                    showNotification('Errore nell\'avvio della chiamata', 'error');
                }
            })
            .catch(error => {
                Swal.close();
                console.error('Errore nell\'avvio della chiamata:', error);
                
                // Messaggi di errore specifici
                if (error.message.includes('NotAllowedError')) {
                    showNotification('Permesso negato per il microfono. Verifica le impostazioni del browser.', 'error');
                } else if (error.message.includes('NotFoundError')) {
                    showNotification('Microfono non trovato. Verifica che sia collegato.', 'error');
                } else if (error.message.includes('NotSupportedError')) {
                    showNotification('WebRTC non è supportato in questo browser.', 'error');
                } else {
                    showNotification('Errore nell\'avvio della chiamata: ' + error.message, 'error');
                }
            });
    } else {
        showNotification('Reverb non connesso', 'error');
    }
}

function startVideoCall() {
    if (!currentChatId) {
        showNotification('Seleziona prima una chat per effettuare una videochiamata', 'warning');
        return;
    }
    
    const chatName = $('#chatHeaderName').text();
    const targetUserId = $('.chat-contactbox.active').data('user-id');
    
    if (!targetUserId) {
        showNotification('Impossibile identificare l\'utente da chiamare', 'error');
        return;
    }
    
    // Verifica compatibilità WebRTC
    if (!reverbClient || !reverbClient.isVideoCallSupported()) {
        showNotification('Le videochiamate non sono supportate in questo browser', 'error');
        return;
    }
    
    console.log(`Avvio videochiamata con ${chatName} (User ID: ${targetUserId})`);
    
    // Avvia videochiamata via Reverb
    if (reverbClient && reverbClient.isConnected) {
        // Mostra indicatore di caricamento
        Swal.fire({
            title: 'Avvio videochiamata...',
            text: 'Richiesta accesso a microfono e camera...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        reverbClient.startCall(targetUserId, 'video')
            .then(success => {
                Swal.close();
                if (success) {
                    showNotification(`Videochiamata in corso con ${chatName}...`, 'info');
                    showCallInterface('video', true);
                } else {
                    showNotification('Errore nell\'avvio della videochiamata', 'error');
                }
            })
            .catch(error => {
                Swal.close();
                console.error('Errore nell\'avvio della videochiamata:', error);
                
                // Messaggi di errore specifici
                if (error.message.includes('NotAllowedError')) {
                    showNotification('Permesso negato per microfono/camera. Verifica le impostazioni del browser.', 'error');
                } else if (error.message.includes('NotFoundError')) {
                    showNotification('Microfono/camera non trovato. Verifica che sia collegato.', 'error');
                } else if (error.message.includes('NotSupportedError')) {
                    showNotification('WebRTC non è supportato in questo browser.', 'error');
                } else {
                    showNotification('Errore nell\'avvio della videochiamata: ' + error.message, 'error');
                }
            });
    } else {
        showNotification('Reverb non connesso', 'error');
    }
}

function openChatSettings() {
    if (!currentChatId) {
        showNotification('Seleziona prima una chat per accedere alle impostazioni', 'warning');
        return;
    }
    
    const chatName = $('#chatHeaderName').text();
    console.log(`Apertura impostazioni per ${chatName} (Chat ID: ${currentChatId})`);
    
    // Mostra modal con le impostazioni della chat
    Swal.fire({
        title: `Impostazioni Chat - ${chatName}`,
        html: `
            <div class="text-start">
                <div class="mb-3">
                    <label class="form-label">Notifiche</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="notificationsEnabled" checked>
                        <label class="form-check-label" for="notificationsEnabled">
                            Abilita notifiche
                        </label>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Suoni</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="soundsEnabled" checked>
                        <label class="form-check-label" for="soundsEnabled">
                            Abilita suoni
                        </label>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Privacy</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="readReceiptsEnabled" checked>
                        <label class="form-check-label" for="readReceiptsEnabled">
                            Conferme di lettura
                        </label>
                    </div>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Salva',
        cancelButtonText: 'Annulla',
        width: '500px'
    }).then((result) => {
        if (result.isConfirmed) {
            // Salva le impostazioni
            const settings = {
                notifications: $('#notificationsEnabled').is(':checked'),
                sounds: $('#soundsEnabled').is(':checked'),
                readReceipts: $('#readReceiptsEnabled').is(':checked')
            };
            
            console.log('Impostazioni salvate:', settings);
            showNotification('Impostazioni salvate con successo', 'success');
        }
    });
}

// Gestione chiamate in arrivo
function handleIncomingCall(data) {
    const fromUserId = data.from_user_id;
    const callType = data.call_type;
    
    // Trova il nome dell'utente che chiama
    const userName = getUserName(fromUserId);
    
    Swal.fire({
        title: `Chiamata ${callType === 'video' ? 'Video' : 'Vocale'} in arrivo`,
        text: `${userName} ti sta chiamando`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Rispondi',
        cancelButtonText: 'Rifiuta',
        showDenyButton: true,
        denyButtonText: 'Ignora'
    }).then((result) => {
        if (result.isConfirmed) {
            // Rispondi alla chiamata
            reverbClient.answerCall(fromUserId, true, data.offer).then(success => {
                if (success) {
                    showCallInterface(callType, false);
                } else {
                    showNotification('Errore nella risposta alla chiamata', 'error');
                }
            });
        } else if (result.isDenied) {
            // Ignora la chiamata
            reverbClient.answerCall(fromUserId, false);
        } else {
            // Rifiuta la chiamata
            reverbClient.answerCall(fromUserId, false);
        }
    });
}

// Gestione risposta chiamata
function handleCallResponse(data) {
    if (data.accepted) {
        showNotification('Chiamata accettata!', 'success');
        
        // Aggiorna l'interfaccia per mostrare che la chiamata è attiva
        const callType = reverbClient.currentCall ? reverbClient.currentCall.callType : 'audio';
        const isOutgoing = reverbClient.currentCall ? reverbClient.currentCall.isInitiator : false;
        
        // Mostra l'interfaccia della chiamata attiva
        showCallInterface(callType, isOutgoing);
        
        // Gestisci la connessione WebRTC
        if (data.answer) {
            reverbClient.handleWebRTCSignal({
                signal: data.answer,
                signal_type: 'answer'
            });
        }
        
        // Aggiorna il testo dell'interfaccia
        setTimeout(() => {
            $('#callInterface p').text('Chiamata in corso...');
        }, 1000);
        
    } else {
        showNotification('Chiamata rifiutata', 'info');
        hideCallInterface();
        
        // Pulisci le risorse WebRTC
        if (reverbClient) {
            reverbClient.endCall();
        }
    }
}

// Gestione segnali WebRTC
function handleWebRTCSignal(data) {
    reverbClient.handleWebRTCSignal(data);
}

// Mostra interfaccia chiamata
function showCallInterface(callType, isOutgoing) {
    // Rimuovi interfaccia esistente se presente
    $('#callInterface').remove();
    
    const callHtml = `
        <div id="callInterface" class="position-fixed top-0 start-0 w-100 h-100 bg-dark d-flex align-items-center justify-content-center" style="z-index: 9999;">
            <div class="text-center text-white">
                <h3>${callType === 'video' ? 'Videochiamata' : 'Chiamata Vocale'}</h3>
                <p id="callStatus">${isOutgoing ? 'Chiamata in corso...' : 'Chiamata in arrivo...'}</p>
                <div class="mt-4">
                    <button class="btn btn-danger btn-lg me-3" onclick="endCurrentCall()">
                        <i class="ph ph-phone-slash"></i> Termina
                    </button>
                    ${callType === 'video' ? '<video id="localVideo" autoplay muted class="w-25 h-auto"></video>' : ''}
                </div>
                <div class="mt-3">
                    <small class="text-muted">Durata: <span id="callDuration">00:00</span></small>
                </div>
            </div>
        </div>
    `;
    
    $('body').append(callHtml);
    
    // Mostra video locale se disponibile
    if (callType === 'video' && reverbClient && reverbClient.localStream) {
        const video = document.getElementById('localVideo');
        if (video) {
            video.srcObject = reverbClient.localStream;
        }
    }
    
    // Avvia timer per la durata della chiamata
    startCallTimer();
}

// Timer per la durata della chiamata
let callTimer = null;
let callStartTime = null;

function startCallTimer() {
    callStartTime = Date.now();
    callTimer = setInterval(() => {
        const duration = Math.floor((Date.now() - callStartTime) / 1000);
        const minutes = Math.floor(duration / 60).toString().padStart(2, '0');
        const seconds = (duration % 60).toString().padStart(2, '0');
        $('#callDuration').text(`${minutes}:${seconds}`);
    }, 1000);
}

function stopCallTimer() {
    if (callTimer) {
        clearInterval(callTimer);
        callTimer = null;
    }
}

// Aggiorna l'interfaccia della chiamata quando viene ricevuto lo stream remoto
function updateCallInterface(remoteStream) {
    console.log('Aggiornamento interfaccia chiamata con stream remoto:', remoteStream);
    
    // Aggiorna il testo dello stato
    $('#callStatus').text('Chiamata attiva');
    
    // Se è una videochiamata, mostra il video remoto
    if (remoteStream && remoteStream.getVideoTracks().length > 0) {
        const remoteVideo = document.getElementById('remoteVideo');
        if (!remoteVideo) {
            // Crea elemento video remoto se non esiste
            const videoHtml = '<video id="remoteVideo" autoplay class="w-50 h-auto ms-3"></video>';
            $('#callInterface .mt-4').append(videoHtml);
        }
        
        const video = document.getElementById('remoteVideo');
        if (video) {
            video.srcObject = remoteStream;
        }
    }
    
    // Mostra notifica di connessione stabilita
    showNotification('Connessione audio stabilita!', 'success');
    
    // Forza l'aggiornamento dell'interfaccia
    setTimeout(() => {
        if ($('#callStatus').text() === 'Chiamata in corso...') {
            $('#callStatus').text('Chiamata attiva');
        }
    }, 1000);
}

// Rendi la funzione disponibile globalmente
window.updateCallInterface = updateCallInterface;

// Nasconde interfaccia chiamata
function hideCallInterface() {
    $('#callInterface').remove();
}

// Termina chiamata corrente
function endCurrentCall() {
    // Ferma il timer della chiamata
    stopCallTimer();
    
    if (reverbClient) {
        reverbClient.endCall();
    }
    hideCallInterface();
    showNotification('Chiamata terminata', 'info');
}

// Ottieni nome utente
function getUserName(userId) {
    // Implementa la logica per ottenere il nome dell'utente
    // Per ora restituisce un valore di default
    return `Utente ${userId}`;
}

// Verifica compatibilità WebRTC
function checkWebRTCCompatibility() {
    if (!reverbClient) {
        console.warn('Reverb client non disponibile');
        return;
    }
    
    if (!reverbClient.isWebRTCSupported()) {
        console.warn('WebRTC non supportato - disabilitando pulsanti chiamate');
        
        // Disabilita pulsanti chiamate
        $('.btn-call-audio').prop('disabled', true).attr('title', 'Chiamate audio non supportate');
        $('.btn-call-video').prop('disabled', true).attr('title', 'Videochiamate non supportate');
        
        // Aggiungi classe per stile disabilitato
        $('.btn-call-audio, .btn-call-video').addClass('disabled');
    } else {
        console.log('WebRTC supportato - pulsanti chiamate abilitati');
    }
}

// Aggiungi messaggio alla chat
function addMessageToChat(message) {
    const container = $('#chatMessages');
    const messageHtml = createMessageHtml(message);
    container.append(messageHtml);
    container.scrollTop(container[0].scrollHeight);
}

// Aggiorna indicatore stato utente
function updateUserStatusIndicator(userId, status, onlineStatus) {
    const indicator = $(`.online-status-indicator[data-user-id="${userId}"]`);
    if (indicator.length > 0) {
        // Aggiorna il colore e l'icona basandosi sullo stato
        indicator.removeClass('bg-success bg-warning bg-danger bg-secondary');
        
        switch (onlineStatus) {
            case 'online':
                indicator.addClass('bg-success');
                break;
            case 'away':
                indicator.addClass('bg-warning');
                break;
            case 'busy':
                indicator.addClass('bg-danger');
                break;
            default:
                indicator.addClass('bg-secondary');
        }
    }
}

// Gestione Impostazioni Privacy
function loadPrivacySettings() {
    fetch('{{ route("online-status.get-privacy-preferences") }}', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const visibility = data.preferences.visibility || 'all';
            $(`input[name="privacy_visibility"][value="${visibility}"]`).prop('checked', true);
        }
    })
    .catch(error => {
        console.error('Errore caricamento impostazioni privacy:', error);
    });
}

function savePrivacySettings() {
    const visibility = $('input[name="privacy_visibility"]:checked').val();
    
    fetch('{{ route("online-status.privacy-preferences") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        body: JSON.stringify({ visibility: visibility })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            $('#privacySettingsModal').modal('hide');
            showNotification('Impostazioni privacy salvate con successo!', 'success');
        } else {
            showNotification('Errore nel salvataggio delle impostazioni', 'error');
        }
    })
    .catch(error => {
        console.error('Errore salvataggio impostazioni privacy:', error);
        showNotification('Errore nel salvataggio delle impostazioni', 'error');
    });
}

// Carica le impostazioni quando si apre il modal
$('#privacySettingsModal').on('show.bs.modal', function() {
    loadPrivacySettings();
});

// Funzione per mostrare notifiche
function showNotification(message, type) {
    const alert = document.createElement('div');
    alert.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
    alert.style.top = '20px';
    alert.style.right = '20px';
    alert.style.zIndex = '9999';
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alert);

    setTimeout(() => {
        alert.remove();
    }, 5000);
}
</script>
@endpush
