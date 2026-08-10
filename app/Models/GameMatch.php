<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

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
        'format_id',
        'age_category_id',
        'match_category_id',
        'country',
        'contact_mobile',
        'contact_whatsapp',
        'contact_email',
        'created_by',
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

    public function format(): BelongsTo
    {
        return $this->belongsTo(Format::class);
    }

    public function ageCategory(): BelongsTo
    {
        return $this->belongsTo(AgeCategory::class);
    }

    public function matchCategory(): BelongsTo
    {
        return $this->belongsTo(MatchCategory::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function matchPlayers(): HasMany
    {
        return $this->hasMany(MatchPlayer::class, 'match_id')->orderBy('sort_order');
    }

    public function liveStreamAccess(): HasMany
    {
        return $this->hasMany(LiveStreamAccess::class, 'match_id');
    }

    /**
     * The $5 pay-per-match streaming unlock that currently governs this
     * match — always the most recent row, mirroring Player::latestSubscription().
     * Reuses an already eager-loaded `liveStreamAccess` relation (see
     * Api\MatchController::BASE_RELATIONS) instead of firing a fresh query
     * per match, since GameMatchResource calls this once per row in the
     * Live Score list.
     */
    public function latestLiveStreamAccess(): ?LiveStreamAccess
    {
        if ($this->relationLoaded('liveStreamAccess')) {
            return $this->liveStreamAccess->sortByDesc('id')->first();
        }

        return $this->liveStreamAccess()->latest('id')->first();
    }

    /** Whether the admin-pasted YouTube URL should currently be exposed to viewers. */
    public function hasActiveStreamAccess(): bool
    {
        return (bool) $this->latestLiveStreamAccess()?->isActive();
    }

    public function homePlayers(): HasMany
    {
        return $this->matchPlayers()->where('side', MatchPlayer::SIDE_HOME);
    }

    public function awayPlayers(): HasMany
    {
        return $this->matchPlayers()->where('side', MatchPlayer::SIDE_AWAY);
    }

    /**
     * Build the admin-panel-authoritative snapshot pushed to Firestore
     * (`live_scores/{id}`) on Start/Finish. Matches the `MultiSportLiveScore`
     * shape sport-mobile/src/services/firebaseService.ts already expects —
     * everything except the sport-specific score block, which the caller
     * merges in separately.
     *
     * @return array<string, mixed>
     */
    public function toFirestoreSnapshot(): array
    {
        $this->loadMissing(['sport', 'homeTeam', 'awayTeam', 'format', 'ageCategory', 'matchCategory', 'matchPlayers']);

        return [
            'match_id' => $this->id,
            'sport_slug' => $this->sport?->slug,
            'status' => $this->status,
            'format' => $this->format?->name,
            'age_group' => $this->ageCategory?->name,
            'category' => $this->matchCategory?->name,
            'date' => $this->scheduled_at?->toDateString(),
            'venue' => $this->venue,
            'country' => $this->country,
            'home_team' => $this->teamSnapshot($this->homeTeam, MatchPlayer::SIDE_HOME),
            'away_team' => $this->teamSnapshot($this->awayTeam, MatchPlayer::SIDE_AWAY),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function teamSnapshot(?Team $team, string $side): array
    {
        return [
            'name' => $team?->name,
            'logo_url' => $this->resolveUrl($team?->logo_url),
            'photo_url' => $this->resolveUrl($team?->photo_url),
            'school_academy' => $team?->school_academy,
            'club' => $team?->club,
            'players' => $this->matchPlayers
                ->where('side', $side)
                ->map(fn (MatchPlayer $player) => [
                    'id' => (string) $player->id,
                    'name' => $player->full_name,
                    'photo_url' => $this->resolveUrl($player->photo_url),
                ])
                ->values()
                ->all(),
        ];
    }

    private function resolveUrl(?string $path): ?string
    {
        return $path ? Storage::disk('public')->url($path) : null;
    }
}
