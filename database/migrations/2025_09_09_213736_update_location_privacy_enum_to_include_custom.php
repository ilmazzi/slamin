<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Per MySQL, dobbiamo modificare l'enum
        DB::statement("ALTER TABLE users MODIFY COLUMN location_privacy ENUM('public', 'region', 'country', 'private', 'custom') DEFAULT 'region'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Ripristina l'enum originale
        DB::statement("ALTER TABLE users MODIFY COLUMN location_privacy ENUM('public', 'region', 'country', 'private') DEFAULT 'region'");
    }
};
