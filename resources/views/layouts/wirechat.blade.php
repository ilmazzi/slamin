@extends('layout.master')

@section('title', 'Chat')

@section('main-content')
<div class="container-fluid py-3">
  <div class="row">
    <div class="col-12">
      {{-- Wrapper senza overflow nascosti né trasformazioni --}}
      <div class="shadow-sm" style="border-radius: .5rem; overflow: visible;">
        {{-- Barra opzionale con titolo --}}
        <div class="p-3 border-bottom d-flex align-items-center justify-content-between bg-body">
          <h5 class="mb-0">Chat</h5>
          {{-- eventuali pulsanti tuoi qui --}}
        </div>

        {{-- IFRAME: usa la rotta del pacchetto (di solito /chats) --}}
        <div style="height: calc(100vh - 10rem);">
          <iframe
            src="{{ url('/chats') }}"
            title="Wirechat"
            style="border:0; width:100%; height:100%; display:block;"
            allow="clipboard-write; microphone; camera"
            referrerpolicy="same-origin"
            sandbox="allow-scripts allow-same-origin allow-forms allow-popups"
          ></iframe>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
