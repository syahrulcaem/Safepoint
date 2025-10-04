<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cases;
use App\Models\CaseEvent;
use App\Services\What3WordsService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublicEmergencyController extends Controller
{
    protected What3WordsService $what3wordsService;

    public function __construct(What3WordsService $what3wordsService)
    {
        $this->what3wordsService = $what3wordsService;
    }

    /**
     * Store a new emergency report without requiring authentication.
     * Basic anti-abuse: throttle via route middleware, optional simple honeypot field.
     */
    public function store(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'accuracy' => 'nullable|integer|min:0',
            'category' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:32',
            // Optional simple honeypot: bots often fill hidden fields
            'website' => 'nullable|string|size:0',
        ], [
            'website.size' => 'Invalid input',
        ]);

        // Convert lat/long to What3Words if available
        $what3words = $this->what3wordsService->convertToWords(
            (float) $request->latitude,
            (float) $request->longitude
        );

        $locatorText = $what3words ?? $this->what3wordsService->getFallbackLocationText(
            (float) $request->latitude,
            (float) $request->longitude
        );

        $case = Cases::create([
            'id' => Str::ulid(),
            'reporter_user_id' => null, // anonymous
            'viewer_token_hash' => hash('sha256', Str::random(32)),
            'phone' => $request->phone,
            'lat' => $request->latitude,
            'lon' => $request->longitude,
            'accuracy' => $request->accuracy,
            'locator_text' => $locatorText,
            'locator_provider' => $what3words ? 'w3w' : 'coordinates',
            'category' => $request->category ?? 'UMUM',
            'description' => $request->description,
            'status' => 'NEW',
            'contacts_snapshot' => [],
        ]);

        CaseEvent::create([
            'case_id' => $case->id,
            'actor_type' => 'PUBLIC',
            'actor_id' => null,
            'action' => 'CREATED',
            'metadata' => [
                'category' => $case->category,
                'location' => $case->locator_text,
                'what3words' => $what3words,
                'anonymous' => true,
            ],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Laporan darurat anonim berhasil dibuat',
            'data' => [
                'case_id' => $case->id,
                'short_id' => $case->short_id,
                'status' => $case->status,
                'locator_text' => $case->locator_text,
                'what3words' => $what3words,
                'google_maps_url' => $this->what3wordsService->getGoogleMapsUrl(
                    (float) $request->latitude,
                    (float) $request->longitude
                ),
            ],
        ], 201);
    }
}
