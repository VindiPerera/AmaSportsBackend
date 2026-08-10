<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GameMatch;
use App\Models\LiveStreamAccess;
use App\Services\PayPalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Throwable;

/**
 * The $5-per-match "unlock live streaming" paywall (Phase 6 revision 2):
 * Live Score itself is always free, but the admin running a match has to
 * pay before the match's YouTube embed is exposed to players
 * (GameMatch::hasActiveStreamAccess() / GameMatchResource). Access
 * auto-closes when the match is marked finished — see
 * LiveScoreController::finish().
 */
class StreamAccessController extends Controller
{
    public function show(GameMatch $match): View
    {
        $match->load(['sport', 'homeTeam', 'awayTeam']);

        return view('admin.matches.stream', [
            'match' => $match,
            'access' => $match->latestLiveStreamAccess(),
        ]);
    }

    /** POST /admin/matches/{match}/stream/create-order — redirects straight to PayPal's hosted checkout. */
    public function createOrder(Request $request, GameMatch $match, PayPalService $paypal): RedirectResponse
    {
        if ($match->status === GameMatch::STATUS_FINISHED) {
            return redirect()->route('admin.matches.stream.show', $match)
                ->with('error', 'This match has finished — live streaming can no longer be enabled for it.');
        }

        if (! $paypal->isConfigured()) {
            return redirect()->route('admin.matches.stream.show', $match)
                ->with('error', 'Payments are not configured yet. Set PAYPAL_CLIENT_ID/SECRET in .env.');
        }

        $currency = config('services.paypal.currency');

        $access = LiveStreamAccess::create([
            'match_id' => $match->id,
            'paid_by' => $request->user()->id,
            'amount' => LiveStreamAccess::AMOUNT,
            'currency' => $currency,
            'status' => LiveStreamAccess::STATUS_PENDING,
        ]);

        try {
            $order = $paypal->createOrder(
                amount: LiveStreamAccess::AMOUNT,
                currency: $currency,
                customId: "stream_access:{$access->id}",
                description: config('app.name')." Live Stream Access — Match #{$match->id}",
                returnUrl: route('admin.matches.stream.return', $match),
                cancelUrl: route('admin.matches.stream.cancel', $match),
            );
        } catch (Throwable $e) {
            Log::error('Failed to create PayPal order for live-stream access.', [
                'match_id' => $match->id,
                'access_id' => $access->id,
                'message' => $e->getMessage(),
            ]);

            return redirect()->route('admin.matches.stream.show', $match)
                ->with('error', 'Could not start checkout with PayPal. Please try again.');
        }

        $access->update(['paypal_order_id' => $order['id']]);

        $approveUrl = $paypal->approveUrl($order);
        if (! $approveUrl) {
            return redirect()->route('admin.matches.stream.show', $match)
                ->with('error', 'PayPal did not return a checkout link. Please try again.');
        }

        return redirect()->away($approveUrl);
    }

    /** GET /admin/matches/{match}/stream/return?token={paypal_order_id}&PayerID=... */
    public function return(Request $request, GameMatch $match, PayPalService $paypal): RedirectResponse
    {
        $orderId = (string) $request->query('token');
        $access = LiveStreamAccess::where('match_id', $match->id)->where('paypal_order_id', $orderId)->first();

        if (! $access) {
            return redirect()->route('admin.matches.stream.show', $match)
                ->with('error', "We couldn't find that order. If you were charged, please contact support.");
        }

        if ($access->status === LiveStreamAccess::STATUS_ACTIVE) {
            return redirect()->route('admin.matches.stream.show', $match)
                ->with('success', 'Live streaming is enabled for this match.');
        }

        try {
            $order = $paypal->getOrder($orderId);

            if (($order['status'] ?? null) !== 'COMPLETED') {
                $order = $paypal->captureOrder($orderId);
            }

            if (($order['status'] ?? null) !== 'COMPLETED') {
                return redirect()->route('admin.matches.stream.show', $match)
                    ->with('error', 'PayPal has not confirmed this payment yet. Please check back in a moment.');
            }
        } catch (Throwable $e) {
            Log::error('Live-stream access capture failed on return.', [
                'match_id' => $match->id,
                'access_id' => $access->id,
                'order_id' => $orderId,
                'message' => $e->getMessage(),
            ]);

            return redirect()->route('admin.matches.stream.show', $match)
                ->with('error', 'We could not confirm your payment with PayPal. Please check back in a moment, or try again.');
        }

        $access->update([
            'status' => LiveStreamAccess::STATUS_ACTIVE,
            'purchased_at' => now(),
        ]);

        return redirect()->route('admin.matches.stream.show', $match)
            ->with('success', 'Payment received — live streaming is now enabled for this match.');
    }

    /** GET /admin/matches/{match}/stream/cancel?token={paypal_order_id} */
    public function cancel(Request $request, GameMatch $match): RedirectResponse
    {
        $orderId = (string) $request->query('token');

        LiveStreamAccess::where('match_id', $match->id)
            ->where('paypal_order_id', $orderId)
            ->where('status', LiveStreamAccess::STATUS_PENDING)
            ->update(['status' => LiveStreamAccess::STATUS_CANCELLED]);

        return redirect()->route('admin.matches.stream.show', $match)
            ->with('error', 'Checkout cancelled — no charge was made.');
    }

    /** PUT /admin/matches/{match}/stream/url — once unlocked, update the URL freely without repaying. */
    public function updateUrl(Request $request, GameMatch $match): RedirectResponse
    {
        if (! $match->hasActiveStreamAccess()) {
            return redirect()->route('admin.matches.stream.show', $match)
                ->with('error', 'Pay to enable live streaming for this match first.');
        }

        $validated = $request->validate([
            'youtube_stream_url' => ['nullable', 'url', 'max:500'],
        ]);

        $match->update(['youtube_stream_url' => $validated['youtube_stream_url'] ?? null]);

        return redirect()->route('admin.matches.stream.show', $match)
            ->with('success', 'Stream URL updated.');
    }
}
