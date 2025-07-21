<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\UserSubscription;
use App\Models\Package;
use Illuminate\Support\Facades\Hash;

class CreateTestUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:test-user {--email=test@slamin.it} {--name=Test User}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea un utente di test per il sistema video';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->option('email');
        $name = $this->option('name');

        $this->info('🧪 CREAZIONE UTENTE TEST SLAMIN');
        $this->info('================================');
        $this->newLine();

        // Verifica se l'utente esiste già
        $existingUser = User::where('email', $email)->first();
        if ($existingUser) {
            $this->warn("⚠️  Utente {$email} esiste già!");
            $this->info("Password: password");
            $this->info("Video attuali: {$existingUser->current_video_count}");
            $this->info("Limite video: {$existingUser->current_video_limit}");
            return;
        }

        // Crea nuovo utente
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        $this->info("✅ Utente creato: {$user->name} ({$user->email})");
        $this->info("🔑 Password: password");
        $this->newLine();

        // Mostra pacchetti disponibili
        $this->info('📦 Pacchetti disponibili:');
        $packages = Package::active()->ordered()->get();

        foreach ($packages as $package) {
            $this->info("   - {$package->name}: {$package->video_limit} video (€{$package->formatted_price})");
        }
        $this->newLine();

        // Chiedi se creare abbonamento premium
        if ($this->confirm('Vuoi creare un abbonamento premium per questo utente?')) {
            $packageChoices = $packages->pluck('name', 'id')->toArray();
            $packageId = $this->choice('Seleziona pacchetto:', $packageChoices);

            $package = Package::find($packageId);

            // Crea abbonamento
            $subscription = UserSubscription::create([
                'user_id' => $user->id,
                'package_id' => $package->id,
                'start_date' => now(),
                'end_date' => now()->addDays($package->duration_days),
                'status' => 'active',
                'stripe_subscription_id' => 'test_sub_' . time(),
                'stripe_customer_id' => 'test_cust_' . $user->id,
            ]);

            $this->info("✅ Abbonamento {$package->name} creato!");
            $this->info("   - Limite video: {$package->video_limit}");
            $this->info("   - Durata: {$package->duration_days} giorni");
        }

        $this->newLine();
        $this->info('🎉 Utente test creato con successo!');
        $this->info('🌐 Login: http://127.0.0.1:8000/login');
        $this->info('🧪 Test Upload: http://127.0.0.1:8000/test/upload');
    }
}
