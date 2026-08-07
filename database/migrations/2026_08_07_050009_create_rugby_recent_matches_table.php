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
        // No `venue` column — the spec's Recent Matches table for Rugby
        // doesn't list one (unlike most other sports).
        Schema::create('rugby_recent_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rugby_profile_id')->constrained()->cascadeOnDelete();
            $table->date('match_date')->nullable();
            $table->string('opponent')->nullable();
            $table->boolean('win')->default(false);
            $table->boolean('lost')->default(false);
            $table->unsignedInteger('tries')->nullable();
            $table->unsignedInteger('conversion')->nullable();
            $table->unsignedInteger('penalty_kick')->nullable();
            $table->unsignedInteger('drop_goal')->nullable();
            $table->unsignedInteger('yellow_card')->nullable();
            $table->unsignedInteger('red_card')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rugby_recent_matches');
    }
};
