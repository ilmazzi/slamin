<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Video;
use App\Models\Poem;
use App\Models\Photo;
use App\Models\Event;
use App\Models\Carousel;

class UpdateSocialCounters extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'social:update-counters {--dry-run : Esegue solo una simulazione senza modificare i dati}';

    /**
     * The console command description.
     */
    protected $description = 'Aggiorna i contatori social nei modelli esistenti usando le nuove tabelle unificate';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        
        if ($isDryRun) {
            $this->info('🔍 Modalità DRY RUN - Nessuna modifica verrà applicata');
        }

        $this->info('🚀 Aggiornando contatori social...');

        try {
            // Aggiorna contatori video
            $this->updateVideoCounters($isDryRun);
            
            // Aggiorna contatori poesie
            $this->updatePoemCounters($isDryRun);
            
            // Aggiorna contatori foto
            $this->updatePhotoCounters($isDryRun);
            
            // Aggiorna contatori eventi
            $this->updateEventCounters($isDryRun);
            
            // Aggiorna contatori carousel (articoli)
            $this->updateCarouselCounters($isDryRun);

            $this->info('✅ Aggiornamento contatori completato!');

        } catch (\Exception $e) {
            $this->error('❌ Errore durante l\'aggiornamento: ' . $e->getMessage());
            return 1;
        }

        return 0;
    }

    /**
     * Aggiorna i contatori dei video
     */
    private function updateVideoCounters(bool $isDryRun): void
    {
        $this->info('📹 Aggiornando contatori video...');
        
        $videos = Video::all();
        $count = 0;

        foreach ($videos as $video) {
            $likeCount = DB::table('unified_likes')
                ->where('likeable_type', Video::class)
                ->where('likeable_id', $video->id)
                ->count();

            $commentCount = DB::table('unified_comments')
                ->where('commentable_type', Video::class)
                ->where('commentable_id', $video->id)
                ->where('status', 'approved')
                ->count();

            if (!$isDryRun) {
                $video->update([
                    'like_count' => $likeCount,
                    'comment_count' => $commentCount,
                ]);
            }
            
            $count++;
        }

        $this->info("   ✅ Aggiornati {$count} video");
    }

    /**
     * Aggiorna i contatori delle poesie
     */
    private function updatePoemCounters(bool $isDryRun): void
    {
        $this->info('📝 Aggiornando contatori poesie...');
        
        $poems = Poem::all();
        $count = 0;

        foreach ($poems as $poem) {
            $likeCount = DB::table('unified_likes')
                ->where('likeable_type', Poem::class)
                ->where('likeable_id', $poem->id)
                ->count();

            $commentCount = DB::table('unified_comments')
                ->where('commentable_type', Poem::class)
                ->where('commentable_id', $poem->id)
                ->where('status', 'approved')
                ->count();

            if (!$isDryRun) {
                $poem->update([
                    'like_count' => $likeCount,
                    'comment_count' => $commentCount,
                ]);
            }
            
            $count++;
        }

        $this->info("   ✅ Aggiornate {$count} poesie");
    }

    /**
     * Aggiorna i contatori delle foto
     */
    private function updatePhotoCounters(bool $isDryRun): void
    {
        $this->info('📸 Aggiornando contatori foto...');
        
        $photos = Photo::all();
        $count = 0;

        foreach ($photos as $photo) {
            $likeCount = DB::table('unified_likes')
                ->where('likeable_type', Photo::class)
                ->where('likeable_id', $photo->id)
                ->count();

            $commentCount = DB::table('unified_comments')
                ->where('commentable_type', Photo::class)
                ->where('commentable_id', $photo->id)
                ->where('status', 'approved')
                ->count();

            if (!$isDryRun) {
                $photo->update([
                    'like_count' => $likeCount,
                    'comment_count' => $commentCount,
                ]);
            }
            
            $count++;
        }

        $this->info("   ✅ Aggiornate {$count} foto");
    }

    /**
     * Aggiorna i contatori degli eventi
     */
    private function updateEventCounters(bool $isDryRun): void
    {
        $this->info('🎉 Aggiornando contatori eventi...');
        
        $events = Event::all();
        $count = 0;

        foreach ($events as $event) {
            $likeCount = DB::table('unified_likes')
                ->where('likeable_type', Event::class)
                ->where('likeable_id', $event->id)
                ->count();

            $commentCount = DB::table('unified_comments')
                ->where('commentable_type', Event::class)
                ->where('commentable_id', $event->id)
                ->where('status', 'approved')
                ->count();

            if (!$isDryRun) {
                $event->update([
                    'like_count' => $likeCount,
                    'comment_count' => $commentCount,
                ]);
            }
            
            $count++;
        }

        $this->info("   ✅ Aggiornati {$count} eventi");
    }

    /**
     * Aggiorna i contatori dei carousel (articoli)
     */
    private function updateCarouselCounters(bool $isDryRun): void
    {
        $this->info('📰 Aggiornando contatori articoli...');
        
        $carousels = Carousel::all();
        $count = 0;

        foreach ($carousels as $carousel) {
            $likeCount = DB::table('unified_likes')
                ->where('likeable_type', Carousel::class)
                ->where('likeable_id', $carousel->id)
                ->count();

            $commentCount = DB::table('unified_comments')
                ->where('commentable_type', Carousel::class)
                ->where('commentable_id', $carousel->id)
                ->where('status', 'approved')
                ->count();

            if (!$isDryRun) {
                $carousel->update([
                    'like_count' => $likeCount,
                    'comment_count' => $commentCount,
                ]);
            }
            
            $count++;
        }

        $this->info("   ✅ Aggiornati {$count} articoli");
    }
}
