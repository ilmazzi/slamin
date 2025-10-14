@extends('layout.master')

@section('title', 'Chat')

@push('styles')
    @wirechatStyles
@endpush

@section('main-content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="h-[calc(100vh_-_20rem)]">
                @yield('content', $slot ?? '')
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    @wirechatAssets(panel: 'chats')
@endpush

