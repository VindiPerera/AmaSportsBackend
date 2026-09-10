<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Logo image for a team name the player listed on a sport's profile (see
 * PlayerTeam) — kept in its own table so it survives that table's
 * delete-and-recreate-on-every-save cycle. See the migration for details.
 */
class PlayerTeamLogo extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'player_id',
        'sport_id',
        'team_name',
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
