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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_online')->default(false);
            $table->timestamp('last_seen_at')->nullable();
            $table->string('online_status')->default('offline'); // online, away, busy, invisible
            $table->json('online_preferences')->nullable(); // preferenze privacy per lo stato online
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_online', 'last_seen_at', 'online_status', 'online_preferences']);
        });
    }
};
