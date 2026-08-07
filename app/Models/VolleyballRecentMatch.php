<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VolleyballRecentMatch extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'volleyball_profile_id', 'match_date', 'opponent', 'venue', 'win', 'lost',
        'set_1', 'set_2', 'set_3', 'set_4', 'set_5',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['match_date' => 'date', 'win' => 'boolean', 'lost' => 'boolean'];
    }

    public function volleyballProfile(): BelongsTo
    {
        return $this->belongsTo(VolleyballProfile::class);
    }
}
