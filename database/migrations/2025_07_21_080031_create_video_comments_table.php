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
        Schema::create('video_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('video_comments')->onDelete('cascade'); // Per risposte
            $table->text('content'); // Contenuto commento
            $table->integer('timestamp')->nullable(); // Timestamp nel video (secondi)
            $table->enum('status', ['pending', 'approved', 'rejected', 'spam'])->default('pending');
            $table->integer('like_count')->default(0); // Like sul commento
            $table->integer('report_count')->default(0); // Segnalazioni
            $table->text('moderation_notes')->nullable(); // Note moderazione
            $table->timestamps();

            // Indici per performance
            $table->index(['video_id', 'status']);
            $table->index(['user_id']);
            $table->index(['timestamp']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_comments');
    }
};
