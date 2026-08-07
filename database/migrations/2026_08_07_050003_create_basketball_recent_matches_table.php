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
        Schema::create('basketball_recent_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('basketball_profile_id')->constrained()->cascadeOnDelete();
            $table->date('match_date')->nullable();
            $table->string('opponent')->nullable();
            $table->string('venue')->nullable();
            $table->boolean('win')->default(false);
            $table->boolean('lost')->default(false);
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
        Schema::dropIfExists('basketball_recent_matches');
    }
};
