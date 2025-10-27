<?php

namespace App\Providers;

use App\Models\Video;
use App\Models\VideoSnap;
use App\Models\Group;
use App\Models\GigApplication;
use App\Models\Event;
use App\Models\Poem;
use App\Models\Article;
use App\Models\Photo;
use App\Models\Gig;
use App\Models\EventInvitation;
use App\Models\EventRequest;
use App\Models\GroupInvitation;
use App\Models\Follow;
use App\Models\UnifiedLike;
use App\Models\UnifiedComment;
use App\Models\ForumPost;
use App\Observers\VideoObserver;
use App\Observers\GroupObserver;
use App\Observers\GigApplicationObserver;
use App\Observers\EventObserver;
use App\Observers\PoemObserver;
use App\Observers\ArticleObserver;
use App\Observers\PhotoObserver;
use App\Observers\GigObserver;
use App\Observers\EventInvitationObserver;
use App\Observers\EventRequestObserver;
use App\Observers\GroupInvitationObserver;
use App\Observers\FollowObserver;
use App\Observers\LikeObserver;
use App\Observers\CommentObserver;
use App\Observers\ForumPostObserver;
use App\Services\LoggingService;
use App\Helpers\TranslationHelper;
use App\Helpers\AutoTranslationHelper;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Nulla qui - morph maps nel boot()
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Configura morph maps secondo la documentazione Laravel
        Relation::enforceMorphMap([
            'user' => \App\Models\User::class,
            'video' => \App\Models\Video::class,
            'video_snap' => \App\Models\VideoSnap::class,
            'poem' => \App\Models\Poem::class,
            'article' => \App\Models\Article::class,
            'photo' => \App\Models\Photo::class,
            'gig' => \App\Models\Gig::class,
            'event' => \App\Models\Event::class,
            'group' => \App\Models\Group::class,
            'forum_post' => \App\Models\ForumPost::class,
            'forum_comment' => \App\Models\ForumComment::class,
            'subreddit' => \App\Models\Subreddit::class,
            'forum_vote' => \App\Models\ForumVote::class,
            'event_round' => \App\Models\EventRound::class,
            'event_participant' => \App\Models\EventParticipant::class,
            'event_ranking' => \App\Models\EventRanking::class,
            'user_badge' => \App\Models\UserBadge::class,
        ]);

        // Registra l'observer per i video
        Video::observe(VideoObserver::class);

        // Registra l'observer per i gruppi
        Group::observe(GroupObserver::class);

        // Registra l'observer per le candidature ai gig
        GigApplication::observe(GigApplicationObserver::class);

        // Registra l'observer per gli eventi
        Event::observe(EventObserver::class);

        // Registra l'observer per i messaggi chat

        // Registra l'observer per le poesie
        Poem::observe(PoemObserver::class);

        // Registra l'observer per gli articoli
        Article::observe(ArticleObserver::class);

        // Registra l'observer per le foto
        Photo::observe(PhotoObserver::class);

        // Registra l'observer per i gig
        Gig::observe(GigObserver::class);

        // Registra l'observer per gli inviti agli eventi
        EventInvitation::observe(EventInvitationObserver::class);

        // Registra l'observer per le richieste agli eventi
        EventRequest::observe(EventRequestObserver::class);

        // Registra l'observer per gli inviti ai gruppi
        GroupInvitation::observe(GroupInvitationObserver::class);

        // Registra l'observer per i follow
        Follow::observe(FollowObserver::class);

        // Registra l'observer per i like unificati (gamification)
        UnifiedLike::observe(LikeObserver::class);

        // Registra l'observer per i commenti unificati (gamification)
        UnifiedComment::observe(CommentObserver::class);

        // Registra l'observer per i post del forum (gamification)
        ForumPost::observe(ForumPostObserver::class);

        // Registra le Blade Directives per le traduzioni
        $this->registerTranslationDirectives();

        // Configura il template di paginazione personalizzato
        $this->configurePaginationView();

        // Registra un handler globale per le eccezioni non gestite
        $this->registerGlobalExceptionHandler();
        
        $morphMap = config('chat.morph_map', []);
        if (!empty($morphMap) && is_array($morphMap)) {
            Relation::enforceMorphMap($morphMap);
        }
    }

    /**
     * Registra le Blade Directives per le traduzioni
     */
    private function registerTranslationDirectives(): void
    {
        // @t() - Traduzione mista (DB + File)
        Blade::directive('t', function ($expression) {
            return "<?php echo App\\Helpers\\TranslationHelper::get($expression); ?>";
        });

        // @auto() - Cattura automatica testi hardcoded
        Blade::directive('auto', function ($expression) {
            return "<?php echo App\\Helpers\\AutoTranslationHelper::capture($expression, 'blade_template'); ?>";
        });

        // @trans() - Alias per @t()
        Blade::directive('trans', function ($expression) {
            return "<?php echo App\\Helpers\\TranslationHelper::get($expression); ?>";
        });

        // @transChoice() - Traduzione con pluralizzazione
        Blade::directive('transChoice', function ($expression) {
            return "<?php echo trans_choice($expression); ?>";
        });

        // @transExists() - Verifica se esiste una traduzione
        Blade::directive('transExists', function ($expression) {
            return "<?php echo App\\Helpers\\TranslationHelper::getFromDatabase($expression) !== null ? 'true' : 'false'; ?>";
        });
    }

    /**
     * Configura il template di paginazione personalizzato
     */
    private function configurePaginationView(): void
    {
        \Illuminate\Pagination\Paginator::defaultView('vendor.pagination.app-pagination');
    }

    /**
     * Registra un handler globale per le eccezioni
     */
    private function registerGlobalExceptionHandler(): void
    {
        // Handler per eccezioni non gestite
        set_exception_handler(function ($exception) {
            try {
                LoggingService::logError('global_exception', [
                    'message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                    'trace' => $exception->getTraceAsString(),
                    'exception_class' => get_class($exception),
                    'request_url' => request()->fullUrl() ?? 'CLI',
                    'request_method' => request()->method() ?? 'CLI',
                    'user_id' => \Illuminate\Support\Facades\Auth::user()?->id,
                ]);

                // Backup log
                Log::error('Global exception handler caught error', [
                    'message' => $exception->getMessage(),
                    'file' => $exception->getFile(),
                    'line' => $exception->getLine(),
                ]);
            } catch (\Exception $e) {
                // Fallback se il logging fallisce
                Log::error('Failed to log exception in global handler', [
                    'original_error' => $exception->getMessage(),
                    'logging_error' => $e->getMessage(),
                ]);
            }
        });

        // Handler per errori fatali
        register_shutdown_function(function () {
            $error = error_get_last();
            if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
                try {
                    LoggingService::logError('fatal_error', [
                        'message' => $error['message'],
                        'file' => $error['file'],
                        'line' => $error['line'],
                        'type' => $error['type'],
                        'request_url' => request()->fullUrl() ?? 'CLI',
                        'user_id' => \Illuminate\Support\Facades\Auth::user()?->id,
                    ]);

                    Log::error('Fatal error occurred', $error);
                } catch (\Exception $e) {
                    Log::error('Failed to log fatal error', [
                        'original_error' => $error,
                        'logging_error' => $e->getMessage(),
                    ]);
                }
            }
        });
    }

}
