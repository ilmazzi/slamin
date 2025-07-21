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
            // Campi PeerTube
            $table->string('peertube_id')->nullable()->after('id'); // ID video su PeerTube
            $table->string('peertube_url')->nullable()->after('peertube_id'); // URL video PeerTube
            $table->string('peertube_embed_url')->nullable()->after('peertube_url'); // URL embed
            $table->string('peertube_thumbnail_url')->nullable()->after('peertube_embed_url'); // Thumbnail
            $table->integer('duration')->nullable()->after('peertube_thumbnail_url'); // Durata in secondi
            $table->string('resolution')->nullable()->after('duration'); // Risoluzione video
            $table->bigInteger('file_size')->nullable()->after('resolution'); // Dimensione file in bytes

            // Statistiche PeerTube
            $table->integer('view_count')->default(0)->after('file_size'); // Visualizzazioni
            $table->integer('like_count')->default(0)->after('view_count'); // Like
            $table->integer('dislike_count')->default(0)->after('like_count'); // Dislike
            $table->integer('comment_count')->default(0)->after('dislike_count'); // Commenti

            // Stato moderazione
            $table->enum('moderation_status', ['pending', 'approved', 'rejected'])->default('pending')->after('comment_count');
            $table->text('moderation_notes')->nullable()->after('moderation_status'); // Note moderazione

            // Indici per performance
            $table->index('peertube_id');
            $table->index('moderation_status');
            $table->index('view_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('videos', function (Blueprint $table) {
            $table->dropIndex(['peertube_id']);
            $table->dropIndex(['moderation_status']);
            $table->dropIndex(['view_count']);

            $table->dropColumn([
                'peertube_id', 'peertube_url', 'peertube_embed_url', 'peertube_thumbnail_url',
                'duration', 'resolution', 'file_size', 'view_count', 'like_count',
                'dislike_count', 'comment_count', 'moderation_status', 'moderation_notes'
            ]);
        });
    }
};
