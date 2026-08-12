<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A single $5 "unlock live streaming for this match" payment — either made
 * by the admin running the match (Admin\StreamAccessController), or by any
 * player paying for "VIP access" from the stream screen itself
 * (Api\StreamAccessController, Phase 6 revision 3). Either way it's the
 * same row/flow: access is match-scoped, not player-scoped, so whoever
 * pays first unlocks the stream for every viewer — Live Score itself
 * always stays free regardless. One match can have more than one
 * historical row (e.g. a cancelled attempt followed by a paid one); current
 * access is always resolved from the latest row for that match — see
 * GameMatch::hasActiveStreamAccess().
 */
class LiveStreamAccess extends Model
{
    protected $table = 'live_stream_access';

    public const STATUS_PENDING = 'pending';

    public const STATUS_ACTIVE = 'active';

    public const STATUS_EXPIRED = 'expired';

    public const STATUS_CANCELLED = 'cancelled';

    /** Fixed price per match for now. */
    public const AMOUNT = 5.00;

    /**
     * @var list<string>
     */
    protected $fillable = [
        'match_id',
        'paid_by',
        'paypal_order_id',
        'amount',
        'currency',
        'status',
        'purchased_at',
        'expires_at',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'purchased_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function match(): BelongsTo
    {
        return $this->belongsTo(GameMatch::class, 'match_id');
    }

    public function paidByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    /**
     * Active and not (yet) auto-expired. `expires_at` is only ever set when
     * the match finishes (LiveScoreController::finish) or the row is
     * otherwise closed out — while a match is still live/upcoming, an
     * active row has no expiry.
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE
            && ($this->expires_at === null || $this->expires_at->isFuture());
    }
}
