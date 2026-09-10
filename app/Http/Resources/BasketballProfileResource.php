<?php

namespace App\Http\Resources;

use App\Models\BasketballProfile;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin BasketballProfile */
class BasketballProfileResource extends JsonResource
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
            'college_logo_url' => $this->college_logo_url ?? null,
            'teams' => $this->team_names ?? [],
            'team_logos' => $this->team_logos ?? [],
            'career_stats' => $this->whenLoaded('careerStats', fn () => $this->careerStats),
            'recent_matches' => $this->whenLoaded('recentMatches', fn () => $this->recentMatches),
        ];
    }
}
