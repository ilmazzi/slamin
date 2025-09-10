
@extends('layout.master')



@section('title', __('chat.page_title'))

@section('main-content')
<meta name="current-user-id" content="{{ auth()->id() }}">

@push('scripts')
<script>


// Gestione dinamica del form chat
document.addEventListener('DOMContentLoaded', function() {
    // Funzione per aggiornare il form quando si seleziona una chat
    function updateChatForm(roomId) {
        const form = document.querySelector('[data-chat-form]');
        const input = document.querySelector('[data-chat-input]');
        const submitBtn = form?.querySelector('button[type="submit"]');

        if (form && roomId) {
            form.action = `/chat/${roomId}/messages`;
            if (input) input.disabled = false;
            if (submitBtn) submitBtn.disabled = false;
        } else if (form) {
            form.action = 'javascript:void(0)';
            if (input) input.disabled = true;
            if (submitBtn) submitBtn.disabled = true;
        }
    }

    // Aggiorna il form quando la pagina si carica
    const urlParams = new URLSearchParams(window.location.search);
    const roomId = urlParams.get('room');
    if (roomId) {
        updateChatForm(roomId);
    }

    // Marca le notifiche della chat come lette quando si carica la pagina
    markChatNotificationsAsRead(roomId);
});

// Funzione per marcare le notifiche della chat come lette
async function markChatNotificationsAsRead(chatRoomId = null) {
    try {
        const response = await fetch('/chat/notifications/mark-notifications-read', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                chat_room_id: chatRoomId
            })
        });

        if (response.ok) {
            const data = await response.json();
            console.log('Notifiche chat marcate come lette:', data);
            
            // Aggiorna il badge delle notifiche se esiste
            if (typeof notificationManager !== 'undefined') {
                notificationManager.loadNotifications(true);
            }
        }
    } catch (error) {
        console.error('Errore nel marcare le notifiche come lette:', error);
    }
}
</script>
        @endpush

        <style>
        .emoji-picker-dropdown {
            position: absolute;
            bottom: 100%;
            left: 0;
            width: 320px;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 1050;
        }

        .emoji-grid {
            display: grid;
            grid-template-columns: repeat(8, 1fr);
            gap: 4px;
        }

        .emoji-item {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 20px;
            transition: background-color 0.2s;
        }

        .emoji-item:hover {
            background-color: #f8f9fa;
        }

        .emoji-categories button {
            font-size: 16px;
            padding: 4px 8px;
        }

        .emoji-picker-search input {
            border-radius: 20px;
            border: 1px solid #dee2e6;
        }

        .emoji-picker-header {
            background-color: #f8f9fa;
            border-radius: 8px 8px 0 0;
        }
        </style>

