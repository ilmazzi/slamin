<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Video;
use App\Models\Photo;
use App\Models\VideoLike;
use App\Models\VideoComment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateTestUserForDeletion extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:create-user-for-deletion';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea un utente di test con dati associati per testare l\'eliminazione';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("➕ CREAZIONE UTENTE DI TEST PER ELIMINAZIONE");
        $this->info("==========================================");

        try {
            // Crea utente di test
            $user = User::create([
                'name' => 'Test User Deletion',
                'email' => 'test.deletion.' . time() . '@example.com',
                'nickname' => 'test_deletion_' . time(),
                'password' => Hash::make('password123'),
                'status' => 'active',
            ]);

            // Assegna ruolo poet
            $user->assignRole('poet');

            $this->info("✅ Utente creato:");
            $this->line("   ID: {$user->id}");
            $this->line("   Nome: {$user->name}");
            $this->line("   Email: {$user->email}");
            $this->line("   Nickname: {$user->nickname}");

            // Crea un video di test
            $video = Video::create([
                'user_id' => $user->id,
                'title' => 'Video Test Eliminazione',
                'description' => 'Video di test per eliminazione utente',
                'video_url' => 'https://example.com/test-video.mp4',
                'thumbnail' => null,
                'is_public' => true,
            ]);

            $this->info("✅ Video di test creato (ID: {$video->id})");

            // Crea un commento di test
            $comment = VideoComment::create([
                'video_id' => $video->id,
                'user_id' => $user->id,
                'content' => 'Commento di test per eliminazione',
            ]);

            $this->info("✅ Commento di test creato (ID: {$comment->id})");

            // Crea un like di test
            $like = VideoLike::create([
                'video_id' => $video->id,
                'user_id' => $user->id,
                'type' => 'like',
            ]);

            $this->info("✅ Like di test creato (ID: {$like->id})");

            $this->info("\n🎯 UTENTE DI TEST PRONTO PER ELIMINAZIONE");
            $this->line("   Comando per testare: php artisan test:user-deletion {$user->id}");
            $this->line("   Comando per dry run: php artisan test:user-deletion {$user->id} --dry-run");

            return 0;

        } catch (\Exception $e) {
            $this->error("❌ Errore durante la creazione: " . $e->getMessage());
            return 1;
        }
    }
}
