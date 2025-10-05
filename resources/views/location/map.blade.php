@extends('main.layout')

@section('title', 'Tracking Petugas')

@section('styles')
    <link href='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css' rel='stylesheet' />
    <style>
        #map {
            width: 100%;
            height: calc(100vh - 200px);
            min-height: 500px;
        }

        .mapboxgl-popup-content {
            padding: 15px;
            min-width: 200px;
        }

        .petugas-info {
            margin: 0;
        }

        .petugas-info strong {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
            color: #1e40af;
        }

        .petugas-info small {
            display: block;
            color: #6b7280;
            margin-top: 3px;
        }

        .status-indicator {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            display: inline-block;
            margin-right: 5px;
        }

        .status-online {
            background-color: #10b981;
            animation: pulse 2s infinite;
        }

        .status-offline {
            background-color: #ef4444;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }
    </style>
@endsection

@section('content')
    <div class="page-content">
        <div class="container-fluid">

            <!-- Page Title -->
            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                        <h4 class="mb-sm-0 font-size-18">Tracking Lokasi Petugas</h4>
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item"><a
                                        href="{{ auth()->user()->role === 'PIMPINAN' ? route('pimpinan.dashboard') : route('dashboard') }}">Dashboard</a>
                                </li>
                                <li class="breadcrumb-item active">Tracking Petugas</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row">
                <div class="col-xl-3 col-md-6">
                    <div class="card mini-stats-wid">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-medium">Total Petugas</p>
                                    <h4 class="mb-0" id="total-petugas">{{ $petugas->count() }}</h4>
                                </div>
                                <div class="flex-shrink-0 align-self-center">
                                    <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                        <span class="avatar-title">
                                            <i class="bx bx-group font-size-24"></i>
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
                                    <p class="text-muted fw-medium">Petugas Online</p>
                                    <h4 class="mb-0" id="online-petugas">
                                        {{ $petugas->where('last_location_update', '>=', now()->subMinutes(5))->count() }}
                                    </h4>
                                </div>
                                <div class="flex-shrink-0 align-self-center">
                                    <div class="mini-stat-icon avatar-sm rounded-circle bg-success">
                                        <span class="avatar-title">
                                            <i class="bx bx-map-pin font-size-24"></i>
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
                                    <p class="text-muted fw-medium">Petugas Offline</p>
                                    <h4 class="mb-0" id="offline-petugas">
                                        {{ $petugas->where('last_location_update', '<', now()->subMinutes(5))->count() }}
                                    </h4>
                                </div>
                                <div class="flex-shrink-0 align-self-center">
                                    <div class="mini-stat-icon avatar-sm rounded-circle bg-danger">
                                        <span class="avatar-title">
                                            <i class="bx bx-map-alt font-size-24"></i>
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
                                    <p class="text-muted fw-medium">Auto Refresh</p>
                                    <h4 class="mb-0">
                                        <span class="badge bg-info font-size-12">10 detik</span>
                                    </h4>
                                </div>
                                <div class="flex-shrink-0 align-self-center">
                                    <div class="mini-stat-icon avatar-sm rounded-circle bg-info">
                                        <span class="avatar-title">
                                            <i class="bx bx-refresh font-size-24"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Map Container -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="card-title">Peta Lokasi Real-time</h4>
                                <button type="button" class="btn btn-sm btn-primary" onclick="refreshMarkers()">
                                    <i class="bx bx-refresh"></i> Refresh
                                </button>
                            </div>
                            <div id="map"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script src='https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js'></script>
    <script>
        // Mapbox Access Token - REPLACE WITH YOUR OWN TOKEN
        mapboxgl.accessToken = 'YOUR_MAPBOX_ACCESS_TOKEN_HERE';

        let map;
        let markers = {};

        // Default center (Indonesia)
        const defaultCenter = [106.8456, -6.2088]; // Jakarta

        // Initialize map
        function initMap() {
            map = new mapboxgl.Map({
                container: 'map',
                style: 'mapbox://styles/mapbox/streets-v12',
                center: defaultCenter,
                zoom: 12
            });

            map.addControl(new mapboxgl.NavigationControl());
            map.addControl(new mapboxgl.FullscreenControl());

            // Load initial markers
            loadMarkers();
        }

        // Load petugas markers
        function loadMarkers() {
            const petugasData = @json($petugas);

            if (petugasData.length === 0) {
                console.log('No petugas with location data');
                return;
            }

            // Clear existing markers
            Object.values(markers).forEach(marker => marker.remove());
            markers = {};

            const bounds = new mapboxgl.LngLatBounds();

            petugasData.forEach(petugas => {
                if (petugas.last_latitude && petugas.last_longitude) {
                    const lngLat = [parseFloat(petugas.last_longitude), parseFloat(petugas.last_latitude)];

                    // Check if online (updated within last 5 minutes)
                    const lastUpdate = new Date(petugas.last_location_update);
                    const isOnline = (Date.now() - lastUpdate.getTime()) < (5 * 60 * 1000);

                    // Create marker element
                    const el = document.createElement('div');
                    el.className = 'marker';
                    el.style.width = '30px';
                    el.style.height = '30px';
                    el.style.borderRadius = '50%';
                    el.style.backgroundColor = isOnline ? '#10b981' : '#ef4444';
                    el.style.border = '3px solid white';
                    el.style.boxShadow = '0 2px 4px rgba(0,0,0,0.3)';
                    el.style.cursor = 'pointer';

                    // Create popup
                    const popup = new mapboxgl.Popup({
                            offset: 25
                        })
                        .setHTML(`
                        <div class="petugas-info">
                            <strong>${petugas.name}</strong>
                            <small><i class="bx bx-building"></i> ${petugas.unit ? petugas.unit.name : 'N/A'}</small>
                            <small><i class="bx bx-time"></i> Update: ${formatDate(petugas.last_location_update)}</small>
                            <small>
                                <span class="status-indicator ${isOnline ? 'status-online' : 'status-offline'}"></span>
                                ${isOnline ? 'Online' : 'Offline'}
                            </small>
                        </div>
                    `);

                    // Create marker
                    const marker = new mapboxgl.Marker(el)
                        .setLngLat(lngLat)
                        .setPopup(popup)
                        .addTo(map);

                    markers[petugas.id] = marker;
                    bounds.extend(lngLat);
                }
            });

            // Fit map to markers
            if (Object.keys(markers).length > 0) {
                map.fitBounds(bounds, {
                    padding: 50
                });
            }
        }

        // Refresh markers from API
        function refreshMarkers() {
            const url = @json(auth()->user()->role === 'PIMPINAN' ? route('pimpinan.petugas-locations') : route('petugas-locations'));

            fetch(url)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateMarkers(data.petugas);
                        updateStats(data.petugas);
                    }
                })
                .catch(error => console.error('Error refreshing markers:', error));
        }

        // Update markers on map
        function updateMarkers(petugasData) {
            petugasData.forEach(petugas => {
                const lngLat = [petugas.longitude, petugas.latitude];

                // Check if online
                const isOnline = petugas.last_update && petugas.last_update.includes('second') ||
                    petugas.last_update.includes('minute') && parseInt(petugas.last_update) < 5;

                if (markers[petugas.id]) {
                    // Update existing marker
                    markers[petugas.id].setLngLat(lngLat);
                    const el = markers[petugas.id].getElement();
                    el.style.backgroundColor = isOnline ? '#10b981' : '#ef4444';

                    // Update popup
                    const popup = new mapboxgl.Popup({
                            offset: 25
                        })
                        .setHTML(`
                        <div class="petugas-info">
                            <strong>${petugas.name}</strong>
                            <small><i class="bx bx-building"></i> ${petugas.unit_name}</small>
                            <small><i class="bx bx-time"></i> Update: ${petugas.last_update}</small>
                            <small>
                                <span class="status-indicator ${isOnline ? 'status-online' : 'status-offline'}"></span>
                                ${isOnline ? 'Online' : 'Offline'}
                            </small>
                        </div>
                    `);
                    markers[petugas.id].setPopup(popup);
                } else {
                    // Create new marker
                    const el = document.createElement('div');
                    el.className = 'marker';
                    el.style.width = '30px';
                    el.style.height = '30px';
                    el.style.borderRadius = '50%';
                    el.style.backgroundColor = isOnline ? '#10b981' : '#ef4444';
                    el.style.border = '3px solid white';
                    el.style.boxShadow = '0 2px 4px rgba(0,0,0,0.3)';
                    el.style.cursor = 'pointer';

                    const popup = new mapboxgl.Popup({
                            offset: 25
                        })
                        .setHTML(`
                        <div class="petugas-info">
                            <strong>${petugas.name}</strong>
                            <small><i class="bx bx-building"></i> ${petugas.unit_name}</small>
                            <small><i class="bx bx-time"></i> Update: ${petugas.last_update}</small>
                            <small>
                                <span class="status-indicator ${isOnline ? 'status-online' : 'status-offline'}"></span>
                                ${isOnline ? 'Online' : 'Offline'}
                            </small>
                        </div>
                    `);

                    const marker = new mapboxgl.Marker(el)
                        .setLngLat(lngLat)
                        .setPopup(popup)
                        .addTo(map);

                    markers[petugas.id] = marker;
                }
            });
        }

        // Update stats cards
        function updateStats(petugasData) {
            const total = petugasData.length;
            const online = petugasData.filter(p =>
                p.last_update && (p.last_update.includes('second') ||
                    (p.last_update.includes('minute') && parseInt(p.last_update) < 5))
            ).length;
            const offline = total - online;

            document.getElementById('total-petugas').textContent = total;
            document.getElementById('online-petugas').textContent = online;
            document.getElementById('offline-petugas').textContent = offline;
        }

        // Format date helper
        function formatDate(dateString) {
            if (!dateString) return 'Never';
            const date = new Date(dateString);
            const now = new Date();
            const diff = Math.floor((now - date) / 1000); // seconds

            if (diff < 60) return `${diff} detik lalu`;
            if (diff < 3600) return `${Math.floor(diff / 60)} menit lalu`;
            if (diff < 86400) return `${Math.floor(diff / 3600)} jam lalu`;
            return `${Math.floor(diff / 86400)} hari lalu`;
        }

        // Initialize on load
        document.addEventListener('DOMContentLoaded', function() {
            initMap();

            // Auto refresh every 10 seconds
            setInterval(refreshMarkers, 10000);
        });
    </script>
@endsection
