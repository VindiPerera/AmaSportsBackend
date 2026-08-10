<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BoxingRecentFight extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = [
        'boxing_profile_id', 'fight_date', 'opponent', 'venue', 'weight_class_id',
        'win', 'lost', 'place',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return ['fight_date' => 'date', 'win' => 'boolean', 'lost' => 'boolean'];
    }

    public function boxingProfile(): BelongsTo
    {
        return $this->belongsTo(BoxingProfile::class);
    }

    public function weightClass(): BelongsTo
    {
        return $this->belongsTo(BoxingWeightClass::class, 'weight_class_id');
    }
}
