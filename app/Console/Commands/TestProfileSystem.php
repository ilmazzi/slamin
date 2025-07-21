<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Video;
use Illuminate\Support\Facades\DB;
use Exception;

class TestProfileSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:profile-system';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test del sistema profilo utente';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 TEST SISTEMA PROFILO SLAMIN');
        $this->info('================================');
        $this->newLine();

        // 1. Test modello User
        $this->info('1. Test Modello User:');
        try {
            $user = User::first();
            if ($user) {
                $this->info("✅ Utente trovato: " . $user->getDisplayName());
                $this->line("   - Email: " . $user->email);
                $this->line("   - Ruoli: " . implode(', ', $user->getRoleNames()->toArray()));
                $this->line("   - Foto profilo: " . $user->profile_photo_url);
            } else {
                $this->error('❌ Nessun utente trovato nel database');
            }
        } catch (Exception $e) {
            $this->error('❌ Errore nel test User: ' . $e->getMessage());
        }

        $this->newLine();

        // 2. Test modello Video
        $this->info('2. Test Modello Video:');
        try {
            $videoCount = Video::count();
            $this->info("✅ Video nel database: " . $videoCount);

            if ($videoCount > 0) {
                $video = Video::first();
                $this->line("   - Titolo: " . $video->title);
                $this->line("   - URL: " . $video->video_url);
                $this->line("   - Thumbnail: " . $video->thumbnail_url);
                $this->line("   - Embed URL: " . $video->embed_url);
            }
        } catch (Exception $e) {
            $this->error('❌ Errore nel test Video: ' . $e->getMessage());
        }

        $this->newLine();

        // 3. Test relazioni
        $this->info('3. Test Relazioni:');
        try {
            if ($user) {
                $this->info("✅ Relazione user->videos: " . $user->videos()->count() . " video");
                $this->info("✅ Relazione user->events: " . $user->events()->count() . " eventi organizzati");
                $this->info("✅ Relazione user->eventRequests: " . $user->eventRequests()->count() . " richieste");
            }
        } catch (Exception $e) {
            $this->error('❌ Errore nel test relazioni: ' . $e->getMessage());
        }

        $this->newLine();

        // 4. Test URL routes
        $this->info('4. Test URL Routes:');
        try {
            $routes = [
                'profile.show' => route('profile.show'),
                'profile.edit' => route('profile.edit'),
                'profile.videos' => route('profile.videos'),
                'profile.activity' => route('profile.activity'),
            ];

            foreach ($routes as $name => $url) {
                $this->info("✅ Route {$name}: {$url}");
            }
        } catch (Exception $e) {
            $this->error('❌ Errore nel test routes: ' . $e->getMessage());
        }

        $this->newLine();

        // 5. Test database
        $this->info('5. Test Database:');
        try {
            $userTable = DB::select("SHOW TABLES LIKE 'users'");
            $videoTable = DB::select("SHOW TABLES LIKE 'videos'");

            if (!empty($userTable)) {
                $this->info("✅ Tabella users: OK");
                $columns = DB::select("SHOW COLUMNS FROM users");
                $profileColumns = ['phone', 'website', 'profile_photo', 'social_facebook', 'social_instagram', 'social_youtube', 'social_twitter'];
                foreach ($profileColumns as $col) {
                    $found = false;
                    foreach ($columns as $column) {
                        if ($column->Field === $col) {
                            $found = true;
                            break;
                        }
                    }
                    $this->line(($found ? "✅" : "❌") . " Colonna {$col}");
                }
            }

            if (!empty($videoTable)) {
                $this->info("✅ Tabella videos: OK");
            }
        } catch (Exception $e) {
            $this->error('❌ Errore nel test database: ' . $e->getMessage());
        }

        $this->newLine();
        $this->info('🎉 Test completato!');
        $this->info('Per testare l\'interfaccia, vai su: http://127.0.0.1:8000/profile');

        return Command::SUCCESS;
    }
}
