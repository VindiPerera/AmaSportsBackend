<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Logo image for a player's School/College/University per sport.
 */
class PlayerCollegeLogo extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'player_id',
        'sport_id',
        'logo_path',
    ];

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public function sport(): BelongsTo
    {
        return $this->belongsTo(Sport::class);
    }
}
