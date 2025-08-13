<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Video;
use App\Models\Poem;
use App\Models\Event;
use App\Models\Photo;
use App\Models\Article;
use App\Models\Carousel;
use App\Models\VideoComment;
use App\Models\PoemComment;
use App\Models\Report;
use App\Models\SystemSetting;
use App\Services\LoggingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ModerationController extends Controller
{
        /**
     * Mostra il dashboard di moderazione
     */
    public function index()
    {
        $stats = $this->getModerationStats();
        $pendingContent = $this->getPendingContent();
        $reports = $this->getActiveReports();

        return view('admin.moderation.index', compact('stats', 'pendingContent', 'reports'));
    }

        /**
     * Mostra i contenuti in attesa di moderazione e le segnalazioni
     */
    public function pending(Request $request)
    {
        $type = $request->get('type', 'all');
        $status = $request->get('status', 'pending');
        $filter = $request->get('filter', 'all'); // all, pending, reports

        if ($type === 'all') {
            $content = [
                'videos' => $this->getContentByType('videos', $status)->with('user')->latest()->get(),
                'poems' => $this->getContentByType('poems', $status)->with('user')->latest()->get(),
                'events' => $this->getContentByType('events', $status)->with('organizer')->latest()->get(),
                'photos' => $this->getContentByType('photos', $status)->with('user')->latest()->get(),
                'articles' => $this->getContentByType('articles', $status)->with('user')->latest()->get(),
                'carousels' => $this->getContentByType('carousels', $status)->latest()->get(),
                'video_comments' => $this->getContentByType('video_comments', $status)->with(['user', 'video'])->latest()->get(),
                'poem_comments' => $this->getContentByType('poem_comments', $status)->with(['user', 'poem'])->latest()->get(),
            ];
        } else {
            $content = [
                $type => $this->getContentByType($type, $status)->with($this->getRelationships($type))->latest()->get()
            ];
        }

        // Filtra i report se necessario
        $reports = collect();
        if ($filter == 'reports' || $filter == 'all') {
            $reports = $this->getActiveReports();
        }

        return view('admin.moderation.pending', compact('content', 'type', 'status', 'reports', 'filter'));
    }

    /**
     * Approva un contenuto
     */
    public function approve(Request $request, $type, $id)
    {
        $content = $this->getContentModel($type, $id);

        if (!$content) {
            LoggingService::logError('moderation_content_not_found', [
                'type' => $type,
                'id' => $id,
                'user_id' => Auth::id()
            ]);
            return response()->json(['success' => false, 'message' => 'Contenuto non trovato']);
        }

        $notes = $request->input('notes');
        $success = $content->approve(Auth::user(), $notes);

        if ($success) {
            LoggingService::logAdmin('content_approved', [
                'content_type' => $type,
                'content_id' => $id,
                'content_title' => $content->title ?? $content->content ?? 'N/A',
                'moderator_id' => Auth::id(),
                'moderator_name' => Auth::user()->name,
                'notes' => $notes
            ], get_class($content), $id);

            return response()->json([
                'success' => true,
                'message' => 'Contenuto approvato con successo'
            ]);
        }

        LoggingService::logError('moderation_approval_failed', [
            'content_type' => $type,
            'content_id' => $id,
            'moderator_id' => Auth::id(),
            'notes' => $notes
        ]);

        return response()->json(['success' => false, 'message' => 'Errore durante l\'approvazione']);
    }

    /**
     * Rifiuta un contenuto
     */
    public function reject(Request $request, $type, $id)
    {
        $content = $this->getContentModel($type, $id);

        if (!$content) {
            LoggingService::logError('moderation_content_not_found', [
                'type' => $type,
                'id' => $id,
                'user_id' => Auth::id()
            ]);
            return response()->json(['success' => false, 'message' => 'Contenuto non trovato']);
        }

        $notes = $request->input('notes');
        $success = $content->reject(Auth::user(), $notes);

        if ($success) {
            // Aggiorna anche le segnalazioni relative a questo contenuto
            $reports = Report::where('reportable_type', get_class($content))
                           ->where('reportable_id', $id)
                           ->where('status', Report::STATUS_PENDING)
                           ->get();
            
            foreach ($reports as $report) {
                $report->update([
                    'status' => Report::STATUS_RESOLVED,
                    'resolved_by' => Auth::id(),
                    'resolved_at' => now(),
                    'resolution_notes' => 'Contenuto rifiutato: ' . $notes
                ]);
            }

            LoggingService::logAdmin('content_rejected', [
                'content_type' => $type,
                'content_id' => $id,
                'content_title' => $content->title ?? $content->content ?? 'N/A',
                'moderator_id' => Auth::id(),
                'moderator_name' => Auth::user()->name,
                'notes' => $notes,
                'reports_updated' => $reports->count()
            ], get_class($content), $id);

            return response()->json([
                'success' => true,
                'message' => 'Contenuto rifiutato con successo' . ($reports->count() > 0 ? ' e ' . $reports->count() . ' segnalazioni risolte' : '')
            ]);
        }

        LoggingService::logError('moderation_rejection_failed', [
            'content_type' => $type,
            'content_id' => $id,
            'moderator_id' => Auth::id(),
            'notes' => $notes
        ]);

        return response()->json(['success' => false, 'message' => 'Errore durante il rifiuto']);
    }

    /**
     * Approva tutti i contenuti di un tipo
     */
    public function approveAll(Request $request, $type)
    {
        $status = $request->get('status', 'pending');
        $content = $this->getContentByType($type, $status);

        $approved = 0;
        foreach ($content as $item) {
            if ($item->approve(Auth::user())) {
                $approved++;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "{$approved} contenuti approvati con successo"
        ]);
    }

    /**
     * Ottiene le statistiche di moderazione
     */
    private function getModerationStats()
    {
        return [
            'videos' => [
                'pending' => Video::pending()->count(),
                'approved' => Video::approved()->count(),
                'rejected' => Video::rejected()->count(),
            ],
            'poems' => [
                'pending' => Poem::pending()->count(),
                'approved' => Poem::approved()->count(),
                'rejected' => Poem::rejected()->count(),
            ],
            'events' => [
                'pending' => Event::pending()->count(),
                'approved' => Event::approved()->count(),
                'rejected' => Event::rejected()->count(),
            ],
            'photos' => [
                'pending' => Photo::pending()->count(),
                'approved' => Photo::approved()->count(),
                'rejected' => Photo::rejected()->count(),
            ],
            'articles' => [
                'pending' => Article::pending()->count(),
                'approved' => Article::approved()->count(),
                'rejected' => Article::rejected()->count(),
            ],
            'carousels' => [
                'pending' => Carousel::pending()->count(),
                'approved' => Carousel::approved()->count(),
                'rejected' => Carousel::rejected()->count(),
            ],
            'video_comments' => [
                'pending' => VideoComment::pending()->count(),
                'approved' => VideoComment::approved()->count(),
                'rejected' => VideoComment::rejected()->count(),
            ],
            'poem_comments' => [
                'pending' => PoemComment::pending()->count(),
                'approved' => PoemComment::approved()->count(),
                'rejected' => PoemComment::rejected()->count(),
            ],
        ];
    }

    /**
     * Ottiene i contenuti in attesa
     */
    private function getPendingContent()
    {
        return [
            'videos' => Video::pending()->with('user')->latest()->limit(5)->get(),
            'poems' => Poem::pending()->with('user')->latest()->limit(5)->get(),
            'events' => Event::pending()->with('organizer')->latest()->limit(5)->get(),
            'photos' => Photo::pending()->with('user')->latest()->limit(5)->get(),
            'articles' => Article::pending()->with('user')->latest()->limit(5)->get(),
            'carousels' => Carousel::pending()->latest()->limit(5)->get(),
            'video_comments' => VideoComment::pending()->with(['user', 'video'])->latest()->limit(5)->get(),
            'poem_comments' => PoemComment::pending()->with(['user', 'poem'])->latest()->limit(5)->get(),
        ];
    }

    /**
     * Ottiene le segnalazioni attive
     */
    private function getActiveReports()
    {
        $reports = Report::active()
            ->with(['user', 'reportable', 'resolver'])
            ->latest()
            ->get();
        
        // Aggiungi i titoli corretti ai report
        foreach ($reports as $report) {
            if ($report->reportable) {
                $content = $report->reportable;
                if (isset($content->title)) {
                    $report->content_title = $content->title;
                } elseif (isset($content->name)) {
                    $report->content_title = $content->name;
                } elseif (isset($content->content)) {
                    $report->content_title = substr($content->content, 0, 50) . '...';
                } else {
                    $report->content_title = 'Contenuto #' . $report->reportable_id;
                }
            } else {
                $report->content_title = 'Contenuto non trovato';
            }
        }
        
        return $reports;
    }

    /**
     * Gestisce una segnalazione
     */
    public function handleReport(Request $request, $reportId)
    {
        $request->validate([
            'action' => 'required|in:investigate,resolve,dismiss',
            'notes' => 'nullable|string'
        ]);

        $report = Report::findOrFail($reportId);
        $action = $request->action;
        $notes = $request->notes;

        switch ($action) {
            case 'investigate':
                $report->update([
                    'status' => Report::STATUS_INVESTIGATING,
                    'resolved_by' => Auth::id(),
                    'resolved_at' => now(),
                    'resolution_notes' => $notes
                ]);
                $message = 'Segnalazione messa in investigazione';
                break;

            case 'resolve':
                $report->update([
                    'status' => Report::STATUS_RESOLVED,
                    'resolved_by' => Auth::id(),
                    'resolved_at' => now(),
                    'resolution_notes' => $notes
                ]);
                
                // Rifiuta anche il contenuto segnalato
                if ($report->reportable) {
                    $report->reportable->reject(Auth::user(), $notes);
                }
                
                $message = 'Segnalazione risolta e contenuto rifiutato';
                break;

            case 'dismiss':
                $report->update([
                    'status' => Report::STATUS_DISMISSED,
                    'resolved_by' => Auth::id(),
                    'resolved_at' => now(),
                    'resolution_notes' => $notes
                ]);
                $message = 'Segnalazione respinta';
                break;
        }

        LoggingService::logAdmin('report_handled', [
            'report_id' => $reportId,
            'action' => $action,
            'report_content' => $report->reportable_title ?? 'N/A',
            'moderator_id' => Auth::id(),
            'moderator_name' => Auth::user()->name,
            'notes' => $notes,
            'new_status' => $report->status
        ], 'App\Models\Report', $reportId);

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * Ottiene le statistiche delle segnalazioni
     */
    private function getReportStats()
    {
        return [
            'pending' => Report::pending()->count(),
            'investigating' => Report::investigating()->count(),
            'resolved' => Report::resolved()->count(),
            'dismissed' => Report::dismissed()->count(),
        ];
    }

    /**
     * Ottiene i contenuti per tipo e status
     */
    private function getContentByType($type, $status)
    {
        switch ($type) {
            case 'videos':
                return $status === 'pending' ? Video::pending() :
                       ($status === 'approved' ? Video::approved() : Video::rejected());
            case 'poems':
                return $status === 'pending' ? Poem::pending() :
                       ($status === 'approved' ? Poem::approved() : Poem::rejected());
            case 'events':
                return $status === 'pending' ? Event::pending() :
                       ($status === 'approved' ? Event::approved() : Event::rejected());
            case 'photos':
                return $status === 'pending' ? Photo::pending() :
                       ($status === 'approved' ? Photo::approved() : Photo::rejected());
            case 'articles':
                return $status === 'pending' ? Article::pending() :
                       ($status === 'approved' ? Article::approved() : Article::rejected());
            case 'carousels':
                return $status === 'pending' ? Carousel::pending() :
                       ($status === 'approved' ? Carousel::approved() : Carousel::rejected());
            case 'video_comments':
                return $status === 'pending' ? VideoComment::pending() :
                       ($status === 'approved' ? VideoComment::approved() : VideoComment::rejected());
            case 'poem_comments':
                return $status === 'pending' ? PoemComment::pending() :
                       ($status === 'approved' ? PoemComment::approved() : PoemComment::rejected());
            default:
                return Video::query(); // Return empty query builder
        }
    }

    /**
     * Ottiene le relazioni per tipo di contenuto
     */
    private function getRelationships($type)
    {
        switch ($type) {
            case 'videos':
                return ['user'];
            case 'poems':
                return ['user'];
            case 'events':
                return ['organizer'];
            case 'photos':
                return ['user'];
            case 'articles':
                return ['user'];
            case 'carousels':
                return [];
            case 'video_comments':
                return ['user', 'video'];
            case 'poem_comments':
                return ['user', 'poem'];
            default:
                return [];
        }
    }

    /**
     * Ottiene il modello del contenuto
     */
    private function getContentModel($type, $id)
    {
        switch ($type) {
            case 'videos':
                return Video::find($id);
            case 'poems':
                return Poem::find($id);
            case 'events':
                return Event::find($id);
            case 'photos':
                return Photo::find($id);
            case 'articles':
                return Article::find($id);
            case 'carousels':
                return Carousel::find($id);
            case 'video_comments':
                return VideoComment::find($id);
            case 'poem_comments':
                return PoemComment::find($id);
            default:
                return null;
        }
    }

    /**
     * Mostra le impostazioni di moderazione
     */
    public function settings()
    {
        $settings = SystemSetting::getGroup('moderation');

        // Mappa le chiavi dal formato database al formato form
        $formSettings = [];

        // Mappa inversa per convertire le chiavi
        $reverseKeyMapping = [
            'moderation.videos.auto_approve' => 'videos_auto_approve',
            'moderation.poems.auto_approve' => 'poems_auto_approve',
            'moderation.events.auto_approve' => 'events_auto_approve',
            'moderation.photos.auto_approve' => 'photos_auto_approve',
            'moderation.articles.auto_approve' => 'articles_auto_approve',
            'moderation.carousels.auto_approve' => 'carousels_auto_approve',
            'moderation.video_comments.auto_approve' => 'comments_auto_approve',
            'moderation.general.notify_on_pending' => 'email_notifications',
            'moderation.general.items_per_page' => 'items_per_page',
            'moderation.general.reports_retention_days' => 'reports_retention_days',
            'moderation.general.auto_archive_rejected' => 'auto_archive_rejected',
        ];

        foreach ($settings as $key => $value) {
            $formKey = $reverseKeyMapping[$key] ?? $key;
            $formSettings[$formKey] = $value;
        }

        return view('admin.moderation.settings', compact('settings', 'formSettings'));
    }

    /**
     * Aggiorna le impostazioni di moderazione
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'videos_auto_approve' => 'nullable|boolean',
            'poems_auto_approve' => 'nullable|boolean',
            'events_auto_approve' => 'nullable|boolean',
            'photos_auto_approve' => 'nullable|boolean',
            'articles_auto_approve' => 'nullable|boolean',
            'carousels_auto_approve' => 'nullable|boolean',
            'comments_auto_approve' => 'nullable|boolean',
            'email_notifications' => 'nullable|boolean',
            'items_per_page' => 'nullable|integer|min:5|max:100',
            'reports_retention_days' => 'nullable|integer|min:1|max:365',
            'auto_archive_rejected' => 'nullable|boolean',
        ]);

        $updated = 0;

        // Mappa delle chiavi per convertire dal formato form al formato del trait
        $keyMapping = [
            'videos_auto_approve' => 'moderation.videos.auto_approve',
            'poems_auto_approve' => 'moderation.poems.auto_approve',
            'events_auto_approve' => 'moderation.events.auto_approve',
            'photos_auto_approve' => 'moderation.photos.auto_approve',
            'articles_auto_approve' => 'moderation.articles.auto_approve',
            'carousels_auto_approve' => 'moderation.carousels.auto_approve',
            'comments_auto_approve' => 'moderation.video_comments.auto_approve', // Per i commenti video
            'email_notifications' => 'moderation.general.notify_on_pending',
            'items_per_page' => 'moderation.general.items_per_page',
            'reports_retention_days' => 'moderation.general.reports_retention_days',
            'auto_archive_rejected' => 'moderation.general.auto_archive_rejected',
        ];

        foreach ($validated as $key => $value) {
            if ($request->has($key)) {
                $settingKey = $keyMapping[$key] ?? $key;
                $type = in_array($key, ['items_per_page', 'reports_retention_days']) ? 'integer' : 'boolean';
                $settingValue = $type === 'boolean' ? ($value ? true : false) : $value;

                SystemSetting::set($settingKey, $settingValue, $type);

                // Aggiorna il gruppo se necessario
                $setting = SystemSetting::where('key', $settingKey)->first();
                if ($setting && $setting->group !== 'moderation') {
                    $setting->group = 'moderation';
                    $setting->save();
                }

                $updated++;
            }
        }

        LoggingService::logSettings('moderation_settings_updated', [
            'updated_count' => $updated,
            'settings' => array_keys($validated),
            'admin_id' => Auth::id(),
            'admin_name' => Auth::user()->name
        ]);

        return redirect()->route('admin.moderation.settings')
            ->with('success', "Impostazioni aggiornate con successo ({$updated} modificate)");
    }

    /**
     * Ottiene i dettagli del contenuto segnalato per il modal
     */
    public function getReportedContentDetails(Request $request, $reportId)
    {
        $report = Report::with('reportable', 'user')->findOrFail($reportId);
        
        if (!$report->reportable) {
            return response()->json([
                'success' => false,
                'message' => 'Contenuto non trovato'
            ]);
        }

        $content = $report->reportable;
        $contentType = $report->content_type;
        
        // Ottieni il titolo direttamente dal contenuto
        $contentTitle = null;
        if ($content) {
            if (isset($content->title)) {
                $contentTitle = $content->title;
            } elseif (isset($content->name)) {
                $contentTitle = $content->name;
            } elseif (isset($content->content)) {
                $contentTitle = substr($content->content, 0, 50) . '...';
            } else {
                $contentTitle = 'Contenuto #' . $report->reportable_id;
            }
        } else {
            $contentTitle = 'Contenuto non trovato';
        }
        
        // Ottieni il contenuto direttamente dal modello
        $contentBody = null;
        $contentUrl = null;
        $contentThumbnail = null;
        
        if ($content) {
            // Contenuto
            $contentMethods = ['content', 'description', 'body', 'text'];
            foreach ($contentMethods as $method) {
                if (isset($content->$method)) {
                    $contentBody = $content->$method;
                    break;
                }
            }
            
            // URL
            $urlMethods = ['getContentUrl', 'getUrl', 'url'];
            foreach ($urlMethods as $method) {
                if (method_exists($content, $method)) {
                    $contentUrl = $content->$method();
                    break;
                }
            }
            
            // Thumbnail
            $thumbnailMethods = ['thumbnail', 'image', 'cover', 'thumbnail_url', 'image_url'];
            foreach ($thumbnailMethods as $method) {
                if (method_exists($content, $method)) {
                    $value = $content->$method;
                    if (is_string($value) && !empty($value)) {
                        $contentThumbnail = $value;
                        break;
                    }
                }
            }
        }



        // Prepara i dati per il modal
        $modalData = [
            'report_id' => $report->id,
            'content_type' => $contentType,
            'content_title' => $contentTitle,
            'content_body' => $contentBody,
            'content_url' => $contentUrl,
            'content_thumbnail' => $contentThumbnail,
            'report_reason' => $report->reason_text,
            'report_description' => $report->description,
            'reporter_name' => $report->user->name ?? 'Utente sconosciuto',
            'reported_at' => $report->created_at->format('d/m/Y H:i'),
            'status' => $report->status_text,
        ];



        // Aggiungi dati specifici per tipo di contenuto
        switch ($report->reportable_type) {
            case 'App\Models\Video':
                $modalData['video_url'] = $content->video_url ?? null;
                $modalData['duration'] = $content->duration ?? null;
                $modalData['author'] = $content->user->name ?? null;
                break;
            case 'App\Models\Photo':
                $modalData['image_url'] = $content->image_url ?? $content->image ?? null;
                $modalData['author'] = $content->user->name ?? null;
                break;
            case 'App\Models\Article':
                $modalData['excerpt'] = $content->excerpt ?? null;
                $modalData['author'] = $content->user->name ?? null;
                break;
            case 'App\Models\Poem':
                $modalData['author'] = $content->user->name ?? null;
                $modalData['category'] = $content->category ?? null;
                break;
            case 'App\Models\Event':
                $modalData['start_date'] = $content->start_date ?? null;
                $modalData['location'] = $content->location ?? null;
                $modalData['author'] = $content->organizer->name ?? null;
                break;
            case 'App\Models\VideoComment':
            case 'App\Models\PoemComment':
                $modalData['author'] = $content->user->name ?? null;
                $modalData['parent_content'] = $content->video->title ?? $content->poem->title ?? null;
                break;
        }

        return response()->json([
            'success' => true,
            'data' => $modalData
        ]);
    }

    /**
     * Ottiene il titolo del contenuto per la visualizzazione
     */
    private function getContentTitle($item, $type)
    {
        switch ($type) {
            case 'videos':
                return $item->title ?? 'Video senza titolo';
            case 'poems':
                return $item->title ?? 'Poesia senza titolo';
            case 'events':
                return $item->title ?? 'Evento senza titolo';
            case 'photos':
                return $item->title ?? 'Foto senza titolo';
            case 'carousels':
                return $item->title ?? 'Carosello senza titolo';
            case 'video_comments':
                return Str::limit($item->content, 50) ?? 'Commento senza contenuto';
            case 'poem_comments':
                return Str::limit($item->content, 50) ?? 'Commento senza contenuto';
            default:
                return 'Contenuto senza titolo';
        }
    }

    /**
     * Ottiene la descrizione del contenuto per la visualizzazione
     */
    private function getContentDescription($item, $type)
    {
        switch ($type) {
            case 'videos':
                return $item->description ?? null;
            case 'poems':
                return $item->description ?? null;
            case 'events':
                return $item->description ?? null;
            case 'photos':
                return $item->description ?? null;
            case 'carousels':
                return $item->description ?? null;
            case 'video_comments':
                return $item->content ?? null;
            case 'poem_comments':
                return $item->content ?? null;
            default:
                return null;
        }
    }

    /**
     * Ottiene l'utente del contenuto
     */
    private function getContentUser($item, $type)
    {
        switch ($type) {
            case 'videos':
            case 'poems':
            case 'photos':
            case 'video_comments':
            case 'poem_comments':
                return $item->user ?? null;
            case 'events':
                return $item->organizer ?? null;
            case 'carousels':
                return null; // I caroselli potrebbero non avere un utente specifico
            default:
                return null;
        }
    }
}
