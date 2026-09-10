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
        // A player's photo gallery — up to 10 (enforced in
        // PlayerPhotoController, since Laravel has no "max rows" validation
        // rule). Player-level, not sport-specific — same idea as photo_url/
        // cover_photo_url on `players`, just repeatable.
        Schema::create('player_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('player_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_photos');
    }
};
