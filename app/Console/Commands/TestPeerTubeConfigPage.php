<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PeerTubeConfig;
use App\Services\PeerTubeService;

class TestPeerTubeConfigPage extends Command
{
    protected $signature = 'peertube:test-page';
    protected $description = 'Testa la pagina di configurazione PeerTube';

    public function handle()
    {
        $this->info('🧪 TEST PAGINA CONFIGURAZIONE PEERTUBE');
        $this->info('=====================================');
        $this->newLine();

        // 1. Verifica configurazioni esistenti
        $this->info('1. CONFIGURAZIONI ESISTENTI');
        $this->info('---------------------------');
        
        $configs = PeerTubeConfig::getAllAsArray();
        
        if (empty($configs)) {
            $this->warn('⚠️  Nessuna configurazione PeerTube trovata');
        } else {
            $this->info('✅ Configurazioni trovate:');
            foreach ($configs as $key => $value) {
                if (str_contains($key, 'password') || str_contains($key, 'token')) {
                    $this->line("   {$key}: " . str_repeat('*', 10));
                } else {
                    if (is_array($value)) {
                        $this->line("   {$key}: " . json_encode($value));
                    } else {
                        $this->line("   {$key}: {$value}");
                    }
                }
            }
        }
        
        $this->newLine();

        // 2. Test servizio PeerTube
        $this->info('2. TEST SERVIZIO PEERTUBE');
        $this->info('-------------------------');
        
        $service = new PeerTubeService();
        
        $this->line("Configurato: " . ($service->isConfigured() ? '✅ Sì' : '❌ No'));
        $this->line("Connessione: " . ($service->testConnection() ? '✅ OK' : '❌ Fallita'));
        
        if ($service->isConfigured()) {
            $this->line("Autenticazione: " . ($service->testAuthentication() ? '✅ OK' : '❌ Fallita'));
        }
        
        $this->newLine();

        // 3. Test controller
        $this->info('3. TEST CONTROLLER');
        $this->info('------------------');
        
        try {
            $controller = new \App\Http\Controllers\Admin\PeerTubeConfigController($service);
            $this->info('✅ Controller istanziato correttamente');
            
            // Test metodo index
            $response = $controller->index();
            $this->info('✅ Metodo index funziona');
            
        } catch (\Exception $e) {
            $this->error('❌ Errore controller: ' . $e->getMessage());
        }
        
        $this->newLine();

        // 4. Test rotte
        $this->info('4. TEST ROTTE');
        $this->info('-------------');
        
        $routes = [
            'admin.peertube.config' => 'GET /admin/peertube/config',
            'admin.peertube.config.update' => 'PUT /admin/peertube/config',
            'admin.peertube.config.test-connection' => 'POST /admin/peertube/config/test-connection',
            'admin.peertube.config.test-auth' => 'POST /admin/peertube/config/test-auth',
            'admin.peertube.config.reset' => 'POST /admin/peertube/config/reset',
        ];
        
        foreach ($routes as $name => $route) {
            try {
                $url = route($name);
                $this->info("✅ {$name}: {$url}");
            } catch (\Exception $e) {
                $this->error("❌ {$name}: Errore - {$e->getMessage()}");
            }
        }
        
        $this->newLine();

        // 5. Test view
        $this->info('5. TEST VIEW');
        $this->info('------------');
        
        $viewPath = 'resources/views/admin/peertube/config.blade.php';
        if (file_exists($viewPath)) {
            $this->info('✅ View trovata: ' . $viewPath);
        } else {
            $this->error('❌ View mancante: ' . $viewPath);
        }
        
        $this->newLine();

        // 6. URL di accesso
        $this->info('6. URL DI ACCESSO');
        $this->info('-----------------');
        
        $baseUrl = config('app.url');
        $this->info("🌐 URL base: {$baseUrl}");
        $this->info("🔧 Configurazione PeerTube: {$baseUrl}/admin/peertube/config");
        $this->info("⚙️  Impostazioni generali: {$baseUrl}/admin/settings");
        
        $this->newLine();

        // 7. Raccomandazioni
        $this->info('7. RACCOMANDAZIONI');
        $this->info('------------------');
        
        if (!$service->isConfigured()) {
            $this->warn('⚠️  PeerTube non è configurato');
            $this->line('💡 Vai su: ' . $baseUrl . '/admin/peertube/config');
            $this->line('💡 Inserisci URL, username e password admin PeerTube');
        } else {
            $this->info('✅ PeerTube è configurato');
            if (!$service->testConnection()) {
                $this->warn('⚠️  Connessione fallita - verifica URL');
            }
            if (!$service->testAuthentication()) {
                $this->warn('⚠️  Autenticazione fallita - verifica credenziali');
            }
        }
        
        $this->newLine();
        $this->info('✅ Test completato!');
        return 0;
    }
} 
