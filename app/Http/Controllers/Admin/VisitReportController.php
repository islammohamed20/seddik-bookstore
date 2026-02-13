<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VisitorLog;
use Illuminate\Http\Request;

class VisitReportController extends Controller
{
    public function index(Request $request)
    {
        $query = VisitorLog::query()->recent();

        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        if ($request->filled('city')) {
            $query->where('city', 'like', "%{$request->city}%");
        }

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }

        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        $visits = $query->paginate(20)->withQueryString();

        $countries = VisitorLog::query()
            ->whereNotNull('country')
            ->select('country')
            ->distinct()
            ->orderBy('country')
            ->pluck('country');

        $stats = [
            'total' => VisitorLog::count(),
            'with_location' => VisitorLog::whereNotNull('latitude')->count(),
            'today' => VisitorLog::whereDate('created_at', now()->toDateString())->count(),
        ];

        return view('admin.visit-reports.index', compact('visits', 'countries', 'stats'));
    }
}
