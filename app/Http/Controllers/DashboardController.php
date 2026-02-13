<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user instanceof User && $user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }

        if (! ($user instanceof User)) {
            return redirect()->route('login');
        }

        $recentOrders = $user->orders()
            ->latest()
            ->take(5)
            ->get();

        $ordersCount = $user->orders()->count();

        return view('dashboard', compact('user', 'recentOrders', 'ordersCount'));
    }
}
