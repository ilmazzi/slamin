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
            if (Schema::hasColumn('poems', 'translation_job_available')) {
                $table->dropColumn('translation_job_available');
            }
            if (Schema::hasColumn('poems', 'translation_languages')) {
                $table->dropColumn('translation_languages');
            }
            if (Schema::hasColumn('poems', 'translation_compensation')) {
                $table->dropColumn('translation_compensation');
            }
            if (Schema::hasColumn('poems', 'translation_deadline')) {
                $table->dropColumn('translation_deadline');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('poems', function (Blueprint $table) {
            $table->boolean('translation_job_available')->default(false);
            $table->json('translation_languages')->nullable();
            $table->string('translation_compensation')->nullable();
            $table->date('translation_deadline')->nullable();
        });
    }
};
