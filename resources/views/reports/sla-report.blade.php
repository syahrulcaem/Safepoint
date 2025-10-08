@extends('layouts.app')

@section('title', 'Laporan SLA & Analitik')

@push('styles')
    <!-- Mapbox GL JS CSS -->
    <link href='https://api.mapbox.com/mapbox-gl-js/v3.0.1/mapbox-gl.css' rel='stylesheet' />
@endpush

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-sm-0 font-size-18">Laporan SLA & Analitik</h4>
                        <p class="text-muted mb-0">Analisis performa dan kepatuhan SLA kasus darurat</p>
                    </div>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Laporan SLA</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Date Filter -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="GET" action="{{ route('reports.sla') }}" class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label for="start_date" class="form-label">Tanggal Mulai</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" 
                                    value="{{ request('start_date', $startDate->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-4">
                                <label for="end_date" class="form-label">Tanggal Akhir</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" 
                                    value="{{ request('end_date', $endDate->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bx bx-filter me-1"></i> Filter Data
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mapbox Heatmap -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i class="bx bx-map me-2"></i>Peta Heatmap Lokasi Kasus
                        </h4>
                        <p class="text-muted mb-0 mt-1">Visualisasi geografis distribusi kasus darurat</p>
                    </div>
                    <div class="card-body">
                        <div id="mapbox-heatmap" style="height: 500px; border-radius: 8px;"></div>
                        <div class="mt-3">
                            <div class="row">
                                <div class="col-md-4">
                                    <small class="text-muted">
                                        <i class="bx bx-map-pin me-1 text-danger"></i>
                                        <strong>{{ $casesWithLocation->count() }}</strong> kasus dengan lokasi valid
                                    </small>
                                </div>
                                <div class="col-md-8 text-end">
                                    <small class="text-muted">
                                        <i class="bx bx-info-circle me-1"></i>
                                        Intensitas warna menunjukkan konsentrasi kasus di area tersebut
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Total Kasus</p>
                                <h4 class="mb-0">{{ $stats['total_cases'] }}</h4>
                            </div>
                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle" style="background: linear-gradient(45deg, #6c757d 0%, #495057 100%) !important;">
                                    <span class="avatar-title" style="background: linear-gradient(45deg, #6c757d 0%, #495057 100%) !important;">
                                        <i class="bx bx-file font-size-20 text-white"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Kasus Selesai</p>
                                <h4 class="mb-0 text-success">{{ $stats['completed_cases'] }}</h4>
                            </div>
                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle" style="background: linear-gradient(45deg, #198754 0%, #146c43 100%) !important;">
                                    <span class="avatar-title" style="background: linear-gradient(45deg, #198754 0%, #146c43 100%) !important;">
                                        <i class="bx bx-check-circle font-size-20 text-white"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Kasus Aktif</p>
                                <h4 class="mb-0 text-warning">{{ $stats['active_cases'] }}</h4>
                            </div>
                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle" style="background: linear-gradient(45deg, #ffc107 0%, #e0a800 100%) !important;">
                                    <span class="avatar-title" style="background: linear-gradient(45deg, #ffc107 0%, #e0a800 100%) !important;">
                                        <i class="bx bx-time font-size-20 text-white"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Kasus Dibatalkan</p>
                                <h4 class="mb-0 text-danger">{{ $stats['cancelled_cases'] }}</h4>
                            </div>
                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle" style="background: linear-gradient(45deg, #dc3545 0%, #c82333 100%) !important;">
                                    <span class="avatar-title" style="background: linear-gradient(45deg, #dc3545 0%, #c82333 100%) !important;">
                                        <i class="bx bx-x-circle font-size-20 text-white"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SLA Compliance Cards -->
        <div class="row">
            <div class="col-xl-4 col-md-6">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-sm me-3">
                                <div class="avatar-title rounded" style="background: linear-gradient(45deg, #0dcaf0 0%, #0aa2c0 100%) !important; color: white;">
                                    <i class="bx bx-time-five font-size-20"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="font-size-15 mb-1">Kepatuhan SLA Respons</h5>
                                <p class="text-muted mb-0 small">Target: ≤ 15 menit</p>
                            </div>
                        </div>
                        <div class="text-center py-3">
                            <h1 class="display-3 mb-2" style="color: #0dcaf0; font-weight: 700;">
                                {{ $slaCompliance['response_compliance'] }}<small class="font-size-24">%</small>
                            </h1>
                            <div class="progress mb-3" style="height: 8px;">
                                <div class="progress-bar" style="width: {{ $slaCompliance['response_compliance'] }}%; background: linear-gradient(90deg, #0dcaf0 0%, #0aa2c0 100%);" 
                                    role="progressbar"></div>
                            </div>
                            <p class="text-muted mb-0">
                                <strong>{{ $slaCompliance['response_sla_met'] }}</strong> dari <strong>{{ $slaCompliance['response_total'] }}</strong> kasus memenuhi target
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-sm me-3">
                                <div class="avatar-title rounded" style="background: linear-gradient(45deg, #198754 0%, #146c43 100%) !important; color: white;">
                                    <i class="bx bx-check-double font-size-20"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="font-size-15 mb-1">Kepatuhan SLA Resolusi</h5>
                                <p class="text-muted mb-0 small">Target: ≤ 120 menit</p>
                            </div>
                        </div>
                        <div class="text-center py-3">
                            <h1 class="display-3 mb-2 text-success" style="font-weight: 700;">
                                {{ $slaCompliance['resolution_compliance'] }}<small class="font-size-24">%</small>
                            </h1>
                            <div class="progress mb-3" style="height: 8px;">
                                <div class="progress-bar bg-success" style="width: {{ $slaCompliance['resolution_compliance'] }}%; background: linear-gradient(90deg, #198754 0%, #146c43 100%);" 
                                    role="progressbar"></div>
                            </div>
                            <p class="text-muted mb-0">
                                <strong>{{ $slaCompliance['resolution_sla_met'] }}</strong> dari <strong>{{ $slaCompliance['resolution_total'] }}</strong> kasus memenuhi target
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-sm me-3">
                                <div class="avatar-title rounded" style="background: linear-gradient(45deg, #6f42c1 0%, #59359a 100%) !important; color: white;">
                                    <i class="bx bx-award font-size-20"></i>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="font-size-15 mb-1">Kepatuhan SLA Keseluruhan</h5>
                                <p class="text-muted mb-0 small">Rata-rata performa</p>
                            </div>
                        </div>
                        <div class="text-center py-3">
                            <h1 class="display-3 mb-2" style="color: #6f42c1; font-weight: 700;">
                                {{ $slaCompliance['overall_compliance'] }}<small class="font-size-24">%</small>
                            </h1>
                            <div class="progress mb-3" style="height: 8px;">
                                <div class="progress-bar" style="width: {{ $slaCompliance['overall_compliance'] }}%; background: linear-gradient(90deg, #6f42c1 0%, #59359a 100%);" 
                                    role="progressbar"></div>
                            </div>
                            <p class="text-muted mb-0">
                                Target minimum: <strong>85%</strong>
                                @if($slaCompliance['overall_compliance'] >= 85)
                                    <span class="badge bg-success ms-2">Tercapai</span>
                                @else
                                    <span class="badge bg-warning ms-2">Perlu Ditingkatkan</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Performance Metrics -->
        <div class="row">
            <div class="col-xl-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <div class="avatar-sm me-3">
                                <div class="avatar-title rounded" style="background: linear-gradient(45deg, #0dcaf0 0%, #0aa2c0 100%) !important; color: white;">
                                    <i class="bx bx-run font-size-20"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="card-title mb-0">Waktu Respons Rata-rata</h4>
                                <p class="text-muted mb-0">Dari laporan hingga dispatch</p>
                            </div>
                        </div>
                        <div class="text-center">
                            <h1 class="display-4 mb-2" style="color: #0dcaf0;">
                                @if($avgResponseTime < 60)
                                    {{ number_format($avgResponseTime, 1) }} <small class="font-size-18">menit</small>
                                @else
                                    {{ number_format($avgResponseTime / 60, 1) }} <small class="font-size-18">jam</small>
                                @endif
                            </h1>
                            @if($avgResponseTime <= 15)
                                <span class="badge bg-success">Sangat Baik</span>
                            @elseif($avgResponseTime <= 30)
                                <span class="badge bg-info">Baik</span>
                            @elseif($avgResponseTime <= 60)
                                <span class="badge bg-warning">Cukup</span>
                            @else
                                <span class="badge" style="background: linear-gradient(45deg, #dc3545 0%, #c82333 100%);">Perlu Perbaikan</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <div class="avatar-sm me-3">
                                <div class="avatar-title rounded" style="background: linear-gradient(45deg, #198754 0%, #146c43 100%) !important; color: white;">
                                    <i class="bx bx-trophy font-size-20"></i>
                                </div>
                            </div>
                            <div>
                                <h4 class="card-title mb-0">Waktu Resolusi Rata-rata</h4>
                                <p class="text-muted mb-0">Dari laporan hingga selesai</p>
                            </div>
                        </div>
                        <div class="text-center">
                            <h1 class="display-4 mb-2 text-success">
                                @if($avgResolutionTime < 60)
                                    {{ number_format($avgResolutionTime, 1) }} <small class="font-size-18">menit</small>
                                @else
                                    {{ number_format($avgResolutionTime / 60, 1) }} <small class="font-size-18">jam</small>
                                @endif
                            </h1>
                            @if($avgResolutionTime <= 120)
                                <span class="badge bg-success">Sangat Baik</span>
                            @elseif($avgResolutionTime <= 180)
                                <span class="badge bg-info">Baik</span>
                            @elseif($avgResolutionTime <= 360)
                                <span class="badge bg-warning">Cukup</span>
                            @else
                                <span class="badge" style="background: linear-gradient(45deg, #dc3545 0%, #c82333 100%);">Perlu Perbaikan</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Heatmap -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0">
                            <i class="bx bx-map-alt me-2"></i>Heatmap Kasus per Jam & Hari
                        </h4>
                        <p class="text-muted mb-0 mt-1">Pola waktu terjadinya kasus darurat</p>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm text-center mb-0">
                                <thead>
                                    <tr>
                                        <th>Hari / Jam</th>
                                        @for($hour = 0; $hour < 24; $hour++)
                                            <th style="min-width: 35px;">{{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}</th>
                                        @endfor
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($heatmapData['days'] as $dayIndex => $dayName)
                                        <tr>
                                            <td class="fw-medium">{{ $dayName }}</td>
                                            @for($hour = 0; $hour < 24; $hour++)
                                                @php
                                                    $count = $heatmapData['data'][$dayIndex][$hour] ?? 0;
                                                    $maxCount = 10; // Adjust based on your data
                                                    $intensity = min(100, ($count / $maxCount) * 100);
                                                    
                                                    if ($count == 0) {
                                                        $bgColor = '#f8f9fa';
                                                        $textColor = '#6c757d';
                                                    } elseif ($intensity < 25) {
                                                        $bgColor = '#d1ecf1';
                                                        $textColor = '#0c5460';
                                                    } elseif ($intensity < 50) {
                                                        $bgColor = '#b8daff';
                                                        $textColor = '#004085';
                                                    } elseif ($intensity < 75) {
                                                        $bgColor = '#ffc107';
                                                        $textColor = '#000';
                                                    } else {
                                                        $bgColor = '#dc3545';
                                                        $textColor = '#fff';
                                                    }
                                                @endphp
                                                <td style="background-color: {{ $bgColor }}; color: {{ $textColor }}; font-weight: {{ $count > 0 ? 'bold' : 'normal' }};">
                                                    {{ $count > 0 ? $count : '-' }}
                                                </td>
                                            @endfor
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="bx bx-info-circle me-1"></i>
                                Warna yang lebih gelap menunjukkan frekuensi kasus yang lebih tinggi pada jam tersebut
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Units (Only for Operator/SuperAdmin) -->
        @if(auth()->user()->role === 'OPERATOR' || auth()->user()->role === 'SUPERADMIN')
            @if($topUnits->isNotEmpty())
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-0">
                                    <i class="bx bx-trophy me-2"></i>Top 5 Unit Berkinerja Terbaik
                                </h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>Peringkat</th>
                                                <th>Nama Unit</th>
                                                <th>Tipe</th>
                                                <th>Kasus Selesai</th>
                                                <th>Performa</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($topUnits as $index => $unit)
                                                <tr>
                                                    <td>
                                                        @if($index === 0)
                                                            <span class="badge bg-warning">🥇 #1</span>
                                                        @elseif($index === 1)
                                                            <span class="badge bg-light text-dark">🥈 #2</span>
                                                        @elseif($index === 2)
                                                            <span class="badge" style="background-color: #cd7f32; color: white;">🥉 #3</span>
                                                        @else
                                                            <span class="badge bg-secondary">#{{ $index + 1 }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="fw-medium">{{ $unit->name }}</td>
                                                    <td>
                                                        <span class="badge badge-soft-info">{{ $unit->type }}</span>
                                                    </td>
                                                    <td>
                                                        <h5 class="mb-0 text-success">{{ $unit->assigned_cases_count }}</h5>
                                                    </td>
                                                    <td>
                                                        <div class="progress" style="height: 20px;">
                                                            @php
                                                                $maxCases = $topUnits->first()->assigned_cases_count;
                                                                $percentage = ($unit->assigned_cases_count / $maxCases) * 100;
                                                            @endphp
                                                            <div class="progress-bar bg-success" role="progressbar" 
                                                                style="width: {{ $percentage }}%" 
                                                                aria-valuenow="{{ $percentage }}" 
                                                                aria-valuemin="0" aria-valuemax="100">
                                                                {{ round($percentage) }}%
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endif
    </div>

    <!-- Mapbox GL JS -->
    <script src='https://api.mapbox.com/mapbox-gl-js/v3.0.1/mapbox-gl.js'></script>

    <script>
        // Initialize Mapbox Heatmap
        mapboxgl.accessToken = @json(config('services.mapbox.access_token'));
        
        const casesData = {!! json_encode($casesWithLocation->map(function($case) {
            return [
                'type' => 'Feature',
                'properties' => [
                    'id' => $case->short_id,
                    'category' => $case->category,
                    'status' => $case->status,
                    'unit' => optional($case->assignedUnit)->name ?? 'N/A',
                ],
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [(float)$case->lon, (float)$case->lat]
                ]
            ];
        })->values()) !!};

        // Calculate center point
        let centerLat = -6.200000; // Default Jakarta
        let centerLon = 106.816666;
        
        if (casesData.length > 0) {
            const lats = casesData.map(c => c.geometry.coordinates[1]);
            const lons = casesData.map(c => c.geometry.coordinates[0]);
            centerLat = lats.reduce((a, b) => a + b, 0) / lats.length;
            centerLon = lons.reduce((a, b) => a + b, 0) / lons.length;
        }

        const map = new mapboxgl.Map({
            container: 'mapbox-heatmap',
            style: 'mapbox://styles/mapbox/dark-v11',
            center: [centerLon, centerLat],
            zoom: casesData.length > 0 ? 11 : 10
        });

        map.on('load', () => {
            // Add heatmap source
            map.addSource('cases-heat', {
                type: 'geojson',
                data: {
                    type: 'FeatureCollection',
                    features: casesData
                }
            });

            // Add heatmap layer
            map.addLayer({
                id: 'cases-heat-layer',
                type: 'heatmap',
                source: 'cases-heat',
                maxzoom: 15,
                paint: {
                    // Increase weight as diameter increases
                    'heatmap-weight': 1,
                    // Increase intensity as zoom level increases
                    'heatmap-intensity': [
                        'interpolate',
                        ['linear'],
                        ['zoom'],
                        0, 1,
                        15, 3
                    ],
                    // Color ramp for heatmap
                    'heatmap-color': [
                        'interpolate',
                        ['linear'],
                        ['heatmap-density'],
                        0, 'rgba(33,102,172,0)',
                        0.2, 'rgb(103,169,207)',
                        0.4, 'rgb(209,229,240)',
                        0.6, 'rgb(253,219,199)',
                        0.8, 'rgb(239,138,98)',
                        1, 'rgb(178,24,43)'
                    ],
                    // Adjust radius by zoom level
                    'heatmap-radius': [
                        'interpolate',
                        ['linear'],
                        ['zoom'],
                        0, 2,
                        15, 20
                    ],
                    // Transition from heatmap to circle layer by zoom level
                    'heatmap-opacity': [
                        'interpolate',
                        ['linear'],
                        ['zoom'],
                        7, 1,
                        15, 0
                    ]
                }
            });

            // Add circle layer for individual points
            map.addLayer({
                id: 'cases-point',
                type: 'circle',
                source: 'cases-heat',
                minzoom: 7,
                paint: {
                    // Size circle radius by zoom level
                    'circle-radius': [
                        'interpolate',
                        ['linear'],
                        ['zoom'],
                        7, 1,
                        16, 6
                    ],
                    // Color circles
                    'circle-color': '#dc3545',
                    'circle-stroke-color': 'white',
                    'circle-stroke-width': 1,
                    // Transition from heatmap to circle layer
                    'circle-opacity': [
                        'interpolate',
                        ['linear'],
                        ['zoom'],
                        7, 0,
                        8, 1
                    ]
                }
            });

            // Add popup on click
            map.on('click', 'cases-point', (e) => {
                const coordinates = e.features[0].geometry.coordinates.slice();
                const props = e.features[0].properties;
                
                new mapboxgl.Popup()
                    .setLngLat(coordinates)
                    .setHTML(`
                        <div style="padding: 8px;">
                            <strong>${props.id}</strong><br>
                            <small>Kategori: ${props.category}</small><br>
                            <small>Status: ${props.status}</small><br>
                            <small>Unit: ${props.unit}</small>
                        </div>
                    `)
                    .addTo(map);
            });

            // Change cursor on hover
            map.on('mouseenter', 'cases-point', () => {
                map.getCanvas().style.cursor = 'pointer';
            });
            map.on('mouseleave', 'cases-point', () => {
                map.getCanvas().style.cursor = '';
            });
        });

        // Add navigation controls
        map.addControl(new mapboxgl.NavigationControl());
        map.addControl(new mapboxgl.FullscreenControl());
    </script>
@endsection
