<?php

namespace App\Observers;

use App\Models\Article;
use App\Services\ActivityService;

class ArticleObserver
{
    /**
     * Handle the Article "created" event.
     */
    public function created(Article $article): void
    {
        // Log activity
        if ($article->user) {
            ActivityService::logCreate($article->user, $article, request());
        }
    }

    /**
     * Handle the Article "updated" event.
     */
    public function updated(Article $article): void
    {
        // Log activity for important changes
        if ($article->user && $article->wasChanged(['title', 'content', 'status', 'is_featured'])) {
            ActivityService::logUpdate($article->user, $article, request());
        }
    }

    /**
     * Handle the Article "deleted" event.
     */
    public function deleted(Article $article): void
    {
        // Log activity before deletion
        if ($article->user) {
            ActivityService::logDelete(
                $article->user,
                'App\\Models\\Article',
                $article->id,
                $article->title ?? 'Articolo',
                request()
            );
        }
    }

    /**
     * Handle the Article "restored" event.
     */
    public function restored(Article $article): void
    {
        //
    }

    /**
     * Handle the Article "force deleted" event.
     */
    public function forceDeleted(Article $article): void
    {
        //
    }
}
