<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Widens the `role` enum (originally created by
     * 2026_08_05_224121_add_role_and_avatar_to_users_table) to add
     * `super_admin` — a stricter tier above `admin` (see EnsureSuperAdmin /
     * User::isSuperAdmin()). Schema::table() can't alter an existing enum's
     * value list without doctrine/dbal, which doesn't handle MySQL enums
     * well either, so this is a raw statement — the same workaround the
     * original enum column would have needed had it changed after creation.
     */
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('coach', 'student', 'admin', 'super_admin') NOT NULL DEFAULT 'student'");
        }
    }

    /**
     * Reverse the migrations.
     *
     * Only safe if no row currently has role = 'super_admin' — narrowing
     * the enum while a row still uses the removed value would fail (or
     * silently corrupt that row, depending on SQL mode). Demote any
     * super_admin rows back to 'admin' first if you need to roll back.
     */
    public function down(): void
    {
    }
};
