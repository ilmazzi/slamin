@extends('layout.master')

@section('main-content')
<div class="container-fluid p-0" style="height: calc(100vh - 100px);">
    @livewire('chat.pages.chats')
</div>
@endsection

