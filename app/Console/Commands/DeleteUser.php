<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Video;
use App\Models\Poem;
use App\Models\Event;
use App\Models\Group;
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
use App\Services\PeerTubeService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DeleteUser extends Command
{
    protected $signature = 'user:delete {user} {--force} {--dry-run}';
    protected $description = 'Elimina un utente e tutte le sue correlazioni, incluso PeerTube';

    public function handle()
    {
        $userIdentifier = $this->argument('user');
        $force = $this->option('force');
        $dryRun = $this->option('dry-run');

        // Trova l'utente
        $user = null;
        if (is_numeric($userIdentifier)) {
            $user = User::find($userIdentifier);
        } else {
            $user = User::where('email', $userIdentifier)
                       ->orWhere('nickname', $userIdentifier)
                       ->first();
        }

        if (!$user) {
            $this->error("❌ Utente non trovato: {$userIdentifier}");
            return 1;
        }

        if ($dryRun) {
            $this->info('🔍 MODALITÀ DRY-RUN: Nessuna eliminazione verrà eseguita');
        }

        $this->info('🗑️ ELIMINAZIONE UTENTE');
        $this->line('');
        $this->info('📋 INFORMAZIONI UTENTE:');
        $this->line("   ID: {$user->id}");
        $this->line("   Nome: {$user->name}");
        $this->line("   Email: {$user->email}");
        $this->line("   Nickname: {$user->nickname}");
        $this->line("   Ruolo: " . $user->roles->pluck('name')->implode(', '));
        $this->line("   PeerTube ID: " . ($user->peertube_user_id ?: 'Nessuno'));
        $this->line('');

        // Mostra statistiche delle correlazioni
        $this->showUserStatistics($user);

        if (!$force && !$dryRun && !$this->confirm('Sei SICURO di voler eliminare questo utente e TUTTE le sue correlazioni? Questa operazione è IRREVERSIBILE!')) {
            $this->error('❌ Operazione annullata');
            return 1;
        }

        // Elimina correlazioni
        $this->deleteUserCorrelations($user, $dryRun);

        // Elimina da PeerTube
        $this->deleteFromPeerTube($user, $dryRun);

        // Elimina file
        $this->deleteUserFiles($user, $dryRun);

        // Elimina l'utente
        if (!$dryRun) {
            try {
                $user->delete();
                $this->info('✅ UTENTE ELIMINATO CON SUCCESSO!');
            } catch (\Exception $e) {
                $this->error("❌ Errore eliminazione utente: " . $e->getMessage());
                return 1;
            }
        } else {
            $this->info('🔍 Eliminerebbe l\'utente: ' . $user->name);
        }

        $this->info('');
        $this->info('🎉 ELIMINAZIONE COMPLETATA!');
        
        if ($dryRun) {
            $this->warn('⚠️ Modalità dry-run: nessuna modifica è stata applicata');
        }

        return 0;
    }

    private function showUserStatistics(User $user)
    {
        $this->info('📊 STATISTICHE CORRELAZIONI:');
        $this->line("   📹 Video: " . Video::where('user_id', $user->id)->count());
        $this->line("   📝 Poesie: " . Poem::where('user_id', $user->id)->count());
        $this->line("   📅 Eventi creati: " . Event::where('organizer_id', $user->id)->count());
        $this->line("   👥 Gruppi creati: " . Group::where('created_by', $user->id)->count());
        $this->line("   🔔 Notifiche: " . Notification::where('user_id', $user->id)->count());
        $this->line("   📊 Log attività: " . ActivityLog::where('user_id', $user->id)->count());
        $this->line("   ❤️ Wishlist: " . Wishlist::where('user_id', $user->id)->count());
        $this->line("   💬 Chat create: " . Chat::where('created_by', $user->id)->count());
        $this->line("   💬 Messaggi chat: " . ChatMessage::where('user_id', $user->id)->count());
        $this->line("   👥 Partecipazioni chat: " . ChatParticipant::where('user_id', $user->id)->count());
        $this->line("   👥 Membri gruppi: " . GroupMember::where('user_id', $user->id)->count());
        $this->line("   📨 Inviti gruppi inviati: " . GroupInvitation::where('user_id', $user->id)->count());
        $this->line("   📨 Richieste gruppi: " . GroupJoinRequest::where('user_id', $user->id)->count());
        $this->line("   📨 Inviti eventi inviati: " . EventInvitation::where('inviter_id', $user->id)->count());
        $this->line("   📨 Richieste eventi: " . EventRequest::where('user_id', $user->id)->count());
        $this->line("   📸 Snap video: " . VideoSnap::where('user_id', $user->id)->count());
        $this->line("   👍 Like video: " . VideoLike::where('user_id', $user->id)->count());
        $this->line("   💬 Commenti video: " . VideoComment::where('user_id', $user->id)->count());
        $this->line("   💬 Commenti poesie: " . PoemComment::where('user_id', $user->id)->count());
        $this->line("   🚨 Segnalazioni: " . Report::where('user_id', $user->id)->count());
        $this->line("   ✅ Task: " . Task::where('created_by', $user->id)->count());
        $this->line("   💬 Commenti task: " . TaskComment::where('user_id', $user->id)->count());
        $this->line("   📷 Foto: " . Photo::where('user_id', $user->id)->count());
        $this->line('');
    }

    private function deleteUserCorrelations(User $user, $dryRun)
    {
        $this->info('🔗 ELIMINAZIONE CORRELAZIONI...');

        $correlations = [
            'Video' => ['model' => Video::class, 'field' => 'user_id'],
            'Poesie' => ['model' => Poem::class, 'field' => 'user_id'],
            'Eventi' => ['model' => Event::class, 'field' => 'organizer_id'],
            'Gruppi' => ['model' => Group::class, 'field' => 'created_by'],
            'Notifiche' => ['model' => Notification::class, 'field' => 'user_id'],
            'Log attività' => ['model' => ActivityLog::class, 'field' => 'user_id'],
            'Wishlist' => ['model' => Wishlist::class, 'field' => 'user_id'],
            'Chat' => ['model' => Chat::class, 'field' => 'created_by'],
            'Messaggi chat' => ['model' => ChatMessage::class, 'field' => 'user_id'],
            'Partecipazioni chat' => ['model' => ChatParticipant::class, 'field' => 'user_id'],
            'Membri gruppi' => ['model' => GroupMember::class, 'field' => 'user_id'],
            'Inviti gruppi' => ['model' => GroupInvitation::class, 'field' => 'user_id'],
            'Richieste gruppi' => ['model' => GroupJoinRequest::class, 'field' => 'user_id'],
            'Inviti eventi' => ['model' => EventInvitation::class, 'field' => 'inviter_id'],
            'Richieste eventi' => ['model' => EventRequest::class, 'field' => 'user_id'],
            'Snap video' => ['model' => VideoSnap::class, 'field' => 'user_id'],
            'Like video' => ['model' => VideoLike::class, 'field' => 'user_id'],
            'Commenti video' => ['model' => VideoComment::class, 'field' => 'user_id'],
            'Commenti poesie' => ['model' => PoemComment::class, 'field' => 'user_id'],
            'Segnalazioni' => ['model' => Report::class, 'field' => 'user_id'],
            'Task' => ['model' => Task::class, 'field' => 'created_by'],
            'Commenti task' => ['model' => TaskComment::class, 'field' => 'user_id'],
            'Foto' => ['model' => Photo::class, 'field' => 'user_id'],
        ];

        foreach ($correlations as $name => $config) {
            $model = $config['model'];
            $field = $config['field'];
            $count = $model::where($field, $user->id)->count();
            if ($count > 0) {
                if ($dryRun) {
                    $this->line("   🔍 Eliminerebbe {$count} {$name}");
                } else {
                    try {
                        $deleted = $model::where($field, $user->id)->delete();
                        $this->line("   ✅ Eliminati {$deleted} {$name}");
                    } catch (\Exception $e) {
                        $this->error("   ❌ Errore eliminazione {$name}: " . $e->getMessage());
                    }
                }
            }
        }
    }

    private function deleteFromPeerTube(User $user, $dryRun)
    {
        if (!$user->peertube_user_id) {
            $this->line("   ℹ️ Utente non ha account PeerTube");
            return;
        }

        if ($dryRun) {
            $this->line("   🔍 Eliminerebbe da PeerTube (ID: {$user->peertube_user_id})");
            return;
        }

        try {
            $peerTubeService = new PeerTubeService();
            $deleted = $peerTubeService->deleteUser($user->peertube_user_id);
            
            if ($deleted) {
                $this->line("   🔗 Eliminato da PeerTube (ID: {$user->peertube_user_id})");
            } else {
                $this->line("   ⚠️ Impossibile eliminare da PeerTube (ID: {$user->peertube_user_id})");
            }
        } catch (\Exception $e) {
            $this->error("   ❌ Errore eliminazione PeerTube: " . $e->getMessage());
        }
    }

    private function deleteUserFiles(User $user, $dryRun)
    {
        $this->info('🗂️ ELIMINAZIONE FILE...');

        $files = [
            'Avatar' => $user->avatar,
            'Banner' => $user->banner_image,
        ];

        foreach ($files as $type => $path) {
            if ($path && Storage::disk('public')->exists($path)) {
                if ($dryRun) {
                    $this->line("   🔍 Eliminerebbe {$type}: {$path}");
                } else {
                    try {
                        Storage::disk('public')->delete($path);
                        $this->line("   ✅ Eliminato {$type}: {$path}");
                    } catch (\Exception $e) {
                        $this->error("   ❌ Errore eliminazione {$type}: " . $e->getMessage());
                    }
                }
            } else {
                if ($dryRun) {
                    $this->line("   🔍 {$type}: Nessun file da eliminare");
                }
            }
        }
    }
} 