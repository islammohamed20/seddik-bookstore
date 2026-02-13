<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserLocation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. If user is authenticated, sync session with their profile city
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->city) {
                $isInside = str_contains(strtolower($user->city), 'assiut') || 
                           str_contains($user->city, 'أسيوط');
                
                session(['user_location' => $isInside ? 'inside_assiut' : 'outside_assiut']);
            }
        }

        // 2. If session is not set, set default (or logic to detect IP could go here)
        if (!session()->has('user_location')) {
            // Default to 'inside_assiut' for now. 
            // In a real scenario, you might redirect to a location picker page:
            // return redirect()->route('location.select');
            
            session(['user_location' => 'inside_assiut']);
        }

        return $next($request);
    }
}
