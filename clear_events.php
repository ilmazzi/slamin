<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Event;
use App\Models\EventInvitation;
use App\Models\EventRequest;
use Illuminate\Support\Facades\DB;

echo "Iniziando pulizia eventi...\n";

// Disabilita foreign key checks temporaneamente
DB::statement('SET FOREIGN_KEY_CHECKS=0');

// Elimina tutti gli eventi
$eventCount = Event::count();
Event::truncate();

// Elimina inviti correlati
$invitationCount = EventInvitation::count();
EventInvitation::truncate();

// Elimina richieste correlate
$requestCount = EventRequest::count();
EventRequest::truncate();

// Riabilita foreign key checks
DB::statement('SET FOREIGN_KEY_CHECKS=1');

echo "Pulizia completata!\n";
echo "Eventi eliminati: $eventCount\n";
echo "Inviti eliminati: $invitationCount\n";
echo "Richieste eliminate: $requestCount\n";
echo "Database pulito e pronto per nuovi eventi!\n";
