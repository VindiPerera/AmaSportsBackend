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
        // Batting Strike Rate (Runs ÷ Balls Faced × 100) can't be computed
        // without this — unlike Bowling, which already tracks `balls`.
        Schema::table('cricket_batting_stats', function (Blueprint $table) {
            $table->unsignedInteger('balls')->nullable()->after('runs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cricket_batting_stats', function (Blueprint $table) {
            $table->dropColumn('balls');
        });
    }
};
