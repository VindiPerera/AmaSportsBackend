<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Player\UpdatePlayerProfileRequest;
use App\Http\Resources\PlayerResource;
use App\Models\Player;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlayerProfileController extends Controller
{
    use ApiResponse;

    /**
     * GET /player/profile — the shared header (name/country/photos) shown
     * atop every sport's form. Creates the `players` row on first touch.
     */
    public function show(Request $request): JsonResponse
    {
        $player = Player::firstOrCreate(['user_id' => $request->user()->id]);

        return $this->success(new PlayerResource($player), 'Player profile retrieved successfully.');
    }

    /**
     * POST /player/profile (with `_method=PATCH`) — update the shared
     * header. Multipart, since cover/profile photo uploads live here.
     */
    public function update(UpdatePlayerProfileRequest $request): JsonResponse
    {
        $player = Player::firstOrCreate(['user_id' => $request->user()->id]);

        $player->fill($request->safe()->only(['full_name', 'country']));

        if ($request->hasFile('cover_photo')) {
            if ($player->cover_photo_url) {
                Storage::disk('public')->delete($player->cover_photo_url);
            }
            $player->cover_photo_url = $request->file('cover_photo')->store("players/{$player->id}", 'public');
        }

        if ($request->hasFile('photo')) {
            if ($player->photo_url) {
                Storage::disk('public')->delete($player->photo_url);
            }
            $player->photo_url = $request->file('photo')->store("players/{$player->id}", 'public');
        }

        $player->save();

        return $this->success(new PlayerResource($player->fresh()), 'Player profile updated successfully.');
    }
}
