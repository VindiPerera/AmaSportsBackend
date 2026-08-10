<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminLoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    public function login(AdminLoginRequest $request): RedirectResponse
    {
        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'These credentials do not match our records.'])->onlyInput('email');
        }

        if (! Auth::user()->isAdmin()) {
            Auth::logout();

            return back()->withErrors(['email' => 'This account does not have admin access.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
