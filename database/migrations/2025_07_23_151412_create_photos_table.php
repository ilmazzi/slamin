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
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('image_path'); // Percorso dell'immagine
            $table->string('thumbnail_path')->nullable(); // Thumbnail
            $table->string('alt_text')->nullable(); // Testo alternativo per accessibilità
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
            $table->integer('like_count')->default(0);
            $table->integer('view_count')->default(0);
            $table->text('moderation_notes')->nullable();
            $table->json('metadata')->nullable(); // Metadati EXIF, dimensioni, etc.
            $table->timestamps();

            // Indici per performance
            $table->index(['user_id']);
            $table->index(['status']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};
