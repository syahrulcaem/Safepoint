@extends('layouts.app')

@section('title', 'Detail Kasus - ' . $case->short_id)

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Detail Kasus</h1>
                    <p class="text-sm text-gray-600 mt-1">
                        Unit: <span class="font-semibold">{{ $unit->name }}</span> ({{ $unit->type }})
                    </p>
                </div>
                <a href="{{ route('pimpinan.dashboard') }}"
                    class="px-4 py-2 bg-gray-100 text-gray-700 text-sm font-medium rounded-md hover:bg-gray-200">
                    ← Kembali ke Dashboard
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Case Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Info Card -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-gray-900">Informasi Kasus</h2>
                            <span
                                class="px-3 py-1 text-sm font-medium rounded-full 
                                @switch($case->status)
                                    @case('NEW') bg-blue-100 text-blue-800 @break
                                    @case('DISPATCHED') @break
                                    @case('ON_THE_WAY') @break
                                    @case('ON_SCENE') @break
                                    @case('CLOSED') @break
                                    @case('CANCELLED') @break
                                    @default
                                @endswitch">
                                {{ $case->status }}
                            </span>
                        </div>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Case ID</label>
                            <p class="mt-1 text-sm font-mono text-gray-900">{{ $case->short_id }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-500">Kategori</label>
                            <p class="mt-1 text-sm text-gray-900">
                                <span class="px-2 py-1 bg-gray-100 rounded">{{ $case->category }}</span>
                            </p>
                        </div>

                        <div class="col-span-2">
                            <label class="text-sm font-medium text-gray-500">Lokasi</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $case->locator_text ?? 'N/A' }}
                            </p>
                            @if ($case->lat && $case->lon)
                                <p class="mt-1 text-xs text-gray-500">
                                    Koordinat: {{ $case->lat }}, {{ $case->lon }}
                                    (Akurasi: {{ $case->accuracy ?? 'N/A' }}m)
                                </p>

                                <!-- Map Display -->
                                <div id="map" class="w-full h-64 rounded-lg border border-gray-300 mt-3"></div>

                                <a href="https://www.google.com/maps?q={{ $case->lat }},{{ $case->lon }}"
                                    target="_blank"
                                    class="mt-2 inline-flex items-center text-xs text-red-600 hover:text-red-800">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Buka di Google Maps
                                </a>
                            @endif
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-500">Nomor Telepon</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $case->phone ?? 'N/A' }}</p>
                        </div>

                        @if ($case->reporterUser)
                            <div>
                                <label class="text-sm font-medium text-gray-500">Pelapor</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $case->reporterUser->name }}</p>
                            </div>
                        @endif

                        <div>
                            <label class="text-sm font-medium text-gray-500">Waktu Laporan</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $case->created_at->format('d F Y, H:i') }} WIB
                                <span class="text-gray-500">({{ $case->created_at->diffForHumans() }})</span>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Dispatch Info Card -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Informasi Dispatch</h2>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500">Operator yang Dispatch</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $dispatch->dispatcher->name }}</p>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-500">Waktu Dispatch</label>
                            <p class="mt-1 text-sm text-gray-900">
                                {{ $dispatch->dispatched_at->format('d F Y, H:i') }} WIB
                                <span class="text-gray-500">({{ $dispatch->dispatched_at->diffForHumans() }})</span>
                            </p>
                        </div>

                        @if ($dispatch->notes)
                            <div>
                                <label class="text-sm font-medium text-gray-500">Catatan dari Operator</label>
                                <p class="mt-1 text-sm text-gray-900 bg-yellow-50 border border-yellow-200 rounded p-3">
                                    {{ $dispatch->notes }}
                                </p>
                            </div>
                        @endif

                        @if ($dispatch->assigned_petugas_id)
                            <div>
                                <label class="text-sm font-medium text-gray-500">Petugas yang Ditugaskan</label>
                                <p class="mt-1 text-sm text-gray-900">
                                    {{ $dispatch->assignedPetugas->name }}
                                    <span class="text-gray-500">
                                        - Ditugaskan {{ $dispatch->assigned_at->diffForHumans() }}
                                    </span>
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Timeline -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-900">Timeline</h2>
                    </div>
                    <div class="px-6 py-4">
                        <div class="flow-root">
                            <ul class="-mb-8">
                                @foreach ($case->caseEvents as $index => $event)
                                    <li>
                                        <div class="relative pb-8">
                                            @if (!$loop->last)
                                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200"
                                                    aria-hidden="true"></span>
                                            @endif
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span
                                                        class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                                        <svg class="h-5 w-5 text-white" fill="currentColor"
                                                            viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd"
                                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900">{{ $event->action }}
                                                        </p>
                                                        @if ($event->notes)
                                                            <p class="text-sm text-gray-500 mt-1">{{ $event->notes }}</p>
                                                        @endif
                                                    </div>
                                                    <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                        {{ $event->created_at->format('H:i') }}
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
            </div>

            <!-- Action Panel -->
            <div class="lg:col-span-1">
                @if (!$dispatch->assigned_petugas_id)
                    <!-- Assign Petugas Card -->
                    <div class="bg-white rounded-lg shadow sticky top-4">
                        <div class="px-6 py-4 border-b border-gray-200 bg-red-50">
                            <h2 class="text-lg font-semibold text-red-900">Tugaskan Petugas</h2>
                            <p class="text-sm text-red-700 mt-1">Pilih petugas untuk menangani kasus ini</p>
                        </div>
                        <div class="px-6 py-4">
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
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm @error('assigned_petugas_id') border-red-300 @enderror">
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
                                            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm @error('notes') border-red-300 @enderror"
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
                    <div class="bg-white rounded-lg shadow sticky top-4">
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
@endsection


