@extends('layout.master')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/chat.css') }}">
@endpush

@section('main-content')
<div class="container-fluid p-0" style="height: calc(100vh - 100px);">
    @livewire('chat.pages.chat', ['conversation' => $conversation])
</div>
@endsection

