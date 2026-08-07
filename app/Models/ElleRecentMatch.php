<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ElleRecentMatch extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'elle_profile_id', 'match_date', 'opponent', 'venue', 'win', 'lost', 'runs', 'catches', 'place',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['match_date' => 'date', 'win' => 'boolean', 'lost' => 'boolean'];
    }

    public function elleProfile(): BelongsTo
    {
        return $this->belongsTo(ElleProfile::class);
    }
}
