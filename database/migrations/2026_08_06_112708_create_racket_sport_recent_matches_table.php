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
        // One shared recent-matches table (not split by Single/Double/Mix)
        // per spec Phase 2 §B3. Set scores are stored as short strings
        // (e.g. "6-4") rather than numbers, since a set result isn't a
        // single number.
        Schema::create('racket_sport_recent_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('racket_sport_profile_id')->constrained()->cascadeOnDelete();
            $table->date('match_date')->nullable();
            $table->string('opponent')->nullable();
            $table->boolean('win')->default(false);
            $table->boolean('lost')->default(false);
            $table->string('set_1')->nullable();
            $table->string('set_2')->nullable();
            $table->string('set_3')->nullable();
            $table->string('set_4')->nullable();
            $table->string('set_5')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('racket_sport_recent_matches');
    }
};
