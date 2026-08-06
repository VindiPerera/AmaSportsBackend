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
        // Repeatable "Recent Match" rows (spec 6.3).
        Schema::create('hockey_recent_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hockey_profile_id')->constrained()->cascadeOnDelete();
            $table->date('match_date')->nullable();
            $table->string('opponent')->nullable();
            $table->string('venue')->nullable();
            $table->unsignedInteger('goals')->nullable();
            $table->unsignedInteger('assist_goals')->nullable();
            $table->unsignedInteger('defeat_goals')->nullable();
            $table->boolean('won')->default(false);
            $table->boolean('lost')->default(false);
            $table->boolean('drawn')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hockey_recent_matches');
    }
};
