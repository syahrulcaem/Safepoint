@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-start">
            <div>
                <div class="flex items-center space-x-3">
                    <h1 class="text-2xl font-bold text-gray-900">Kasus {{ $case->short_id }}</h1>
                    <x-status-badge :status="$case->status" />
                </div>
                <p class="text-gray-600">{{ $case->created_at->format('d F Y, H:i') }} WIB</p>
            </div>
            <div class="flex space-x-3">
                @if ($case->lat && $case->lon)
                    <a href="https://www.google.com/maps?q={{ $case->lat }},{{ $case->lon }}" target="_blank"
                        class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Buka di Maps
                    </a>
                @endif
                <a href="{{ route('cases.index') }}" class="text-red-600 hover:text-red-500">
                    ← Kembali ke daftar
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Case Metadata -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Metadata Kasus</h3>
                    </div>
                    <div class="px-6 py-4">
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">ID Kasus</dt>
                                <dd class="mt-1 text-sm font-mono text-gray-900">{{ $case->short_id }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Waktu Laporan</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $case->created_at->format('d F Y, H:i:s') }} WIB
                                    <span class="text-gray-500">({{ $case->created_at->diffForHumans() }})</span>
                                </dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Kategori</dt>
                                <dd class="mt-1">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $case->category === 'MEDIS' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $case->category === 'KEBAKARAN' ? 'bg-red-100 text-red-800' : '' }}
                                        {{ $case->category === 'KRIMINAL' ? 'bg-purple-100 text-purple-800' : '' }}
                                        {{ $case->category === 'UMUM' ? 'bg-gray-100 text-gray-800' : '' }}
                                        {{ in_array($case->category, ['BENCANA_ALAM', 'BANJIR', 'POHON_TUMBANG']) ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ in_array($case->category, ['KECELAKAAN', 'KEBOCORAN_GAS']) ? 'bg-orange-100 text-orange-800' : '' }}">
                                        @switch($case->category)
                                            @case('MEDIS')
                                                Medis
                                            @break

                                            @case('KEBAKARAN')
                                                Kebakaran
                                            @break

                                            @case('KRIMINAL')
                                                Kriminal
                                            @break

                                            @case('UMUM')
                                                Umum
                                            @break

                                            @case('BENCANA_ALAM')
                                                Bencana Alam
                                            @break

                                            @case('KECELAKAAN')
                                                Kecelakaan
                                            @break

                                            @case('KEBOCORAN_GAS')
                                                Kebocoran Gas
                                            @break

                                            @case('POHON_TUMBANG')
                                                Pohon Tumbang
                                            @break

                                            @case('BANJIR')
                                                Banjir
                                            @break

                                            @default
                                                {{ $case->category }}
                                        @endswitch
                                    </span>
                                </dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Status Saat Ini</dt>
                                <dd class="mt-1">
                                    <x-status-badge :status="$case->status" />
                                </dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Lokasi (What3Words)</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <div class="flex items-center space-x-2">
                                        <span class="font-mono text-blue-600">{{ $case->locator_text }}</span>
                                        <a href="{{ $googleMapsUrl }}" target="_blank"
                                            class="inline-flex items-center px-2 py-1 border border-blue-300 shadow-sm text-xs font-medium rounded text-blue-700 bg-blue-50 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                                </path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            Buka di Maps
                                        </a>
                                    </div>
                                </dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Koordinat (Lat, Lon)</dt>
                                <dd class="mt-1 text-sm font-mono text-gray-600">{{ $case->lat }}, {{ $case->lon }}
                                </dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Pelapor</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    @if ($case->reporterUser)
                                        <div>
                                            <span class="font-medium">{{ $case->reporterUser->name }}</span>
                                            <span class="text-gray-500">(Warga)</span>
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $case->reporterUser->email }}</div>
                                        @if ($case->reporterUser->citizenProfile)
                                            @if ($case->reporterUser->citizenProfile->whatsapp_keluarga)
                                                <div class="text-xs text-green-600 mt-1">
                                                    📱 WhatsApp Keluarga:
                                                    {{ $case->reporterUser->citizenProfile->whatsapp_keluarga }}
                                                </div>
                                            @endif
                                            @if ($case->reporterUser->citizenProfile->hubungan)
                                                <div class="text-xs text-gray-500 mt-1">
                                                    Hubungan: {{ $case->reporterUser->citizenProfile->hubungan_display }}
                                                </div>
                                            @endif
                                        @endif
                                    @else
                                        <span class="text-gray-500 italic">Guest Reporter</span>
                                    @endif
                                </dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nomor Telepon</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $case->phone ?: '-' }}</dd>
                            </div>

                            @if ($case->assignedUnit)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Unit Ditugaskan</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        <div class="font-medium">{{ $case->assignedUnit->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $case->assignedUnit->type }}</div>
                                    </dd>
                                </div>
                            @endif

                            @if ($case->assignedPetugas)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Petugas Ditugaskan</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        <div class="font-medium">{{ $case->assignedPetugas->name }}</div>
                                        <div class="text-xs text-gray-500">
                                            {{ $case->assignedPetugas->phone }}
                                            @if ($case->assignedPetugas->duty_status === 'ON_DUTY')
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 ml-1">
                                                    Bertugas
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 ml-1">
                                                    Off Duty
                                                </span>
                                            @endif
                                        </div>
                                    </dd>
                                </div>
                            @endif

                            @if ($case->verified_at)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Waktu Verifikasi</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $case->verified_at->format('d F Y, H:i') }}
                                        WIB</dd>
                                </div>
                            @endif

                            @if ($case->dispatched_at)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Waktu Dispatch</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $case->dispatched_at->format('d F Y, H:i') }}
                                        WIB</dd>
                                </div>
                            @endif

                            @if ($case->closed_at)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Waktu Penutupan</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $case->closed_at->format('d F Y, H:i') }} WIB
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                </div>

                <!-- Description -->
                @if ($case->description)
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Deskripsi Kasus</h3>
                        </div>
                        <div class="px-6 py-4">
                            <p class="text-sm text-gray-900 whitespace-pre-wrap">{{ $case->description }}</p>
                        </div>
                    </div>
                @endif

                <!-- Maps Direction (only show if case is dispatched and has assigned petugas) -->
                @if (
                    $case->assignedPetugas &&
                        $case->lat &&
                        $case->lon &&
                        in_array($case->status, ['DISPATCHED', 'ON_THE_WAY', 'ON_SCENE']))
                    <div class="bg-white shadow rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Petunjuk Arah ke Lokasi</h3>
                            <p class="text-sm text-gray-500 mt-1">Rute dari petugas {{ $case->assignedPetugas->name }} ke
                                lokasi kejadian</p>
                        </div>
                        <div class="px-6 py-4">
                            <!-- Maps Container -->
                            <div id="map-container"
                                class="w-full h-96 rounded-lg overflow-hidden border border-gray-300 mb-4 relative"
                                style="min-height: 400px;">
                                <div id="directions-map" class="w-full h-full" style="min-height: 400px;"></div>
                                <!-- Loading overlay -->
                                <div id="map-loading"
                                    class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center">
                                    <div class="text-center">
                                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto">
                                        </div>
                                        <p class="mt-2 text-sm text-gray-600">Memuat peta...</p>
                                    </div>
                                </div>
                                <!-- Fallback: Open in Google Maps -->
                                <div id="map-fallback"
                                    class="absolute inset-0 bg-gray-100 flex items-center justify-center"
                                    style="display: none;">
                                    <div class="text-center p-4">
                                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none"
                                            stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                            </path>
                                        </svg>
                                        <p class="text-sm text-gray-600 mb-3">Peta tidak dapat dimuat</p>
                                        <a href="https://www.google.com/maps/dir/{{ $case->assignedPetugas->last_latitude ?? -6.2088 }},{{ $case->assignedPetugas->last_longitude ?? 106.8456 }}/{{ $case->lat }},{{ $case->lon }}"
                                            target="_blank"
                                            class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14">
                                                </path>
                                            </svg>
                                            Buka di Google Maps
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Route Information -->
                            <div id="route-info" class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                <div class="bg-blue-50 p-3 rounded-lg">
                                    <div class="font-medium text-blue-900">Jarak</div>
                                    <div id="route-distance" class="text-blue-700">Menghitung...</div>
                                </div>
                                <div class="bg-green-50 p-3 rounded-lg">
                                    <div class="font-medium text-green-900">Estimasi Waktu</div>
                                    <div id="route-duration" class="text-green-700">Menghitung...</div>
                                </div>
                                <div class="bg-yellow-50 p-3 rounded-lg">
                                    <div class="font-medium text-yellow-900">Status Petugas</div>
                                    <div id="petugas-status" class="text-yellow-700">
                                        @if ($case->assignedPetugas->last_latitude && $case->assignedPetugas->last_longitude)
                                            Lokasi Tersedia
                                        @else
                                            Lokasi Tidak Tersedia
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Refresh Button -->
                            <div class="mt-4 text-center">
                                <button id="refresh-directions"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                        </path>
                                    </svg>
                                    Perbarui Rute
                                </button>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Timeline Events -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Timeline Event</h3>
                    </div>
                    <div class="px-6 py-4">
                        @if ($case->caseEvents && $case->caseEvents->count() > 0)
                            <div class="flow-root">
                                <ul role="list" class="-mb-8">
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
                                                            @switch($event->action)
                                                                @case('VERIFIED')
                                                                    <svg class="w-4 h-4 text-white" fill="currentColor"
                                                                        viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd"
                                                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                                            clip-rule="evenodd" />
                                                                    </svg>
                                                                @break

                                                                @case('DISPATCHED')
                                                                    <svg class="w-4 h-4 text-white" fill="currentColor"
                                                                        viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd"
                                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                            clip-rule="evenodd" />
                                                                    </svg>
                                                                @break

                                                                @case('CLOSED')
                                                                    <svg class="w-4 h-4 text-white" fill="currentColor"
                                                                        viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd"
                                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                            clip-rule="evenodd" />
                                                                    </svg>
                                                                @break

                                                                @case('CANCELLED')
                                                                    <svg class="w-4 h-4 text-white" fill="currentColor"
                                                                        viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd"
                                                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                                            clip-rule="evenodd" />
                                                                    </svg>
                                                                @break

                                                                @default
                                                                    <svg class="w-4 h-4 text-white" fill="currentColor"
                                                                        viewBox="0 0 20 20">
                                                                        <path fill-rule="evenodd"
                                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                            clip-rule="evenodd" />
                                                                    </svg>
                                                            @endswitch
                                                        </span>
                                                    </div>
                                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                        <div>
                                                            <p class="text-sm text-gray-500">
                                                                <span
                                                                    class="font-medium text-gray-900">{{ $event->action }}</span>
                                                                oleh {{ $event->actor->name ?? 'System' }}
                                                            </p>
                                                            @if ($event->notes)
                                                                <p class="mt-1 text-sm text-gray-700">{{ $event->notes }}
                                                                </p>
                                                            @endif
                                                            @if ($event->metadata)
                                                                <div class="mt-2 text-xs text-gray-500">
                                                                    <details>
                                                                        <summary class="cursor-pointer">Detail metadata
                                                                        </summary>
                                                                        <pre class="mt-1 bg-gray-50 p-2 rounded text-xs">{{ json_encode($event->metadata, JSON_PRETTY_PRINT) }}</pre>
                                                                    </details>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                            {{ $event->created_at->format('d/m H:i') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-4">Belum ada event untuk kasus ini.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar Actions -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Quick Actions -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Aksi Cepat</h3>
                    </div>
                    <div class="px-6 py-4 space-y-4">
                        @if ($case->status === 'NEW')
                            <!-- Dispatch to Multiple Units -->
                            <div>
                                <form method="POST" action="{{ route('cases.dispatch-multi', $case) }}"
                                    class="space-y-3" id="dispatch-form">
                                    @csrf
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            Pilih Unit (Dapat memilih lebih dari satu)
                                        </label>
                                        <div
                                            class="space-y-2 max-h-64 overflow-y-auto border border-gray-300 rounded-md p-3">
                                            @foreach ($units as $unit)
                                                <label
                                                    class="flex items-center space-x-3 p-2 hover:bg-gray-50 rounded cursor-pointer">
                                                    <input type="checkbox" name="unit_ids[]" value="{{ $unit->id }}"
                                                        class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                                                    <span class="text-sm text-gray-900">
                                                        {{ $unit->name }} <span
                                                            class="text-gray-500">({{ $unit->type }})</span>
                                                    </span>
                                                </label>
                                            @endforeach
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500">
                                            Setiap unit akan menerima notifikasi dispatch. Pimpinan unit akan menugaskan
                                            petugas.
                                        </p>
                                    </div>

                                    <div>
                                        <label for="notes" class="block text-sm font-medium text-gray-700">Catatan
                                            untuk Pimpinan Unit</label>
                                        <textarea name="notes" id="notes" rows="3"
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                                            placeholder="Catatan untuk pimpinan unit..."></textarea>
                                    </div>

                                    <button type="submit"
                                        class="w-full bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                        </svg>
                                        Kirim ke Unit Terpilih
                                    </button>
                                </form>
                            </div>
                        @endif

                        @if (in_array($case->status, ['DISPATCHED', 'ON_THE_WAY', 'ON_SCENE']))
                            <!-- Close Case -->
                            <div>
                                <form method="POST" action="{{ route('cases.close', $case) }}" class="space-y-3">
                                    @csrf
                                    <div>
                                        <label for="close_notes" class="block text-sm font-medium text-gray-700">Alasan
                                            Penutupan *</label>
                                        <textarea name="notes" id="close_notes" rows="3" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                                            placeholder="Jelaskan hasil penanganan kasus..."></textarea>
                                    </div>

                                    <button type="submit"
                                        class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                        onclick="return confirm('Apakah Anda yakin ingin menutup kasus ini?')">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Tutup Kasus
                                    </button>
                                </form>
                            </div>
                        @endif

                        @if (!in_array($case->status, ['CLOSED', 'CANCELLED']))
                            <!-- Cancel Case -->
                            <div>
                                <form method="POST" action="{{ route('cases.cancel', $case) }}" class="space-y-3">
                                    @csrf
                                    <div>
                                        <label for="cancel_notes" class="block text-sm font-medium text-gray-700">Alasan
                                            Pembatalan *</label>
                                        <textarea name="notes" id="cancel_notes" rows="3" required
                                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm"
                                            placeholder="Jelaskan alasan pembatalan (laporan palsu, dll)..."></textarea>
                                    </div>

                                    <button type="submit"
                                        class="w-full bg-red-800 text-white px-4 py-2 rounded-md hover:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                                        onclick="return confirm('Apakah Anda yakin ingin membatalkan kasus ini? Tindakan ini menandai kasus sebagai laporan palsu.')">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Batalkan Kasus
                                    </button>
                                </form>
                            </div>
                        @endif

                        <!-- WhatsApp Contact -->
                        @if (
                            $case->reporterUser &&
                                $case->reporterUser->citizenProfile &&
                                $case->reporterUser->citizenProfile->whatsapp_keluarga)
                            <div class="pt-4 border-t border-gray-200">
                                <h4 class="text-sm font-medium text-gray-700 mb-3">Kontak Keluarga</h4>
                                @php
                                    $contactUser = $case->reporterUser;
                                    $familyPhone = $case->reporterUser->citizenProfile->whatsapp_keluarga;
                                @endphp

                                <a href="{{ route('cases.whatsapp', $case) }}"
                                    class="w-full bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 flex items-center justify-center">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488" />
                                    </svg>
                                    WhatsApp Keluarga
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Quick Info -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Informasi Cepat</h3>
                    </div>
                    <div class="px-6 py-4 space-y-3">
                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Durasi:</span>
                            <span class="text-sm font-medium text-gray-900">
                                @if ($case->status === 'CLOSED' && $case->closed_at)
                                    {{ $case->created_at->diffInHours($case->closed_at) }} jam
                                @else
                                    {{ $case->created_at->diffForHumans(null, true) }}
                                @endif
                            </span>
                        </div>

                        @if ($case->lat && $case->lon)
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Koordinat:</span>
                                <span class="text-sm font-mono text-gray-900">{{ round($case->lat, 6) }},
                                    {{ round($case->lon, 6) }}</span>
                            </div>
                        @endif

                        @if ($case->accuracy)
                            <div class="flex justify-between">
                                <span class="text-sm text-gray-500">Akurasi GPS:</span>
                                <span class="text-sm text-gray-900">{{ $case->accuracy }}m</span>
                            </div>
                        @endif

                        <div class="flex justify-between">
                            <span class="text-sm text-gray-500">Provider:</span>
                            <span class="text-sm text-gray-900">{{ ucfirst($case->locator_provider) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Form validation for multi-unit dispatch
            const dispatchForm = document.getElementById('dispatch-form');
            if (dispatchForm) {
                dispatchForm.addEventListener('submit', function(e) {
                    const checkboxes = dispatchForm.querySelectorAll('input[name="unit_ids[]"]:checked');
                    if (checkboxes.length === 0) {
                        e.preventDefault();
                        alert('Silakan pilih minimal satu unit untuk dispatch.');
                        return false;
                    }
                });
            }
        });
    </script>

    <!-- Mapbox Directions Script -->
    @if (
        $case->assignedPetugas &&
            $case->lat &&
            $case->lon &&
            in_array($case->status, ['DISPATCHED', 'ON_THE_WAY', 'ON_SCENE']))
        <script>
            // Initialize when DOM is ready
            document.addEventListener('DOMContentLoaded', function() {
                console.log('🚀 Initializing Mapbox...');

                // Check DOM elements
                const mapContainer = document.getElementById('directions-map');
                const loading = document.getElementById('map-loading');
                const fallback = document.getElementById('map-fallback');

                console.log('DOM Elements Check:');
                console.log('- Map Container:', mapContainer);
                console.log('- Loading:', loading);
                console.log('- Fallback:', fallback);

                if (!mapContainer) {
                    console.error('❌ Map container not found!');
                    return;
                }

                // Configuration
                const token = '{{ config('services.mapbox.access_token') }}';
                const caseLocation = [{{ $case->lon }}, {{ $case->lat }}];
                @if ($case->assignedPetugas->last_latitude && $case->assignedPetugas->last_longitude)
                    const petugasLocation = [{{ $case->assignedPetugas->last_longitude }},
                        {{ $case->assignedPetugas->last_latitude }}
                    ];
                @else
                    const petugasLocation = [106.8456, -6.2088];
                @endif

                console.log('Configuration:');
                console.log('- Token:', token ? 'Present' : 'Missing');
                console.log('- Case Location:', caseLocation);
                console.log('- Petugas Location:', petugasLocation);

                // Check if Mapbox is available
                if (typeof mapboxgl === 'undefined') {
                    console.error('❌ Mapbox GL JS not loaded');
                    showFallback();
                    return;
                }

                console.log('✅ Mapbox GL JS loaded:', mapboxgl.version);

                // Get directions first, then initialize map
                getDirections().then(() => {
                    // Initialize map after getting directions
                    initializeMap();
                });

                // Functions
                function getDirections() {
                    console.log('📡 Getting directions...');
                    const url =
                        `https://api.mapbox.com/directions/v5/mapbox/driving/${petugasLocation[0]},${petugasLocation[1]};${caseLocation[0]},${caseLocation[1]}?geometries=geojson&access_token=${token}`;

                    return fetch(url)
                        .then(response => response.json())
                        .then(data => {
                            console.log('📊 Directions response:', data);
                            if (data.routes && data.routes.length > 0) {
                                const route = data.routes[0];
                                const distance = (route.distance / 1000).toFixed(1) + ' km';
                                const duration = Math.round(route.duration / 60) + ' menit';

                                document.getElementById('route-distance').textContent = distance;
                                document.getElementById('route-duration').textContent = duration;
                                console.log('✅ Directions updated:', {
                                    distance,
                                    duration
                                });

                                // Store route data for map display
                                window.routeGeometry = route.geometry;
                                return route.geometry;
                            } else {
                                document.getElementById('route-distance').textContent = 'Tidak tersedia';
                                document.getElementById('route-duration').textContent = 'Tidak tersedia';
                                return null;
                            }
                        })
                        .catch(error => {
                            console.error('❌ Directions error:', error);
                            document.getElementById('route-distance').textContent = 'Error';
                            document.getElementById('route-duration').textContent = 'Error';
                            return null;
                        });
                }

                function initializeMap() {
                    console.log('🗺️ Initializing map...');

                    // Check container dimensions
                    const container = document.getElementById('directions-map');
                    const containerRect = container.getBoundingClientRect();
                    console.log('Container dimensions:', {
                        width: containerRect.width,
                        height: containerRect.height,
                        display: window.getComputedStyle(container).display,
                        visibility: window.getComputedStyle(container).visibility
                    });

                    try {
                        mapboxgl.accessToken = token;

                        console.log('🏗️ Creating map instance...');
                        const map = new mapboxgl.Map({
                            container: 'directions-map',
                            style: 'mapbox://styles/mapbox/streets-v12',
                            center: caseLocation,
                            zoom: 12
                        });

                        console.log('✅ Map instance created');

                        // Store map instance globally for refresh functionality
                        window.mapInstance = map;

                        // Force resize after a short delay
                        setTimeout(() => {
                            console.log('🔄 Triggering map resize...');
                            map.resize();
                        }, 500);

                        map.on('load', function() {
                            console.log('✅ Map loaded successfully');
                            if (loading) loading.style.display = 'none';

                            // Add markers with custom popups
                            console.log('📍 Adding markers...');

                            // Case location marker (red) with popup
                            new mapboxgl.Marker({
                                    color: '#ef4444'
                                })
                                .setLngLat(caseLocation)
                                .setPopup(new mapboxgl.Popup({
                                    offset: 25
                                }).setHTML(`
                                <div class="p-3">
                                    <h3 class="font-bold text-sm text-red-700">📍 Lokasi Kejadian</h3>
                                    <p class="text-xs text-gray-600 mt-1">{{ $case->locator_text }}</p>
                                    <p class="text-xs text-gray-500">{{ $case->lat }}, {{ $case->lon }}</p>
                                </div>
                            `))
                                .addTo(map);

                            // Petugas location marker (blue) with popup  
                            new mapboxgl.Marker({
                                    color: '#3b82f6'
                                })
                                .setLngLat(petugasLocation)
                                .setPopup(new mapboxgl.Popup({
                                    offset: 25
                                }).setHTML(`
                                <div class="p-3">
                                    <h3 class="font-bold text-sm text-blue-700">👮‍♂️ {{ $case->assignedPetugas->name }}</h3>
                                    <p class="text-xs text-gray-600">{{ $case->assignedPetugas->unit->name ?? 'Unit tidak tersedia' }}</p>
                                    @if (!$case->assignedPetugas->last_latitude || !$case->assignedPetugas->last_longitude)
                                        <p class="text-xs text-orange-600">⚠️ Lokasi perkiraan</p>
                                    @endif
                                </div>
                            `))
                                .addTo(map);

                            // Add route line if available
                            if (window.routeGeometry) {
                                console.log('🛣️ Adding route line...');
                                addRouteToMap(map, window.routeGeometry);
                            }

                            // Fit bounds to show both markers and route
                            console.log('🔍 Fitting bounds...');
                            const bounds = new mapboxgl.LngLatBounds();
                            bounds.extend(caseLocation);
                            bounds.extend(petugasLocation);
                            map.fitBounds(bounds, {
                                padding: 60
                            });

                            console.log('✅ Map initialization complete');
                        });

                        map.on('error', function(e) {
                            console.error('❌ Map error:', e);
                            showFallback();
                        });

                        map.on('styledata', function() {
                            console.log('🎨 Map style loaded');
                        });

                        map.on('sourcedata', function() {
                            console.log('📊 Map source data loaded');
                        });

                        // Timeout for map loading
                        setTimeout(function() {
                            if (loading && loading.style.display !== 'none') {
                                console.warn('⏰ Map loading timeout');
                                showFallback();
                            }
                        }, 10000);

                    } catch (error) {
                        console.error('❌ Map init error:', error);
                        showFallback();
                    }
                }

                function showFallback() {
                    console.log('🔄 Showing fallback...');
                    if (loading) loading.style.display = 'none';
                    if (fallback) fallback.style.display = 'flex';
                }

                function addRouteToMap(map, geometry) {
                    console.log('🗺️ Adding route line to map...');

                    // Remove existing route if any
                    if (map.getSource('route')) {
                        map.removeLayer('route');
                        map.removeSource('route');
                    }

                    // Add route source
                    map.addSource('route', {
                        type: 'geojson',
                        data: {
                            type: 'Feature',
                            properties: {},
                            geometry: geometry
                        }
                    });

                    // Add route layer with blue line
                    map.addLayer({
                        id: 'route',
                        type: 'line',
                        source: 'route',
                        layout: {
                            'line-join': 'round',
                            'line-cap': 'round'
                        },
                        paint: {
                            'line-color': '#3b82f6',
                            'line-width': 5,
                            'line-opacity': 0.8
                        }
                    });

                    // Add route outline for better visibility
                    map.addLayer({
                        id: 'route-outline',
                        type: 'line',
                        source: 'route',
                        layout: {
                            'line-join': 'round',
                            'line-cap': 'round'
                        },
                        paint: {
                            'line-color': '#1e40af',
                            'line-width': 7,
                            'line-opacity': 0.4
                        }
                    }, 'route'); // Add outline below the main route

                    console.log('✅ Route line added successfully');
                }

                // Refresh button
                const refreshBtn = document.getElementById('refresh-directions');
                if (refreshBtn) {
                    refreshBtn.addEventListener('click', function() {
                        console.log('🔄 Refresh button clicked');
                        getDirections().then(() => {
                            // Update route line on map if map exists
                            if (window.mapInstance && window.routeGeometry) {
                                addRouteToMap(window.mapInstance, window.routeGeometry);
                            }
                        });
                    });
                }
            });
        </script>
    @endif
@endsection
