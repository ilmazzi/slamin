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
        Schema::create('test_lives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('test_users')->onDelete('cascade');
            
            // PeerTube integration
            $table->integer('peertube_live_id')->nullable()->unique();
            
            // Live metadata
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('tags')->nullable();
            $table->string('thumbnail_path')->nullable();
            
            // Live status
            $table->enum('status', ['scheduled', 'active', 'ended', 'cancelled'])->default('scheduled');
            $table->enum('privacy', ['public', 'unlisted', 'private'])->default('public');
            
            // Statistics
            $table->integer('viewer_count')->default(0);
            $table->integer('like_count')->default(0);
            $table->integer('dislike_count')->default(0);
            
            // Streaming configuration
            $table->string('stream_key')->nullable();
            $table->string('rtmp_url')->nullable();
            $table->string('live_url')->nullable();
            $table->string('embed_url')->nullable();
            
            // Timestamps
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->integer('duration')->nullable(); // in seconds
            
            // Additional metadata
            $table->json('metadata')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['peertube_live_id']);
            $table->index(['user_id']);
            $table->index(['status']);
            $table->index(['privacy']);
            $table->index(['scheduled_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_lives');
    }
};
