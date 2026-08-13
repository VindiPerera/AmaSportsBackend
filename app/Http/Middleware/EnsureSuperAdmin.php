<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Guards the /admin/super routes — a stricter tier above EnsureAdmin.
 * Redirects guests to the admin login page (same as EnsureAdmin, so a
 * logged-out visitor clicking "Super Admin" in the nav lands on the normal
 * login form) and 403s an authenticated user who isn't a super_admin,
 * including regular admins — match-creation access doesn't imply this.
 */
class EnsureSuperAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('admin.login');
        }

        if (! Auth::user()->isSuperAdmin()) {
            abort(403, 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
