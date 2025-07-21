<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Package;
use App\Models\UserSubscription;
use App\Models\Video;
use App\Models\VideoComment;
use App\Models\VideoSnap;
use App\Models\VideoLike;
use Carbon\Carbon;

class TestPremiumSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:premium-system';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa il sistema premium completo';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 TEST SISTEMA PREMIUM SLAMIN');
        $this->info('================================');
        $this->newLine();

        // 1. Test Pacchetti
        $this->info('1. Test Pacchetti Premium:');
        $packages = Package::active()->ordered()->get();
        $this->info("✅ Pacchetti trovati: {$packages->count()}");

        foreach ($packages as $package) {
            $this->info("   - {$package->name}: {$package->formatted_price} ({$package->video_limit} video)");
        }
        $this->newLine();

        // 2. Test Utente
        $this->info('2. Test Utente:');
        $user = User::where('email', 'organizer@poetryslam.it')->first();

        if (!$user) {
            $this->error('❌ Utente non trovato');
            return;
        }

        $this->info("✅ Utente trovato: {$user->name}");
        $this->info("   - Email: {$user->email}");
        $this->info("   - Video attuali: {$user->current_video_count}");
        $this->info("   - Limite video: {$user->current_video_limit}");
        $this->info("   - Può caricare altri video: " . ($user->canUploadMoreVideos() ? 'Sì' : 'No'));
        $this->info("   - Video rimanenti: {$user->remaining_video_uploads}");
        $this->info("   - Ha abbonamento premium: " . ($user->hasPremiumSubscription() ? 'Sì' : 'No'));
        $this->newLine();

        // 3. Test Abbonamento
        $this->info('3. Test Abbonamento:');
        $subscription = $user->activeSubscription;

        if ($subscription) {
            $this->info("✅ Abbonamento attivo trovato:");
            $this->info("   - Pacchetto: {$subscription->package->name}");
            $this->info("   - Data inizio: {$subscription->start_date->format('d/m/Y')}");
            $this->info("   - Data fine: {$subscription->end_date->format('d/m/Y')}");
            $this->info("   - Giorni rimanenti: {$subscription->days_remaining}");
            $this->info("   - Progresso: {$subscription->progress_percentage}%");
            $this->info("   - Limite effettivo: {$subscription->effective_video_limit}");
        } else {
            $this->info("ℹ️  Nessun abbonamento attivo");
        }
        $this->newLine();

        // 4. Test Video con PeerTube
        $this->info('4. Test Video PeerTube:');
        $videos = $user->videos;
        $this->info("✅ Video trovati: {$videos->count()}");

        foreach ($videos as $video) {
            $this->info("   - {$video->title}");
            $this->info("     • PeerTube: " . ($video->isOnPeerTube() ? 'Sì' : 'No'));
            $this->info("     • Moderazione: {$video->moderation_status}");
            $this->info("     • Visualizzazioni: {$video->view_count}");
            $this->info("     • Like: {$video->like_count}");
            $this->info("     • Commenti: {$video->comment_count}");
        }
        $this->newLine();

        // 5. Test Tabelle Interazione
        $this->info('5. Test Tabelle Interazione:');

        // Commenti
        $comments = VideoComment::count();
        $this->info("✅ Commenti totali: {$comments}");

        // Snap
        $snaps = VideoSnap::count();
        $this->info("✅ Snap totali: {$snaps}");

        // Like
        $likes = VideoLike::count();
        $this->info("✅ Like totali: {$likes}");
        $this->newLine();

        // 6. Test Database Schema
        $this->info('6. Test Database Schema:');

        $tables = [
            'packages' => 'Pacchetti',
            'user_subscriptions' => 'Abbonamenti',
            'video_comments' => 'Commenti Video',
            'video_snaps' => 'Snap Video',
            'video_likes' => 'Like Video',
        ];

        foreach ($tables as $table => $name) {
            try {
                $count = \DB::table($table)->count();
                $this->info("✅ Tabella {$name}: OK ({$count} record)");
            } catch (\Exception $e) {
                $this->error("❌ Tabella {$name}: ERRORE");
            }
        }
        $this->newLine();

        // 7. Test Campi PeerTube
        $this->info('7. Test Campi PeerTube:');
        $peerTubeFields = [
            'peertube_id',
            'peertube_url',
            'peertube_embed_url',
            'peertube_thumbnail_url',
            'duration',
            'resolution',
            'file_size',
            'view_count',
            'like_count',
            'dislike_count',
            'comment_count',
            'moderation_status',
        ];

        foreach ($peerTubeFields as $field) {
            try {
                $exists = \Schema::hasColumn('videos', $field);
                $this->info("✅ Campo {$field}: " . ($exists ? 'OK' : 'Mancante'));
            } catch (\Exception $e) {
                $this->error("❌ Campo {$field}: ERRORE");
            }
        }
        $this->newLine();

        $this->info('🎉 Test completato!');
        $this->info('Per testare l\'interfaccia, vai su: http://127.0.0.1:8000/profile');
    }
}
