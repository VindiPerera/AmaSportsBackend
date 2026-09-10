<?php

namespace App\Http\Resources;

use App\Models\ChessProfile;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin ChessProfile */
class ChessProfileResource extends JsonResource
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
            'current_ranking' => $this->current_ranking,
            'college_university' => $this->college_university,
            'teams' => $this->team_names ?? [],
            'team_logos' => $this->team_logos ?? [],
            'college_logo_url' => $this->college_logo_url ?? null,
            'career_stats' => $this->whenLoaded('careerStats', fn () => $this->careerStats),
            'recent_matches' => $this->whenLoaded('recentMatches', fn () => $this->recentMatches),
        ];
    }
}
