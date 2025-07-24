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
            // Campi PeerTube
            $table->unsignedBigInteger('peertube_user_id')->nullable()->after('id');
            $table->string('peertube_username')->nullable()->after('peertube_user_id');
            $table->string('peertube_display_name')->nullable()->after('peertube_username');
            $table->text('peertube_token')->nullable()->after('peertube_display_name');
            $table->text('peertube_refresh_token')->nullable()->after('peertube_token');
            $table->timestamp('peertube_token_expires_at')->nullable()->after('peertube_refresh_token');
            $table->unsignedBigInteger('peertube_account_id')->nullable()->after('peertube_token_expires_at');
            $table->unsignedBigInteger('peertube_channel_id')->nullable()->after('peertube_account_id');
            $table->string('peertube_password')->nullable()->after('peertube_channel_id'); // Password in chiaro per PeerTube
            
            // Indici
            $table->index('peertube_user_id');
            $table->index('peertube_username');
            $table->index('peertube_account_id');
            $table->index('peertube_channel_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['peertube_user_id']);
            $table->dropIndex(['peertube_username']);
            $table->dropIndex(['peertube_account_id']);
            $table->dropIndex(['peertube_channel_id']);
            
            $table->dropColumn([
                'peertube_user_id',
                'peertube_username',
                'peertube_display_name',
                'peertube_token',
                'peertube_refresh_token',
                'peertube_token_expires_at',
                'peertube_account_id',
                'peertube_channel_id',
                'peertube_password',
            ]);
        });
    }
};
