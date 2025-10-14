<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Spatie\Permission\Models\Role;

class VerifyAdminRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:verify-roles {--fix : Automatically fix missing admin roles}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify that admin users still have their roles after deployment';

    /**
     * Lista degli user ID che DEVONO essere admin
     * IMPORTANTE: Modifica questi ID con quelli reali degli admin del tuo sito
     */
    protected $requiredAdmins = [
        36, // davide.mazzitelli84@gmail.com
        // Aggiungi qui gli altri ID admin se necessari
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Verifica ruoli admin in corso...');
        $this->newLine();

        // Verifica che il ruolo admin esista
        $adminRole = Role::where('name', 'admin')->first();
        
        if (!$adminRole) {
            $this->error('❌ ERRORE: Il ruolo "admin" non esiste nel database!');
            
            if ($this->option('fix')) {
                $this->info('📝 Creazione ruolo admin...');
                $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
                $this->info('✅ Ruolo admin creato');
            } else {
                $this->warn('💡 Esegui: php artisan admin:verify-roles --fix');
                return 1;
            }
        }

        $issues = [];
        $fixed = [];

        foreach ($this->requiredAdmins as $userId) {
            $user = User::find($userId);
            
            if (!$user) {
                $this->warn("⚠️  User ID {$userId} non esiste nel database");
                continue;
            }

            $hasAdminRole = $user->hasRole('admin');

            if (!$hasAdminRole) {
                $issues[] = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ];

                $this->error("❌ {$user->name} ({$user->email}) NON ha il ruolo admin!");

                if ($this->option('fix')) {
                    $user->assignRole('admin');
                    $fixed[] = $user->name;
                    $this->info("✅ Ruolo admin assegnato a {$user->name}");
                }
            } else {
                $this->info("✅ {$user->name} ({$user->email}) ha il ruolo admin");
            }
        }

        $this->newLine();

        if (empty($issues)) {
            $this->info('🎉 Tutti gli admin hanno i ruoli corretti!');
            return 0;
        }

        if ($this->option('fix')) {
            $this->info('✅ ' . count($fixed) . ' ruoli admin ripristinati');
            $this->newLine();
            $this->info('🎉 Verifica completata e problemi risolti!');
            return 0;
        } else {
            $this->error('❌ Trovati ' . count($issues) . ' problemi con i ruoli admin');
            $this->warn('💡 Esegui: php artisan admin:verify-roles --fix');
            return 1;
        }
    }
}


