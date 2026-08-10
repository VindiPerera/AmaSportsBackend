<?php

namespace App\Http\Resources;

use App\Models\RacketSportProfile;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin RacketSportProfile */
class RacketSportProfileResource extends JsonResource
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
            'sport_id' => $this->sport_id,
            'born' => $this->born?->toDateString(),
            'age' => $this->age,
            'height' => $this->height,
            'dominant_hand' => $this->dominant_hand,
            'weight' => $this->weight,
            'current_ranking' => $this->current_ranking,
            'college_university' => $this->college_university,
            'teams' => $this->team_names ?? [],
            'career_stats' => $this->whenLoaded('careerStats', fn () => $this->careerStats),
            'recent_matches' => $this->whenLoaded('recentMatches', fn () => $this->recentMatches),
        ];
    }
}
