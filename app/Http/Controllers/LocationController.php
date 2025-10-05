<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Show map view with all petugas locations
     */
    public function index()
    {
        $user = auth()->user();

        // Get petugas based on role
        if ($user->role === 'PIMPINAN') {
            // Pimpinan only sees their unit's petugas
            $petugas = User::where('role', 'PETUGAS')
                ->where('unit_id', $user->unit_id)
                ->whereNotNull('last_latitude')
                ->whereNotNull('last_longitude')
                ->get();
        } else {
            // Operator/Superadmin sees all petugas
            $petugas = User::where('role', 'PETUGAS')
                ->whereNotNull('last_latitude')
                ->whereNotNull('last_longitude')
                ->with('unit')
                ->get();
        }

        return view('location.map', compact('petugas'));
    }

    /**
     * Get petugas locations as JSON (for AJAX refresh)
     */
    public function getPetugasLocations()
    {
        $user = auth()->user();

        if ($user->role === 'PIMPINAN') {
            $petugas = User::where('role', 'PETUGAS')
                ->where('unit_id', $user->unit_id)
                ->whereNotNull('last_latitude')
                ->whereNotNull('last_longitude')
                ->get(['id', 'name', 'last_latitude', 'last_longitude', 'last_location_update', 'unit_id']);
        } else {
            $petugas = User::where('role', 'PETUGAS')
                ->whereNotNull('last_latitude')
                ->whereNotNull('last_longitude')
                ->with('unit:id,name')
                ->get(['id', 'name', 'last_latitude', 'last_longitude', 'last_location_update', 'unit_id']);
        }

        return response()->json([
            'success' => true,
            'petugas' => $petugas->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'latitude' => (float) $p->last_latitude,
                    'longitude' => (float) $p->last_longitude,
                    'last_update' => $p->last_location_update?->diffForHumans(),
                    'unit_name' => $p->unit?->name ?? 'N/A',
                ];
            })
        ]);
    }

    /**
     * Update petugas location (for web interface)
     */
    public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $user = auth()->user();

        if ($user->role !== 'PETUGAS') {
            return response()->json([
                'success' => false,
                'message' => 'Only petugas can update location'
            ], 403);
        }

        $user->update([
            'last_latitude' => $request->latitude,
            'last_longitude' => $request->longitude,
            'last_location_update' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Location updated successfully',
            'data' => [
                'latitude' => (float) $user->last_latitude,
                'longitude' => (float) $user->last_longitude,
                'last_update' => $user->last_location_update->diffForHumans(),
            ]
        ]);
    }

    /**
     * Get location of a specific user (for map display)
     */
    public function getUserLocation($userId)
    {
        $user = User::findOrFail($userId);

        // Check authorization
        $authUser = auth()->user();
        if ($authUser->role === 'PIMPINAN') {
            // Pimpinan can only see their unit's petugas
            if ($user->unit_id !== $authUser->unit_id) {
                abort(403, 'Unauthorized');
            }
        } elseif (!in_array($authUser->role, ['OPERATOR', 'SUPERADMIN'])) {
            abort(403, 'Unauthorized');
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'latitude' => (float) $user->last_latitude,
                'longitude' => (float) $user->last_longitude,
                'updated_at' => $user->last_location_update?->diffForHumans(),
            ]
        ]);
    }

    /**
     * Get all petugas locations in a unit
     */
    public function getUnitLocations($unitId = null)
    {
        $user = auth()->user();

        // If pimpinan, use their unit_id
        if ($user->role === 'PIMPINAN') {
            $unitId = $user->unit_id;
        }

        if (!$unitId) {
            return response()->json([
                'success' => false,
                'message' => 'Unit ID is required'
            ], 400);
        }

        $petugas = User::where('role', 'PETUGAS')
            ->where('unit_id', $unitId)
            ->whereNotNull('last_latitude')
            ->whereNotNull('last_longitude')
            ->get(['id', 'name', 'last_latitude', 'last_longitude', 'last_location_update']);

        return response()->json([
            'success' => true,
            'data' => $petugas->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'latitude' => (float) $p->last_latitude,
                    'longitude' => (float) $p->last_longitude,
                    'updated_at' => $p->last_location_update?->diffForHumans(),
                ];
            })
        ]);
    }
}
