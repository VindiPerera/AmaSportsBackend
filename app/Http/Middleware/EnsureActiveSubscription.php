<?php

namespace App\Http\Middleware;

use App\Models\Player;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Gates every endpoint that writes/reads subscription-only player data:
 * submitting a sport profile (new *or* already-registered — product
 * decision: an expired subscription locks editing too, not just new
 * registrations) and the Analysis tab's aggregate endpoints.
 *
 * `error_code: 'subscription_required'` is a stable contract the mobile app
 * checks for (see apiClient.ts's response interceptor) to redirect straight
 * to the paywall/renewal screen instead of showing a generic error toast.
 */
class EnsureActiveSubscription
{
    public function handle(Request $request, Closure $next): Response
    {
        // Dev-only escape hatch — see config/subscription.php. Leaves the
        // real check below completely intact for when it's turned back off.
        if (config('subscription.bypass')) {
            return $next($request);
        }

        $player = Player::firstOrCreate(['user_id' => $request->user()->id]);

        if (! $player->hasActiveSubscription()) {
            return response()->json([
                'success' => false,
                'message' => 'An active AmaSports subscription is required to do this.',
                'error_code' => 'subscription_required',
            ], 402);
        }

        return $next($request);
    }
}
