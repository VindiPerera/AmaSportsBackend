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
        Schema::create('elle_recent_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('elle_profile_id')->constrained()->cascadeOnDelete();
            $table->date('match_date')->nullable();
            $table->string('opponent')->nullable();
            $table->string('venue')->nullable();
            $table->boolean('win')->default(false);
            $table->boolean('lost')->default(false);
            $table->unsignedInteger('runs')->nullable();
            $table->unsignedInteger('catches')->nullable();
            $table->string('place')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elle_recent_matches');
    }
};
