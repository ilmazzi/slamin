@extends('layout.master')

@section('main-content')
<div class="container">
    <h1>🎭 I Miei Inviti - Versione Semplificata</h1>

    @if(isset($invitations) && $invitations->count() > 0)
        <p>Inviti trovati: {{ $invitations->count() }}</p>
        @foreach($invitations as $invitation)
            <div class="card mb-3">
                <div class="card-body">
                    <h5>{{ $invitation->event->title ?? 'N/A' }}</h5>
                    <p>Stato: {{ $invitation->status }}</p>
                    <p>Ruolo: {{ $invitation->role }}</p>
                </div>
            </div>
        @endforeach
    @else
        <p>Nessun invito trovato.</p>
    @endif
</div>
@endsection
