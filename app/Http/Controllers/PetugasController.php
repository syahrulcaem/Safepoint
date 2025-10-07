<?php

namespace App\Http\Controllers;

use App\Models\CaseDispatch;
use App\Models\Cases;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PetugasController extends Controller
{
    /**
     * Display the petugas dashboard with assigned cases
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Check if user is petugas
        if ($user->role !== 'PETUGAS') {
            abort(403, 'Unauthorized: Only PETUGAS can access this page');
        }

        // Get cases assigned to this petugas
        $assignedCases = CaseDispatch::where('assigned_petugas_id', $user->id)
            ->with(['case.caseEvents', 'unit', 'dispatcher'])
            ->orderBy('assigned_at', 'desc')
            ->get();

        // Separate by status
        $activeCases = $assignedCases->filter(function ($dispatch) {
            return in_array($dispatch->case->status, ['DISPATCHED', 'ON_THE_WAY', 'ON_SCENE']);
        });

        $completedCases = $assignedCases->filter(function ($dispatch) {
            return in_array($dispatch->case->status, ['CLOSED', 'CANCELLED']);
        });

        return view('petugas.dashboard', compact(
            'assignedCases',
            'activeCases',
            'completedCases'
        ));
    }

    /**
     * Show case detail for petugas
     */
    public function showCase($caseId)
    {
        $user = Auth::user();

        // Check if user is petugas
        if ($user->role !== 'PETUGAS') {
            abort(403, 'Unauthorized: Only PETUGAS can access this page');
        }

        // Find the case and verify it's assigned to this petugas
        $dispatch = CaseDispatch::where('case_id', $caseId)
            ->where('assigned_petugas_id', $user->id)
            ->with(['case.caseEvents', 'case.reporterUser', 'unit', 'dispatcher'])
            ->firstOrFail();

        $case = $dispatch->case;

        // Get petugas location from database as fallback
        $petugasLocation = [
            'lat' => $user->last_latitude,
            'lon' => $user->last_longitude,
            'updated_at' => $user->last_location_update
        ];

        return view('petugas.case-detail', compact('case', 'dispatch', 'petugasLocation'));
    }

    /**
     * Update case status (e.g., ON_THE_WAY, ON_SCENE)
     */
    public function updateStatus(Request $request, Cases $case)
    {
        $user = Auth::user();

        // Check if user is petugas
        if ($user->role !== 'PETUGAS') {
            return back()->with('error', 'Unauthorized: Only PETUGAS can update status');
        }

        // Verify case is assigned to this petugas
        $dispatch = CaseDispatch::where('case_id', $case->id)
            ->where('assigned_petugas_id', $user->id)
            ->firstOrFail();

        // Validate request
        $validated = $request->validate([
            'status' => 'required|in:ON_THE_WAY,ON_SCENE,CLOSED',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Update case status
        $case->update([
            'status' => $validated['status'],
        ]);

        // Add case event
        $case->caseEvents()->create([
            'user_id' => $user->id,
            'action' => 'STATUS_CHANGED',
            'metadata' => ['status' => $validated['status']],
            'notes' => $validated['notes'] ?? "Status diubah menjadi {$validated['status']}",
        ]);

        return back()->with('success', 'Status berhasil diperbarui');
    }
}
