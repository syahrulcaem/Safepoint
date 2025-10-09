@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-sm-0 font-size-18">Detail Kasus - {{ $case->short_id }}</h4>
                        <p class="text-muted mb-0">Unit: <span class="fw-medium">{{ $unit->name }}</span>
                            ({{ $unit->type }})</p>
                    </div>
                    <div class="page-title-right">
                        <a href="{{ route('pimpinan.dashboard') }}" class="btn btn-secondary btn-sm">
                            <i class="bx bx-arrow-back me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Case Information -->
            <div class="col-lg-8">
                <!-- Basic Info Card -->
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h5 class="card-title mb-0">Informasi Kasus</h5>
                            <span
                                class="badge rounded-pill 
                                @switch($case->status)
                                    @case('NEW') bg-info @break
                                    @case('DISPATCHED') bg-warning @break
                                    @case('ON_THE_WAY') bg-primary @break
                                    @case('ON_SCENE') bg-secondary @break
                                    @case('CLOSED') bg-success @break
                                    @case('CANCELLED') bg-danger @break
                                    @default bg-secondary
                                @endswitch">
                                {{ $case->status }}
                            </span>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted fw-medium">Case ID</label>
                                <p class="mb-0"><code>{{ $case->short_id }}</code></p>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted fw-medium">Kategori</label>
                                <p class="mb-0">
                                    <span class="badge bg-light text-dark">{{ $case->category }}</span>
                                </p>
                            </div>

                            <div class="col-12">
                                <label class="form-label text-muted fw-medium">Lokasi</label>
                                <p class="mb-1">{{ $case->locator_text ?? 'N/A' }}</p>
                                @if ($case->lat && $case->lon)
                                    <p class="text-muted small mb-2">
                                        Koordinat: {{ $case->lat }}, {{ $case->lon }}
                                        (Akurasi: {{ $case->accuracy ?? 'N/A' }}m)
                                    </p>

                                    <!-- Map Display -->
                                    <div id="map" style="width: 100%; height: 300px;" class="rounded border mt-2">
                                    </div>

                                    <a href="https://www.google.com/maps?q={{ $case->lat }},{{ $case->lon }}"
                                        target="_blank" class="btn btn-sm btn-outline-danger mt-2">
                                        <i class="bx bx-map me-1"></i>
                                        Buka di Google Maps
                                    </a>
                                @endif
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted fw-medium">Nomor Telepon</label>
                                <p class="mb-0">{{ $case->phone ?? 'N/A' }}</p>
                            </div>

                            @if ($case->reporterUser)
                                <div class="col-md-6">
                                    <label class="form-label text-muted fw-medium">Pelapor</label>
                                    <p class="mb-0">{{ $case->reporterUser->name }}</p>
                                </div>
                            @endif

                            <div class="col-12">
                                <label class="form-label text-muted fw-medium">Waktu Laporan</label>
                                <p class="mb-0">
                                    {{ $case->created_at->format('d F Y, H:i') }} WIB
                                    <span class="text-muted">({{ $case->created_at->diffForHumans() }})</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Dispatch Info Card -->
                <div class="card mt-3">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Informasi Dispatch</h5>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted fw-medium">Operator yang Dispatch</label>
                                <p class="mb-0">{{ $dispatch->dispatcher->name }}</p>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted fw-medium">Waktu Dispatch</label>
                                <p class="mb-0">
                                    {{ $dispatch->dispatched_at->format('d F Y, H:i') }} WIB
                                    <span class="text-muted">({{ $dispatch->dispatched_at->diffForHumans() }})</span>
                                </p>
                            </div>

                            @if ($dispatch->notes)
                                <div class="col-12">
                                    <label class="form-label text-muted fw-medium">Catatan dari Operator</label>
                                    <div class="alert alert-warning mb-0">
                                        {{ $dispatch->notes }}
                                    </div>
                                </div>
                            @endif

                            @if ($dispatch->assigned_petugas_id)
                                <div class="col-12">
                                    <label class="form-label text-muted fw-medium">Petugas yang Ditugaskan</label>
                                    <p class="mb-0">
                                        {{ $dispatch->assignedPetugas->name }}
                                        <span class="text-muted">
                                            - Ditugaskan {{ $dispatch->assigned_at->diffForHumans() }}
                                        </span>
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="card mt-3">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Timeline</h5>
                        
                        @if($case->caseEvents->count() > 0)
                            <div class="timeline">
                                @foreach ($case->caseEvents as $event)
                                    <div class="timeline-item">
                                        <div class="timeline-dot bg-danger"></div>
                                        <div class="timeline-content">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <h6 class="mb-1">{{ $event->action }}</h6>
                                                    @if ($event->notes)
                                                        <p class="text-muted small mb-0">{{ $event->notes }}</p>
                                                    @endif
                                                </div>
                                                <span class="badge bg-light text-dark">
                                                    {{ $event->created_at->format('H:i') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted text-center mb-0">Belum ada timeline</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Panel -->
            <div class="col-lg-4">
                @if (!$dispatch->assigned_petugas_id)
                    <!-- Assign Petugas Card -->
                    <div class="card">
                        <div class="card-header bg-danger text-white">
                            <h5 class="card-title text-white mb-1">Tugaskan Petugas</h5>
                            <p class="mb-0 small">Pilih petugas untuk menangani kasus ini</p>
                        </div>
                        <div class="card-body">
                            <!-- Success/Error Messages -->
                            @if (session('success'))
                                <div class="mb-4 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-md">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ session('success') }}
                                    </div>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-md">
                                    <div class="flex items-center">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd"
                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        {{ session('error') }}
                                    </div>
                                </div>
                            @endif

                            @if ($errors->any())
                                <div class="mb-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-md">
                                    <div class="font-medium mb-2">Terjadi kesalahan:</div>
                                    <ul class="list-disc list-inside space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if ($availablePetugas->isEmpty())
                                <div class="text-center py-8">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <p class="mt-2 text-sm text-gray-500">Tidak ada petugas di unit ini. <a
                                            href="{{ route('pimpinan.petugas') }}" class="text-red-600 underline">Tambah
                                            petugas</a></p>
                                </div>
                            @else
                                <form method="POST" action="{{ route('pimpinan.assign', $dispatch->id) }}"
                                    class="space-y-4">
                                    @csrf
                                    <div>
                                        <label for="assigned_petugas_id"
                                            class="block text-sm font-medium text-gray-700 mb-2">
                                            Pilih Petugas <span class="text-red-500">*</span>
                                        </label>
                                        <select name="assigned_petugas_id" id="assigned_petugas_id" required
                                            class="block w-full rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm @error('assigned_petugas_id') border-red-300 @enderror">
                                            <option value="">-- Pilih Petugas --</option>
                                            @foreach ($availablePetugas as $petugas)
                                                <option value="{{ $petugas->id }}"
                                                    {{ old('assigned_petugas_id') == $petugas->id ? 'selected' : '' }}>
                                                    {{ $petugas->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('assigned_petugas_id')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                            Catatan untuk Petugas (Opsional)
                                        </label>
                                        <textarea name="notes" id="notes" rows="4"
                                            class="block w-full rounded-md shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm @error('notes') border-red-300 @enderror"
                                            placeholder="Instruksi khusus untuk petugas...">{{ old('notes', $dispatch->notes) }}</textarea>
                                        @error('notes')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <button type="submit"
                                        class="w-full bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        Tugaskan Petugas
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @else
                    <!-- Petugas Already Assigned -->
                    <div class="bg-white rounded-lg shadow sticky top-4 space-y-4">
                        <div class="px-6 py-4 border-b border-gray-200 bg-green-50">
                            <h2 class="text-lg font-semibold text-green-900">Petugas Ditugaskan</h2>
                        </div>
                        <div class="px-6 py-4">
                            <div class="flex items-center space-x-3 mb-4">
                                <div class="flex-shrink-0">
                                    <div class="h-12 w-12 rounded-full bg-green-100 flex items-center justify-center">
                                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $dispatch->assignedPetugas->name }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $dispatch->assignedPetugas->phone }}</p>
                                </div>
                            </div>
                            <div class="bg-green-50 border border-green-200 rounded-md p-3">
                                <p class="text-xs text-green-800">
                                    <strong>Ditugaskan:</strong> {{ $dispatch->assigned_at->format('d F Y, H:i') }} WIB
                                </p>
                            </div>

                            @if ($dispatch->assignedPetugas->last_latitude && $dispatch->assignedPetugas->last_longitude)
                                <!-- Route Information -->
                                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg" id="routeInfo">
                                    <div class="flex items-center mb-3">
                                        <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7">
                                            </path>
                                        </svg>
                                        <h3 class="text-sm font-semibold text-blue-900">Informasi Rute</h3>
                                    </div>

                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-blue-700">Jarak:</span>
                                            <span class="text-sm font-bold text-blue-900" id="routeDistance">
                                                <svg class="inline w-4 h-4 animate-spin" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>
                                                Loading...
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs text-blue-700">Estimasi Waktu:</span>
                                            <span class="text-sm font-bold text-blue-900" id="routeDuration">
                                                <svg class="inline w-4 h-4 animate-spin" fill="none"
                                                    stroke="currentColor" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4"></circle>
                                                    <path class="opacity-75" fill="currentColor"
                                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                    </path>
                                                </svg>
                                                Loading...
                                            </span>
                                        </div>
                                        <div class="pt-2 border-t border-blue-200">
                                            <p class="text-xs text-blue-600 italic">
                                                <svg class="inline w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd"
                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                        clip-rule="evenodd"></path>
                                                </svg>
                                                Update otomatis setiap 30 detik
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Last Location Update -->
                                <div class="mt-4 text-xs text-gray-500 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Update terakhir:
                                    @if ($dispatch->assignedPetugas->last_location_update)
                                        {{ \Carbon\Carbon::parse($dispatch->assignedPetugas->last_location_update)->diffForHumans() }}
                                    @else
                                        Tidak ada data
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Info Box -->
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
                                <p>Kasus ini di-dispatch ke unit Anda oleh operator. Segera tugaskan petugas untuk menangani
                                    kasus ini.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mapbox GL JS -->
    <script src='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js'></script>
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css' rel='stylesheet' />

    <script>
        console.log('=== MAPBOX DEBUG START ===');
        console.log('Mapbox Token:',
            'pk.eyJ1IjoicGFsb24wMDUiLCJhIjoiY21jMjJ4MDJvMDR0bzJqc2ZtMmxrOW56OSJ9.YFif8g-Il5g5qdynTCXLbA');
        console.log('Case Lat/Lon:', {{ $case->lat ?? 'null' }}, {{ $case->lon ?? 'null' }});

        @if ($dispatch && $dispatch->assignedPetugas)
            console.log('Assigned Petugas:', {
                id: {{ $dispatch->assignedPetugas->id ?? 'null' }},
                name: @json($dispatch->assignedPetugas->name ?? 'N/A'),
                lat: {{ $dispatch->assignedPetugas->last_latitude ?? 'null' }},
                lon: {{ $dispatch->assignedPetugas->last_longitude ?? 'null' }},
                last_update: @json($dispatch->assignedPetugas->last_location_update ?? 'N/A')
            });
        @else
            console.log('No assigned petugas or petugas relation not loaded');
        @endif
        console.log('=== MAPBOX DEBUG END ===');

        @if ($case->lat && $case->lon)
            mapboxgl.accessToken =
                'pk.eyJ1IjoicGFsb24wMDUiLCJhIjoiY21jMjJ4MDJvMDR0bzJqc2ZtMmxrOW56OSJ9.YFif8g-Il5g5qdynTCXLbA';

            if (!mapboxgl.accessToken) {
                console.error('❌ Mapbox token is not configured!');
            }

            const caseLat = {{ $case->lat }};
            const caseLon = {{ $case->lon }};

            const map = new mapboxgl.Map({
                container: 'map',
                style: 'mapbox://styles/mapbox/streets-v11',
                center: [caseLon, caseLat],
                zoom: 15
            });

            map.on('load', function() {
                console.log('✅ Map loaded successfully');
            });

            map.on('error', function(e) {
                console.error('❌ Map error:', e);
            });

            // Add marker for case location (RED)
            const caseMarker = new mapboxgl.Marker({
                    color: '#EF4444' // red-500
                })
                .setLngLat([caseLon, caseLat])
                .setPopup(new mapboxgl.Popup().setHTML(`
                    <div class='text-sm'>
                        <strong>Lokasi Kejadian</strong><br>
                        {{ $case->locator_text ?? 'N/A' }}<br>
                        <span class='text-xs text-gray-500'>${caseLat}, ${caseLon}</span>
                    </div>
                `))
                .addTo(map);
            caseMarker.togglePopup();

            @if (
                $dispatch &&
                    $dispatch->assignedPetugas &&
                    $dispatch->assignedPetugas->last_latitude &&
                    $dispatch->assignedPetugas->last_longitude)
                console.log('✅ Adding petugas marker...');

                let petugasLat = {{ $dispatch->assignedPetugas->last_latitude }};
                let petugasLon = {{ $dispatch->assignedPetugas->last_longitude }};
                const petugasName = @json($dispatch->assignedPetugas->name);
                const petugasId = {{ $dispatch->assignedPetugas->id }};

                let petugasMarker = new mapboxgl.Marker({
                        color: '#3B82F6' // blue-500
                    })
                    .setLngLat([petugasLon, petugasLat])
                    .setPopup(new mapboxgl.Popup().setHTML(`
                        <div class='text-sm'>
                            <strong>${petugasName}</strong><br>
                            <span class='text-xs text-gray-500'>Lokasi Petugas</span><br>
                            <span class='text-xs text-gray-400'>${petugasLat.toFixed(6)}, ${petugasLon.toFixed(6)}</span>
                        </div>
                    `))
                    .addTo(map);

                console.log('✅ Petugas marker added at:', petugasLat, petugasLon);

                // Fit map to show both markers
                const bounds = new mapboxgl.LngLatBounds();
                bounds.extend([caseLon, caseLat]);
                bounds.extend([petugasLon, petugasLat]);
                map.fitBounds(bounds, {
                    padding: 80,
                    maxZoom: 15
                });

                // Function to fetch and draw route
                function drawRoute() {
                    console.log('🗺️ Fetching route from Mapbox Directions API...');

                    // Update UI to loading state
                    const distanceEl = document.getElementById('routeDistance');
                    const durationEl = document.getElementById('routeDuration');

                    if (distanceEl && distanceEl.textContent !== 'Loading...') {
                        distanceEl.innerHTML =
                            '<svg class="inline w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Loading...';
                    }
                    if (durationEl && durationEl.textContent !== 'Loading...') {
                        durationEl.innerHTML =
                            '<svg class="inline w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Loading...';
                    }

                    const directionsUrl =
                        `https://api.mapbox.com/directions/v5/mapbox/driving/${petugasLon},${petugasLat};${caseLon},${caseLat}?geometries=geojson&access_token=${mapboxgl.accessToken}`;

                    fetch(directionsUrl)
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`HTTP ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('🛣️ Directions API response:', data);

                            if (data.routes && data.routes.length > 0) {
                                const route = data.routes[0];
                                const distance = (route.distance / 1000).toFixed(2); // km
                                const duration = Math.round(route.duration / 60); // minutes

                                console.log(`✅ Route found: ${distance} km, ${duration} minutes`);

                                // Remove old route layer if exists
                                if (map.getLayer('route')) {
                                    map.removeLayer('route');
                                    map.removeSource('route');
                                }

                                // Add route layer
                                map.addSource('route', {
                                    type: 'geojson',
                                    data: {
                                        type: 'Feature',
                                        properties: {},
                                        geometry: route.geometry
                                    }
                                });

                                map.addLayer({
                                    id: 'route',
                                    type: 'line',
                                    source: 'route',
                                    layout: {
                                        'line-join': 'round',
                                        'line-cap': 'round'
                                    },
                                    paint: {
                                        'line-color': '#3B82F6', // blue-500
                                        'line-width': 4,
                                        'line-opacity': 0.75
                                    }
                                });

                                // Update petugas popup with distance & ETA
                                petugasMarker.setPopup(
                                    new mapboxgl.Popup().setHTML(`
                                        <div class='text-sm'>
                                            <strong>${petugasName}</strong><br>
                                            <span class='text-xs text-gray-500'>Lokasi Petugas</span><br>
                                            <span class='text-xs text-gray-400'>${petugasLat.toFixed(6)}, ${petugasLon.toFixed(6)}</span><br>
                                            <div class='mt-2 pt-2 border-t border-gray-200'>
                                                <div class='flex items-center gap-1'>
                                                    <svg class='w-3 h-3 text-blue-500' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M13 7h8m0 0v8m0-8l-8 8-4-4-6 6'></path>
                                                    </svg>
                                                    <span class='text-xs font-medium text-blue-600'>${distance} km</span>
                                                </div>
                                                <div class='flex items-center gap-1 mt-1'>
                                                    <svg class='w-3 h-3 text-blue-500' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                                                        <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'></path>
                                                    </svg>
                                                    <span class='text-xs font-medium text-blue-600'>~${duration} menit</span>
                                                </div>
                                            </div>
                                        </div>
                                    `)
                                );

                                // Update sidebar info panel
                                const distanceEl = document.getElementById('routeDistance');
                                const durationEl = document.getElementById('routeDuration');

                                if (distanceEl) {
                                    distanceEl.innerHTML = `
                                        <svg class="inline w-4 h-4 mr-1 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                        </svg>
                                        ${distance} km
                                    `;
                                }

                                if (durationEl) {
                                    durationEl.innerHTML = `
                                        <svg class="inline w-4 h-4 mr-1 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        ~${duration} menit
                                    `;
                                }
                            } else {
                                console.warn('⚠️ No route found');

                                // Show error in sidebar
                                const distanceEl = document.getElementById('routeDistance');
                                const durationEl = document.getElementById('routeDuration');

                                if (distanceEl) distanceEl.textContent = 'N/A';
                                if (durationEl) durationEl.textContent = 'N/A';
                            }
                        })
                        .catch(error => {
                            console.error('❌ Error fetching directions:', error);

                            // Show error in sidebar
                            const distanceEl = document.getElementById('routeDistance');
                            const durationEl = document.getElementById('routeDuration');

                            if (distanceEl) distanceEl.textContent = 'Error';
                            if (durationEl) durationEl.textContent = 'Error';
                        });
                }

                // Draw route - multiple triggers to ensure it runs
                let routeDrawn = false;

                // Try 1: On map load
                map.on('load', function() {
                    console.log('✅ Map loaded, drawing route (trigger 1)...');
                    setTimeout(function() {
                        if (!routeDrawn) {
                            drawRoute();
                            routeDrawn = true;
                        }
                    }, 500);
                });

                // Try 2: After 2 seconds (fallback)
                setTimeout(function() {
                    if (!routeDrawn) {
                        console.log('⚠️ Drawing route via timeout fallback (trigger 2)...');
                        drawRoute();
                        routeDrawn = true;
                    }
                }, 2000);

                // Try 3: After 4 seconds (second fallback)
                setTimeout(function() {
                    if (!routeDrawn) {
                        console.log('⚠️ Drawing route via second fallback (trigger 3)...');
                        drawRoute();
                        routeDrawn = true;
                    }
                }, 4000);

                // Auto-refresh petugas location and route every 30 seconds
                setInterval(function() {
                    console.log('🔄 Fetching updated petugas location for ID:', petugasId);

                    fetch(`/api/location/user/${petugasId}`)
                        .then(response => {
                            console.log('📡 Location API response status:', response.status);
                            return response.json();
                        })
                        .then(data => {
                            console.log('📍 Location API data:', data);

                            if (data.success && data.data.latitude && data.data.longitude) {
                                const oldLat = petugasLat;
                                const oldLon = petugasLon;

                                petugasLat = data.data.latitude;
                                petugasLon = data.data.longitude;

                                // Only update if location changed significantly (> 10 meters)
                                const distance = Math.sqrt(
                                    Math.pow((petugasLat - oldLat) * 111000, 2) +
                                    Math.pow((petugasLon - oldLon) * 111000, 2)
                                );

                                if (distance > 10) {
                                    petugasMarker.setLngLat([petugasLon, petugasLat]);
                                    console.log('✅ Petugas marker updated to:', petugasLat, petugasLon,
                                        `(moved ${distance.toFixed(0)}m)`);

                                    // Redraw route
                                    drawRoute();
                                } else {
                                    console.log('⏸️ Location unchanged (< 10m)');
                                }
                            } else {
                                console.warn('⚠️ No valid location data received');
                            }
                        })
                        .catch(error => {
                            console.error('❌ Error fetching petugas location:', error);
                        });
                }, 30000); // 30 seconds
            @else
                console.log('⚠️ Petugas marker NOT added - petugas not assigned or no location data');
            @endif
        @else
            console.error('❌ Cannot initialize map - case lat/lon missing');
        @endif
    </script>
@endsection
