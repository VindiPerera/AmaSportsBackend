<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BaseBallRecentMatch extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'base_ball_profile_id', 'match_date', 'opponent', 'venue', 'won', 'lost', 'runs',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['match_date' => 'date', 'won' => 'boolean', 'lost' => 'boolean'];
    }

    public function baseBallProfile(): BelongsTo
    {
        return $this->belongsTo(BaseBallProfile::class);
    }
}
