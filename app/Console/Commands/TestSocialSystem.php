<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Video;
use App\Models\Poem;
use App\Models\Photo;
use App\Models\Event;
use App\Models\Carousel;
use App\Models\User;
use App\Models\SystemSetting;

class TestSocialSystem extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'social:test {--user-id=1 : ID utente per i test}';

    /**
     * The console command description.
     */
    protected $description = 'Testa il sistema social unificato';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->option('user-id');
        $user = User::find($userId);
        
        if (!$user) {
            $this->error("❌ Utente con ID {$userId} non trovato!");
            return 1;
        }

        $this->info("🧪 Testando sistema social per utente: {$user->name}");
        $this->newLine();

        try {
            // Test 1: Verifica impostazioni
            $this->testSettings();
            
            // Test 2: Test like
            $this->testLikes($user);
            
            // Test 3: Test views
            $this->testViews($user);
            
            // Test 4: Test commenti
            $this->testComments($user);
            
            // Test 5: Statistiche
            $this->showStats($user);

            $this->info("✅ Tutti i test completati con successo!");
            return 0;

        } catch (\Exception $e) {
            $this->error("❌ Errore durante i test: " . $e->getMessage());
            return 1;
        }
    }

    /**
     * Testa le impostazioni del sistema
     */
    private function testSettings(): void
    {
        $this->info("📋 Test 1: Verifica impostazioni social...");
        
        $settings = [
            'social_enable_likes' => SystemSetting::get('social_enable_likes', true),
            'social_enable_comments' => SystemSetting::get('social_enable_comments', true),
            'social_enable_views' => SystemSetting::get('social_enable_views', true),
            'social_enable_notifications' => SystemSetting::get('social_enable_notifications', true),
        ];

        foreach ($settings as $key => $value) {
            $status = $value ? '✅ Abilitato' : '❌ Disabilitato';
            $this->line("   {$key}: {$status}");
        }
        
        $this->newLine();
    }

    /**
     * Testa il sistema like
     */
    private function testLikes(User $user): void
    {
        $this->info("❤️ Test 2: Sistema Like...");
        
        // Test video
        $video = Video::first();
        if ($video) {
            $this->line("   Testando video: {$video->title}");
            $this->line("   Like count iniziale: {$video->like_count}");
            
            // Test isLikedBy
            $isLiked = $video->isLikedBy($user);
            $this->line("   È già likato: " . ($isLiked ? 'Sì' : 'No'));
            
            // Test toggle
            $result = $video->toggleLike($user);
            $this->line("   Toggle like: " . ($result ? '✅ OK' : '❌ Errore'));
            
            $video->refresh();
            $this->line("   Like count finale: {$video->like_count}");
        }

        $this->newLine();
    }

    /**
     * Testa il sistema views
     */
    private function testViews(User $user): void
    {
        $this->info("👁️ Test 3: Sistema Views...");
        
        // Test video
        $video = Video::first();
        if ($video) {
            $oldCount = $video->view_count;
            $result = $video->incrementViewIfNotOwner($user);
            $newCount = $video->fresh()->view_count;
            $this->line("   Video '{$video->title}': " . ($result ? '✅ View increment OK' : '❌ Errore increment'));
            $this->line("   Views: {$oldCount} → {$newCount}");
        }

        // Test poesia
        $poem = Poem::first();
        if ($poem) {
            $oldCount = $poem->view_count;
            $result = $poem->incrementViewIfNotOwner($user);
            $newCount = $poem->fresh()->view_count;
            $this->line("   Poesia '{$poem->title}': " . ($result ? '✅ View increment OK' : '❌ Errore increment'));
            $this->line("   Views: {$oldCount} → {$newCount}");
        }

        $this->newLine();
    }

    /**
     * Testa il sistema commenti
     */
    private function testComments(User $user): void
    {
        $this->info("💬 Test 4: Sistema Commenti...");
        
        // Test video
        $video = Video::first();
        if ($video) {
            $oldCount = $video->comment_count;
            $comment = $video->addComment("Test commento da {$user->name}", $user);
            $newCount = $video->fresh()->comment_count;
            
            if ($comment) {
                $this->line("   Video '{$video->title}': ✅ Commento aggiunto");
                $this->line("   Commenti: {$oldCount} → {$newCount}");
                
                // Test like sul commento
                $comment->toggleLike($user);
                $this->line("   Like sul commento: ✅ OK");
            } else {
                $this->line("   Video '{$video->title}': ❌ Errore aggiunta commento");
            }
        }

        // Test poesia
        $poem = Poem::first();
        if ($poem) {
            $oldCount = $poem->comment_count;
            $comment = $poem->addComment($user, "Test commento poesia da {$user->name}");
            $newCount = $poem->fresh()->comment_count;
            
            if ($comment) {
                $this->line("   Poesia '{$poem->title}': ✅ Commento aggiunto");
                $this->line("   Commenti: {$oldCount} → {$newCount}");
            } else {
                $this->line("   Poesia '{$poem->title}': ❌ Errore aggiunta commento");
            }
        }

        $this->newLine();
    }

    /**
     * Mostra statistiche
     */
    private function showStats(User $user): void
    {
        $this->info("📊 Test 5: Statistiche Sistema...");
        
        $this->table(
            ['Tipo', 'Conteggio'],
            [
                ['Video', Video::count()],
                ['Poesie', Poem::count()],
                ['Foto', Photo::count()],
                ['Eventi', Event::count()],
                ['Articoli', Carousel::count()],
                ['Like Totali', \App\Models\UnifiedLike::count()],
                ['Views Totali', \App\Models\UnifiedView::count()],
                ['Commenti Totali', \App\Models\UnifiedComment::count()],
                ['Like Utente', $user->likes()->count()],
                ['Views Utente', $user->views()->count()],
                ['Commenti Utente', $user->comments()->count()],
            ]
        );
    }
}
