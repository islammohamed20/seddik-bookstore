<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Update the user's location preference.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'location' => 'required|in:inside_assiut,outside_assiut',
        ]);

        session(['user_location' => $validated['location']]);

        // If user is logged in, we might want to update their profile city as well, 
        // but let's keep it simple for now (session-based for price display).
        
        return back()->with('status', 'location-updated');
    }
}
