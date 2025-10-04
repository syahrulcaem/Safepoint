@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-sm-0 font-size-18">Manajemen Kasus</h4>
                        <p class="text-muted mb-0">Kelola semua kasus darurat dengan filter lengkap</p>
                    </div>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li class="breadcrumb-item active">Kasus</li>
                        </ol>
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
                                <h4 class="mb-0">{{ $stats['total'] }}</h4>
                            </div>
                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle" style="background: linear-gradient(45deg, #6c757d 0%, #495057 100%) !important;">
                                    <span class="avatar-title" style="background: linear-gradient(45deg, #6c757d 0%, #495057 100%) !important;">
                                        <i class="bx bx-bar-chart-alt-2 font-size-20 text-white"></i>
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
                                <h4 class="mb-0 text-danger">{{ $stats['new'] }}</h4>
                            </div>
                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle" style="background: linear-gradient(45deg, #dc3545 0%, #c82333 100%) !important;">
                                    <span class="avatar-title" style="background: linear-gradient(45deg, #dc3545 0%, #c82333 100%) !important;">
                                        <i class="bx bx-plus font-size-20 text-white"></i>
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
                                <h4 class="mb-0 text-warning">{{ $stats['active'] }}</h4>
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
                                <p class="text-muted fw-medium">Kasus Selesai</p>
                                <h4 class="mb-0 text-success">{{ $stats['closed'] }}</h4>
                            </div>
                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle" style="background: linear-gradient(45deg, #198754 0%, #146c43 100%) !important;">
                                    <span class="avatar-title" style="background: linear-gradient(45deg, #198754 0%, #146c43 100%) !important;">
                                        <i class="bx bx-check font-size-20 text-white"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Advanced Filter Panel -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="card-title mb-0">Filter & Pencarian</h4>
                            <button type="button" id="toggleFilter" class="btn btn-sm btn-outline-primary">
                                <i class="bx bx-filter me-1"></i> Toggle Filter
                            </button>
                        </div>
                    </div>

                    <div class="card-body" id="filterForm">
                        <form method="GET" action="{{ route('cases.index') }}">
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

                                <!-- Unit Filter -->
                                <div class="col-md-2">
                                    <label for="unit" class="form-label">Unit</label>
                                    <select name="unit" id="unit" class="form-select">
                                        <option value="">Semua Unit</option>
                                        <option value="unassigned" {{ request('unit') == 'unassigned' ? 'selected' : '' }}>Belum Ditugaskan</option>
                                        <option value="assigned" {{ request('unit') == 'assigned' ? 'selected' : '' }}>Sudah Ditugaskan</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}" {{ request('unit') == $unit->id ? 'selected' : '' }}>
                                                {{ $unit->name }} ({{ $unit->type }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Date From -->
                                <div class="col-md-2">
                                    <label for="date_from" class="form-label">Dari Tanggal</label>
                                    <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}" class="form-control">
                                </div>

                                <!-- Date To -->
                                <div class="col-md-2">
                                    <label for="date_to" class="form-label">Sampai Tanggal</label>
                                    <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}" class="form-control">
                                </div>

                                <!-- Search -->
                                <div class="col-md-2">
                                    <label for="q" class="form-label">Pencarian</label>
                                    <input type="text" name="q" id="q" value="{{ request('q') }}" placeholder="ID, lokasi, atau telepon..." class="form-control">
                                </div>
                            </div>

                            <!-- Quick Date Filters -->
                            <div class="mt-3">
                                <label class="form-label">Filter Cepat:</label>
                                <div class="d-flex flex-wrap gap-2">
                                    <button type="button" onclick="setDateFilter(1)" class="btn btn-sm btn-outline-info">Hari Ini</button>
                                    <button type="button" onclick="setDateFilter(7)" class="btn btn-sm btn-outline-info">7 Hari</button>
                                    <button type="button" onclick="setDateFilter(30)" class="btn btn-sm btn-outline-info">30 Hari</button>
                                    <button type="button" onclick="setDateFilter(90)" class="btn btn-sm btn-outline-info">90 Hari</button>
                                </div>
                            </div>

                            <div class="mt-3 d-flex flex-wrap gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bx bx-search me-1"></i> Filter
                                </button>
                                <a href="{{ route('cases.index') }}" class="btn btn-secondary">
                                    <i class="bx bx-refresh me-1"></i> Reset
                                </a>
                                <button type="button" onclick="exportData()" class="btn btn-success">
                                    <i class="bx bx-download me-1"></i> Export
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cases Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="card-title mb-0">Daftar Kasus ({{ $cases->total() }} total)</h4>
                            <div class="d-flex align-items-center gap-3">
                                <!-- Items per page selector -->
                                <div class="d-flex align-items-center gap-2">
                                    <label for="per_page" class="form-label mb-0 text-muted">Items per halaman:</label>
                                    <select name="per_page" id="per_page" onchange="changePerPage(this.value)" class="form-select form-select-sm">
                                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                                        <option value="15" {{ request('per_page') == 15 ? 'selected' : '' }}>15</option>
                                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                    </select>
                                </div>

                                <div class="text-muted">
                                    Menampilkan {{ $cases->firstItem() ?? 0 }} - {{ $cases->lastItem() ?? 0 }} dari {{ $cases->total() }} kasus
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        @if ($cases->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-nowrap mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>ID Kasus</th>
                                            <th>Waktu</th>
                                            <th>Kategori</th>
                                            <th>Status</th>
                                            <th>Unit</th>
                                            <th>Lokasi</th>
                                            <th>Pelapor</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($cases as $case)
                                            <tr>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <a href="javascript:void(0);" onclick="showCaseDetail('{{ $case->id }}')" class="text-primary fw-medium">
                                                            {{ $case->short_id }}
                                                        </a>
                                                        <small class="text-muted font-monospace">{{ Str::limit($case->locator_text, 15) }}</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <small>{{ $case->created_at->format('d/m/Y') }}</small>
                                                        <small class="text-muted">{{ $case->created_at->format('H:i') }}</small>
                                                    </div>
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
                                                            @case('BENCANA_ALAM') Bencana @break
                                                            @case('KECELAKAAN') Kecelakaan @break
                                                            @case('KEBOCORAN_GAS') Gas @break
                                                            @case('POHON_TUMBANG') Pohon @break
                                                            @case('BANJIR') Banjir @break
                                                            @default {{ $case->category }}
                                                        @endswitch
                                                    </span>
                                                </td>
                                                <td>
                                                    <x-status-badge :status="$case->status" />
                                                </td>
                                                <td>
                                                    @if ($case->assignedUnit)
                                                        <div class="d-flex flex-column">
                                                            <small class="fw-medium">{{ Str::limit($case->assignedUnit->name, 15) }}</small>
                                                            <small class="text-muted">{{ $case->assignedUnit->type }}</small>
                                                            @if ($case->assignedPetugas)
                                                                <small class="text-info">{{ Str::limit($case->assignedPetugas->name, 15) }}</small>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <span class="text-muted">Belum ditugaskan</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="text-truncate" style="max-width: 200px;" title="{{ $case->locator_text }}">
                                                        {{ Str::limit($case->locator_text, 40) }}
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($case->reporterUser)
                                                        <div class="d-flex flex-column">
                                                            <small class="fw-medium">{{ Str::limit($case->reporterUser->name, 12) }}</small>
                                                            <small class="text-muted">Warga</small>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">Guest</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <button onclick="showCaseDetail('{{ $case->id }}')" class="btn btn-sm btn-outline-primary">
                                                            Detail
                                                        </button>
                                                        @if ($case->lat && $case->lon)
                                                            <a href="https://www.google.com/maps?q={{ $case->lat }},{{ $case->lon }}" target="_blank" class="btn btn-sm btn-outline-info">
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

                            <!-- Pagination -->
                            <div class="mt-3 d-flex align-items-center justify-content-between">
                                <div class="d-flex d-sm-none">
                                    <!-- Mobile pagination -->
                                    @if ($cases->previousPageUrl())
                                        <a href="{{ $cases->previousPageUrl() }}" class="btn btn-outline-secondary btn-sm me-2">Previous</a>
                                    @endif
                                    @if ($cases->nextPageUrl())
                                        <a href="{{ $cases->nextPageUrl() }}" class="btn btn-outline-secondary btn-sm">Next</a>
                                    @endif
                                </div>

                                <div class="d-none d-sm-flex align-items-center justify-content-between w-100">
                                    <div>
                                        <p class="text-muted mb-0">
                                            Showing <span class="fw-medium">{{ $cases->firstItem() ?? 0 }}</span> to
                                            <span class="fw-medium">{{ $cases->lastItem() ?? 0 }}</span> of
                                            <span class="fw-medium">{{ $cases->total() }}</span> results
                                        </p>
                                    </div>
                                    <div>
                                        {{ $cases->links() }}
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <div class="avatar-md mx-auto mb-4">
                                    <div class="avatar-title bg-light rounded-circle">
                                        <i class="bx bx-file font-size-24 text-primary"></i>
                                    </div>
                                </div>
                                <h5 class="font-size-15">Tidak ada kasus</h5>
                                <p class="text-muted">Tidak ada kasus yang sesuai dengan filter yang dipilih.</p>
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle filter visibility
            const toggleFilterBtn = document.getElementById('toggleFilter');
            const filterForm = document.getElementById('filterForm');
            
            if (toggleFilterBtn && filterForm) {
                toggleFilterBtn.addEventListener('click', function() {
                    if (filterForm.style.display === 'none') {
                        filterForm.style.display = 'block';
                        this.innerHTML = '<i class="bx bx-filter me-1"></i> Hide Filter';
                    } else {
                        filterForm.style.display = 'none';
                        this.innerHTML = '<i class="bx bx-filter me-1"></i> Show Filter';
                    }
                });
            }

            // Auto-submit form on filter change
            const filterSelects = document.querySelectorAll('#filterForm select');
            filterSelects.forEach(select => {
                select.addEventListener('change', function() {
                    this.closest('form').submit();
                });
            });
        });

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

            document.querySelector('#filterForm form').submit();
        }

        // Change items per page
        function changePerPage(perPage) {
            const currentUrl = new URL(window.location);
            currentUrl.searchParams.set('per_page', perPage);
            currentUrl.searchParams.delete('page'); // Reset to first page
            window.location.href = currentUrl.toString();
        }
    </script>
@endsection
