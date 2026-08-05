<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class OtpCode extends Model
{
    public const TYPE_EMAIL_VERIFICATION = 'email_verification';

    public const TYPE_PASSWORD_RESET = 'password_reset';

    /** Minutes an OTP code stays valid after being issued. */
    public const EXPIRY_MINUTES = 10;

    protected $fillable = [
        'email',
        'code',
        'type',
        'expires_at',
        'used_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'used_at' => 'datetime',
        ];
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isUsed(): bool
    {
        return $this->used_at !== null;
    }

    public function isValid(): bool
    {
        return ! $this->isExpired() && ! $this->isUsed();
    }

    public function markAsUsed(): void
    {
        $this->update(['used_at' => Carbon::now()]);
    }

    /** Generate a random zero-padded 6-digit code. */
    public static function generateCode(): string
    {
        return str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}
