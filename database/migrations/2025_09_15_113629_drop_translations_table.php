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
        Schema::dropIfExists('translations');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('locale');
            $table->text('value');
            $table->timestamps();

            $table->index(['key', 'locale']);
        });
    }
};
