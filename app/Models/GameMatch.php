<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Represents a row in the `matches` table. Named `GameMatch` (not `Match`)
 * because `match` is a reserved word in PHP 8.
 */
class GameMatch extends Model
{
    protected $table = 'matches';

    public const STATUS_UPCOMING = 'upcoming';

    public const STATUS_LIVE = 'live';

    public const STATUS_FINISHED = 'finished';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'sport_id',
        'home_team_id',
        'away_team_id',
        'status',
        'scheduled_at',
        'venue',
        'live_score',
        'youtube_stream_url',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'scheduled_at' => 'datetime',
            'live_score' => 'array',
        ];
    }

    public function sport(): BelongsTo
    {
        return $this->belongsTo(Sport::class);
    }

    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }
}
