<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cases;
use App\Models\CaseEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CaseController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lon' => 'required|numeric|between:-180,180',
            'accuracy' => 'nullable|integer|min:0',
            'locator_text' => 'required|string|max:64',
            'locator_provider' => 'nullable|in:w3w,pluscode',
            'category' => 'nullable|string|max:20',
            'phone' => 'nullable|string|max:32',
        ]);

        $user = $request->user();

        $case = Cases::create([
            'id' => Str::ulid(),
            'reporter_user_id' => $user->id,
            'viewer_token_hash' => hash('sha256', Str::random(32)),
            'phone' => $request->phone ?? $user->phone,
            'lat' => $request->lat,
            'lon' => $request->lon,
            'accuracy' => $request->accuracy,
            'locator_text' => $request->locator_text,
            'locator_provider' => $request->locator_provider ?? 'pluscode',
            'category' => $request->category ?? 'UMUM',
            'status' => 'NEW',
            'contacts_snapshot' => $this->getContactsSnapshot($user),
        ]);

        // Create case event
        CaseEvent::create([
            'case_id' => $case->id,
            'actor_type' => 'WARGA',
            'actor_id' => $user->id,
            'event' => 'CREATED',
            'meta' => [
                'reporter_name' => $user->name,
                'category' => $case->category,
                'location' => $case->locator_text,
            ],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Panggilan darurat berhasil dibuat',
            'data' => [
                'case' => $case->load(['reporterUser', 'assignedUnit']),
            ],
        ], 201);
    }

    public function show(Request $request, Cases $case)
    {
        // Only allow access to own cases
        if ($case->reporter_user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak',
            ], 403);
        }

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

        return response()->json([
            'success' => true,
            'data' => [
                'case' => $case,
            ],
        ]);
    }

    public function myCases(Request $request)
    {
        $cases = Cases::where('reporter_user_id', $request->user()->id)
            ->with(['assignedUnit'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => [
                'cases' => $cases,
            ],
        ]);
    }

    private function getContactsSnapshot($user)
    {
        $contacts = [];

        if ($user->citizenProfile) {
            $contacts[] = [
                'name' => $user->name,
                'phone' => $user->phone,
                'relation' => 'Pelapor',
                'is_primary' => true,
            ];
        }

        return $contacts;
    }
}
