<?php

namespace App\Http\Controllers;

use App\Models\CaseDispatch;
use App\Models\CaseEvent;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        // Get the unit - try unitAsPimpinan first, fallback to user's unit
        $unit = $user->unitAsPimpinan ?? $user->unit;

        if (!$unit) {
            abort(403, 'Unauthorized: You are not assigned to any unit');
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
        try {
            $user = Auth::user();

            // Check if user is pimpinan
            if ($user->role !== 'PIMPINAN') {
                Log::warning('Unauthorized assign attempt', ['user_id' => $user->id, 'role' => $user->role]);
                return back()->with('error', 'Unauthorized: Only PIMPINAN can assign petugas');
            }

            // Get the unit - try unitAsPimpinan first, fallback to user's unit
            $unit = $user->unitAsPimpinan ?? $user->unit;

            // Enhanced logging for debugging
            Log::info('Assign Petugas Debug', [
                'user_id' => $user->id,
                'user_role' => $user->role,
                'user_unit_id' => $user->unit_id,
                'unitAsPimpinan_exists' => $user->unitAsPimpinan !== null,
                'unitAsPimpinan_id' => $user->unitAsPimpinan?->id,
                'fallback_to_user_unit' => $user->unitAsPimpinan === null && $user->unit !== null,
                'final_unit_id' => $unit?->id,
                'dispatch_id' => $dispatch->id,
                'dispatch_unit_id' => $dispatch->unit_id,
                'dispatch_unit_id_type' => gettype($dispatch->unit_id),
                'final_unit_id_type' => gettype($unit?->id),
                'strict_comparison' => $dispatch->unit_id !== $unit->id,
                'loose_comparison' => $dispatch->unit_id != $unit->id,
            ]);

            if (!$unit) {
                return back()->with('error', 'Anda tidak terdaftar di unit manapun. Hubungi administrator.');
            }

            // Use loose comparison (==) instead of strict (===) to avoid type mismatch
            if ((int)$dispatch->unit_id !== (int)$unit->id) {
                return back()->with('error', sprintf(
                    'Dispatch ini untuk unit lain (Unit ID: %d). Anda hanya bisa assign untuk unit Anda (Unit ID: %d).',
                    $dispatch->unit_id,
                    $unit->id
                ));
            }

            // Validate request
            $validated = $request->validate([
                'assigned_petugas_id' => 'required|exists:users,id',
                'notes' => 'nullable|string|max:1000',
            ]);

            // Verify the petugas belongs to this unit
            $petugas = User::findOrFail($validated['assigned_petugas_id']);

            Log::info('Petugas Validation Debug', [
                'petugas_id' => $petugas->id,
                'petugas_unit_id' => $petugas->unit_id,
                'petugas_unit_id_type' => gettype($petugas->unit_id),
                'unit_id' => $unit->id,
                'unit_id_type' => gettype($unit->id),
                'petugas_role' => $petugas->role,
                'unit_match_strict' => $petugas->unit_id !== $unit->id,
                'unit_match_loose' => $petugas->unit_id != $unit->id,
                'role_match' => $petugas->role !== 'PETUGAS',
            ]);

            if ((int)$petugas->unit_id !== (int)$unit->id) {
                return back()->with('error', sprintf(
                    'Petugas ini dari unit lain. Petugas unit_id: %s, Your unit_id: %s',
                    $petugas->unit_id,
                    $unit->id
                ));
            }

            if ($petugas->role !== 'PETUGAS') {
                return back()->with('error', sprintf(
                    'User ini bukan petugas. Role: %s',
                    $petugas->role
                ));
            }

            DB::transaction(function () use ($dispatch, $validated, $user) {
                // Update the dispatch
                $dispatch->update([
                    'assigned_petugas_id' => $validated['assigned_petugas_id'],
                    'notes' => $validated['notes'] ?? $dispatch->notes,
                    'assigned_at' => now(),
                ]);

                // Create case event
                CaseEvent::create([
                    'case_id' => $dispatch->case_id,
                    'actor_type' => 'PIMPINAN',
                    'actor_id' => $user->id,
                    'action' => 'PETUGAS_ASSIGNED',
                    'notes' => "Petugas ditugaskan: " . User::find($validated['assigned_petugas_id'])->name,
                    'metadata' => [
                        'dispatch_id' => $dispatch->id,
                        'petugas_id' => $validated['assigned_petugas_id'],
                        'notes' => $validated['notes']
                    ]
                ]);

                Log::info('Petugas assigned successfully', [
                    'dispatch_id' => $dispatch->id,
                    'petugas_id' => $validated['assigned_petugas_id'],
                    'assigned_by' => $user->id
                ]);
            });

            return back()->with('success', 'Petugas berhasil ditugaskan');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error in assignPetugas', [
                'errors' => $e->errors(),
                'dispatch_id' => $dispatch->id
            ]);
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            Log::error('Error assigning petugas', [
                'message' => $e->getMessage(),
                'dispatch_id' => $dispatch->id,
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'Terjadi kesalahan saat menugaskan petugas: ' . $e->getMessage());
        }
    }

    /**
     * Show case detail for pimpinan
     */
    public function showCase(Request $request, $caseId)
    {
        $user = Auth::user();

        // Check if user is pimpinan
        if ($user->role !== 'PIMPINAN') {
            abort(403, 'Unauthorized: Only PIMPINAN can access this page');
        }

        // Get the unit - try unitAsPimpinan first, fallback to user's unit
        $unit = $user->unitAsPimpinan ?? $user->unit;

        if (!$unit) {
            abort(403, 'Unauthorized: You are not assigned to any unit');
        }

        // Find the case and verify it's dispatched to this unit
        $dispatch = CaseDispatch::where('case_id', $caseId)
            ->where('unit_id', $unit->id)
            ->with(['case.caseEvents.actor', 'case.reporterUser', 'assignedPetugas', 'dispatcher'])
            ->firstOrFail();

        $case = $dispatch->case;

        // Get all petugas in this unit
        $availablePetugas = User::where('unit_id', $unit->id)
            ->where('role', 'PETUGAS')
            ->orderBy('name')
            ->get();

        // Check if this is an AJAX request (for modal)
        if ($request->wantsJson() || $request->ajax()) {
            $html = view('pimpinan.show-modal', compact(
                'case',
                'dispatch',
                'unit',
                'availablePetugas'
            ))->render();

            return response()->json([
                'success' => true,
                'html' => $html
            ]);
        }

        // Return full page view
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

        // Get the unit - try unitAsPimpinan first, fallback to user's unit
        $unit = $user->unitAsPimpinan ?? $user->unit;

        if (!$unit) {
            abort(403, 'Unauthorized: You are not assigned to any unit');
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

        // Get the unit - try unitAsPimpinan first, fallback to user's unit
        $unit = $user->unitAsPimpinan ?? $user->unit;

        if (!$unit) {
            return back()->with('error', 'Unauthorized: You are not assigned to any unit');
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
