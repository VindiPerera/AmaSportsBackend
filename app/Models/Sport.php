<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sport extends Model
{
    public const CRICKET_SLUG = 'cricket';

    public const HOCKEY_SLUG = 'hockey';

    public const VOLLEYBALL_SLUG = 'volleyball';

    public const BADMINTON_SLUG = 'badminton';

    /** Sports the admin Live Score panel currently builds a scoreboard for. */
    public const ADMIN_LIVE_SCORE_SLUGS = [
        self::CRICKET_SLUG,
        self::VOLLEYBALL_SLUG,
        self::BADMINTON_SLUG,
    ];

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'slug',
        'has_full_form',
        'sort_order',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'has_full_form' => 'boolean',
        ];
    }

    public function playerSports(): HasMany
    {
        return $this->hasMany(PlayerSport::class);
    }
}
