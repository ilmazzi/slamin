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
        Schema::create('user_notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('notification_type'); // 'group_announcements', 'public_announcements', etc.
            $table->boolean('enabled')->default(true);
            $table->unsignedBigInteger('group_id')->nullable(); // Per preferenze specifiche per gruppo
            $table->timestamps();
            
            // Indici
            $table->index(['user_id', 'notification_type']);
            $table->index(['user_id', 'group_id']);
            $table->unique(['user_id', 'notification_type', 'group_id'], 'user_notif_prefs_unique');
            
            // Foreign keys
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_notification_preferences');
    }
};
