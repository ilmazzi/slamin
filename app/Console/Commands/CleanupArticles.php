<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Article;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CleanupArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cleanup:articles {--force} {--dry-run}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Elimina tutti gli articoli e crea articoli di test senza immagini';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $force = $this->option('force');
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->info('🔍 Modalità DRY-RUN - Nessuna modifica verrà applicata');
        }

        // Conta articoli esistenti
        $existingArticles = Article::count();
        $this->info("📊 Articoli esistenti: {$existingArticles}");

        if ($existingArticles > 0) {
            if (!$force && !$dryRun) {
                if (!$this->confirm('⚠️  Sei sicuro di voler eliminare TUTTI gli articoli?')) {
                    $this->info('❌ Operazione annullata');
                    return;
                }
            }

            if (!$dryRun) {
                $this->info('🗑️  Eliminazione articoli in corso...');
                
                // Elimina in transazione per sicurezza
                DB::transaction(function () {
                    // Elimina commenti associati (se la tabella esiste)
                    if (DB::getSchemaBuilder()->hasTable('comments')) {
                        DB::table('comments')->where('commentable_type', 'App\Models\Article')->delete();
                    }
                    
                    // Elimina report associati (se la tabella esiste)
                    if (DB::getSchemaBuilder()->hasTable('reports')) {
                        DB::table('reports')->where('reportable_type', 'App\Models\Article')->delete();
                    }
                    
                    // Elimina articoli
                    Article::query()->delete();
                });

                $this->info('✅ Articoli eliminati con successo');
            } else {
                $this->info('🔍 DRY-RUN: Articoli che verrebbero eliminati: ' . $existingArticles);
            }
        }

        // Crea articoli di test
        if (!$dryRun) {
            $this->info('📝 Creazione articoli di test...');
            $this->createTestArticles();
        } else {
            $this->info('🔍 DRY-RUN: Verrebbero creati 5 articoli di test');
        }

        $this->info('🎉 Operazione completata!');
    }

    private function createTestArticles()
    {
        // Ottieni un utente admin o il primo utente disponibile
        $user = User::whereHas('roles', function($q) {
            $q->where('name', 'admin');
        })->first() ?? User::first();

        if (!$user) {
            $this->error('❌ Nessun utente trovato per creare gli articoli');
            return;
        }

        $testArticles = [
            [
                'title' => 'Storia del Poetry Slam in Europa',
                'content' => 'Il Poetry Slam è nato negli Stati Uniti negli anni \'80 e si è diffuso rapidamente in Europa. Questo movimento artistico ha portato una nuova forma di espressione poetica che combina performance, competizione e community building. In Europa, il Poetry Slam ha trovato terreno fertile, specialmente in Germania, Francia e Italia, dove ha sviluppato caratteristiche uniche e locali.',
                'excerpt' => 'Un viaggio attraverso la storia del Poetry Slam in Europa, dalle origini americane alle evoluzioni europee.',
                'status' => 'published',
                'is_public' => true,
                'featured' => true,
            ],
            [
                'title' => 'Come Scrivere Testi Emotivamente Coinvolgenti',
                'content' => 'La scrittura di testi emotivamente coinvolgenti richiede una profonda comprensione delle emozioni umane e delle tecniche narrative. È importante creare connessioni autentiche con il pubblico attraverso l\'uso di immagini vivide, metafore potenti e un linguaggio che risuoni con l\'esperienza comune. La chiave è l\'autenticità e la capacità di toccare le corde emotive del pubblico.',
                'excerpt' => 'Tecniche e consigli per creare testi che emozionano e coinvolgono il pubblico.',
                'status' => 'published',
                'is_public' => true,
                'featured' => false,
            ],
            [
                'title' => 'Come Costruire una Community di Poetry Slam',
                'content' => 'Costruire una community di Poetry Slam richiede passione, dedizione e una visione chiara. È importante creare spazi inclusivi dove tutti si sentano benvenuti, indipendentemente dal loro livello di esperienza. Organizzare eventi regolari, workshop e momenti di condivisione aiuta a rafforzare i legami tra i membri della community.',
                'excerpt' => 'Strategie e consigli per creare e mantenere una community attiva di Poetry Slam.',
                'status' => 'published',
                'is_public' => true,
                'featured' => false,
            ],
            [
                'title' => 'Poetry Slam e Social Media: Una Guida Completa',
                'content' => 'I social media offrono infinite possibilità per promuovere il Poetry Slam e raggiungere nuovi pubblici. Instagram, TikTok e YouTube sono piattaforme ideali per condividere performance, backstage e contenuti educativi. È importante creare contenuti autentici che riflettano i valori del Poetry Slam: inclusività, creatività e community.',
                'excerpt' => 'Come utilizzare i social media per promuovere il Poetry Slam e costruire una community online.',
                'status' => 'published',
                'is_public' => true,
                'featured' => true,
            ],
            [
                'title' => 'Workshop di Scrittura Creativa per Poetry Slam',
                'content' => 'I workshop di scrittura creativa sono fondamentali per sviluppare le competenze dei poeti slam. Questi momenti di formazione permettono di esplorare diverse tecniche narrative, lavorare sulla struttura dei testi e migliorare la capacità di comunicazione. Un buon workshop combina teoria e pratica, offrendo esercizi guidati e feedback costruttivi.',
                'excerpt' => 'Organizzazione e gestione di workshop efficaci per la scrittura creativa nel Poetry Slam.',
                'status' => 'published',
                'is_public' => true,
                'featured' => false,
            ],
        ];

        foreach ($testArticles as $index => $articleData) {
            $article = Article::create([
                'user_id' => $user->id,
                'title' => $articleData['title'],
                'content' => $articleData['content'],
                'excerpt' => $articleData['excerpt'],
                'status' => $articleData['status'],
                'is_public' => $articleData['is_public'],
                'featured' => $articleData['featured'],
                'moderation_status' => 'approved', // Approvato per essere visibile
                'featured_image' => null, // Nessuna immagine per testare i placeholder
                'published_at' => now()->subDays(rand(1, 30)),
                'views_count' => rand(1, 100),
                'likes_count' => rand(0, 50),
                'comments_count' => rand(0, 20),
            ]);

            $this->info("✅ Creato: {$article->title}");
        }

        $this->info("🎯 Creati " . count($testArticles) . " articoli di test senza immagini");
    }
}
