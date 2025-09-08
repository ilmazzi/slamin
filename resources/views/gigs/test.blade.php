@extends('layout.master')

@section('title', 'Test Gigs')

@section('main-content')
<div class="page-content">
    <div class="container-fluid">
        <h1>Test Gigs Page</h1>
        <p>This is a minimal test page to isolate the htmlspecialchars error.</p>

        <div class="card">
            <div class="card-body">
                <h5>Categories Test</h5>
                @if(isset($categories))
                    <ul>
                        @foreach($categories as $key => $category)
                            <li>{{ $key }}: {{ $category }}</li>
                        @endforeach
                    </ul>
                @else
                    <p>No categories passed</p>
                @endif
            </div>
        </div>

        <div class="card mt-3">
            <div class="card-body">
                <h5>Types Test</h5>
                @if(isset($types))
                    <ul>
                        @foreach($types as $key => $type)
                            <li>{{ $key }}: {{ $type }}</li>
                        @endforeach
                    </ul>
                @else
                    <p>No types passed</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
