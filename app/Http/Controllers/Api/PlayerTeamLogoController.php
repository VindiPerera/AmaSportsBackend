<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Player;
use App\Models\PlayerTeamLogo;
use App\Models\Sport;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PlayerTeamLogoController extends Controller
{
    use ApiResponse;

    /**
     * POST /player/team-logo — uploads (or replaces) the logo for one of the
     * free-text team names a player listed on a sport's profile (see
     * TeamsInput on the mobile app). Multipart; immediate, not part of the
     * profile's bulk save — same pattern as the player's own avatar/cover
     * photo (see PlayerProfileController).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            // Defaults to cricket — the only form this is wired up on today
            // — but accepts any sport slug so other sports can adopt it
            // later without a backend change.
            'sport' => ['nullable', 'string', 'exists:sports,slug'],
            'team_name' => ['required', 'string', 'max:255'],
            'logo' => ['required', 'image', 'max:5120'],
        ]);

        $player = Player::firstOrCreate(['user_id' => $request->user()->id]);
        $sport = Sport::where('slug', $validated['sport'] ?? Sport::CRICKET_SLUG)->firstOrFail();
        $teamName = trim($validated['team_name']);

        $existing = PlayerTeamLogo::where('player_id', $player->id)
            ->where('sport_id', $sport->id)
            ->where('team_name', $teamName)
            ->first();

        if ($existing) {
            Storage::disk('public')->delete($existing->logo_path);
        }

        $logoPath = $request->file('logo')->store("players/{$player->id}/teams", 'public');

        $logo = PlayerTeamLogo::updateOrCreate(
            ['player_id' => $player->id, 'sport_id' => $sport->id, 'team_name' => $teamName],
            ['logo_path' => $logoPath]
        );

        return $this->success([
            'team_name' => $logo->team_name,
            'logo_url' => Storage::disk('public')->url($logo->logo_path),
        ], 'Team logo uploaded successfully.');
    }

    /**
     * DELETE /player/team-logo — removes a previously uploaded team logo
     * (the team name itself, in `player_teams`, is untouched — this only
     * clears the logo).
     */
    public function destroy(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'sport' => ['nullable', 'string', 'exists:sports,slug'],
            'team_name' => ['required', 'string', 'max:255'],
        ]);

        $player = Player::firstOrCreate(['user_id' => $request->user()->id]);
        $sport = Sport::where('slug', $validated['sport'] ?? Sport::CRICKET_SLUG)->firstOrFail();
        $teamName = trim($validated['team_name']);

        $logo = PlayerTeamLogo::where('player_id', $player->id)
            ->where('sport_id', $sport->id)
            ->where('team_name', $teamName)
            ->first();

        if ($logo) {
            Storage::disk('public')->delete($logo->logo_path);
            $logo->delete();
        }

        return $this->success(null, 'Team logo removed successfully.');
    }
}
