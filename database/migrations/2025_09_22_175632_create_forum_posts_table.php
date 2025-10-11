<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('forum_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subreddit_id')->constrained('subreddits')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('content')->nullable(); // Per post di testo
            $table->enum('type', ['text', 'link', 'image']); // Solo questi tipi
            $table->string('url')->nullable(); // Per post di link
            $table->string('image_path')->nullable(); // Per post di immagini (WebP)
            $table->string('original_image_name')->nullable(); // Nome originale
            $table->integer('upvotes')->default(0);
            $table->integer('downvotes')->default(0);
            $table->integer('score')->default(0); // upvotes - downvotes
            $table->integer('comments_count')->default(0);
            $table->integer('views_count')->default(0);
            $table->boolean('is_sticky')->default(false); // Post in evidenza
            $table->boolean('is_locked')->default(false); // Post bloccato
            $table->boolean('is_archived')->default(false); // Post archiviato
            $table->timestamp('approved_at')->nullable(); // Per moderazione
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamps();

            $table->index(['subreddit_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index(['type', 'created_at']);
            $table->index(['score', 'created_at']);
            $table->index(['is_sticky', 'created_at']);
            $table->fullText(['title', 'content']); // Per ricerca full-text
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_posts');
    }
};
