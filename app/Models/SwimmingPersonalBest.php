<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SwimmingPersonalBest extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = ['swimming_profile_id', 'swimming_event_id', 'personal_best'];

    public function swimmingProfile(): BelongsTo
    {
        return $this->belongsTo(SwimmingProfile::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(SwimmingEvent::class, 'swimming_event_id');
    }
}
