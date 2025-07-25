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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('action', 100)->index(); // e.g., 'user.login', 'event.create', 'video.upload'
            $table->string('category', 50)->index(); // e.g., 'authentication', 'events', 'videos'
            $table->text('description'); // Human readable description in English
            $table->json('details')->nullable(); // Additional data as JSON
            $table->string('ip_address', 45)->nullable(); // IPv4 or IPv6
            $table->text('user_agent')->nullable();
            $table->string('url')->nullable();
            $table->string('method', 10)->nullable(); // GET, POST, PUT, DELETE
            $table->integer('status_code')->nullable();
            $table->integer('response_time')->nullable(); // in milliseconds
            $table->enum('level', ['info', 'warning', 'error', 'critical'])->default('info')->index();
            $table->string('related_model')->nullable(); // e.g., 'App\Models\Event'
            $table->unsignedBigInteger('related_id')->nullable(); // ID of related model
            $table->timestamps();

            // Indexes for better performance
            $table->index(['created_at', 'level']);
            $table->index(['user_id', 'created_at']);
            $table->index(['category', 'created_at']);
            $table->index(['related_model', 'related_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
