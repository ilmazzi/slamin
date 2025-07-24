<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class TestUserProfiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:user-profiles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa i profili utente e le route';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('👥 TEST PROFILI UTENTE');
        $this->info('=====================');

        $users = User::take(5)->get(['id', 'name', 'email']);

        $this->info("Trovati {$users->count()} utenti per il test:");

        foreach ($users as $user) {
            $this->line("📋 ID: {$user->id} - {$user->name} ({$user->email})");
            $this->line("   Route profilo: /user/{$user->id}");
            $this->line("   URL completo: " . url("/user/{$user->id}"));
            $this->line('');
        }

        $this->info('✅ Test completato! Ora puoi testare i link nei profili.');
    }
}
