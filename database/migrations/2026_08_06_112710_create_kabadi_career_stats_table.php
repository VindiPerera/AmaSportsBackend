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
        // Column names spelled out (raids, successful_raids, ...) rather
        // than the abbreviations (R, SR, ...) shown in the UI — the
        // glossary from spec Phase 2 §B4 lives client-side as help text.
        Schema::create('kabadi_career_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kabadi_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('format_id')->constrained();
            $table->foreignId('age_category_id')->constrained();
            $table->foreignId('match_category_id')->constrained();
            $table->unsignedInteger('matches')->nullable();
            $table->unsignedInteger('win')->nullable();
            $table->unsignedInteger('lost')->nullable();
            $table->unsignedInteger('cbp')->nullable();
            $table->unsignedInteger('raids')->nullable();
            $table->unsignedInteger('successful_raids')->nullable();
            $table->unsignedInteger('unsuccessful_raids')->nullable();
            $table->unsignedInteger('raid_touch_point')->nullable();
            $table->unsignedInteger('raid_bonus_point')->nullable();
            $table->unsignedInteger('tackles')->nullable();
            $table->unsignedInteger('successful_tackles')->nullable();
            $table->unsignedInteger('unsuccessful_tackles')->nullable();
            $table->unsignedInteger('empty_raids')->nullable();
            $table->unsignedInteger('yellow_cards')->nullable();
            $table->unsignedInteger('green_cards')->nullable();
            $table->unsignedInteger('red_cards')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kabadi_career_stats');
    }
};
