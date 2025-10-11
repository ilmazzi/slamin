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
        Schema::create('forum_bans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('subreddit_id')->nullable()->constrained('subreddits')->onDelete('cascade'); // NULL = ban globale
            $table->text('reason');
            $table->enum('type', ['temporary', 'permanent'])->default('temporary');
            $table->timestamp('expires_at')->nullable(); // Per ban temporanei
            $table->foreignId('banned_by')->constrained('users'); // Chi ha bannato
            $table->boolean('is_active')->default(true);
            $table->timestamp('lifted_at')->nullable();
            $table->foreignId('lifted_by')->nullable()->constrained('users');
            $table->timestamps();

            $table->index(['user_id', 'is_active']);
            $table->index(['subreddit_id', 'is_active']);
            $table->index(['expires_at', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_bans');
    }
};
