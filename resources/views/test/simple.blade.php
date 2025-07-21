@extends('layout.master')

@section('title', 'Test Simple')

@section('main-content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Test Page</h5>
                    </div>
                    <div class="card-body">
                        <h1>Test Page Funzionante!</h1>
                        <p>Se vedi questo messaggio, il layout funziona correttamente.</p>
                        <div class="alert alert-success">
                            <strong>Successo!</strong> Il sistema è funzionante.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
