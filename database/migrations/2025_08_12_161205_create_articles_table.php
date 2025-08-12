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
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('article_categories')->onDelete('set null');
            $table->json('title'); // Per traduzioni
            $table->json('content'); // Per traduzioni
            $table->json('excerpt')->nullable(); // Per traduzioni
            $table->string('featured_image')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->boolean('featured')->default(false);
            $table->integer('views_count')->default(0);
            $table->integer('likes_count')->default(0);
            $table->integer('comments_count')->default(0);
            $table->string('slug')->unique(); // Per URL friendly
            $table->json('meta_title')->nullable(); // Per SEO
            $table->json('meta_description')->nullable(); // Per SEO
            $table->json('meta_keywords')->nullable(); // Per SEO
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            // Indici per performance
            $table->index(['status', 'published_at']);
            $table->index(['featured', 'published_at']);
            $table->index(['user_id', 'status']);
            $table->index('slug');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
