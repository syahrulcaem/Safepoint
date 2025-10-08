<?php

namespace App\Http\Controllers;

use App\Models\Cases;
use App\Models\CaseEvent;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SlaReportController extends Controller
{
    /**
     * Display SLA Report and Analytics
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Date range filter (default: last 30 days)
        $startDate = $request->input('start_date', now()->subDays(30)->startOfDay());
        $endDate = $request->input('end_date', now()->endOfDay());
        
        // Convert to Carbon instances if they're strings
        $startDate = Carbon::parse($startDate);
        $endDate = Carbon::parse($endDate);

        // Base query with date filter
        $casesQuery = Cases::whereBetween('created_at', [$startDate, $endDate]);

        // Role-based filtering
        if ($user->role === 'PIMPINAN') {
            $unit = $user->unitAsPimpinan ?? $user->unit;
            if ($unit) {
                $casesQuery->where('assigned_unit_id', $unit->id);
            }
        } elseif ($user->role === 'PETUGAS') {
            $casesQuery->where('assigned_petugas_id', $user->id);
        }

        // Statistics
        $stats = [
            'total_cases' => (clone $casesQuery)->count(),
            'completed_cases' => (clone $casesQuery)->where('status', 'CLOSED')->count(),
            'cancelled_cases' => (clone $casesQuery)->where('status', 'CANCELLED')->count(),
            'active_cases' => (clone $casesQuery)->whereIn('status', ['NEW', 'VERIFIED', 'DISPATCHED', 'ON_THE_WAY', 'ON_SCENE'])->count(),
        ];

        // Average response time (time from NEW to DISPATCHED)
        $avgResponseTime = $this->calculateAverageResponseTime($startDate, $endDate, $user);

        // Average resolution time (time from NEW to CLOSED)
        $avgResolutionTime = $this->calculateAverageResolutionTime($startDate, $endDate, $user);

        // Cases by category
        $casesByCategory = (clone $casesQuery)
            ->select('category', DB::raw('count(*) as total'))
            ->groupBy('category')
            ->get();

        // Cases by status
        $casesByStatus = (clone $casesQuery)
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // Cases by priority
        $casesByPriority = (clone $casesQuery)
            ->select('priority', DB::raw('count(*) as total'))
            ->groupBy('priority')
            ->get();

        // Daily cases trend (last 30 days)
        $dailyCases = Cases::whereBetween('created_at', [$startDate, $endDate])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Heatmap data - Cases by hour and day of week
        $heatmapData = $this->getHeatmapData($startDate, $endDate, $user);

        // Top performing units (for OPERATOR and SUPERADMIN)
        $topUnits = [];
        if (in_array($user->role, ['OPERATOR', 'SUPERADMIN'])) {
            $topUnits = Unit::withCount([
                'assignedCases' => function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate])
                          ->where('status', 'CLOSED');
                }
            ])
            ->having('assigned_cases_count', '>', 0)
            ->orderByDesc('assigned_cases_count')
            ->limit(5)
            ->get();
        }

        // SLA Compliance (cases closed within target time)
        $slaCompliance = $this->calculateSLACompliance($startDate, $endDate, $user);

        // Recent cases
        $recentCases = (clone $casesQuery)
            ->with(['assignedUnit', 'assignedPetugas', 'reporterUser'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Cases with locations for map heatmap
        $casesWithLocation = (clone $casesQuery)
            ->whereNotNull('lat')
            ->whereNotNull('lon')
            ->with(['assignedUnit'])
            ->get();

        return view('reports.sla-report', compact(
            'stats',
            'avgResponseTime',
            'avgResolutionTime',
            'casesByCategory',
            'casesByStatus',
            'casesByPriority',
            'dailyCases',
            'heatmapData',
            'topUnits',
            'slaCompliance',
            'recentCases',
            'casesWithLocation',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Calculate average response time (NEW to DISPATCHED)
     */
    private function calculateAverageResponseTime($startDate, $endDate, $user)
    {
        $query = Cases::whereBetween('created_at', [$startDate, $endDate])
            ->whereNotNull('dispatched_at');

        if ($user->role === 'PIMPINAN') {
            $unit = $user->unitAsPimpinan ?? $user->unit;
            if ($unit) {
                $query->where('assigned_unit_id', $unit->id);
            }
        } elseif ($user->role === 'PETUGAS') {
            $query->where('assigned_petugas_id', $user->id);
        }

        $cases = $query->get();

        if ($cases->isEmpty()) {
            return 0;
        }

        $totalMinutes = 0;
        foreach ($cases as $case) {
            $responseTime = $case->created_at->diffInMinutes($case->dispatched_at);
            $totalMinutes += $responseTime;
        }

        return round($totalMinutes / $cases->count(), 2);
    }

    /**
     * Calculate average resolution time (NEW to CLOSED)
     */
    private function calculateAverageResolutionTime($startDate, $endDate, $user)
    {
        $query = Cases::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'CLOSED')
            ->whereNotNull('closed_at');

        if ($user->role === 'PIMPINAN') {
            $unit = $user->unitAsPimpinan ?? $user->unit;
            if ($unit) {
                $query->where('assigned_unit_id', $unit->id);
            }
        } elseif ($user->role === 'PETUGAS') {
            $query->where('assigned_petugas_id', $user->id);
        }

        $cases = $query->get();

        if ($cases->isEmpty()) {
            return 0;
        }

        $totalMinutes = 0;
        foreach ($cases as $case) {
            $resolutionTime = $case->created_at->diffInMinutes($case->closed_at);
            $totalMinutes += $resolutionTime;
        }

        return round($totalMinutes / $cases->count(), 2);
    }

    /**
     * Get heatmap data - Cases by hour and day of week
     */
    private function getHeatmapData($startDate, $endDate, $user)
    {
        $query = Cases::whereBetween('created_at', [$startDate, $endDate]);

        if ($user->role === 'PIMPINAN') {
            $unit = $user->unitAsPimpinan ?? $user->unit;
            if ($unit) {
                $query->where('assigned_unit_id', $unit->id);
            }
        } elseif ($user->role === 'PETUGAS') {
            $query->where('assigned_petugas_id', $user->id);
        }

        $cases = $query->get();

        // Initialize heatmap array
        $heatmap = [];
        $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        
        foreach (range(0, 6) as $day) {
            for ($hour = 0; $hour < 24; $hour++) {
                $heatmap[$day][$hour] = 0;
            }
        }

        // Count cases by day and hour
        foreach ($cases as $case) {
            $dayOfWeek = $case->created_at->dayOfWeek;
            $hour = $case->created_at->hour;
            $heatmap[$dayOfWeek][$hour]++;
        }

        return [
            'data' => $heatmap,
            'days' => $days
        ];
    }

    /**
     * Calculate SLA Compliance
     * Target: Response within 15 minutes, Resolution within 120 minutes
     */
    private function calculateSLACompliance($startDate, $endDate, $user)
    {
        $query = Cases::whereBetween('created_at', [$startDate, $endDate]);

        if ($user->role === 'PIMPINAN') {
            $unit = $user->unitAsPimpinan ?? $user->unit;
            if ($unit) {
                $query->where('assigned_unit_id', $unit->id);
            }
        } elseif ($user->role === 'PETUGAS') {
            $query->where('assigned_petugas_id', $user->id);
        }

        $totalCases = (clone $query)->count();
        
        if ($totalCases === 0) {
            return [
                'response_compliance' => 0,
                'resolution_compliance' => 0,
                'overall_compliance' => 0
            ];
        }

        // Response SLA (15 minutes)
        $responseSLAMet = 0;
        $casesWithDispatch = (clone $query)->whereNotNull('dispatched_at')->get();
        foreach ($casesWithDispatch as $case) {
            $responseTime = $case->created_at->diffInMinutes($case->dispatched_at);
            if ($responseTime <= 15) {
                $responseSLAMet++;
            }
        }

        // Resolution SLA (120 minutes)
        $resolutionSLAMet = 0;
        $closedCases = (clone $query)->where('status', 'CLOSED')->whereNotNull('closed_at')->get();
        foreach ($closedCases as $case) {
            $resolutionTime = $case->created_at->diffInMinutes($case->closed_at);
            if ($resolutionTime <= 120) {
                $resolutionSLAMet++;
            }
        }

        $responseCompliance = $casesWithDispatch->count() > 0 
            ? round(($responseSLAMet / $casesWithDispatch->count()) * 100, 2) 
            : 0;
        
        $resolutionCompliance = $closedCases->count() > 0 
            ? round(($resolutionSLAMet / $closedCases->count()) * 100, 2) 
            : 0;

        return [
            'response_compliance' => $responseCompliance,
            'resolution_compliance' => $resolutionCompliance,
            'overall_compliance' => round(($responseCompliance + $resolutionCompliance) / 2, 2),
            'response_sla_met' => $responseSLAMet,
            'response_total' => $casesWithDispatch->count(),
            'resolution_sla_met' => $resolutionSLAMet,
            'resolution_total' => $closedCases->count()
        ];
    }
}
