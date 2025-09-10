@extends('layout.master')

@section('title', 'Test Logs - Admin')

@section('main-content')
<div class="container-fluid">
    <!-- Breadcrumb -->
    <div class="row m-1">
        <div class="col-12">
            <h4 class="main-title">Test Logs</h4>
            
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
