<?php

namespace App\Http\Controllers;

use App\Models\Cases;
use App\Models\Unit;
use App\Models\CaseEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CaseController extends Controller
{
    public function index(Request $request)
    {
        $query = Cases::with(['reporterUser', 'assignedUnit']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by unit (assigned/unassigned)
        if ($request->filled('unit')) {
            if ($request->unit === 'unassigned') {
                $query->whereNull('assigned_unit_id');
            } elseif ($request->unit === 'assigned') {
                $query->whereNotNull('assigned_unit_id');
            } else {
                $query->where('assigned_unit_id', $request->unit);
            }
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search by ID or locator_text
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('short_id', 'LIKE', "%{$search}%")
                    ->orWhere('locator_text', 'LIKE', "%{$search}%")
                    ->orWhere('location', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        // Get per_page parameter, default to 10, max 100
        $perPage = min((int) $request->get('per_page', 10), 100);

        $cases = $query->orderBy('created_at', 'desc')->paginate($perPage)->withQueryString();

        $units = Unit::active()->orderBy('name')->get();
        $categories = ['UMUM', 'MEDIS', 'KEBAKARAN', 'KRIMINAL', 'BENCANA_ALAM', 'KECELAKAAN', 'KEBOCORAN_GAS', 'POHON_TUMBANG', 'BANJIR'];
        $statuses = ['NEW', 'VERIFIED', 'DISPATCHED', 'ON_THE_WAY', 'ON_SCENE', 'CLOSED', 'CANCELLED'];

        // Stats for display
        $stats = [
            'total' => Cases::count(),
            'new' => Cases::where('status', 'NEW')->count(),
            'active' => Cases::whereIn('status', ['VERIFIED', 'DISPATCHED', 'ON_THE_WAY', 'ON_SCENE'])->count(),
            'closed' => Cases::where('status', 'CLOSED')->count(),
        ];

        return view('cases.index', compact('cases', 'units', 'categories', 'statuses', 'stats'));
    }

    public function show(Cases $case)
    {
        $case->load([
            'reporterUser',
            'assignedUnit',
            'caseEvents' => function ($query) {
                $query->with('actor')->orderBy('created_at', 'desc');
            },
            'dispatches' => function ($query) {
                $query->with(['unit', 'assignedBy'])->orderBy('created_at', 'desc');
            }
        ]);

        $units = Unit::active()->orderBy('name')->get();

        return view('cases.show', compact('case', 'units'));
    }

    public function modal(Cases $case)
    {
        $case->load([
            'reporterUser',
            'assignedUnit',
            'caseEvents' => function ($query) {
                $query->orderBy('created_at', 'asc');
            }
        ]);

        // Format timeline data
        $timeline = $case->caseEvents->map(function ($event) {
            return [
                'event' => $event->action,
                'created_at' => $event->created_at->toISOString(),
                'notes' => $event->notes,
                'metadata' => $event->metadata
            ];
        });

        return response()->json([
            'id' => $case->id,
            'short_id' => $case->short_id,
            'status' => $case->status,
            'category' => $case->category,
            'location' => $case->location,
            'locator_text' => $case->locator_text,
            'description' => $case->description,
            'phone' => $case->phone,
            'lat' => $case->lat,
            'lon' => $case->lon,
            'created_at' => $case->created_at->toISOString(),
            'reporter_user' => $case->reporterUser ? [
                'name' => $case->reporterUser->name,
                'email' => $case->reporterUser->email
            ] : null,
            'assigned_unit' => $case->assignedUnit ? [
                'name' => $case->assignedUnit->name,
                'type' => $case->assignedUnit->type,
                'phone' => $case->assignedUnit->phone
            ] : null,
            'timeline' => $timeline
        ]);
    }

    public function verify(Cases $case)
    {
        if ($case->status !== 'NEW') {
            return back()->with('error', 'Hanya kasus dengan status BARU yang dapat diverifikasi.');
        }

        $case->update([
            'status' => 'VERIFIED',
            'verified_at' => now()
        ]);

        // Create case event
        CaseEvent::create([
            'case_id' => $case->id,
            'actor_id' => Auth::id(),
            'action' => 'VERIFIED',
            'notes' => 'Kasus telah diverifikasi oleh operator',
            'metadata' => json_encode([
                'previous_status' => 'NEW',
                'new_status' => 'VERIFIED'
            ])
        ]);

        return back()->with('success', 'Kasus berhasil diverifikasi.');
    }

    public function dispatch(Request $request, Cases $case)
    {
        $request->validate([
            'unit_id' => 'required|exists:units,id',
            'notes' => 'nullable|string|max:500'
        ]);

        if (!in_array($case->status, ['VERIFIED', 'NEW'])) {
            return back()->with('error', 'Kasus tidak dapat dikirim dalam status saat ini.');
        }

        $unit = Unit::findOrFail($request->unit_id);

        $case->update([
            'status' => 'DISPATCHED',
            'assigned_unit_id' => $unit->id,
            'dispatched_at' => now()
        ]);

        // Create case event
        CaseEvent::create([
            'case_id' => $case->id,
            'actor_id' => Auth::id(),
            'action' => 'DISPATCHED',
            'notes' => $request->notes ?: "Dikirim ke unit {$unit->name}",
            'metadata' => json_encode([
                'unit_id' => $unit->id,
                'unit_name' => $unit->name,
                'unit_type' => $unit->type,
                'previous_status' => $case->getOriginal('status'),
                'new_status' => 'DISPATCHED'
            ])
        ]);

        return back()->with('success', "Kasus berhasil dikirim ke unit {$unit->name}.");
    }

    public function close(Request $request, Cases $case)
    {
        $request->validate([
            'notes' => 'required|string|max:500'
        ]);

        if (!in_array($case->status, ['ON_SCENE', 'DISPATCHED', 'ON_THE_WAY'])) {
            return back()->with('error', 'Kasus tidak dapat ditutup dalam status saat ini.');
        }

        $case->update([
            'status' => 'CLOSED',
            'closed_at' => now()
        ]);

        // Create case event
        CaseEvent::create([
            'case_id' => $case->id,
            'actor_id' => Auth::id(),
            'action' => 'CLOSED',
            'notes' => $request->notes,
            'metadata' => json_encode([
                'previous_status' => $case->getOriginal('status'),
                'new_status' => 'CLOSED',
                'closure_reason' => $request->notes
            ])
        ]);

        return back()->with('success', 'Kasus berhasil ditutup.');
    }

    public function cancel(Request $request, Cases $case)
    {
        $request->validate([
            'notes' => 'required|string|max:500'
        ]);

        if (in_array($case->status, ['CLOSED', 'CANCELLED'])) {
            return back()->with('error', 'Kasus tidak dapat dibatalkan dalam status saat ini.');
        }

        $case->update([
            'status' => 'CANCELLED'
        ]);

        // Create case event
        CaseEvent::create([
            'case_id' => $case->id,
            'actor_id' => Auth::id(),
            'action' => 'CANCELLED',
            'notes' => $request->notes,
            'metadata' => json_encode([
                'previous_status' => $case->getOriginal('status'),
                'new_status' => 'CANCELLED',
                'cancellation_reason' => $request->notes
            ])
        ]);

        return back()->with('success', 'Kasus berhasil dibatalkan.');
    }
}
