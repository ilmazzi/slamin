<!DOCTYPE html>
<html>
<head>
    <title>Test Inviti</title>
</head>
<body>
    <h1>Test Inviti</h1>
    <p>Se vedi questo, il template funziona!</p>

    @if(isset($invitations))
        <p>Inviti trovati: {{ $invitations->count() }}</p>
        @foreach($invitations as $invitation)
            <div>
                <strong>{{ $invitation->event->title ?? 'N/A' }}</strong>
                - {{ $invitation->status }}
            </div>
        @endforeach
    @else
        <p>Nessun dato passato</p>
    @endif
</body>
</html>
