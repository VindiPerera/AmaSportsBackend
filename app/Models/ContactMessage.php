<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactMessage extends Model
{
    /**
     * @var list<string>
     */
    protected $fillable = ['user_id', 'name', 'email', 'message'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
