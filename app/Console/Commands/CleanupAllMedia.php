<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Video;
use App\Models\Poem;
use App\Models\Event;
use App\Models\Group;
use App\Models\User;
use App\Models\Notification;
use App\Models\ActivityLog;
use App\Models\Wishlist;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\ChatParticipant;
use App\Models\GroupMember;
use App\Models\GroupInvitation;
use App\Models\GroupJoinRequest;
use App\Models\EventInvitation;
use App\Models\EventRequest;
use App\Models\VideoSnap;
use App\Models\VideoLike;
use App\Models\VideoComment;
use App\Models\PoemComment;
use App\Models\Report;
use App\Models\Task;
use App\Models\TaskComment;
use App\Models\Photo;
use App\Models\Carousel;
use App\Models\RecentVenue;
use App\Services\PeerTubeService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CleanupAllMedia extends Command
{
    protected $signature = 'media:cleanup-all {--force} {--dry-run} {--keep-users} {--keep-events} {--keep-kanban}';
    protected $description = 'Elimina tutti i media dal sito per partire da una situazione pulita';

    public function handle()
    {
        $force = $this->option('force');
        $dryRun = $this->option('dry-run');
        $keepUsers = $this->option('keep-users');
        $keepEvents = $this->option('keep-events');
        $keepKanban = $this->option('keep-kanban');

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

        // 7. ELIMINAZIONE NOTIFICHE
        $this->cleanupNotifications($dryRun);

        // 8. ELIMINAZIONE LOG ATTIVITÀ
        $this->cleanupActivityLogs($dryRun);

        // 9. ELIMINAZIONE DATI SOCIAL
        $this->cleanupSocialData($dryRun);

        // 10. ELIMINAZIONE DATI GRUPPI
        $this->cleanupGroupData($dryRun);

        // 11. ELIMINAZIONE DATI EVENTI
        $this->cleanupEventData($dryRun);

        // 12. ELIMINAZIONE DATI VIDEO
        $this->cleanupVideoData($dryRun);

        // 13. ELIMINAZIONE DATI POESIE
        $this->cleanupPoemData($dryRun);

        // 14. ELIMINAZIONE ALTRI DATI (escluso kanban se richiesto)
        if (!$keepKanban) {
            $this->cleanupOtherData($dryRun);
        } else {
            $this->info('📋 KANBAN MANTENUTO (--keep-kanban)');
        }

        // 16. PULIZIA FILESYSTEM
        $this->cleanupFilesystem($dryRun);

        // 17. RESET AUTO-INCREMENT
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
        $keepUsers = $this->option('keep-users');
        $keepEvents = $this->option('keep-events');
        $keepKanban = $this->option('keep-kanban');

        $this->info('📊 STATISTICHE ATUALI:');
        $this->line("   📹 Video: " . Video::count());
        $this->line("   📝 Poesie: " . Poem::count());
        $this->line("   📅 Eventi: " . Event::count() . ($keepEvents ? ' (MANTENUTI)' : ''));
        $this->line("   👥 Gruppi: " . Group::count());
        $this->line("   👤 Utenti: " . User::count() . ($keepUsers ? ' (MANTENUTI)' : ''));
        $this->line("   🔔 Notifiche: " . Notification::count());
        $this->line("   📊 Log attività: " . ActivityLog::count());
        $this->line("   ❤️ Wishlist: " . Wishlist::count());
        $this->line("   💬 Chat: " . Chat::count());
        $this->line("   💬 Messaggi chat: " . ChatMessage::count());
        $this->line("   👥 Membri gruppi: " . GroupMember::count());
        $this->line("   📨 Inviti gruppi: " . GroupInvitation::count());
        $this->line("   📨 Richieste gruppi: " . GroupJoinRequest::count());
        $this->line("   📨 Inviti eventi: " . EventInvitation::count());
        $this->line("   📨 Richieste eventi: " . EventRequest::count());
        $this->line("   📸 Snap video: " . VideoSnap::count());
        $this->line("   👍 Like video: " . VideoLike::count());
        $this->line("   💬 Commenti video: " . VideoComment::count());
        $this->line("   💬 Commenti poesie: " . PoemComment::count());
        $this->line("   🚨 Segnalazioni: " . Report::count());
        $this->line("   ✅ Task: " . Task::count() . ($keepKanban ? ' (MANTENUTI)' : ''));
        $this->line("   💬 Commenti task: " . TaskComment::count() . ($keepKanban ? ' (MANTENUTI)' : ''));
        $this->line("   📷 Foto: " . Photo::count());
        $this->line("   🎠 Carousel: " . Carousel::count());
        $this->line("   🏢 Venue recenti: " . RecentVenue::count());
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

        $peerTubeService = new PeerTubeService();
        $deletedCount = 0;
        $peerTubeDeletedCount = 0;

        foreach ($users as $user) {
            try {
                // Elimina da PeerTube se ha account
                if ($user->peertube_user_id) {
                    $peerTubeDeleted = $peerTubeService->deleteUser($user->peertube_user_id);
                    if ($peerTubeDeleted) {
                        $peerTubeDeletedCount++;
                        $this->line("   🔗 Eliminato da PeerTube: {$user->name} (ID: {$user->peertube_user_id})");
                    } else {
                        $this->line("   ⚠️ Impossibile eliminare da PeerTube: {$user->name} (ID: {$user->peertube_user_id})");
                    }
                }

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

        $this->info("   📊 Risultati: {$deletedCount} utenti eliminati, {$peerTubeDeletedCount} da PeerTube");
    }

    private function cleanupNotifications($dryRun)
    {
        $this->info('🔔 ELIMINAZIONE NOTIFICHE...');
        
        $notifications = Notification::all();
        $this->line("   Trovate {$notifications->count()} notifiche");

        if ($dryRun) {
            foreach ($notifications as $notification) {
                $this->line("   🔍 Eliminerebbe: {$notification->title} (ID: {$notification->id})");
            }
            return;
        }

        $deletedCount = 0;
        foreach ($notifications as $notification) {
            try {
                $notification->delete();
                $deletedCount++;
            } catch (\Exception $e) {
                $this->error("   ❌ Errore eliminazione notifica {$notification->id}: " . $e->getMessage());
            }
        }

        $this->info("   📊 Risultati: {$deletedCount} notifiche eliminate");
    }

    private function cleanupActivityLogs($dryRun)
    {
        $this->info('📊 ELIMINAZIONE LOG ATTIVITÀ...');
        
        $logs = ActivityLog::all();
        $this->line("   Trovati {$logs->count()} log attività");

        if ($dryRun) {
            foreach ($logs as $log) {
                $this->line("   🔍 Eliminerebbe: {$log->action} (ID: {$log->id})");
            }
            return;
        }

        $deletedCount = 0;
        foreach ($logs as $log) {
            try {
                $log->delete();
                $deletedCount++;
            } catch (\Exception $e) {
                $this->error("   ❌ Errore eliminazione log {$log->id}: " . $e->getMessage());
            }
        }

        $this->info("   📊 Risultati: {$deletedCount} log attività eliminati");
    }

    private function cleanupSocialData($dryRun)
    {
        $this->info('💬 ELIMINAZIONE DATI SOCIAL...');

        // Wishlist
        $wishlists = Wishlist::all();
        $this->line("   Trovate {$wishlists->count()} wishlist");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($wishlists as $wishlist) {
                try {
                    $wishlist->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione wishlist {$wishlist->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} wishlist eliminate");
        }

        // Chat
        $chats = Chat::all();
        $this->line("   Trovate {$chats->count()} chat");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($chats as $chat) {
                try {
                    $chat->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione chat {$chat->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} chat eliminate");
        }

        // Chat Messages
        $messages = ChatMessage::all();
        $this->line("   Trovati {$messages->count()} messaggi chat");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($messages as $message) {
                try {
                    $message->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione messaggio {$message->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} messaggi eliminati");
        }

        // Chat Participants
        $participants = ChatParticipant::all();
        $this->line("   Trovati {$participants->count()} partecipanti chat");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($participants as $participant) {
                try {
                    $participant->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione partecipante {$participant->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} partecipanti eliminati");
        }
    }

    private function cleanupGroupData($dryRun)
    {
        $this->info('👥 ELIMINAZIONE DATI GRUPPI...');

        // Group Members
        $members = GroupMember::all();
        $this->line("   Trovati {$members->count()} membri gruppi");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($members as $member) {
                try {
                    $member->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione membro {$member->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} membri eliminati");
        }

        // Group Invitations
        $invitations = GroupInvitation::all();
        $this->line("   Trovati {$invitations->count()} inviti gruppi");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($invitations as $invitation) {
                try {
                    $invitation->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione invito {$invitation->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} inviti eliminati");
        }

        // Group Join Requests
        $requests = GroupJoinRequest::all();
        $this->line("   Trovate {$requests->count()} richieste gruppi");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($requests as $request) {
                try {
                    $request->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione richiesta {$request->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} richieste eliminate");
        }
    }

    private function cleanupEventData($dryRun)
    {
        $this->info('📅 ELIMINAZIONE DATI EVENTI...');

        // Event Invitations
        $invitations = EventInvitation::all();
        $this->line("   Trovati {$invitations->count()} inviti eventi");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($invitations as $invitation) {
                try {
                    $invitation->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione invito {$invitation->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} inviti eliminati");
        }

        // Event Requests
        $requests = EventRequest::all();
        $this->line("   Trovate {$requests->count()} richieste eventi");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($requests as $request) {
                try {
                    $request->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione richiesta {$request->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} richieste eliminate");
        }
    }

    private function cleanupVideoData($dryRun)
    {
        $this->info('📹 ELIMINAZIONE DATI VIDEO...');

        // Video Snaps
        $snaps = VideoSnap::all();
        $this->line("   Trovati {$snaps->count()} snap video");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($snaps as $snap) {
                try {
                    $snap->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione snap {$snap->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} snap eliminati");
        }

        // Video Likes
        $likes = VideoLike::all();
        $this->line("   Trovati {$likes->count()} like video");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($likes as $like) {
                try {
                    $like->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione like {$like->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} like eliminati");
        }

        // Video Comments
        $comments = VideoComment::all();
        $this->line("   Trovati {$comments->count()} commenti video");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($comments as $comment) {
                try {
                    $comment->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione commento {$comment->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} commenti eliminati");
        }
    }

    private function cleanupPoemData($dryRun)
    {
        $this->info('📝 ELIMINAZIONE DATI POESIE...');

        // Poem Comments
        $comments = PoemComment::all();
        $this->line("   Trovati {$comments->count()} commenti poesie");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($comments as $comment) {
                try {
                    $comment->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione commento {$comment->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} commenti eliminati");
        }
    }

    private function cleanupOtherData($dryRun)
    {
        $keepKanban = $this->option('keep-kanban');
        $this->info('📋 ELIMINAZIONE ALTRI DATI...');

        // Reports
        $reports = Report::all();
        $this->line("   Trovate {$reports->count()} segnalazioni");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($reports as $report) {
                try {
                    $report->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione segnalazione {$report->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} segnalazioni eliminate");
        }

        // Tasks (solo se non keep-kanban)
        if (!$keepKanban) {
            $tasks = Task::all();
            $this->line("   Trovati {$tasks->count()} task");
            if (!$dryRun) {
                $deletedCount = 0;
                foreach ($tasks as $task) {
                    try {
                        $task->delete();
                        $deletedCount++;
                    } catch (\Exception $e) {
                        $this->error("   ❌ Errore eliminazione task {$task->id}: " . $e->getMessage());
                    }
                }
                $this->line("   ✅ {$deletedCount} task eliminati");
            }
        } else {
            $this->line("   ✅ Task mantenuti (--keep-kanban)");
        }

        // Task Comments (solo se non keep-kanban)
        if (!$keepKanban) {
            $comments = TaskComment::all();
            $this->line("   Trovati {$comments->count()} commenti task");
            if (!$dryRun) {
                $deletedCount = 0;
                foreach ($comments as $comment) {
                    try {
                        $comment->delete();
                        $deletedCount++;
                    } catch (\Exception $e) {
                        $this->error("   ❌ Errore eliminazione commento task {$comment->id}: " . $e->getMessage());
                    }
                }
                $this->line("   ✅ {$deletedCount} commenti task eliminati");
            }
        } else {
            $this->line("   ✅ Commenti task mantenuti (--keep-kanban)");
        }

        // Photos
        $photos = Photo::all();
        $this->line("   Trovate {$photos->count()} foto");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($photos as $photo) {
                try {
                    $photo->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione foto {$photo->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} foto eliminate");
        }

        // Carousels
        $carousels = Carousel::all();
        $this->line("   Trovati {$carousels->count()} carousel");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($carousels as $carousel) {
                try {
                    $carousel->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione carousel {$carousel->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} carousel eliminati");
        }

        // Recent Venues
        $venues = RecentVenue::all();
        $this->line("   Trovati {$venues->count()} venue recenti");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($venues as $venue) {
                try {
                    $venue->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione venue {$venue->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} venue eliminati");
        }
    }

    private function cleanupFilesystem($dryRun)
    {
        $this->info('🗂️ PULIZIA FILESYSTEM...');

        $directories = [
            'videos',
            'thumbnails',
            'avatars',
            'banners',
            'events',
            'groups',
            'poems'
        ];

        foreach ($directories as $dir) {
            try {
                if (Storage::disk('public')->exists($dir)) {
                    if ($dryRun) {
                        $files = Storage::disk('public')->files($dir);
                        $this->line("   🔍 Eliminerebbe {$dir} (" . count($files) . " file)");
                    } else {
                        $files = Storage::disk('public')->files($dir);
                        foreach ($files as $file) {
                            Storage::disk('public')->delete($file);
                        }
                        $this->line("   ✅ Pulito: {$dir} (" . count($files) . " file eliminati)");
                    }
                } else {
                    if ($dryRun) {
                        $this->line("   🔍 Directory {$dir} non esiste");
                    }
                }
            } catch (\Exception $e) {
                $this->error("   ❌ Errore pulizia {$dir}: " . $e->getMessage());
            }
        }
    }

    private function resetAutoIncrement()
    {
        $keepUsers = $this->option('keep-users');
        $keepEvents = $this->option('keep-events');
        $keepKanban = $this->option('keep-kanban');
        
        $this->info('🔄 RESET AUTO-INCREMENT...');

        $tables = [
            'videos', 'poems', 'groups',
            'notifications', 'activity_logs', 'wishlists',
            'chats', 'chat_messages', 'chat_participants',
            'group_members', 'group_invitations', 'group_join_requests',
            'video_snaps', 'video_likes', 'video_comments',
            'poem_comments',
            'reports', 'photos', 'carousels', 'recent_venues'
        ];

        // Aggiungi tabelle condizionalmente
        if (!$keepEvents) {
            $tables[] = 'events';
            $tables[] = 'event_invitations';
            $tables[] = 'event_requests';
        }
        
        if (!$keepUsers) {
            $tables[] = 'users';
        }
        
        if (!$keepKanban) {
            $tables[] = 'tasks';
            $tables[] = 'task_comments';
        }
        
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