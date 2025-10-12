@extends('layout.master')

@section('title', 'Gestione Partecipanti - ' . $event->title)

@section('main-content')
    @livewire('events.scoring.participant-management', ['event' => $event])
@endsection

