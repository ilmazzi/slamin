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
        Schema::table('videos', function (Blueprint $table) {
            if (!Schema::hasColumn('videos', 'moderation_status')) {
                $table->enum('moderation_status', ['pending', 'approved', 'rejected'])
                    ->default('approved')
                    ->after('status');
            }
            if (!Schema::hasColumn('videos', 'moderation_notes')) {
                $table->text('moderation_notes')->nullable()->after('moderation_status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            if (Schema::hasColumn('videos', 'moderation_status')) {
                $table->dropColumn('moderation_status');
            }
            if (Schema::hasColumn('videos', 'moderation_notes')) {
                $table->dropColumn('moderation_notes');
            }
        });
    }
};

