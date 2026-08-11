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
            // Career-to-date bowling breakdown — see Phase 7 migration note
            // on cricket_profiles. Always an object, never null, so the
            // mobile form doesn't need a null-guard per lookup category.
            //
            // Cast to (object): every key here is a numeric lookup id, and
            // JsonResource::filter() -> removeMissingValues() silently
            // reindexes any array whose keys are ALL numeric back to a
            // 0-based list via array_values() — discarding the id => count
            // mapping entirely and turning e.g. {"3": 20} into a bare
            // position in a JSON array. Casting to stdClass skips that
            // array-specific filtering path (is_array() is false) so the
            // keys survive into the JSON response as object properties.
            'pitching_line_breakdown' => (object) ($this->pitching_line_breakdown ?? []),
            'ball_type_breakdown' => (object) ($this->ball_type_breakdown ?? []),
            // Set explicitly by the controller (player_teams is keyed by
            // player_id, not cricket_profile_id, so it isn't a real relation
            // on this model).
            'teams' => $this->team_names ?? [],
            'batting' => $this->whenLoaded('battingStats', fn () => $this->battingStats),
            'bowling' => $this->whenLoaded('bowlingStats', fn () => $this->bowlingStats),
            'recent_matches' => $this->whenLoaded('recentMatches', fn () => $this->recentMatches),
            'drop_catches' => $this->whenLoaded('dropCatches', fn () => $this->dropCatches),
        ];
    }
}
