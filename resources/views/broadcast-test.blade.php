@extends('layout.master') {{-- oppure il layout che usi di default --}}

@section('main-content')
    <div class="container">
        <h1>Test Broadcast</h1>
        <button id="broadcast-btn">Invia evento</button>
    </div>
@endsection

@push('scripts')
<script>
    document.getElementById('broadcast-btn').addEventListener('click', function () {
        fetch('/api/send-broadcast').then(() => {
            console.log("Evento inviato!");
        });
    });
</script>
@endpush
