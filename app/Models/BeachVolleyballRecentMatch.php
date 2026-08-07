<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BeachVolleyballRecentMatch extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'beach_volleyball_profile_id', 'match_date', 'opponent', 'venue', 'win', 'lost',
        'set_1', 'set_2', 'set_3',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['match_date' => 'date', 'win' => 'boolean', 'lost' => 'boolean'];
    }

    public function beachVolleyballProfile(): BelongsTo
    {
        return $this->belongsTo(BeachVolleyballProfile::class);
    }
}