@push('styles')
    <!-- Mapbox GL JS CSS -->
    <link href='https://api.mapbox.com/mapbox-gl-js/v3.0.1/mapbox-gl.css' rel='stylesheet' />
@endpush

@push('scripts')
    <!-- Mapbox GL JS -->
    <script src='https://api.mapbox.com/mapbox-gl-js/v3.0.1/mapbox-gl.js'></script>
    <script>
        @if ($case->lat && $case->lon)
            mapboxgl.accessToken = @json(config('services.mapbox.access_token'));
            const caseLat = {{ $case->lat }};
            const caseLon = {{ $case->lon }};
            const map = new mapboxgl.Map({
                container: 'map',
                style: 'mapbox://styles/mapbox/streets-v12',
                center: [caseLon, caseLat],
                zoom: 15
            });

            // Add marker for case location (RED)
            const caseMarker = new mapboxgl.Marker({
                    color: 'red'
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

            @if ($dispatch && $dispatch->petugas && $dispatch->petugas->last_latitude && $dispatch->petugas->last_longitude)
                let petugasLat = {{ $dispatch->petugas->last_latitude }};
                let petugasLon = {{ $dispatch->petugas->last_longitude }};
                const petugasName = @json($dispatch->petugas->name);
                let petugasMarker = new mapboxgl.Marker({
                        color: 'blue'
                    })
                    .setLngLat([petugasLon, petugasLat])
                    .setPopup(new mapboxgl.Popup().setHTML(`
                        <div class='text-sm'>
                            <strong>${petugasName}</strong><br>
                            <span class='text-xs text-gray-500'>Lokasi Petugas</span>
                        </div>
                    `))
                    .addTo(map);

                // Fit map to show both markers
                const bounds = new mapboxgl.LngLatBounds();
                bounds.extend([caseLon, caseLat]);
                bounds.extend([petugasLon, petugasLat]);
                map.fitBounds(bounds, {
                    padding: 50
                });

                // Auto-refresh petugas location every 30 seconds
                setInterval(function() {
                    fetch(`/api/location/user/{{ $dispatch->petugas->id }}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.data.latitude && data.data.longitude) {
                                petugasLat = data.data.latitude;
                                petugasLon = data.data.longitude;
                                petugasMarker.setLngLat([petugasLon, petugasLat]);
                            }
                        })
                        .catch(error => console.error('Error fetching petugas location:', error));
                }, 30000);
            @endif
        @endif
    </script>
@endpush
