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
        // Set scores stored as short strings (e.g. "25-20"), same pattern
        // as racket_sport_recent_matches — a set result isn't a single number.
        Schema::create('volleyball_recent_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('volleyball_profile_id')->constrained()->cascadeOnDelete();
            $table->date('match_date')->nullable();
            $table->string('opponent')->nullable();
            $table->string('venue')->nullable();
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
        Schema::dropIfExists('volleyball_recent_matches');
    }
};
