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
        Schema::table('events', function (Blueprint $table) {
            // Campi per eventi online
            $table->boolean('is_online')->default(false)->after('is_recurring');
            $table->string('timezone', 50)->nullable()->after('is_online');
            $table->text('online_url')->nullable()->after('timezone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['is_online', 'timezone', 'online_url']);
        });
    }
};
