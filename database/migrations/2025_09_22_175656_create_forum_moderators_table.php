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
        Schema::create('forum_moderators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subreddit_id')->constrained('subreddits')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('role', ['moderator', 'admin'])->default('moderator');
            $table->json('permissions')->nullable(); // Permessi specifici
            $table->foreignId('added_by')->constrained('users'); // Chi ha aggiunto il moderatore
            $table->timestamps();

            $table->unique(['subreddit_id', 'user_id']); // Un moderatore per subreddit
            $table->index(['subreddit_id', 'role']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_moderators');
    }
};