<div class="row position-relative chat-container-box">
    <div class="col-lg-4 col-xxl-3  box-col-5">
        <div class="chat-div">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                <span class="chatdp h-45 w-45 d-flex-center b-r-50 position-relative bg-danger">
                    {!! getUserAvatarHtml(auth()->user(), 'h-45 w-45', 'b-r-50') !!}

                                         <span
                                class="position-absolute top-0 end-0 p-1 {{ auth()->user()->presence_class }} border border-light rounded-circle"
                                title="{{ auth()->user()->presence_label }}">
                </span>
                        </span>



                        <div class="flex-grow-1 ps-2">
                            <div class="fs-6"> {{ auth()->user()->name }}</div>
                            <div class="text-muted f-s-12"> {{ auth()->user()->nickname }}</div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <!-- Pulsante + sempre visibile (mobile e desktop) -->
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
                            
                            <div class="btn-group dropdown-icon-none">
                                <a role="button" data-bs-placement="top" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
                                    <i class="ti ti-settings fs-5"></i>
                                </a>
                                <ul class="dropdown-menu" data-popper-placement="bottom-start">
                                    <li><a class="dropdown-item" href="#"><i class="ti ti-brand-hipchat"></i> <span
                                        class="f-s-13">{{ __('chat.chat_settings') }}</span></a>
                                    <li><a class="dropdown-item" href="#"><i class="ti ti-brand-hipchat"></i> <span
                                                class="f-s-13">{{ __('chat.chat_settings') }}</span></a>
                                    </li>
                                    <li><a class="dropdown-item" href="#"><i class="ti ti-phone-call"></i> <span
                                                class="f-s-13">{{ __('chat.contact_settings') }}</span></a>
                                    </li>
                                    <li><a class="dropdown-item" href="#"><i class="ti ti-settings"></i> <span
                                                class="f-s-13">{{ __('chat.settings') }}</span></a>
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
                            <li class="tab-link active" data-tab="1"><i class="ph-fill  ph-chat-circle-text f-s-18 me-2"></i>{{ __('chat.title') }}</li>
                            <li class="tab-link" data-tab="2"><i class="ph-fill  ph-wechat-logo f-s-18 me-2"></i>{{ __('chat.updates') }}</li>
                            <li class="tab-link" data-tab="3"><i class="ph-fill  ph-phone-call f-s-18 me-2"></i>{{ __('chat.contact') }}</li>
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
                                                    tabindex="-1"><i class="ph-fill  ph-lock-key-open me-2"></i>{{ __('chat.private') }}</button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="groups-tab" data-bs-toggle="tab"
                                                    data-bs-target="#groups-tab-pane" type="button" role="tab"
                                                    aria-controls="groups-tab-pane" aria-selected="false" tabindex="-1"><i class="ph-fill  ph-users-three me-2"></i>{{ __('chat.group') }}</button>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="BasicContent">
                                        <!-- Private Chat -->
                                        <div class="tab-pane fade show active" id="private-tab-pane" role="tabpanel"
                                             aria-labelledby="private-tab" tabindex="0">

                                            <div class="chat-contact">
                                                                @forelse($contacts as $contact)
                <a href="{{ route('chat.index', ['room' => $contact['chat_room_id']]) }}" class="text-decoration-none" onclick="markChatNotificationsAsRead({{ $contact['chat_room_id'] }})">
                <div class="chat-contactbox" data-chat-room="{{ $contact['chat_room_id'] }}">
                                                    <div class="position-absolute">
                                                        @php $user = \App\Models\User::find($contact['id']); @endphp
                                                        <span class="h-45 w-45 d-flex-center b-r-10 position-relative m-auto"
                                                              data-user-id="{{ $user->id }}">
                                                            <img alt="avatar" class="img-fluid b-r-10"
                                                                 src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($user) }}">
                                                            <span class="position-absolute top-0 end-0 p-1 border border-light rounded-circle bg-secondary"
                                                                  data-presence-dot></span>
                                </span>
                                                    </div>
                                                    <div class="flex-grow-1 text-start mg-s-50">
                                                        <p class="mb-0 f-w-500 text-dark txt-ellipsis-1">{{ $contact['name'] }}</p>
                                                        <p class="text-secondary mb-0 f-s-12 mb-0 chat-message">
                                                            <i class="ti ti-checks"></i> {{ $contact['last_message'] ?: __('chat.no_message') }}
                                                        </p>
                                                        <!-- Typing indicator -->
                                                        <div class="typing-indicator-contact d-none" data-room-id="{{ $contact['chat_room_id'] }}">
                                                            <small class="text-info">
                                                                <i class="ti ti-pencil me-1"></i>{{ __('chat.typing') }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <p class="f-s-12 chat-time me-2">{{ $contact['last_message_time'] ?: '--' }}</p>
                                                        <!-- Badge individuale per questa chat -->
                                                        @php
                                                            $unreadCount = \App\Models\Notification::where('user_id', auth()->id())
                                                                ->where('type', \App\Models\Notification::TYPE_CHAT_MESSAGE)
                                                                ->whereJsonContains('data->chat_room_id', $contact['chat_room_id'])
                                                                ->where('is_read', false)
                                                                ->count();
                                                        @endphp
                                                                                                                @if($unreadCount > 0)
                                                            <span class="chat-individual-badge badge bg-danger badge-sm"
                                                                  style="font-size: 10px; padding: 2px 6px; border-radius: 10px; display: inline-block !important; position: relative; z-index: 10;">
                                                                {{ $unreadCount }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                </a>
                                                @empty
                                                <div class="text-center py-4">
                                                    <p class="text-muted">{{ __('chat.no_contacts_found') }}</p>
                                                    </div>
                                                @endforelse
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
                                        <div class="d-flex justify-content-end mt-3">
                                            <div class="btn-group dropup dropdown-icon-none">
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

                            <div class="d-flex justify-content-end mt-3">
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
                            <div class="d-flex justify-content-end mt-3">
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
        <div class="card chat-container-content-box" data-chat-room="{{ $selectedRoom?->id }}">
            <div class="card-header bg-white border-bottom" style="z-index: 1020;">
                <div class="chat-header d-flex align-items-center">
                    <div class="d-lg-none">
                        <a class="me-3 toggle-btn" role="button" data-bs-toggle="offcanvas" data-bs-target="#chatListOffcanvas" aria-controls="chatListOffcanvas">
                            <i class="ti ti-align-justified"></i>
                        </a>
                    </div>
                    @if($selectedContact)
                        @php $user = \App\Models\User::find($selectedContact['id']); @endphp
                        <span class="h-45 w-45 d-flex-center b-r-10 position-relative m-auto"
                              data-user-id="{{ $user->id }}">
                            <img alt="avatar" class="img-fluid b-r-10"
                                 src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($user) }}">
                            <span class="position-absolute top-0 end-0 p-1 border border-light rounded-circle bg-secondary"
                                  data-presence-dot></span>
                        </span>
                    @else
                <span class="profileimg h-45 w-45 d-flex-center b-r-50 position-relative">
                            <i class="ti ti-user f-s-20 text-muted"></i>
                </span>
                    @endif
                    <div class="flex-grow-1 ps-2 pe-2">
                        @if($selectedContact)
                            <div class="fs-6">{{ $selectedContact['name'] }}</div>
                            <div class="text-muted f-s-12" data-presence-label data-user-id="{{ $selectedContact['id'] }}">
                                {{ ucfirst($selectedContact['status']) }}
                            </div>
                            <!-- Typing indicator in header -->
                            <div class="typing-indicator-header d-none" data-room-id="{{ $selectedRoom?->id }}">
                                <small class="text-info">
                                    <i class="ti ti-pencil me-1"></i>sta scrivendo...
                                </small>
                            </div>
                        @else
                            <div class="fs-6">Seleziona una chat</div>
                            <div class="text-muted f-s-12">Clicca su un contatto per iniziare</div>
                        @endif
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
                    @if($selectedRoom && count($messages) > 0)
                        @php $currentDate = ''; @endphp
                        @foreach($messages as $message)
                            @if($message['date'] !== $currentDate)
                                @php $currentDate = $message['date']; @endphp
                    <div class="text-center">
                                    <span class="badge text-light-secondary">{{ $message['date'] }}</span>
                    </div>
                            @endif

                            @if($message['is_own'])
                    <div class="position-relative">
                        <div class="chat-box-right d-flex flex-column align-items-end" style="margin-right: 60px;">
                            <div class="chat-text bg-primary text-white p-3 rounded-3 mb-2 shadow-sm" style="max-width: 85%;">
                                {{ $message['content'] }}
                            </div>
                            <p class="text-muted f-s-12"><i class="ti ti-checks text-primary"></i> {{ $message['time'] }}</p>
                        </div>
                        <div class="chatdp h-45 w-45 b-r-50 position-absolute end-0 top-0 bg-danger">
                            @php $user = \App\Models\User::find($message['sender_id']); @endphp
                            <span class="h-45 w-45 d-flex-center b-r-10 position-relative m-auto"
                                  data-user-id="{{ $user->id }}">
                                <img alt="avatar" class="img-fluid b-r-10"
                                     src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($user) }}">
                                <span class="position-absolute top-0 end-0 p-1 border border-light rounded-circle bg-secondary"
                                      data-presence-dot></span>
                            </span>
                        </div>
                    </div>
                        @else
                    <div class="position-relative">
                        <div class="chatdp h-45 w-45 b-r-50 position-absolute start-0 bg-light">
                            @php $user = \App\Models\User::find($message['sender_id']); @endphp
                            <span class="h-45 w-45 d-flex-center b-r-10 position-relative m-auto"
                                  data-user-id="{{ $user->id }}">
                                <img alt="avatar" class="img-fluid b-r-10"
                                     src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($user) }}">
                                <span class="position-absolute top-0 end-0 p-1 border border-light rounded-circle bg-secondary"
                                      data-presence-dot></span>
                            </span>
                        </div>
                        <div class="chat-box d-flex flex-column align-items-start" style="margin-left: 60px;">
                            <div class="chat-text bg-light text-dark p-3 rounded-3 mb-2 shadow-sm border" style="max-width: 85%;">
                                {{ $message['content'] }}
                            </div>
                            <p class="text-muted f-s-12"><i class="ti ti-checks text-primary"></i> {{ $message['time'] }}</p>
                        </div>
                    </div>
                        @endif
                        @endforeach
                    @elseif($selectedRoom)
                        <div class="text-center py-4">
                            <p class="text-muted">Nessun messaggio ancora</p>
                            <p class="text-muted f-s-12">Inizia la conversazione!</p>
                            </div>
                    @else
                        <div class="text-center py-4">
                            <p class="text-muted">Seleziona una chat per iniziare</p>
                        </div>
                    @endif
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
                @if(!$selectedRoom)
                    <div class="text-center text-muted py-3">
                        <i class="ti ti-message-circle f-s-18 me-2"></i>
                        Seleziona una chat per iniziare a scrivere
                    </div>
                @else
                <form class="chat-footer d-flex" data-chat-form action="{{ route('chat.store', $selectedRoom->id) }}" method="POST">
                    @csrf
                    <div class="app-form flex-grow-1">
                        <div class="input-group">
                            <span class="input-group-text bg-secondary ms-2 me-2 b-r-10 position-relative">
                                <a class="emoji-btn d-flex-center" id="chat-emoji-btn" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Emoji" role="button">
                                  <i class="ti ti-mood-smile f-s-18"></i>
                                </a>

                                <!-- Emoji Picker Dropdown -->
                                <div class="emoji-picker-dropdown d-none" id="emoji-picker-chat-emoji-btn">
                                    <div class="emoji-picker-header d-flex justify-content-between align-items-center p-2 border-bottom">
                                        <span class="f-s-14 f-w-500">Emoji</span>
                                        <button type="button" class="btn-close btn-close-sm" onclick="closeEmojiPicker('chat-emoji-btn')"></button>
                                    </div>

                                    <div class="emoji-picker-search p-2">
                                        <input type="text" class="form-control form-control-sm" placeholder="Cerca emoji..." onkeyup="searchEmojis(this.value, 'chat-emoji-btn')">
                                    </div>

                                    <div class="emoji-picker-content p-2" style="max-height: 300px; overflow-y: auto;">
                                        <div class="emoji-categories mb-2">
                                            <button class="btn btn-sm btn-outline-primary me-1" onclick="showEmojiCategory('smileys', 'chat-emoji-btn')">😊</button>
                                            <button class="btn btn-sm btn-outline-primary me-1" onclick="showEmojiCategory('animals', 'chat-emoji-btn')">🐶</button>
                                            <button class="btn btn-sm btn-outline-primary me-1" onclick="showEmojiCategory('food', 'chat-emoji-btn')">🍕</button>
                                            <button class="btn btn-sm btn-outline-primary me-1" onclick="showEmojiCategory('activities', 'chat-emoji-btn')">⚽</button>
                                            <button class="btn btn-sm btn-outline-primary me-1" onclick="showEmojiCategory('travel', 'chat-emoji-btn')">✈️</button>
                                            <button class="btn btn-sm btn-outline-primary me-1" onclick="showEmojiCategory('objects', 'chat-emoji-btn')">💡</button>
                                            <button class="btn btn-sm btn-outline-primary me-1" onclick="showEmojiCategory('symbols', 'chat-emoji-btn')">❤️</button>
                                            <button class="btn btn-sm btn-outline-primary me-1" onclick="showEmojiCategory('flags', 'chat-emoji-btn')">🏁</button>
                                        </div>

                                        <div class="emoji-grid" id="emoji-grid-chat-emoji-btn">
                                            <!-- Le emoji verranno caricate qui dinamicamente -->
                                        </div>
                                    </div>
                                </div>
                            </span>
                            <input type="text" class="form-control b-r-6" placeholder="Type a message" aria-label="message" data-chat-input name="content" maxlength="1000" oninput="updateCharCount(this)">
                            <small class="text-muted char-count" style="position: absolute; bottom: -20px; right: 0; font-size: 11px;">0/1000</small>



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
                @endif
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
                @forelse($contacts as $contact)
                <div class="chat-contactbox p-2 border-bottom contact-item"
                     data-contact-id="{{ $contact['id'] }}"
                     data-chat-room="{{ $contact['chat_room_id'] }}"
                     data-contact-name="{{ $contact['name'] }}"
                     style="cursor: pointer;">
                    <div class="d-flex align-items-center">
                        <div class="position-relative me-3">
                            @php $user = \App\Models\User::find($contact['id']); @endphp
                            <span class="h-40 w-40 d-flex-center b-r-10 position-relative" data-user-id="{{ $user->id }}">
                                <img alt="avatar" class="img-fluid b-r-10" src="{{ \App\Helpers\AvatarHelper::getUserAvatarUrl($user) }}">
                                <span class="position-absolute top-0 end-0 p-1 border border-light rounded-circle bg-secondary" data-presence-dot></span>
                            </span>
                        </div>
                        <div class="flex-grow-1">
                            <p class="mb-1 f-w-500 text-dark">{{ $contact['name'] }}</p>
                            <p class="text-secondary mb-0 f-s-12">
                                <i class="ti ti-checks"></i> {{ $contact['last_message'] ?: 'Nessun messaggio' }}
                            </p>
                            <!-- Typing indicator mobile -->
                            <div class="typing-indicator-contact d-none" data-room-id="{{ $contact['chat_room_id'] }}">
                                <small class="text-info">
                                    <i class="ti ti-pencil me-1"></i>sta scrivendo...
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4">
                    <i class="ti ti-message-circle fs-1 text-muted"></i>
                    <p class="text-muted mt-2">Nessun contatto disponibile</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@endsection

