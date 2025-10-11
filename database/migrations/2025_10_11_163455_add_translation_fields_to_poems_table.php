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
            if (!Schema::hasColumn('poems', 'translation_available')) {
                $table->boolean('translation_available')->default(false)->after('is_public');
            }
            if (!Schema::hasColumn('poems', 'translation_price')) {
                $table->decimal('translation_price', 8, 2)->nullable()->after('translation_available');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('poems', function (Blueprint $table) {
            if (Schema::hasColumn('poems', 'translation_available')) {
                $table->dropColumn('translation_available');
            }
            if (Schema::hasColumn('poems', 'translation_price')) {
                $table->dropColumn('translation_price');
            }
        });
    }
};

