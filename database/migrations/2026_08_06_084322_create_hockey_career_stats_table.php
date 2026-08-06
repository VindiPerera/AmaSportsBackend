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
        // Repeatable "Career Status" rows (spec 6.3). The reference mockup
        // and written spec both list "Win/Lost" AND "Won/Lost/Drawn" as two
        // separate column pairs — kept literally as two pairs here even
        // though it reads redundant (matches_won/matches_lost vs.
        // result_won/result_lost/result_drawn).
        Schema::create('hockey_career_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hockey_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('format_id')->constrained();
            $table->foreignId('age_category_id')->constrained();
            $table->foreignId('match_category_id')->constrained();
            $table->unsignedInteger('kit_number')->nullable();
            $table->unsignedInteger('matches')->nullable();
            $table->unsignedInteger('matches_won')->nullable();
            $table->unsignedInteger('matches_lost')->nullable();
            $table->unsignedInteger('goals')->nullable();
            $table->unsignedInteger('assist_goals')->nullable();
            $table->unsignedInteger('defeat_goal')->nullable();
            $table->unsignedInteger('result_won')->nullable();
            $table->unsignedInteger('result_lost')->nullable();
            $table->unsignedInteger('result_drawn')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hockey_career_stats');
    }
};
