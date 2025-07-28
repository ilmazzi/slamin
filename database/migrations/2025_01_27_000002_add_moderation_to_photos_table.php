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
                Schema::table('photos', function (Blueprint $table) {
            if (!Schema::hasColumn('photos', 'moderation_status')) {
                $table->enum('moderation_status', ['pending', 'approved', 'rejected'])->default('pending')->after('status');
            }
            if (!Schema::hasColumn('photos', 'moderated_by')) {
                $table->foreignId('moderated_by')->nullable()->constrained('users')->onDelete('set null')->after('moderation_notes');
            }
            if (!Schema::hasColumn('photos', 'moderated_at')) {
                $table->timestamp('moderated_at')->nullable()->after('moderated_by');
            }

            // Indici per performance
            if (!Schema::hasIndex('photos', 'photos_moderation_status_index')) {
                $table->index(['moderation_status']);
            }
            if (!Schema::hasIndex('photos', 'photos_moderated_by_index')) {
                $table->index(['moderated_by']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('photos', function (Blueprint $table) {
            $table->dropIndex(['moderation_status']);
            $table->dropIndex(['moderated_by']);
            $table->dropForeign(['moderated_by']);
            $table->dropColumn(['moderation_status', 'moderation_notes', 'moderated_by', 'moderated_at']);
        });
    }
};
