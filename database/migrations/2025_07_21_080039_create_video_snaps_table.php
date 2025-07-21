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
        Schema::create('video_snaps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('video_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->integer('timestamp'); // Timestamp nel video (secondi)
            $table->string('title')->nullable(); // Titolo snap
            $table->text('description')->nullable(); // Descrizione
            $table->string('thumbnail_url')->nullable(); // Thumbnail del momento
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->integer('like_count')->default(0); // Like sullo snap
            $table->integer('view_count')->default(0); // Visualizzazioni snap
            $table->text('moderation_notes')->nullable(); // Note moderazione
            $table->timestamps();

            // Indici per performance
            $table->index(['video_id', 'timestamp']);
            $table->index(['user_id']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_snaps');
    }
};
