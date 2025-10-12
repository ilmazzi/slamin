@extends('layout.master')

@section('title', 'Scoring Dashboard - ' . $event->title)

@section('main-content')
    @livewire('events.scoring.dashboard', ['event' => $event])
@endsection

