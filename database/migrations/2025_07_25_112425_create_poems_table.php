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
        Schema::create('poems', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->text('description')->nullable();
            $table->string('thumbnail')->nullable();
            $table->string('thumbnail_path')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('is_public')->default(true);
            $table->enum('moderation_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('moderation_notes')->nullable();
            $table->integer('view_count')->default(0);
            $table->integer('like_count')->default(0);
            $table->integer('comment_count')->default(0);
            $table->json('tags')->nullable();
            $table->string('language', 10)->default('it');
            $table->string('category')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('poems');
    }
};
