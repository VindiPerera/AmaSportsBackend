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
        // Wires the admin Match Setup form's Format/Age/Category fields onto
        // the already-seeded lookup tables, plus a few paper-form-accurate
        // bookkeeping fields. All nullable — existing rows and the public
        // API (routes/api.php) are unaffected.
        Schema::table('matches', function (Blueprint $table) {
            $table->foreignId('format_id')->nullable()->after('sport_id')->constrained()->nullOnDelete();
            $table->foreignId('age_category_id')->nullable()->after('format_id')->constrained()->nullOnDelete();
            $table->foreignId('match_category_id')->nullable()->after('age_category_id')->constrained()->nullOnDelete();
            $table->string('country')->nullable()->after('venue');
            $table->string('contact_mobile', 30)->nullable()->after('country');
            $table->string('contact_whatsapp', 30)->nullable()->after('contact_mobile');
            $table->string('contact_email')->nullable()->after('contact_whatsapp');
            $table->foreignId('created_by')->nullable()->after('contact_email')->constrained('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropConstrainedForeignId('format_id');
            $table->dropConstrainedForeignId('age_category_id');
            $table->dropConstrainedForeignId('match_category_id');
            $table->dropConstrainedForeignId('created_by');
            $table->dropColumn(['country', 'contact_mobile', 'contact_whatsapp', 'contact_email']);
        });
    }
};
