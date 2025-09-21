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
        Schema::create('article_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('article_id')->constrained()->onDelete('cascade');
            $table->string('language', 10);
            $table->string('title');
            $table->text('content');
            $table->text('excerpt')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->foreignId('translator_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('translation_type', ['manual', 'automatic', 'hybrid'])->default('manual');
            $table->json('translation_metadata')->nullable(); // Per salvare info aggiuntive
            $table->timestamp('translated_at')->nullable();
            $table->timestamps();

            // Indici
            $table->unique(['article_id', 'language']);
            $table->index(['language', 'status']);
            $table->index('translator_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_translations');
    }
};
