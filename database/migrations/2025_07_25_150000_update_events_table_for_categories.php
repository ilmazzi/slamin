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
            // Aggiungi campo postcode se non esiste
            if (!Schema::hasColumn('events', 'postcode')) {
                $table->string('postcode')->nullable()->after('city');
            }
            
            // Aggiungi campo category
            $table->string('category')->default('poetry_slam')->after('tags');
            
            // Aggiungi indice per category
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex(['category']);
            $table->dropColumn('category');
            $table->dropColumn('postcode');
        });
    }
}; 