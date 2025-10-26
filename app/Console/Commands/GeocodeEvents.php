<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use Illuminate\Support\Facades\Http;

class GeocodeEvents extends Command
{
    protected $signature = 'events:geocode {--force : Force geocode all events}';
    protected $description = 'Geocode events that have address but no coordinates';

    public function handle()
    {
        $query = Event::query();
        
        if ($this->option('force')) {
            $events = $query->whereNotNull('venue_address')->get();
            $this->info('🌍 Geocoding ALL events with address...');
        } else {
            $events = $query->whereNotNull('venue_address')
                ->whereNull('latitude')
                ->whereNull('longitude')
                ->get();
            $this->info('🌍 Geocoding events without coordinates...');
        }

        if ($events->isEmpty()) {
            $this->info('✅ No events to geocode!');
            return 0;
        }

        $this->info("Found {$events->count()} events to geocode");
        
        $bar = $this->output->createProgressBar($events->count());
        $bar->start();

        $success = 0;
        $failed = 0;

        foreach ($events as $event) {
            // Build address string
            $country = $event->country === 'IT' ? 'Italy' : $event->country;
            
            $addressParts = array_filter([
                $event->venue_address,
                $event->city,
                $event->postcode,
                $country
            ]);
            
            $fullAddress = implode(', ', $addressParts);
            
            try {
                // Use Nominatim (OpenStreetMap) for free geocoding
                $response = Http::withHeaders([
                    'User-Agent' => 'SlamIn Poetry Network/1.0 (https://slamin.it; contact@slamin.it)'
                ])->get('https://nominatim.openstreetmap.org/search', [
                    'q' => $fullAddress,
                    'format' => 'json',
                    'limit' => 1,
                    'addressdetails' => 1
                ]);

                if ($response->successful() && !empty($response->json())) {
                    $result = $response->json()[0];
                    
                    $event->latitude = $result['lat'];
                    $event->longitude = $result['lon'];
                    $event->save();
                    
                    $success++;
                    $this->newLine();
                    $this->info("✅ {$event->title}: ({$result['lat']}, {$result['lon']})");
                } else {
                    $failed++;
                    $this->newLine();
                    $this->warn("⚠️  {$event->title}: No results for '{$fullAddress}'");
                }
                
                // Respect Nominatim usage policy: max 1 request per second
                sleep(1);
                
            } catch (\Exception $e) {
                $failed++;
                $this->newLine();
                $this->error("❌ {$event->title}: {$e->getMessage()}");
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        
        $this->info("🎉 Geocoding completed!");
        $this->info("✅ Success: {$success}");
        $this->warn("⚠️  Failed: {$failed}");

        return 0;
    }
}
