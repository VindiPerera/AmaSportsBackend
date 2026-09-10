<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Extends every other sport's "Add New Stat" wizard (StatSectionWizard) with
 * the same `year` column Cricket's Batting/Bowling Career Stats already
 * have (see 2026_08_24_120000_add_year_to_cricket_batting_and_bowling_stats_tables.php)
 * — tags which playing year an entry's totals belong to, and is part of the
 * identity key a duplicate Format+Category(+...) entry merges into. Nullable/
 * additive so existing rows are unaffected.
 */
return new class extends Migration
{
    private const TABLES = [
        'hockey_career_stats',
        'base_ball_career_stats',
        'net_ball_career_stats',
        'racket_sport_career_stats',
        'kabadi_career_stats',
        'judo_career_stats',
        'basketball_career_stats',
        'football_career_stats',
        'rugby_career_stats',
        'boxing_career_stats',
        'karate_career_stats',
        'chess_career_stats',
        'athletics_career_stats',
        'swimming_career_stats',
        'volleyball_career_stats',
        'beach_volleyball_career_stats',
        'elle_career_stats',
    ];

    public function up(): void
    {
        foreach (self::TABLES as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->unsignedSmallInteger('year')->nullable();
            });
        }
    }

    public function down(): void
    {
        foreach (self::TABLES as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn('year');
            });
        }
    }
};
