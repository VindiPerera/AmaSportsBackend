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
        Schema::create('kabadi_recent_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kabadi_profile_id')->constrained()->cascadeOnDelete();
            $table->date('match_date')->nullable();
            $table->string('opponent')->nullable();
            $table->string('venue')->nullable();
            $table->boolean('win')->default(false);
            $table->boolean('lost')->default(false);
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
        Schema::dropIfExists('kabadi_recent_matches');
    }
};
