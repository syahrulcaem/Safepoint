<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UnitController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'web.role']);
    }

    /**
     * Display a listing of the units.
     */
    public function index()
    {
        $units = Unit::withCount(['users', 'cases'])
            ->orderBy('name')
            ->paginate(10);

        return view('units.index', compact('units'));
    }

    /**
     * Show the form for creating a new unit.
     */
    public function create()
    {
        return view('units.create');
    }

    /**
     * Store a newly created unit in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:units',
            'type' => 'required|in:POLISI,DAMKAR,AMBULANCE',
            'is_active' => 'boolean',
        ]);

        $unit = Unit::create([
            'name' => $request->name,
            'type' => $request->type,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('units.index')
            ->with('success', 'Unit berhasil dibuat.');
    }

    /**
     * Display the specified unit.
     */
    public function show(Unit $unit)
    {
        $unit->load(['users', 'cases' => function ($query) {
            $query->latest()->take(10);
        }]);

        return view('units.show', compact('unit'));
    }

    /**
     * Show the form for editing the specified unit.
     */
    public function edit(Unit $unit)
    {
        return view('units.edit', compact('unit'));
    }

    /**
     * Update the specified unit in storage.
     */
    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:units,name,' . $unit->id,
            'type' => 'required|in:POLISI,DAMKAR,AMBULANCE',
            'is_active' => 'boolean',
        ]);

        $unit->update([
            'name' => $request->name,
            'type' => $request->type,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('units.index')
            ->with('success', 'Unit berhasil diperbarui.');
    }

    /**
     * Remove the specified unit from storage.
     */
    public function destroy(Unit $unit)
    {
        // Check if unit has active cases or users
        if ($unit->cases()->whereNotIn('status', ['CLOSED', 'CANCELLED'])->exists()) {
            return redirect()->route('units.index')
                ->with('error', 'Tidak dapat menghapus unit yang memiliki kasus aktif.');
        }

        if ($unit->users()->exists()) {
            return redirect()->route('units.index')
                ->with('error', 'Tidak dapat menghapus unit yang memiliki petugas.');
        }

        $unit->delete();

        return redirect()->route('units.index')
            ->with('success', 'Unit berhasil dihapus.');
    }

    /**
     * Toggle unit status
     */
    public function toggleStatus(Unit $unit)
    {
        $unit->update([
            'is_active' => !$unit->is_active
        ]);

        $status = $unit->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->back()
            ->with('success', "Unit berhasil {$status}.");
    }
}
