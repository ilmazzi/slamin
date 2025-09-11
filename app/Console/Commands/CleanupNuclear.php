<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class CleanupNuclear extends Command
{
    protected $signature = 'db:cleanup-nuclear {--force} {--dry-run}';
    protected $description = 'PULIZIA NUCLEARE - Elimina TUTTO tranne utenti e configurazioni di sistema';

    public function handle()
    {
        $force = $this->option('force');
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->info('🔍 MODALITÀ DRY-RUN: Nessuna eliminazione verrà eseguita');
        }

        $this->line('');
        $this->line('💥 PULIZIA NUCLEARE DATABASE');
        $this->line('');

        // Mostra statistiche attuali
        $this->showCurrentStats();

        if (!$force && !$dryRun) {
            if (!$this->confirm('⚠️  ATTENZIONE: PULIZIA NUCLEARE! Eliminerà TUTTO tranne utenti e configurazioni. Continuare?')) {
                $this->info('Operazione annullata.');
                return;
            }
        }

        $this->line('');
        $this->line('🚀 Inizio pulizia NUCLEARE...');
        $this->line('');

        // Disabilita TUTTI i controlli
        if (!$dryRun) {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::statement('SET UNIQUE_CHECKS=0;');
            DB::statement('SET AUTOCOMMIT=0;');
            $this->line('🔓 Tutti i controlli disabilitati');
        }

        // Pulisci tutto tranne utenti e configurazioni
        $this->cleanupAllDataNuclear($dryRun);

        // Riabilita i controlli
        if (!$dryRun) {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            DB::statement('SET UNIQUE_CHECKS=1;');
            DB::statement('SET AUTOCOMMIT=1;');
            $this->line('🔒 Tutti i controlli riabilitati');
        }

        $this->line('');
        $this->line('✅ Pulizia NUCLEARE completata!');
        $this->line('');
        $this->showFinalStats();
    }

    private function showCurrentStats()
    {
        $this->line('📊 STATISTICHE ATUALI:');
        
        // Contenuti principali
        $this->line("   📹 Video: " . DB::table('videos')->count());
        $this->line("   📝 Poesie: " . DB::table('poems')->count());
        $this->line("   📰 Articoli: " . DB::table('articles')->count());
        $this->line("   📅 Eventi: " . DB::table('events')->count());
        $this->line("   👥 Gruppi: " . DB::table('groups')->count());
        
        // Articoli e correlati
        $this->line("   📂 Categorie articoli: " . DB::table('article_categories')->count());
        $this->line("   🏷️ Tag articoli: " . DB::table('article_tags')->count());
        $this->line("   📐 Layout articoli: " . DB::table('article_layouts')->count());
        $this->line("   🚨 Segnalazioni articoli: " . DB::table('article_reports')->count());
        $this->line("   💬 Commenti articoli: " . DB::table('article_comments')->count());
        $this->line("   ❤️ Like articoli: " . DB::table('article_likes')->count());
        
        // Modelli unificati
        $this->line("   ❤️ Like unificati: " . DB::table('unified_likes')->count());
        $this->line("   💬 Commenti unificati: " . DB::table('unified_comments')->count());
        $this->line("   👁️ Visualizzazioni unificate: " . DB::table('unified_views')->count());
        
        // Gruppi e correlati
        $this->line("   📢 Annunci gruppi: " . DB::table('group_announcements')->count());
        $this->line("   👥 Membri gruppi: " . DB::table('group_members')->count());
        $this->line("   📨 Inviti gruppi: " . DB::table('group_invitations')->count());
        $this->line("   📨 Richieste gruppi: " . DB::table('group_join_requests')->count());
        
        // Gig e correlati
        $this->line("   💼 Gig: " . DB::table('gigs')->count());
        $this->line("   📝 Candidature gig: " . DB::table('gig_applications')->count());
        $this->line("   🎯 Posizioni gig: " . DB::table('gig_positions')->count());
        
        // Eventi e correlati
        $this->line("   📨 Inviti eventi: " . DB::table('event_invitations')->count());
        $this->line("   📨 Richieste eventi: " . DB::table('event_requests')->count());
        $this->line("   📅 Risposte disponibilità: " . DB::table('event_availability_responses')->count());
        $this->line("   ⏰ Opzioni disponibilità: " . DB::table('event_availability_options')->count());
        
        // Chat e correlati
        $this->line("   💬 Chat room: " . DB::table('chat_rooms')->count());
        $this->line("   💬 Messaggi chat: " . DB::table('chat_messages')->count());
        $this->line("   👥 Partecipanti chat: " . DB::table('chat_participants')->count());
        $this->line("   😀 Reazioni messaggi: " . DB::table('message_reactions')->count());
        $this->line("   👁️ Messaggi letti: " . DB::table('chat_message_reads')->count());
        $this->line("   😀 Reazioni chat: " . DB::table('chat_message_reactions')->count());
        
        // Video e correlati
        $this->line("   📸 Snap video: " . DB::table('video_snaps')->count());
        $this->line("   👍 Like video: " . DB::table('video_likes')->count());
        $this->line("   💬 Commenti video: " . DB::table('video_comments')->count());
        
        // Poesie e correlati
        $this->line("   💬 Commenti poesie: " . DB::table('poem_comments')->count());
        $this->line("   🌐 Traduzioni poesie: " . DB::table('poem_translations')->count());
        $this->line("   💬 Negoziazioni traduzioni: " . DB::table('poem_translation_negotiations')->count());
        $this->line("   💰 Pagamenti traduzioni: " . DB::table('translation_payments')->count());
        
        // Altri contenuti
        $this->line("   📸 Foto: " . DB::table('photos')->count());
        $this->line("   🎠 Carousel: " . DB::table('carousels')->count());
        $this->line("   📍 Venue recenti: " . DB::table('recent_venues')->count());
        $this->line("   ✅ Task: " . DB::table('tasks')->count() . ' (MANTENUTI)');
        $this->line("   💬 Commenti task: " . DB::table('task_comments')->count());
        
        // Interazioni e social
        $this->line("   👥 Follow: " . DB::table('follows')->count());
        $this->line("   ❤️ Wishlist: " . DB::table('wishlists')->count());
        $this->line("   🚨 Segnalazioni: " . DB::table('reports')->count());
        
        // Moderazione
        $this->line("   🚨 Messaggi moderazione: " . DB::table('moderation_messages')->count());
        $this->line("   💬 Conversazioni moderazione: " . DB::table('moderation_conversations')->count());
        
        // Sistema
        $this->line("   🔔 Notifiche: " . DB::table('notifications')->count());
        $this->line("   📊 Log attività: " . DB::table('activity_logs')->count());
        $this->line("   📊 Attività: " . DB::table('activities')->count());
        $this->line("   ⚙️ Preferenze notifiche: " . DB::table('user_notification_preferences')->count());
        $this->line("   🎨 Impostazioni placeholder: " . DB::table('placeholder_settings')->count());
        $this->line("   📦 Pacchetti: " . DB::table('packages')->count());
        
        // Mantenuti
        $this->line("   👤 Utenti: " . DB::table('users')->count() . ' (MANTENUTI)');
        $this->line("   ⚙️ Configurazioni: " . DB::table('system_settings')->count() . ' (MANTENUTE)');
        $this->line("   💳 Sottoscrizioni: " . DB::table('user_subscriptions')->count() . ' (MANTENUTE)');
        $this->line("   🌐 Lingue utenti: " . DB::table('user_languages')->count() . ' (MANTENUTE)');
        $this->line('');
    }

    private function showFinalStats()
    {
        $this->line('📊 STATISTICHE FINALI:');
        
        // Contenuti principali
        $this->line("   📹 Video: " . DB::table('videos')->count());
        $this->line("   📝 Poesie: " . DB::table('poems')->count());
        $this->line("   📰 Articoli: " . DB::table('articles')->count());
        $this->line("   📅 Eventi: " . DB::table('events')->count());
        $this->line("   👥 Gruppi: " . DB::table('groups')->count());
        
        // Articoli e correlati
        $this->line("   📂 Categorie articoli: " . DB::table('article_categories')->count());
        $this->line("   🏷️ Tag articoli: " . DB::table('article_tags')->count());
        $this->line("   📐 Layout articoli: " . DB::table('article_layouts')->count());
        $this->line("   🚨 Segnalazioni articoli: " . DB::table('article_reports')->count());
        $this->line("   💬 Commenti articoli: " . DB::table('article_comments')->count());
        $this->line("   ❤️ Like articoli: " . DB::table('article_likes')->count());
        
        // Modelli unificati
        $this->line("   ❤️ Like unificati: " . DB::table('unified_likes')->count());
        $this->line("   💬 Commenti unificati: " . DB::table('unified_comments')->count());
        $this->line("   👁️ Visualizzazioni unificate: " . DB::table('unified_views')->count());
        
        // Gruppi e correlati
        $this->line("   📢 Annunci gruppi: " . DB::table('group_announcements')->count());
        $this->line("   👥 Membri gruppi: " . DB::table('group_members')->count());
        $this->line("   📨 Inviti gruppi: " . DB::table('group_invitations')->count());
        $this->line("   📨 Richieste gruppi: " . DB::table('group_join_requests')->count());
        
        // Gig e correlati
        $this->line("   💼 Gig: " . DB::table('gigs')->count());
        $this->line("   📝 Candidature gig: " . DB::table('gig_applications')->count());
        $this->line("   🎯 Posizioni gig: " . DB::table('gig_positions')->count());
        
        // Eventi e correlati
        $this->line("   📨 Inviti eventi: " . DB::table('event_invitations')->count());
        $this->line("   📨 Richieste eventi: " . DB::table('event_requests')->count());
        $this->line("   📅 Risposte disponibilità: " . DB::table('event_availability_responses')->count());
        $this->line("   ⏰ Opzioni disponibilità: " . DB::table('event_availability_options')->count());
        
        // Chat e correlati
        $this->line("   💬 Chat room: " . DB::table('chat_rooms')->count());
        $this->line("   💬 Messaggi chat: " . DB::table('chat_messages')->count());
        $this->line("   👥 Partecipanti chat: " . DB::table('chat_participants')->count());
        $this->line("   😀 Reazioni messaggi: " . DB::table('message_reactions')->count());
        $this->line("   👁️ Messaggi letti: " . DB::table('chat_message_reads')->count());
        $this->line("   😀 Reazioni chat: " . DB::table('chat_message_reactions')->count());
        
        // Video e correlati
        $this->line("   📸 Snap video: " . DB::table('video_snaps')->count());
        $this->line("   👍 Like video: " . DB::table('video_likes')->count());
        $this->line("   💬 Commenti video: " . DB::table('video_comments')->count());
        
        // Poesie e correlati
        $this->line("   💬 Commenti poesie: " . DB::table('poem_comments')->count());
        $this->line("   🌐 Traduzioni poesie: " . DB::table('poem_translations')->count());
        $this->line("   💬 Negoziazioni traduzioni: " . DB::table('poem_translation_negotiations')->count());
        $this->line("   💰 Pagamenti traduzioni: " . DB::table('translation_payments')->count());
        
        // Altri contenuti
        $this->line("   📸 Foto: " . DB::table('photos')->count());
        $this->line("   🎠 Carousel: " . DB::table('carousels')->count());
        $this->line("   📍 Venue recenti: " . DB::table('recent_venues')->count());
        $this->line("   ✅ Task: " . DB::table('tasks')->count() . ' (MANTENUTI)');
        $this->line("   💬 Commenti task: " . DB::table('task_comments')->count());
        
        // Interazioni e social
        $this->line("   👥 Follow: " . DB::table('follows')->count());
        $this->line("   ❤️ Wishlist: " . DB::table('wishlists')->count());
        $this->line("   🚨 Segnalazioni: " . DB::table('reports')->count());
        
        // Moderazione
        $this->line("   🚨 Messaggi moderazione: " . DB::table('moderation_messages')->count());
        $this->line("   💬 Conversazioni moderazione: " . DB::table('moderation_conversations')->count());
        
        // Sistema
        $this->line("   🔔 Notifiche: " . DB::table('notifications')->count());
        $this->line("   📊 Log attività: " . DB::table('activity_logs')->count());
        $this->line("   📊 Attività: " . DB::table('activities')->count());
        $this->line("   ⚙️ Preferenze notifiche: " . DB::table('user_notification_preferences')->count());
        $this->line("   🎨 Impostazioni placeholder: " . DB::table('placeholder_settings')->count());
        $this->line("   📦 Pacchetti: " . DB::table('packages')->count());
        
        // Mantenuti
        $this->line("   👤 Utenti: " . DB::table('users')->count() . ' (MANTENUTI)');
        $this->line("   ⚙️ Configurazioni: " . DB::table('system_settings')->count() . ' (MANTENUTE)');
        $this->line("   💳 Sottoscrizioni: " . DB::table('user_subscriptions')->count() . ' (MANTENUTE)');
        $this->line("   🌐 Lingue utenti: " . DB::table('user_languages')->count() . ' (MANTENUTE)');
        $this->line('');
    }

    private function cleanupAllDataNuclear($dryRun)
    {
        $this->line('💥 Eliminazione NUCLEARE di tutti i dati...');

        // Lista di tutte le tabelle da pulire (escluso utenti e configurazioni)
        // ORDINE IMPORTANTE: prima le relazioni, poi le tabelle principali
        $tablesToClean = [
            // 1. PRIMA: Relazioni e dipendenze
            'article_comments', 'article_likes', 'article_reports', 'article_tag',
            'article_layouts', 'article_tags', 'article_categories',
            'unified_likes', 'unified_comments', 'unified_views',
            
            // 2. POI: Contenuti principali
            'videos', 'poems', 'articles', 'events', 'groups',
            
            // Gruppi e correlati
            'group_announcements', 'group_members', 'group_invitations', 'group_join_requests',
            
            // Gig e correlati
            'gigs', 'gig_applications', 'gig_positions',
            
            // Eventi e correlati
            'event_invitations', 'event_requests', 'event_availability_responses', 'event_availability_options',
            
            // Chat e correlati
            'chat_rooms', 'chat_messages', 'chat_participants', 'message_reactions',
            'chat_message_reads', 'chat_message_reactions',
            
            // Video e correlati
            'video_snaps', 'video_likes', 'video_comments',
            
            // Poesie e correlati
            'poem_comments', 'poem_translations', 'poem_translation_negotiations', 'translation_payments',
            
            // Altri contenuti
            'photos', 'carousels', 'recent_venues', 'task_comments',
            
            // Interazioni e social
            'follows', 'wishlists', 'reports',
            
            // Moderazione
            'moderation_messages', 'moderation_conversations',
            
            // Sistema (ordine importante: prima activities, poi activity_logs)
            'activities', 'activity_logs', 'notifications', 'user_notification_preferences',
            'placeholder_settings', 'packages',
        ];

        foreach ($tablesToClean as $table) {
            try {
                $count = DB::table($table)->count();
                $this->line("   Trovati {$count} record in {$table}");
                
                if (!$dryRun && $count > 0) {
                    // ELIMINAZIONE NUCLEARE - usa DELETE FROM per essere sicuri
                    DB::statement("DELETE FROM {$table}");
                    $this->line("   💥 {$count} record ELIMINATI NUCLEARMENTE da {$table}");
                } elseif ($dryRun) {
                    $this->line("   🔍 DRY-RUN: Eliminerebbe NUCLEARMENTE {$count} record da {$table}");
                }
            } catch (\Exception $e) {
                $this->error("   ❌ Errore eliminazione NUCLEARE {$table}: " . $e->getMessage());
            }
        }

        // Pulisci cache e file temporanei
        if (!$dryRun) {
            $this->line('');
            $this->line('🧹 Pulizia cache e file temporanei...');
            
            try {
                // Pulisci cache Laravel
                Artisan::call('cache:clear');
                Artisan::call('config:clear');
                Artisan::call('route:clear');
                Artisan::call('view:clear');
                
                $this->line('   ✅ Cache Laravel pulita');
            } catch (\Exception $e) {
                $this->error('   ❌ Errore pulizia cache: ' . $e->getMessage());
            }

            // Pulisci file temporanei di upload
            try {
                $tempDirs = ['temp', 'uploads/temp', 'public/temp'];
                foreach ($tempDirs as $dir) {
                    if (Storage::exists($dir)) {
                        $files = Storage::allFiles($dir);
                        foreach ($files as $file) {
                            Storage::delete($file);
                        }
                        $this->line("   ✅ File temporanei eliminati da {$dir}");
                    }
                }
            } catch (\Exception $e) {
                $this->error('   ❌ Errore pulizia file temporanei: ' . $e->getMessage());
            }
        }
    }
}
