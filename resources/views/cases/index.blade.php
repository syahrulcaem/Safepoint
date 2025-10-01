@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Manajemen Kasus</h1>
                <p class="text-gray-600">Kelola semua kasus darurat dengan filter lengkap</p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-gray-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Kasus</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $stats['total'] }}</p>
                    </div>
                    <div class="p-3 bg-gray-100 rounded-full">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-red-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Kasus Baru</p>
                        <p class="text-2xl font-bold text-red-600">{{ $stats['new'] }}</p>
                    </div>
                    <div class="p-3 bg-red-100 rounded-full">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-orange-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Kasus Aktif</p>
                        <p class="text-2xl font-bold text-orange-600">{{ $stats['active'] }}</p>
                    </div>
                    <div class="p-3 bg-orange-100 rounded-full">
                        <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-lg shadow border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Kasus Selesai</p>
                        <p class="text-2xl font-bold text-green-600">{{ $stats['closed'] }}</p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Filter Panel -->
        <div class="bg-white p-6 rounded-lg shadow">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Filter & Pencarian</h3>
                <button type="button" id="toggleFilter" class="text-red-600 hover:text-red-700 text-sm font-medium">
                    Toggle Filter
                </button>
            </div>

            <form method="GET" action="{{ route('cases.index') }}" id="filterForm">
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select name="status" id="status"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                            <option value="">Semua Status</option>
                            @foreach ($statuses as $status)
                                <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                    @switch($status)
                                        @case('NEW')
                                            Baru
                                        @break

                                        @case('VERIFIED')
                                            Terverifikasi
                                        @break

                                        @case('DISPATCHED')
                                            Dikirim
                                        @break

                                        @case('ON_THE_WAY')
                                            Dalam Perjalanan
                                        @break

                                        @case('ON_SCENE')
                                            Di Lokasi
                                        @break

                                        @case('CLOSED')
                                            Selesai
                                        @break

                                        @case('CANCELLED')
                                            Dibatalkan
                                        @break

                                        @default
                                            {{ $status }}
                                    @endswitch
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">Kategori</label>
                        <select name="category" id="category"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                            <option value="">Semua Kategori</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category }}"
                                    {{ request('category') == $category ? 'selected' : '' }}>
                                    @switch($category)
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
                                            {{ $category }}
                                    @endswitch
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Unit Filter -->
                    <div>
                        <label for="unit" class="block text-sm font-medium text-gray-700">Unit</label>
                        <select name="unit" id="unit"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                            <option value="">Semua Unit</option>
                            <option value="unassigned" {{ request('unit') == 'unassigned' ? 'selected' : '' }}>Belum
                                Ditugaskan</option>
                            <option value="assigned" {{ request('unit') == 'assigned' ? 'selected' : '' }}>Sudah Ditugaskan
                            </option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}" {{ request('unit') == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }} ({{ $unit->type }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Date From -->
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700">Dari Tanggal</label>
                        <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                    </div>

                    <!-- Date To -->
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                        <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                    </div>

                    <!-- Search -->
                    <div>
                        <label for="q" class="block text-sm font-medium text-gray-700">Pencarian</label>
                        <input type="text" name="q" id="q" value="{{ request('q') }}"
                            placeholder="ID, lokasi, atau telepon..."
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 sm:text-sm">
                    </div>
                </div>

                <!-- Quick Date Filters -->
                <div class="mt-4 mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter Cepat:</label>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" onclick="setDateFilter(1)"
                            class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Hari Ini
                        </button>
                        <button type="button" onclick="setDateFilter(7)"
                            class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            7 Hari
                        </button>
                        <button type="button" onclick="setDateFilter(30)"
                            class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            30 Hari
                        </button>
                        <button type="button" onclick="setDateFilter(90)"
                            class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            90 Hari
                        </button>
                    </div>
                </div>

                <div class="mt-4 flex flex-wrap gap-3">
                    <button type="submit"
                        class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Filter
                    </button>
                    <a href="{{ route('cases.index') }}"
                        class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                            </path>
                        </svg>
                        Reset
                    </a>
                    <button type="button" onclick="exportData()"
                        class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2z">
                            </path>
                        </svg>
                        Export
                    </button>
                </div>
            </form>
        </div>

        <!-- Cases Table -->
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:p-6">
                <div class="mb-4 flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Daftar Kasus ({{ $cases->total() }} total)
                    </h3>
                    <div class="flex items-center space-x-4">
                        <!-- Items per page selector -->
                        <div class="flex items-center space-x-2">
                            <label for="per_page" class="text-sm text-gray-700">Items per halaman:</label>
                            <select name="per_page" id="per_page" onchange="changePerPage(this.value)"
                                class="rounded-md border-gray-300 shadow-sm focus:border-red-500 focus:ring-red-500 text-sm">
                                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                                <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>15</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </div>

                        <div class="text-sm text-gray-500">
                            Menampilkan {{ $cases->firstItem() ?? 0 }} - {{ $cases->lastItem() ?? 0 }} dari
                            {{ $cases->total() }} kasus
                        </div>
                    </div>
                </div>

                @if ($cases->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                        ID Kasus
                                    </th>
                                    <th
                                        class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-20">
                                        Waktu
                                    </th>
                                    <th
                                        class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                                        Kategori
                                    </th>
                                    <th
                                        class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                                        Status
                                    </th>
                                    <th
                                        class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                        Unit
                                    </th>
                                    <th
                                        class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Lokasi
                                    </th>
                                    <th
                                        class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">
                                        Pelapor
                                    </th>
                                    <th
                                        class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-32">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($cases as $case)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-3 py-3 whitespace-nowrap">
                                            <div class="text-sm font-medium text-red-600">
                                                <a href="{{ route('cases.show', $case) }}" class="hover:underline">
                                                    {{ $case->short_id }}
                                                </a>
                                            </div>
                                            <div class="text-xs text-gray-500 truncate max-w-[120px] font-mono"
                                                title="{{ $case->locator_text }}">
                                                {{ $case->locator_text }}
                                            </div>
                                        </td>
                                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900">
                                            <div class="text-xs">{{ $case->created_at->format('d/m/Y') }}</div>
                                            <div class="text-xs text-gray-500">{{ $case->created_at->format('H:i') }}
                                            </div>
                                        </td>
                                        <td class="px-3 py-3 whitespace-nowrap">
                                            <span
                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
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
                                                        Bencana
                                                    @break

                                                    @case('KECELAKAAN')
                                                        Kecelakaan
                                                    @break

                                                    @case('KEBOCORAN_GAS')
                                                        Gas
                                                    @break

                                                    @case('POHON_TUMBANG')
                                                        Pohon
                                                    @break

                                                    @case('BANJIR')
                                                        Banjir
                                                    @break

                                                    @default
                                                        {{ $case->category }}
                                                @endswitch
                                            </span>
                                        </td>
                                        <td class="px-3 py-3 whitespace-nowrap">
                                            <x-status-badge :status="$case->status" />
                                        </td>
                                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900">
                                            @if ($case->assignedUnit)
                                                <div class="text-xs font-medium truncate max-w-[120px]"
                                                    title="{{ $case->assignedUnit->name }}">
                                                    {{ $case->assignedUnit->name }}
                                                </div>
                                                <div class="text-xs text-gray-500">{{ $case->assignedUnit->type }}</div>
                                            @else
                                                <span class="text-gray-400 italic text-xs">Belum ditugaskan</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-3 text-sm text-gray-900">
                                            <div class="truncate max-w-[200px] font-mono text-blue-600"
                                                title="{{ $case->locator_text }}">
                                                {{ Str::limit($case->locator_text, 40) }}
                                            </div>
                                        </td>
                                        <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-900">
                                            @if ($case->reporterUser)
                                                <div class="text-xs font-medium truncate max-w-[80px]"
                                                    title="{{ $case->reporterUser->name }}">
                                                    {{ Str::limit($case->reporterUser->name, 12) }}
                                                </div>
                                                <div class="text-xs text-gray-500">Warga</div>
                                            @else
                                                <span class="text-gray-400 italic text-xs">Guest</span>
                                            @endif
                                        </td>
                                        <td class="px-3 py-3 whitespace-nowrap text-sm">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('cases.show', $case) }}"
                                                    class="inline-flex items-center px-2 py-1 text-xs font-medium text-red-600 hover:text-red-700 hover:bg-red-50 rounded border border-red-200 hover:border-red-300 transition-colors">
                                                    Detail
                                                </a>
                                                @if ($case->lat && $case->lon)
                                                    <a href="https://www.google.com/maps?q={{ $case->lat }},{{ $case->lon }}"
                                                        target="_blank"
                                                        class="inline-flex items-center px-2 py-1 text-xs font-medium text-blue-600 hover:text-blue-700 hover:bg-blue-50 rounded border border-blue-200 hover:border-blue-300 transition-colors">
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

                    <!-- Simple & Working Pagination -->
                    <div class="mt-6 flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3">
                        <div class="flex flex-1 justify-between sm:hidden">
                            <!-- Mobile pagination -->
                            @if ($cases->previousPageUrl())
                                <a href="{{ $cases->previousPageUrl() }}"
                                    class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                    Previous
                                </a>
                            @endif

                            @if ($cases->nextPageUrl())
                                <a href="{{ $cases->nextPageUrl() }}"
                                    class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                                    Next
                                </a>
                            @endif
                        </div>

                        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing <span class="font-medium">{{ $cases->firstItem() ?? 0 }}</span> to
                                    <span class="font-medium">{{ $cases->lastItem() ?? 0 }}</span> of
                                    <span class="font-medium">{{ $cases->total() }}</span> results
                                </p>
                            </div>
                            <div>
                                {{ $cases->links() }}
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada kasus</h3>
                        <p class="mt-1 text-sm text-gray-500">Tidak ada kasus yang sesuai dengan filter yang dipilih.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Toggle filter visibility
        document.getElementById('toggleFilter').addEventListener('click', function() {
            const filterForm = document.getElementById('filterForm');
            filterForm.style.display = filterForm.style.display === 'none' ? 'block' : 'none';
        });

        // Auto-submit form on filter change
        const filterInputs = document.querySelectorAll('#filterForm select');
        filterInputs.forEach(input => {
            input.addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        });

        // Export function
        function exportData() {
            const currentUrl = new URL(window.location);
            currentUrl.searchParams.set('export', 'csv');
            window.open(currentUrl.toString(), '_blank');
        }

        // Quick date filters
        function setDateFilter(days) {
            const today = new Date();
            const fromDate = new Date(today.getTime() - (days * 24 * 60 * 60 * 1000));

            document.getElementById('date_from').value = fromDate.toISOString().split('T')[0];
            document.getElementById('date_to').value = today.toISOString().split('T')[0];

            document.getElementById('filterForm').submit();
        }

        // Change items per page
        function changePerPage(perPage) {
            const currentUrl = new URL(window.location);
            currentUrl.searchParams.set('per_page', perPage);
            currentUrl.searchParams.delete('page'); // Reset to first page
            window.location.href = currentUrl.toString();
        }

        // Auto-submit form on filter change
        const filterInputs = document.querySelectorAll('#filterForm select');
        filterInputs.forEach(input => {
            input.addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
        });

        // Export function
        function exportData() {
            const currentUrl = new URL(window.location);
            currentUrl.searchParams.set('export', 'csv');
            window.open(currentUrl.toString(), '_blank');
        }

        // Quick date filters
        function setDateFilter(days) {
            const today = new Date();
            const fromDate = new Date(today.getTime() - (days * 24 * 60 * 60 * 1000));

            document.getElementById('date_from').value = fromDate.toISOString().split('T')[0];
            document.getElementById('date_to').value = today.toISOString().split('T')[0];

            document.getElementById('filterForm').submit();
        }
    </script>
@endsection
