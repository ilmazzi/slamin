<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Video;
use App\Models\Poem;
use App\Models\Event;
use App\Models\Group;
use App\Models\User;
use App\Services\PeerTubeService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CleanupAllMedia extends Command
{
    protected $signature = 'media:cleanup-all {--force} {--dry-run} {--keep-users} {--keep-events}';
    protected $description = 'Elimina tutti i media dal sito per partire da una situazione pulita';

    public function handle()
    {
        $force = $this->option('force');
        $dryRun = $this->option('dry-run');
        $keepUsers = $this->option('keep-users');
        $keepEvents = $this->option('keep-events');

        if ($dryRun) {
            $this->info('🔍 MODALITÀ DRY-RUN: Nessuna eliminazione verrà eseguita');
        }

        $this->info('🧹 PULIZIA COMPLETA DEI MEDIA');
        $this->line('');

        // 1. STATISTICHE INIZIALI
        $this->showStatistics();

        if (!$force && !$dryRun && !$this->confirm('Sei SICURO di voler eliminare TUTTI i media? Questa operazione è IRREVERSIBILE!')) {
            $this->error('❌ Operazione annullata');
            return 1;
        }

        // 2. ELIMINAZIONE VIDEO E PEERTUBE
        $this->cleanupVideos($dryRun);

        // 3. ELIMINAZIONE POESIE
        $this->cleanupPoems($dryRun);

        // 4. ELIMINAZIONE EVENTI (se richiesto)
        if (!$keepEvents) {
            $this->cleanupEvents($dryRun);
        }

        // 5. ELIMINAZIONE GRUPPI
        $this->cleanupGroups($dryRun);

        // 6. ELIMINAZIONE UTENTI (se richiesto)
        if (!$keepUsers) {
            $this->cleanupUsers($dryRun);
        }

        // 7. PULIZIA FILESYSTEM
        $this->cleanupFilesystem($dryRun);

        // 8. RESET AUTO-INCREMENT
        if (!$dryRun) {
            $this->resetAutoIncrement();
        }

        $this->info('');
        $this->info('🎉 PULIZIA COMPLETATA!');
        
        if ($dryRun) {
            $this->warn('⚠️ Modalità dry-run: nessuna modifica è stata applicata');
        } else {
            $this->info('✅ Tutti i media sono stati eliminati');
        }

        return 0;
    }

    private function showStatistics()
    {
        $this->info('📊 STATISTICHE ATUALI:');
        $this->line("   📹 Video: " . Video::count());
        $this->line("   📝 Poesie: " . Poem::count());
        $this->line("   📅 Eventi: " . Event::count());
        $this->line("   👥 Gruppi: " . Group::count());
        $this->line("   👤 Utenti: " . User::count());
        $this->line('');
    }

    private function cleanupVideos($dryRun)
    {
        $this->info('📹 ELIMINAZIONE VIDEO...');
        
        $videos = Video::all();
        $this->line("   Trovati {$videos->count()} video");

        if ($dryRun) {
            foreach ($videos as $video) {
                $this->line("   🔍 Eliminerebbe: {$video->title} (ID: {$video->id})");
            }
            return;
        }

        $peerTubeService = new PeerTubeService();
        $deletedCount = 0;
        $peerTubeDeletedCount = 0;

        foreach ($videos as $video) {
            try {
                // Elimina da PeerTube se ha UUID
                if ($video->peertube_uuid) {
                    $peerTubeDeleted = $peerTubeService->deleteVideoByUuid($video->peertube_uuid);
                    if ($peerTubeDeleted) {
                        $peerTubeDeletedCount++;
                    }
                }

                // Elimina thumbnail
                if ($video->thumbnail_path && Storage::disk('public')->exists($video->thumbnail_path)) {
                    Storage::disk('public')->delete($video->thumbnail_path);
                }

                // Elimina il video
                $video->delete();
                $deletedCount++;

                $this->line("   ✅ Eliminato: {$video->title}");
            } catch (\Exception $e) {
                $this->error("   ❌ Errore eliminazione video {$video->id}: " . $e->getMessage());
            }
        }

        $this->info("   📊 Risultati: {$deletedCount} video eliminati, {$peerTubeDeletedCount} da PeerTube");
    }

    private function cleanupPoems($dryRun)
    {
        $this->info('📝 ELIMINAZIONE POESIE...');
        
        $poems = Poem::all();
        $this->line("   Trovate {$poems->count()} poesie");

        if ($dryRun) {
            foreach ($poems as $poem) {
                $this->line("   🔍 Eliminerebbe: {$poem->title} (ID: {$poem->id})");
            }
            return;
        }

        $deletedCount = 0;
        foreach ($poems as $poem) {
            try {
                $poem->delete();
                $deletedCount++;
                $this->line("   ✅ Eliminata: {$poem->title}");
            } catch (\Exception $e) {
                $this->error("   ❌ Errore eliminazione poesia {$poem->id}: " . $e->getMessage());
            }
        }

        $this->info("   📊 Risultati: {$deletedCount} poesie eliminate");
    }

    private function cleanupEvents($dryRun)
    {
        $this->info('📅 ELIMINAZIONE EVENTI...');
        
        $events = Event::all();
        $this->line("   Trovati {$events->count()} eventi");

        if ($dryRun) {
            foreach ($events as $event) {
                $this->line("   🔍 Eliminerebbe: {$event->title} (ID: {$event->id})");
            }
            return;
        }

        $deletedCount = 0;
        foreach ($events as $event) {
            try {
                // Elimina immagine evento se presente
                if ($event->image && Storage::disk('public')->exists($event->image)) {
                    Storage::disk('public')->delete($event->image);
                }

                $event->delete();
                $deletedCount++;
                $this->line("   ✅ Eliminato: {$event->title}");
            } catch (\Exception $e) {
                $this->error("   ❌ Errore eliminazione evento {$event->id}: " . $e->getMessage());
            }
        }

        $this->info("   📊 Risultati: {$deletedCount} eventi eliminati");
    }

    private function cleanupGroups($dryRun)
    {
        $this->info('👥 ELIMINAZIONE GRUPPI...');
        
        $groups = Group::all();
        $this->line("   Trovati {$groups->count()} gruppi");

        if ($dryRun) {
            foreach ($groups as $group) {
                $this->line("   🔍 Eliminerebbe: {$group->name} (ID: {$group->id})");
            }
            return;
        }

        $deletedCount = 0;
        foreach ($groups as $group) {
            try {
                // Elimina immagine gruppo se presente
                if ($group->image && Storage::disk('public')->exists($group->image)) {
                    Storage::disk('public')->delete($group->image);
                }

                $group->delete();
                $deletedCount++;
                $this->line("   ✅ Eliminato: {$group->name}");
            } catch (\Exception $e) {
                $this->error("   ❌ Errore eliminazione gruppo {$group->id}: " . $e->getMessage());
            }
        }

        $this->info("   📊 Risultati: {$deletedCount} gruppi eliminati");
    }

    private function cleanupUsers($dryRun)
    {
        $this->info('👤 ELIMINAZIONE UTENTI...');
        
        $users = User::all();
        $this->line("   Trovati {$users->count()} utenti");

        if ($dryRun) {
            foreach ($users as $user) {
                $this->line("   🔍 Eliminerebbe: {$user->name} (ID: {$user->id})");
            }
            return;
        }

        $deletedCount = 0;
        foreach ($users as $user) {
            try {
                // Elimina avatar se presente
                if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                    Storage::disk('public')->delete($user->avatar);
                }

                // Elimina banner se presente
                if ($user->banner_image && Storage::disk('public')->exists($user->banner_image)) {
                    Storage::disk('public')->delete($user->banner_image);
                }

                $user->delete();
                $deletedCount++;
                $this->line("   ✅ Eliminato: {$user->name}");
            } catch (\Exception $e) {
                $this->error("   ❌ Errore eliminazione utente {$user->id}: " . $e->getMessage());
            }
        }

        $this->info("   📊 Risultati: {$deletedCount} utenti eliminati");
    }

    private function cleanupFilesystem($dryRun)
    {
        $this->info('🗂️ PULIZIA FILESYSTEM...');

        $directories = [
            'storage/app/public/videos',
            'storage/app/public/thumbnails',
            'storage/app/public/avatars',
            'storage/app/public/banners',
            'storage/app/public/events',
            'storage/app/public/groups',
            'storage/app/public/poems'
        ];

        foreach ($directories as $dir) {
            if (is_dir($dir)) {
                if ($dryRun) {
                    $files = glob($dir . '/*');
                    $this->line("   🔍 Eliminerebbe {$dir} (" . count($files) . " file)");
                } else {
                    try {
                        $files = glob($dir . '/*');
                        foreach ($files as $file) {
                            if (is_file($file)) {
                                unlink($file);
                            }
                        }
                        $this->line("   ✅ Pulito: {$dir}");
                    } catch (\Exception $e) {
                        $this->error("   ❌ Errore pulizia {$dir}: " . $e->getMessage());
                    }
                }
            }
        }
    }

    private function resetAutoIncrement()
    {
        $this->info('🔄 RESET AUTO-INCREMENT...');

        $tables = ['videos', 'poems', 'events', 'groups', 'users'];
        
        foreach ($tables as $table) {
            try {
                DB::statement("ALTER TABLE {$table} AUTO_INCREMENT = 1");
                $this->line("   ✅ Reset auto-increment per {$table}");
            } catch (\Exception $e) {
                $this->error("   ❌ Errore reset auto-increment per {$table}: " . $e->getMessage());
            }
        }
    }
} 