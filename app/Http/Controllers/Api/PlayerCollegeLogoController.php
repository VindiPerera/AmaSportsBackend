<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\PlayerCollegeLogo;
use App\Models\Sport;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlayerCollegeLogoController extends Controller
{
    use ApiResponse;

    /**
     * POST /player/college-logo — uploads (or replaces) the School/College/University
     * logo for a specific sport. Defaults to cricket if sport is omitted.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'sport' => ['nullable', 'string', 'exists:sports,slug'],
            'logo' => ['required', 'image', 'max:5120'],
        ]);

        $player = Player::firstOrCreate(['user_id' => $request->user()->id]);
        $sport = Sport::where('slug', $validated['sport'] ?? Sport::CRICKET_SLUG)->firstOrFail();

        $existing = PlayerCollegeLogo::where('player_id', $player->id)
            ->where('sport_id', $sport->id)
            ->first();

        if ($existing && $existing->logo_path) {
            Storage::disk('public')->delete($existing->logo_path);
        }

        $logoPath = $request->file('logo')->store("players/{$player->id}/college/{$sport->slug}", 'public');

        $logo = PlayerCollegeLogo::updateOrCreate(
            ['player_id' => $player->id, 'sport_id' => $sport->id],
            ['logo_path' => $logoPath]
        );

        // Keep cricket_profiles.college_logo_path in sync for backwards compatibility
        if ($sport->slug === Sport::CRICKET_SLUG) {
            $cricketProfile = $player->cricketProfile;
            if ($cricketProfile) {
                if ($cricketProfile->college_logo_path && $cricketProfile->college_logo_path !== $logoPath) {
                    Storage::disk('public')->delete($cricketProfile->college_logo_path);
                }
                $cricketProfile->update(['college_logo_path' => $logoPath]);
            }
        }

        return $this->success([
            'college_logo_url' => Storage::disk('public')->url($logo->logo_path),
        ], 'College logo uploaded successfully.');
    }

    /**
     * DELETE /player/college-logo — removes the School/College/University logo
     * for a specific sport.
     */
    public function destroy(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'sport' => ['nullable', 'string', 'exists:sports,slug'],
        ]);

        $player = Player::firstOrCreate(['user_id' => $request->user()->id]);
        $sport = Sport::where('slug', $validated['sport'] ?? Sport::CRICKET_SLUG)->firstOrFail();

        $logo = PlayerCollegeLogo::where('player_id', $player->id)
            ->where('sport_id', $sport->id)
            ->first();

        if ($logo) {
            if ($logo->logo_path) {
                Storage::disk('public')->delete($logo->logo_path);
            }
            $logo->delete();
        }

        if ($sport->slug === Sport::CRICKET_SLUG) {
            $cricketProfile = $player->cricketProfile;
            if ($cricketProfile?->college_logo_path) {
                Storage::disk('public')->delete($cricketProfile->college_logo_path);
                $cricketProfile->update(['college_logo_path' => null]);
            }
        }

        return $this->success(null, 'College logo removed successfully.');
    }
}
