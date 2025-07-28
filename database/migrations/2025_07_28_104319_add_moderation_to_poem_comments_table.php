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
        Schema::table('poem_comments', function (Blueprint $table) {
            if (!Schema::hasColumn('poem_comments', 'moderation_status')) {
                $table->enum('moderation_status', ['pending', 'approved', 'rejected'])->default('pending')->after('is_edited');
            }
            if (!Schema::hasColumn('poem_comments', 'moderation_notes')) {
                $table->text('moderation_notes')->nullable()->after('moderation_status');
            }
            if (!Schema::hasColumn('poem_comments', 'moderated_by')) {
                $table->unsignedBigInteger('moderated_by')->nullable()->after('moderation_notes');
                $table->foreign('moderated_by')->references('id')->on('users')->onDelete('set null');
            }
            if (!Schema::hasColumn('poem_comments', 'moderated_at')) {
                $table->timestamp('moderated_at')->nullable()->after('moderated_by');
            }
        });

        // Aggiungi indici per migliorare le performance
        Schema::table('poem_comments', function (Blueprint $table) {
            if (!Schema::hasIndex('poem_comments', 'poem_comments_moderation_status_index')) {
                $table->index('moderation_status');
            }
            if (!Schema::hasIndex('poem_comments', 'poem_comments_moderated_by_index')) {
                $table->index('moderated_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('poem_comments', function (Blueprint $table) {
            $table->dropIndex(['moderation_status']);
            $table->dropIndex(['moderated_by']);
            $table->dropForeign(['moderated_by']);
            $table->dropColumn(['moderation_status', 'moderation_notes', 'moderated_by', 'moderated_at']);
        });
    }
};
