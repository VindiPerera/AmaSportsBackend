<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NetBallRecentMatch extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'net_ball_profile_id', 'match_date', 'opponent', 'venue', 'goals', 'attempts',
        'goal_accuracy', 'win', 'lost',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['match_date' => 'date', 'win' => 'boolean', 'lost' => 'boolean'];
    }

    public function netBallProfile(): BelongsTo
    {
        return $this->belongsTo(NetBallProfile::class);
    }
}
