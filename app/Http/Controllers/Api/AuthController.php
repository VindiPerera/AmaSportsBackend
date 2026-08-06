<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Resources\UserResource;
use App\Models\OtpCode;
use App\Models\User;
use App\Services\OtpService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponse;

    public function __construct(private readonly OtpService $otpService) {}

    /**
     * Register a new account and log the user straight in — there is no
     * email verification step.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        // There is only one signup type — Player. The `role` column stays
        // in the schema for future coach/admin features, but the client
        // never chooses it.
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => User::ROLE_STUDENT,
        ]);

        $token = $user->createToken('mobile')->plainTextToken;

        return $this->success([
            'token' => $token,
            'user' => new UserResource($user),
        ], 'Registration successful.', 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return $this->error('The provided credentials are incorrect.', 422, [
                'email' => ['The provided credentials are incorrect.'],
            ]);
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
}
