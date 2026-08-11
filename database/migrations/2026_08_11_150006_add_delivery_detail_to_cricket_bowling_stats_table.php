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
        // Phase 7 (Bowling Analyses) delivery-outcome detail. All nullable,
        // additive.
        Schema::table('cricket_bowling_stats', function (Blueprint $table) {
            $table->unsignedInteger('dot_balls')->nullable()->after('balls');
            $table->unsignedInteger('wide_balls')->nullable()->after('dot_balls');
            $table->unsignedInteger('no_balls')->nullable()->after('wide_balls');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cricket_bowling_stats', function (Blueprint $table) {
            $table->dropColumn(['dot_balls', 'wide_balls', 'no_balls']);
        });
    }
};
