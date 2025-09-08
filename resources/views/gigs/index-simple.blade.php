@extends('layout.master')

@section('title', 'Gigs Simple')

@section('main-content')
<div class="page-content">
    <div class="container-fluid">
        <h1>Gigs Index - Simple Version</h1>
        <p>This is a simplified version to test for errors.</p>

        <div class="card">
            <div class="card-body">
                <h5>Debug Info</h5>
                <p>Categories count: {{ is_array($categories ?? null) ? count($categories) : 'Not set' }}</p>
                <p>Types count: {{ is_array($types ?? null) ? count($types) : 'Not set' }}</p>
                <p>Sort options count: {{ is_array($sortOptions ?? null) ? count($sortOptions) : 'Not set' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
