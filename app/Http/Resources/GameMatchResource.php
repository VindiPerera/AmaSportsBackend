<?php

namespace App\Http\Resources;

use App\Models\GameMatch;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin GameMatch
 *
 * Used for both the Live Score list and the Match Detail screen — the list
 * just ignores `live_score`/`youtube_stream_url` client-side.
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
            'home_team' => ['id' => $this->homeTeam->id, 'name' => $this->homeTeam->name],
            'away_team' => ['id' => $this->awayTeam->id, 'name' => $this->awayTeam->name],
            'status' => $this->status,
            'scheduled_at' => $this->scheduled_at?->toISOString(),
            'venue' => $this->venue,
            'live_score' => $this->live_score,
            'youtube_stream_url' => $this->youtube_stream_url,
        ];
    }
}
