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
            $table->unsignedBigInteger('group_id')->nullable()->after('organizer_id');
            $table->enum('group_permissions', ['creator_only', 'group_admins', 'group_members'])->nullable()->after('group_id');
            
            // Indici
            $table->index('group_id');
            $table->index(['group_id', 'group_permissions']);
            
            // Foreign key
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
            $table->dropIndex(['group_id']);
            $table->dropIndex(['group_id', 'group_permissions']);
            $table->dropColumn(['group_id', 'group_permissions']);
        });
    }
};
