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
        Schema::table('matches', function (Blueprint $table) {
            $table->boolean('is_tie_breaker')->default(false)->after('status');
            $table->json('tie_breaker_data')->nullable()->after('is_tie_breaker');
            $table->boolean('penalty_shootout_enabled')->default(true)->after('tie_breaker_data');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn(['is_tie_breaker', 'tie_breaker_data', 'penalty_shootout_enabled']);
        });
    }
};
