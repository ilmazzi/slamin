@extends('layout.master')

@section('title', 'Test Logs - Admin')

@section('main-content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">Test Logs</h4>
            <ul class="app-line-breadcrumbs mb-3">
                <li class="">
                    <a href="{{ route('dashboard') }}" class="f-s-14 f-w-500">
                        <span>
                            <i class="ph-duotone ph-house f-s-16"></i> Dashboard
                        </span>
                    </a>
                </li>
                <li class="">
                    <a href="{{ route('admin.logs.index') }}" class="f-s-14 f-w-500">Logs</a>
                </li>
                <li class="active">
                    <a href="#" class="f-s-14 f-w-500">Test</a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4>Test Logs</h4>
                </div>
                <div class="card-body">
                    <p>Numero totale di log: {{ $count }}</p>
                    <a href="{{ route('admin.logs.index') }}" class="btn btn-primary">Vai ai Log</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
