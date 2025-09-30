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
            'DISPATCHED' => Cases::where('status', 'DISPATCHED')->count(),
            'ON_SCENE' => Cases::where('status', 'ON_SCENE')->count(),
            'CLOSED' => Cases::where('status', 'CLOSED')->count(),
        ];

        // Get 10 latest cases
        $recentCases = Cases::with(['reporterUser', 'assignedUnit'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.index', compact('statusCounts', 'recentCases'));
    }
}
