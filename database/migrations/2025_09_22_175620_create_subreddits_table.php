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
        Schema::create('subreddits', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nome visualizzato
            $table->string('slug')->unique(); // URL slug
            $table->text('description')->nullable();
            $table->text('rules')->nullable(); // Regole del subreddit
            $table->string('icon')->nullable(); // Icona del subreddit
            $table->string('banner')->nullable(); // Banner del subreddit
            $table->string('color', 7)->default('#007bff'); // Colore tema
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_private')->default(false); // Subreddit privato
            $table->integer('subscribers_count')->default(0);
            $table->integer('posts_count')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'subscribers_count']);
            $table->index('created_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subreddits');
    }
};
