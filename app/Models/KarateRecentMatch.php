<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KarateRecentMatch extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'karate_profile_id', 'match_date', 'opponent', 'venue', 'win', 'lost',
        'stats', 'weight_category', 'age_category', 'place',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['match_date' => 'date', 'win' => 'boolean', 'lost' => 'boolean'];
    }

    public function karateProfile(): BelongsTo
    {
        return $this->belongsTo(KarateProfile::class);
    }
}
