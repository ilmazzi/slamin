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
        Schema::create('test_users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            
            // PeerTube integration fields
            $table->integer('peertube_user_id')->nullable()->unique();
            $table->string('peertube_username')->nullable()->unique();
            $table->string('peertube_display_name')->nullable();
            $table->text('peertube_token')->nullable();
            $table->text('peertube_refresh_token')->nullable();
            $table->timestamp('peertube_token_expires_at')->nullable();
            $table->integer('peertube_account_id')->nullable();
            $table->integer('peertube_channel_id')->nullable();
            
            // Status and metadata
            $table->enum('status', ['pending', 'active', 'suspended', 'deleted'])->default('pending');
            $table->json('metadata')->nullable();
            
            $table->timestamps();
            
            // Indexes
            $table->index(['peertube_user_id']);
            $table->index(['peertube_username']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_users');
    }
};
