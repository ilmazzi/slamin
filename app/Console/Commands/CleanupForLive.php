<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Video;
use App\Models\Poem;
use App\Models\Article;
use App\Models\ArticleCategory;
use App\Models\ArticleTag;
use App\Models\ArticleLayout;
use App\Models\ArticleReport;
use App\Models\ArticleComment;
use App\Models\ArticleLike;
use App\Models\UnifiedLike;
use App\Models\UnifiedComment;
use App\Models\UnifiedView;
use App\Models\GroupAnnouncement;
use App\Models\Gig;
use App\Models\GigApplication;
use App\Models\GigPosition;
use App\Models\EventAvailabilityResponse;
use App\Models\EventAvailabilityOption;
use App\Models\Follow;
use App\Models\MessageReaction;
use App\Models\ChatMessageRead;
use App\Models\ChatMessageReaction;
use App\Models\ModerationMessage;
use App\Models\ModerationConversation;
use App\Models\PoemTranslation;
use App\Models\PoemTranslationNegotiation;
use App\Models\TranslationPayment;
use App\Models\UserNotificationPreference;
use App\Models\PlaceholderSetting;
use App\Models\Package;
use App\Models\Activity;
use App\Models\Event;
use App\Models\Group;
use App\Models\User;
use App\Models\Notification;
use App\Models\ActivityLog;
use App\Models\Wishlist;
use App\Models\ChatRoom;
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
use App\Models\SystemSetting;
use App\Models\UserSubscription;
use App\Models\UserLanguage;
use App\Services\PeerTubeService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class CleanupForLive extends Command
{
    protected $signature = 'db:cleanup-for-live {--force} {--dry-run}';
    protected $description = 'Svuota tutto il database mantenendo solo utenti e configurazioni per il live';

    public function handle()
    {
        $force = $this->option('force');
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->info('🔍 MODALITÀ DRY-RUN: Nessuna eliminazione verrà eseguita');
        }

        $this->line('');
        $this->line('🧹 PULIZIA DATABASE PER LIVE');
        $this->line('');

        // Mostra statistiche attuali
        $this->showCurrentStats();

        if (!$force && !$dryRun) {
            if (!$this->confirm('⚠️  ATTENZIONE: Questo comando eliminerà TUTTI i dati tranne utenti e configurazioni. Continuare?')) {
                $this->info('Operazione annullata.');
                return;
            }
        }

        $this->line('');
        $this->line('🚀 Inizio pulizia...');
        $this->line('');

        // Pulisci tutto tranne utenti e configurazioni
        $this->cleanupContent($dryRun);
        $this->cleanupRelations($dryRun);
        $this->cleanupMedia($dryRun);

        $this->line('');
        $this->line('✅ Pulizia completata!');
        $this->line('');
        $this->showFinalStats();
    }

    private function showCurrentStats()
    {
        $this->line('📊 STATISTICHE ATUALI:');
        $this->line("   📹 Video: " . Video::count());
        $this->line("   📝 Poesie: " . Poem::count());
        $this->line("   📰 Articoli: " . Article::count());
        $this->line("   📂 Categorie articoli: " . ArticleCategory::count());
        $this->line("   🏷️ Tag articoli: " . ArticleTag::count());
        $this->line("   📐 Layout articoli: " . ArticleLayout::count());
        $this->line("   🚨 Segnalazioni articoli: " . ArticleReport::count());
        $this->line("   💬 Commenti articoli: " . ArticleComment::count());
        $this->line("   ❤️ Like articoli: " . ArticleLike::count());
        $this->line("   ❤️ Like unificati: " . UnifiedLike::count());
        $this->line("   💬 Commenti unificati: " . UnifiedComment::count());
        $this->line("   👁️ Visualizzazioni unificate: " . UnifiedView::count());
        $this->line("   📅 Eventi: " . Event::count());
        $this->line("   👥 Gruppi: " . Group::count());
        $this->line("   👤 Utenti: " . User::count() . ' (MANTENUTI)');
        $this->line("   🔔 Notifiche: " . Notification::count());
        $this->line("   📊 Log attività: " . ActivityLog::count());
        $this->line("   ❤️ Wishlist: " . Wishlist::count());
        $this->line("   💬 Chat room: " . ChatRoom::count());
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
        $this->line("   ✅ Task: " . Task::count() . ' (MANTENUTI)');
        $this->line("   💬 Commenti task: " . TaskComment::count());
        $this->line("   📸 Foto: " . Photo::count());
        $this->line("   🎠 Carousel: " . Carousel::count());
        $this->line("   📍 Venue recenti: " . RecentVenue::count());
        $this->line("   ⚙️ Configurazioni: " . SystemSetting::count() . ' (MANTENUTE)');
        $this->line("   💳 Sottoscrizioni: " . UserSubscription::count() . ' (MANTENUTE)');
        $this->line("   🌐 Lingue utenti: " . UserLanguage::count() . ' (MANTENUTE)');
        $this->line('');
    }

    private function showFinalStats()
    {
        $this->line('📊 STATISTICHE FINALI:');
        $this->line("   📹 Video: " . Video::count());
        $this->line("   📝 Poesie: " . Poem::count());
        $this->line("   📰 Articoli: " . Article::count());
        $this->line("   📂 Categorie articoli: " . ArticleCategory::count());
        $this->line("   🏷️ Tag articoli: " . ArticleTag::count());
        $this->line("   📐 Layout articoli: " . ArticleLayout::count());
        $this->line("   🚨 Segnalazioni articoli: " . ArticleReport::count());
        $this->line("   💬 Commenti articoli: " . ArticleComment::count());
        $this->line("   ❤️ Like articoli: " . ArticleLike::count());
        $this->line("   ❤️ Like unificati: " . UnifiedLike::count());
        $this->line("   💬 Commenti unificati: " . UnifiedComment::count());
        $this->line("   👁️ Visualizzazioni unificate: " . UnifiedView::count());
        $this->line("   📅 Eventi: " . Event::count());
        $this->line("   👥 Gruppi: " . Group::count());
        $this->line("   👤 Utenti: " . User::count() . ' (MANTENUTI)');
        $this->line("   🔔 Notifiche: " . Notification::count());
        $this->line("   📊 Log attività: " . ActivityLog::count());
        $this->line("   ❤️ Wishlist: " . Wishlist::count());
        $this->line("   💬 Chat room: " . ChatRoom::count());
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
        $this->line("   ✅ Task: " . Task::count() . ' (MANTENUTI)');
        $this->line("   💬 Commenti task: " . TaskComment::count());
        $this->line("   📸 Foto: " . Photo::count());
        $this->line("   🎠 Carousel: " . Carousel::count());
        $this->line("   📍 Venue recenti: " . RecentVenue::count());
        $this->line("   ⚙️ Configurazioni: " . SystemSetting::count() . ' (MANTENUTE)');
        $this->line("   💳 Sottoscrizioni: " . UserSubscription::count() . ' (MANTENUTE)');
        $this->line("   🌐 Lingue utenti: " . UserLanguage::count() . ' (MANTENUTE)');
        $this->line('');
    }

    private function cleanupContent($dryRun)
    {
        $this->line('🗑️  Eliminazione contenuti...');

        // Video
        $videos = Video::all();
        $this->line("   Trovati {$videos->count()} video");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($videos as $video) {
                try {
                    // Elimina file fisici se esistono
                    if ($video->file_path && Storage::exists($video->file_path)) {
                        Storage::delete($video->file_path);
                    }
                    if ($video->thumbnail_path && Storage::exists($video->thumbnail_path)) {
                        Storage::delete($video->thumbnail_path);
                    }
                    $video->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione video {$video->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} video eliminati");
        }

        // Poesie
        $poems = Poem::all();
        $this->line("   Trovate {$poems->count()} poesie");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($poems as $poem) {
                try {
                    if ($poem->thumbnail_path && Storage::exists($poem->thumbnail_path)) {
                        Storage::delete($poem->thumbnail_path);
                    }
                    $poem->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione poesia {$poem->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} poesie eliminate");
        }

        // Articoli
        $articles = Article::all();
        $this->line("   Trovati {$articles->count()} articoli");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($articles as $article) {
                try {
                    if ($article->featured_image && Storage::exists($article->featured_image)) {
                        Storage::delete($article->featured_image);
                    }
                    $article->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione articolo {$article->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} articoli eliminati");
        }

        // Categorie articoli
        $categories = ArticleCategory::all();
        $this->line("   Trovate {$categories->count()} categorie articoli");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($categories as $category) {
                try {
                    $category->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione categoria articolo {$category->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} categorie articoli eliminate");
        }

        // Tag articoli
        $tags = ArticleTag::all();
        $this->line("   Trovati {$tags->count()} tag articoli");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($tags as $tag) {
                try {
                    $tag->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione tag articolo {$tag->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} tag articoli eliminati");
        }

        // Layout articoli
        $layouts = ArticleLayout::all();
        $this->line("   Trovati {$layouts->count()} layout articoli");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($layouts as $layout) {
                try {
                    $layout->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione layout articolo {$layout->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} layout articoli eliminati");
        }

        // Segnalazioni articoli
        $reports = ArticleReport::all();
        $this->line("   Trovate {$reports->count()} segnalazioni articoli");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($reports as $report) {
                try {
                    $report->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione segnalazione articolo {$report->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} segnalazioni articoli eliminate");
        }

        // Commenti articoli
        $articleComments = ArticleComment::all();
        $this->line("   Trovati {$articleComments->count()} commenti articoli");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($articleComments as $comment) {
                try {
                    $comment->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione commento articolo {$comment->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} commenti articoli eliminati");
        }

        // Like articoli
        $articleLikes = ArticleLike::all();
        $this->line("   Trovati {$articleLikes->count()} like articoli");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($articleLikes as $like) {
                try {
                    $like->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione like articolo {$like->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} like articoli eliminati");
        }

        // Like unificati
        $unifiedLikes = UnifiedLike::all();
        $this->line("   Trovati {$unifiedLikes->count()} like unificati");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($unifiedLikes as $like) {
                try {
                    $like->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione like unificato {$like->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} like unificati eliminati");
        }

        // Commenti unificati
        $unifiedComments = UnifiedComment::all();
        $this->line("   Trovati {$unifiedComments->count()} commenti unificati");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($unifiedComments as $comment) {
                try {
                    $comment->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione commento unificato {$comment->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} commenti unificati eliminati");
        }

        // Visualizzazioni unificate
        $unifiedViews = UnifiedView::all();
        $this->line("   Trovate {$unifiedViews->count()} visualizzazioni unificate");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($unifiedViews as $view) {
                try {
                    $view->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione visualizzazione unificata {$view->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} visualizzazioni unificate eliminate");
        }

        // Eventi
        $events = Event::all();
        $this->line("   Trovati {$events->count()} eventi");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($events as $event) {
                try {
                    $event->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione evento {$event->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} eventi eliminati");
        }

        // Gruppi
        $groups = Group::all();
        $this->line("   Trovati {$groups->count()} gruppi");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($groups as $group) {
                try {
                    if ($group->image && Storage::exists($group->image)) {
                        Storage::delete($group->image);
                    }
                    $group->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione gruppo {$group->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} gruppi eliminati");
        }

        // Task
        //$tasks = Task::all();
        //$this->line("   Trovati {$tasks->count()} task");
        /*if (!$dryRun) {
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
        }*/

        // Carousel
        $carousels = Carousel::all();
        $this->line("   Trovati {$carousels->count()} carousel");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($carousels as $carousel) {
                try {
                    if ($carousel->image_path && Storage::exists($carousel->image_path)) {
                        Storage::delete($carousel->image_path);
                    }
                    $carousel->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione carousel {$carousel->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} carousel eliminati");
        }

        // Foto
        $photos = Photo::all();
        $this->line("   Trovate {$photos->count()} foto");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($photos as $photo) {
                try {
                    if ($photo->file_path && Storage::exists($photo->file_path)) {
                        Storage::delete($photo->file_path);
                    }
                    $photo->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione foto {$photo->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} foto eliminate");
        }

        // Venue recenti
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
            $this->line("   ✅ {$deletedCount} venue recenti eliminate");
        }
    }

    private function cleanupRelations($dryRun)
    {
        $this->line('');
        $this->line('🔗 Eliminazione relazioni...');

        // Chat
        $chatRooms = ChatRoom::all();
        $this->line("   Trovate {$chatRooms->count()} chat room");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($chatRooms as $chatRoom) {
                try {
                    $chatRoom->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione chat room {$chatRoom->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} chat room eliminate");
        }

        // Chat Messages
        $messages = ChatMessage::all();
        $this->line("   Trovati {$messages->count()} messaggi chat");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($messages as $message) {
                try {
                    if ($message->file_path && Storage::exists($message->file_path)) {
                        Storage::delete($message->file_path);
                    }
                    $message->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione messaggio {$message->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} messaggi chat eliminati");
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
            $this->line("   ✅ {$deletedCount} partecipanti chat eliminati");
        }

        // Membri gruppi
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
            $this->line("   ✅ {$deletedCount} membri gruppi eliminati");
        }

        // Inviti gruppi
        $invitations = GroupInvitation::all();
        $this->line("   Trovati {$invitations->count()} inviti gruppi");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($invitations as $invitation) {
                try {
                    $invitation->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione invito gruppo {$invitation->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} inviti gruppi eliminati");
        }

        // Richieste gruppi
        $requests = GroupJoinRequest::all();
        $this->line("   Trovate {$requests->count()} richieste gruppi");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($requests as $request) {
                try {
                    $request->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione richiesta gruppo {$request->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} richieste gruppi eliminate");
        }

        // Inviti eventi
        $eventInvitations = EventInvitation::all();
        $this->line("   Trovati {$eventInvitations->count()} inviti eventi");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($eventInvitations as $invitation) {
                try {
                    $invitation->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione invito evento {$invitation->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} inviti eventi eliminati");
        }

        // Richieste eventi
        $eventRequests = EventRequest::all();
        $this->line("   Trovate {$eventRequests->count()} richieste eventi");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($eventRequests as $request) {
                try {
                    $request->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione richiesta evento {$request->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} richieste eventi eliminate");
        }

        // Snap video
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
            $this->line("   ✅ {$deletedCount} snap video eliminati");
        }

        // Like video
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
            $this->line("   ✅ {$deletedCount} like video eliminati");
        }

        // Commenti video
        $videoComments = VideoComment::all();
        $this->line("   Trovati {$videoComments->count()} commenti video");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($videoComments as $comment) {
                try {
                    $comment->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione commento video {$comment->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} commenti video eliminati");
        }

        // Commenti poesie
        $poemComments = PoemComment::all();
        $this->line("   Trovati {$poemComments->count()} commenti poesie");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($poemComments as $comment) {
                try {
                    $comment->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione commento poesia {$comment->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} commenti poesie eliminati");
        }

        // Commenti task
        $taskComments = TaskComment::all();
        $this->line("   Trovati {$taskComments->count()} commenti task");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($taskComments as $comment) {
                try {
                    $comment->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione commento task {$comment->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} commenti task eliminati");
        }

        // Segnalazioni
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
    }

    private function cleanupMedia($dryRun)
    {
        $this->line('');
        $this->line('🗑️  Eliminazione notifiche e log...');

        // Notifiche
        $notifications = Notification::all();
        $this->line("   Trovate {$notifications->count()} notifiche");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($notifications as $notification) {
                try {
                    $notification->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione notifica {$notification->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} notifiche eliminate");
        }

        // Log attività
        $logs = ActivityLog::all();
        $this->line("   Trovati {$logs->count()} log attività");
        if (!$dryRun) {
            $deletedCount = 0;
            foreach ($logs as $log) {
                try {
                    $log->delete();
                    $deletedCount++;
                } catch (\Exception $e) {
                    $this->error("   ❌ Errore eliminazione log {$log->id}: " . $e->getMessage());
                }
            }
            $this->line("   ✅ {$deletedCount} log attività eliminati");
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
