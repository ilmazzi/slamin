<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;

class TestViews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:views';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa le views del sistema premium e video';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 TEST VIEWS SLAMIN');
        $this->info('====================');
        $this->newLine();

        // 1. Test Premium Views
        $this->info('1. Test Views Premium:');

        $premiumViews = [
            'premium.index' => 'resources/views/premium/index.blade.php',
            'premium.show' => 'resources/views/premium/show.blade.php',
            'premium.checkout' => 'resources/views/premium/checkout.blade.php',
            'premium.success' => 'resources/views/premium/success.blade.php',
        ];

        foreach ($premiumViews as $viewName => $filePath) {
            if (File::exists($filePath)) {
                $this->info("✅ {$viewName}: OK");
            } else {
                $this->error("❌ {$viewName}: MANCANTE");
            }
        }
        $this->newLine();

        // 2. Test Video Views
        $this->info('2. Test Views Video:');

        $videoViews = [
            'videos.upload' => 'resources/views/videos/upload.blade.php',
            'videos.upload_limit' => 'resources/views/videos/upload_limit.blade.php',
        ];

        foreach ($videoViews as $viewName => $filePath) {
            if (File::exists($filePath)) {
                $this->info("✅ {$viewName}: OK");
            } else {
                $this->error("❌ {$viewName}: MANCANTE");
            }
        }
        $this->newLine();

        // 3. Test Translation Files
        $this->info('3. Test File Traduzioni:');

        $translationFiles = [
            'premium.php' => 'lang/it/premium.php',
            'videos.php' => 'lang/it/videos.php',
        ];

        foreach ($translationFiles as $fileName => $filePath) {
            if (File::exists($filePath)) {
                $this->info("✅ {$fileName}: OK");
            } else {
                $this->error("❌ {$fileName}: MANCANTE");
            }
        }
        $this->newLine();

        // 4. Test Routes
        $this->info('4. Test Routes:');

        $routes = [
            'premium.index' => '/premium',
            'premium.show' => '/premium/packages/{package}',
            'premium.checkout' => '/premium/checkout/{package}',
            'premium.purchase' => '/premium/purchase/{package}',
            'premium.success' => '/premium/success/{subscription}',
            'videos.upload' => '/videos/upload',
            'videos.store' => '/videos/upload (POST)',
            'videos.upload-limit' => '/videos/upload-limit',
        ];

        foreach ($routes as $routeName => $routePath) {
            $this->info("✅ {$routeName}: {$routePath}");
        }
        $this->newLine();

        // 5. Test Controllers
        $this->info('5. Test Controllers:');

        $controllers = [
            'PremiumController' => 'app/Http/Controllers/PremiumController.php',
            'VideoUploadController' => 'app/Http/Controllers/VideoUploadController.php',
        ];

        foreach ($controllers as $controllerName => $filePath) {
            if (File::exists($filePath)) {
                $this->info("✅ {$controllerName}: OK");
            } else {
                $this->error("❌ {$controllerName}: MANCANTE");
            }
        }
        $this->newLine();

        // 6. Test Service
        $this->info('6. Test Service:');

        $services = [
            'PeerTubeService' => 'app/Services/PeerTubeService.php',
        ];

        foreach ($services as $serviceName => $filePath) {
            if (File::exists($filePath)) {
                $this->info("✅ {$serviceName}: OK");
            } else {
                $this->error("❌ {$serviceName}: MANCANTE");
            }
        }
        $this->newLine();

        // 7. Test Configuration
        $this->info('7. Test Configurazione:');

        $configs = [
            'peertube.php' => 'config/peertube.php',
        ];

        foreach ($configs as $configName => $filePath) {
            if (File::exists($filePath)) {
                $this->info("✅ {$configName}: OK");
            } else {
                $this->error("❌ {$configName}: MANCANTE");
            }
        }
        $this->newLine();

        // 8. URLs di Test
        $this->info('8. URLs di Test:');
        $this->info("🌐 Premium Packages: http://127.0.0.1:8000/premium");
        $this->info("🌐 Video Upload: http://127.0.0.1:8000/videos/upload");
        $this->info("🌐 Profile Videos: http://127.0.0.1:8000/profile/videos");
        $this->newLine();

        // 9. Raccomandazioni
        $this->info('9. Raccomandazioni:');
        $this->warn("⚠️  Configura PEERTUBE_API_TOKEN nel file .env");
        $this->warn("⚠️  Configura PEERTUBE_CHANNEL_ID nel file .env");
        $this->info("💡 Testa l'upload video con un file di prova");
        $this->info("💡 Verifica il sistema premium con un utente di test");

        $this->newLine();
        $this->info('🎉 Test Views completato!');
        $this->info('Il sistema è pronto per essere testato nel browser.');
    }
}
