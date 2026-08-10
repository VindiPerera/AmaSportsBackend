<?php

namespace App\Http\Resources;

use App\Models\BeachVolleyballProfile;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin BeachVolleyballProfile */
class BeachVolleyballProfileResource extends JsonResource
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
            'height' => $this->height,
            'dominant_hand' => $this->dominant_hand,
            'player_position' => $this->player_position,
            'college_university' => $this->college_university,
            'teams' => $this->team_names ?? [],
            'career_stats' => $this->whenLoaded('careerStats', fn () => $this->careerStats),
            'recent_matches' => $this->whenLoaded('recentMatches', fn () => $this->recentMatches),
        ];
    }
}
