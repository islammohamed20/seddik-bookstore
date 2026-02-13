<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VisitorLog;

class VisitTrackingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'city' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'path' => 'nullable|string|max:500',
            'referrer' => 'nullable|string|max:500',
            'source' => 'nullable|string|max:50',
        ]);

        VisitorLog::create([
            'user_id' => $request->user()?->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'path' => $validated['path'] ?? $request->path(),
            'referrer' => $validated['referrer'] ?? $request->headers->get('referer'),
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
            'city' => $validated['city'] ?? null,
            'region' => $validated['region'] ?? null,
            'country' => $validated['country'] ?? null,
            'source' => $validated['source'] ?? 'browser',
        ]);

        return response()->json(['status' => 'ok']);
    }
}
