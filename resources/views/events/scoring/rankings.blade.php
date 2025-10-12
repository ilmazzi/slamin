@extends('layout.master')

@section('title', 'Classifica - ' . $event->title)

@section('main-content')
    @livewire('events.scoring.rankings', ['event' => $event])
@endsection

