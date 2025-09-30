<?php

namespace App\Http\Controllers;

use App\Models\Cases;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Count cases by status
        $statusCounts = [
            'NEW' => Cases::where('status', 'NEW')->count(),
            'VERIFIED' => Cases::where('status', 'VERIFIED')->count(),
            'DISPATCHED' => Cases::where('status', 'DISPATCHED')->count(),
            'ON_THE_WAY' => Cases::where('status', 'ON_THE_WAY')->count(),
            'ON_SCENE' => Cases::where('status', 'ON_SCENE')->count(),
            'CLOSED' => Cases::where('status', 'CLOSED')->count(),
            'CANCELLED' => Cases::where('status', 'CANCELLED')->count(),
        ];

        // Get active cases for map (not CLOSED or CANCELLED)
        $activeCases = Cases::whereNotIn('status', ['CLOSED', 'CANCELLED'])
            ->whereNotNull('lat')
            ->whereNotNull('lon')
            ->select('id', 'short_id', 'category', 'status', 'location', 'lat', 'lon', 'created_at')
            ->get();

        // Get 5 latest cases
        $recentCases = Cases::with(['reporterUser', 'assignedUnit'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Total cases count
        $totalCases = Cases::count();

        return view('dashboard.index', compact('statusCounts', 'recentCases', 'activeCases', 'totalCases'));
    }
}
