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
            $table->string('nickname')->unique()->nullable()->after('name');
            $table->text('bio')->nullable()->after('email_verified_at');
            $table->string('location')->nullable()->after('bio');
            $table->enum('status', ['active', 'inactive', 'suspended', 'banned'])
                  ->default('active')->after('location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['nickname', 'bio', 'location', 'status']);
        });
    }
};
