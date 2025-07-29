<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SystemSetting;

class SetupPeerTubeConfig extends Command
{
    protected $signature = 'peertube:setup-config {--url=https://video.slamin.it} {--username=} {--password=}';
    protected $description = 'Configura le impostazioni PeerTube nel database';

    public function handle()
    {
        $this->info('🔧 CONFIGURAZIONE PEERTUBE');
        $this->info('========================');

        $url = $this->option('url');
        $username = $this->option('username');
        $password = $this->option('password');

        if (!$username) {
            $username = $this->ask('Inserisci username admin PeerTube:');
        }

        if (!$password) {
            $password = $this->secret('Inserisci password admin PeerTube:');
        }

        try {
            // URL PeerTube
            SystemSetting::updateOrCreate(
                ['key' => 'peertube_url'],
                [
                    'value' => $url,
                    'group' => 'peertube',
                    'type' => 'text',
                    'display_name' => 'PeerTube URL',
                    'description' => 'URL del server PeerTube',
                    'is_public' => false
                ]
            );

            // Username admin
            SystemSetting::updateOrCreate(
                ['key' => 'peertube_admin_username'],
                [
                    'value' => $username,
                    'group' => 'peertube',
                    'type' => 'text',
                    'display_name' => 'PeerTube Admin Username',
                    'description' => 'Username dell\'amministratore PeerTube',
                    'is_public' => false
                ]
            );

            // Password admin
            SystemSetting::updateOrCreate(
                ['key' => 'peertube_admin_password'],
                [
                    'value' => $password,
                    'group' => 'peertube',
                    'type' => 'password',
                    'display_name' => 'PeerTube Admin Password',
                    'description' => 'Password dell\'amministratore PeerTube',
                    'is_public' => false
                ]
            );

            $this->info('✅ Configurazioni PeerTube salvate nel database');
            $this->line('   URL: ' . $url);
            $this->line('   Username: ' . $username);
            $this->line('   Password: ***');

        } catch (\Exception $e) {
            $this->error('❌ Errore durante il salvataggio: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }
}