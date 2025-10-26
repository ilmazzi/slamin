<?php

namespace App\Helpers;

use App\Models\Article;

class ArticleImageHelper
{
    /**
     * Get the article featured image URL with fallback
     *
     * @param Article $article
     * @return string
     */
    public static function getArticleImageUrl(Article $article): string
    {
        // Se l'articolo ha una featured_image (storage)
        if ($article->featured_image && \Storage::disk('public')->exists($article->featured_image)) {
            return asset('storage/' . $article->featured_image);
        }

        // Se l'articolo ha una featured_image (public path)
        if ($article->featured_image && file_exists(public_path($article->featured_image))) {
            return asset($article->featured_image);
        }

        // Fallback: placeholder configurabile dall'admin
        return PlaceholderHelper::getArticlePlaceholderUrl();
    }
}

