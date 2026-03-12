<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! Auth::check()) {
            return redirect()->route('admin.login');
        }

        // Check if user has admin role using Spatie Permission
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (! $user->hasRole('admin')) {
            return redirect()->route('admin.login')->with('error', 'يجب تسجيل الدخول كمسؤول للوصول لهذه الصفحة.');
        }

        return $next($request);
    }
}
