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
        Schema::table('poems', function (Blueprint $table) {
            // Rendi nullable i campi titolo, categoria e tipo di poesia
            $table->string('title')->nullable()->change();
            $table->string('category')->nullable()->change();
            $table->string('poem_type')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('poems', function (Blueprint $table) {
            // Ripristina i vincoli NOT NULL
            $table->string('title')->nullable(false)->change();
            $table->string('category')->nullable(false)->change();
            $table->string('poem_type')->nullable(false)->change();
        });
    }
};
