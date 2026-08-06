<?php

namespace App\Http\Resources;

use App\Models\CricketProfile;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin CricketProfile */
class CricketProfileResource extends JsonResource
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
            'born' => $this->born?->toDateString(),
            'age' => $this->age,
            'batting_style' => $this->batting_style,
            'bowling_style' => $this->bowling_style,
            'playing_role' => $this->playing_role,
            'height' => $this->height,
            'college_university' => $this->college_university,
            // Set explicitly by the controller (player_teams is keyed by
            // player_id, not cricket_profile_id, so it isn't a real relation
            // on this model).
            'teams' => $this->team_names ?? [],
            'batting' => $this->whenLoaded('battingStats', fn () => $this->battingStats),
            'bowling' => $this->whenLoaded('bowlingStats', fn () => $this->bowlingStats),
            'recent_matches' => $this->whenLoaded('recentMatches', fn () => $this->recentMatches),
        ];
    }
}
