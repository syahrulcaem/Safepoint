@extends('layouts.app')

@section('title', 'Detail Kasus - ' . $case->short_id)

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Kasus</h1>
                <p class="text-sm text-gray-600 mt-1">
                    Case ID: <span class="font-mono font-semibold">{{ $case->short_id }}</span>
                </p>
            </div>
            <a href="{{ route('petugas.dashboard') }}"
                class="mt-4 sm:mt-0 px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-200 inline-block">
                ← Kembali
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-800 rounded-lg p-4">
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Case Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Status & Category -->
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">Status Kasus</h2>
                        <span
                            class="px-3 py-1 text-sm font-medium rounded-full 
                            @switch($case->status)
                                @case('NEW') bg-blue-100 @break
                                @case('DISPATCHED') @break
                                @case('ON_THE_WAY') @break
                                @case('ON_SCENE') text-orange-800 @break
                                @case('CLOSED') @break
                                @case('CANCELLED') @break
                                @default
                            @endswitch">
                            {{ str_replace('_', ' ', $case->status) }}
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Kategori</label>
                            <p class="mt-1 text-sm font-medium text-gray-900">{{ $case->category }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Waktu Laporan</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $case->created_at->format('d M Y, H:i') }} WIB</p>
                            <p class="text-xs text-gray-500">{{ $case->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Location Info -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Lokasi Kejadian</h2>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Alamat</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $case->locator_text ?? 'N/A' }}</p>
                        </div>
                        @if ($case->lat && $case->lon)
                            <div>
                                <label class="text-sm font-medium text-gray-500">Koordinat</label>
                                <p class="mt-1 text-sm font-mono text-gray-900">
                                    {{ $case->lat }}, {{ $case->lon }}
                                    <span class="text-gray-500">(Akurasi: {{ $case->accuracy ?? 'N/A' }}m)</span>
                                </p>
                            </div>

                            <!-- Map Display -->
                            <div>
                                <label class="text-sm font-medium text-gray-500 block mb-2">Peta Lokasi</label>
                                <div id="map" class="w-full h-64 rounded-lg border border-gray-300"></div>
                            </div>

                            <div class="flex space-x-3">
                                <a href="https://www.google.com/maps?q={{ $case->lat }},{{ $case->lon }}"
                                    target="_blank"
                                    class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Buka di Google Maps
                                </a>
                                <a href="https://www.waze.com/ul?ll={{ $case->lat }},{{ $case->lon }}&navigate=yes"
                                    target="_blank"
                                    class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-sky-600 text-white text-sm font-medium rounded-md hover:bg-sky-700">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z" />
                                    </svg>
                                    Buka di Waze
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Reporter Info -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Pelapor</h2>
                    <div class="grid grid-cols-2 gap-4">
                        @if ($case->reporterUser)
                            <div>
                                <label class="text-sm font-medium text-gray-500">Nama</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $case->reporterUser->name }}</p>
                            </div>
                        @endif
                        <div>
                            <label class="text-sm font-medium text-gray-500">Nomor Telepon</label>
                            <p class="mt-1 text-sm text-gray-900">
                                @if ($case->phone)
                                    <a href="tel:{{ $case->phone }}" class="text-blue-600 hover:underline">
                                        {{ $case->phone }}
                                    </a>
                                @else
                                    N/A
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Dispatch Info -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Penugasan</h2>
                    <div class="space-y-3">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Unit</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $dispatch->unit->name }}
                                ({{ $dispatch->unit->type }})</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Operator/Dispatcher</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $dispatch->dispatcher->name }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Waktu Ditugaskan</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $dispatch->assigned_at->format('d F Y, H:i') }} WIB
                                <span class="text-gray-500">({{ $dispatch->assigned_at->diffForHumans() }})</span>
                            </p>
                        </div>
                        @if ($dispatch->notes)
                            <div>
                                <label class="text-sm font-medium text-gray-500">Catatan/Instruksi</label>
                                <div class="mt-1 text-sm text-gray-900 bg-yellow-50 border border-yellow-200 rounded p-3">
                                    {{ $dispatch->notes }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Timeline -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Timeline Kasus</h2>
                    <div class="flow-root">
                        <ul class="-mb-8">
                            @foreach ($case->caseEvents as $event)
                                <li>
                                    <div class="relative pb-8">
                                        @if (!$loop->last)
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"
                                                aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex space-x-3">
                                            <div>
                                                <span
                                                    class="h-8 w-8 rounded-full bg-red-500 flex items-center justify-center ring-8 ring-white">
                                                    <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900">{{ $event->action }}</p>
                                                    @if ($event->notes)
                                                        <p class="text-sm text-gray-500 mt-1">{{ $event->notes }}</p>
                                                    @endif
                                                </div>
                                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                    {{ $event->created_at->format('d M H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Action Panel -->
            <div class="lg:col-span-1">
                @if (in_array($case->status, ['DISPATCHED', 'ON_THE_WAY', 'ON_SCENE']))
                    <!-- Update Status Card -->
                    <div class="bg-white rounded-lg shadow sticky top-4">
                        <div class="px-6 py-4 border-b border-gray-200 bg-red-50">
                            <h2 class="text-lg font-semibold text-red-900">Update Status</h2>
                            <p class="text-sm text-red-700 mt-1">Perbarui status penanganan kasus</p>
                        </div>
                        <div class="px-6 py-4">
                            <form method="POST" action="{{ route('petugas.update-status', $case->id) }}"
                                class="space-y-4">
                                @csrf
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                        Status Baru <span class="text-red-500">*</span>
                                    </label>
                                    <select name="status" id="status" required
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                                        <option value="">-- Pilih Status --</option>
                                        @if ($case->status === 'DISPATCHED')
                                            <option value="ON_THE_WAY">🚗 Dalam Perjalanan</option>
                                        @endif
                                        @if (in_array($case->status, ['DISPATCHED', 'ON_THE_WAY']))
                                            <option value="ON_SCENE">📍 Tiba di Lokasi</option>
                                        @endif
                                        @if (in_array($case->status, ['ON_THE_WAY', 'ON_SCENE']))
                                            <option value="CLOSED">✅ Selesai Ditangani</option>
                                        @endif
                                    </select>
                                </div>

                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                        Catatan (Opsional)
                                    </label>
                                    <textarea name="notes" id="notes" rows="4"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                                        placeholder="Catatan tentang update status..."></textarea>
                                </div>

                                <button type="submit"
                                    class="w-full bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                    <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                    </svg>
                                    Update Status
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Case Completed -->
                    <div class="bg-white rounded-lg shadow sticky top-4">
                        <div class="px-6 py-4 border-b border-gray-200 bg-green-50">
                            <h2 class="text-lg font-semibold text-green-900">Kasus Selesai</h2>
                        </div>
                        <div class="px-6 py-4">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="flex-shrink-0">
                                    <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center">
                                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Status: {{ $case->status }}</p>
                                    @if ($case->closed_at)
                                        <p class="text-xs text-gray-500">Ditutup: {{ $case->closed_at->diffForHumans() }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Quick Actions -->
                <div class="mt-6 bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Informasi</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <p>Pastikan untuk mengupdate status secara berkala. Jika ada kendala, hubungi pimpinan unit
                                    Anda.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
@endpush

@push('scripts')
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        // Initialize map
        @if ($case->lat && $case->lon)
            const caseLat = {{ $case->lat }};
            const caseLon = {{ $case->lon }};

            // Create map centered on case location
            const map = L.map('map').setView([caseLat, caseLon], 15);

            // Add OpenStreetMap tile layer
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '© OpenStreetMap contributors'
            }).addTo(map);

            // Add marker for case location (RED)
            const caseMarker = L.marker([caseLat, caseLon], {
                icon: L.icon({
                    iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-red.png',
                    shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                    iconSize: [25, 41],
                    iconAnchor: [12, 41],
                    popupAnchor: [1, -34],
                    shadowSize: [41, 41]
                })
            }).addTo(map);

            caseMarker.bindPopup(`
                <div class="text-sm">
                    <strong>Lokasi Kejadian</strong><br>
                    ${@json($case->locator_text ?? 'N/A')}<br>
                    <span class="text-xs text-gray-500">${caseLat}, ${caseLon}</span>
                </div>
            `);

            // Track petugas location and add marker (BLUE)
            let petugasMarker = null;
            let locationUpdateInterval = null;

            function updatePetugasLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            const petugasLat = position.coords.latitude;
                            const petugasLon = position.coords.longitude;

                            // Update marker
                            if (petugasMarker) {
                                petugasMarker.setLatLng([petugasLat, petugasLon]);
                            } else {
                                petugasMarker = L.marker([petugasLat, petugasLon], {
                                    icon: L.icon({
                                        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
                                        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
                                        iconSize: [25, 41],
                                        iconAnchor: [12, 41],
                                        popupAnchor: [1, -34],
                                        shadowSize: [41, 41]
                                    })
                                }).addTo(map);

                                petugasMarker.bindPopup(`
                                    <div class="text-sm">
                                        <strong>Lokasi Anda</strong><br>
                                        <span class="text-xs text-gray-500">Diperbarui otomatis</span>
                                    </div>
                                `);
                            }

                            // Send location to server
                            fetch('{{ route('api.location.update') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({
                                    latitude: petugasLat,
                                    longitude: petugasLon
                                })
                            }).catch(error => console.error('Error updating location:', error));
                        },
                        function(error) {
                            console.error('Geolocation error:', error);
                        }, {
                            enableHighAccuracy: true,
                            maximumAge: 10000,
                            timeout: 5000
                        }
                    );
                }
            }

            // Initial update
            updatePetugasLocation();

            // Update every 30 seconds
            locationUpdateInterval = setInterval(updatePetugasLocation, 30000);

            // Cleanup on page unload
            window.addEventListener('beforeunload', function() {
                if (locationUpdateInterval) {
                    clearInterval(locationUpdateInterval);
                }
            });
        @endif
    </script>
@endpush
