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
        Schema::create('basketball_career_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('basketball_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('format_id')->constrained();
            $table->foreignId('age_category_id')->constrained();
            $table->foreignId('match_category_id')->constrained();
            $table->unsignedInteger('matches')->nullable();
            $table->unsignedInteger('win')->nullable();
            $table->unsignedInteger('lost')->nullable();
            $table->unsignedInteger('points')->nullable();
            $table->unsignedInteger('rebounds')->nullable();
            $table->unsignedInteger('assists')->nullable();
            $table->unsignedInteger('blocks')->nullable();
            $table->unsignedInteger('steals')->nullable();
            $table->unsignedInteger('minutes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('basketball_career_stats');
    }
};