<style>
/* CSS minimo per header e footer fissi su mobile */
@media (max-width: 991.98px) {
    .card-header {
        position: fixed !important;
        top: 60px;
        left: 0;
        right: 0;
        z-index: 1020;
        background: white;
        border-bottom: 1px solid #dee2e6;
        padding: 15px;
    }

    .card-footer {
        position: fixed !important;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 1010;
        background: white;
        border-top: 1px solid #dee2e6;
        padding: 15px;
    }

    /* Prevenire zoom su input focus */
    input, textarea {
        font-size: 16px !important;
        transform: scale(1) !important;
        transform-origin: left top !important;
    }

    /* Layout mobile ottimizzato */
    .chat-container-content-box {
        height: 100vh;
        overflow: hidden;
    }

    /* Prevenire spostamenti durante la digitazione */
    .chat-footer form {
        position: relative;
        z-index: 1011;
    }

    .chat-footer input {
        background: white;
        border: 1px solid #dee2e6;
        border-radius: 20px;
        padding: 10px 15px;
    }

    /* Nascondi completamente i pulsanti + originali in fondo su mobile */
    .d-flex.justify-content-end.mt-3 {
        display: none !important;
    }

    /* Pulsante + nell'header sempre visibile su mobile */
    .d-flex.align-items-center.gap-2 .btn-group.dropdown-icon-none .btn {
        min-width: 40px !important;
        min-height: 40px !important;
        border-radius: 8px !important;
        font-size: 18px !important;
    }
}

