<?php

namespace App\Http\Controllers;

use App\Models\CaseDispatch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PimpinanController extends Controller
{
    /**
     * Display the pimpinan dashboard with pending dispatches
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Check if user is pimpinan
        if ($user->role !== 'PIMPINAN') {
            abort(403, 'Unauthorized: Only PIMPINAN can access this page');
        }

        // Get the unit where this user is pimpinan
        $unit = $user->unitAsPimpinan;

        if (!$unit) {
            abort(403, 'Unauthorized: You are not assigned as pimpinan of any unit');
        }

        // Get dispatches for this unit that haven't been assigned to petugas yet
        $pendingDispatches = CaseDispatch::where('unit_id', $unit->id)
            ->whereNull('assigned_petugas_id')
            ->with(['case', 'dispatcher'])
            ->orderBy('dispatched_at', 'desc')
            ->get();

        // Get assigned dispatches (already has petugas)
        $assignedDispatches = CaseDispatch::where('unit_id', $unit->id)
            ->whereNotNull('assigned_petugas_id')
            ->with(['case', 'assignedPetugas', 'dispatcher'])
            ->orderBy('assigned_at', 'desc')
            ->limit(20)
            ->get();

        // Get all petugas in this unit
        $availablePetugas = User::where('unit_id', $unit->id)
            ->where('role', 'PETUGAS')
            ->orderBy('name')
            ->get();

        return view('pimpinan.dashboard', compact(
            'unit',
            'pendingDispatches',
            'assignedDispatches',
            'availablePetugas'
        ));
    }

    /**
     * Assign a petugas to a dispatch
     */
    public function assignPetugas(Request $request, CaseDispatch $dispatch)
    {
        $user = Auth::user();

        // Check if user is pimpinan
        if ($user->role !== 'PIMPINAN') {
            return back()->with('error', 'Unauthorized: Only PIMPINAN can assign petugas');
        }

        // Get the unit where this user is pimpinan
        $unit = $user->unitAsPimpinan;

        if (!$unit || $dispatch->unit_id !== $unit->id) {
            return back()->with('error', 'Unauthorized: This dispatch is not for your unit');
        }

        // Validate request
        $validated = $request->validate([
            'assigned_petugas_id' => 'required|exists:users,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Verify the petugas belongs to this unit
        $petugas = User::findOrFail($validated['assigned_petugas_id']);
        if ($petugas->unit_id !== $unit->id || $petugas->role !== 'PETUGAS') {
            return back()->with('error', 'Invalid petugas selection');
        }

        // Update the dispatch
        $dispatch->update([
            'assigned_petugas_id' => $validated['assigned_petugas_id'],
            'notes' => $validated['notes'] ?? $dispatch->notes,
            'assigned_at' => now(),
        ]);

        // Update the case status to DISPATCHED
        $case = $dispatch->case;
        if ($case->status === 'NEW') {
            $case->update([
                'status' => 'DISPATCHED',
                'dispatched_at' => now(),
            ]);
        }

        return back()->with('success', 'Petugas assigned successfully');
    }

    /**
     * Show case detail for pimpinan
     */
    public function showCase($caseId)
    {
        $user = Auth::user();

        // Check if user is pimpinan
        if ($user->role !== 'PIMPINAN') {
            abort(403, 'Unauthorized: Only PIMPINAN can access this page');
        }

        // Get the unit where this user is pimpinan
        $unit = $user->unitAsPimpinan;

        if (!$unit) {
            abort(403, 'Unauthorized: You are not assigned as pimpinan of any unit');
        }

        // Find the case and verify it's dispatched to this unit
        $dispatch = CaseDispatch::where('case_id', $caseId)
            ->where('unit_id', $unit->id)
            ->with(['case.caseEvents', 'case.reporterUser', 'assignedPetugas', 'dispatcher'])
            ->firstOrFail();

        $case = $dispatch->case;

        // Get all petugas in this unit
        $availablePetugas = User::where('unit_id', $unit->id)
            ->where('role', 'PETUGAS')
            ->orderBy('name')
            ->get();

        return view('pimpinan.case-detail', compact(
            'case',
            'dispatch',
            'unit',
            'availablePetugas'
        ));
    }

    /**
     * Show petugas management page
     */
    public function managePetugas()
    {
        $user = Auth::user();

        // Check if user is pimpinan
        if ($user->role !== 'PIMPINAN') {
            abort(403, 'Unauthorized: Only PIMPINAN can access this page');
        }

        // Get the unit where this user is pimpinan
        $unit = $user->unitAsPimpinan;

        if (!$unit) {
            abort(403, 'Unauthorized: You are not assigned as pimpinan of any unit');
        }

        // Get all petugas in this unit
        $petugas = User::where('unit_id', $unit->id)
            ->where('role', 'PETUGAS')
            ->orderBy('name')
            ->get();

        return view('pimpinan.petugas', compact('unit', 'petugas'));
    }

    /**
     * Store a new petugas
     */
    public function storePetugas(Request $request)
    {
        $user = Auth::user();

        // Check if user is pimpinan
        if ($user->role !== 'PIMPINAN') {
            return back()->with('error', 'Unauthorized: Only PIMPINAN can add petugas');
        }

        // Get the unit where this user is pimpinan
        $unit = $user->unitAsPimpinan;

        if (!$unit) {
            return back()->with('error', 'Unauthorized: You are not assigned as pimpinan of any unit');
        }

        // Validate request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create new petugas
        $petugas = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => bcrypt($validated['password']),
            'role' => 'PETUGAS',
            'unit_id' => $unit->id,
        ]);

        return back()->with('success', 'Petugas berhasil ditambahkan');
    }
}
