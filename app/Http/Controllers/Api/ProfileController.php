<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\UpdateProfileRequest;
use App\Http\Resources\UserResource;
use App\Services\AccountDeletionService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    use ApiResponse;

    /** GET /user — current authenticated user's profile. */
    public function show(Request $request): JsonResponse
    {
        return $this->success(new UserResource($request->user()), 'Profile retrieved successfully.');
    }

    /** PATCH /user — update name/email/avatar for the current user. */
    public function update(UpdateProfileRequest $request): JsonResponse
    {
        $user = $request->user();
        $user->update($request->validated());

        return $this->success(new UserResource($user->fresh()), 'Profile updated successfully.');
    }

    /** PUT /user/password — change password while authenticated. */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        $user = $request->user();

        if (! Hash::check($request->current_password, $user->password)) {
            return $this->error('The current password is incorrect.', 422, [
                'current_password' => ['The current password is incorrect.'],
            ]);
        }

        $user->update(['password' => Hash::make($request->password)]);

        // Keep the session that just made this change; revoke every other token.
        $user->tokens()
            ->where('id', '!=', $request->user()->currentAccessToken()->id)
            ->delete();

        return $this->success(null, 'Password changed successfully.');
    }

    /**
     * DELETE /user — permanently delete the current account and all of its
     * player data (Google Play account-deletion requirement). Asks for the
     * password again so an unlocked phone can't wipe the account by accident.
     */
    public function destroy(Request $request, AccountDeletionService $deletion): JsonResponse
    {
        $request->validate(['password' => ['required', 'string']]);

        $user = $request->user();

        if (! Hash::check($request->password, $user->password)) {
            return $this->error('The password is incorrect.', 422, [
                'password' => ['The password is incorrect.'],
            ]);
        }

        $deletion->delete($user);

        return $this->success(null, 'Your account has been deleted.');
    }
}
