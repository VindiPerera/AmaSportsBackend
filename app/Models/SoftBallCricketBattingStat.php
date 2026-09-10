<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SoftBallCricketBattingStat extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'soft_ball_cricket_profile_id', 'matches', 'runs', 'innings', 'highest', 'not_out',
        'hundreds', 'fifties', 'sixes', 'fours', 'catches', 'stumpings', 'won', 'lost', 'tied',
    ];

    public function softBallCricketProfile(): BelongsTo
    {
        return $this->belongsTo(SoftBallCricketProfile::class);
    }
}
