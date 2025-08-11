<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateSuperAdmin extends Command
{
    protected $signature = 'admin:create-super {--email=} {--name=} {--nickname=} {--password=} {--force}';
    protected $description = 'Crea un super amministratore con credenziali specifiche';

    public function handle()
    {
        $force = $this->option('force');
        
        // Credenziali predefinite
        $defaultEmail = 'super@slamin.com';
        $defaultName = 'SuperAdmin';
        $defaultNickname = 'SuperAdmin';
        $defaultPassword = 'Sl@dm1n@';

        // Usa le opzioni se fornite, altrimenti usa i default
        $email = $this->option('email') ?: $defaultEmail;
        $name = $this->option('name') ?: $defaultName;
        $nickname = $this->option('nickname') ?: $defaultNickname;
        $password = $this->option('password') ?: $defaultPassword;

        $this->info('🔧 CREAZIONE SUPER AMMINISTRATORE');
        $this->line('');

        // Mostra le credenziali che verranno utilizzate
        $this->info('📋 CREDENZIALI SUPER ADMIN:');
        $this->line("   📧 Email: {$email}");
        $this->line("   👤 Nome: {$name}");
        $this->line("   🏷️ Nickname: {$nickname}");
        $this->line("   🔑 Password: {$password}");
        $this->line('');

        // Controlla se l'utente esiste già
        $existingUser = User::where('email', $email)->orWhere('nickname', $nickname)->first();

        // Validazione (escludi l'utente esistente se presente)
        $validationRules = [
            'email' => 'required|email',
            'name' => 'required|string|max:255',
            'nickname' => 'required|string|max:255',
            'password' => 'required|string|min:8'
        ];

        if ($existingUser) {
            // Se l'utente esiste, escludilo dalla validazione di unicità
            $validationRules['email'] .= '|unique:users,email,' . $existingUser->id;
            $validationRules['nickname'] .= '|unique:users,nickname,' . $existingUser->id;
        } else {
            // Se è un nuovo utente, richiedi unicità
            $validationRules['email'] .= '|unique:users,email';
            $validationRules['nickname'] .= '|unique:users,nickname';
        }

        $validator = Validator::make([
            'email' => $email,
            'name' => $name,
            'nickname' => $nickname,
            'password' => $password
        ], $validationRules);

        if ($validator->fails()) {
            $this->error('❌ ERRORE DI VALIDAZIONE:');
            foreach ($validator->errors()->all() as $error) {
                $this->error("   • {$error}");
            }
            return 1;
        }

        if ($existingUser) {
            $this->warn('⚠️ ATTENZIONE:');
            $this->line("   Un utente con email '{$email}' o nickname '{$nickname}' esiste già!");
            $this->line("   ID: {$existingUser->id}");
            $this->line("   Nome: {$existingUser->name}");
            $this->line("   Email: {$existingUser->email}");
            $this->line("   Nickname: {$existingUser->nickname}");
            $this->line("   Ruolo: " . ($existingUser->roles->pluck('name')->implode(', ') ?: 'user'));
            
            if (!$force && !$this->confirm('Vuoi continuare e aggiornare questo utente come super admin?')) {
                $this->error('❌ Operazione annullata');
                return 1;
            }

            // Aggiorna l'utente esistente
            try {
                $existingUser->update([
                    'name' => $name,
                    'nickname' => $nickname,
                    'email' => $email,
                    'password' => Hash::make($password),
                    'email_verified_at' => now()
                ]);

                // Assegna il ruolo super-admin usando Spatie Permission
                $existingUser->assignRole('super-admin');

                $this->info('✅ SUPER ADMIN AGGIORNATO CON SUCCESSO!');
                $this->line("   ID: {$existingUser->id}");
                $this->line("   Nome: {$existingUser->name}");
                $this->line("   Email: {$existingUser->email}");
                $this->line("   Nickname: {$existingUser->nickname}");
                $this->line("   Ruolo: " . $existingUser->roles->pluck('name')->implode(', '));
                $this->line("   Password: Aggiornata");
                $this->line('');
                $this->info('🎉 L\'utente è ora un super amministratore!');

                return 0;
            } catch (\Exception $e) {
                $this->error("❌ Errore durante l'aggiornamento: " . $e->getMessage());
                return 1;
            }
        }

        // Conferma se non in modalità force
        if (!$force && !$this->confirm('Sei sicuro di voler creare questo super amministratore?')) {
            $this->error('❌ Operazione annullata');
            return 1;
        }

        // Crea il nuovo utente
        try {
            $user = User::create([
                'name' => $name,
                'nickname' => $nickname,
                'email' => $email,
                'password' => Hash::make($password),
                'email_verified_at' => now()
            ]);

            // Assegna il ruolo super-admin usando Spatie Permission
            $user->assignRole('super-admin');

            $this->info('✅ SUPER ADMIN CREATO CON SUCCESSO!');
            $this->line("   ID: {$user->id}");
            $this->line("   Nome: {$user->name}");
            $this->line("   Email: {$user->email}");
            $this->line("   Nickname: {$user->nickname}");
            $this->line("   Ruolo: " . $user->roles->pluck('name')->implode(', '));
            $this->line("   Password: {$password}");
            $this->line('');
            $this->info('🎉 Il super amministratore è stato creato e può accedere al sistema!');
            $this->info('🔐 Il super admin può eliminare qualsiasi utente, inclusi gli admin!');

            return 0;
        } catch (\Exception $e) {
            $this->error("❌ Errore durante la creazione: " . $e->getMessage());
            return 1;
        }
    }
} 