/* Miglioramenti per desktop - pulsanti + sempre visibili */
@media (min-width: 992px) {
    /* Assicura che i pulsanti + siano sempre visibili su desktop */
    .d-flex.justify-content-end.mt-3 {
        position: sticky !important;
        bottom: 10px !important;
        z-index: 100 !important;
        background: rgba(255, 255, 255, 0.95) !important;
        backdrop-filter: blur(10px) !important;
        border-radius: 10px !important;
        padding: 10px 0 !important;
        margin: 10px 0 !important;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1) !important;
    }

    .d-flex.justify-content-end.mt-3 .btn-group.dropdown-icon-none .btn {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15) !important;
        border: 2px solid white !important;
        min-width: 45px !important;
        min-height: 45px !important;
    }

    /* Assicura che il contenuto sia scrollabile */
    .chat-contact {
        padding-bottom: 60px !important;
    }

    .content-wrapper {
        padding-bottom: 20px !important;
    }

    /* Pulsante + nell'header su desktop */
    .d-flex.align-items-center.gap-2 .btn-group.dropdown-icon-none .btn {
        min-width: 35px !important;
        min-height: 35px !important;
        border-radius: 6px !important;
        font-size: 16px !important;
    }

    /* Migliora la visibilità del dropdown */
    .dropdown-menu {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
        border: none !important;
        border-radius: 10px !important;
        min-width: 200px !important;
    }

    /* Assicura che il contenuto sia scrollabile */
    .chat-contact {
        padding-bottom: 80px !important;
    }

    .content-wrapper {
        padding-bottom: 20px !important;
    }
}
</style>

