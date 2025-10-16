@extends('layout.master')

@section('content')
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body p-0" style="height: calc(100vh - 150px);">
                    <livewire:chat.pages.chat :conversation="$conversation" />
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

