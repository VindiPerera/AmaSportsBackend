<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Permanently deletes a user's account — required by Google Play's User
 * Data policy for any app that lets people create an account. Used by both
 * the in-app "Delete account" action (DELETE /api/user) and the public
 * web deletion page (/delete-account) Play Console links to.
 *
 * Every player table cascades off users -> players, so deleting the user
 * row removes profiles, stats, photos, achievements and subscriptions; this
 * only has to clean up the uploaded files (all under players/{id}/ on the
 * public disk) and the Sanctum tokens.
 */
class AccountDeletionService
{
    public function delete(User $user): void
    {
        $playerId = $user->player?->id;

        DB::transaction(function () use ($user) {
            $user->tokens()->delete();
            DB::table('sessions')->where('user_id', $user->id)->delete();
            $user->delete();
        });

        if ($playerId !== null) {
            Storage::disk('public')->deleteDirectory("players/{$playerId}");
        }
    }
}
