@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Dashboard SafePoint</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <!-- Total Cases -->
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Total</p>
                                <h4 class="mb-0">{{ $totalCases }}</h4>
                            </div>
                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle" style="background: linear-gradient(45deg, #dc3545 0%, #c82333 100%) !important;">
                                    <span class="avatar-title" style="background: linear-gradient(45deg, #dc3545 0%, #c82333 100%) !important;">
                                        <i class="bx bx-bar-chart-alt-2 font-size-20 text-white"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- New Cases -->
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Baru</p>
                                <h4 class="mb-0">{{ $statusCounts['NEW'] }}</h4>
                            </div>
                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle" style="background: linear-gradient(45deg, #dc3545 0%, #b71c1c 100%) !important;">
                                    <span class="avatar-title" style="background: linear-gradient(45deg, #dc3545 0%, #b71c1c 100%) !important;">
                                        <i class="bx bx-plus font-size-20 text-white"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Dispatched Cases -->
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Dikirim</p>
                                <h4 class="mb-0">{{ $statusCounts['DISPATCHED'] }}</h4>
                            </div>
                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle" style="background: linear-gradient(45deg, #dc3545 0%, #b71c1c 100%) !important;">
                                    <span class="avatar-title" style="background: linear-gradient(45deg, #dc3545 0%, #b71c1c 100%) !important;">
                                        <i class="bx bx-time font-size-20 text-white"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- On The Way Cases -->
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Perjalanan</p>
                                <h4 class="mb-0">{{ $statusCounts['ON_THE_WAY'] }}</h4>
                            </div>
                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle" style="background: linear-gradient(45deg, #dc3545 0%, #b71c1c 100%) !important;">
                                    <span class="avatar-title" style="background: linear-gradient(45deg, #dc3545 0%, #b71c1c 100%) !important;">
                                        <i class="bx bx-car font-size-20 text-white"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- On Scene Cases -->
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Di Lokasi</p>
                                <h4 class="mb-0">{{ $statusCounts['ON_SCENE'] }}</h4>
                            </div>
                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle" style="background: linear-gradient(45deg, #e53e3e 0%, #c53030 100%) !important;">
                                    <span class="avatar-title" style="background: linear-gradient(45deg, #e53e3e 0%, #c53030 100%) !important;">
                                        <i class="bx bx-map-pin font-size-20 text-white"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Closed Cases -->
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Selesai</p>
                                <h4 class="mb-0">{{ $statusCounts['CLOSED'] }}</h4>
                            </div>
                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle" style="background: linear-gradient(45deg, #dc3545 0%, #ad1a1a 100%) !important;">
                                    <span class="avatar-title" style="background: linear-gradient(45deg, #dc3545 0%, #ad1a1a 100%) !important;">
                                        <i class="bx bx-check font-size-20 text-white"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Map and Recent Cases -->
        <div class="row">
            <!-- Mapbox Map - Full Width -->
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h4 class="card-title">Peta Kasus Aktif</h4>
                        </div>
                        <div id="map" style="height: 500px;" class="rounded position-relative">
                            <!-- Grid Info Panel -->
                            <div id="grid-info" class="position-absolute"
                                style="bottom: 15px; left: 15px; background: white; padding: 10px; border-radius: 6px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); border: 1px solid #e9ecef; font-size: 12px; color: #6c757d; z-index: 10; display: none;">
                                <div class="fw-medium text-danger mb-1">Grid 3x3 Meter</div>
                                <div>Setiap kotak = ~3m x 3m</div>
                                <div>Zoom in untuk detail lebih presisi</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Cases - Scrollable Table -->
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <h4 class="card-title">5 Kasus Terbaru</h4>
                            <a href="{{ route('cases.index') }}" class="btn btn-sm btn-primary">
                                Lihat Semua <i class="mdi mdi-arrow-right ms-1"></i>
                            </a>
                        </div>

                        @if ($recentCases->count() > 0)
                            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                                <table class="table table-nowrap mb-0">
                                    <thead class="table-light sticky-top">
                                        <tr>
                                            <th scope="col">ID Kasus</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Kategori</th>
                                            <th scope="col">Lokasi</th>
                                            <th scope="col">Unit</th>
                                            <th scope="col">Waktu</th>
                                            <th scope="col">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($recentCases as $case)
                                            <tr>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <a href="{{ route('cases.show', $case) }}" class="text-primary fw-medium">
                                                            {{ $case->short_id }}
                                                        </a>
                                                        <small class="text-muted">
                                                            {{ $case->created_at->format('d/m H:i') }}
                                                        </small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <x-status-badge :status="$case->status" />
                                                </td>
                                                <td>
                                                    <span class="badge 
                                                        @switch($case->category)
                                                            @case('MEDIS') badge-soft-primary @break
                                                            @case('KEBAKARAN') badge-soft-danger @break
                                                            @case('KRIMINAL') badge-soft-dark @break
                                                            @case('UMUM') badge-soft-secondary @break
                                                            @case('BENCANA_ALAM') @case('BANJIR') @case('POHON_TUMBANG') badge-soft-warning @break
                                                            @case('KECELAKAAN') @case('KEBOCORAN_GAS') badge-soft-info @break
                                                            @default badge-soft-secondary
                                                        @endswitch
                                                    ">
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
                                                </td>
                                                <td>
                                                    <div class="text-truncate" style="max-width: 200px;" title="{{ $case->location ?: $case->locator_text }}">
                                                        {{ Str::limit($case->location ?: $case->locator_text, 40) }}
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($case->assignedUnit)
                                                        <div class="d-flex align-items-center">
                                                            <i class="bx bx-group me-1 text-muted"></i>
                                                            <span class="text-truncate" style="max-width: 100px;" title="{{ $case->assignedUnit->name }}">
                                                                {{ Str::limit($case->assignedUnit->name, 15) }}
                                                            </span>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <small class="text-muted">{{ $case->created_at->diffForHumans() }}</small>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <button onclick="showCaseDetail('{{ $case->id }}')" class="btn btn-sm btn-outline-primary">
                                                            Detail
                                                        </button>
                                                        @if ($case->lat && $case->lon)
                                                            <a href="https://www.google.com/maps?q={{ $case->lat }},{{ $case->lon }}"
                                                                target="_blank" class="btn btn-sm btn-outline-info">
                                                                Maps
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="avatar-md mx-auto mb-4">
                                    <div class="avatar-title bg-light rounded-circle">
                                        <i class="bx bx-file font-size-24 text-primary"></i>
                                    </div>
                                </div>
                                <h5 class="font-size-15">Tidak ada kasus</h5>
                                <p class="text-muted">Belum ada laporan kasus darurat.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Case Detail Modal -->
    <div class="modal fade" id="caseDetailModal" tabindex="-1" aria-labelledby="caseDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="caseDetailModalLabel">Detail Kasus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="caseDetailContent">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">Memuat detail kasus...</p>
                    </div>
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
        const activeCases = {!! json_encode($activeCases) !!};

        // Grid toggle functionality
        let gridVisible = false;
        let gridLayer = null;

        // Add grid toggle button
        const gridToggle = document.createElement('button');
        gridToggle.innerHTML = `
            <i class="bx bx-grid-alt me-1"></i>
            <span class="small">Grid 3x3m</span>
        `;
        gridToggle.className = 'btn btn-sm btn-outline-primary position-absolute';
        gridToggle.style.top = '10px';
        gridToggle.style.right = '10px';
        gridToggle.style.zIndex = '1000';

        // Add button to map container
        document.getElementById('map').appendChild(gridToggle);

        // Function to create 3x3 meter grid
        function create3mGrid(bounds) {
            const features = [];
            const zoom = map.getZoom();

            // Adjust grid density based on zoom level
            let gridSpacing;
            if (zoom >= 18) {
                gridSpacing = 0.000027; // ~3m - very detailed
            } else if (zoom >= 16) {
                gridSpacing = 0.000054; // ~6m - medium detail
            } else if (zoom >= 14) {
                gridSpacing = 0.000108; // ~12m - less detail
            } else {
                gridSpacing = 0.000216; // ~24m - minimal detail
            }

            const west = bounds.getWest();
            const east = bounds.getEast();
            const north = bounds.getNorth();
            const south = bounds.getSouth();

            // Limit grid generation to prevent performance issues
            const maxLines = 200;
            const verticalLines = Math.min(Math.ceil((east - west) / gridSpacing), maxLines);
            const horizontalLines = Math.min(Math.ceil((north - south) / gridSpacing), maxLines);

            // Create vertical lines
            for (let i = 0; i <= verticalLines; i++) {
                const lng = west + (i * gridSpacing);
                if (lng <= east) {
                    features.push({
                        type: 'Feature',
                        geometry: {
                            type: 'LineString',
                            coordinates: [
                                [lng, south],
                                [lng, north]
                            ]
                        },
                        properties: {
                            type: 'grid-line'
                        }
                    });
                }
            }

            // Create horizontal lines  
            for (let i = 0; i <= horizontalLines; i++) {
                const lat = south + (i * gridSpacing);
                if (lat <= north) {
                    features.push({
                        type: 'Feature',
                        geometry: {
                            type: 'LineString',
                            coordinates: [
                                [west, lat],
                                [east, lat]
                            ]
                        },
                        properties: {
                            type: 'grid-line'
                        }
                    });
                }
            }

            return {
                type: 'FeatureCollection',
                features: features
            };
        }

        // Function to update grid info
        function updateGridInfo() {
            const zoom = map.getZoom();
            const infoPanel = document.getElementById('grid-info');

            let gridSize;
            if (zoom >= 18) {
                gridSize = '~3m x 3m';
            } else if (zoom >= 16) {
                gridSize = '~6m x 6m';
            } else if (zoom >= 14) {
                gridSize = '~12m x 12m';
            } else {
                gridSize = '~24m x 24m';
            }

            infoPanel.innerHTML = `
                <div class="fw-medium text-danger mb-1">Grid Presisi Lokasi</div>
                <div>Setiap kotak = ${gridSize}</div>
                <div>Zoom: ${Math.round(zoom * 10) / 10}</div>
                <div class="small text-muted mt-1">Zoom in untuk grid lebih presisi</div>
            `;
        }

        // Function to toggle grid
        function toggleGrid() {
            const infoPanel = document.getElementById('grid-info');

            if (gridVisible) {
                // Hide grid
                if (map.getLayer('grid-layer')) {
                    map.removeLayer('grid-layer');
                }
                if (map.getSource('grid-source')) {
                    map.removeSource('grid-source');
                }
                gridToggle.className = 'btn btn-sm btn-outline-primary position-absolute';
                gridToggle.innerHTML = `
                    <i class="bx bx-grid-alt me-1"></i>
                    <span class="small">Tampilkan Grid</span>
                `;
                infoPanel.style.display = 'none';
                gridVisible = false;
            } else {
                // Show grid
                const bounds = map.getBounds();
                const gridData = create3mGrid(bounds);

                map.addSource('grid-source', {
                    type: 'geojson',
                    data: gridData
                });

                map.addLayer({
                    id: 'grid-layer',
                    type: 'line',
                    source: 'grid-source',
                    paint: {
                        'line-color': '#ef4444',
                        'line-width': [
                            'interpolate',
                            ['linear'],
                            ['zoom'],
                            12, 0.3,
                            18, 1
                        ],
                        'line-opacity': 0.7
                    }
                });

                gridToggle.className = 'btn btn-sm btn-primary position-absolute';
                gridToggle.innerHTML = `
                    <i class="bx bx-grid-alt me-1"></i>
                    <span class="small">Sembunyikan Grid</span>
                `;
                infoPanel.style.display = 'block';
                updateGridInfo();
                gridVisible = true;
            }
        }

        // Add click event to grid toggle
        gridToggle.addEventListener('click', toggleGrid);

        // Update grid when map moves or zooms (only if grid is visible)
        map.on('moveend', function() {
            if (gridVisible) {
                const bounds = map.getBounds();
                const gridData = create3mGrid(bounds);

                if (map.getSource('grid-source')) {
                    map.getSource('grid-source').setData(gridData);
                }
                updateGridInfo();
            }
        });

        map.on('zoomend', function() {
            if (gridVisible) {
                updateGridInfo();
            }
        });

        // Add hover coordinate display
        const coordinateDisplay = document.createElement('div');
        coordinateDisplay.id = 'coordinate-display';
        coordinateDisplay.className = 'position-absolute bg-white border rounded p-2 small text-muted';
        coordinateDisplay.style.top = '15px';
        coordinateDisplay.style.left = '15px';
        coordinateDisplay.style.zIndex = '10';
        coordinateDisplay.style.display = 'none';
        document.getElementById('map').appendChild(coordinateDisplay);

        // Show coordinates on hover when grid is visible
        map.on('mousemove', function(e) {
            if (gridVisible) {
                const lat = e.lngLat.lat.toFixed(6);
                const lng = e.lngLat.lng.toFixed(6);

                coordinateDisplay.innerHTML = `
                    <div class="fw-medium text-primary">Koordinat Presisi</div>
                    <div>Lat: ${lat}</div>
                    <div>Lng: ${lng}</div>
                `;
                coordinateDisplay.style.display = 'block';
            }
        });

        map.on('mouseleave', function() {
            coordinateDisplay.style.display = 'none';
        });

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
                            <h6 class="fw-bold">${caseData.short_id}</h6>
                            <p class="mb-1 small text-muted">${caseData.category}</p>
                            <p class="mb-1 small text-muted">${caseData.location}</p>
                            <p class="mb-2 small text-muted">${new Date(caseData.created_at).toLocaleString('id-ID')}</p>
                            <div>
                                <span class="badge badge-soft-primary">
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

        // Show case detail in modal
        function showCaseDetail(caseId) {
            const modal = new bootstrap.Modal(document.getElementById('caseDetailModal'));
            const modalContent = document.getElementById('caseDetailContent');
            
            // Show loading
            modalContent.innerHTML = `
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 text-muted">Memuat detail kasus...</p>
                </div>
            `;
            
            modal.show();
            
            // Fetch case details
            fetch(`/cases/${caseId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    modalContent.innerHTML = data.html;
                } else {
                    modalContent.innerHTML = `
                        <div class="alert alert-danger">
                            <h6>Error</h6>
                            <p class="mb-0">${data.message || 'Gagal memuat detail kasus'}</p>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                modalContent.innerHTML = `
                    <div class="alert alert-danger">
                        <h6>Error</h6>
                        <p class="mb-0">Terjadi kesalahan saat memuat detail kasus</p>
                    </div>
                `;
            });
        }
    </script>
@endsection
