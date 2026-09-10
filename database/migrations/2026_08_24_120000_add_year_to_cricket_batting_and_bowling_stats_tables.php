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
        // Career-Stat rows are now added one match at a time from the mobile
        // app (a Category+Division picker step, merged into an existing row
        // when the same combination is reused — see CricketProfileScreen's
        // "Add New Batting/Bowling Stat" flow) — `year` tags which playing
        // year an entry's totals belong to. Nullable/additive so rows saved
        // before this feature shipped keep loading and resubmitting fine.
        Schema::table('cricket_batting_stats', function (Blueprint $table) {
            $table->unsignedSmallInteger('year')->nullable()->after('cricket_match_type_id');
        });

        Schema::table('cricket_bowling_stats', function (Blueprint $table) {
            $table->unsignedSmallInteger('year')->nullable()->after('cricket_match_type_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cricket_batting_stats', function (Blueprint $table) {
            $table->dropColumn('year');
        });

        Schema::table('cricket_bowling_stats', function (Blueprint $table) {
            $table->dropColumn('year');
        });
    }
};
