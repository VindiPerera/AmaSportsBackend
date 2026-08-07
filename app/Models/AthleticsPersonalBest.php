<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AthleticsPersonalBest extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = ['athletics_profile_id', 'athletics_event_id', 'personal_best'];

    public function athleticsProfile(): BelongsTo
    {
        return $this->belongsTo(AthleticsProfile::class);
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(AthleticsEvent::class, 'athletics_event_id');
    }
}
