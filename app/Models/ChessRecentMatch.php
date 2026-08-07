<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChessRecentMatch extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'chess_profile_id', 'match_date', 'opponent', 'venue', 'win', 'lost', 'place',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['match_date' => 'date', 'win' => 'boolean', 'lost' => 'boolean'];
    }

    public function chessProfile(): BelongsTo
    {
        return $this->belongsTo(ChessProfile::class);
    }
}
