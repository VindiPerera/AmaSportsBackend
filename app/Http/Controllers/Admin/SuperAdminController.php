<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LiveStreamAccess;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Administrative Controller for managing Users, Payments, and Purchases.
 */
class SuperAdminController extends Controller
{
    public function index(): View
    {
        $subscriptions = Subscription::with('player.user')
            ->latest('id')
            ->limit(100)
            ->get();

        $streamPayments = LiveStreamAccess::with(['match.homeTeam', 'match.awayTeam', 'paidByUser'])
            ->latest('id')
            ->limit(100)
            ->get();

        $users = User::whereIn('role', [User::ROLE_COACH, User::ROLE_STUDENT])
            ->latest('id')
            ->get();

        $clients = User::where('role', User::ROLE_ADMIN)
            ->latest('id')
            ->get();

        return view('admin.super.index', [
            'subscriptions' => $subscriptions,
            'streamPayments' => $streamPayments,
            'users' => $users,
            'clients' => $clients,
        ]);
    }

    public function users(Request $request): View
    {
        $query = User::query()->with(['player.subscriptions']);

        if ($search = trim((string) $request->query('q'))) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($role = $request->query('role')) {
            $query->where('role', $role);
        }

        $users = $query->latest('id')->paginate(20)->withQueryString();

        return view('admin.super.users', compact('users', 'search', 'role'));
    }

    public function payments(Request $request): View
    {
        $type = $request->query('type', 'all');

        $subscriptions = collect();
        $streamPayments = collect();

        if ($type === 'all' || $type === 'subscriptions') {
            $subscriptions = Subscription::with(['player.user'])
                ->latest('id')
                ->get();
        }

        if ($type === 'all' || $type === 'stream') {
            $streamPayments = LiveStreamAccess::with(['match.homeTeam', 'match.awayTeam', 'paidByUser'])
                ->latest('id')
                ->get();
        }

        return view('admin.super.payments', compact('subscriptions', 'streamPayments', 'type'));
    }

    public function purchases(Request $request): View
    {
        // Aliased view or detailed purchases list
        return $this->payments($request);
    }
}
