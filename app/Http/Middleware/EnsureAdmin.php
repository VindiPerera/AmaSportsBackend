<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Guards every /admin route except the login form itself. Redirects guests
 * to the admin login page and 403s an authenticated non-admin — a
 * Blade-friendly counterpart to EnsureUserHasRole, which is API/JSON-only
 * and shouldn't be reused for HTML pages.
 */
class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('admin.login');
        }

        if (! Auth::user()->isAdmin()) {
            Auth::logout();
            $request->session()->invalidate();

            abort(403, 'You do not have permission to access the admin panel.');
        }

        return $next($request);
    }
}
