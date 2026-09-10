<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * "Format" (cricket_match_type_id) and "Match Category" (match_category_id)
     * were dropped from the Batting/Bowling "Add New Stat" Match Details step
     * — the mobile app no longer collects either, so match_category_id (the
     * one of the two that was still required) has to become optional or
     * every new entry would fail validation with nothing to put in it.
     */
    public function up(): void
    {
        Schema::table('cricket_batting_stats', function (Blueprint $table) {
            $table->unsignedBigInteger('match_category_id')->nullable()->change();
        });

        Schema::table('cricket_bowling_stats', function (Blueprint $table) {
            $table->unsignedBigInteger('match_category_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cricket_batting_stats', function (Blueprint $table) {
            $table->unsignedBigInteger('match_category_id')->nullable(false)->change();
        });

        Schema::table('cricket_bowling_stats', function (Blueprint $table) {
            $table->unsignedBigInteger('match_category_id')->nullable(false)->change();
        });
    }
};
