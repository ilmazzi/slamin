@extends('layout.master')

@section('title', __('chat_general.page_title'))

@section('main-content')
<div class="chat-page-container">
    <!-- Main Chat Room - Full Screen -->
    <div class="chat-room-wrapper">
        <livewire:chat.chat-room :roomId="$roomId" />
    </div>
</div>
@endsection

@push('styles')
<style>
/* Full-screen chat layout */
.chat-page-container {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: #f8f9fa;
    z-index: 1000;
}

.chat-room-wrapper {
    width: 100%;
    height: 100vh;
    background: white;
}

/* Adjust for master layout */
.page-wrapper {
    margin-left: 0 !important;
}

@media (max-width: 768px) {
    .chat-page-container {
        top: 0;
    }
}
</style>
@endpush
