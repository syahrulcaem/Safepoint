<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cases;
use App\Models\CaseEvent;
use App\Services\What3WordsService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CaseController extends Controller
{
    protected What3WordsService $what3wordsService;

    public function __construct(What3WordsService $what3wordsService)
    {
        $this->what3wordsService = $what3wordsService;
    }
    public function create(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'accuracy' => 'nullable|integer|min:0',
            'category' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:32',
        ]);

        $user = $request->user();

        // Convert lat/long to What3Words
        $what3words = $this->what3wordsService->convertToWords(
            (float) $request->latitude,
            (float) $request->longitude
        );

        // Use What3Words as locator_text, or fallback to coordinates
        $locatorText = $what3words ?? $this->what3wordsService->getFallbackLocationText(
            (float) $request->latitude,
            (float) $request->longitude
        );

        $case = Cases::create([
            'id' => Str::ulid(),
            'reporter_user_id' => $user->id,
            'viewer_token_hash' => hash('sha256', Str::random(32)),
            'phone' => $request->phone ?? $user->phone,
            'lat' => $request->latitude,
            'lon' => $request->longitude,
            'accuracy' => $request->accuracy,
            'locator_text' => $locatorText,
            'locator_provider' => $what3words ? 'w3w' : 'coordinates',
            'category' => $request->category ?? 'UMUM',
            'description' => $request->description,
            'status' => 'NEW',
            'contacts_snapshot' => $this->getContactsSnapshot($user),
        ]);

        // Create case event
        CaseEvent::create([
            'case_id' => $case->id,
            'actor_type' => 'WARGA',
            'actor_id' => $user->id,
            'action' => 'CREATED',
            'metadata' => [
                'reporter_name' => $user->name,
                'category' => $case->category,
                'location' => $case->locator_text,
                'what3words' => $what3words,
            ],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Panggilan darurat berhasil dibuat',
            'data' => [
                'case' => $case->load(['reporterUser', 'assignedUnit']),
                'what3words' => $what3words,
                'google_maps_url' => $this->what3wordsService->getGoogleMapsUrl(
                    (float) $request->latitude,
                    (float) $request->longitude
                ),
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
