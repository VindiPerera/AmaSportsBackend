<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlayerPhoto extends Model
{
    /** Gallery cap — enforced in PlayerPhotoController::store(). */
    public const MAX_PHOTOS = 10;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'player_id',
        'path',
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }
}
