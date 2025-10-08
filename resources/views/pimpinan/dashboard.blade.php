@extends('layouts.app')

@section('title', 'Dashboard Pimpinan')

@section('content')
    <div class="container-fluid">
        <!-- Header -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <div>
                        <h4 class="mb-sm-0 font-size-18">Dashboard Pimpinan</h4>
                        <p class="text-muted mb-0">Unit: <span class="fw-semibold">{{ $unit->name }}</span> ({{ $unit->type }})</p>
                    </div>
                    <div class="page-title-right">
                        <a href="{{ route('pimpinan.petugas') }}" class="btn btn-primary">
                            <i class="bx bx-user-plus me-1"></i> Kelola Petugas
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row">
            <div class="col-xl-4 col-md-6">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Dispatch Pending</p>
                                <h4 class="mb-0">{{ $pendingDispatches->count() }}</h4>
                            </div>
                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle" style="background: linear-gradient(45deg, #dc3545 0%, #c82333 100%) !important;">
                                    <span class="avatar-title" style="background: linear-gradient(45deg, #dc3545 0%, #c82333 100%) !important;">
                                        <i class="bx bx-time-five font-size-20 text-white"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Sudah Ditugaskan</p>
                                <h4 class="mb-0 text-success">{{ $assignedDispatches->count() }}</h4>
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

            <div class="col-xl-4 col-md-6">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium">Total Petugas</p>
                                <h4 class="mb-0">{{ $availablePetugas->count() }}</h4>
                            </div>
                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle" style="background: linear-gradient(45deg, #6c757d 0%, #495057 100%) !important;">
                                    <span class="avatar-title" style="background: linear-gradient(45deg, #6c757d 0%, #495057 100%) !important;">
                                        <i class="bx bx-user font-size-20 text-white"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Dispatches -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header" style="background: linear-gradient(45deg, #dc3545 0%, #c82333 100%) !important;">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="card-title mb-0 text-white">
                                <i class="bx bx-error-circle me-2"></i>Dispatch Menunggu Penugasan
                            </h4>
                            <span class="badge bg-white text-danger">{{ $pendingDispatches->count() }} Pending</span>
                        </div>
                    </div>
                    <div class="card-body">
                        @forelse($pendingDispatches as $dispatch)
                            <div class="border rounded p-3 mb-3 hover-shadow" style="transition: all 0.3s;">
                                <div class="row align-items-center">
                                    <div class="col-lg-8">
                                        <div class="d-flex align-items-start gap-3">
                                            <div class="avatar-sm">
                                                <div class="avatar-title rounded" style="background: linear-gradient(45deg, #dc3545 0%, #c82333 100%) !important; color: white;">
                                                    <i class="bx bx-alarm font-size-20"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center gap-2 mb-2">
                                                    <h5 class="mb-0 font-monospace">{{ $dispatch->case->short_id }}</h5>
                                                    <x-status-badge :status="$dispatch->case->status" />
                                                    <span class="badge badge-soft-{{ $dispatch->case->category === 'MEDIS' ? 'primary' : ($dispatch->case->category === 'KEBAKARAN' ? 'danger' : 'secondary') }}">
                                                        {{ $dispatch->case->category }}
                                                    </span>
                                                </div>
                                                
                                                <p class="text-muted mb-1">
                                                    <i class="bx bx-map me-1"></i>
                                                    <strong>Lokasi:</strong> {{ Str::limit($dispatch->case->locator_text ?? 'N/A', 60) }}
                                                </p>

                                                @if ($dispatch->notes)
                                                    <p class="text-muted mb-1">
                                                        <i class="bx bx-note me-1"></i>
                                                        <strong>Catatan Operator:</strong> {{ Str::limit($dispatch->notes, 60) }}
                                                    </p>
                                                @endif

                                                <p class="text-muted mb-0" style="font-size: 0.85rem;">
                                                    <i class="bx bx-user me-1"></i>
                                                    Dispatch oleh: {{ $dispatch->dispatcher->name }} • 
                                                    <i class="bx bx-time me-1"></i>
                                                    {{ $dispatch->dispatched_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                                        <div class="d-flex flex-column flex-lg-row gap-2 justify-content-lg-end">
                                            <button onclick="showCaseDetail('{{ $dispatch->case->id }}')" 
                                                class="btn btn-outline-primary btn-sm">
                                                <i class="bx bx-show me-1"></i> Lihat Detail
                                            </button>
                                            <button onclick="showAssignModal({{ $dispatch->id }}, '{{ $dispatch->case->short_id }}')"
                                                class="btn btn-sm" style="background: linear-gradient(45deg, #dc3545 0%, #c82333 100%) !important; color: white; border: none;">
                                                <i class="bx bx-user-plus me-1"></i> Tugaskan Petugas
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <div class="avatar-md mx-auto mb-4">
                                    <div class="avatar-title rounded-circle" style="background: linear-gradient(45deg, #198754 0%, #146c43 100%) !important; color: white;">
                                        <i class="bx bx-check-circle font-size-24"></i>
                                    </div>
                                </div>
                                <h5 class="font-size-15">Tidak Ada Dispatch Pending</h5>
                                <p class="text-muted">Semua dispatch sudah ditugaskan ke petugas</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Assigned Dispatches -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex align-items-center justify-content-between">
                            <h4 class="card-title mb-0">
                                <i class="bx bx-list-check me-2"></i>Riwayat Penugasan
                            </h4>
                            <span class="badge bg-success">{{ $assignedDispatches->count() }} Ditugaskan</span>
                        </div>
                    </div>
                    <div class="card-body">
                        @forelse($assignedDispatches as $dispatch)
                            <div class="border rounded p-3 mb-3 hover-shadow" style="transition: all 0.3s;">
                                <div class="row align-items-center">
                                    <div class="col-lg-8">
                                        <div class="d-flex align-items-start gap-3">
                                            <div class="avatar-sm">
                                                <div class="avatar-title rounded" style="background: linear-gradient(45deg, #198754 0%, #146c43 100%) !important; color: white;">
                                                    <i class="bx bx-check font-size-20"></i>
                                                </div>
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center gap-2 mb-2">
                                                    <h5 class="mb-0 font-monospace">{{ $dispatch->case->short_id }}</h5>
                                                    <x-status-badge :status="$dispatch->case->status" />
                                                    <span class="badge badge-soft-{{ $dispatch->case->category === 'MEDIS' ? 'primary' : ($dispatch->case->category === 'KEBAKARAN' ? 'danger' : 'secondary') }}">
                                                        {{ $dispatch->case->category }}
                                                    </span>
                                                </div>
                                                
                                                <p class="text-muted mb-1">
                                                    <i class="bx bx-user me-1"></i>
                                                    <strong>Petugas:</strong> {{ $dispatch->assignedPetugas->name }}
                                                </p>

                                                <p class="text-muted mb-1">
                                                    <i class="bx bx-map me-1"></i>
                                                    <strong>Lokasi:</strong> {{ Str::limit($dispatch->case->locator_text ?? 'N/A', 60) }}
                                                </p>

                                                <p class="text-muted mb-0" style="font-size: 0.85rem;">
                                                    <i class="bx bx-time me-1"></i>
                                                    Ditugaskan: {{ $dispatch->assigned_at->diffForHumans() }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 text-lg-end mt-3 mt-lg-0">
                                        <button onclick="showCaseDetail('{{ $dispatch->case->id }}')" 
                                            class="btn btn-outline-primary btn-sm">
                                            <i class="bx bx-show me-1"></i> Lihat Detail
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5">
                                <div class="avatar-md mx-auto mb-4">
                                    <div class="avatar-title bg-light rounded-circle">
                                        <i class="bx bx-file font-size-24 text-muted"></i>
                                    </div>
                                </div>
                                <h5 class="font-size-15">Belum Ada Penugasan</h5>
                                <p class="text-muted">Belum ada petugas yang ditugaskan</p>
                            </div>
                        @endforelse
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

    <!-- Assign Modal -->
    <div class="modal fade" id="assignModal" tabindex="-1" aria-labelledby="assignModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(45deg, #dc3545 0%, #c82333 100%) !important;">
                    <h5 class="modal-title text-white" id="assignModalLabel">
                        <i class="bx bx-user-plus me-2"></i>Tugaskan Petugas
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>Case ID:</strong> <span id="modalCaseId" class="font-monospace"></span>
                    </div>

                    <form id="assignForm" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="assigned_petugas_id" class="form-label">
                                Pilih Petugas <span class="text-danger">*</span>
                            </label>
                            <select name="assigned_petugas_id" id="assigned_petugas_id" required class="form-select">
                                <option value="">-- Pilih Petugas --</option>
                                @foreach ($availablePetugas as $petugas)
                                    <option value="{{ $petugas->id }}">{{ $petugas->name }}</option>
                                @endforeach
                            </select>
                            @if ($availablePetugas->isEmpty())
                                <small class="text-danger">
                                    Tidak ada petugas di unit ini. 
                                    <a href="{{ route('pimpinan.petugas') }}" class="text-decoration-underline">Tambah petugas</a>
                                </small>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">
                                Catatan untuk Petugas (Opsional)
                            </label>
                            <textarea name="notes" id="notes" rows="3" class="form-control"
                                placeholder="Instruksi khusus untuk petugas..."></textarea>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn flex-fill" style="background: linear-gradient(45deg, #dc3545 0%, #c82333 100%) !important; color: white; border: none;">
                                <i class="bx bx-check me-1"></i> Tugaskan
                            </button>
                            <button type="button" class="btn btn-secondary flex-fill" data-bs-dismiss="modal">
                                <i class="bx bx-x me-1"></i> Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .hover-shadow:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }
    </style>

    <script>
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
            fetch(`/pimpinan/cases/${caseId}`, {
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

        // Show assign modal
        function showAssignModal(dispatchId, caseId) {
            const modal = new bootstrap.Modal(document.getElementById('assignModal'));
            const form = document.getElementById('assignForm');
            const modalCaseId = document.getElementById('modalCaseId');

            // Set form action
            form.action = `/pimpinan/dispatches/${dispatchId}/assign`;

            // Set case ID display
            modalCaseId.textContent = caseId;

            // Show modal
            modal.show();
        }
    </script>
@endsection
