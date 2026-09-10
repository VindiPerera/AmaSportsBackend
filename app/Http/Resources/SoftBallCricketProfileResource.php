<?php

namespace App\Http\Resources;

use App\Models\SoftBallCricketProfile;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin SoftBallCricketProfile */
class SoftBallCricketProfileResource extends JsonResource
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
            'teams' => $this->team_names ?? [],
            'team_logos' => $this->team_logos ?? [],
            'college_logo_url' => $this->college_logo_url ?? null,
            'batting' => $this->whenLoaded('battingStats', fn () => $this->battingStats),
            'bowling' => $this->whenLoaded('bowlingStats', fn () => $this->bowlingStats),
            'recent_matches' => $this->whenLoaded('recentMatches', fn () => $this->recentMatches),
        ];
    }
}
