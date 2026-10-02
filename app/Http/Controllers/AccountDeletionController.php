<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AccountDeletionService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

/**
 * Public /delete-account page — Google Play requires a web link where
 * users can delete their account without reinstalling the app. This URL
 * is what goes into Play Console → App content → Data safety → "Delete
 * account URL". Same deletion as the in-app action (DELETE /api/user).
 */
class AccountDeletionController extends Controller
{
    public function show(): View
    {
        return view('legal.delete-account');
    }

    public function destroy(Request $request, AccountDeletionService $deletion): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'confirm' => ['accepted'],
        ], [
            'confirm.accepted' => 'Please tick the box to confirm you want to permanently delete your account.',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'The email or password is incorrect.']);
        }

        $deletion->delete($user);

        return redirect()->route('delete-account')->with('deleted', true);
    }
}
