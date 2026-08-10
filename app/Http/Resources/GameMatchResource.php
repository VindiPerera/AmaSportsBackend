<?php

namespace App\Http\Resources;

use App\Models\GameMatch;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin GameMatch
 *
 * Used for both the Live Score list and the Match Detail screen. The admin
 * panel (see App\Models\GameMatch::toFirestoreSnapshot()) populates
 * format/age/category/country and team logo/photo/school/club/roster —
 * mirrored here so the mobile app's REST fetch (sport-mobile's
 * `MatchSummary`/`MatchTeam` types already declare these fields) has real
 * data to prefer over its Firestore/mock fallback.
 */
class GameMatchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'sport' => [
                'id' => $this->sport->id,
                'name' => $this->sport->name,
                'slug' => $this->sport->slug,
            ],
            'home_team' => $this->teamPayload($this->homeTeam, 'home'),
            'away_team' => $this->teamPayload($this->awayTeam, 'away'),
            'status' => $this->status,
            'scheduled_at' => $this->scheduled_at?->toISOString(),
            'venue' => $this->venue,
            'format' => $this->format?->name,
            'age_group' => $this->ageCategory?->name,
            'category' => $this->matchCategory?->name,
            'country' => $this->country,
            'date' => $this->scheduled_at?->toDateString(),
            'live_score' => $this->live_score,
            'youtube_stream_url' => $this->youtube_stream_url,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function teamPayload(?Team $team, string $side): array
    {
        $payload = [
            'id' => $team?->id,
            'name' => $team?->name,
            'logo_url' => $this->resolveUrl($team?->logo_url),
            'photo_url' => $this->resolveUrl($team?->photo_url),
            'school_academy' => $team?->school_academy,
            'club' => $team?->club,
            'country' => $team?->country,
        ];

        // Roster only travels on the detail screen (MatchController::show
        // eager-loads matchPlayers) — the list endpoint stays light.
        if ($this->relationLoaded('matchPlayers')) {
            $payload['players'] = $this->matchPlayers
                ->where('side', $side)
                ->map(fn ($player) => [
                    'id' => (string) $player->id,
                    'name' => $player->full_name,
                    'photo_url' => $this->resolveUrl($player->photo_url),
                ])
                ->values()
                ->all();
        }

        return $payload;
    }

    private function resolveUrl(?string $path): ?string
    {
        return $path ? Storage::disk('public')->url($path) : null;
    }
}
