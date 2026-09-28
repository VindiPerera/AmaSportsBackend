<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use App\Models\User;
use App\Services\PayHereService;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * GET /payments/payhere/checkout?order=…&signature=… — the link every
 * payment flow hands the payer (in-app WebView, in-app browser, or the
 * admin's own browser). Renders PayHere's checkout form pre-filled from
 * the DB row and auto-submits it to payhere.lk. See PayHereService for why
 * this page exists at all (PayHere checkout is a form POST, not a URL).
 */
class PayHereCheckoutController extends Controller
{
    public function show(Request $request, PayHereService $payhere): View
    {
        if (! $request->hasValidRelativeSignature()) {
            return view('payments.result', [
                'success' => false,
                'title' => 'Checkout link expired',
                'message' => 'This checkout link is invalid or has expired. Please close this window and start the payment again from the app.',
            ]);
        }

        $payable = $payhere->findPayable((string) $request->query('order'));

        if (! $payable) {
            return view('payments.result', [
                'success' => false,
                'title' => 'Payment not found',
                'message' => "We couldn't find that order. Please close this window and try again.",
            ]);
        }

        // Only a still-pending row may be paid — never let a stale link
        // re-open checkout for something already paid or cancelled.
        if ($payable->status !== $payable::STATUS_PENDING) {
            $paid = $payable->status === $payable::STATUS_ACTIVE;

            return view('payments.result', [
                'success' => $paid,
                'title' => $paid ? 'Already paid' : 'Checkout closed',
                'message' => $paid
                    ? 'This order has already been paid. You can close this window and return to the app.'
                    : 'This checkout was cancelled. Please close this window and start again from the app.',
            ]);
        }

        $payer = $payable instanceof Subscription
            ? $payable->player?->user
            : User::find($payable->paid_by);

        return view('payments.payhere-checkout', [
            'action' => $payhere->checkoutActionUrl(),
            'fields' => $payhere->checkoutFields(
                $payable,
                $payer,
                (string) $request->query('items'),
                (string) $request->query('return'),
                (string) $request->query('cancel'),
            ),
        ]);
    }
}
