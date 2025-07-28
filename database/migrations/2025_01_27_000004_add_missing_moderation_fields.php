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
        // Aggiungi campi mancanti a videos
        Schema::table('videos', function (Blueprint $table) {
            if (!Schema::hasColumn('videos', 'moderated_by')) {
                $table->foreignId('moderated_by')->nullable()->constrained('users')->onDelete('set null')->after('moderation_notes');
            }
            if (!Schema::hasColumn('videos', 'moderated_at')) {
                $table->timestamp('moderated_at')->nullable()->after('moderated_by');
            }
        });

        // Aggiungi campi mancanti a poems
        Schema::table('poems', function (Blueprint $table) {
            if (!Schema::hasColumn('poems', 'moderated_by')) {
                $table->foreignId('moderated_by')->nullable()->constrained('users')->onDelete('set null')->after('moderation_notes');
            }
            if (!Schema::hasColumn('poems', 'moderated_at')) {
                $table->timestamp('moderated_at')->nullable()->after('moderated_by');
            }
        });

        // Aggiungi campi mancanti a video_comments
        Schema::table('video_comments', function (Blueprint $table) {
            if (!Schema::hasColumn('video_comments', 'moderated_by')) {
                $table->foreignId('moderated_by')->nullable()->constrained('users')->onDelete('set null')->after('moderation_notes');
            }
            if (!Schema::hasColumn('video_comments', 'moderated_at')) {
                $table->timestamp('moderated_at')->nullable()->after('moderated_by');
            }
        });

        // Aggiungi campi mancanti a poem_comments
        Schema::table('poem_comments', function (Blueprint $table) {
            if (!Schema::hasColumn('poem_comments', 'moderated_by')) {
                $table->foreignId('moderated_by')->nullable()->constrained('users')->onDelete('set null')->after('moderation_notes');
            }
            if (!Schema::hasColumn('poem_comments', 'moderated_at')) {
                $table->timestamp('moderated_at')->nullable()->after('moderated_by');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Rimuovi campi da videos
        Schema::table('videos', function (Blueprint $table) {
            if (Schema::hasColumn('videos', 'moderated_by')) {
                $table->dropForeign(['moderated_by']);
                $table->dropColumn('moderated_by');
            }
            if (Schema::hasColumn('videos', 'moderated_at')) {
                $table->dropColumn('moderated_at');
            }
        });

        // Rimuovi campi da poems
        Schema::table('poems', function (Blueprint $table) {
            if (Schema::hasColumn('poems', 'moderated_by')) {
                $table->dropForeign(['moderated_by']);
                $table->dropColumn('moderated_by');
            }
            if (Schema::hasColumn('poems', 'moderated_at')) {
                $table->dropColumn('moderated_at');
            }
        });

        // Rimuovi campi da video_comments
        Schema::table('video_comments', function (Blueprint $table) {
            if (Schema::hasColumn('video_comments', 'moderated_by')) {
                $table->dropForeign(['moderated_by']);
                $table->dropColumn('moderated_by');
            }
            if (Schema::hasColumn('video_comments', 'moderated_at')) {
                $table->dropColumn('moderated_at');
            }
        });

        // Rimuovi campi da poem_comments
        Schema::table('poem_comments', function (Blueprint $table) {
            if (Schema::hasColumn('poem_comments', 'moderated_by')) {
                $table->dropForeign(['moderated_by']);
                $table->dropColumn('moderated_by');
            }
            if (Schema::hasColumn('poem_comments', 'moderated_at')) {
                $table->dropColumn('moderated_at');
            }
        });
    }
};
