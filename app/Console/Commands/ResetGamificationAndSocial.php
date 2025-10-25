<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetGamificationAndSocial extends Command
{
    protected $signature = 'reset:gamification-social {--force : Force reset without confirmation}';
    protected $description = 'Reset all gamification (badges, points) and social interactions (likes, comments)';

    public function handle()
    {
        if (!$this->option('force')) {
            if (!$this->confirm('⚠️  Questa azione eliminerà TUTTI i like, commenti, badge e punti. Continuare?')) {
                $this->info('Operazione annullata.');
                return 0;
            }
        }

        $this->info('🧹 Inizio pulizia...');
        $this->newLine();

        // 1. Pulisci likes
        $this->info('1️⃣  Eliminazione likes...');
        $likesCount = DB::table('unified_likes')->count();
        DB::table('unified_likes')->truncate();
        $this->info("   ✅ Eliminati {$likesCount} likes");

        // 2. Pulisci comments
        $this->info('2️⃣  Eliminazione commenti...');
        $commentsCount = DB::table('unified_comments')->count();
        DB::table('unified_comments')->truncate();
        $this->info("   ✅ Eliminati {$commentsCount} commenti");

        // 3. Pulisci user badges
        $this->info('3️⃣  Eliminazione badge utenti...');
        $userBadgesCount = DB::table('user_badges')->count();
        DB::table('user_badges')->truncate();
        $this->info("   ✅ Eliminati {$userBadgesCount} badge utenti");

        // 4. Pulisci point transactions
        $this->info('4️⃣  Eliminazione transazioni punti...');
        $transactionsCount = DB::table('point_transactions')->count();
        DB::table('point_transactions')->truncate();
        $this->info("   ✅ Eliminate {$transactionsCount} transazioni");

        // 5. Reset user points
        $this->info('5️⃣  Reset punti utenti...');
        $pointsUpdated = DB::table('user_points')->update([
            'total_points' => 0,
            'badges_count' => 0,
            'level' => 1,
        ]);
        $this->info("   ✅ Resettati punti di {$pointsUpdated} utenti");

        // 6. Reset content counts
        $this->info('6️⃣  Reset contatori contenuti...');
        
        // Reset solo se le colonne esistono
        if (\Schema::hasColumn('poems', 'like_count')) {
            DB::table('poems')->update(['like_count' => 0, 'comment_count' => 0]);
            $this->info("   ✅ Poems contatori resettati");
        }
        
        if (\Schema::hasColumn('articles', 'like_count')) {
            DB::table('articles')->update(['like_count' => 0, 'comment_count' => 0]);
            $this->info("   ✅ Articles contatori resettati");
        }
        
        if (\Schema::hasColumn('videos', 'like_count')) {
            DB::table('videos')->update(['like_count' => 0, 'comment_count' => 0]);
            $this->info("   ✅ Videos contatori resettati");
        }
        
        if (\Schema::hasColumn('photos', 'like_count')) {
            DB::table('photos')->update(['like_count' => 0, 'comment_count' => 0]);
            $this->info("   ✅ Photos contatori resettati");
        }
        
        if (\Schema::hasColumn('events', 'like_count')) {
            DB::table('events')->update(['like_count' => 0, 'comment_count' => 0]);
            $this->info("   ✅ Events contatori resettati");
        }

        $this->newLine();
        $this->info('✅ Pulizia completata con successo!');
        $this->newLine();
        $this->info('📊 Riepilogo:');
        $this->table(
            ['Tipo', 'Eliminati'],
            [
                ['Likes', $likesCount],
                ['Commenti', $commentsCount],
                ['Badge Utenti', $userBadgesCount],
                ['Transazioni Punti', $transactionsCount],
                ['Punti Utenti Reset', $pointsUpdated],
            ]
        );
        $this->newLine();
        $this->info('🎯 Ora puoi testare i badge partendo da zero!');
        $this->info('💡 Metti like alle poesie e guadagnerai il primo badge!');

        return 0;
    }
}
