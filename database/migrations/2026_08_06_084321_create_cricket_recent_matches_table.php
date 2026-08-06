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
        // Repeatable "Recent Matches" rows, chronological (spec 6.2).
        Schema::create('cricket_recent_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cricket_profile_id')->constrained()->cascadeOnDelete();
            $table->date('match_date')->nullable();
            $table->string('opponent')->nullable();
            $table->boolean('played_xi')->default(false);
            $table->unsignedInteger('runs')->nullable();
            $table->unsignedInteger('balls')->nullable();
            $table->unsignedInteger('fours')->nullable();
            $table->unsignedInteger('sixes')->nullable();
            $table->decimal('overs', 5, 1)->nullable();
            $table->unsignedInteger('maidens')->nullable();
            $table->unsignedInteger('wickets')->nullable();
            $table->unsignedInteger('catches')->nullable();
            $table->unsignedInteger('stumpings')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cricket_recent_matches');
    }
};
