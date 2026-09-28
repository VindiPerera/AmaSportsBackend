<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Payments\StreamAccessPaymentController;
use App\Models\GameMatch;
use App\Models\LiveStreamAccess;
use App\Services\PayHereService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

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

    /** POST /admin/matches/{match}/stream/create-order — redirects straight to PayHere's hosted checkout. */
    public function createOrder(Request $request, GameMatch $match, PayHereService $payhere): RedirectResponse
    {
        if ($match->status === GameMatch::STATUS_FINISHED) {
            return redirect()->route('admin.matches.stream.show', $match)
                ->with('error', 'This match has finished — live streaming can no longer be enabled for it.');
        }

        if (! $payhere->isConfigured()) {
            return redirect()->route('admin.matches.stream.show', $match)
                ->with('error', 'Payments are not configured yet. Set PAYHERE_MERCHANT_ID/SECRET in .env.');
        }

        $access = LiveStreamAccess::create([
            'match_id' => $match->id,
            'paid_by' => $request->user()->id,
            'amount' => LiveStreamAccess::AMOUNT,
            'currency' => $payhere->currency(),
            'status' => LiveStreamAccess::STATUS_PENDING,
        ]);

        $orderId = $payhere->newOrderId(PayHereService::PREFIX_STREAM_ACCESS, $access->id);
        $access->update(['payment_order_id' => $orderId]);

        return redirect()->away($payhere->checkoutUrl(
            orderId: $orderId,
            items: config('app.name')." Live Stream Access - Match #{$match->id}",
            returnUrl: route('admin.matches.stream.return', $match),
            cancelUrl: route('admin.matches.stream.cancel', $match),
        ));
    }

    /**
     * GET /admin/matches/{match}/stream/return?order_id={payment_order_id}
     * — PayHere's notify_url (Api\PayHereNotifyController) is what actually
     * activates access; this only confirms early via the optional Retrieval
     * API, otherwise tells the admin to refresh in a moment.
     */
    public function return(Request $request, GameMatch $match, PayHereService $payhere): RedirectResponse
    {
        $access = LiveStreamAccess::where('match_id', $match->id)
            ->where('payment_order_id', (string) $request->query('order_id'))
            ->first();

        if (! $access) {
            return redirect()->route('admin.matches.stream.show', $match)
                ->with('error', "We couldn't find that order. If you were charged, please contact support.");
        }

        if ($access->status !== LiveStreamAccess::STATUS_ACTIVE && ($payment = $payhere->findReceivedPayment($access))) {
            StreamAccessPaymentController::activate($access, $payment['payment_id'] ?? null, $payment['payment_method']['method'] ?? null);
        }

        if ($access->status === LiveStreamAccess::STATUS_ACTIVE) {
            return redirect()->route('admin.matches.stream.show', $match)
                ->with('success', 'Payment received — live streaming is now enabled for this match.');
        }

        return redirect()->route('admin.matches.stream.show', $match)
            ->with('success', 'Payment submitted — PayHere is confirming it. Refresh this page in a few seconds.');
    }

    /** GET /admin/matches/{match}/stream/cancel?order_id={payment_order_id} */
    public function cancel(Request $request, GameMatch $match): RedirectResponse
    {
        LiveStreamAccess::where('match_id', $match->id)
            ->where('payment_order_id', (string) $request->query('order_id'))
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
