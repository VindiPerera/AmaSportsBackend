<?php

namespace App\Services;

use App\Models\OtpCode;
use App\Models\User;
use App\Notifications\OtpCodeNotification;
use Illuminate\Support\Carbon;

/**
 * Central place for issuing and validating one-time codes, shared by the
 * email-verification and password-reset flows.
 */
class OtpService
{
    public function issue(User $user, string $type): OtpCode
    {
        // Invalidate any previously issued, still-unused codes of this type
        // so only the most recent code can be redeemed.
        OtpCode::where('email', $user->email)
            ->where('type', $type)
            ->whereNull('used_at')
            ->update(['used_at' => Carbon::now()]);

        $otp = OtpCode::create([
            'email' => $user->email,
            'code' => OtpCode::generateCode(),
            'type' => $type,
            'expires_at' => Carbon::now()->addMinutes(OtpCode::EXPIRY_MINUTES),
        ]);

        $user->notify(new OtpCodeNotification($otp));

        return $otp;
    }

    public function verify(string $email, string $code, string $type): bool
    {
        $otp = OtpCode::where('email', $email)
            ->where('code', $code)
            ->where('type', $type)
            ->latest('id')
            ->first();

        if (! $otp || ! $otp->isValid()) {
            return false;
        }

        $otp->markAsUsed();

        return true;
    }
}
