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
            // Campi per il file system locale (solo se non esistono)
            if (!Schema::hasColumn('videos', 'file_path')) {
                $table->string('file_path')->nullable()->after('video_url');
            }
            if (!Schema::hasColumn('videos', 'thumbnail_path')) {
                $table->string('thumbnail_path')->nullable()->after('thumbnail');
            }

            // Campo status (solo se non esiste)
            if (!Schema::hasColumn('videos', 'status')) {
                $table->string('status')->default('uploaded')->after('is_public');
            }

            // Campi per metadati video (solo se non esistono)
            if (!Schema::hasColumn('videos', 'duration')) {
                $table->integer('duration')->nullable()->after('moderation_notes');
            }
            if (!Schema::hasColumn('videos', 'resolution')) {
                $table->string('resolution')->nullable()->after('duration');
            }
            if (!Schema::hasColumn('videos', 'file_size')) {
                $table->bigInteger('file_size')->nullable()->after('resolution');
            }

            // Campi per statistiche (solo se non esistono)
            if (!Schema::hasColumn('videos', 'view_count')) {
                $table->integer('view_count')->default(0)->after('views');
            }
            if (!Schema::hasColumn('videos', 'like_count')) {
                $table->integer('like_count')->default(0)->after('view_count');
            }
            if (!Schema::hasColumn('videos', 'dislike_count')) {
                $table->integer('dislike_count')->default(0)->after('like_count');
            }
            if (!Schema::hasColumn('videos', 'comment_count')) {
                $table->integer('comment_count')->default(0)->after('dislike_count');
            }

            // Campo tags (solo se non esiste)
            if (!Schema::hasColumn('videos', 'tags')) {
                $table->json('tags')->nullable()->after('comment_count');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $columns = ['file_path', 'thumbnail_path', 'status', 'duration', 'resolution', 'file_size', 'view_count', 'like_count', 'dislike_count', 'comment_count', 'tags'];

            foreach ($columns as $column) {
                if (Schema::hasColumn('videos', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
