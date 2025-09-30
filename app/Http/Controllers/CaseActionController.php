<?php

namespace App\Http\Controllers;

use App\Models\Cases;
use App\Models\CaseEvent;
use App\Models\Dispatch;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CaseActionController extends Controller
{
    public function verify(Cases $case)
    {
        if (!Auth::user()->canManageCases()) {
            abort(403, 'Unauthorized action.');
        }

        if ($case->status !== 'NEW') {
            return back()->withErrors(['action' => 'Kasus ini tidak dapat diverifikasi.']);
        }

        DB::transaction(function () use ($case) {
            $case->update([
                'status' => 'VERIFIED',
                'verified_at' => now(),
            ]);

            CaseEvent::create([
                'case_id' => $case->id,
                'actor_type' => Auth::user()->role,
                'actor_id' => Auth::id(),
                'event' => 'VERIFIED',
                'meta' => ['verified_by' => Auth::user()->name],
            ]);
        });

        return back()->with('success', 'Kasus berhasil diverifikasi.');
    }

    public function dispatch(Request $request, Cases $case)
    {
        if (!Auth::user()->canManageCases()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        if (!in_array($case->status, ['NEW', 'VERIFIED'])) {
            return back()->withErrors(['action' => 'Kasus ini tidak dapat didispatch.']);
        }

        $unit = Unit::findOrFail($request->unit_id);

        DB::transaction(function () use ($case, $unit, $request) {
            $case->update([
                'status' => 'DISPATCHED',
                'assigned_unit_id' => $unit->id,
                'dispatched_at' => now(),
            ]);

            Dispatch::create([
                'case_id' => $case->id,
                'unit_id' => $unit->id,
                'assigned_by' => Auth::id(),
                'notes' => $request->notes,
            ]);

            CaseEvent::create([
                'case_id' => $case->id,
                'actor_type' => Auth::user()->role,
                'actor_id' => Auth::id(),
                'event' => 'DISPATCHED',
                'meta' => [
                    'unit_name' => $unit->name,
                    'unit_type' => $unit->type,
                    'notes' => $request->notes,
                ],
            ]);
        });

        return back()->with('success', "Kasus berhasil didispatch ke {$unit->name}.");
    }

    public function close(Cases $case)
    {
        if (!Auth::user()->canManageCases()) {
            abort(403, 'Unauthorized action.');
        }

        if (!in_array($case->status, ['DISPATCHED', 'ON_THE_WAY', 'ON_SCENE'])) {
            return back()->withErrors(['action' => 'Kasus ini tidak dapat ditutup.']);
        }

        DB::transaction(function () use ($case) {
            $case->update([
                'status' => 'CLOSED',
                'closed_at' => now(),
            ]);

            CaseEvent::create([
                'case_id' => $case->id,
                'actor_type' => Auth::user()->role,
                'actor_id' => Auth::id(),
                'event' => 'CLOSED',
                'meta' => ['closed_by' => Auth::user()->name],
            ]);
        });

        return back()->with('success', 'Kasus berhasil ditutup.');
    }
}
