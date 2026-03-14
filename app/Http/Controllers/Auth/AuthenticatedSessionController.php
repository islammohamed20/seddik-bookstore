<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\AdminNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View|RedirectResponse
    {
        // If trying to access admin login and already admin, redirect to dashboard
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if ((request()->routeIs('admin.login') || request()->is('admin/login')) && Auth::check() && $user && $user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if (request()->routeIs('admin.login') || request()->is('admin/login')) {
            return view('auth.admin-login');
        }

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // منع العملاء من استخدام /admin/login
        if (request()->is('admin/login')) {
            $user = \App\Models\User::where('email', $request->email)->first();
            if ($user && ($user->hasRole('customer') || ! $user->isAdmin())) {
                return redirect()->route('login')
                    ->with('error', 'هذه الصفحة مخصصة للإدمن فقط');
            }
        }

        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();
        if ($user && !$user->isAdmin()) {
            $dbCartItems = $user->cartItems()->get();
            $sessionCart = $request->session()->get('cart', []);
            
            foreach ($dbCartItems as $item) {
                $key = $item->variant_id ? $item->product_id . '_v' . $item->variant_id : (string) $item->product_id;
                if (!isset($sessionCart[$key])) {
                    $sessionCart[$key] = [
                        'product_id' => $item->product_id,
                        'variant_id' => $item->variant_id,
                        'quantity' => $item->quantity,
                    ];
                }
            }
            $request->session()->put('cart', $sessionCart);
        }

        // إنشاء إشعار لتسجيل الدخول
        AdminNotification::createLoginNotification(Auth::user());

        // توجيه حسب الدور والمسار
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if ($user?->isAdmin()) {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        }

        return redirect()->intended(route('home', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Check if the logout request came from the admin panel
        $isAdminLogout = str_contains(url()->previous(), '/admin');

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        if ($isAdminLogout) {
            return redirect()->route('admin.login');
        }

        return redirect('/');
    }
}
