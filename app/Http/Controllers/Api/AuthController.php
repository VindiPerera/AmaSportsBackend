<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResendOtpRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\VerifyOtpRequest;
use App\Http\Resources\UserResource;
use App\Models\OtpCode;
use App\Models\User;
use App\Services\OtpService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(private readonly OtpService $otpService) {}

    /**
     * Register a new account and immediately send an email-verification code.
     * A token is issued right away so the app can show the OTP screen in an
     * authenticated context, but protected routes should still be treated
     * as locked until `email_verified_at` is set (see /auth/verify-otp).
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        $this->otpService->issue($user, OtpCode::TYPE_EMAIL_VERIFICATION);

        $token = $user->createToken('mobile')->plainTextToken;

        return $this->success([
            'token' => $token,
            'user' => new UserResource($user),
        ], 'Registration successful. Please verify your email.', 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return $this->error('The provided credentials are incorrect.', 422, [
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (! $user->email_verified_at) {
            $this->otpService->issue($user, OtpCode::TYPE_EMAIL_VERIFICATION);

            return $this->error(
                'Please verify your email before logging in. A new code has been sent.',
                403
            );
        }

        $token = $user->createToken('mobile')->plainTextToken;

        return $this->success([
            'token' => $token,
            'user' => new UserResource($user),
        ], 'Login successful.');
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return $this->success(null, 'Logged out successfully.');
    }

    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->firstOrFail();

        $this->otpService->issue($user, OtpCode::TYPE_PASSWORD_RESET);

        return $this->success(null, 'A password reset code has been sent to your email.');
    }

    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        $isValid = $this->otpService->verify(
            $request->email,
            $request->token,
            OtpCode::TYPE_PASSWORD_RESET
        );

        if (! $isValid) {
            return $this->error('This reset code is invalid or has expired.', 422, [
                'token' => ['This reset code is invalid or has expired.'],
            ]);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $user->update(['password' => Hash::make($request->password)]);

        // Revoke all existing sessions so old tokens can't be used after a reset.
        $user->tokens()->delete();

        return $this->success(null, 'Your password has been reset. Please log in.');
    }

    public function verifyOtp(VerifyOtpRequest $request): JsonResponse
    {
        $isValid = $this->otpService->verify(
            $request->email,
            $request->otp,
            OtpCode::TYPE_EMAIL_VERIFICATION
        );

        if (! $isValid) {
            return $this->error('This verification code is invalid or has expired.', 422, [
                'otp' => ['This verification code is invalid or has expired.'],
            ]);
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $user->markEmailAsVerified();

        $token = $user->createToken('mobile')->plainTextToken;

        return $this->success([
            'token' => $token,
            'user' => new UserResource($user->fresh()),
        ], 'Email verified successfully.');
    }

    public function resendOtp(ResendOtpRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->firstOrFail();

        if ($user->email_verified_at) {
            return $this->error('This account is already verified.', 422);
        }

        $this->otpService->issue($user, OtpCode::TYPE_EMAIL_VERIFICATION);

        return $this->success(null, 'A new verification code has been sent to your email.');
    }
}
