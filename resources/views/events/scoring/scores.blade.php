@extends('layout.master')

@section('title', 'Inserimento Punteggi - ' . $event->title)

@section('main-content')
    @livewire('events.scoring.score-entry', ['event' => $event])
@endsection

