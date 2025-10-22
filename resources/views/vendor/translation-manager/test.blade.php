@extends('layout.master')

@section('title', 'Translation Manager Test')

@section('content')
<div class="container-fluid">
    <h1>Translation Manager Test</h1>
    <p>Se vedi questo, il layout funziona!</p>
    
    <div class="card">
        <div class="card-body">
            <h5>Variabili disponibili:</h5>
            <ul>
                <li>Groups: {{ count($groups ?? []) }}</li>
                <li>Locales: {{ count($locales ?? []) }}</li>
                <li>Translations: {{ count($translations ?? []) }}</li>
            </ul>
        </div>
    </div>
</div>
@endsection

