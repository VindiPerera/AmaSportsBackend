<?php

namespace App\Http\Resources;

use App\Models\PlayerAchievement;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin PlayerAchievement */
class PlayerAchievementResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->achievement->title,
            'description' => $this->achievement->description,
            'icon' => $this->achievement->icon,
            'color' => $this->achievement->color,
            'threshold' => $this->achievement->threshold,
            'achieved_value' => $this->achieved_value,
            'unlocked_at' => $this->unlocked_at?->toIso8601String(),
            'posted_at' => $this->posted_at?->toIso8601String(),
        ];
    }
}
