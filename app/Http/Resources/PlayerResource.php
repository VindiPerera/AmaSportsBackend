<?php

namespace App\Http\Resources;

use App\Models\Player;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/** @mixin Player */
class PlayerResource extends JsonResource
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
            'full_name' => $this->full_name,
            'country' => $this->country,
            'cover_photo_url' => $this->cover_photo_url ? Storage::disk('public')->url($this->cover_photo_url) : null,
            'photo_url' => $this->photo_url ? Storage::disk('public')->url($this->photo_url) : null,
        ];
    }
}
