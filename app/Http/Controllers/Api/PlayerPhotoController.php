<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\PlayerPhoto;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlayerPhotoController extends Controller
{
    use ApiResponse;

    /**
     * POST /player/photos — adds one photo to the player's gallery (up to
     * PlayerPhoto::MAX_PHOTOS). Immediate, not part of any bulk profile
     * save — same "own endpoint" pattern as team-logo/college-logo. The
     * mobile app converts to WebP and compresses under 1MB before this is
     * ever called; `mimes:webp` + `max:1024` re-check that server-side
     * rather than trusting the client.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'photo' => ['required', 'image', 'mimes:webp', 'max:1024'],
        ]);

        $player = Player::firstOrCreate(['user_id' => $request->user()->id]);

        if ($player->photos()->count() >= PlayerPhoto::MAX_PHOTOS) {
            return $this->error(
                'You can only have up to ' . PlayerPhoto::MAX_PHOTOS . ' photos. Remove one before adding another.',
                422
            );
        }

        $path = $request->file('photo')->store("players/{$player->id}/gallery", 'public');

        $photo = $player->photos()->create(['path' => $path]);

        return $this->success([
            'id' => $photo->id,
            'url' => Storage::disk('public')->url($photo->path),
        ], 'Photo uploaded successfully.', 201);
    }

    /**
     * DELETE /player/photos/{photo} — removes one gallery photo. Scoped to
     * the authenticated player's own photos via the route-model-bound
     * player check below, not just the photo id, so one player can't delete
     * another's.
     */
    public function destroy(Request $request, PlayerPhoto $photo): JsonResponse
    {
        $player = Player::firstOrCreate(['user_id' => $request->user()->id]);

        if ($photo->player_id !== $player->id) {
            return $this->error('Photo not found.', 404);
        }

        Storage::disk('public')->delete($photo->path);
        $photo->delete();

        return $this->success(null, 'Photo removed successfully.');
    }
}