@push('scripts')
<script>


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
      

        if (!this.chatInput) {
            console.error('TypingManager.init() - Chat input not found!');
            return;
        }


        // Event listeners per input
        this.chatInput.addEventListener('input', () => this.handleInput());
        this.chatInput.addEventListener('keydown', (e) => this.handleKeyDown(e));
        this.chatInput.addEventListener('blur', () => this.stopTyping());

        // Ottieni room ID corrente
        this.currentRoom = this.getCurrentRoomId();
    

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





        return roomId;
    }

    handleInput() {
        

        if (!this.currentRoom) {
            
            return;
        }

        if (!this.isTyping) {
            
            this.startTyping();
        } else {
            
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
        

        if (!this.currentRoom) {
            
            return;
        }

        try {
            const url = `/chat/${this.currentRoom}/typing/${action}`;
            

            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            

            if (!response.ok) {
                
            } else {
                
            }
        } catch (error) {
            
        }
    }

    listenToTypingEvents() {

        

        if (!window.Echo || !this.currentRoom) {
            console.error('TypingManager.listenToTypingEvents() - Echo or room ID missing');
            if (!window.Echo) console.error('TypingManager.listenToTypingEvents() - Echo not available');
            if (!this.currentRoom) console.error('TypingManager.listenToTypingEvents() - Room ID missing');
            return;
        }

        

        // Ascolta canale privato per la room
        const channelName = `chat.room.${this.currentRoom}`;
        

        const channel = window.Echo.private(channelName);
        

        channel
            .subscribed(() => {
                
            })
            .error((err) => {
                
            })
            .listen('.typing.started', (e) => {
                
                this.handleTypingStarted(e);
            })
            .listen('.typing.stopped', (e) => {
                
                this.handleTypingStopped(e);
            });

        
    }

    handleTypingStarted(event) {
        

        if (event.user_id === {{ auth()->id() }}) {
            
            return; // Ignora i propri eventi
        }

        const typingUsers = event.typing_users;
        const currentUserNames = Object.values(typingUsers);




        if (currentUserNames.length > 0) {
            this.showTypingIndicator(currentUserNames);
        }
    }

    handleTypingStopped(event) {
        
        
        if (event.user_id === {{ auth()->id() }}) {
            
            return; // Ignora i propri eventi
        }

        const typingUsers = event.typing_users;

        if (Object.keys(typingUsers).length === 0) {
            this.hideTypingIndicator();
        } else {
            const currentUserNames = Object.values(typingUsers);
            this.showTypingIndicator(currentUserNames);
        }
    }

    showTypingIndicator(userNames) {


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


            this.typingText.textContent = text;
            this.typingIndicator.classList.remove('d-none');

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


    // Inizializza typing manager
    const chatInput = document.querySelector('[data-chat-input]');


    if (chatInput) {

        typingManager = new TypingManager();

    } else {
        
    }

    // Verifica che l'offcanvas sia presente
    const offcanvas = document.getElementById('chatListOffcanvas');
    if (offcanvas) {

    } else {
        
    }

    

        // Gestione click sui contatti nell'offcanvas
    const contactItems = document.querySelectorAll('#chatListOffcanvas .contact-item');
    contactItems.forEach(item => {
        item.addEventListener('click', function() {
            const contactId = this.getAttribute('data-contact-id');
            const chatRoom = this.getAttribute('data-chat-room');
            const contactName = this.getAttribute('data-contact-name');



            // Chiudi l'offcanvas
            const offcanvasInstance = bootstrap.Offcanvas.getInstance(document.getElementById('chatListOffcanvas'));
            if (offcanvasInstance) {
                offcanvasInstance.hide();
            }

            // Replica esattamente il comportamento desktop: redirect completo
            const chatUrl = `{{ route('chat.index') }}?room=${chatRoom}`;

            window.location.href = chatUrl;
        });
    });

    // Listener per cambiamenti di URL (utile per mobile)
    if (typingManager) {
        // Controlla se l'URL è cambiato e aggiorna la stanza corrente
        const checkUrlChange = () => {
            const currentRoomId = typingManager.getCurrentRoomId();
            if (currentRoomId && currentRoomId !== typingManager.currentRoom) {

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

// Funzioni globali per le reazioni ai messaggi
function toggleReaction(messageId, emoji) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch(`/chat/reactions/toggle`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            message_id: messageId,
            reaction: emoji
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Aggiorna la visualizzazione delle reazioni
            updateReactionsDisplay(messageId, data.reactions);
        }
    })
    .catch(error => {
        console.error('Errore durante il toggle della reazione:', error);
    });
}

function addReaction(messageId, emoji) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch(`/chat/reactions/add`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            message_id: messageId,
            reaction: emoji
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Aggiorna la visualizzazione delle reazioni
            updateReactionsDisplay(messageId, data.reactions);
            // Nasconde l'emoji picker
            hideReactionPicker(messageId);
        }
    })
    .catch(error => {
        console.error('Errore durante l\'aggiunta della reazione:', error);
    });
}

