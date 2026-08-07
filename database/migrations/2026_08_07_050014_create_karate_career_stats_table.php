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
        // `weight_category` / `age_category` here are free-text fields, not
        // FKs — no reference list was given for them (unlike the player's
        // Style, which uses `karate_styles`). `age_category` is deliberately
        // separate from the shared `age_category_id` (the "Age" column) —
        // the spec lists both, mirroring the Hockey/Net Ball literal-
        // duplicate-column precedent from earlier phases.
        Schema::create('karate_career_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('karate_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('format_id')->constrained();
            $table->foreignId('age_category_id')->constrained();
            $table->foreignId('match_category_id')->constrained();
            $table->unsignedInteger('matches')->nullable();
            $table->unsignedInteger('fights')->nullable();
            $table->unsignedInteger('win')->nullable();
            $table->unsignedInteger('lost')->nullable();
            $table->string('stats')->nullable();
            $table->string('weight_category')->nullable();
            $table->string('age_category')->nullable();
            $table->unsignedInteger('third_place')->nullable();
            $table->unsignedInteger('second_place')->nullable();
            $table->unsignedInteger('champion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karate_career_stats');
    }
};
