<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Exception;

class EncryptPeerTubePasswords extends Command
{
    protected $signature = 'peertube:encrypt-passwords';
    protected $description = 'Cripta tutte le password PeerTube esistenti nel database';

    public function handle()
    {
        $this->info('🔐 CRIPTAZIONE PASSWORD PEERTUBE');
        $this->info('==================================');
        $this->newLine();

        // Trova tutti gli utenti con password PeerTube
        $users = User::whereNotNull('peertube_password')->get();
        
        if ($users->isEmpty()) {
            $this->info('ℹ️  Nessun utente con password PeerTube trovato');
            return 0;
        }

        $this->info('1. Utenti trovati: ' . $users->count());
        $this->newLine();

        $encrypted = 0;
        $alreadyEncrypted = 0;
        $errors = 0;

        foreach ($users as $user) {
            try {
                $password = $user->peertube_password;
                
                // Controlla se è già criptata
                if (str_starts_with($password, 'eyJpdiI6')) {
                    $this->info("   ✅ ID {$user->id} ({$user->name}): Già criptata");
                    $alreadyEncrypted++;
                    continue;
                }

                // Cripta la password
                $encryptedPassword = encrypt($password);
                
                // Aggiorna il database
                $user->update(['peertube_password' => $encryptedPassword]);
                
                $this->info("   🔒 ID {$user->id} ({$user->name}): Criptata");
                $encrypted++;
                
            } catch (Exception $e) {
                $this->error("   ❌ ID {$user->id} ({$user->name}): Errore - " . $e->getMessage());
                $errors++;
            }
        }

        $this->newLine();
        $this->info('2. Risultati:');
        $this->info("   ✅ Criptate: {$encrypted}");
        $this->info("   ℹ️  Già criptate: {$alreadyEncrypted}");
        $this->info("   ❌ Errori: {$errors}");
        
        if ($errors === 0) {
            $this->newLine();
            $this->info('🎉 Tutte le password sono state criptate con successo!');
            $this->info('🔐 Le password sono ora sicure nel database');
        } else {
            $this->newLine();
            $this->warn('⚠️  Alcune password non sono state criptate. Controlla gli errori sopra.');
        }

        return 0;
    }
} 