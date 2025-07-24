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
        Schema::create('test_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('test_users')->onDelete('cascade');
            
            // PeerTube integration
            $table->integer('peertube_video_id')->nullable()->unique();
            
            // Video metadata
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('tags')->nullable();
            $table->string('thumbnail_path')->nullable();
            $table->string('video_path')->nullable();
            
            // Video properties
            $table->integer('duration')->nullable(); // in seconds
            $table->string('resolution')->nullable(); // e.g., "1920x1080"
            $table->bigInteger('file_size')->nullable(); // in bytes
            $table->enum('status', ['uploading', 'processing', 'published', 'failed', 'deleted'])->default('uploading');
            $table->enum('privacy', ['public', 'unlisted', 'private'])->default('public');
            
            // Statistics
            $table->integer('view_count')->default(0);
            $table->integer('like_count')->default(0);
            $table->integer('dislike_count')->default(0);
            
            // Upload progress
            $table->integer('upload_progress')->default(0); // 0-100
            $table->enum('transcoding_status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            
            // Additional metadata
            $table->json('metadata')->nullable();
            
            // Timestamps
            $table->timestamp('uploaded_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['peertube_video_id']);
            $table->index(['user_id']);
            $table->index(['status']);
            $table->index(['privacy']);
            $table->index(['uploaded_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_videos');
    }
};