function updateReactionsDisplay(messageId, reactions) {
    const reactionsDisplay = document.querySelector(`[data-reactions-display="${messageId}"]`);
    if (!reactionsDisplay) return;

    reactionsDisplay.innerHTML = '';

    if (reactions && reactions.length > 0) {
        reactions.forEach(reaction => {
            const reactionElement = document.createElement('div');
            reactionElement.className = 'reaction-item';
            reactionElement.onclick = () => toggleReaction(messageId, reaction.emoji);
            reactionElement.title = reaction.users.map(user => user.name).join(', ');

            reactionElement.innerHTML = `
                <span class="reaction-emoji">${reaction.emoji}</span>
                <span class="reaction-count">${reaction.count}</span>
            `;

            reactionsDisplay.appendChild(reactionElement);
        });
    }
}

function toggleReactionPicker(messageId) {
    const picker = document.getElementById(`emoji-picker-${messageId}`);
    if (picker) {
        picker.classList.toggle('d-none');
    }
}

function hideReactionPicker(messageId) {
    const picker = document.getElementById(`emoji-picker-${messageId}`);
    if (picker) {
        picker.classList.add('d-none');
    }
}

// Funzioni per l'emoji picker
function closeEmojiPicker(buttonId) {
    const picker = document.getElementById(`emoji-picker-${buttonId}`);
    if (picker) {
        picker.classList.add('d-none');
    }
}

