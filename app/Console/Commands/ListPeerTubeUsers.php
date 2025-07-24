<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ListPeerTubeUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'peertube:list-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lista gli utenti con account PeerTube';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('👥 UTENTI CON ACCOUNT PEERTUBE');
        $this->info('==============================');
        $this->newLine();

        $users = User::whereNotNull('peertube_user_id')->get();

        if ($users->count() === 0) {
            $this->warn('⚠️ Nessun utente con account PeerTube trovato');
            return 0;
        }

        $this->table(
            ['ID', 'Nome', 'Email', 'PT User ID', 'PT Username'],
            $users->map(function($user) {
                return [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->peertube_user_id,
                    $user->peertube_username ?? 'N/A'
                ];
            })
        );

        $this->newLine();
        $this->info('💡 Usa --user-id=N con altri comandi per testare un utente specifico');

        return 0;
    }
} 