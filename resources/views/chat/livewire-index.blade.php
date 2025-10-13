@extends('layout.master')

@section('title', __('chat.page_title'))

@section('main-content')
<div class="container-fluid">
    <div class="row">
        <!-- Chat List Sidebar -->
        <div class="col-md-4 col-lg-3">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="ti ti-message-circle me-2"></i>
                        {{ __('chat.title') }}
                    </h5>
                </div>
                <div class="card-body p-0">
                    <!-- Search Component -->
                    <div class="p-3 border-bottom">
                        <livewire:chat.chat-search />
                    </div>
                    
                    <!-- Chat List -->
                    <div class="chat-list" style="height: calc(100vh - 200px); overflow-y: auto;">
                        <!-- This would contain the list of chat rooms -->
                        <div class="p-3 text-center text-muted">
                            <i class="ti ti-message-circle f-s-48 mb-3"></i>
                            <p>{{ __('chat.select_chat_description') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Main Chat Area -->
        <div class="col-md-8 col-lg-9">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center justify-content-center">
                    <div class="text-center text-muted">
                        <i class="ti ti-message-circle f-s-64 mb-3"></i>
                        <h4>{{ __('chat.select_chat') }}</h4>
                        <p>{{ __('chat.select_chat_description') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.chat-list {
    scrollbar-width: thin;
    scrollbar-color: #dee2e6 #f8f9fa;
}

.chat-list::-webkit-scrollbar {
    width: 6px;
}

.chat-list::-webkit-scrollbar-track {
    background: #f8f9fa;
    border-radius: 3px;
}

.chat-list::-webkit-scrollbar-thumb {
    background: #dee2e6;
    border-radius: 3px;
}

.chat-list::-webkit-scrollbar-thumb:hover {
    background: #adb5bd;
}

/* Mobile optimization */
@media (max-width: 768px) {
    .col-md-4 {
        margin-bottom: 1rem;
    }
    
    .chat-list {
        height: calc(50vh - 100px) !important;
    }
}
</style>
@endpush