function searchEmojis(query, buttonId) {
    // Implementazione della ricerca emoji
    
}

function showEmojiCategory(category, buttonId) {
    // Implementazione della visualizzazione categoria emoji
    
}

function updateCharCount(input) {
    const maxLength = 1000;
    const currentLength = input.value.length;
    const charCount = input.parentNode.querySelector('.char-count');
    if (charCount) {
        charCount.textContent = `${currentLength}/${maxLength}`;
    }
}

</script>

<style>
/* Stili per le reazioni ai messaggi */
.reaction-item {
    display: inline-flex;
    align-items: center;
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 12px;
    padding: 2px 6px;
    margin: 2px;
    cursor: pointer;
    font-size: 12px;
    transition: background-color 0.2s;
}

.reaction-item:hover {
    background: #e9ecef;
}

.reaction-emoji {
    margin-right: 4px;
}

.reaction-count {
    font-weight: 500;
    color: #6c757d;
}

/* Stili per l'indicatore di digitazione */
.typing-dots {
    display: flex;
    align-items: center;
}

.typing-dot {
    width: 4px;
    height: 4px;
    border-radius: 50%;
    background-color: #6c757d;
    margin: 0 1px;
    animation: typing 1.4s infinite ease-in-out;
}

.typing-dot:nth-child(1) { animation-delay: -0.32s; }
.typing-dot:nth-child(2) { animation-delay: -0.16s; }

@keyframes typing {
    0%, 80%, 100% {
        transform: scale(0.8);
        opacity: 0.5;
    }
    40% {
        transform: scale(1);
        opacity: 1;
    }
}
</style>

@endpush
