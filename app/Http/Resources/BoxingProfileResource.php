<?php

namespace App\Http\Resources;

use App\Models\BoxingProfile;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin BoxingProfile */
class BoxingProfileResource extends JsonResource
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
            'weight_class_id' => $this->weight_class_id,
            'current_ranking' => $this->current_ranking,
            'college_university' => $this->college_university,
            'teams' => $this->team_names ?? [],
            'career_stats' => $this->whenLoaded('careerStats', fn () => $this->careerStats),
            'recent_fights' => $this->whenLoaded('recentFights', fn () => $this->recentFights),
        ];
    }
}
