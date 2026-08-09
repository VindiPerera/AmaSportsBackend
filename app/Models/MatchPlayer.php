<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * An ad-hoc roster entry (ID number / name / photo) an admin adds to one
 * side of a match. Not tied to `players`/`users` — these participants don't
 * have app accounts, unlike the mobile app's own athlete-profile system.
 */
class MatchPlayer extends Model
{
    public const SIDE_HOME = 'home';

    public const SIDE_AWAY = 'away';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'match_id',
        'side',
        'id_number',
        'full_name',
        'photo_url',
        'sort_order',
    ];

    public function match(): BelongsTo
    {
        return $this->belongsTo(GameMatch::class, 'match_id');
    }
}
