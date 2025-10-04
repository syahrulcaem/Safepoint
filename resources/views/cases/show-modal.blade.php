<!-- Case Detail Modal Content -->
<div class="row">
    <div class="col-12">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h4 class="mb-1">Kasus {{ $case->short_id }}</h4>
                <p class="text-muted mb-0">{{ $case->created_at->format('d F Y, H:i') }} WIB</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <x-status-badge :status="$case->status" />
                @if ($case->lat && $case->lon)
                    <a href="https://www.google.com/maps?q={{ $case->lat }},{{ $case->lon }}" target="_blank"
                        class="btn btn-sm btn-outline-primary">
                        <i class="bx bx-map me-1"></i> Maps
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Main Content -->
    <div class="col-lg-8">
        <!-- Case Metadata -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Informasi Kasus</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">ID Kasus</label>
                            <p class="mb-0 fw-medium">{{ $case->short_id }}</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Status</label>
                            <div><x-status-badge :status="$case->status" /></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Kategori</label>
                            <p class="mb-0">
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
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Prioritas</label>
                            <p class="mb-0">
                                @switch($case->priority)
                                    @case('HIGH')
                                        <span class="badge bg-danger">Tinggi</span>
                                        @break
                                    @case('MEDIUM')
                                        <span class="badge bg-warning">Sedang</span>
                                        @break
                                    @case('LOW')
                                        <span class="badge bg-info">Rendah</span>
                                        @break
                                    @default
                                        <span class="badge bg-secondary">{{ $case->priority }}</span>
                                @endswitch
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label text-muted">Nomor Telepon</label>
                            <p class="mb-0">{{ $case->phone ?: '-' }}</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label text-muted">Lokasi</label>
                            <p class="mb-0">{{ $case->location ?: $case->locator_text }}</p>
                        </div>
                    </div>
                    @if ($case->assignedUnit)
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Unit Ditugaskan</label>
                                <p class="mb-0">{{ $case->assignedUnit->name }}</p>
                            </div>
                        </div>
                    @endif
                    @if ($case->assignedPetugas)
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Petugas Ditugaskan</label>
                                <p class="mb-0">{{ $case->assignedPetugas->name }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Description -->
        @if ($case->description)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Deskripsi Kasus</h5>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $case->description }}</p>
                </div>
            </div>
        @endif

        <!-- Timeline Events -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Timeline Event</h5>
            </div>
            <div class="card-body">
                @if ($case->caseEvents && $case->caseEvents->count() > 0)
                    <div class="timeline">
                        @foreach ($case->caseEvents as $event)
                            <div class="timeline-item">
                                <div class="timeline-marker 
                                    @switch($event->action)
                                        @case('CREATED') bg-primary @break
                                        @case('VERIFIED') bg-success @break
                                        @case('DISPATCHED') bg-warning @break
                                        @case('ON_THE_WAY') bg-info @break
                                        @case('ON_SCENE') bg-dark @break
                                        @case('CLOSED') bg-success @break
                                        @case('CANCELLED') bg-danger @break
                                        @default bg-secondary
                                    @endswitch
                                "></div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="timeline-title mb-1">
                                                @switch($event->action)
                                                    @case('CREATED') Kasus Dibuat @break
                                                    @case('VERIFIED') Diverifikasi @break
                                                    @case('DISPATCHED') Dikirim @break
                                                    @case('ON_THE_WAY') Dalam Perjalanan @break
                                                    @case('ON_SCENE') Di Lokasi @break
                                                    @case('CLOSED') Ditutup @break
                                                    @case('CANCELLED') Dibatalkan @break
                                                    @default {{ $event->action }}
                                                @endswitch
                                            </h6>
                                            @if ($event->notes)
                                                <p class="timeline-description mb-2">{{ $event->notes }}</p>
                                            @endif
                                            @if ($event->actor)
                                                <small class="text-muted">oleh {{ $event->actor->name }}</small>
                                            @endif
                                        </div>
                                        <small class="text-muted">{{ $event->created_at->format('d/m H:i') }}</small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bx bx-time-five text-muted" style="font-size: 2rem;"></i>
                        <p class="text-muted mt-2 mb-0">Belum ada timeline event untuk kasus ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar Actions -->
    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">Aksi Cepat</h5>
            </div>
            <div class="card-body">
                @if ($case->status === 'NEW')
                    <!-- Verify Case -->
                    <form method="POST" action="{{ route('cases.verify', $case) }}" class="mb-3">
                        @csrf
                        <button type="submit" class="btn btn-success w-100" 
                                onclick="return confirm('Yakin ingin memverifikasi kasus ini?')">
                            <i class="bx bx-check me-1"></i> Verifikasi Kasus
                        </button>
                    </form>
                @endif

                @if (in_array($case->status, ['NEW', 'VERIFIED']))
                    <!-- Dispatch to Unit -->
                    <div class="mb-3">
                        <form method="POST" action="{{ route('cases.dispatch', $case) }}">
                            @csrf
                            <div class="mb-3">
                                <label for="unit_id" class="form-label">Pilih Unit</label>
                                <select name="unit_id" id="unit_id" class="form-select" required>
                                    <option value="">Pilih Unit</option>
                                    @foreach ($units as $unit)
                                        <option value="{{ $unit->id }}">{{ $unit->name }} ({{ $unit->type }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3" id="petugas-container" style="display: none;">
                                <label for="petugas_id" class="form-label">Pilih Petugas (Opsional)</label>
                                <div id="petugas-loading" style="display: none;">
                                    <div class="text-center py-2">
                                        <div class="spinner-border spinner-border-sm" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                        <span class="ms-2 text-muted">Memuat petugas...</span>
                                    </div>
                                </div>
                                <select name="petugas_id" id="petugas_id" class="form-select" disabled>
                                    <option value="">Pilih Petugas (Opsional)</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="notes" class="form-label">Catatan (Opsional)</label>
                                <textarea name="notes" id="notes" class="form-control" rows="3" 
                                          placeholder="Tambahkan catatan untuk pengiriman..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bx bx-send me-1"></i> Kirim ke Unit
                            </button>
                        </form>
                    </div>
                @endif

                @if (in_array($case->status, ['DISPATCHED', 'ON_THE_WAY', 'ON_SCENE']))
                    <!-- Close Case -->
                    <div class="mb-3">
                        <button type="button" class="btn btn-success w-100" data-bs-toggle="collapse" 
                                data-bs-target="#closeForm" aria-expanded="false">
                            <i class="bx bx-check-circle me-1"></i> Tutup Kasus
                        </button>
                        <div class="collapse mt-3" id="closeForm">
                            <form method="POST" action="{{ route('cases.close', $case) }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="close_notes" class="form-label">Catatan Penutupan</label>
                                    <textarea name="notes" id="close_notes" class="form-control" rows="3" 
                                              placeholder="Jelaskan alasan penutupan kasus..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-success w-100">Tutup Kasus</button>
                            </form>
                        </div>
                    </div>
                @endif

                @if (!in_array($case->status, ['CLOSED', 'CANCELLED']))
                    <!-- Cancel Case -->
                    <div class="mb-3">
                        <button type="button" class="btn btn-danger w-100" data-bs-toggle="collapse" 
                                data-bs-target="#cancelForm" aria-expanded="false">
                            <i class="bx bx-x-circle me-1"></i> Batalkan Kasus
                        </button>
                        <div class="collapse mt-3" id="cancelForm">
                            <form method="POST" action="{{ route('cases.cancel', $case) }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="cancel_notes" class="form-label">Alasan Pembatalan</label>
                                    <textarea name="notes" id="cancel_notes" class="form-control" rows="3" 
                                              placeholder="Jelaskan alasan pembatalan..." required></textarea>
                                </div>
                                <button type="submit" class="btn btn-danger w-100">Batalkan Kasus</button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Quick Info -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Informasi Cepat</h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <span class="text-muted">Durasi:</span>
                    <span class="fw-medium">
                        @if ($case->closed_at)
                            {{ $case->created_at->diffInHours($case->closed_at) }} jam
                        @else
                            {{ $case->created_at->diffForHumans() }}
                        @endif
                    </span>
                </div>

                @if ($case->lat && $case->lon)
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Koordinat:</span>
                        <span class="fw-medium">{{ number_format($case->lat, 6) }}, {{ number_format($case->lon, 6) }}</span>
                    </div>
                @endif

                @if ($case->accuracy)
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Akurasi:</span>
                        <span class="fw-medium">{{ $case->accuracy }}m</span>
                    </div>
                @endif

                <div class="d-flex justify-content-between">
                    <span class="text-muted">Provider:</span>
                    <span class="fw-medium">{{ ucfirst($case->locator_provider) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 10px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 25px;
}

.timeline-marker {
    position: absolute;
    left: -25px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #dee2e6;
}

.timeline-title {
    color: #495057;
    font-size: 14px;
    font-weight: 600;
}

.timeline-description {
    color: #6c757d;
    font-size: 13px;
    line-height: 1.5;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const unitSelect = document.getElementById('unit_id');
    const petugasContainer = document.getElementById('petugas-container');
    const petugasSelect = document.getElementById('petugas_id');
    const petugasLoading = document.getElementById('petugas-loading');

    if (unitSelect) {
        unitSelect.addEventListener('change', function() {
            const unitId = this.value;

            if (unitId) {
                // Show container and loading
                petugasContainer.style.display = 'block';
                petugasLoading.style.display = 'block';
                petugasSelect.innerHTML = '<option value="">Pilih Petugas (Opsional)</option>';
                petugasSelect.disabled = true;

                // Fetch petugas for selected unit
                fetch(`/api/units/${unitId}/petugas`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    petugasLoading.style.display = 'none';
                    
                    if (data.success && data.data) {
                        petugasSelect.innerHTML = '<option value="">Pilih Petugas (Opsional)</option>';
                        data.data.forEach(petugas => {
                            const option = document.createElement('option');
                            option.value = petugas.id;
                            option.textContent = petugas.name;
                            petugasSelect.appendChild(option);
                        });
                        petugasSelect.disabled = false;
                    } else {
                        petugasSelect.innerHTML = '<option value="">Tidak ada petugas tersedia</option>';
                    }
                })
                .catch(error => {
                    petugasLoading.style.display = 'none';
                    petugasSelect.innerHTML = '<option value="">Error memuat petugas</option>';
                    console.error('Error:', error);
                });
            } else {
                // Hide petugas container if no unit selected
                petugasContainer.style.display = 'none';
                petugasSelect.innerHTML = '<option value="">Pilih Petugas (Opsional)</option>';
            }
        });
    }
});
</script>