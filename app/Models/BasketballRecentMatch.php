<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BasketballRecentMatch extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'basketball_profile_id', 'match_date', 'opponent', 'venue', 'win', 'lost',
        'points', 'rebounds', 'assists', 'blocks', 'steals', 'minutes',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['match_date' => 'date', 'win' => 'boolean', 'lost' => 'boolean'];
    }

    public function basketballProfile(): BelongsTo
    {
        return $this->belongsTo(BasketballProfile::class);
    }
}
