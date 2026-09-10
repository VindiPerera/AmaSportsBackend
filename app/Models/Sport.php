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

    public const BASE_BALL_SLUG = 'base-ball';

    public const NETBALL_SLUG = 'netball';

    public const TENNIS_SLUG = 'tennis';

    public const TABLE_TENNIS_SLUG = 'table-tennis';

    public const KABADI_SLUG = 'kabadi';

    public const JUDO_SLUG = 'judo';

    public const BASKETBALL_SLUG = 'basketball';

    public const FOOTBALL_SLUG = 'football';

    public const RUGBY_SLUG = 'rugby';

    public const BOXING_SLUG = 'boxing';

    public const KARATE_SLUG = 'karate';

    public const CHESS_SLUG = 'chess';

    public const ATHLETICS_SLUG = 'athletics';

    public const SWIMMING_SLUG = 'swimming';

    public const BEACH_VOLLEYBALL_SLUG = 'beach-volleyball';

    public const ELLE_SLUG = 'elle';

    public const SOFT_BALL_CRICKET_SLUG = 'soft-ball-cricket';

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
