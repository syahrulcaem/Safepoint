@extends('layouts.app')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0 font-size-18">Manajemen Kasus</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">SafePoint</a></li>
                        <li class="breadcrumb-item active">Kasus</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <!-- Statistics Cards -->
    <div class="row">
        <div class="col-xl-3 col-md-6">
            <div class="card mini-stats-wid">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <p class="text-muted fw-medium">Total Kasus</p>
                            <h4 class="mb-0">{{ $stats['total'] }}</h4>
                        </div>
                        <div class="align-self-center flex-shrink-0">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                <span class="avatar-title">
                                    <i data-feather="layers" class="font-size-16"></i>
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
                            <p class="text-muted fw-medium">Kasus Baru</p>
                            <h4 class="mb-0">{{ $stats['new'] }}</h4>
                        </div>
                        <div class="align-self-center flex-shrink-0">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-danger">
                                <span class="avatar-title">
                                    <i data-feather="plus-circle" class="font-size-16"></i>
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
                            <h4 class="mb-0">{{ $stats['active'] }}</h4>
                        </div>
                        <div class="align-self-center flex-shrink-0">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-warning">
                                <span class="avatar-title">
                                    <i data-feather="activity" class="font-size-16"></i>
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
                            <h4 class="mb-0">{{ $stats['closed'] }}</h4>
                        </div>
                        <div class="align-self-center flex-shrink-0">
                            <div class="mini-stat-icon avatar-sm rounded-circle bg-success">
                                <span class="avatar-title">
                                    <i data-feather="check-circle" class="font-size-16"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    <!-- Filters -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">Filter & Pencarian</h4>
                        <button type="button" class="btn btn-link text-primary" id="toggleFilter">
                            <i data-feather="filter" class="font-size-14 me-1"></i>Toggle Filter
                        </button>
                    </div>
                </div>
                <div class="card-body" id="filterPanel">
                    <form method="GET" action="{{ route('cases.index') }}" id="filterForm">
                        <div class="row g-3">
                            <!-- Status Filter -->
                            <div class="col-md-2">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="">Semua Status</option>
                                    @foreach ($statuses as $status)
                                        <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                            @switch($status)
                                                @case('NEW') Baru @break
                                                @case('VERIFIED') Terverifikasi @break
                                                @case('DISPATCHED') Dikirim @break
                                                @case('ON_THE_WAY') Dalam Perjalanan @break
                                                @case('ON_SCENE') Di Lokasi @break
                                                @case('CLOSED') Selesai @break
                                                @case('CANCELLED') Dibatalkan @break
                                                @default {{ $status }}
                                            @endswitch
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Category Filter -->
                            <div class="col-md-2">
                                <label for="category" class="form-label">Kategori</label>
                                <select name="category" id="category" class="form-select">
                                    <option value="">Semua Kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                            @switch($category)
                                                @case('MEDIS') Medis @break
                                                @case('KEBAKARAN') Kebakaran @break
                                                @case('KRIMINAL') Kriminal @break
                                                @case('UMUM') Umum @break
                                                @case('BENCANA_ALAM') Bencana Alam @break
                                                @case('KECELAKAAN') Kecelakaan @break
                                                @case('KEBOCORAN_GAS') Kebocoran Gas @break
                                                @case('POHON_TUMBANG') Pohon Tumbang @break
                                                @case('BANJIR') Banjir @break
                                                @default {{ $category }}
                                            @endswitch
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Priority Filter -->
                            <div class="col-md-2">
                                <label for="priority" class="form-label">Prioritas</label>
                                <select name="priority" id="priority" class="form-select">
                                    <option value="">Semua Prioritas</option>
                                    @foreach ($priorities as $priority)
                                        <option value="{{ $priority }}" {{ request('priority') == $priority ? 'selected' : '' }}>
                                            @switch($priority)
                                                @case('HIGH') Tinggi @break
                                                @case('MEDIUM') Sedang @break
                                                @case('LOW') Rendah @break
                                                @default {{ $priority }}
                                            @endswitch
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Unit Filter -->
                            <div class="col-md-2">
                                <label for="unit_id" class="form-label">Unit</label>
                                <select name="unit_id" id="unit_id" class="form-select">
                                    <option value="">Semua Unit</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>
                                            {{ $unit->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Date Filter -->
                            <div class="col-md-2">
                                <label for="date_from" class="form-label">Tanggal Dari</label>
                                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="form-control">
                            </div>

                            <div class="col-md-2">
                                <label for="date_to" class="form-label">Tanggal Sampai</label>
                                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="form-control">
                            </div>

                            <!-- Search -->
                            <div class="col-md-8">
                                <label for="search" class="form-label">Pencarian</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                                       placeholder="Cari berdasarkan ID, lokasi, deskripsi, atau nama pelapor..." class="form-control">
                            </div>

                            <!-- Filter Actions -->
                            <div class="col-md-4 d-flex align-items-end">
                                <div class="btn-group w-100" role="group">
                                    <button type="submit" class="btn btn-primary">
                                        <i data-feather="search" class="font-size-12 me-1"></i>Filter
                                    </button>
                                    <a href="{{ route('cases.index') }}" class="btn btn-outline-secondary">
                                        <i data-feather="x" class="font-size-12 me-1"></i>Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
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
    <!-- Cases Table -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">
                            Data Kasus 
                            @if(request()->hasAny(['status', 'category', 'priority', 'unit_id', 'date_from', 'date_to', 'search']))
                                <span class="badge bg-primary ms-2">Filtered</span>
                            @endif
                        </h4>
                        <div class="d-flex gap-2">
                            <span class="text-muted font-size-12">
                                Menampilkan {{ $cases->firstItem() ?? 0 }} - {{ $cases->lastItem() ?? 0 }} dari {{ $cases->total() }} data
                            </span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if ($cases->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-nowrap table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th scope="col">ID Kasus</th>
                                        <th scope="col">Status</th>
                                        <th scope="col">Kategori</th>
                                        <th scope="col">Prioritas</th>
                                        <th scope="col">Lokasi</th>
                                        <th scope="col">Unit</th>
                                        <th scope="col">Waktu</th>
                                        <th scope="col">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cases as $case)
                                        <tr>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <a href="{{ route('cases.show', $case) }}" class="text-primary fw-medium">
                                                        {{ $case->short_id }}
                                                    </a>
                                                    <small class="text-muted">{{ $case->created_at->format('d/m H:i') }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <x-status-badge :status="$case->status" />
                                            </td>
                                            <td>
                                                <span class="badge 
                                                    @switch($case->category)
                                                        @case('MEDIS') bg-info @break
                                                        @case('KEBAKARAN') bg-danger @break
                                                        @case('KRIMINAL') bg-warning @break
                                                        @case('UMUM') bg-secondary @break
                                                        @case('BENCANA_ALAM') bg-warning @break
                                                        @case('KECELAKAAN') bg-warning @break
                                                        @case('KEBOCORAN_GAS') bg-danger @break
                                                        @case('POHON_TUMBANG') bg-warning @break
                                                        @case('BANJIR') bg-primary @break
                                                        @default bg-secondary
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
                                                <span class="badge 
                                                    @switch($case->priority)
                                                        @case('HIGH') bg-danger @break
                                                        @case('MEDIUM') bg-warning @break
                                                        @case('LOW') bg-success @break
                                                        @default bg-secondary
                                                    @endswitch
                                                ">
                                                    @switch($case->priority)
                                                        @case('HIGH') Tinggi @break
                                                        @case('MEDIUM') Sedang @break
                                                        @case('LOW') Rendah @break
                                                        @default {{ $case->priority }}
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
                                                        <i data-feather="truck" class="font-size-12 me-1"></i>
                                                        <span class="text-truncate" style="max-width: 120px;" title="{{ $case->assignedUnit->name }}">
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
                                                <div class="dropdown">
                                                    <a class="text-muted dropdown-toggle font-size-16" role="button" data-bs-toggle="dropdown" aria-haspopup="true">
                                                        <i class="mdi mdi-dots-horizontal"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <a class="dropdown-item" href="{{ route('cases.show', $case) }}">
                                                            <i data-feather="eye" class="font-size-12 me-2"></i>Detail
                                                        </a>
                                                        @if ($case->lat && $case->lon)
                                                            <a class="dropdown-item" href="https://www.google.com/maps?q={{ $case->lat }},{{ $case->lon }}" target="_blank">
                                                                <i data-feather="map-pin" class="font-size-12 me-2"></i>Buka Maps
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="text-muted font-size-13">
                                Menampilkan {{ $cases->firstItem() }} - {{ $cases->lastItem() }} dari {{ $cases->total() }} data
                            </div>
                            <div>
                                {{ $cases->links() }}
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i data-feather="file-text" class="font-size-24 text-muted mb-2"></i>
                            <h6 class="text-muted mb-0">Tidak ada data kasus</h6>
                            <p class="text-muted font-size-13">
                                @if(request()->hasAny(['status', 'category', 'priority', 'unit_id', 'date_from', 'date_to', 'search']))
                                    Tidak ada kasus yang sesuai dengan filter yang dipilih.
                                @else
                                    Belum ada laporan kasus darurat.
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleFilter = document.getElementById('toggleFilter');
            const filterPanel = document.getElementById('filterPanel');
            
            // Check if filter is active
            const hasFilters = {{ request()->hasAny(['status', 'category', 'priority', 'unit_id', 'date_from', 'date_to', 'search']) ? 'true' : 'false' }};
            
            if (!hasFilters) {
                filterPanel.style.display = 'none';
            }

            toggleFilter.addEventListener('click', function() {
                if (filterPanel.style.display === 'none') {
                    filterPanel.style.display = 'block';
                } else {
                    filterPanel.style.display = 'none';
                }
            });

            // Auto submit form on filter change
            const filterInputs = document.querySelectorAll('#filterForm select, #filterForm input[type="date"]');
            filterInputs.forEach(input => {
                input.addEventListener('change', function() {
                    document.getElementById('filterForm').submit();
                });
            });

                        });
                    });
                });
            });
        });
    </script>
@endpush
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
                                                @if ($case->assignedPetugas)
                                                    <div class="text-xs text-blue-600 font-medium mt-1"
                                                        title="{{ $case->assignedPetugas->name }}">
                                                        ↳ {{ Str::limit($case->assignedPetugas->name, 15) }}
                                                    </div>
                                                @endif
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
