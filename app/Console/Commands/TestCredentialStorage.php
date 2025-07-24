<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PeerTubeConfig;
use Illuminate\Support\Facades\Crypt;

class TestCredentialStorage extends Command
{
    protected $signature = 'peertube:test-storage';
    protected $description = 'Testa il salvataggio e la lettura delle credenziali';

    public function handle()
    {
        $this->info('💾 TEST SALVATAGGIO CREDENZIALI');
        $this->info('==============================');
        $this->newLine();

        $testPassword = 'Sl@M@z292929@';
        $testKey = 'test_password_storage';

        $this->line("Password di test: {$testPassword}");
        $this->newLine();

        // 1. Test salvataggio criptato
        $this->info('1. Test salvataggio criptato...');
        try {
            PeerTubeConfig::setValue($testKey, $testPassword, 'string', 'Test password', true);
            $this->line("   ✅ Password salvata criptata");
        } catch (\Exception $e) {
            $this->line("   ❌ Errore salvataggio: " . $e->getMessage());
            return 1;
        }

        // 2. Test lettura criptata
        $this->info('2. Test lettura criptata...');
        try {
            $readPassword = PeerTubeConfig::getValue($testKey);
            $this->line("   Password letta: {$readPassword}");
            
            if ($readPassword === $testPassword) {
                $this->line("   ✅ Password letta correttamente");
            } else {
                $this->line("   ❌ Password diversa!");
                $this->line("   Originale: {$testPassword}");
                $this->line("   Letta: {$readPassword}");
            }
        } catch (\Exception $e) {
            $this->line("   ❌ Errore lettura: " . $e->getMessage());
        }

        $this->newLine();

        // 3. Test credenziali attuali
        $this->info('3. Test credenziali attuali nel database...');
        $currentPassword = PeerTubeConfig::getValue('peertube_admin_password');
        $currentUsername = PeerTubeConfig::getValue('peertube_admin_username');
        
        $this->line("   Username salvato: {$currentUsername}");
        $this->line("   Password salvata: " . str_repeat('*', strlen($currentPassword)));
        
        if ($currentPassword === $testPassword) {
            $this->line("   ✅ Password nel database è corretta");
        } else {
            $this->line("   ❌ Password nel database è diversa!");
            $this->line("   Attesa: {$testPassword}");
            $this->line("   Salvata: {$currentPassword}");
        }

        $this->newLine();

        // 4. Test criptazione manuale
        $this->info('4. Test criptazione manuale...');
        try {
            $encrypted = Crypt::encryptString($testPassword);
            $this->line("   Password criptata: " . substr($encrypted, 0, 50) . "...");
            
            $decrypted = Crypt::decryptString($encrypted);
            $this->line("   Password decriptata: {$decrypted}");
            
            if ($decrypted === $testPassword) {
                $this->line("   ✅ Criptazione/decrittazione OK");
            } else {
                $this->line("   ❌ Criptazione/decrittazione fallita");
            }
        } catch (\Exception $e) {
            $this->line("   ❌ Errore criptazione: " . $e->getMessage());
        }

        $this->newLine();

        // 5. Aggiorna credenziali corrette
        $this->info('5. Aggiornamento credenziali corrette...');
        try {
            PeerTubeConfig::setValue('peertube_admin_password', $testPassword, 'string', 'Password admin PeerTube', true);
            $this->line("   ✅ Credenziali aggiornate");
            
            // Verifica
            $newPassword = PeerTubeConfig::getValue('peertube_admin_password');
            if ($newPassword === $testPassword) {
                $this->line("   ✅ Verifica OK");
            } else {
                $this->line("   ❌ Verifica fallita");
            }
        } catch (\Exception $e) {
            $this->line("   ❌ Errore aggiornamento: " . $e->getMessage());
        }

        // 6. Pulisci test
        $this->newLine();
        $this->info('6. Pulizia test...');
        try {
            PeerTubeConfig::where('key', $testKey)->delete();
            $this->line("   ✅ Test pulito");
        } catch (\Exception $e) {
            $this->line("   ❌ Errore pulizia: " . $e->getMessage());
        }

        $this->newLine();
        $this->info('🎯 Test completato!');
        
        return 0;
    }
} 