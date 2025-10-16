@extends('layout.master')

@section('main-content')
<div class="container-fluid p-0" style="height: calc(100vh - 100px);">
    <div class="row h-100 g-0">
        <!-- Sidebar -->
        <div class="col-md-4 col-lg-3 border-end bg-light">
            <livewire:chat-simple.sidebar />
        </div>
        
        <!-- Main Chat Area -->
        <div class="col-md-8 col-lg-9">
            <livewire:chat-simple.main />
        </div>
    </div>
</div>
@endsection
