<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HockeyRecentMatch extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'hockey_profile_id',
        'match_date',
        'opponent',
        'venue',
        'goals',
        'assist_goals',
        'defeat_goals',
        'won',
        'lost',
        'drawn',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'match_date' => 'date',
            'won' => 'boolean',
            'lost' => 'boolean',
            'drawn' => 'boolean',
        ];
    }

    public function hockeyProfile(): BelongsTo
    {
        return $this->belongsTo(HockeyProfile::class);
    }
}
