<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

class TestVideoUploadRoute extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:video-upload-route';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa le route di upload video con Guzzle';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Test route di upload video...');

        // Test 1: Verifica che le route esistano
        $this->info('1. Verifica route esistenti:');
        $routes = Route::getRoutes();
        $uploadRoutes = [];

        foreach ($routes as $route) {
            if (str_contains($route->uri(), 'videos/upload')) {
                $uploadRoutes[] = [
                    'method' => $route->methods()[0],
                    'uri' => $route->uri(),
                    'name' => $route->getName(),
                    'action' => $route->getActionName()
                ];
            }
        }

        if (empty($uploadRoutes)) {
            $this->error('Nessuna route videos/upload trovata!');
            return 1;
        }

        foreach ($uploadRoutes as $route) {
            $this->info("   {$route['method']} {$route['uri']} -> {$route['name']} ({$route['action']})");
        }

        // Test 2: Test route GET
        $this->info('2. Test route GET /videos/upload:');
        try {
            $response = Http::get('http://slamin.local/videos/upload');
            $this->info("   Status: {$response->status()}");
            $this->info("   Content-Type: " . $response->header('Content-Type'));

            if ($response->status() === 200) {
                $this->info("   ✅ Route GET funziona");
            } elseif ($response->status() === 302) {
                $this->info("   ⚠️  Reindirizzamento (probabilmente per autenticazione)");
                $this->info("   Location: " . $response->header('Location'));
            } else {
                $this->error("   ❌ Errore: {$response->status()}");
            }
        } catch (\Exception $e) {
            $this->error("   ❌ Errore: " . $e->getMessage());
        }

        // Test 3: Test route POST
        $this->info('3. Test route POST /videos/upload:');
        try {
            $response = Http::post('http://slamin.local/videos/upload', [
                'title' => 'Test Video',
                'description' => 'Test Description',
                'video_file' => 'test.mp4'
            ]);

            $this->info("   Status: {$response->status()}");
            $this->info("   Content-Type: " . $response->header('Content-Type'));

            if ($response->status() === 405) {
                $this->error("   ❌ Method Not Allowed - Route POST non riconosciuta");
            } elseif ($response->status() === 419) {
                $this->info("   ⚠️  CSRF Token mancante (normale per test senza form)");
            } elseif ($response->status() === 302) {
                $this->info("   ⚠️  Reindirizzamento (probabilmente per autenticazione)");
                $this->info("   Location: " . $response->header('Location'));
            } else {
                $this->info("   ✅ Route POST risponde (status: {$response->status()})");
            }
        } catch (\Exception $e) {
            $this->error("   ❌ Errore: " . $e->getMessage());
        }

        // Test 4: Verifica route name
        $this->info('4. Verifica route name:');
        try {
            $url = route('videos.upload');
            $this->info("   Route 'videos.upload' genera: {$url}");

            if (str_contains($url, 'videos/upload')) {
                $this->info("   ✅ Route name corretto");
            } else {
                $this->error("   ❌ Route name non corretto");
            }
        } catch (\Exception $e) {
            $this->error("   ❌ Errore route name: " . $e->getMessage());
        }

        $this->info('Test completato!');
        return 0;
    }
}
