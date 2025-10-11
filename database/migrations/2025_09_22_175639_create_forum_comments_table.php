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
        Schema::create('forum_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('forum_posts')->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('forum_comments')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->text('content');
            $table->string('original_language', 5)->default('auto'); // Per traduzioni
            $table->integer('upvotes')->default(0);
            $table->integer('downvotes')->default(0);
            $table->integer('score')->default(0); // upvotes - downvotes
            $table->integer('depth')->default(0); // Livello di annidamento (0 = root)
            $table->boolean('is_deleted')->default(false);
            $table->timestamp('deleted_at')->nullable();
            $table->foreignId('deleted_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable(); // Per moderazione
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamps();

            $table->index(['post_id', 'created_at']);
            $table->index(['parent_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index(['depth', 'created_at']);
            $table->index(['score', 'created_at']);
            $table->index(['is_deleted', 'created_at']);
            $table->fullText('content'); // Per ricerca full-text
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_comments');
    }
};
