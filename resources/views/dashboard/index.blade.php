@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard SafePoint</h1>
            <p class="text-gray-600">Ringkasan status kasus darurat dan monitoring lokasi</p>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-7 gap-4">
            <!-- Total Cases -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-gray-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 w-0 flex-1">
                            <dt class="text-sm font-medium text-gray-500 truncate">Total</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $totalCases }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- New Cases -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 w-0 flex-1">
                            <dt class="text-sm font-medium text-gray-500 truncate">Baru</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $statusCounts['NEW'] }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Verified Cases -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-400 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 w-0 flex-1">
                            <dt class="text-sm font-medium text-gray-500 truncate">Terverifikasi</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $statusCounts['VERIFIED'] }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dispatched Cases -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-300 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 w-0 flex-1">
                            <dt class="text-sm font-medium text-gray-500 truncate">Dikirim</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $statusCounts['DISPATCHED'] }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- On The Way Cases -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-orange-400 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 w-0 flex-1">
                            <dt class="text-sm font-medium text-gray-500 truncate">Perjalanan</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $statusCounts['ON_THE_WAY'] }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- On Scene Cases -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                    </path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 w-0 flex-1">
                            <dt class="text-sm font-medium text-gray-500 truncate">Di Lokasi</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $statusCounts['ON_SCENE'] }}</dd>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Closed Cases -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-gray-400 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3 w-0 flex-1">
                            <dt class="text-sm font-medium text-gray-500 truncate">Selesai</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $statusCounts['CLOSED'] }}</dd>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Map and Recent Cases -->
        <div class="space-y-6">
            <!-- Mapbox Map - Full Width -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                        Peta Kasus Aktif
                    </h3>
                    <div id="map" style="height: 500px;" class="rounded-lg"></div>
                </div>
            </div>

            <!-- Recent Cases - Full Width Card Layout -->
            <div class="bg-white shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">
                            10 Kasus Terbaru
                        </h3>
                        <a href="{{ route('cases.index') }}" class="text-red-600 hover:text-red-500 text-sm font-medium">
                            Lihat Semua →
                        </a>
                    </div>

                    @if($recentCases->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                            @foreach ($recentCases as $case)
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex items-start justify-between mb-3">
                                        <div>
                                            <a href="{{ route('cases.show', $case) }}" class="text-sm font-medium text-red-600 hover:text-red-500">
                                                {{ $case->short_id }}
                                            </a>
                                            <p class="text-xs text-gray-500">{{ $case->created_at->format('d/m H:i') }}</p>
                                        </div>
                                        <x-status-badge :status="$case->status" />
                                    </div>

                                    <div class="space-y-2">
                                        <div>
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                                {{ $case->category === 'MEDIS' ? 'bg-blue-100 text-blue-800' : '' }}
                                                {{ $case->category === 'KEBAKARAN' ? 'bg-red-100 text-red-800' : '' }}
                                                {{ $case->category === 'KRIMINAL' ? 'bg-purple-100 text-purple-800' : '' }}
                                                {{ $case->category === 'UMUM' ? 'bg-gray-100 text-gray-800' : '' }}
                                                {{ in_array($case->category, ['BENCANA_ALAM', 'BANJIR', 'POHON_TUMBANG']) ? 'bg-yellow-100 text-yellow-800' : '' }}
                                                {{ in_array($case->category, ['KECELAKAAN', 'KEBOCORAN_GAS']) ? 'bg-orange-100 text-orange-800' : '' }}">
                                                @switch($case->category)
                                                    @case('MEDIS') Medis @break
                                                    @case('KEBAKARAN') Kebakaran @break
                                                    @case('KRIMINAL') Kriminal @break
                                                    @case('UMUM') Umum @break
                                                    @case('BENCANA_ALAM') Bencana Alam @break
                                                    @case('KECELAKAAN') Kecelakaan @break
                                                    @case('KEBOCORAN_GAS') Kebocoran Gas @break
                                                    @case('POHON_TUMBANG') Pohon Tumbang @break
                                                    @case('BANJIR') Banjir @break
                                                    @default {{ $case->category }}
                                                @endswitch
                                            </span>
                                        </div>

                                        <p class="text-sm text-gray-900 font-medium line-clamp-2">
                                            {{ Str::limit($case->location ?: $case->locator_text, 50) }}
                                        </p>

                                        @if($case->assignedUnit)
                                            <div class="flex items-center text-xs text-gray-500">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                </svg>
                                                {{ $case->assignedUnit->name }}
                                            </div>
                                        @endif

                                        <div class="flex items-center justify-between pt-2">
                                            <span class="text-xs text-gray-500">
                                                {{ $case->created_at->diffForHumans() }}
                                            </span>
                                            <div class="flex space-x-2">
                                                <a href="{{ route('cases.show', $case) }}" 
                                                   class="text-xs text-red-600 hover:text-red-700 font-medium">
                                                    Detail
                                                </a>
                                                @if($case->lat && $case->lon)
                                                    <a href="https://www.google.com/maps?q={{ $case->lat }},{{ $case->lon }}" 
                                                       target="_blank" 
                                                       class="text-xs text-blue-600 hover:text-blue-700 font-medium">
                                                        Maps
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada kasus</h3>
                            <p class="mt-1 text-sm text-gray-500">Belum ada laporan kasus darurat.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Mapbox GL JS -->
    <script src='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js'></script>
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css' rel='stylesheet' />

    <script>
        // Initialize Mapbox
        mapboxgl.accessToken = 'pk.eyJ1IjoicGFsb24wMDUiLCJhIjoiY21jMjJ4MDJvMDR0bzJqc2ZtMmxrOW56OSJ9.YFif8g-Il5g5qdynTCXLbA';

        const map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/mapbox/streets-v11',
            center: [106.816666, -6.200000], // Jakarta center
            zoom: 11
        });

        // Active cases data
        const activeCases = @json($activeCases);

        // Add markers for active cases
        map.on('load', function() {
            // Add cases as markers
            activeCases.forEach(function(caseData) {
                if (caseData.lat && caseData.lon) {
                    // Create marker color based on status
                    let markerColor = '#ef4444'; // red-500 default
                    switch (caseData.status) {
                        case 'NEW':
                            markerColor = '#ef4444';
                            break; // red-500
                        case 'VERIFIED':
                            markerColor = '#f87171';
                            break; // red-400
                        case 'DISPATCHED':
                            markerColor = '#fca5a5';
                            break; // red-300
                        case 'ON_THE_WAY':
                            markerColor = '#fb923c';
                            break; // orange-400
                        case 'ON_SCENE':
                            markerColor = '#10b981';
                            break; // green-500
                    }

                    // Create popup content
                    const popupContent = `
                        <div class="p-2">
                            <h3 class="font-bold text-sm">${caseData.short_id}</h3>
                            <p class="text-sm text-gray-600">${caseData.category}</p>
                            <p class="text-xs text-gray-500">${caseData.location}</p>
                            <p class="text-xs text-gray-500">${new Date(caseData.created_at).toLocaleString('id-ID')}</p>
                            <div class="mt-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800">
                                    ${getStatusLabel(caseData.status)}
                                </span>
                            </div>
                        </div>
                    `;

                    // Create marker
                    const marker = new mapboxgl.Marker({
                            color: markerColor
                        })
                        .setLngLat([caseData.lon, caseData.lat])
                        .setPopup(new mapboxgl.Popup().setHTML(popupContent))
                        .addTo(map);
                }
            });

            // Fit map to show all markers if there are any
            if (activeCases.length > 0) {
                const coordinates = activeCases
                    .filter(c => c.lat && c.lon)
                    .map(c => [c.lon, c.lat]);

                if (coordinates.length > 0) {
                    const bounds = new mapboxgl.LngLatBounds();
                    coordinates.forEach(coord => bounds.extend(coord));
                    map.fitBounds(bounds, {
                        padding: 50
                    });
                }
            }
        });

        function getStatusLabel(status) {
            const labels = {
                'NEW': 'Baru',
                'VERIFIED': 'Terverifikasi',
                'DISPATCHED': 'Dikirim',
                'ON_THE_WAY': 'Dalam Perjalanan',
                'ON_SCENE': 'Di Lokasi',
                'CLOSED': 'Selesai',
                'CANCELLED': 'Dibatalkan'
            };
            return labels[status] || status;
        }
    </script>
@endsection
