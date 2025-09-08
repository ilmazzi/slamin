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
            if (Schema::hasColumn('poems', 'translation_price')) {
                $table->dropColumn('translation_price');
            }
            if (Schema::hasColumn('poems', 'translation_available')) {
                $table->dropColumn('translation_available');
            }
            if (Schema::hasColumn('poems', 'translation_base_price')) {
                $table->dropColumn('translation_base_price');
            }
            if (Schema::hasColumn('poems', 'translation_negotiable')) {
                $table->dropColumn('translation_negotiable');
            }
            if (Schema::hasColumn('poems', 'translation_instructions')) {
                $table->dropColumn('translation_instructions');
            }
            if (Schema::hasColumn('poems', 'translation_requests')) {
                $table->dropColumn('translation_requests');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('poems', function (Blueprint $table) {
            $table->decimal('translation_price', 8, 2)->nullable();
            $table->boolean('translation_available')->default(false);
            $table->decimal('translation_base_price', 8, 2)->nullable();
            $table->boolean('translation_negotiable')->default(false);
            $table->text('translation_instructions')->nullable();
            $table->json('translation_requests')->nullable();
        });
    }
};
