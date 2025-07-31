<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Video;
use App\Models\Poem;
use App\Models\Photo;
use App\Models\Event;
use App\Models\Carousel;
use App\Models\VideoLike;
use App\Models\PoemComment;
use App\Models\VideoComment;

class MigrateSocialData extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'social:migrate-data {--dry-run : Esegue solo una simulazione senza modificare i dati}';

    /**
     * The console command description.
     */
    protected $description = 'Migra i dati social dalle tabelle esistenti alle nuove tabelle unificate';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        
        if ($isDryRun) {
            $this->info('🔍 Modalità DRY RUN - Nessuna modifica verrà applicata');
        }

        $this->info('🚀 Iniziando migrazione dati social...');

        try {
            // Migra i like dei video
            $this->migrateVideoLikes($isDryRun);
            
            // Migra i like delle poesie
            $this->migratePoemLikes($isDryRun);
            
            // Migra i commenti dei video
            $this->migrateVideoComments($isDryRun);
            
            // Migra i commenti delle poesie
            $this->migratePoemComments($isDryRun);

            $this->info('✅ Migrazione completata con successo!');
            
            if ($isDryRun) {
                $this->info('📊 Statistiche migrazione:');
                $this->showMigrationStats();
            }

        } catch (\Exception $e) {
            $this->error('❌ Errore durante la migrazione: ' . $e->getMessage());
            Log::error('Errore migrazione dati social', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }

        return 0;
    }

    /**
     * Migra i like dei video
     */
    private function migrateVideoLikes(bool $isDryRun): void
    {
        $this->info('📹 Migrando like dei video...');
        
        $videoLikes = VideoLike::with('video', 'user')->get();
        $count = 0;

        foreach ($videoLikes as $videoLike) {
            if (!$videoLike->video || !$videoLike->user) {
                continue;
            }

            if (!$isDryRun) {
                DB::table('unified_likes')->insertOrIgnore([
                    'user_id' => $videoLike->user_id,
                    'likeable_type' => Video::class,
                    'likeable_id' => $videoLike->video_id,
                    'created_at' => $videoLike->created_at,
                    'updated_at' => $videoLike->updated_at,
                ]);
            }
            
            $count++;
        }

        $this->info("   ✅ Migrati {$count} like dei video");
    }

    /**
     * Migra i like delle poesie
     */
    private function migratePoemLikes(bool $isDryRun): void
    {
        $this->info('📝 Migrando like delle poesie...');
        
        $poemLikes = DB::table('poem_likes')->get();
        $count = 0;

        foreach ($poemLikes as $poemLike) {
            if (!$isDryRun) {
                DB::table('unified_likes')->insertOrIgnore([
                    'user_id' => $poemLike->user_id,
                    'likeable_type' => Poem::class,
                    'likeable_id' => $poemLike->poem_id,
                    'created_at' => $poemLike->created_at ?? now(),
                    'updated_at' => $poemLike->updated_at ?? now(),
                ]);
            }
            
            $count++;
        }

        $this->info("   ✅ Migrati {$count} like delle poesie");
    }

    /**
     * Migra i commenti dei video
     */
    private function migrateVideoComments(bool $isDryRun): void
    {
        $this->info('💬 Migrando commenti dei video...');
        
        $videoComments = VideoComment::with('video', 'user')->get();
        $count = 0;

        foreach ($videoComments as $videoComment) {
            if (!$videoComment->video || !$videoComment->user) {
                continue;
            }

            if (!$isDryRun) {
                DB::table('unified_comments')->insertOrIgnore([
                    'user_id' => $videoComment->user_id,
                    'commentable_type' => Video::class,
                    'commentable_id' => $videoComment->video_id,
                    'content' => $videoComment->content,
                    'status' => $videoComment->status ?? 'approved',
                    'parent_id' => null, // I commenti esistenti non hanno risposte
                    'created_at' => $videoComment->created_at,
                    'updated_at' => $videoComment->updated_at,
                ]);
            }
            
            $count++;
        }

        $this->info("   ✅ Migrati {$count} commenti dei video");
    }

    /**
     * Migra i commenti delle poesie
     */
    private function migratePoemComments(bool $isDryRun): void
    {
        $this->info('💬 Migrando commenti delle poesie...');
        
        $poemComments = PoemComment::with('poem', 'user')->get();
        $count = 0;

        foreach ($poemComments as $poemComment) {
            if (!$poemComment->poem || !$poemComment->user) {
                continue;
            }

            if (!$isDryRun) {
                DB::table('unified_comments')->insertOrIgnore([
                    'user_id' => $poemComment->user_id,
                    'commentable_type' => Poem::class,
                    'commentable_id' => $poemComment->poem_id,
                    'content' => $poemComment->content,
                    'status' => $poemComment->status ?? 'approved',
                    'parent_id' => null, // I commenti esistenti non hanno risposte
                    'created_at' => $poemComment->created_at,
                    'updated_at' => $poemComment->updated_at,
                ]);
            }
            
            $count++;
        }

        $this->info("   ✅ Migrati {$count} commenti delle poesie");
    }

    /**
     * Mostra le statistiche della migrazione
     */
    private function showMigrationStats(): void
    {
        $this->table(
            ['Tipo', 'Conteggio'],
            [
                ['Video Likes', VideoLike::count()],
                ['Poem Likes', DB::table('poem_likes')->count()],
                ['Video Comments', VideoComment::count()],
                ['Poem Comments', PoemComment::count()],
                ['Unified Likes', DB::table('unified_likes')->count()],
                ['Unified Comments', DB::table('unified_comments')->count()],
            ]
        );
    }
}
