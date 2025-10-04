<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cases;
use App\Models\CaseEvent;
use App\Models\User;
use App\Services\What3WordsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PetugasController extends Controller
{
    protected $what3words;

    public function __construct(What3WordsService $what3words)
    {
        $this->what3words = $what3words;
    }

    /**
     * Get petugas profile
     */
    public function profile(Request $request)
    {
        try {
            $user = $request->user();

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'role' => $user->role,
                    'unit' => $user->unit,
                    'duty_status' => $user->duty_status,
                    'duty_started_at' => $user->duty_started_at,
                    'last_location' => $user->getLastLocationAttribute(),
                    'last_activity_at' => $user->last_activity_at
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting petugas profile: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil profil'
            ], 500);
        }
    }

    /**
     * Update petugas profile
     */
    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
        ]);

        try {
            $user = $request->user();
            $user->update($request->only(['name', 'phone']));

            return response()->json([
                'success' => true,
                'message' => 'Profil berhasil diperbarui',
                'data' => $user->fresh()
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating petugas profile: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui profil'
            ], 500);
        }
    }

    /**
     * Start duty shift
     */
    public function startDuty(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->isOnDuty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah dalam status bertugas'
                ], 422);
            }

            $user->startDuty();

            return response()->json([
                'success' => true,
                'message' => 'Duty dimulai',
                'data' => [
                    'duty_status' => $user->duty_status,
                    'duty_started_at' => $user->duty_started_at
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error starting duty: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memulai duty'
            ], 500);
        }
    }

    /**
     * End duty shift
     */
    public function endDuty(Request $request)
    {
        try {
            $user = $request->user();

            if ($user->isOffDuty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah dalam status off duty'
                ], 422);
            }

            $user->endDuty();

            return response()->json([
                'success' => true,
                'message' => 'Duty diakhiri',
                'data' => [
                    'duty_status' => $user->duty_status,
                    'duty_ended_at' => now()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error ending duty: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengakhiri duty'
            ], 500);
        }
    }

    /**
     * Get duty status
     */
    public function getDutyStatus(Request $request)
    {
        try {
            $user = $request->user();

            return response()->json([
                'success' => true,
                'data' => [
                    'duty_status' => $user->duty_status,
                    'is_on_duty' => $user->isOnDuty(),
                    'duty_started_at' => $user->duty_started_at,
                    'last_activity_at' => $user->last_activity_at
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting duty status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil status duty'
            ], 500);
        }
    }

    /**
     * Update current location
     */
    public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180'
        ]);

        try {
            $user = $request->user();
            $latitude = $request->latitude;
            $longitude = $request->longitude;

            $user->updateLocation($latitude, $longitude);

            return response()->json([
                'success' => true,
                'message' => 'Lokasi berhasil diperbarui',
                'data' => [
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'updated_at' => now()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating location: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui lokasi'
            ], 500);
        }
    }

    /**
     * Get current location
     */
    public function getCurrentLocation(Request $request)
    {
        try {
            $user = $request->user();
            $location = $user->getLastLocationAttribute();

            if (!$location) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lokasi belum tersedia'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $location
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting current location: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil lokasi'
            ], 500);
        }
    }

    /**
     * Get assigned cases for petugas
     */
    public function getAssignedCases(Request $request)
    {
        try {
            $user = $request->user();
            $perPage = $request->get('per_page', 10);
            $status = $request->get('status');

            $query = Cases::with(['reporter', 'dispatches', 'lastEvent'])
                ->where(function ($q) use ($user) {
                    // Cases assigned to this specific petugas
                    $q->where('assigned_petugas_id', $user->id)
                        // OR cases assigned to this petugas's unit (but not to specific petugas)
                        ->orWhere(function ($subq) use ($user) {
                            $subq->where('assigned_unit_id', $user->unit_id)
                                ->whereNull('assigned_petugas_id');
                        });
                });

            if ($status) {
                $query->where('status', $status);
            }

            $cases = $query->orderBy('created_at', 'desc')
                ->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $cases->items(),
                'pagination' => [
                    'current_page' => $cases->currentPage(),
                    'per_page' => $cases->perPage(),
                    'total' => $cases->total(),
                    'last_page' => $cases->lastPage()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting assigned cases: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil kasus yang ditugaskan'
            ], 500);
        }
    }

    /**
     * Get case detail
     */
    public function getCaseDetail(Request $request, $caseId)
    {
        try {
            $user = $request->user();

            $case = Cases::with([
                'reporter.citizenProfile',
                'dispatches.unit',
                'events.actor'
            ])->find($caseId);

            if (!$case) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kasus tidak ditemukan'
                ], 404);
            }

            // Check if petugas has access to this case
            $hasAccess = ($case->assigned_petugas_id === $user->id) ||
                ($case->assigned_unit_id === $user->unit_id && !$case->assigned_petugas_id);

            if (!$hasAccess) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak'
                ], 403);
            }            // Calculate distance from petugas current location
            $distance = null;
            if ($user->last_latitude && $user->last_longitude) {
                $distance = $this->calculateDistance(
                    $user->last_latitude,
                    $user->last_longitude,
                    $case->latitude,
                    $case->longitude
                );
            }

            // Get What3Words address
            $what3words = null;
            if ($case->latitude && $case->longitude) {
                $what3words = $this->what3words->getWhat3WordsFromCoordinates(
                    $case->latitude,
                    $case->longitude
                );
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'case' => $case,
                    'distance_km' => $distance,
                    'what3words' => $what3words,
                    'google_maps_url' => "https://www.google.com/maps?q={$case->latitude},{$case->longitude}"
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting case detail: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail kasus'
            ], 500);
        }
    }

    /**
     * Update case status
     */
    public function updateCaseStatus(Request $request, $caseId)
    {
        $request->validate([
            'status' => 'required|in:ON_ROUTE,ARRIVED,IN_PROGRESS,RESOLVED,CLOSED',
            'notes' => 'nullable|string|max:1000'
        ]);

        try {
            $user = $request->user();

            $case = Cases::find($caseId);
            if (!$case) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kasus tidak ditemukan'
                ], 404);
            }

            // Check access
            $hasAccess = $case->dispatches()
                ->where('unit_id', $user->unit_id)
                ->exists();

            if (!$hasAccess) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak'
                ], 403);
            }

            DB::transaction(function () use ($case, $request, $user) {
                // Update case status
                $case->update(['status' => $request->status]);

                // Create case event
                CaseEvent::create([
                    'id' => Str::ulid(),
                    'case_id' => $case->id,
                    'actor_id' => $user->id,
                    'event_type' => 'STATUS_CHANGE',
                    'description' => "Status diubah menjadi {$request->status}" .
                        ($request->notes ? " - {$request->notes}" : ""),
                    'metadata' => json_encode([
                        'old_status' => $case->getOriginal('status'),
                        'new_status' => $request->status,
                        'notes' => $request->notes,
                        'location' => $user->getLastLocationAttribute()
                    ])
                ]);
            });

            return response()->json([
                'success' => true,
                'message' => 'Status kasus berhasil diperbarui',
                'data' => $case->fresh()
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating case status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui status kasus'
            ], 500);
        }
    }

    /**
     * Add case note
     */
    public function addCaseNote(Request $request, $caseId)
    {
        $request->validate([
            'note' => 'required|string|max:1000'
        ]);

        try {
            $user = $request->user();

            $case = Cases::find($caseId);
            if (!$case) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kasus tidak ditemukan'
                ], 404);
            }

            // Check access
            $hasAccess = $case->dispatches()
                ->where('unit_id', $user->unit_id)
                ->exists();

            if (!$hasAccess) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak'
                ], 403);
            }

            // Create case event for note
            $caseEvent = CaseEvent::create([
                'id' => Str::ulid(),
                'case_id' => $case->id,
                'actor_id' => $user->id,
                'event_type' => 'NOTE_ADDED',
                'description' => $request->note,
                'metadata' => json_encode([
                    'note_type' => 'field_note',
                    'location' => $user->getLastLocationAttribute()
                ])
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Catatan berhasil ditambahkan',
                'data' => $caseEvent
            ]);
        } catch (\Exception $e) {
            Log::error('Error adding case note: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan catatan'
            ], 500);
        }
    }

    /**
     * Get family contacts for case
     */
    public function getFamilyContacts(Request $request, $caseId)
    {
        try {
            $user = $request->user();

            $case = Cases::with('reporter.citizenProfile')->find($caseId);
            if (!$case) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kasus tidak ditemukan'
                ], 404);
            }

            // Check access
            $hasAccess = $case->dispatches()
                ->where('unit_id', $user->unit_id)
                ->exists();

            if (!$hasAccess) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak'
                ], 403);
            }

            $contacts = [];

            // Reporter contact
            if ($case->reporter) {
                $contacts[] = [
                    'type' => 'reporter',
                    'name' => $case->reporter->name,
                    'phone' => $case->reporter->phone,
                    'relationship' => 'Pelapor'
                ];
            }

            // Family WhatsApp contact
            if ($case->reporter && $case->reporter->citizenProfile && $case->reporter->citizenProfile->whatsapp_keluarga) {
                $contacts[] = [
                    'type' => 'family_whatsapp',
                    'name' => $case->reporter->citizenProfile->hubungan_keluarga ?? 'Keluarga',
                    'phone' => $case->reporter->citizenProfile->whatsapp_keluarga,
                    'relationship' => $case->reporter->citizenProfile->hubungan_keluarga ?? 'Keluarga'
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $contacts
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting family contacts: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil kontak keluarga'
            ], 500);
        }
    }

    /**
     * Contact family via WhatsApp
     */
    public function contactFamily(Request $request, $caseId)
    {
        $request->validate([
            'contact_type' => 'required|in:reporter,family_whatsapp',
            'message_template' => 'required|in:status_update,arrival_notification,completion_notice'
        ]);

        try {
            $user = $request->user();

            $case = Cases::with('reporter.citizenProfile')->find($caseId);
            if (!$case) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kasus tidak ditemukan'
                ], 404);
            }

            // Check access
            $hasAccess = $case->dispatches()
                ->where('unit_id', $user->unit_id)
                ->exists();

            if (!$hasAccess) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak'
                ], 403);
            }

            // Get phone number based on contact type
            $phoneNumber = null;
            $contactName = '';

            if ($request->contact_type === 'reporter' && $case->reporter) {
                $phoneNumber = $case->reporter->phone;
                $contactName = $case->reporter->name;
            } elseif (
                $request->contact_type === 'family_whatsapp' &&
                $case->reporter &&
                $case->reporter->citizenProfile &&
                $case->reporter->citizenProfile->whatsapp_keluarga
            ) {
                $phoneNumber = $case->reporter->citizenProfile->whatsapp_keluarga;
                $contactName = $case->reporter->citizenProfile->hubungan_keluarga ?? 'Keluarga';
            }

            if (!$phoneNumber) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nomor telepon tidak tersedia'
                ], 422);
            }

            // Generate message based on template
            $message = $this->generateWhatsAppMessage($request->message_template, $case, $user);

            // Create WhatsApp link
            $whatsappUrl = "https://wa.me/" . preg_replace('/[^0-9]/', '', $phoneNumber) . "?text=" . urlencode($message);

            // Log the contact attempt
            CaseEvent::create([
                'id' => Str::ulid(),
                'case_id' => $case->id,
                'actor_id' => $user->id,
                'event_type' => 'FAMILY_CONTACTED',
                'description' => "Menghubungi {$contactName} via WhatsApp",
                'metadata' => json_encode([
                    'contact_type' => $request->contact_type,
                    'phone_number' => $phoneNumber,
                    'message_template' => $request->message_template,
                    'contact_name' => $contactName
                ])
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Link WhatsApp berhasil dibuat',
                'data' => [
                    'whatsapp_url' => $whatsappUrl,
                    'contact_name' => $contactName,
                    'phone_number' => $phoneNumber,
                    'message' => $message
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error contacting family: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghubungi keluarga'
            ], 500);
        }
    }

    /**
     * Get dashboard data for petugas
     */
    public function getDashboardData(Request $request)
    {
        try {
            $user = $request->user();

            // Get statistics
            $stats = [
                'assigned_cases_today' => Cases::whereHas('dispatches', function ($q) use ($user) {
                    $q->where('unit_id', $user->unit_id);
                })->whereDate('created_at', today())->count(),

                'resolved_cases_today' => Cases::whereHas('dispatches', function ($q) use ($user) {
                    $q->where('unit_id', $user->unit_id);
                })->where('status', 'RESOLVED')
                    ->whereDate('updated_at', today())->count(),

                'pending_cases' => Cases::whereHas('dispatches', function ($q) use ($user) {
                    $q->where('unit_id', $user->unit_id);
                })->whereIn('status', ['ASSIGNED', 'ON_ROUTE', 'ARRIVED', 'IN_PROGRESS'])->count(),

                'duty_duration' => $user->duty_started_at ?
                    now()->diffInMinutes($user->duty_started_at) : 0
            ];

            // Get recent cases
            $recentCases = Cases::with(['reporter', 'lastEvent'])
                ->whereHas('dispatches', function ($q) use ($user) {
                    $q->where('unit_id', $user->unit_id);
                })
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'stats' => $stats,
                    'recent_cases' => $recentCases,
                    'user_status' => [
                        'duty_status' => $user->duty_status,
                        'last_location' => $user->getLastLocationAttribute(),
                        'unit' => $user->unit
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting dashboard data: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data dashboard'
            ], 500);
        }
    }

    /**
     * Check for updates (polling endpoint)
     */
    public function checkUpdates(Request $request)
    {
        try {
            $user = $request->user();
            $lastCheck = $request->get('last_check');

            // If no last_check provided, default to 5 minutes ago
            if (!$lastCheck) {
                $lastCheck = now()->subMinutes(5)->toISOString();
            }

            $lastCheckTime = \Carbon\Carbon::parse($lastCheck);

            // Get new assignments since last check
            $newAssignments = Cases::with(['reporterUser.citizenProfile'])
                ->where(function ($q) use ($user) {
                    // Cases assigned to this specific petugas
                    $q->where('assigned_petugas_id', $user->id)
                        // OR cases assigned to this petugas's unit (but not to specific petugas)
                        ->orWhere(function ($subq) use ($user) {
                            $subq->where('assigned_unit_id', $user->unit_id)
                                ->whereNull('assigned_petugas_id');
                        });
                })
                ->where('dispatched_at', '>', $lastCheckTime)
                ->orderBy('dispatched_at', 'desc')
                ->get();

            // Get status updates for existing assigned cases
            $statusUpdates = Cases::with(['caseEvents' => function ($q) use ($lastCheckTime) {
                $q->where('created_at', '>', $lastCheckTime)
                    ->orderBy('created_at', 'desc');
            }])
                ->where(function ($q) use ($user) {
                    $q->where('assigned_petugas_id', $user->id)
                        ->orWhere(function ($subq) use ($user) {
                            $subq->where('assigned_unit_id', $user->unit_id)
                                ->whereNull('assigned_petugas_id');
                        });
                })
                ->where('updated_at', '>', $lastCheckTime)
                ->whereNotIn('status', ['NEW', 'PENDING']) // Only dispatched cases
                ->get();

            // Calculate distances for new assignments
            $enrichedAssignments = $newAssignments->map(function ($case) use ($user) {
                $distance = null;
                $eta = null;

                if ($user->last_latitude && $user->last_longitude && $case->lat && $case->lon) {
                    $distance = $this->calculateDistance(
                        $user->last_latitude,
                        $user->last_longitude,
                        (float) $case->lat,
                        (float) $case->lon
                    );

                    // Simple ETA calculation (distance / average speed 40km/h)
                    $eta = round(($distance / 40) * 60); // minutes
                }

                return [
                    'id' => $case->id,
                    'category' => $case->category,
                    'description' => $case->description,
                    'status' => $case->status,
                    'latitude' => $case->lat,
                    'longitude' => $case->lon,
                    'locator_text' => $case->locator_text,
                    'reporter_name' => $case->reporterUser->name ?? 'Unknown',
                    'reporter_phone' => $case->phone ?? $case->reporterUser->phone ?? null,
                    'distance_km' => $distance,
                    'eta_minutes' => $eta,
                    'dispatched_at' => $case->dispatched_at,
                    'created_at' => $case->created_at,
                    'google_maps_url' => "https://www.google.com/maps?q={$case->lat},{$case->lon}",
                    'priority' => $this->calculatePriority($case->category, $distance)
                ];
            });

            $hasUpdates = $newAssignments->isNotEmpty() ||
                $statusUpdates->isNotEmpty();

            return response()->json([
                'success' => true,
                'data' => [
                    'has_updates' => $hasUpdates,
                    'new_assignments' => $enrichedAssignments,
                    'status_updates' => $statusUpdates,
                    'last_check' => now()->toISOString(),
                    'petugas_location' => [
                        'latitude' => $user->last_latitude,
                        'longitude' => $user->last_longitude,
                        'updated_at' => $user->last_location_update
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error checking updates: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengecek update'
            ], 500);
        }
    }

    /**
     * Calculate priority based on category and distance
     */
    private function calculatePriority($category, $distance)
    {
        $categoryPriority = [
            'BENCANA_ALAM' => 5,
            'KECELAKAAN' => 4,
            'KEBOCORAN_GAS' => 4,
            'BANJIR' => 3,
            'POHON_TUMBANG' => 2,
            'UMUM' => 1
        ];

        $basePriority = $categoryPriority[$category] ?? 1;

        // Increase priority if closer
        if ($distance !== null) {
            if ($distance < 1) $basePriority += 2;
            elseif ($distance < 3) $basePriority += 1;
        }

        return min($basePriority, 5); // Max priority 5
    }

    /**
     * Calculate distance between two coordinates in kilometers
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return round($earthRadius * $c, 2);
    }

    /**
     * Generate WhatsApp message based on template
     */
    private function generateWhatsAppMessage($template, $case, $user)
    {
        $messages = [
            'status_update' => "Halo, ini adalah update status kasus emergency ID: {$case->id}.\n\nStatus saat ini: {$case->status}\nPetugas: {$user->name}\nWaktu: " . now()->format('d/m/Y H:i') . "\n\nTerima kasih.",

            'arrival_notification' => "Halo, kami ingin memberitahukan bahwa petugas telah tiba di lokasi emergency.\n\nKasus ID: {$case->id}\nPetugas: {$user->name}\nWaktu kedatangan: " . now()->format('d/m/Y H:i') . "\n\nTerima kasih.",

            'completion_notice' => "Halo, kami ingin memberitahukan bahwa penanganan kasus emergency telah selesai.\n\nKasus ID: {$case->id}\nPetugas: {$user->name}\nWaktu selesai: " . now()->format('d/m/Y H:i') . "\n\nTerima kasih atas kepercayaan Anda pada layanan SafePoint."
        ];

        return $messages[$template] ?? 'Pesan tidak ditemukan';
    }

    /**
     * Get What3Words address from coordinates
     */
    public function getWhat3Words(Request $request, $lat, $lon)
    {
        try {
            $what3words = $this->what3words->convertToWords(
                (float) $lat,
                (float) $lon
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'coordinates' => [
                        'latitude' => (float) $lat,
                        'longitude' => (float) $lon
                    ],
                    'what3words' => $what3words,
                    'google_maps_url' => "https://www.google.com/maps?q={$lat},{$lon}"
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting What3Words: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil What3Words address'
            ], 500);
        }
    }

    /**
     * Get coordinates from What3Words address
     */
    public function getCoordinatesFromWhat3Words(Request $request)
    {
        $request->validate([
            'words' => 'required|string|regex:/^[a-zA-Z0-9]+\.[a-zA-Z0-9]+\.[a-zA-Z0-9]+$/'
        ]);

        try {
            $coordinates = $this->what3words->convertToCoordinates($request->words);

            if (!$coordinates) {
                return response()->json([
                    'success' => false,
                    'message' => 'What3Words address tidak valid atau tidak ditemukan'
                ], 422);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'what3words' => $request->words,
                    'coordinates' => [
                        'latitude' => $coordinates['latitude'],
                        'longitude' => $coordinates['longitude']
                    ],
                    'google_maps_url' => "https://www.google.com/maps?q={$coordinates['latitude']},{$coordinates['longitude']}"
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting coordinates from What3Words: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil koordinat dari What3Words address'
            ], 500);
        }
    }
}
