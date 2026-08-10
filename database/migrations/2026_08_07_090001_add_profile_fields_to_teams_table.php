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
        // Lets a `Team` (a Live Score match side) carry the profile info the
        // admin panel's Match Setup form collects — country/school/club plus
        // an uploaded logo/photo — so a team becomes reusable/searchable
        // across matches instead of just a bare name. `logo_url`/`photo_url`
        // store relative storage paths, matching the Player model's
        // `*_url` naming convention (see PlayerProfileController).
        Schema::table('teams', function (Blueprint $table) {
            $table->string('country')->nullable()->after('name');
            $table->string('school_academy')->nullable()->after('country');
            $table->string('club')->nullable()->after('school_academy');
            $table->string('logo_url')->nullable()->after('club');
            $table->string('photo_url')->nullable()->after('logo_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn(['country', 'school_academy', 'club', 'logo_url', 'photo_url']);
        });
    }
};
