<?php

namespace App\Http\Resources;

use App\Models\AthleticsProfile;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin AthleticsProfile */
class AthleticsProfileResource extends JsonResource
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
            'weight' => $this->weight,
            'college_university' => $this->college_university,
            'teams' => $this->team_names ?? [],
            'personal_bests' => $this->whenLoaded('personalBests', fn () => $this->personalBests),
            'career_stats' => $this->whenLoaded('careerStats', fn () => $this->careerStats),
            'recent_events' => $this->whenLoaded('recentEvents', fn () => $this->recentEvents),
        ];
    }
}
